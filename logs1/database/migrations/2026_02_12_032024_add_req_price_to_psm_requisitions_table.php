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
            $table->decimal('req_price', 15, 2)->after('req_items')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('psm')->table('psm_requisition', function (Blueprint $table) {
            $table->dropColumn('req_price');
        });
    }
};
