<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PSMController;

// Vendor Management Routes
Route::get('/vendors', [PSMController::class, 'getVendors']);
Route::post('/vendors', [PSMController::class, 'createVendor']);
Route::put('/vendors/{id}', [PSMController::class, 'updateVendor']);
Route::delete('/vendors/{id}', [PSMController::class, 'deleteVendor']);

// Vendor Products Routes
Route::get('/vendor-products', [PSMController::class, 'getVendorProducts']);
Route::get('/vendors/{vendorId}/products', [PSMController::class, 'getVendorProducts']);
Route::post('/vendor-products', [PSMController::class, 'createVendorProduct']);
Route::get('/vendor-products/{id}', [PSMController::class, 'getVendorProduct']);
Route::put('/vendor-products/{id}', [PSMController::class, 'updateVendorProduct']);
Route::delete('/vendor-products/{id}', [PSMController::class, 'deleteVendorProduct']);

// Vendor Quote Routes
Route::get('/quotes', [PSMController::class, 'getQuotes']);
Route::post('/quotes', [PSMController::class, 'createQuote']);
Route::get('/quotes/{id}', [PSMController::class, 'getQuote']);
Route::put('/quotes/{id}', [PSMController::class, 'updateQuote']);
Route::delete('/quotes/{id}', [PSMController::class, 'deleteQuote']);

// Purchase Management Routes
Route::get('/purchase/requests', [PSMController::class, 'getPurchaseRequests']);
Route::post('/purchase/requests', [PSMController::class, 'createPurchaseRequest']);
Route::get('/purchase/requests/{id}', [PSMController::class, 'getPurchaseRequest']);
Route::put('/purchase/requests/{id}', [PSMController::class, 'updatePurchaseRequest']);
Route::delete('/purchase/requests/{id}', [PSMController::class, 'deletePurchaseRequest']);

// Purchase requests for quotes dropdown
Route::get('/purchase/requests-for-quotes', [PSMController::class, 'getPurchaseRequestsForQuotes']);

// Health check
Route::get('/health', function () {
    return response()->json(['status' => 'OK', 'service' => 'PSM Module']);
});