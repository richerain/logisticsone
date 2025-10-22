<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentTracker extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'dtlr_document_tracker';

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
        'document_id',
        'document_type',
        'linked_transaction',
        'extracted_fields',
        'upload_date',
        'uploaded_by',
        'status',
        'file_path',
        'file_name',
        'file_size',
        'file_type'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'upload_date' => 'datetime',
        'file_size' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Get the document type in full text
     */
    public function getDocumentTypeFullAttribute()
    {
        $types = [
            'PO' => 'Purchase Order',
            'GRN' => 'Goods Received Note',
            'Invoice' => 'Invoice',
            'Delivery Note' => 'Delivery Note',
            'Quotation' => 'Quotation',
            'Contract' => 'Contract',
            'Other' => 'Other Document'
        ];

        return $types[$this->document_type] ?? $this->document_type;
    }

    /**
     * Get extracted fields as array
     */
    public function getExtractedFieldsArrayAttribute()
    {
        if (empty($this->extracted_fields)) {
            return [];
        }

        if (is_string($this->extracted_fields)) {
            $decoded = json_decode($this->extracted_fields, true);
            return $decoded ?: [];
        }

        return $this->extracted_fields ?? [];
    }

    /**
     * Check if document has file
     */
    public function getHasFileAttribute()
    {
        return !empty($this->file_path);
    }

    /**
     * Get file size in human readable format
     */
    public function getFileSizeFormattedAttribute()
    {
        if (!$this->file_size) return 'N/A';

        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = $this->file_size;
        $factor = floor((strlen($bytes) - 1) / 3);
        
        return sprintf("%.2f", $bytes / pow(1024, $factor)) . ' ' . $units[$factor];
    }

    /**
     * Set extracted fields attribute
     */
    public function setExtractedFieldsAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['extracted_fields'] = json_encode($value);
        } else {
            $this->attributes['extracted_fields'] = $value;
        }
    }
}