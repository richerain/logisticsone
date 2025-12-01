<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PSMController;
use App\Http\Controllers\SWSController;
use App\Http\Controllers\PLTController;
use App\Http\Controllers\ALMSController;
use App\Http\Controllers\DTLRController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\VendorAuthController;
use Illuminate\Http\Request;

// Simple session setup for auth routes
Route::middleware([
    \App\Http\Middleware\EncryptCookies::class,
    \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
    \Illuminate\Session\Middleware\StartSession::class,
    \Illuminate\View\Middleware\ShareErrorsFromSession::class,
    \App\Http\Middleware\VerifyCsrfToken::class,
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
])->group(function () {
    
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
        if (Auth::guard('sws')->check()) { return redirect('/home'); }
        if (Auth::guard('vendor')->check()) { return redirect('/vendor/home'); }
        if (!$request->has('email')) {
            $portal = $request->get('portal');
            if ($portal === 'vendor') { return redirect('/login/vendor-portal'); }
            return redirect('/login');
        }
        return view('login-auth.otp-verification', ['email' => $request->email, 'portal' => $request->get('portal')]);
    })->name('otp.verification');

    Route::get('/splash-login', function () {
        if (!Auth::guard('sws')->check()) { return redirect('/login'); }
        return view('login-auth.splash-login');
    })->name('splash.login');

    Route::get('/vendor/splash-login', function () {
        if (!Auth::guard('vendor')->check()) { return redirect('/login/vendor-portal'); }
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
                'sws-inventory-flow' => 'sws.inventory-flow',
                'sws-digital-inventory' => 'sws.digital-inventory',
                'sws-warehouse-management' => 'sws.warehouse-management',
                'plt-logistics-projects' => 'plt.logistics-projects',
                'alms-asset-management' => 'alms.asset-management',
                'alms-maintenance-management' => 'alms.maintenance-management',
                'dtlr-document-tracker' => 'dtlr.document-tracker',
                'dtlr-logistics-record' => 'dtlr.logistics-record',
            ];

            $view = $moduleViews[$module] ?? 'components.module-not-found';
            
            if (!view()->exists($view)) {
                return response()->view('components.module-under-construction', ['module' => $module], 404);
            }
            
            // If it's an AJAX request, return just the module content
            if (request()->ajax()) {
                return view($view);
            }
            
            // If it's a direct browser request, return the full home page with the module loaded
            return view('home');
        })->name('module.load');
    });

    Route::middleware(['auth:vendor', 'session.timeout'])->group(function () {
        Route::get('/vendor/home', function () { return view('home'); })->name('vendor.home');
        Route::get('/vendor/dashboard', function () { return view('dashboard.index'); })->name('vendor.dashboard');
        Route::get('/vendor/module/{module}', function ($module) {
            $moduleViews = [
                'dashboard' => 'dashboard.index',
                'vendor-quote' => 'psm.vendor-quote',
            ];

            $view = $moduleViews[$module] ?? 'components.module-not-found';

            if (!view()->exists($view)) {
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
        if (Auth::guard('sws')->check()) { return redirect('/home'); }
        if (Auth::guard('vendor')->check()) { return redirect('/vendor/home'); }
        return redirect('/login');
    });
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
        return response()->json(['database' => 'Connection failed: ' . $e->getMessage()], 500);
    }
});

// FIXED: Add explicit API routes for authentication to ensure they're accessible
// These routes use the session middleware but exclude CSRF verification
Route::middleware([
    \App\Http\Middleware\EncryptCookies::class,
    \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
    \Illuminate\Session\Middleware\StartSession::class,
    \Illuminate\Routing\Middleware\SubstituteBindings::class,
])->prefix('api')->group(function () {
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

        // Vendor-accessible PSM Vendor Quote APIs (session-based)
        Route::middleware(['auth:vendor'])->prefix('v1/psm/vendor-quote')->group(function () {
            Route::get('/', [\App\Http\Controllers\PSMController::class, 'listQuotes']);
            Route::get('/notifications', [\App\Http\Controllers\PSMController::class, 'listApprovedPurchasesForQuote']);
            Route::post('/review-from-purchase/{purchaseId}', [\App\Http\Controllers\PSMController::class, 'reviewPurchaseToQuote']);
            Route::put('/{id}', [\App\Http\Controllers\PSMController::class, 'updateQuote']);
            Route::delete('/{id}', [\App\Http\Controllers\PSMController::class, 'deleteQuote']);
        });
    });
});