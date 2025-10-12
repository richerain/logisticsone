<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Disposal extends Model
{
    use HasFactory;

    protected $table = 'alms_disposals';
    
    protected $fillable = [
        'disposal_id',
        'asset_id',
        'disposal_date',
        'method',
        'disposal_value',
        'reason',
        'compliance_notes'
    ];

    protected $casts = [
        'disposal_date' => 'date',
        'disposal_value' => 'decimal:2'
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }
}