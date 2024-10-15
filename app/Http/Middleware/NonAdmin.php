<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class NonAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Check if user is authenticated and is not an admin
        if (Auth::check() && Auth::user()->role !== 'admin') {
            return $next($request);
        }

        // If the user is admin or not authenticated, redirect to a different page (e.g. home or admin dashboard)
        return redirect('/')->with('error', 'Admins cannot access this page.');
    }
}
