<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Only add foreign keys if they don't exist
        Schema::table('sws_inventory', function (Blueprint $table) {
            if (!DB::select("SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS 
                           WHERE CONSTRAINT_SCHEMA = ? 
                           AND TABLE_NAME = 'sws_inventory' 
                           AND CONSTRAINT_NAME = 'sws_inventory_item_storage_from_foreign'", [DB::getDatabaseName()])) {
                $table->foreign('item_storage_from')
                      ->references('storage_id')
                      ->on('sws_storage')
                      ->onDelete('restrict')
                      ->onUpdate('cascade');
            }
        });

        Schema::table('sws_restock', function (Blueprint $table) {
            if (!DB::select("SELECT CONSTRAINT_NAME FROM information_schema.TABLE_CONSTRAINTS 
                           WHERE CONSTRAINT_SCHEMA = ? 
                           AND TABLE_NAME = 'sws_restock' 
                           AND CONSTRAINT_NAME = 'sws_restock_restock_item_id_foreign'", [DB::getDatabaseName()])) {
                $table->foreign('restock_item_id')
                      ->references('item_id')
                      ->on('sws_inventory')
                      ->onDelete('restrict')
                      ->onUpdate('cascade');
            }
        });
    }

    public function down()
    {
        // Safe drop foreign keys
        Schema::table('sws_inventory', function (Blueprint $table) {
            $table->dropForeignIfExists(['item_storage_from']);
        });

        Schema::table('sws_restock', function (Blueprint $table) {
            $table->dropForeignIfExists(['restock_item_id']);
        });
    }
};