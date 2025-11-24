<?php

namespace App\Services;

use App\Models\Event;
use App\Models\User;
use App\Models\EventCalendarSync;
use Illuminate\Support\Facades\Log;

/**
 * Service untuk membersihkan orphaned events di Google Calendar
 * dan memperbaiki data inconsistencies antara app database dan Google Calendar
 */
class GoogleCalendarCleanupService
{
    protected GoogleCalendarService $calendarService;

    public function __construct(GoogleCalendarService $calendarService)
    {
        $this->calendarService = $calendarService;
    }

    /**
     * Scan dan bersihkan semua orphaned events untuk seorang user
     * Orphaned = event ada di Google Calendar tapi tidak ada di database
     */
    public function cleanupUserOrphanedEvents(User $user): array
    {
        Log::info('GoogleCalendarCleanupService: Starting orphaned events cleanup', [
            'user_id' => $user->id,
            'user_email' => $user->email
        ]);

        $results = [
            'orphaned_found' => 0,
            'orphaned_deleted' => 0,
            'errors' => [],
        ];

        if (!$user->hasGoogleCalendarAccess()) {
            Log::warning('GoogleCalendarCleanupService: User has no Google Calendar access', [
                'user_id' => $user->id
            ]);
            $results['errors'][] = 'User tidak memiliki akses Google Calendar';
            return $results;
        }

        try {
            // Get semua events dari Google Calendar user
            $googleEvents = $this->calendarService->getUserGoogleCalendarEvents($user);

            Log::info('GoogleCalendarCleanupService: Retrieved user events', [
                'user_id' => $user->id,
                'event_count' => count($googleEvents)
            ]);

            foreach ($googleEvents as $googleEvent) {
                $results['orphaned_found']++;

                // Try to find corresponding event in database
                $eventTitle = $googleEvent->getSummary();
                $eventStart = $googleEvent->getStart();
                $googleEventId = $googleEvent->getId();

                // Check if this event exists in our sync records
                $syncRecord = EventCalendarSync::where('google_event_id', $googleEventId)
                    ->where('user_id', $user->id)
                    ->first();

                if ($syncRecord && $syncRecord->event) {
                    // Event exists in our database, skip
                    Log::info('GoogleCalendarCleanupService: Event found in database', [
                        'google_event_id' => $googleEventId,
                        'event_id' => $syncRecord->event_id,
                        'title' => $eventTitle
                    ]);
                    continue;
                }

                // Event tidak ada di database, ini orphaned event
                Log::info('GoogleCalendarCleanupService: Orphaned event found', [
                    'google_event_id' => $googleEventId,
                    'title' => $eventTitle,
                    'start' => $eventStart ? $eventStart->getDateTime() : 'No start time'
                ]);

                // Delete dari Google Calendar
                try {
                    $this->calendarService->deleteGoogleCalendarEvent($user, $googleEventId);
                    $results['orphaned_deleted']++;

                    // Delete sync record if exists
                    if ($syncRecord) {
                        $syncRecord->delete();
                    }

                    Log::info('GoogleCalendarCleanupService: Orphaned event deleted', [
                        'google_event_id' => $googleEventId,
                        'user_id' => $user->id,
                        'title' => $eventTitle
                    ]);
                } catch (\Exception $e) {
                    Log::error('GoogleCalendarCleanupService: Failed to delete orphaned event', [
                        'google_event_id' => $googleEventId,
                        'user_id' => $user->id,
                        'error' => $e->getMessage()
                    ]);
                    $results['errors'][] = "Gagal menghapus event '$eventTitle': " . $e->getMessage();
                }
            }
        } catch (\Exception $e) {
            Log::error('GoogleCalendarCleanupService: Error during cleanup', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
            $results['errors'][] = 'Error saat scan Google Calendar: ' . $e->getMessage();
        }

        Log::info('GoogleCalendarCleanupService: Cleanup completed', [
            'user_id' => $user->id,
            'orphaned_found' => $results['orphaned_found'],
            'orphaned_deleted' => $results['orphaned_deleted']
        ]);

        return $results;
    }

    /**
     * Cleanup semua users yang memiliki Google Calendar access
     */
    public function cleanupAllUsersOrphanedEvents(): array
    {
        Log::info('GoogleCalendarCleanupService: Starting cleanup for all users');

        $results = [
            'users_processed' => 0,
            'total_orphaned_found' => 0,
            'total_orphaned_deleted' => 0,
            'user_results' => [],
            'errors' => [],
        ];

        $usersWithAccess = User::whereNotNull('google_id')
            ->whereNotNull('google_access_token')
            ->get();

        Log::info('GoogleCalendarCleanupService: Found users with access', [
            'user_count' => $usersWithAccess->count()
        ]);

        foreach ($usersWithAccess as $user) {
            try {
                $userResults = $this->cleanupUserOrphanedEvents($user);

                $results['users_processed']++;
                $results['total_orphaned_found'] += $userResults['orphaned_found'];
                $results['total_orphaned_deleted'] += $userResults['orphaned_deleted'];
                $results['user_results'][$user->id] = [
                    'email' => $user->email,
                    'name' => $user->full_name,
                    'results' => $userResults
                ];

                if (!empty($userResults['errors'])) {
                    $results['errors'] = array_merge($results['errors'], $userResults['errors']);
                }
            } catch (\Exception $e) {
                Log::error('GoogleCalendarCleanupService: Error processing user', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage()
                ]);
                $results['errors'][] = "Error untuk user {$user->email}: " . $e->getMessage();
            }
        }

        Log::info('GoogleCalendarCleanupService: All cleanup completed', [
            'users_processed' => $results['users_processed'],
            'total_orphaned_found' => $results['total_orphaned_found'],
            'total_orphaned_deleted' => $results['total_orphaned_deleted']
        ]);

        return $results;
    }

    /**
     * Verify sync integrity untuk seorang user
     * Check apakah semua sync records masih valid
     */
    public function verifyUserSyncIntegrity(User $user): array
    {
        Log::info('GoogleCalendarCleanupService: Verifying sync integrity', [
            'user_id' => $user->id
        ]);

        $results = [
            'total_syncs' => 0,
            'valid_syncs' => 0,
            'broken_syncs' => 0,
            'missing_events' => [],
            'missing_google_events' => [],
        ];

        if (!$user->hasGoogleCalendarAccess()) {
            return $results;
        }

        // Get semua sync records untuk user ini
        $syncs = EventCalendarSync::where('user_id', $user->id)->get();
        $results['total_syncs'] = $syncs->count();

        try {
            $googleEvents = $this->calendarService->getUserGoogleCalendarEvents($user);
            $googleEventIds = array_map(fn($e) => $e->getId(), $googleEvents);

            foreach ($syncs as $sync) {
                // Check if event masih ada di database
                if (!$sync->event) {
                    $results['broken_syncs']++;
                    $results['missing_events'][] = [
                        'sync_id' => $sync->id,
                        'event_id' => $sync->event_id,
                        'google_event_id' => $sync->google_event_id
                    ];
                    Log::warning('GoogleCalendarCleanupService: Sync record refers to deleted event', [
                        'sync_id' => $sync->id,
                        'event_id' => $sync->event_id
                    ]);
                    continue;
                }

                // Check if event masih ada di Google Calendar
                if ($sync->google_event_id && !in_array($sync->google_event_id, $googleEventIds)) {
                    $results['broken_syncs']++;
                    $results['missing_google_events'][] = [
                        'sync_id' => $sync->id,
                        'event_id' => $sync->event_id,
                        'google_event_id' => $sync->google_event_id
                    ];
                    Log::warning('GoogleCalendarCleanupService: Google event not found', [
                        'sync_id' => $sync->id,
                        'google_event_id' => $sync->google_event_id
                    ]);
                    continue;
                }

                $results['valid_syncs']++;
            }
        } catch (\Exception $e) {
            Log::error('GoogleCalendarCleanupService: Error verifying sync integrity', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }

        return $results;
    }

    /**
     * Repair broken sync records untuk seorang user
     */
    public function repairUserSyncs(User $user): array
    {
        Log::info('GoogleCalendarCleanupService: Repairing user syncs', [
            'user_id' => $user->id
        ]);

        $results = [
            'removed_invalid_syncs' => 0,
            'errors' => []
        ];

        // Get verification results
        $integrity = $this->verifyUserSyncIntegrity($user);

        // Remove sync records dengan missing events
        foreach ($integrity['missing_events'] as $missing) {
            try {
                $sync = EventCalendarSync::find($missing['sync_id']);
                if ($sync) {
                    $sync->delete();
                    $results['removed_invalid_syncs']++;
                    Log::info('GoogleCalendarCleanupService: Removed invalid sync record', [
                        'sync_id' => $missing['sync_id'],
                        'event_id' => $missing['event_id']
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('GoogleCalendarCleanupService: Error removing sync', [
                    'sync_id' => $missing['sync_id'],
                    'error' => $e->getMessage()
                ]);
                $results['errors'][] = "Gagal menghapus sync record: " . $e->getMessage();
            }
        }

        // Remove sync records dengan missing Google events
        foreach ($integrity['missing_google_events'] as $missing) {
            try {
                $sync = EventCalendarSync::find($missing['sync_id']);
                if ($sync) {
                    $sync->delete();
                    $results['removed_invalid_syncs']++;
                    Log::info('GoogleCalendarCleanupService: Removed sync with missing Google event', [
                        'sync_id' => $missing['sync_id'],
                        'google_event_id' => $missing['google_event_id']
                    ]);
                }
            } catch (\Exception $e) {
                Log::error('GoogleCalendarCleanupService: Error removing sync', [
                    'sync_id' => $missing['sync_id'],
                    'error' => $e->getMessage()
                ]);
                $results['errors'][] = "Gagal menghapus sync record: " . $e->getMessage();
            }
        }

        Log::info('GoogleCalendarCleanupService: Sync repair completed', [
            'user_id' => $user->id,
            'removed_invalid_syncs' => $results['removed_invalid_syncs']
        ]);

        return $results;
    }

    /**
     * Get full cleanup report
     */
    public function getFullReport(): array
    {
        Log::info('GoogleCalendarCleanupService: Generating full report');

        $report = [
            'timestamp' => now(),
            'total_users_with_access' => 0,
            'total_sync_records' => 0,
            'total_events' => 0,
            'users' => []
        ];

        $users = User::whereNotNull('google_id')
            ->whereNotNull('google_access_token')
            ->get();

        $report['total_users_with_access'] = $users->count();
        $report['total_events'] = Event::count();
        $report['total_sync_records'] = EventCalendarSync::count();

        foreach ($users as $user) {
            $integrity = $this->verifyUserSyncIntegrity($user);

            $report['users'][] = [
                'id' => $user->id,
                'email' => $user->email,
                'name' => $user->full_name,
                'sync_integrity' => $integrity
            ];
        }

        return $report;
    }
}
