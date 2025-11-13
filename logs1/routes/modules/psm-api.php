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

// PSM Purchase Management Routes
Route::prefix('purchase-management')->group(function () {
    Route::get('/', [PSMController::class, 'getPurchases']);
    Route::get('/stats', [PSMController::class, 'getPurchaseStats']);
    Route::get('/{id}', [PSMController::class, 'getPurchase']);
    Route::get('/by-pur-id/{purId}', [PSMController::class, 'getPurchaseByPurchaseId']);
    Route::post('/', [PSMController::class, 'createPurchase']);
    Route::put('/{id}', [PSMController::class, 'updatePurchase']);
    Route::delete('/{id}', [PSMController::class, 'deletePurchase']);
});

// PSM Active Vendors for Purchase
Route::get('/active-vendors', [PSMController::class, 'getActiveVendorsForPurchase']);

// Legacy PSM routes
Route::get('/purchases', [PSMController::class, 'getPurchases']);
Route::get('/vendor-quotes', [PSMController::class, 'getVendorQuotes']);