<?php

namespace App\Http\Controllers;

use App\Models\EmployeeAccount;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    /**
     * Get a fresh API Token (JWT) for the authenticated user
     */
    public function getApiToken(Request $request)
    {
        $token = null;

        if (Auth::guard('sws')->check()) {
            $user = Auth::guard('sws')->user();
            $token = $this->authService->generateTokenForUser($user);
        } elseif (Auth::guard('vendor')->check()) {
            $user = Auth::guard('vendor')->user();
            // We need VendorAuthService for vendor tokens to ensure correct claims
            $vendorAuthService = app(\App\Services\VendorAuthService::class);
            $token = $vendorAuthService->generateTokenForUser($user);
        }

        if ($token) {
            return response()->json([
                'success' => true,
                'token' => $token,
                'token_type' => 'Bearer',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Unable to generate token. User may not be authenticated.',
        ], 401);
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
                'errors' => $validator->errors(),
            ], 422);
        }

        $result = $this->authService->login($request->only('email', 'password'));

        return response()->json($result, $result['success'] ? 200 : 401);
    }

    public function sendOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:main.employee_account,email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $result = $this->authService->sendOtp($request->email);

        return response()->json($result);
    }

    public function verifyOtp(Request $request)
    {
        Log::info('OTP verification started', ['email' => $request->email]);

        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:main.employee_account,email',
            'otp' => 'required|string|size:6',
        ]);

        if ($validator->fails()) {
            Log::error('OTP validation failed', ['errors' => $validator->errors()]);

            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $result = $this->authService->verifyOtp($request->email, $request->otp);

        // If OTP verification is successful, ensure session is properly set
        if ($result['success']) {
            Log::info('OTP verification successful, ensuring session persistence');

            try {
                // Force session save and regeneration
                $request->session()->save();
                $request->session()->regenerate();

                Log::info('Session regenerated and saved after OTP verification');
            } catch (\Exception $e) {
                Log::error('Failed to regenerate session after OTP verification', ['error' => $e->getMessage()]);
            }
        }

        return response()->json($result, $result['success'] ? 200 : 401);
    }

    public function logout(Request $request)
    {
        // Handle vendor logout if applicable
        if (Auth::guard('vendor')->check()) {
            Auth::guard('vendor')->logout();
        }

        $result = $this->authService->logout();

        return response()->json($result);
    }

    public function me(Request $request)
    {
        $result = $this->authService->getCurrentUser();

        return response()->json($result, $result['success'] ? 200 : 401);
    }

    public function updateProfile(Request $request)
    {
        if (! Auth::guard('sws')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated',
            ], 401);
        }

        /** @var EmployeeAccount $user */
        $user = Auth::guard('sws')->user();

        $rules = [
            'firstname' => ['required', 'string', 'max:255'],
            'middlename' => ['nullable', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'sex' => ['nullable', 'in:male,female'],
            'age' => ['nullable', 'integer', 'min:0', 'max:150'],
            'birthdate' => ['nullable', 'date'],
            'contactnum' => ['nullable', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255', 'unique:main.employee_account,email,'.$user->id],
            'address' => ['nullable', 'string'],
            'picture' => ['nullable', 'image', 'max:2048'],
            'remove_picture' => ['nullable', 'boolean'],
        ];

        $validator = Validator::make($request->all(), $rules);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $data = $validator->validated();

        if (! empty($data['remove_picture'])) {
            if ($user->picture) {
                $path = $user->picture;
                // Handle old storage path
                if (str_starts_with($path, 'storage/')) {
                    $storagePath = substr($path, 8);
                    if (Storage::disk('public')->exists($storagePath)) {
                        Storage::disk('public')->delete($storagePath);
                    }
                } 
                // Handle new public path
                else {
                    $publicPath = public_path($path);
                    if (file_exists($publicPath)) {
                        unlink($publicPath);
                    }
                }
            }
            $user->picture = null;
        }

        if ($request->hasFile('picture')) {
            $file = $request->file('picture');
            $filename = $file->hashName();
            $file->move(public_path('images/profile-picture'), $filename);

            if ($user->picture) {
                $oldPath = $user->picture;
                // Handle old storage path
                if (str_starts_with($oldPath, 'storage/')) {
                    $storagePath = substr($oldPath, 8);
                    if (Storage::disk('public')->exists($storagePath)) {
                        Storage::disk('public')->delete($storagePath);
                    }
                }
                // Handle new public path
                else {
                    $publicPath = public_path($oldPath);
                    if (file_exists($publicPath)) {
                        unlink($publicPath);
                    }
                }
            }
            $user->picture = 'images/profile-picture/' . $filename;
        }

        $user->firstname = $data['firstname'];
        $user->middlename = $data['middlename'] ?? null;
        $user->lastname = $data['lastname'];
        $user->sex = $data['sex'] ?? null;
        $user->age = $data['age'] ?? null;
        $user->birthdate = $data['birthdate'] ?? null;
        $user->contactnum = $data['contactnum'] ?? null;
        $user->email = $data['email'];
        $user->address = $data['address'] ?? null;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'user' => $user->fresh(),
        ]);
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
                'session_refreshed_at' => now()->toDateTimeString(),
            ]);
        }

        if (Auth::guard('vendor')->check()) {
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
                'session_refreshed_at' => now()->toDateTimeString(),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Not authenticated',
            'requires_relogin' => true,
        ], 401);
    }

    /**
     * Get current CSRF token (for when session is still valid but token might expire)
     */
    public function getCsrfToken(Request $request)
    {
        return response()->json([
            'csrf_token' => csrf_token(),
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
                    'role' => 'employee'
                ],
                'csrf_token' => csrf_token(),
                'session_lifetime' => config('session.lifetime', 40),
            ]);
        }

        if (Auth::guard('vendor')->check()) {
            // Update last activity
            $request->session()->put('last_activity', time());

            return response()->json([
                'success' => true,
                'authenticated' => true,
                'user' => [
                    'id' => Auth::guard('vendor')->id(),
                    'email' => Auth::guard('vendor')->user()->email,
                    'role' => 'vendor'
                ],
                'csrf_token' => csrf_token(),
                'session_lifetime' => config('session.lifetime', 40),
            ]);
        }

        return response()->json([
            'success' => false,
            'authenticated' => false,
            'message' => 'Session not found or expired',
        ], 401);
    }

    /**
     * Check if user is authenticated (for splash login redirect)
     */
    public function checkAuth(Request $request)
    {
        if (Auth::guard('sws')->check()) {
            return response()->json([
                'success' => true,
                'authenticated' => true,
                'user' => [
                    'id' => Auth::guard('sws')->id(),
                    'email' => Auth::guard('sws')->user()->email,
                    'role' => 'employee'
                ],
            ]);
        }

        if (Auth::guard('vendor')->check()) {
            return response()->json([
                'success' => true,
                'authenticated' => true,
                'user' => [
                    'id' => Auth::guard('vendor')->id(),
                    'email' => Auth::guard('vendor')->user()->email,
                    'role' => 'vendor'
                ],
            ]);
        }

        return response()->json([
            'success' => false,
            'authenticated' => false,
            'message' => 'Not authenticated',
        ], 401);
    }
}
