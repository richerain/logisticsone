<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::connection('psm')->table('psm_purchase', function (Blueprint $table) {
            $table->enum('pur_status', [
                'Pending',
                'Approved',
                'Rejected',
                'Cancel',
                'PO Received',
                'Processing Order',
                'Dispatched',
                'Delivered',
                // legacy labels kept for compatibility
                'Vendor-Review',
                'In-Progress',
                'Completed',
            ])->default('Pending')->change();
        });
    }

    public function down()
    {
        Schema::connection('psm')->table('psm_purchase', function (Blueprint $table) {
            $table->enum('pur_status', [
                'Pending',
                'Approved',
                'Rejected',
                'Cancel',
                'Vendor-Review',
                'In-Progress',
                'Completed',
            ])->default('Pending')->change();
        });
    }
};

