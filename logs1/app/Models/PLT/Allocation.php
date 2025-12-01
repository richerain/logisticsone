<?php

namespace App\Models\PLT;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Allocation extends Model
{
    use HasFactory;

    protected $connection = 'plt';
    protected $table = 'plt_allocations';
    protected $primaryKey = 'allo_id';

    protected $fillable = [
        'allo_project_id',
        'allo_milestone_id',
        'allo_resource_id',
        'allo_location_id',
        'allo_quantity',
        'allo_allocated_date',
        'allo_status',
    ];

    protected $casts = [
        'allo_allocated_date' => 'date',
    ];

    // Relationships
    public function project()
    {
        return $this->belongsTo(Project::class, 'allo_project_id', 'pro_id');
    }

    public function milestone()
    {
        return $this->belongsTo(Milestone::class, 'allo_milestone_id', 'mile_id');
    }

    public function resource()
    {
        return $this->belongsTo(Resource::class, 'allo_resource_id', 'res_id');
    }

    public function dispatches()
    {
        return $this->hasMany(Dispatch::class, 'dis_allocation_id', 'allo_id');
    }
}