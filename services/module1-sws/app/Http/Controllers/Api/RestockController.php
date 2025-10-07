<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Restock;
use App\Models\Inventory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class RestockController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Restock::with('inventory')->latest();

            // Search
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('restock_id', 'like', "%{$search}%")
                      ->orWhere('restock_item_name', 'like', "%{$search}%")
                      ->orWhere('restock_desc', 'like', "%{$search}%");
                });
            }

            // Filter by type
            if ($request->has('type') && $request->type != '') {
                $query->where('restock_item_type', $request->type);
            }

            // Filter by status
            if ($request->has('status') && $request->status != '') {
                $query->where('restock_status', $request->status);
            }

            $restocks = $query->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $restocks
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch restocks: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'restock_item_id' => 'required|exists:sws_inventory,item_id',
            'restock_item_unit' => 'required|integer|min:1',
            'restock_desc' => 'nullable|string',
            'restock_status' => 'required|in:pending,approve,delivered'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Get inventory item details
            $inventory = Inventory::where('item_id', $request->restock_item_id)->firstOrFail();
            
            $restockId = Restock::generateRestockId();
            
            $restock = Restock::create([
                'restock_id' => $restockId,
                'restock_item_id' => $request->restock_item_id,
                'restock_item_name' => $inventory->item_name,
                'restock_item_type' => $inventory->item_type,
                'restock_item_unit' => $request->restock_item_unit,
                'restock_item_capacity' => $inventory->item_stock_capacity,
                'restock_desc' => $request->restock_desc,
                'restock_status' => $request->restock_status
            ]);

            // If status is delivered, update inventory stock
            if ($request->restock_status === 'delivered') {
                $inventory->item_stock += $request->restock_item_unit;
                
                // Update stock level based on new stock
                if ($inventory->item_stock <= $inventory->item_stock_capacity * 0.2) {
                    $inventory->item_stock_level = 'lowstock';
                } else {
                    $inventory->item_stock_level = 'instock';
                }
                
                $inventory->save();

                // Update storage used units
                $storage = $inventory->storage;
                if ($storage) {
                    $storage->storage_used_unit += $request->restock_item_unit;
                    $storage->save();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Restock request created successfully',
                'data' => $restock->load('inventory')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create restock request: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            // Find by restock_id (string) or by auto-increment id
            $restock = Restock::with('inventory')
                ->where('restock_id', $id)
                ->orWhere('id', $id)
                ->firstOrFail();

            return response()->json([
                'success' => true,
                'data' => $restock
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Restock request not found'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'restock_item_unit' => 'sometimes|required|integer|min:1',
            'restock_desc' => 'nullable|string',
            'restock_status' => 'sometimes|required|in:pending,approve,delivered'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            DB::beginTransaction();

            // Find by restock_id (string) or by auto-increment id
            $restock = Restock::with('inventory')
                ->where('restock_id', $id)
                ->orWhere('id', $id)
                ->firstOrFail();
                
            $oldStatus = $restock->restock_status;
            $oldUnit = $restock->restock_item_unit;

            $restock->update($request->all());

            // Handle status change to delivered
            if ($oldStatus !== 'delivered' && $request->restock_status === 'delivered') {
                $inventory = $restock->inventory;
                $inventory->item_stock += $request->restock_item_unit;
                
                // Update stock level based on new stock
                if ($inventory->item_stock <= $inventory->item_stock_capacity * 0.2) {
                    $inventory->item_stock_level = 'lowstock';
                } else {
                    $inventory->item_stock_level = 'instock';
                }
                
                $inventory->save();

                // Update storage used units
                $storage = $inventory->storage;
                if ($storage) {
                    $storage->storage_used_unit += $request->restock_item_unit;
                    $storage->save();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Restock request updated successfully',
                'data' => $restock->load('inventory')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update restock request: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            // Find by restock_id (string) or by auto-increment id
            $restock = Restock::where('restock_id', $id)
                ->orWhere('id', $id)
                ->firstOrFail();
                
            $restock->delete();

            return response()->json([
                'success' => true,
                'message' => 'Restock request deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete restock request: ' . $e->getMessage()
            ], 500);
        }
    }
}