<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::connection('plt')->create('plt_projects', function (Blueprint $table) {
            $table->id('pro_id');
            $table->string('pro_project_name', 255);
            $table->text('pro_description')->nullable();
            $table->date('pro_start_date');
            $table->date('pro_end_date')->nullable();
            $table->enum('pro_status', ['planning', 'active', 'on_hold', 'completed', 'cancelled'])->default('planning');
            $table->decimal('pro_budget_allocated', 10, 2)->default(0.00);
            $table->integer('pro_assigned_manager_id');
            $table->timestamps();

            $table->index('pro_status');
            $table->index('pro_start_date');
            $table->index('pro_end_date');
        });
    }

    public function down()
    {
        Schema::connection('plt')->dropIfExists('plt_projects');
    }
};
