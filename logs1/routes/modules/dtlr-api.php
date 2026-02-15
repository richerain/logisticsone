<?php

use App\Http\Controllers\DTLRController;
use Illuminate\Support\Facades\Route;

// DTLR Module API Routes
Route::get('/document-tracker', [DTLRController::class, 'getDocumentTracker']);
Route::post('/document-tracker', [DTLRController::class, 'createDocument']);
Route::get('/document-tracker/{docId}/view', [DTLRController::class, 'viewDocument']);
Route::get('/document-tracker/{docId}/download', [DTLRController::class, 'downloadDocument']);
Route::patch('/document-tracker/{docId}/status', [DTLRController::class, 'updateDocumentStatus']);
Route::delete('/document-tracker/{docId}', [DTLRController::class, 'deleteDocument']);
Route::get('/logistics-record', [DTLRController::class, 'getLogisticsRecord']);

// External dataset with API key
Route::get('/document-tracker/external', [DTLRController::class, 'externalDocumentTracker']);
// Additional DTLR routes can be added here as the module develops
Route::get('/test', function () {
    return response()->json([
        'module' => 'DTLR - Document Tracking & Logistics Record',
        'status' => 'active',
        'submodules' => [
            'Document Tracker',
            'Logistics Record',
        ],
    ]);
});
