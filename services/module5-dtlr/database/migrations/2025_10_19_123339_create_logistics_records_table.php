<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('logistics_records', function (Blueprint $table) {
            $table->id();
            $table->string('log_id')->unique();
            $table->string('action'); // Uploaded, Approved, Delivered, Verified, etc.
            $table->string('module'); // Procurement, Smart Warehousing, Project Logistics, etc.
            $table->text('description');
            $table->string('performed_by');
            $table->timestamp('timestamp');
            $table->boolean('ai_ocr_used')->default(false);
            $table->string('related_reference')->nullable(); // DOC-001, PO-005, etc.
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            
            $table->index(['log_id', 'action', 'module', 'timestamp']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('logistics_records');
    }
};