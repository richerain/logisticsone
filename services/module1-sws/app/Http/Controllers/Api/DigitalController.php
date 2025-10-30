<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Digital;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class DigitalController extends Controller
{
    // Get all Digital Inventory records
    public function index()
    {
        try {
            $stockRecords = Digital::orderBy('created_at', 'desc')->get();
            
            return response()->json([
                'success' => true,
                'data' => $stockRecords,
                'message' => 'Digital Inventory records retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching Digital Inventory records: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve Digital Inventory records',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Create new Digital Inventory record
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'item_name' => 'required|string|max:255',
                'type' => 'required|string|in:Equipment,Supplies,Furniture',
                'units' => 'required|string|max:50',
                'available_item' => 'required|integer|min:0',
                'vendor_id' => 'nullable|integer',
                'vendor_name' => 'nullable|string|max:255',
                'quote_id' => 'nullable|string|max:50',
                'quote_code' => 'nullable|string|max:50',
                'purchase_price' => 'nullable|numeric|min:0',
                'warranty_info' => 'nullable|string'
            ]);

            // Generate Stock ID
            $stockId = Digital::generateStockId();

            // Determine status based on available items
            $status = $this->determineStatus($validatedData['available_item']);

            $stockRecord = Digital::create([
                'stock_id' => $stockId,
                'item_name' => $validatedData['item_name'],
                'type' => $validatedData['type'],
                'units' => $validatedData['units'],
                'available_item' => $validatedData['available_item'],
                'status' => $status,
                'vendor_id' => $validatedData['vendor_id'] ?? null,
                'vendor_name' => $validatedData['vendor_name'] ?? null,
                'quote_id' => $validatedData['quote_id'] ?? null,
                'quote_code' => $validatedData['quote_code'] ?? null,
                'purchase_price' => $validatedData['purchase_price'] ?? null,
                'warranty_info' => $validatedData['warranty_info'] ?? null
            ]);

            return response()->json([
                'success' => true,
                'data' => $stockRecord,
                'message' => 'Digital Inventory record created successfully'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating Digital Inventory record: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create Digital Inventory record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Get single Digital Inventory record
    public function show($id)
    {
        try {
            $stockRecord = Digital::find($id);
            
            if (!$stockRecord) {
                return response()->json([
                    'success' => false,
                    'message' => 'Digital Inventory record not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $stockRecord,
                'message' => 'Digital Inventory record retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching Digital Inventory record: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve Digital Inventory record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Update Digital Inventory record
    public function update(Request $request, $id)
    {
        try {
            $stockRecord = Digital::find($id);
            
            if (!$stockRecord) {
                return response()->json([
                    'success' => false,
                    'message' => 'Digital Inventory record not found'
                ], 404);
            }

            $validatedData = $request->validate([
                'item_name' => 'sometimes|string|max:255',
                'type' => 'sometimes|string|in:Equipment,Supplies,Furniture',
                'units' => 'sometimes|string|max:50',
                'available_item' => 'sometimes|integer|min:0',
                'vendor_id' => 'nullable|integer',
                'vendor_name' => 'nullable|string|max:255',
                'quote_id' => 'nullable|string|max:50',
                'quote_code' => 'nullable|string|max:50',
                'purchase_price' => 'nullable|numeric|min:0',
                'warranty_info' => 'nullable|string'
            ]);

            // Update status based on available items if available_item is being updated
            if (isset($validatedData['available_item'])) {
                $validatedData['status'] = $this->determineStatus($validatedData['available_item']);
            }

            $stockRecord->update($validatedData);

            return response()->json([
                'success' => true,
                'data' => $stockRecord,
                'message' => 'Digital Inventory record updated successfully'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating Digital Inventory record: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update Digital Inventory record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Delete Digital Inventory record
    public function destroy($id)
    {
        try {
            $stockRecord = Digital::find($id);
            
            if (!$stockRecord) {
                return response()->json([
                    'success' => false,
                    'message' => 'Digital Inventory record not found'
                ], 404);
            }

            $stockRecord->delete();

            return response()->json([
                'success' => true,
                'message' => 'Digital Inventory record deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting Digital Inventory record: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete Digital Inventory record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Get received quotes from PSM for dropdown
    public function getReceivedQuotes()
    {
        try {
            Log::info('Fetching received quotes from PSM module...');
            
            // Use caching to reduce load on PSM service (cache for 5 minutes)
            $receivedQuotes = Cache::remember('received_quotes', 300, function () {
                // Fetch quotes from PSM module through the gateway
                // Note: SWS service (port 8002) calls Gateway (port 8001) which routes to PSM (port 8003)
                $response = Http::timeout(10)->get('http://localhost:8001/api/psm/quotes');
                
                Log::info('PSM Quotes API Response Status: ' . $response->status());
                
                if (!$response->successful()) {
                    Log::error('Failed to fetch quotes from PSM module. Status: ' . $response->status());
                    // Return empty array instead of throwing exception for graceful degradation
                    return [];
                }

                $quotesData = $response->json();
                Log::info('PSM Quotes Data structure:', ['keys' => array_keys($quotesData)]);
                
                if (!$quotesData['success'] ?? false) {
                    Log::error('PSM API returned error: ' . ($quotesData['message'] ?? 'Unknown error'));
                    return [];
                }

                // Filter quotes with status "received" or "approved" and ensure they have required data
                $receivedQuotes = collect($quotesData['data'] ?? [])
                    ->filter(function ($quote) {
                        $hasValidStatus = isset($quote['status']) && in_array($quote['status'], ['received', 'approved']);
                        $hasItemName = isset($quote['item_name']) && !empty($quote['item_name']);
                        $hasQuantity = isset($quote['quantity']) && $quote['quantity'] > 0;
                        
                        return $hasValidStatus && $hasItemName && $hasQuantity;
                    })
                    ->map(function ($quote) {
                        Log::info('Processing quote for digital inventory:', [
                            'quote_id' => $quote['quote_id'] ?? null,
                            'item_name' => $quote['item_name'] ?? null,
                            'status' => $quote['status'] ?? null
                        ]);
                        
                        return [
                            'quote_id' => $quote['quote_id'] ?? null,
                            'quote_code' => $quote['quote_code'] ?? ($quote['quote_id'] ?? 'N/A'),
                            'item_name' => $quote['item_name'] ?? 'Unknown Item',
                            'quantity' => $quote['quantity'] ?? 0,
                            'units' => $this->mapUnits($quote['units'] ?? 'pcs'),
                            'unit_price' => $quote['unit_price'] ?? 0,
                            'vendor_id' => $quote['ven_id'] ?? null,
                            'vendor_name' => $this->getVendorName($quote),
                            'vendor_type' => $this->getVendorType($quote),
                            'warranty_info' => $this->extractWarrantyInfo($quote),
                            'delivery_lead_time' => $quote['delivery_lead_time'] ?? 0,
                            'total_quote' => $quote['total_quote'] ?? 0
                        ];
                    })
                    ->values()
                    ->toArray();

                Log::info('Successfully processed received quotes', ['count' => count($receivedQuotes)]);
                return $receivedQuotes;
            });

            return response()->json([
                'success' => true,
                'data' => $receivedQuotes,
                'message' => 'Received quotes retrieved successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error fetching received quotes: ' . $e->getMessage());
            Log::error('Error trace: ' . $e->getTraceAsString());
            
            // Return empty array for graceful degradation
            return response()->json([
                'success' => true, // Still return success=true to not break frontend
                'message' => 'No quotes available from vendor system',
                'data' => []
            ]);
        }
    }

    // Get Digital Inventory statistics
    public function getStats()
    {
        try {
            $totalRecords = Digital::count();
            $onStock = Digital::where('status', 'onstock')->count();
            $lowStock = Digital::where('status', 'lowstock')->count();
            $outOfStock = Digital::where('status', 'outofstock')->count();
            $totalItems = Digital::sum('available_item');

            return response()->json([
                'success' => true,
                'data' => [
                    'total_records' => $totalRecords,
                    'on_stock' => $onStock,
                    'low_stock' => $lowStock,
                    'out_of_stock' => $outOfStock,
                    'total_items' => $totalItems
                ],
                'message' => 'Digital Inventory statistics retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching Digital Inventory statistics: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve Digital Inventory statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Search Digital Inventory records
    public function search(Request $request)
    {
        try {
            $query = Digital::query();

            // Search by multiple criteria
            if ($request->has('search') && !empty($request->search)) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('stock_id', 'like', '%' . $searchTerm . '%')
                      ->orWhere('item_name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('type', 'like', '%' . $searchTerm . '%')
                      ->orWhere('units', 'like', '%' . $searchTerm . '%')
                      ->orWhere('vendor_name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('quote_code', 'like', '%' . $searchTerm . '%');
                });
            }

            if ($request->has('status') && !empty($request.status)) {
                $query->where('status', $request->status);
            }

            if ($request->has('type') && !empty($request.type)) {
                $query->where('type', $request->type);
            }

            $results = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $results,
                'message' => 'Digital Inventory records search completed successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error searching Digital Inventory records: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to search Digital Inventory records',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Determine stock status based on available items
    private function determineStatus($availableItem)
    {
        if ($availableItem <= 0) {
            return 'outofstock';
        } elseif ($availableItem < 10) { // Low stock threshold
            return 'lowstock';
        } else {
            return 'onstock';
        }
    }

    // Extract warranty information from quote
    private function extractWarrantyInfo($quote)
    {
        // Check if there's warranty information in the quote or vendor data
        if (isset($quote['notes']) && !empty($quote['notes'])) {
            return $quote['notes'];
        }
        
        if (isset($quote['warranty_info']) && !empty($quote['warranty_info'])) {
            return $quote['warranty_info'];
        }
        
        // Default warranty information
        return "Standard warranty applies";
    }

    // Map units from purchase to inventory units
    private function mapUnits($purchaseUnits)
    {
        $unitMapping = [
            'pcs' => 'pcs',
            'kg' => 'kg',
            'g' => 'g',
            'L' => 'L',
            'ml' => 'ml',
            'boxes' => 'boxes',
            'units' => 'units',
            'sets' => 'sets'
        ];
        
        return $unitMapping[$purchaseUnits] ?? 'pcs';
    }

    // Get vendor name from quote data
    private function getVendorName($quote)
    {
        if (isset($quote['vendor']['ven_name'])) {
            return $quote['vendor']['ven_name'];
        }
        
        if (isset($quote['vendor_name'])) {
            return $quote['vendor_name'];
        }
        
        if (isset($quote['vendor']) && is_string($quote['vendor'])) {
            return $quote['vendor'];
        }
        
        return 'Unknown Vendor';
    }

    // Get vendor type from quote data
    private function getVendorType($quote)
    {
        if (isset($quote['vendor']['vendor_type'])) {
            return $quote['vendor']['vendor_type'];
        }
        
        // Default to Equipment if not specified
        return 'Equipment';
    }

    // Sync stock from GRN (Goods Received Note)
    public function syncFromGrn(Request $request, $grnId)
    {
        try {
            // This method would sync data from Inventory Flow (GRN) to Digital Inventory
            // Implementation depends on your GRN data structure
            Log::info("Syncing digital inventory from GRN: {$grnId}");
            
            return response()->json([
                'success' => true,
                'message' => 'Sync initiated successfully',
                'data' => ['grn_id' => $grnId]
            ]);
        } catch (\Exception $e) {
            Log::error('Error syncing from GRN: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to sync from GRN',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}