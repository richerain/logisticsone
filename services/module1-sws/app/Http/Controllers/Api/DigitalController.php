<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Digital;
use App\Models\Warehousing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DigitalController extends Controller
{
    // Get all digital inventory records
    public function index()
    {
        try {
            Log::info('Fetching all digital inventory records');
            $digitalRecords = Digital::with('warehousing')
                ->orderBy('created_at', 'desc')
                ->get();
            
            Log::info('Successfully fetched ' . count($digitalRecords) . ' digital inventory records');
            
            return response()->json([
                'success' => true,
                'data' => $digitalRecords,
                'message' => 'Digital inventory records retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching digital inventory records: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve digital inventory records',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Create new digital inventory record
    public function store(Request $request)
    {
        try {
            Log::info('Creating new digital inventory record', $request->all());

            $validatedData = $request->validate([
                'item_name' => 'required|string|max:255',
                'type' => 'required|string|max:100',
                'units' => 'required|string|max:50',
                'available_item' => 'required|integer|min:0',
                'status' => 'sometimes|string|in:lowstock,onstock,outofstock',
                'grn_id' => 'sometimes|exists:sws_warehousing,id'
            ]);

            // Generate Stock ID
            $stockId = Digital::generateStockId();

            $digitalRecord = Digital::create([
                'stock_id' => $stockId,
                'item_name' => $validatedData['item_name'],
                'type' => $validatedData['type'],
                'units' => $validatedData['units'],
                'available_item' => $validatedData['available_item'],
                'status' => $validatedData['status'] ?? 'onstock',
                'grn_id' => $validatedData['grn_id'] ?? null
            ]);

            // Auto-update status based on available items
            $digitalRecord->updateStatus();

            Log::info('Successfully created digital inventory record with ID: ' . $digitalRecord->id);

            return response()->json([
                'success' => true,
                'data' => $digitalRecord->load('warehousing'),
                'message' => 'Digital inventory record created successfully'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed for digital inventory record: ' . json_encode($e->errors()));
            
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating digital inventory record: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create digital inventory record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Get single digital inventory record
    public function show($id)
    {
        try {
            Log::info('Fetching digital inventory record with ID: ' . $id);
            
            $digitalRecord = Digital::with('warehousing')->find($id);
            
            if (!$digitalRecord) {
                Log::warning('Digital inventory record not found with ID: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Digital inventory record not found'
                ], 404);
            }

            Log::info('Successfully fetched digital inventory record with ID: ' . $id);

            return response()->json([
                'success' => true,
                'data' => $digitalRecord,
                'message' => 'Digital inventory record retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching digital inventory record: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve digital inventory record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Update digital inventory record
    public function update(Request $request, $id)
    {
        try {
            Log::info('Updating digital inventory record with ID: ' . $id, $request->all());

            $digitalRecord = Digital::find($id);
            
            if (!$digitalRecord) {
                Log::warning('Digital inventory record not found with ID: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Digital inventory record not found'
                ], 404);
            }

            $validatedData = $request->validate([
                'item_name' => 'sometimes|string|max:255',
                'type' => 'sometimes|string|max:100',
                'units' => 'sometimes|string|max:50',
                'available_item' => 'sometimes|integer|min:0',
                'status' => 'sometimes|string|in:lowstock,onstock,outofstock',
                'grn_id' => 'sometimes|exists:sws_warehousing,id'
            ]);

            $digitalRecord->update($validatedData);

            // Auto-update status based on available items
            $digitalRecord->updateStatus();

            Log::info('Successfully updated digital inventory record with ID: ' . $id);

            return response()->json([
                'success' => true,
                'data' => $digitalRecord->load('warehousing'),
                'message' => 'Digital inventory record updated successfully'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            Log::error('Validation failed for digital inventory record update: ' . json_encode($e->errors()));
            
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating digital inventory record: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update digital inventory record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Delete digital inventory record
    public function destroy($id)
    {
        try {
            Log::info('Deleting digital inventory record with ID: ' . $id);

            $digitalRecord = Digital::find($id);
            
            if (!$digitalRecord) {
                Log::warning('Digital inventory record not found with ID: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Digital inventory record not found'
                ], 404);
            }

            $digitalRecord->delete();

            Log::info('Successfully deleted digital inventory record with ID: ' . $id);

            return response()->json([
                'success' => true,
                'message' => 'Digital inventory record deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting digital inventory record: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete digital inventory record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Get digital inventory statistics
    public function getStats()
    {
        try {
            Log::info('Fetching digital inventory statistics');

            $totalRecords = Digital::count();
            $lowStock = Digital::lowStock()->count();
            $onStock = Digital::onStock()->count();
            $outOfStock = Digital::outOfStock()->count();
            $totalItems = Digital::sum('available_item');

            $stats = [
                'total_records' => $totalRecords,
                'low_stock' => $lowStock,
                'on_stock' => $onStock,
                'out_of_stock' => $outOfStock,
                'total_items' => $totalItems
            ];

            Log::info('Digital inventory statistics: ' . json_encode($stats));

            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Digital inventory statistics retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching digital inventory statistics: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve digital inventory statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Search digital inventory records
    public function search(Request $request)
    {
        try {
            Log::info('Searching digital inventory records', $request->all());

            $query = Digital::with('warehousing');

            // Search by multiple criteria
            if ($request->has('search') && !empty($request->search)) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('stock_id', 'like', '%' . $searchTerm . '%')
                      ->orWhere('item_name', 'like', '%' . $searchTerm . '%')
                      ->orWhere('type', 'like', '%' . $searchTerm . '%')
                      ->orWhere('units', 'like', '%' . $searchTerm . '%');
                });
            }

            if ($request->has('status') && !empty($request->status)) {
                $query->where('status', $request->status);
            }

            if ($request->has('type') && !empty($request->type)) {
                $query->where('type', 'like', '%' . $request->type . '%');
            }

            $results = $query->orderBy('created_at', 'desc')->get();

            Log::info('Search completed, found ' . count($results) . ' records');

            return response()->json([
                'success' => true,
                'data' => $results,
                'message' => 'Digital inventory records search completed successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error searching digital inventory records: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to search digital inventory records',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Sync from Goods Received (GRN) - automatically create digital inventory from completed GRN
    public function syncFromGrn($grnId)
    {
        try {
            Log::info('Syncing GRN to digital inventory for GRN ID: ' . $grnId);

            $grnRecord = Warehousing::find($grnId);
            
            if (!$grnRecord) {
                Log::warning('GRN record not found with ID: ' . $grnId);
                return response()->json([
                    'success' => false,
                    'message' => 'GRN record not found'
                ], 404);
            }

            if ($grnRecord->status !== 'Completed') {
                Log::warning('GRN record not completed, cannot sync. GRN ID: ' . $grnId);
                return response()->json([
                    'success' => false,
                    'message' => 'GRN record must be completed to sync to digital inventory'
                ], 400);
            }

            // Check if already synced
            $existingDigital = Digital::where('grn_id', $grnId)->first();
            if ($existingDigital) {
                Log::warning('GRN record already synced to digital inventory. GRN ID: ' . $grnId);
                return response()->json([
                    'success' => false,
                    'message' => 'GRN record already synced to digital inventory'
                ], 400);
            }

            // Create digital inventory record
            $stockId = Digital::generateStockId();
            
            $digitalRecord = Digital::create([
                'stock_id' => $stockId,
                'item_name' => $grnRecord->item,
                'type' => 'General', // Default type, can be customized
                'units' => 'pcs', // Default units, can be customized
                'available_item' => $grnRecord->qty_received,
                'grn_id' => $grnRecord->id
            ]);

            // Auto-update status
            $digitalRecord->updateStatus();

            Log::info('Successfully synced GRN to digital inventory. Stock ID: ' . $stockId);

            return response()->json([
                'success' => true,
                'data' => $digitalRecord,
                'message' => 'GRN record successfully synced to digital inventory'
            ]);

        } catch (\Exception $e) {
            Log::error('Error syncing GRN to digital inventory: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to sync GRN to digital inventory',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}