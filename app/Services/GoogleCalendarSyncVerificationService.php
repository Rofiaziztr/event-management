<?php

namespace App\Services;

use App\Models\Event;
use App\Models\User;
use App\Models\EventCalendarSync;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * Google Calendar Sync Verification & Recovery Service
 * 
 * Handles:
 * - Detecting sync inconsistencies
 * - Repairing broken sync records
 * - Cleaning up orphaned data
 * - Verifying data integrity
 */
class GoogleCalendarSyncVerificationService
{
    protected $calendarService;

    public function __construct(GoogleCalendarService $calendarService)
    {
        $this->calendarService = $calendarService;
    }

    /**
     * Verify all sync records and fix inconsistencies
     * Returns array of issues found and fixed
     */
    public function verifyAllSyncs(): array
    {
        Log::info('Starting comprehensive sync verification');

        $issues = [
            'orphaned_sync_records' => 0,
            'missing_event_references' => 0,
            'missing_user_references' => 0,
            'failed_syncs' => 0,
            'fixed_issues' => 0,
        ];

        // Check for orphaned sync records (referencing deleted events)
        $orphanedEventSyncs = EventCalendarSync::whereNotIn('event_id', Event::pluck('id'))->get();
        if ($orphanedEventSyncs->count() > 0) {
            Log::warning("Found {$orphanedEventSyncs->count()} sync records with deleted events");
            $issues['missing_event_references'] = $orphanedEventSyncs->count();

            foreach ($orphanedEventSyncs as $sync) {
                try {
                    // Try to delete from Google Calendar if we have the ID
                    if ($sync->google_event_id && $sync->user) {
                        try {
                            $user = $sync->user;
                            if ($user->hasGoogleCalendarAccess()) {
                                $client = $this->calendarService->getGoogleClientForUser($user);
                                $service = new \Google\Service\Calendar($client);
                                $service->events->delete('primary', $sync->google_event_id);
                                Log::info('Deleted orphaned Google event', [
                                    'sync_id' => $sync->id,
                                    'google_event_id' => $sync->google_event_id,
                                    'user_id' => $sync->user_id
                                ]);
                            }
                        } catch (\Exception $e) {
                            Log::warning('Could not delete orphaned Google event', [
                                'sync_id' => $sync->id,
                                'error' => $e->getMessage()
                            ]);
                        }
                    }

                    // Clean up the local sync record
                    $sync->delete();
                    $issues['fixed_issues']++;
                } catch (\Exception $e) {
                    Log::error('Failed to cleanup orphaned sync record', [
                        'sync_id' => $sync->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }

        // Check for orphaned sync records (referencing deleted users)
        $orphanedUserSyncs = EventCalendarSync::whereNotIn('user_id', User::pluck('id'))->get();
        if ($orphanedUserSyncs->count() > 0) {
            Log::warning("Found {$orphanedUserSyncs->count()} sync records with deleted users");
            $issues['missing_user_references'] = $orphanedUserSyncs->count();

            foreach ($orphanedUserSyncs as $sync) {
                try {
                    $sync->delete();
                    $issues['fixed_issues']++;
                } catch (\Exception $e) {
                    Log::error('Failed to cleanup sync record with deleted user', [
                        'sync_id' => $sync->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }

        // Check for failed syncs that need retry
        $failedSyncs = EventCalendarSync::where('sync_status', 'failed')
            ->where('last_sync_attempt', '<', now()->subHours(1))
            ->limit(100)
            ->get();

        if ($failedSyncs->count() > 0) {
            Log::info("Found {$failedSyncs->count()} failed syncs to retry");
            $issues['failed_syncs'] = $failedSyncs->count();

            foreach ($failedSyncs as $sync) {
                try {
                    $event = Event::find($sync->event_id);
                    $user = User::find($sync->user_id);

                    if ($event && $user && $user->hasGoogleCalendarAccess()) {
                        if ($this->calendarService->syncEventToUserCalendar($event, $user)) {
                            $issues['fixed_issues']++;
                        }
                    }
                } catch (\Exception $e) {
                    Log::warning('Failed to retry sync', [
                        'sync_id' => $sync->id,
                        'error' => $e->getMessage()
                    ]);
                }
            }
        }

        Log::info('Sync verification completed', $issues);
        return $issues;
    }

    /**
     * Verify sync records for a specific event
     */
    public function verifyEventSyncs(Event $event): array
    {
        Log::info('Verifying sync records for event', ['event_id' => $event->id]);

        $issues = [];
        $syncs = EventCalendarSync::where('event_id', $event->id)->get();

        foreach ($syncs as $sync) {
            $user = $sync->user;

            if (!$user) {
                $issues[] = [
                    'type' => 'missing_user',
                    'sync_id' => $sync->id,
                    'message' => "Sync record references deleted user (ID: {$sync->user_id})"
                ];
                continue;
            }

            if (!$user->hasGoogleCalendarAccess()) {
                $issues[] = [
                    'type' => 'no_calendar_access',
                    'sync_id' => $sync->id,
                    'user_id' => $user->id,
                    'message' => "User no longer has Google Calendar access"
                ];
                continue;
            }

            if ($sync->sync_status === 'failed') {
                $issues[] = [
                    'type' => 'sync_failed',
                    'sync_id' => $sync->id,
                    'user_id' => $user->id,
                    'error' => $sync->sync_error,
                    'message' => "Sync failed: {$sync->sync_error}"
                ];
            }
        }

        if ($issues) {
            Log::warning("Found {count($issues)} issues in event sync records", [
                'event_id' => $event->id,
                'issues' => $issues
            ]);
        }

        return $issues;
    }

    /**
     * Verify sync records for a specific user
     */
    public function verifyUserSyncs(User $user): array
    {
        Log::info('Verifying sync records for user', ['user_id' => $user->id]);

        $issues = [];

        if (!$user->hasGoogleCalendarAccess()) {
            Log::info('User does not have Google Calendar access', ['user_id' => $user->id]);
            return [['type' => 'no_calendar_access', 'message' => 'User has no Google Calendar access']];
        }

        $syncs = EventCalendarSync::where('user_id', $user->id)->get();

        foreach ($syncs as $sync) {
            $event = $sync->event;

            if (!$event) {
                $issues[] = [
                    'type' => 'missing_event',
                    'sync_id' => $sync->id,
                    'event_id' => $sync->event_id,
                    'message' => "Sync record references deleted event (ID: {$sync->event_id})"
                ];
                continue;
            }

            if ($sync->sync_status === 'failed') {
                $issues[] = [
                    'type' => 'sync_failed',
                    'sync_id' => $sync->id,
                    'event_id' => $event->id,
                    'error' => $sync->sync_error,
                    'message' => "Sync failed: {$sync->sync_error}"
                ];
            }
        }

        if ($issues) {
            Log::warning("Found {count($issues)} issues in user sync records", [
                'user_id' => $user->id,
                'issues' => $issues
            ]);
        }

        return $issues;
    }

    /**
     * Repair all sync records for an event
     */
    public function repairEventSyncs(Event $event): array
    {
        Log::info('Repairing sync records for event', ['event_id' => $event->id]);

        $results = [
            'repaired' => 0,
            'failed' => 0,
            'errors' => []
        ];

        $syncs = EventCalendarSync::where('event_id', $event->id)
            ->where('sync_status', '!=', 'synced')
            ->get();

        foreach ($syncs as $sync) {
            $user = $sync->user;

            if (!$user) {
                $results['failed']++;
                $results['errors'][] = "Sync ID {$sync->id}: User not found";
                continue;
            }

            if (!$user->hasGoogleCalendarAccess()) {
                $results['failed']++;
                $results['errors'][] = "Sync ID {$sync->id}: User no calendar access";
                continue;
            }

            try {
                if ($this->calendarService->syncEventToUserCalendar($event, $user)) {
                    $results['repaired']++;
                } else {
                    $results['failed']++;
                    $results['errors'][] = "Sync ID {$sync->id}: Sync returned false";
                }
            } catch (\Exception $e) {
                $results['failed']++;
                $results['errors'][] = "Sync ID {$sync->id}: {$e->getMessage()}";
            }
        }

        Log::info('Event sync repair completed', $results);
        return $results;
    }

    /**
     * Repair all sync records for a user
     */
    public function repairUserSyncs(User $user): array
    {
        Log::info('Repairing sync records for user', ['user_id' => $user->id]);

        $results = [
            'repaired' => 0,
            'failed' => 0,
            'errors' => []
        ];

        if (!$user->hasGoogleCalendarAccess()) {
            $results['failed']++;
            $results['errors'][] = 'User does not have Google Calendar access';
            return $results;
        }

        $syncs = EventCalendarSync::where('user_id', $user->id)
            ->where('sync_status', '!=', 'synced')
            ->get();

        foreach ($syncs as $sync) {
            $event = $sync->event;

            if (!$event) {
                $results['failed']++;
                $results['errors'][] = "Sync ID {$sync->id}: Event not found";
                continue;
            }

            try {
                if ($this->calendarService->syncEventToUserCalendar($event, $user)) {
                    $results['repaired']++;
                } else {
                    $results['failed']++;
                    $results['errors'][] = "Sync ID {$sync->id}: Sync returned false";
                }
            } catch (\Exception $e) {
                $results['failed']++;
                $results['errors'][] = "Sync ID {$sync->id}: {$e->getMessage()}";
            }
        }

        Log::info('User sync repair completed', $results);
        return $results;
    }

    /**
     * Get sync statistics
     */
    public function getSyncStatistics(): array
    {
        $totalSyncs = EventCalendarSync::count();
        $syncedCount = EventCalendarSync::where('sync_status', 'synced')->count();
        $failedCount = EventCalendarSync::where('sync_status', 'failed')->count();
        $pendingCount = EventCalendarSync::where('sync_status', 'pending')->count();

        $orphanedEventCount = EventCalendarSync::whereNotIn('event_id', Event::pluck('id'))->count();
        $orphanedUserCount = EventCalendarSync::whereNotIn('user_id', User::pluck('id'))->count();

        return [
            'total_syncs' => $totalSyncs,
            'synced' => $syncedCount,
            'failed' => $failedCount,
            'pending' => $pendingCount,
            'success_rate' => $totalSyncs > 0 ? round(($syncedCount / $totalSyncs) * 100, 2) : 0,
            'orphaned_events' => $orphanedEventCount,
            'orphaned_users' => $orphanedUserCount,
            'issues_count' => $failedCount + $orphanedEventCount + $orphanedUserCount
        ];
    }
}
