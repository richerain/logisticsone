<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;

    protected $table = 'alms_assets';

    protected $fillable = [
        'asset_id',
        'asset_name',
        'asset_type',
        'assigned_location',
        'deployment_date',
        'next_service_date',
        'warranty_start',
        'warranty_end',
        'status',
        'description'
    ];

    protected $casts = [
        'deployment_date' => 'date',
        'next_service_date' => 'date',
        'warranty_start' => 'date',
        'warranty_end' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
}