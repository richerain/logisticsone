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
        // Check if user is authenticated via cookie or localStorage
        $isAuthenticated = false;
        
        // Check cookie first
        if (isset($_COOKIE['isAuthenticated']) && $_COOKIE['isAuthenticated'] === 'true') {
            $isAuthenticated = true;
        }
        
        // If not authenticated via cookie, check if this is an API request with headers
        if (!$isAuthenticated && $request->hasHeader('X-User-Id')) {
            $isAuthenticated = true;
        }
        
        if (!$isAuthenticated) {
            return redirect()->route('login');
        }

        return $next($request);
    }
}