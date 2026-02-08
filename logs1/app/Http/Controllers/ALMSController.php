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
            // If SWS not accessible, continue returning current ALMS assets
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
        // Local data
        $localRows = DB::connection('alms')->table('alms_request_maintenance')
            ->orderByDesc('id')
            ->get();

        // External data
        $externalRows = [];
        try {
            $response = Http::timeout(5)->get('https://log2.microfinancial-1.com/api/maintenance_api.php', [
                'key' => 'd4f8a9b3c6e2f1a4b7d9e0c3f2a1b4d6'
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                // Assume data is in 'data' key or root array
                $items = $data['data'] ?? ($data['requests'] ?? ($data ?? []));
                if (is_array($items)) {
                    foreach ($items as $item) {
                        $externalRows[] = (object) [
                            'req_id' => $item['req_id'] ?? ($item['id'] ?? 'EXT-' . uniqid()),
                            'req_asset_name' => $item['req_asset_name'] ?? ($item['asset_name'] ?? 'Unknown Asset'),
                            'req_date' => $item['req_date'] ?? ($item['date'] ?? now()->toDateString()),
                            'req_priority' => $item['req_priority'] ?? ($item['priority'] ?? 'low'),
                            'req_processed' => $item['req_processed'] ?? 0,
                            'req_type' => $item['req_type'] ?? ($item['type'] ?? 'External'),
                            'is_external' => true
                        ];
                    }
                }
            }
        } catch (\Exception $e) {
            // Log error but continue with local data
        }

        // Merge and sort (latest date first)
        $merged = collect($externalRows)->merge($localRows);
        $sorted = $merged->sortByDesc('req_date')->values();

        return response()->json(['data' => $sorted]);
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
        $updated = DB::connection('alms')->table('alms_request_maintenance')->where('req_id', $id)->update([
            'req_processed' => 1,
        ]);
        if (! $updated) {
            return response()->json(['message' => 'Mark processed failed'], 400);
        }

        return response()->json(['message' => 'Request marked as processed']);
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
            'position' => 'required|in:Technician,Mechanic',
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
