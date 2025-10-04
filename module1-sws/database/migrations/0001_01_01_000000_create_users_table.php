<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('employee_id')->unique(); // New column for employee ID
            $table->string('lastname');
            $table->string('firstname');
            $table->string('middlename')->nullable();
            $table->enum('sex', ['M', 'F']);
            $table->integer('age');
            $table->date('birthdate');
            $table->string('contactnum');
            $table->string('Email')->unique();
            $table->string('password');
            $table->string('roles');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->string('profile_picture')->nullable(); // New column for profile picture
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};