<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function update(Request $request)
    {
        Log::info('Profile update request', $request->all());

        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id',
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'Email' => 'required|email|max:255|unique:users,Email,' . $request->id,
            'contactnum' => 'required|string|max:20',
            'sex' => 'required|in:M,F',
            'age' => 'required|integer|min:1|max:120',
            'birthdate' => 'required|date',
        ], [
            'Email.unique' => 'This email is already taken by another user.',
            'birthdate.date' => 'The birthdate must be a valid date.',
        ]);

        if ($validator->fails()) {
            Log::warning('Profile validation failed', ['errors' => $validator->errors()]);
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::find($request->id);
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            // Format birthdate properly
            $birthdate = Carbon::parse($request->birthdate)->format('Y-m-d');

            $user->update([
                'firstname' => $request->firstname,
                'lastname' => $request->lastname,
                'middlename' => $request->middlename,
                'Email' => $request->Email,
                'contactnum' => $request->contactnum,
                'sex' => $request->sex,
                'age' => $request->age,
                'birthdate' => $birthdate,
            ]);

            Log::info('Profile updated successfully', ['user_id' => $user->id]);

            // Return updated user data
            $updatedUser = [
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

            return response()->json([
                'success' => true,
                'message' => 'Profile updated successfully',
                'user' => $updatedUser
            ]);

        } catch (\Exception $e) {
            Log::error('Profile update error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to update profile: ' . $e->getMessage()
            ], 500);
        }
    }

    public function uploadPicture(Request $request)
    {
        Log::info('Profile picture upload request');

        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:users,id',
            'profile_picture' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $user = User::find($request->id);
            
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found'
                ], 404);
            }

            // Delete old profile picture if exists
            if ($user->profile_picture && Storage::exists($user->profile_picture)) {
                Storage::delete($user->profile_picture);
            }

            // Store new profile picture
            $path = $request->file('profile_picture')->store('profile-pictures', 'public');
            $user->update(['profile_picture' => $path]);

            Log::info('Profile picture uploaded successfully', ['user_id' => $user->id]);

            // Return updated user data
            $updatedUser = [
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

            return response()->json([
                'success' => true,
                'message' => 'Profile picture updated successfully',
                'profile_picture_url' => Storage::url($path),
                'user' => $updatedUser
            ]);

        } catch (\Exception $e) {
            Log::error('Profile picture upload error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload profile picture'
            ], 500);
        }
    }
}