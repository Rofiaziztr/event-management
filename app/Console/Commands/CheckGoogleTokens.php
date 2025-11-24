<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Carbon\Carbon;

class CheckGoogleTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'google-calendar:check-tokens {--user-id= : Check specific user} {--fix-expired : Auto-clear expired tokens}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check status of Google Calendar tokens for all users or specific user';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $userId = $this->option('user-id');
        $fixExpired = $this->option('fix-expired');

        $query = User::whereNotNull('google_access_token');

        if ($userId) {
            $query->where('id', $userId);
        }

        $users = $query->get();

        if ($users->isEmpty()) {
            $this->info('No users with Google tokens found.');
            return;
        }

        $this->info("Checking " . count($users) . " user(s) with Google tokens:\n");

        $headers = ['User ID', 'Email', 'Token Status', 'Expires At', 'Days Until Expiry', 'Action'];
        $rows = [];

        foreach ($users as $user) {
            $status = $user->hasGoogleCalendarAccess() ? 'Valid' : 'Invalid/Expired';
            $expiresAt = $user->google_token_expires_at?->format('Y-m-d H:i:s') ?? 'NULL';

            $daysUntilExpiry = 'N/A';
            if ($user->google_token_expires_at) {
                $diffInHours = now()->diffInHours($user->google_token_expires_at, false);
                if ($diffInHours < 0) {
                    if (abs($diffInHours) < 24) {
                        $daysUntilExpiry = "EXPIRED (" . abs($diffInHours) . " hours ago)";
                    } else {
                        $days = ceil(abs($diffInHours) / 24);
                        $daysUntilExpiry = "EXPIRED (" . $days . " days ago)";
                    }
                } else {
                    if ($diffInHours < 24) {
                        $daysUntilExpiry = $diffInHours . " hours";
                    } else {
                        $days = floor($diffInHours / 24);
                        $daysUntilExpiry = $days . " days";
                    }
                }
            }

            $action = '';
            if (!$user->hasGoogleCalendarAccess()) {
                $action = 'Clear Tokens';
                if ($fixExpired) {
                    $user->update([
                        'google_access_token' => null,
                        'google_refresh_token' => null,
                        'google_token_expires_at' => null,
                        'google_calendar_id' => null,
                    ]);
                    $action .= ' ✓';
                }
            }

            $rows[] = [
                $user->id,
                $user->email,
                $status,
                $expiresAt,
                $daysUntilExpiry,
                $action
            ];
        }

        $this->table($headers, $rows);

        if (!$fixExpired && collect($rows)->filter(fn($r) => str_contains($r[2], 'Invalid'))->count() > 0) {
            $this->newLine();
            $this->warn('Run with --fix-expired flag to automatically clear invalid tokens:');
            $this->line('php artisan google-calendar:check-tokens --fix-expired');
        }

        if ($fixExpired) {
            $this->newLine();
            $this->info('✓ Expired tokens have been cleared. Users will be prompted to re-authenticate.');
        }
    }
}
