<?php

namespace App\Http\Controllers;

use App\Models\VendorAccount;
use App\Services\VendorAuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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

    public function updateProfile(Request $request)
    {
        if (! Auth::guard('vendor')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Not authenticated',
            ], 401);
        }

        /** @var VendorAccount $user */
        $user = Auth::guard('vendor')->user();

        $rules = [
            'firstname' => ['required', 'string', 'max:255'],
            'middlename' => ['nullable', 'string', 'max:255'],
            'lastname' => ['required', 'string', 'max:255'],
            'sex' => ['nullable', 'in:male,female'],
            'age' => ['nullable', 'integer', 'min:0', 'max:150'],
            'birthdate' => ['nullable', 'date'],
            'contactnum' => ['nullable', 'string', 'max:50'],
            'email' => ['required', 'email', 'max:255', 'unique:main.vendor_account,email,'.$user->id],
            'address' => ['nullable', 'string'],
            'company_type' => ['nullable', 'string', 'max:255'],
            'company_name' => ['nullable', 'string', 'max:255'],
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
        $user->company_type = $data['company_type'] ?? null;
        $user->company_name = $data['company_name'] ?? null;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
            'user' => $user->fresh(),
        ]);
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
