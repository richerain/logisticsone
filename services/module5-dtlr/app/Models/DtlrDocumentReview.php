<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DtlrDocumentReview extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dtlr_document_reviews';
    
    protected $fillable = [
        'document_id',
        'reviewer_id',
        'review_status',
        'comments',
        'reviewed_at'
    ];

    protected $casts = [
        'reviewed_at' => 'datetime'
    ];

    public function document()
    {
        return $this->belongsTo(DtlrDocument::class, 'document_id');
    }

    public function reviewer()
    {
        return $this->belongsTo(DtlrUser::class, 'reviewer_id');
    }
}