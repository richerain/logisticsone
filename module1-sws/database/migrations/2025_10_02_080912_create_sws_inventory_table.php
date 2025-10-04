<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sws_inventory', function (Blueprint $table) {
            $table->id();
            $table->string('item_id')->unique();
            $table->string('item_name');
            $table->enum('item_type', ['Document', 'Supplies', 'Equipment', 'Furniture']);
            $table->integer('item_stock')->default(0);
            $table->integer('item_stock_capacity');
            $table->text('item_desc')->nullable();
            $table->string('item_storage_from');
            $table->enum('item_stock_level', ['instock', 'lowstock']);
            $table->enum('item_status', ['pending', 'restocking', 'reserved', 'distributed']);
            $table->timestamps();
            
            $table->index(['item_type', 'item_status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('sws_inventory');
    }
};