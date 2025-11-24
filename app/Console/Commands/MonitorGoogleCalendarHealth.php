<?php

namespace App\Console\Commands;

use App\Models\EventCalendarSync;
use App\Models\User;
use App\Services\GoogleCalendarService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class MonitorGoogleCalendarHealth extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google-calendar:monitor {--detailed} {--fix-report} {--log}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Monitor Google Calendar sync health and report issues';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $detailed = $this->option('detailed');
        $fixReport = $this->option('fix-report');
        $shouldLog = $this->option('log');

        $this->info('=== Google Calendar Health Monitor ===');

        // 1. Database Health Check
        $this->checkDatabaseHealth();

        // 2. Token Health Check
        $this->checkTokenHealth();

        // 3. Sync Consistency Check
        $this->checkSyncConsistency();

        // 4. Orphaned Event Detection
        $orphanedInfo = $this->checkOrphanedEvents();

        // 5. Detailed Analysis (optional)
        if ($detailed) {
            $this->analyzeDetailed();
        }

        // 6. Fix Report (optional)
        if ($fixReport) {
            $this->generateFixReport($orphanedInfo);
        }

        // 7. Log Results (optional)
        if ($shouldLog) {
            $this->logResults($orphanedInfo);
        }

        return 0;
    }

    private function checkDatabaseHealth(): void
    {
        $this->info("\n📊 Database Health Check:");

        $syncsCount = EventCalendarSync::count();
        $usersWithGoogleCount = User::whereNotNull('google_id')->count();
        $usersWithTokenCount = User::whereNotNull('google_access_token')->count();

        $this->line("  Total Sync Records: $syncsCount");
        $this->line("  Users with Google ID: $usersWithGoogleCount");
        $this->line("  Users with Access Token: $usersWithTokenCount");

        // Check for orphaned sync records
        $orphanedSyncs = EventCalendarSync::whereNull('event_id')->orWhereNull('user_id')->count();
        if ($orphanedSyncs > 0) {
            $this->warn("  ⚠️  Orphaned Sync Records: $orphanedSyncs");
        } else {
            $this->line("  ✅ No orphaned sync records");
        }

        // Check for syncs where user is not a participant
        $invalidSyncs = EventCalendarSync::whereDoesntHave('user', function ($query) {
            $query->whereHas('participatedEvents');
        })->count();

        if ($invalidSyncs > 0) {
            $this->warn("  ⚠️  Syncs for Non-Participants: $invalidSyncs");
        } else {
            $this->line("  ✅ All syncs have valid user-event relationships");
        }
    }

    private function checkTokenHealth(): void
    {
        $this->info("\n🔐 Token Health Check:");

        $usersWithToken = User::whereNotNull('google_access_token')->get();

        if ($usersWithToken->isEmpty()) {
            $this->line("  No users with Google Calendar access");
            return;
        }

        $expired = 0;
        $expiringSoon = 0;
        $valid = 0;

        foreach ($usersWithToken as $user) {
            if ($user->isGoogleTokenExpired()) {
                $expired++;
                $this->warn("  ❌ {$user->email} - Token expired");
            } elseif ($user->google_token_expires_at && $user->google_token_expires_at->diffInMinutes() < 60) {
                $expiringSoon++;
                $this->warn("  ⚠️  {$user->email} - Token expires soon");
            } else {
                $valid++;
            }
        }

        $this->line("  Valid Tokens: $valid");
        $this->line("  Expiring Soon (< 1 hour): $expiringSoon");
        $this->line("  Expired: $expired");
    }

    private function checkSyncConsistency(): void
    {
        $this->info("\n🔄 Sync Consistency Check:");

        $syncs = EventCalendarSync::with('event', 'user')->get();

        if ($syncs->isEmpty()) {
            $this->line("  No sync records to verify");
            return;
        }

        $valid = 0;
        $invalid = 0;
        $issues = [];

        foreach ($syncs as $sync) {
            $syncValid = true;

            // Check if event exists
            if (!$sync->event) {
                $issues[] = "Sync $sync->id: Event deleted but sync remains";
                $invalid++;
                $syncValid = false;
            }

            // Check if user exists
            if (!$sync->user) {
                $issues[] = "Sync $sync->id: User deleted but sync remains";
                $invalid++;
                $syncValid = false;
            }

            // Check if user is participant
            if ($sync->user && $sync->event && !$sync->user->participatedEvents()->where('event_id', $sync->event_id)->exists()) {
                $issues[] = "Sync $sync->id: User not participant of event";
                $invalid++;
                $syncValid = false;
            }

            if ($syncValid) {
                $valid++;
            }
        }

        $this->line("  Valid Syncs: $valid");
        if ($invalid > 0) {
            $this->warn("  Invalid Syncs: $invalid");
        }

        if (!empty($issues)) {
            foreach ($issues as $issue) {
                $this->warn("    • $issue");
            }
        }
    }

    private function checkOrphanedEvents(): array
    {
        $this->info("\n👻 Orphaned Events Check:");

        $googleCalendarService = app(GoogleCalendarService::class);
        $orphanedInfo = [
            'total_scanned' => 0,
            'total_google_events' => 0,
            'orphaned_detected' => 0,
            'issues' => []
        ];

        $usersWithToken = User::whereNotNull('google_access_token')->get();

        if ($usersWithToken->isEmpty()) {
            $this->line("  No users with Google Calendar access to scan");
            return $orphanedInfo;
        }

        foreach ($usersWithToken as $user) {
            $this->line("  Checking {$user->email}...");

            try {
                // Get all Google Calendar events
                $googleEvents = $googleCalendarService->getUserGoogleCalendarEvents($user);
                $orphanedInfo['total_scanned']++;
                $eventCount = count($googleEvents);
                $orphanedInfo['total_google_events'] += $eventCount;

                // Get all sync records for this user
                $syncedEventIds = $user->eventCalendarSyncs()
                    ->pluck('google_event_id')
                    ->toArray();

                // Find orphaned events
                $orphaned = array_filter($googleEvents, function ($event) use ($syncedEventIds) {
                    return !in_array($event['id'], $syncedEventIds);
                });

                $orphanedCount = count($orphaned);
                if ($orphanedCount > 0) {
                    $orphanedInfo['orphaned_detected'] += $orphanedCount;
                    $this->warn("    ⚠️  Found $orphanedCount orphaned events");

                    // Log orphaned event details
                    foreach ($orphaned as $event) {
                        $orphanedInfo['issues'][] = [
                            'user_id' => $user->id,
                            'user_email' => $user->email,
                            'google_event_id' => $event['id'],
                            'event_summary' => $event['summary'] ?? 'Unknown',
                            'event_start' => $event['start']['dateTime'] ?? $event['start']['date'] ?? null,
                        ];
                    }
                } else {
                    $this->line("    ✅ No orphaned events ($eventCount total)");
                }
            } catch (\Exception $e) {
                $this->error("    ❌ Error scanning {$user->email}: {$e->getMessage()}");
                $orphanedInfo['issues'][] = [
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'error' => $e->getMessage()
                ];
            }
        }

        return $orphanedInfo;
    }

    private function analyzeDetailed(): void
    {
        $this->info("\n📋 Detailed Analysis:");

        $syncs = EventCalendarSync::with('event', 'user')->get();

        $this->table(
            ['ID', 'User', 'Event', 'Google Event ID', 'Status', 'Created'],
            $syncs->map(function ($sync) {
                return [
                    $sync->id,
                    $sync->user?->email ?? 'N/A',
                    $sync->event?->title ?? 'N/A',
                    $sync->google_event_id,
                    $sync->sync_status,
                    $sync->created_at->format('Y-m-d H:i'),
                ];
            })->toArray()
        );
    }

    private function generateFixReport(array $orphanedInfo): void
    {
        if ($orphanedInfo['orphaned_detected'] === 0) {
            $this->line("\n✅ No fixes needed - system is healthy");
            return;
        }

        $this->warn("\n⚠️  Fix Report:");
        $this->line("  Orphaned events detected: " . $orphanedInfo['orphaned_detected']);
        $this->line("\n  Recommended actions:");
        $this->line("  1. Run: php artisan google-calendar:cleanup --all");
        $this->line("  2. Verify: php artisan google-calendar:monitor");
        $this->line("  3. Log: php artisan google-calendar:monitor --log");

        if (!empty($orphanedInfo['issues'])) {
            $this->line("\n  Issues:");
            foreach ($orphanedInfo['issues'] as $issue) {
                if (isset($issue['error'])) {
                    $this->warn("    • {$issue['user_email']}: {$issue['error']}");
                } else {
                    $this->warn("    • {$issue['user_email']}: Orphaned event '{$issue['event_summary']}'");
                }
            }
        }
    }

    private function logResults(array $orphanedInfo): void
    {
        Log::info('Google Calendar Health Monitor Results', [
            'timestamp' => now()->toIso8601String(),
            'total_scanned' => $orphanedInfo['total_scanned'],
            'total_google_events' => $orphanedInfo['total_google_events'],
            'orphaned_detected' => $orphanedInfo['orphaned_detected'],
            'issues_count' => count($orphanedInfo['issues']),
            'issues' => $orphanedInfo['issues'],
        ]);

        $this->info("\n✅ Results logged to storage/logs/laravel.log");
    }
}
