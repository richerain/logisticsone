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
        Schema::connection('psm')->table('psm_consolidated', function (Blueprint $table) {
            // Rename con_req_id to con_id (This will hold the CONS... ID)
            // and add req_id (This will hold the psm_requisition req_id)
            $table->string('req_id')->after('id')->nullable();
            $table->string('parent_budget_req_id')->after('con_budget_approval')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('psm')->table('psm_consolidated', function (Blueprint $table) {
            $table->dropColumn(['req_id', 'parent_budget_req_id']);
        });
    }
};
