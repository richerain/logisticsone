<?php

namespace App\Models\PSM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Consolidated extends Model
{
    use HasFactory;

    protected $connection = 'psm';

    protected $table = 'psm_consolidated';

    protected $fillable = [
        'con_req_id',
        'req_id',
        'con_items',
        'con_total_price',
        'con_requester',
        'con_date',
        'con_note',
        'con_status',
        'con_budget_approval',
        'parent_budget_req_id',
    ];

    protected $casts = [
        'con_items' => 'json',
        'con_date' => 'datetime',
    ];
}
