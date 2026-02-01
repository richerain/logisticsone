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
            if (Schema::connection('psm')->hasColumn('psm_purchase', 'pur_delivery_date_from')) {
                $table->dropColumn('pur_delivery_date_from');
            }
            if (Schema::connection('psm')->hasColumn('psm_purchase', 'pur_delivery_date_to')) {
                $table->renameColumn('pur_delivery_date_to', 'pur_delivery_date');
            }
        });

        Schema::connection('psm')->table('psm_quote', function (Blueprint $table) {
            if (Schema::connection('psm')->hasColumn('psm_quote', 'quo_delivery_date_from')) {
                $table->dropColumn('quo_delivery_date_from');
            }
            if (Schema::connection('psm')->hasColumn('psm_quote', 'quo_delivery_date_to')) {
                $table->renameColumn('quo_delivery_date_to', 'quo_delivery_date');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('psm')->table('psm_purchase', function (Blueprint $table) {
            if (Schema::connection('psm')->hasColumn('psm_purchase', 'pur_delivery_date')) {
                $table->renameColumn('pur_delivery_date', 'pur_delivery_date_to');
            }
            if (!Schema::connection('psm')->hasColumn('psm_purchase', 'pur_delivery_date_from')) {
                $table->date('pur_delivery_date_from')->nullable()->after('pur_desc');
            }
        });

        Schema::connection('psm')->table('psm_quote', function (Blueprint $table) {
            if (Schema::connection('psm')->hasColumn('psm_quote', 'quo_delivery_date')) {
                $table->renameColumn('quo_delivery_date', 'quo_delivery_date_to');
            }
            if (!Schema::connection('psm')->hasColumn('psm_quote', 'quo_delivery_date_from')) {
                $table->date('quo_delivery_date_from')->nullable()->after('quo_total_amount');
            }
        });
    }
};
