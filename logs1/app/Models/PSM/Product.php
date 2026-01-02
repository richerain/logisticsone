<?php

namespace App\Models\PSM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $connection = 'psm';

    protected $table = 'psm_product';

    protected $primaryKey = 'id';

    protected $fillable = [
        'prod_id',
        'prod_vendor',
        'prod_name',
        'prod_price',
        'prod_stock',
        'prod_type',
        'prod_warranty',
        'prod_expiration',
        'prod_desc',
        'prod_module_from',
        'prod_submodule_from',
    ];

    protected $casts = [
        'prod_price' => 'decimal:2',
        'prod_stock' => 'integer',
        'prod_expiration' => 'date',
    ];

    /**
     * Get the vendor that owns the product.
     */
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'prod_vendor', 'ven_id');
    }
}
