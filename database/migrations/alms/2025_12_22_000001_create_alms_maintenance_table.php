<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('alms')->create('alms_maintenance', function (Blueprint $table) {
            $table->increments('mnt_id');
            $table->string('mnt_code', 50)->unique()->nullable();
            $table->string('mnt_asset_name', 255);
            $table->string('mnt_type', 50);
            $table->date('mnt_scheduled_date');
            $table->foreignId('mnt_repair_personnel_id')->nullable()->constrained('almns_repair_personnel', 'id');
            $table->enum('mnt_status', ['scheduled', 'in_progress', 'completed', 'cancelled', 'on_hold'])->default('scheduled');
            $table->enum('mnt_priority', ['low', 'medium', 'high', 'critical'])->default('medium');
            $table->timestamp('mnt_created_at')->useCurrent();
            $table->timestamp('mnt_updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->index('mnt_scheduled_date');
            $table->index('mnt_status');
            $table->index('mnt_priority');
        });
    }

    public function down(): void
    {
        Schema::connection('alms')->dropIfExists('alms_maintenance');
    }
};