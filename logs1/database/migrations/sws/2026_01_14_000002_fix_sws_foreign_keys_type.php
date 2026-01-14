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
        $this->safeDropForeignKey('sws_transactions', 'sws_transactions_tra_warehouse_id_foreign');
        if (Schema::connection('sws')->hasTable('sws_transactions')) {
            Schema::connection('sws')->table('sws_transactions', function (Blueprint $table) {
                 $table->string('tra_warehouse_id', 20)->nullable()->change();
            });
        }

        $this->safeDropForeignKey('sws_inventory_audits', 'sws_inventory_audits_aud_warehouse_id_foreign');
        if (Schema::connection('sws')->hasTable('sws_inventory_audits')) {
            Schema::connection('sws')->table('sws_inventory_audits', function (Blueprint $table) {
                 $table->string('aud_warehouse_id', 20)->nullable()->change();
            });
        }

        $this->safeDropForeignKey('sws_inventory_snapshots', 'sws_inventory_snapshots_snap_warehouse_id_foreign');
        if (Schema::connection('sws')->hasTable('sws_inventory_snapshots')) {
            Schema::connection('sws')->table('sws_inventory_snapshots', function (Blueprint $table) {
                 $table->string('snap_warehouse_id', 20)->nullable()->change();
            });
        }
    }

    protected function safeDropForeignKey($table, $key)
    {
        try {
            $dbName = config('database.connections.sws.database');
            $exists = DB::connection('sws')->select("
                SELECT CONSTRAINT_NAME 
                FROM information_schema.KEY_COLUMN_USAGE 
                WHERE TABLE_SCHEMA = ? AND TABLE_NAME = ? AND CONSTRAINT_NAME = ?
            ", [$dbName, $table, $key]);

            if (!empty($exists)) {
                Schema::connection('sws')->table($table, function (Blueprint $table) use ($key) {
                    $table->dropForeign($key);
                });
            }
        } catch (\Exception $e) {
            // Ignore
        }
    }

    public function down(): void
    {
    }
};
