<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Specify the PSM database connection
        Schema::connection('psm')->create('psm_product', function (Blueprint $table) {
            $table->id();
            $table->string('prod_id')->unique();
            $table->string('prod_vendor');
            $table->string('prod_name');
            $table->decimal('prod_price', 10, 2);
            $table->integer('prod_stock');
            $table->enum('prod_type', ['equipment', 'supplies', 'furniture', 'automotive']);
            $table->string('prod_warranty')->default('no warranty');
            $table->date('prod_expiration')->nullable();
            $table->text('prod_desc')->nullable();
            $table->string('prod_module_from')->default('psm');
            $table->string('prod_submodule_from')->default('vendor-management');
            $table->timestamps();

            $table->foreign('prod_vendor')->references('ven_id')->on('psm_vendor')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::connection('psm')->dropIfExists('psm_product');
    }
};
