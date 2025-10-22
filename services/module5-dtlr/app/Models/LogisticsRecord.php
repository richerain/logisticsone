<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogisticsRecord extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dtlr_logistics_record';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'log_id',
        'action',
        'module',
        'performed_by',
        'timestamp',
        'ai_ocr_used',
        'details'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'timestamp' => 'datetime',
        'ai_ocr_used' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get AI/OCR usage status as text
     */
    public function getAiOcrUsedTextAttribute()
    {
        return $this->ai_ocr_used ? 'Yes' : 'No';
    }

    /**
     * Get formatted timestamp
     */
    public function getFormattedTimestampAttribute()
    {
        return $this->timestamp->format('M j, Y g:i A');
    }

    /**
     * Get time ago format
     */
    public function getTimeAgoAttribute()
    {
        return $this->timestamp->diffForHumans();
    }

    /**
     * Scope for recent logs
     */
    public function scopeRecent($query, $days = 7)
    {
        return $query->where('timestamp', '>=', now()->subDays($days));
    }

    /**
     * Scope for AI/OCR logs
     */
    public function scopeWithAI($query)
    {
        return $query->where('ai_ocr_used', true);
    }

    /**
     * Scope for module specific logs
     */
    public function scopeForModule($query, $module)
    {
        return $query->where('module', $module);
    }
}