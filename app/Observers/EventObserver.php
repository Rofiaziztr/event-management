<?php

namespace App\Observers;

use App\Models\Event;
use App\Services\Calendar\GoogleCalendarSyncService;
use Illuminate\Support\Str;

class EventObserver
{
    public function __construct(protected GoogleCalendarSyncService $calendarSync)
    {
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

    public function created(Event $event): void
    {
        $this->calendarSync->sync($event);
    }

    /**
     * Handle the Event "updated" event.
     */
    public function updated(Event $event): void
    {
        if ($event->status === 'Dibatalkan') {
            $this->calendarSync->delete($event);

            return;
        }

        if ($this->shouldSync($event)) {
            $this->calendarSync->sync($event);
        }
    }

    /**
     * Handle the Event "deleted" event.
     */
    public function deleted(Event $event): void
    {
        $this->calendarSync->delete($event);
    }

    /**
     * Handle the Event "restored" event.
     */
    public function restored(Event $event): void
    {
        //
    }

    /**
     * Handle the Event "force deleted" event.
     */
    public function forceDeleted(Event $event): void
    {
        $this->calendarSync->delete($event);
    }

    protected function shouldSync(Event $event): bool
    {
        $dirty = array_keys($event->getChanges());

        $syncable = [
            'title',
            'description',
            'start_time',
            'end_time',
            'location',
            'status',
        ];

        return (bool) array_intersect($dirty, $syncable);
    }
}
