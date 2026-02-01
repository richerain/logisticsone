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
        Schema::connection('psm')->table('psm_purchase_product', function (Blueprint $table) {
            $table->string('purcprod_id', 100)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Cannot easily revert to auto-increment ID if there are string values
        // But for structure:
        // $table->id('purcprod_id')->change(); 
    }
};
