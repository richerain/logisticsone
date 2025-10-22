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
        Schema::create('dtlr_document_tracker', function (Blueprint $table) {
            $table->id();
            $table->string('document_id')->unique()->comment('Auto-generated format: DOC00001');
            $table->string('document_type')->comment('PO, GRN, Invoice, Delivery Note, etc.');
            $table->string('linked_transaction')->nullable()->comment('Which PO, GRN, or Delivery ID is linked');
            $table->json('extracted_fields')->nullable()->comment('Fields captured using OCR');
            $table->timestamp('upload_date')->useCurrent();
            $table->string('uploaded_by')->comment('User or department who uploaded');
            $table->enum('status', ['Indexed', 'Pending Review', 'Archived'])->default('Pending Review');
            $table->string('file_path')->nullable()->comment('Storage path for uploaded file');
            $table->string('file_name')->nullable()->comment('Original file name');
            $table->bigInteger('file_size')->nullable()->comment('File size in bytes');
            $table->string('file_type')->nullable()->comment('MIME type of the file');
            $table->timestamps();

            // Indexes for better performance
            $table->index('document_id');
            $table->index('document_type');
            $table->index('status');
            $table->index('upload_date');
            $table->index('uploaded_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dtlr_document_tracker');
    }
};