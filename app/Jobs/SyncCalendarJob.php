<?php

namespace App\Jobs;

use App\Models\Event;
use App\Models\User;
use App\Services\GoogleCalendarService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SyncCalendarJob implements ShouldQueue
{
    use Queueable;

    public $tries = 3;
    public $backoff = [10, 30, 60]; // Retry delays in seconds

    protected $event;
    protected $users;
    protected $syncType; // 'bulk' or 'individual'

    /**
     * Create a new job instance.
     *
     * @param Event $event
     * @param User[]|User $users - Array of users for bulk sync, or single user for individual sync
     * @param string $syncType - 'bulk' or 'individual'
     */
    public function __construct(Event $event, $users, string $syncType = 'bulk')
    {
        $this->event = $event;
        // Ensure users is always an array of User objects
        if ($users instanceof \Illuminate\Database\Eloquent\Collection) {
            $this->users = $users->all();
        } elseif (is_array($users)) {
            $this->users = $users;
        } else {
            $this->users = [$users];
        }
        $this->syncType = $syncType;
    }

    /**
     * Execute the job.
     */
    public function handle(GoogleCalendarService $calendarService): void
    {
        Log::info("SyncCalendarJob: Starting {$this->syncType} calendar sync", [
            'event_id' => $this->event->id,
            'event_title' => $this->event->title,
            'user_count' => count($this->users),
            'sync_type' => $this->syncType
        ]);

        $successfulSyncs = 0;
        $failedSyncs = 0;
        $connectedUsers = 0;

        // Phase 1: Sync event to all participants
        foreach ($this->users as $user) {
            if (!$user->hasValidGoogleCalendarAccess()) {
                Log::info("SyncCalendarJob: Skipping user without calendar access", [
                    'user_id' => $user->id,
                    'user_name' => $user->full_name
                ]);
                continue;
            }

            $connectedUsers++;

            try {
                Log::info("SyncCalendarJob: Syncing calendar for user", [
                    'event_id' => $this->event->id,
                    'user_id' => $user->id,
                    'user_name' => $user->full_name
                ]);

                $result = $calendarService->syncEventToUserCalendar($this->event, $user);

                if ($result) {
                    $successfulSyncs++;
                    Log::info("SyncCalendarJob: Successfully synced calendar for user", [
                        'event_id' => $this->event->id,
                        'user_id' => $user->id
                    ]);
                } else {
                    $failedSyncs++;
                    Log::warning("SyncCalendarJob: Failed to sync calendar for user", [
                        'event_id' => $this->event->id,
                        'user_id' => $user->id
                    ]);
                }
            } catch (\Exception $e) {
                $failedSyncs++;
                Log::error("SyncCalendarJob: Exception while syncing calendar for user", [
                    'event_id' => $this->event->id,
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
            }
        }

        // Phase 2: If full_refresh mode, cleanup orphaned events for ALL users with Google access
        if ($this->syncType === 'full_refresh') {
            Log::info("SyncCalendarJob: Starting orphaned event cleanup phase", [
                'event_id' => $this->event->id
            ]);

            $allUsersWithGoogleAccess = User::whereNotNull('google_access_token')->get();
            $cleanupCount = 0;

            foreach ($allUsersWithGoogleAccess as $user) {
                try {
                    Log::info("SyncCalendarJob: Cleaning orphaned events for user", [
                        'event_id' => $this->event->id,
                        'user_id' => $user->id
                    ]);

                    if ($calendarService->cleanupOrphanedEvents($user)) {
                        $cleanupCount++;
                        Log::info("SyncCalendarJob: Successfully cleaned orphaned events for user", [
                            'user_id' => $user->id
                        ]);
                    }
                } catch (\Exception $e) {
                    Log::warning("SyncCalendarJob: Error cleaning orphaned events for user", [
                        'user_id' => $user->id,
                        'error' => $e->getMessage()
                    ]);
                    // Continue with other users
                }
            }

            Log::info("SyncCalendarJob: Orphaned event cleanup phase completed", [
                'event_id' => $this->event->id,
                'users_cleaned' => $cleanupCount
            ]);
        }

        Log::info("SyncCalendarJob: Completed {$this->syncType} calendar sync", [
            'event_id' => $this->event->id,
            'total_users' => count($this->users),
            'connected_users' => $connectedUsers,
            'successful_syncs' => $successfulSyncs,
            'failed_syncs' => $failedSyncs
        ]);
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::error("SyncCalendarJob: Job failed completely", [
            'event_id' => $this->event->id,
            'sync_type' => $this->syncType,
            'error' => $exception->getMessage()
        ]);
    }
}
