<?php

use App\Http\Middleware\CheckRole;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

// Vercel serverless environment has a read-only filesystem except for /tmp.
// We must create directories and override environment variables BEFORE Laravel boots.
if (isset($_ENV['VERCEL']) || getenv('VERCEL')) {
    $tmpStorage = '/tmp/storage';
    $directories = ['framework/cache/data', 'framework/sessions', 'framework/testing', 'framework/views', 'logs'];
    foreach ($directories as $dir) {
        if (!is_dir("$tmpStorage/$dir")) {
            mkdir("$tmpStorage/$dir", 0777, true);
        }
    }
    $_ENV['LARAVEL_STORAGE_PATH'] = $tmpStorage;
    putenv("LARAVEL_STORAGE_PATH=$tmpStorage");
    $_ENV['VIEW_COMPILED_PATH'] = "$tmpStorage/framework/views";
    putenv("VIEW_COMPILED_PATH=$tmpStorage/framework/views");
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
