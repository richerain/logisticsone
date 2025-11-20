<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sws_inventory_snapshots', function (Blueprint $table) {
            $table->id('snap_id');
            $table->unsignedInteger('snap_item_id');
            $table->unsignedInteger('snap_location_id');
            $table->unsignedInteger('snap_warehouse_id')->nullable();
            $table->integer('snap_current_quantity');
            $table->integer('snap_min_threshold')->default(0);
            $table->enum('snap_alert_level', ['normal', 'low', 'critical'])->default('normal');
            $table->date('snap_snapshot_date');
            $table->string('snap_recorded_by', 100)->nullable();
            $table->text('snap_notes')->nullable();

            $table->foreign('snap_item_id')->references('item_id')->on('sws_items');
            $table->foreign('snap_location_id')->references('loc_id')->on('sws_locations');
            $table->foreign('snap_warehouse_id')->references('ware_id')->on('sws_warehouses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sws_inventory_snapshots');
    }
};
