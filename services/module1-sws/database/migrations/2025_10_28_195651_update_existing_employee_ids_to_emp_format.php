<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Get all users and update their employee_ids
        $users = DB::table('users')->orderBy('id')->get();
        
        $counter = 1;
        foreach ($users as $user) {
            $newEmployeeId = 'EMP' . str_pad($counter, 5, '0', STR_PAD_LEFT);
            
            DB::table('users')
                ->where('id', $user->id)
                ->update(['employee_id' => $newEmployeeId]);
            
            $counter++;
        }
        
        // Log the update
        \Illuminate\Support\Facades\Log::info("Updated {$counter} user employee IDs to EMP format");
    }

    public function down(): void
    {
        // This is a one-way migration - cannot reliably revert
        // Backup your database before running this migration
        \Illuminate\Support\Facades\Log::warning('Cannot revert employee ID update migration - data would be lost');
    }
};