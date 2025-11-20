<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SWSController;

// SWS Module API Routes
Route::get('/inventory-flow', [SWSController::class, 'getInventoryFlow']);
Route::get('/digital-inventory', [SWSController::class, 'getDigitalInventory']);

// Additional SWS routes can be added here as the module develops
Route::get('/test', function () {
    return response()->json([
        'module' => 'SWS - Smart Warehousing System',
        'status' => 'active',
        'submodules' => [
            'Inventory Flow',
            'Digital Inventory'
        ]
    ]);
});

// Warehouse routes
Route::get('/warehouse', [SWSController::class, 'warehouses']);
Route::get('/warehouse/{id}', [SWSController::class, 'showWarehouse']);
Route::post('/warehouse', [SWSController::class, 'createWarehouse']);
Route::put('/warehouse/{id}', [SWSController::class, 'updateWarehouse']);
Route::delete('/warehouse/{id}', [SWSController::class, 'deleteWarehouse']);

// Digital Inventory routes
Route::get('/inventory/stats', [SWSController::class, 'getInventoryStats']);
Route::get('/inventory/stock-levels', [SWSController::class, 'getStockLevelsByCategory']);
Route::get('/inventory/items', [SWSController::class, 'getItems']);
Route::get('/inventory/items/{id}', [SWSController::class, 'getItem']);
Route::post('/inventory/items', [SWSController::class, 'createItem']);
Route::put('/inventory/items/{id}', [SWSController::class, 'updateItem']);
Route::delete('/inventory/items/{id}', [SWSController::class, 'deleteItem']);
Route::get('/inventory/categories', [SWSController::class, 'getCategories']);