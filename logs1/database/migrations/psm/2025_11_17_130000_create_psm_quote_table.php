<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::connection('psm')->create('psm_quote', function (Blueprint $table) {
            $table->id();
            $table->string('quo_id')->unique();
            $table->json('quo_items');
            $table->integer('quo_units');
            $table->decimal('quo_total_amount', 12, 2);
            $table->date('quo_delivery_date_from')->nullable();
            $table->date('quo_delivery_date_to')->nullable();
            $table->enum('quo_status', ['Reject', 'Cancel', 'Vendor-Review', 'In-Progress', 'Completed'])->default('Vendor-Review');
            $table->string('quo_item_drop_to')->nullable();
            $table->string('quo_payment')->nullable();
            $table->string('quo_stored_from')->default('Main Warehouse A');
            $table->string('quo_department_from')->default('Logistics 1');
            $table->string('quo_module_from')->default('Procurement & Sourcing Management');
            $table->string('quo_submodule_from')->default('Vendor Quote');
            $table->unsignedBigInteger('quo_purchase_id')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::connection('psm')->dropIfExists('psm_quote');
    }
};
