<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('alms_disposals', function (Blueprint $table) {
            $table->id();
            $table->string('disposal_id', 20)->unique()->comment('Format: DS00001');
            $table->foreignId('asset_id')->constrained('alms_assets')->unique();
            $table->date('disposal_date');
            $table->enum('method', ['decommission', 'disposal', 'resale']);
            $table->decimal('disposal_value', 10, 2)->nullable();
            $table->text('reason')->nullable();
            $table->text('compliance_notes')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('alms_disposals');
    }
};