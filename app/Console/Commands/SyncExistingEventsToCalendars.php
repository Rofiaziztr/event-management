<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\User;
use App\Services\GoogleCalendarService;
use Illuminate\Console\Command;

class SyncExistingEventsToCalendars extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:sync-existing-events-to-calendars {--user= : Sync only for specific user ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync existing events to Google Calendars of authorized users';

    protected $calendarService;

    public function __construct(GoogleCalendarService $calendarService)
    {
        parent::__construct();
        $this->calendarService = $calendarService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->option('user');

        if ($userId) {
            $user = User::find($userId);
            if (!$user) {
                $this->error("User with ID {$userId} not found.");
                return 1;
            }

            if (!$user->hasGoogleCalendarAccess()) {
                $this->warn("User {$user->full_name} does not have Google Calendar access.");
                return 1;
            }

            $this->syncEventsForUser($user);
        } else {
            $users = User::whereNotNull('google_access_token')->get();

            if ($users->isEmpty()) {
                $this->info('No users with Google Calendar access found.');
                return 0;
            }

            $this->info("Found {$users->count()} users with Google Calendar access.");

            foreach ($users as $user) {
                $this->syncEventsForUser($user);
            }
        }

        $this->info('Sync completed.');
        return 0;
    }

    protected function syncEventsForUser(User $user)
    {
        $this->info("Syncing events for user: {$user->full_name} ({$user->email})");

        $events = Event::with('participants')->get();
        $synced = 0;
        $failed = 0;

        foreach ($events as $event) {
            // Check if user is participant or creator
            $isParticipant = $event->participants->contains($user);
            $isCreator = $event->creator_id === $user->id;

            if ($isParticipant || $isCreator) {
                $success = $this->calendarService->syncEventToUserCalendar($event, $user);
                if ($success) {
                    $synced++;
                } else {
                    $failed++;
                }
            }
        }

        $this->info("Synced: {$synced} events, Failed: {$failed} events");
    }
}
