<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PltMilestone extends Model
{
    use HasFactory;

    protected $table = 'plt_milestones';

    protected $fillable = [
        'project_id',
        'dispatch_id',
        'name',
        'description',
        'due_date',
        'actual_date',
        'status',
        'delay_alert'
    ];

    protected $casts = [
        'due_date' => 'date',
        'actual_date' => 'date'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(PltProject::class, 'project_id');
    }

    public function dispatch(): BelongsTo
    {
        return $this->belongsTo(PltDispatch::class, 'dispatch_id');
    }
}