<?php

namespace App\Console\Commands;

use App\Models\Event;
use App\Models\User;
use App\Models\EventCalendarSync;
use App\Services\GoogleCalendarSyncVerificationService;
use Illuminate\Console\Command;

class VerifyGoogleCalendarSyncs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google-calendar:verify-syncs 
                            {--user-id= : Verify syncs for specific user ID}
                            {--event-id= : Verify syncs for specific event ID}
                            {--repair : Automatically repair detected issues}
                            {--cleanup : Remove orphaned sync records}
                            {--full : Run full verification and repair}
                            {--stats : Show sync statistics only}';

    /**
     * The description of the console command.
     *
     * @var string
     */
    protected $description = 'Verify and repair Google Calendar sync records for data consistency';

    protected $verificationService;

    /**
     * Execute the console command.
     */
    public function handle(GoogleCalendarSyncVerificationService $verificationService)
    {
        $this->verificationService = $verificationService;

        $this->info('🔍 Google Calendar Sync Verification Tool');
        $this->info('=' . str_repeat('=', 70));

        if ($this->option('stats')) {
            $this->showStatistics();
            return Command::SUCCESS;
        }

        if ($this->option('user-id')) {
            $this->verifyUserSyncs();
            return Command::SUCCESS;
        }

        if ($this->option('event-id')) {
            $this->verifyEventSyncs();
            return Command::SUCCESS;
        }

        if ($this->option('full')) {
            $this->runFullVerification();
            return Command::SUCCESS;
        }

        // Default: Show stats and basic verification
        $this->showStatistics();
        $this->info('');
        $this->line('Tip: Use --repair to fix issues, --full for comprehensive check');

        return Command::SUCCESS;
    }

    private function showStatistics()
    {
        $stats = $this->verificationService->getSyncStatistics();

        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Syncs', $stats['total_syncs']],
                ['Successfully Synced', $stats['synced']],
                ['Failed Syncs', "<fg=red>{$stats['failed']}</>"],
                ['Pending Syncs', "<fg=yellow>{$stats['pending']}</>"],
                ['Success Rate', "{$stats['success_rate']}%"],
                ['Orphaned (Events)', "<fg=red>{$stats['orphaned_events']}</>"],
                ['Orphaned (Users)', "<fg=red>{$stats['orphaned_users']}</>"],
                ['Total Issues', "<fg=red>{$stats['issues_count']}</>"],
            ]
        );
    }

    private function verifyUserSyncs()
    {
        $userId = $this->option('user-id');
        $user = User::find($userId);

        if (!$user) {
            $this->error("❌ User with ID {$userId} not found");
            return;
        }

        $this->info("👤 Verifying syncs for: {$user->full_name} (ID: {$user->id})");
        $this->line('');

        $issues = $this->verificationService->verifyUserSyncs($user);

        if (empty($issues)) {
            $this->info('✅ No issues found!');
            return;
        }

        $this->warn("⚠️  Found " . count($issues) . " issue(s):");
        $this->line('');

        foreach ($issues as $issue) {
            $this->line("  • [" . strtoupper($issue['type']) . "] " . $issue['message']);
        }

        if ($this->option('repair')) {
            $this->info('');
            $this->line('🔧 Attempting to repair...');

            $results = $this->verificationService->repairUserSyncs($user);

            $this->table(
                ['Status', 'Count'],
                [
                    ['Repaired', "<fg=green>{$results['repaired']}</>"],
                    ['Failed', "<fg=red>{$results['failed']}</>"],
                ]
            );

            if (!empty($results['errors'])) {
                $this->error('Errors encountered:');
                foreach ($results['errors'] as $error) {
                    $this->line("  • {$error}");
                }
            }
        }
    }

    private function verifyEventSyncs()
    {
        $eventId = $this->option('event-id');
        $event = Event::find($eventId);

        if (!$event) {
            $this->error("❌ Event with ID {$eventId} not found");
            return;
        }

        $this->info("📅 Verifying syncs for: {$event->title} (ID: {$event->id})");
        $this->line('');

        $issues = $this->verificationService->verifyEventSyncs($event);

        if (empty($issues)) {
            $this->info('✅ No issues found!');
            return;
        }

        $this->warn("⚠️  Found " . count($issues) . " issue(s):");
        $this->line('');

        foreach ($issues as $issue) {
            $this->line("  • [" . strtoupper($issue['type']) . "] " . $issue['message']);
        }

        if ($this->option('repair')) {
            $this->info('');
            $this->line('🔧 Attempting to repair...');

            $results = $this->verificationService->repairEventSyncs($event);

            $this->table(
                ['Status', 'Count'],
                [
                    ['Repaired', "<fg=green>{$results['repaired']}</>"],
                    ['Failed', "<fg=red>{$results['failed']}</>"],
                ]
            );

            if (!empty($results['errors'])) {
                $this->error('Errors encountered:');
                foreach ($results['errors'] as $error) {
                    $this->line("  • {$error}");
                }
            }
        }
    }

    private function runFullVerification()
    {
        $this->info('🔄 Running full verification and repair...');
        $this->line('');

        $results = $this->verificationService->verifyAllSyncs();

        $this->table(
            ['Issue Type', 'Count'],
            [
                ['Orphaned Sync Records (Events)', $results['missing_event_references']],
                ['Orphaned Sync Records (Users)', $results['missing_user_references']],
                ['Failed Syncs', $results['failed_syncs']],
                ['Total Fixed', "<fg=green>{$results['fixed_issues']}</>"],
            ]
        );

        if ($results['fixed_issues'] > 0) {
            $this->info('');
            $this->info("✅ Successfully fixed {$results['fixed_issues']} issue(s)!");
        } else {
            $this->info('');
            $this->info('✅ All syncs verified - no issues found!');
        }
    }
}
