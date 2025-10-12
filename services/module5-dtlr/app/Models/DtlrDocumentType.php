<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DtlrDocumentType extends Model
{
    use HasFactory;

    protected $table = 'dtlr_document_types';
    
    protected $fillable = [
        'name',
        'description'
    ];

    public function documents()
    {
        return $this->hasMany(DtlrDocument::class, 'document_type_id');
    }
}