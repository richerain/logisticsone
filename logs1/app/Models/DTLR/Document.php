<?php

namespace App\Models\DTLR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;

    protected $connection = 'dtlr';

    protected $table = 'dtlr_documents';

    protected $primaryKey = 'doc_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'doc_id',
        'doc_type',
        'doc_title',
        'doc_status',
        'doc_file_available',
        'doc_file_path',
        'doc_file_original_name',
        'doc_file_mime',
        'doc_file_size',
    ];
}
