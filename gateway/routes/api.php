<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GatewayController;

// login routes start 
Route::post('/auth/login', [GatewayController::class, 'login']);
Route::put('/profile/update', [GatewayController::class, 'updateProfile']);
Route::post('/profile/upload-picture', [GatewayController::class, 'uploadProfilePicture']);
// login routes end

// module2-sws entire routes start
// SWS Inventory routes
Route::get('/sws/inventory', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8002/api/inventory');
});
Route::post('/sws/inventory', function (Request $request) {
    return app(GatewayController::class)->proxyPost($request, 'http://localhost:8002/api/inventory');
});
Route::get('/sws/inventory/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyGet($request, "http://localhost:8002/api/inventory/{$id}");
});
Route::put('/sws/inventory/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyPut($request, "http://localhost:8002/api/inventory/{$id}");
});
Route::delete('/sws/inventory/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyDelete($request, "http://localhost:8002/api/inventory/{$id}");
});

// SWS Storage routes
Route::get('/sws/storage', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8002/api/storage');
});
Route::post('/sws/storage', function (Request $request) {
    return app(GatewayController::class)->proxyPost($request, 'http://localhost:8002/api/storage');
});
Route::get('/sws/storage/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyGet($request, "http://localhost:8002/api/storage/{$id}");
});
Route::put('/sws/storage/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyPut($request, "http://localhost:8002/api/storage/{$id}");
});
Route::delete('/sws/storage/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyDelete($request, "http://localhost:8002/api/storage/{$id}");
});

// SWS Restock routes
Route::get('/sws/restock', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8002/api/restock');
});
Route::post('/sws/restock', function (Request $request) {
    return app(GatewayController::class)->proxyPost($request, 'http://localhost:8002/api/restock');
});
Route::get('/sws/restock/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyGet($request, "http://localhost:8002/api/restock/{$id}");
});
Route::put('/sws/restock/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyPut($request, "http://localhost:8002/api/restock/{$id}");
});
Route::delete('/sws/restock/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyDelete($request, "http://localhost:8002/api/restock/{$id}");
});
// module1-sws entire routes end

// module2-psm entire routes start
Route::get('/psm/vendors', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8003/api/vendors');
});
Route::post('/psm/vendors', function (Request $request) {
    return app(GatewayController::class)->proxyPost($request, 'http://localhost:8003/api/vendors');
});
Route::put('/psm/vendors/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyPut($request, "http://localhost:8003/api/vendors/{$id}");
});
Route::delete('/psm/vendors/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyDelete($request, "http://localhost:8003/api/vendors/{$id}");
});

// PSM Shop routes
Route::get('/psm/shops', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8003/api/shops');
});
Route::post('/psm/shops', function (Request $request) {
    return app(GatewayController::class)->proxyPost($request, 'http://localhost:8003/api/shops');
});

// PSM Product routes
Route::get('/psm/products', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8003/api/products');
});
Route::post('/psm/products', function (Request $request) {
    return app(GatewayController::class)->proxyPost($request, 'http://localhost:8003/api/products');
});
Route::put('/psm/products/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyPut($request, "http://localhost:8003/api/products/{$id}");
});
Route::delete('/psm/products/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyDelete($request, "http://localhost:8003/api/products/{$id}");
});

// PSM Market routes
Route::get('/psm/market/products', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8003/api/market/products');
});

// PSM Order routes
Route::get('/psm/orders', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8003/api/orders');
});
Route::post('/psm/orders', function (Request $request) {
    return app(GatewayController::class)->proxyPost($request, 'http://localhost:8003/api/orders');
});
Route::put('/psm/orders/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyPut($request, "http://localhost:8003/api/orders/{$id}");
});

// PSM Budget Approval routes
Route::get('/psm/budget-approvals', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8003/api/budget-approvals');
});
Route::put('/psm/budget-approvals/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyPut($request, "http://localhost:8003/api/budget-approvals/{$id}");
});

// PSM Reorder routes
Route::get('/psm/reorders', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8003/api/reorders');
});
Route::post('/psm/reorders', function (Request $request) {
    return app(GatewayController::class)->proxyPost($request, 'http://localhost:8003/api/reorders');
});
// module2-psm entire routes end

// module3-plt entire routes start
// module3-plt entire routes end

// module4-alms entire routes start
// module4-alms entire routes end

// module5-dtlr entire routes start
// module5-dtlr entire routes end

// CORS headers
Route::middleware([])->group(function () {
    // Add CORS headers for all API routes
});