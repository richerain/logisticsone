<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Truncate the table to clear old data (User requested to refresh db data)
        DB::connection('psm')->table('psm_request_budget')->truncate();

        // 2. Change req_id from auto-increment bigint to string
        // We use raw SQL because changing an auto-increment primary key is complex in standard Blueprint
        DB::connection('psm')->statement('ALTER TABLE psm_request_budget MODIFY req_id VARCHAR(50) NOT NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to auto-increment bigint (note: this might fail if strings exist in req_id)
        DB::connection('psm')->statement('ALTER TABLE psm_request_budget MODIFY req_id BIGINT UNSIGNED AUTO_INCREMENT');
    }
};
