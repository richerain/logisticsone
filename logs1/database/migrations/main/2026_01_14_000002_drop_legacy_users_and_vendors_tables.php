<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::connection('main')->hasTable('users')) {
            Schema::connection('main')->drop('users');
        }

        if (Schema::connection('main')->hasTable('vendors')) {
            Schema::connection('main')->drop('vendors');
        }
    }

    public function down(): void
    {
        // Intentionally left empty: legacy tables are not recreated.
    }
};

