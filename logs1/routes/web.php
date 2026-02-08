<?php

use App\Http\Controllers\ALMSController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VendorAuthController;
use App\Http\Controllers\DashboardController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Simple session setup for auth routes
// Middleware is already applied by bootstrap/app.php
// Public Authentication Routes - FIXED: Proper route definitions
Route::get('/', function () {
    return redirect()->route('login');
});

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
            return redirect('/splash-login');
        }
        if (Auth::guard('vendor')->check()) {
            return redirect('/vendor/splash-login');
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

    Route::get('/auth/token', [App\Http\Controllers\AuthController::class, 'getApiToken']);

    Route::get('/debug-config', function () {
        try {
            return response()->json([
                'db_default' => config('database.default'),
                'db_connections' => config('database.connections'),
                'session_driver' => config('session.driver'),
                'session_connection' => config('session.connection'),
                'env_db_connection' => env('DB_CONNECTION'),
                'env_session_driver' => env('SESSION_DRIVER'),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    });

    // Protected Routes - Using normal Laravel auth with session timeout
    Route::middleware(['auth:sws', 'session.timeout'])->group(function () {
        Route::get('/home', function () {
            // Generate JWT Token for API access
            $jwtToken = '';
            try {
                if (Auth::guard('sws')->check()) {
                    $user = Auth::guard('sws')->user();
                    if ($user) {
                        $authService = app(\App\Services\AuthService::class);
                        $jwtToken = $authService->generateTokenForUser($user);
                        if (!$jwtToken) {
                            \Illuminate\Support\Facades\Log::error('JWT Generation returned null for user: ' . $user->id);
                        } else {
                            \Illuminate\Support\Facades\Log::info('JWT Generated successfully for user: ' . $user->id);
                        }
                    } else {
                        \Illuminate\Support\Facades\Log::error('Auth check passed but user is null');
                    }
                } else {
                    \Illuminate\Support\Facades\Log::error('Auth check failed in /home route');
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Failed to generate JWT token for home: ' . $e->getMessage());
            }
            return view('home', ['jwtToken' => $jwtToken]);
        })->name('home');

        // Dashboard Route
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard.index');
        Route::post('/dashboard/announcements', [DashboardController::class, 'storeAnnouncement'])->name('dashboard.announcements.store');
        Route::get('/dashboard/announcements', [DashboardController::class, 'fetchAnnouncements'])->name('dashboard.announcements.fetch');
        
        // Profile Update
        Route::post('/profile/update', [AuthController::class, 'updateProfile'])->name('profile.update');

        // Module Loader Route
        Route::get('/module/{module}', function ($module) {
            $map = [
                'dashboard' => 'dashboard.index',
                'sws-inventory-flow' => 'sws.inventory-flow',
                'sws-digital-inventory' => 'sws.digital-inventory',
                'sws-warehouse-management' => 'sws.warehouse-management',
                'plt-logistics-projects' => 'plt.logistics-projects',
                'alms-asset-management' => 'alms.asset-management',
                'alms-maintenance-management' => 'alms.maintenance-management',
                'dtlr-document-tracker' => 'dtlr.document-tracker',
                'dtlr-logistics-record' => 'dtlr.logistics-record',
                'psm-purchase' => 'psm.purchase-management',
                'psm-budgeting' => 'psm.budgeting',
                'psm-vendor-management' => 'psm.vendor-management',
                'um-account-management' => 'user-management.account-management',
                'um-audit-trail' => 'user-management.audit-trail',
            ];

            if (array_key_exists($module, $map)) {
                return view($map[$module]);
            }

            return view('components.module-not-found');
        })->name('module.load');
    });

    // Vendor Protected Routes
    Route::prefix('vendor')->middleware(['auth:vendor', 'session.timeout'])->group(function () {
        Route::get('/home', function () {
            // Generate JWT Token for API access
            $jwtToken = '';
            try {
                if (Auth::guard('vendor')->check()) {
                    $user = Auth::guard('vendor')->user();
                    if ($user) {
                        $authService = app(\App\Services\VendorAuthService::class);
                        $jwtToken = $authService->generateTokenForUser($user);
                        if (!$jwtToken) {
                            \Illuminate\Support\Facades\Log::error('JWT Generation returned null for vendor: ' . $user->id);
                        }
                    }
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Failed to generate JWT token for vendor home: ' . $e->getMessage());
            }
            return view('vendor-portal.home', ['jwtToken' => $jwtToken]);
        })->name('vendor.home');

        // Vendor Profile Update
        Route::post('/profile/update', [VendorAuthController::class, 'updateProfile'])->name('vendor.profile.update');

        // Vendor Module Loader Route
        Route::get('/module/{module}', function ($module) {
            $map = [
                'dashboard' => 'dashboard.index', // Vendor dashboard is part of dashboard.index
                'vendor-quote' => 'psm.vendor-quote',
                'vendor-products' => 'psm.vendor-products',
            ];

            if (array_key_exists($module, $map)) {
                return view($map[$module]);
            }

            return view('components.module-not-found');
        })->name('vendor.module.load');
    });
