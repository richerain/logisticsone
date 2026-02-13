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
            $table->string('con_chosen_vendor')->nullable()->after('con_items');
            $table->string('con_dept')->nullable()->after('con_requester');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('psm')->table('psm_consolidated', function (Blueprint $table) {
            $table->dropColumn(['con_chosen_vendor', 'con_dept']);
        });
    }
};
