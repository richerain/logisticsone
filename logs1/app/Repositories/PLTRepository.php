<?php

namespace App\Repositories;

use App\Models\PLT\Project;
use App\Models\PLT\Milestone;
use App\Models\PLT\Resource;
use App\Models\PLT\Allocation;
use App\Models\PLT\Dispatch;
use App\Models\PLT\TrackingLog;
use Illuminate\Support\Facades\DB;

class PLTRepository
{
    public function getAllProjects($filters = [])
    {
        $query = Project::with(['milestones', 'resources', 'manager']);

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('pro_project_name', 'like', "%{$search}%")
                  ->orWhere('pro_description', 'like', "%{$search}%");
            });
        }

        if (!empty($filters['status'])) {
            $query->where('pro_status', $filters['status']);
        }

        return $query->orderBy('created_at', 'desc')->paginate(10);
    }

    public function getProjectById($id)
    {
        return Project::with([
            'milestones', 
            'resources', 
            'trackingLogs',
            'manager'
        ])->find($id);
    }

    public function createProject($data)
    {
        return Project::create($data);
    }

    public function updateProject($id, $data)
    {
        $project = Project::find($id);
        if ($project) {
            $project->update($data);
        }
        return $project;
    }

    public function deleteProject($id)
    {
        DB::beginTransaction();
        try {
            $project = Project::find($id);
            if (!$project) {
                DB::rollBack();
                return false;
            }

            $allocations = Allocation::where('allo_project_id', $id)->get();
            foreach ($allocations as $a) {
                Dispatch::where('dis_allocation_id', $a->allo_id)->delete();
            }
            Allocation::where('allo_project_id', $id)->delete();
            Milestone::where('mile_project_id', $id)->delete();
            Resource::where('res_project_id', $id)->delete();
            TrackingLog::where('track_project_id', $id)->delete();

            $project->delete();

            DB::commit();
            return true;
        } catch (\Exception $e) {
            DB::rollBack();
            return false;
        }
    }

    public function getProjectStats()
    {
        return [
            'total' => Project::count(),
            'completed' => Project::completed()->count(),
            'active' => Project::active()->count(),
            'planning' => Project::planning()->count(),
            'delayed' => Project::delayed()->count(),
        ];
    }

    public function createMilestone($data)
    {
        return Milestone::create($data);
    }

    public function getMilestonesByProject($projectId)
    {
        return Milestone::where('mile_project_id', $projectId)
            ->orderBy('mile_target_date', 'asc')
            ->get();
    }

    public function updateMilestone($id, $data)
    {
        $mile = Milestone::find($id);
        if ($mile) {
            $mile->update($data);
        }
        return $mile;
    }

    public function deleteMilestone($id)
    {
        return Milestone::destroy($id);
    }

    public function findMilestone($id)
    {
        return Milestone::find($id);
    }

    public function createResource($data)
    {
        return Resource::create($data);
    }

    public function createAllocation($data)
    {
        return Allocation::create($data);
    }

    public function createDispatch($data)
    {
        return Dispatch::create($data);
    }

    public function createTrackingLog($data)
    {
        return TrackingLog::create($data);
    }

    public function getProjectsReport($startDate = null, $endDate = null)
    {
        $query = Project::with(['milestones', 'resources']);

        if ($startDate && $endDate) {
            $query->whereBetween('pro_start_date', [$startDate, $endDate]);
        }

        return $query->get();
    }
}