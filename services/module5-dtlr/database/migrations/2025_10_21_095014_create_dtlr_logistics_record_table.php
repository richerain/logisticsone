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
        Schema::create('dtlr_logistics_record', function (Blueprint $table) {
            $table->id();
            $table->string('log_id')->unique()->comment('Auto-generated format: LOG00001');
            $table->string('action')->comment('What was done (Uploaded, Approved, Delivered)');
            $table->string('module')->comment('Where the action occurred');
            $table->string('performed_by')->comment('User or system who did it');
            $table->timestamp('timestamp')->useCurrent();
            $table->boolean('ai_ocr_used')->default(false)->comment('Indicates if AI OCR was used in the process');
            $table->text('details')->nullable()->comment('Additional details about the action');
            $table->timestamps();

            // Indexes for better performance
            $table->index('log_id');
            $table->index('action');
            $table->index('module');
            $table->index('performed_by');
            $table->index('timestamp');
            $table->index('ai_ocr_used');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dtlr_logistics_record');
    }
};