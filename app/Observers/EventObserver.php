<?php

namespace App\Observers;

use App\Models\Event;
use App\Services\GoogleCalendarService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class EventObserver
{
    protected $calendarService;

    public function __construct(GoogleCalendarService $calendarService)
    {
        $this->calendarService = $calendarService;
    }

    /**
     * Handle the Event "created" event.
     */
    public function creating(Event $event): void
    {
        // Buat kode unik yang belum pernah ada di tabel
        do {
            $code = 'EVT-' . strtoupper(Str::random(6));
        } while (Event::where('code', $code)->exists());

        $event->code = $code;
    }

    /**
     * Handle the Event "created" event.
     */
    public function created(Event $event): void
    {
        // Sync to all participants' calendars
        $this->calendarService->syncEventToAllParticipants($event);
    }

    /**
     * Handle the Event "updated" event.
     */
    public function updated(Event $event): void
    {
        // Re-sync to all participants' calendars
        $this->calendarService->syncEventToAllParticipants($event);
    }

    /**
     * Handle the Event "deleting" event.
     */
    public function deleting(Event $event): void
    {
        Log::info('EventObserver: Event deleting, removing from calendars', [
            'event_id' => $event->id,
            'title' => $event->title
        ]);

        // Remove from all calendars BEFORE the event is deleted from database
        $this->calendarService->removeEventFromAllCalendars($event);

        Log::info('EventObserver: Finished removing event from calendars', [
            'event_id' => $event->id
        ]);
    }
}
