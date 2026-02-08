<?php

use Illuminate\Support\Facades\Route;

// Debug route to check OTP status
Route::get('/debug/otp-status/{email}', function ($email) {
    $user = \App\Models\EmployeeAccount::where('email', $email)->first();

    if (! $user) {
        return response()->json(['error' => 'User not found'], 404);
    }

    return response()->json([
        'email' => $user->email,
        'otp' => $user->otp,
        'otp_expires_at' => $user->otp_expires_at,
        'current_time' => \Carbon\Carbon::now(),
        'is_expired' => $user->otp_expires_at ? \Carbon\Carbon::now()->gt($user->otp_expires_at) : true,
        'timezone' => config('app.timezone'),
    ]);
});

// Public authentication routes
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

// Vendor Authentication Routes
Route::prefix('vendor/auth')->group(function () {
    Route::post('/login', [App\Http\Controllers\VendorAuthController::class, 'login']);
    Route::post('/send-otp', [App\Http\Controllers\VendorAuthController::class, 'sendOtp']);
    Route::post('/verify-otp', [App\Http\Controllers\VendorAuthController::class, 'verifyOtp']);
    Route::post('/logout', [App\Http\Controllers\VendorAuthController::class, 'logout']);
    Route::get('/me', [App\Http\Controllers\VendorAuthController::class, 'me']);
    Route::get('/check-auth', [App\Http\Controllers\VendorAuthController::class, 'checkAuth']);
    Route::post('/refresh-session', [App\Http\Controllers\VendorAuthController::class, 'refreshSession']);
    Route::get('/check-session', [App\Http\Controllers\VendorAuthController::class, 'checkSession']);
    Route::get('/csrf-token', [App\Http\Controllers\VendorAuthController::class, 'getCsrfToken']);
});

// Public vendor info routes (for other modules)
Route::prefix('vendor-info')->group(function () {
    Route::get('/data', [App\Http\Controllers\PSMController::class, 'getVendorInfo']);
    Route::post('/update', [App\Http\Controllers\PSMController::class, 'updateVendorInfo']);
});

// Public PSM dataset preview (read-only)
Route::prefix('psm')->group(function () {
    Route::get('/vendors', [App\Http\Controllers\PSMController::class, 'getVendorManagement']);
    Route::get('/products', [App\Http\Controllers\PSMController::class, 'getProducts']);
});

// External Integration Routes (API Key Protected)
Route::middleware(['api.key'])->prefix('alms/external')->group(function () {
    Route::get('/maintenance', [App\Http\Controllers\ALMSController::class, 'getMaintenance']);
});

Route::middleware(['api.key'])->prefix('psm/external')->group(function () {
    Route::get('/budget-requests', [App\Http\Controllers\PSMController::class, 'getRequestBudgets']);
});

Route::middleware([
    'jwt.auth',
])->group(function () {

    // PSM Module Routes
    Route::prefix('psm')->group(function () {
        require __DIR__.'/modules/psm-api.php';
    });

    // SWS Module Routes
    Route::prefix('sws')->group(function () {
        require __DIR__.'/modules/sws-api.php';
    });

    // PLT Module Routes
    Route::prefix('plt')->group(function () {
        require __DIR__.'/modules/plt-api.php';
    });

    // ALMS Module Routes
    Route::prefix('alms')->group(function () {
        require __DIR__.'/modules/alms-api.php';
    });

    // DTLR Module Routes
    Route::prefix('dtlr')->group(function () {
        require __DIR__.'/modules/dtlr-api.php';
    });

    // User Management Module Routes
    Route::prefix('user-management')->group(function () {
        require __DIR__.'/modules/um-api.php';
    });
});
