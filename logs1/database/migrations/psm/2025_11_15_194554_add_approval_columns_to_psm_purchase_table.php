<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::connection('psm')->table('psm_purchase', function (Blueprint $table) {
            $table->string('pur_approved_by')->nullable()->after('pur_status');
            $table->string('pur_order_by')->nullable()->after('pur_approved_by');
        });
    }

    public function down()
    {
        Schema::connection('psm')->table('psm_purchase', function (Blueprint $table) {
            $table->dropColumn(['pur_approved_by', 'pur_order_by']);
        });
    }
};