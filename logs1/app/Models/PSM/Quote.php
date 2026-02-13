<?php

namespace App\Models\PSM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    protected $connection = 'psm';

    protected $table = 'psm_quote';

    protected $primaryKey = 'id';

    protected $fillable = [
        'quo_id',
        'quo_items',
        'quo_units',
        'quo_total_amount',
        'quo_delivery_date',
        'quo_status',
        'quo_stored_from',
        'quo_department_from',
        'quo_module_from',
        'quo_submodule_from',
        'quo_purchase_id',
    ];

    protected $casts = [
        'quo_items' => 'array',
        'quo_units' => 'integer',
        'quo_total_amount' => 'decimal:2',
        'quo_delivery_date' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
