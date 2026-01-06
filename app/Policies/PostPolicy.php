<?php

namespace App\Policies;

use App\Models\Post;
use App\Models\User;

/**
 * PostPolicy - Post সংক্রান্ত Authorization Logic
 *
 * Laravel Policy হলো authorization logic কে organize করার একটি clean উপায়।
 * এই Policy define করে কোন User কোন Post এ কি কি action নিতে পারবে।
 *
 * Role-based permissions:
 * - Author: নিজের post create, view, edit করতে পারবে (শুধু draft/rejected status এ)
 * - Editor: সব pending post দেখতে পারবে, approve/reject করতে পারবে
 *
 * @see https://laravel.com/docs/authorization#creating-policies
 */
class PostPolicy
{
    /**
     * সব published posts দেখতে পারবে কি না
     * Guest সহ সবাই published posts দেখতে পারবে
     */
    public function viewAny(?User $user): bool
    {
        return true; // সবাই published posts দেখতে পারবে
    }

    /**
     * নির্দিষ্ট একটি post দেখতে পারবে কি না
     *
     * - Published post সবাই দেখতে পারবে
     * - Draft/Pending/Rejected post শুধু owner এবং editor দেখতে পারবে
     */
    public function view(?User $user, Post $post): bool
    {
        // Published post সবাই দেখতে পারে
        if ($post->isPublished()) {
            return true;
        }

        // Logged in না থাকলে unpublished post দেখতে পারবে না
        if (!$user) {
            return false;
        }

        // Post owner দেখতে পারবে
        if ($post->user_id === $user->id) {
            return true;
        }

        // Editor সব post দেখতে পারবে
        return $user->isEditor();
    }

    /**
     * নতুন post create করতে পারবে কি না
     * শুধু authenticated users (Author এবং Editor উভয়েই) post create করতে পারবে
     */
    public function create(User $user): bool
    {
        return true; // যেকোনো logged in user post create করতে পারবে
    }

    /**
     * Post edit করতে পারবে কি না
     *
     * Rules:
     * - Author শুধু নিজের post edit করতে পারবে
     * - Author শুধু draft বা rejected status এ edit করতে পারবে
     * - Editor কোনো post edit করতে পারবে না (শুধু approve/reject)
     */
    public function update(User $user, Post $post): bool
    {
        // শুধু post owner edit করতে পারবে
        if ($post->user_id !== $user->id) {
            return false;
        }

        // শুধু draft বা rejected status এ edit করা যাবে
        // Pending বা published হয়ে গেলে আর edit করা যাবে না
        return $post->isDraft() || $post->isRejected();
    }

    /**
     * Post delete করতে পারবে কি না
     *
     * Rules:
     * - Author শুধু নিজের draft post delete করতে পারবে
     * - Editor কোনো post delete করতে পারবে না
     */
    public function delete(User $user, Post $post): bool
    {
        // শুধু post owner delete করতে পারবে
        if ($post->user_id !== $user->id) {
            return false;
        }

        // শুধু draft status এ delete করা যাবে
        return $post->isDraft();
    }

    /**
     * Post submit করতে পারবে কি না (draft -> pending)
     *
     * Author তার draft post submit করলে সেটা pending হয়ে
     * Editor এর approval queue তে যাবে
     */
    public function submit(User $user, Post $post): bool
    {
        // শুধু post owner submit করতে পারবে
        if ($post->user_id !== $user->id) {
            return false;
        }

        // শুধু draft বা rejected post submit করা যাবে
        return $post->isDraft() || $post->isRejected();
    }

    /**
     * Post approve করতে পারবে কি না (pending -> published)
     * শুধু Editor pending posts approve করতে পারবে
     */
    public function approve(User $user, Post $post): bool
    {
        // শুধু Editor approve করতে পারবে
        if (!$user->isEditor()) {
            return false;
        }

        // শুধু pending post approve করা যাবে
        return $post->isPending();
    }

    /**
     * Post reject করতে পারবে কি না (pending -> rejected)
     * শুধু Editor pending posts reject করতে পারবে
     */
    public function reject(User $user, Post $post): bool
    {
        // শুধু Editor reject করতে পারবে
        if (!$user->isEditor()) {
            return false;
        }

        // শুধু pending post reject করা যাবে
        return $post->isPending();
    }

    /**
     * Pending posts list দেখতে পারবে কি না
     * শুধু Editor pending posts এর list দেখতে পারবে
     */
    public function viewPending(User $user): bool
    {
        return $user->isEditor();
    }
}
