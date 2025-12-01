<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::connection('plt')->create('plt_resources', function (Blueprint $table) {
            $table->id('res_id');
            $table->foreignId('res_project_id')->constrained('plt_projects', 'pro_id');
            $table->integer('res_item_id')->comment('Reference to sws_items.id');
            $table->integer('res_quantity_required');
            $table->integer('res_quantity_allocated')->default(0);
            $table->decimal('res_estimated_cost', 10, 2)->nullable();
            $table->text('res_notes')->nullable();
            $table->timestamps();
            
            $table->index('res_project_id');
        });
    }

    public function down()
    {
        Schema::connection('plt')->dropIfExists('plt_resources');
    }
};