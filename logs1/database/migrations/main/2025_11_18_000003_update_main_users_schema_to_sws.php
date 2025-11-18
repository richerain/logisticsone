<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::connection('main')->table('users', function (Blueprint $table) {
            if (!Schema::connection('main')->hasColumn('users', 'employeeid')) {
                $table->string('employeeid')->unique()->after('id');
            }
            if (!Schema::connection('main')->hasColumn('users', 'lastname')) {
                $table->string('lastname')->after('employeeid');
            }
            if (!Schema::connection('main')->hasColumn('users', 'firstname')) {
                $table->string('firstname')->after('lastname');
            }
            if (!Schema::connection('main')->hasColumn('users', 'middlename')) {
                $table->string('middlename')->nullable()->after('firstname');
            }
            if (!Schema::connection('main')->hasColumn('users', 'sex')) {
                $table->enum('sex', ['male', 'female'])->after('middlename');
            }
            if (!Schema::connection('main')->hasColumn('users', 'age')) {
                $table->integer('age')->after('sex');
            }
            if (!Schema::connection('main')->hasColumn('users', 'birthdate')) {
                $table->date('birthdate')->after('age');
            }
            if (!Schema::connection('main')->hasColumn('users', 'contactnum')) {
                $table->string('contactnum')->after('birthdate');
            }
            if (!Schema::connection('main')->hasColumn('users', 'address')) {
                $table->text('address')->nullable()->after('contactnum');
            }
            if (!Schema::connection('main')->hasColumn('users', 'picture')) {
                $table->string('picture')->nullable()->after('password');
            }
            if (!Schema::connection('main')->hasColumn('users', 'roles')) {
                $table->enum('roles', ['superadmin', 'admin', 'manager', 'staff', 'vendor'])->after('picture');
            }
            if (!Schema::connection('main')->hasColumn('users', 'status')) {
                $table->enum('status', ['active', 'inactive'])->default('active')->after('roles');
            }
            if (!Schema::connection('main')->hasColumn('users', 'otp')) {
                $table->string('otp')->nullable()->after('status');
            }
            if (!Schema::connection('main')->hasColumn('users', 'otp_expires_at')) {
                $table->timestamp('otp_expires_at')->nullable()->after('otp');
            }
        });
    }

    public function down(): void
    {
        // no-op
    }
};