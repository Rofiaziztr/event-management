<?php

namespace App\Observers;

use App\Models\EventParticipant;
use App\Services\GoogleCalendarService;
use Illuminate\Support\Facades\Log;

class EventParticipantObserver
{
    protected $calendarService;

    public function __construct(GoogleCalendarService $calendarService)
    {
        $this->calendarService = $calendarService;
    }

    /**
     * Handle the EventParticipant "created" event.
     */
    public function created(EventParticipant $eventParticipant): void
    {
        $user = $eventParticipant->user;
        $event = $eventParticipant->event;

        // Only sync if user has Google Calendar access
        if ($user->hasGoogleCalendarAccess()) {
            try {
                Log::info('EventParticipantObserver: Dispatching background sync job', [
                    'event_id' => $event->id,
                    'user_id' => $user->id,
                    'event_title' => $event->title,
                    'user_name' => $user->full_name
                ]);

                // Dispatch background job instead of immediate sync
                \App\Jobs\SyncCalendarJob::dispatch($event, $user, 'auto');
            } catch (\Exception $e) {
                Log::error('EventParticipantObserver: Exception during job dispatch', [
                    'event_id' => $event->id,
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
            }
        } else {
            Log::info('EventParticipantObserver: Skipping auto-sync - user does not have Google Calendar access', [
                'event_id' => $event->id,
                'user_id' => $user->id,
                'user_name' => $user->full_name
            ]);
        }
    }

    /**
     * Handle the EventParticipant "updated" event.
     */
    public function updated(EventParticipant $eventParticipant): void
    {
        //
    }

    /**
     * Handle the EventParticipant "deleting" event (BEFORE deletion).
     * FIX: Use deleting() instead of deleted() to access relationships before they're deleted
     */
    public function deleting(EventParticipant $eventParticipant): void
    {
        // Remove event from participant's calendar when they are removed
        // Use $eventParticipant->event and $eventParticipant->user which are still available here
        if (!$eventParticipant->event || !$eventParticipant->user) {
            Log::warning('EventParticipantObserver: Cannot remove from calendar - relationships not available', [
                'event_id' => $eventParticipant->event_id,
                'user_id' => $eventParticipant->user_id,
                'event_exists' => $eventParticipant->event ? 'yes' : 'no',
                'user_exists' => $eventParticipant->user ? 'yes' : 'no'
            ]);
            return;
        }

        Log::info('EventParticipantObserver: Removing event from participant calendar', [
            'event_id' => $eventParticipant->event_id,
            'user_id' => $eventParticipant->user_id,
            'event_title' => $eventParticipant->event->title,
            'user_name' => $eventParticipant->user->full_name
        ]);

        try {
            $this->calendarService->removeEventFromUserCalendar($eventParticipant->event, $eventParticipant->user);

            Log::info('EventParticipantObserver: Successfully removed event from participant calendar', [
                'event_id' => $eventParticipant->event_id,
                'user_id' => $eventParticipant->user_id
            ]);
        } catch (\Exception $e) {
            Log::error('EventParticipantObserver: Error removing event from calendar', [
                'event_id' => $eventParticipant->event_id,
                'user_id' => $eventParticipant->user_id,
                'error' => $e->getMessage()
            ]);
            // Don't re-throw - let the deletion proceed even if calendar removal fails
        }
    }

    /**
     * Handle the EventParticipant "deleted" event (AFTER deletion).
     * Kept for backward compatibility, but main work now done in deleting()
     */
    public function deleted(EventParticipant $eventParticipant): void
    {
        // Main work is done in deleting() hook
        // This is kept as fallback/logging only
    }

    /**
     * Handle the EventParticipant "restored" event.
     */
    public function restored(EventParticipant $eventParticipant): void
    {
        // Re-sync event to participant's calendar when they are restored
        $this->calendarService->syncEventToUserCalendar($eventParticipant->event, $eventParticipant->user);
    }

    /**
     * Handle the EventParticipant "force deleted" event.
     */
    public function forceDeleted(EventParticipant $eventParticipant): void
    {
        // Same as deleted for force delete
        $this->deleted($eventParticipant);
    }
}
