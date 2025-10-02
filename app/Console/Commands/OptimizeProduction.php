<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class OptimizeProduction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:optimize-production {--skip-assets : Skip asset compilation}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Optimize application for production deployment';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🚀 Starting production optimization...');

        // Clear existing caches first
        $this->info('Clearing existing caches...');
        Artisan::call('cache:clear');
        Artisan::call('config:clear');
        Artisan::call('route:clear');
        Artisan::call('view:clear');

        // Optimize database
        $this->info('Optimizing database...');
        Artisan::call('migrate', ['--force' => true]);

        // Cache configurations
        $this->info('Caching configurations...');
        Artisan::call('config:cache');
        Artisan::call('route:cache');
        Artisan::call('view:cache');

        // Build assets if not skipped
        if (!$this->option('skip-assets')) {
            $this->info('Building optimized assets...');
            exec('npm run prod');
        }

        // Additional optimizations
        $this->info('Running additional optimizations...');
        Artisan::call('optimize');

        $this->info('✅ Production optimization completed successfully!');
        $this->info('Your application is now optimized for production use.');
    }
}
