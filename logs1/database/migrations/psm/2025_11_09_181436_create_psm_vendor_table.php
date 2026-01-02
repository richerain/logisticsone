<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Specify the PSM database connection
        Schema::connection('psm')->create('psm_vendor', function (Blueprint $table) {
            $table->id();
            $table->string('ven_id')->unique();
            $table->string('ven_company_name');
            $table->string('ven_contact_person');
            $table->string('ven_email');
            $table->string('ven_phone');
            $table->text('ven_address');
            $table->integer('ven_rating')->default(1);
            $table->enum('ven_type', ['equipment', 'supplies', 'furniture', 'automotive']);
            $table->integer('ven_product')->default(0);
            $table->enum('ven_status', ['active', 'inactive'])->default('active');
            $table->text('ven_desc')->nullable();
            $table->string('ven_module_from')->default('psm');
            $table->string('ven_submodule_from')->default('vendor-management');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('psm')->dropIfExists('psm_vendor');
    }
};
