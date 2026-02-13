<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('psm')->create('psm_purcahse_request', function (Blueprint $table) {
            $table->id();
            $table->string('preq_id')->unique();
            $table->json('preq_name_items')->nullable();
            $table->integer('preq_unit')->default(0);
            $table->decimal('preq_total_amount', 12, 2)->default(0);
            $table->string('preq_ven_id')->nullable();
            $table->string('preq_ven_company_name')->nullable();
            $table->string('preq_ven_type')->nullable();
            $table->string('preq_status')->default('Pending');
            $table->string('preq_process')->nullable();
            $table->string('preq_order_by')->nullable();
            $table->text('preq_desc')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::connection('psm')->dropIfExists('psm_purcahse_request');
    }
};

