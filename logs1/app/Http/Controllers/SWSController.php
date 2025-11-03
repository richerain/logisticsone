<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SWSController extends Controller
{
    public function getInventoryFlow()
    {
        return response()->json([
            'message' => 'SWS Inventory Flow data',
            'data' => []
        ]);
    }

    public function getDigitalInventory()
    {
        return response()->json([
            'message' => 'SWS Digital Inventory data',
            'data' => []
        ]);
    }
}