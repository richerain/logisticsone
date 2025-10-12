<?php

namespace App\Http\Controllers;

use App\Models\PltProject;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PltProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = PltProject::orderBy('created_at', 'desc');

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
        }

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $projects = $query->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $projects
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'branch_from' => 'required|string|max:100',
            'branch_to' => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|in:planned,in_progress,delayed,completed,cancelled',
            'progress_percent' => 'required|integer|min:0|max:100'
        ]);

        $project = PltProject::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Project created successfully',
            'data' => $project
        ], 201);
    }

    public function show($id)
    {
        $project = PltProject::find($id);

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $project
        ]);
    }

    public function update(Request $request, $id)
    {
        $project = PltProject::find($id);

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found'
            ], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'branch_from' => 'sometimes|required|string|max:100',
            'branch_to' => 'sometimes|required|string|max:100',
            'start_date' => 'sometimes|required|date',
            'end_date' => 'sometimes|required|date|after:start_date',
            'status' => 'sometimes|required|in:planned,in_progress,delayed,completed,cancelled',
            'progress_percent' => 'sometimes|required|integer|min:0|max:100'
        ]);

        $project->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Project updated successfully',
            'data' => $project
        ]);
    }

    public function destroy($id)
    {
        $project = PltProject::find($id);

        if (!$project) {
            return response()->json([
                'success' => false,
                'message' => 'Project not found'
            ], 404);
        }

        $project->delete();

        return response()->json([
            'success' => true,
            'message' => 'Project deleted successfully'
        ]);
    }

    public function stats()
    {
        $totalProjects = PltProject::count();
        $activeProjects = PltProject::whereIn('status', ['planned', 'in_progress'])->count();
        $delayedProjects = PltProject::where('status', 'delayed')->count();
        $completedProjects = PltProject::where('status', 'completed')->count();

        return response()->json([
            'success' => true,
            'data' => [
                'total_projects' => $totalProjects,
                'active_projects' => $activeProjects,
                'delayed_projects' => $delayedProjects,
                'completed_projects' => $completedProjects
            ]
        ]);
    }
}