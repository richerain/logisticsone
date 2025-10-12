<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\Branch;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AssetController extends Controller
{
    public function index(Request $request)
    {
        $query = Asset::with(['category', 'branch', 'assignedEmployee'])
            ->latest();

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('asset_code', 'like', "%{$search}%")
                  ->orWhere('name', 'like', "%{$search}%")
                  ->orWhere('serial_number', 'like', "%{$search}%");
            });
        }

        // Filter by category
        if ($request->has('category') && $request->category != '') {
            $query->where('category_id', $request->category);
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $assets = $query->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $assets,
            'categories' => AssetCategory::all(),
            'stats' => [
                'total' => Asset::count(),
                'active' => Asset::where('status', 'active')->count(),
                'maintenance' => Asset::where('status', 'maintenance')->count(),
                'retired' => Asset::where('status', 'retired')->count(),
            ]
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => 'required|exists:asset_categories,id',
            'branch_id' => 'required|exists:branches,id',
            'name' => 'required|string|max:255',
            'serial_number' => 'required|string|unique:assets,serial_number',
            'purchase_date' => 'required|date',
            'purchase_cost' => 'required|numeric|min:0',
            'warranty_period' => 'required|integer|min:0',
            'assigned_employee_id' => 'nullable|exists:employees,id',
            'specifications' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            // Generate asset code
            $assetCode = 'AST' . date('Y') . str_pad(Asset::count() + 1, 5, '0', STR_PAD_LEFT);

            $imagePath = null;
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = $assetCode . '_' . time() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('assets', $imageName, 'public');
            }

            $asset = Asset::create([
                'asset_code' => $assetCode,
                'category_id' => $request->category_id,
                'branch_id' => $request->branch_id,
                'name' => $request->name,
                'serial_number' => $request->serial_number,
                'purchase_date' => $request->purchase_date,
                'purchase_cost' => $request->purchase_cost,
                'warranty_period' => $request->warranty_period,
                'assigned_employee_id' => $request->assigned_employee_id,
                'specifications' => $request->specifications,
                'image_path' => $imagePath,
                'status' => 'active'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Asset registered successfully',
                'data' => $asset
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to register asset: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $asset = Asset::with(['category', 'branch', 'assignedEmployee'])->find($id);

        if (!$asset) {
            return response()->json([
                'success' => false,
                'message' => 'Asset not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $asset
        ]);
    }

    public function update(Request $request, $id)
    {
        $asset = Asset::find($id);

        if (!$asset) {
            return response()->json([
                'success' => false,
                'message' => 'Asset not found'
            ], 404);
        }

        $request->validate([
            'category_id' => 'sometimes|exists:asset_categories,id',
            'branch_id' => 'sometimes|exists:branches,id',
            'name' => 'sometimes|string|max:255',
            'serial_number' => 'sometimes|string|unique:assets,serial_number,' . $id,
            'purchase_date' => 'sometimes|date',
            'purchase_cost' => 'sometimes|numeric|min:0',
            'warranty_period' => 'sometimes|integer|min:0',
            'assigned_employee_id' => 'nullable|exists:employees,id',
            'specifications' => 'nullable|string',
            'status' => 'sometimes|in:active,maintenance,retired',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        try {
            $data = $request->except(['image']);

            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($asset->image_path) {
                    Storage::disk('public')->delete($asset->image_path);
                }

                $image = $request->file('image');
                $imageName = $asset->asset_code . '_' . time() . '.' . $image->getClientOriginalExtension();
                $data['image_path'] = $image->storeAs('assets', $imageName, 'public');
            }

            $asset->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Asset updated successfully',
                'data' => $asset
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update asset: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        $asset = Asset::find($id);

        if (!$asset) {
            return response()->json([
                'success' => false,
                'message' => 'Asset not found'
            ], 404);
        }

        try {
            // Delete associated image
            if ($asset->image_path) {
                Storage::disk('public')->delete($asset->image_path);
            }

            $asset->delete();

            return response()->json([
                'success' => true,
                'message' => 'Asset deleted successfully'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete asset: ' . $e->getMessage()
            ], 500);
        }
    }

    public function stats()
    {
        $totalAssets = Asset::count();
        $totalValue = Asset::sum('purchase_cost');
        $activeAssets = Asset::where('status', 'active')->count();
        $underMaintenance = Asset::where('status', 'maintenance')->count();

        // Assets by category
        $assetsByCategory = Asset::with('category')
            ->selectRaw('category_id, COUNT(*) as count')
            ->groupBy('category_id')
            ->get();

        return response()->json([
            'success' => true,
            'data' => [
                'total_assets' => $totalAssets,
                'total_value' => $totalValue,
                'active_assets' => $activeAssets,
                'under_maintenance' => $underMaintenance,
                'assets_by_category' => $assetsByCategory
            ]
        ]);
    }
}