<?php

use App\Http\Middleware\CheckRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Vercel serverless environment has a read-only filesystem except for /tmp.
// We must create directories and override environment variables BEFORE Laravel boots.
if (isset($_ENV['VERCEL']) || getenv('VERCEL')) {
    $tmpStorage = '/tmp/storage';
    $directories = [
        'framework/cache/data', 
        'framework/sessions', 
        'framework/testing', 
        'framework/views', 
        'logs',
        'bootstrap/cache'
    ];
    foreach ($directories as $dir) {
        if (!is_dir("$tmpStorage/$dir")) {
            mkdir("$tmpStorage/$dir", 0777, true);
        }
    }
    
    $envs = [
        'LARAVEL_STORAGE_PATH' => $tmpStorage,
        'VIEW_COMPILED_PATH' => "$tmpStorage/framework/views",
        'APP_SERVICES_CACHE' => "$tmpStorage/bootstrap/cache/services.php",
        'APP_PACKAGES_CACHE' => "$tmpStorage/bootstrap/cache/packages.php",
        'APP_CONFIG_CACHE' => "$tmpStorage/bootstrap/cache/config.php",
        'APP_ROUTES_CACHE' => "$tmpStorage/bootstrap/cache/routes.php",
        'APP_EVENTS_CACHE' => "$tmpStorage/bootstrap/cache/events.php",
    ];
    
    foreach ($envs as $k => $v) {
        $_ENV[$k] = $v;
        putenv("$k=$v");
    }
}

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => CheckRole::class
        ]);
        $middleware->trustProxies(at: ['*']);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();

return $app;
