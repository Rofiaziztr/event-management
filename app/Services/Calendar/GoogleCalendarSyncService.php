<?php

namespace App\Services\Calendar;

use App\Models\Event;
use Google\Service\Calendar\EventAttendee;
use Google\Service\Exception as GoogleServiceException;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use RuntimeException;
use Spatie\GoogleCalendar\Event as GoogleCalendarEvent;
use Throwable;

class GoogleCalendarSyncService
{
    /**
     * Sync the given event with Google Calendar.
     */
    public function sync(Event $event): bool
    {
        if (! config('google-calendar.sync_enabled')) {
            $this->markAsDisabled($event);

            return false;
        }

        if (! $event->start_time || ! $event->end_time) {
            return false;
        }

        $calendarId = config('google-calendar.calendar_id');

        try {
            $this->ensureCredentialsAvailable();

            $payload = $this->buildPayload($event);
            $optParams = $this->buildOptParams();
            $googleEvent = null;

            if ($event->google_calendar_event_id) {
                $googleEvent = $this->tryUpdateExistingEvent($event, $payload, $optParams, $calendarId);
            }

            if (! $googleEvent) {
                $googleEvent = $this->createEvent($payload, $optParams, $calendarId);
            }

            if ($this->shouldAttachMeetLink() && ! $googleEvent->hangoutLink) {
                $googleEvent->addMeetLink();
                $googleEvent = $googleEvent->save('updateEvent', $optParams);
            }

            $this->persistSyncResult($event, $googleEvent, 'synced');

            return true;
        } catch (Throwable $exception) {
            $this->persistSyncError($event, $exception);

            return false;
        }
    }

    /**
     * Delete the Google Calendar event if it exists.
     */
    public function delete(Event $event): bool
    {
        if (! $event->google_calendar_event_id) {
            return false;
        }

        if (! config('google-calendar.sync_enabled')) {
            $this->markAsDisabled($event);

            return false;
        }

        $calendarId = config('google-calendar.calendar_id');

        try {
            $this->ensureCredentialsAvailable();

            $googleEvent = GoogleCalendarEvent::find($event->google_calendar_event_id, $calendarId);
            $googleEvent->delete();

            $event->forceFill([
                'google_calendar_event_id' => null,
                'google_calendar_link' => null,
                'google_conference_link' => null,
                'google_calendar_synced_at' => now(),
                'google_calendar_sync_status' => 'deleted',
                'google_calendar_last_error' => null,
            ])->saveQuietly();

            return true;
        } catch (GoogleServiceException $exception) {
            if ($exception->getCode() === 404) {
                $event->forceFill([
                    'google_calendar_event_id' => null,
                    'google_calendar_link' => null,
                    'google_conference_link' => null,
                    'google_calendar_synced_at' => now(),
                    'google_calendar_sync_status' => 'missing',
                    'google_calendar_last_error' => null,
                ])->saveQuietly();

                return true;
            }

            $this->persistSyncError($event, $exception);
        } catch (Throwable $exception) {
            $this->persistSyncError($event, $exception);
        }

        return false;
    }

    protected function createEvent(array $payload, array $optParams, ?string $calendarId): GoogleCalendarEvent
    {
        return GoogleCalendarEvent::create($payload, $calendarId, $optParams);
    }

    protected function tryUpdateExistingEvent(Event $event, array $payload, array $optParams, ?string $calendarId): ?GoogleCalendarEvent
    {
        try {
            $googleEvent = GoogleCalendarEvent::find($event->google_calendar_event_id, $calendarId);
            $googleEvent = $googleEvent->update($payload, $optParams);

            return $googleEvent;
        } catch (GoogleServiceException $exception) {
            if ($exception->getCode() === 404) {
                Log::warning('Google Calendar event missing during sync, will recreate.', [
                    'event_id' => $event->id,
                    'google_event_id' => $event->google_calendar_event_id,
                ]);

                return null;
            }

            throw $exception;
        }
    }

    protected function buildPayload(Event $event): array
    {
        $timezone = $this->resolveTimezone();
        $description = trim($event->description ?? '');

        $payload = [
            'name' => $event->title,
            'description' => $this->augmentDescription($event, $description),
            'startDateTime' => $event->start_time->copy()->setTimezone($timezone),
            'endDateTime' => $event->end_time->copy()->setTimezone($timezone),
            'location' => $event->location,
            'status' => $event->status === 'Dibatalkan' ? 'cancelled' : null,
            'guestsCanModify' => false,
            'guestsCanInviteOthers' => false,
            'guestsCanSeeOtherGuests' => true,
            'anyoneCanAddSelf' => false,
        ];

        if (is_null($payload['status'])) {
            unset($payload['status']);
        }

        if ($source = $this->buildSourceMetadata($event)) {
            $payload['source'] = $source;
        }

        if ($reminders = $this->buildRemindersConfig()) {
            $payload['reminders'] = $reminders;
        }

        if ($attendees = $this->buildAttendees($event)) {
            $payload['attendees'] = $attendees;
        }

        return $payload;
    }

    protected function augmentDescription(Event $event, string $initial): string
    {
        $parts = array_filter([
            $initial,
            'Lokasi: ' . $event->location,
            'Dibuat melalui ' . config('app.name'),
        ]);

        return implode(PHP_EOL . PHP_EOL, $parts);
    }

    protected function buildRemindersConfig(): ?array
    {
        $settings = config('google-calendar.default_event_settings.reminders');

        if (! $settings) {
            return null;
        }

        if (Arr::get($settings, 'use_default')) {
            return ['useDefault' => true];
        }

        $overrides = [];

        $emailMinutes = Arr::get($settings, 'email_minutes');
        if ($emailMinutes !== null) {
            $overrides[] = [
                'method' => 'email',
                'minutes' => (int) $emailMinutes,
            ];
        }

        $popupMinutes = Arr::get($settings, 'popup_minutes');
        if ($popupMinutes !== null) {
            $overrides[] = [
                'method' => 'popup',
                'minutes' => (int) $popupMinutes,
            ];
        }

        if (empty($overrides)) {
            return null;
        }

        return [
            'useDefault' => false,
            'overrides' => $overrides,
        ];
    }

    protected function buildAttendees(Event $event): array
    {
        $defaultAttendees = collect(config('google-calendar.default_event_settings.attendees', []))
            ->filter()
            ->map(fn ($email) => trim((string) $email));

        $participantEmails = $event->participants()
            ->pluck('users.email')
            ->filter();

        $emails = $defaultAttendees
            ->merge($participantEmails)
            ->when($event->creator && $event->creator->email, function ($collection) use ($event) {
                return $collection->push($event->creator->email);
            })
            ->filter(fn ($email) => filter_var($email, FILTER_VALIDATE_EMAIL))
            ->unique(fn ($email) => strtolower($email))
            ->values();

        return $emails
            ->map(function ($email) {
                $attendee = new EventAttendee();
                $attendee->setEmail($email);
                $attendee->setResponseStatus('accepted');

                return $attendee;
            })
            ->all();
    }

    protected function buildOptParams(): array
    {
        $params = [];

        if ($sendUpdates = config('google-calendar.send_updates')) {
            $params['sendUpdates'] = $sendUpdates;
        }

        if ($this->shouldAttachMeetLink()) {
            $params['conferenceDataVersion'] = 1;
        }

        return $params;
    }

    protected function shouldAttachMeetLink(): bool
    {
        return (bool) config('google-calendar.default_event_settings.conference.enabled');
    }

    protected function buildSourceMetadata(Event $event): ?array
    {
        $baseUrl = config('app.url');
        $eventUrl = $baseUrl ? rtrim($baseUrl, '/') . '/admin/events/' . $event->getKey() : null;

        if (! $eventUrl) {
            return null;
        }

        return [
            'title' => config('app.name'),
            'url' => $eventUrl,
        ];
    }

    protected function persistSyncResult(Event $event, GoogleCalendarEvent $googleEvent, string $status): void
    {
        $event->forceFill([
            'google_calendar_event_id' => $googleEvent->id,
            'google_calendar_link' => $googleEvent->htmlLink,
            'google_conference_link' => $googleEvent->hangoutLink,
            'google_calendar_synced_at' => now(),
            'google_calendar_sync_status' => $status,
            'google_calendar_last_error' => null,
        ])->saveQuietly();
    }

    protected function persistSyncError(Event $event, Throwable $exception): void
    {
        Log::error('Sinkronisasi Google Calendar gagal', [
            'event_id' => $event->id,
            'message' => $exception->getMessage(),
        ]);

        $event->forceFill([
            'google_calendar_sync_status' => 'failed',
            'google_calendar_last_error' => $this->translateErrorMessage($exception),
            'google_calendar_synced_at' => now(),
        ])->saveQuietly();
    }

    protected function markAsDisabled(Event $event): void
    {
        $event->forceFill([
            'google_calendar_sync_status' => 'disabled',
            'google_calendar_last_error' => null,
        ])->saveQuietly();
    }

    protected function resolveTimezone(): string
    {
        return config('google-calendar.timezone', config('app.timezone', 'UTC'));
    }

    protected function ensureCredentialsAvailable(): void
    {
        $profile = config('google-calendar.default_auth_profile', 'service_account');
        $profiles = config('google-calendar.auth_profiles', []);
        $profileConfig = Arr::get($profiles, $profile, []);

        if (empty($profileConfig)) {
            return;
        }

        $profileConfigKey = "google-calendar.auth_profiles.$profile";

        if ($credentialsPath = Arr::get($profileConfig, 'credentials_json')) {
            $credentialsPath = $this->normalizeCredentialsPath($credentialsPath);

            config(["$profileConfigKey.credentials_json" => $credentialsPath]);

            if (! file_exists($credentialsPath)) {
                throw new RuntimeException(
                    sprintf('File kredensial Google Calendar tidak ditemukan: %s', $credentialsPath)
                );
            }
        }

        if ($tokenPath = Arr::get($profileConfig, 'token_json')) {
            $tokenPath = $this->normalizeCredentialsPath($tokenPath);

            config(["$profileConfigKey.token_json" => $tokenPath]);
        }
    }

    protected function translateErrorMessage(Throwable $exception): string
    {
        $message = $exception->getMessage();

        if ($exception instanceof RuntimeException && Str::contains($message, 'kredensial')) {
            return $message;
        }

        if (Str::contains($message, 'does not exist')) {
            $path = $this->extractPathFromMessage($message);

            return sprintf('File kredensial Google Calendar tidak ditemukan: %s', $path ?? $message);
        }

        if ($exception instanceof GoogleServiceException) {
            return match ($exception->getCode()) {
                401 => 'Akses Google Calendar ditolak. Periksa kredensial dan izin akun layanan.',
                403 => 'Google Calendar menolak permintaan. Pastikan akun memiliki akses ke kalender tujuan.',
                404 => 'Event Google Calendar tidak ditemukan. Sinkronisasi ulang akan membuat event baru.',
                default => 'Google Calendar mengembalikan error: ' . $message,
            };
        }

        return 'Sinkronisasi Google Calendar gagal. Detail: ' . $message;
    }

    protected function extractPathFromMessage(string $message): ?string
    {
        if (preg_match('/"([^"\n]+)"/', $message, $matches)) {
            return $matches[1];
        }

        return null;
    }

    protected function normalizeCredentialsPath(string $path): string
    {
        $trimmed = trim($path, '\"\'');

        if (Str::startsWith($trimmed, ['/', '\\']) || preg_match('/^[A-Za-z]:\\\\/', $trimmed)) {
            return $trimmed;
        }

        return base_path($trimmed);
    }
}
