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
        Schema::create('psm_purchase_orders', function (Blueprint $table) {
            $table->id('purchase_id');
            $table->string('request_id', 20)->unique()->comment('Auto-generated: REQ00001');
            $table->string('po_number', 20)->unique()->comment('Auto-generated: PO00001');
            $table->string('branch', 100);
            $table->string('vendor', 255);
            $table->string('item', 255);
            $table->integer('quantity');
            $table->integer('units');
            $table->decimal('unit_price', 12, 2);
            $table->decimal('total_quote', 12, 2);
            $table->decimal('estimated_budget', 12, 2);
            $table->string('expected_delivery', 50);
            $table->date('quote_date');
            $table->enum('status', ['Pending', 'In Progress', 'Received', 'Approved', 'Rejected'])->default('Pending');
            $table->text('description')->nullable();
            $table->timestamps();
            
            // Indexes for better performance
            $table->index('request_id');
            $table->index('po_number');
            $table->index('branch');
            $table->index('vendor');
            $table->index('status');
            $table->index('quote_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('psm_purchase_orders');
    }
};