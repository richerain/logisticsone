<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class StorageController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Storage::latest();

            // Search
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('storage_id', 'like', "%{$search}%")
                      ->orWhere('storage_name', 'like', "%{$search}%")
                      ->orWhere('storage_location', 'like', "%{$search}%");
                });
            }

            // Filter by type
            if ($request->has('type') && $request->type != '') {
                $query->where('storage_type', $request->type);
            }

            // Filter by status
            if ($request->has('status') && $request->status != '') {
                $query->where('storage_status', $request->status);
            }

            $storage = $query->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $storage
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch storage: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'storage_name' => 'required|string|max:255',
            'storage_location' => 'required|string|max:255',
            'storage_type' => 'required|in:Document,Supplies,Equipment,Furniture',
            'storage_max_unit' => 'required|integer|min:1',
            'storage_status' => 'required|in:active,inactive,maintenance'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $storageId = Storage::generateStorageId();
            
            $storage = Storage::create([
                'storage_id' => $storageId,
                'storage_name' => $request->storage_name,
                'storage_location' => $request->storage_location,
                'storage_type' => $request->storage_type,
                'storage_max_unit' => $request->storage_max_unit,
                'storage_used_unit' => 0,
                'storage_status' => $request->storage_status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Storage created successfully',
                'data' => $storage
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create storage: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $storage = Storage::with('inventories')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $storage
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Storage not found'
            ], 404);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'storage_name' => 'sometimes|required|string|max:255',
            'storage_location' => 'sometimes|required|string|max:255',
            'storage_type' => 'sometimes|required|in:Document,Supplies,Equipment,Furniture',
            'storage_max_unit' => 'sometimes|required|integer|min:1',
            'storage_status' => 'sometimes|required|in:active,inactive,maintenance'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $storage = Storage::findOrFail($id);
            $storage->update($request->all());

            return response()->json([
                'success' => true,
                'message' => 'Storage updated successfully',
                'data' => $storage
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update storage: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $storage = Storage::findOrFail($id);
            
            // Check if storage has items
            if ($storage->inventories()->count() > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete storage that has items'
                ], 400);
            }

            $storage->delete();

            return response()->json([
                'success' => true,
                'message' => 'Storage deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete storage: ' . $e->getMessage()
            ], 500);
        }
    }
}