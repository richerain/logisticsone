<?php

use App\Http\Controllers\DTLRController;
use Illuminate\Support\Facades\Route;

// DTLR Module API Routes
Route::get('/document-tracker', [DTLRController::class, 'getDocumentTracker']);
Route::post('/document-tracker', [DTLRController::class, 'createDocument']);
// External dataset with API key (must be before dynamic {docId} routes)
Route::get('/document-tracker/external', [DTLRController::class, 'externalDocumentTracker']);
Route::get('/document-tracker/{docId}/view', [DTLRController::class, 'viewDocument']);
Route::get('/document-tracker/{docId}/download', [DTLRController::class, 'downloadDocument']);
Route::patch('/document-tracker/{docId}/status', [DTLRController::class, 'updateDocumentStatus']);
Route::delete('/document-tracker/{docId}', [DTLRController::class, 'deleteDocument'])->where('docId', '^(?!external$).+');
Route::get('/logistics-record', [DTLRController::class, 'getLogisticsRecord']);

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
