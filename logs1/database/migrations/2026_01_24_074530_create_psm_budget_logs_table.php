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
        Schema::connection('psm')->create('psm_budget_logs', function (Blueprint $table) {
            $table->id();
            $table->string('bud_id')->nullable();
            $table->decimal('bud_spent', 15, 2)->default(0);
            $table->string('bud_type')->default('Purchase Payment'); // Purchase Payment, Project Payment
            $table->dateTime('bud_spent_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('psm')->dropIfExists('psm_budget_logs');
    }
};
