<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    protected $connection = 'sws';

    public function up(): void
    {
        // Check if we need to modify the column
        $hasIntegerId = false;
        try {
            // This is a rough check, ideally we inspect schema but simple check:
            // If we can insert a string, it's string. If not, it's int.
            // Better: use DB::select to check column type from information_schema
            $results = DB::connection('sws')->select("
                SELECT DATA_TYPE 
                FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE TABLE_SCHEMA = ? AND TABLE_NAME = 'sws_warehouse' AND COLUMN_NAME = 'ware_id'
            ", [config('database.connections.sws.database')]);
            
            if (!empty($results) && strtolower($results[0]->DATA_TYPE) === 'int') {
                $hasIntegerId = true;
            }
        } catch (\Exception $e) {
            // Fallback or ignore
        }

        if (true) { // Force attempt to modify
            // We need to drop primary key, modify column, add primary key
            // Note: If it's auto_increment, we must drop that first.
            
            DB::connection('sws')->statement('ALTER TABLE sws_warehouse MODIFY ware_id INT NOT NULL');
            DB::connection('sws')->statement('ALTER TABLE sws_warehouse DROP PRIMARY KEY');
            DB::connection('sws')->statement('ALTER TABLE sws_warehouse MODIFY ware_id VARCHAR(20) NOT NULL');
            DB::connection('sws')->statement('ALTER TABLE sws_warehouse ADD PRIMARY KEY (ware_id)');
        }
    }

    public function down(): void
    {
        // Irreversible easily as we lose auto increment data or string data might not convert to int
    }
};
