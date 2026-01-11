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
        Schema::connection('sws')->create('sws_inventory_snapshots', function (Blueprint $table) {
            $table->id('snap_id');
            $table->unsignedBigInteger('snap_item_id'); // Changed to BigInteger
            $table->unsignedBigInteger('snap_location_id'); // Changed to BigInteger
            $table->unsignedBigInteger('snap_warehouse_id')->nullable(); // Changed to BigInteger
            $table->integer('snap_current_quantity');
            $table->integer('snap_min_threshold')->default(0);
            $table->enum('snap_alert_level', ['normal', 'low', 'critical'])->default('normal');
            $table->date('snap_snapshot_date');
            $table->string('snap_recorded_by', 100)->nullable();
            $table->text('snap_notes')->nullable();

            // Foreign keys removed to avoid circular dependency/creation order issues
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('sws')->dropIfExists('sws_inventory_snapshots');
    }
};
