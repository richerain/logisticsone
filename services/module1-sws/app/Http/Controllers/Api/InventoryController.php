<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Models\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Inventory::with('storage')->latest();

            // Search
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('item_id', 'like', "%{$search}%")
                      ->orWhere('item_name', 'like', "%{$search}%")
                      ->orWhere('item_desc', 'like', "%{$search}%");
                });
            }

            // Filter by type
            if ($request->has('type') && $request->type != '') {
                $query->where('item_type', $request->type);
            }

            // Filter by status
            if ($request->has('status') && $request->status != '') {
                $query->where('item_status', $request->status);
            }

            $inventory = $query->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $inventory
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch inventory: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'item_name' => 'required|string|max:255',
            'item_type' => 'required|in:Document,Supplies,Equipment,Furniture',
            'item_stock' => 'required|integer|min:0',
            'item_stock_capacity' => 'required|integer|min:1',
            'item_desc' => 'nullable|string',
            'item_storage_from' => 'required|exists:sws_storage,storage_id',
            'item_stock_level' => 'required|in:instock,lowstock',
            'item_status' => 'required|in:pending,restocking,reserved,distributed'
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

            $itemId = Inventory::generateItemId();
            
            $inventory = Inventory::create([
                'item_id' => $itemId,
                'item_name' => $request->item_name,
                'item_type' => $request->item_type,
                'item_stock' => $request->item_stock,
                'item_stock_capacity' => $request->item_stock_capacity,
                'item_desc' => $request->item_desc,
                'item_storage_from' => $request->item_storage_from,
                'item_stock_level' => $request->item_stock_level,
                'item_status' => $request->item_status
            ]);

            // Update storage used units
            $storage = Storage::where('storage_id', $request->item_storage_from)->first();
            if ($storage) {
                $storage->storage_used_unit += $request->item_stock;
                $storage->save();
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Item created successfully',
                'data' => $inventory->load('storage')
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create item: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $inventory = Inventory::with('storage')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $inventory
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Item not found'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'item_name' => 'sometimes|required|string|max:255',
            'item_type' => 'sometimes|required|in:Document,Supplies,Equipment,Furniture',
            'item_stock' => 'sometimes|required|integer|min:0',
            'item_stock_capacity' => 'sometimes|required|integer|min:1',
            'item_desc' => 'nullable|string',
            'item_storage_from' => 'sometimes|required|exists:sws_storage,storage_id',
            'item_stock_level' => 'sometimes|required|in:instock,lowstock',
            'item_status' => 'sometimes|required|in:pending,restocking,reserved,distributed'
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

            $inventory = Inventory::findOrFail($id);
            $oldStorage = $inventory->item_storage_from;
            $oldStock = $inventory->item_stock;

            $inventory->update($request->all());

            // Update storage if storage location or stock changed
            if ($request->has('item_storage_from') || $request->has('item_stock')) {
                // Update old storage
                $oldStorageRecord = Storage::where('storage_id', $oldStorage)->first();
                if ($oldStorageRecord) {
                    $oldStorageRecord->storage_used_unit -= $oldStock;
                    $oldStorageRecord->save();
                }

                // Update new storage
                $newStorage = Storage::where('storage_id', $inventory->item_storage_from)->first();
                if ($newStorage) {
                    $newStorage->storage_used_unit += $inventory->item_stock;
                    $newStorage->save();
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Item updated successfully',
                'data' => $inventory->load('storage')
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update item: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();

            $inventory = Inventory::findOrFail($id);
            
            // Update storage used units
            $storage = Storage::where('storage_id', $inventory->item_storage_from)->first();
            if ($storage) {
                $storage->storage_used_unit -= $inventory->item_stock;
                $storage->save();
            }

            $inventory->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Item deleted successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete item: ' . $e->getMessage()
            ], 500);
        }
    }
}