<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class Digital extends Model
{
    use HasFactory;

    protected $table = 'sws_digital';

    protected $primaryKey = 'id';

    protected $fillable = [
        'stock_id',
        'item_name',
        'type',
        'units',
        'available_item',
        'status',
        'grn_id'
    ];

    protected $casts = [
        'available_item' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Generate Stock ID in format STK00001
     */
    public static function generateStockId()
    {
        try {
            $config = [
                'table' => 'sws_digital',
                'field' => 'stock_id',
                'length' => 8,
                'prefix' => 'STK',
                'reset_on_prefix_change' => true
            ];

            return IdGenerator::generate($config);
        } catch (\Exception $e) {
            // Fallback ID generation if package fails
            $lastRecord = self::orderBy('created_at', 'desc')->first();
            $lastId = $lastRecord ? intval(substr($lastRecord->stock_id, 3)) : 0;
            $newId = $lastId + 1;
            return 'STK' . str_pad($newId, 5, '0', STR_PAD_LEFT);
        }
    }

    /**
     * Relationship with Warehousing (Goods Received)
     */
    public function warehousing()
    {
        return $this->belongsTo(Warehousing::class, 'grn_id', 'id');
    }

    /**
     * Scope a query to only include low stock items
     */
    public function scopeLowStock($query)
    {
        return $query->where('status', 'lowstock');
    }

    /**
     * Scope a query to only include on stock items
     */
    public function scopeOnStock($query)
    {
        return $query->where('status', 'onstock');
    }

    /**
     * Scope a query to only include out of stock items
     */
    public function scopeOutOfStock($query)
    {
        return $query->where('status', 'outofstock');
    }

    /**
     * Update status based on available items
     */
    public function updateStatus()
    {
        if ($this->available_item <= 0) {
            $this->status = 'outofstock';
        } elseif ($this->available_item < 10) { // Assuming low stock threshold is 10
            $this->status = 'lowstock';
        } else {
            $this->status = 'onstock';
        }
        
        $this->save();
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeAttribute()
    {
        $statusClasses = [
            'lowstock' => 'bg-yellow-500 text-white',
            'onstock' => 'bg-green-500 text-white',
            'outofstock' => 'bg-red-500 text-white'
        ];
        
        return $statusClasses[$this->status] ?? 'bg-gray-500 text-white';
    }
}