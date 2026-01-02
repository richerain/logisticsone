<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'sws';

    public function up(): void
    {
        Schema::connection('sws')->create('sws_warehouse', function (Blueprint $table) {
            $table->integer('ware_id', true);
            $table->string('ware_name', 100);
            $table->string('ware_location', 255)->nullable();
            $table->integer('ware_capacity')->nullable();
            $table->integer('ware_capacity_used')->default(0);
            $table->integer('ware_capacity_free')->default(0);
            $table->decimal('ware_utilization', 5, 2)->default(0.00);
            $table->enum('ware_status', ['active', 'inactive', 'maintenance'])->default('active');
            $table->enum('ware_zone_type', ['liquid', 'illiquid', 'climate_controlled', 'general'])->default('general');
            $table->boolean('ware_supports_fixed_items')->default(true);
            $table->timestamp('ware_created_at')->useCurrent();
            $table->timestamp('ware_updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::connection('sws')->dropIfExists('sws_warehouse');
    }
};
