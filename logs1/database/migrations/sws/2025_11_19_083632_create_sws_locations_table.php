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
        Schema::create('sws_locations', function (Blueprint $table) {
            $table->id('loc_id');
            $table->string('loc_name', 100);
            $table->enum('loc_type', ['warehouse', 'storage_room', 'office', 'facility', 'drop_point', 'bin']);
            $table->enum('loc_zone_type', ['liquid', 'illiquid', 'climate_controlled', 'general']);
            $table->boolean('loc_supports_fixed_items')->default(true);
            $table->integer('loc_capacity')->nullable();
            $table->unsignedInteger('loc_parent_id')->nullable();
            $table->boolean('loc_is_active')->default(true);
            $table->timestamp('loc_created_at')->useCurrent();

            $table->foreign('loc_parent_id')->references('loc_id')->on('sws_locations');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sws_locations');
    }
};