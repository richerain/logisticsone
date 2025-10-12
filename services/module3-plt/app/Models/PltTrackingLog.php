<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PltTrackingLog extends Model
{
    use HasFactory;

    protected $table = 'plt_tracking_logs';

    protected $fillable = [
        'dispatch_id',
        'timestamp',
        'location',
        'status_update',
        'notes'
    ];

    protected $casts = [
        'timestamp' => 'datetime'
    ];

    public function dispatch(): BelongsTo
    {
        return $this->belongsTo(PltDispatch::class, 'dispatch_id');
    }
}