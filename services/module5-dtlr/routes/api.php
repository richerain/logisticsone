<?php

use App\Http\Controllers\DTLRController;
use Illuminate\Support\Facades\Route;

Route::get('/health', [DTLRController::class, 'healthCheck']);

// Document routes
Route::prefix('documents')->group(function () {
    Route::get('/', [DTLRController::class, 'getDocuments']);
    Route::post('/', [DTLRController::class, 'createDocument']);
    Route::get('/{id}', [DTLRController::class, 'getDocument']);
    Route::put('/{id}', [DTLRController::class, 'updateDocument']);
    Route::delete('/{id}', [DTLRController::class, 'deleteDocument']);
    Route::post('/{id}/process-ocr', [DTLRController::class, 'processOCR']);
});

// Logistics Records routes
Route::prefix('logistics-records')->group(function () {
    Route::get('/', [DTLRController::class, 'getLogisticsRecords']);
    Route::post('/', [DTLRController::class, 'addLogisticsRecord']);
    Route::put('/{id}', [DTLRController::class, 'updateLogisticsRecord']);
    Route::delete('/{id}', [DTLRController::class, 'deleteLogisticsRecord']);
});

// Utility routes
Route::get('/document-types', [DTLRController::class, 'getDocumentTypes']);
Route::get('/stats/overview', [DTLRController::class, 'getOverviewStats']);

// Search route
Route::get('/search', [DTLRController::class, 'getDocuments']);