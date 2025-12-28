<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::connection('alms')->create('alms_request_maintenance', function (Blueprint $table) {
            $table->id();
            $table->string('req_id', 50)->unique();
            $table->string('req_asset_name', 255);
            $table->date('req_date');
            $table->string('req_priority', 20);
        });
    }

    public function down(): void
    {
        Schema::connection('alms')->dropIfExists('alms_request_maintenance');
    }
};