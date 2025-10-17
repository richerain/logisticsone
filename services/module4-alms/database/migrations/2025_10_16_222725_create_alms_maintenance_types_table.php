<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('alms_maintenance_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->unique();
            $table->string('frequency_unit', 20);
            $table->decimal('estimated_cost', 10, 2)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('alms_maintenance_types');
    }
};