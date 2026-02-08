<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class ALMSController extends Controller
{
    public function getAssets()
    {
        // 1. Sync from SWS (Local)
        try {
            $items = DB::connection('sws')->table('sws_items as i')
                ->leftJoin('sws_categories as c', 'c.cat_id', '=', 'i.item_category_id')
                ->select('i.item_name', 'i.item_stored_from', 'c.cat_name as category')
                ->orderByDesc('i.item_created_at')
                ->get();

            foreach ($items as $it) {
                $exists = DB::connection('alms')->table('alms_assets')
                    ->where('asset_name', $it->item_name)
                    ->where('asset_location', $it->item_stored_from)
                    ->exists();
                if ($exists) {
                    continue;
                }

                $code = 'AST'.now()->format('Ymd').strtoupper(Str::random(5));
                DB::connection('alms')->table('alms_assets')->insert([
                    'asset_code' => $code,
                    'asset_name' => $it->item_name,
                    'asset_category' => $it->category,
                    'asset_location' => $it->item_stored_from,
                    'asset_status' => 'operational',
                    'last_maintenance' => now()->toDateString(),
                    'next_maintenance' => now()->toDateString(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        } catch (\Exception $e) {
            // If SWS not accessible, continue
        }

        // 2. Sync from External Vehicles API
        try {
            $response = Http::timeout(5)->get('https://log2.microfinancial-1.com/api/vehicles_api.php', [
                'key' => 'd4f8a9b3c6e2f1a4b7d9e0c3f2a1b4d6'
            ]);

            if ($response->successful()) {
                $vData = $response->json();
                $vehicles = $vData['data'] ?? [];
                
                foreach ($vehicles as $v) {
                    // Map Status
                    $statusMap = [
                        'Active' => 'operational',
                        'Inactive' => 'out_of_service',
                        'Maintenance' => 'under_maintenance'
                    ];
                    $dbStatus = $statusMap[$v['status'] ?? ''] ?? 'operational';

                    // Use vehicle + plate for unique name check to avoid duplicates
                    // User requested asset_name = "Toyota Hiace" (from vehicle field)
                    // But to ensure uniqueness we must check if we already imported this specific plate.
                    // We will check by asset_name matching the pattern or check via a unique identifier if we stored one.
                    // Since we don't have external_id column, we'll construct a unique name.
                    // "Toyota Hiace - ABC-1234"
                    $uniqueName = ($v['vehicle'] ?? 'Unknown') . ' - ' . ($v['plate'] ?? 'NoPlate');
                    
                    $exists = DB::connection('alms')->table('alms_assets')
                        ->where('asset_name', $uniqueName)
                        ->where('asset_category', 'Vehicle')
                        ->exists();

                    if ($exists) {
                        continue;
                    }

                    $code = 'AST'.now()->format('Ymd').strtoupper(Str::random(5));
                    
                    DB::connection('alms')->table('alms_assets')->insert([
                        'asset_code' => $code,
                        'asset_name' => $uniqueName, // Storing "Vehicle - Plate" to be unique
                        'asset_category' => 'Vehicle',
                        'asset_location' => 'Garage',
                        'asset_status' => $dbStatus,
                        'last_maintenance' => $v['last_maintenance'] ?? now()->toDateString(),
                        'next_maintenance' => null, // "na" mapped to null
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Log error or ignore
        }

        $assets = DB::connection('alms')->table('alms_assets')
            ->orderByDesc('created_at')
            ->get();

        return response()->json([
            'message' => 'ALMS Assets data',
            'data' => $assets,
        ]);
    }

    public function getMaintenance()
    {
        $rows = DB::connection('alms')->table('alms_maintenance as m')
            ->leftJoin('almns_repair_personnel as rp', 'rp.id', '=', 'm.mnt_repair_personnel_id')
            ->orderByDesc('m.mnt_created_at')
            ->select('m.*', 'rp.firstname as rp_firstname', 'rp.middlename as rp_middlename', 'rp.lastname as rp_lastname', 'rp.position as rp_position')
            ->get();

        return response()->json([
            'message' => 'ALMS Maintenance data',
            'data' => $rows,
        ]);
    }

    public function getRequestMaintenance()
    {
        try {
            // Get processed external IDs
            $processedExternalIds = [];
            try {
                $processedExternalIds = DB::connection('alms')->table('alms_processed_external_requests')
                    ->pluck('external_id')->toArray();
            } catch (\Exception $e) {
                // Table might not exist or connection issue, ignore for now
            }

            // Local data
            $localRows = DB::connection('alms')->table('alms_request_maintenance')
                ->orderByDesc('id')
                ->get()
                ->map(function ($row) {
                    // Generate display ID: REQM + YYYYMMDD + 5 chars from hash of ID
                    $dateStr = date('Ymd', strtotime($row->req_date));
                    // Use md5 to ensure stability of the ID for the same record
                    $hash = strtoupper(substr(md5($row->id . 'local'), 0, 5));
                    
                    // We preserve the original ID for actions
                    $row->real_id = $row->req_id; // Original REQ... from DB
                    $row->req_id = "REQM{$dateStr}{$hash}"; // Display ID
                    
                    $row->is_external = false;
                    return $row;
                });

            // External data
            $externalRows = [];
            try {
                $response = Http::withoutVerifying()->timeout(10)->get('https://log2.microfinancial-1.com/api/maintenance_api.php', [
                    'key' => 'd4f8a9b3c6e2f1a4b7d9e0c3f2a1b4d6'
                ]);
                
                if ($response->successful()) {
                    $data = $response->json();
                    // Assume data is in 'data' key or root array
                    $items = $data['data'] ?? ($data['requests'] ?? ($data ?? []));
                    
                    if (is_array($items)) {
                        foreach ($items as $item) {
                            $rawId = $item['req_id'] ?? ($item['id'] ?? null);
                            if (!$rawId) continue;
                            
                            // Check if processed locally
                            // We assume we store the raw ID in the tracking table
                            $isProcessed = in_array((string)$rawId, $processedExternalIds);

                            // Construct unique asset name from vehicle + plate if available
                            $vehicleName = $item['vehicle'] ?? ($item['req_asset_name'] ?? ($item['asset_name'] ?? 'Unknown Asset'));
                            $plate = $item['plate'] ?? '';
                            // Format: "Vehicle - Plate"
                            $assetName = $vehicleName . ($plate ? ' - ' . $plate : '');
                            
                            // Consistent external ID for logic
                            // We use EXT- prefix for the 'id' field to avoid collision with local IDs in frontend
                            $extId = 'EXT-' . $rawId;

                            // Generate display ID
                            $dateVal = $item['req_date'] ?? ($item['date'] ?? now()->toDateString());
                            $dateStr = date('Ymd', strtotime($dateVal));
                            // Stable hash for external item
                            $hash = strtoupper(substr(md5($rawId . 'external'), 0, 5));
                            $reqCode = "REQM{$dateStr}{$hash}";

                            $externalRows[] = (object) [
                                'id' => $extId, // Unique identifier for the row
                                'real_id' => $extId, // For actions (EXT-...)
                                'req_id' => $reqCode, // Formatted ID as requested
                                'req_asset_name' => $assetName,
                                'req_date' => $dateVal,
                                'req_priority' => strtolower($item['req_priority'] ?? ($item['priority'] ?? 'low')),
                                'req_processed' => $isProcessed ? 1 : ($item['req_processed'] ?? 0),
                                'req_type' => $item['req_type'] ?? ($item['type'] ?? 'External'),
                                'is_external' => true
                            ];
                        }
                    }
                }
            } catch (\Exception $e) {
                // Log error but continue with local data
                // Log::error("Maintenance API Error: " . $e->getMessage());
            }

            // Merge and sort (latest date first)
            $merged = $localRows->merge($externalRows);
            $sorted = $merged->sortByDesc('req_date')->values();

            return response()->json(['data' => $sorted]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => true,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function showAsset($id)
    {
        $asset = DB::connection('alms')->table('alms_assets')->where('id', $id)->first();
        if (! $asset) {
            return response()->json(['message' => 'Asset not found'], 404);
        }

        return response()->json(['asset' => $asset]);
    }

    public function updateAssetStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'asset_status' => 'required|in:operational,under_maintenance,out_of_service',
        ]);
        $updated = DB::connection('alms')->table('alms_assets')->where('id', $id)->update([
            'asset_status' => $validated['asset_status'],
            'updated_at' => now(),
        ]);
        if (! $updated) {
            return response()->json(['message' => 'Update failed'], 400);
        }
        $asset = DB::connection('alms')->table('alms_assets')->where('id', $id)->first();

        return response()->json(['message' => 'Status updated', 'asset' => $asset]);
    }

    public function deleteAsset($id)
    {
        $deleted = DB::connection('alms')->table('alms_assets')->where('id', $id)->delete();
        if (! $deleted) {
            return response()->json(['message' => 'Delete failed'], 400);
        }

        return response()->json(['message' => 'Asset deleted']);
    }

    public function getRepairPersonnel()
    {
        // Sync from External Employees API
        try {
            $response = Http::withoutVerifying()->timeout(15)->withHeaders([
                'X-API-Key' => 'b24e8778f104db434adedd4342e94d39cee6d0668ec595dc6f02c739c522b57a',
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
            ])->get('https://hr4.microfinancial-1.com/allemployees');

            if ($response->successful()) {
                $data = $response->json();
                $employees = $data['data'] ?? $data;
                
                foreach ($employees as $emp) {
                    if (!is_array($emp)) continue;

                    $empId = $emp['employee_id'] ?? null;
                    if (!$empId) continue;

                    // Parse Position
                    $position = $emp['job']['job_title'] ?? ($emp['position']['department'] ?? 'External Staff');
                    
                    // Filter: Only allow specific positions
                    $allowedPositions = ['Cleaning Staff', 'Technician', 'Mechanic'];
                    if (!in_array($position, $allowedPositions)) {
                        continue;
                    }

                    // Check if exists by rep_id
                    $exists = DB::connection('alms')->table('almns_repair_personnel')
                        ->where('rep_id', $empId)
                        ->exists();
                    
                    if ($exists) continue;

                    // Parse Name
                    $fullName = trim($emp['full_name'] ?? '');
                    $parts = explode(' ', $fullName);
                    $lastname = array_pop($parts);
                    $firstname = implode(' ', $parts);
                    if (empty($firstname)) {
                        $firstname = $lastname; // Fallback
                        $lastname = '';
                    }
                    
                    // Parse Status
                    $status = (strtolower($emp['status'] ?? '') === 'active') ? 'active' : 'inactive';

                    DB::connection('alms')->table('almns_repair_personnel')->insert([
                        'rep_id' => $empId,
                        'firstname' => $firstname,
                        'middlename' => null,
                        'lastname' => $lastname,
                        'position' => substr($position, 0, 100),
                        'status' => $status,
                    ]);
                }
            }
        } catch (\Exception $e) {
            // Log error or ignore
        }

        $status = request()->query('status');
        $query = DB::connection('alms')->table('almns_repair_personnel');
        if ($status) {
            $query->where('status', $status);
        }
        $rows = $query->orderByDesc('id')->get();

        return response()->json(['data' => $rows]);
    }

    public function storeMaintenance(Request $request)
    {
        $validated = $request->validate([
            'mnt_asset_name' => 'required|string|max:255',
            'mnt_type' => 'required|string|max:50',
            'mnt_scheduled_date' => 'required|date',
            'mnt_repair_personnel_id' => 'nullable|integer',
            'mnt_status' => 'required|in:scheduled,in_progress,completed,cancelled',
            'mnt_priority' => 'required|in:low,medium,high',
        ]);

        do {
            $code = 'MTN'.now()->format('Ymd')
                .random_int(1, 9)
                .chr(random_int(65, 90))
                .random_int(1, 9)
                .chr(random_int(65, 90))
                .random_int(1, 9);
            $exists = DB::connection('alms')->table('alms_maintenance')->where('mnt_code', $code)->exists();
        } while ($exists);

        DB::connection('alms')->table('alms_maintenance')->insert([
            'mnt_code' => $code,
            'mnt_asset_name' => $validated['mnt_asset_name'],
            'mnt_type' => $validated['mnt_type'],
            'mnt_scheduled_date' => $validated['mnt_scheduled_date'],
            'mnt_repair_personnel_id' => $validated['mnt_repair_personnel_id'] ?? null,
            'mnt_status' => $validated['mnt_status'],
            'mnt_priority' => $validated['mnt_priority'],
            'mnt_created_at' => now(),
            'mnt_updated_at' => now(),
        ]);

        return response()->json(['message' => 'Maintenance scheduled', 'code' => $code], 201);
    }

    public function storeRequestMaintenance(Request $request)
    {
        $validated = $request->validate([
            'req_asset_name' => 'required|string|max:255',
            'req_priority' => 'required|in:low,medium,high',
            'req_type' => 'required|string|max:100',
        ]);

        do {
            $reqId = 'REQ'.strtoupper(Str::random(5));
            $exists = DB::connection('alms')->table('alms_request_maintenance')->where('req_id', $reqId)->exists();
        } while ($exists);

        DB::connection('alms')->table('alms_request_maintenance')->insert([
            'req_id' => $reqId,
            'req_asset_name' => $validated['req_asset_name'],
            'req_date' => now()->toDateString(),
            'req_priority' => $validated['req_priority'],
            'req_type' => $validated['req_type'],
        ]);

        return response()->json(['message' => 'Request saved', 'req_id' => $reqId], 201);
    }

    public function deleteRequestMaintenance($id)
    {
        $deleted = DB::connection('alms')->table('alms_request_maintenance')->where('req_id', $id)->delete();
        if (! $deleted) {
            return response()->json(['message' => 'Delete failed'], 400);
        }

        return response()->json(['message' => 'Request deleted']);
    }

    public function markRequestProcessed($id)
    {
        if (str_starts_with($id, 'EXT-')) {
            // External request: record in local processed table
            $rawId = substr($id, 4); // Remove 'EXT-' prefix
            try {
                // Check if already processed
                $exists = DB::connection('alms')->table('alms_processed_external_requests')
                    ->where('external_id', $rawId)
                    ->exists();
                
                if (!$exists) {
                    DB::connection('alms')->table('alms_processed_external_requests')->insert([
                        'external_id' => $rawId,
                        'processed_at' => now(),
                    ]);
                }
                return response()->json(['message' => 'External request marked as processed']);
            } catch (\Exception $e) {
                return response()->json(['message' => 'Failed to mark external request'], 500);
            }
        } else {
            // Local request
            $updated = DB::connection('alms')->table('alms_request_maintenance')->where('req_id', $id)->update([
                'req_processed' => 1,
            ]);
            if (! $updated) {
                // It might already be processed or not found
                return response()->json(['message' => 'Mark processed failed or already processed'], 200);
            }
            return response()->json(['message' => 'Request marked as processed']);
        }
    }

    public function updateMaintenanceStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'mnt_status' => 'required|in:scheduled,in_progress,completed,cancelled',
        ]);
        $updated = DB::connection('alms')->table('alms_maintenance')->where('mnt_code', $id)->update([
            'mnt_status' => $validated['mnt_status'],
            'mnt_updated_at' => now(),
        ]);
        if (! $updated) {
            return response()->json(['message' => 'Update failed'], 400);
        }
        $row = DB::connection('alms')->table('alms_maintenance')->where('mnt_code', $id)->first();

        return response()->json(['message' => 'Status updated', 'maintenance' => $row]);
    }

    public function deleteMaintenance($id)
    {
        $deleted = DB::connection('alms')->table('alms_maintenance')->where('mnt_code', $id)->delete();
        if (! $deleted) {
            return response()->json(['message' => 'Delete failed'], 400);
        }

        return response()->json(['message' => 'Maintenance deleted']);
    }

    public function storeRepairPersonnel(Request $request)
    {
        $validated = $request->validate([
            'firstname' => 'required|string|max:100',
            'middlename' => 'nullable|string|max:100',
            'lastname' => 'required|string|max:100',
            'position' => 'required|in:Technician,Mechanic,Cleaning Staff',
            'status' => 'required|in:active,inactive',
        ]);

        $repId = null;
        do {
            $repId = 'RPL'.random_int(1, 9).chr(random_int(65, 90)).random_int(1, 9).chr(random_int(65, 90)).random_int(1, 9);
            $exists = DB::connection('alms')->table('almns_repair_personnel')->where('rep_id', $repId)->exists();
        } while ($exists);

        DB::connection('alms')->table('almns_repair_personnel')->insert([
            'rep_id' => $repId,
            'firstname' => $validated['firstname'],
            'middlename' => $validated['middlename'] ?? null,
            'lastname' => $validated['lastname'],
            'position' => $validated['position'],
            'status' => $validated['status'],
        ]);

        return response()->json(['message' => 'Repair personnel added', 'rep_id' => $repId], 201);
    }

    public function deleteRepairPersonnel($id)
    {
        $deleted = DB::connection('alms')->table('almns_repair_personnel')->where('id', $id)->delete();
        if (! $deleted) {
            return response()->json(['message' => 'Delete failed'], 400);
        }

        return response()->json(['message' => 'Repair personnel deleted']);
    }
}
