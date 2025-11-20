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
        Schema::create('sws_inventory_audits', function (Blueprint $table) {
            $table->id('aud_id');
            $table->unsignedInteger('aud_item_id')->nullable();
            $table->unsignedInteger('aud_location_id')->nullable();
            $table->unsignedInteger('aud_warehouse_id')->nullable();
            $table->enum('aud_adjustment_type', ['count', 'adjustment', 'expiration_writeoff', 'warranty_check'])->nullable();
            $table->integer('aud_quantity_change')->nullable();
            $table->text('aud_reason')->nullable();
            $table->timestamp('aud_audit_date')->useCurrent();
            $table->string('aud_audited_by', 100)->nullable();

            $table->foreign('aud_item_id')->references('item_id')->on('sws_items');
            $table->foreign('aud_location_id')->references('loc_id')->on('sws_locations');
            $table->foreign('aud_warehouse_id')->references('ware_id')->on('sws_warehouses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sws_inventory_audits');
    }
};
