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
        Schema::connection('psm')->table('psm_budget_logs', function (Blueprint $table) {
            $table->string('spent_to')->nullable()->after('bud_spent');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('psm')->table('psm_budget_logs', function (Blueprint $table) {
            $table->dropColumn('spent_to');
        });
    }
};
