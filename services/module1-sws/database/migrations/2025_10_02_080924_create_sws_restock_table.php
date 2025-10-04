<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sws_restock', function (Blueprint $table) {
            $table->id();
            $table->string('restock_id')->unique();
            $table->string('restock_item_id');
            $table->string('restock_item_name');
            $table->enum('restock_item_type', ['Document', 'Supplies', 'Equipment', 'Furniture']);
            $table->integer('restock_item_unit');
            $table->integer('restock_item_capacity');
            $table->text('restock_desc')->nullable();
            $table->enum('restock_status', ['pending', 'approve', 'delivered']);
            $table->timestamps();
            
            $table->index(['restock_item_type', 'restock_status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('sws_restock');
    }
};