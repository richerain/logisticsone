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
            
            if ($lastActivity && (time() - $lastActivity > $sessionLifetime)) {
                Auth::guard('sws')->logout();
                session()->flush();
                
                if ($request->expectsJson()) {
                    return response()->json(['session_expired' => true], 401);
                }
                
                return redirect('/splash-logout');
            }
            
            session(['last_activity' => time()]);
        }

        return $next($request);
    }
}