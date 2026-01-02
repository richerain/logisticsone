<?php

namespace App\Repositories;

use App\Models\Main\User;

class UserRepository
{
    public function findByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    public function createUser(array $data)
    {
        return User::create($data);
    }

    public function updateUser($id, array $data)
    {
        $user = User::find($id);
        if ($user) {
            $user->update($data);

            return $user;
        }

        return null;
    }

    public function findById($id)
    {
        return User::find($id);
    }

    public function getAllUsers()
    {
        return User::all();
    }

    public function getUsersByRole($role)
    {
        return User::where('roles', $role)->get();
    }
}
