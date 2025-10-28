<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Services\GoogleCalendarService;

class TestGoogleCalendarValidation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:google-calendar-validation {user_id?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test Google Calendar access validation functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->argument('user_id');

        if ($userId) {
            $user = User::find($userId);
        } else {
            $user = User::whereNotNull('google_access_token')->first();
        }

        if (!$user) {
            $this->error('No user found with Google Calendar access');
            return;
        }

        $this->info("Testing Google Calendar validation for user: {$user->name} (ID: {$user->id})");

        // Test basic access check
        $basicAccess = $user->hasGoogleCalendarAccess();
        $this->info("Basic access check (local tokens): " . ($basicAccess ? '✅ Valid' : '❌ Invalid'));

        // Test real API validation
        $calendarService = app(GoogleCalendarService::class);
        $realAccess = $calendarService->validateGoogleCalendarAccess($user);
        $this->info("Real API validation: " . ($realAccess ? '✅ Valid' : '❌ Invalid/Revoked'));

        // Show token status
        $this->info("Token exists: " . ($user->google_access_token ? '✅ Yes' : '❌ No'));
        $this->info("Token expired: " . ($user->isGoogleTokenExpired() ? '❌ Yes' : '✅ No'));
        $this->info("Calendar ID: " . ($user->google_calendar_id ?? 'Not set'));

        if (!$realAccess && $user->google_access_token) {
            $this->warn("⚠️  Access appears to be revoked - local tokens have been cleared");
        }
    }
}
