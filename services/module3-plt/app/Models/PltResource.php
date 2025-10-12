<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PltResource extends Model
{
    use HasFactory;

    protected $table = 'plt_resources';

    protected $fillable = [
        'name',
        'type',
        'description',
        'quantity_available',
        'location'
    ];

    public function allocations(): HasMany
    {
        return $this->hasMany(PltAllocation::class, 'resource_id');
    }
}