<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class Restock extends Model
{
    use HasFactory;

    protected $table = 'sws_restock';
    protected $primaryKey = 'restock_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'restock_id',
        'restock_item_id',
        'restock_item_name',
        'restock_item_type',
        'restock_item_unit',
        'restock_item_capacity',
        'restock_desc',
        'restock_status'
    ];

    public static function generateRestockId()
    {
        $config = [
            'table' => 'sws_restock',
            'field' => 'restock_id',
            'length' => 8,
            'prefix' => 'RES',
            'reset_on_prefix_change' => true
        ];

        return IdGenerator::generate($config);
    }

    public function inventory()
    {
        return $this->belongsTo(Inventory::class, 'restock_item_id', 'item_id');
    }
}