<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::connection('plt')->create('plt_allocations', function (Blueprint $table) {
            $table->id('allo_id');
            $table->foreignId('allo_project_id')->constrained('plt_projects', 'pro_id');
            $table->foreignId('allo_milestone_id')->constrained('plt_milestones', 'mile_id');
            $table->foreignId('allo_resource_id')->constrained('plt_resources', 'res_id');
            $table->integer('allo_location_id')->comment('Reference to sws_locations.id');
            $table->integer('allo_quantity');
            $table->date('allo_allocated_date');
            $table->enum('allo_status', ['allocated', 'dispatched', 'delivered'])->default('allocated');
            $table->timestamps();

            $table->index(['allo_project_id', 'allo_milestone_id']);
            $table->index('allo_status');
        });
    }

    public function down()
    {
        Schema::connection('plt')->dropIfExists('plt_allocations');
    }
};
