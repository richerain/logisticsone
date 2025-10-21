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
        Schema::create('alms_assets', function (Blueprint $table) {
            $table->id();
            $table->string('asset_id', 8)->unique(); // AST00001 format
            $table->string('asset_name');
            $table->enum('asset_type', ['Document', 'Supplies', 'Equipment', 'Furniture']);
            $table->string('assigned_location');
            $table->date('deployment_date');
            $table->date('next_service_date')->nullable();
            $table->date('warranty_start')->nullable();
            $table->date('warranty_end')->nullable();
            $table->enum('status', ['Active', 'Issued', 'Under Maintenance', 'Re-Schedule', 'Replacement', 'Rejected'])->default('Active');
            $table->text('description')->nullable();
            $table->timestamps();

            // Indexes for better performance
            $table->index('asset_id');
            $table->index('asset_type');
            $table->index('status');
            $table->index('deployment_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alms_assets');
    }
};