<?php

namespace App\Models\SWS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $connection = 'sws';

    protected $table = 'sws_transactions';

    protected $primaryKey = 'tra_id';

    public $timestamps = false;

    protected $fillable = [
        'tra_item_id',
        'tra_type',
        'tra_quantity',
        'tra_from_location_id',
        'tra_to_location_id',
        'tra_warehouse_id',
        'tra_transaction_date',
        'tra_reference_id',
        'tra_status',
        'tra_notes',
    ];

    protected $casts = [
        'tra_quantity' => 'integer',
        'tra_transaction_date' => 'datetime',
    ];

    public function item()
    {
        return $this->belongsTo(Item::class, 'tra_item_id', 'item_id');
    }

    public function fromLocation()
    {
        return $this->belongsTo(Location::class, 'tra_from_location_id', 'loc_id');
    }

    public function toLocation()
    {
        return $this->belongsTo(Location::class, 'tra_to_location_id', 'loc_id');
    }

    public function warehouse()
    {
        return $this->belongsTo(Warehouse::class, 'tra_warehouse_id', 'ware_id');
    }

    public function logs()
    {
        return $this->hasMany(TransactionLog::class, 'log_transaction_id', 'tra_id');
    }
}
