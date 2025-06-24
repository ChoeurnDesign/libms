<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        if (Auth::user()->role !== 'admin') {
            return redirect()->route('user.dashboard')->with('error', 'Access denied. Admin privileges required.');
        }

        return $next($request);
    }
}
