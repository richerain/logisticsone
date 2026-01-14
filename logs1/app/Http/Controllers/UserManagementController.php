<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EmployeeAccount;
use App\Models\VendorAccount;
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
            'email' => 'required|email|unique:employee_account,email',
            'role' => 'required|string',
            'status' => 'required|in:active,inactive',
            'password' => 'required|string|min:6',
        ]);

        $id = $this->generateUniqueId('EMP');

        $account = new EmployeeAccount();
        $account->employeeid = $id;
        $account->firstname = $request->firstname;
        $account->middlename = $request->middlename;
        $account->lastname = $request->lastname;
        $account->sex = 'male';
        $account->age = 0;
        $account->birthdate = now();
        $account->contactnum = '0000000000';
        $account->email = $request->email;
        $account->password = Hash::make($request->password);
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
            'email' => 'required|email|unique:vendor_account,email',
            'status' => 'required|in:active,inactive',
            'password' => 'required|string|min:6',
        ]);

        $id = $this->generateUniqueId('VEN');

        $account = new VendorAccount();
        $account->vendorid = $id;
        $account->firstname = $request->firstname;
        $account->middlename = $request->middlename;
        $account->lastname = $request->lastname;
        $account->email = $request->email;
        $account->password = Hash::make($request->password);
        $account->roles = 'Vendor';
        $account->status = $request->status;
        $account->sex = 'male';
        $account->age = 0;
        $account->birthdate = now();
        $account->contactnum = '0000000000';
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
        } else {
            $account = VendorAccount::findOrFail($id);
            $account->status = $request->status;
            $account->save();
        }

        return response()->json(['message' => 'Status updated successfully']);
    }

    public function destroy(Request $request, $id)
    {
        $type = $request->query('type', 'employee');

        if ($type === 'employee') {
            $account = EmployeeAccount::findOrFail($id);
            $account->delete();
        } else {
            $account = VendorAccount::findOrFail($id);
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
