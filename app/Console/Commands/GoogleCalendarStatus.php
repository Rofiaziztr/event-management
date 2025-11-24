<?php

namespace App\Console\Commands;

use App\Models\EventCalendarSync;
use App\Models\User;
use Illuminate\Console\Command;

class GoogleCalendarStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google-calendar:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Quick status report of Google Calendar integration';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('╔════════════════════════════════════════════════════════╗');
        $this->info('║   Google Calendar Integration Status Report            ║');
        $this->info('╚════════════════════════════════════════════════════════╝');

        // System Status
        $this->printSystemStatus();

        // User Status
        $this->printUserStatus();

        // Sync Status
        $this->printSyncStatus();

        // Quick Commands
        $this->printQuickCommands();

        return 0;
    }

    private function printSystemStatus(): void
    {
        $this->info("\n📊 System Status:");

        $syncCount = EventCalendarSync::count();
        $googleUserCount = User::whereNotNull('google_id')->count();
        $tokenCount = User::whereNotNull('google_access_token')->count();

        $this->line("  • Sync Records: $syncCount");
        $this->line("  • Users with Google ID: $googleUserCount");
        $this->line("  • Users with Active Token: $tokenCount");

        // Check health
        $orphanedCount = EventCalendarSync::whereNull('event_id')->orWhereNull('user_id')->count();
        if ($orphanedCount > 0) {
            $this->warn("  • ⚠️  Orphaned Records: $orphanedCount");
        } else {
            $this->line("  • ✅ No Orphaned Records");
        }
    }

    private function printUserStatus(): void
    {
        $this->info("\n👥 User Status:");

        $users = User::whereNotNull('google_id')->get();

        if ($users->isEmpty()) {
            $this->line("  No users with Google Calendar integration");
            return;
        }

        foreach ($users as $user) {
            $email = $user->email;
            $hasToken = $user->google_access_token ? '✅' : '❌';
            $isExpired = $user->google_access_token && $user->isGoogleTokenExpired() ? ' (Expired)' : '';
            $syncs = $user->eventCalendarSyncs()->count();
            $events = $user->participatedEvents()->count();

            $status = "$hasToken $email - Syncs: $syncs | Events: $events$isExpired";

            if ($isExpired) {
                $this->warn("  • $status");
            } else {
                $this->line("  • $status");
            }
        }
    }

    private function printSyncStatus(): void
    {
        $this->info("\n🔄 Sync Status:");

        $syncs = EventCalendarSync::with('event', 'user')->get();

        if ($syncs->isEmpty()) {
            $this->line("  No synced events");
            return;
        }

        foreach ($syncs as $sync) {
            $event = $sync->event?->title ?? 'N/A';
            $user = $sync->user?->email ?? 'N/A';
            $status = $sync->sync_status === 'synced' ? '✅' : '⚠️';

            $this->line("  • $status $event → $user");
        }
    }

    private function printQuickCommands(): void
    {
        $this->info("\n⚡ Quick Commands:");
        $this->line("  • Monitor Health:");
        $this->line("    php artisan google-calendar:monitor");
        $this->line("");
        $this->line("  • Refresh Tokens:");
        $this->line("    php artisan google-calendar:refresh-tokens");
        $this->line("");
        $this->line("  • Cleanup Orphaned Events:");
        $this->line("    php artisan google-calendar:cleanup --all");
        $this->line("");
        $this->line("  • View This Status:");
        $this->line("    php artisan google-calendar:status");
    }
}
