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
            $table->unsignedBigInteger('grn_id')->nullable()->comment('Reference to Goods Received Note');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('grn_id')
                  ->references('id')
                  ->on('sws_warehousing')
                  ->onDelete('set null');

            // Indexes for better performance
            $table->index('stock_id');
            $table->index('item_name');
            $table->index('type');
            $table->index('status');
            $table->index('grn_id');
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