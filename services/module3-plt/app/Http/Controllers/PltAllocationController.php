<?php

namespace App\Http\Controllers;

use App\Models\PltAllocation;
use Illuminate\Http\Request;

class PltAllocationController extends Controller
{
    public function index(Request $request)
    {
        $allocations = PltAllocation::orderBy('created_at', 'desc')->paginate(10);
        
        return response()->json([
            'success' => true,
            'data' => $allocations
        ]);
    }

    public function stats()
    {
        return response()->json([
            'success' => true,
            'data' => [
                'total_allocations' => PltAllocation::count(),
                'assigned' => PltAllocation::where('status', 'assigned')->count(),
                'in_use' => PltAllocation::where('status', 'in_use')->count(),
                'returned' => PltAllocation::where('status', 'returned')->count()
            ]
        ]);
    }
}