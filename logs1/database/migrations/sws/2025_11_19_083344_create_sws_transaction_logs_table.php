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
        Schema::connection('sws')->create('sws_transaction_logs', function (Blueprint $table) {
            $table->id('log_id');
            $table->unsignedBigInteger('log_transaction_id'); // Changed to BigInteger
            $table->string('log_event', 100)->nullable();
            $table->text('log_details')->nullable();
            $table->timestamp('log_logged_at')->useCurrent();
            $table->string('log_logged_by', 100)->nullable();

            // Foreign keys removed to avoid circular dependency/creation order issues
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('sws')->dropIfExists('sws_transaction_logs');
    }
};
