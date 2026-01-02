<?php

namespace App\Models\SWS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventorySnapshot extends Model
{
    use HasFactory;

    protected $connection = 'sws';

    protected $table = 'sws_inventory_snapshots';

    protected $primaryKey = 'snap_id';

    public $timestamps = false;

    protected $fillable = [
        'snap_id',
        'snap_item_id',
        'snap_location_id',
        'snap_warehouse_id',
        'snap_current_quantity',
        'snap_min_threshold',
        'snap_alert_level',
        'snap_snapshot_date',
        'snap_recorded_by',
        'snap_notes',
    ];

    protected $casts = [
        'snap_current_quantity' => 'integer',
        'snap_min_threshold' => 'integer',
        'snap_snapshot_date' => 'date',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'snap_item_id', 'item_id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'snap_location_id', 'loc_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'snap_warehouse_id', 'ware_id');
    }
}
