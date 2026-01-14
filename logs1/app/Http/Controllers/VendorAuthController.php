<?php

namespace App\Http\Controllers;

use App\Services\VendorAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VendorAuthController extends Controller
{
    protected $authService;

    public function __construct(VendorAuthService $authService)
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
            return response()->json(['success' => false, 'message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }
        $result = $this->authService->login($request->only('email', 'password'));

        return response()->json($result, $result['success'] ? 200 : 401);
    }

    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:main.vendor_account,email',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }
        $result = $this->authService->sendOtp($request->email);

        return response()->json($result);
    }

    public function verifyOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:main.vendor_account,email',
            'otp' => 'required|string|size:6',
        ]);
        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Validation error', 'errors' => $validator->errors()], 422);
        }
        $result = $this->authService->verifyOtp($request->email, $request->otp);
        if ($result['success']) {
            try {
                $request->session()->save();
                $request->session()->regenerate();
            } catch (\Exception $e) {
            }
        }

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

    public function refreshSession(Request $request)
    {
        if (Auth::guard('vendor')->check()) {
            $request->session()->regenerate(true);
            $request->session()->put('last_activity', time());
            $request->session()->save();

            return response()->json(['success' => true, 'message' => 'Session refreshed successfully', 'csrf_token' => csrf_token(), 'session_refreshed_at' => now()->toDateTimeString()]);
        }

        return response()->json(['success' => false, 'message' => 'Not authenticated', 'requires_relogin' => true], 401);
    }

    public function getCsrfToken(Request $request)
    {
        return response()->json(['csrf_token' => csrf_token()]);
    }

    public function checkSession(Request $request)
    {
        if (Auth::guard('vendor')->check()) {
            $request->session()->put('last_activity', time());

            return response()->json([
                'success' => true,
                'authenticated' => true,
                'user' => [
                    'id' => Auth::guard('vendor')->id(),
                    'email' => Auth::guard('vendor')->user()->email,
                ],
                'csrf_token' => csrf_token(),
                'session_lifetime' => config('session.lifetime', 40),
            ]);
        }

        return response()->json(['success' => false, 'authenticated' => false, 'message' => 'Session not found or expired'], 401);
    }

    public function checkAuth(Request $request)
    {
        if (Auth::guard('vendor')->check()) {
            return response()->json(['success' => true, 'authenticated' => true, 'user' => [
                'id' => Auth::guard('vendor')->id(),
                'email' => Auth::guard('vendor')->user()->email,
            ]]);
        }

        return response()->json(['success' => false, 'authenticated' => false, 'message' => 'Not authenticated'], 401);
    }
}
