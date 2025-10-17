<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PltProjectController;
use App\Http\Controllers\PltDispatchController;
use App\Http\Controllers\PltResourceController;
use App\Http\Controllers\PltAllocationController;
use App\Http\Controllers\PltMilestoneController;
use App\Http\Controllers\PltTrackingLogController;

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

// Dispatch routes
Route::get('/dispatches', [PltDispatchController::class, 'index']);
Route::post('/dispatches', [PltDispatchController::class, 'store']);
Route::get('/dispatches/{id}', [PltDispatchController::class, 'show']);
Route::put('/dispatches/{id}', [PltDispatchController::class, 'update']);
Route::delete('/dispatches/{id}', [PltDispatchController::class, 'destroy']);
Route::get('/dispatches/stats', [PltDispatchController::class, 'stats']);

// Resource routes
Route::get('/resources', [PltResourceController::class, 'index']);
Route::post('/resources', [PltResourceController::class, 'store']);
Route::get('/resources/{id}', [PltResourceController::class, 'show']);
Route::put('/resources/{id}', [PltResourceController::class, 'update']);
Route::delete('/resources/{id}', [PltResourceController::class, 'destroy']);
Route::get('/resources/stats', [PltResourceController::class, 'stats']);

// Allocation routes
Route::get('/allocations', [PltAllocationController::class, 'index']);
Route::post('/allocations', [PltAllocationController::class, 'store']);
Route::get('/allocations/{id}', [PltAllocationController::class, 'show']);
Route::put('/allocations/{id}', [PltAllocationController::class, 'update']);
Route::delete('/allocations/{id}', [PltAllocationController::class, 'destroy']);
Route::get('/allocations/stats', [PltAllocationController::class, 'stats']);

// Milestone routes
Route::get('/milestones', [PltMilestoneController::class, 'index']);
Route::post('/milestones', [PltMilestoneController::class, 'store']);
Route::get('/milestones/{id}', [PltMilestoneController::class, 'show']);
Route::put('/milestones/{id}', [PltMilestoneController::class, 'update']);
Route::delete('/milestones/{id}', [PltMilestoneController::class, 'destroy']);
Route::get('/milestones/stats', [PltMilestoneController::class, 'stats']);

// Tracking Log routes
Route::get('/tracking-logs', [PltTrackingLogController::class, 'index']);
Route::post('/tracking-logs', [PltTrackingLogController::class, 'store']);
Route::get('/tracking-logs/{id}', [PltTrackingLogController::class, 'show']);
Route::put('/tracking-logs/{id}', [PltTrackingLogController::class, 'update']);
Route::delete('/tracking-logs/{id}', [PltTrackingLogController::class, 'destroy']);
Route::get('/tracking-logs/dispatch/{dispatchId}', [PltTrackingLogController::class, 'getByDispatch']);