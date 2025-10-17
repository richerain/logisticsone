<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('alms_asset_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('transfer_id', 20)->unique()->comment('Format: AT00001');
            $table->foreignId('asset_id')->constrained('alms_assets');
            $table->foreignId('from_branch_id')->constrained('alms_branches');
            $table->foreignId('to_branch_id')->constrained('alms_branches');
            $table->date('transfer_date');
            $table->text('reason')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->index(['transfer_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('alms_asset_transfers');
    }
};