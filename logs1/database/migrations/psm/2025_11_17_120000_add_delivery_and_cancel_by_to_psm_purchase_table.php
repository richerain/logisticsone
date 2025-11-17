<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::connection('psm')->table('psm_purchase', function (Blueprint $table) {
            $table->date('pur_delivery')->nullable()->after('pur_desc');
            $table->string('pur_cancel_by')->nullable()->after('pur_approved_by');
        });
    }

    public function down()
    {
        Schema::connection('psm')->table('psm_purchase', function (Blueprint $table) {
            $table->dropColumn(['pur_delivery', 'pur_cancel_by']);
        });
    }
};