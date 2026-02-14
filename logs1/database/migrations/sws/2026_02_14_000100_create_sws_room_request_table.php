<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    protected $connection = 'sws';

    public function up(): void
    {
        if (! Schema::connection('sws')->hasTable('sws_room_request')) {
            Schema::connection('sws')->create('sws_room_request', function (Blueprint $table) {
                $table->increments('rmreq_id');
                $table->string('rmreq_requester', 100)->nullable();
                $table->enum('rmreq_room_type', ['office', 'department', 'facility', 'room', 'storage']);
                $table->text('rmreq_note')->nullable();
                $table->timestamp('rmreq_date')->useCurrent();
                $table->enum('rmreq_status', ['pending', 'approved', 'rejected'])->default('pending');
            });
        }
    }

    public function down(): void
    {
        Schema::connection('sws')->dropIfExists('sws_room_request');
    }
};

