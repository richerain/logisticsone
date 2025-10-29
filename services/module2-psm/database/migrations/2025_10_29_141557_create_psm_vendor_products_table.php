<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('psm_vendor_products')) {
            Schema::create('psm_vendor_products', function (Blueprint $table) {
                $table->id('product_id');
                $table->string('product_code', 20)->unique()->comment('Auto-generated: PROD00001');
                $table->unsignedBigInteger('ven_id'); // Foreign key to psm_vendors
                $table->string('product_name', 255);
                $table->text('product_description')->nullable();
                $table->decimal('product_price', 12, 2);
                $table->integer('product_stock')->default(0);
                $table->enum('product_status', ['active', 'inactive'])->default('active');
                $table->date('warranty_from')->nullable(); // New column
                $table->date('warranty_to')->nullable(); // New column
                $table->date('expiration')->nullable(); // New column
                $table->timestamps();
                
                // Foreign key constraint
                $table->foreign('ven_id')
                      ->references('ven_id')
                      ->on('psm_vendors')
                      ->onDelete('cascade');
                
                // Indexes for better performance
                $table->index('product_code');
                $table->index('ven_id');
                $table->index('product_name');
                $table->index('product_status');
                $table->index('created_at');
                $table->index('warranty_from'); // New index
                $table->index('warranty_to'); // New index
                $table->index('expiration'); // New index
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('psm_vendor_products');
    }
};