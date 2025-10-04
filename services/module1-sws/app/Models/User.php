<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Haruncpi\LaravelIdGenerator\IdGenerator;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'employee_id',
        'lastname',
        'firstname',
        'middlename',
        'sex',
        'age',
        'birthdate',
        'contactnum',
        'Email',
        'password',
        'roles',
        'status',
        'profile_picture',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'birthdate' => 'date',
        ];
    }

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($user) {
            if (empty($user->employee_id)) {
                $prefixes = [
                    'superadmin' => 'L1SAD',
                    'admin' => 'L1ADM',
                    'manager' => 'L1MAN',
                    'staff' => 'L1STF',
                    'vendor' => 'L1VEN'
                ];

                $prefix = $prefixes[strtolower($user->roles)] ?? 'L1EMP';
                
                $config = [
                    'table' => 'users',
                    'field' => 'employee_id',
                    'length' => 8,
                    'prefix' => $prefix,
                    'reset_on_prefix_change' => true
                ];

                $user->employee_id = IdGenerator::generate($config);
            }
        });
    }
}