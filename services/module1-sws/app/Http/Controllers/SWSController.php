<?php

namespace App\Http\Controllers;

use App\Models\Warehousing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class SWSController extends Controller
{
    // Get all Inventory Flow records
    public function index()
    {
        try {
            $grnRecords = Warehousing::orderBy('created_at', 'desc')->get();
            
            return response()->json([
                'success' => true,
                'data' => $grnRecords,
                'message' => 'Inventory Flow records retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching Inventory Flow records: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve Inventory Flow records',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Create new Inventory Flow record
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'item' => 'required|string|max:255',
                'qty_ordered' => 'required|integer|min:1',
                'qty_received' => 'required|integer|min:0',
                'condition' => 'required|string|in:Good,Damaged,Missing',
                'warehouse_location' => 'required|string|max:255',
                'received_by' => 'required|string|max:255',
                'status' => 'sometimes|string|in:Pending,Completed,Cancelled'
            ]);

            // Generate GRN ID
            $grnId = IdGenerator::generate([
                'table' => 'sws_warehousing',
                'field' => 'grn_id',
                'length' => 8,
                'prefix' => 'GRN'
            ]);

            // Generate PO Number (format: PO00001)
            $lastPO = Warehousing::orderBy('created_at', 'desc')->first();
            $poNumber = 'PO' . str_pad(($lastPO ? intval(substr($lastPO->po_number, 2)) + 1 : 1), 5, '0', STR_PAD_LEFT);

            $grnRecord = Warehousing::create([
                'grn_id' => $grnId,
                'po_number' => $poNumber,
                'item' => $validatedData['item'],
                'qty_ordered' => $validatedData['qty_ordered'],
                'qty_received' => $validatedData['qty_received'],
                'condition' => $validatedData['condition'],
                'warehouse_location' => $validatedData['warehouse_location'],
                'received_by' => $validatedData['received_by'],
                'status' => $validatedData['status'] ?? 'Pending'
            ]);

            return response()->json([
                'success' => true,
                'data' => $grnRecord,
                'message' => 'Inventory Flow record created successfully'
            ], 201);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error creating Inventory Flow record: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to create Inventory Flow record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Get single Inventory Flow record
    public function show($id)
    {
        try {
            $grnRecord = Warehousing::find($id);
            
            if (!$grnRecord) {
                return response()->json([
                    'success' => false,
                    'message' => 'Inventory Flow record not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $grnRecord,
                'message' => 'Inventory Flow record retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching Inventory Flow record: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve Inventory Flow record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Update Inventory Flow record
    public function update(Request $request, $id)
    {
        try {
            $grnRecord = Warehousing::find($id);
            
            if (!$grnRecord) {
                return response()->json([
                    'success' => false,
                    'message' => 'Inventory Flow record not found'
                ], 404);
            }

            $validatedData = $request->validate([
                'item' => 'sometimes|string|max:255',
                'qty_ordered' => 'sometimes|integer|min:1',
                'qty_received' => 'sometimes|integer|min:0',
                'condition' => 'sometimes|string|in:Good,Damaged,Missing',
                'warehouse_location' => 'sometimes|string|max:255',
                'received_by' => 'sometimes|string|max:255',
                'status' => 'sometimes|string|in:Pending,Completed,Cancelled'
            ]);

            $grnRecord->update($validatedData);

            return response()->json([
                'success' => true,
                'data' => $grnRecord,
                'message' => 'Inventory Flow record updated successfully'
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error updating Inventory Flow record: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update Inventory Flow record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Delete Inventory Flow record
    public function destroy($id)
    {
        try {
            $grnRecord = Warehousing::find($id);
            
            if (!$grnRecord) {
                return response()->json([
                    'success' => false,
                    'message' => 'Inventory Flow record not found'
                ], 404);
            }

            $grnRecord->delete();

            return response()->json([
                'success' => true,
                'message' => 'Inventory Flow record deleted successfully'
            ]);

        } catch (\Exception $e) {
            Log::error('Error deleting Inventory Flow record: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete Inventory Flow record',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Get Inventory Flow statistics
    public function getStats()
    {
        try {
            $totalRecords = Warehousing::count();
            $goodCondition = Warehousing::where('condition', 'Good')->count();
            $damagedCondition = Warehousing::where('condition', 'Damaged')->count();
            $missingCondition = Warehousing::where('condition', 'Missing')->count();
            $completedStatus = Warehousing::where('status', 'Completed')->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_records' => $totalRecords,
                    'good_condition' => $goodCondition,
                    'damaged_condition' => $damagedCondition,
                    'missing_condition' => $missingCondition,
                    'completed_status' => $completedStatus
                ],
                'message' => 'Inventory Flow statistics retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching Inventory Flow statistics: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve Inventory Flow statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Search Inventory Flow records
    public function search(Request $request)
    {
        try {
            $query = Warehousing::query();

            // Search by multiple criteria
            if ($request->has('search') && !empty($request->search)) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('grn_id', 'like', '%' . $searchTerm . '%')
                      ->orWhere('po_number', 'like', '%' . $searchTerm . '%')
                      ->orWhere('item', 'like', '%' . $searchTerm . '%')
                      ->orWhere('warehouse_location', 'like', '%' . $searchTerm . '%')
                      ->orWhere('received_by', 'like', '%' . $searchTerm . '%');
                });
            }

            if ($request->has('condition') && !empty($request->condition)) {
                $query->where('condition', $request->condition);
            }

            if ($request->has('status') && !empty($request->status)) {
                $query->where('status', $request->status);
            }

            $results = $query->orderBy('created_at', 'desc')->get();

            return response()->json([
                'success' => true,
                'data' => $results,
                'message' => 'Inventory Flow records search completed successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error searching Inventory Flow records: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to search Inventory Flow records',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}