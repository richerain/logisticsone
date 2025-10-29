<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class VendorProduct extends Model
{
    use HasFactory;

    protected $table = 'psm_vendor_products';
    protected $primaryKey = 'product_id';
    
    protected $fillable = [
        'product_code',
        'ven_id',
        'product_name',
        'product_description',
        'product_price',
        'product_stock',
        'product_status',
        'warranty_from', // New field
        'warranty_to', // New field
        'expiration' // New field
    ];

    protected $casts = [
        'product_price' => 'decimal:2',
        'product_stock' => 'integer',
        'warranty_from' => 'date', // New cast
        'warranty_to' => 'date', // New cast
        'expiration' => 'date', // New cast
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Boot function for auto-generating product code
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->product_code)) {
                $model->product_code = IdGenerator::generate([
                    'table' => 'psm_vendor_products',
                    'field' => 'product_code',
                    'length' => 8,
                    'prefix' => 'PROD'
                ]);
            }
        });

        static::created(function ($model) {
            // Update vendor's product count
            $model->vendor->updateProductCount();
        });

        static::deleted(function ($model) {
            // Update vendor's product count
            $model->vendor->updateProductCount();
        });
    }

    /**
     * Get the vendor that owns this product
     */
    public function vendor(): BelongsTo
    {
        return $this->belongsTo(Vendor::class, 'ven_id', 'ven_id');
    }

    /**
     * Format product price for display with currency
     */
    public function getProductPriceDisplayAttribute(): string
    {
        return '₱' . number_format($this->product_price, 2);
    }

    /**
     * Get stock status
     */
    public function getStockStatusAttribute(): string
    {
        if ($this->product_stock <= 0) {
            return 'Out of Stock';
        } elseif ($this->product_stock <= 10) {
            return 'Low Stock';
        } else {
            return 'In Stock';
        }
    }

    /**
     * Get stock status badge class
     */
    public function getStockStatusBadgeAttribute(): string
    {
        $statusClasses = [
            'Out of Stock' => 'badge-error',
            'Low Stock' => 'badge-warning',
            'In Stock' => 'badge-success'
        ];

        $status = $this->stock_status;
        $class = $statusClasses[$status] ?? 'badge-neutral';
        
        return '<span class="badge ' . $class . '">' . $status . '</span>';
    }

    /**
     * Check if product has active warranty
     */
    public function getHasActiveWarrantyAttribute(): bool
    {
        if (!$this->warranty_from || !$this->warranty_to) {
            return false;
        }
        
        $now = now();
        return $now->between($this->warranty_from, $this->warranty_to);
    }

    /**
     * Check if product is expired
     */
    public function getIsExpiredAttribute(): bool
    {
        if (!$this->expiration) {
            return false;
        }
        
        return now()->gt($this->expiration);
    }

    /**
     * Format warranty period for display
     */
    public function getWarrantyPeriodDisplayAttribute(): string
    {
        if (!$this->warranty_from || !$this->warranty_to) {
            return 'No Warranty';
        }
        
        return $this->warranty_from->format('m-d-Y') . ' to ' . $this->warranty_to->format('m-d-Y');
    }

    /**
     * Format expiration for display
     */
    public function getExpirationDisplayAttribute(): string
    {
        if (!$this->expiration) {
            return 'No Expiration';
        }
        
        return $this->expiration->format('m-d-Y');
    }
}