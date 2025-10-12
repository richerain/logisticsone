<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GatewayController;

// login routes start 
Route::post('/auth/login', [GatewayController::class, 'login']);
Route::put('/profile/update', [GatewayController::class, 'updateProfile']);
Route::post('/profile/upload-picture', [GatewayController::class, 'uploadProfilePicture']);
// login routes end

// module1-sws entire routes start
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
    return app(GatewayController::class)->proxyUpload($request, 'http://localhost:8003/api/products');
});
Route::put('/psm/products/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyUpload($request, "http://localhost:8003/api/products/{$id}");
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
// Project routes
Route::get('/plt/projects', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8004/api/projects');
});
Route::post('/plt/projects', function (Request $request) {
    return app(GatewayController::class)->proxyPost($request, 'http://localhost:8004/api/projects');
});
Route::get('/plt/projects/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyGet($request, "http://localhost:8004/api/projects/{$id}");
});
Route::put('/plt/projects/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyPut($request, "http://localhost:8004/api/projects/{$id}");
});
Route::delete('/plt/projects/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyDelete($request, "http://localhost:8004/api/projects/{$id}");
});
Route::get('/plt/projects/stats', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8004/api/projects/stats');
});

// Dispatch routes
Route::get('/plt/dispatches', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8004/api/dispatches');
});
Route::post('/plt/dispatches', function (Request $request) {
    return app(GatewayController::class)->proxyPost($request, 'http://localhost:8004/api/dispatches');
});
Route::get('/plt/dispatches/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyGet($request, "http://localhost:8004/api/dispatches/{$id}");
});
Route::put('/plt/dispatches/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyPut($request, "http://localhost:8004/api/dispatches/{$id}");
});
Route::delete('/plt/dispatches/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyDelete($request, "http://localhost:8004/api/dispatches/{$id}");
});
Route::get('/plt/dispatches/stats', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8004/api/dispatches/stats');
});

// Tracking Log routes
Route::get('/plt/tracking-logs', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8004/api/tracking-logs');
});
Route::post('/plt/tracking-logs', function (Request $request) {
    return app(GatewayController::class)->proxyPost($request, 'http://localhost:8004/api/tracking-logs');
});
Route::get('/plt/tracking-logs/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyGet($request, "http://localhost:8004/api/tracking-logs/{$id}");
});
Route::put('/plt/tracking-logs/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyPut($request, "http://localhost:8004/api/tracking-logs/{$id}");
});
Route::delete('/plt/tracking-logs/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyDelete($request, "http://localhost:8004/api/tracking-logs/{$id}");
});
Route::get('/plt/tracking-logs/dispatch/{dispatchId}', function (Request $request, $dispatchId) {
    return app(GatewayController::class)->proxyGet($request, "http://localhost:8004/api/tracking-logs/dispatch/{$dispatchId}");
});

// Resource routes
Route::get('/plt/resources', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8004/api/resources');
});
Route::post('/plt/resources', function (Request $request) {
    return app(GatewayController::class)->proxyPost($request, 'http://localhost:8004/api/resources');
});
Route::get('/plt/resources/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyGet($request, "http://localhost:8004/api/resources/{$id}");
});
Route::put('/plt/resources/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyPut($request, "http://localhost:8004/api/resources/{$id}");
});
Route::delete('/plt/resources/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyDelete($request, "http://localhost:8004/api/resources/{$id}");
});
Route::get('/plt/resources/stats', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8004/api/resources/stats');
});

// Allocation routes
Route::get('/plt/allocations', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8004/api/allocations');
});
Route::post('/plt/allocations', function (Request $request) {
    return app(GatewayController::class)->proxyPost($request, 'http://localhost:8004/api/allocations');
});
Route::get('/plt/allocations/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyGet($request, "http://localhost:8004/api/allocations/{$id}");
});
Route::put('/plt/allocations/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyPut($request, "http://localhost:8004/api/allocations/{$id}");
});
Route::delete('/plt/allocations/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyDelete($request, "http://localhost:8004/api/allocations/{$id}");
});
Route::get('/plt/allocations/stats', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8004/api/allocations/stats');
});

// Milestone routes
Route::get('/plt/milestones', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8004/api/milestones');
});
Route::post('/plt/milestones', function (Request $request) {
    return app(GatewayController::class)->proxyPost($request, 'http://localhost:8004/api/milestones');
});
Route::get('/plt/milestones/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyGet($request, "http://localhost:8004/api/milestones/{$id}");
});
Route::put('/plt/milestones/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyPut($request, "http://localhost:8004/api/milestones/{$id}");
});
Route::delete('/plt/milestones/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyDelete($request, "http://localhost:8004/api/milestones/{$id}");
});
Route::get('/plt/milestones/stats', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8004/api/milestones/stats');
});
// module3-plt entire routes end

// module4-alms entire routes start
// Asset routes
Route::get('/alms/assets', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8005/api/assets');
});
Route::post('/alms/assets', function (Request $request) {
    return app(GatewayController::class)->proxyUpload($request, 'http://localhost:8005/api/assets');
});
Route::get('/alms/assets/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyGet($request, "http://localhost:8005/api/assets/{$id}");
});
Route::put('/alms/assets/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyPut($request, "http://localhost:8005/api/assets/{$id}");
});
Route::delete('/alms/assets/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyDelete($request, "http://localhost:8005/api/assets/{$id}");
});
Route::get('/alms/assets/stats', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8005/api/assets/stats');
});

// Asset Category routes
Route::get('/alms/asset-categories', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8005/api/asset-categories');
});
Route::post('/alms/asset-categories', function (Request $request) {
    return app(GatewayController::class)->proxyPost($request, 'http://localhost:8005/api/asset-categories');
});

// Branch routes
Route::get('/alms/branches', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8005/api/branches');
});

// Employee routes
Route::get('/alms/employees', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8005/api/employees');
});
Route::post('/alms/employees', function (Request $request) {
    return app(GatewayController::class)->proxyPost($request, 'http://localhost:8005/api/employees');
});

// Maintenance routes
Route::get('/alms/maintenances', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8005/api/maintenances');
});
Route::post('/alms/maintenances', function (Request $request) {
    return app(GatewayController::class)->proxyPost($request, 'http://localhost:8005/api/maintenances');
});
Route::put('/alms/maintenances/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyPut($request, "http://localhost:8005/api/maintenances/{$id}");
});

// Depreciation routes
Route::get('/alms/depreciations', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8005/api/depreciations');
});
Route::post('/alms/depreciations', function (Request $request) {
    return app(GatewayController::class)->proxyPost($request, 'http://localhost:8005/api/depreciations');
});
// module4-alms entire routes end

// module5-dtlr entire routes start
// Document routes
Route::get('/dtlr/documents', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8006/api/documents');
});
Route::post('/dtlr/documents', function (Request $request) {
    return app(GatewayController::class)->proxyUpload($request, 'http://localhost:8006/api/documents');
});
Route::get('/dtlr/documents/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyGet($request, "http://localhost:8006/api/documents/{$id}");
});
Route::put('/dtlr/documents/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyPut($request, "http://localhost:8006/api/documents/{$id}");
});
Route::delete('/dtlr/documents/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyDelete($request, "http://localhost:8006/api/documents/{$id}");
});
Route::post('/dtlr/documents/{id}/transfer', function (Request $request, $id) {
    return app(GatewayController::class)->proxyPost($request, "http://localhost:8006/api/documents/{$id}/transfer");
});

// Document log routes
Route::get('/dtlr/document-logs', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8006/api/document-logs');
});
Route::get('/dtlr/document-logs/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyGet($request, "http://localhost:8006/api/document-logs/{$id}");
});

// Document review routes
Route::get('/dtlr/document-reviews', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8006/api/document-reviews');
});
Route::post('/dtlr/document-reviews', function (Request $request) {
    return app(GatewayController::class)->proxyPost($request, 'http://localhost:8006/api/document-reviews');
});
Route::put('/dtlr/document-reviews/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyPut($request, "http://localhost:8006/api/document-reviews/{$id}");
});

// Utility routes
Route::get('/dtlr/document-types', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8006/api/document-types');
});
Route::get('/dtlr/branches', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8006/api/branches');
});
Route::get('/dtlr/stats/overview', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8006/api/stats/overview');
});
// module5-dtlr entire routes end