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
        Schema::create('sws_categories', function (Blueprint $table) {
            $table->id('cat_id');
            $table->string('cat_name', 100)->unique();
            $table->text('cat_description')->nullable();
            $table->timestamp('cat_created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sws_categories');
    }
};
