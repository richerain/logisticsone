<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::connection('psm')->hasTable('psm_vendor')) {
            DB::connection('psm')->statement('SET FOREIGN_KEY_CHECKS=0;');
            DB::connection('psm')->table('psm_vendor')->truncate();
            DB::connection('psm')->statement('SET FOREIGN_KEY_CHECKS=1;');
        }
    }

    public function down(): void
    {
        // No way to restore truncated data
    }
};
