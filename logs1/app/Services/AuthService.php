<?php

namespace App\Services;

use App\Models\SWS\User;
use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function login($credentials)
    {
        $user = $this->userRepository->findByEmail($credentials['email']);

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return [
                'success' => false,
                'message' => 'Invalid credentials'
            ];
        }

        if ($user->status !== 'active') {
            return [
                'success' => false,
                'message' => 'Account is inactive'
            ];
        }

        // Generate OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10)
        ]);

        // Send OTP via Email
        $this->sendOtpEmail($user->email, $otp, $user->firstname);

        // Store email in session for OTP verification
        Session::put('otp_email', $user->email);

        return [
            'success' => true,
            'message' => 'OTP sent to your email',
            'email' => $user->email,
            'requires_otp' => true
        ];
    }

    public function sendOtp($email)
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'User not found'
            ];
        }

        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        $user->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10)
        ]);

        $this->sendOtpEmail($user->email, $otp, $user->firstname);

        // Store email in session for OTP verification
        Session::put('otp_email', $user->email);

        return [
            'success' => true,
            'message' => 'OTP sent successfully',
            'email' => $user->email
        ];
    }

    public function verifyOtp($email, $otp)
    {
        $user = $this->userRepository->findByEmail($email);

        if (!$user) {
            return [
                'success' => false,
                'message' => 'User not found'
            ];
        }

        if ($user->otp !== $otp || now()->gt($user->otp_expires_at)) {
            return [
                'success' => false,
                'message' => 'Invalid or expired OTP'
            ];
        }

        // Clear OTP
        $user->update([
            'otp' => null,
            'otp_expires_at' => null,
            'email_verified_at' => now()
        ]);

        // Log the user in using the correct guard
        Auth::guard('sws')->login($user);

        return [
            'success' => true,
            'message' => 'OTP verified successfully',
            'user' => [
                'id' => $user->id,
                'employeeid' => $user->employeeid,
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'email' => $user->email,
                'roles' => $user->roles,
                'picture' => $user->picture
            ]
        ];
    }

    public function logout()
    {
        Auth::guard('sws')->logout();
        Session::invalidate();
        Session::regenerateToken();

        return [
            'success' => true,
            'message' => 'Successfully logged out'
        ];
    }

    public function getCurrentUser()
    {
        $user = Auth::guard('sws')->user();

        if (!$user) {
            return [
                'success' => false,
                'message' => 'Not authenticated'
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
                'status' => $user->status
            ]
        ];
    }

    private function sendOtpEmail($email, $otp, $name)
    {
        $data = [
            'name' => $name,
            'otp' => $otp,
            'expires_in' => 10
        ];

        try {
            Mail::send('emails.otp', $data, function($message) use ($email, $name) {
                $message->to($email, $name)
                        ->subject('Your OTP Code - Microfinancial Logistics I');
                $message->from('logistic1.microfinancial@gmail.com', 'Microfinancial Logistics I');
            });
        } catch (\Exception $e) {
            \Log::error('OTP Email failed: ' . $e->getMessage());
        }
    }
}