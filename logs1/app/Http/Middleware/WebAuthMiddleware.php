<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Log;

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
            $request->is('otp-verification') ||
            $request->is('api/auth/*') ||
            $request->is('api/session/*') ||
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

        // SESSION TIMEOUT DISABLED - No timeout checks

        if (!$isAuthenticated) {
            // Store intended URL for redirect after login
            if ($request->isMethod('get')) {
                $request->session()->put('url.intended', $request->fullUrl());
            }
            
            return redirect()->route('login')->with('error', 'Please login to access this page.');
        }

        return $next($request);
    }

    /**
     * Clear all session data
     */
    private function clearSessionData()
    {
        // Clear cookies
        setcookie('isAuthenticated', '', time() - 3600, '/');
        setcookie('user', '', time() - 3600, '/');
        setcookie('lastActivity', '', time() - 3600, '/');
        setcookie('sessionStart', '', time() - 3600, '/');
        setcookie('browserSession', '', time() - 3600, '/');
        
        // Clear session storage
        if (isset($_SESSION)) {
            session_unset();
            session_destroy();
        }
    }
}