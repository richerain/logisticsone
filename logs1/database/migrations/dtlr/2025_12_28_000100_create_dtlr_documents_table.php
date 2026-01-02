<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (! Schema::connection('dtlr')->hasTable('dtlr_documents')) {
            Schema::connection('dtlr')->create('dtlr_documents', function (Blueprint $table) {
                $table->string('doc_id', 20)->primary();
                $table->enum('doc_type', ['Contract', 'Purchase Order', 'Invoice', 'Quotation', 'Good Received Note']);
                $table->string('doc_title', 255);
                $table->enum('doc_status', ['pending_review', 'indexed', 'archived'])->default('pending_review');
                $table->boolean('doc_file_available')->default(true);
                $table->string('doc_file_path', 500)->nullable();
                $table->string('doc_file_original_name', 255)->nullable();
                $table->string('doc_file_mime', 100)->nullable();
                $table->unsignedBigInteger('doc_file_size')->nullable();
                $table->timestamps();

                $table->index('doc_type');
                $table->index('doc_status');
                $table->index('created_at');
            });
        }
    }

    public function down()
    {
        Schema::connection('dtlr')->dropIfExists('dtlr_documents');
    }
};
