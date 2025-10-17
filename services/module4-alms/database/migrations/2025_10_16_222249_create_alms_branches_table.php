<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('alms_branches', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->unique();
            $table->text('address')->nullable();
            $table->string('code', 50)->unique();
            $table->timestamps();
            
            $table->index(['code']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('alms_branches');
    }
};