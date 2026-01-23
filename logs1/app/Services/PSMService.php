<?php

namespace App\Services;

use App\Models\PSM\Budget;
use App\Models\PSM\Product;
use App\Models\PSM\Purchase;
use App\Models\PSM\Quote;
use App\Models\PSM\Vendor;
use App\Models\VendorAccount;
use App\Repositories\PSMRepository;
use Exception;
use Illuminate\Support\Facades\DB;

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
            $query = VendorAccount::where('status', 'active');

            if (isset($filters['search']) && $filters['search']) {
                $search = $filters['search'];
                $query->where(function ($q) use ($search) {
                    $q->where('company_name', 'like', "%{$search}%")
                      ->orWhere('firstname', 'like', "%{$search}%")
                      ->orWhere('lastname', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            }

            if (isset($filters['type']) && $filters['type']) {
                $query->where('company_type', $filters['type']);
            }

            $vendors = $query->get()->map(function ($vendor) {
                return [
                    'id' => $vendor->id,
                    'ven_id' => $vendor->vendorid,
                    'ven_company_name' => $vendor->company_name,
                    'ven_contact_person' => trim($vendor->firstname . ' ' . $vendor->lastname),
                    'ven_email' => $vendor->email,
                    'ven_phone' => $vendor->contactnum,
                    'ven_address' => $vendor->address,
                    'ven_type' => $vendor->company_type ?? 'Unknown',
                    'ven_rating' => $vendor->rating ?? 0,
                    'ven_status' => $vendor->status,
                    'ven_picture' => $vendor->picture,
                    'ven_desc' => '',
                    'created_at' => $vendor->created_at,
                    'updated_at' => $vendor->updated_at,
                ];
            });

            return [
                'success' => true,
                'data' => $vendors,
                'message' => 'Vendors retrieved successfully',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch vendors: '.$e->getMessage(),
                'data' => [],
            ];
        }
    }

    /**
     * Get active vendors for purchase orders
     */
    public function getActiveVendorsForPurchase()
    {
        try {
            $vendors = VendorAccount::where('status', 'active')->get();
            
            $data = $vendors->map(function ($vendor) {
                // Find products where prod_vendor matches vendorid (cross-db)
                $products = Product::where('prod_vendor', $vendor->vendorid)
                    ->where('prod_stock', '>', 0)
                    ->get();

                return [
                    'id' => $vendor->id,
                    'ven_id' => $vendor->vendorid,
                    'company_name' => $vendor->company_name,
                    'type' => $vendor->company_type,
                    'products' => $products->map(function ($product) {
                        return [
                            'id' => $product->id,
                            'prod_id' => $product->prod_id,
                            'name' => $product->prod_name,
                            'price' => $product->prod_price,
                            'stock' => $product->prod_stock,
                            'type' => $product->prod_type,
                        ];
                    }),
                ];
            });

            return [
                'success' => true,
                'data' => $data,
                'message' => 'Active vendors retrieved successfully',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch active vendors: '.$e->getMessage(),
                'data' => [],
            ];
        }
    }

    /**
     * Get all purchases with filters
     */
    public function getPurchases($filters = [])
    {
        try {
            $purchases = $this->psmRepository->getPurchases();

            return [
                'success' => true,
                'data' => $purchases,
                'message' => 'Purchases retrieved successfully',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch purchases: '.$e->getMessage(),
                'data' => [],
            ];
        }
    }

    /**
     * Get vendor by ID
     */
    public function getVendor($id)
    {
        try {
            $vendor = VendorAccount::find($id);

            if ($vendor) {
                $data = [
                    'id' => $vendor->id,
                    'ven_id' => $vendor->vendorid,
                    'ven_company_name' => $vendor->company_name,
                    'ven_contact_person' => trim($vendor->firstname . ' ' . $vendor->lastname),
                    'ven_email' => $vendor->email,
                    'ven_phone' => $vendor->contactnum,
                    'ven_address' => $vendor->address,
                    'ven_type' => $vendor->company_type ?? 'Unknown',
                    'ven_rating' => $vendor->rating ?? 0,
                    'ven_status' => $vendor->status,
                    'ven_picture' => $vendor->picture,
                    'ven_desc' => '',
                    'created_at' => $vendor->created_at,
                    'updated_at' => $vendor->updated_at,
                ];

                return [
                    'success' => true,
                    'data' => $data,
                    'message' => 'Vendor retrieved successfully',
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Vendor not found',
                    'data' => null,
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch vendor: '.$e->getMessage(),
                'data' => null,
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
                    'message' => 'Purchase retrieved successfully',
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Purchase not found',
                    'data' => null,
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch purchase: '.$e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function getVendorByVendorId($venId)
    {
        try {
            $vendor = VendorAccount::where('vendorid', $venId)->first();
            if ($vendor) {
                $data = [
                    'id' => $vendor->id,
                    'ven_id' => $vendor->vendorid,
                    'ven_company_name' => $vendor->company_name,
                    'ven_contact_person' => trim($vendor->firstname . ' ' . $vendor->lastname),
                    'ven_email' => $vendor->email,
                    'ven_phone' => $vendor->contactnum,
                    'ven_address' => $vendor->address,
                    'ven_type' => $vendor->company_type ?? 'Unknown',
                    'ven_rating' => $vendor->rating ?? 0,
                    'ven_status' => $vendor->status,
                    'ven_desc' => '',
                    'created_at' => $vendor->created_at,
                    'updated_at' => $vendor->updated_at,
                ];

                return [
                    'success' => true,
                    'data' => $data,
                    'message' => 'Vendor retrieved successfully',
                ];
            }

            return [
                'success' => false,
                'message' => 'Vendor not found',
                'data' => null,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch vendor: '.$e->getMessage(),
                'data' => null,
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
                    'message' => 'Purchase retrieved successfully',
                ];
            }

            return [
                'success' => false,
                'message' => 'Purchase not found',
                'data' => null,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch purchase: '.$e->getMessage(),
                'data' => null,
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
                'data' => $vendor,
            ];
        } catch (Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Failed to create vendor: '.$e->getMessage(),
                'data' => null,
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

            // Set default status to Pending
            $data['pur_status'] = 'Pending';

            // Set department and module information
            $data['pur_department_from'] = 'Logistics 1';
            $data['pur_module_from'] = 'Procurement & Sourcing Management';
            $data['pur_submodule_from'] = 'Purchase Management';

            // Set order_by from authenticated user
            $data['pur_order_by'] = $data['pur_order_by'] ?? 'System User';

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
                'data' => $purchase,
            ];
        } catch (Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Failed to create purchase: '.$e->getMessage(),
                'data' => null,
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
                    'data' => $vendor,
                ];
            } else {
                DB::rollBack();

                return [
                    'success' => false,
                    'message' => 'Vendor not found',
                    'data' => null,
                ];
            }
        } catch (Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Failed to update vendor: '.$e->getMessage(),
                'data' => null,
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
                    'data' => $purchase,
                ];
            } else {
                DB::rollBack();

                return [
                    'success' => false,
                    'message' => 'Purchase not found',
                    'data' => null,
                ];
            }
        } catch (Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Failed to update purchase: '.$e->getMessage(),
                'data' => null,
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
                    'message' => 'Vendor deleted successfully',
                ];
            } else {
                DB::rollBack();

                return [
                    'success' => false,
                    'message' => 'Vendor not found',
                ];
            }
        } catch (Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Failed to delete vendor: '.$e->getMessage(),
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
                    'message' => 'Purchase deleted successfully',
                ];
            } else {
                DB::rollBack();

                return [
                    'success' => false,
                    'message' => 'Purchase not found',
                ];
            }
        } catch (Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Failed to delete purchase: '.$e->getMessage(),
            ];
        }
    }

    public function getVendorStats()
    {
        try {
            $activeVendors = VendorAccount::where('status', 'active');
            
            $totalVendors = $activeVendors->count();
            
            $vendorsByType = VendorAccount::where('status', 'active')
                ->select('company_type', DB::raw('count(*) as count'))
                ->groupBy('company_type')
                ->pluck('count', 'company_type')
                ->toArray();

            $activeVendorIds = VendorAccount::where('status', 'active')->pluck('vendorid');
            $totalProducts = Product::whereIn('prod_vendor', $activeVendorIds)->count();

            $stats = [
                'total_vendors' => $totalVendors,
                'active_vendors' => $totalVendors,
                'vendors_by_type' => $vendorsByType,
                'total_products' => $totalProducts,
            ];

            return [
                'success' => true,
                'data' => $stats,
                'message' => 'Vendor stats retrieved successfully',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch vendor stats: '.$e->getMessage(),
                'data' => [],
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
                'message' => 'Products retrieved successfully',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch products: '.$e->getMessage(),
                'data' => [],
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
                'message' => 'Vendor products retrieved successfully',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch vendor products: '.$e->getMessage(),
                'data' => [],
            ];
        }
    }

    /**
     * Generate unique vendor ID
     */
    private function generateVendorId()
    {
        $prefix = 'VEND';
        $last = Vendor::where('ven_id', 'like', $prefix.'%')
            ->orderBy('ven_id', 'desc')
            ->first();
        $next = $last ? (int) substr($last->ven_id, strlen($prefix)) + 1 : 1;

        return $prefix.str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Generate unique purchase ID
     */
    private function generatePurchaseId()
    {
        $prefix = 'PURC';
        $datePart = now()->format('Ymd');

        do {
            $randomPart = $this->generateRandomAlphanumeric(5);
            $purId = $prefix.$datePart.$randomPart;
        } while (Purchase::where('pur_id', $purId)->exists());

        return $purId;
    }

    /**
     * Create product for vendor
     */
    public function createProduct($data)
    {
        DB::beginTransaction();
        try {
            $data['prod_id'] = $this->generateProductId();
            
            // Add default module info if not present
            $data['prod_module_from'] = $data['prod_module_from'] ?? 'psm';
            $data['prod_submodule_from'] = $data['prod_submodule_from'] ?? 'vendor-product-management';

            if (isset($data['prod_picture']) && $data['prod_picture'] instanceof \Illuminate\Http\UploadedFile) {
                $file = $data['prod_picture'];
                $filename = 'prod_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/products'), $filename);
                $data['prod_picture'] = 'storage/products/' . $filename;
            }

            $product = $this->psmRepository->createProduct($data);

            DB::commit();

            return [
                'success' => true,
                'message' => 'Product created successfully',
                'data' => $product,
            ];
        } catch (Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Failed to create product: '.$e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function updateProduct($id, $data)
    {
        DB::beginTransaction();
        try {
            // Handle image removal if requested
            if (isset($data['remove_picture']) && $data['remove_picture']) {
                $product = $this->psmRepository->getProduct($id);
                if ($product && $product->prod_picture) {
                    $path = public_path($product->prod_picture);
                    if (file_exists($path)) {
                        @unlink($path);
                    }
                    $data['prod_picture'] = null;
                }
                unset($data['remove_picture']);
            }

            // Handle new image upload
            if (isset($data['prod_picture']) && $data['prod_picture'] instanceof \Illuminate\Http\UploadedFile) {
                $product = $this->psmRepository->getProduct($id);
                
                // Delete old image if exists
                if ($product && $product->prod_picture) {
                    $path = public_path($product->prod_picture);
                    if (file_exists($path)) {
                        @unlink($path);
                    }
                }

                $file = $data['prod_picture'];
                $filename = 'prod_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('storage/products'), $filename);
                $data['prod_picture'] = 'storage/products/' . $filename;
            }

            $product = $this->psmRepository->updateProduct($id, $data);
            if ($product) {
                DB::commit();

                return [
                    'success' => true,
                    'message' => 'Product updated successfully',
                    'data' => $product,
                ];
            }
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Product not found',
                'data' => null,
            ];
        } catch (Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Failed to update product: '.$e->getMessage(),
                'data' => null,
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
                    'message' => 'Product deleted successfully',
                ];
            }
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Product not found',
            ];
        } catch (Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Failed to delete product: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Generate unique product ID
     */
    private function generateProductId()
    {
        $prefix = 'PROD';
        $datePart = now()->format('Ymd');

        do {
            $randomPart = $this->generateRandomAlphanumeric(5);
            $prodId = $prefix.$datePart.$randomPart;
        } while (Product::where('prod_id', $prodId)->exists());

        return $prodId;
    }

    /**
     * Generate random alphanumeric string
     */
    private function generateRandomAlphanumeric($length = 5)
    {
        $characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
        $charactersLength = strlen($characters);
        $result = '';

        for ($i = 0; $i < $length; $i++) {
            $result .= $characters[random_int(0, $charactersLength - 1)];
        }

        return $result;
    }

    private function generateQuoteId()
    {
        $prefix = 'QUOT';
        $last = Quote::where('quo_id', 'like', $prefix.'%')
            ->orderBy('quo_id', 'desc')
            ->first();
        $next = $last ? (int) substr($last->quo_id, strlen($prefix)) + 1 : 1;

        return $prefix.str_pad($next, 5, '0', STR_PAD_LEFT);
    }

    /**
     * Budget Methods
     */

    /**
     * Get current budget
     */
    public function getCurrentBudget()
    {
        try {
            $budget = $this->psmRepository->getCurrentBudget();

            if ($budget) {
                return [
                    'success' => true,
                    'data' => $budget,
                    'message' => 'Budget retrieved successfully',
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'No active budget found',
                    'data' => null,
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch budget: '.$e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function getQuotes()
    {
        try {
            $quotes = $this->psmRepository->getQuotes();

            return [
                'success' => true,
                'data' => $quotes,
                'message' => 'Quotes retrieved successfully',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch quotes: '.$e->getMessage(),
                'data' => [],
            ];
        }
    }

    public function createQuote($data)
    {
        DB::beginTransaction();
        try {
            $data['quo_id'] = $this->generateQuoteId();
            $data['quo_status'] = $data['quo_status'] ?? 'Vendor-Review';
            $data['quo_stored_from'] = $data['quo_stored_from'] ?? 'Main Warehouse A';
            $data['quo_department_from'] = 'Logistics 1';
            $data['quo_module_from'] = 'Procurement & Sourcing Management';
            $data['quo_submodule_from'] = 'Vendor Quote';
            if (isset($data['quo_items']) && is_string($data['quo_items'])) {
                $data['quo_items'] = json_decode($data['quo_items'], true);
            }
            $items = $data['quo_items'] ?? [];
            $data['quo_units'] = $data['quo_units'] ?? count($items);
            if (! isset($data['quo_total_amount'])) {
                $total = 0;
                foreach ($items as $item) {
                    if (isset($item['price'])) {
                        $total += floatval($item['price']);
                    }
                }
                $data['quo_total_amount'] = $total;
            }
            $quote = $this->psmRepository->createQuote($data);
            DB::commit();

            return [
                'success' => true,
                'message' => 'Quote created successfully',
                'data' => $quote,
            ];
        } catch (Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Failed to create quote: '.$e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function updateQuote($id, $data)
    {
        DB::beginTransaction();
        try {
            if (isset($data['quo_items']) && is_string($data['quo_items'])) {
                $data['quo_items'] = json_decode($data['quo_items'], true);
            }
            $quote = $this->psmRepository->updateQuote($id, $data);
            if ($quote) {
                if ($quote->quo_purchase_id) {
                    $purchaseUpdates = [];
                    if (array_key_exists('quo_delivery_date_from', $data)) {
                        $purchaseUpdates['pur_delivery_date_from'] = $data['quo_delivery_date_from'];
                    }
                    if (array_key_exists('quo_delivery_date_to', $data)) {
                        $purchaseUpdates['pur_delivery_date_to'] = $data['quo_delivery_date_to'];
                    }
                    if (! empty($purchaseUpdates)) {
                        $this->psmRepository->updatePurchase($quote->quo_purchase_id, $purchaseUpdates);
                    }
                }
                if (isset($data['quo_status']) && $quote->quo_purchase_id) {
                    $map = [
                        'Vendor-Review' => 'Vendor-Review',
                        'In-Progress' => 'In-Progress',
                        'Completed' => 'Completed',
                        'Cancel' => 'Cancel',
                        'Reject' => 'Rejected',
                    ];
                    $targetStatus = $map[$data['quo_status']] ?? null;
                    if ($targetStatus) {
                        $this->updatePurchaseStatus($quote->quo_purchase_id, $targetStatus, false, null);
                    }
                }
                DB::commit();

                return [
                    'success' => true,
                    'message' => 'Quote updated successfully',
                    'data' => $quote,
                ];
            }
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Quote not found',
                'data' => null,
            ];
        } catch (Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Failed to update quote: '.$e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function deleteQuote($id)
    {
        DB::beginTransaction();
        try {
            $result = $this->psmRepository->deleteQuote($id);
            if ($result) {
                DB::commit();

                return [
                    'success' => true,
                    'message' => 'Quote deleted successfully',
                ];
            }
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Quote not found',
            ];
        } catch (Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Failed to delete quote: '.$e->getMessage(),
            ];
        }
    }

    public function getApprovedPurchasesForQuote()
    {
        try {
            $purchases = $this->psmRepository->getApprovedPurchasesWithoutQuote();

            return [
                'success' => true,
                'data' => $purchases,
                'message' => 'Approved purchases retrieved successfully',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch approved purchases: '.$e->getMessage(),
                'data' => [],
            ];
        }
    }

    public function reviewPurchaseToQuote($purchaseId)
    {
        DB::beginTransaction();
        try {
            $purchase = $this->psmRepository->getPurchaseById($purchaseId);
            if (! $purchase || $purchase->pur_status !== 'Approved') {
                DB::rollBack();

                return [
                    'success' => false,
                    'message' => 'Purchase not eligible',
                    'data' => null,
                ];
            }
            $data = [];
            $data['quo_id'] = $this->generateQuoteId();
            $data['quo_items'] = $purchase->pur_name_items;
            $data['quo_units'] = $purchase->pur_unit;
            $data['quo_total_amount'] = $purchase->pur_total_amount;
            $data['quo_status'] = 'Vendor-Review';
            $data['quo_stored_from'] = 'Main Warehouse A';
            $data['quo_department_from'] = 'Logistics 1';
            $data['quo_module_from'] = 'Procurement & Sourcing Management';
            $data['quo_submodule_from'] = 'Vendor Quote';
            $data['quo_purchase_id'] = $purchase->id;
            $quote = $this->psmRepository->createQuote($data);
            $this->updatePurchaseStatus($purchase->id, 'Vendor-Review', false, null);
            DB::commit();

            return [
                'success' => true,
                'message' => 'Purchase moved to Vendor Review',
                'data' => $quote,
            ];
        } catch (Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Failed to review purchase: '.$e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * Extend budget validity
     */
    public function extendBudgetValidity($id, $validityType, $additionalAmount = 0)
    {
        DB::beginTransaction();
        try {
            $budget = $this->psmRepository->getBudgetById($id);

            if (! $budget) {
                DB::rollBack();

                return [
                    'success' => false,
                    'message' => 'Budget not found',
                    'data' => null,
                ];
            }

            $newValidTo = $this->calculateNewValidTo($budget->bud_valid_from, $validityType);

            $updatedBudget = $this->psmRepository->extendBudgetValidity($id, $newValidTo, $additionalAmount);

            if ($updatedBudget) {
                DB::commit();

                return [
                    'success' => true,
                    'message' => 'Budget extended successfully',
                    'data' => $updatedBudget,
                ];
            } else {
                DB::rollBack();

                return [
                    'success' => false,
                    'message' => 'Failed to extend budget',
                    'data' => null,
                ];
            }
        } catch (Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Failed to extend budget: '.$e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * Calculate new valid to date based on validity type
     */
    private function calculateNewValidTo($validFrom, $validityType)
    {
        $validFrom = \Carbon\Carbon::parse($validFrom);

        switch ($validityType) {
            case 'Week':
                return $validFrom->addWeek()->toDateString();
            case 'Month':
                return $validFrom->addMonth()->toDateString();
            case 'Year':
                return $validFrom->addYear()->toDateString();
            default:
                return $validFrom->addMonth()->toDateString();
        }
    }

    /**
     * Update purchase status with budget check
     */
    public function updatePurchaseStatus($id, $status, $budgetCheck = false, $approvedBy = null)
    {
        DB::beginTransaction();
        try {
            $purchase = $this->psmRepository->getPurchaseById($id);

            if (! $purchase) {
                DB::rollBack();

                return [
                    'success' => false,
                    'message' => 'Purchase not found',
                    'data' => null,
                ];
            }

            // If budget check is required and status is being approved
            if ($budgetCheck && $status === 'Approved') {
                $budget = $this->psmRepository->getCurrentBudget();

                if (! $budget) {
                    DB::rollBack();

                    return [
                        'success' => false,
                        'message' => 'No active budget available',
                        'data' => null,
                    ];
                }

                // Check if budget has sufficient funds
                if ($budget->bud_remaining_amount < $purchase->pur_total_amount) {
                    DB::rollBack();

                    return [
                        'success' => false,
                        'message' => 'Insufficient budget. Remaining: '.number_format($budget->bud_remaining_amount, 2).', Required: '.number_format($purchase->pur_total_amount, 2),
                        'data' => null,
                    ];
                }

                // Update budget spent amount
                $this->psmRepository->updateBudgetSpent($budget->id, $purchase->pur_total_amount);
            }

            // Set approved by if provided (for both approval and rejection)
            if ($approvedBy) {
                $purchase->pur_approved_by = $approvedBy;
            }

            // Update purchase status
            $purchase->pur_status = $status;
            $purchase->save();

            DB::commit();

            return [
                'success' => true,
                'message' => 'Purchase status updated successfully',
                'data' => $purchase,
            ];
        } catch (Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Failed to update purchase status: '.$e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * Cancel purchase (only for Pending status)
     */
    public function cancelPurchase($id, $cancelBy = null)
    {
        DB::beginTransaction();
        try {
            $purchase = $this->psmRepository->getPurchaseById($id);

            if (! $purchase) {
                DB::rollBack();

                return [
                    'success' => false,
                    'message' => 'Purchase not found',
                    'data' => null,
                ];
            }

            // Check if purchase can be cancelled
            if (! $purchase->can_be_cancelled) {
                DB::rollBack();

                return [
                    'success' => false,
                    'message' => 'Only purchases with Pending status can be cancelled',
                    'data' => null,
                ];
            }

            // Update cancel fields
            if ($cancelBy) {
                $purchase->pur_cancel_by = $cancelBy;
                // Also reflect in approved_by column for display purposes
                $purchase->pur_approved_by = $cancelBy;
            }
            // Update purchase status to Cancel
            $purchase->pur_status = 'Cancel';
            $purchase->save();

            DB::commit();

            return [
                'success' => true,
                'message' => 'Purchase cancelled successfully',
                'data' => $purchase,
            ];
        } catch (Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Failed to cancel purchase: '.$e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * Process budget approval or rejection
     */
    public function processBudgetApproval($id, $action, $approvedBy)
    {
        DB::beginTransaction();
        try {
            $purchase = $this->psmRepository->getPurchaseById($id);

            if (! $purchase) {
                DB::rollBack();

                return [
                    'success' => false,
                    'message' => 'Purchase not found',
                    'data' => null,
                ];
            }

            // Set the approver name regardless of action
            $purchase->pur_approved_by = $approvedBy;

            if ($action === 'approve') {
                // Check budget for approval
                $budget = $this->psmRepository->getCurrentBudget();

                if (! $budget) {
                    DB::rollBack();

                    return [
                        'success' => false,
                        'message' => 'No active budget available',
                        'data' => null,
                    ];
                }

                // Check if budget has sufficient funds
                if ($budget->bud_remaining_amount < $purchase->pur_total_amount) {
                    DB::rollBack();

                    return [
                        'success' => false,
                        'message' => 'Insufficient budget. Remaining: '.number_format($budget->bud_remaining_amount, 2).', Required: '.number_format($purchase->pur_total_amount, 2),
                        'data' => null,
                    ];
                }

                // Update budget spent amount
                $this->psmRepository->updateBudgetSpent($budget->id, $purchase->pur_total_amount);

                // Update purchase status to Approved
                $purchase->pur_status = 'Approved';
            } else {
                // Update purchase status to Rejected
                $purchase->pur_status = 'Rejected';
            }

            $purchase->save();

            DB::commit();

            return [
                'success' => true,
                'message' => $action === 'approve' ? 'Purchase approved successfully' : 'Purchase rejected successfully',
                'data' => $purchase,
            ];
        } catch (Exception $e) {
            DB::rollBack();

            return [
                'success' => false,
                'message' => 'Failed to process budget approval: '.$e->getMessage(),
                'data' => null,
            ];
        }
    }
}
