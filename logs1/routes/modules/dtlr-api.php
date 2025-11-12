<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DTLRController;

// DTLR Module API Routes
Route::get('/document-tracker', [DTLRController::class, 'getDocumentTracker']);
Route::get('/logistics-record', [DTLRController::class, 'getLogisticsRecord']);

// Additional DTLR routes can be added here as the module develops
Route::get('/test', function () {
    return response()->json([
        'module' => 'DTLR - Document Tracking & Logistics Record',
        'status' => 'active',
        'submodules' => [
            'Document Tracker',
            'Logistics Record'
        ]
    ]);
});