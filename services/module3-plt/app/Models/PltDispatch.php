<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PltDispatch extends Model
{
    use HasFactory;

    protected $table = 'plt_dispatches';

    protected $fillable = [
        'project_id',
        'material_type',
        'quantity',
        'from_location',
        'to_location',
        'dispatch_date',
        'expected_delivery_date',
        'actual_delivery_date',
        'status',
        'courier_info',
        'receipt_reference'
    ];

    protected $casts = [
        'dispatch_date' => 'date',
        'expected_delivery_date' => 'date',
        'actual_delivery_date' => 'date',
        'courier_info' => 'array'
    ];

    public function project(): BelongsTo
    {
        return $this->belongsTo(PltProject::class, 'project_id');
    }

    public function trackingLogs(): HasMany
    {
        return $this->hasMany(PltTrackingLog::class, 'dispatch_id');
    }

    public function milestones(): HasMany
    {
        return $this->hasMany(PltMilestone::class, 'dispatch_id');
    }
}