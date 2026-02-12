<?php

use App\Http\Controllers\PSMController;
use Illuminate\Support\Facades\Route;

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
    Route::get('/{id}', [PSMController::class, 'getPurchase']);
    Route::get('/by-pur-id/{purId}', [PSMController::class, 'getPurchaseByPurchaseId']);
    Route::post('/', [PSMController::class, 'createPurchase']);
    Route::put('/{id}', [PSMController::class, 'updatePurchase']);
    Route::delete('/{id}', [PSMController::class, 'deletePurchase']);
    Route::patch('/{id}/status', [PSMController::class, 'updatePurchaseStatus']);
    Route::post('/{id}/cancel', [PSMController::class, 'cancelPurchase']);
    Route::post('/{id}/purchase-approval', [PSMController::class, 'purchaseApproval']);
});

// PSM Budget Management Routes
Route::prefix('budget-management')->group(function () {
    Route::get('/', [PSMController::class, 'getBudgets']);
    Route::get('/current', [PSMController::class, 'getCurrentBudget']);
    Route::post('/', [PSMController::class, 'createBudget']);
    Route::put('/{id}', [PSMController::class, 'updateBudget']);
    Route::post('/{id}/extend', [PSMController::class, 'extendBudget']);
    Route::get('/allocated', [PSMController::class, 'getAllocatedBudgets']);
    Route::get('/requests', [PSMController::class, 'getBudgetRequests']);
    Route::post('/requests', [PSMController::class, 'createBudgetRequest']);
    Route::post('/requests/{id}/cancel', [PSMController::class, 'cancelBudgetRequest']);
});

// PSM Budget Log Routes
Route::prefix('budget-logs')->group(function () {
    Route::get('/', [PSMController::class, 'getBudgetLogs']);
});

// PSM Requisition Routes
Route::prefix('requisitions')->group(function () {
    Route::get('/', [PSMController::class, 'getRequisitions']);
    Route::get('/{id}', [PSMController::class, 'getRequisition']);
    Route::post('/', [PSMController::class, 'storeRequisition']);
    Route::patch('/{id}/status', [PSMController::class, 'updateRequisitionStatus']);
    Route::delete('/{id}', [PSMController::class, 'deleteRequisition']);
});


// PSM Active Vendors for Purchase
Route::get('/active-vendors', [PSMController::class, 'getActiveVendorsForPurchase']);

// Legacy PSM routes
Route::get('/purchases', [PSMController::class, 'getPurchases']);
Route::get('/vendor-quotes', [PSMController::class, 'getVendorQuotes']);

// PSM Vendor Quote Routes
Route::prefix('vendor-quote')->group(function () {
    Route::get('/', [PSMController::class, 'getVendorQuotes']);
    Route::get('/notifications', [PSMController::class, 'getVendorQuoteNotifications']);
    Route::post('/review-from-purchase/{id}', [PSMController::class, 'reviewPurchaseToQuote']);
    Route::put('/{id}', [PSMController::class, 'updateQuote']);
    Route::delete('/{id}', [PSMController::class, 'deleteQuote']);
});

// list of endpoints links with json file output*
