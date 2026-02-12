<?php

namespace App\Models\PSM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requisition extends Model
{
    use HasFactory;

    protected $connection = 'psm';

    protected $table = 'psm_requisition';

    protected $fillable = [
        'req_id',
        'req_items',
        'req_price',
        'req_requester',
        'req_dept',
        'req_date',
        'req_note',
        'req_status',
        'is_consolidated',
    ];

    protected $casts = [
        'req_items' => 'json',
        'req_date' => 'date',
    ];
}
