<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\GoogleCalendarService;
use Illuminate\Console\Command;

class CleanupOrphanedCalendarEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:cleanup-orphaned-calendar-events {--user_id= : Specific user ID to cleanup}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up orphaned events in Google Calendar that are no longer in sync records';

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
        $userId = $this->option('user_id');

        if ($userId) {
            $user = User::find($userId);
            if (!$user) {
                $this->error("User with ID {$userId} not found.");
                return 1;
            }

            $this->info("Cleaning up orphaned events for user: {$user->full_name} ({$user->email})");
            $result = $this->calendarService->cleanupOrphanedEvents($user);

            if ($result) {
                $this->info("✅ Cleanup completed for user {$user->full_name}");
            } else {
                $this->error("❌ Cleanup failed for user {$user->full_name}");
            }
        } else {
            $users = User::whereNotNull('google_access_token')->get();
            $totalUsers = $users->count();
            $successCount = 0;

            $this->info("Cleaning up orphaned events for {$totalUsers} users with Google Calendar access...");

            $progressBar = $this->output->createProgressBar($totalUsers);
            $progressBar->start();

            foreach ($users as $user) {
                $result = $this->calendarService->cleanupOrphanedEvents($user);
                if ($result) {
                    $successCount++;
                }
                $progressBar->advance();
            }

            $progressBar->finish();
            $this->newLine();

            $this->info("✅ Cleanup completed for {$successCount}/{$totalUsers} users");
        }

        return 0;
    }
}
