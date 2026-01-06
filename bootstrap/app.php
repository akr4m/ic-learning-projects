<?php

/**
 * Application Bootstrap Configuration
 *
 * এই ফাইলে Laravel application এর core configuration থাকে।
 * Routing, Middleware, Exception handling এখানে configure করা হয়।
 *
 * @see https://laravel.com/docs/configuration
 */

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        /**
         * Guest Middleware Redirect Configuration
         *
         * 'guest' middleware ব্যবহার করলে logged-in user কোথায় redirect হবে তা নির্ধারণ করে।
         * এখানে /tasks route এ redirect করা হচ্ছে (tasks.index)।
         */
        $middleware->redirectGuestsTo('/login');
        $middleware->redirectUsersTo('/tasks');
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
