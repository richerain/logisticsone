<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GatewayController;

// login routes start 
Route::post('/auth/login', [GatewayController::class, 'login']);
Route::put('/profile/update', [GatewayController::class, 'updateProfile']);
Route::post('/profile/upload-picture', [GatewayController::class, 'uploadProfilePicture']);
// login routes end

//otp routes start
Route::post('/auth/generate-otp', function (Request $request) {
    return app(GatewayController::class)->proxyPost($request, 'http://localhost:8002/api/auth/generate-otp');
});

Route::post('/auth/verify-otp', function (Request $request) {
    return app(GatewayController::class)->proxyPost($request, 'http://localhost:8002/api/auth/verify-otp');
});

Route::post('/auth/resend-otp', function (Request $request) {
    return app(GatewayController::class)->proxyPost($request, 'http://localhost:8002/api/auth/resend-otp');
});

Route::post('/auth/check-otp-session', function (Request $request) {
    return app(GatewayController::class)->proxyPost($request, 'http://localhost:8002/api/auth/check-otp-session');
});

Route::post('/auth/test-email', function (Request $request) {
    return app(GatewayController::class)->proxyPost($request, 'http://localhost:8002/api/auth/test-email');
});

Route::post('/auth/test-real-email', function (Request $request) {
    return app(GatewayController::class)->proxyPost($request, 'http://localhost:8002/api/auth/test-real-email');
});

// Debug route
Route::get('/debug/email-config', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8002/api/debug/email-config');
});
//otp routes end

// module1-sws entire routes start
// SWS Warehousing routes - GRN Management
Route::get('/sws/warehousing', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8002/api/warehousing');
});
Route::post('/sws/warehousing', function (Request $request) {
    return app(GatewayController::class)->proxyPost($request, 'http://localhost:8002/api/warehousing');
});
Route::get('/sws/warehousing/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyGet($request, "http://localhost:8002/api/warehousing/{$id}");
});
Route::put('/sws/warehousing/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyPut($request, "http://localhost:8002/api/warehousing/{$id}");
});
Route::delete('/sws/warehousing/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyDelete($request, "http://localhost:8002/api/warehousing/{$id}");
});
Route::get('/sws/warehousing/stats/overview', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8002/api/warehousing/stats/overview');
});
Route::get('/sws/warehousing/search/filter', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8002/api/warehousing/search/filter');
});

// SWS Digital Inventory routes
Route::get('/sws/digital', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8002/api/digital');
});
Route::post('/sws/digital', function (Request $request) {
    return app(GatewayController::class)->proxyPost($request, 'http://localhost:8002/api/digital');
});
Route::get('/sws/digital/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyGet($request, "http://localhost:8002/api/digital/{$id}");
});
Route::put('/sws/digital/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyPut($request, "http://localhost:8002/api/digital/{$id}");
});
Route::delete('/sws/digital/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyDelete($request, "http://localhost:8002/api/digital/{$id}");
});
Route::get('/sws/digital/stats/overview', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8002/api/digital/stats/overview');
});
Route::get('/sws/digital/search/filter', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8002/api/digital/search/filter');
});
Route::get('/sws/digital/received-quotes', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8002/api/digital/received-quotes');
});
Route::post('/sws/digital/sync-from-grn/{grnId}', function (Request $request, $grnId) {
    return app(GatewayController::class)->proxyPost($request, "http://localhost:8002/api/digital/sync-from-grn/{$grnId}");
});

// SWS Restock routes (now points to digital inventory)
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
// PSM Vendor routes
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

// PSM Vendor Products routes
Route::get('/psm/vendor-products', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8003/api/vendor-products');
});
Route::get('/psm/vendors/{vendorId}/products', function (Request $request, $vendorId) {
    return app(GatewayController::class)->proxyGet($request, "http://localhost:8003/api/vendors/{$vendorId}/products");
});
Route::post('/psm/vendor-products', function (Request $request) {
    return app(GatewayController::class)->proxyPost($request, 'http://localhost:8003/api/vendor-products');
});
Route::get('/psm/vendor-products/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyGet($request, "http://localhost:8003/api/vendor-products/{$id}");
});
Route::put('/psm/vendor-products/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyPut($request, "http://localhost:8003/api/vendor-products/{$id}");
});
Route::delete('/psm/vendor-products/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyDelete($request, "http://localhost:8003/api/vendor-products/{$id}");
});

// PSM Quote routes
Route::get('/psm/quotes', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8003/api/quotes');
});
Route::post('/psm/quotes', function (Request $request) {
    return app(GatewayController::class)->proxyPost($request, 'http://localhost:8003/api/quotes');
});
Route::get('/psm/quotes/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyGet($request, "http://localhost:8003/api/quotes/{$id}");
});
Route::put('/psm/quotes/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyPut($request, "http://localhost:8003/api/quotes/{$id}");
});
Route::delete('/psm/quotes/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyDelete($request, "http://localhost:8003/api/quotes/{$id}");
});

// PSM Purchase Management routes
Route::get('/psm/purchase/requests', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8003/api/purchase/requests');
});
Route::post('/psm/purchase/requests', function (Request $request) {
    return app(GatewayController::class)->proxyPost($request, 'http://localhost:8003/api/purchase/requests');
});
Route::get('/psm/purchase/requests/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyGet($request, "http://localhost:8003/api/purchase/requests/{$id}");
});
Route::put('/psm/purchase/requests/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyPut($request, "http://localhost:8003/api/purchase/requests/{$id}");
});
Route::delete('/psm/purchase/requests/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyDelete($request, "http://localhost:8003/api/purchase/requests/{$id}");
});
Route::get('/psm/purchase/requests-for-quotes', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8003/api/purchase/requests-for-quotes');
});
// module2-psm entire routes end

// module3-plt entire routes start
// PLT Logistics Projects routes
Route::get('/plt/logistics', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8004/api/logistics');
});

Route::post('/plt/logistics', function (Request $request) {
    return app(GatewayController::class)->proxyPost($request, 'http://localhost:8004/api/logistics');
});

Route::get('/plt/logistics/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyGet($request, "http://localhost:8004/api/logistics/{$id}");
});

Route::put('/plt/logistics/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyPut($request, "http://localhost:8004/api/logistics/{$id}");
});

Route::delete('/plt/logistics/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyDelete($request, "http://localhost:8004/api/logistics/{$id}");
});

Route::get('/plt/logistics/stats', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8004/api/logistics/stats');
});

Route::put('/plt/logistics/{id}/status', function (Request $request, $id) {
    return app(GatewayController::class)->proxyPut($request, "http://localhost:8004/api/logistics/{$id}/status");
});
// module3-plt entire routes end

// module4-alms entire routes start
// Asset routes
Route::get('/alms/assets', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8005/api/assets');
});
Route::post('/alms/assets', function (Request $request) {
    return app(GatewayController::class)->proxyPost($request, 'http://localhost:8005/api/assets');
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

// Maintenance Schedule routes
Route::get('/alms/maintenance-schedules', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8005/api/maintenance-schedules');
});
Route::post('/alms/maintenance-schedules', function (Request $request) {
    return app(GatewayController::class)->proxyPost($request, 'http://localhost:8005/api/maintenance-schedules');
});
Route::get('/alms/maintenance-schedules/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyGet($request, "http://localhost:8005/api/maintenance-schedules/{$id}");
});
Route::put('/alms/maintenance-schedules/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyPut($request, "http://localhost:8005/api/maintenance-schedules/{$id}");
});
Route::post('/alms/maintenance-schedules/{id}/complete', function (Request $request, $id) {
    return app(GatewayController::class)->proxyPost($request, "http://localhost:8005/api/maintenance-schedules/{$id}/complete");
});
Route::get('/alms/maintenance-schedules/stats', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8005/api/maintenance-schedules/stats');
});
// module4-alms entire routes end

// module5-dtlr entire routes start
// Document tracker routes
Route::get('/dtlr/documents', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8006/api/documents');
});

Route::post('/dtlr/documents', function (Request $request) {
    return app(GatewayController::class)->uploadDocument($request);
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

Route::get('/dtlr/documents/{id}/download', function (Request $request, $id) {
    return app(GatewayController::class)->proxyGet($request, "http://localhost:8006/api/documents/{$id}/download");
});

// Logistics Record routes
Route::get('/dtlr/logistics-records', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8006/api/logistics-records');
});

Route::get('/dtlr/logistics-records/{id}', function (Request $request, $id) {
    return app(GatewayController::class)->proxyGet($request, "http://localhost:8006/api/logistics-records/{$id}");
});

Route::post('/dtlr/logistics-records/export', function (Request $request) {
    return app(GatewayController::class)->proxyPost($request, 'http://localhost:8006/api/logistics-records/export');
});

// Stats route
Route::get('/dtlr/stats', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8006/api/stats');
});

// Health check
Route::get('/dtlr/health', function (Request $request) {
    return app(GatewayController::class)->proxyGet($request, 'http://localhost:8006/api/health');
});
// module5-dtlr entire routes end