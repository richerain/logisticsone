<?php

namespace App\Models\PLT;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Milestone extends Model
{
    use HasFactory;

    protected $connection = 'plt';
    protected $table = 'plt_milestones';
    protected $primaryKey = 'mile_id';

    protected $fillable = [
        'mile_project_id',
        'mile_milestone_name',
        'mile_description',
        'mile_target_date',
        'mile_actual_date',
        'mile_status',
        'mile_priority',
    ];

    protected $casts = [
        'mile_target_date' => 'date',
        'mile_actual_date' => 'date',
    ];

    // Relationships
    public function project()
    {
        return $this->belongsTo(Project::class, 'mile_project_id', 'pro_id');
    }

    public function allocations()
    {
        return $this->hasMany(Allocation::class, 'allo_milestone_id', 'mile_id');
    }

    // Scopes
    public function scopeOverdue($query)
    {
        return $query->where('mile_target_date', '<', now())
                    ->whereNotIn('mile_status', ['completed']);
    }
}