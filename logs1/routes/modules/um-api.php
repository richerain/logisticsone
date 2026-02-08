<?php

use App\Http\Controllers\UserManagementController;
use Illuminate\Support\Facades\Route;

Route::controller(UserManagementController::class)->group(function () {
    Route::get('/accounts', 'getAccounts');
    Route::get('/stats', 'getStats');
    Route::post('/accounts/employee', 'createEmployee');
    Route::post('/accounts/vendor', 'createVendor');
    Route::get('/accounts/{id}', 'show');
    Route::put('/accounts/{id}/role', 'updateRole');
    Route::put('/accounts/{id}/status', 'updateStatus');
    Route::delete('/accounts/{id}', 'destroy');
});
