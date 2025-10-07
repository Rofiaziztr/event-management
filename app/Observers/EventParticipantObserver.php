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
     * Handle the EventParticipant "deleted" event.
     */
    public function deleted(EventParticipant $eventParticipant): void
    {
        // Remove event from participant's calendar when they are removed
        Log::info('EventParticipantObserver: Removing event from participant calendar', [
            'event_id' => $eventParticipant->event_id,
            'user_id' => $eventParticipant->user_id,
            'event_title' => $eventParticipant->event->title ?? 'Unknown'
        ]);

        $this->calendarService->removeEventFromUserCalendar($eventParticipant->event, $eventParticipant->user);

        Log::info('EventParticipantObserver: Finished removing event from participant calendar', [
            'event_id' => $eventParticipant->event_id,
            'user_id' => $eventParticipant->user_id
        ]);
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
