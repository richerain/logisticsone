<?php

namespace App\Models\SWS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $connection = 'sws';
    protected $table = 'sws_items';
    protected $primaryKey = 'item_id';
    public $timestamps = false;

    protected $fillable = [
        'item_id',
        'item_name',
        'item_description',
        'item_stock_keeping_unit',
        'item_category_id',
        'item_item_type',
        'item_is_fixed',
        'item_expiration_date',
        'item_warranty_end',
        'item_unit_price',
        'item_total_quantity',
        'item_liquidity_risk_level',
        'item_is_collateral',
        'item_created_at',
        'item_updated_at',
    ];

    protected $casts = [
        'item_expiration_date' => 'date',
        'item_warranty_end' => 'date',
        'item_unit_price' => 'decimal:2',
        'item_total_quantity' => 'integer',
        'item_is_fixed' => 'boolean',
        'item_is_collateral' => 'boolean',
        'item_created_at' => 'datetime',
        'item_updated_at' => 'datetime',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'item_category_id', 'cat_id');
    }

    public function snapshots()
    {
        return $this->hasMany(InventorySnapshot::class, 'snap_item_id', 'item_id');
    }

    public function getStockStatusAttribute()
    {
        // This would be calculated based on business logic
        // For now, return a simple status
        if ($this->item_total_quantity <= 0) {
            return 'out_of_stock';
        } elseif ($this->item_total_quantity <= 10) {
            return 'low_stock';
        } else {
            return 'in_stock';
        }
    }
}