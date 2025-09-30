<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AlertServiceProvider extends ServiceProvider
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
        // Make sure the alert handler component is available
        $this->loadViewComponentsAs('', [
            'alert-handler' => \App\View\Components\AlertHandler::class,
        ]);
    }
}