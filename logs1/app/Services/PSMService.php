<?php

namespace App\Services;

use App\Repositories\PSMRepository;
use App\Models\PSM\Vendor;
use App\Models\PSM\Product;
use App\Models\PSM\Purchase;
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
     * Get active vendors for purchase orders
     */
    public function getActiveVendorsForPurchase()
    {
        try {
            $vendors = Vendor::where('ven_status', 'active')
                ->with(['products' => function($query) {
                    $query->where('prod_stock', '>', 0);
                }])
                ->get()
                ->map(function($vendor) {
                    return [
                        'id' => $vendor->id,
                        'ven_id' => $vendor->ven_id,
                        'company_name' => $vendor->ven_company_name,
                        'type' => $vendor->ven_type,
                        'products' => $vendor->products->map(function($product) {
                            return [
                                'id' => $product->id,
                                'prod_id' => $product->prod_id,
                                'name' => $product->prod_name,
                                'price' => $product->prod_price,
                                'stock' => $product->prod_stock,
                                'type' => $product->prod_type
                            ];
                        })
                    ];
                });

            return [
                'success' => true,
                'data' => $vendors,
                'message' => 'Active vendors retrieved successfully'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch active vendors: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }

    /**
     * Get all purchases with filters
     */
    public function getPurchases($filters = [])
    {
        try {
            $purchases = $this->psmRepository->getPurchases($filters);

            return [
                'success' => true,
                'data' => $purchases,
                'message' => 'Purchases retrieved successfully'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch purchases: ' . $e->getMessage(),
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

    /**
     * Get purchase by ID
     */
    public function getPurchase($id)
    {
        try {
            $purchase = $this->psmRepository->getPurchaseById($id);
            
            if ($purchase) {
                return [
                    'success' => true,
                    'data' => $purchase,
                    'message' => 'Purchase retrieved successfully'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Purchase not found',
                    'data' => null
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch purchase: ' . $e->getMessage(),
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

    public function getPurchaseByPurchaseId($purId)
    {
        try {
            $purchase = $this->psmRepository->getPurchaseByPurchaseId($purId);
            if ($purchase) {
                return [
                    'success' => true,
                    'data' => $purchase,
                    'message' => 'Purchase retrieved successfully'
                ];
            }
            return [
                'success' => false,
                'message' => 'Purchase not found',
                'data' => null
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch purchase: ' . $e->getMessage(),
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
     * Create new purchase
     */
    public function createPurchase($data)
    {
        DB::beginTransaction();
        try {
            $data['pur_id'] = $this->generatePurchaseId();
            
            // Ensure items is an array with name and price
            if (isset($data['pur_name_items']) && is_string($data['pur_name_items'])) {
                $data['pur_name_items'] = json_decode($data['pur_name_items'], true);
            }
            
            // Set default status to pending
            $data['pur_status'] = 'pending';
            
            // Calculate total units and amount from items
            $items = $data['pur_name_items'] ?? [];
            $data['pur_unit'] = count($items);
            
            $totalAmount = 0;
            foreach ($items as $item) {
                if (isset($item['price'])) {
                    $totalAmount += floatval($item['price']);
                }
            }
            $data['pur_total_amount'] = $totalAmount;
            
            $purchase = $this->psmRepository->createPurchase($data);
            
            DB::commit();
            
            return [
                'success' => true,
                'message' => 'Purchase order created successfully',
                'data' => $purchase
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Failed to create purchase: ' . $e->getMessage(),
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
     * Update purchase
     */
    public function updatePurchase($id, $data)
    {
        DB::beginTransaction();
        try {
            // Ensure items is an array with name and price
            if (isset($data['pur_name_items']) && is_string($data['pur_name_items'])) {
                $data['pur_name_items'] = json_decode($data['pur_name_items'], true);
            }
            
            // Calculate total units and amount from items
            $items = $data['pur_name_items'] ?? [];
            $data['pur_unit'] = count($items);
            
            $totalAmount = 0;
            foreach ($items as $item) {
                if (isset($item['price'])) {
                    $totalAmount += floatval($item['price']);
                }
            }
            $data['pur_total_amount'] = $totalAmount;
            
            $purchase = $this->psmRepository->updatePurchase($id, $data);
            
            if ($purchase) {
                DB::commit();
                return [
                    'success' => true,
                    'message' => 'Purchase order updated successfully',
                    'data' => $purchase
                ];
            } else {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'Purchase not found',
                    'data' => null
                ];
            }
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Failed to update purchase: ' . $e->getMessage(),
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

    /**
     * Delete purchase
     */
    public function deletePurchase($id)
    {
        DB::beginTransaction();
        try {
            $result = $this->psmRepository->deletePurchase($id);
            
            if ($result) {
                DB::commit();
                return [
                    'success' => true,
                    'message' => 'Purchase deleted successfully'
                ];
            } else {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'Purchase not found'
                ];
            }
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Failed to delete purchase: ' . $e->getMessage()
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

    public function getPurchaseStats()
    {
        try {
            $stats = $this->psmRepository->getPurchaseStats();
            return [
                'success' => true,
                'data' => $stats,
                'message' => 'Purchase stats retrieved successfully'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch purchase stats: ' . $e->getMessage(),
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
     * Generate unique purchase ID
     */
    private function generatePurchaseId()
    {
        $prefix = 'PURC';
        $last = Purchase::where('pur_id', 'like', $prefix . '%')
            ->orderBy('pur_id', 'desc')
            ->first();
        $next = $last ? (int) substr($last->pur_id, strlen($prefix)) + 1 : 1;
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