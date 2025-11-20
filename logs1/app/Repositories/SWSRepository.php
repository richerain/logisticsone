<?php

namespace App\Repositories;

use App\Models\SWS\Item;
use App\Models\SWS\Category;
use App\Models\SWS\InventorySnapshot;
use App\Models\SWS\Location;
use App\Models\SWS\Warehouse;
use Illuminate\Support\Facades\DB;

class SWSRepository
{
    public function getAllItems()
    {
        return Item::with('category')->orderBy('item_name')->get();
    }

    public function getItemById($id)
    {
        return Item::with('category')->find($id);
    }

    public function createItem(array $data)
    {
        return Item::create($data);
    }

    public function updateItem($id, array $data)
    {
        $item = Item::find($id);
        if ($item) {
            $item->update($data);
            return $item;
        }
        return null;
    }

    public function deleteItem($id)
    {
        $item = Item::find($id);
        if ($item) {
            return $item->delete();
        }
        return false;
    }

    public function getAllCategories()
    {
        return Category::orderBy('cat_name')->get();
    }

    public function getCategoryById($id)
    {
        return Category::find($id);
    }

    public function createCategory(array $data)
    {
        return Category::create($data);
    }

    public function updateCategory($id, array $data)
    {
        $category = Category::find($id);
        if ($category) {
            $category->update($data);
            return $category;
        }
        return null;
    }

    public function deleteCategory($id)
    {
        $category = Category::find($id);
        if ($category) {
            return $category->delete();
        }
        return false;
    }

    public function getInventoryStats()
    {
        $totalItems = Item::count();
        $incomingItems = InventorySnapshot::where('snap_alert_level', 'low')->count();
        $outgoingItems = InventorySnapshot::where('snap_alert_level', 'critical')->count();
        $lowStockItems = Item::where('item_total_quantity', '<=', 10)->count();

        return [
            'total_items' => $totalItems,
            'incoming_items' => $incomingItems,
            'outgoing_items' => $outgoingItems,
            'low_stock_items' => $lowStockItems
        ];
    }

    public function getStockLevelsByCategory()
    {
        try {
            // Get categories with their items and calculate total quantity
            $categories = Category::with(['items' => function($query) {
                $query->select('item_category_id', 'item_total_quantity');
            }])->get();

            $stockLevels = [];
            
            foreach ($categories as $category) {
                $totalQuantity = $category->items->sum('item_total_quantity');
                
                // Calculate utilization percentage (assuming max capacity of 100 per category for demo)
                $maxCapacity = 100;
                $utilization = $maxCapacity > 0 ? min(($totalQuantity / $maxCapacity) * 100, 100) : 0;
                
                $stockLevels[] = [
                    'name' => $category->cat_name,
                    'utilization' => round($utilization, 2),
                    'total_quantity' => $totalQuantity
                ];
            }

            return $stockLevels;
        } catch (\Exception $e) {
            // Return empty array if there's an error
            return [];
        }
    }

    public function getItemsWithStockInfo()
    {
        try {
            return Item::with('category')
                ->select('item_id', 'item_name', 'item_stock_keeping_unit', 'item_category_id', 
                        'item_total_quantity', 'item_updated_at')
                ->get()
                ->map(function($item) {
                    // Calculate min stock based on business logic (e.g., 20% of current stock)
                    $minStock = max(1, round($item->item_total_quantity * 0.2));
                    
                    // Determine status
                    if ($item->item_total_quantity <= 0) {
                        $status = 'Out of Stock';
                        $statusClass = 'badge-error';
                    } elseif ($item->item_total_quantity <= $minStock) {
                        $status = 'Low Stock';
                        $statusClass = 'badge-warning';
                    } else {
                        $status = 'In Stock';
                        $statusClass = 'badge-success';
                    }

                    return [
                        'item_id' => $item->item_id,
                        'item_code' => $item->item_stock_keeping_unit ?? 'ITM-' . str_pad($item->item_id, 3, '0', STR_PAD_LEFT),
                        'item_name' => $item->item_name,
                        'category' => $item->category ? $item->category->cat_name : 'Uncategorized',
                        'current_stock' => $item->item_total_quantity,
                        'min_stock' => $minStock,
                        'status' => $status,
                        'status_class' => $statusClass,
                        'last_updated' => $item->item_updated_at ? $item->item_updated_at->format('Y-m-d') : 'N/A'
                    ];
                });
        } catch (\Exception $e) {
            return [];
        }
    }
}