<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AccountSyncSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Starting Account Synchronization...');

        // 1. Sync Users -> EmployeeAccount
        $this->syncEmployees();

        // 2. Sync Vendors -> VendorAccount
        $this->syncVendors();

        $this->command->info('Account Synchronization Completed.');
    }

    private function generateUniqueId($prefix)
    {
        $date = now()->format('Ymd');
        $random = strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 5));
        return $prefix . $date . $random;
    }

    private function syncEmployees()
    {
        $this->command->info('Syncing Users to EmployeeAccount with new IDs...');

        // Clear existing data
        DB::connection('main')->table('employee_account')->truncate();

        $users = DB::connection('main')->table('users')->get();
        $count = 0;

        foreach ($users as $user) {
            // Generate new ID using the requested logic
            $newId = $this->generateUniqueId('EMP');

            // Update the source user record with the new ID
            DB::connection('main')->table('users')->where('id', $user->id)->update(['employeeid' => $newId]);

            // Insert into employee_account with the new ID
            DB::connection('main')->table('employee_account')->insert([
                'user_id' => $user->id,
                'employeeid' => $newId, // Use new ID
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
                'last_login' => null, 
            ]);
            $count++;
        }

        $this->command->info("Synced {$count} employees with renewed IDs.");
    }

    private function syncVendors()
    {
        $this->command->info('Syncing Vendors to VendorAccount with new IDs...');

        DB::connection('main')->table('vendor_account')->truncate();

        $vendors = DB::connection('main')->table('vendors')->get();
        $count = 0;

        foreach ($vendors as $vendor) {
            // Generate new ID using the requested logic
            $newId = $this->generateUniqueId('VEN');

            // Update the source vendor record with the new ID
            DB::connection('main')->table('vendors')->where('id', $vendor->id)->update(['vendorid' => $newId]);

            // Insert into vendor_account with the new ID
            DB::connection('main')->table('vendor_account')->insert([
                'vendor_id' => $vendor->id,
                'vendorid' => $newId, // Use new ID
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
                'last_login' => null,
                'company_type' => null,
                'rating' => 0,
            ]);
            $count++;
        }

        $this->command->info("Synced {$count} vendors with renewed IDs.");
    }
}
