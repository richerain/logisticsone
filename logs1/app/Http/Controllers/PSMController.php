<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PSMController extends Controller
{
    public function getPurchases()
    {
        return response()->json([
            'message' => 'PSM Purchases data',
            'data' => []
        ]);
    }

    public function getVendorQuotes()
    {
        return response()->json([
            'message' => 'PSM Vendor Quotes data',
            'data' => []
        ]);
    }

    public function getVendors()
    {
        return response()->json([
            'message' => 'PSM Vendors data',
            'data' => []
        ]);
    }
}