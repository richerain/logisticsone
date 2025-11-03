<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DTLRController extends Controller
{
    public function getDocumentTracker()
    {
        return response()->json([
            'message' => 'DTLR Document Tracker data',
            'data' => []
        ]);
    }

    public function getLogisticsRecord()
    {
        return response()->json([
            'message' => 'DTLR Logistics Record data',
            'data' => []
        ]);
    }
}