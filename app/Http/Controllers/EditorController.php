<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

/**
 * EditorController - Editor এর Post Approval/Rejection Handle করে
 *
 * এই controller শুধু Editor role এর users এর জন্য।
 * Editor pending posts approve বা reject করতে পারবে।
 *
 * Workflow:
 * 1. Author post submit করে (pending status)
 * 2. Editor pending list দেখে
 * 3. Editor post approve করলে published হয়
 * 4. Editor post reject করলে rejection reason সহ rejected status হয়
 */
class EditorController extends Controller
{
    /**
     * সব pending posts এর list দেখায়
     * শুধু Editor এই page access করতে পারবে
     */
    public function pending(): View
    {
        // Authorization: শুধু Editor pending list দেখতে পারবে
        Gate::authorize('viewPending', Post::class);

        // সব pending posts, author সহ, পুরোনো আগে
        $posts = Post::pending()
            ->with('author')
            ->oldest() // যে আগে submit করেছে সে আগে
            ->paginate(10);

        return view('editor.pending', compact('posts'));
    }

    /**
     * Post approve করে (pending -> published)
     *
     * Approve হলে:
     * - status = published
     * - published_at = current timestamp
     */
    public function approve(Post $post): RedirectResponse
    {
        Gate::authorize('approve', $post);

        $post->update([
            'status' => 'published',
            'published_at' => now(),
            'rejection_reason' => null,
        ]);

        return redirect()
            ->route('editor.pending')
            ->with('success', 'পোস্ট সফলভাবে প্রকাশিত হয়েছে!');
    }

    /**
     * Post reject করে (pending -> rejected)
     *
     * Reject করার সময় একটি কারণ দিতে হবে।
     * Author এই কারণ দেখতে পাবে এবং post সংশোধন করে
     * আবার submit করতে পারবে।
     */
    public function reject(Request $request, Post $post): RedirectResponse
    {
        Gate::authorize('reject', $post);

        // Rejection reason validation
        $validated = $request->validate([
            'rejection_reason' => ['required', 'string', 'max:1000'],
        ]);

        $post->update([
            'status' => 'rejected',
            'rejection_reason' => $validated['rejection_reason'],
        ]);

        return redirect()
            ->route('editor.pending')
            ->with('success', 'পোস্ট প্রত্যাখ্যান করা হয়েছে।');
    }
}
