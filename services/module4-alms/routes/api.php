<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AssetController;
use App\Http\Controllers\AssetCategoryController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\EmployeeController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Asset routes
Route::get('/assets', [AssetController::class, 'index']);
Route::post('/assets', [AssetController::class, 'store']);
Route::get('/assets/{id}', [AssetController::class, 'show']);
Route::put('/assets/{id}', [AssetController::class, 'update']);
Route::delete('/assets/{id}', [AssetController::class, 'destroy']);
Route::get('/assets/stats', [AssetController::class, 'stats']);

// Asset Category routes
Route::get('/asset-categories', [AssetCategoryController::class, 'index']);
Route::post('/asset-categories', [AssetCategoryController::class, 'store']);

// Branch routes
Route::get('/branches', [BranchController::class, 'index']);

// Employee routes
Route::get('/employees', [EmployeeController::class, 'index']);
Route::post('/employees', [EmployeeController::class, 'store']);

// Health check
Route::get('/health', function () {
    return response()->json([
        'success' => true,
        'message' => 'ALMS module is running',
        'timestamp' => now()
    ]);
});