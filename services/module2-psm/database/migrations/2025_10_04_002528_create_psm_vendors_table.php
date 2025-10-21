<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('psm_vendors')) {
            Schema::create('psm_vendors', function (Blueprint $table) {
                $table->id('ven_id');
                $table->string('ven_code', 20)->unique(); // New field for VEN00001 format
                $table->string('ven_name', 255);
                $table->string('ven_contacts', 255)->nullable();
                $table->string('ven_email', 255)->unique();
                $table->text('ven_address')->nullable();
                $table->decimal('ven_rating', 3, 2)->default(0.00);
                $table->enum('ven_status', ['active', 'inactive'])->default('active');
                $table->timestamps();
                
                $table->index('ven_status');
                $table->index('ven_code'); // Add index for ven_code
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('psm_vendors');
    }
};