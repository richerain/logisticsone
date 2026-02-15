<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::connection('plt')->create('plt_movement_project', function (Blueprint $table) {
            $table->id('mp_id');
            $table->string('mp_item_name', 255);
            $table->integer('mp_unit_transfer')->default(0);
            $table->string('mp_stored_from', 255)->nullable();
            $table->string('mp_stored_to', 255)->nullable();
            $table->string('mp_item_type', 100)->nullable();
            $table->enum('mp_movement_type', ['Stock Transfer', 'Asset Transfer'])->default('Stock Transfer');
            $table->enum('mp_status', ['pending', 'in-progress', 'delayed', 'completed'])->default('pending');
            $table->timestamps();
            
            $table->index('mp_status');
            $table->index('mp_movement_type');
        });
    }

    public function down()
    {
        Schema::connection('plt')->dropIfExists('plt_movement_project');
    }
};

