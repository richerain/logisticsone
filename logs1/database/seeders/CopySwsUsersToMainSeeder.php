<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CopySwsUsersToMainSeeder extends Seeder
{
    public function run(): void
    {
        $rows = DB::connection('sws')->table('users')->get();
        if ($rows->isEmpty()) {
            return;
        }

        $batch = [];
        foreach ($rows as $row) {
            $batch[] = [
                'name' => trim(($row->firstname ?? '').' '.($row->lastname ?? '')),
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
            ];
        }

        $chunks = array_chunk($batch, 500);
        foreach ($chunks as $chunk) {
            DB::connection('main')->table('users')->insert($chunk);
        }
    }
}
