<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\SWSController;

// Auth routes
Route::post('/auth/login', [AuthController::class, 'login']);

// OTP routes with real email testing
Route::post('/auth/test-email', [OtpController::class, 'testEmail']);
Route::post('/auth/test-real-email', [OtpController::class, 'testRealEmail']);
Route::post('/auth/generate-otp', [OtpController::class, 'generateOtp']);
Route::post('/auth/verify-otp', [OtpController::class, 'verifyOtp']);
Route::post('/auth/resend-otp', [OtpController::class, 'resendOtp']);
Route::post('/auth/check-otp-session', [OtpController::class, 'checkOtpSession']);

Route::put('/profile/update', [ProfileController::class, 'update']);
Route::post('/profile/upload-picture', [ProfileController::class, 'uploadPicture']);

// SWS Warehousing routes - GRN Management (Primary endpoints)
Route::prefix('warehousing')->group(function () {
    Route::get('/', [SWSController::class, 'index']);
    Route::post('/', [SWSController::class, 'store']);
    Route::get('/stats/overview', [SWSController::class, 'getStats']);
    Route::get('/search/filter', [SWSController::class, 'search']);
    Route::get('/{id}', [SWSController::class, 'show']);
    Route::put('/{id}', [SWSController::class, 'update']);
    Route::delete('/{id}', [SWSController::class, 'destroy']);
});

// Legacy routes for backward compatibility with gateway
Route::get('/inventory', [SWSController::class, 'index']);
Route::post('/inventory', [SWSController::class, 'store']);
Route::get('/inventory/{id}', [SWSController::class, 'show']);
Route::put('/inventory/{id}', [SWSController::class, 'update']);
Route::delete('/inventory/{id}', [SWSController::class, 'destroy']);

Route::get('/storage', [SWSController::class, 'index']);
Route::post('/storage', [SWSController::class, 'store']);
Route::get('/storage/{id}', [SWSController::class, 'show']);
Route::put('/storage/{id}', [SWSController::class, 'update']);
Route::delete('/storage/{id}', [SWSController::class, 'destroy']);

Route::get('/restock', [SWSController::class, 'index']);
Route::post('/restock', [SWSController::class, 'store']);
Route::get('/restock/{id}', [SWSController::class, 'show']);
Route::put('/restock/{id}', [SWSController::class, 'update']);
Route::delete('/restock/{id}', [SWSController::class, 'destroy']);

// Debug route to check email configuration
Route::get('/debug/email-config', function() {
    return response()->json([
        'mail_host' => env('MAIL_HOST'),
        'mail_port' => env('MAIL_PORT'),
        'mail_username' => env('MAIL_USERNAME'),
        'mail_from_address' => env('MAIL_FROM_ADDRESS'),
        'mail_from_name' => env('MAIL_FROM_NAME'),
        'is_gmail' => str_contains(env('MAIL_HOST'), 'gmail.com')
    ]);
});