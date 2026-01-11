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
        Schema::connection('sws')->create('sws_transactions', function (Blueprint $table) {
            $table->id('tra_id');
            $table->unsignedBigInteger('tra_item_id');
            $table->enum('tra_type', ['inbound', 'outbound', 'transfer', 'pick_up', 'drop_off', 'adjustment']);
            $table->integer('tra_quantity');
            $table->unsignedBigInteger('tra_from_location_id')->nullable();
            $table->unsignedBigInteger('tra_to_location_id')->nullable();
            $table->unsignedBigInteger('tra_warehouse_id')->nullable();
            $table->timestamp('tra_transaction_date')->useCurrent();
            $table->string('tra_reference_id', 100)->nullable();
            $table->enum('tra_status', ['pending', 'in_transit', 'completed', 'cancelled'])->default('pending');
            $table->text('tra_notes')->nullable();

            // Foreign keys removed to avoid circular dependency/creation order issues
            // They should be added in a separate migration after all tables exist
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('sws')->dropIfExists('sws_transactions');
    }
};
