<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BudgetApproval extends Model
{
    use HasFactory;

    protected $table = 'psm_budget_approvals';
    protected $primaryKey = 'bud_id';
    
    protected $fillable = [
        'entity_type',
        'entity_id',
        'bud_name',
        'quantity',
        'unit_price',
        'total_budget',
        'bud_desc',
        'bud_status',
        'approver_user_id',
        'finance_budget_id',
        'approved_at'
    ];

    protected $casts = [
        'unit_price' => 'decimal:2',
        'total_budget' => 'decimal:2',
        'approved_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationship for orders
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class, 'entity_id', 'order_id');
    }

    // Relationship for reorders
    public function reorder(): BelongsTo
    {
        return $this->belongsTo(Reorder::class, 'entity_id', 'restock_id');
    }

    // Dynamic relationship to get the related entity based on entity_type
    public function entity()
    {
        if ($this->entity_type === 'order') {
            return $this->belongsTo(Order::class, 'entity_id', 'order_id');
        } elseif ($this->entity_type === 'restock') {
            return $this->belongsTo(Reorder::class, 'entity_id', 'restock_id');
        }
        
        // Return a fallback relationship that won't break eager loading
        return $this->belongsTo(Order::class, 'entity_id', 'order_id')->whereRaw('1=0');
    }

    // Accessor for formatted entity name
    public function getEntityNameAttribute()
    {
        switch ($this->entity_type) {
            case 'order':
                return 'Order #' . $this->entity_id;
            case 'restock':
                return 'Restock #' . $this->entity_id;
            case 'requisition':
                return 'Requisition #' . $this->entity_id;
            default:
                return 'Entity #' . $this->entity_id;
        }
    }

    // Accessor for formatted budget ID
    public function getFormattedIdAttribute()
    {
        return 'BUD' . str_pad($this->bud_id, 6, '0', STR_PAD_LEFT);
    }

    // Scopes for filtering
    public function scopeOrders($query)
    {
        return $query->where('entity_type', 'order');
    }

    public function scopeRestocks($query)
    {
        return $query->where('entity_type', 'restock');
    }

    public function scopePending($query)
    {
        return $query->where('bud_status', 'pending');
    }

    public function scopeApproved($query)
    {
        return $query->where('bud_status', 'approved');
    }

    public function scopeRejected($query)
    {
        return $query->where('bud_status', 'rejected');
    }
}