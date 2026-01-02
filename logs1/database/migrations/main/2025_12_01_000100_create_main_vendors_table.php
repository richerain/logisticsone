<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::connection('main')->hasTable('vendors')) {
            Schema::connection('main')->create('vendors', function (Blueprint $table) {
                $table->id();
                $table->string('vendorid')->unique();
                $table->string('lastname');
                $table->string('firstname');
                $table->string('middlename')->nullable();
                $table->enum('sex', ['male', 'female']);
                $table->integer('age');
                $table->date('birthdate');
                $table->string('contactnum');
                $table->string('email')->unique();
                $table->text('address')->nullable();
                $table->string('password');
                $table->string('picture')->nullable();
                $table->enum('roles', ['vendor'])->default('vendor');
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->string('otp')->nullable();
                $table->timestamp('otp_expires_at')->nullable();
                $table->timestamp('email_verified_at')->nullable();
                $table->rememberToken();
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::connection('main')->dropIfExists('vendors');
    }
};
