<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'sws';

    public function up(): void
    {
        $schema = Schema::connection('sws');
        if ($schema->hasTable('sws_warehouses') && ! $schema->hasTable('sws_warehouse')) {
            DB::connection('sws')->statement('RENAME TABLE sws_warehouses TO sws_warehouse');
        }
    }

    public function down(): void
    {
        $schema = Schema::connection('sws');
        if ($schema->hasTable('sws_warehouse') && ! $schema->hasTable('sws_warehouses')) {
            DB::connection('sws')->statement('RENAME TABLE sws_warehouse TO sws_warehouses');
        }
    }
};
