<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::connection('main')->hasTable('vendor_account')) {
            Schema::connection('main')->table('vendor_account', function (Blueprint $table) {
                if (! Schema::connection('main')->hasColumn('vendor_account', 'company_name')) {
                    $table->string('company_name')->nullable()->after('company_type');
                }
            });
        }
    }

    public function down(): void
    {
        if (Schema::connection('main')->hasTable('vendor_account')) {
            Schema::connection('main')->table('vendor_account', function (Blueprint $table) {
                if (Schema::connection('main')->hasColumn('vendor_account', 'company_name')) {
                    $table->dropColumn('company_name');
                }
            });
        }
    }
};

