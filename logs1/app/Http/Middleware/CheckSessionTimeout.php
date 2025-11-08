<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckSessionTimeout
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('sws')->check()) {
            $lastActivity = session('last_activity');
            $sessionLifetime = config('session.lifetime') * 60; // Convert to seconds
            
            // Initialize last_activity if not set
            if (!$lastActivity) {
                $lastActivity = time();
                session(['last_activity' => $lastActivity]);
            }
            
            // Check if session has expired
            if ((time() - $lastActivity) > $sessionLifetime) {
                Auth::guard('sws')->logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                
                if ($request->expectsJson()) {
                    return response()->json([
                        'success' => false,
                        'session_expired' => true,
                        'message' => 'Session expired due to inactivity'
                    ], 401);
                }
                
                return redirect('/splash-logout');
            }
            
            // Update last activity for every request (only if not an API call to refresh-session or check-session)
            if (!$request->is('api/refresh-session') && !$request->is('api/check-session')) {
                session(['last_activity' => time()]);
            }
        }

        return $next($request);
    }
}