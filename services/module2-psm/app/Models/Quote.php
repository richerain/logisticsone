<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class Quote extends Model
{
    use HasFactory;

    protected $table = 'psm_quotes';
    protected $primaryKey = 'quote_id';
    
    protected $fillable = [
        'quote_code',
        'request_id',
        'ven_id',
        'item_name',
        'quantity',
        'units',
        'unit_price',
        'total_quote',
        'delivery_lead_time',
        'quote_date',
        'status',
        'notes'
    ];

    protected $casts = [
        'quantity' => 'integer',
        'units' => 'integer',
        'unit_price' => 'decimal:2',
        'total_quote' => 'decimal:2',
        'delivery_lead_time' => 'integer',
        'quote_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Boot function for auto-generating codes
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Generate quote code if not set
            if (empty($model->quote_code)) {
                $model->quote_code = IdGenerator::generate([
                    'table' => 'psm_quotes',
                    'field' => 'quote_code',
                    'length' => 7,
                    'prefix' => 'QT'
                ]);
            }

            // Ensure request_id is set
            if (empty($model->request_id)) {
                throw new \Exception('Request ID is required for creating a quote');
            }

            // Auto-calculate units and total quote
            $model->units = $model->quantity;
            $model->total_quote = $model->units * $model->unit_price;
        });

        static::updating(function ($model) {
            // Recalculate units and total quote when quantity or unit_price changes
            if ($model->isDirty(['quantity', 'unit_price'])) {
                $model->units = $model->quantity;
                $model->total_quote = $model->units * $model->unit_price;
            }
        });
    }

    /**
     * Get the vendor associated with this quote
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'ven_id', 'ven_id');
    }

    /**
     * Get the purchase request associated with this quote
     */
    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class, 'request_id', 'request_id');
    }

    /**
     * Format delivery lead time for display
     */
    public function getDeliveryLeadTimeDisplayAttribute(): string
    {
        return $this->delivery_lead_time . ' Day' . ($this->delivery_lead_time > 1 ? 's' : '');
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
}