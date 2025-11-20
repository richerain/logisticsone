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
        Schema::create('sws_transactions', function (Blueprint $table) {
            $table->id('tra_id');
            $table->unsignedInteger('tra_item_id');
            $table->enum('tra_type', ['inbound', 'outbound', 'transfer', 'pick_up', 'drop_off', 'adjustment']);
            $table->integer('tra_quantity');
            $table->unsignedInteger('tra_from_location_id')->nullable();
            $table->unsignedInteger('tra_to_location_id')->nullable();
            $table->unsignedInteger('tra_warehouse_id')->nullable();
            $table->timestamp('tra_transaction_date')->useCurrent();
            $table->string('tra_reference_id', 100)->nullable();
            $table->enum('tra_status', ['pending', 'in_transit', 'completed', 'cancelled'])->default('pending');
            $table->text('tra_notes')->nullable();

            $table->foreign('tra_item_id')->references('item_id')->on('sws_items');
            $table->foreign('tra_from_location_id')->references('loc_id')->on('sws_locations');
            $table->foreign('tra_to_location_id')->references('loc_id')->on('sws_locations');
            $table->foreign('tra_warehouse_id')->references('ware_id')->on('sws_warehouses');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sws_transactions');
    }
};
