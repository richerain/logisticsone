<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::connection('plt')->create('plt_milestones', function (Blueprint $table) {
            $table->id('mile_id');
            $table->foreignId('mile_project_id')->constrained('plt_projects', 'pro_id');
            $table->string('mile_milestone_name', 100);
            $table->text('mile_description')->nullable();
            $table->date('mile_target_date');
            $table->date('mile_actual_date')->nullable();
            $table->enum('mile_status', ['pending', 'in_progress', 'completed', 'overdue'])->default('pending');
            $table->enum('mile_priority', ['low', 'medium', 'high'])->default('medium');
            $table->timestamps();
            
            $table->index('mile_project_id');
            $table->index('mile_status');
            $table->index('mile_target_date');
        });
    }

    public function down()
    {
        Schema::connection('plt')->dropIfExists('plt_milestones');
    }
};