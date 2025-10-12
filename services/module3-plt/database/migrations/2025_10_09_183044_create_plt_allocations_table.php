<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('plt_allocations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('plt_projects')->onDelete('cascade');
            $table->foreignId('resource_id')->constrained('plt_resources')->onDelete('restrict');
            $table->integer('quantity_assigned')->default(1);
            $table->date('assigned_date');
            $table->date('return_date')->nullable();
            $table->enum('status', ['assigned', 'in_use', 'returned', 'issue'])->default('assigned');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('plt_allocations');
    }
};