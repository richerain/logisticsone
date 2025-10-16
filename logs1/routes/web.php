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
Route::get('/login-splash', function () {
    // Check if user is authenticated via cookie
    $isAuthenticated = isset($_COOKIE['isAuthenticated']) && $_COOKIE['isAuthenticated'] === 'true';
    if (!$isAuthenticated) {
        return redirect()->route('login');
    }
    return view('auth.login-splash');
})->name('login.splash');

Route::get('/logout-splash', function (Request $request) {
    $timeoutReason = $request->get('timeout', 'normal');
    $message = 'You have been logged out successfully.';
    
    return view('auth.logout-splash', [
        'message' => $message,
        'timeout_reason' => $timeoutReason
    ]);
})->name('logout.splash');

// Logout route - direct to logout splash
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

// Session routes (kept for compatibility but timeout disabled)
Route::get('/api/session/check', [SessionController::class, 'checkSession'])->name('api.session.check');
Route::post('/api/session/extend', [SessionController::class, 'extendSession'])->name('api.session.extend');
Route::get('/api/session/info', [SessionController::class, 'getSessionInfo'])->name('api.session.info');
Route::post('/api/session/initialize', [SessionController::class, 'initializeSession'])->name('api.session.initialize');
Route::post('/api/session/handle-timeout', [SessionController::class, 'handleTimeout'])->name('api.session.handle-timeout');

// Redirect /home to dashboard (default post-login)
Route::get('/home', function () {
    return redirect()->route('dashboard');
});

// Login processing route
Route::post('/process-login', [FrontendController::class, 'processLogin'])->name('process.login');

// OTP routes
Route::post('/api/auth/verify-otp', [FrontendController::class, 'verifyOtp'])->name('api.auth.verify-otp');
Route::post('/api/auth/resend-otp', [FrontendController::class, 'resendOtp'])->name('api.auth.resend-otp');

// Protected routes - with authentication middleware
Route::middleware(['web.auth'])->group(function () {
    Route::get('/dashboard', [FrontendController::class, 'dashboard'])->name('dashboard');

    // SWS
    Route::get('/modules/sws/inventory', [FrontendController::class, 'swsInventory'])->name('modules.sws.inventory');
    Route::get('/modules/sws/storage', [FrontendController::class, 'swsStorage'])->name('modules.sws.storage');
    Route::get('/modules/sws/restock', [FrontendController::class, 'swsRestock'])->name('modules.sws.restock');

    // PSM
    Route::get('/modules/psm/vendor-management', [FrontendController::class, 'psmVendorManagement'])->name('modules.psm.vendor-management');
    Route::get('/modules/psm/vendor-market', [FrontendController::class, 'psmVendorMarket'])->name('modules.psm.vendor-market');
    Route::get('/modules/psm/order-management', [FrontendController::class, 'psmOrderManagement'])->name('modules.psm.order-management');
    Route::get('/modules/psm/budget-approval', [FrontendController::class, 'psmBudgetApproval'])->name('modules.psm.budget-approval');
    Route::get('/modules/psm/place-order', [FrontendController::class, 'psmPlaceOrder'])->name('modules.psm.place-order');
    Route::get('/modules/psm/reorder-management', [FrontendController::class, 'psmReorderManagement'])->name('modules.psm.reorder-management');
    Route::get('/modules/psm/products-management', [FrontendController::class, 'psmProductsManagement'])->name('modules.psm.products-management');
    Route::get('/modules/psm/shop-management', [FrontendController::class, 'psmShopManagement'])->name('modules.psm.shop-management');
    
    // PLT
    Route::get('/modules/plt/projects', [FrontendController::class, 'pltProjects'])->name('modules.plt.projects');
    Route::get('/modules/plt/dispatches', [FrontendController::class, 'pltDispatches'])->name('modules.plt.dispatches');
    Route::get('/modules/plt/resources', [FrontendController::class, 'pltResources'])->name('modules.plt.resources');
    Route::get('/modules/plt/allocations', [FrontendController::class, 'pltAllocations'])->name('modules.plt.allocations');
    Route::get('/modules/plt/milestones', [FrontendController::class, 'pltMilestones'])->name('modules.plt.milestones');
    Route::get('/modules/plt/tracking-logs', [FrontendController::class, 'pltTrackingLogs'])->name('modules.plt.tracking-logs');

    // ALMS
    Route::get('/modules/alms/registration', [FrontendController::class, 'almsRegistration'])->name('modules.alms.registration');
    Route::get('/modules/alms/scheduling', [FrontendController::class, 'almsScheduling'])->name('modules.alms.scheduling');

    // DTLR
    Route::get('/modules/dtlr/upload', [FrontendController::class, 'dtlrUpload'])->name('modules.dtlr.upload');
    Route::get('/modules/dtlr/documents', [FrontendController::class, 'dtlrDocuments'])->name('modules.dtlr.documents');
    Route::get('/modules/dtlr/logs', [FrontendController::class, 'dtlrLogs'])->name('modules.dtlr.logs');
    Route::get('/modules/dtlr/reviews', [FrontendController::class, 'dtlrReviews'])->name('modules.dtlr.reviews');
});

// profile-route
Route::post('/api/profile/update', [FrontendController::class, 'updateProfile'])->name('api.profile.update');