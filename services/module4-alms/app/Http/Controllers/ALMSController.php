<?php

namespace App\Http\Controllers;

use App\Models\Asset;
use App\Models\AssetCategory;
use App\Models\Branch;
use App\Models\Employee;
use App\Models\MaintenanceSchedule;
use App\Models\MaintenanceType;
use App\Models\MaintenanceRecord;
use App\Models\AssetTransfer;
use App\Models\Disposal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class ALMSController extends Controller
{
    // ==================== ASSET MANAGEMENT ====================
    
    public function getAssets(Request $request)
    {
        try {
            $query = Asset::with(['category', 'currentBranch', 'assignedEmployee'])
                ->orderBy('created_at', 'desc');

            // Search
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('alms_id', 'like', "%{$search}%")
                      ->orWhere('serial_number', 'like', "%{$search}%")
                      ->orWhere('name', 'like', "%{$search}%")
                      ->orWhereHas('category', function($q) use ($search) {
                          $q->where('name', 'like', "%{$search}%");
                      });
                });
            }

            // Filter by status
            if ($request->has('status') && $request->status != '') {
                $query->where('status', $request->status);
            }

            // Filter by category
            if ($request->has('category') && $request->category != '') {
                $query->where('category_id', $request->category);
            }

            $assets = $query->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $assets,
                'message' => 'Assets retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve assets: ' . $e->getMessage()
            ], 500);
        }
    }

    public function createAsset(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'serial_number' => 'required|unique:alms_assets,serial_number',
                'name' => 'required|string|max:255',
                'category_id' => 'required|exists:alms_asset_categories,id',
                'acquisition_date' => 'required|date',
                'acquisition_cost' => 'required|numeric|min:0',
                'current_branch_id' => 'required|exists:alms_branches,id',
                'assigned_employee_id' => 'nullable|exists:alms_employees,id',
                'description' => 'nullable|string'
            ]);

            $almsId = IdGenerator::generate([
                'table' => 'alms_assets',
                'field' => 'alms_id',
                'length' => 8,
                'prefix' => 'ALMS'
            ]);

            $asset = Asset::create([
                'alms_id' => $almsId,
                'serial_number' => $request->serial_number,
                'name' => $request->name,
                'category_id' => $request->category_id,
                'acquisition_date' => $request->acquisition_date,
                'acquisition_cost' => $request->acquisition_cost,
                'current_branch_id' => $request->current_branch_id,
                'assigned_employee_id' => $request->assigned_employee_id,
                'description' => $request->description,
                'status' => 'active'
            ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $asset->load(['category', 'currentBranch', 'assignedEmployee']),
                'message' => 'Asset created successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create asset: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateAsset(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $asset = Asset::findOrFail($id);

            $request->validate([
                'serial_number' => 'required|unique:alms_assets,serial_number,' . $id,
                'name' => 'required|string|max:255',
                'category_id' => 'required|exists:alms_asset_categories,id',
                'acquisition_date' => 'required|date',
                'acquisition_cost' => 'required|numeric|min:0',
                'current_branch_id' => 'required|exists:alms_branches,id',
                'assigned_employee_id' => 'nullable|exists:alms_employees,id',
                'status' => 'required|in:active,in_maintenance,disposed',
                'description' => 'nullable|string'
            ]);

            $asset->update($request->all());

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $asset->load(['category', 'currentBranch', 'assignedEmployee']),
                'message' => 'Asset updated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update asset: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteAsset($id)
    {
        try {
            $asset = Asset::findOrFail($id);
            $asset->delete();

            return response()->json([
                'success' => true,
                'message' => 'Asset deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete asset: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getAssetStats()
    {
        try {
            $totalAssets = Asset::count();
            $activeAssets = Asset::where('status', 'active')->count();
            $maintenanceAssets = Asset::where('status', 'in_maintenance')->count();
            $disposedAssets = Asset::where('status', 'disposed')->count();
            $overdueMaintenance = MaintenanceSchedule::where('is_overdue', true)->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_assets' => $totalAssets,
                    'active_assets' => $activeAssets,
                    'maintenance_assets' => $maintenanceAssets,
                    'disposed_assets' => $disposedAssets,
                    'overdue_maintenance' => $overdueMaintenance
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve stats: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==================== MAINTENANCE SCHEDULING ====================

    public function getMaintenanceSchedules(Request $request)
    {
        try {
            $query = MaintenanceSchedule::with(['asset', 'maintenanceType', 'asset.currentBranch'])
                ->orderBy('due_date', 'asc');

            // Search
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('schedule_id', 'like', "%{$search}%")
                      ->orWhereHas('asset', function($q) use ($search) {
                          $q->where('alms_id', 'like', "%{$search}%")
                            ->orWhere('serial_number', 'like', "%{$search}%")
                            ->orWhere('name', 'like', "%{$search}%");
                      });
                });
            }

            // Filter by status
            if ($request->has('status') && $request->status != '') {
                $query->where('status', $request->status);
            }

            // Filter by overdue
            if ($request->has('is_overdue') && $request->is_overdue != '') {
                $query->where('is_overdue', $request->is_overdue);
            }

            $schedules = $query->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $schedules,
                'message' => 'Maintenance schedules retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve maintenance schedules: ' . $e->getMessage()
            ], 500);
        }
    }

    public function createMaintenanceSchedule(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'asset_id' => 'required|exists:alms_assets,id',
                'maintenance_type_id' => 'required|exists:alms_maintenance_types,id',
                'due_date' => 'required|date',
                'frequency_value' => 'required|integer|min:1'
            ]);

            $scheduleId = IdGenerator::generate([
                'table' => 'alms_maintenance_schedules',
                'field' => 'schedule_id',
                'length' => 6,
                'prefix' => 'MS'
            ]);

            $schedule = MaintenanceSchedule::create([
                'schedule_id' => $scheduleId,
                'asset_id' => $request->asset_id,
                'maintenance_type_id' => $request->maintenance_type_id,
                'due_date' => $request->due_date,
                'frequency_value' => $request->frequency_value,
                'status' => 'pending'
            ]);

            // Update asset status if needed
            $asset = Asset::find($request->asset_id);
            if ($asset && $asset->status === 'active') {
                $asset->update(['status' => 'in_maintenance']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $schedule->load(['asset', 'maintenanceType']),
                'message' => 'Maintenance schedule created successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create maintenance schedule: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateMaintenanceSchedule(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $schedule = MaintenanceSchedule::findOrFail($id);

            $request->validate([
                'due_date' => 'required|date',
                'frequency_value' => 'required|integer|min:1',
                'status' => 'required|in:pending,in_progress,completed,cancelled'
            ]);

            $schedule->update($request->all());

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $schedule->load(['asset', 'maintenanceType']),
                'message' => 'Maintenance schedule updated successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to update maintenance schedule: ' . $e->getMessage()
            ], 500);
        }
    }

    public function completeMaintenance(Request $request, $id)
    {
        try {
            DB::beginTransaction();

            $schedule = MaintenanceSchedule::findOrFail($id);
            $recordId = IdGenerator::generate([
                'table' => 'alms_maintenance_records',
                'field' => 'record_id',
                'length' => 6,
                'prefix' => 'MR'
            ]);

            $maintenanceRecord = MaintenanceRecord::create([
                'record_id' => $recordId,
                'asset_id' => $schedule->asset_id,
                'schedule_id' => $schedule->id,
                'performed_date' => now(),
                'cost' => $request->cost,
                'description' => $request->description,
                'performed_by' => $request->performed_by
            ]);

            // Update schedule
            $schedule->update([
                'status' => 'completed',
                'last_maintained_date' => now(),
                'is_overdue' => false,
                'due_date' => now()->addMonths($schedule->frequency_value) // Example for monthly
            ]);

            // Update asset status back to active
            $asset = Asset::find($schedule->asset_id);
            if ($asset) {
                $asset->update(['status' => 'active']);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $maintenanceRecord,
                'message' => 'Maintenance completed successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to complete maintenance: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getMaintenanceStats()
    {
        try {
            $totalSchedules = MaintenanceSchedule::count();
            $pendingSchedules = MaintenanceSchedule::where('status', 'pending')->count();
            $overdueSchedules = MaintenanceSchedule::where('is_overdue', true)->count();
            $completedThisMonth = MaintenanceRecord::whereMonth('performed_date', now()->month)
                ->whereYear('performed_date', now()->year)
                ->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'total_schedules' => $totalSchedules,
                    'pending_schedules' => $pendingSchedules,
                    'overdue_schedules' => $overdueSchedules,
                    'completed_this_month' => $completedThisMonth
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve maintenance stats: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==================== SUPPORTING DATA ====================

    public function getBranches()
    {
        try {
            $branches = Branch::orderBy('name')->get();
            return response()->json([
                'success' => true,
                'data' => $branches
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve branches'
            ], 500);
        }
    }

    public function getEmployees()
    {
        try {
            $employees = Employee::with('branch')->orderBy('name')->get();
            return response()->json([
                'success' => true,
                'data' => $employees
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve employees'
            ], 500);
        }
    }

    public function getAssetCategories()
    {
        try {
            $categories = AssetCategory::orderBy('name')->get();
            return response()->json([
                'success' => true,
                'data' => $categories
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve asset categories'
            ], 500);
        }
    }

    public function getMaintenanceTypes()
    {
        try {
            $types = MaintenanceType::orderBy('name')->get();
            return response()->json([
                'success' => true,
                'data' => $types
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve maintenance types'
            ], 500);
        }
    }
}