<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('sws_storage', function (Blueprint $table) {
            $table->id();
            $table->string('storage_id')->unique();
            $table->string('storage_name');
            $table->string('storage_location');
            $table->enum('storage_type', ['Document', 'Supplies', 'Equipment', 'Furniture']);
            $table->integer('storage_max_unit');
            $table->integer('storage_used_unit')->default(0);
            $table->integer('storage_free_unit');
            $table->decimal('storage_utilization_rate', 5, 2)->default(0);
            $table->enum('storage_status', ['active', 'inactive', 'maintenance']);
            $table->timestamps();
            
            $table->index(['storage_type', 'storage_status']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('sws_storage');
    }
};