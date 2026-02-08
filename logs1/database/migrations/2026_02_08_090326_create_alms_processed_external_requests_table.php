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
        if (!Schema::connection('alms')->hasTable('alms_processed_external_requests')) {
            Schema::connection('alms')->create('alms_processed_external_requests', function (Blueprint $table) {
                $table->id();
                $table->string('external_id')->unique(); // ID from external API
                $table->timestamp('processed_at')->useCurrent();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('alms')->dropIfExists('alms_processed_external_requests');
    }
};
