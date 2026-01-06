<?php

/**
 * Web Routes
 *
 * এই ফাইলে সব web routes define করা হয়।
 * Routes হলো URL এবং Controller method এর মধ্যে সংযোগ।
 *
 * Route Group ব্যবহার করে related routes একসাথে রাখা যায়।
 * Middleware ব্যবহার করে route access control করা যায়।
 *
 * @see https://laravel.com/docs/routing
 */

use App\Http\Controllers\AuthController;
use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (Guest)
|--------------------------------------------------------------------------
|
| এই routes গুলো সবাই access করতে পারে।
| Home page সবার জন্য উন্মুক্ত।
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Guest Routes (শুধুমাত্র logged-out user দের জন্য)
|--------------------------------------------------------------------------
|
| 'guest' middleware ব্যবহার করা হয়েছে।
| এই routes গুলো শুধুমাত্র logged-out users access করতে পারবে।
| Logged-in user এই routes এ গেলে tasks.index এ redirect হবে।
|
*/

Route::middleware('guest')->group(function () {
    // Registration Routes
    Route::get('/register', [AuthController::class, 'showRegisterForm'])
        ->name('register');
    Route::post('/register', [AuthController::class, 'register']);

    // Login Routes
    Route::get('/login', [AuthController::class, 'showLoginForm'])
        ->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

/*
|--------------------------------------------------------------------------
| Protected Routes (শুধুমাত্র logged-in user দের জন্য)
|--------------------------------------------------------------------------
|
| 'auth' middleware ব্যবহার করা হয়েছে।
| এই routes গুলো শুধুমাত্র logged-in users access করতে পারবে।
| Logged-out user এই routes এ গেলে login page এ redirect হবে।
|
*/

Route::middleware('auth')->group(function () {
    // Logout Route
    Route::post('/logout', [AuthController::class, 'logout'])
        ->name('logout');

    /*
    |--------------------------------------------------------------------------
    | Task Resource Routes
    |--------------------------------------------------------------------------
    |
    | Route::resource() একসাথে 7টি CRUD route তৈরি করে:
    |
    | GET      /tasks              -> index()   - Task list দেখায়
    | GET      /tasks/create       -> create()  - Create form দেখায়
    | POST     /tasks              -> store()   - নতুন task সংরক্ষণ করে
    | GET      /tasks/{task}       -> show()    - একটি task দেখায়
    | GET      /tasks/{task}/edit  -> edit()    - Edit form দেখায়
    | PUT      /tasks/{task}       -> update()  - Task update করে
    | DELETE   /tasks/{task}       -> destroy() - Task মুছে ফেলে
    |
    */
    Route::resource('tasks', TaskController::class);

    // Additional Task Routes (file handling)
    Route::get('/tasks/{task}/download', [TaskController::class, 'download'])
        ->name('tasks.download');
    Route::delete('/tasks/{task}/attachment', [TaskController::class, 'deleteAttachment'])
        ->name('tasks.attachment.delete');
});
