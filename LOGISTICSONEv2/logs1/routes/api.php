<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return response()->json([
        'message' => 'Logistics 1 Subsystem API',
        'version' => '1.0.0',
        'modules' => [
            'SWS - Smart Warehousing System',
            'PSM - Procurement & Sourcing Management', 
            'PLT - Project Logistics Tracker',
            'ALMS - Asset Lifecycle & Maintenance',
            'DTLR - Document Tracking & Logistics Record'
        ]
    ]);
});

// Health check endpoint
Route::get('/health', function () {
    return response()->json(['status' => 'healthy', 'timestamp' => now()]);
});

// Test database connection
Route::get('/test-db', function () {
    try {
        DB::connection()->getPdo();
        return response()->json(['database' => 'Connected successfully']);
    } catch (\Exception $e) {
        return response()->json(['database' => 'Connection failed: ' . $e->getMessage()], 500);
    }
});