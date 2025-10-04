<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        Log::info('Auth login hit', $request->all());

        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::where('Email', $request->email)
                    ->where('status', 'active')
                    ->first();

        if (!$user) {
            Log::warning('User not found or inactive', ['email' => $request->email]);
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password.'
            ], 401);
        }

        if (!Hash::check($request->password, $user->password)) {
            Log::warning('Invalid password attempt', ['email' => $request->email]);
            return response()->json([
                'success' => false,
                'message' => 'Invalid email or password.'
            ], 401);
        }

        $userData = [
            'id' => $user->id,
            'employee_id' => $user->employee_id,
            'firstname' => $user->firstname,
            'lastname' => $user->lastname,
            'middlename' => $user->middlename,
            'Email' => $user->Email,
            'roles' => $user->roles,
            'status' => $user->status,
            'sex' => $user->sex,
            'age' => $user->age,
            'contactnum' => $user->contactnum,
            'birthdate' => $user->birthdate,
            'profile_picture' => $user->profile_picture,
        ];

        Log::info('Login successful', ['user_id' => $user->id, 'roles' => $user->roles]);

        return response()->json([
            'success' => true,
            'user' => $userData,
            'message' => 'Login successful'
        ]);
    }
}