<?php

namespace App\Models\SWS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TransactionLog extends Model
{
    use HasFactory;

    protected $connection = 'sws';

    protected $table = 'sws_transaction_logs';

    protected $primaryKey = 'log_id';

    public $timestamps = false;

    protected $fillable = [
        'log_transaction_id',
        'log_event',
        'log_details',
        'log_logged_at',
        'log_logged_by',
    ];

    protected $casts = [
        'log_logged_at' => 'datetime',
    ];

    public function transaction()
    {
        return $this->belongsTo(Transaction::class, 'log_transaction_id', 'tra_id');
    }
}
