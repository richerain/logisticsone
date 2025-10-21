<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehousing extends Model
{
    use HasFactory;

    protected $table = 'sws_warehousing';

    protected $primaryKey = 'id';

    protected $fillable = [
        'grn_id',
        'po_number',
        'item',
        'qty_ordered',
        'qty_received',
        'condition',
        'warehouse_location',
        'received_by',
        'status'
    ];

    protected $casts = [
        'qty_ordered' => 'integer',
        'qty_received' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'qty_ordered' => 'integer',
            'qty_received' => 'integer',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
        ];
    }

    /**
     * Scope a query to only include records with good condition.
     */
    public function scopeGoodCondition($query)
    {
        return $query->where('condition', 'Good');
    }

    /**
     * Scope a query to only include completed records.
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'Completed');
    }

    /**
     * Calculate the difference between ordered and received quantities
     */
    public function getQuantityDifferenceAttribute()
    {
        return $this->qty_ordered - $this->qty_received;
    }

    /**
     * Check if the receipt is complete
     */
    public function getIsCompleteAttribute()
    {
        return $this->qty_ordered === $this->qty_received;
    }

    /**
     * Check if there are discrepancies
     */
    public function getHasDiscrepancyAttribute()
    {
        return $this->qty_ordered !== $this->qty_received || $this->condition !== 'Good';
    }
}