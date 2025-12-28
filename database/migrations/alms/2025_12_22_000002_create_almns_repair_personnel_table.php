<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('alms')->create('almns_repair_personnel', function (Blueprint $table) {
            $table->increments('id');
            $table->string('firstname', 100);
            $table->string('middlename', 100)->nullable();
            $table->string('lastname', 100);
            $table->string('position', 100)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
        });
    }

    public function down(): void
    {
        Schema::connection('alms')->dropIfExists('almns_repair_personnel');
    }
};