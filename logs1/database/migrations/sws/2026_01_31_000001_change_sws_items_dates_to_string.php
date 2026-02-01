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
        Schema::connection('sws')->table('sws_items', function (Blueprint $table) {
            $table->string('item_expiration_date', 100)->nullable()->change();
            $table->string('item_warranty_end', 100)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('sws')->table('sws_items', function (Blueprint $table) {
            $table->date('item_expiration_date')->nullable()->change();
            $table->date('item_warranty_end')->nullable()->change();
        });
    }
};
