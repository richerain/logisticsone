<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create employee_account table
        if (! Schema::connection('main')->hasTable('employee_account')) {
            Schema::connection('main')->create('employee_account', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id')->nullable(); // Link to users table
                
                // Fields from users table
                $table->string('employeeid')->nullable();
                $table->string('lastname')->nullable();
                $table->string('firstname')->nullable();
                $table->string('middlename')->nullable();
                $table->enum('sex', ['male', 'female'])->nullable();
                $table->integer('age')->nullable();
                $table->date('birthdate')->nullable();
                $table->string('contactnum')->nullable();
                $table->string('email')->unique();
                $table->text('address')->nullable();
                $table->string('password')->nullable();
                $table->string('picture')->nullable();
                $table->string('roles')->nullable(); // Changed from enum to string to avoid issues, or match exact enum
                $table->string('status')->default('active');
                
                // New columns
                $table->timestamp('last_login')->nullable();
                
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            });

            // Populate employee_account from users
            $users = DB::connection('main')->table('users')->get();
            foreach ($users as $user) {
                DB::connection('main')->table('employee_account')->insert([
                    'user_id' => $user->id,
                    'employeeid' => $user->employeeid,
                    'lastname' => $user->lastname,
                    'firstname' => $user->firstname,
                    'middlename' => $user->middlename,
                    'sex' => $user->sex,
                    'age' => $user->age,
                    'birthdate' => $user->birthdate,
                    'contactnum' => $user->contactnum,
                    'email' => $user->email,
                    'address' => $user->address,
                    'password' => $user->password,
                    'picture' => $user->picture,
                    'roles' => $user->roles,
                    'status' => $user->status,
                    'created_at' => $user->created_at,
                    'updated_at' => $user->updated_at,
                ]);
            }
        }

        // Create vendor_account table
        if (! Schema::connection('main')->hasTable('vendor_account')) {
            Schema::connection('main')->create('vendor_account', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('vendor_id')->nullable(); // Link to vendors table

                // Fields from vendors table
                $table->string('vendorid')->nullable();
                $table->string('lastname')->nullable();
                $table->string('firstname')->nullable();
                $table->string('middlename')->nullable();
                $table->enum('sex', ['male', 'female'])->nullable();
                $table->integer('age')->nullable();
                $table->date('birthdate')->nullable();
                $table->string('contactnum')->nullable();
                $table->string('email')->unique();
                $table->text('address')->nullable();
                $table->string('password')->nullable();
                $table->string('picture')->nullable();
                $table->string('roles')->default('vendor');
                $table->string('status')->default('active');

                // New columns
                $table->timestamp('last_login')->nullable();
                $table->string('company_type')->nullable();
                $table->integer('rating')->default(0);

                $table->timestamps();

                $table->foreign('vendor_id')->references('id')->on('vendors')->onDelete('cascade');
            });

            // Populate vendor_account from vendors
            $vendors = DB::connection('main')->table('vendors')->get();
            foreach ($vendors as $vendor) {
                DB::connection('main')->table('vendor_account')->insert([
                    'vendor_id' => $vendor->id,
                    'vendorid' => $vendor->vendorid,
                    'lastname' => $vendor->lastname,
                    'firstname' => $vendor->firstname,
                    'middlename' => $vendor->middlename,
                    'sex' => $vendor->sex,
                    'age' => $vendor->age,
                    'birthdate' => $vendor->birthdate,
                    'contactnum' => $vendor->contactnum,
                    'email' => $vendor->email,
                    'address' => $vendor->address,
                    'password' => $vendor->password,
                    'picture' => $vendor->picture,
                    'roles' => $vendor->roles,
                    'status' => $vendor->status,
                    'created_at' => $vendor->created_at,
                    'updated_at' => $vendor->updated_at,
                    'rating' => 0, // Default rating
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::connection('main')->dropIfExists('vendor_account');
        Schema::connection('main')->dropIfExists('employee_account');
    }
};
