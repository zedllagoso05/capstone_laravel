<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/');
        }

        if (Auth::user()->email_verified_at === null && !$request->is('verify-email*') && !$request->is('logout')) {
            return redirect()->route('verification.notice');
        }

        if (!in_array(Auth::user()->role, $roles)) {
            abort(403, 'Unauthorized.');
        }

        return $next($request);
    }
}