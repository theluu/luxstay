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
            return $request->user()
                ? redirect('/')->with('error', 'Unauthorized.')
                : redirect('/login');
        }
        return $next($request);
    }
}
