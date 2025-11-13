<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PSMController;

// PSM Vendor Management Routes
Route::prefix('vendor-management')->group(function () {
    Route::get('/', [PSMController::class, 'getVendorManagement']);
    Route::get('/{id}', [PSMController::class, 'getVendor']);
    Route::get('/stats', [PSMController::class, 'getVendorStats']);
    Route::get('/by-ven-id/{venId}', [PSMController::class, 'getVendorByVendorId']);
    Route::post('/', [PSMController::class, 'createVendor']);
    Route::put('/{id}', [PSMController::class, 'updateVendor']);
    Route::delete('/{id}', [PSMController::class, 'deleteVendor']);
});

// PSM Product Management Routes
Route::prefix('product-management')->group(function () {
    Route::get('/', [PSMController::class, 'getProducts']);
    Route::get('/by-vendor/{venId}', [PSMController::class, 'getProductsByVendor']);
    Route::post('/', [PSMController::class, 'createProduct']);
    Route::put('/{id}', [PSMController::class, 'updateProduct']);
    Route::delete('/{id}', [PSMController::class, 'deleteProduct']);
});

// Legacy PSM routes
Route::get('/purchases', [PSMController::class, 'getPurchases']);
Route::get('/vendor-quotes', [PSMController::class, 'getVendorQuotes']);