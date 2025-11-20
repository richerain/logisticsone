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
            // Add new stock columns
            $table->integer('item_current_stock')->default(0)->after('item_total_quantity');
            $table->integer('item_max_stock')->default(100)->after('item_current_stock');
            
            // Add item_code if not exists
            if (!Schema::connection('sws')->hasColumn('sws_items', 'item_code')) {
                $table->string('item_code', 20)->unique()->nullable()->after('item_id');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('sws')->table('sws_items', function (Blueprint $table) {
            $table->dropColumn(['item_current_stock', 'item_max_stock']);
        });
    }
};