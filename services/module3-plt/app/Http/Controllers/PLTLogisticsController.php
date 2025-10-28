<?php

namespace App\Http\Controllers;

use App\Models\Logistics;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PLTController extends Controller
{
    /**
     * Display a listing of the logistics projects.
     */
    public function index(Request $request)
    {
        try {
            \Log::info('PLTController index called', ['request' => $request->all()]);
            
            $query = Logistics::query();

            // Search functionality
            if ($request->has('search') && !empty($request->search)) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('delivery_id', 'like', "%{$search}%")
                      ->orWhere('vehicle_id', 'like', "%{$search}%")
                      ->orWhere('driver_name', 'like', "%{$search}%")
                      ->orWhere('destination', 'like', "%{$search}%")
                      ->orWhere('receiver_name', 'like', "%{$search}%")
                      ->orWhere('items', 'like', "%{$search}%");
                });
            }

            // Filter by status
            if ($request->has('status') && !empty($request->status)) {
                $query->where('status', $request->status);
            }

            // Filter by date range
            if ($request->has('start_date') && !empty($request->start_date)) {
                $query->where('delivery_date', '>=', $request->start_date);
            }

            if ($request->has('end_date') && !empty($request->end_date)) {
                $query->where('delivery_date', '<=', $request->end_date);
            }

            // Sort functionality
            $sortField = $request->get('sort_field', 'created_at');
            $sortOrder = $request->get('sort_order', 'desc');
            $query->orderBy($sortField, $sortOrder);

            $logistics = $query->get();

            \Log::info('PLTController returning data', ['count' => count($logistics)]);

            return response()->json([
                'success' => true,
                'data' => $logistics,
                'message' => 'Logistics projects retrieved successfully.'
            ]);

        } catch (\Exception $e) {
            \Log::error('PLTController index error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve logistics projects: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Store a newly created logistics project.
     */
    public function store(Request $request)
    {
        \Log::info('PLTController store called', $request->all());

        $validator = Validator::make($request->all(), [
            'vehicle_id' => 'required|string|max:20',
            'driver_name' => 'required|string|max:100',
            'route' => 'required|string|max:200',
            'destination' => 'required|string|max:100',
            'items' => 'required|string',
            'status' => 'required|in:Scheduled,In Transit,Delivered',
            'receiver_name' => 'required|string|max:100',
            'delivery_date' => 'required|date',
            'notes' => 'nullable|string'
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

            $logistics = Logistics::create($request->all());

            DB::commit();

            \Log::info('PLTController store success', ['delivery_id' => $logistics->delivery_id]);

            return response()->json([
                'success' => true,
                'data' => $logistics,
                'message' => 'Logistics project created successfully.'
            ], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('PLTController store error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to create logistics project: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified logistics project.
     */
    public function show($id)
    {
        try {
            \Log::info('PLTController show called', ['id' => $id]);

            $logistics = Logistics::where('delivery_id', $id)
                                ->orWhere('logistics_id', $id)
                                ->first();

            if (!$logistics) {
                return response()->json([
                    'success' => false,
                    'message' => 'Logistics project not found.'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $logistics,
                'message' => 'Logistics project retrieved successfully.'
            ]);

        } catch (\Exception $e) {
            \Log::error('PLTController show error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve logistics project: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified logistics project.
     */
    public function update(Request $request, $id)
    {
        \Log::info('PLTController update called', ['id' => $id, 'data' => $request->all()]);

        $validator = Validator::make($request->all(), [
            'vehicle_id' => 'sometimes|required|string|max:20',
            'driver_name' => 'sometimes|required|string|max:100',
            'route' => 'sometimes|required|string|max:200',
            'destination' => 'sometimes|required|string|max:100',
            'items' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:Scheduled,In Transit,Delivered',
            'receiver_name' => 'sometimes|required|string|max:100',
            'delivery_date' => 'sometimes|required|date',
            'notes' => 'nullable|string'
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

            $logistics = Logistics::where('delivery_id', $id)
                                ->orWhere('logistics_id', $id)
                                ->first();

            if (!$logistics) {
                return response()->json([
                    'success' => false,
                    'message' => 'Logistics project not found.'
                ], 404);
            }

            $logistics->update($request->all());

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $logistics,
                'message' => 'Logistics project updated successfully.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('PLTController update error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update logistics project: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified logistics project.
     */
    public function destroy($id)
    {
        try {
            \Log::info('PLTController destroy called', ['id' => $id]);

            DB::beginTransaction();

            $logistics = Logistics::where('delivery_id', $id)
                                ->orWhere('logistics_id', $id)
                                ->first();

            if (!$logistics) {
                return response()->json([
                    'success' => false,
                    'message' => 'Logistics project not found.'
                ], 404);
            }

            $logistics->delete();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Logistics project deleted successfully.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('PLTController destroy error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to delete logistics project: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get logistics statistics
     */
    public function stats()
    {
        try {
            \Log::info('PLTController stats called');

            $total = Logistics::count();
            $scheduled = Logistics::where('status', 'Scheduled')->count();
            $inTransit = Logistics::where('status', 'In Transit')->count();
            $delivered = Logistics::where('status', 'Delivered')->count();

            // Recent deliveries (last 7 days)
            $recentDeliveries = Logistics::where('delivery_date', '>=', now()->subDays(7))
                                        ->count();

            $stats = [
                'total_deliveries' => $total,
                'scheduled' => $scheduled,
                'in_transit' => $inTransit,
                'delivered' => $delivered,
                'recent_deliveries' => $recentDeliveries,
                'completion_rate' => $total > 0 ? round(($delivered / $total) * 100, 2) : 0
            ];

            \Log::info('PLTController stats result', $stats);

            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Logistics statistics retrieved successfully.'
            ]);

        } catch (\Exception $e) {
            \Log::error('PLTController stats error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve logistics statistics: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update delivery status
     */
    public function updateStatus(Request $request, $id)
    {
        \Log::info('PLTController updateStatus called', ['id' => $id, 'status' => $request->status]);

        $validator = Validator::make($request->all(), [
            'status' => 'required|in:Scheduled,In Transit,Delivered'
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

            $logistics = Logistics::where('delivery_id', $id)
                                ->orWhere('logistics_id', $id)
                                ->first();

            if (!$logistics) {
                return response()->json([
                    'success' => false,
                    'message' => 'Logistics project not found.'
                ], 404);
            }

            $updateData = ['status' => $request->status];
            
            // If status is Delivered and delivery_date is not set, set it to now
            if ($request->status === 'Delivered' && empty($logistics->delivery_date)) {
                $updateData['delivery_date'] = now()->format('Y-m-d');
            }

            $logistics->update($updateData);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $logistics,
                'message' => 'Delivery status updated successfully.'
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('PLTController updateStatus error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to update delivery status: ' . $e->getMessage()
            ], 500);
        }
    }
}