<?php

namespace App\Http\Controllers;

use App\Models\Main\Announcement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard.index');
    }

    public function fetchAnnouncements(Request $request)
    {
        try {
            $query = Announcement::orderBy('created_date', 'desc');
            $announcements = $query->paginate(3);

            return response()->json([
                'success' => true,
                'data' => $announcements->items(),
                'pagination' => [
                    'current_page' => $announcements->currentPage(),
                    'last_page' => $announcements->lastPage(),
                    'total' => $announcements->total(),
                    'per_page' => $announcements->perPage(),
                    'has_more_pages' => $announcements->hasMorePages(),
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching announcements: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to fetch announcements'], 500);
        }
    }

    public function storeAnnouncement(Request $request)
    {
        // Role check
        $user = Auth::guard('sws')->user();
        if (!$user) {
             return response()->json(['success' => false, 'message' => 'Unauthorized'], 401);
        }
        
        $role = strtolower($user->roles ?? '');
        if (!in_array($role, ['superadmin', 'admin'])) {
            return response()->json(['success' => false, 'message' => 'Unauthorized action'], 403);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'desc' => 'required|string',
            'announcement_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()], 422);
        }

        try {
            $imagePath = null;
            if ($request->hasFile('announcement_image')) {
                $image = $request->file('announcement_image');
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('images/announcement'), $imageName);
                $imagePath = 'images/announcement/' . $imageName;
            }

            $announcement = Announcement::create([
                'title' => $request->title,
                'desc' => $request->desc,
                'announcement_image' => $imagePath,
            ]);

            return response()->json(['success' => true, 'message' => 'Announcement created successfully', 'data' => $announcement]);

        } catch (\Exception $e) {
            Log::error('Error creating announcement: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to create announcement'], 500);
        }
    }
}
