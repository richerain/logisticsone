<?php

namespace App\Models\DTLR;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LogisticsRecord extends Model
{
    use HasFactory;

    protected $connection = 'dtlr';

    protected $table = 'dtlr_logistics_records';

    protected $primaryKey = 'log_id';

    public $incrementing = false;

    protected $keyType = 'string';

    protected $fillable = [
        'log_id',
        'doc_id',
        'doc_type',
        'doc_title',
        'doc_status',
        'module',
        'submodule',
        'performed_action',
        'performed_by',
    ];
}
