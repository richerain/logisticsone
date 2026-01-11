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
        // Add foreign keys for sws_items
        Schema::connection('sws')->table('sws_items', function (Blueprint $table) {
            $table->foreign('item_category_id')->references('cat_id')->on('sws_categories')->onDelete('set null');
        });

        // Update location columns to match loc_id type (string)
        Schema::connection('sws')->table('sws_locations', function (Blueprint $table) {
            $table->string('loc_parent_id', 10)->nullable()->change();
        });

        // Add foreign keys for sws_locations
        Schema::connection('sws')->table('sws_locations', function (Blueprint $table) {
            $table->foreign('loc_parent_id')->references('loc_id')->on('sws_locations')->onDelete('set null');
        });

        // Add foreign keys for sws_transactions
        Schema::connection('sws')->table('sws_transactions', function (Blueprint $table) {
            $table->foreign('tra_item_id')->references('item_id')->on('sws_items')->onDelete('cascade');
        });

        // Update transaction location columns
        if (Schema::connection('sws')->hasTable('sws_locations')) {
            Schema::connection('sws')->table('sws_transactions', function (Blueprint $table) {
                $table->string('tra_from_location_id', 10)->nullable()->change();
                $table->string('tra_to_location_id', 10)->nullable()->change();
            });
            
            Schema::connection('sws')->table('sws_transactions', function (Blueprint $table) {
                $table->foreign('tra_from_location_id')->references('loc_id')->on('sws_locations')->onDelete('set null');
                $table->foreign('tra_to_location_id')->references('loc_id')->on('sws_locations')->onDelete('set null');
            });
        }

        // Add foreign keys for sws_transaction_logs
        Schema::connection('sws')->table('sws_transaction_logs', function (Blueprint $table) {
            $table->foreign('log_transaction_id')->references('tra_id')->on('sws_transactions')->onDelete('cascade');
        });

        // Update snapshot location column
        Schema::connection('sws')->table('sws_inventory_snapshots', function (Blueprint $table) {
            $table->string('snap_location_id', 10)->change();
        });

        // Add foreign keys for sws_inventory_snapshots
        Schema::connection('sws')->table('sws_inventory_snapshots', function (Blueprint $table) {
            $table->foreign('snap_item_id')->references('item_id')->on('sws_items')->onDelete('cascade');
            $table->foreign('snap_location_id')->references('loc_id')->on('sws_locations')->onDelete('cascade');
        });

        // Update audit location column
        Schema::connection('sws')->table('sws_inventory_audits', function (Blueprint $table) {
            $table->string('aud_location_id', 10)->nullable()->change();
        });

        // Add foreign keys for sws_inventory_audits
        Schema::connection('sws')->table('sws_inventory_audits', function (Blueprint $table) {
            $table->foreign('aud_item_id')->references('item_id')->on('sws_items')->onDelete('set null');
            $table->foreign('aud_location_id')->references('loc_id')->on('sws_locations')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign keys
        Schema::connection('sws')->table('sws_items', function (Blueprint $table) {
            $table->dropForeign(['item_category_id']);
        });

        Schema::connection('sws')->table('sws_locations', function (Blueprint $table) {
            $table->dropForeign(['loc_parent_id']);
            // Revert column type if needed, but risky
        });

        Schema::connection('sws')->table('sws_transactions', function (Blueprint $table) {
            $table->dropForeign(['tra_item_id']);
            $table->dropForeign(['tra_from_location_id']);
            $table->dropForeign(['tra_to_location_id']);
        });

        Schema::connection('sws')->table('sws_transaction_logs', function (Blueprint $table) {
            $table->dropForeign(['log_transaction_id']);
        });

        Schema::connection('sws')->table('sws_inventory_snapshots', function (Blueprint $table) {
            $table->dropForeign(['snap_item_id']);
            $table->dropForeign(['snap_location_id']);
        });

        Schema::connection('sws')->table('sws_inventory_audits', function (Blueprint $table) {
            $table->dropForeign(['aud_item_id']);
            $table->dropForeign(['aud_location_id']);
        });
    }
};
