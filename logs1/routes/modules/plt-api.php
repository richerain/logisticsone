<?php

use App\Http\Controllers\PLTController;
use Illuminate\Support\Facades\Route;

// PLT Module API Routes
Route::get('/projects', [PLTController::class, 'getProjects']);
Route::get('/projects/stats', [PLTController::class, 'getProjectStats']);
Route::get('/projects/{id}', [PLTController::class, 'getProject']);
Route::post('/projects', [PLTController::class, 'createProject']);
Route::put('/projects/{id}', [PLTController::class, 'updateProject']);
Route::delete('/projects/{id}', [PLTController::class, 'deleteProject']);

// Milestones
Route::get('/projects/{id}/milestones', [PLTController::class, 'getProjectMilestones']);
Route::post('/projects/{id}/milestones', [PLTController::class, 'createMilestone']);
Route::put('/milestones/{id}', [PLTController::class, 'updateMilestone']);
Route::delete('/milestones/{id}', [PLTController::class, 'deleteMilestone']);

// Movement Project (new)
Route::get('/movement-project', [PLTController::class, 'getMovementProjects']);
Route::post('/movement-project', [PLTController::class, 'createMovementProject']);
Route::put('/movement-project/{id}/status', [PLTController::class, 'updateMovementStatus']);
Route::put('/movement-project/{id}/end-date', [PLTController::class, 'updateMovementEndDate']);
Route::delete('/movement-project/{id}', [PLTController::class, 'deleteMovementProject']);

// Additional PLT routes can be added here as the module develops
Route::get('/test', function () {
    return response()->json([
        'module' => 'PLT - Project Logistics Tracker',
        'status' => 'active',
        'submodules' => [
            'Logistics Projects',
        ],
    ]);
});
