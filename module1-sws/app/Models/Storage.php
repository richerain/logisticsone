<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class Storage extends Model
{
    use HasFactory;

    protected $table = 'sws_storage';
    protected $primaryKey = 'storage_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'storage_id',
        'storage_name',
        'storage_location',
        'storage_type',
        'storage_max_unit',
        'storage_used_unit',
        'storage_free_unit',
        'storage_utilization_rate',
        'storage_status'
    ];

    public static function generateStorageId()
    {
        $config = [
            'table' => 'sws_storage',
            'field' => 'storage_id',
            'length' => 8,
            'prefix' => 'STR',
            'reset_on_prefix_change' => true
        ];

        return IdGenerator::generate($config);
    }

    public function inventories()
    {
        return $this->hasMany(Inventory::class, 'item_storage_from', 'storage_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->storage_free_unit = $model->storage_max_unit - $model->storage_used_unit;
            $model->storage_utilization_rate = ($model->storage_used_unit / $model->storage_max_unit) * 100;
        });

        static::updating(function ($model) {
            $model->storage_free_unit = $model->storage_max_unit - $model->storage_used_unit;
            $model->storage_utilization_rate = ($model->storage_used_unit / $model->storage_max_unit) * 100;
        });
    }
}