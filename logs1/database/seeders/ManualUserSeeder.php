<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ManualUserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'employeeid' => 'EMP00001',
                'lastname' => 'Altamante',
                'firstname' => 'Ric Jason',
                'middlename' => 'E.',
                'sex' => 'male',
                'age' => 25,
                'birthdate' => '1998-01-01',
                'contactnum' => '09123456789',
                'email' => 'altamantericjason@gmail.com',
                'address' => 'BLK00 LOT00, Streets Barangay Subdivision City 0123',
                'password' => Hash::make('logs123'),
                'roles' => 'superadmin',
                'status' => 'active',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'employeeid' => 'EMP00002',
                'lastname' => 'Quilenderino',
                'firstname' => 'Robert',
                'middlename' => 'B.',
                'sex' => 'male',
                'age' => 25,
                'birthdate' => '1998-01-02',
                'contactnum' => '09123456790',
                'email' => 'robertbarredoquilenderino@gmail.com',
                'address' => 'BLK00 LOT00, Streets Barangay Subdivision City 0123',
                'password' => Hash::make('logs123'),
                'roles' => 'admin',
                'status' => 'active',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'employeeid' => 'EMP00003',
                'lastname' => 'Ibuos',
                'firstname' => 'Carl',
                'middlename' => 'B.',
                'sex' => 'male',
                'age' => 25,
                'birthdate' => '1998-01-03',
                'contactnum' => '09123456791',
                'email' => 'carleyibuos@gmail.com',
                'address' => 'BLK00 LOT00, Streets Barangay Subdivision City 0123',
                'password' => Hash::make('logs123'),
                'roles' => 'manager',
                'status' => 'active',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'employeeid' => 'EMP00004',
                'lastname' => 'Sobejana',
                'firstname' => 'Rhenelynn Jhuy',
                'middlename' => 'B.',
                'sex' => 'female',
                'age' => 25,
                'birthdate' => '1998-01-04',
                'contactnum' => '09123456792',
                'email' => 'sobejanajoy@gmail.com',
                'address' => 'BLK00 LOT00, Streets Barangay Subdivision City 0123',
                'password' => Hash::make('logs123'),
                'roles' => 'staff',
                'status' => 'active',
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            // Vendor sample user moved to vendors table seeding below
        ];

        foreach ($users as $user) {
            DB::connection('main')->table('users')->insert($user);
        }

        // Seed sample vendor to vendors table
        DB::connection('main')->table('vendors')->insert([
            'vendorid' => 'VEN00001',
            'lastname' => 'Tolentino',
            'firstname' => 'Kristian Icy',
            'middlename' => 'B.',
            'sex' => 'male',
            'age' => 25,
            'birthdate' => '1998-01-05',
            'contactnum' => '09123456793',
            'email' => 'aicy3987@gmail.com',
            'address' => 'BLK00 LOT00, Streets Barangay Subdivision City 0123',
            'password' => Hash::make('logs123'),
            'roles' => 'vendor',
            'status' => 'active',
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $this->command->info('Users and vendors seeded successfully in MAIN database.');
    }
}