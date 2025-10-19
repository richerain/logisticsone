<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogisticsRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'log_id',
        'action',
        'module',
        'description',
        'performed_by',
        'timestamp',
        'ai_ocr_used',
        'related_reference',
        'ip_address',
        'user_agent'
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'ai_ocr_used' => 'boolean'
    ];

    // Scope for different actions
    public function scopeUploads($query)
    {
        return $query->where('action', 'uploaded');
    }

    public function scopeApprovals($query)
    {
        return $query->where('action', 'approved');
    }

    public function scopeDeliveries($query)
    {
        return $query->where('action', 'delivered');
    }

    public function scopeAiUsed($query)
    {
        return $query->where('ai_ocr_used', true);
    }
}