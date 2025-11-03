<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PLTController extends Controller
{
    public function getProjects()
    {
        return response()->json([
            'message' => 'PLT Projects data',
            'data' => []
        ]);
    }
}