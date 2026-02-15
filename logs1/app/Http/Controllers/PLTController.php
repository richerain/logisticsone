<?php

namespace App\Http\Controllers;

use App\Services\PLTService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class PLTController extends Controller
{
    protected $pltService;

    public function __construct(PLTService $pltService)
    {
        $this->pltService = $pltService;
    }

    public function getProjects(Request $request)
    {
        $filters = [
            'search' => $request->get('search'),
            'status' => $request->get('status'),
        ];

        $result = $this->pltService->getAllProjects($filters);

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    public function getMovementProjects(Request $request)
    {
        try {
            $perPage = (int) ($request->get('per_page', 10));
            $perPage = $perPage > 0 ? $perPage : 10;
            $page = (int) ($request->get('page', 1));
            $status = $request->get('status');
            $search = $request->get('search');

            $query = DB::connection('plt')->table('plt_movement_project');

            if (! empty($status)) {
                $query->where('mp_status', $status);
            }
            if (! empty($search)) {
                $query->where(function ($q) use ($search) {
                    $like = '%'.$search.'%';
                    $q->where('mp_item_name', 'like', $like)
                        ->orWhere('mp_stored_from', 'like', $like)
                        ->orWhere('mp_stored_to', 'like', $like)
                        ->orWhere('mp_item_type', 'like', $like)
                        ->orWhere('mp_movement_type', 'like', $like);
                });
            }

            $query->orderBy('created_at', 'desc');

            $paginator = $query->paginate($perPage, ['*'], 'page', $page);

            return response()->json([
                'success' => true,
                'data' => $paginator,
                'message' => 'Movement records loaded',
            ]);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Failed to load movement records',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function createMovementProject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'mp_item_name' => 'required|string|max:255',
            'mp_unit_transfer' => 'required|integer|min:1',
            'mp_stored_from' => 'nullable|string|max:255',
            'mp_stored_to' => 'nullable|string|max:255',
            'mp_item_type' => 'nullable|string|max:100',
            'mp_movement_type' => 'nullable|in:Stock Transfer,Asset Transfer',
            'mp_status' => 'nullable|in:pending,in-progress,delayed,completed',
            'mp_project_start' => 'nullable|date',
            'mp_project_end' => 'nullable|date|after_or_equal:mp_project_start',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }
        try {
            $payload = $validator->validated();
            if (empty($payload['mp_movement_type'])) {
                $payload['mp_movement_type'] = 'Stock Transfer';
            }
            if (empty($payload['mp_status'])) {
                $payload['mp_status'] = 'pending';
            }
            if (empty($payload['mp_project_start'])) {
                $payload['mp_project_start'] = now()->toDateString();
            }
            $payload['created_at'] = now();
            $payload['updated_at'] = now();
            $id = DB::connection('plt')->table('plt_movement_project')->insertGetId($payload, 'mp_id');
            $record = DB::connection('plt')->table('plt_movement_project')->where('mp_id', $id)->first();
            return response()->json([
                'success' => true,
                'data' => $record,
                'message' => 'Movement created',
            ], 201);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'data' => null,
                'message' => 'Failed to create movement',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function updateMovementStatus(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'mp_status' => 'required|in:in-progress,delayed,completed',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }
        try {
            $newStatus = $validator->validated()['mp_status'];
            $movement = DB::connection('plt')->table('plt_movement_project')->where('mp_id', $id)->first();
            if (! $movement) {
                return response()->json(['success' => false, 'message' => 'Not found'], 404);
            }

            $updateData = [
                'mp_status' => $newStatus,
                'updated_at' => now(),
            ];
            if ($newStatus === 'completed') {
                $updateData['mp_project_end'] = now()->toDateString();
            }

            $updated = DB::connection('plt')->table('plt_movement_project')
                ->where('mp_id', $id)
                ->update($updateData);
            if ($updated === 0) {
                return response()->json(['success' => false, 'message' => 'Not found'], 404);
            }
            
            // When completed, decrement DI stock and log transfer in SWS
            if ($newStatus === 'completed') {
                try {
                    $item = \App\Models\SWS\Item::where('item_name', $movement->mp_item_name)->first();
                    if ($item) {
                        $qty = (int) ($movement->mp_unit_transfer ?? 0);
                        $newStock = max(0, (int)$item->item_current_stock - $qty);
                        $item->item_current_stock = $newStock;
                        $item->item_updated_at = now();
                        $item->save();

                        $fromLoc = \App\Models\SWS\Location::where('loc_name', $movement->mp_stored_from)->first();
                        $toLoc = \App\Models\SWS\Location::where('loc_name', $movement->mp_stored_to)->first();
                        \App\Models\SWS\Transaction::create([
                            'tra_item_id' => $item->item_id,
                            'tra_type' => 'transfer',
                            'tra_quantity' => $qty,
                            'tra_from_location_id' => $fromLoc->loc_id ?? null,
                            'tra_to_location_id' => $toLoc->loc_id ?? null,
                            'tra_warehouse_id' => null,
                            'tra_transaction_date' => now(),
                            'tra_reference_id' => 'PLT-MOVE-' . $movement->mp_id,
                            'tra_status' => 'completed',
                            'tra_notes' => 'Finalized PLT movement to ' . ($movement->mp_stored_to ?? 'unknown'),
                        ]);
                    }
                } catch (\Throwable $ex) {
                    // Soft-fail: log error but do not break status update
                    \Log::error('Failed to finalize DI stock on PLT completion: ' . $ex->getMessage());
                }
            }

            return response()->json(['success' => true, 'message' => 'Status updated', 'data' => ['mp_project_end' => $updateData['mp_project_end'] ?? null]]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Failed to update status'], 500);
        }
    }

    public function updateMovementEndDate(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'mp_project_end' => 'required|date',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }
        try {
            $updated = DB::connection('plt')->table('plt_movement_project')
                ->where('mp_id', $id)
                ->update([
                    'mp_project_end' => $validator->validated()['mp_project_end'],
                    'updated_at' => now(),
                ]);
            if ($updated === 0) {
                return response()->json(['success' => false, 'message' => 'Not found'], 404);
            }
            return response()->json(['success' => true, 'message' => 'End date set']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Failed to set end date'], 500);
        }
    }

    public function deleteMovementProject($id)
    {
        try {
            $deleted = DB::connection('plt')->table('plt_movement_project')->where('mp_id', $id)->delete();
            if ($deleted === 0) {
                return response()->json(['success' => false, 'message' => 'Not found'], 404);
            }
            return response()->json(['success' => true, 'message' => 'Movement deleted']);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => 'Failed to delete movement'], 500);
        }
    }
    public function getProject($id)
    {
        $result = $this->pltService->getProject($id);

        return response()->json($result, $result['success'] ? 200 : 404);
    }

    public function createProject(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pro_project_name' => 'required|string|max:255',
            'pro_description' => 'nullable|string',
            'pro_start_date' => 'required|date',
            'pro_end_date' => 'nullable|date|after_or_equal:pro_start_date',
            'pro_status' => 'required|in:planning,active,completed,cancelled',
            'pro_budget_allocated' => 'required|numeric|min:0',
            'pro_assigned_manager_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $result = $this->pltService->createProject($request->all());

        return response()->json($result, $result['success'] ? 201 : 400);
    }

    public function updateProject(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'pro_project_name' => 'sometimes|required|string|max:255',
            'pro_description' => 'nullable|string',
            'pro_start_date' => 'sometimes|required|date',
            'pro_end_date' => 'nullable|date|after_or_equal:pro_start_date',
            'pro_status' => 'sometimes|required|in:planning,active,completed,cancelled',
            'pro_budget_allocated' => 'sometimes|required|numeric|min:0',
            'pro_assigned_manager_id' => 'sometimes|required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $result = $this->pltService->updateProject($id, $request->all());

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    public function deleteProject($id)
    {
        $result = $this->pltService->deleteProject($id);

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    public function getProjectStats()
    {
        $result = $this->pltService->getProjectStats();

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    public function getProjectMilestones($projectId)
    {
        $result = $this->pltService->getProjectMilestones($projectId);

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    public function createMilestone(Request $request, $projectId)
    {
        $validator = Validator::make($request->all(), [
            'mile_milestone_name' => 'required|string|max:100',
            'mile_description' => 'nullable|string',
            'mile_target_date' => 'required|date',
            'mile_actual_date' => 'nullable|date|after_or_equal:mile_target_date',
            'mile_status' => 'required|in:pending,in_progress,completed,overdue',
            'mile_priority' => 'required|in:low,medium,high',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $payload = array_merge($request->all(), [
            'mile_project_id' => (int) $projectId,
        ]);

        $result = $this->pltService->createMilestone($payload);

        return response()->json($result, $result['success'] ? 201 : 400);
    }

    public function updateMilestone(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'mile_milestone_name' => 'sometimes|required|string|max:100',
            'mile_description' => 'nullable|string',
            'mile_target_date' => 'sometimes|required|date',
            'mile_actual_date' => 'nullable|date|after_or_equal:mile_target_date',
            'mile_status' => 'sometimes|required|in:pending,in_progress,completed,overdue',
            'mile_priority' => 'sometimes|required|in:low,medium,high',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $result = $this->pltService->updateMilestone($id, $request->all());

        return response()->json($result, $result['success'] ? 200 : 400);
    }

    public function deleteMilestone($id)
    {
        $result = $this->pltService->deleteMilestone($id);

        return response()->json($result, $result['success'] ? 200 : 400);
    }
}
