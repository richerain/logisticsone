<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('psm_products')) {
            Schema::create('psm_products', function (Blueprint $table) {
                $table->id('prod_id');
                $table->unsignedBigInteger('shop_id');
                $table->string('prod_name', 255);
                $table->string('prod_category', 100)->nullable();
                $table->integer('prod_stock')->default(0);
                $table->enum('prod_stock_status', ['lowstock', 'onstock', 'outofstock'])->default('onstock');
                $table->decimal('prod_price', 10, 2);
                $table->text('prod_desc')->nullable();
                $table->string('prod_img', 500)->nullable();
                $table->enum('prod_publish', ['posted', 'not posted'])->default('not posted');
                $table->timestamps();
                
                $table->foreign('shop_id')
                      ->references('shop_id')
                      ->on('psm_shops')
                      ->onDelete('cascade');
                      
                $table->index('prod_stock_status');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('psm_products');
    }
};