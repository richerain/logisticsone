<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Copy vendor accounts from users to vendors
        if (Schema::connection('main')->hasTable('users') && Schema::connection('main')->hasTable('vendors')) {
            $vendors = DB::connection('main')->table('users')->where('roles', 'vendor')->get();
            foreach ($vendors as $u) {
                $exists = DB::connection('main')->table('vendors')->where('email', $u->email)->exists();
                if ($exists) {
                    continue;
                }
                DB::connection('main')->table('vendors')->insert([
                    'id' => $u->id,
                    'vendorid' => $u->employeeid,
                    'lastname' => $u->lastname,
                    'firstname' => $u->firstname,
                    'middlename' => $u->middlename,
                    'sex' => $u->sex,
                    'age' => $u->age,
                    'birthdate' => $u->birthdate,
                    'contactnum' => $u->contactnum,
                    'email' => $u->email,
                    'address' => $u->address,
                    'password' => $u->password,
                    'picture' => $u->picture,
                    'roles' => 'vendor',
                    'status' => $u->status,
                    'otp' => $u->otp,
                    'otp_expires_at' => $u->otp_expires_at,
                    'email_verified_at' => $u->email_verified_at,
                    'remember_token' => $u->remember_token,
                    'created_at' => $u->created_at,
                    'updated_at' => $u->updated_at,
                ]);
            }

            // Remove vendor accounts from users table
            DB::connection('main')->table('users')->where('roles', 'vendor')->delete();
        }

        // Update users.roles enum to remove 'vendor'
        if (Schema::connection('main')->hasTable('users')) {
            Schema::connection('main')->table('users', function (Blueprint $table) {
                $table->enum('roles', ['superadmin', 'admin', 'manager', 'staff'])->change();
            });
        }
    }

    public function down(): void
    {
        // Re-add vendor role to users.roles enum
        if (Schema::connection('main')->hasTable('users')) {
            Schema::connection('main')->table('users', function (Blueprint $table) {
                $table->enum('roles', ['superadmin', 'admin', 'manager', 'staff', 'vendor'])->change();
            });
        }

        // Move vendor accounts back to users if needed
        if (Schema::connection('main')->hasTable('vendors') && Schema::connection('main')->hasTable('users')) {
            $vendors = DB::connection('main')->table('vendors')->get();
            foreach ($vendors as $v) {
                $exists = DB::connection('main')->table('users')->where('email', $v->email)->exists();
                if ($exists) {
                    continue;
                }
                DB::connection('main')->table('users')->insert([
                    'id' => $v->id,
                    'employeeid' => $v->vendorid,
                    'lastname' => $v->lastname,
                    'firstname' => $v->firstname,
                    'middlename' => $v->middlename,
                    'sex' => $v->sex,
                    'age' => $v->age,
                    'birthdate' => $v->birthdate,
                    'contactnum' => $v->contactnum,
                    'email' => $v->email,
                    'address' => $v->address,
                    'password' => $v->password,
                    'picture' => $v->picture,
                    'roles' => 'vendor',
                    'status' => $v->status,
                    'otp' => $v->otp,
                    'otp_expires_at' => $v->otp_expires_at,
                    'email_verified_at' => $v->email_verified_at,
                    'remember_token' => $v->remember_token,
                    'created_at' => $v->created_at,
                    'updated_at' => $v->updated_at,
                ]);
            }
        }
    }
};
