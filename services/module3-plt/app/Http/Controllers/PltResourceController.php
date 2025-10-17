<?php

namespace App\Http\Controllers;

use App\Models\PltResource;
use Illuminate\Http\Request;

class PltResourceController extends Controller
{
    public function index(Request $request)
    {
        $query = PltResource::withCount('allocations')
            ->orderBy('created_at', 'desc');

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Filter by type
        if ($request->has('type') && $request->type != '') {
            $query->where('type', $request->type);
        }

        $resources = $query->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $resources
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:asset,supply,personnel',
            'description' => 'nullable|string',
            'quantity_available' => 'required|integer|min:0',
            'location' => 'nullable|string|max:255'
        ]);

        $resource = PltResource::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Resource created successfully',
            'data' => $resource
        ], 201);
    }

    public function show($id)
    {
        $resource = PltResource::withCount('allocations')->find($id);

        if (!$resource) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $resource
        ]);
    }

    public function update(Request $request, $id)
    {
        $resource = PltResource::find($id);

        if (!$resource) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'type' => 'sometimes|required|in:asset,supply,personnel',
            'description' => 'nullable|string',
            'quantity_available' => 'sometimes|required|integer|min:0',
            'location' => 'nullable|string|max:255'
        ]);

        $resource->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Resource updated successfully',
            'data' => $resource
        ]);
    }

    public function destroy($id)
    {
        $resource = PltResource::find($id);

        if (!$resource) {
            return response()->json([
                'success' => false,
                'message' => 'Resource not found'
            ], 404);
        }

        // Check if resource has allocations
        if ($resource->allocations()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete resource with existing allocations'
            ], 400);
        }

        $resource->delete();

        return response()->json([
            'success' => true,
            'message' => 'Resource deleted successfully'
        ]);
    }

    public function stats()
    {
        try {
            $totalResources = PltResource::count();
            $assets = PltResource::where('type', 'asset')->count();
            $supplies = PltResource::where('type', 'supply')->count();
            $personnel = PltResource::where('type', 'personnel')->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_resources' => $totalResources,
                    'assets' => $assets,
                    'supplies' => $supplies,
                    'personnel' => $personnel
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error calculating resource statistics: ' . $e->getMessage()
            ], 500);
        }
    }
}