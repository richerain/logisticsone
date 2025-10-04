<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'sws_inventory';
    protected $primaryKey = 'item_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'item_id',
        'item_name',
        'item_type',
        'item_stock',
        'item_stock_capacity',
        'item_desc',
        'item_storage_from',
        'item_stock_level',
        'item_status'
    ];

    public static function generateItemId()
    {
        $config = [
            'table' => 'sws_inventory',
            'field' => 'item_id',
            'length' => 8,
            'prefix' => 'ITM',
            'reset_on_prefix_change' => true
        ];

        return IdGenerator::generate($config);
    }

    public function storage()
    {
        return $this->belongsTo(Storage::class, 'item_storage_from', 'storage_id');
    }

    public function restocks()
    {
        return $this->hasMany(Restock::class, 'restock_item_id', 'item_id');
    }
}