<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PltProject extends Model
{
    use HasFactory;

    protected $table = 'plt_projects';

    protected $fillable = [
        'name',
        'description',
        'branch_from',
        'branch_to',
        'start_date',
        'end_date',
        'status',
        'progress_percent'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function dispatches(): HasMany
    {
        return $this->hasMany(PltDispatch::class, 'project_id');
    }

    public function allocations(): HasMany
    {
        return $this->hasMany(PltAllocation::class, 'project_id');
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(PltMilestone::class, 'project_id');
    }
}