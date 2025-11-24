<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Console\Scheduling\Schedule;

class ScheduleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->app->booted(function () {
            $schedule = $this->app->make(Schedule::class);

            // Refresh expired Google Calendar tokens every hour
            $schedule->command('google-calendar:refresh-tokens')
                ->hourly()
                ->onSuccess(function () {
                    \Illuminate\Support\Facades\Log::info('Google Calendar token refresh job completed successfully');
                })
                ->onFailure(function () {
                    \Illuminate\Support\Facades\Log::error('Google Calendar token refresh job failed');
                });

            // Clean up orphaned Google Calendar events every day at 2 AM
            $schedule->command('google-calendar:cleanup --all')
                ->daily()
                ->at('02:00')
                ->onSuccess(function () {
                    \Illuminate\Support\Facades\Log::info('Google Calendar cleanup job completed successfully');
                })
                ->onFailure(function () {
                    \Illuminate\Support\Facades\Log::error('Google Calendar cleanup job failed');
                });

            // Verify sync integrity every week on Monday at 3 AM
            $schedule->command('google-calendar:cleanup --all --verify-only')
                ->weeklyOn(1, '03:00')
                ->onSuccess(function () {
                    \Illuminate\Support\Facades\Log::info('Google Calendar sync integrity verification completed');
                })
                ->onFailure(function () {
                    \Illuminate\Support\Facades\Log::error('Google Calendar sync integrity verification failed');
                });
        });
    }
}
