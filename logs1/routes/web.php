<?php

use App\Http\Controllers\FrontendController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

// Splash on root (fresh run) - changed to login for initial load
Route::view('/', 'auth.login')->name('root');

// Custom auth views/routes (no real auth checks)
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// OTP Verification Route
Route::get('/otp-verification', [FrontendController::class, 'showOtpVerification'])->name('otp.verification');

// Splash pages (standalone) - with authentication check
Route::get('/login-splash', function (Request $request) {
    // Check if user is authenticated via cookie
    $isAuthenticated = isset($_COOKIE['isAuthenticated']) && $_COOKIE['isAuthenticated'] === 'true';
    if (!$isAuthenticated) {
        return redirect()->route('login');
    }

    // Get user data from cookie
    $user = null;
    if (isset($_COOKIE['user'])) {
        $user = json_decode($_COOKIE['user'], true);
    }

    return view('auth.login-splash', [
        'user' => $user // Pass user data to the view
    ]);
})->name('login.splash');

Route::get('/logout-splash', function (Request $request) {
    $timeoutReason = $request->get('timeout', 'normal');
    $message = 'You have been logged out successfully.';
    
    return view('auth.logout-splash', [
        'message' => $message,
        'timeout_reason' => $timeoutReason
    ]);
})->name('logout.splash');

// Logout route section start - direct to logout splash
Route::get('/logout', function (Request $request) {
    $user = null;
    $userCookie = isset($_COOKIE['user']) ? json_decode($_COOKIE['user'], true) : null;
    
    if ($userCookie) {
        Log::info('User logout', ['user_id' => $userCookie['id'], 'email' => $userCookie['Email']]);
    }

    // Clear all authentication data
    $cookies = ['isAuthenticated', 'user', 'lastActivity', 'sessionStart', 'browserSession'];
    foreach ($cookies as $cookie) {
        setcookie($cookie, '', time() - 3600, '/');
    }

    // Clear session storage
    session()->flush();

    return redirect()->route('logout.splash');
})->name('logout');
// Logout route section end

// Login processing route section start
Route::post('/process-login', [FrontendController::class, 'processLogin'])->name('process.login');
// Login processing route section end

// OTP section routes start
Route::post('/api/auth/verify-otp', [FrontendController::class, 'verifyOtp'])->name('api.auth.verify-otp');
Route::post('/api/auth/resend-otp', [FrontendController::class, 'resendOtp'])->name('api.auth.resend-otp');
// OTP section routes end

// account-profile section route start
Route::post('/api/profile/update', [FrontendController::class, 'updateProfile'])->name('api.profile.update');
// account-profile section route end

// Protected routes section - with authentication middleware start
Route::middleware(['web.auth'])->group(function () {
    Route::get('/dashboard', [FrontendController::class, 'dashboard'])->name('dashboard');

    // SWS gateway route section start
    Route::get('/modules/sws/warehousing', [FrontendController::class, 'swsWarehousing'])->name('modules.sws.warehousing');
    Route::get('/modules/sws/restock', [FrontendController::class, 'swsRestock'])->name('modules.sws.restock');
    // SWS gateway route section end

    // PSM gateway route section start
    Route::get('/modules/psm/vendor-management', [FrontendController::class, 'psmVendorManagement'])->name('modules.psm.vendor-management');
    Route::get('/modules/psm/vendor-quote', [FrontendController::class, 'psmVendorQuote'])->name('modules.psm.vendor-quote');
    Route::get('/modules/psm/purchase-management', [FrontendController::class, 'psmPurchaseManagement'])->name('modules.psm.purchase-management');
    // PSM gateway route section end    

    // PLT gateway route section start
    Route::get('/modules/plt/logistics', [FrontendController::class, 'pltLogistics'])->name('modules.plt.logistics');
    // PLT gateway route section end

    // ALMS gateway route section start
    Route::get('/modules/alms/asset', [FrontendController::class, 'almsAsset'])->name('modules.alms.asset');
    Route::get('/modules/alms/maintenance', [FrontendController::class, 'almsMaintenance'])->name('modules.alms.maintenance');
    // ALMS gateway route section end

    // DTLR gateway route section start
    Route::get('/modules/dtlr/documents', [FrontendController::class, 'dtlrDocuments'])->name('modules.dtlr.documents');
    Route::get('/modules/dtlr/logistics', [FrontendController::class, 'dtlrLogistics'])->name('modules.dtlr.logistics');
    // DTLR gateway route section end
});
// Protected routes section - with authentication middleware end

// PSM Purchase Management Proxy Routes
Route::prefix('api/psm/purchase')->group(function () {
    Route::get('/{endpoint}', [FrontendController::class, 'psmPurchaseProxyGet'])->where('endpoint', '.*');
    Route::post('/{endpoint}', [FrontendController::class, 'psmPurchaseProxyPost'])->where('endpoint', '.*');
    Route::put('/{endpoint}', [FrontendController::class, 'psmPurchaseProxyPut'])->where('endpoint', '.*');
    Route::delete('/{endpoint}', [FrontendController::class, 'psmPurchaseProxyDelete'])->where('endpoint', '.*');
});

// SWS Inventory Flow Proxy Routes
Route::prefix('api/sws/warehousing')->group(function () {
    Route::get('/{endpoint}', [FrontendController::class, 'swsWarehousingProxyGet'])->where('endpoint', '.*');
    Route::post('/{endpoint}', [FrontendController::class, 'swsWarehousingProxyPost'])->where('endpoint', '.*');
    Route::put('/{endpoint}', [FrontendController::class, 'swsWarehousingProxyPut'])->where('endpoint', '.*');
    Route::delete('/{endpoint}', [FrontendController::class, 'swsWarehousingProxyDelete'])->where('endpoint', '.*');
});

// SWS Digital Inventory Proxy Routes
Route::prefix('api/sws/digital')->group(function () {
    Route::get('/{endpoint}', [FrontendController::class, 'swsDigitalProxyGet'])->where('endpoint', '.*');
    Route::post('/{endpoint}', [FrontendController::class, 'swsDigitalProxyPost'])->where('endpoint', '.*');
    Route::put('/{endpoint}', [FrontendController::class, 'swsDigitalProxyPut'])->where('endpoint', '.*');
    Route::delete('/{endpoint}', [FrontendController::class, 'swsDigitalProxyDelete'])->where('endpoint', '.*');
});

// PLT Proxy Routes
Route::prefix('api/plt')->group(function () {
    Route::get('/{endpoint}', [FrontendController::class, 'pltProxyGet'])->where('endpoint', '.*');
    Route::post('/{endpoint}', [FrontendController::class, 'pltProxyPost'])->where('endpoint', '.*');
    Route::put('/{endpoint}', [FrontendController::class, 'pltProxyPut'])->where('endpoint', '.*');
    Route::delete('/{endpoint}', [FrontendController::class, 'pltProxyDelete'])->where('endpoint', '.*');
});

// DTLR Proxy Routes
Route::prefix('api/dtlr')->group(function () {
    Route::get('/{endpoint}', [FrontendController::class, 'dtlrProxyGet'])->where('endpoint', '.*');
    Route::post('/{endpoint}', [FrontendController::class, 'dtlrProxyPost'])->where('endpoint', '.*');
    Route::put('/{endpoint}', [FrontendController::class, 'dtlrProxyPut'])->where('endpoint', '.*');
    Route::delete('/{endpoint}', [FrontendController::class, 'dtlrProxyDelete'])->where('endpoint', '.*');
});