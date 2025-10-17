<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ALMSDatabaseSeeder extends Seeder
{
    public function run()
    {
        // Seed branches
        DB::table('alms_branches')->insert([
            ['name' => 'Headquarters', 'address' => '123 Main Street, City Center', 'code' => 'HQ-001', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Branch A', 'address' => '456 Oak Avenue, District 1', 'code' => 'BR-A01', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Branch B', 'address' => '789 Pine Road, District 2', 'code' => 'BR-B01', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Regional Office', 'address' => '321 Elm Street, Regional Area', 'code' => 'RO-001', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Seed employees
        DB::table('alms_employees')->insert([
            ['name' => 'John Smith', 'email' => 'john.smith@company.com', 'position' => 'IT Manager', 'branch_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Maria Garcia', 'email' => 'maria.garcia@company.com', 'position' => 'Loan Officer', 'branch_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'David Johnson', 'email' => 'david.johnson@company.com', 'position' => 'Branch Manager', 'branch_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Sarah Chen', 'email' => 'sarah.chen@company.com', 'position' => 'Operations Supervisor', 'branch_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Michael Brown', 'email' => 'michael.brown@company.com', 'position' => 'Customer Service', 'branch_id' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Seed asset categories
        DB::table('alms_asset_categories')->insert([
            ['name' => 'Computers', 'description' => 'Laptops, desktops, and computer equipment', 'maintenance_frequency' => 'yearly', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Printers', 'description' => 'Printers and scanning equipment', 'maintenance_frequency' => 'yearly', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Biometric Devices', 'description' => 'Fingerprint scanners and access control systems', 'maintenance_frequency' => 'monthly', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Vehicles', 'description' => 'Company vehicles for loan officers', 'maintenance_frequency' => 'km_based', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Office Furniture', 'description' => 'Desks, chairs, and office equipment', 'maintenance_frequency' => 'yearly', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'ATM/Kiosk', 'description' => 'Automated teller machines and self-service kiosks', 'maintenance_frequency' => 'monthly', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Networking', 'description' => 'Routers, switches, and network equipment', 'maintenance_frequency' => 'yearly', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Seed maintenance types
        DB::table('alms_maintenance_types')->insert([
            ['name' => 'Annual Servicing', 'frequency_unit' => 'months', 'estimated_cost' => 150.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Software Update', 'frequency_unit' => 'months', 'estimated_cost' => 50.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Hardware Cleaning', 'frequency_unit' => 'months', 'estimated_cost' => 75.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Oil Change', 'frequency_unit' => 'km', 'estimated_cost' => 120.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Tire Rotation', 'frequency_unit' => 'km', 'estimated_cost' => 80.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Calibration', 'frequency_unit' => 'months', 'estimated_cost' => 200.00, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Security Update', 'frequency_unit' => 'months', 'estimated_cost' => 100.00, 'created_at' => now(), 'updated_at' => now()],
        ]);

        // Seed sample assets
        DB::table('alms_assets')->insert([
            [
                'alms_id' => 'ALMS00001',
                'serial_number' => 'LAP-DELL-001',
                'name' => 'Dell Latitude Laptop',
                'category_id' => 1,
                'acquisition_date' => '2024-01-15',
                'acquisition_cost' => 1200.00,
                'current_branch_id' => 1,
                'assigned_employee_id' => 1,
                'status' => 'active',
                'description' => 'Dell Latitude 5420, 16GB RAM, 512GB SSD',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'alms_id' => 'ALMS00002',
                'serial_number' => 'PRN-HP-001',
                'name' => 'HP LaserJet Printer',
                'category_id' => 2,
                'acquisition_date' => '2024-02-20',
                'acquisition_cost' => 450.00,
                'current_branch_id' => 2,
                'assigned_employee_id' => 2,
                'status' => 'active',
                'description' => 'HP LaserJet Pro MFP M428fdw',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'alms_id' => 'ALMS00003',
                'serial_number' => 'BIO-ZKT-001',
                'name' => 'ZKTeco Biometric Scanner',
                'category_id' => 3,
                'acquisition_date' => '2024-03-10',
                'acquisition_cost' => 350.00,
                'current_branch_id' => 1,
                'assigned_employee_id' => null,
                'status' => 'active',
                'description' => 'ZKTeco BioTime 8.0 fingerprint scanner',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'alms_id' => 'ALMS00004',
                'serial_number' => 'VEH-TOY-001',
                'name' => 'Toyota Hilux',
                'category_id' => 4,
                'acquisition_date' => '2024-01-05',
                'acquisition_cost' => 28500.00,
                'current_branch_id' => 2,
                'assigned_employee_id' => 3,
                'status' => 'active',
                'description' => 'Company vehicle for branch operations',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // Seed sample maintenance schedules
        DB::table('alms_maintenance_schedules')->insert([
            [
                'schedule_id' => 'MS00001',
                'asset_id' => 1,
                'maintenance_type_id' => 1,
                'due_date' => '2024-12-15',
                'frequency_value' => 12,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'schedule_id' => 'MS00002',
                'asset_id' => 4,
                'maintenance_type_id' => 4,
                'due_date' => '2024-11-01',
                'frequency_value' => 5000,
                'status' => 'pending',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // Seed sample maintenance records
        DB::table('alms_maintenance_records')->insert([
            [
                'record_id' => 'MR00001',
                'asset_id' => 1,
                'schedule_id' => 1,
                'performed_date' => '2024-01-20',
                'cost' => 120.00,
                'description' => 'Initial setup and software installation',
                'performed_by' => 'IT Support Team',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);

        // Seed sample asset transfer
        DB::table('alms_asset_transfers')->insert([
            [
                'transfer_id' => 'AT00001',
                'asset_id' => 2,
                'from_branch_id' => 1,
                'to_branch_id' => 2,
                'transfer_date' => '2024-03-01',
                'reason' => 'Branch expansion and equipment allocation',
                'notes' => 'Printer transferred to support new branch operations',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ]);
    }
}