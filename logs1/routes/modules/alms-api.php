<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ALMSController;

// ALMS Module API Routes
Route::get('/assets', [ALMSController::class, 'getAssets']);
Route::get('/maintenance', [ALMSController::class, 'getMaintenance']);

// Additional ALMS routes can be added here as the module develops
Route::get('/test', function () {
    return response()->json([
        'module' => 'ALMS - Asset Lifecycle & Maintenance',
        'status' => 'active',
        'submodules' => [
            'Asset Management',
            'Maintenance Management'
        ]
    ]);
});