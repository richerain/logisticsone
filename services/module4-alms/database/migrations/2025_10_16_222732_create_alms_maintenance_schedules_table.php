<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('alms_maintenance_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('schedule_id', 20)->unique()->comment('Format: MS00001');
            $table->foreignId('asset_id')->constrained('alms_assets');
            $table->foreignId('maintenance_type_id')->constrained('alms_maintenance_types');
            $table->date('due_date');
            $table->integer('frequency_value');
            $table->date('last_maintained_date')->nullable();
            $table->boolean('is_overdue')->default(false);
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->timestamps();
            
            $table->index(['due_date']);
            $table->index(['is_overdue']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('alms_maintenance_schedules');
    }
};