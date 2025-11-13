<?php

namespace App\Services;

use App\Repositories\PSMRepository;
use App\Models\PSM\Vendor;
use App\Models\PSM\Product;
use Illuminate\Support\Facades\DB;
use Exception;

class PSMService
{
    protected $psmRepository;

    public function __construct(PSMRepository $psmRepository)
    {
        $this->psmRepository = $psmRepository;
    }

    /**
     * Get all vendors with filters
     */
    public function getVendors($filters = [])
    {
        try {
            $vendors = $this->psmRepository->getVendors($filters);

            return [
                'success' => true,
                'data' => $vendors,
                'message' => 'Vendors retrieved successfully'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch vendors: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }

    /**
     * Get vendor by ID
     */
    public function getVendor($id)
    {
        try {
            $vendor = $this->psmRepository->getVendorById($id);
            
            if ($vendor) {
                return [
                    'success' => true,
                    'data' => $vendor,
                    'message' => 'Vendor retrieved successfully'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Vendor not found',
                    'data' => null
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch vendor: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    public function getVendorByVendorId($venId)
    {
        try {
            $vendor = $this->psmRepository->getVendorByVendorId($venId);
            if ($vendor) {
                return [
                    'success' => true,
                    'data' => $vendor,
                    'message' => 'Vendor retrieved successfully'
                ];
            }
            return [
                'success' => false,
                'message' => 'Vendor not found',
                'data' => null
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch vendor: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * Create new vendor
     */
    public function createVendor($data)
    {
        DB::beginTransaction();
        try {
            $data['ven_id'] = $this->generateVendorId();
            
            $vendor = $this->psmRepository->createVendor($data);
            
            DB::commit();
            
            return [
                'success' => true,
                'message' => 'Vendor created successfully',
                'data' => $vendor
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Failed to create vendor: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * Update vendor
     */
    public function updateVendor($id, $data)
    {
        DB::beginTransaction();
        try {
            $vendor = $this->psmRepository->updateVendor($id, $data);
            
            if ($vendor) {
                DB::commit();
                return [
                    'success' => true,
                    'message' => 'Vendor updated successfully',
                    'data' => $vendor
                ];
            } else {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'Vendor not found',
                    'data' => null
                ];
            }
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Failed to update vendor: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    /**
     * Delete vendor
     */
    public function deleteVendor($id)
    {
        DB::beginTransaction();
        try {
            $result = $this->psmRepository->deleteVendor($id);
            
            if ($result) {
                DB::commit();
                return [
                    'success' => true,
                    'message' => 'Vendor deleted successfully'
                ];
            } else {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'Vendor not found'
                ];
            }
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Failed to delete vendor: ' . $e->getMessage()
            ];
        }
    }

    public function getVendorStats()
    {
        try {
            $stats = $this->psmRepository->getVendorStats();
            return [
                'success' => true,
                'data' => $stats,
                'message' => 'Vendor stats retrieved successfully'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch vendor stats: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }

    public function getProducts($filters = [])
    {
        try {
            $products = $this->psmRepository->getProducts($filters);
            return [
                'success' => true,
                'data' => $products,
                'message' => 'Products retrieved successfully'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch products: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }

    public function getVendorProducts($venId)
    {
        try {
            $products = $this->psmRepository->getVendorProducts($venId);
            return [
                'success' => true,
                'data' => $products,
                'message' => 'Vendor products retrieved successfully'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch vendor products: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }

    /**
     * Generate unique vendor ID
     */
    private function generateVendorId()
    {
        $prefix = 'VEND';
        $last = Vendor::where('ven_id', 'like', $prefix . '%')
            ->orderBy('ven_id', 'desc')
            ->first();
        $next = $last ? (int) substr($last->ven_id, strlen($prefix)) + 1 : 1;
        return $prefix . str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Create product for vendor
     */
    public function createProduct($data)
    {
        DB::beginTransaction();
        try {
            $data['prod_id'] = $this->generateProductId();
            
            $product = $this->psmRepository->createProduct($data);
            
            DB::commit();
            
            return [
                'success' => true,
                'message' => 'Product created successfully',
                'data' => $product
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Failed to create product: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    public function updateProduct($id, $data)
    {
        DB::beginTransaction();
        try {
            $product = $this->psmRepository->updateProduct($id, $data);
            if ($product) {
                DB::commit();
                return [
                    'success' => true,
                    'message' => 'Product updated successfully',
                    'data' => $product
                ];
            }
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Product not found',
                'data' => null
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Failed to update product: ' . $e->getMessage(),
                'data' => null
            ];
        }
    }

    public function deleteProduct($id)
    {
        DB::beginTransaction();
        try {
            $result = $this->psmRepository->deleteProduct($id);
            if ($result) {
                DB::commit();
                return [
                    'success' => true,
                    'message' => 'Product deleted successfully'
                ];
            }
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Product not found'
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Failed to delete product: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Generate unique product ID
     */
    private function generateProductId()
    {
        $prefix = 'PROD';
        $last = Product::where('prod_id', 'like', $prefix . '%')
            ->orderBy('prod_id', 'desc')
            ->first();
        $next = $last ? (int) substr($last->prod_id, strlen($prefix)) + 1 : 1;
        return $prefix . str_pad($next, 5, '0', STR_PAD_LEFT);
    }
}