<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PLTController;

// PLT Module API Routes
Route::get('/projects', [PLTController::class, 'getProjects']);

// Additional PLT routes can be added here as the module develops
Route::get('/test', function () {
    return response()->json([
        'module' => 'PLT - Project Logistics Tracker',
        'status' => 'active',
        'submodules' => [
            'Logistics Projects'
        ]
    ]);
});