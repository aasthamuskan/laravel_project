<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    /**
     * Handle an incoming request.
     *
     * Supports multiple allowed roles: middleware('role:Expert,Admin')
     * Each $roles entry may itself be comma-separated (Laravel passes them individually
     * when using route::middleware(['auth', 'role:Expert,Admin'])), so we explode.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Flatten any comma-separated role strings: 'Expert,Admin' → ['Expert', 'Admin']
        $allowedRoles = [];
        foreach ($roles as $role) {
            foreach (explode(',', $role) as $r) {
                $allowedRoles[] = trim($r);
            }
        }

        if (!in_array($user->role, $allowedRoles, strict: true)) {
            abort(403, "Access denied. Required role: " . implode(' or ', $allowedRoles) . ".");
        }

        return $next($request);
    }
}
