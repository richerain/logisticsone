<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('psm_reorders')) {
            Schema::create('psm_reorders', function (Blueprint $table) {
                $table->id('restock_id');
                $table->unsignedBigInteger('order_id')->nullable();
                $table->unsignedBigInteger('shop_id');
                $table->unsignedBigInteger('prod_id');
                $table->integer('quantity');
                $table->decimal('restock_price', 10, 2);
                $table->decimal('total_amount', 12, 2)->storedAs('quantity * restock_price');
                $table->enum('budget_approval_status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->text('restock_desc')->nullable();
                $table->enum('restock_status', ['pending', 'auto_triggered', 'settled'])->default('pending');
                $table->integer('sws_stock_id');
                $table->integer('trigger_threshold')->default(10);
                $table->timestamps();
                
                $table->foreign('order_id')
                      ->references('order_id')
                      ->on('psm_orders');
                      
                $table->foreign('shop_id')
                      ->references('shop_id')
                      ->on('psm_shops');
                      
                $table->foreign('prod_id')
                      ->references('prod_id')
                      ->on('psm_products');
                      
                $table->index('restock_status');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('psm_reorders');
    }
};