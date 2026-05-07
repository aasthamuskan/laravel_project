<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Advisory;
use App\Models\Crop;
use Illuminate\Http\Request;

/**
 * AdminController
 *
 * Admin-only operations:
 * - User management
 * - Advisory management (approve/delete)
 * - System overview
 */
class AdminController extends Controller
{
    /**
     * GET /admin/users
     * List all registered users with their roles.
     */
    public function users()
    {
        $users = User::orderBy('role')->orderBy('name')->get();
        return view('admin.users', compact('users'));
    }

    /**
     * DELETE /admin/users/{user}
     * Remove a user account.
     */
    public function destroyUser(User $user)
    {
        // Prevent admin from deleting themselves
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();
        return back()->with('success', "User \"{$user->name}\" has been removed.");
    }

    /**
     * PATCH /admin/users/{user}/role
     * Change a user's role.
     */
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:Farmer,Expert,Admin',
        ]);

        $user->update(['role' => $request->role]);
        return back()->with('success', "Role updated for {$user->name}.");
    }

    /**
     * GET /admin/advisories
     * Full advisory listing with approve/delete actions.
     */
    public function advisories()
    {
        $advisories = Advisory::with('crop')->orderBy('created_at', 'desc')->get();
        return view('admin.advisories', compact('advisories'));
    }

    /**
     * DELETE /admin/advisories/{advisory}
     * Admin hard-deletes an advisory.
     */
    public function deleteAdvisory(Advisory $advisory)
    {
        $advisory->delete();
        return back()->with('success', 'Advisory removed from the system.');
    }

    /**
     * GET /admin/overview
     * Dashboard with system stats.
     */
    public function overview()
    {
        $stats = [
            'users'      => User::count(),
            'farmers'    => User::where('role', 'Farmer')->count(),
            'experts'    => User::where('role', 'Expert')->count(),
            'crops'      => Crop::count(),
            'advisories' => Advisory::count(),
        ];

        return view('admin.overview', compact('stats'));
    }
}
