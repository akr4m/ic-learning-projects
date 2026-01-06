<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

/**
 * PostController - Blog Post CRUD Operations
 *
 * এটি একটি Resource Controller যা posts এর সব CRUD operations handle করে।
 * Laravel এর resource controller convention follow করা হয়েছে।
 *
 * Resource Controller Methods:
 * - index()   : GET /posts         - সব posts দেখায়
 * - create()  : GET /posts/create  - নতুন post create form
 * - store()   : POST /posts        - নতুন post save
 * - show()    : GET /posts/{post}  - একটি post দেখায়
 * - edit()    : GET /posts/{post}/edit - post edit form
 * - update()  : PUT /posts/{post}  - post update
 * - destroy() : DELETE /posts/{post} - post delete
 *
 * @see https://laravel.com/docs/controllers#resource-controllers
 */
class PostController extends Controller
{
    /**
     * সব published posts দেখায় (Public)
     * Homepage বা blog listing page এর জন্য
     */
    public function index(): View
    {
        // শুধু published posts, সাথে author এর নাম (eager loading)
        $posts = Post::published()
            ->with('author') // N+1 problem avoid করতে eager load
            ->latest('published_at')
            ->paginate(10);

        return view('posts.index', compact('posts'));
    }

    /**
     * নতুন post create form দেখায়
     * Policy: create - যেকোনো logged-in user
     */
    public function create(): View
    {
        // Authorization check using Gate
        Gate::authorize('create', Post::class);

        return view('posts.create');
    }

    /**
     * নতুন post database এ save করে
     *
     * Validation Rules:
     * - title: required, max 255 chars
     * - body: required
     *
     * নতুন post সবসময় 'draft' status এ create হয়
     */
    public function store(Request $request): RedirectResponse
    {
        Gate::authorize('create', Post::class);

        // Validation - Laravel এর built-in validation
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ]);

        // নতুন post create - user relationship এর মাধ্যমে
        // এতে automatically user_id set হয়ে যায়
        $post = $request->user()->posts()->create([
            'title' => $validated['title'],
            'body' => $validated['body'],
            'status' => 'draft', // নতুন post সবসময় draft
        ]);

        return redirect()
            ->route('posts.show', $post)
            ->with('success', 'পোস্ট সফলভাবে তৈরি হয়েছে!');
    }

    /**
     * একটি নির্দিষ্ট post দেখায়
     *
     * Route Model Binding ব্যবহৃত হয়েছে।
     * Laravel automatically Post model find করে slug/id দিয়ে।
     *
     * Policy: view - published সবাই, unpublished শুধু owner/editor
     */
    public function show(Post $post): View
    {
        Gate::authorize('view', $post);

        // Author information সাথে নিয়ে আসো
        $post->load('author');

        return view('posts.show', compact('post'));
    }

    /**
     * Post edit form দেখায়
     * Policy: update - শুধু owner এবং draft/rejected status এ
     */
    public function edit(Post $post): View
    {
        Gate::authorize('update', $post);

        return view('posts.edit', compact('post'));
    }

    /**
     * Post update করে database এ
     */
    public function update(Request $request, Post $post): RedirectResponse
    {
        Gate::authorize('update', $post);

        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string'],
        ]);

        $post->update($validated);

        return redirect()
            ->route('posts.show', $post)
            ->with('success', 'পোস্ট সফলভাবে আপডেট হয়েছে!');
    }

    /**
     * Post delete করে
     * Policy: delete - শুধু owner এবং draft status এ
     */
    public function destroy(Post $post): RedirectResponse
    {
        Gate::authorize('delete', $post);

        $post->delete();

        return redirect()
            ->route('dashboard')
            ->with('success', 'পোস্ট সফলভাবে মুছে ফেলা হয়েছে!');
    }

    /**
     * Post submit করে approval এর জন্য (draft -> pending)
     *
     * Author যখন post লেখা শেষ করে, তখন এই method call করে
     * submit করলে post pending status এ যায় এবং Editor এর queue তে পৌঁছায়
     */
    public function submit(Post $post): RedirectResponse
    {
        Gate::authorize('submit', $post);

        $post->update([
            'status' => 'pending',
            'rejection_reason' => null, // আগের rejection reason clear করো
        ]);

        return redirect()
            ->route('posts.show', $post)
            ->with('success', 'পোস্ট সফলভাবে জমা দেওয়া হয়েছে! Editor এর অনুমোদনের অপেক্ষায় আছে।');
    }

    /**
     * User এর নিজের সব posts দেখায় (My Posts)
     */
    public function myPosts(Request $request): View
    {
        $posts = $request->user()
            ->posts()
            ->latest()
            ->paginate(10);

        return view('posts.my-posts', compact('posts'));
    }
}
