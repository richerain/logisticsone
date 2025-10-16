<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use App\Models\User;
use Illuminate\Support\Str;

class OtpController extends Controller
{
    // Generate and send OTP
    public function generateOtp(Request $request)
    {
        Log::info('🎯 OTP generation requested', $request->all());

        $validator = \Validator::make($request->all(), [
            'email' => 'required|email',
            'user_id' => 'required|integer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::where('Email', $request->email)
                        ->where('id', $request->user_id)
                        ->where('status', 'active')
                        ->first();

            if (!$user) {
                Log::warning('❌ User not found or inactive', ['email' => $request->email]);
                return response()->json([
                    'success' => false,
                    'message' => 'User not found or inactive'
                ], 404);
            }

            // Generate 6-digit OTP
            $otpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $sessionId = Str::uuid()->toString();

            // Store OTP in cache for 10 minutes
            Cache::put('otp_' . $sessionId, [
                'code' => $otpCode,
                'user_id' => $user->id,
                'email' => $user->Email,
                'attempts' => 0,
                'created_at' => now()
            ], 600); // 10 minutes

            // Send OTP via email to real user email
            $emailSent = $this->sendOtpEmail($user->Email, $otpCode, $user->firstname);

            if (!$emailSent) {
                Log::error('❌ Failed to send OTP email', ['email' => $user->Email]);
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to send OTP email. Please try again.'
                ], 500);
            }

            Log::info('✅ OTP generated and sent to real email', [
                'session_id' => $sessionId,
                'user_id' => $user->id,
                'email' => $user->Email
            ]);

            return response()->json([
                'success' => true,
                'session_id' => $sessionId,
                'message' => 'OTP sent successfully to your email address'
            ]);

        } catch (\Exception $e) {
            Log::error('❌ OTP generation error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to generate OTP. Please try again.'
            ], 500);
        }
    }

    // Verify OTP
    public function verifyOtp(Request $request)
    {
        Log::info('🔐 OTP verification requested', $request->all());

        $validator = \Validator::make($request->all(), [
            'session_id' => 'required|string',
            'otp_code' => 'required|string|size:6',
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $cacheKey = 'otp_' . $request->session_id;
            $otpData = Cache::get($cacheKey);

            if (!$otpData) {
                Log::warning('❌ OTP session expired', ['session_id' => $request->session_id]);
                return response()->json([
                    'success' => false,
                    'message' => 'OTP session expired or invalid'
                ], 400);
            }

            // Check attempts (max 5 attempts)
            if ($otpData['attempts'] >= 5) {
                Cache::forget($cacheKey);
                Log::warning('🚫 Too many OTP attempts', ['session_id' => $request->session_id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Too many failed attempts. Please request a new OTP.'
                ], 400);
            }

            // Verify OTP code
            if ($otpData['code'] !== $request->otp_code) {
                $otpData['attempts']++;
                Cache::put($cacheKey, $otpData, 600);

                $attemptsLeft = 5 - $otpData['attempts'];
                Log::warning('❌ Invalid OTP attempt', [
                    'session_id' => $request->session_id,
                    'attempts' => $otpData['attempts'],
                    'remaining' => $attemptsLeft
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid OTP code. ' . $attemptsLeft . ' attempt(s) remaining.'
                ], 400);
            }

            // OTP verified successfully
            $user = User::where('id', $otpData['user_id'])
                        ->where('status', 'active')
                        ->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found or inactive'
                ], 404);
            }

            // Clear OTP from cache
            Cache::forget($cacheKey);

            // Prepare user data
            $userData = [
                'id' => $user->id,
                'employee_id' => $user->employee_id,
                'firstname' => $user->firstname,
                'lastname' => $user->lastname,
                'middlename' => $user->middlename,
                'Email' => $user->Email,
                'roles' => $user->roles,
                'status' => $user->status,
                'sex' => $user->sex,
                'age' => $user->age,
                'contactnum' => $user->contactnum,
                'birthdate' => $user->birthdate,
                'profile_picture' => $user->profile_picture,
            ];

            Log::info('✅ OTP verification successful', ['user_id' => $user->id]);

            return response()->json([
                'success' => true,
                'user' => $userData,
                'message' => 'OTP verified successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('❌ OTP verification error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'OTP verification failed. Please try again.'
            ], 500);
        }
    }

    // Resend OTP
    public function resendOtp(Request $request)
    {
        Log::info('🔄 OTP resend requested', $request->all());

        $validator = \Validator::make($request->all(), [
            'session_id' => 'required|string',
            'email' => 'required|email'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $cacheKey = 'otp_' . $request->session_id;
            $otpData = Cache::get($cacheKey);

            if (!$otpData) {
                return response()->json([
                    'success' => false,
                    'message' => 'OTP session expired or invalid'
                ], 400);
            }

            $user = User::where('id', $otpData['user_id'])
                        ->where('Email', $request->email)
                        ->where('status', 'active')
                        ->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found or inactive'
                ], 404);
            }

            // Generate new OTP
            $newOtpCode = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
            $newSessionId = Str::uuid()->toString();

            // Store new OTP in cache
            Cache::put('otp_' . $newSessionId, [
                'code' => $newOtpCode,
                'user_id' => $user->id,
                'email' => $user->Email,
                'attempts' => 0,
                'created_at' => now()
            ], 600);

            // Clear old OTP
            Cache::forget($cacheKey);

            // Send new OTP via email to real user email
            $emailSent = $this->sendOtpEmail($user->Email, $newOtpCode, $user->firstname);

            if (!$emailSent) {
                Log::error('❌ Failed to resend OTP email', ['email' => $user->Email]);
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to resend OTP email. Please try again.'
                ], 500);
            }

            Log::info('✅ OTP resent successfully to real email', [
                'new_session_id' => $newSessionId,
                'user_id' => $user->id
            ]);

            return response()->json([
                'success' => true,
                'session_id' => $newSessionId,
                'message' => 'New OTP sent successfully to your email address'
            ]);

        } catch (\Exception $e) {
            Log::error('❌ OTP resend error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to resend OTP. Please try again.'
            ], 500);
        }
    }

    // Send OTP email to REAL email address
    private function sendOtpEmail($email, $otpCode, $firstName)
    {
        try {
            $subject = 'Your OTP Code - Microfinancial Logistics System';
            
            $emailContent = "
            <!DOCTYPE html>
            <html>
            <head>
                <style>
                    body { 
                        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; 
                        background-color: #f8fafc; 
                        padding: 20px; 
                        margin: 0;
                        line-height: 1.6;
                    }
                    .container { 
                        background-color: #ffffff; 
                        padding: 40px; 
                        border-radius: 15px; 
                        box-shadow: 0 10px 25px rgba(0,0,0,0.1); 
                        max-width: 600px;
                        margin: 0 auto;
                        border: 1px solid #e2e8f0;
                    }
                    .header { 
                        background: linear-gradient(135deg, #16a34a, #22c55e);
                        padding: 30px;
                        border-radius: 15px 15px 0 0;
                        text-align: center;
                        color: white;
                        margin: -40px -40px 30px -40px;
                    }
                    .header h1 {
                        margin: 0;
                        font-size: 28px;
                        font-weight: 700;
                    }
                    .header h2 {
                        margin: 10px 0 0 0;
                        font-size: 20px;
                        font-weight: 500;
                        opacity: 0.9;
                    }
                    .otp-code { 
                        font-size: 48px; 
                        font-weight: bold; 
                        color: #16a34a; 
                        text-align: center; 
                        letter-spacing: 10px; 
                        margin: 40px 0;
                        padding: 20px;
                        background-color: #f0fdf4;
                        border: 3px dashed #16a34a;
                        border-radius: 12px;
                        font-family: 'Courier New', monospace;
                    }
                    .footer { 
                        margin-top: 40px; 
                        padding-top: 25px; 
                        border-top: 2px solid #e2e8f0; 
                        color: #64748b; 
                        font-size: 14px; 
                        text-align: center;
                    }
                    .warning {
                        background-color: #fffbeb;
                        border: 2px solid #f59e0b;
                        padding: 15px;
                        border-radius: 8px;
                        margin: 25px 0;
                        font-size: 14px;
                        color: #92400e;
                    }
                    .info-box {
                        background-color: #f8fafc;
                        border-left: 4px solid #3b82f6;
                        padding: 15px;
                        margin: 20px 0;
                        border-radius: 0 8px 8px 0;
                    }
                    .greeting {
                        color: #1e293b;
                        font-size: 18px;
                        margin-bottom: 20px;
                    }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h1>Microfinancial Logistics</h1>
                        <h2>Secure OTP Verification</h2>
                    </div>
                    
                    <div class='greeting'>Hello <strong>{$firstName}</strong>,</div>
                    
                    <p>You're just one step away from accessing your account. Your One-Time Password (OTP) for login verification is:</p>
                    
                    <div class='otp-code'>{$otpCode}</div>
                    
                    <div class='info-box'>
                        <strong>⏰ Valid for:</strong> 10 minutes<br>
                        <strong>📱 Use this code in:</strong> OTP verification page
                    </div>
                    
                    <div class='warning'>
                        <strong⚠️ IMPORTANT SECURITY NOTICE:</strong><br>
                        • Never share this OTP with anyone<br>
                        • Microfinancial will never ask for your OTP or password<br>
                        • If you didn't request this, please contact support immediately
                    </div>
                    
                    <p>Enter this code in the verification page to complete your login and access the Microfinancial Logistics System.</p>
                    
                    <div class='footer'>
                        <p><strong>Microfinancial Logistics I Department</strong><br>
                        Secure Access System | Automated Security Message<br>
                        © " . date('Y') . " Microfinancial. All rights reserved.</p>
                        <p style='font-size: 12px; color: #94a3b8;'>This is an automated message. Please do not reply to this email.</p>
                    </div>
                </div>
            </body>
            </html>
            ";

            // Log email sending attempt
            Log::info('📧 SENDING OTP TO REAL EMAIL:', [
                'to' => $email,
                'subject' => $subject,
                'mail_config' => [
                    'host' => env('MAIL_HOST'),
                    'port' => env('MAIL_PORT'),
                    'from' => env('MAIL_FROM_ADDRESS')
                ]
            ]);

            // Send email using Gmail SMTP
            Mail::html($emailContent, function ($message) use ($email, $subject) {
                $message->to($email)
                        ->subject($subject)
                        ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            });

            Log::info('✅ OTP email sent successfully to real email address', ['email' => $email]);
            return true;

        } catch (\Exception $e) {
            Log::error('❌ REAL EMAIL SENDING ERROR: ' . $e->getMessage());
            Log::info('🔑 OTP CODE FOR MANUAL ENTRY: ' . $otpCode . ' for ' . $email);
            
            return false;
        }
    }

    // Test real email configuration
    public function testRealEmail(Request $request)
    {
        try {
            $email = $request->email ?? env('MAIL_FROM_ADDRESS'); // Test with system email or provided email
            $otpCode = '654321';
            $firstName = 'Test User';

            Log::info('🧪 Testing REAL email configuration...', ['test_email' => $email]);

            $result = $this->sendOtpEmail($email, $otpCode, $firstName);

            return response()->json([
                'success' => $result,
                'message' => $result ? 'Test email sent successfully to REAL email address' : 'Failed to send test email',
                'test_email' => $email,
                'debug_info' => [
                    'mail_host' => env('MAIL_HOST'),
                    'mail_port' => env('MAIL_PORT'),
                    'mail_from' => env('MAIL_FROM_ADDRESS')
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Real email test error: ' . $e->getMessage(),
                'debug_info' => [
                    'mail_host' => env('MAIL_HOST'),
                    'mail_port' => env('MAIL_PORT'),
                    'mail_username' => env('MAIL_USERNAME'),
                    'mail_from' => env('MAIL_FROM_ADDRESS')
                ]
            ], 500);
        }
    }
}