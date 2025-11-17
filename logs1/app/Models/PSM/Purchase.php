<?php

namespace App\Models\PSM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $connection = 'psm';
    protected $table = 'psm_purchase';
    protected $primaryKey = 'id';

    protected $fillable = [
        'pur_id',
        'pur_name_items',
        'pur_unit',
        'pur_total_amount',
        'pur_company_name',
        'pur_ven_type',
        'pur_status',
        'pur_approved_by',
        'pur_order_by',
        'pur_desc',
        'pur_department_from',
        'pur_module_from',
        'pur_submodule_from'
    ];

    protected $casts = [
        'pur_name_items' => 'array',
        'pur_unit' => 'integer',
        'pur_total_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Scope a query to only include purchases of specific status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('pur_status', $status);
    }

    /**
     * Scope a query to only include purchases of specific vendor type.
     */
    public function scopeByVendorType($query, $type)
    {
        return $query->where('pur_ven_type', $type);
    }

    /**
     * Scope a query to search in purchase fields.
     */
    public function scopeSearch($query, $search)
    {
        if (!$search) {
            return $query;
        }
        
        return $query->where(function ($q) use ($search) {
            $q->where('pur_id', 'like', "%{$search}%")
              ->orWhere('pur_company_name', 'like', "%{$search}%")
              ->orWhere('pur_desc', 'like', "%{$search}%")
              ->orWhere('pur_approved_by', 'like', "%{$search}%")
              ->orWhere('pur_order_by', 'like', "%{$search}%")
              ->orWhereJsonContains('pur_name_items', $search);
        });
    }

    /**
     * Get formatted status badge class
     */
    public function getStatusBadgeAttribute()
    {
        $statusClasses = [
            'Pending' => 'bg-yellow-100 text-yellow-800',
            'Approved' => 'bg-blue-100 text-blue-800',
            'Rejected' => 'bg-red-100 text-red-800',
            'Cancel' => 'bg-red-100 text-red-800',
            'Vendor-Review' => 'bg-purple-100 text-purple-800',
            'In-Progress' => 'bg-indigo-100 text-indigo-800',
            'Completed' => 'bg-green-100 text-green-800'
        ];

        return $statusClasses[$this->pur_status] ?? 'bg-gray-100 text-gray-800';
    }

    /**
     * Get formatted items as string
     */
    public function getItemsStringAttribute()
    {
        $items = $this->pur_name_items ?? [];
        return implode(', ', array_slice($items, 0, 3)) . (count($items) > 3 ? '...' : '');
    }

    /**
     * Get items with details (name and price)
     */
    public function getItemsWithDetailsAttribute()
    {
        $items = $this->pur_name_items ?? [];
        $itemsWithDetails = [];
        
        foreach ($items as $item) {
            if (is_array($item) && isset($item['name']) && isset($item['price'])) {
                $itemsWithDetails[] = $item;
            } else {
                // Handle legacy string items
                $itemsWithDetails[] = [
                    'name' => $item,
                    'price' => 0
                ];
            }
        }
        
        return $itemsWithDetails;
    }

    /**
     * Calculate total units from items
     */
    public function calculateTotalUnits()
    {
        $items = $this->pur_name_items ?? [];
        return count($items);
    }

    /**
     * Calculate total amount from items
     */
    public function calculateTotalAmount()
    {
        $items = $this->items_with_details;
        $total = 0;
        
        foreach ($items as $item) {
            $total += floatval($item['price']);
        }
        
        return $total;
    }

    /**
     * Check if purchase can be cancelled (only Pending status)
     */
    public function getCanBeCancelledAttribute()
    {
        return $this->pur_status === 'Pending';
    }

    /**
     * Check if purchase can be approved (only Pending status)
     */
    public function getCanBeApprovedAttribute()
    {
        return $this->pur_status === 'Pending';
    }
}