<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DtlrDocumentLog extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'dtlr_document_logs';
    
    protected $fillable = [
        'document_id',
        'action',
        'from_branch_id',
        'to_branch_id',
        'performed_by',
        'timestamp',
        'notes',
        'ip_address'
    ];

    protected $casts = [
        'timestamp' => 'datetime'
    ];

    public function document()
    {
        return $this->belongsTo(DtlrDocument::class, 'document_id');
    }

    public function fromBranch()
    {
        return $this->belongsTo(DtlrBranch::class, 'from_branch_id');
    }

    public function toBranch()
    {
        return $this->belongsTo(DtlrBranch::class, 'to_branch_id');
    }

    public function performer()
    {
        return $this->belongsTo(DtlrUser::class, 'performed_by');
    }
}