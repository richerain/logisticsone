<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Register migration paths for each module
        $this->loadMigrationsFrom([
            database_path('migrations/sws'),
            database_path('migrations/psm'),
            database_path('migrations/plt'),
            database_path('migrations/alms'),
            database_path('migrations/dtlr'),
        ]);
    }
}
