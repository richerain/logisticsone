<?php

namespace App\Models\Main;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    use HasFactory;

    protected $connection = 'main';
    protected $table = 'announcements';

    const CREATED_AT = 'created_date';
    const UPDATED_AT = 'updated_date';

    protected $fillable = [
        'title',
        'desc',
        'announcement_image',
    ];

    protected $casts = [
        'created_date' => 'datetime',
        'updated_date' => 'datetime',
    ];
}
