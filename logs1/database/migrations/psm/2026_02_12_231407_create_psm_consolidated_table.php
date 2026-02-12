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
        Schema::connection('psm')->create('psm_consolidated', function (Blueprint $table) {
            $table->id();
            $table->string('con_req_id')->unique();
            $table->text('con_items')->nullable();
            $table->decimal('con_total_price', 15, 2)->default(0.00);
            $table->string('con_requester')->nullable();
            $table->timestamp('con_date')->nullable();
            $table->text('con_note')->nullable();
            $table->string('con_status')->nullable();
            $table->string('con_budget_approval')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('psm')->dropIfExists('psm_consolidated');
    }
};
