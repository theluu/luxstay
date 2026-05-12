<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (! $request->user()?->isAdmin()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden.'], 403);
            }
            // The 'auth' middleware redirects guests before we are reached in the
            // standard ['auth', 'admin'] stack. This branch guards standalone usage.
            return $request->user()
                ? redirect('/')->with('error', 'Unauthorized.')
                : redirect('/login');
        }
        return $next($request);
    }
}
