<?php

namespace App\Models\Main;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $connection = 'main';
    protected $table = 'users';

    protected $fillable = [
        'employeeid',
        'lastname',
        'firstname',
        'middlename',
        'sex',
        'age',
        'birthdate',
        'contactnum',
        'email',
        'address',
        'password',
        'picture',
        'roles',
        'status',
        'email_verified_at',
        'otp',
        'otp_expires_at'
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp'
    ];

    protected $casts = [
        'birthdate' => 'date',
        'email_verified_at' => 'datetime',
        'otp_expires_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function isActive()
    {
        return $this->status === 'active';
    }

    public function hasRole($role)
    {
        return $this->roles === $role;
    }

    // Check if OTP is expired
    public function isOtpExpired()
    {
        if (!$this->otp_expires_at) {
            return true;
        }
        
        return Carbon::now()->gt($this->otp_expires_at);
    }

    // Specify the guard for this model
    public function guardName()
    {
        return 'sws';
    }
}