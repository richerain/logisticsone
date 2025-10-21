<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class Logistics extends Model
{
    use HasFactory;

    protected $primaryKey = 'logistics_id';
    public $incrementing = true;
    protected $keyType = 'int';

    // Specify the correct table name
    protected $table = 'plt_logistics';

    protected $fillable = [
        'delivery_id',
        'vehicle_id',
        'driver_name',
        'route',
        'destination',
        'items',
        'status',
        'receiver_name',
        'delivery_date',
        'notes'
    ];

    protected $casts = [
        'delivery_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->delivery_id)) {
                $model->delivery_id = IdGenerator::generate([
                    'table' => 'plt_logistics', // Make sure this matches the table name
                    'field' => 'delivery_id',
                    'length' => 8,
                    'prefix' => 'DLY'
                ]);
            }
        });
    }

    /**
     * Get the formatted delivery date
     */
    public function getFormattedDeliveryDateAttribute()
    {
        return $this->delivery_date ? $this->delivery_date->format('Y-m-d') : null;
    }

    /**
     * Scope for scheduled deliveries
     */
    public function scopeScheduled($query)
    {
        return $query->where('status', 'Scheduled');
    }

    /**
     * Scope for in transit deliveries
     */
    public function scopeInTransit($query)
    {
        return $query->where('status', 'In Transit');
    }

    /**
     * Scope for delivered deliveries
     */
    public function scopeDelivered($query)
    {
        return $query->where('status', 'Delivered');
    }

    /**
     * Scope for deliveries by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('delivery_date', [$startDate, $endDate]);
    }
}