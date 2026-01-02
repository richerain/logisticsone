<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::connection('psm')->create('psm_purchase', function (Blueprint $table) {
            $table->id();
            $table->string('pur_id')->unique();
            $table->json('pur_name_items'); // Store items as JSON with name and price
            $table->integer('pur_unit');
            $table->decimal('pur_total_amount', 12, 2);
            $table->string('pur_company_name');
            $table->enum('pur_ven_type', ['equipment', 'supplies', 'furniture', 'automotive']);
            $table->enum('pur_status', ['pending', 'approved', 'processing', 'received', 'cancel', 'rejected'])->default('pending');
            $table->text('pur_desc')->nullable();
            $table->string('pur_module_from')->default('psm');
            $table->string('pur_submodule_from')->default('purchase-management');
            $table->timestamps();
        });

        // Add index for better performance
        Schema::connection('psm')->table('psm_purchase', function (Blueprint $table) {
            $table->index('pur_status');
            $table->index('pur_company_name');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::connection('psm')->dropIfExists('psm_purchase');
    }
};
