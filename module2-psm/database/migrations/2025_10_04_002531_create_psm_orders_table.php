<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('psm_orders')) {
            Schema::create('psm_orders', function (Blueprint $table) {
                $table->id('order_id');
                $table->unsignedBigInteger('req_id')->nullable();
                $table->unsignedBigInteger('shop_id');
                $table->unsignedBigInteger('prod_id');
                $table->integer('quantity');
                $table->decimal('order_price', 10, 2);
                $table->decimal('total_amount', 12, 2)->storedAs('quantity * order_price');
                $table->enum('budget_approval_status', ['pending', 'approved', 'rejected'])->default('pending');
                $table->text('order_desc')->nullable();
                $table->enum('order_status', ['pending', 'issued', 'received', 'settled', 'cancelled'])->default('pending');
                $table->integer('sws_stock_id')->nullable();
                $table->timestamp('settled_at')->nullable();
                $table->timestamps();
                
                $table->foreign('shop_id')
                      ->references('shop_id')
                      ->on('psm_shops');
                      
                $table->foreign('prod_id')
                      ->references('prod_id')
                      ->on('psm_products');
                      
                $table->index('order_status');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('psm_orders');
    }
};