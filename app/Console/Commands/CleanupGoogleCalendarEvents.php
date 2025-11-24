<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\GoogleCalendarCleanupService;

class CleanupGoogleCalendarEvents extends Command
{
    protected $signature = 'google-calendar:cleanup
                            {--user-id= : Cleanup untuk user tertentu}
                            {--all : Cleanup semua users}
                            {--verify-only : Hanya verify tanpa cleanup}
                            {--report : Tampilkan report saja}';

    protected $description = 'Bersihkan orphaned Google Calendar events dan repair sync inconsistencies';

    protected GoogleCalendarCleanupService $cleanupService;

    public function __construct(GoogleCalendarCleanupService $cleanupService)
    {
        parent::__construct();
        $this->cleanupService = $cleanupService;
    }

    public function handle()
    {
        $this->info('🔍 Google Calendar Cleanup Service');
        $this->line('');

        if ($this->option('report')) {
            return $this->showReport();
        }

        if ($this->option('verify-only')) {
            return $this->verifyOnly();
        }

        if ($this->option('user-id')) {
            return $this->cleanupUser((int) $this->option('user-id'));
        }

        if ($this->option('all')) {
            return $this->cleanupAll();
        }

        // Show menu
        $this->showMenu();
    }

    private function showMenu()
    {
        $this->line('Pilih action:');
        $this->line('1. Cleanup orphaned events untuk user tertentu');
        $this->line('2. Cleanup semua users');
        $this->line('3. Verify sync integrity');
        $this->line('4. Show report');
        $this->line('5. Repair syncs untuk user');

        $choice = $this->ask('Pilih (1-5)');

        match ($choice) {
            '1' => $this->promptAndCleanupUser(),
            '2' => $this->cleanupAll(),
            '3' => $this->verifyOnly(),
            '4' => $this->showReport(),
            '5' => $this->promptAndRepairUser(),
            default => $this->error('Invalid choice')
        };
    }

    private function promptAndCleanupUser()
    {
        $userId = $this->ask('Masukkan User ID');
        $this->cleanupUser((int) $userId);
    }

    private function promptAndRepairUser()
    {
        $userId = $this->ask('Masukkan User ID');
        $this->repairUser((int) $userId);
    }

    private function cleanupUser(int $userId)
    {
        $user = User::find($userId);

        if (!$user) {
            $this->error("User dengan ID $userId tidak ditemukan");
            return 1;
        }

        $this->info("🧹 Membersihkan orphaned events untuk user: {$user->email}");
        $this->line('');

        $results = $this->cleanupService->cleanupUserOrphanedEvents($user);

        $this->displayResults($results, $user);

        return 0;
    }

    private function cleanupAll()
    {
        $this->info('🧹 Membersihkan orphaned events untuk semua users');
        $this->line('');

        $results = $this->cleanupService->cleanupAllUsersOrphanedEvents();

        $this->displayAllResults($results);

        return 0;
    }

    private function verifyOnly()
    {
        $userId = $this->option('user-id');

        if ($userId) {
            $this->verifyUser((int) $userId);
        } else {
            $this->info('📋 Verifying sync integrity untuk semua users');
            $this->line('');

            $report = $this->cleanupService->getFullReport();

            foreach ($report['users'] as $userReport) {
                $this->displayUserIntegrity($userReport);
            }
        }

        return 0;
    }

    private function verifyUser(int $userId)
    {
        $user = User::find($userId);

        if (!$user) {
            $this->error("User dengan ID $userId tidak ditemukan");
            return 1;
        }

        $this->info("📋 Verifying sync integrity untuk: {$user->email}");
        $this->line('');

        $integrity = $this->cleanupService->verifyUserSyncIntegrity($user);

        $this->displayIntegrityResults($integrity);

        return 0;
    }

    private function repairUser(int $userId)
    {
        $user = User::find($userId);

        if (!$user) {
            $this->error("User dengan ID $userId tidak ditemukan");
            return 1;
        }

        $this->info("🔧 Repairing syncs untuk: {$user->email}");
        $this->line('');

        $results = $this->cleanupService->repairUserSyncs($user);

        $this->line("✅ Removed invalid syncs: {$results['removed_invalid_syncs']}");

        if (!empty($results['errors'])) {
            $this->line('');
            $this->error('⚠️  Errors:');
            foreach ($results['errors'] as $error) {
                $this->line("  - $error");
            }
        }

        return 0;
    }

    private function showReport()
    {
        $this->info('📊 Google Calendar Cleanup Report');
        $this->line('');

        $report = $this->cleanupService->getFullReport();

        $this->line("Timestamp: {$report['timestamp']}");
        $this->line("Total Users with Access: {$report['total_users_with_access']}");
        $this->line("Total Events: {$report['total_events']}");
        $this->line("Total Sync Records: {$report['total_sync_records']}");
        $this->line('');

        $this->table(
            ['User', 'Email', 'Total Syncs', 'Valid', 'Broken'],
            array_map(function ($user) {
                $integrity = $user['sync_integrity'];
                return [
                    $user['name'],
                    $user['email'],
                    $integrity['total_syncs'],
                    $integrity['valid_syncs'],
                    $integrity['broken_syncs']
                ];
            }, $report['users'])
        );

        return 0;
    }

    private function displayResults(array $results, User $user)
    {
        $this->table(
            ['Metric', 'Value'],
            [
                ['User', $user->email],
                ['Orphaned Found', $results['orphaned_found']],
                ['Orphaned Deleted', $results['orphaned_deleted']],
                ['Status', $results['orphaned_deleted'] > 0 ? '✅ Cleaned' : '✅ No orphaned events'],
            ]
        );

        if (!empty($results['errors'])) {
            $this->line('');
            $this->error('⚠️  Errors:');
            foreach ($results['errors'] as $error) {
                $this->line("  - $error");
            }
        }
    }

    private function displayAllResults(array $results)
    {
        $this->table(
            ['Metric', 'Value'],
            [
                ['Users Processed', $results['users_processed']],
                ['Total Orphaned Found', $results['total_orphaned_found']],
                ['Total Orphaned Deleted', $results['total_orphaned_deleted']],
            ]
        );

        if (!empty($results['user_results'])) {
            $this->line('');
            $this->info('📝 User Details:');

            foreach ($results['user_results'] as $userId => $userResult) {
                $this->line('');
                $this->line("👤 {$userResult['name']} ({$userResult['email']})");
                $this->line("   Orphaned Found: {$userResult['results']['orphaned_found']}");
                $this->line("   Orphaned Deleted: {$userResult['results']['orphaned_deleted']}");

                if (!empty($userResult['results']['errors'])) {
                    foreach ($userResult['results']['errors'] as $error) {
                        $this->line("   ⚠️  $error");
                    }
                }
            }
        }

        if (!empty($results['errors'])) {
            $this->line('');
            $this->error('⚠️  General Errors:');
            foreach ($results['errors'] as $error) {
                $this->line("  - $error");
            }
        }
    }

    private function displayIntegrityResults(array $integrity)
    {
        $this->table(
            ['Metric', 'Value'],
            [
                ['Total Syncs', $integrity['total_syncs']],
                ['Valid Syncs', $integrity['valid_syncs']],
                ['Broken Syncs', $integrity['broken_syncs']],
            ]
        );

        if (!empty($integrity['missing_events'])) {
            $this->line('');
            $this->error('❌ Syncs referring to deleted events:');
            foreach ($integrity['missing_events'] as $missing) {
                $this->line("   Sync ID: {$missing['sync_id']}, Event ID: {$missing['event_id']}");
            }
        }

        if (!empty($integrity['missing_google_events'])) {
            $this->line('');
            $this->error('❌ Syncs referring to deleted Google events:');
            foreach ($integrity['missing_google_events'] as $missing) {
                $this->line("   Sync ID: {$missing['sync_id']}, Google Event ID: {$missing['google_event_id']}");
            }
        }
    }

    private function displayUserIntegrity(array $userReport)
    {
        $integrity = $userReport['sync_integrity'];

        $this->line("{$userReport['name']} ({$userReport['email']})");
        $this->line("  Total Syncs: {$integrity['total_syncs']}");
        $this->line("  Valid Syncs: {$integrity['valid_syncs']}");
        $this->line("  Broken Syncs: {$integrity['broken_syncs']}");

        if (!empty($integrity['missing_events'])) {
            $this->line("  ❌ Missing Events: " . count($integrity['missing_events']));
        }

        if (!empty($integrity['missing_google_events'])) {
            $this->line("  ❌ Missing Google Events: " . count($integrity['missing_google_events']));
        }

        $this->line('');
    }
}
