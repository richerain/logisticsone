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
        Schema::connection('psm')->create('psm_requisition', function (Blueprint $table) {
            $table->id();
            $table->string('req_id')->unique();
            $table->json('req_items'); // Assuming items will be stored as JSON
            $table->string('req_requester');
            $table->string('req_dept');
            $table->date('req_date');
            $table->text('req_note')->nullable();
            $table->string('req_status')->default('pending');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('psm')->dropIfExists('psm_requisition');
    }
};
