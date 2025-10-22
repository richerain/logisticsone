<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DTLRController;

// Health check endpoint
Route::get('/health', [DTLRController::class, 'health']);

// Document Tracker Routes
Route::prefix('documents')->group(function () {
    Route::get('/', [DTLRController::class, 'getDocuments']);
    Route::post('/', [DTLRController::class, 'createDocument']);
    Route::get('/{id}', [DTLRController::class, 'getDocument']);
    Route::put('/{id}', [DTLRController::class, 'updateDocument']);
    Route::delete('/{id}', [DTLRController::class, 'deleteDocument']);
    Route::get('/{id}/download', [DTLRController::class, 'downloadDocument']);
});

// Logistics Record Routes
Route::prefix('logistics-records')->group(function () {
    Route::get('/', [DTLRController::class, 'getLogisticsRecords']);
    Route::get('/{id}', [DTLRController::class, 'getLogisticsRecord']);
    Route::post('/export', [DTLRController::class, 'exportLogisticsRecords']);
});

// Statistics and Dashboard Routes
Route::get('/stats', [DTLRController::class, 'getStats']);

// Fallback route for undefined endpoints
Route::fallback(function () {
    return response()->json([
        'success' => false,
        'message' => 'API endpoint not found'
    ], 404);
});