<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Asset extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'asset_code',
        'category_id',
        'branch_id',
        'name',
        'serial_number',
        'purchase_date',
        'purchase_cost',
        'warranty_period',
        'assigned_employee_id',
        'specifications',
        'image_path',
        'status'
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'purchase_cost' => 'decimal:2'
    ];

    public function category()
    {
        return $this->belongsTo(AssetCategory::class, 'category_id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function assignedEmployee()
    {
        return $this->belongsTo(Employee::class, 'assigned_employee_id');
    }

    public function maintenances()
    {
        return $this->hasMany(Maintenance::class, 'asset_id');
    }

    public function depreciations()
    {
        return $this->hasMany(Depreciation::class, 'asset_id');
    }
}