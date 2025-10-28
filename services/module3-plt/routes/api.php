<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PLTController;

// Test route to verify API is working
Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'PLT API is working!',
        'service' => 'Project Logistics Tracker',
        'timestamp' => now()
    ]);
});

// Logistics Projects routes - FIXED ORDER
Route::get('/logistics/stats', [PLTController::class, 'stats']); // This must come BEFORE the {id} routes
Route::get('/logistics', [PLTController::class, 'index']);
Route::post('/logistics', [PLTController::class, 'store']);
Route::get('/logistics/{id}', [PLTController::class, 'show']);
Route::put('/logistics/{id}', [PLTController::class, 'update']);
Route::delete('/logistics/{id}', [PLTController::class, 'destroy']);
Route::put('/logistics/{id}/status', [PLTController::class, 'updateStatus']);