<?php

namespace App\Models\SWS;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomRequest extends Model
{
    use HasFactory;

    protected $connection = 'sws';

    protected $table = 'sws_room_request';

    protected $primaryKey = 'rmreq_id';

    public $timestamps = false;

    protected $fillable = [
        'rmreq_requester',
        'rmreq_room_type',
        'rmreq_note',
        'rmreq_date',
        'rmreq_status',
    ];
}

