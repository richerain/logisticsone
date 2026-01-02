<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'sws';

    public function up(): void
    {
        Schema::connection('sws')->table('sws_locations', function (Blueprint $table) {
            // Change loc_id from integer to string
            $table->string('loc_id', 10)->change();
        });
    }

    public function down(): void
    {
        Schema::connection('sws')->table('sws_locations', function (Blueprint $table) {
            // Revert back to integer (this might cause data loss if we have string IDs)
            $table->integer('loc_id')->change();
        });
    }
};
