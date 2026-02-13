<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('psm')->table('psm_purchase', function (Blueprint $table) {
            if (Schema::connection('psm')->hasColumn('psm_purchase', 'pur_approved_by')) {
                $table->dropColumn('pur_approved_by');
            }
            if (Schema::connection('psm')->hasColumn('psm_purchase', 'pur_cancel_by')) {
                $table->dropColumn('pur_cancel_by');
            }
            if (Schema::connection('psm')->hasColumn('psm_purchase', 'pur_module_from')) {
                $table->dropColumn('pur_module_from');
            }
            if (Schema::connection('psm')->hasColumn('psm_purchase', 'pur_submodule_from')) {
                $table->dropColumn('pur_submodule_from');
            }
        });
    }

    public function down(): void
    {
        Schema::connection('psm')->table('psm_purchase', function (Blueprint $table) {
            if (! Schema::connection('psm')->hasColumn('psm_purchase', 'pur_approved_by')) {
                $table->string('pur_approved_by')->nullable()->after('pur_status');
            }
            if (! Schema::connection('psm')->hasColumn('psm_purchase', 'pur_cancel_by')) {
                $table->string('pur_cancel_by')->nullable()->after('pur_approved_by');
            }
            if (! Schema::connection('psm')->hasColumn('psm_purchase', 'pur_module_from')) {
                $table->string('pur_module_from')->default('Procurement & Sourcing Management');
            }
            if (! Schema::connection('psm')->hasColumn('psm_purchase', 'pur_submodule_from')) {
                $table->string('pur_submodule_from')->default('Purchase Management');
            }
        });
    }
};

