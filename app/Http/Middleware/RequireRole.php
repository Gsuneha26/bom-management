<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RequireRole
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $roles = null): Response
    {
        $user = $request->user();

        if (! $user || ! $roles) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'You do not have permission to access this resource.'], 403);
            }

            return redirect()->route('dashboard')->with('error', 'You do not have permission to access this page.');
        }

        $allowedRoles = array_map('trim', explode('|', $roles));

        if (! $user->hasAnyRole($allowedRoles)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'You do not have permission to access this resource.'], 403);
            }

            return redirect()->route('dashboard')->with('error', 'You do not have permission to access this page.');
        }

        return $next($request);
    }
}
