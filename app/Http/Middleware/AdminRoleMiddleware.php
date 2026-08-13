<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminRoleMiddleware
{
    public function handle(Request $request, Closure $next, ?string $role = null): Response
    {
        if (!Auth::guard('web')->check()) {
            return redirect()->route('login');
        }

        $user = Auth::guard('web')->user();

        if (!$user->role_id) {
            return redirect()->route('login')->with('error', 'Access denied. No role assigned.');
        }

        if ($role && !$user->hasRole($role)) {
            return redirect()->route('admin.dashboard')
                ->with('error', 'Access denied. Insufficient permissions.');
        }

        return $next($request);
    }
}
