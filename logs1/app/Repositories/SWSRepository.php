<?php

namespace App\Repositories;

use App\Models\SWS\Item;
use App\Models\SWS\Category;
use App\Models\SWS\InventorySnapshot;
use App\Models\SWS\Location;
use App\Models\SWS\Warehouse;
use App\Models\SWS\Transaction;
use App\Models\SWS\TransactionLog;
use Illuminate\Support\Facades\DB;

class SWSRepository
{
    public function getAllItems()
    {
        return Item::with('category')->orderBy('item_created_at', 'desc')->get();
    }

    public function getItemById($id)
    {
        return Item::with('category')->find($id);
    }

    public function createItem(array $data)
    {
        // Generate item code before creating
        if (empty($data['item_code'])) {
            $data['item_code'] = $this->generateItemCode();
        }

        // Set current_stock equal to total_quantity for new items (backward compatibility)
        if (empty($data['item_current_stock']) && isset($data['item_total_quantity'])) {
            $data['item_current_stock'] = $data['item_total_quantity'];
        }

        // Set default max_stock if not provided
        if (empty($data['item_max_stock'])) {
            $data['item_max_stock'] = max(100, ($data['item_current_stock'] ?? $data['item_total_quantity']) * 2);
        }

        return Item::create($data);
    }

    public function updateItem($id, array $data)
    {
        $item = Item::find($id);
        if ($item) {
            // Remove total_quantity from update since we're using current_stock now
            if (isset($data['item_total_quantity'])) {
                unset($data['item_total_quantity']);
            }
            
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

    public function getAllLocations()
    {
        return Location::orderBy('loc_name')->get();
    }

    public function getInventoryFlowSummary()
    {
        $incoming = Transaction::whereIn('tra_type', ['inbound', 'drop_off'])->count();
        $outgoing = Transaction::whereIn('tra_type', ['outbound', 'pick_up'])->count();
        $transfers = Transaction::where('tra_type', 'transfer')->count();
        $lowStock = Item::where('item_current_stock', '<=', DB::raw('item_max_stock * 0.2'))
            ->where('item_current_stock', '>', 0)
            ->count();
        $totalItems = Item::count();
        return [
            'total_items' => $totalItems,
            'incoming' => $incoming,
            'outgoing' => $outgoing,
            'transfers' => $transfers,
            'low_stock' => $lowStock,
        ];
    }

    public function getRecentTransactions($limit = 50, $filters = [])
    {
        $query = Transaction::with(['item', 'fromLocation', 'toLocation', 'warehouse'])
            ->orderBy('tra_transaction_date', 'desc');
        if (!empty($filters['range'])) {
            $now = now();
            if ($filters['range'] === 'week') {
                $query->whereBetween('tra_transaction_date', [$now->startOfWeek(), $now->endOfWeek()]);
            } elseif ($filters['range'] === 'month') {
                $query->whereBetween('tra_transaction_date', [$now->startOfMonth(), $now->endOfMonth()]);
            } elseif ($filters['range'] === 'year') {
                $query->whereBetween('tra_transaction_date', [$now->startOfYear(), $now->endOfYear()]);
            }
        }
        if (!empty($filters['from'])) {
            $query->where('tra_transaction_date', '>=', $filters['from']);
        }
        if (!empty($filters['to'])) {
            $query->where('tra_transaction_date', '<=', $filters['to']);
        }
        return $query->limit($limit)->get()->map(function ($t) {
            $warehouseData = null;
            if ($t->warehouse) {
                $warehouseData = [
                    'ware_id' => $t->warehouse->ware_id,
                    'ware_name' => $t->warehouse->ware_name,
                ];
            } elseif ($t->fromLocation) {
                $w = Warehouse::where('ware_name', $t->fromLocation->loc_name)->first();
                if ($w) {
                    $warehouseData = [
                        'ware_id' => $w->ware_id,
                        'ware_name' => $w->ware_name,
                    ];
                }
            }
            return [
                'tra_id' => $t->tra_id,
                'type' => $t->tra_type,
                'quantity' => $t->tra_quantity,
                'status' => $t->tra_status,
                'transaction_date' => $t->tra_transaction_date,
                'reference_id' => $t->tra_reference_id,
                'item' => $t->item ? [
                    'item_id' => $t->item->item_id,
                    'item_code' => $t->item->item_code,
                    'item_name' => $t->item->item_name,
                    'category' => $t->item->category ? $t->item->category->cat_name : null,
                ] : null,
                'from_location' => $t->fromLocation ? [
                    'loc_id' => $t->fromLocation->loc_id,
                    'loc_name' => $t->fromLocation->loc_name,
                ] : (
                    $t->item && $t->item->item_stored_from ? [
                        'loc_id' => null,
                        'loc_name' => $t->item->item_stored_from,
                    ] : null
                ),
                'to_location' => $t->toLocation ? [
                    'loc_id' => $t->toLocation->loc_id,
                    'loc_name' => $t->toLocation->loc_name,
                ] : null,
                'warehouse' => $warehouseData,
            ];
        });
    }

    public function getInventoryFlowReportData($filters = [])
    {
        $query = Transaction::with(['item', 'fromLocation', 'toLocation', 'warehouse', 'logs'])
            ->orderBy('tra_transaction_date', 'desc');
        if (!empty($filters['range'])) {
            $now = now();
            if ($filters['range'] === 'week') {
                $query->whereBetween('tra_transaction_date', [$now->startOfWeek(), $now->endOfWeek()]);
            } elseif ($filters['range'] === 'month') {
                $query->whereBetween('tra_transaction_date', [$now->startOfMonth(), $now->endOfMonth()]);
            } elseif ($filters['range'] === 'year') {
                $query->whereBetween('tra_transaction_date', [$now->startOfYear(), $now->endOfYear()]);
            }
        }
        if (!empty($filters['from'])) {
            $query->where('tra_transaction_date', '>=', $filters['from']);
        }
        if (!empty($filters['to'])) {
            $query->where('tra_transaction_date', '<=', $filters['to']);
        }
        if (!empty($filters['warehouse_id'])) {
            $query->where('tra_warehouse_id', $filters['warehouse_id']);
        }
        return $query->get();
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
        
        // Calculate total value properly using current_stock
        $totalValue = Item::select(DB::raw('SUM(item_unit_price * item_current_stock) as total_value'))
            ->first()
            ->total_value ?? 0;
            
        $lowStockItems = Item::where('item_current_stock', '<=', DB::raw('item_max_stock * 0.2'))
            ->where('item_current_stock', '>', 0)
            ->count();
            
        $outOfStockItems = Item::where('item_current_stock', '<=', 0)->count();

        return [
            'total_items' => $totalItems,
            'total_value' => $totalValue,
            'low_stock_items' => $lowStockItems,
            'out_of_stock_items' => $outOfStockItems
        ];
    }

    public function getStockLevelsByCategory()
    {
        try {
            // Get categories with their items and calculate utilization based on current_stock and max_stock
            $categories = Category::with(['items' => function($query) {
                $query->select('item_category_id', 'item_current_stock', 'item_max_stock');
            }])->get();

            $stockLevels = [];
            
            foreach ($categories as $category) {
                $totalCurrentStock = $category->items->sum('item_current_stock');
                $totalMaxStock = $category->items->sum('item_max_stock');
                
                // Calculate utilization percentage based on actual max capacity
                $utilization = $totalMaxStock > 0 ? min(($totalCurrentStock / $totalMaxStock) * 100, 100) : 0;
                
                $stockLevels[] = [
                    'name' => $category->cat_name,
                    'utilization' => round($utilization, 2),
                    'total_quantity' => $totalCurrentStock,
                    'max_capacity' => $totalMaxStock
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
                ->select('item_id', 'item_code', 'item_name', 'item_stock_keeping_unit', 'item_category_id', 
                        'item_stored_from', 'item_unit_price', 'item_current_stock', 'item_max_stock',
                        'item_updated_at', 'item_created_at', 'item_item_type', 'item_is_fixed', 
                        'item_is_collateral', 'item_liquidity_risk_level', 'item_expiration_date', 
                        'item_warranty_end', 'item_description')
                ->orderBy('item_created_at', 'desc')
                ->get()
                ->map(function($item) {
                    // Calculate min stock based on max_stock (20% of max_stock)
                    $minStock = max(1, round($item->item_max_stock * 0.2));
                    
                    // Determine status based on current_stock and max_stock
                    if ($item->item_current_stock <= 0) {
                        $status = 'Out of Stock';
                        $statusClass = 'badge-error';
                    } elseif ($item->item_current_stock <= $minStock) {
                        $status = 'Low Stock';
                        $statusClass = 'badge-warning';
                    } else {
                        $status = 'In Stock';
                        $statusClass = 'badge-success';
                    }

                    // Calculate total value using current_stock
                    $unitPrice = $item->item_unit_price ?? 0;
                    $quantity = $item->item_current_stock ?? 0;
                    $totalValue = $unitPrice * $quantity;

                    // Calculate stock utilization
                    $utilization = $item->item_max_stock > 0 ? 
                        min(($item->item_current_stock / $item->item_max_stock) * 100, 100) : 0;

                    return [
                        'item_id' => $item->item_id,
                        'item_code' => $item->item_code,
                        'item_name' => $item->item_name,
                        'item_stock_keeping_unit' => $item->item_stock_keeping_unit,
                        'item_stored_from' => $item->item_stored_from,
                        'category' => $item->category ? $item->category->cat_name : 'Uncategorized',
                        'current_stock' => $item->item_current_stock,
                        'max_stock' => $item->item_max_stock,
                        'stock_utilization' => round($utilization, 2),
                        'unit_price' => $unitPrice,
                        'total_value' => $totalValue,
                        'min_stock' => $minStock,
                        'status' => $status,
                        'status_class' => $statusClass,
                        'last_updated' => $item->item_updated_at ? $item->item_updated_at->format('Y-m-d H:i:s') : ($item->item_created_at ? $item->item_created_at->format('Y-m-d H:i:s') : 'N/A'),
                        'item_created_at' => $item->item_created_at,
                        'item_item_type' => $item->item_item_type,
                        'item_is_fixed' => $item->item_is_fixed,
                        'item_is_collateral' => $item->item_is_collateral,
                        'item_liquidity_risk_level' => $item->item_liquidity_risk_level,
                        'item_expiration_date' => $item->item_expiration_date,
                        'item_warranty_end' => $item->item_warranty_end,
                        'item_description' => $item->item_description
                    ];
                });
        } catch (\Exception $e) {
            return [];
        }
    }


    // Generate item code: ITM + YYYY + MM + DD + 5 random alphanumeric characters
    private function generateItemCode()
    {
        $now = now();
        $year = $now->year;
        $month = str_pad($now->month, 2, '0', STR_PAD_LEFT);
        $day = str_pad($now->day, 2, '0', STR_PAD_LEFT);
        
        // Generate 5 random alphanumeric characters
        $chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $randomPart = '';
        for ($i = 0; $i < 5; $i++) {
            $randomPart .= $chars[rand(0, strlen($chars) - 1)];
        }
        
        $itemCode = "ITM{$year}{$month}{$day}{$randomPart}";
        
        // Ensure uniqueness
        $counter = 1;
        while (Item::where('item_code', $itemCode)->exists() && $counter <= 10) {
            $randomPart = '';
            for ($i = 0; $i < 5; $i++) {
                $randomPart .= $chars[rand(0, strlen($chars) - 1)];
            }
            $itemCode = "ITM{$year}{$month}{$day}{$randomPart}";
            $counter++;
        }
        
        return $itemCode;
    }
}