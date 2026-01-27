<?php

use App\Http\Controllers\ALMSController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VendorAuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Simple session setup for auth routes
// Middleware is already applied by bootstrap/app.php
// Public Authentication Routes - FIXED: Proper route definitions
Route::get('/login', function (Request $request) {
    if (Auth::guard('sws')->check()) {
        return redirect('/home');
    }

    return view('login-auth.login');
})->name('login');

    Route::get('/login/vendor-portal', function (Request $request) {
        if (Auth::guard('vendor')->check()) {
            return redirect('/vendor/home');
        }

        return view('login-auth.vendor-login');
    })->name('login.vendor');

    Route::get('/otp-verification', function (Request $request) {
        if (Auth::guard('sws')->check()) {
            return redirect('/home');
        }
        if (Auth::guard('vendor')->check()) {
            return redirect('/vendor/home');
        }
        if (! $request->has('email')) {
            $portal = $request->get('portal');
            if ($portal === 'vendor') {
                return redirect('/login/vendor-portal');
            }

            return redirect('/login');
        }

        return view('login-auth.otp-verification', ['email' => $request->email, 'portal' => $request->get('portal')]);
    })->name('otp.verification');

    Route::get('/splash-login', function () {
        if (! Auth::guard('sws')->check()) {
            return redirect('/login');
        }

        return view('login-auth.splash-login');
    })->name('splash.login');

    Route::get('/vendor/splash-login', function () {
        if (! Auth::guard('vendor')->check()) {
            return redirect('/login/vendor-portal');
        }

        return view('login-auth.vendor-splash-login');
    })->name('vendor.splash.login');

    Route::get('/splash-logout', function () {
        return view('login-auth.splash-logout');
    })->name('splash.logout');

    Route::get('/vendor/splash-logout', function () {
        return view('login-auth.vendor-splash-logout');
    })->name('vendor.splash.logout');

    // Protected Routes - Using normal Laravel auth with session timeout
    Route::middleware(['auth:sws', 'session.timeout'])->group(function () {
        Route::get('/home', function () {
            return view('home');
        })->name('home');

        Route::get('/dashboard', function () {
            return view('dashboard.index');
        })->name('dashboard');

        // Module content loading routes
        Route::get('/module/{module}', function ($module) {
            // Map module names to their respective views
            $moduleViews = [
                'dashboard' => 'dashboard.index',
                'psm-purchase' => 'psm.purchase-management',
                'psm-vendor-management' => 'psm.vendor-management',
                'psm-budgeting' => 'psm.budgeting',
                'sws-inventory-flow' => 'sws.inventory-flow',
                'sws-digital-inventory' => 'sws.digital-inventory',
                'sws-warehouse-management' => 'sws.warehouse-management',
                'plt-logistics-projects' => 'plt.logistics-projects',
                'alms-asset-management' => 'alms.asset-management',
                'alms-maintenance-management' => 'alms.maintenance-management',
                'dtlr-document-tracker' => 'dtlr.document-tracker',
                'dtlr-logistics-record' => 'dtlr.logistics-record',
                'um-account-management' => 'user-management.account-management',
                'um-audit-trail' => 'user-management.audit-trail',
            ];

            $view = $moduleViews[$module] ?? 'components.module-not-found';

            // Restrict access to Budgeting module
            if ($module === 'psm-budgeting') {
                $user = Auth::guard('sws')->user();
                $role = strtolower($user->roles ?? '');
                if (!in_array($role, ['superadmin', 'admin', 'manager'])) {
                    abort(403, 'Access denied');
                }
            }

            if (! view()->exists($view)) {
                return response()->view('components.module-under-construction', ['module' => $module], 404);
            }

            // Generate JWT Token for API access
            $jwtToken = '';
            if (Auth::guard('sws')->check()) {
                $user = Auth::guard('sws')->user();
                $authService = app(\App\Services\AuthService::class);
                $jwtToken = $authService->generateTokenForUser($user);
            }

            // If it's an AJAX request, return just the module content
            if (request()->ajax()) {
                return view($view, ['jwtToken' => $jwtToken]);
            }

            // If it's a direct browser request, return the full home page with the module loaded
            return view('home', ['jwtToken' => $jwtToken]);
        })->name('module.load');

        Route::get('/alms/assets', [ALMSController::class, 'getAssets'])->name('alms.assets.index');
        Route::get('/alms/assets/{id}', [ALMSController::class, 'showAsset'])->name('alms.assets.show');
        Route::put('/alms/assets/{id}/status', [ALMSController::class, 'updateAssetStatus'])->name('alms.assets.status');
        Route::delete('/alms/assets/{id}', [ALMSController::class, 'deleteAsset'])->name('alms.assets.delete');

        Route::get('/alms/repair-personnel', [ALMSController::class, 'getRepairPersonnel'])->name('alms.repair_personnel.index');
        Route::post('/alms/repair-personnel', [ALMSController::class, 'storeRepairPersonnel'])->name('alms.repair_personnel.store');
        Route::delete('/alms/repair-personnel/{id}', [ALMSController::class, 'deleteRepairPersonnel'])->name('alms.repair_personnel.delete');
        Route::get('/alms/maintenance', [ALMSController::class, 'getMaintenance'])->name('alms.maintenance.index');
        Route::post('/alms/maintenance', [ALMSController::class, 'storeMaintenance'])->name('alms.maintenance.store');
        Route::put('/alms/maintenance/{id}/status', [ALMSController::class, 'updateMaintenanceStatus'])->name('alms.maintenance.status');
        Route::delete('/alms/maintenance/{id}', [ALMSController::class, 'deleteMaintenance'])->name('alms.maintenance.delete');

        Route::get('/alms/request-maintenance', [ALMSController::class, 'getRequestMaintenance'])->name('alms.request_maintenance.index');
        Route::post('/alms/request-maintenance', [ALMSController::class, 'storeRequestMaintenance'])->name('alms.request_maintenance.store');
        Route::delete('/alms/request-maintenance/{id}', [ALMSController::class, 'deleteRequestMaintenance'])->name('alms.request_maintenance.delete');
        Route::post('/alms/request-maintenance/{id}/processed', [ALMSController::class, 'markRequestProcessed'])->name('alms.request_maintenance.processed');

        // User Management Routes
        Route::get('/user-management/accounts', [App\Http\Controllers\UserManagementController::class, 'getAccounts'])->name('user-management.accounts');
        Route::get('/user-management/stats', [App\Http\Controllers\UserManagementController::class, 'getStats'])->name('user-management.stats');
        Route::post('/user-management/accounts/employee', [App\Http\Controllers\UserManagementController::class, 'createEmployee'])->name('user-management.accounts.createEmployee');
        Route::post('/user-management/accounts/vendor', [App\Http\Controllers\UserManagementController::class, 'createVendor'])->name('user-management.accounts.createVendor');
        Route::put('/user-management/accounts/{id}/role', [App\Http\Controllers\UserManagementController::class, 'updateRole'])->name('user-management.accounts.updateRole');
        Route::put('/user-management/accounts/{id}/status', [App\Http\Controllers\UserManagementController::class, 'updateStatus'])->name('user-management.accounts.updateStatus');
        Route::delete('/user-management/accounts/{id}', [App\Http\Controllers\UserManagementController::class, 'destroy'])->name('user-management.accounts.destroy');
        Route::get('/user-management/accounts/{id}', [App\Http\Controllers\UserManagementController::class, 'show'])->name('user-management.accounts.show');

        Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');

        // PSM Module Routes
        Route::prefix('psm')->group(function () {
            Route::get('/budget-management/all', [\App\Http\Controllers\PSMController::class, 'getAllBudgets']);
            Route::get('/budget-logs/all', [\App\Http\Controllers\PSMController::class, 'getBudgetLogs']);
        });
    });

    Route::middleware(['auth:vendor', 'session.timeout'])->group(function () {
        Route::get('/vendor/home', function () {
            return view('home');
        })->name('vendor.home');
        Route::get('/vendor/dashboard', function () {
            return view('dashboard.index');
        })->name('vendor.dashboard');
        Route::get('/vendor/module/{module}', function ($module) {
            $moduleViews = [
                'dashboard' => 'dashboard.index',
                'vendor-quote' => 'psm.vendor-quote',
                'vendor-products' => 'psm.vendor-products',
            ];

            $view = $moduleViews[$module] ?? 'components.module-not-found';

            if (! view()->exists($view)) {
                return response()->view('components.module-under-construction', ['module' => $module], 404);
            }

            if (request()->ajax()) {
                return view($view);
            }

            return view('home');
        })->name('vendor.module.load');
    });

    // Redirect root to appropriate page
    Route::get('/', function () {
        if (Auth::guard('sws')->check()) {
            return redirect('/home');
        }
        if (Auth::guard('vendor')->check()) {
            return redirect('/vendor/home');
        }

        return redirect('/login');
    });

// Health check endpoints (outside session middleware)
Route::get('/up', function () {
    return 'Application is healthy';
});

Route::get('/api/health', function () {
    return response()->json(['status' => 'healthy', 'timestamp' => now()]);
});

Route::get('/api/test-db', function () {
    try {
        DB::connection('sws')->getPdo();

        return response()->json(['database' => 'Connected successfully']);
    } catch (\Exception $e) {
        return response()->json(['database' => 'Connection failed: '.$e->getMessage()], 500);
    }
});

// FIXED: Add explicit API routes for authentication to ensure they're accessible
// These routes use the session middleware (inherited from web group) but exclude CSRF verification (handled in bootstrap/app.php)
    Route::prefix('api')->group(function () {
    Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
    Route::post('/send-otp', [App\Http\Controllers\AuthController::class, 'sendOtp']);
    Route::post('/verify-otp', [App\Http\Controllers\AuthController::class, 'verifyOtp']);
    Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout']);
    Route::get('/me', [App\Http\Controllers\AuthController::class, 'me']);
    Route::get('/check-auth', [App\Http\Controllers\AuthController::class, 'checkAuth']);
    Route::post('/refresh-session', [App\Http\Controllers\AuthController::class, 'refreshSession']);
    Route::get('/check-session', [App\Http\Controllers\AuthController::class, 'checkSession']);
    Route::get('/csrf-token', [App\Http\Controllers\AuthController::class, 'getCsrfToken']);

    Route::prefix('vendor')->group(function () {
        Route::post('/login', [VendorAuthController::class, 'login']);
        Route::post('/send-otp', [VendorAuthController::class, 'sendOtp']);
        Route::post('/verify-otp', [VendorAuthController::class, 'verifyOtp']);
        Route::post('/logout', [VendorAuthController::class, 'logout']);
        Route::get('/me', [VendorAuthController::class, 'me']);
        Route::get('/check-auth', [VendorAuthController::class, 'checkAuth']);
        Route::post('/refresh-session', [VendorAuthController::class, 'refreshSession']);
        Route::get('/check-session', [VendorAuthController::class, 'checkSession']);
        Route::get('/csrf-token', [VendorAuthController::class, 'getCsrfToken']);

        Route::post('/profile/update', [VendorAuthController::class, 'updateProfile'])->name('vendor.profile.update');

        Route::middleware(['auth:vendor'])->prefix('v1/psm/vendor-quote')->group(function () {
            Route::get('/', [\App\Http\Controllers\PSMController::class, 'listQuotes']);
            Route::get('/notifications', [\App\Http\Controllers\PSMController::class, 'listApprovedPurchasesForQuote']);
            Route::post('/review-from-purchase/{purchaseId}', [\App\Http\Controllers\PSMController::class, 'reviewPurchaseToQuote']);
            Route::put('/{id}', [\App\Http\Controllers\PSMController::class, 'updateQuote']);
            Route::delete('/{id}', [\App\Http\Controllers\PSMController::class, 'deleteQuote']);
        });

        Route::middleware(['auth:vendor'])->prefix('v1/psm/product-management')->group(function () {
            Route::get('/', [\App\Http\Controllers\PSMController::class, 'getProducts']);
            Route::get('/by-vendor/{venId}', [\App\Http\Controllers\PSMController::class, 'getProductsByVendor']);
            Route::post('/', [\App\Http\Controllers\PSMController::class, 'createProduct']);
            Route::put('/{id}', [\App\Http\Controllers\PSMController::class, 'updateProduct']);
            Route::delete('/{id}', [\App\Http\Controllers\PSMController::class, 'deleteProduct']);
        });
    });
});
