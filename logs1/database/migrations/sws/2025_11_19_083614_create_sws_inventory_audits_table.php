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
        Schema::connection('sws')->create('sws_inventory_audits', function (Blueprint $table) {
            $table->id('aud_id');
            $table->unsignedBigInteger('aud_item_id')->nullable(); // Changed to BigInteger
            $table->unsignedBigInteger('aud_location_id')->nullable(); // Changed to BigInteger
            $table->unsignedBigInteger('aud_warehouse_id')->nullable(); // Changed to BigInteger
            $table->enum('aud_adjustment_type', ['count', 'adjustment', 'expiration_writeoff', 'warranty_check'])->nullable();
            $table->integer('aud_quantity_change')->nullable();
            $table->text('aud_reason')->nullable();
            $table->timestamp('aud_audit_date')->useCurrent();
            $table->string('aud_audited_by', 100)->nullable();

            // Foreign keys removed to avoid circular dependency/creation order issues
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('sws')->dropIfExists('sws_inventory_audits');
    }
};
