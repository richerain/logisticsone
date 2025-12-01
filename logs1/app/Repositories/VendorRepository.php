<?php

namespace App\Repositories;

use App\Models\Main\Vendor;

class VendorRepository
{
    public function findByEmail($email)
    {
        return Vendor::where('email', $email)->first();
    }

    public function create(array $data)
    {
        return Vendor::create($data);
    }

    public function update($id, array $data)
    {
        $vendor = Vendor::find($id);
        if ($vendor) {
            $vendor->update($data);
            return $vendor;
        }
        return null;
    }

    public function findById($id)
    {
        return Vendor::find($id);
    }

    public function getAll()
    {
        return Vendor::all();
    }

    public function getActive()
    {
        return Vendor::where('status', 'active')->get();
    }
}