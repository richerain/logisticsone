<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('alms_maintenance_records', function (Blueprint $table) {
            $table->id();
            $table->string('record_id', 20)->unique()->comment('Format: MR00001');
            $table->foreignId('asset_id')->constrained('alms_assets');
            $table->foreignId('schedule_id')->nullable()->constrained('alms_maintenance_schedules')->onDelete('set null');
            $table->date('performed_date');
            $table->decimal('cost', 10, 2)->nullable();
            $table->text('description')->nullable();
            $table->string('performed_by', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('alms_maintenance_records');
    }
};