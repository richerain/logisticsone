<?php

namespace App\Models\PSM;

use Illuminate\Database\Eloquent\Model;

class PurchaseRequest extends Model
{
    protected $connection = 'psm';

    protected $table = 'psm_purcahse_request';

    protected $fillable = [
        'preq_id',
        'preq_name_items',
        'preq_unit',
        'preq_total_amount',
        'preq_ven_id',
        'preq_ven_company_name',
        'preq_ven_type',
        'preq_status',
        'preq_process',
        'preq_order_by',
        'preq_desc',
    ];

    protected $casts = [
        'preq_name_items' => 'array',
        'preq_unit' => 'integer',
        'preq_total_amount' => 'decimal:2',
    ];
}

