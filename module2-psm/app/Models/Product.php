<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    protected $table = 'psm_products';
    protected $primaryKey = 'prod_id';
    
    protected $fillable = [
        'shop_id',
        'prod_name',
        'prod_category',
        'prod_stock',
        'prod_stock_status',
        'prod_price',
        'prod_desc',
        'prod_img',
        'prod_publish'
    ];

    protected $casts = [
        'prod_price' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'prod_id');
    }
}