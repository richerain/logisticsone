<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dtlr_documents', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number', 100)->unique();
            $table->foreignId('document_type_id')->constrained('dtlr_document_types');
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->json('extracted_data')->nullable();
            $table->string('file_path', 500);
            $table->enum('status', ['pending', 'processed', 'approved', 'archived', 'rejected'])->default('pending');
            $table->foreignId('current_branch_id')->constrained('dtlr_branches');
            $table->foreignId('created_by')->constrained('dtlr_users');
            $table->foreignId('updated_by')->nullable()->constrained('dtlr_users');
            $table->timestamp('ocr_processed_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dtlr_documents');
    }
};