<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::connection('psm')->table('psm_purchase', function (Blueprint $table) {
            // Remove pur_status_timeline column if it exists
            if (Schema::connection('psm')->hasColumn('psm_purchase', 'pur_status_timeline')) {
                $table->dropColumn('pur_status_timeline');
            }

            // Add pur_department_from column
            $table->string('pur_department_from')->default('Logistics 1');

            // Update pur_module_from default value
            $table->string('pur_module_from')->default('Procurement & Sourcing Management')->change();

            // Update pur_submodule_from default value
            $table->string('pur_submodule_from')->default('Purchase Management')->change();

            // Update pur_status enum values
            $table->enum('pur_status', ['Pending', 'Approved', 'Rejected', 'Cancel', 'Vendor-Review', 'In-Progress', 'Completed'])->default('Pending')->change();
        });
    }

    public function down()
    {
        Schema::connection('psm')->table('psm_purchase', function (Blueprint $table) {
            $table->json('pur_status_timeline')->nullable();
            $table->dropColumn('pur_department_from');
            $table->string('pur_module_from')->default('psm')->change();
            $table->string('pur_submodule_from')->default('purchase-management')->change();
            $table->enum('pur_status', ['pending', 'approved', 'processing', 'received', 'cancel', 'rejected'])->default('pending')->change();
        });
    }
};
