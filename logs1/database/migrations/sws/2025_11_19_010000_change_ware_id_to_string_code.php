<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    protected $connection = 'sws';

    public function up(): void
    {
        // Remove auto_increment from ware_id while keeping current PK
        DB::connection('sws')->statement('ALTER TABLE sws_warehouse MODIFY ware_id INT(11) NOT NULL');
        // Drop existing primary key
        DB::connection('sws')->statement('ALTER TABLE sws_warehouse DROP PRIMARY KEY');
        // Change ware_id to VARCHAR(8)
        DB::connection('sws')->statement('ALTER TABLE sws_warehouse MODIFY ware_id VARCHAR(8) NOT NULL');
        // Convert any numeric ids to WHxxxxxx format
        DB::connection('sws')->statement("UPDATE sws_warehouse SET ware_id = CONCAT('WH', LPAD(ware_id, 6, '0')) WHERE ware_id REGEXP '^[0-9]+$'");
        // Add primary key back on ware_id
        DB::connection('sws')->statement('ALTER TABLE sws_warehouse ADD PRIMARY KEY (ware_id)');
    }

    public function down(): void
    {
        // Revert to INT auto_increment (best-effort)
        DB::connection('sws')->statement('ALTER TABLE sws_warehouse DROP PRIMARY KEY');
        DB::connection('sws')->statement('ALTER TABLE sws_warehouse MODIFY ware_id INT(11) NOT NULL');
        DB::connection('sws')->statement("UPDATE sws_warehouse SET ware_id = CAST(SUBSTRING(ware_id, 3) AS UNSIGNED) WHERE ware_id LIKE 'WH%'");
        DB::connection('sws')->statement('ALTER TABLE sws_warehouse MODIFY ware_id INT(11) NOT NULL AUTO_INCREMENT');
        DB::connection('sws')->statement('ALTER TABLE sws_warehouse ADD PRIMARY KEY (ware_id)');
    }
};
