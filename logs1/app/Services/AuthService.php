<?php

namespace App\Services;

use App\Models\EmployeeAccount;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class AuthService
{
    public function login($credentials)
    {
        $user = EmployeeAccount::where('email', $credentials['email'])->first();

        if (! $user) {
            return [
                'success' => false,
                'message' => 'Invalid credentials',
            ];
        }

        $passwordValid = Hash::check($credentials['password'], $user->password)
            || $user->password === $credentials['password'];

        if (! $passwordValid) {
            return [
                'success' => false,
                'message' => 'Invalid credentials',
            ];
        }

        if ($user->status !== 'active') {
            return [
                'success' => false,
                'message' => 'Account is inactive',
            ];
        }

        // Generate OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Update user with OTP and expiration
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // Log the OTP for debugging (remove in production)
        Log::info("OTP generated for {$user->email}: {$otp}, expires at: {$user->otp_expires_at}");

        // Send OTP via Email
        $this->sendOtpEmail($user->email, $otp, $user->firstname);

        return [
            'success' => true,
            'message' => 'OTP sent to your email',
            'email' => $user->email,
            'requires_otp' => true,
        ];
    }

    public function sendOtp($email)
    {
        $user = EmployeeAccount::where('email', $email)->first();

        if (! $user) {
            return [
                'success' => false,
                'message' => 'User not found',
            ];
        }

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        // Update user with OTP and expiration
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => Carbon::now()->addMinutes(10),
        ]);

        // Log the OTP for debugging (remove in production)
        Log::info("OTP regenerated for {$user->email}: {$otp}, expires at: {$user->otp_expires_at}");

        $this->sendOtpEmail($user->email, $otp, $user->firstname);

        return [
            'success' => true,
            'message' => 'OTP sent successfully',
            'email' => $user->email,
        ];
    }

    public function verifyOtp($email, $otp)
    {
        $user = EmployeeAccount::where('email', $email)->first();

        if (! $user) {
            Log::error("OTP verification failed: User not found for email: {$email}");

            return [
                'success' => false,
                'message' => 'User not found',
            ];
        }

        // Debug logging
        Log::info("OTP verification attempt for {$email}");
        Log::info("Stored OTP: {$user->otp}, Input OTP: {$otp}");
        Log::info("OTP expires at: {$user->otp_expires_at}, Current time: ".Carbon::now());

        // Check if OTP exists
        if (! $user->otp) {
            Log::error("OTP verification failed: No OTP found for user: {$email}");

            return [
                'success' => false,
                'message' => 'No OTP found. Please request a new one.',
            ];
        }

        // Check if OTP matches
        if ($user->otp !== $otp) {
            Log::error("OTP verification failed: OTP mismatch for user: {$email}");

            return [
                'success' => false,
                'message' => 'Invalid OTP code',
            ];
        }

        // Check if OTP is expired
        if (! $user->otp_expires_at || Carbon::now()->gt($user->otp_expires_at)) {
            Log::error("OTP verification failed: OTP expired for user: {$email}");
            Log::error('Current time: '.Carbon::now().", OTP expires: {$user->otp_expires_at}");

            return [
                'success' => false,
                'message' => 'OTP has expired. Please request a new one.',
            ];
        }

        $user->update([
            'otp' => null,
            'otp_expires_at' => null,
            'email_verified_at' => Carbon::now(),
        ]);

        Auth::guard('sws')->login($user, true);

        $user->update(['last_login' => Carbon::now()]);

        Log::info("OTP verification successful for user: {$email}");

        $secret = config('app.key');
        if (is_string($secret) && str_starts_with($secret, 'base64:')) {
            $secret = base64_decode(substr($secret, 7));
        }

        $expiresAt = Carbon::now()->addHours(2)->timestamp;
        $payload = [
            'iss' => config('app.url') ?? 'logs1',
            'sub' => $user->id,
            'email' => $user->email,
            'roles' => $user->roles,
            'iat' => Carbon::now()->timestamp,
            'exp' => $expiresAt,
        ];

        $token = JWT::encode($payload, $secret, 'HS256');

        return [
            'success' => true,
            'message' => 'OTP verified successfully',
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_at' => Carbon::createFromTimestamp($expiresAt)->toIso8601String(),
            'expires_in' => 7200,
            'user' => [
                'id' => $user->id,
                'employeeid' => $user->employeeid,
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'email' => $user->email,
                'roles' => $user->roles,
                'picture' => $user->picture,
            ],
        ];
    }

    public function logout()
    {
        Auth::guard('sws')->logout();
        // Invalidate session and regenerate CSRF token
        try {
            request()->session()->invalidate();
            request()->session()->regenerateToken();
        } catch (\Exception $e) {
            // ignore session invalidation errors
        }

        return [
            'success' => true,
            'message' => 'Successfully logged out',
        ];
    }

    public function getCurrentUser()
    {
        $user = Auth::guard('sws')->user();

        if (! $user) {
            return [
                'success' => false,
                'message' => 'Not authenticated',
            ];
        }

        return [
            'success' => true,
            'user' => [
                'id' => $user->id,
                'employeeid' => $user->employeeid,
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'email' => $user->email,
                'roles' => $user->roles,
                'picture' => $user->picture,
                'status' => $user->status,
            ],
        ];
    }

    private function sendOtpEmail($email, $otp, $name)
    {
        $data = [
            'name' => $name,
            'otp' => $otp,
            'expires_in' => 10,
            'brand' => 'Microfinancial Logistics I',
        ];

        try {
            Mail::send('emails.otp', $data, function ($message) use ($email, $name) {
                $message->to($email, $name)
                    ->subject('Your OTP Code - Microfinancial Logistics I');
                $message->from('logistic1.microfinancial@gmail.com', 'Microfinancial Logistics I');
            });
            Log::info("OTP email sent successfully to: {$email}");
        } catch (\Exception $e) {
            Log::error('OTP Email failed: '.$e->getMessage());
        }
    }
}
