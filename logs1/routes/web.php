<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PSMController;
use App\Http\Controllers\SWSController;
use App\Http\Controllers\PLTController;
use App\Http\Controllers\ALMSController;
use App\Http\Controllers\DTLRController;
use App\Http\Controllers\AuthController;
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
    Route::get('/login', function () {
        // If user is already authenticated, redirect to home
        if (Auth::guard('sws')->check()) {
            return redirect('/home');
        }
        return view('login-auth.login');
    })->name('login');

    Route::get('/otp-verification', function (Request $request) {
        // If user is already authenticated, redirect to home
        if (Auth::guard('sws')->check()) {
            return redirect('/home');
        }
        
        // Check if email is provided, otherwise redirect to login
        if (!$request->has('email')) {
            return redirect('/login');
        }
        
        return view('login-auth.otp-verification', ['email' => $request->email]);
    })->name('otp.verification');

    Route::get('/splash-login', function () {
        // This route should only be accessible after successful OTP verification
        // If user is not authenticated, redirect to login
        if (!Auth::guard('sws')->check()) {
            return redirect('/login');
        }
        return view('login-auth.splash-login');
    })->name('splash.login');

    Route::get('/splash-logout', function () {
        return view('login-auth.splash-logout');
    })->name('splash.logout');

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
                'psm-vendor-quote' => 'psm.vendor-quote',
                'psm-vendor-management' => 'psm.vendor-management',
                'sws-inventory-flow' => 'sws.inventory-flow',
                'sws-digital-inventory' => 'sws.digital-inventory',
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

    // Redirect root to appropriate page
    Route::get('/', function () {
        if (Auth::guard('sws')->check()) {
            return redirect('/home');
        }
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
});