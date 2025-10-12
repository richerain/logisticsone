<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DtlrDocumentController;
use App\Http\Controllers\DtlrDocumentLogController;
use App\Http\Controllers\DtlrDocumentReviewController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Document routes
Route::get('/documents', [DtlrDocumentController::class, 'index']);
Route::post('/documents', [DtlrDocumentController::class, 'store']);
Route::get('/documents/{id}', [DtlrDocumentController::class, 'show']);
Route::put('/documents/{id}', [DtlrDocumentController::class, 'update']);
Route::delete('/documents/{id}', [DtlrDocumentController::class, 'destroy']);
Route::post('/documents/{id}/transfer', [DtlrDocumentController::class, 'transferDocument']);

// Document log routes
Route::get('/document-logs', [DtlrDocumentLogController::class, 'index']);
Route::get('/document-logs/{id}', [DtlrDocumentLogController::class, 'show']);

// Document review routes
Route::get('/document-reviews', [DtlrDocumentReviewController::class, 'index']);
Route::post('/document-reviews', [DtlrDocumentReviewController::class, 'store']);
Route::put('/document-reviews/{id}', [DtlrDocumentReviewController::class, 'update']);

// Additional utility routes
Route::get('/document-types', function () {
    return response()->json([
        'success' => true,
        'data' => \App\Models\DtlrDocumentType::all()
    ]);
});

Route::get('/branches', function () {
    return response()->json([
        'success' => true,
        'data' => \App\Models\DtlrBranch::all()
    ]);
});

Route::get('/stats/overview', function () {
    $totalDocuments = \App\Models\DtlrDocument::count();
    $totalLogs = \App\Models\DtlrDocumentLog::count();
    $pendingReviews = \App\Models\DtlrDocumentReview::where('review_status', 'pending')->count();
    $transferredThisMonth = \App\Models\DtlrDocumentLog::where('action', 'transferred')
        ->whereMonth('created_at', now()->month)
        ->count();

    return response()->json([
        'success' => true,
        'data' => [
            'total_documents' => $totalDocuments,
            'total_logs' => $totalLogs,
            'pending_reviews' => $pendingReviews,
            'transferred_this_month' => $transferredThisMonth
        ]
    ]);
});