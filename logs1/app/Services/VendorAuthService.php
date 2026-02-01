<?php

namespace App\Services;

use App\Models\VendorAccount;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class VendorAuthService
{
    public function login($credentials)
    {
        $vendor = VendorAccount::where('email', $credentials['email'])->first();

        if (! $vendor) {
            return ['success' => false, 'message' => 'Invalid credentials'];
        }

        $passwordValid = Hash::check($credentials['password'], $vendor->password)
            || $vendor->password === $credentials['password'];

        if (! $passwordValid) {
            return ['success' => false, 'message' => 'Invalid credentials'];
        }

        if ($vendor->status !== 'active') {
            return ['success' => false, 'message' => 'Account is inactive'];
        }

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $vendor->update(['otp' => $otp, 'otp_expires_at' => Carbon::now()->addMinutes(10)]);
        Log::info("Vendor OTP generated for {$vendor->email}: {$otp}, expires at: {$vendor->otp_expires_at}");
        $this->sendOtpEmail($vendor->email, $otp, $vendor->firstname);

        return [
            'success' => true,
            'message' => 'OTP sent to your email',
            'email' => $vendor->email,
            'requires_otp' => true,
            'otp_debug' => $otp, // For debugging purposes
        ];
    }

    public function sendOtp($email)
    {
        $vendor = VendorAccount::where('email', $email)->first();
        if (! $vendor) {
            return ['success' => false, 'message' => 'Vendor not found'];
        }

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $vendor->update(['otp' => $otp, 'otp_expires_at' => Carbon::now()->addMinutes(10)]);
        Log::info("Vendor OTP regenerated for {$vendor->email}: {$otp}, expires at: {$vendor->otp_expires_at}");
        $this->sendOtpEmail($vendor->email, $otp, $vendor->firstname);

        return ['success' => true, 'message' => 'OTP sent successfully', 'email' => $vendor->email];
    }

    public function verifyOtp($email, $otp)
    {
        $vendor = VendorAccount::where('email', $email)->first();
        if (! $vendor) {
            return ['success' => false, 'message' => 'Vendor not found'];
        }

        Log::info("Vendor OTP verification attempt for {$email}");
        if (! $vendor->otp) {
            return ['success' => false, 'message' => 'No OTP found. Please request a new one.'];
        }
        if ($vendor->otp !== $otp) {
            return ['success' => false, 'message' => 'Invalid OTP code'];
        }
        if (! $vendor->otp_expires_at || Carbon::now()->gt($vendor->otp_expires_at)) {
            return ['success' => false, 'message' => 'OTP has expired. Please request a new one.'];
        }

        $vendor->update(['otp' => null, 'otp_expires_at' => null, 'email_verified_at' => Carbon::now(), 'last_login' => Carbon::now()]);
        Auth::guard('vendor')->login($vendor, true);

        Log::info("Vendor OTP verification successful for user: {$email}");

        $secret = config('app.key');
        if (is_string($secret) && str_starts_with($secret, 'base64:')) {
            $secret = base64_decode(substr($secret, 7));
        }

        if (empty($secret)) {
            Log::error('JWT Secret (app.key) is missing or empty.');
            $secret = env('JWT_SECRET'); // Fallback to env
        }

        try {
            if (empty($secret)) {
                throw new \Exception('Application key is not set.');
            }

            // Generate JWT Token
            $expiresAt = Carbon::now()->addHours(2)->timestamp;
            
            // Ensure roles is a string or array, handle potential null
            $roles = $vendor->roles ?? 'vendor';
            
            $payload = [
                'iss' => config('app.url') ?? 'logs1',
                'sub' => $vendor->id,
                'email' => $vendor->email,
                'roles' => $roles,
                'iat' => Carbon::now()->timestamp,
                'exp' => $expiresAt,
            ];

            if (!class_exists(\Firebase\JWT\JWT::class)) {
                throw new \Exception('Firebase\JWT\JWT class not found. Please check composer dependencies.');
            }

            $token = \Firebase\JWT\JWT::encode($payload, $secret, 'HS256');
        } catch (\Throwable $e) {
            // CRITICAL: Logout the user if token generation fails to prevent inconsistent state
            Auth::guard('vendor')->logout();
            request()->session()->invalidate();
            
            Log::error('JWT Token Generation Error for Vendor: ' . $e->getMessage());
            Log::error('JWT Payload Trace: ' . json_encode($payload ?? []));
            
            return [
                'success' => false,
                'message' => 'Token Generation Failed: ' . $e->getMessage(), // Exposed for debugging
                'error_debug' => $e->getMessage(),
                'vendor' => $vendor
            ];
        }

        return [
            'success' => true,
            'message' => 'OTP verified successfully',
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::createFromTimestamp($expiresAt)->toIso8601String(),
            'expires_in' => 7200,
            'user' => [
                'id' => $vendor->id,
                'vendorid' => $vendor->vendorid,
                'firstname' => $vendor->firstname,
                'lastname' => $vendor->lastname,
                'email' => $vendor->email,
                'roles' => $vendor->roles,
                'picture' => $vendor->picture,
            ],
        ];
    }

    public function logout()
    {
        Auth::guard('vendor')->logout();
        try {
            request()->session()->invalidate();
            request()->session()->regenerateToken();
        } catch (\Exception $e) {
        }

        return ['success' => true, 'message' => 'Successfully logged out'];
    }

    public function getCurrentUser()
    {
        $vendor = Auth::guard('vendor')->user();
        if (! $vendor) {
            return ['success' => false, 'message' => 'Not authenticated'];
        }

        return [
            'success' => true,
            'user' => [
                'id' => $vendor->id,
                'vendorid' => $vendor->vendorid,
                'firstname' => $vendor->firstname,
                'lastname' => $vendor->lastname,
                'email' => $vendor->email,
                'roles' => $vendor->roles,
                'picture' => $vendor->picture,
                'status' => $vendor->status,
            ],
        ];
    }

    private function sendOtpEmail($email, $otp, $name)
    {
        $data = ['name' => $name, 'otp' => $otp, 'expires_in' => 10, 'brand' => 'Microfinancial Vendors'];
        try {
            Mail::send('emails.otp', $data, function ($message) use ($email, $name) {
                $message->to($email, $name)
                    ->subject('Your OTP Code - Microfinancial Vendors')
                    ->from('logistic1.microfinancial@gmail.com', 'Microfinancial Vendors');
            });
            Log::info("Vendor OTP email sent successfully to: {$email}");
        } catch (\Exception $e) {
            Log::error('Vendor OTP Email failed: '.$e->getMessage());
        }
    }
}
