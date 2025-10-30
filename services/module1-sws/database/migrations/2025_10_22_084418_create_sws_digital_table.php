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
        Schema::create('sws_digital', function (Blueprint $table) {
            $table->id();
            $table->string('stock_id', 50)->unique()->comment('Stock ID - format: STK00001');
            $table->string('item_name', 255)->comment('Name of the item');
            $table->string('type', 100)->comment('Type/category of the item');
            $table->string('units', 50)->comment('Measurement units (pcs, kg, boxes, etc)');
            $table->integer('available_item')->default(0)->comment('Number of available items');
            $table->enum('status', ['lowstock', 'onstock', 'outofstock'])->default('onstock');
            $table->unsignedBigInteger('vendor_id')->nullable()->comment('Reference to Vendor');
            $table->string('vendor_name', 255)->nullable()->comment('Vendor name');
            $table->string('quote_id', 50)->nullable()->comment('Reference to Quote ID');
            $table->string('quote_code', 50)->nullable()->comment('Quote Code');
            $table->decimal('purchase_price', 12, 2)->nullable()->comment('Purchase price from quote');
            $table->text('warranty_info')->nullable()->comment('Warranty information');
            $table->timestamps();

            // Indexes for better performance
            $table->index('stock_id');
            $table->index('item_name');
            $table->index('type');
            $table->index('status');
            $table->index('vendor_id');
            $table->index('quote_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sws_digital');
    }
};