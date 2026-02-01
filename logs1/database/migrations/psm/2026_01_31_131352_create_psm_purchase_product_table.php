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
        Schema::connection('psm')->create('psm_purchase_product', function (Blueprint $table) {
            $table->id('purcprod_id');
            $table->string('purcprod_prod_id')->nullable();
            $table->string('purcprod_prod_name')->nullable();
            $table->decimal('purcprod_prod_price', 15, 2)->nullable();
            $table->string('purcprod_prod_unit')->nullable();
            $table->string('purcprod_prod_type')->nullable();
            $table->string('purcprod_status')->nullable();
            $table->date('purcprod_date')->nullable();
            $table->string('purcprod_warranty')->nullable();
            $table->string('purcprod_expiration')->nullable();
            $table->text('purcprod_desc')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('psm')->dropIfExists('psm_purchase_product');
    }
};
