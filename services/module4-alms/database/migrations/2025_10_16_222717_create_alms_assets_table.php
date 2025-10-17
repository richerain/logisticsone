<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('alms_assets', function (Blueprint $table) {
            $table->id();
            $table->string('alms_id', 20)->unique()->comment('Format: ALMS00001');
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
    }

    public function down()
    {
        Schema::dropIfExists('alms_assets');
    }
};