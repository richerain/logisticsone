<?php

namespace App\Models\PLT;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $connection = 'plt';

    protected $table = 'plt_projects';

    protected $primaryKey = 'pro_id';

    protected $fillable = [
        'pro_project_name',
        'pro_description',
        'pro_start_date',
        'pro_end_date',
        'pro_status',
        'pro_budget_allocated',
        'pro_assigned_manager_id',
    ];

    protected $casts = [
        'pro_start_date' => 'date',
        'pro_end_date' => 'date',
        'pro_budget_allocated' => 'decimal:2',
    ];

    // Relationships
    public function milestones()
    {
        return $this->hasMany(Milestone::class, 'mile_project_id', 'pro_id');
    }

    public function resources()
    {
        return $this->hasMany(Resource::class, 'res_project_id', 'pro_id');
    }

    public function trackingLogs()
    {
        return $this->hasMany(TrackingLog::class, 'track_project_id', 'pro_id');
    }

    public function manager()
    {
        return $this->belongsTo(\App\Models\EmployeeAccount::class, 'pro_assigned_manager_id', 'id');
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('pro_status', 'active');
    }

    public function scopeCompleted($query)
    {
        return $query->where('pro_status', 'completed');
    }

    public function scopePlanning($query)
    {
        return $query->where('pro_status', 'planning');
    }

    public function scopeDelayed($query)
    {
        return $query->where('pro_end_date', '<', now())
            ->whereNotIn('pro_status', ['completed', 'cancelled']);
    }

    // Accessors
    public function getProgressAttribute()
    {
        $totalMilestones = $this->milestones()->count();
        if ($totalMilestones === 0) {
            return 0;
        }

        $completedMilestones = $this->milestones()->where('mile_status', 'completed')->count();

        return ($completedMilestones / $totalMilestones) * 100;
    }

    public function getTotalBudgetUsedAttribute()
    {
        return $this->resources()->sum('res_estimated_cost');
    }
}
