<?php

namespace App\Http\Controllers;

use App\Models\DtlrDocumentLog;
use Illuminate\Http\Request;

class DtlrDocumentLogController extends Controller
{
    public function index(Request $request)
    {
        $query = DtlrDocumentLog::with(['document', 'fromBranch', 'toBranch', 'performer'])
            ->latest();

        // Search
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('document', function($q2) use ($search) {
                    $q2->where('tracking_number', 'like', "%{$search}%")
                       ->orWhere('title', 'like', "%{$search}%");
                })
                ->orWhere('notes', 'like', "%{$search}%")
                ->orWhere('action', 'like', "%{$search}%");
            });
        }

        // Filter by action
        if ($request->has('action') && $request->action != '') {
            $query->where('action', $request->action);
        }

        $logs = $query->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $logs,
            'stats' => [
                'total' => DtlrDocumentLog::count(),
                'accessed' => DtlrDocumentLog::where('action', 'accessed')->count(),
                'printed' => DtlrDocumentLog::where('action', 'printed')->count(),
                'transferred' => DtlrDocumentLog::where('action', 'transferred')->count(),
                'reviewed' => DtlrDocumentLog::where('action', 'reviewed')->count(),
            ]
        ]);
    }

    public function show($id)
    {
        $log = DtlrDocumentLog::with(['document', 'fromBranch', 'toBranch', 'performer'])->find($id);

        if (!$log) {
            return response()->json([
                'success' => false,
                'message' => 'Log not found'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $log
        ]);
    }
}