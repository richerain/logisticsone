<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dtlr_document_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('document_id')->constrained('dtlr_documents');
            $table->enum('action', ['accessed', 'printed', 'transferred', 'reviewed', 'status_changed']);
            $table->foreignId('from_branch_id')->nullable()->constrained('dtlr_branches');
            $table->foreignId('to_branch_id')->nullable()->constrained('dtlr_branches');
            $table->foreignId('performed_by')->constrained('dtlr_users');
            $table->timestamp('timestamp')->useCurrent();
            $table->text('notes')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dtlr_document_logs');
    }
};