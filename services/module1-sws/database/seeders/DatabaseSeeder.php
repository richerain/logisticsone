<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('logs123');

        $users = [
            [
                'employee_id' => 'L1SAD001',
                'lastname' => 'Altamante',
                'firstname' => 'Ric',
                'middlename' => 'Jason E.',
                'sex' => 'M',
                'age' => 28,
                'birthdate' => Carbon::now()->subYears(28)->format('Y-m-d'),
                'contactnum' => '09171111111',
                'Email' => 'altamantericjason@gmail.com',
                'password' => $password,
                'roles' => 'superadmin',
                'status' => 'active',
                'profile_picture' => null,
            ],
            [
                'employee_id' => 'L1ADM001',
                'lastname' => 'Quilenderino',
                'firstname' => 'Robert',
                'middlename' => 'B.',
                'sex' => 'M',
                'age' => 26,
                'birthdate' => Carbon::now()->subYears(26)->format('Y-m-d'),
                'contactnum' => '09172222222',
                'Email' => 'robertbarredoquilenderino@gmail.com',
                'password' => $password,
                'roles' => 'admin',
                'status' => 'active',
                'profile_picture' => null,
            ],
            [
                'employee_id' => 'L1MAN001',
                'lastname' => 'Ibuos',
                'firstname' => 'Carl',
                'middlename' => 'B.',
                'sex' => 'M',
                'age' => 25,
                'birthdate' => Carbon::now()->subYears(25)->format('Y-m-d'),
                'contactnum' => '09173333333',
                'Email' => 'carleyibuos@gmail.com',
                'password' => $password,
                'roles' => 'manager',
                'status' => 'active',
                'profile_picture' => null,
            ],
            [
                'employee_id' => 'L1STF001',
                'lastname' => 'Sobejana',
                'firstname' => 'Rhealynn Jhuy',
                'middlename' => 'B.',
                'sex' => 'F',
                'age' => 24,
                'birthdate' => Carbon::now()->subYears(24)->format('Y-m-d'),
                'contactnum' => '09174444444',
                'Email' => 'sobejanajoy@gmail.com',
                'password' => $password,
                'roles' => 'staff',
                'status' => 'active',
                'profile_picture' => null,
            ],
            [
                'employee_id' => 'L1VEN001',
                'lastname' => 'Tolentino',
                'firstname' => 'Kristian Icy',
                'middlename' => 'B.',
                'sex' => 'M',
                'age' => 23,
                'birthdate' => Carbon::now()->subYears(23)->format('Y-m-d'),
                'contactnum' => '09175555555',
                'Email' => 'aicy3987@gmail.com',
                'password' => $password,
                'roles' => 'vendor',
                'status' => 'active',
                'profile_picture' => null,
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(
                ['Email' => $userData['Email']],
                $userData
            );
        }

        // Add SWS data seeder
        $this->call(SWSDatabaseSeeder::class);
    }
}