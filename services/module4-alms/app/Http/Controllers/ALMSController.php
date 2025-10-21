<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class ALMSController extends Controller
{
    // Asset Management Methods

    public function getAssets(Request $request)
    {
        try {
            $assets = DB::table('alms_assets')
                ->select('*')
                ->orderBy('created_at', 'desc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $assets,
                'message' => 'Assets retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching assets: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve assets',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getAsset($id)
    {
        try {
            $asset = DB::table('alms_assets')->where('id', $id)->first();

            if (!$asset) {
                return response()->json([
                    'success' => false,
                    'message' => 'Asset not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $asset,
                'message' => 'Asset retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching asset: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve asset',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function createAsset(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'asset_name' => 'required|string|max:255',
            'asset_type' => 'required|string|in:Document,Supplies,Equipment,Furniture',
            'assigned_location' => 'required|string|max:255',
            'deployment_date' => 'required|date',
            'next_service_date' => 'nullable|date',
            'warranty_start' => 'nullable|date',
            'warranty_end' => 'nullable|date',
            'status' => 'required|string|in:Active,Issued,Under Maintenance,Re-Schedule,Replacement,Rejected',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Generate Asset ID in format AST00001
            $assetId = IdGenerator::generate([
                'table' => 'alms_assets',
                'field' => 'asset_id',
                'length' => 8,
                'prefix' => 'AST',
                'reset_on_prefix_change' => true
            ]);

            $assetData = [
                'asset_id' => $assetId,
                'asset_name' => $request->asset_name,
                'asset_type' => $request->asset_type,
                'assigned_location' => $request->assigned_location,
                'deployment_date' => $request->deployment_date,
                'next_service_date' => $request->next_service_date,
                'warranty_start' => $request->warranty_start,
                'warranty_end' => $request->warranty_end,
                'status' => $request->status,
                'description' => $request->description,
                'created_at' => now(),
                'updated_at' => now()
            ];

            $id = DB::table('alms_assets')->insertGetId($assetData);

            return response()->json([
                'success' => true,
                'message' => 'Asset created successfully',
                'data' => array_merge(['id' => $id], $assetData)
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating asset: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create asset',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateAsset(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'asset_name' => 'sometimes|required|string|max:255',
            'asset_type' => 'sometimes|required|string|in:Document,Supplies,Equipment,Furniture',
            'assigned_location' => 'sometimes|required|string|max:255',
            'deployment_date' => 'sometimes|required|date',
            'next_service_date' => 'nullable|date',
            'warranty_start' => 'nullable|date',
            'warranty_end' => 'nullable|date',
            'status' => 'sometimes|required|string|in:Active,Issued,Under Maintenance,Re-Schedule,Replacement,Rejected',
            'description' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $asset = DB::table('alms_assets')->where('id', $id)->first();

            if (!$asset) {
                return response()->json([
                    'success' => false,
                    'message' => 'Asset not found'
                ], 404);
            }

            $updateData = $request->all();
            $updateData['updated_at'] = now();

            DB::table('alms_assets')->where('id', $id)->update($updateData);

            $updatedAsset = DB::table('alms_assets')->where('id', $id)->first();

            return response()->json([
                'success' => true,
                'message' => 'Asset updated successfully',
                'data' => $updatedAsset
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating asset: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update asset',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteAsset($id)
    {
        try {
            $asset = DB::table('alms_assets')->where('id', $id)->first();

            if (!$asset) {
                return response()->json([
                    'success' => false,
                    'message' => 'Asset not found'
                ], 404);
            }

            // Check if asset has maintenance schedules
            $maintenanceCount = DB::table('alms_maintenance')
                ->where('asset_name', $asset->asset_name)
                ->count();

            if ($maintenanceCount > 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot delete asset with existing maintenance schedules'
                ], 400);
            }

            DB::table('alms_assets')->where('id', $id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Asset deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting asset: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete asset',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getAssetStats()
    {
        try {
            $stats = DB::table('alms_assets')
                ->select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get();

            $total = DB::table('alms_assets')->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'total' => $total,
                    'by_status' => $stats
                ],
                'message' => 'Asset statistics retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching asset stats: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve asset statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Maintenance Management Methods

    public function getMaintenanceSchedules(Request $request)
    {
        try {
            $schedules = DB::table('alms_maintenance')
                ->select('*')
                ->orderBy('schedule_date', 'asc')
                ->orderBy('schedule_time', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'data' => $schedules,
                'message' => 'Maintenance schedules retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching maintenance schedules: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve maintenance schedules',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getMaintenanceSchedule($id)
    {
        try {
            $schedule = DB::table('alms_maintenance')->where('id', $id)->first();

            if (!$schedule) {
                return response()->json([
                    'success' => false,
                    'message' => 'Maintenance schedule not found'
                ], 404);
            }

            return response()->json([
                'success' => true,
                'data' => $schedule,
                'message' => 'Maintenance schedule retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching maintenance schedule: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve maintenance schedule',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function createMaintenanceSchedule(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'asset_name' => 'required|string|max:255',
            'maintenance_type' => 'required|string|in:Inspection,Cleaning/Sanitization,Repair,Calibration/Testing,Replacement',
            'assigned_personnel' => 'required|string|max:255',
            'schedule_date' => 'required|date',
            'schedule_time' => 'required|string',
            'status' => 'required|string|in:Pending,Under Maintenance,Re-Schedule,Replacement,Reject,Done',
            'notes' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            // Generate Schedule ID in format SCD00001
            $scheduleId = IdGenerator::generate([
                'table' => 'alms_maintenance',
                'field' => 'schedule_id',
                'length' => 8,
                'prefix' => 'SCD',
                'reset_on_prefix_change' => true
            ]);

            $maintenanceData = [
                'schedule_id' => $scheduleId,
                'asset_name' => $request->asset_name,
                'maintenance_type' => $request->maintenance_type,
                'assigned_personnel' => $request->assigned_personnel,
                'schedule_date' => $request->schedule_date,
                'schedule_time' => $request->schedule_time,
                'status' => $request->status,
                'notes' => $request->notes,
                'created_at' => now(),
                'updated_at' => now()
            ];

            $id = DB::table('alms_maintenance')->insertGetId($maintenanceData);

            // Update corresponding asset status based on maintenance status
            $this->updateAssetStatusFromMaintenance($request->asset_name, $request->status);

            return response()->json([
                'success' => true,
                'message' => 'Maintenance schedule created successfully',
                'data' => array_merge(['id' => $id], $maintenanceData)
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating maintenance schedule: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create maintenance schedule',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function updateMaintenanceSchedule(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'asset_name' => 'sometimes|required|string|max:255',
            'maintenance_type' => 'sometimes|required|string|in:Inspection,Cleaning/Sanitization,Repair,Calibration/Testing,Replacement',
            'assigned_personnel' => 'sometimes|required|string|max:255',
            'schedule_date' => 'sometimes|required|date',
            'schedule_time' => 'sometimes|required|string',
            'status' => 'sometimes|required|string|in:Pending,Under Maintenance,Re-Schedule,Replacement,Reject,Done',
            'notes' => 'nullable|string',
            'reschedule_reason' => 'nullable|string'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $schedule = DB::table('alms_maintenance')->where('id', $id)->first();

            if (!$schedule) {
                return response()->json([
                    'success' => false,
                    'message' => 'Maintenance schedule not found'
                ], 404);
            }

            $updateData = $request->all();
            $updateData['updated_at'] = now();

            DB::table('alms_maintenance')->where('id', $id)->update($updateData);

            $updatedSchedule = DB::table('alms_maintenance')->where('id', $id)->first();

            // Update corresponding asset status based on maintenance status
            if (isset($request->status)) {
                $this->updateAssetStatusFromMaintenance($updatedSchedule->asset_name, $request->status);
            }

            return response()->json([
                'success' => true,
                'message' => 'Maintenance schedule updated successfully',
                'data' => $updatedSchedule
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating maintenance schedule: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update maintenance schedule',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function completeMaintenance(Request $request, $id)
    {
        try {
            $schedule = DB::table('alms_maintenance')->where('id', $id)->first();

            if (!$schedule) {
                return response()->json([
                    'success' => false,
                    'message' => 'Maintenance schedule not found'
                ], 404);
            }

            DB::table('alms_maintenance')->where('id', $id)->update([
                'status' => 'Done',
                'updated_at' => now()
            ]);

            // Update corresponding asset status to Active when maintenance is done
            $this->updateAssetStatusFromMaintenance($schedule->asset_name, 'Done');

            return response()->json([
                'success' => true,
                'message' => 'Maintenance marked as completed successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error completing maintenance: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to complete maintenance',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteMaintenanceSchedule($id)
    {
        try {
            $schedule = DB::table('alms_maintenance')->where('id', $id)->first();

            if (!$schedule) {
                return response()->json([
                    'success' => false,
                    'message' => 'Maintenance schedule not found'
                ], 404);
            }

            DB::table('alms_maintenance')->where('id', $id)->delete();

            return response()->json([
                'success' => true,
                'message' => 'Maintenance schedule deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting maintenance schedule: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete maintenance schedule',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function getMaintenanceStats()
    {
        try {
            $stats = DB::table('alms_maintenance')
                ->select('status', DB::raw('count(*) as count'))
                ->groupBy('status')
                ->get();

            $total = DB::table('alms_maintenance')->count();

            return response()->json([
                'success' => true,
                'data' => [
                    'total' => $total,
                    'by_status' => $stats
                ],
                'message' => 'Maintenance statistics retrieved successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching maintenance stats: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve maintenance statistics',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Helper method to update asset status based on maintenance status
    private function updateAssetStatusFromMaintenance($assetName, $maintenanceStatus)
    {
        $statusMapping = [
            'Pending' => 'Issued',
            'Under Maintenance' => 'Under Maintenance',
            'Re-Schedule' => 'Re-Schedule',
            'Replacement' => 'Replacement',
            'Reject' => 'Rejected',
            'Done' => 'Active'
        ];

        $assetStatus = $statusMapping[$maintenanceStatus] ?? 'Active';

        DB::table('alms_assets')
            ->where('asset_name', $assetName)
            ->update([
                'status' => $assetStatus,
                'updated_at' => now()
            ]);
    }
}