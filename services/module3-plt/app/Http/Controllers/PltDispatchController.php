<?php

namespace App\Http\Controllers;

use App\Models\PltDispatch;
use Illuminate\Http\Request;

class PltDispatchController extends Controller
{
    public function index(Request $request)
    {
        $query = PltDispatch::orderBy('created_at', 'desc');

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where('material_type', 'like', '%' . $request->search . '%')
                  ->orWhere('receipt_reference', 'like', '%' . $request->search . '%');
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by material type
        if ($request->has('material_type') && $request->material_type != '') {
            $query->where('material_type', $request->material_type);
        }

        $dispatches = $query->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $dispatches
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:plt_projects,id',
            'material_type' => 'required|string|max:100',
            'quantity' => 'required|integer|min:1',
            'from_location' => 'required|string|max:255',
            'to_location' => 'required|string|max:255',
            'dispatch_date' => 'required|date',
            'expected_delivery_date' => 'required|date|after:dispatch_date',
            'actual_delivery_date' => 'nullable|date',
            'status' => 'required|in:dispatched,in_transit,delayed,delivered,failed',
            'courier_info' => 'nullable|array',
            'receipt_reference' => 'nullable|string|max:100'
        ]);

        $dispatch = PltDispatch::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Dispatch created successfully',
            'data' => $dispatch
        ], 201);
    }

    public function show($id)
    {
        $dispatch = PltDispatch::find($id);

        if (!$dispatch) {
            return response()->json([
                'success' => false,
                'message' => 'Dispatch not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $dispatch
        ]);
    }

    public function update(Request $request, $id)
    {
        $dispatch = PltDispatch::find($id);

        if (!$dispatch) {
            return response()->json([
                'success' => false,
                'message' => 'Dispatch not found'
            ], 404);
        }

        $validated = $request->validate([
            'project_id' => 'sometimes|required|exists:plt_projects,id',
            'material_type' => 'sometimes|required|string|max:100',
            'quantity' => 'sometimes|required|integer|min:1',
            'from_location' => 'sometimes|required|string|max:255',
            'to_location' => 'sometimes|required|string|max:255',
            'dispatch_date' => 'sometimes|required|date',
            'expected_delivery_date' => 'sometimes|required|date|after:dispatch_date',
            'actual_delivery_date' => 'nullable|date',
            'status' => 'sometimes|required|in:dispatched,in_transit,delayed,delivered,failed',
            'courier_info' => 'nullable|array',
            'receipt_reference' => 'nullable|string|max:100'
        ]);

        $dispatch->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Dispatch updated successfully',
            'data' => $dispatch
        ]);
    }

    public function destroy($id)
    {
        $dispatch = PltDispatch::find($id);

        if (!$dispatch) {
            return response()->json([
                'success' => false,
                'message' => 'Dispatch not found'
            ], 404);
        }

        $dispatch->delete();

        return response()->json([
            'success' => true,
            'message' => 'Dispatch deleted successfully'
        ]);
    }

    public function stats()
    {
        $totalDispatches = PltDispatch::count();
        $inTransit = PltDispatch::where('status', 'in_transit')->count();
        $delivered = PltDispatch::where('status', 'delivered')->count();
        $delayed = PltDispatch::where('status', 'delayed')->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_dispatches' => $totalDispatches,
                'in_transit' => $inTransit,
                'delivered' => $delivered,
                'delayed' => $delayed
            ]
        ]);
    }
}