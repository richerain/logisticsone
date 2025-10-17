<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Branch extends Model
{
    use HasFactory;

    protected $table = 'alms_branches';
    
    protected $fillable = [
        'name',
        'address',
        'code'
    ];

    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'current_branch_id');
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'branch_id');
    }

    public function fromTransfers(): HasMany
    {
        return $this->hasMany(AssetTransfer::class, 'from_branch_id');
    }

    public function toTransfers(): HasMany
    {
        return $this->hasMany(AssetTransfer::class, 'to_branch_id');
    }
}