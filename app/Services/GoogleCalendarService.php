<?php

namespace App\Services;

use App\Models\Event;
use App\Models\User;
use App\Models\EventCalendarSync;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class GoogleCalendarService
{
    /**
     * Retry a function with exponential backoff
     */
    private function retryWithBackoff(callable $function, int $maxRetries = 3, string $operation = 'operation', array $context = [])
    {
        $lastException = null;

        for ($attempt = 1; $attempt <= $maxRetries; $attempt++) {
            try {
                return $function();
            } catch (\Exception $e) {
                $lastException = $e;

                // Don't retry on authentication errors or client errors
                if ($e instanceof \Google\Service\Exception) {
                    $code = $e->getCode();
                    if ($code >= 400 && $code < 500) {
                        Log::warning("{$operation} failed with client error, not retrying", array_merge($context, [
                            'attempt' => $attempt,
                            'error_code' => $code,
                            'error' => $e->getMessage()
                        ]));
                        throw $e;
                    }
                }

                if ($attempt < $maxRetries) {
                    $delay = min(pow(2, $attempt - 1) * 1000, 10000); // Exponential backoff, max 10 seconds
                    Log::info("{$operation} failed, retrying in {$delay}ms", array_merge($context, [
                        'attempt' => $attempt,
                        'max_retries' => $maxRetries,
                        'delay_ms' => $delay,
                        'error' => $e->getMessage()
                    ]));

                    usleep($delay * 1000); // Convert to microseconds
                } else {
                    Log::error("{$operation} failed after {$maxRetries} attempts", array_merge($context, [
                        'attempt' => $attempt,
                        'max_retries' => $maxRetries,
                        'final_error' => $e->getMessage()
                    ]));
                }
            }
        }

        throw $lastException;
    }
    /**
     * Sync event to user's Google Calendar with retry logic
     */
    public function syncEventToUserCalendar(Event $event, User $user, int $maxRetries = 3)
    {
        return $this->retryWithBackoff(function () use ($event, $user) {
            return $this->syncEventToUserCalendarOnce($event, $user);
        }, $maxRetries, 'syncEventToUserCalendar', ['event_id' => $event->id, 'user_id' => $user->id]);
    }

    /**
     * Single attempt to sync event to user's Google Calendar
     */
    private function syncEventToUserCalendarOnce(Event $event, User $user)
    {
        if (!$user->hasGoogleCalendarAccess()) {
            Log::info('User does not have Google Calendar access', [
                'user_id' => $user->id,
                'event_id' => $event->id
            ]);
            return false;
        }

        // Get or create sync record
        $sync = EventCalendarSync::firstOrCreate([
            'event_id' => $event->id,
            'user_id' => $user->id,
        ]);

        try {
            $client = $this->getGoogleClientForUser($user);
            $service = new \Google\Service\Calendar($client);

            $calendarEvent = $this->createCalendarEventObject($event);

            // If we have a stored Google Event ID, try to update that specific event
            if ($sync->google_event_id) {
                try {
                    $updatedEvent = $service->events->update('primary', $sync->google_event_id, $calendarEvent);
                    $sync->markSynced($updatedEvent->getId());
                    Log::info('Updated existing event in user calendar', [
                        'event_id' => $event->id,
                        'user_id' => $user->id,
                        'google_event_id' => $updatedEvent->getId()
                    ]);
                    return true;
                } catch (\Google\Service\Exception $e) {
                    if ($e->getCode() === 404) {
                        // Event not found, clear the stored ID and create new
                        $sync->google_event_id = null;
                        $sync->save();
                        Log::info('Google event not found, will create new', [
                            'event_id' => $event->id,
                            'user_id' => $user->id,
                            'old_google_event_id' => $sync->google_event_id
                        ]);
                    } else {
                        throw $e;
                    }
                }
            }

            // Check if event already exists by searching - and clean up duplicates
            $existingEvents = $this->findAllSimilarGoogleCalendarEvents($service, $event, $user);

            // Clean up duplicate events (events with same title in similar time range)
            if (count($existingEvents) > 1) {
                // Sort by updated time, keep the most recent
                usort($existingEvents, function ($a, $b) {
                    return strtotime($b->getUpdated()) - strtotime($a->getUpdated());
                });

                // Delete all but the most recent
                for ($i = 1; $i < count($existingEvents); $i++) {
                    try {
                        $service->events->delete('primary', $existingEvents[$i]->getId());
                        Log::info('Deleted duplicate event during sync', [
                            'event_id' => $event->id,
                            'user_id' => $user->id,
                            'deleted_google_event_id' => $existingEvents[$i]->getId(),
                            'title' => $existingEvents[$i]->getSummary()
                        ]);
                    } catch (\Exception $e) {
                        Log::warning('Failed to delete duplicate event during sync', [
                            'event_id' => $event->id,
                            'user_id' => $user->id,
                            'google_event_id' => $existingEvents[$i]->getId(),
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            }

            if (count($existingEvents) >= 1) {
                // Always update the most recent existing event with correct data (including correct date/time)
                $mostRecentEvent = $existingEvents[0];

                // Check if the existing event has the correct date/time
                $existingStart = $mostRecentEvent->getStart();
                $correctStart = Carbon::parse($event->start_time)->toRfc3339String();

                $needsUpdate = true;
                if ($existingStart && $existingStart->getDateTime()) {
                    // Compare dates (ignore seconds for flexibility)
                    $existingDateTime = Carbon::parse($existingStart->getDateTime());
                    $correctDateTime = Carbon::parse($event->start_time);

                    // If dates are the same (within 1 minute), no need to update
                    if (abs($existingDateTime->diffInMinutes($correctDateTime)) <= 1) {
                        $needsUpdate = false;
                        Log::info('Event date/time is already correct, skipping update', [
                            'event_id' => $event->id,
                            'user_id' => $user->id,
                            'google_event_id' => $mostRecentEvent->getId()
                        ]);
                    }
                }

                if ($needsUpdate) {
                    $updatedEvent = $service->events->update('primary', $mostRecentEvent->getId(), $calendarEvent);
                    $sync->markSynced($updatedEvent->getId());
                    Log::info('Updated event with correct date/time in user calendar', [
                        'event_id' => $event->id,
                        'user_id' => $user->id,
                        'google_event_id' => $updatedEvent->getId(),
                        'corrected_date' => $event->start_time
                    ]);
                } else {
                    // Just update sync record without API call
                    $sync->markSynced($mostRecentEvent->getId());
                }
            } else {
                // Create new event
                $newEvent = $service->events->insert('primary', $calendarEvent);
                $sync->markSynced($newEvent->getId());
                Log::info('Created new event in user calendar', [
                    'event_id' => $event->id,
                    'user_id' => $user->id,
                    'google_event_id' => $newEvent->getId()
                ]);
            }

            return true;
        } catch (\Exception $e) {
            $sync->markFailed($e->getMessage());
            Log::error('Failed to sync event to user calendar', [
                'event_id' => $event->id,
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            throw $e; // Re-throw to allow retry logic to handle it
        }
    }

    /**
     * Remove event from user's Google Calendar
     */
    public function removeEventFromUserCalendar(Event $event, User $user)
    {
        if (!$user->hasGoogleCalendarAccess()) {
            Log::info('User does not have Google Calendar access, skipping delete', [
                'user_id' => $user->id,
                'event_id' => $event->id
            ]);
            return false;
        }

        $sync = EventCalendarSync::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->first();

        Log::info('Attempting to remove event from user calendar', [
            'event_id' => $event->id,
            'user_id' => $user->id,
            'has_sync_record' => $sync ? 'yes' : 'no',
            'google_event_id' => $sync ? $sync->google_event_id : null
        ]);

        // Refresh token if expired
        if ($user->isGoogleTokenExpired()) {
            if (!$this->refreshUserToken($user)) {
                if ($sync) $sync->markFailed('Token refresh failed');
                return false;
            }
        }

        try {
            $client = $this->getGoogleClientForUser($user);
            $service = new \Google\Service\Calendar($client);

            // If we have stored Google Event ID, delete that specific event
            if ($sync && $sync->google_event_id) {
                try {
                    $service->events->delete('primary', $sync->google_event_id);
                    $sync->delete(); // Remove sync record
                    Log::info('Removed event from user calendar by ID', [
                        'event_id' => $event->id,
                        'user_id' => $user->id,
                        'google_event_id' => $sync->google_event_id
                    ]);
                    return true;
                } catch (\Google\Service\Exception $e) {
                    if ($e->getCode() === 404) {
                        // Event not found, remove sync record anyway
                        $sync->delete();
                        Log::info('Event not found in calendar, removed sync record', [
                            'event_id' => $event->id,
                            'user_id' => $user->id
                        ]);
                        return true;
                    } else {
                        Log::error('Google API error deleting event by ID', [
                            'event_id' => $event->id,
                            'user_id' => $user->id,
                            'google_event_id' => $sync->google_event_id,
                            'error_code' => $e->getCode(),
                            'error_message' => $e->getMessage()
                        ]);
                        throw $e;
                    }
                }
            }

            // Fallback: search and delete by criteria
            $googleEvent = $this->findGoogleCalendarEvent($service, $event, $user);

            if ($googleEvent) {
                $service->events->delete('primary', $googleEvent->getId());
                if ($sync) $sync->delete(); // Remove sync record
                Log::info('Removed event from user calendar by search', [
                    'event_id' => $event->id,
                    'user_id' => $user->id
                ]);
                return true;
            }

            // No event found, but remove sync record if exists
            if ($sync) $sync->delete();

            Log::info('No Google Calendar event found to delete', [
                'event_id' => $event->id,
                'user_id' => $user->id
            ]);

            return false;
        } catch (\Exception $e) {
            if ($sync) $sync->markFailed($e->getMessage());
            Log::error('Failed to remove event from user calendar', [
                'event_id' => $event->id,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return false;
        }
    }

    /**
     * Sync event to all participants' Google Calendars
     */
    public function syncEventToAllParticipants(Event $event)
    {
        $participants = $event->participants;
        $successCount = 0;
        $totalCount = $participants->count();

        Log::info('Starting bulk sync for all participants', [
            'event_id' => $event->id,
            'total_participants' => $totalCount
        ]);

        foreach ($participants as $index => $user) {
            try {
                Log::debug('Syncing event for participant', [
                    'event_id' => $event->id,
                    'user_id' => $user->id,
                    'progress' => ($index + 1) . '/' . $totalCount
                ]);

                $result = $this->syncEventToUserCalendar($event, $user);
                if ($result) {
                    $successCount++;
                }
            } catch (\Exception $e) {
                Log::warning('Failed to sync event for participant in bulk operation', [
                    'event_id' => $event->id,
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
                // Continue with other participants even if one fails
            }

            // Clean up orphaned events after each sync
            try {
                $this->cleanupOrphanedEvents($user);
            } catch (\Exception $e) {
                Log::warning('Failed to cleanup orphaned events', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        Log::info('Bulk sync completed', [
            'event_id' => $event->id,
            'total_participants' => $totalCount,
            'successful_syncs' => $successCount
        ]);

        return $successCount > 0;
    }

    /**
     * Remove event from all participants' Google Calendars
     */
    public function removeEventFromAllCalendars(Event $event)
    {
        $participants = $event->participants;

        foreach ($participants as $user) {
            $this->removeEventFromUserCalendar($event, $user);
        }

        // After removing from calendars, clean up sync records
        EventCalendarSync::where('event_id', $event->id)->delete();
    }

    /**
     * Remove all events from user's Google Calendar (for revoke access)
     */
    public function removeUserEventsFromCalendar(User $user)
    {
        // Get all sync records for this user first
        $syncs = EventCalendarSync::where('user_id', $user->id)
            ->whereNotNull('google_event_id')
            ->get();

        // If no sync records, nothing to do
        if ($syncs->isEmpty()) {
            return true;
        }

        // Try to delete from Google Calendar if user has valid access
        if ($user->hasGoogleCalendarAccess()) {
            try {
                // Refresh token if expired
                if ($user->isGoogleTokenExpired()) {
                    if (!$this->refreshUserToken($user)) {
                        Log::warning('Token refresh failed during event removal, will clean up local records only', [
                            'user_id' => $user->id
                        ]);
                        // Continue to clean up local records even if token refresh fails
                    }
                }

                $client = $this->getGoogleClientForUser($user);
                $service = new \Google\Service\Calendar($client);

                $deletedCount = 0;
                foreach ($syncs as $sync) {
                    try {
                        $service->events->delete('primary', $sync->google_event_id);
                        $sync->delete(); // Remove sync record
                        $deletedCount++;
                    } catch (\Exception $e) {
                        Log::warning('Failed to delete individual event from calendar', [
                            'user_id' => $user->id,
                            'google_event_id' => $sync->google_event_id,
                            'error' => $e->getMessage()
                        ]);
                        // Still delete the sync record to prevent orphaned data
                        $sync->delete();
                    }
                }

                Log::info('Removed user events from calendar', [
                    'user_id' => $user->id,
                    'deleted_count' => $deletedCount
                ]);

                return true;
            } catch (\Exception $e) {
                Log::error('Failed to initialize Google Calendar service for event removal', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
                // Continue to clean up local records even if Google service fails
            }
        }

        // Clean up local sync records even if Google Calendar access failed
        foreach ($syncs as $sync) {
            $sync->delete();
        }

        Log::info('Cleaned up local sync records (Google Calendar access not available)', [
            'user_id' => $user->id,
            'records_cleaned' => $syncs->count()
        ]);

        return false; // Return false to indicate Google Calendar removal failed, but local cleanup succeeded
    }

    /**
     * Refresh user's Google access token
     */
    public function refreshUserToken(User $user)
    {
        if (!$user->google_refresh_token) {
            return false;
        }

        try {
            // Use Google API Client to refresh token
            $client = new \Google_Client();
            $client->setClientId(config('services.google.client_id'));
            $client->setClientSecret(config('services.google.client_secret'));
            $client->refreshToken($user->google_refresh_token);

            $newToken = $client->getAccessToken();

            $user->update([
                'google_access_token' => $newToken['access_token'],
                'google_token_expires_at' => now()->addSeconds($newToken['expires_in']),
                'google_refresh_token' => $newToken['refresh_token'] ?? $user->google_refresh_token,
            ]);

            Log::info('Refreshed Google token for user', ['user_id' => $user->id]);
            return true;
        } catch (\Exception $e) {
            Log::error('Failed to refresh Google token', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Validate if user has valid Google Calendar access by making a test API call
     * If access is revoked, clear local tokens
     */
    public function validateGoogleCalendarAccess(User $user)
    {
        // First check if user has basic token requirements
        if (!$user->hasGoogleCalendarAccess()) {
            return false;
        }

        try {
            $client = $this->getGoogleClientForUser($user);
            $service = new \Google\Service\Calendar($client);

            // Make a simple API call to test if token is still valid
            // We'll try to get the calendar list (this is a lightweight call)
            $calendarList = $service->calendarList->listCalendarList(['maxResults' => 1]);

            // If we get here without exception, token is valid
            Log::info('Google Calendar access validated successfully', ['user_id' => $user->id]);
            return true;
        } catch (\Google\Service\Exception $e) {
            $code = $e->getCode();

            // If it's an authentication error (401/403), access has been revoked
            if ($code === 401 || $code === 403) {
                Log::warning('Google Calendar access revoked, clearing local tokens', [
                    'user_id' => $user->id,
                    'error_code' => $code,
                    'error' => $e->getMessage()
                ]);

                // Clear local tokens since access is revoked
                $user->update([
                    'google_access_token' => null,
                    'google_refresh_token' => null,
                    'google_token_expires_at' => null,
                    'google_calendar_id' => null,
                ]);

                // Clean up sync records
                EventCalendarSync::where('user_id', $user->id)->delete();

                return false;
            }

            // For other errors, log but don't clear tokens (might be temporary)
            Log::warning('Google Calendar API error during validation', [
                'user_id' => $user->id,
                'error_code' => $code,
                'error' => $e->getMessage()
            ]);

            return false;
        } catch (\Exception $e) {
            Log::error('Unexpected error during Google Calendar access validation', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Get Google Client configured for specific user
     */
    protected function getGoogleClientForUser(User $user)
    {
        $client = new \Google_Client();
        $client->setClientId(config('services.google.client_id'));
        $client->setClientSecret(config('services.google.client_secret'));

        // Set the access token with full token data if available
        if ($user->google_access_token) {
            $tokenData = [
                'access_token' => $user->google_access_token,
                'refresh_token' => $user->google_refresh_token,
                'expires_in' => $user->google_token_expires_at ? $user->google_token_expires_at->diffInSeconds(now()) : 3600,
                'created' => $user->google_token_expires_at ? $user->google_token_expires_at->subHours(1)->timestamp : time(),
            ];
            $client->setAccessToken($tokenData);
        }

        $client->setScopes([\Google\Service\Calendar::CALENDAR]);

        // If token is expired, try to refresh it automatically
        if ($client->isAccessTokenExpired()) {
            if ($user->google_refresh_token && $this->refreshUserToken($user)) {
                // Token refreshed, update the client with new token
                $tokenData = [
                    'access_token' => $user->google_access_token,
                    'refresh_token' => $user->google_refresh_token,
                    'expires_in' => $user->google_token_expires_at ? $user->google_token_expires_at->diffInSeconds(now()) : 3600,
                    'created' => $user->google_token_expires_at ? $user->google_token_expires_at->subHours(1)->timestamp : time(),
                ];
                $client->setAccessToken($tokenData);
            }
        }

        return $client;
    }

    /**
     * Find existing Google Calendar event
     */
    protected function findGoogleCalendarEvent(\Google\Service\Calendar $service, Event $event, User $user)
    {
        try {
            // Search for events in a time range
            $optParams = [
                'timeMin' => Carbon::parse($event->start_time)->subHours(1)->toRfc3339String(),
                'timeMax' => Carbon::parse($event->end_time)->addHours(1)->toRfc3339String(),
                'singleEvents' => true,
                'orderBy' => 'startTime',
            ];

            $results = $service->events->listEvents('primary', $optParams);
            $googleEvents = $results->getItems();

            foreach ($googleEvents as $googleEvent) {
                if (
                    $googleEvent->getSummary() === $event->title &&
                    Carbon::parse($googleEvent->getStart()->getDateTime())->equalTo(Carbon::parse($event->start_time))
                ) {
                    return $googleEvent;
                }
            }

            return null;
        } catch (\Exception $e) {
            Log::error('Failed to find Google Calendar event', [
                'event_id' => $event->id,
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * Find all similar Google Calendar events (returns array instead of single event)
     */
    protected function findAllSimilarGoogleCalendarEvents(\Google\Service\Calendar $service, Event $event, User $user)
    {
        try {
            // First, try to find events in the correct time range (±1 hour)
            $optParams = [
                'timeMin' => Carbon::parse($event->start_time)->subHours(1)->toRfc3339String(),
                'timeMax' => Carbon::parse($event->end_time)->addHours(1)->toRfc3339String(),
                'singleEvents' => true,
                'orderBy' => 'startTime',
            ];

            $results = $service->events->listEvents('primary', $optParams);
            $googleEvents = $results->getItems();

            $similarEvents = [];
            foreach ($googleEvents as $googleEvent) {
                if ($googleEvent->getSummary() === $event->title) {
                    $similarEvents[] = $googleEvent;
                }
            }

            // If no events found in correct time range, search broader (past 30 days and future 30 days)
            // This helps find events that users have dragged to wrong dates
            if (empty($similarEvents)) {
                $optParams = [
                    'timeMin' => Carbon::parse($event->start_time)->subDays(30)->toRfc3339String(),
                    'timeMax' => Carbon::parse($event->end_time)->addDays(30)->toRfc3339String(),
                    'singleEvents' => true,
                    'orderBy' => 'startTime',
                    'q' => $event->title, // Search by title in query
                ];

                $results = $service->events->listEvents('primary', $optParams);
                $googleEvents = $results->getItems();

                foreach ($googleEvents as $googleEvent) {
                    if ($googleEvent->getSummary() === $event->title) {
                        $similarEvents[] = $googleEvent;
                        Log::info('Found event that was moved to different date, will correct it', [
                            'event_id' => $event->id,
                            'user_id' => $user->id,
                            'google_event_id' => $googleEvent->getId(),
                            'original_date' => $event->start_time,
                            'current_date' => $googleEvent->getStart()->getDateTime()
                        ]);
                    }
                }
            }

            return $similarEvents;
        } catch (\Exception $e) {
            Log::error('Failed to find Google Calendar events', [
                'event_id' => $event->id,
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Create Google Calendar Event object from our Event
     */
    protected function createCalendarEventObject(Event $event)
    {
        $googleEvent = new \Google\Service\Calendar\Event();
        $googleEvent->setSummary($event->title);

        // Create comprehensive description with all event details
        $description = $event->description . "\n\n";

        // Add organizer information
        if ($event->creator) {
            $description .= "Penyelenggara: {$event->creator->name}";
            if ($event->creator->division) {
                $description .= " ({$event->creator->division})";
            }
            $description .= "\n";
        }

        // Add category if exists
        if ($event->category) {
            $description .= "Kategori: {$event->category->name}\n";
        }

        // Add participant count
        $participantCount = $event->participants()->count();
        $description .= "Jumlah Peserta Terdaftar: {$participantCount}\n";

        // Add current status
        $description .= "Status: {$event->status}\n";

        // Add location if available
        if ($event->location) {
            $description .= "Lokasi: {$event->location}\n";
        }

        // Add link to view full event details
        $eventUrl = url("/participant/events/{$event->id}");
        $description .= "\nLihat Detail Lengkap: {$eventUrl}\n";

        // Add QR code scanning instruction (without event code)
        $description .= "\nUntuk absensi, scan QR code di aplikasi event.";

        $googleEvent->setDescription($description);
        $googleEvent->setLocation($event->location);

        $start = new \Google\Service\Calendar\EventDateTime();
        $start->setDateTime(Carbon::parse($event->start_time)->toRfc3339String());
        $start->setTimeZone(config('app.timezone', 'Asia/Jakarta'));
        $googleEvent->setStart($start);

        $end = new \Google\Service\Calendar\EventDateTime();
        $end->setDateTime(Carbon::parse($event->end_time)->toRfc3339String());
        $end->setTimeZone(config('app.timezone', 'Asia/Jakarta'));
        $googleEvent->setEnd($end);

        // Add reminder
        $reminder = new \Google\Service\Calendar\EventReminder();
        $reminder->setMethod('popup');
        $reminder->setMinutes(15);

        $reminders = new \Google\Service\Calendar\EventReminders();
        $reminders->setUseDefault(false);
        $reminders->setOverrides([$reminder]);
        $googleEvent->setReminders($reminders);

        return $googleEvent;
    }

    /**
     * Clean up orphaned events in user's calendar (events that exist in calendar but not in sync records)
     */
    public function cleanupOrphanedEvents(User $user)
    {
        if (!$user->hasGoogleCalendarAccess()) {
            return false;
        }

        // Refresh token if expired
        if ($user->isGoogleTokenExpired()) {
            if (!$this->refreshUserToken($user)) {
                return false;
            }
        }

        try {
            $client = $this->getGoogleClientForUser($user);
            $service = new \Google\Service\Calendar($client);

            // Get all sync records for this user
            $validEventIds = EventCalendarSync::where('user_id', $user->id)
                ->whereNotNull('google_event_id')
                ->pluck('google_event_id')
                ->toArray();

            // Search for events that might be created by our system (look for events with our typical patterns)
            $optParams = [
                'timeMin' => now()->subDays(7)->toRfc3339String(), // Look back 7 days
                'timeMax' => now()->addDays(30)->toRfc3339String(), // Look forward 30 days
                'singleEvents' => true,
                'orderBy' => 'startTime',
                'maxResults' => 250 // Get more results
            ];

            $results = $service->events->listEvents('primary', $optParams);
            $googleEvents = $results->getItems();

            $deletedCount = 0;
            foreach ($googleEvents as $googleEvent) {
                $googleEventId = $googleEvent->getId();

                // Skip if this event is in our sync records
                if (in_array($googleEventId, $validEventIds)) {
                    continue;
                }

                $eventTitle = $googleEvent->getSummary();
                $eventDescription = $googleEvent->getDescription() ?: '';

                // Check if this looks like an event created by our system
                $isOurEvent = false;

                // Check for patterns that indicate our events
                if (
                    // Contains typical Indonesian event terms
                    stripos($eventTitle, 'presentasi') !== false ||
                    stripos($eventTitle, 'rapat') !== false ||
                    stripos($eventTitle, 'seminar') !== false ||
                    stripos($eventTitle, 'workshop') !== false ||
                    stripos($eventTitle, 'training') !== false ||
                    stripos($eventTitle, 'meeting') !== false ||
                    // Contains "Test" (our test events)
                    stripos($eventTitle, 'test') !== false ||
                    stripos($eventTitle, 'Test') !== false ||
                    // Has location that looks like our format
                    (stripos($eventDescription, 'Gedung') !== false ||
                        stripos($eventDescription, 'Ruang') !== false ||
                        stripos($eventDescription, 'Room') !== false)
                ) {
                    $isOurEvent = true;
                }

                // Also check if there's a similar event in our database (more lenient check)
                $eventStart = Carbon::parse($googleEvent->getStart()->getDateTime());
                $similarEvent = Event::where('title', 'LIKE', '%' . substr($eventTitle, 0, 10) . '%')
                    ->where('start_time', '>=', $eventStart->copy()->subHours(24))
                    ->where('start_time', '<=', $eventStart->copy()->addHours(24))
                    ->first();

                if ($similarEvent) {
                    $isOurEvent = true;
                }

                if ($isOurEvent) {
                    try {
                        $service->events->delete('primary', $googleEventId);
                        $deletedCount++;
                        Log::info('Cleaned up orphaned event', [
                            'user_id' => $user->id,
                            'google_event_id' => $googleEventId,
                            'title' => $eventTitle,
                            'reason' => 'matched_our_patterns'
                        ]);
                    } catch (\Exception $e) {
                        Log::warning('Failed to delete orphaned event', [
                            'user_id' => $user->id,
                            'google_event_id' => $googleEventId,
                            'title' => $eventTitle,
                            'error' => $e->getMessage()
                        ]);
                    }
                }
            }

            if ($deletedCount > 0) {
                Log::info('Cleaned up orphaned events', [
                    'user_id' => $user->id,
                    'deleted_count' => $deletedCount
                ]);
            }

            return true;
        } catch (\Exception $e) {
            Log::error('Failed to cleanup orphaned events', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            return false;
        }
    }
}
