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
        Schema::create('alms_maintenance', function (Blueprint $table) {
            $table->id();
            $table->string('schedule_id', 8)->unique(); // SCD00001 format
            $table->string('asset_name');
            $table->enum('maintenance_type', ['Inspection', 'Cleaning/Sanitization', 'Repair', 'Calibration/Testing', 'Replacement']);
            $table->string('assigned_personnel');
            $table->date('schedule_date');
            $table->time('schedule_time')->nullable();
            $table->enum('status', ['Pending', 'Under Maintenance', 'Re-Schedule', 'Replacement', 'Reject', 'Done'])->default('Pending');
            $table->text('notes')->nullable();
            $table->text('reschedule_reason')->nullable();
            $table->timestamps();

            // Indexes for better performance
            $table->index('schedule_id');
            $table->index('asset_name');
            $table->index('maintenance_type');
            $table->index('status');
            $table->index('schedule_date');
            $table->index(['schedule_date', 'schedule_time']);

            // Foreign key constraint (optional - for data integrity)
            // $table->foreign('asset_name')->references('asset_name')->on('alms_assets')->onDelete('restrict');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alms_maintenance');
    }
};