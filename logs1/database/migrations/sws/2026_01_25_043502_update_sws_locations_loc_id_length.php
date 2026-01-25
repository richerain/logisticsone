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
        // 1. Drop foreign keys referencing loc_id
        Schema::connection('sws')->table('sws_locations', function (Blueprint $table) {
            $table->dropForeign(['loc_parent_id']);
        });

        Schema::connection('sws')->table('sws_transactions', function (Blueprint $table) {
            $table->dropForeign(['tra_from_location_id']);
            $table->dropForeign(['tra_to_location_id']);
        });

        Schema::connection('sws')->table('sws_inventory_snapshots', function (Blueprint $table) {
            $table->dropForeign(['snap_location_id']);
        });

        Schema::connection('sws')->table('sws_inventory_audits', function (Blueprint $table) {
            $table->dropForeign(['aud_location_id']);
        });

        // 2. Update referencing column lengths
        Schema::connection('sws')->table('sws_locations', function (Blueprint $table) {
            $table->string('loc_parent_id', 25)->nullable()->change();
        });

        Schema::connection('sws')->table('sws_transactions', function (Blueprint $table) {
            $table->string('tra_from_location_id', 25)->nullable()->change();
            $table->string('tra_to_location_id', 25)->nullable()->change();
        });

        Schema::connection('sws')->table('sws_inventory_snapshots', function (Blueprint $table) {
            $table->string('snap_location_id', 25)->change();
        });

        Schema::connection('sws')->table('sws_inventory_audits', function (Blueprint $table) {
            $table->string('aud_location_id', 25)->nullable()->change();
        });

        // 3. Update the main column loc_id
        Schema::connection('sws')->table('sws_locations', function (Blueprint $table) {
            $table->string('loc_id', 25)->change();
        });

        // 4. Re-add foreign keys
        Schema::connection('sws')->table('sws_locations', function (Blueprint $table) {
            $table->foreign('loc_parent_id')->references('loc_id')->on('sws_locations')->onDelete('set null');
        });

        Schema::connection('sws')->table('sws_transactions', function (Blueprint $table) {
            $table->foreign('tra_from_location_id')->references('loc_id')->on('sws_locations')->onDelete('set null');
            $table->foreign('tra_to_location_id')->references('loc_id')->on('sws_locations')->onDelete('set null');
        });

        Schema::connection('sws')->table('sws_inventory_snapshots', function (Blueprint $table) {
            $table->foreign('snap_location_id')->references('loc_id')->on('sws_locations')->onDelete('cascade');
        });

        Schema::connection('sws')->table('sws_inventory_audits', function (Blueprint $table) {
            $table->foreign('aud_location_id')->references('loc_id')->on('sws_locations')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // 1. Drop foreign keys
        Schema::connection('sws')->table('sws_locations', function (Blueprint $table) {
            $table->dropForeign(['loc_parent_id']);
        });

        Schema::connection('sws')->table('sws_transactions', function (Blueprint $table) {
            $table->dropForeign(['tra_from_location_id']);
            $table->dropForeign(['tra_to_location_id']);
        });

        Schema::connection('sws')->table('sws_inventory_snapshots', function (Blueprint $table) {
            $table->dropForeign(['snap_location_id']);
        });

        Schema::connection('sws')->table('sws_inventory_audits', function (Blueprint $table) {
            $table->dropForeign(['aud_location_id']);
        });

        // 2. Revert referencing column lengths
        Schema::connection('sws')->table('sws_locations', function (Blueprint $table) {
            $table->string('loc_parent_id', 10)->nullable()->change();
        });

        Schema::connection('sws')->table('sws_transactions', function (Blueprint $table) {
            $table->string('tra_from_location_id', 10)->nullable()->change();
            $table->string('tra_to_location_id', 10)->nullable()->change();
        });

        Schema::connection('sws')->table('sws_inventory_snapshots', function (Blueprint $table) {
            $table->string('snap_location_id', 10)->change();
        });

        Schema::connection('sws')->table('sws_inventory_audits', function (Blueprint $table) {
            $table->string('aud_location_id', 10)->nullable()->change();
        });

        // 3. Revert main column loc_id
        Schema::connection('sws')->table('sws_locations', function (Blueprint $table) {
            $table->string('loc_id', 10)->change();
        });

        // 4. Re-add foreign keys
        Schema::connection('sws')->table('sws_locations', function (Blueprint $table) {
            $table->foreign('loc_parent_id')->references('loc_id')->on('sws_locations')->onDelete('set null');
        });

        Schema::connection('sws')->table('sws_transactions', function (Blueprint $table) {
            $table->foreign('tra_from_location_id')->references('loc_id')->on('sws_locations')->onDelete('set null');
            $table->foreign('tra_to_location_id')->references('loc_id')->on('sws_locations')->onDelete('set null');
        });

        Schema::connection('sws')->table('sws_inventory_snapshots', function (Blueprint $table) {
            $table->foreign('snap_location_id')->references('loc_id')->on('sws_locations')->onDelete('cascade');
        });

        Schema::connection('sws')->table('sws_inventory_audits', function (Blueprint $table) {
            $table->foreign('aud_location_id')->references('loc_id')->on('sws_locations')->onDelete('set null');
        });
    }
};
