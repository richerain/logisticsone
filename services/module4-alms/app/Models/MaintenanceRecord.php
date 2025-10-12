<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceRecord extends Model
{
    use HasFactory;

    protected $table = 'alms_maintenance_records';
    
    protected $fillable = [
        'record_id',
        'asset_id',
        'schedule_id',
        'performed_date',
        'cost',
        'description',
        'performed_by'
    ];

    protected $casts = [
        'performed_date' => 'date',
        'cost' => 'decimal:2'
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(MaintenanceSchedule::class, 'schedule_id');
    }
}