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
    
    // Authentication Routes
    Route::get('/login', function () {
        return view('login-auth.login');
    })->name('login');

    Route::get('/otp-verification', function () {
        return view('login-auth.otp-verification');
    })->name('otp.verification');

    Route::get('/splash-login', function () {
        return view('login-auth.splash-login');
    })->name('splash.login');

    Route::get('/splash-logout', function () {
        return view('login-auth.splash-logout');
    })->name('splash.logout');

    // Public API routes for authentication
    Route::prefix('api')->group(function () {
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/send-otp', [AuthController::class, 'sendOtp']);
        Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/me', [AuthController::class, 'me']);
        Route::post('/refresh-session', [AuthController::class, 'refreshSession']);
        Route::get('/csrf-token', [AuthController::class, 'getCsrfToken']);
        Route::get('/check-session', [AuthController::class, 'checkSession']); // Added this line
    });

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

    // Protected API routes with session timeout
    Route::prefix('api')->middleware(['auth:sws', 'session.timeout'])->group(function () {
        // PSM routes
        Route::prefix('psm')->group(function () {
            Route::get('/purchases', [PSMController::class, 'getPurchases']);
            Route::get('/vendor-quotes', [PSMController::class, 'getVendorQuotes']);
            Route::get('/vendors', [PSMController::class, 'getVendors']);
        });
        
        // SWS routes
        Route::prefix('sws')->group(function () {
            Route::get('/inventory-flow', [SWSController::class, 'getInventoryFlow']);
            Route::get('/digital-inventory', [SWSController::class, 'getDigitalInventory']);
        });
        
        // PLT routes
        Route::prefix('plt')->group(function () {
            Route::get('/projects', [PLTController::class, 'getProjects']);
        });

        // ALMS routes
        Route::prefix('alms')->group(function () {
            Route::get('/assets', [ALMSController::class, 'getAssets']);
            Route::get('/maintenance', [ALMSController::class, 'getMaintenance']);
        });

        // DTLR routes
        Route::prefix('dtlr')->group(function () {
            Route::get('/document-tracker', [DTLRController::class, 'getDocumentTracker']);
            Route::get('/logistics-record', [DTLRController::class, 'getLogisticsRecord']);
        });
    });

    // Redirect root to login
    Route::get('/', function () {
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