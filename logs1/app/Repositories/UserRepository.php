<?php

namespace App\Repositories;

use App\Models\EmployeeAccount;

class UserRepository
{
    public function findByEmail($email)
    {
        return EmployeeAccount::where('email', $email)->first();
    }

    public function createUser(array $data)
    {
        return EmployeeAccount::create($data);
    }

    public function updateUser($id, array $data)
    {
        $user = EmployeeAccount::find($id);
        if ($user) {
            $user->update($data);

            return $user;
        }

        return null;
    }

    public function findById($id)
    {
        return EmployeeAccount::find($id);
    }

    public function getAllUsers()
    {
        return EmployeeAccount::all();
    }

    public function getUsersByRole($role)
    {
        return EmployeeAccount::where('roles', $role)->get();
    }
}
