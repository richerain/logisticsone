<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'alms';

    public function up(): void
    {
        if (!Schema::connection('alms')->hasTable('alms_assets')) {
            Schema::connection('alms')->create('alms_assets', function (Blueprint $table) {
                $table->id();
                $table->string('asset_code', 20)->unique();
                $table->string('asset_name', 255);
                $table->string('asset_category', 100)->nullable();
                $table->string('asset_location', 100)->nullable();
                $table->enum('asset_status', ['operational', 'under_maintenance', 'out_of_service', 'in_storage'])->default('operational');
                $table->date('last_maintenance')->nullable();
                $table->date('next_maintenance')->nullable();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::connection('alms')->dropIfExists('alms_assets');
    }
};