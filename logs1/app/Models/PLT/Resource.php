<?php

namespace App\Models\PLT;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Resource extends Model
{
    use HasFactory;

    protected $connection = 'plt';
    protected $table = 'plt_resources';
    protected $primaryKey = 'res_id';

    protected $fillable = [
        'res_project_id',
        'res_item_id',
        'res_quantity_required',
        'res_quantity_allocated',
        'res_estimated_cost',
        'res_notes',
    ];

    protected $casts = [
        'res_estimated_cost' => 'decimal:2',
    ];

    // Relationships
    public function project()
    {
        return $this->belongsTo(Project::class, 'res_project_id', 'pro_id');
    }

    public function allocations()
    {
        return $this->hasMany(Allocation::class, 'allo_resource_id', 'res_id');
    }
}