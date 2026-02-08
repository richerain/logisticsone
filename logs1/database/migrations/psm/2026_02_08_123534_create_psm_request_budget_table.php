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
        Schema::connection('psm')->create('psm_request_budget', function (Blueprint $table) {
            $table->id('req_id');
            $table->string('req_by');
            $table->date('req_date');
            $table->string('req_dept');
            $table->decimal('req_amount', 15, 2);
            $table->text('req_purpose');
            $table->string('req_contact');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('psm')->dropIfExists('psm_request_budget');
    }
};
