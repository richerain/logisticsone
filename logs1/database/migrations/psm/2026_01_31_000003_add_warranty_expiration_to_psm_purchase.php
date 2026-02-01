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
        Schema::connection('psm')->table('psm_purchase', function (Blueprint $table) {
            $table->string('pur_warranty')->nullable()->after('pur_desc');
            $table->string('pur_expiration')->nullable()->after('pur_warranty');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('psm')->table('psm_purchase', function (Blueprint $table) {
            $table->dropColumn(['pur_warranty', 'pur_expiration']);
        });
    }
};
