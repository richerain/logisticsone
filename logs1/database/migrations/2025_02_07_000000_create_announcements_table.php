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
        Schema::connection('main')->create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('desc');
            $table->string('announcement_image')->nullable();
            $table->timestamp('created_date')->useCurrent();
            $table->timestamp('updated_date')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('main')->dropIfExists('announcements');
    }
};
