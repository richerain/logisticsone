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
        Schema::connection('psm')->table('psm_requisition', function (Blueprint $table) {
            $table->boolean('is_consolidated')->default(false)->after('req_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('psm')->table('psm_requisition', function (Blueprint $table) {
            $table->dropColumn('is_consolidated');
        });
    }
};
