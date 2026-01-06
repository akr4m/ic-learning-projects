<?php

/**
 * API Routes
 *
 * এই ফাইলে REST API endpoints define করা হয়েছে।
 * সব routes automatically '/api' prefix পায়।
 *
 * Authentication:
 * - Laravel Sanctum ব্যবহৃত হয়েছে
 * - API token পেতে POST /api/login (email, password)
 * - Token header এ পাঠাতে হবে: Authorization: Bearer {token}
 *
 * @see https://laravel.com/docs/sanctum#api-token-authentication
 */

use App\Http\Controllers\Api\PostController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

/*
|--------------------------------------------------------------------------
| Public API Routes (Authentication না লাগে)
|--------------------------------------------------------------------------
*/

// Published posts list
Route::get('/posts', [PostController::class, 'index']);

// Single post view
Route::get('/posts/{post}', [PostController::class, 'show']);

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

/**
 * Login - API Token Issue করে
 *
 * Request Body:
 * - email: string (required)
 * - password: string (required)
 * - device_name: string (optional, default: 'api')
 *
 * Response:
 * - token: string (Bearer token)
 */
Route::post('/login', function (Request $request) {
    $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required'],
        'device_name' => ['sometimes', 'string'],
    ]);

    $user = User::where('email', $request->email)->first();

    // Invalid credentials check
    if (! $user || ! Hash::check($request->password, $user->password)) {
        throw ValidationException::withMessages([
            'email' => ['The provided credentials are incorrect.'],
        ]);
    }

    // Create new token
    $token = $user->createToken($request->device_name ?? 'api')->plainTextToken;

    return response()->json([
        'token' => $token,
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
        ],
    ]);
});

/*
|--------------------------------------------------------------------------
| Protected API Routes (Sanctum Auth Required)
|--------------------------------------------------------------------------
| এই routes গুলো access করতে valid Bearer token লাগবে।
| Header: Authorization: Bearer {token}
*/

Route::middleware('auth:sanctum')->group(function () {

    // Current user info
    Route::get('/user', function (Request $request) {
        return response()->json($request->user());
    });

    // Logout - Current token revoke করে
    Route::post('/logout', function (Request $request) {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    });

    // Create new post
    Route::post('/posts', [PostController::class, 'store']);

    // Update post
    Route::put('/posts/{post}', [PostController::class, 'update']);

    // Submit post for approval
    Route::post('/posts/{post}/submit', [PostController::class, 'submit']);

    /*
    |--------------------------------------------------------------------------
    | Editor Only Routes
    |--------------------------------------------------------------------------
    | এই routes শুধু Editor role এর users access করতে পারবে।
    | PostPolicy এর মাধ্যমে authorization check হবে।
    */

    // Pending posts list (Editor only)
    Route::get('/posts/pending/list', [PostController::class, 'pending']);

    // Approve post (Editor only)
    Route::post('/posts/{post}/approve', [PostController::class, 'approve']);

    // Reject post (Editor only)
    Route::post('/posts/{post}/reject', [PostController::class, 'reject']);
});
