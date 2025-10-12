<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Reorder extends Model
{
    use HasFactory;

    protected $table = 'psm_reorders';
    protected $primaryKey = 'restock_id';
    
    protected $fillable = [
        'order_id',
        'shop_id',
        'prod_id',
        'quantity',
        'restock_price',
        'total_amount',
        'budget_approval_status',
        'restock_desc',
        'restock_status',
        'sws_stock_id',
        'trigger_threshold'
    ];

    protected $casts = [
        'restock_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'prod_id');
    }
}