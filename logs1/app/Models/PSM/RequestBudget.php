<?php

namespace App\Models\PSM;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RequestBudget extends Model
{
    use HasFactory;

    protected $connection = 'psm';
    protected $table = 'psm_request_budget';
    protected $primaryKey = 'req_id';

    protected $fillable = [
        'req_by',
        'req_date',
        'req_dept',
        'req_amount',
        'req_purpose',
        'req_contact',
        'req_status',
    ];

    protected $casts = [
        'req_date' => 'date',
        'req_amount' => 'decimal:2',
    ];
}
