<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DtlrDocument extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dtlr_documents';
    
    protected $fillable = [
        'tracking_number',
        'document_type_id',
        'title',
        'description',
        'extracted_data',
        'file_path',
        'status',
        'current_branch_id',
        'created_by',
        'updated_by',
        'ocr_processed_at'
    ];

    protected $casts = [
        'extracted_data' => 'array',
        'ocr_processed_at' => 'datetime'
    ];

    public function documentType()
    {
        return $this->belongsTo(DtlrDocumentType::class, 'document_type_id');
    }

    public function currentBranch()
    {
        return $this->belongsTo(DtlrBranch::class, 'current_branch_id');
    }

    public function creator()
    {
        return $this->belongsTo(DtlrUser::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(DtlrUser::class, 'updated_by');
    }

    public function documentLogs()
    {
        return $this->hasMany(DtlrDocumentLog::class, 'document_id');
    }

    public function documentReviews()
    {
        return $this->hasMany(DtlrDocumentReview::class, 'document_id');
    }
}