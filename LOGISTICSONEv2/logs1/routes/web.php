<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PSMController;
use App\Http\Controllers\SWSController;
use App\Http\Controllers\PLTController;
use App\Http\Controllers\ALMSController;
use App\Http\Controllers\DTLRController;

Route::get('/home', function () {
    return view('home');
})->name('home');

Route::get('/up', function () {
    return 'Application is healthy';// Health check endpoint
});

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

    // Redirect root to home
    Route::get('/', function () {
        return redirect('/home');
    });

// API routes for each module can be added here later
Route::prefix('api')->group(function () {
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

    // dtLR routes
    Route::prefix('dtlr')->group(function () {
        Route::get('/document-tracker', [DTLRController::class, 'getDocumentTracker']);
        Route::get('/logistics-record', [DTLRController::class, 'getLogisticsRecord']);
    });
});