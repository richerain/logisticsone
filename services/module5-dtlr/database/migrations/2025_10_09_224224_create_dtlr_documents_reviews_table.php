<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dtlr_document_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('dtlr_documents');
            $table->foreignId('reviewer_id')->constrained('dtlr_users');
            $table->enum('review_status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('comments')->nullable();
            $table->timestamp('reviewed_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dtlr_document_reviews');
    }
};