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
use Illuminate\Support\Facades\Storage;
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

            // Generate ALMS ID: ALMS00001 format
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

            // Generate Schedule ID: MS00001 format
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
            
            // Generate Record ID: MR00001 format
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

    // ==================== ASSET TRANSFERS ====================

    public function getAssetTransfers(Request $request)
    {
        try {
            $query = AssetTransfer::with(['asset', 'fromBranch', 'toBranch'])
                ->orderBy('transfer_date', 'desc');

            // Search
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('transfer_id', 'like', "%{$search}%")
                      ->orWhereHas('asset', function($q) use ($search) {
                          $q->where('alms_id', 'like', "%{$search}%")
                            ->orWhere('serial_number', 'like', "%{$search}%")
                            ->orWhere('name', 'like', "%{$search}%");
                      });
                });
            }

            $transfers = $query->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $transfers,
                'message' => 'Asset transfers retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve asset transfers: ' . $e->getMessage()
            ], 500);
        }
    }

    public function createAssetTransfer(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'asset_id' => 'required|exists:alms_assets,id',
                'from_branch_id' => 'required|exists:alms_branches,id',
                'to_branch_id' => 'required|exists:alms_branches,id|different:from_branch_id',
                'transfer_date' => 'required|date',
                'reason' => 'required|string',
                'notes' => 'nullable|string'
            ]);

            // Generate Transfer ID: AT00001 format
            $transferId = IdGenerator::generate([
                'table' => 'alms_asset_transfers',
                'field' => 'transfer_id',
                'length' => 6,
                'prefix' => 'AT'
            ]);

            $transfer = AssetTransfer::create([
                'transfer_id' => $transferId,
                'asset_id' => $request->asset_id,
                'from_branch_id' => $request->from_branch_id,
                'to_branch_id' => $request->to_branch_id,
                'transfer_date' => $request->transfer_date,
                'reason' => $request->reason,
                'notes' => $request->notes
            ]);

            // Update asset's current branch
            $asset = Asset::find($request->asset_id);
            $asset->update(['current_branch_id' => $request->to_branch_id]);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $transfer->load(['asset', 'fromBranch', 'toBranch']),
                'message' => 'Asset transfer created successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create asset transfer: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==================== DISPOSAL MANAGEMENT ====================

    public function getDisposals(Request $request)
    {
        try {
            $query = Disposal::with(['asset', 'asset.currentBranch'])
                ->orderBy('disposal_date', 'desc');

            // Search
            if ($request->has('search') && $request->search != '') {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('disposal_id', 'like', "%{$search}%")
                      ->orWhereHas('asset', function($q) use ($search) {
                          $q->where('alms_id', 'like', "%{$search}%")
                            ->orWhere('serial_number', 'like', "%{$search}%")
                            ->orWhere('name', 'like', "%{$search}%");
                      });
                });
            }

            // Filter by method
            if ($request->has('method') && $request->method != '') {
                $query->where('method', $request->method);
            }

            $disposals = $query->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $disposals,
                'message' => 'Disposals retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve disposals: ' . $e->getMessage()
            ], 500);
        }
    }

    public function createDisposal(Request $request)
    {
        try {
            DB::beginTransaction();

            $request->validate([
                'asset_id' => 'required|exists:alms_assets,id|unique:alms_disposals,asset_id',
                'disposal_date' => 'required|date',
                'method' => 'required|in:decommission,disposal,resale',
                'disposal_value' => 'nullable|numeric|min:0',
                'reason' => 'required|string',
                'compliance_notes' => 'nullable|string'
            ]);

            // Generate Disposal ID: DS00001 format
            $disposalId = IdGenerator::generate([
                'table' => 'alms_disposals',
                'field' => 'disposal_id',
                'length' => 6,
                'prefix' => 'DS'
            ]);

            $disposal = Disposal::create([
                'disposal_id' => $disposalId,
                'asset_id' => $request->asset_id,
                'disposal_date' => $request->disposal_date,
                'method' => $request->method,
                'disposal_value' => $request->disposal_value,
                'reason' => $request->reason,
                'compliance_notes' => $request->compliance_notes
            ]);

            // Update asset status to disposed
            $asset = Asset::find($request->asset_id);
            $asset->update(['status' => 'disposed']);

            DB::commit();

            return response()->json([
                'success' => true,
                'data' => $disposal->load(['asset']),
                'message' => 'Asset disposal recorded successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Failed to create disposal record: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==================== ASSET CATEGORIES ====================

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

    public function createAssetCategory(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:alms_asset_categories,name',
                'description' => 'nullable|string',
                'maintenance_frequency' => 'nullable|in:monthly,yearly,km_based'
            ]);

            $category = AssetCategory::create([
                'name' => $request->name,
                'description' => $request->description,
                'maintenance_frequency' => $request->maintenance_frequency
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Asset category created successfully',
                'data' => $category
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create asset category: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==================== BRANCHES ====================

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

    public function createBranch(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:alms_branches,name',
                'address' => 'nullable|string',
                'code' => 'required|string|max:50|unique:alms_branches,code'
            ]);

            $branch = Branch::create([
                'name' => $request->name,
                'address' => $request->address,
                'code' => $request->code
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Branch created successfully',
                'data' => $branch
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create branch: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==================== EMPLOYEES ====================

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

    public function createEmployee(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:alms_employees,email',
                'position' => 'nullable|string|max:100',
                'branch_id' => 'required|exists:alms_branches,id'
            ]);

            $employee = Employee::create([
                'name' => $request->name,
                'email' => $request->email,
                'position' => $request->position,
                'branch_id' => $request->branch_id
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Employee created successfully',
                'data' => $employee->load('branch')
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create employee: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==================== MAINTENANCE TYPES ====================

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

    public function createMaintenanceType(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required|string|max:255|unique:alms_maintenance_types,name',
                'frequency_unit' => 'required|string|max:20',
                'estimated_cost' => 'nullable|numeric|min:0'
            ]);

            $maintenanceType = MaintenanceType::create([
                'name' => $request->name,
                'frequency_unit' => $request->frequency_unit,
                'estimated_cost' => $request->estimated_cost
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Maintenance type created successfully',
                'data' => $maintenanceType
            ], 201);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create maintenance type: ' . $e->getMessage()
            ], 500);
        }
    }

    // ==================== REPORTS & ANALYTICS ====================

    public function getReports(Request $request)
    {
        try {
            $reportType = $request->get('type', 'overview');
            
            switch ($reportType) {
                case 'asset_status':
                    $data = $this->getAssetStatusReport();
                    break;
                case 'maintenance_analytics':
                    $data = $this->getMaintenanceAnalytics();
                    break;
                case 'cost_analysis':
                    $data = $this->getCostAnalysis();
                    break;
                case 'branch_assets':
                    $data = $this->getBranchAssetsReport();
                    break;
                default:
                    $data = $this->getOverviewReport();
            }

            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Report generated successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to generate report: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getOverviewReport()
    {
        $totalAssets = Asset::count();
        $totalValue = Asset::sum('acquisition_cost');
        $activeAssets = Asset::where('status', 'active')->count();
        $maintenanceAssets = Asset::where('status', 'in_maintenance')->count();
        $disposedAssets = Asset::where('status', 'disposed')->count();
        
        $assetsByCategory = Asset::with('category')
            ->selectRaw('category_id, COUNT(*) as count')
            ->groupBy('category_id')
            ->get()
            ->map(function($item) {
                return [
                    'category' => $item->category->name,
                    'count' => $item->count
                ];
            });

        $recentMaintenance = MaintenanceRecord::with(['asset', 'schedule'])
            ->orderBy('performed_date', 'desc')
            ->limit(10)
            ->get();

        return [
            'total_assets' => $totalAssets,
            'total_value' => $totalValue,
            'active_assets' => $activeAssets,
            'maintenance_assets' => $maintenanceAssets,
            'disposed_assets' => $disposedAssets,
            'assets_by_category' => $assetsByCategory,
            'recent_maintenance' => $recentMaintenance
        ];
    }

    private function getAssetStatusReport()
    {
        return Asset::with(['category', 'currentBranch'])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();
    }

    private function getMaintenanceAnalytics()
    {
        $totalMaintenance = MaintenanceRecord::count();
        $totalMaintenanceCost = MaintenanceRecord::sum('cost');
        $avgMaintenanceCost = MaintenanceRecord::avg('cost');
        
        $maintenanceByMonth = MaintenanceRecord::selectRaw('YEAR(performed_date) as year, MONTH(performed_date) as month, COUNT(*) as count, SUM(cost) as total_cost')
            ->groupBy('year', 'month')
            ->orderBy('year', 'desc')
            ->orderBy('month', 'desc')
            ->limit(12)
            ->get();

        return [
            'total_maintenance' => $totalMaintenance,
            'total_maintenance_cost' => $totalMaintenanceCost,
            'avg_maintenance_cost' => $avgMaintenanceCost,
            'maintenance_by_month' => $maintenanceByMonth
        ];
    }

    private function getCostAnalysis()
    {
        $totalAcquisitionCost = Asset::sum('acquisition_cost');
        $totalMaintenanceCost = MaintenanceRecord::sum('cost');
        $totalDisposalValue = Disposal::sum('disposal_value');
        
        $costByCategory = Asset::with('category')
            ->selectRaw('category_id, SUM(acquisition_cost) as total_cost')
            ->groupBy('category_id')
            ->get()
            ->map(function($item) {
                return [
                    'category' => $item->category->name,
                    'total_cost' => $item->total_cost
                ];
            });

        return [
            'total_acquisition_cost' => $totalAcquisitionCost,
            'total_maintenance_cost' => $totalMaintenanceCost,
            'total_disposal_value' => $totalDisposalValue,
            'cost_by_category' => $costByCategory
        ];
    }

    private function getBranchAssetsReport()
    {
        return Asset::with('currentBranch')
            ->selectRaw('current_branch_id, COUNT(*) as asset_count, SUM(acquisition_cost) as total_value')
            ->groupBy('current_branch_id')
            ->get()
            ->map(function($item) {
                return [
                    'branch' => $item->currentBranch->name,
                    'asset_count' => $item->asset_count,
                    'total_value' => $item->total_value
                ];
            });
    }
}