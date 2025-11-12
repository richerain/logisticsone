<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CheckSessionTimeout;

// Debug route to check OTP status
Route::get('/debug/otp-status/{email}', function ($email) {
    $user = \App\Models\SWS\User::where('email', $email)->first();
    
    if (!$user) {
        return response()->json(['error' => 'User not found'], 404);
    }
    
    return response()->json([
        'email' => $user->email,
        'otp' => $user->otp,
        'otp_expires_at' => $user->otp_expires_at,
        'current_time' => \Carbon\Carbon::now(),
        'is_expired' => $user->isOtpExpired(),
        'timezone' => config('app.timezone')
    ]);
});

// Public authentication routes - FIXED: Remove duplicate /api prefix
Route::prefix('auth')->group(function () {
    Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
    Route::post('/send-otp', [App\Http\Controllers\AuthController::class, 'sendOtp']);
    Route::post('/verify-otp', [App\Http\Controllers\AuthController::class, 'verifyOtp']);
    Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout']);
    Route::get('/me', [App\Http\Controllers\AuthController::class, 'me']);
    Route::get('/check-auth', [App\Http\Controllers\AuthController::class, 'checkAuth']);
    Route::post('/refresh-session', [App\Http\Controllers\AuthController::class, 'refreshSession']);
    Route::get('/check-session', [App\Http\Controllers\AuthController::class, 'checkSession']);
    Route::get('/csrf-token', [App\Http\Controllers\AuthController::class, 'getCsrfToken']);
});

// Public vendor info routes
Route::prefix('vendor-info')->group(function () {
    Route::get('/data', [App\Http\Controllers\PSMController::class, 'getVendorInfo']);
    Route::post('/update', [App\Http\Controllers\PSMController::class, 'updateVendorInfo']);
});

// Protected module routes
Route::middleware(['auth:sws', 'session.timeout'])->group(function () {
    Route::prefix('psm')->group(function () {
        require __DIR__ . '/modules/psm-api.php';
    });
    
    Route::prefix('sws')->group(function () {
        require __DIR__ . '/modules/sws-api.php';
    });
    
    Route::prefix('plt')->group(function () {
        require __DIR__ . '/modules/plt-api.php';
    });

    Route::prefix('alms')->group(function () {
        require __DIR__ . '/modules/alms-api.php';
    });

    Route::prefix('dtlr')->group(function () {
        require __DIR__ . '/modules/dtlr-api.php';
    });
});