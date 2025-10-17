<?php

namespace App\Http\Controllers;

use App\Models\PltAllocation;
use App\Models\PltProject;
use App\Models\PltResource;
use Illuminate\Http\Request;

class PltAllocationController extends Controller
{
    public function index(Request $request)
    {
        $query = PltAllocation::with(['project', 'resource'])
            ->orderBy('created_at', 'desc');

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->whereHas('project', function($projectQuery) use ($request) {
                    $projectQuery->where('name', 'like', '%' . $request->search . '%');
                })->orWhereHas('resource', function($resourceQuery) use ($request) {
                    $resourceQuery->where('name', 'like', '%' . $request->search . '%');
                });
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $allocations = $query->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $allocations
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:plt_projects,id',
            'resource_id' => 'required|exists:plt_resources,id',
            'quantity_assigned' => 'required|integer|min:1',
            'assigned_date' => 'required|date',
            'return_date' => 'nullable|date|after:assigned_date',
            'status' => 'required|in:assigned,in_use,returned,issue'
        ]);

        // Check resource availability
        $resource = PltResource::find($validated['resource_id']);
        if ($resource->quantity_available < $validated['quantity_assigned']) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient resource quantity. Available: ' . $resource->quantity_available
            ], 400);
        }

        $allocation = PltAllocation::create($validated);

        // Update resource quantity
        $resource->quantity_available -= $validated['quantity_assigned'];
        $resource->save();

        return response()->json([
            'success' => true,
            'message' => 'Allocation created successfully',
            'data' => $allocation->load(['project', 'resource'])
        ], 201);
    }

    public function show($id)
    {
        $allocation = PltAllocation::with(['project', 'resource'])->find($id);

        if (!$allocation) {
            return response()->json([
                'success' => false,
                'message' => 'Allocation not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $allocation
        ]);
    }

    public function update(Request $request, $id)
    {
        $allocation = PltAllocation::find($id);

        if (!$allocation) {
            return response()->json([
                'success' => false,
                'message' => 'Allocation not found'
            ], 404);
        }

        $validated = $request->validate([
            'project_id' => 'sometimes|required|exists:plt_projects,id',
            'resource_id' => 'sometimes|required|exists:plt_resources,id',
            'quantity_assigned' => 'sometimes|required|integer|min:1',
            'assigned_date' => 'sometimes|required|date',
            'return_date' => 'nullable|date|after:assigned_date',
            'status' => 'sometimes|required|in:assigned,in_use,returned,issue'
        ]);

        // Handle resource quantity changes
        if (isset($validated['quantity_assigned']) && $validated['quantity_assigned'] != $allocation->quantity_assigned) {
            $resource = PltResource::find($validated['resource_id'] ?? $allocation->resource_id);
            $quantityDifference = $validated['quantity_assigned'] - $allocation->quantity_assigned;
            
            if ($resource->quantity_available < $quantityDifference) {
                return response()->json([
                    'success' => false,
                    'message' => 'Insufficient resource quantity for update. Available: ' . $resource->quantity_available
                ], 400);
            }

            $resource->quantity_available -= $quantityDifference;
            $resource->save();
        }

        $allocation->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Allocation updated successfully',
            'data' => $allocation->load(['project', 'resource'])
        ]);
    }

    public function destroy($id)
    {
        $allocation = PltAllocation::find($id);

        if (!$allocation) {
            return response()->json([
                'success' => false,
                'message' => 'Allocation not found'
            ], 404);
        }

        // Return resource quantity
        $resource = $allocation->resource;
        $resource->quantity_available += $allocation->quantity_assigned;
        $resource->save();

        $allocation->delete();

        return response()->json([
            'success' => true,
            'message' => 'Allocation deleted successfully'
        ]);
    }

    public function stats()
    {
        try {
            $totalAllocations = PltAllocation::count();
            $assigned = PltAllocation::where('status', 'assigned')->count();
            $inUse = PltAllocation::where('status', 'in_use')->count();
            $returned = PltAllocation::where('status', 'returned')->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_allocations' => $totalAllocations,
                    'assigned' => $assigned,
                    'in_use' => $inUse,
                    'returned' => $returned
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error calculating allocation statistics: ' . $e->getMessage()
            ], 500);
        }
    }
}