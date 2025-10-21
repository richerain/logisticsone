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
        'ven_status'
    ];

    protected $casts = [
        'ven_rating' => 'decimal:2',
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
        });
    }

    public function shops()
    {
        return $this->hasMany(Shop::class, 'ven_id');
    }

    /**
     * Get vendor code for display
     */
    public function getVendorCodeDisplayAttribute(): string
    {
        return $this->ven_code;
    }
}