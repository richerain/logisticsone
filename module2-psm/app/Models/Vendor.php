<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vendor extends Model
{
    use HasFactory;

    protected $table = 'psm_vendors';
    protected $primaryKey = 'ven_id';
    
    protected $fillable = [
        'ven_name',
        'ven_contacts',
        'ven_email',
        'ven_address',
        'ven_rating',
        'ven_status'
    ];

    protected $casts = [
        'ven_rating' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    public function shops()
    {
        return $this->hasMany(Shop::class, 'ven_id');
    }
}