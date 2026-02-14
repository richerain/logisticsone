<?php

namespace App\Http\Controllers;

use App\Models\SWS\Item;
use App\Models\SWS\IncomingAsset;
use App\Models\PSM\PurchaseProduct;
use App\Models\SWS\Location;
use App\Models\SWS\Transaction;
use App\Models\SWS\Warehouse;
use App\Services\SWSService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class SWSController extends Controller
{
    protected $swsService;

    public function __construct(SWSService $swsService)
    {
        $this->swsService = $swsService;
    }

    public function forecastDemand(Request $request)
    {
        $validated = $request->validate([
            'item_id' => ['nullable', 'integer'],
            'sku' => ['nullable', 'string', 'max:255'],
            'series' => ['nullable', 'array'],
            'series.*.timestamp' => ['required_with:series', 'date'],
            'series.*.value' => ['required_with:series', 'numeric'],
            'horizon' => ['nullable', 'integer', 'min:1', 'max:365'],
            'cadence' => ['nullable', Rule::in(['D', 'W', 'M'])],
        ]);
        $horizon = $validated['horizon'] ?? 30;
        $cadence = $validated['cadence'] ?? 'D';
        $itemId = $validated['item_id'] ?? null;
        $sku = $validated['sku'] ?? null;

        $series = $validated['series'] ?? [];
        try {
            if (empty($series) && $itemId) {
                // Build a naive daily demand series from transactions (outgoing quantities)
                $rows = DB::connection('sws')->table('sws_transactions')
                    ->selectRaw('DATE(tran_created_at) as d, SUM(CASE WHEN tran_type IN ("out","issued","dispatch") THEN tran_qty ELSE 0 END) as qty')
                    ->where('tran_item_id', $itemId)
                    ->groupBy('d')
                    ->orderBy('d', 'asc')
                    ->get();
                foreach ($rows as $r) {
                    $series[] = ['timestamp' => $r->d, 'value' => (float) ($r->qty ?? 0)];
                }
            }
        } catch (\Throwable $e) {
            Log::warning('Failed to build local series for demand forecast: '.$e->getMessage());
        }

        if (empty($series)) {
            return response()->json([
                'success' => false,
                'message' => 'No time series data available. Provide "series" or ensure transactions exist for this item.',
            ], 422);
        }

        $endpoint = config('services.huggingface.forecast_endpoint');
        $token = config('services.huggingface.api_token');
        if (! $endpoint || ! $token) {
            return response()->json([
                'success' => false,
                'message' => 'Forecasting service not configured. Set HUGGINGFACE_API_TOKEN and HF_FORECAST_ENDPOINT.',
            ], 500);
        }

        try {
            $payload = [
                'series' => $series,
                'horizon' => $horizon,
                'cadence' => $cadence,
                'metadata' => array_filter([
                    'item_id' => $itemId,
                    'sku' => $sku,
                ]),
            ];
            $timeout = (int) config('services.huggingface.timeout', 12);
            $resp = Http::withToken($token)->timeout($timeout)->post($endpoint, $payload);
            $ok = $resp->ok();
            $body = $resp->json();

            if (! $ok) {
                return response()->json([
                    'success' => false,
                    'message' => 'Forecasting request failed',
                    'status' => $resp->status(),
                    'error' => $body,
                ], 502);
            }

            $preds = [];
            if (isset($body['predictions']) && is_array($body['predictions'])) {
                $preds = $body['predictions'];
            } elseif (isset($body['forecast']) && is_array($body['forecast'])) {
                $preds = $body['forecast'];
            } elseif (is_array($body)) {
                $preds = $body;
            }

            return response()->json([
                'success' => true,
                'item_id' => $itemId,
                'sku' => $sku,
                'horizon' => $horizon,
                'cadence' => $cadence,
                'predictions' => $preds,
            ]);
        } catch (\Throwable $e) {
            Log::error('Demand forecast error: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Forecasting service error',
            ], 500);
        }
    }

    public function getInventoryFlow()
    {
        $filters = [
            'range' => request()->get('range'),
            'from' => request()->get('from'),
            'to' => request()->get('to'),
            'warehouse_id' => request()->get('warehouse_id'),
        ];
        $result = $this->swsService->getInventoryFlow($filters);

        return response()->json($result, $result['success'] ? 200 : 500);
    }

    public function getDigitalInventory()
    {
        return response()->json([
            'message' => 'SWS Digital Inventory data',
            'data' => [],
        ]);
    }

    // Warehouse methods (existing)
    public function warehouses(Request $request)
    {
        $warehouses = Warehouse::query()
            ->orderBy('ware_name')
            ->get()
            ->map(function ($w) {
                $used = Item::where('item_stored_from', $w->ware_name)->sum('item_current_stock');
                $capacity = $w->ware_capacity ?? 0;
                $free = max(($capacity - $used), 0);
                $util = $capacity > 0 ? round(($used / $capacity) * 100, 2) : 0.00;
                $w->ware_capacity_used = (int) $used;
                $w->ware_capacity_free = (int) $free;
                $w->ware_utilization = $util;

                return $w;
            });

        return response()->json(['data' => $warehouses]);
    }

    public function getIncomingAssets(Request $request)
    {
        try {
            $psmProducts = PurchaseProduct::query()->orderByDesc('purcprod_date')->get();

            foreach ($psmProducts as $p) {
                $existing = IncomingAsset::where('sws_purcprod_id', $p->purcprod_id)->first();
                $payload = [
                    'sws_purcprod_id' => $p->purcprod_id,
                    'sws_purcprod_prod_id' => $p->purcprod_prod_id,
                    'sws_purcprod_prod_name' => $p->purcprod_prod_name,
                    'sws_purcprod_prod_price' => $p->purcprod_prod_price,
                    'sws_purcprod_prod_unit' => $p->purcprod_prod_unit,
                    'sws_purcprod_prod_type' => $p->purcprod_prod_type,
                    'sws_purcprod_status' => $p->purcprod_status,
                    'sws_purcprod_date' => $p->purcprod_date,
                    'sws_purcprod_warranty' => $p->purcprod_warranty,
                    'sws_purcprod_expiration' => $p->purcprod_expiration,
                    'sws_purcprod_desc' => $p->purcprod_desc,
                ];

                if ($existing) {
                    // Preserve inventory flag if present
                    $existing->fill($payload);
                    $existing->save();
                } else {
                    // Default inventory flag to 'no'
                    $payload['sws_purcprod_inventory'] = 'no';
                    IncomingAsset::create($payload);
                }
            }

            $assets = IncomingAsset::orderByDesc('created_at')->get();

            return response()->json([
                'success' => true,
                'data' => $assets,
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to get incoming assets: '.$e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'success' => false,
                'message' => 'Failed to load incoming assets',
            ], 500);
        }
    }

    public function deleteIncomingAsset($id)
    {
        try {
            $deleted = IncomingAsset::where('sws_purcprod_id', $id)->delete();
            return response()->json([
                'success' => true,
                'deleted' => $deleted,
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to delete incoming asset: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete incoming asset',
            ], 500);
        }
    }

    public function updateIncomingAsset(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'sws_purcprod_inventory' => ['required', Rule::in(['yes', 'no'])],
            ]);
            $updated = IncomingAsset::where('sws_purcprod_id', $id)
                ->update([
                    'sws_purcprod_inventory' => $validated['sws_purcprod_inventory'],
                    'updated_at' => now(),
                ]);
            return response()->json([
                'success' => true,
                'updated' => $updated,
            ]);
        } catch (\Throwable $e) {
            Log::error('Failed to update incoming asset: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update incoming asset',
            ], 500);
        }
    }

    public function showWarehouse(string $id)
    {
        $item = Warehouse::find($id);
        if (! $item) {
            return response()->json(['error' => 'Not found'], 404);
        }

        return response()->json(['data' => $item]);
    }

    public function createWarehouse(Request $request)
    {
        try {
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
            $wareId = $request->input('ware_id');
            if (! $wareId) {
                do {
                    $datePart = now()->format('Ymd');
                    $randomTail = sprintf('%d%s%d%s%d', random_int(0, 9), chr(random_int(65, 90)), random_int(0, 9), chr(random_int(65, 90)), random_int(0, 9));
                    $wareId = 'WRHS'.$datePart.$randomTail;
                } while (Warehouse::where('ware_id', $wareId)->exists());
            }

            $item = Warehouse::create(array_merge($validated, [
                'ware_id' => $wareId,
                'ware_capacity' => $capacity,
                'ware_capacity_used' => $used,
                'ware_capacity_free' => $free,
                'ware_utilization' => $util,
            ]));

            return response()->json(['data' => $item], 201);
        } catch (\Exception $e) {
            Log::error('Create Warehouse Error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to create warehouse'], 500);
        }
    }

    public function updateWarehouse(Request $request, string $id)
    {
        $item = Warehouse::find($id);
        if (! $item) {
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
        if (! $item) {
            return response()->json(['error' => 'Not found'], 404);
        }
        $item->delete();

        return response()->json(['deleted' => true]);
    }

    public function transferItem(Request $request)
    {
        $validated = $request->validate([
            'item_id' => ['required', 'integer', 'exists:sws.sws_items,item_id'],
            'location_to_id' => ['required', 'integer', 'exists:sws.sws_locations,loc_id'],
            'transfer_units' => ['required', 'integer', 'min:1'],
        ]);

        DB::connection('sws')->beginTransaction();
        try {
            $item = Item::find($validated['item_id']);
            $units = (int) $validated['transfer_units'];
            $current = (int) ($item->item_current_stock ?? 0);
            if ($units > $current) {
                DB::connection('sws')->rollBack();

                return response()->json(['success' => false, 'message' => 'Transfer units exceed current stock', 'data' => null], 422);
            }

            $toLocation = Location::find($validated['location_to_id']);
            $fromLocation = null;
            $originName = $item->item_stored_from;
            if ($originName) {
                $fromLocation = Location::where('loc_name', $originName)->first();
            }
            $randomTail = sprintf('%d%s%d%s%d', random_int(0, 9), chr(random_int(65, 90)), random_int(0, 9), chr(random_int(65, 90)), random_int(0, 9));
            $referenceId = 'RF'.date('Ymd').$randomTail;

            $transaction = Transaction::create([
                'tra_item_id' => $item->item_id,
                'tra_type' => 'transfer',
                'tra_quantity' => $units,
                'tra_from_location_id' => $fromLocation ? $fromLocation->loc_id : null,
                'tra_to_location_id' => $toLocation ? $toLocation->loc_id : null,
                'tra_warehouse_id' => null,
                'tra_transaction_date' => now(),
                'tra_reference_id' => $referenceId,
                'tra_status' => 'completed',
                'tra_notes' => null,
            ]);

            $item->item_current_stock = $current - $units;
            $item->save();

            DB::connection('sws')->commit();

            return response()->json(['success' => true, 'data' => $transaction, 'message' => 'Item transferred successfully'], 201);
        } catch (\Exception $e) {
            DB::connection('sws')->rollBack();
            Log::error('SWS transfer failed: '.$e->getMessage());

            return response()->json(['success' => false, 'message' => 'Transfer failed', 'data' => null], 500);
        }
    }

    public function generateInventoryFlowReport()
    {
        $filters = [
            'range' => request()->get('range'),
            'from' => request()->get('from'),
            'to' => request()->get('to'),
            'warehouse_id' => request()->get('warehouse_id'),
        ];
        $result = $this->swsService->getInventoryFlowReportData($filters);
        if (! $result['success']) {
            return response()->json($result, 500);
        }
        $data = $result['data'];
        $pdf = Pdf::loadView('generate-reports.inventory-flow-reports', [
            'transactions' => $data,
            'filters' => $filters,
        ])->setPaper('A4', 'portrait');

        return $pdf->download('inventory-flow-report.pdf');
    }

    public function generateDigitalInventoryReport(Request $request)
    {
        try {
            $range = $request->query('range');
            $from = $request->query('from');
            $to = $request->query('to');

            $query = Item::with('category');
            if ($range === 'week') {
                $start = now()->startOfWeek();
                $end = now()->endOfWeek();
                $query->whereBetween('item_created_at', [$start, $end]);
            } elseif ($range === 'month') {
                $start = now()->startOfMonth();
                $end = now()->endOfMonth();
                $query->whereBetween('item_created_at', [$start, $end]);
            } elseif ($range === 'year') {
                $start = now()->startOfYear();
                $end = now()->endOfYear();
                $query->whereBetween('item_created_at', [$start, $end]);
            } elseif ($from && $to) {
                $query->whereBetween('item_created_at', [$from, $to]);
            }

            $items = $query->orderBy('item_created_at', 'desc')->get();
            $payload = [
                'items' => $items,
                'from' => $from,
                'to' => $to,
                'generated_at' => now()->toDateTimeString(),
            ];

            if ($request->query('format') === 'pdf') {
                $pdf = Pdf::loadView('generate-reports.digital-inventory-report', $payload);

                return $pdf->download('digital-inventory-report.pdf');
            }

            return view('generate-reports.digital-inventory-report', $payload);
        } catch (\Exception $e) {
            Log::error('Digital Inventory report failed: '.$e->getMessage());

            return response()->json(['success' => false, 'message' => 'Failed to generate report'], 500);
        }
    }

    public function deleteTransaction($id)
    {
        try {
            $t = Transaction::find($id);
            if (! $t) {
                return response()->json(['success' => false, 'message' => 'Transaction not found'], 404);
            }
            $t->delete();

            return response()->json(['success' => true, 'message' => 'Transaction deleted']);
        } catch (\Exception $e) {
            Log::error('Delete transaction failed: '.$e->getMessage());

            return response()->json(['success' => false, 'message' => 'Failed to delete transaction'], 500);
        }
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
            'item_stock_keeping_unit' => ['nullable', 'string', 'max:100'],
            'item_category_id' => ['nullable', 'string', 'exists:sws.sws_categories,cat_id'],
            'item_stored_from' => ['nullable', 'string', 'max:100'],
            'item_item_type' => ['required', Rule::in(['liquid', 'illiquid', 'hybrid'])],
            'item_is_fixed' => ['nullable', 'boolean'],
            'item_expiration_date' => ['nullable', 'string', 'max:100'],
            'item_warranty_end' => ['nullable', 'string', 'max:100'],
            'item_unit_price' => ['nullable', 'numeric', 'min:0'],
            'item_current_stock' => ['required', 'integer', 'min:0'],
            'item_max_stock' => ['nullable', 'integer', 'min:1'],
            'item_liquidity_risk_level' => ['required', Rule::in(['high', 'medium', 'low'])],
            'item_is_collateral' => ['nullable', 'boolean'],
            'item_code' => ['nullable', 'string', 'max:20', 'unique:sws.sws_items,item_code'],
            'psm_purchase_id' => ['nullable', 'string'],
            'psm_item_index' => ['nullable', 'integer'],
        ]);

        $result = $this->swsService->createItem($validated);

        return response()->json($result, $result['success'] ? 201 : 500);
    }

    public function updateItem(Request $request, $id)
    {
        $validated = $request->validate([
            'item_name' => ['sometimes', 'string', 'max:255'],
            'item_description' => ['sometimes', 'nullable', 'string'],
            'item_category_id' => ['sometimes', 'nullable', 'string', 'exists:sws.sws_categories,cat_id'],
            'item_stored_from' => ['sometimes', 'nullable', 'string', 'max:100'],
            'item_item_type' => ['sometimes', Rule::in(['liquid', 'illiquid', 'hybrid'])],
            'item_is_fixed' => ['sometimes', 'boolean'],
            'item_expiration_date' => ['sometimes', 'nullable', 'string', 'max:100'],
            'item_warranty_end' => ['sometimes', 'nullable', 'string', 'max:100'],
            'item_unit_price' => ['sometimes', 'nullable', 'numeric', 'min:0'],
            'item_current_stock' => ['sometimes', 'integer', 'min:0'],
            'item_max_stock' => ['sometimes', 'integer', 'min:1'],
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

    public function createCategory(Request $request)
    {
        $validated = $request->validate([
            'cat_name' => ['required', 'string', 'max:100', 'unique:sws.sws_categories,cat_name'],
            'cat_description' => ['nullable', 'string'],
        ]);

        $result = $this->swsService->createCategory($validated);

        return response()->json($result, $result['success'] ? 201 : 500);
    }

    public function updateCategory(Request $request, $id)
    {
        $validated = $request->validate([
            'cat_name' => ['sometimes', 'string', 'max:100'],
            'cat_description' => ['nullable', 'string'],
        ]);

        $result = $this->swsService->updateCategory($id, $validated);

        return response()->json($result, $result['success'] ? 200 : 500);
    }

    public function deleteCategory($id)
    {
        $result = $this->swsService->deleteCategory($id);

        return response()->json($result, $result['success'] ? 200 : 500);
    }

    public function createLocation(Request $request)
    {
        $validated = $request->validate([
            'loc_id' => ['required', 'string', 'max:25', 'unique:sws.sws_locations,loc_id'],
            'loc_name' => ['required', 'string', 'max:100'],
            'loc_type' => ['required', Rule::in(['warehouse', 'storage_room', 'office', 'facility', 'drop_point', 'bin', 'department', 'room'])],
            'loc_zone_type' => ['required', Rule::in(['liquid', 'illiquid', 'climate_controlled', 'general'])],
            'loc_capacity' => ['nullable', 'integer', 'min:0'],
            'loc_supports_fixed_items' => ['required', 'boolean'],
            'loc_parent_id' => ['nullable', 'string', 'exists:sws.sws_locations,loc_id'],
            'loc_is_active' => ['nullable', 'boolean'],
        ]);

        $result = $this->swsService->createLocation($validated);

        return response()->json($result, $result['success'] ? 201 : 500);
    }

    public function getLocations()
    {
        $result = $this->swsService->getAllLocations();

        return response()->json($result, $result['success'] ? 200 : 500);
    }

    public function updateLocation(Request $request, $id)
    {
        $validated = $request->validate([
            'loc_name' => ['sometimes', 'string', 'max:100'],
            'loc_type' => ['sometimes', 'string', 'max:50'],
            'loc_zone_type' => ['sometimes', 'string', 'max:50'],
            'loc_capacity' => ['sometimes', 'integer', 'min:1'],
            'loc_supports_fixed_items' => ['sometimes', 'boolean'],
            'loc_is_active' => ['sometimes', 'boolean'],
        ]);

        $result = $this->swsService->updateLocation($id, $validated);

        return response()->json($result, $result['success'] ? 200 : 500);
    }

    public function deleteLocation($id)
    {
        $result = $this->swsService->deleteLocation($id);

        return response()->json($result, $result['success'] ? 200 : 500);
    }
}
