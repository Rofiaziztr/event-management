<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\GoogleCalendarService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class RefreshExpiredGoogleTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google-calendar:refresh-tokens {--user-id=} {--force}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Refresh expired Google Calendar tokens for all users or specific user';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $googleCalendarService = app(GoogleCalendarService::class);

        $userId = $this->option('user-id');
        $force = $this->option('force');

        if ($userId) {
            // Refresh specific user
            $user = User::find($userId);
            if (!$user) {
                $this->error("User #{$userId} not found");
                return 1;
            }

            if (!$user->google_access_token) {
                $this->warn("User {$user->email} does not have Google Calendar access");
                return 0;
            }

            $this->info("Refreshing token for user: {$user->email}");
            $result = $googleCalendarService->refreshUserToken($user);

            if ($result) {
                $this->info("✅ Token refreshed successfully");
                return 0;
            } else {
                $this->error("❌ Failed to refresh token");
                return 1;
            }
        }

        // Refresh all users
        $users = User::whereNotNull('google_access_token')->get();

        if ($users->isEmpty()) {
            $this->info("No users with Google Calendar access found");
            return 0;
        }

        $this->info("Found {$users->count()} users with Google Calendar access");

        $refreshed = 0;
        $failed = 0;
        $skipped = 0;

        foreach ($users as $user) {
            try {
                // Skip if token not expired (unless --force)
                if (!$force && !$user->isGoogleTokenExpired()) {
                    $expiresAt = $user->google_token_expires_at;
                    $this->line("⏭️  {$user->email} - Token expires at {$expiresAt}, skipping");
                    $skipped++;
                    continue;
                }

                $this->line("🔄 Refreshing {$user->email}...");

                if ($googleCalendarService->refreshUserToken($user)) {
                    $this->line("✅ {$user->email} - Token refreshed successfully");
                    $refreshed++;
                } else {
                    $this->error("❌ {$user->email} - Failed to refresh token");
                    $failed++;
                }
            } catch (\Exception $e) {
                $this->error("❌ {$user->email} - Error: {$e->getMessage()}");
                $failed++;
            }
        }

        $this->info("\n=== Summary ===");
        $this->line("Refreshed: {$refreshed}");
        $this->line("Failed: {$failed}");
        $this->line("Skipped: {$skipped}");

        return $failed > 0 ? 1 : 0;
    }
}
