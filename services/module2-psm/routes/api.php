<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PSMController;

// Vendor Management Routes
Route::get('/vendors', [PSMController::class, 'getVendors']);
Route::post('/vendors', [PSMController::class, 'createVendor']);
Route::put('/vendors/{id}', [PSMController::class, 'updateVendor']);
Route::delete('/vendors/{id}', [PSMController::class, 'deleteVendor']);

// Shop Management Routes
Route::get('/shops', [PSMController::class, 'getShops']);
Route::post('/shops', [PSMController::class, 'createShop']);

// Products Management Routes - FIXED: Use PUT for updates
Route::get('/products', [PSMController::class, 'getProducts']);
Route::post('/products', [PSMController::class, 'createProduct']);
Route::put('/products/{id}', [PSMController::class, 'updateProduct']); // Use PUT for updates
Route::delete('/products/{id}', [PSMController::class, 'deleteProduct']);

// Vendor Market Routes
Route::get('/market/products', [PSMController::class, 'getPublishedProducts']);

// Order Management Routes
Route::get('/orders', [PSMController::class, 'getOrders']);
Route::post('/orders', [PSMController::class, 'createOrder']);
Route::put('/orders/{id}', [PSMController::class, 'updateOrder']);

// Budget Approval Routes
Route::get('/budget-approvals', [PSMController::class, 'getBudgetApprovals']);
Route::put('/budget-approvals/{id}', [PSMController::class, 'updateBudgetApproval']);

// Reorder Management Routes
Route::get('/reorders', [PSMController::class, 'getReorders']);
Route::post('/reorders', [PSMController::class, 'createReorder']);

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