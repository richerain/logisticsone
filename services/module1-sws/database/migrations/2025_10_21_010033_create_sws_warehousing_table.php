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
        Schema::create('sws_warehousing', function (Blueprint $table) {
            $table->id();
            $table->string('grn_id', 50)->unique()->comment('Goods Receipt Note ID - format: GRN00001');
            $table->string('po_number', 50)->unique()->comment('Purchase Order Number - format: PO00001');
            $table->string('item', 255)->comment('Name or type of item received');
            $table->integer('qty_ordered')->comment('Number of items expected');
            $table->integer('qty_received')->comment('Actual number received');
            $table->enum('condition', ['Good', 'Damaged', 'Missing'])->default('Good');
            $table->string('warehouse_location', 255)->comment('Where items are stored');
            $table->string('received_by', 255)->comment('Person or system receiving the items');
            $table->enum('status', ['Pending', 'Completed', 'Cancelled'])->default('Pending');
            $table->timestamps();

            // Indexes for better performance
            $table->index('grn_id');
            $table->index('po_number');
            $table->index('condition');
            $table->index('status');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sws_warehousing');
    }
};