<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaintenanceSchedule extends Model
{
    use HasFactory;

    protected $table = 'alms_maintenance_schedules';
    
    protected $fillable = [
        'schedule_id',
        'asset_id',
        'maintenance_type_id',
        'due_date',
        'frequency_value',
        'last_maintained_date',
        'is_overdue',
        'status'
    ];

    protected $casts = [
        'due_date' => 'date',
        'last_maintained_date' => 'date',
        'is_overdue' => 'boolean'
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function maintenanceType(): BelongsTo
    {
        return $this->belongsTo(MaintenanceType::class, 'maintenance_type_id');
    }

    public function maintenanceRecords(): HasMany
    {
        return $this->hasMany(MaintenanceRecord::class, 'schedule_id');
    }
}