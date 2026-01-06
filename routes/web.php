<?php

/**
 * Web Routes
 *
 * এই ফাইলে web application এর সব routes define করা হয়েছে।
 * Routes গুলো 'web' middleware group এর আওতায়।
 *
 * Route Types:
 * - Public routes: Authentication ছাড়া access করা যায়
 * - Auth routes: Login required
 * - Editor routes: Editor role required
 *
 * IMPORTANT: Route order matters!
 * Static routes (like /posts/create) must come BEFORE dynamic routes (like /posts/{post})
 * Otherwise Laravel will try to match "create" as a {post} parameter
 *
 * @see https://laravel.com/docs/routing
 */

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EditorController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes (Authentication না লাগে)
|--------------------------------------------------------------------------
*/

// Homepage - Published posts দেখায়
Route::get('/', [PostController::class, 'index'])->name('home');

// Published posts listing (alias for home)
Route::get('/posts', [PostController::class, 'index'])->name('posts.index');

/*
|--------------------------------------------------------------------------
| Authenticated Routes (Login লাগবে)
|--------------------------------------------------------------------------
| এই routes গুলো শুধু logged-in users access করতে পারবে।
| 'auth' middleware automatic redirect করবে login page এ।
*/

Route::middleware('auth')->group(function () {

    // Dashboard - User এর personal dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile routes (Breeze default)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /*
    |--------------------------------------------------------------------------
    | Post Management Routes
    |--------------------------------------------------------------------------
    | Author এর post create, edit, delete এর routes
    |
    | IMPORTANT: /posts/create MUST be before /posts/{post}
    | Otherwise "create" will be treated as a post slug
    */

    // My Posts - User এর নিজের সব posts
    Route::get('/my-posts', [PostController::class, 'myPosts'])->name('posts.my');

    // Create new post - এটা /posts/{post} এর আগে থাকতে হবে!
    Route::get('/posts/create', [PostController::class, 'create'])->name('posts.create');
    Route::post('/posts', [PostController::class, 'store'])->name('posts.store');

    // Edit post
    Route::get('/posts/{post}/edit', [PostController::class, 'edit'])->name('posts.edit');
    Route::put('/posts/{post}', [PostController::class, 'update'])->name('posts.update');

    // Delete post
    Route::delete('/posts/{post}', [PostController::class, 'destroy'])->name('posts.destroy');

    // Submit post for approval (draft -> pending)
    Route::post('/posts/{post}/submit', [PostController::class, 'submit'])->name('posts.submit');

    /*
    |--------------------------------------------------------------------------
    | Editor Routes
    |--------------------------------------------------------------------------
    | শুধু Editor role এর users এই routes access করতে পারবে।
    | PostPolicy এর মাধ্যমে authorization check হবে।
    */

    // Pending posts queue (Editor only)
    Route::get('/editor/pending', [EditorController::class, 'pending'])->name('editor.pending');

    // Approve post (Editor only)
    Route::post('/posts/{post}/approve', [EditorController::class, 'approve'])->name('posts.approve');

    // Reject post (Editor only)
    Route::post('/posts/{post}/reject', [EditorController::class, 'reject'])->name('posts.reject');

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    | শুধু Admin role এর users এই routes access করতে পারবে।
    | User role management এর জন্য।
    */

    // User Management (Admin only)
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::get('/admin/users/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
    Route::put('/admin/users/{user}', [UserController::class, 'update'])->name('admin.users.update');
});

/*
|--------------------------------------------------------------------------
| Single Post View (Public)
|--------------------------------------------------------------------------
| এই route সবার শেষে থাকতে হবে কারণ এটা dynamic parameter ব্যবহার করে।
| /posts/{post} - এখানে {post} যেকোনো value হতে পারে।
*/

// Single post view - Published posts সবাই দেখতে পারবে
Route::get('/posts/{post}', [PostController::class, 'show'])->name('posts.show');

// Breeze authentication routes
require __DIR__.'/auth.php';
