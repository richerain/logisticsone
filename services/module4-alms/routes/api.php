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
Route::get('/assets/{id}', [ALMSController::class, 'getAsset']);
Route::put('/assets/{id}', [ALMSController::class, 'updateAsset']);
Route::delete('/assets/{id}', [ALMSController::class, 'deleteAsset']);
Route::get('/assets/stats', [ALMSController::class, 'getAssetStats']);

// Maintenance management routes
Route::get('/maintenance-schedules', [ALMSController::class, 'getMaintenanceSchedules']);
Route::post('/maintenance-schedules', [ALMSController::class, 'createMaintenanceSchedule']);
Route::get('/maintenance-schedules/{id}', [ALMSController::class, 'getMaintenanceSchedule']);
Route::put('/maintenance-schedules/{id}', [ALMSController::class, 'updateMaintenanceSchedule']);
Route::post('/maintenance-schedules/{id}/complete', [ALMSController::class, 'completeMaintenance']);
Route::delete('/maintenance-schedules/{id}', [ALMSController::class, 'deleteMaintenanceSchedule']);
Route::get('/maintenance-schedules/stats', [ALMSController::class, 'getMaintenanceStats']);

// Helper method for getting a single asset
Route::get('/assets/{id}', function ($id) {
    return app(ALMSController::class)->getAsset($id);
});

// Helper method for getting a single maintenance schedule  
Route::get('/maintenance-schedules/{id}', function ($id) {
    return app(ALMSController::class)->getMaintenanceSchedule($id);
});

// Helper method for deleting a maintenance schedule
Route::delete('/maintenance-schedules/{id}', function ($id) {
    return app(ALMSController::class)->deleteMaintenanceSchedule($id);
});