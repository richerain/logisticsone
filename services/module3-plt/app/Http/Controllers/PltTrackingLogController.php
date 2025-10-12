<?php

namespace App\Http\Controllers;

use App\Models\PltTrackingLog;
use App\Models\PltDispatch;
use Illuminate\Http\Request;

class PltTrackingLogController extends Controller
{
    public function index(Request $request)
    {
        $query = PltTrackingLog::with(['dispatch.project'])
            ->orderBy('created_at', 'desc');

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('status_update', 'like', '%' . $request->search . '%')
                  ->orWhere('location', 'like', '%' . $request->search . '%')
                  ->orWhereHas('dispatch', function($dispatchQuery) use ($request) {
                      $dispatchQuery->whereHas('project', function($projectQuery) use ($request) {
                          $projectQuery->where('name', 'like', '%' . $request->search . '%');
                      });
                  });
            });
        }

        $logs = $query->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $logs
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'dispatch_id' => 'required|exists:plt_dispatches,id',
            'timestamp' => 'required|date',
            'location' => 'nullable|string|max:255',
            'status_update' => 'required|string|max:100',
            'notes' => 'nullable|string'
        ]);

        $log = PltTrackingLog::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tracking log created successfully',
            'data' => $log
        ], 201);
    }

    public function show($id)
    {
        $log = PltTrackingLog::with(['dispatch.project'])->find($id);

        if (!$log) {
            return response()->json([
                'success' => false,
                'message' => 'Tracking log not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $log
        ]);
    }

    public function update(Request $request, $id)
    {
        $log = PltTrackingLog::find($id);

        if (!$log) {
            return response()->json([
                'success' => false,
                'message' => 'Tracking log not found'
            ], 404);
        }

        $validated = $request->validate([
            'dispatch_id' => 'sometimes|required|exists:plt_dispatches,id',
            'timestamp' => 'sometimes|required|date',
            'location' => 'nullable|string|max:255',
            'status_update' => 'sometimes|required|string|max:100',
            'notes' => 'nullable|string'
        ]);

        $log->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Tracking log updated successfully',
            'data' => $log
        ]);
    }

    public function destroy($id)
    {
        $log = PltTrackingLog::find($id);

        if (!$log) {
            return response()->json([
                'success' => false,
                'message' => 'Tracking log not found'
            ], 404);
        }

        $log->delete();

        return response()->json([
            'success' => true,
            'message' => 'Tracking log deleted successfully'
        ]);
    }

    public function getByDispatch($dispatchId)
    {
        $logs = PltTrackingLog::where('dispatch_id', $dispatchId)
            ->orderBy('timestamp', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $logs
        ]);
    }
}