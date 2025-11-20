<?php

namespace App\Services;

use App\Repositories\SWSRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SWSService
{
    protected $swsRepository;

    public function __construct(SWSRepository $swsRepository)
    {
        $this->swsRepository = $swsRepository;
    }

    public function getAllItems()
    {
        try {
            $items = $this->swsRepository->getAllItems();
            return [
                'success' => true,
                'data' => $items,
                'message' => 'Items retrieved successfully'
            ];
        } catch (\Exception $e) {
            Log::error('Error retrieving items: ' . $e->getMessage());
            return [
                'success' => false,
                'data' => [],
                'message' => 'Failed to retrieve items'
            ];
        }
    }

    public function getItemById($id)
    {
        try {
            $item = $this->swsRepository->getItemById($id);
            if ($item) {
                return [
                    'success' => true,
                    'data' => $item,
                    'message' => 'Item retrieved successfully'
                ];
            }
            return [
                'success' => false,
                'data' => null,
                'message' => 'Item not found'
            ];
        } catch (\Exception $e) {
            Log::error('Error retrieving item: ' . $e->getMessage());
            return [
                'success' => false,
                'data' => null,
                'message' => 'Failed to retrieve item'
            ];
        }
    }

    public function createItem($data)
    {
        try {
            DB::connection('sws')->beginTransaction();

            // Generate SKU if not provided
            if (empty($data['item_stock_keeping_unit'])) {
                $data['item_stock_keeping_unit'] = 'ITM-' . date('YmdHis') . rand(100, 999);
            }

            $item = $this->swsRepository->createItem($data);
            
            DB::connection('sws')->commit();

            return [
                'success' => true,
                'data' => $item,
                'message' => 'Item created successfully'
            ];
        } catch (\Exception $e) {
            DB::connection('sws')->rollBack();
            Log::error('Error creating item: ' . $e->getMessage());
            return [
                'success' => false,
                'data' => null,
                'message' => 'Failed to create item'
            ];
        }
    }

    public function updateItem($id, $data)
    {
        try {
            DB::connection('sws')->beginTransaction();

            $item = $this->swsRepository->updateItem($id, $data);
            
            if ($item) {
                DB::connection('sws')->commit();
                return [
                    'success' => true,
                    'data' => $item,
                    'message' => 'Item updated successfully'
                ];
            }

            DB::connection('sws')->rollBack();
            return [
                'success' => false,
                'data' => null,
                'message' => 'Item not found'
            ];
        } catch (\Exception $e) {
            DB::connection('sws')->rollBack();
            Log::error('Error updating item: ' . $e->getMessage());
            return [
                'success' => false,
                'data' => null,
                'message' => 'Failed to update item'
            ];
        }
    }

    public function deleteItem($id)
    {
        try {
            DB::connection('sws')->beginTransaction();

            $result = $this->swsRepository->deleteItem($id);
            
            if ($result) {
                DB::connection('sws')->commit();
                return [
                    'success' => true,
                    'message' => 'Item deleted successfully'
                ];
            }

            DB::connection('sws')->rollBack();
            return [
                'success' => false,
                'message' => 'Item not found'
            ];
        } catch (\Exception $e) {
            DB::connection('sws')->rollBack();
            Log::error('Error deleting item: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to delete item'
            ];
        }
    }

    public function getInventoryStats()
    {
        try {
            $stats = $this->swsRepository->getInventoryStats();
            return [
                'success' => true,
                'data' => $stats,
                'message' => 'Inventory stats retrieved successfully'
            ];
        } catch (\Exception $e) {
            Log::error('Error retrieving inventory stats: ' . $e->getMessage());
            return [
                'success' => false,
                'data' => [
                    'total_items' => 0,
                    'incoming_items' => 0,
                    'outgoing_items' => 0,
                    'low_stock_items' => 0
                ],
                'message' => 'Failed to retrieve inventory stats'
            ];
        }
    }

    public function getStockLevelsByCategory()
    {
        try {
            $stockLevels = $this->swsRepository->getStockLevelsByCategory();
            return [
                'success' => true,
                'data' => $stockLevels,
                'message' => 'Stock levels by category retrieved successfully'
            ];
        } catch (\Exception $e) {
            Log::error('Error retrieving stock levels by category: ' . $e->getMessage());
            Log::error('Error trace: ' . $e->getTraceAsString());
            
            // Return default data for the 4 categories even if there's an error
            $defaultCategories = [
                ['name' => 'Equipment', 'utilization' => 0, 'total_quantity' => 0],
                ['name' => 'Supplies', 'utilization' => 0, 'total_quantity' => 0],
                ['name' => 'Furniture', 'utilization' => 0, 'total_quantity' => 0],
                ['name' => 'Automotive', 'utilization' => 0, 'total_quantity' => 0],
            ];
            
            return [
                'success' => true,
                'data' => $defaultCategories,
                'message' => 'Using default category data'
            ];
        }
    }

    public function getItemsWithStockInfo()
    {
        try {
            $items = $this->swsRepository->getItemsWithStockInfo();
            return [
                'success' => true,
                'data' => $items,
                'message' => 'Items with stock info retrieved successfully'
            ];
        } catch (\Exception $e) {
            Log::error('Error retrieving items with stock info: ' . $e->getMessage());
            return [
                'success' => false,
                'data' => [],
                'message' => 'Failed to retrieve items with stock info'
            ];
        }
    }

    public function getAllCategories()
    {
        try {
            $categories = $this->swsRepository->getAllCategories();
            return [
                'success' => true,
                'data' => $categories,
                'message' => 'Categories retrieved successfully'
            ];
        } catch (\Exception $e) {
            Log::error('Error retrieving categories: ' . $e->getMessage());
            return [
                'success' => false,
                'data' => [],
                'message' => 'Failed to retrieve categories'
            ];
        }
    }
}