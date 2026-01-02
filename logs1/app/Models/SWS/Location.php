<?php

namespace App\Models\SWS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;

    protected $connection = 'sws';

    protected $table = 'sws_locations';

    protected $primaryKey = 'loc_id';

    public $timestamps = false;

    protected $fillable = [
        'loc_id',
        'loc_name',
        'loc_type',
        'loc_zone_type',
        'loc_supports_fixed_items',
        'loc_capacity',
        'loc_parent_id',
        'loc_is_active',
        'loc_created_at',
    ];

    protected $casts = [
        'loc_supports_fixed_items' => 'boolean',
        'loc_capacity' => 'integer',
        'loc_is_active' => 'boolean',
        'loc_created_at' => 'datetime',
    ];

    public function parent()
    {
        return $this->belongsTo(Location::class, 'loc_parent_id', 'loc_id');
    }

    public function children()
    {
        return $this->hasMany(Location::class, 'loc_parent_id', 'loc_id');
    }

    public function snapshots()
    {
        return $this->hasMany(InventorySnapshot::class, 'snap_location_id', 'loc_id');
    }
}
