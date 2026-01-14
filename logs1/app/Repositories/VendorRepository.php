<?php

namespace App\Repositories;

use App\Models\VendorAccount;

class VendorRepository
{
    public function findByEmail($email)
    {
        return VendorAccount::where('email', $email)->first();
    }

    public function create(array $data)
    {
        return VendorAccount::create($data);
    }

    public function update($id, array $data)
    {
        $vendor = VendorAccount::find($id);
        if ($vendor) {
            $vendor->update($data);

            return $vendor;
        }

        return null;
    }

    public function findById($id)
    {
        return VendorAccount::find($id);
    }

    public function getAll()
    {
        return VendorAccount::all();
    }

    public function getActive()
    {
        return VendorAccount::where('status', 'active')->get();
    }
}
