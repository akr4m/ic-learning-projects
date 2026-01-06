<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\View\View;

/**
 * UserController - User Management (Admin Only)
 *
 * এই controller admin দের জন্য user role management handle করে।
 * শুধুমাত্র admin রা এই actions গুলো করতে পারবে।
 *
 * Features:
 * - User list দেখা
 * - User role change করা
 *
 * @see \App\Models\User
 */
class UserController extends Controller
{
    /**
     * সব users এর list দেখায়
     * শুধু admin রা এই page দেখতে পারবে
     */
    public function index(Request $request): View
    {
        // Admin check
        if (!$request->user()->isAdmin()) {
            abort(403, 'শুধুমাত্র Admin এই page দেখতে পারবে।');
        }

        $users = User::orderBy('name')->get();

        return view('admin.users.index', compact('users'));
    }

    /**
     * User edit form দেখায়
     * এখানে role change করা যাবে
     */
    public function edit(Request $request, User $user): View
    {
        // Admin check
        if (!$request->user()->isAdmin()) {
            abort(403, 'শুধুমাত্র Admin এই page দেখতে পারবে।');
        }

        // নিজের role নিজে change করতে পারবে না
        if ($request->user()->id === $user->id) {
            abort(403, 'নিজের role নিজে change করা যাবে না।');
        }

        $roles = ['author', 'editor', 'admin'];

        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * User role update করে
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        // Admin check
        if (!$request->user()->isAdmin()) {
            abort(403, 'শুধুমাত্র Admin এই action করতে পারবে।');
        }

        // নিজের role নিজে change করতে পারবে না
        if ($request->user()->id === $user->id) {
            abort(403, 'নিজের role নিজে change করা যাবে না।');
        }

        // Validate
        $validated = $request->validate([
            'role' => ['required', 'in:author,editor,admin'],
        ]);

        $user->update(['role' => $validated['role']]);

        return redirect()
            ->route('admin.users.index')
            ->with('success', "{$user->name} এর role পরিবর্তন করা হয়েছে।");
    }
}
