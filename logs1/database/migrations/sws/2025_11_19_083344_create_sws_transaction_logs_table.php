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
        Schema::create('sws_transaction_logs', function (Blueprint $table) {
            $table->id('log_id');
            $table->unsignedInteger('log_transaction_id');
            $table->string('log_event', 100)->nullable();
            $table->text('log_details')->nullable();
            $table->timestamp('log_logged_at')->useCurrent();
            $table->string('log_logged_by', 100)->nullable();

            $table->foreign('log_transaction_id')->references('tra_id')->on('sws_transactions');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sws_transaction_logs');
    }
};
