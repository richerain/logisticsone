<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::connection('psm')->create('psm_budget', function (Blueprint $table) {
            $table->id();
            $table->string('bud_id')->unique();
            $table->decimal('bud_allocated_amount', 15, 2);
            $table->decimal('bud_spent_amount', 15, 2)->default(0);
            $table->decimal('bud_remaining_amount', 15, 2);
            $table->date('bud_assigned_date');
            $table->enum('bud_validity_type', ['Week', 'Month', 'Year']);
            $table->date('bud_valid_from');
            $table->date('bud_valid_to');
            $table->enum('bud_amount_status_health', ['Healthy', 'Stable', 'Alert', 'Exceeded'])->default('Healthy');
            $table->string('bud_for_department')->default('Logistics 1');
            $table->string('bud_for_module')->default('Procurement & Sourcing Management');
            $table->string('bud_for_submodule')->default('Purchase Management');
            $table->text('bud_desc')->nullable();
            $table->timestamps();
        });

        // Add index for better performance
        Schema::connection('psm')->table('psm_budget', function (Blueprint $table) {
            $table->index('bud_valid_to');
            $table->index('bud_amount_status_health');
            $table->index('bud_assigned_date');
        });

        // Insert initial budget data
        DB::connection('psm')->table('psm_budget')->insert([
            'bud_id' => 'BUDG00001',
            'bud_allocated_amount' => 100000.00,
            'bud_spent_amount' => 0.00,
            'bud_remaining_amount' => 100000.00,
            'bud_assigned_date' => now()->toDateString(),
            'bud_validity_type' => 'Month',
            'bud_valid_from' => now()->toDateString(),
            'bud_valid_to' => now()->addMonth()->toDateString(),
            'bud_amount_status_health' => 'Healthy',
            'bud_for_department' => 'Logistics 1',
            'bud_for_module' => 'Procurement & Sourcing Management',
            'bud_for_submodule' => 'Purchase Management',
            'bud_desc' => 'Initial budget for testing purposes',
            'created_at' => now(),
            'updated_at' => now()
        ]);
    }

    public function down()
    {
        Schema::connection('psm')->dropIfExists('psm_budget');
    }
};