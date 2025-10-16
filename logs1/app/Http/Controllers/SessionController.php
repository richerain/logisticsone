<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SessionController extends Controller
{
    /**
     * Simple session check - always returns valid
     */
    public function checkSession(Request $request)
    {
        try {
            Log::debug('Session check called - SESSION TIMEOUT DISABLED');
            
            return response()->json([
                'session_valid' => true,
                'show_warning' => false,
                'time_until_expiry' => 3600, // 1 hour in seconds
                'minutes_remaining' => 60,
                'message' => 'Session is active (timeout disabled)'
            ]);

        } catch (\Exception $e) {
            Log::error('Session check error: ' . $e->getMessage());
            
            return response()->json([
                'session_valid' => true, // Always return true even on error
                'message' => 'Session check failed but session remains active'
            ], 500);
        }
    }

    /**
     * Extend session - simple success response
     */
    public function extendSession(Request $request)
    {
        try {
            Log::info('Session extend called - NO OP');
            
            return response()->json([
                'success' => true,
                'message' => 'Session extended successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Session extension error: ' . $e->getMessage());
            
            return response()->json([
                'success' => true, // Always return true
                'message' => 'Session remains active'
            ], 500);
        }
    }

    /**
     * Get session information
     */
    public function getSessionInfo(Request $request)
    {
        return response()->json([
            'session_active' => true,
            'last_activity' => time(),
            'session_start' => time(),
            'browser_session' => 'true',
            'time_until_expiry' => 3600,
            'minutes_remaining' => 60,
            'inactivity_timeout' => 0,
            'absolute_timeout' => 0,
            'time_since_last_activity' => 0,
            'time_since_session_start' => 0,
            'message' => 'Session timeout disabled'
        ]);
    }

    /**
     * Initialize session after login
     */
    public function initializeSession(Request $request)
    {
        try {
            $currentTime = time();
            
            // Set basic session cookies only
            setcookie('isAuthenticated', 'true', [
                'expires' => 0,
                'path' => '/',
                'secure' => false,
                'httponly' => true,
                'samesite' => 'Lax'
            ]);
            
            setcookie('user', json_encode(['id' => $request->user_id ?? 'unknown']), [
                'expires' => time() + (86400 * 30),
                'path' => '/',
                'secure' => false,
                'httponly' => true,
                'samesite' => 'Lax'
            ]);

            Log::info('Session initialized - timeout disabled');

            return response()->json([
                'success' => true,
                'message' => 'Session initialized successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Session initialization error: ' . $e->getMessage());
            
            return response()->json([
                'success' => true, // Always return true
                'message' => 'Session initialized with warnings'
            ], 500);
        }
    }

    /**
     * Handle session timeout - redirect to logout splash
     */
    public function handleTimeout(Request $request)
    {
        try {
            Log::info('Session timeout handler called - NO OP');
            
            return response()->json([
                'success' => true,
                'redirect_to' => '/logout-splash?timeout=normal',
                'message' => 'Session timeout handled'
            ]);

        } catch (\Exception $e) {
            Log::error('Session timeout handling error: ' . $e->getMessage());
            
            return response()->json([
                'success' => true,
                'message' => 'Session timeout handled with warnings'
            ], 500);
        }
    }
}