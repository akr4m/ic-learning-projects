<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * DashboardController - User Dashboard Handle করে
 *
 * এই controller logged-in user এর dashboard দেখায়।
 * User তার role অনুযায়ী dashboard এ বিভিন্ন তথ্য দেখবে।
 *
 * Author dashboard: নিজের posts এর summary
 * Editor dashboard: Pending posts এর count সহ approval queue
 */
class DashboardController extends Controller
{
    /**
     * User এর dashboard দেখায়
     *
     * - Author: তার নিজের posts এর statistics
     * - Editor: Pending approval queue এর summary
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // User এর নিজের posts এর statistics
        $stats = [
            'total' => $user->posts()->count(),
            'draft' => $user->posts()->draft()->count(),
            'pending' => $user->posts()->pending()->count(),
            'published' => $user->posts()->published()->count(),
            'rejected' => $user->posts()->where('status', 'rejected')->count(),
        ];

        // Editor হলে pending posts এর global count
        $pendingApprovalCount = 0;
        if ($user->isEditor()) {
            $pendingApprovalCount = \App\Models\Post::pending()->count();
        }

        // User এর recent posts (latest 5)
        $recentPosts = $user->posts()
            ->latest()
            ->take(5)
            ->get();

        return view('dashboard', compact('stats', 'pendingApprovalCount', 'recentPosts'));
    }
}
