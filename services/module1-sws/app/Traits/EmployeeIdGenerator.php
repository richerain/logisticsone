<?php

namespace App\Traits;

use Haruncpi\LaravelIdGenerator\IdGenerator;

trait EmployeeIdGenerator
{
    public static function generateEmployeeId($role)
    {
        // Use EMP prefix for all roles
        $config = [
            'table' => 'users',
            'field' => 'employee_id',
            'length' => 8,
            'prefix' => 'EMP',
            'reset_on_prefix_change' => false
        ];

        return IdGenerator::generate($config);
    }
}