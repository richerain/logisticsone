<?php

namespace App\Repositories;

use App\Models\PSM\Vendor;
use App\Models\PSM\Product;

class PSMRepository
{
    /**
     * Get all vendors with optional filters and search
     */
    public function getVendors($filters = [])
    {
        $query = Vendor::withCount('products');

        if (!empty($filters['search'])) {
            $query->search($filters['search']);
        }

        if (!empty($filters['status'])) {
            $query->where('ven_status', $filters['status']);
        }

        if (!empty($filters['type'])) {
            $query->where('ven_type', $filters['type']);
        }

        $sortField = $filters['sort_field'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortField, $sortOrder);

        return $query->get();
    }

    /**
     * Get vendor by ID
     */
    public function getVendorById($id)
    {
        return Vendor::with('products')->find($id);
    }

    /**
     * Get vendor by vendor ID
     */
    public function getVendorByVendorId($venId)
    {
        return Vendor::with('products')->where('ven_id', $venId)->first();
    }

    /**
     * Create new vendor
     */
    public function createVendor($data)
    {
        return Vendor::create($data);
    }

    /**
     * Update vendor
     */
    public function updateVendor($id, $data)
    {
        $vendor = Vendor::find($id);
        if ($vendor) {
            $vendor->update($data);
            return $vendor;
        }
        return null;
    }

    /**
     * Delete vendor
     */
    public function deleteVendor($id)
    {
        $vendor = Vendor::find($id);
        if ($vendor) {
            return $vendor->delete();
        }
        return false;
    }

    /**
     * Get products by vendor
     */
    public function getVendorProducts($venId)
    {
        return Product::where('prod_vendor', $venId)->get();
    }

    public function getProducts($filters = [])
    {
        $query = Product::query();
        if (!empty($filters['vendor'])) {
            $query->where('prod_vendor', $filters['vendor']);
        }
        if (!empty($filters['type'])) {
            $query->where('prod_type', $filters['type']);
        }
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('prod_name', 'like', "%{$search}%")
                  ->orWhere('prod_desc', 'like', "%{$search}%");
            });
        }
        $sortField = $filters['sort_field'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortField, $sortOrder);
        return $query->get();
    }

    public function getVendorStats()
    {
        return [
            'total_vendors' => Vendor::count(),
            'active_vendors' => Vendor::where('ven_status', 'active')->count(),
            'inactive_vendors' => Vendor::where('ven_status', 'inactive')->count(),
            'total_products' => Product::count()
        ];
    }

    /**
     * Create product for vendor
     */
    public function createProduct($data)
    {
        return Product::create($data);
    }

    /**
     * Update product
     */
    public function updateProduct($id, $data)
    {
        $product = Product::find($id);
        if ($product) {
            $product->update($data);
            return $product;
        }
        return null;
    }

    /**
     * Delete product
     */
    public function deleteProduct($id)
    {
        $product = Product::find($id);
        if ($product) {
            return $product->delete();
        }
        return false;
    }
}