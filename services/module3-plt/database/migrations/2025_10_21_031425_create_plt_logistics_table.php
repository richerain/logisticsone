<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('plt_logistics', function (Blueprint $table) {
            $table->id('logistics_id');
            $table->string('delivery_id', 20)->unique();
            $table->string('vehicle_id', 20);
            $table->string('driver_name', 100);
            $table->string('route', 200);
            $table->string('destination', 100);
            $table->text('items');
            $table->enum('status', ['Scheduled', 'In Transit', 'Delivered'])->default('Scheduled');
            $table->string('receiver_name', 100);
            $table->date('delivery_date')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index('delivery_id');
            $table->index('vehicle_id');
            $table->index('status');
            $table->index('delivery_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plt_logistics');
    }
};