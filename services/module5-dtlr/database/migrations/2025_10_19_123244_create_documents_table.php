<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('document_id')->unique();
            $table->string('document_type'); // PO, GRN, Invoice, Delivery Note, etc.
            $table->string('linked_transaction')->nullable(); // PO-005, DLY-001, etc.
            $table->text('extracted_fields')->nullable(); // JSON or text field for OCR extracted data
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_type'); // pdf, jpg, png, etc.
            $table->string('status')->default('pending'); // indexed, pending, review, archived
            $table->date('upload_date');
            $table->string('uploaded_by');
            $table->string('uploaded_to'); // Department: Procurement, Logistics, etc.
            $table->text('description')->nullable();
            $table->boolean('ocr_processed')->default(false);
            $table->timestamp('ocr_processed_at')->nullable();
            $table->timestamps();
            
            $table->index(['document_id', 'status', 'document_type']);
            $table->index('upload_date');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};