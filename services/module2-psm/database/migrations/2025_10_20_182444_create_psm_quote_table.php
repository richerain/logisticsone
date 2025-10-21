<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('psm_quotes')) {
            Schema::create('psm_quotes', function (Blueprint $table) {
                $table->id('quote_id');
                $table->string('quote_code', 20)->unique(); // Format: QT00001
                $table->string('request_id', 20); // Foreign key to psm_purchase_orders (Request ID)
                $table->unsignedBigInteger('ven_id'); // Foreign key to psm_vendors
                $table->string('item_name', 255);
                $table->integer('quantity');
                $table->integer('units')->default(0); // Automated: quantity (same as purchase)
                $table->decimal('unit_price', 12, 2);
                $table->decimal('total_quote', 12, 2);
                $table->integer('delivery_lead_time'); // Number of days
                $table->date('quote_date');
                $table->enum('status', ['pending', 'received', 'approved', 'rejected'])->default('pending');
                $table->text('notes')->nullable();
                $table->timestamps();
                
                // Foreign key constraints
                $table->foreign('ven_id')
                      ->references('ven_id')
                      ->on('psm_vendors')
                      ->onDelete('cascade');
                
                // Reference to purchase orders table using request_id
                $table->foreign('request_id')
                      ->references('request_id')
                      ->on('psm_purchase_orders')
                      ->onDelete('cascade');
                
                // Indexes
                $table->index('quote_code');
                $table->index('request_id');
                $table->index('ven_id');
                $table->index('status');
                $table->index('quote_date');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('psm_quotes');
    }
};