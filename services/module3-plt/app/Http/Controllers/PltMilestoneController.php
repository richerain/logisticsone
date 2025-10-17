<?php

namespace App\Http\Controllers;

use App\Models\PltMilestone;
use App\Models\PltProject;
use Illuminate\Http\Request;

class PltMilestoneController extends Controller
{
    public function index(Request $request)
    {
        $query = PltMilestone::with(['project', 'dispatch'])
            ->orderBy('created_at', 'desc');

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhereHas('project', function($projectQuery) use ($request) {
                      $projectQuery->where('name', 'like', '%' . $request->search . '%');
                  });
            });
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $milestones = $query->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $milestones
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id' => 'required|exists:plt_projects,id',
            'dispatch_id' => 'nullable|exists:plt_dispatches,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'actual_date' => 'nullable|date',
            'status' => 'required|in:pending,in_progress,completed,delayed',
            'delay_alert' => 'sometimes|boolean'
        ]);

        $milestone = PltMilestone::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Milestone created successfully',
            'data' => $milestone
        ], 201);
    }

    public function show($id)
    {
        $milestone = PltMilestone::with(['project', 'dispatch'])->find($id);

        if (!$milestone) {
            return response()->json([
                'success' => false,
                'message' => 'Milestone not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $milestone
        ]);
    }

    public function update(Request $request, $id)
    {
        $milestone = PltMilestone::find($id);

        if (!$milestone) {
            return response()->json([
                'success' => false,
                'message' => 'Milestone not found'
            ], 404);
        }

        $validated = $request->validate([
            'project_id' => 'sometimes|required|exists:plt_projects,id',
            'dispatch_id' => 'nullable|exists:plt_dispatches,id',
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'sometimes|required|date',
            'actual_date' => 'nullable|date',
            'status' => 'sometimes|required|in:pending,in_progress,completed,delayed',
            'delay_alert' => 'sometimes|boolean'
        ]);

        $milestone->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Milestone updated successfully',
            'data' => $milestone
        ]);
    }

    public function destroy($id)
    {
        $milestone = PltMilestone::find($id);

        if (!$milestone) {
            return response()->json([
                'success' => false,
                'message' => 'Milestone not found'
            ], 404);
        }

        $milestone->delete();

        return response()->json([
            'success' => true,
            'message' => 'Milestone deleted successfully'
        ]);
    }

    public function stats()
    {
        try {
            $totalMilestones = PltMilestone::count();
            $pending = PltMilestone::where('status', 'pending')->count();
            $inProgress = PltMilestone::where('status', 'in_progress')->count();
            $completed = PltMilestone::where('status', 'completed')->count();
            $delayed = PltMilestone::where('delay_alert', true)->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_milestones' => $totalMilestones,
                    'pending' => $pending,
                    'in_progress' => $inProgress,
                    'completed' => $completed,
                    'delayed' => $delayed
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error calculating milestone statistics: ' . $e->getMessage()
            ], 500);
        }
    }
}