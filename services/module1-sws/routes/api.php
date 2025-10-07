<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\StorageController;
use App\Http\Controllers\Api\RestockController;

// Auth routes
Route::post('/auth/login', [AuthController::class, 'login']);
Route::put('/profile/update', [ProfileController::class, 'update']);
Route::post('/profile/upload-picture', [ProfileController::class, 'uploadPicture']);

// Inventory routes - match gateway structure
Route::get('/inventory', [InventoryController::class, 'index']);
Route::post('/inventory', [InventoryController::class, 'store']);
Route::get('/inventory/{id}', [InventoryController::class, 'show']);
Route::put('/inventory/{id}', [InventoryController::class, 'update']);
Route::delete('/inventory/{id}', [InventoryController::class, 'destroy']);

// Storage routes - match gateway structure
Route::get('/storage', [StorageController::class, 'index']);
Route::post('/storage', [StorageController::class, 'store']);
Route::get('/storage/{id}', [StorageController::class, 'show']);
Route::put('/storage/{id}', [StorageController::class, 'update']);
Route::delete('/storage/{id}', [StorageController::class, 'destroy']);

// Restock routes - match gateway structure
Route::get('/restock', [RestockController::class, 'index']);
Route::post('/restock', [RestockController::class, 'store']);
Route::get('/restock/{id}', [RestockController::class, 'show']);
Route::put('/restock/{id}', [RestockController::class, 'update']);
Route::delete('/restock/{id}', [RestockController::class, 'destroy']);