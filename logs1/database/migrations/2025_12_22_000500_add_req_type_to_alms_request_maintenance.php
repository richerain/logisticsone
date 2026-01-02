<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('alms')->table('alms_request_maintenance', function (Blueprint $table) {
            $table->string('req_type', 100)->nullable();
        });
    }

    public function down(): void
    {
        Schema::connection('alms')->table('alms_request_maintenance', function (Blueprint $table) {
            if (Schema::connection('alms')->hasColumn('alms_request_maintenance', 'req_type')) {
                $table->dropColumn('req_type');
            }
        });
    }
};
