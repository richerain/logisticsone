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
    Route::post('/{id}/budget-approval', [PSMController::class, 'budgetApproval']);
});

// PSM Budget Management Routes
Route::prefix('budget-management')->group(function () {
    Route::get('/all', [PSMController::class, 'getAllBudgets']);
    Route::get('/current', [PSMController::class, 'getCurrentBudget']);
    Route::post('/{id}/extend', [PSMController::class, 'extendBudgetValidity']);
});

// PSM Budget Requests Routes
Route::prefix('budget-requests')->group(function () {
    Route::get('/', [PSMController::class, 'getRequestBudgets']);
    Route::post('/', [PSMController::class, 'storeRequestBudget']);
    Route::patch('/{id}/cancel', [PSMController::class, 'cancelRequestBudget']);
});

// PSM Budget Logs Routes
Route::prefix('budget-logs')->group(function () {
    Route::get('/all', [PSMController::class, 'getBudgetLogs']);
});


// PSM Active Vendors for Purchase
Route::get('/active-vendors', [PSMController::class, 'getActiveVendorsForPurchase']);

// Legacy PSM routes
Route::get('/purchases', [PSMController::class, 'getPurchases']);
Route::get('/vendor-quotes', [PSMController::class, 'getVendorQuotes']);

// PSM Vendor Quote Routes moved under vendor session API in web.php

// list of endpoints links with json file output*
