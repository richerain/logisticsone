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
        Schema::connection('psm')->table('psm_request_budget', function (Blueprint $table) {
            $table->string('req_status')->default('Pending')->after('req_contact');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('psm')->table('psm_request_budget', function (Blueprint $table) {
            $table->dropColumn('req_status');
        });
    }
};
