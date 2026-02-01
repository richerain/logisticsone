<?php

namespace App\Models\PSM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseProduct extends Model
{
    use HasFactory;

    protected $connection = 'psm';

    protected $table = 'psm_purchase_product';

    protected $primaryKey = 'purcprod_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'purcprod_id',
        'purcprod_prod_id',
        'purcprod_prod_name',
        'purcprod_prod_price',
        'purcprod_prod_unit',
        'purcprod_prod_type',
        'purcprod_status',
        'purcprod_date',
        'purcprod_warranty',
        'purcprod_expiration',
        'purcprod_desc',
    ];
}
