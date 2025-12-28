<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'alms';

    public function up(): void
    {
        Schema::connection('alms')->table('almns_repair_personnel', function (Blueprint $table) {
            $table->string('rep_id', 20)->unique()->nullable()->after('id');
        });
    }

    public function down(): void
    {
        Schema::connection('alms')->table('almns_repair_personnel', function (Blueprint $table) {
            $table->dropColumn('rep_id');
        });
    }
};