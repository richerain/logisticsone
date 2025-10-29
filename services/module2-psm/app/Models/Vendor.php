<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class Vendor extends Model
{
    use HasFactory;

    protected $table = 'psm_vendors';
    protected $primaryKey = 'ven_id';
    
    protected $fillable = [
        'ven_code',
        'ven_name',
        'ven_contacts',
        'ven_email',
        'ven_address',
        'ven_rating',
        'ven_status',
        'vendor_type', // New field
        'owner', // Changed from shop_name to owner
        'shop_prods'
    ];

    protected $casts = [
        'ven_rating' => 'decimal:2',
        'shop_prods' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Boot function for auto-generating vendor code
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->ven_code)) {
                $model->ven_code = IdGenerator::generate([
                    'table' => 'psm_vendors',
                    'field' => 'ven_code',
                    'length' => 8,
                    'prefix' => 'VEN'
                ]);
            }

            // Set default owner if not provided
            if (empty($model->owner)) {
                $model->owner = $model->ven_name . ' Owner';
            }
        });
    }

    /**
     * Get vendor code for display
     */
    public function getVendorCodeDisplayAttribute(): string
    {
        return $this->ven_code;
    }

    /**
     * Get the quotes for this vendor
     */
    public function quotes()
    {
        return $this->hasMany(Quote::class, 'ven_id');
    }

    /**
     * Get the products for this vendor
     */
    public function products()
    {
        return $this->hasMany(VendorProduct::class, 'ven_id');
    }

    /**
     * Update product count
     */
    public function updateProductCount()
    {
        $this->shop_prods = $this->products()->count();
        $this->save();
    }

    /**
     * Get owner name with fallback
     */
    public function getOwnerDisplayAttribute(): string
    {
        return $this->owner ?: $this->ven_name . ' Owner';
    }

    /**
     * Get vendor type with fallback
     */
    public function getVendorTypeDisplayAttribute(): string
    {
        return $this->vendor_type ?: 'Supplies';
    }
}