<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::connection('psm')->table('psm_purchase', function (Blueprint $table) {
            if (Schema::connection('psm')->hasColumn('psm_purchase', 'pur_delivery')) {
                $table->renameColumn('pur_delivery', 'pur_delivery_date_from');
            }
            if (!Schema::connection('psm')->hasColumn('psm_purchase', 'pur_delivery_date_to')) {
                $table->date('pur_delivery_date_to')->nullable()->after('pur_delivery_date_from');
            }
        });
    }

    public function down()
    {
        Schema::connection('psm')->table('psm_purchase', function (Blueprint $table) {
            if (Schema::connection('psm')->hasColumn('psm_purchase', 'pur_delivery_date_from')) {
                $table->renameColumn('pur_delivery_date_from', 'pur_delivery');
            }
            if (Schema::connection('psm')->hasColumn('psm_purchase', 'pur_delivery_date_to')) {
                $table->dropColumn('pur_delivery_date_to');
            }
        });
    }
};