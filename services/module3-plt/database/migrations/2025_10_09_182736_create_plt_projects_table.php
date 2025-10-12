<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('plt_projects', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('branch_from');
            $table->string('branch_to');
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['planned', 'in_progress', 'delayed', 'completed', 'cancelled'])->default('planned');
            $table->tinyInteger('progress_percent')->default(0);
            $table->timestamps();
            
            $table->index(['name']);
            $table->index(['status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('plt_projects');
    }
};