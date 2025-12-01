<?php

namespace App\Models\PLT;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dispatch extends Model
{
    use HasFactory;

    protected $connection = 'plt';
    protected $table = 'plt_dispatches';
    protected $primaryKey = 'dis_id';

    protected $fillable = [
        'dis_allocation_id',
        'dis_dispatch_number',
        'dis_carrier',
        'dis_expected_delivery_date',
        'dis_actual_delivery_date',
        'dis_tracking_code',
        'dis_status',
    ];

    protected $casts = [
        'dis_expected_delivery_date' => 'date',
        'dis_actual_delivery_date' => 'date',
    ];

    // Relationships
    public function allocation()
    {
        return $this->belongsTo(Allocation::class, 'dis_allocation_id', 'allo_id');
    }
}