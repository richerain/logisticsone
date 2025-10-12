<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PltProjectController;

// Test route to verify API is working
Route::get('/test', function () {
    return response()->json([
        'success' => true,
        'message' => 'PLT API is working!',
        'service' => 'Project Logistics Tracker',
        'timestamp' => now()
    ]);
});

// Project routes
Route::get('/projects', [PltProjectController::class, 'index']);
Route::post('/projects', [PltProjectController::class, 'store']);
Route::get('/projects/{id}', [PltProjectController::class, 'show']);
Route::put('/projects/{id}', [PltProjectController::class, 'update']);
Route::delete('/projects/{id}', [PltProjectController::class, 'destroy']);
Route::get('/projects/stats', [PltProjectController::class, 'stats']);

// Basic routes for other modules (you can expand these later)
Route::get('/dispatches', function () {
    return response()->json([
        'success' => true,
        'data' => [],
        'message' => 'Dispatches endpoint - add controller later'
    ]);
});

Route::get('/resources', function () {
    return response()->json([
        'success' => true,
        'data' => [],
        'message' => 'Resources endpoint - add controller later'
    ]);
});

Route::get('/allocations', function () {
    return response()->json([
        'success' => true,
        'data' => [],
        'message' => 'Allocations endpoint - add controller later'
    ]);
});

Route::get('/milestones', function () {
    return response()->json([
        'success' => true,
        'data' => [],
        'message' => 'Milestones endpoint - add controller later'
    ]);
});

Route::get('/tracking-logs', function () {
    return response()->json([
        'success' => true,
        'data' => [],
        'message' => 'Tracking logs endpoint - add controller later'
    ]);
});