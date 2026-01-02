<?php

namespace App\Models\SWS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;

    protected $connection = 'sws';

    protected $table = 'sws_warehouse';

    protected $primaryKey = 'ware_id';

    public $incrementing = false;

    protected $keyType = 'string';

    public $timestamps = false;

    protected $fillable = [
        'ware_id',
        'ware_name',
        'ware_location',
        'ware_capacity',
        'ware_capacity_used',
        'ware_capacity_free',
        'ware_utilization',
        'ware_status',
        'ware_zone_type',
        'ware_supports_fixed_items',
        'ware_created_at',
        'ware_updated_at',
    ];
}
