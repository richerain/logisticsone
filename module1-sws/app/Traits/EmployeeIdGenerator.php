<?php

namespace App\Traits;

use Haruncpi\LaravelIdGenerator\IdGenerator;

trait EmployeeIdGenerator
{
    public static function generateEmployeeId($role)
    {
        $prefixes = [
            'superadmin' => 'L1SAD',
            'admin' => 'L1ADM',
            'manager' => 'L1MAN',
            'staff' => 'L1STF',
            'vendor' => 'L1VEN'
        ];

        $prefix = $prefixes[strtolower($role)] ?? 'L1EMP';
        
        $config = [
            'table' => 'users',
            'field' => 'employee_id',
            'length' => 8,
            'prefix' => $prefix,
            'reset_on_prefix_change' => true
        ];

        return IdGenerator::generate($config);
    }
}