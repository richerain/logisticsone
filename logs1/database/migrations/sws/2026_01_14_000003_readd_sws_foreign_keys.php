<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'sws';

    public function up(): void
    {
        // Re-add FK for sws_transactions
        if (Schema::connection('sws')->hasTable('sws_transactions')) {
            Schema::connection('sws')->table('sws_transactions', function (Blueprint $table) {
                // Ensure index exists (usually Laravel adds it automatically with foreign)
                $table->foreign('tra_warehouse_id')->references('ware_id')->on('sws_warehouse')->onDelete('cascade');
            });
        }

        // Re-add FK for sws_inventory_audits
        if (Schema::connection('sws')->hasTable('sws_inventory_audits')) {
            Schema::connection('sws')->table('sws_inventory_audits', function (Blueprint $table) {
                $table->foreign('aud_warehouse_id')->references('ware_id')->on('sws_warehouse')->onDelete('cascade');
            });
        }
        
        // Re-add FK for sws_inventory_snapshots
        if (Schema::connection('sws')->hasTable('sws_inventory_snapshots')) {
            Schema::connection('sws')->table('sws_inventory_snapshots', function (Blueprint $table) {
                $table->foreign('snap_warehouse_id')->references('ware_id')->on('sws_warehouse')->onDelete('cascade');
            });
        }
    }

    public function down(): void
    {
        // Drop FKs
        if (Schema::connection('sws')->hasTable('sws_transactions')) {
            Schema::connection('sws')->table('sws_transactions', function (Blueprint $table) {
                $table->dropForeign(['tra_warehouse_id']);
            });
        }
        // ... others
    }
};
