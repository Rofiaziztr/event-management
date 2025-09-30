<?php

namespace App\Providers;

use Carbon\Carbon;
use App\Models\Event;
use App\Observers\EventObserver;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Mailer\Transport\Dsn;
use Symfony\Component\Mailer\Bridge\Brevo\Transport\BrevoTransportFactory;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind('alert', function ($app) {
            return new \App\Helpers\Alert();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::observe(EventObserver::class);

        Mail::extend('brevo', function () {
            return (new BrevoTransportFactory)->create(
                new Dsn(
                    'brevo+api',
                    'default',
                    config('services.brevo.key')
                )
            );
        });

        Paginator::defaultView('custom.pagination');
        Paginator::defaultSimpleView('custom.simple-pagination');

        Carbon::setLocale('id');
        
        // Register alert-handler component
        $this->loadViewComponentsAs('', [
            'alert-handler' => \App\View\Components\AlertHandler::class,
        ]);
    }
}
