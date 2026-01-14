<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class VendorAccount extends Authenticatable
{
    use Notifiable;

    protected $connection = 'main';
    protected $table = 'vendor_account';

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
        'otp',
    ];

    protected $casts = [
        'birthdate' => 'date',
        'otp_expires_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'last_login' => 'datetime',
    ];
}
