<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('plt_tracking_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('dispatch_id')->constrained('plt_dispatches')->onDelete('cascade');
            $table->timestamp('timestamp')->useCurrent();
            $table->string('location')->nullable();
            $table->string('status_update');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('plt_tracking_logs');
    }
};