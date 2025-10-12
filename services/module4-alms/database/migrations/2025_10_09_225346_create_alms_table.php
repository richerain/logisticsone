<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Branches table
        Schema::create('alms_branches', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->unique();
            $table->text('address')->nullable();
            $table->string('code', 50)->unique();
            $table->timestamps();
            
            $table->index(['code']);
        });

        // Employees table
        Schema::create('alms_employees', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('email', 255)->unique();
            $table->string('position', 100)->nullable();
            $table->foreignId('branch_id')->nullable()->constrained('alms_branches')->onDelete('set null');
            $table->timestamps();
        });

        // Asset Categories table
        Schema::create('alms_asset_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->text('description')->nullable();
            $table->enum('maintenance_frequency', ['monthly', 'yearly', 'km_based'])->nullable();
            $table->timestamps();
        });

        // Assets table
        Schema::create('alms_assets', function (Blueprint $table) {
            $table->id();
            $table->string('alms_id')->unique()->comment('Format: ALMS00001');
            $table->string('serial_number', 100)->unique();
            $table->string('name', 255);
            $table->foreignId('category_id')->constrained('alms_asset_categories');
            $table->date('acquisition_date');
            $table->decimal('acquisition_cost', 10, 2);
            $table->foreignId('current_branch_id')->constrained('alms_branches');
            $table->foreignId('assigned_employee_id')->nullable()->constrained('alms_employees')->onDelete('set null');
            $table->enum('status', ['active', 'in_maintenance', 'disposed'])->default('active');
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index(['serial_number']);
            $table->index(['status']);
        });

        // Maintenance Types table
        Schema::create('alms_maintenance_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('frequency_unit', 20);
            $table->decimal('estimated_cost', 10, 2)->nullable();
            $table->timestamps();
        });

        // Maintenance Schedules table
        Schema::create('alms_maintenance_schedules', function (Blueprint $table) {
            $table->id();
            $table->string('schedule_id')->unique()->comment('Format: MS00001');
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

        // Maintenance Records table
        Schema::create('alms_maintenance_records', function (Blueprint $table) {
            $table->id();
            $table->string('record_id')->unique()->comment('Format: MR00001');
            $table->foreignId('asset_id')->constrained('alms_assets');
            $table->foreignId('schedule_id')->nullable()->constrained('alms_maintenance_schedules')->onDelete('set null');
            $table->date('performed_date');
            $table->decimal('cost', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->string('performed_by', 255)->nullable();
            $table->timestamps();
        });

        // Asset Transfers table
        Schema::create('alms_asset_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_id')->unique()->comment('Format: AT00001');
            $table->foreignId('asset_id')->constrained('alms_assets');
            $table->foreignId('from_branch_id')->constrained('alms_branches');
            $table->foreignId('to_branch_id')->constrained('alms_branches');
            $table->date('transfer_date');
            $table->text('reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['transfer_date']);
        });

        // Disposals table
        Schema::create('alms_disposals', function (Blueprint $table) {
            $table->id();
            $table->string('disposal_id')->unique()->comment('Format: DS00001');
            $table->foreignId('asset_id')->constrained('alms_assets')->unique();
            $table->date('disposal_date');
            $table->enum('method', ['decommission', 'disposal', 'resale']);
            $table->decimal('disposal_value', 10, 2)->nullable();
            $table->text('reason')->nullable();
            $table->text('compliance_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('alms_disposals');
        Schema::dropIfExists('alms_asset_transfers');
        Schema::dropIfExists('alms_maintenance_records');
        Schema::dropIfExists('alms_maintenance_schedules');
        Schema::dropIfExists('alms_maintenance_types');
        Schema::dropIfExists('alms_assets');
        Schema::dropIfExists('alms_asset_categories');
        Schema::dropIfExists('alms_employees');
        Schema::dropIfExists('alms_branches');
    }
};