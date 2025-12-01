<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PLTService;
use Illuminate\Support\Facades\Validator;

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
                'errors' => $validator->errors()
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
                'errors' => $validator->errors()
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
                'errors' => $validator->errors()
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
                'errors' => $validator->errors()
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