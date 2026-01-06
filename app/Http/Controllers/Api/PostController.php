<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

/**
 * API PostController - REST API Endpoints for Posts
 *
 * এই controller Laravel Sanctum দিয়ে protected API endpoints provide করে।
 * API Token authentication এর মাধ্যমে access করা যাবে।
 *
 * API Authentication:
 * - Bearer Token header এ পাঠাতে হবে
 * - Token পেতে: POST /api/login (email, password দিয়ে)
 *
 * Available Endpoints:
 * - GET  /api/posts         - Published posts list
 * - POST /api/posts         - Create new post (Auth required)
 * - GET  /api/posts/{post}  - Single post view
 * - PUT  /api/posts/{post}  - Update post (Auth required)
 * - POST /api/posts/{post}/submit  - Submit for approval
 * - POST /api/posts/{post}/approve - Approve post (Editor only)
 * - POST /api/posts/{post}/reject  - Reject post (Editor only)
 *
 * @see https://laravel.com/docs/sanctum
 */
class PostController extends Controller
{
    /**
     * GET /api/posts
     * সব published posts return করে
     */
    public function index(): JsonResponse
    {
        $posts = Post::published()
            ->with('author:id,name') // শুধু id আর name নাও
            ->latest('published_at')
            ->paginate(10);

        return response()->json($posts);
    }

    /**
     * POST /api/posts
     * নতুন post create করে (Sanctum Auth Required)
     */
    public function store(Request $request): JsonResponse
    {
        Gate::authorize('create', Post::class);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ]);

        $post = $request->user()->posts()->create([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'status' => 'draft',
        ]);

        return response()->json([
            'message' => 'Post created successfully',
            'post' => $post,
        ], 201);
    }

    /**
     * GET /api/posts/{post}
     * Single post details return করে
     */
    public function show(Post $post): JsonResponse
    {
        Gate::authorize('view', $post);

        $post->load('author:id,name');

        return response()->json($post);
    }

    /**
     * PUT /api/posts/{post}
     * Post update করে
     */
    public function update(Request $request, Post $post): JsonResponse
    {
        Gate::authorize('update', $post);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ]);

        $post->update($validated);

        return response()->json([
            'message' => 'Post updated successfully',
            'post' => $post,
        ]);
    }

    /**
     * POST /api/posts/{post}/submit
     * Post submit করে approval এর জন্য
     */
    public function submit(Post $post): JsonResponse
    {
        Gate::authorize('submit', $post);

        $post->update([
            'status' => 'pending',
            'rejection_reason' => null,
        ]);

        return response()->json([
            'message' => 'Post submitted for approval',
            'post' => $post,
        ]);
    }

    /**
     * GET /api/posts/pending
     * Pending posts list (Editor only)
     */
    public function pending(): JsonResponse
    {
        Gate::authorize('viewPending', Post::class);

        $posts = Post::pending()
            ->with('author:id,name')
            ->oldest()
            ->paginate(10);

        return response()->json($posts);
    }

    /**
     * POST /api/posts/{post}/approve
     * Post approve করে (Editor only)
     */
    public function approve(Post $post): JsonResponse
    {
        Gate::authorize('approve', $post);

        $post->update([
            'status' => 'published',
            'published_at' => now(),
            'rejection_reason' => null,
        ]);

        return response()->json([
            'message' => 'Post approved and published',
            'post' => $post,
        ]);
    }

    /**
     * POST /api/posts/{post}/reject
     * Post reject করে (Editor only)
     */
    public function reject(Request $request, Post $post): JsonResponse
    {
        Gate::authorize('reject', $post);

        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        $post->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return response()->json([
            'message' => 'Post rejected',
            'post' => $post,
        ]);
    }
}
