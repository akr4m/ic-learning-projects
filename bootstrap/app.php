<?php

/**
 * Laravel Application Bootstrap
 *
 * এই ফাইলে application এর core configuration সেট করা হয়েছে।
 *
 * - Routing: web.php এবং api.php routes define করা
 * - Middleware: Sanctum API middleware setup
 * - Exceptions: Error handling configuration
 *
 * @see https://laravel.com/docs/configuration
 */

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Sanctum middleware - API authentication এর জন্য
        // এটা stateful requests handle করে (SPA authentication)
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
