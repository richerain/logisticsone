<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaintenanceType extends Model
{
    use HasFactory;

    protected $table = 'alms_maintenance_types';
    
    protected $fillable = [
        'name',
        'frequency_unit',
        'estimated_cost'
    ];

    protected $casts = [
        'estimated_cost' => 'decimal:2'
    ];

    public function maintenanceSchedules(): HasMany
    {
        return $this->hasMany(MaintenanceSchedule::class, 'maintenance_type_id');
    }
}