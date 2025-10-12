<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class WebAuthMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Skip authentication check for login routes and API routes
        if ($request->is('login') || 
            $request->is('process-login') || 
            $request->is('logout') || 
            $request->is('logout-splash') ||
            $request->is('login-splash') ||
            $request->is('api/*')) {
            return $next($request);
        }

        // Check if user is authenticated via cookie
        $isAuthenticated = false;
        
        if (isset($_COOKIE['isAuthenticated']) && $_COOKIE['isAuthenticated'] === 'true') {
            $isAuthenticated = true;
        }
        
        // Also check if we have user data in cookie
        $userCookie = isset($_COOKIE['user']) ? json_decode($_COOKIE['user'], true) : null;
        if ($userCookie && isset($userCookie['id'])) {
            $isAuthenticated = true;
        }

        if (!$isAuthenticated) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}