<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop foreign key on sws_items if it exists
        Schema::connection('sws')->table('sws_items', function (Blueprint $table) {
            // We need to check if the foreign key exists before dropping
            // Laravel doesn't have a clean 'hasForeignKey' method in Blueprint, 
            // but we can try to drop it inside a try-catch or just attempt it.
            // A safer way is to check information_schema or just use dropForeign with array syntax
            // which Laravel handles by guessing the name.
            
            // However, since we don't know if it exists for sure, let's try to drop it.
            try {
                $table->dropForeign(['item_category_id']);
            } catch (\Exception $e) {
                // Ignore if FK doesn't exist
            }
        });

        // Modify sws_categories cat_id to string
        Schema::connection('sws')->table('sws_categories', function (Blueprint $table) {
            $table->string('cat_id', 100)->change();
        });

        // Modify sws_items item_category_id to string
        Schema::connection('sws')->table('sws_items', function (Blueprint $table) {
            $table->string('item_category_id', 100)->nullable()->change();
        });

        // Re-add foreign key
        Schema::connection('sws')->table('sws_items', function (Blueprint $table) {
            $table->foreign('item_category_id')->references('cat_id')->on('sws_categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop FK
        Schema::connection('sws')->table('sws_items', function (Blueprint $table) {
            try {
                $table->dropForeign(['item_category_id']);
            } catch (\Exception $e) {}
        });

        // Revert columns (this is risky as string data might be lost)
        // We generally don't revert string -> int if data is string.
        // But for completeness:
        /*
        Schema::connection('sws')->table('sws_categories', function (Blueprint $table) {
            $table->integer('cat_id')->change(); // This requires AUTO_INCREMENT usually
        });
        
        Schema::connection('sws')->table('sws_items', function (Blueprint $table) {
            $table->integer('item_category_id')->nullable()->change();
        });

        Schema::connection('sws')->table('sws_items', function (Blueprint $table) {
            $table->foreign('item_category_id')->references('cat_id')->on('sws_categories')->onDelete('set null');
        });
        */
    }
};
