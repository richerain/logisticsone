<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MAIN\Vendor;

class VendorAccount extends Model
{
    protected $connection = 'main';
    protected $table = 'vendor_account';

    protected $guarded = [];

    protected $casts = [
        'birthdate' => 'date',
        'otp_expires_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'last_login' => 'datetime',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
}
