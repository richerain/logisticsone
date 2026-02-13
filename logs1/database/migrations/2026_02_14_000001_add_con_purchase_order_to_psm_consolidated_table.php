<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('psm')->table('psm_consolidated', function (Blueprint $table) {
            if (!Schema::connection('psm')->hasColumn('psm_consolidated', 'con_purchase_order')) {
                $table->string('con_purchase_order')->nullable()->after('con_budget_approval');
            }
        });
    }

    public function down(): void
    {
        Schema::connection('psm')->table('psm_consolidated', function (Blueprint $table) {
            if (Schema::connection('psm')->hasColumn('psm_consolidated', 'con_purchase_order')) {
                $table->dropColumn('con_purchase_order');
            }
        });
    }
};

