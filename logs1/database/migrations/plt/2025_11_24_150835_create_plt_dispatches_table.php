<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::connection('plt')->create('plt_dispatches', function (Blueprint $table) {
            $table->id('dis_id');
            $table->foreignId('dis_allocation_id')->constrained('plt_allocations', 'allo_id');
            $table->string('dis_dispatch_number', 50)->unique();
            $table->string('dis_carrier', 100)->nullable();
            $table->date('dis_expected_delivery_date');
            $table->date('dis_actual_delivery_date')->nullable();
            $table->string('dis_tracking_code', 100)->nullable();
            $table->enum('dis_status', ['prepared', 'in_transit', 'delivered', 'returned'])->default('prepared');
            $table->timestamps();
            
            $table->index('dis_allocation_id');
            $table->index('dis_status');
            $table->index('dis_dispatch_number');
        });
    }

    public function down()
    {
        Schema::connection('plt')->dropIfExists('plt_dispatches');
    }
};