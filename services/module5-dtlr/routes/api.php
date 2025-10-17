<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DTLRController;

/*
|--------------------------------------------------------------------------
| API Routes for Document Tracking & Logistics Record (DTLR)
|--------------------------------------------------------------------------
*/

// Document routes
Route::get('/documents', [DTLRController::class, 'getDocuments']);
Route::post('/documents', [DTLRController::class, 'createDocument']);
Route::get('/documents/{id}', [DTLRController::class, 'getDocument']);
Route::put('/documents/{id}', [DTLRController::class, 'updateDocument']);
Route::delete('/documents/{id}', [DTLRController::class, 'deleteDocument']);
Route::post('/documents/{id}/transfer', [DTLRController::class, 'transferDocument']);
Route::post('/documents/{id}/process-ocr', [DTLRController::class, 'processOCR']);

// Document log routes
Route::get('/document-logs', [DTLRController::class, 'getDocumentLogs']);
Route::get('/document-logs/{id}', [DTLRController::class, 'getDocumentLog']);

// Document review routes
Route::get('/document-reviews', [DTLRController::class, 'getDocumentReviews']);
Route::post('/document-reviews', [DTLRController::class, 'createDocumentReview']);
Route::put('/document-reviews/{id}', [DTLRController::class, 'updateDocumentReview']);

// Utility routes
Route::get('/document-types', [DTLRController::class, 'getDocumentTypes']);
Route::get('/branches', [DTLRController::class, 'getBranches']);
Route::get('/stats/overview', [DTLRController::class, 'getOverviewStats']);
Route::get('/search', [DTLRController::class, 'searchDocuments']);

// Health check
Route::get('/health', function () {
    return response()->json([
        'success' => true,
        'message' => 'DTLR Service is running',
        'timestamp' => now()->toISOString()
    ]);
});