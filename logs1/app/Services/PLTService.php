<?php

namespace App\Services;

use App\Repositories\PLTRepository;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PLTService
{
    protected $pltRepository;

    public function __construct(PLTRepository $pltRepository)
    {
        $this->pltRepository = $pltRepository;
    }

    public function getAllProjects($filters = [])
    {
        try {
            $projects = $this->pltRepository->getAllProjects($filters);

            return [
                'success' => true,
                'data' => $projects,
                'message' => 'Projects retrieved successfully',
            ];
        } catch (\Exception $e) {
            Log::error('Error fetching projects: '.$e->getMessage());

            return [
                'success' => false,
                'data' => [],
                'message' => 'Failed to retrieve projects',
            ];
        }
    }

    public function getProject($id)
    {
        try {
            $project = $this->pltRepository->getProjectById($id);

            if (! $project) {
                return [
                    'success' => false,
                    'data' => null,
                    'message' => 'Project not found',
                ];
            }

            return [
                'success' => true,
                'data' => $project,
                'message' => 'Project retrieved successfully',
            ];
        } catch (\Exception $e) {
            Log::error('Error fetching project: '.$e->getMessage());

            return [
                'success' => false,
                'data' => null,
                'message' => 'Failed to retrieve project',
            ];
        }
    }

    public function createProject($data)
    {
        DB::beginTransaction();
        try {
            $project = $this->pltRepository->createProject($data);

            // Create initial tracking log
            $this->pltRepository->createTrackingLog([
                'track_project_id' => $project->pro_id,
                'track_log_type' => 'milestone_update',
                'track_description' => 'Project created successfully',
                'track_logged_by' => auth()->guard('sws')->user()->email ?? 'system',
            ]);

            // Auto-generate default milestones
            $start = $data['pro_start_date'] ?? now()->toDateString();
            $end = $data['pro_end_date'] ?? null;
            $defaults = [
                [
                    'mile_project_id' => $project->pro_id,
                    'mile_milestone_name' => 'Initiation',
                    'mile_description' => 'Project initiation and kickoff',
                    'mile_target_date' => $start,
                    'mile_actual_date' => null,
                    'mile_status' => 'pending',
                    'mile_priority' => 'medium',
                ],
                [
                    'mile_project_id' => $project->pro_id,
                    'mile_milestone_name' => 'Execution',
                    'mile_description' => 'Main execution phase',
                    'mile_target_date' => $end ?? $start,
                    'mile_actual_date' => null,
                    'mile_status' => 'pending',
                    'mile_priority' => 'medium',
                ],
                [
                    'mile_project_id' => $project->pro_id,
                    'mile_milestone_name' => 'Closure',
                    'mile_description' => 'Finalize and close project',
                    'mile_target_date' => $end ?? $start,
                    'mile_actual_date' => null,
                    'mile_status' => 'pending',
                    'mile_priority' => 'medium',
                ],
            ];
            foreach ($defaults as $m) {
                $this->pltRepository->createMilestone($m);
            }

            DB::commit();

            return [
                'success' => true,
                'data' => $project,
                'message' => 'Project created successfully',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating project: '.$e->getMessage());

            return [
                'success' => false,
                'data' => null,
                'message' => 'Failed to create project',
            ];
        }
    }

    public function updateProject($id, $data)
    {
        DB::beginTransaction();
        try {
            $project = $this->pltRepository->updateProject($id, $data);

            if (! $project) {
                return [
                    'success' => false,
                    'data' => null,
                    'message' => 'Project not found',
                ];
            }

            // Create tracking log for update
            $this->pltRepository->createTrackingLog([
                'track_project_id' => $id,
                'track_log_type' => 'milestone_update',
                'track_description' => 'Project details updated',
                'track_logged_by' => auth()->guard('sws')->user()->email ?? 'system',
            ]);

            DB::commit();

            return [
                'success' => true,
                'data' => $project,
                'message' => 'Project updated successfully',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating project: '.$e->getMessage());

            return [
                'success' => false,
                'data' => null,
                'message' => 'Failed to update project',
            ];
        }
    }

    public function deleteProject($id)
    {
        DB::beginTransaction();
        try {
            $result = $this->pltRepository->deleteProject($id);

            if (! $result) {
                return [
                    'success' => false,
                    'message' => 'Project not found',
                ];
            }

            DB::commit();

            return [
                'success' => true,
                'message' => 'Project deleted successfully',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting project: '.$e->getMessage());

            return [
                'success' => false,
                'message' => 'Failed to delete project',
            ];
        }
    }

    public function getProjectStats()
    {
        try {
            $stats = $this->pltRepository->getProjectStats();

            return [
                'success' => true,
                'data' => $stats,
                'message' => 'Project statistics retrieved successfully',
            ];
        } catch (\Exception $e) {
            Log::error('Error fetching project stats: '.$e->getMessage());

            return [
                'success' => false,
                'data' => [],
                'message' => 'Failed to retrieve project statistics',
            ];
        }
    }

    public function getProjectMilestones($projectId)
    {
        try {
            $items = $this->pltRepository->getMilestonesByProject($projectId);

            return [
                'success' => true,
                'data' => $items,
                'message' => 'Milestones retrieved successfully',
            ];
        } catch (\Exception $e) {
            Log::error('Error fetching milestones: '.$e->getMessage());

            return [
                'success' => false,
                'data' => [],
                'message' => 'Failed to retrieve milestones',
            ];
        }
    }

    public function createMilestone($data)
    {
        DB::beginTransaction();
        try {
            if (! empty($data['mile_actual_date']) && ($data['mile_status'] ?? '') !== 'completed') {
                $data['mile_status'] = 'in_progress';
            }
            $mile = $this->pltRepository->createMilestone($data);
            $this->recalculateProjectStatusAndProgress($data['mile_project_id'] ?? null);
            DB::commit();

            return [
                'success' => true,
                'data' => $mile,
                'message' => 'Milestone created successfully',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error creating milestone: '.$e->getMessage());

            return [
                'success' => false,
                'data' => null,
                'message' => 'Failed to create milestone',
            ];
        }
    }

    public function updateMilestone($id, $data)
    {
        DB::beginTransaction();
        try {
            if (! empty($data['mile_actual_date']) && ($data['mile_status'] ?? '') !== 'completed') {
                $data['mile_status'] = 'in_progress';
            }
            $mile = $this->pltRepository->updateMilestone($id, $data);
            if (! $mile) {
                DB::rollBack();

                return [
                    'success' => false,
                    'data' => null,
                    'message' => 'Milestone not found',
                ];
            }
            $this->recalculateProjectStatusAndProgress($mile->mile_project_id);
            DB::commit();

            return [
                'success' => true,
                'data' => $mile,
                'message' => 'Milestone updated successfully',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating milestone: '.$e->getMessage());

            return [
                'success' => false,
                'data' => null,
                'message' => 'Failed to update milestone',
            ];
        }
    }

    public function deleteMilestone($id)
    {
        DB::beginTransaction();
        try {
            $mile = $this->pltRepository->findMilestone($id);
            $ok = $this->pltRepository->deleteMilestone($id);
            if ($mile) {
                $this->recalculateProjectStatusAndProgress($mile->mile_project_id);
            }
            DB::commit();

            return [
                'success' => (bool) $ok,
                'message' => $ok ? 'Milestone deleted successfully' : 'Milestone not found',
            ];
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error deleting milestone: '.$e->getMessage());

            return [
                'success' => false,
                'message' => 'Failed to delete milestone',
            ];
        }
    }

    private function recalculateProjectStatusAndProgress($projectId)
    {
        if (! $projectId) {
            return;
        }
        $milestones = $this->pltRepository->getMilestonesByProject($projectId);
        $total = $milestones->count();
        $completed = $milestones->where('mile_status', 'completed')->count();
        $inProgress = $milestones->where('mile_status', 'in_progress')->count();
        $overdue = $milestones->where('mile_status', 'overdue')->count();

        $status = 'planning';
        if ($total > 0 && $completed === $total) {
            $status = 'completed';
        } elseif ($inProgress > 0) {
            $status = 'active';
        } elseif ($overdue > 0) {
            $status = 'active';
        } else {
            $status = 'planning';
        }

        $this->pltRepository->updateProject($projectId, ['pro_status' => $status]);
    }
}
