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
    Route::patch('/{id}/status', [PSMController::class, 'updatePurchaseStatus']);
    Route::post('/{id}/cancel', [PSMController::class, 'cancelPurchase']);
    Route::post('/{id}/budget-approval', [PSMController::class, 'budgetApproval']);
});

// PSM Budget Management Routes
Route::prefix('budget-management')->group(function () {
    Route::get('/current', [PSMController::class, 'getCurrentBudget']);
    Route::post('/{id}/extend', [PSMController::class, 'extendBudgetValidity']);
});

// PSM Active Vendors for Purchase
Route::get('/active-vendors', [PSMController::class, 'getActiveVendorsForPurchase']);

// Legacy PSM routes
Route::get('/purchases', [PSMController::class, 'getPurchases']);
Route::get('/vendor-quotes', [PSMController::class, 'getVendorQuotes']);

// list of endpoints links with json file output*
// provide indicate each working endpoints here (takenote that these ff endpoints should work if i search it through my local browser or using an postman)
// should indicate all endpoints in a form of this like commented especially the endpoints of the ff below

// vendors endpoints = http://localhost:9000/api/v1/psm/vendor-management
// vendor by id endpoints = http://localhost:9000/api/v1/psm/vendor-management/{id}
// vendor stats endpoints = http://localhost:9000/api/v1/psm/vendor-management/stats
// vendor by ven-id endpoints = http://localhost:9000/api/v1/psm/vendor-management/by-ven-id/{venId}

// product endpoints = http://localhost:9000/api/v1/psm/product-management
// products by vendor endpoints = http://localhost:9000/api/v1/psm/product-management/by-vendor/{venId}

// purchase endpoints = http://localhost:9000/api/v1/psm/purchase-management
// purchase stats endpoints = http://localhost:9000/api/v1/psm/purchase-management/stats
// purchase by id endpoints = http://localhost:9000/api/v1/psm/purchase-management/{id}
// purchase by pur-id endpoints = http://localhost:9000/api/v1/psm/purchase-management/by-pur-id/{purId}
// purchase status update endpoints = http://localhost:9000/api/v1/psm/purchase-management/{id}/status
// purchase cancel endpoints = http://localhost:9000/api/v1/psm/purchase-management/{id}/cancel
// purchase budget approval endpoints = http://localhost:9000/api/v1/psm/purchase-management/{id}/budget-approval

// budget endpoints = http://localhost:9000/api/v1/psm/budget-management/current
// budget extend endpoints = http://localhost:9000/api/v1/psm/budget-management/{id}/extend

// active vendors endpoints = http://localhost:9000/api/v1/psm/active-vendors