<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PSMController;

// PSM Module API Routes
Route::get('/purchases', [PSMController::class, 'getPurchases']);
Route::get('/vendor-quotes', [PSMController::class, 'getVendorQuotes']);
Route::get('/vendor-management', [PSMController::class, 'getVendorManagement']);

// Additional PSM routes can be added here as the module develops
Route::get('/test', function () {
    return response()->json([
        'module' => 'PSM - Procurement & Sourcing Management',
        'status' => 'active',
        'submodules' => [
            'Purchase Management',
            'Vendor Management', 
            'Vendor Quote'
        ]
    ]);
});