<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $table = 'psm_orders';
    protected $primaryKey = 'order_id';
    
    protected $fillable = [
        'req_id',
        'shop_id',
        'prod_id',
        'quantity',
        'order_price',
        'total_amount',
        'budget_approval_status',
        'order_desc',
        'order_status',
        'sws_stock_id',
        'settled_at'
    ];

    protected $casts = [
        'order_price' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'settled_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'prod_id');
    }

    public function budgetApprovals()
    {
        return $this->hasMany(BudgetApproval::class, 'entity_id')->where('entity_type', 'order');
    }
}