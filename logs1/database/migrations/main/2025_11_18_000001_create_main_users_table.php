<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::connection('main')->hasTable('users')) {
            Schema::connection('main')->create('users', function (Blueprint $table) {
                $table->id();
                $table->string('employeeid')->unique();
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
                $table->enum('roles', ['superadmin', 'admin', 'manager', 'staff', 'vendor']);
                $table->enum('status', ['active', 'inactive'])->default('active');
                $table->string('otp')->nullable();
                $table->timestamp('otp_expires_at')->nullable();
                $table->timestamp('email_verified_at')->nullable();
                $table->rememberToken();
                $table->timestamps();
            });
        }

        try {
            $rows = DB::connection('sws')->table('users')->get();
            foreach ($rows as $row) {
                $exists = DB::connection('main')->table('users')->where('employeeid', $row->employeeid)->exists();
                if ($exists) {
                    continue;
                }
                DB::connection('main')->table('users')->insert([
                    'id' => $row->id,
                    'employeeid' => $row->employeeid,
                    'lastname' => $row->lastname,
                    'firstname' => $row->firstname,
                    'middlename' => $row->middlename,
                    'sex' => $row->sex,
                    'age' => $row->age,
                    'birthdate' => $row->birthdate,
                    'contactnum' => $row->contactnum,
                    'email' => $row->email,
                    'address' => $row->address,
                    'password' => $row->password,
                    'picture' => $row->picture,
                    'roles' => $row->roles,
                    'status' => $row->status,
                    'otp' => $row->otp,
                    'otp_expires_at' => $row->otp_expires_at,
                    'email_verified_at' => $row->email_verified_at,
                    'remember_token' => $row->remember_token,
                    'created_at' => $row->created_at,
                    'updated_at' => $row->updated_at,
                ]);
            }
        } catch (\Throwable $e) {
            // ignore copy errors
        }
    }

    public function down(): void
    {
        Schema::connection('main')->dropIfExists('users');
    }
};
