<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'document_type',
        'linked_transaction',
        'extracted_fields',
        'file_path',
        'file_name',
        'file_type',
        'status',
        'upload_date',
        'uploaded_by',
        'uploaded_to',
        'description',
        'ocr_processed',
        'ocr_processed_at'
    ];

    protected $casts = [
        'upload_date' => 'date',
        'ocr_processed_at' => 'datetime',
        'extracted_fields' => 'array'
    ];

    public function review(): HasOne
    {
        return $this->hasOne(DocumentReview::class);
    }

    // Scope for different statuses
    public function scopeIndexed($query)
    {
        return $query->where('status', 'indexed');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeUnderReview($query)
    {
        return $query->where('status', 'review');
    }

    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    public function scopeOcrProcessed($query)
    {
        return $query->where('ocr_processed', true);
    }
    // In App\Models\Document class
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->document_id)) {
                $latest = static::orderBy('id', 'desc')->first();
                $nextNumber = $latest ? (int) substr($latest->document_id, 3) + 1 : 1;
                $model->document_id = 'DOC' . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);
            }
        });
    }
}