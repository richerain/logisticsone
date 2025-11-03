<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ALMSController extends Controller
{
    public function getAssets()
    {
        return response()->json([
            'message' => 'ALMS Assets data',
            'data' => []
        ]);
    }

    public function getMaintenance()
    {
        return response()->json([
            'message' => 'ALMS Maintenance data',
            'data' => []
        ]);
    }
}