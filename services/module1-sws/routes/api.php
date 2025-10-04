<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\StorageController;
use App\Http\Controllers\Api\RestockController;

Route::post('/auth/login', [AuthController::class, 'login']);
Route::put('/profile/update', [ProfileController::class, 'update']);
Route::post('/profile/upload-picture', [ProfileController::class, 'uploadPicture']);

// Inventory routes
Route::apiResource('inventory', InventoryController::class);
Route::get('inventory/search/{search}', [InventoryController::class, 'search']);

// Storage routes
Route::apiResource('storage', StorageController::class);
Route::get('storage/search/{search}', [StorageController::class, 'search']);

// Restock routes
Route::apiResource('restock', RestockController::class);
Route::get('restock/search/{search}', [RestockController::class, 'search']);

// Add CORS middleware
Route::middleware([])->group(function () {
    // Your API routes here
});