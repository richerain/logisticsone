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
        Schema::connection('psm')->table('psm_product', function (Blueprint $table) {
            if (!Schema::connection('psm')->hasColumn('psm_product', 'prod_picture')) {
                $table->string('prod_picture')->nullable()->after('prod_desc');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('psm')->table('psm_product', function (Blueprint $table) {
            if (Schema::connection('psm')->hasColumn('psm_product', 'prod_picture')) {
                $table->dropColumn('prod_picture');
            }
        });
    }
};
