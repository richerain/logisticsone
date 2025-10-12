<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('plt_dispatches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('plt_projects')->onDelete('cascade');
            $table->string('material_type');
            $table->integer('quantity');
            $table->string('from_location');
            $table->string('to_location');
            $table->date('dispatch_date');
            $table->date('expected_delivery_date');
            $table->date('actual_delivery_date')->nullable();
            $table->enum('status', ['dispatched', 'in_transit', 'delayed', 'delivered', 'failed'])->default('dispatched');
            $table->json('courier_info')->nullable();
            $table->string('receipt_reference')->nullable();
            $table->timestamps();
            
            $table->index(['status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('plt_dispatches');
    }
};