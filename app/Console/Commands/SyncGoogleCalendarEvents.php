<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Services\Calendar\GoogleCalendarSyncService;
use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'events:sync-google')]
class SyncGoogleCalendarEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:sync-google {event? : Event ID atau kode event} {--delete : Hapus event dari Google Calendar} {--only-pending : Hanya sinkronkan event yang belum pernah berhasil sinkron}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sinkronisasi manual data event dengan Google Calendar.';

    /**
     * Execute the console command.
     */
    public function handle(GoogleCalendarSyncService $syncService)
    {
        $identifier = $this->argument('event');

        if ($identifier) {
            $event = $this->findEvent($identifier);

            if (! $event) {
                $this->error("Event dengan ID/Kode {$identifier} tidak ditemukan.");

                return self::FAILURE;
            }

            $this->processEvent($event, $syncService);

            return self::SUCCESS;
        }

    $query = Event::query();

        if ($this->option('only-pending')) {
            $query->whereIn('google_calendar_sync_status', ['never', 'failed', null]);
        }

        $count = $query->count();

        if ($count === 0) {
            $this->info('Tidak ada event yang perlu disinkronkan.');

            return self::SUCCESS;
        }

        $this->info("Menjalankan sinkronisasi Google Calendar untuk {$count} event...");
        $this->output->progressStart($count);

        $query->chunk(50, function ($events) use ($syncService) {
            foreach ($events as $event) {
                $this->processEvent($event, $syncService);
                $this->output->progressAdvance();
            }
        });

        $this->output->progressFinish();

        $this->info('Sinkronisasi selesai.');

        return self::SUCCESS;
    }

    protected function processEvent(Event $event, GoogleCalendarSyncService $syncService): void
    {
        if ($this->option('delete')) {
            $result = $syncService->delete($event);
            $event->refresh();

            if ($result && $event->google_calendar_sync_status === 'deleted') {
                $this->line("[HAPUS] {$event->title} ({$event->id})");

                return;
            }

            $this->warn(sprintf('[GAGAL HAPUS] %s (%s): %s',
                $event->title,
                $event->id,
                $event->google_calendar_last_error ?? 'Tidak ada detail kesalahan'
            ));

            return;
        }

        $result = $syncService->sync($event);
        $event->refresh();

        if ($result && $event->google_calendar_sync_status === 'synced') {
            $this->line("[SYNC] {$event->title} ({$event->id})");

            return;
        }

        $this->warn(sprintf('[GAGAL SYNC] %s (%s): %s',
            $event->title,
            $event->id,
            $event->google_calendar_last_error ?? 'Tidak ada detail kesalahan'
        ));
    }

    protected function findEvent(string $identifier): ?Event
    {
        return Event::query()
            ->where('id', $identifier)
            ->orWhere('code', $identifier)
            ->first();
    }
}
