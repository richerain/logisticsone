<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::connection('main')->hasTable('employee_account')) {
            Schema::connection('main')->table('employee_account', function (Blueprint $table) {
                if (! Schema::connection('main')->hasColumn('employee_account', 'otp')) {
                    $table->string('otp')->nullable()->after('status');
                }
                if (! Schema::connection('main')->hasColumn('employee_account', 'otp_expires_at')) {
                    $table->timestamp('otp_expires_at')->nullable()->after('otp');
                }
                if (! Schema::connection('main')->hasColumn('employee_account', 'email_verified_at')) {
                    $table->timestamp('email_verified_at')->nullable()->after('otp_expires_at');
                }
                if (! Schema::connection('main')->hasColumn('employee_account', 'remember_token')) {
                    $table->rememberToken();
                }
            });

            if (Schema::connection('main')->hasTable('users')) {
                $users = DB::connection('main')->table('users')->get();
                foreach ($users as $user) {
                    DB::connection('main')->table('employee_account')
                        ->where('email', $user->email)
                        ->update([
                            'otp' => $user->otp ?? null,
                            'otp_expires_at' => $user->otp_expires_at ?? null,
                            'email_verified_at' => $user->email_verified_at ?? null,
                            'remember_token' => $user->remember_token ?? null,
                        ]);
                }
            }
        }

        if (Schema::connection('main')->hasTable('vendor_account')) {
            Schema::connection('main')->table('vendor_account', function (Blueprint $table) {
                if (! Schema::connection('main')->hasColumn('vendor_account', 'otp')) {
                    $table->string('otp')->nullable()->after('status');
                }
                if (! Schema::connection('main')->hasColumn('vendor_account', 'otp_expires_at')) {
                    $table->timestamp('otp_expires_at')->nullable()->after('otp');
                }
                if (! Schema::connection('main')->hasColumn('vendor_account', 'email_verified_at')) {
                    $table->timestamp('email_verified_at')->nullable()->after('otp_expires_at');
                }
                if (! Schema::connection('main')->hasColumn('vendor_account', 'remember_token')) {
                    $table->rememberToken();
                }
            });

            if (Schema::connection('main')->hasTable('vendors')) {
                $vendors = DB::connection('main')->table('vendors')->get();
                foreach ($vendors as $vendor) {
                    DB::connection('main')->table('vendor_account')
                        ->where('email', $vendor->email)
                        ->update([
                            'otp' => $vendor->otp ?? null,
                            'otp_expires_at' => $vendor->otp_expires_at ?? null,
                            'email_verified_at' => $vendor->email_verified_at ?? null,
                            'remember_token' => $vendor->remember_token ?? null,
                        ]);
                }
            }
        }
    }

    public function down(): void
    {
        if (Schema::connection('main')->hasTable('employee_account')) {
            Schema::connection('main')->table('employee_account', function (Blueprint $table) {
                if (Schema::connection('main')->hasColumn('employee_account', 'otp')) {
                    $table->dropColumn('otp');
                }
                if (Schema::connection('main')->hasColumn('employee_account', 'otp_expires_at')) {
                    $table->dropColumn('otp_expires_at');
                }
                if (Schema::connection('main')->hasColumn('employee_account', 'email_verified_at')) {
                    $table->dropColumn('email_verified_at');
                }
                if (Schema::connection('main')->hasColumn('employee_account', 'remember_token')) {
                    $table->dropColumn('remember_token');
                }
            });
        }

        if (Schema::connection('main')->hasTable('vendor_account')) {
            Schema::connection('main')->table('vendor_account', function (Blueprint $table) {
                if (Schema::connection('main')->hasColumn('vendor_account', 'otp')) {
                    $table->dropColumn('otp');
                }
                if (Schema::connection('main')->hasColumn('vendor_account', 'otp_expires_at')) {
                    $table->dropColumn('otp_expires_at');
                }
                if (Schema::connection('main')->hasColumn('vendor_account', 'email_verified_at')) {
                    $table->dropColumn('email_verified_at');
                }
                if (Schema::connection('main')->hasColumn('vendor_account', 'remember_token')) {
                    $table->dropColumn('remember_token');
                }
            });
        }
    }
};

