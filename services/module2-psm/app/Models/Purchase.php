<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class Purchase extends Model
{
    use HasFactory;

    protected $table = 'psm_purchase_orders';
    protected $primaryKey = 'purchase_id';
    
    protected $fillable = [
        'request_id',
        'po_number',
        'branch',
        'vendor',
        'item',
        'quantity',
        'units',
        'unit_price',
        'total_quote',
        'estimated_budget',
        'expected_delivery',
        'quote_date',
        'status',
        'description'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'units' => 'integer',
        'unit_price' => 'decimal:2',
        'total_quote' => 'decimal:2',
        'estimated_budget' => 'decimal:2',
        'quote_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Boot function for auto-generating codes and calculations
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Generate request ID if not set
            if (empty($model->request_id)) {
                $model->request_id = IdGenerator::generate([
                    'table' => 'psm_purchase_orders',
                    'field' => 'request_id',
                    'length' => 8,
                    'prefix' => 'REQ'
                ]);
            }

            // Generate PO number if not set
            if (empty($model->po_number)) {
                $model->po_number = IdGenerator::generate([
                    'table' => 'psm_purchase_orders',
                    'field' => 'po_number',
                    'length' => 7,
                    'prefix' => 'PO'
                ]);
            }

            // Auto-calculate units and total quote
            $model->units = $model->quantity;
            $model->total_quote = $model->quantity * $model->unit_price;

            // Set default status if not provided
            if (empty($model->status)) {
                $model->status = 'Pending';
            }
        });

        static::updating(function ($model) {
            // Recalculate units and total quote when quantity or unit_price changes
            if ($model->isDirty(['quantity', 'unit_price'])) {
                $model->units = $model->quantity;
                $model->total_quote = $model->quantity * $model->unit_price;
            }
        });
    }

    /**
     * Format total quote for display with currency
     */
    public function getTotalQuoteDisplayAttribute(): string
    {
        return '₱' . number_format($this->total_quote, 2);
    }

    /**
     * Format unit price for display with currency
     */
    public function getUnitPriceDisplayAttribute(): string
    {
        return '₱' . number_format($this->unit_price, 2);
    }

    /**
     * Format estimated budget for display with currency
     */
    public function getEstimatedBudgetDisplayAttribute(): string
    {
        return '₱' . number_format($this->estimated_budget, 2);
    }

    /**
     * Get the status with appropriate badge class
     */
    public function getStatusBadgeAttribute(): string
    {
        $statusClasses = [
            'Pending' => 'badge-warning',
            'In Progress' => 'badge-info',
            'Received' => 'badge-success',
            'Approved' => 'badge-success',
            'Rejected' => 'badge-error'
        ];

        $class = $statusClasses[$this->status] ?? 'badge-neutral';
        
        return '<span class="badge ' . $class . '">' . $this->status . '</span>';
    }

    /**
     * Get the quotes for this purchase request
     */
    public function quotes()
    {
        return $this->hasMany(Quote::class, 'request_id', 'request_id');
    }
}