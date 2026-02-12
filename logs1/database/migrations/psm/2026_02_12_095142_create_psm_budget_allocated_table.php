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
        Schema::connection('psm')->create('psm_budget_allocated', function (Blueprint $table) {
            $table->id();
            $table->string('all_id')->unique();
            $table->string('all_req_id');
            $table->string('all_req_by');
            $table->decimal('all_amount', 15, 2);
            $table->decimal('all_budget_allocated', 15, 2)->default(0.00);
            $table->string('all_department');
            $table->date('all_date');
            $table->text('all_purpose');
            $table->string('all_status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('psm_budget_allocated');
    }
};
