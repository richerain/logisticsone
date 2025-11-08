<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
//newly added imports
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\SWS\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->authService->login($request->only('email', 'password'));

        return response()->json($result, $result['success'] ? 200 : 401);
    }

    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:sws.users,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->authService->sendOtp($request->email);

        return response()->json($result);
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:sws.users,email',
            'otp' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $result = $this->authService->verifyOtp($request->email, $request->otp);

        return response()->json($result, $result['success'] ? 200 : 401);
    }

    public function logout(Request $request)
    {
        $result = $this->authService->logout();

        return response()->json($result);
    }

    public function me(Request $request)
    {
        $result = $this->authService->getCurrentUser();

        return response()->json($result, $result['success'] ? 200 : 401);
    }

    /**
     * Refresh session activity timestamp
     */
    public function refreshSession(Request $request)
    {
        if (Auth::guard('sws')->check()) {
            // Force session regeneration and save
            $request->session()->regenerate(true);
            
            // Update last activity timestamp in session
            $request->session()->put('last_activity', time());
            
            // Force immediate session save
            $request->session()->save();

            // Get fresh CSRF token
            $newToken = csrf_token();
            
            return response()->json([
                'success' => true, 
                'message' => 'Session refreshed successfully',
                'csrf_token' => $newToken,
                'session_refreshed_at' => now()->toDateTimeString()
            ]);
        }

        return response()->json([
            'success' => false, 
            'message' => 'Not authenticated',
            'requires_relogin' => true
        ], 401);
    }

    /**
     * Get current CSRF token (for when session is still valid but token might expire)
     */
    public function getCsrfToken(Request $request)
    {
        return response()->json([
            'csrf_token' => csrf_token()
        ]);
    }

    /**
     * Check session status
     */
    public function checkSession(Request $request)
    {
        if (Auth::guard('sws')->check()) {
            // Update last activity
            $request->session()->put('last_activity', time());
            
            return response()->json([
                'success' => true,
                'authenticated' => true,
                'user' => [
                    'id' => Auth::guard('sws')->id(),
                    'email' => Auth::guard('sws')->user()->email,
                ],
                'csrf_token' => csrf_token(),
                'session_lifetime' => config('session.lifetime', 5)
            ]);
        }

        return response()->json([
            'success' => false,
            'authenticated' => false,
            'message' => 'Session not found or expired'
        ], 401);
    }
}