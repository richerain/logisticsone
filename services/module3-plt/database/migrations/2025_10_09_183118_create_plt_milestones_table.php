<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('plt_milestones', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('plt_projects')->onDelete('cascade');
            $table->foreignId('dispatch_id')->nullable()->constrained('plt_dispatches')->onDelete('set null');
            $table->string('name');
            $table->text('description')->nullable();
            $table->date('due_date');
            $table->date('actual_date')->nullable();
            $table->enum('status', ['pending', 'in_progress', 'completed', 'delayed'])->default('pending');
            $table->boolean('delay_alert')->default(false);
            $table->timestamps();
            
            $table->index(['status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('plt_milestones');
    }
};