<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class PSMVendorSeeder extends Seeder
{
    public function run()
    {
        // Clear existing data
        DB::connection('psm')->table('psm_vendor')->delete();

        $vendors = [
            [
                'ven_id' => 'VEND00001',
                'ven_company_name' => 'EquipmentPro Solutions',
                'ven_contact_person' => 'Maria Santos',
                'ven_email' => 'maria@equipmentpro.com',
                'ven_phone' => '+63 917 123 4567',
                'ven_address' => '123 Industrial Ave, Makati City, Metro Manila',
                'ven_rating' => 5,
                'ven_type' => 'equipment',
                'ven_product' => 4,
                'ven_status' => 'active',
                'ven_desc' => 'Leading supplier of industrial equipment and machinery with over 15 years of experience. We provide high-quality equipment with excellent after-sales support.',
                'ven_module_from' => 'psm',
                'ven_submodule_from' => 'vendor-management',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'ven_id' => 'VEND00002',
                'ven_company_name' => 'OfficeSupplies Depot',
                'ven_contact_person' => 'James Reyes',
                'ven_email' => 'james@officesuppliesdepot.com',
                'ven_phone' => '+63 918 234 5678',
                'ven_address' => '456 Business District, Ortigas Center, Pasig City',
                'ven_rating' => 4,
                'ven_type' => 'supplies',
                'ven_product' => 6,
                'ven_status' => 'active',
                'ven_desc' => 'Your one-stop shop for all office supplies. From stationery to printing materials, we have everything you need for your daily office operations.',
                'ven_module_from' => 'psm',
                'ven_submodule_from' => 'vendor-management',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'ven_id' => 'VEND00003',
                'ven_company_name' => 'ModernFurniture Co.',
                'ven_contact_person' => 'Andrea Lim',
                'ven_email' => 'andrea@modernfurniture.com',
                'ven_phone' => '+63 919 345 6789',
                'ven_address' => '789 Furniture Strip, Quezon City, Metro Manila',
                'ven_rating' => 4,
                'ven_type' => 'furniture',
                'ven_product' => 5,
                'ven_status' => 'active',
                'ven_desc' => 'Specializing in modern and ergonomic office furniture. We create comfortable and productive workspaces with our premium furniture solutions.',
                'ven_module_from' => 'psm',
                'ven_submodule_from' => 'vendor-management',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'ven_id' => 'VEND00004',
                'ven_company_name' => 'AutoParts Express',
                'ven_contact_person' => 'Roberto Cruz',
                'ven_email' => 'roberto@autopartsexpress.com',
                'ven_phone' => '+63 920 456 7890',
                'ven_address' => '321 Automotive Center, Banawe, Quezon City',
                'ven_rating' => 3,
                'ven_type' => 'automotive',
                'ven_product' => 8,
                'ven_status' => 'active',
                'ven_desc' => 'Trusted supplier of automotive parts and accessories. We provide genuine and high-quality parts for various vehicle brands and models.',
                'ven_module_from' => 'psm',
                'ven_submodule_from' => 'vendor-management',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]
        ];

        // Insert vendors
        DB::connection('psm')->table('psm_vendor')->insert($vendors);

        $this->command->info('Successfully seeded 4 vendors!');
        $this->command->info('Vendor Types: Equipment, Supplies, Furniture, Automotive');
    }
}