<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('psm_shops')) {
            Schema::create('psm_shops', function (Blueprint $table) {
                $table->id('shop_id');
                $table->string('shop_name', 255);
                $table->unsignedBigInteger('ven_id')->nullable();
                $table->integer('shop_prods')->default(0);
                $table->enum('shop_status', ['active', 'inactive', 'maintenance'])->default('active');
                $table->timestamps();
                
                $table->foreign('ven_id')
                      ->references('ven_id')
                      ->on('psm_vendors')
                      ->onDelete('set null');
                      
                $table->index('shop_status');
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('psm_shops');
    }
};