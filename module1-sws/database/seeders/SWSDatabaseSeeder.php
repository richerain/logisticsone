<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Storage;
use App\Models\Inventory;
use App\Models\Restock;
use Illuminate\Support\Facades\DB;

class SWSDatabaseSeeder extends Seeder
{
    public function run()
    {
        // Clear existing data
        DB::table('sws_restock')->delete();
        DB::table('sws_inventory')->delete();
        DB::table('sws_storage')->delete();

        // Create storage locations
        $storage1 = Storage::create([
            'storage_id' => Storage::generateStorageId(),
            'storage_name' => 'Main Warehouse',
            'storage_location' => 'Building A, Floor 1',
            'storage_type' => 'Equipment',
            'storage_max_unit' => 1000,
            'storage_used_unit' => 0,
            'storage_status' => 'active'
        ]);

        $storage2 = Storage::create([
            'storage_id' => Storage::generateStorageId(),
            'storage_name' => 'Document Archive',
            'storage_location' => 'Building B, Floor 2',
            'storage_type' => 'Document',
            'storage_max_unit' => 500,
            'storage_used_unit' => 0,
            'storage_status' => 'active'
        ]);

        $storage3 = Storage::create([
            'storage_id' => Storage::generateStorageId(),
            'storage_name' => 'Supply Room',
            'storage_location' => 'Building A, Floor 1',
            'storage_type' => 'Supplies',
            'storage_max_unit' => 800,
            'storage_used_unit' => 0,
            'storage_status' => 'active'
        ]);

        // Create inventory items
        $inventory1 = Inventory::create([
            'item_id' => Inventory::generateItemId(),
            'item_name' => 'Laptop Computers',
            'item_type' => 'Equipment',
            'item_stock' => 50,
            'item_stock_capacity' => 100,
            'item_desc' => 'Dell Latitude laptops for office use',
            'item_storage_from' => $storage1->storage_id,
            'item_stock_level' => 'instock',
            'item_status' => 'pending'
        ]);

        $inventory2 = Inventory::create([
            'item_id' => Inventory::generateItemId(),
            'item_name' => 'Office Chairs',
            'item_type' => 'Furniture',
            'item_stock' => 25,
            'item_stock_capacity' => 50,
            'item_desc' => 'Ergonomic office chairs',
            'item_storage_from' => $storage1->storage_id,
            'item_stock_level' => 'instock',
            'item_status' => 'reserved'
        ]);

        $inventory3 = Inventory::create([
            'item_id' => Inventory::generateItemId(),
            'item_name' => 'A4 Paper',
            'item_type' => 'Supplies',
            'item_stock' => 10,
            'item_stock_capacity' => 200,
            'item_desc' => 'A4 printing paper, 500 sheets per pack',
            'item_storage_from' => $storage3->storage_id,
            'item_stock_level' => 'lowstock',
            'item_status' => 'restocking'
        ]);

        // Create restock requests
        Restock::create([
            'restock_id' => Restock::generateRestockId(),
            'restock_item_id' => $inventory3->item_id,
            'restock_item_name' => $inventory3->item_name,
            'restock_item_type' => $inventory3->item_type,
            'restock_item_unit' => 50,
            'restock_item_capacity' => $inventory3->item_stock_capacity,
            'restock_desc' => 'Low stock, need immediate restock',
            'restock_status' => 'pending'
        ]);

        // Update storage used units
        $storage1->storage_used_unit = $inventory1->item_stock + $inventory2->item_stock;
        $storage1->save();

        $storage3->storage_used_unit = $inventory3->item_stock;
        $storage3->save();

        $this->command->info('SWS demo data seeded successfully!');
        $this->command->info('Storage locations: ' . Storage::count());
        $this->command->info('Inventory items: ' . Inventory::count());
        $this->command->info('Restock requests: ' . Restock::count());
    }
}