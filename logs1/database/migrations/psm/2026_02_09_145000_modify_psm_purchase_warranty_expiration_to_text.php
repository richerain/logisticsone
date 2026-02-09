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
            $table->text('pur_warranty')->nullable()->change();
            $table->text('pur_expiration')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('psm')->table('psm_purchase', function (Blueprint $table) {
            $table->string('pur_warranty', 255)->nullable()->change();
            $table->string('pur_expiration', 255)->nullable()->change();
        });
    }
};
