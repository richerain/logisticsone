<?php

namespace App\Models\PSM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Budget extends Model
{
    use HasFactory;

    protected $connection = 'psm';

    protected $table = 'psm_budget';

    protected $primaryKey = 'id';

    protected $fillable = [
        'bud_id',
        'bud_allocated_amount',
        'bud_spent_amount',
        'bud_remaining_amount',
        'bud_assigned_date',
        'bud_validity_type',
        'bud_valid_from',
        'bud_valid_to',
        'bud_amount_status_health',
        'bud_for_department',
        'bud_for_module',
        'bud_for_submodule',
        'bud_desc',
    ];

    protected $casts = [
        'bud_allocated_amount' => 'decimal:2',
        'bud_spent_amount' => 'decimal:2',
        'bud_remaining_amount' => 'decimal:2',
        'bud_assigned_date' => 'date',
        'bud_valid_from' => 'date',
        'bud_valid_to' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Calculate budget health status based on percentage used
     */
    public function calculateHealthStatus()
    {
        $percentageUsed = ($this->bud_spent_amount / $this->bud_allocated_amount) * 100;

        if ($percentageUsed > 100) {
            return 'Exceeded';
        } elseif ($percentageUsed > 80) {
            return 'Alert';
        } elseif ($percentageUsed > 50) {
            return 'Stable';
        } else {
            return 'Healthy';
        }
    }

    /**
     * Update budget health status
     */
    public function updateHealthStatus()
    {
        $this->bud_amount_status_health = $this->calculateHealthStatus();
        $this->save();
    }

    /**
     * Get health status color
     */
    public function getHealthStatusColorAttribute()
    {
        $colors = [
            'Healthy' => 'text-green-600 bg-green-100',
            'Stable' => 'text-yellow-600 bg-yellow-100',
            'Alert' => 'text-orange-600 bg-orange-100',
            'Exceeded' => 'text-red-600 bg-red-100',
        ];

        return $colors[$this->bud_amount_status_health] ?? 'text-gray-600 bg-gray-100';
    }

    /**
     * Get health status icon
     */
    public function getHealthStatusIconAttribute()
    {
        $icons = [
            'Healthy' => 'bx-check-circle',
            'Stable' => 'bx-info-circle',
            'Alert' => 'bx-error',
            'Exceeded' => 'bx-x-circle',
        ];

        return $icons[$this->bud_amount_status_health] ?? 'bx-question-mark';
    }

    /**
     * Check if budget is expired
     */
    public function getIsExpiredAttribute()
    {
        return now()->gt($this->bud_valid_to);
    }

    /**
     * Get days remaining
     */
    public function getDaysRemainingAttribute()
    {
        return now()->diffInDays($this->bud_valid_to, false);
    }

    /**
     * Get percentage used
     */
    public function getPercentageUsedAttribute()
    {
        return ($this->bud_spent_amount / $this->bud_allocated_amount) * 100;
    }

    /**
     * Scope active budgets
     */
    public function scopeActive($query)
    {
        return $query->where('bud_valid_to', '>=', now()->toDateString());
    }

    /**
     * Scope by department and module
     */
    public function scopeByDepartmentModule($query, $department, $module, $submodule)
    {
        return $query->where('bud_for_department', $department)
            ->where('bud_for_module', $module)
            ->where('bud_for_submodule', $submodule);
    }
}
