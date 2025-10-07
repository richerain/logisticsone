<?php

use App\Http\Controllers\FrontendController;
use Illuminate\Support\Facades\Route;

// Splash on root (fresh run) - changed to login for initial load
Route::view('/', 'auth.login')->name('root');

// Custom auth views/routes (no real auth checks)
Route::get('/login', function () {
    // Check if user is already logged in via multiple methods
    $isAuthenticated = false;
    
    // Check cookie
    if (isset($_COOKIE['isAuthenticated']) && $_COOKIE['isAuthenticated'] === 'true') {
        $isAuthenticated = true;
    }
    
    // Check localStorage via JavaScript will handle on frontend
    if ($isAuthenticated) {
        return redirect()->route('dashboard');
    }
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Splash pages (standalone) - with authentication check
Route::get('/login-splash', function () {
    // Check if user is authenticated via cookie
    $isAuthenticated = isset($_COOKIE['isAuthenticated']) && $_COOKIE['isAuthenticated'] === 'true';
    if (!$isAuthenticated) {
        return redirect()->route('login');
    }
    return view('auth.login-splash');
})->name('login.splash');

Route::view('/logout-splash', 'auth.logout-splash')->name('logout.splash');

// Logout route - direct to logout splash
Route::get('/logout', function () {
    // Clear all authentication data
    setcookie('isAuthenticated', '', time() - 3600, '/');
    setcookie('user', '', time() - 3600, '/');
    
    return redirect()->route('logout.splash');
})->name('logout');

// Redirect /home to dashboard (default post-login)
Route::get('/home', function () {
    return redirect()->route('dashboard');
});

// Login processing route
Route::post('/process-login', [FrontendController::class, 'processLogin'])->name('process.login');

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
    Route::get('/modules/plt/shipment', [FrontendController::class, 'pltShipment'])->name('modules.plt.shipment');
    Route::get('/modules/plt/route', [FrontendController::class, 'pltRoute'])->name('modules.plt.route');

    // ALMS
    Route::get('/modules/alms/registration', [FrontendController::class, 'almsRegistration'])->name('modules.alms.registration');
    Route::get('/modules/alms/scheduling', [FrontendController::class, 'almsScheduling'])->name('modules.alms.scheduling');

    // DTLR
    Route::get('/modules/dtlr/upload', [FrontendController::class, 'dtlrUpload'])->name('modules.dtlr.upload');
    Route::get('/modules/dtlr/logs', [FrontendController::class, 'dtlrLogs'])->name('modules.dtlr.logs');

    // User Management
    Route::get('/modules/user-management', [FrontendController::class, 'userManagement'])->name('modules.user-management');
});

// profile-route
Route::post('/api/profile/update', [FrontendController::class, 'updateProfile'])->name('api.profile.update');