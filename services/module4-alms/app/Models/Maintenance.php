<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Maintenance extends Model
{
    use HasFactory;

    protected $table = 'alms_maintenance';

    protected $fillable = [
        'schedule_id',
        'asset_name',
        'maintenance_type',
        'assigned_personnel',
        'schedule_date',
        'schedule_time',
        'status',
        'notes',
        'reschedule_reason'
    ];

    protected $casts = [
        'schedule_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}