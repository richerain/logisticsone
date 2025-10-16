<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\InventoryController;
use App\Http\Controllers\Api\StorageController;
use App\Http\Controllers\Api\RestockController;

// Auth routes
Route::post('/auth/login', [AuthController::class, 'login']);

// OTP routes with real email testing
Route::post('/auth/test-email', [OtpController::class, 'testEmail']);
Route::post('/auth/test-real-email', [OtpController::class, 'testRealEmail']);
Route::post('/auth/generate-otp', [OtpController::class, 'generateOtp']);
Route::post('/auth/verify-otp', [OtpController::class, 'verifyOtp']);
Route::post('/auth/resend-otp', [OtpController::class, 'resendOtp']);
Route::post('/auth/check-otp-session', [OtpController::class, 'checkOtpSession']);

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

// Debug route to check email configuration
Route::get('/debug/email-config', function() {
    return response()->json([
        'mail_host' => env('MAIL_HOST'),
        'mail_port' => env('MAIL_PORT'),
        'mail_username' => env('MAIL_USERNAME'),
        'mail_from_address' => env('MAIL_FROM_ADDRESS'),
        'mail_from_name' => env('MAIL_FROM_NAME'),
        'is_gmail' => str_contains(env('MAIL_HOST'), 'gmail.com')
    ]);
});