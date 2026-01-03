<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\MAIN\User;

class EmployeeAccount extends Model
{
    protected $connection = 'main';
    protected $table = 'employee_account';
    
    protected $guarded = [];

    protected $casts = [
        'birthdate' => 'date',
        'otp_expires_at' => 'datetime',
        'email_verified_at' => 'datetime',
        'last_login' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
