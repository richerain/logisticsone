<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Debug routes for PSM module
Route::get('/debug/psm', function () {
    $debugInfo = [
        'module' => 'PSM Debug Information',
        'timestamp' => now(),
        'database_connections' => [],
        'routes' => [],
        'models' => [],
    ];

    // Check database connections
    try {
        DB::connection('psm')->getPdo();
        $debugInfo['database_connections']['psm'] = 'Connected';
        $debugInfo['vendor_count'] = DB::connection('psm')->table('psm_vendor')->count();
        $debugInfo['product_count'] = DB::connection('psm')->table('psm_product')->count();
    } catch (\Exception $e) {
        $debugInfo['database_connections']['psm'] = 'Failed: '.$e->getMessage();
    }

    // Check if models exist
    $debugInfo['models']['Vendor'] = class_exists('App\Models\PSM\Vendor') ? 'Exists' : 'Not found';
    $debugInfo['models']['Product'] = class_exists('App\Models\PSM\Product') ? 'Exists' : 'Not found';

    // List available PSM routes
    $routeCollection = Route::getRoutes();
    foreach ($routeCollection->getRoutes() as $route) {
        $uri = $route->uri();
        if (str_contains($uri, 'psm')) {
            $debugInfo['routes'][] = [
                'uri' => $uri,
                'methods' => $route->methods(),
            ];
        }
    }

    return response()->json($debugInfo);
});
