<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SWS\Warehouse;
use App\Services\SWSService;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class SWSController extends Controller
{
    protected $swsService;

    public function __construct(SWSService $swsService)
    {
        $this->swsService = $swsService;
    }

    public function getInventoryFlow()
    {
        return response()->json([
            'message' => 'SWS Inventory Flow data',
            'data' => []
        ]);
    }

    public function getDigitalInventory()
    {
        return response()->json([
            'message' => 'SWS Digital Inventory data',
            'data' => []
        ]);
    }

    // Warehouse methods (existing)
    public function warehouses(Request $request)
    {
        $items = Warehouse::query()->orderBy('ware_name')->get();
        return response()->json(['data' => $items]);
    }

    public function showWarehouse(string $id)
    {
        $item = Warehouse::find($id);
        if (!$item) {
            return response()->json(['error' => 'Not found'], 404);
        }
        return response()->json(['data' => $item]);
    }

    public function createWarehouse(Request $request)
    {
        $validated = $request->validate([
            'ware_name' => ['required', 'string', 'max:100'],
            'ware_location' => ['nullable', 'string', 'max:255'],
            'ware_capacity' => ['nullable', 'integer', 'min:0'],
            'ware_status' => ['nullable', Rule::in(['active', 'inactive', 'maintenance'])],
            'ware_zone_type' => ['nullable', Rule::in(['liquid', 'illiquid', 'climate_controlled', 'general'])],
            'ware_supports_fixed_items' => ['nullable', 'boolean'],
        ]);

        $capacity = $validated['ware_capacity'] ?? 0;
        $used = 0;
        $free = max(($capacity - $used), 0);
        $util = $capacity > 0 ? round(($used / $capacity) * 100, 2) : 0.00;

        // Generate unique ware_id like WH123456
        $wareId = $this->generateUniqueWareId();

        $item = Warehouse::create(array_merge($validated, [
            'ware_id' => $wareId,
            'ware_capacity' => $capacity,
            'ware_capacity_used' => $used,
            'ware_capacity_free' => $free,
            'ware_utilization' => $util,
        ]));

        return response()->json(['data' => $item], 201);
    }

    public function updateWarehouse(Request $request, string $id)
    {
        $item = Warehouse::find($id);
        if (!$item) {
            return response()->json(['error' => 'Not found'], 404);
        }

        $validated = $request->validate([
            'ware_name' => ['sometimes', 'string', 'max:100'],
            'ware_location' => ['sometimes', 'nullable', 'string', 'max:255'],
            'ware_capacity' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'ware_capacity_used' => ['sometimes', 'nullable', 'integer', 'min:0'],
            'ware_status' => ['sometimes', Rule::in(['active', 'inactive', 'maintenance'])],
            'ware_zone_type' => ['sometimes', Rule::in(['liquid', 'illiquid', 'climate_controlled', 'general'])],
            'ware_supports_fixed_items' => ['sometimes', 'boolean'],
        ]);

        $item->fill($validated);

        $capacity = $item->ware_capacity ?? 0;
        $used = $item->ware_capacity_used ?? 0;
        $free = max(($capacity - $used), 0);
        $util = $capacity > 0 ? round(($used / $capacity) * 100, 2) : 0.00;

        $item->ware_capacity_free = $free;
        $item->ware_utilization = $util;
        $item->save();

        return response()->json(['data' => $item]);
    }

    public function deleteWarehouse(string $id)
    {
        $item = Warehouse::find($id);
        if (!$item) {
            return response()->json(['error' => 'Not found'], 404);
        }
        $item->delete();
        return response()->json(['deleted' => true]);
    }

    protected function generateUniqueWareId(): string
    {
        do {
            $code = 'WH' . str_pad((string)random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        } while (Warehouse::where('ware_id', $code)->exists());
        return $code;
    }

    // Digital Inventory methods
    public function getInventoryStats()
    {
        $result = $this->swsService->getInventoryStats();
        return response()->json($result, $result['success'] ? 200 : 500);
    }

    public function getStockLevelsByCategory()
    {
        $result = $this->swsService->getStockLevelsByCategory();
        return response()->json($result, $result['success'] ? 200 : 500);
    }

    public function getItems()
    {
        $result = $this->swsService->getItemsWithStockInfo();
        return response()->json($result, $result['success'] ? 200 : 500);
    }

    public function getItem($id)
    {
        $result = $this->swsService->getItemById($id);
        return response()->json($result, $result['success'] ? 200 : 404);
    }

    public function createItem(Request $request)
    {
        $validated = $request->validate([
            'item_name' => ['required', 'string', 'max:255'],
            'item_description' => ['nullable', 'string'],
            'item_stock_keeping_unit' => ['nullable', 'string', 'max:100', 'unique:sws.sws_items,item_stock_keeping_unit'],
            'item_category_id' => ['nullable', 'integer', 'exists:sws.sws_categories,cat_id'],
            'item_item_type' => ['required', Rule::in(['liquid', 'illiquid', 'hybrid'])],
            'item_is_fixed' => ['nullable', 'boolean'],
            'item_expiration_date' => ['nullable', 'date'],
            'item_warranty_end' => ['nullable', 'date'],
            'item_unit_price' => ['nullable', 'numeric', 'min:0'],
            'item_total_quantity' => ['required', 'integer', 'min:0'],
            'item_liquidity_risk_level' => ['required', Rule::in(['high', 'medium', 'low'])],
            'item_is_collateral' => ['nullable', 'boolean'],
        ]);

        $result = $this->swsService->createItem($validated);
        return response()->json($result, $result['success'] ? 201 : 500);
    }

    public function updateItem(Request $request, $id)
    {
        $validated = $request->validate([
            'item_name' => ['sometimes', 'string', 'max:255'],
            'item_description' => ['sometimes', 'nullable', 'string'],
            'item_stock_keeping_unit' => ['sometimes', 'nullable', 'string', 'max:100'],
            'item_category_id' => ['sometimes', 'nullable', 'integer', 'exists:sws.sws_categories,cat_id'],
            'item_item_type' => ['sometimes', Rule::in(['liquid', 'illiquid', 'hybrid'])],
            'item_is_fixed' => ['sometimes', 'boolean'],
            'item_expiration_date' => ['sometimes', 'nullable', 'date'],
            'item_warranty_end' => ['sometimes', 'nullable', 'date'],
            'item_unit_price' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'item_total_quantity' => ['sometimes', 'integer', 'min:0'],
            'item_liquidity_risk_level' => ['sometimes', Rule::in(['high', 'medium', 'low'])],
            'item_is_collateral' => ['sometimes', 'boolean'],
        ]);

        $result = $this->swsService->updateItem($id, $validated);
        return response()->json($result, $result['success'] ? 200 : 500);
    }

    public function deleteItem($id)
    {
        $result = $this->swsService->deleteItem($id);
        return response()->json($result, $result['success'] ? 200 : 500);
    }

    public function getCategories()
    {
        $result = $this->swsService->getAllCategories();
        return response()->json($result, $result['success'] ? 200 : 500);
    }
}