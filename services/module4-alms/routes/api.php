<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ALMSController;

// Health check
Route::get('/health', function () {
    return response()->json([
        'success' => true,
        'message' => 'ALMS module is running',
        'timestamp' => now()
    ]);
});

// Asset routes
Route::get('/assets', [ALMSController::class, 'getAssets']);
Route::post('/assets', [ALMSController::class, 'createAsset']);
Route::put('/assets/{id}', [ALMSController::class, 'updateAsset']);
Route::delete('/assets/{id}', [ALMSController::class, 'deleteAsset']);
Route::get('/assets/stats', [ALMSController::class, 'getAssetStats']);

// Maintenance Schedule routes
Route::get('/maintenance-schedules', [ALMSController::class, 'getMaintenanceSchedules']);
Route::post('/maintenance-schedules', [ALMSController::class, 'createMaintenanceSchedule']);
Route::put('/maintenance-schedules/{id}', [ALMSController::class, 'updateMaintenanceSchedule']);
Route::post('/maintenance-schedules/{id}/complete', [ALMSController::class, 'completeMaintenance']);
Route::get('/maintenance-schedules/stats', [ALMSController::class, 'getMaintenanceStats']);

// Asset Transfer routes
Route::get('/asset-transfers', [ALMSController::class, 'getAssetTransfers']);
Route::post('/asset-transfers', [ALMSController::class, 'createAssetTransfer']);

// Disposal routes
Route::get('/disposals', [ALMSController::class, 'getDisposals']);
Route::post('/disposals', [ALMSController::class, 'createDisposal']);

// Asset Category routes
Route::get('/asset-categories', [ALMSController::class, 'getAssetCategories']);
Route::post('/asset-categories', [ALMSController::class, 'createAssetCategory']);

// Branch routes
Route::get('/branches', [ALMSController::class, 'getBranches']);
Route::post('/branches', [ALMSController::class, 'createBranch']);

// Employee routes
Route::get('/employees', [ALMSController::class, 'getEmployees']);
Route::post('/employees', [ALMSController::class, 'createEmployee']);

// Maintenance Type routes
Route::get('/maintenance-types', [ALMSController::class, 'getMaintenanceTypes']);
Route::post('/maintenance-types', [ALMSController::class, 'createMaintenanceType']);

// Reports routes
Route::get('/reports', [ALMSController::class, 'getReports']);