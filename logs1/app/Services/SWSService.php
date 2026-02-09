<?php

namespace App\Services;

use App\Repositories\SWSRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\PSMService;

class SWSService
{
    protected $swsRepository;
    protected $psmService;

    public function __construct(SWSRepository $swsRepository, PSMService $psmService)
    {
        $this->swsRepository = $swsRepository;
        $this->psmService = $psmService;
    }

    public function getAllItems()
    {
        try {
            $items = $this->swsRepository->getAllItems();

            return [
                'success' => true,
                'data' => $items,
                'message' => 'Items retrieved successfully',
            ];
        } catch (\Exception $e) {
            Log::error('Error retrieving items: '.$e->getMessage());

            return [
                'success' => false,
                'data' => [],
                'message' => 'Failed to retrieve items',
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
                    'message' => 'Item retrieved successfully',
                ];
            }

            return [
                'success' => false,
                'data' => null,
                'message' => 'Item not found',
            ];
        } catch (\Exception $e) {
            Log::error('Error retrieving item: '.$e->getMessage());

            return [
                'success' => false,
                'data' => null,
                'message' => 'Failed to retrieve item',
            ];
        }
    }

    public function createItem($data)
    {
        try {
            DB::connection('sws')->beginTransaction();

            // Generate SKU if not provided
            if (empty($data['item_stock_keeping_unit'])) {
                $data['item_stock_keeping_unit'] = 'SKU-'.date('YmdHis').rand(100, 999);
            }

            // Check if item exists by SKU
            $existingItem = $this->swsRepository->getItemBySku($data['item_stock_keeping_unit']);

            // Extract PSM fields if present
            $psmPurchaseId = $data['psm_purchase_id'] ?? null;
            $psmItemIndex = $data['psm_item_index'] ?? null;
            
            // Remove them from data before sending to repo
            unset($data['psm_purchase_id']);
            unset($data['psm_item_index']);

            if ($existingItem) {
                // Update existing item
                $newStock = $existingItem->item_current_stock + ($data['item_current_stock'] ?? 0);
                $updateData = [
                    'item_current_stock' => $newStock,
                    'item_unit_price' => $data['item_unit_price'] ?? $existingItem->item_unit_price,
                ];
                
                $item = $this->swsRepository->updateItem($existingItem->item_id, $updateData);
                $message = 'Existing item updated: Stock added and price updated.';
            } else {
                $item = $this->swsRepository->createItem($data);
                $message = 'Item created successfully';
            }

            // Update PSM if needed
            if ($psmPurchaseId && $psmItemIndex !== null) {
                $this->psmService->markItemAsInventory($psmPurchaseId, $psmItemIndex);
            }

            DB::connection('sws')->commit();

            return [
                'success' => true,
                'data' => $item,
                'message' => $message,
            ];
        } catch (\Exception $e) {
            DB::connection('sws')->rollBack();
            Log::error('Error creating item: '.$e->getMessage());

            return [
                'success' => false,
                'data' => null,
                'message' => 'Failed to create item',
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
                    'message' => 'Item updated successfully',
                ];
            }

            DB::connection('sws')->rollBack();

            return [
                'success' => false,
                'data' => null,
                'message' => 'Item not found',
            ];
        } catch (\Exception $e) {
            DB::connection('sws')->rollBack();
            Log::error('Error updating item: '.$e->getMessage());

            return [
                'success' => false,
                'data' => null,
                'message' => 'Failed to update item',
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
                    'message' => 'Item deleted successfully',
                ];
            }

            DB::connection('sws')->rollBack();

            return [
                'success' => false,
                'message' => 'Item not found',
            ];
        } catch (\Exception $e) {
            DB::connection('sws')->rollBack();
            Log::error('Error deleting item: '.$e->getMessage());

            return [
                'success' => false,
                'message' => 'Failed to delete item',
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
                'message' => 'Inventory stats retrieved successfully',
            ];
        } catch (\Exception $e) {
            Log::error('Error retrieving inventory stats: '.$e->getMessage());

            return [
                'success' => false,
                'data' => [
                    'total_items' => 0,
                    'total_value' => 0,
                    'low_stock_items' => 0,
                    'out_of_stock_items' => 0,
                ],
                'message' => 'Failed to retrieve inventory stats',
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
                'message' => 'Stock levels by category retrieved successfully',
            ];
        } catch (\Exception $e) {
            Log::error('Error retrieving stock levels by category: '.$e->getMessage());
            Log::error('Error trace: '.$e->getTraceAsString());

            // Return default data for the 4 categories even if there's an error
            $defaultCategories = [
                ['name' => 'Equipment', 'utilization' => 0, 'total_quantity' => 0, 'max_capacity' => 100],
                ['name' => 'Supplies', 'utilization' => 0, 'total_quantity' => 0, 'max_capacity' => 100],
                ['name' => 'Furniture', 'utilization' => 0, 'total_quantity' => 0, 'max_capacity' => 100],
                ['name' => 'Automotive', 'utilization' => 0, 'total_quantity' => 0, 'max_capacity' => 100],
            ];

            return [
                'success' => true,
                'data' => $defaultCategories,
                'message' => 'Using default category data',
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
                'message' => 'Items with stock info retrieved successfully',
            ];
        } catch (\Exception $e) {
            Log::error('Error retrieving items with stock info: '.$e->getMessage());

            return [
                'success' => false,
                'data' => [],
                'message' => 'Failed to retrieve items with stock info',
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
                'message' => 'Categories retrieved successfully',
            ];
        } catch (\Exception $e) {
            Log::error('Error retrieving categories: '.$e->getMessage());

            return [
                'success' => false,
                'data' => [],
                'message' => 'Failed to retrieve categories',
            ];
        }
    }

    public function createCategory($data)
    {
        try {
            DB::connection('sws')->beginTransaction();

            // Generate ID: ICAT + YYYYMMDD + 5 random chars
            do {
                $datePart = now()->format('Ymd');
                // Generate 5 random alphanumeric characters (uppercase)
                $randomTail = strtoupper(\Illuminate\Support\Str::random(5));
                $catId = 'ICAT' . $datePart . $randomTail;
            } while (\App\Models\SWS\Category::where('cat_id', $catId)->exists());

            $data['cat_id'] = $catId;

            $category = $this->swsRepository->createCategory($data);

            DB::connection('sws')->commit();

            return [
                'success' => true,
                'data' => $category,
                'message' => 'Category created successfully',
            ];
        } catch (\Exception $e) {
            DB::connection('sws')->rollBack();
            Log::error('Error creating category: '.$e->getMessage());

            return [
                'success' => false,
                'data' => null,
                'message' => 'Failed to create category',
            ];
        }
    }

    public function updateCategory($id, $data)
    {
        try {
            DB::connection('sws')->beginTransaction();

            $category = $this->swsRepository->updateCategory($id, $data);

            if ($category) {
                DB::connection('sws')->commit();

                return [
                    'success' => true,
                    'data' => $category,
                    'message' => 'Category updated successfully',
                ];
            }

            DB::connection('sws')->rollBack();

            return [
                'success' => false,
                'data' => null,
                'message' => 'Category not found',
            ];
        } catch (\Exception $e) {
            DB::connection('sws')->rollBack();
            Log::error('Error updating category: '.$e->getMessage());

            return [
                'success' => false,
                'data' => null,
                'message' => 'Failed to update category',
            ];
        }
    }

    public function deleteCategory($id)
    {
        try {
            DB::connection('sws')->beginTransaction();

            $success = $this->swsRepository->deleteCategory($id);

            if ($success) {
                DB::connection('sws')->commit();

                return [
                    'success' => true,
                    'data' => null,
                    'message' => 'Category deleted successfully',
                ];
            }

            DB::connection('sws')->rollBack();

            return [
                'success' => false,
                'data' => null,
                'message' => 'Category not found',
            ];
        } catch (\Exception $e) {
            DB::connection('sws')->rollBack();
            Log::error('Error deleting category: '.$e->getMessage());

            return [
                'success' => false,
                'data' => null,
                'message' => 'Failed to delete category',
            ];
        }
    }

    public function getAllLocations()
    {
        try {
            $locations = $this->swsRepository->getAllLocations();

            return [
                'success' => true,
                'data' => $locations,
                'message' => 'Locations retrieved successfully',
            ];
        } catch (\Exception $e) {
            Log::error('Error retrieving locations: '.$e->getMessage());

            return [
                'success' => false,
                'data' => [],
                'message' => 'Failed to retrieve locations',
            ];
        }
    }

    public function createLocation($data)
    {
        try {
            foreach ($data as $key => $value) {
                if ($value === '') {
                    $data[$key] = null;
                }
            }

            DB::connection('sws')->beginTransaction();

            $location = $this->swsRepository->createLocation($data);

            DB::connection('sws')->commit();

            return [
                'success' => true,
                'data' => $location,
                'message' => 'Location created successfully',
            ];
        } catch (\Exception $e) {
            DB::connection('sws')->rollBack();
            Log::error('Error creating location: '.$e->getMessage());

            return [
                'success' => false,
                'data' => null,
                'message' => 'Failed to create location',
            ];
        }
    }

    public function updateLocation($id, $data)
    {
        try {
            DB::connection('sws')->beginTransaction();

            $location = $this->swsRepository->updateLocation($id, $data);

            if ($location) {
                DB::connection('sws')->commit();

                return [
                    'success' => true,
                    'data' => $location,
                    'message' => 'Location updated successfully',
                ];
            }

            DB::connection('sws')->rollBack();

            return [
                'success' => false,
                'data' => null,
                'message' => 'Location not found',
            ];
        } catch (\Exception $e) {
            DB::connection('sws')->rollBack();
            Log::error('Error updating location: '.$e->getMessage());

            return [
                'success' => false,
                'data' => null,
                'message' => 'Failed to update location',
            ];
        }
    }

    public function deleteLocation($id)
    {
        try {
            DB::connection('sws')->beginTransaction();

            $result = $this->swsRepository->deleteLocation($id);

            if ($result) {
                DB::connection('sws')->commit();

                return [
                    'success' => true,
                    'message' => 'Location deleted successfully',
                ];
            }

            DB::connection('sws')->rollBack();

            return [
                'success' => false,
                'message' => 'Location not found',
            ];
        } catch (\Exception $e) {
            DB::connection('sws')->rollBack();
            Log::error('Error deleting location: '.$e->getMessage());

            return [
                'success' => false,
                'message' => 'Failed to delete location',
            ];
        }
    }

    public function getInventoryFlow($filters = [])
    {
        try {
            $summary = $this->swsRepository->getInventoryFlowSummary();
            $transactions = $this->swsRepository->getRecentTransactions(100, $filters);

            return [
                'success' => true,
                'data' => [
                    'summary' => $summary,
                    'transactions' => $transactions,
                ],
                'message' => 'Inventory flow retrieved successfully',
            ];
        } catch (\Exception $e) {
            Log::error('Error retrieving inventory flow: '.$e->getMessage());

            return [
                'success' => false,
                'data' => [],
                'message' => 'Failed to retrieve inventory flow',
            ];
        }
    }

    public function getInventoryFlowReportData($filters = [])
    {
        try {
            $data = $this->swsRepository->getInventoryFlowReportData($filters);

            return [
                'success' => true,
                'data' => $data,
                'message' => 'Inventory flow report data retrieved successfully',
            ];
        } catch (\Exception $e) {
            Log::error('Error retrieving inventory flow report data: '.$e->getMessage());

            return [
                'success' => false,
                'data' => [],
                'message' => 'Failed to retrieve inventory flow report data',
            ];
        }
    }
}
