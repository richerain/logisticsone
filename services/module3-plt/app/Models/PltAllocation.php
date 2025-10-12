<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PltAllocation extends Model
{
    use HasFactory;

    protected $table = 'plt_allocations';

    protected $fillable = [
        'project_id',
        'resource_id',
        'quantity_assigned',
        'assigned_date',
        'return_date',
        'status'
    ];

    protected $casts = [
        'assigned_date' => 'date',
        'return_date' => 'date'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(PltProject::class, 'project_id');
    }

    public function resource(): BelongsTo
    {
        return $this->belongsTo(PltResource::class, 'resource_id');
    }
}