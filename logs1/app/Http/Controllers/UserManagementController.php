<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmployeeAccount;
use App\Models\VendorAccount;
use App\Models\MAIN\User;
use App\Models\MAIN\Vendor;
use Illuminate\Support\Facades\Hash;

class UserManagementController extends Controller
{
    public function getAccounts(Request $request)
    {
        $type = $request->query('type', 'employee'); // Default to employee
        $search = $request->query('search');

        if ($type === 'vendor') {
            $query = VendorAccount::query();
        } else {
            $query = EmployeeAccount::query();
        }

        if ($search) {
            $query->where(function($q) use ($search, $type) {
                if ($type === 'vendor') {
                    $q->where('vendorid', 'like', "%{$search}%")
                      ->orWhere('lastname', 'like', "%{$search}%")
                      ->orWhere('firstname', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                } else {
                    $q->where('employeeid', 'like', "%{$search}%")
                      ->orWhere('lastname', 'like', "%{$search}%")
                      ->orWhere('firstname', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                }
            });
        }

        $accounts = $query->orderBy('created_at', 'desc')->paginate(10);

        return response()->json($accounts);
    }

    public function getStats()
    {
        $employeeCount = EmployeeAccount::count();
        $vendorCount = VendorAccount::count();
        $activeCount = EmployeeAccount::where('status', 'active')->count() + VendorAccount::where('status', 'active')->count();
        $inactiveCount = EmployeeAccount::where('status', 'inactive')->count() + VendorAccount::where('status', 'inactive')->count();

        return response()->json([
            'total_employee' => $employeeCount,
            'total_vendor' => $vendorCount,
            'active' => $activeCount,
            'inactive' => $inactiveCount
        ]);
    }

    public function createEmployee(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:employee_account,email|unique:users,email',
            'role' => 'required|string',
            'status' => 'required|in:active,inactive',
            'password' => 'required|string|min:6',
        ]);

        $id = $this->generateUniqueId('EMP');

        // Create User first to allow login
        $user = new User();
        $user->employeeid = $id;
        $user->firstname = $request->firstname;
        $user->middlename = $request->middlename;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->roles = $request->role;
        $user->status = $request->status;
        // Fill required fields with defaults if necessary
        $user->sex = 'male'; // Default or add to form
        $user->age = 0;
        $user->birthdate = now();
        $user->contactnum = '0000000000';
        $user->save();

        $account = new EmployeeAccount();
        $account->user_id = $user->id;
        $account->employeeid = $id;
        $account->firstname = $request->firstname;
        $account->middlename = $request->middlename;
        $account->lastname = $request->lastname;
        $account->email = $request->email;
        $account->password = $request->password; // Save plain text as requested
        $account->roles = $request->role;
        $account->status = $request->status;
        $account->save();

        return response()->json(['message' => 'Employee created successfully']);
    }

    public function createVendor(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string|max:255',
            'middlename' => 'nullable|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|email|unique:vendor_account,email|unique:vendors,email',
            'status' => 'required|in:active,inactive',
            'password' => 'required|string|min:6',
        ]);

        $id = $this->generateUniqueId('VEN');

        // Create Vendor first
        $vendor = new Vendor();
        $vendor->vendorid = $id;
        $vendor->firstname = $request->firstname;
        $vendor->middlename = $request->middlename;
        $vendor->lastname = $request->lastname;
        $vendor->email = $request->email;
        $vendor->password = Hash::make($request->password);
        $vendor->roles = 'Vendor';
        $vendor->status = $request->status;
        // Fill required fields
        $vendor->sex = 'male';
        $vendor->age = 0;
        $vendor->birthdate = now();
        $vendor->contactnum = '0000000000';
        $vendor->save();

        $account = new VendorAccount();
        $account->vendor_id = $vendor->id;
        $account->vendorid = $id;
        $account->firstname = $request->firstname;
        $account->middlename = $request->middlename;
        $account->lastname = $request->lastname;
        $account->email = $request->email;
        $account->password = $request->password; // Save plain text as requested
        $account->roles = 'Vendor'; // Default role for vendors
        $account->status = $request->status;
        $account->save();

        return response()->json(['message' => 'Vendor created successfully']);
    }

    private function generateUniqueId($prefix)
    {
        $date = now()->format('Ymd');
        $random = strtoupper(substr(str_shuffle('0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'), 0, 5));
        return $prefix . $date . $random;
    }

    public function updateRole(Request $request, $id)
    {
        $request->validate([
            'role' => 'required|string'
        ]);

        $account = EmployeeAccount::findOrFail($id);
        $account->roles = $request->role;
        $account->save();

        if ($account->user) {
            $account->user->roles = $request->role;
            $account->user->save();
        }

        return response()->json(['message' => 'Role updated successfully']);
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:active,inactive',
            'type' => 'required|in:employee,vendor'
        ]);

        if ($request->type === 'employee') {
            $account = EmployeeAccount::findOrFail($id);
            $account->status = $request->status;
            $account->save();

            if ($account->user) {
                $account->user->status = $request->status;
                $account->user->save();
            }
        } else {
            $account = VendorAccount::findOrFail($id);
            $account->status = $request->status;
            $account->save();

            if ($account->vendor) {
                $account->vendor->status = $request->status;
                $account->vendor->save();
            }
        }

        return response()->json(['message' => 'Status updated successfully']);
    }

    public function destroy(Request $request, $id)
    {
        $type = $request->query('type', 'employee');

        if ($type === 'employee') {
            $account = EmployeeAccount::findOrFail($id);
            if ($account->user) {
                $account->user->delete();
            }
            $account->delete();
        } else {
            $account = VendorAccount::findOrFail($id);
            if ($account->vendor) {
                $account->vendor->delete();
            }
            $account->delete();
        }

        return response()->json(['message' => 'Account deleted successfully']);
    }

    public function show(Request $request, $id)
    {
        $type = $request->query('type', 'employee');

        if ($type === 'employee') {
            $account = EmployeeAccount::findOrFail($id);
        } else {
            $account = VendorAccount::findOrFail($id);
        }

        return response()->json($account);
    }
}
