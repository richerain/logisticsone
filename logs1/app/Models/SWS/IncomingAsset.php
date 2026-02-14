<?php

namespace App\Models\SWS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomingAsset extends Model
{
    use HasFactory;

    protected $connection = 'sws';

    protected $table = 'sws_incoming_asset';

    protected $fillable = [
        'sws_purcprod_id',
        'sws_purcprod_prod_id',
        'sws_purcprod_prod_name',
        'sws_purcprod_prod_price',
        'sws_purcprod_prod_unit',
        'sws_purcprod_prod_type',
        'sws_purcprod_status',
        'sws_purcprod_date',
        'sws_purcprod_warranty',
        'sws_purcprod_expiration',
        'sws_purcprod_desc',
        'sws_purcprod_inventory',
    ];
}
