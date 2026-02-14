<?php

namespace App\Services;

use App\Models\PSM\Product;
use App\Models\PSM\Purchase;
use App\Models\PSM\PurchaseProduct;
use App\Models\PSM\Quote;
use App\Models\PSM\Vendor;
use App\Models\VendorAccount;
use App\Repositories\PSMRepository;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class PSMService
{
    protected $psmRepository;

    public function __construct(PSMRepository $psmRepository)
    {
        $this->psmRepository = $psmRepository;
    }

    /**
     * Get purchase products
     */
    public function getPurchaseProducts()
    {
        try {
            return $this->psmRepository->getPurchaseProducts();
        } catch (Exception $e) {
            throw new Exception('Error fetching purchase products: ' . $e->getMessage());
        }
    }
    
    /**
     * Get purchase requests
     */
    public function getPurchaseRequests($filters = [])
    {
        try {
            return $this->psmRepository->getPurchaseRequests($filters);
        } catch (Exception $e) {
            throw new Exception('Error fetching purchase requests: ' . $e->getMessage());
        }
    }

    /**
     * Sync all pending purchases into purchase requests mirror
     */
    public function syncPendingPurchaseRequests()
    {
        DB::beginTransaction();
        try {
            $pending = $this->psmRepository->getPurchases(['status' => 'Pending']);
            $count = 0;
            foreach ($pending as $purchase) {
                $this->psmRepository->upsertPurchaseRequestFromPurchase($purchase);
                $count++;
            }
            DB::commit();
            return [
                'success' => true,
                'message' => 'Synced pending purchases to purchase requests',
                'data' => ['count' => $count],
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Failed to sync purchase requests: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * Review purchase request: mark as Reviewed, create a Quote, and refresh purchase status
     */
    public function reviewPurchaseRequest($preqId)
    {
        DB::beginTransaction();
        try {
            $req = $this->psmRepository->getPurchaseRequestByPreqId($preqId);
            if (!$req) {
                DB::rollBack();
                return [
                    'success' => false,
                    'message' => 'Purchase request not found',
                    'data' => null,
                ];
            }
            // Mark request as received (rename Reviewed -> PO-Received)
            $this->psmRepository->updatePurchaseRequestByPreqId($preqId, [
                'preq_status' => 'PO-Received',   // status label with hyphen
                'preq_process' => 'PO Received',  // process marker with space
                'updated_at' => now(),
            ]);

            // Create quote from the underlying purchase if available
            $purchase = $this->psmRepository->getPurchaseByPurchaseId($preqId);
            if ($purchase) {
                $quoteData = [
                    'quo_items' => $purchase->pur_name_items,
                    'quo_units' => $purchase->pur_unit,
                    'quo_total_amount' => $purchase->pur_total_amount,
                    'quo_status' => 'PO Received',
                    'quo_stored_from' => 'Main Warehouse A',
                    'quo_department_from' => 'Logistics 1',
                    'quo_module_from' => 'Procurement & Sourcing Management',
                    'quo_submodule_from' => 'Vendor Quote',
                    'quo_purchase_id' => $purchase->id,
                ];
                $quoteResult = $this->createQuote($quoteData);
                // Optionally sync purchase status for consistency
                $this->updatePurchaseStatus($purchase->id, 'PO Received', null);
                // Ensure the mirrored request is removed from New Purchase Order list
                $this->psmRepository->deletePurchaseRequestByPreqId($purchase->pur_id);
            } else {
                $quoteResult = ['success' => false, 'data' => null];
            }

            DB::commit();
            return [
                'success' => true,
                'message' => 'Purchase request received',
                'data' => [
                    'request' => $req,
                    'quote' => $quoteResult['data'] ?? null
                ],
            ];
        } catch (Exception $e) {
            DB::rollBack();
            return [
                'success' => false,
                'message' => 'Failed to review purchase request: ' . $e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function markProductsAsReceivedByProdId($prodId)
    {
        try {
            return $this->psmRepository->markProductsAsReceivedByProdId($prodId);
        } catch (Exception $e) {
            Log::error('Error marking products as received by prod ID: ' . $e->getMessage());
            return false;
        }
    }

    public function markProductAsReceivedById($id)
    {
        try {
            return $this->psmRepository->markProductAsReceivedById($id);
        } catch (Exception $e) {
            Log::error('Error marking product as received by ID: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete purchase product
     */
    public function deletePurchaseProduct($id)
    {
        try {
            $result = $this->psmRepository->deletePurchaseProduct($id);
            if ($result) {
                return [
                    'success' => true,
                    'message' => 'Purchase product deleted successfully'
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Purchase product not found'
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error deleting purchase product: ' . $e->getMessage()
            ];
        }
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
                    'ven_desc' => $vendor->company_desc,
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
                'picture' => $product->prod_picture,
                'warranty' => $product->prod_warranty,
                'expiration' => $product->prod_expiration,
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
            $purchases = $this->psmRepository->getPurchases($filters);

            // Enrich items with warranty and expiration if missing
            $allItemNames = [];
            foreach ($purchases as $purchase) {
                if (is_array($purchase->pur_name_items)) {
                    foreach ($purchase->pur_name_items as $item) {
                        if (isset($item['name'])) {
                            $allItemNames[] = $item['name'];
                        }
                    }
                }
            }
            
            $products = [];
            if (!empty($allItemNames)) {
                $products = Product::whereIn('prod_name', array_unique($allItemNames))
                    ->get()
                    ->keyBy('prod_name');
            }

            $purchases->transform(function ($purchase) use ($products) {
                $items = $purchase->pur_name_items;
                if (is_array($items)) {
                    $updated = false;
                    foreach ($items as &$item) {
                        if (isset($item['name']) && $products->has($item['name'])) {
                            $product = $products->get($item['name']);
                            if (!isset($item['warranty']) || $item['warranty'] === null) {
                                $item['warranty'] = $product->prod_warranty;
                                $updated = true;
                            }
                            if (!isset($item['expiration']) || $item['expiration'] === null) {
                                $item['expiration'] = $product->prod_expiration;
                                $updated = true;
                            }
                        }
                    }
                    // Explicitly set the attribute to ensure it's included in toArray()/JSON
                    if ($updated) {
                        $purchase->pur_name_items = $items;
                    }
                }
                return $purchase;
            });

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
     * Mark a purchase item as added to inventory
     */
    public function markItemAsInventory($purchaseId, $itemIndex)
    {
        try {
            $purchase = Purchase::where('pur_id', $purchaseId)->first();
            if (!$purchase) {
                return ['success' => false, 'message' => 'Purchase not found'];
            }

            $items = $purchase->pur_name_items;
            // The items might be an array of arrays or array of objects depending on how it was decoded/encoded
            // Since cast is 'array', it should be array of arrays.
            
            // Note: The index provided by frontend matches the array index in pur_name_items
            if (isset($items[$itemIndex])) {
                $items[$itemIndex]['in_inventory'] = true;
                $purchase->pur_name_items = $items;
                $purchase->save();
                return ['success' => true];
            }

            return ['success' => false, 'message' => 'Item index not found'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
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
                    'ven_desc' => $vendor->company_desc,
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

    public function getPSMVendor($venId)
    {
        try {
            $vendor = $this->psmRepository->getVendorByVendorId($venId);
            if ($vendor) {
                return ['success' => true, 'data' => $vendor];
            }
            return ['success' => false, 'message' => 'Vendor not found in PSM'];
        } catch (Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
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
                    'ven_desc' => $vendor->company_desc,
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
        DB::connection('psm')->beginTransaction();
        try {
            if (!isset($data['ven_id'])) {
                $data['ven_id'] = $this->generateVendorId();
            }

            $vendor = $this->psmRepository->createVendor($data);

            DB::connection('psm')->commit();

            return [
                'success' => true,
                'message' => 'Vendor created successfully',
                'data' => $vendor,
            ];
        } catch (Exception $e) {
            DB::connection('psm')->rollBack();

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
            if (empty($data['pur_order_by'])) {
                $data['pur_order_by'] = 'System User';
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

            $purchase = $this->psmRepository->createPurchase($data);

            // Mirror Pending purchases into psm_purcahse_request
            if ($purchase && $purchase->pur_status === 'Pending') {
                $this->psmRepository->upsertPurchaseRequestFromPurchase($purchase);
            }

            // If this purchase was created from a consolidated request, mark it as completed
            if (!empty($data['consolidated_id']) || !empty($data['con_req_id'])) {
                $query = \App\Models\PSM\Consolidated::query();
                if (!empty($data['consolidated_id'])) {
                    $query->orWhere('id', $data['consolidated_id']);
                }
                if (!empty($data['con_req_id'])) {
                    $query->orWhere('con_req_id', $data['con_req_id']);
                }
                $query->update([
                    'con_purchase_order' => 'Completed',
                    'updated_at' => now(),
                ]);
            }

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
        DB::connection('psm')->beginTransaction();
        try {
            $vendor = $this->psmRepository->updateVendor($id, $data);

            if ($vendor) {
                DB::connection('psm')->commit();

                return [
                    'success' => true,
                    'message' => 'Vendor updated successfully',
                    'data' => $vendor,
                ];
            } else {
                DB::connection('psm')->rollBack();

                return [
                    'success' => false,
                    'message' => 'Vendor not found',
                    'data' => null,
                ];
            }
        } catch (Exception $e) {
            DB::connection('psm')->rollBack();

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

    /**
     * Get product by ID
     */
    public function getProduct($id, $columns = ['*'])
    {
        try {
            $product = $this->psmRepository->getProduct($id, $columns);
            if ($product) {
                return [
                    'success' => true,
                    'data' => $product,
                    'message' => 'Product retrieved successfully',
                ];
            }

            return [
                'success' => false,
                'message' => 'Product not found',
                'data' => null,
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch product: '.$e->getMessage(),
                'data' => null,
            ];
        }
    }

    public function getProducts($filters = [], $columns = ['*'])
    {
        try {
            $products = $this->psmRepository->getProducts($filters, $columns);

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

    /**
     * Get all requisitions
     */
    public function getRequisitions($filters = [])
    {
        try {
            $requisitions = $this->psmRepository->getRequisitions($filters);

            return [
                'success' => true,
                'data' => $requisitions->items(),
                'meta' => [
                    'current_page' => $requisitions->currentPage(),
                    'last_page' => $requisitions->lastPage(),
                    'per_page' => $requisitions->perPage(),
                    'total' => $requisitions->total(),
                ],
                'stats' => $this->psmRepository->getRequisitionStats(),
                'message' => 'Requisitions retrieved successfully',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch requisitions: '.$e->getMessage(),
                'data' => [],
            ];
        }
    }

    /**
     * Get requisition by ID
     */
    public function getRequisition($id)
    {
        try {
            $requisition = $this->psmRepository->getRequisition($id);

            if ($requisition) {
                return [
                    'success' => true,
                    'data' => $requisition,
                    'message' => 'Requisition retrieved successfully',
                ];
            } else {
                return [
                    'success' => false,
                    'message' => 'Requisition not found',
                    'data' => null,
                ];
            }
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch requisition: '.$e->getMessage(),
                'data' => null,
            ];
        }
    }

    /**
     * Create new requisition
     */
    public function createRequisition($data)
    {
        try {
            $requisition = $this->psmRepository->createRequisition($data);

            return [
                'success' => true,
                'data' => $requisition,
                'message' => 'Requisition created successfully',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to create requisition: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Update requisition status
     */
    public function updateRequisitionStatus($id, $status)
    {
        try {
            $requisition = $this->psmRepository->updateRequisitionStatus($id, $status);

            if ($requisition) {
                return [
                    'success' => true,
                    'data' => $requisition,
                    'message' => 'Requisition status updated successfully',
                ];
            }

            return [
                'success' => false,
                'message' => 'Requisition not found',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to update requisition status: '.$e->getMessage(),
            ];
        }
    }

    /**
     * Delete requisition
     */
    public function deleteRequisition($id)
    {
        try {
            $deleted = $this->psmRepository->deleteRequisition($id);

            if ($deleted) {
                return [
                    'success' => true,
                    'message' => 'Requisition deleted successfully',
                ];
            }

            return [
                'success' => false,
                'message' => 'Requisition not found or could not be deleted',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to delete requisition: '.$e->getMessage(),
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

    public function getVendorQuoteNotifications($vendorUser)
    {
        try {
            if (! $vendorUser || ! $vendorUser->company_name) {
                return [
                    'success' => false,
                    'message' => 'Vendor information not found',
                    'data' => [],
                ];
            }

            $purchases = $this->psmRepository->getApprovedPurchasesForVendor($vendorUser->company_name);

            return [
                'success' => true,
                'data' => $purchases,
                'message' => 'Notifications retrieved successfully',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch notifications: '.$e->getMessage(),
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
        DB::connection('psm')->beginTransaction();
        try {
            $data['prod_id'] = $this->generateProductId();
            
            // Add default module info if not present
            $data['prod_module_from'] = $data['prod_module_from'] ?? 'psm';
            $data['prod_submodule_from'] = $data['prod_submodule_from'] ?? 'vendor-product-management';

            if (isset($data['prod_picture']) && $data['prod_picture'] instanceof \Illuminate\Http\UploadedFile) {
                $file = $data['prod_picture'];
                $filename = 'prod_' . time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('images/product-picture'), $filename); // Changed to correct path
                $data['prod_picture'] = 'images/product-picture/' . $filename; // Changed to correct path
            }

            $product = $this->psmRepository->createProduct($data);

            DB::connection('psm')->commit();

            return [
                'success' => true,
                'message' => 'Product created successfully',
                'data' => $product,
            ];
        } catch (Exception $e) {
            DB::connection('psm')->rollBack();

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
                $file->move(public_path('images/product-picture'), $filename);
                $data['prod_picture'] = 'images/product-picture/' . $filename;
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

    public function getVendorQuotes($vendorUser)
    {
        try {
            if (! $vendorUser || ! $vendorUser->company_name) {
                return [
                    'success' => false,
                    'message' => 'Vendor information not found',
                    'data' => [],
                ];
            }

            $quotes = $this->psmRepository->getQuotesForVendor($vendorUser->company_name);

            return [
                'success' => true,
                'data' => $quotes,
                'message' => 'Vendor quotes retrieved successfully',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Failed to fetch vendor quotes: '.$e->getMessage(),
                'data' => [],
            ];
        }
    }

    public function createQuote($data)
    {
        DB::beginTransaction();
        try {
            $data['quo_id'] = $this->generateQuoteId();
            $data['quo_status'] = $data['quo_status'] ?? 'PO Received';
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
            // Map status to quo_status if present (fixes frontend mismatch)
            if (isset($data['status']) && !isset($data['quo_status'])) {
                $data['quo_status'] = $data['status'];
            }

            if (isset($data['quo_items']) && is_string($data['quo_items'])) {
                $data['quo_items'] = json_decode($data['quo_items'], true);
            }
            $quote = $this->psmRepository->updateQuote($id, $data);
            if ($quote) {
                if ($quote->quo_purchase_id) {
                    $purchaseUpdates = [];
                    if (array_key_exists('quo_delivery_date', $data)) {
                        $purchaseUpdates['pur_delivery_date'] = $data['quo_delivery_date'];
                    }
                    if (! empty($purchaseUpdates)) {
                        $this->psmRepository->updatePurchase($quote->quo_purchase_id, $purchaseUpdates);
                    }
                }
                if (isset($data['quo_status']) && $quote->quo_purchase_id) {
                    // Map quote status to purchase status (supports new and legacy values)
                    $map = [
                        'PO Received' => 'PO Received',
                        'Vendor-Review' => 'PO Received',
                        'Processing Order' => 'Processing Order',
                        'In-Progress' => 'Processing Order',
                        'Dispatched' => 'Dispatched',
                        'Delivered' => 'Delivered',
                        'Completed' => 'Delivered',
                        'Cancel' => 'Cancel',
                        'Reject' => 'Rejected',
                    ];
                    $targetStatus = $map[$data['quo_status']] ?? ($map[ucwords(strtolower($data['quo_status']))] ?? null);
                    if ($targetStatus) {
                        $statusResult = $this->updatePurchaseStatus($quote->quo_purchase_id, $targetStatus, false, null);
                        if (is_array($statusResult) && isset($statusResult['success']) && !$statusResult['success']) {
                            throw new Exception($statusResult['message'] ?? 'Failed to update linked purchase status');
                        }
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

    /**
     * Get all budgets
     */
    public function getBudgets()
    {
        try {
            return $this->psmRepository->getAllBudgets();
        } catch (Exception $e) {
            throw new Exception('Error fetching budgets: ' . $e->getMessage());
        }
    }

    /**
     * Get current active budget
     */
    public function getCurrentBudget()
    {
        try {
            return $this->psmRepository->getCurrentBudget();
        } catch (Exception $e) {
            throw new Exception('Error fetching current budget: ' . $e->getMessage());
        }
    }

    /**
     * Create new budget
     */
    public function createBudget($data)
    {
        DB::beginTransaction();
        try {
            $budget = $this->psmRepository->createBudget($data);
            DB::commit();
            return $budget;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Error creating budget: ' . $e->getMessage());
        }
    }

    /**
     * Update budget
     */
    public function updateBudget($id, $data)
    {
        DB::beginTransaction();
        try {
            $budget = $this->psmRepository->updateBudget($id, $data);
            DB::commit();
            return $budget;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Error updating budget: ' . $e->getMessage());
        }
    }

    /**
     * Extend budget validity
     */
    public function extendBudget($id, $days)
    {
        DB::beginTransaction();
        try {
            $budget = $this->psmRepository->extendBudget($id, $days);
            DB::commit();
            return $budget;
        } catch (Exception $e) {
            DB::rollBack();
            throw new Exception('Error extending budget: ' . $e->getMessage());
        }
    }

    /**
     * Get budget logs
     */
    public function getBudgetLogs()
    {
        try {
            return $this->psmRepository->getAllBudgetLogs();
        } catch (Exception $e) {
            throw new Exception('Error fetching budget logs: ' . $e->getMessage());
        }
    }

    /**
     * Get allocated budgets
     */
    public function getAllocatedBudgets()
    {
        try {
            return $this->psmRepository->getAllocatedBudgets();
        } catch (Exception $e) {
            throw new Exception('Error fetching allocated budgets: '.$e->getMessage());
        }
    }

    /**
     * Get budget requests
     */
    public function getBudgetRequests()
    {
        try {
            return $this->psmRepository->getBudgetRequests();
        } catch (Exception $e) {
            throw new Exception('Error fetching budget requests: '.$e->getMessage());
        }
    }

    /**
     * Get single budget request for external API (excluding req_contact)
     */
    public function getExternalBudgetRequest($id)
    {
        try {
            $request = $this->psmRepository->getBudgetRequestById($id);
            $key = request()->query('key');

            if ($request) {
                if (is_object($request)) {
                    if (isset($request->req_contact)) {
                        unset($request->req_contact);
                    }
                } elseif (is_array($request)) {
                    unset($request['req_contact']);
                }

                // Add quick action URLs
                $baseUrl = config('app.url') . '/api/v1/psm/external/budget-requests/' . $id;
                if (is_object($request)) {
                    $request->approve_url = "{$baseUrl}?req_status=Approved&key={$key}";
                    $request->reject_url = "{$baseUrl}?req_status=Rejected&key={$key}";
                } else {
                    $request['approve_url'] = "{$baseUrl}?req_status=Approved&key={$key}";
                    $request['reject_url'] = "{$baseUrl}?req_status=Rejected&key={$key}";
                }
            }

            return $request;
        } catch (Exception $e) {
            throw new Exception('Error fetching external budget request: '.$e->getMessage());
        }
    }

    /**
     * Get budget requests for external API (excluding req_contact)
     */
    public function getExternalBudgetRequests()
    {
        try {
            $requests = $this->psmRepository->getBudgetRequests();
            $key = request()->query('key');

            // Remove req_contact from each request and add quick approve link
            return collect($requests)->map(function ($request) use ($key) {
                if (is_object($request)) {
                    if (isset($request->req_contact)) {
                        unset($request->req_contact);
                    }
                    $id = $request->req_id;
                } elseif (is_array($request)) {
                    unset($request['req_contact']);
                    $id = $request['req_id'];
                }

                // Add quick approve URL
                $baseUrl = config('app.url') . '/api/v1/psm/external/budget-requests/' . $id;
                $request->approve_url = "{$baseUrl}?req_status=Approved&key={$key}";
                $request->reject_url = "{$baseUrl}?req_status=Rejected&key={$key}";

                return $request;
            });
        } catch (Exception $e) {
            throw new Exception('Error fetching external budget requests: '.$e->getMessage());
        }
    }

    /**
     * Update budget request status
     */
    public function updateBudgetRequestStatus($id, $status)
    {
        try {
            return $this->psmRepository->updateBudgetRequestStatus($id, $status);
        } catch (Exception $e) {
            throw new Exception('Error updating budget request status: '.$e->getMessage());
        }
    }

    /**
     * Create consolidated budget request and mark requisitions as consolidated
     */
    public function createBudgetRequest($data)
    {
        DB::connection('psm')->beginTransaction();
        try {
            $budgetRequestData = [
                'req_id' => $this->generateBudgetRequestId(),
                'req_by' => $data['req_by'] ?? 'PSM System',
                'req_date' => now()->toDateString(),
                'req_dept' => $data['req_dept'] ?? 'Logistics 1',
                'req_amount' => $data['req_amount'],
                'req_purpose' => $data['req_purpose'] ?? 'Consolidated Budget Request',
                'req_contact' => $data['req_contact'] ?? 'N/A',
                'req_status' => 'Pending',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            $result = $this->psmRepository->createBudgetRequest($budgetRequestData);

            if ($result) {
                // Mark included requisitions as consolidated
                if (isset($data['included_req_ids']) && is_array($data['included_req_ids'])) {
                    $this->markRequisitionsAsConsolidated($data['included_req_ids'], $budgetRequestData['req_id']);
                }

                DB::connection('psm')->commit();

                return [
                    'success' => true,
                    'message' => 'Budget request created successfully',
                    'data' => $budgetRequestData
                ];
            }

            DB::connection('psm')->rollBack();
            return [
                'success' => false,
                'message' => 'Failed to create budget request'
            ];
        } catch (Exception $e) {
            DB::connection('psm')->rollBack();
            throw new Exception('Error creating budget request: '.$e->getMessage());
        }
    }

    /**
     * Cancel budget request
     */
    public function cancelBudgetRequest($id)
    {
        try {
            $request = $this->psmRepository->getBudgetRequestById($id);
            if (!$request) {
                return [
                    'success' => false,
                    'message' => 'Budget request not found'
                ];
            }

            if ($request->req_status !== 'Pending') {
                return [
                    'success' => false,
                    'message' => 'Only pending requests can be cancelled'
                ];
            }

            $result = $this->psmRepository->updateBudgetRequestStatus($id, 'Cancelled');

            if ($result) {
                return [
                    'success' => true,
                    'message' => 'Budget request cancelled successfully'
                ];
            }

            return [
                'success' => false,
                'message' => 'Failed to cancel budget request'
            ];
        } catch (Exception $e) {
            throw new Exception('Error cancelling budget request: '.$e->getMessage());
        }
    }

    /**
     * Mark included requisitions as consolidated
     */
    public function markRequisitionsAsConsolidated($reqIds, $parentBudgetReqId = null)
    {
        return $this->psmRepository->markRequisitionsAsConsolidated($reqIds, $parentBudgetReqId);
    }

    /**
     * Generate budget request ID
     */
    private function generateBudgetRequestId()
    {
        $prefix = 'REQB';
        $date = now()->format('Ymd');
        $random = strtoupper(Str::random(8));
        return $prefix . $date . $random;
    }

    /**
     * Generate consolidated ID
     * Format: CONS202602131A1A1
     */
    public function generateConsolidatedId()
    {
        $prefix = 'CONS';
        $date = now()->format('Ymd');
        $random = strtoupper(Str::random(5));
        return $prefix . $date . $random;
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
            $data['quo_status'] = 'PO Received';
            $data['quo_stored_from'] = 'Main Warehouse A';
            $data['quo_department_from'] = 'Logistics 1';
            $data['quo_module_from'] = 'Procurement & Sourcing Management';
            $data['quo_submodule_from'] = 'Vendor Quote';
            $data['quo_purchase_id'] = $purchase->id;
            $quote = $this->psmRepository->createQuote($data);
            $this->updatePurchaseStatus($purchase->id, 'PO Received', false, null);
            DB::commit();

            return [
                'success' => true,
                'message' => 'Purchase moved to PO Received',
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
     * Update purchase status
     */
    public function updatePurchaseStatus($id, $status, $approvedBy = null)
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

            // approved_by column removed; keep parameter for compatibility but do not persist

            // Calculate warranty and expiration when purchase is delivered
            if ($status === 'Delivered') {
                $items = $purchase->pur_name_items;
                $warranties = [];
                $expirations = [];
                $today = now();

                if (is_array($items)) {
                    // Enrich items with product defaults if missing
                    $productNames = array_column($items, 'name');
                    $products = Product::whereIn('prod_name', $productNames)->get()->keyBy('prod_name');

                    foreach ($items as $item) {
                        $itemName = $item['name'] ?? 'Unknown Item';
                        
                        // Get warranty/expiration from item or fallback to product
                        $itemWarranty = $item['warranty'] ?? null;
                        $itemExpiration = $item['expiration'] ?? null;

                        if (empty($itemWarranty) && isset($products[$itemName])) {
                            $itemWarranty = $products[$itemName]->prod_warranty;
                        }
                        if (empty($itemExpiration) && isset($products[$itemName])) {
                            $itemExpiration = $products[$itemName]->prod_expiration;
                        }

                        // Warranty Calculation
                        $calculatedWarrantyDate = null;
                        if ($itemWarranty) {
                            $warrantyStr = $itemWarranty;
                            $daysToAdd = 0;
                            
                            if (preg_match('/(\d+)\s*Day/i', $warrantyStr, $matches)) {
                                $daysToAdd = (int)$matches[1];
                            } elseif (preg_match('/(\d+)\s*Month/i', $warrantyStr, $matches)) {
                                $daysToAdd = (int)$matches[1] * 31;
                            } elseif (preg_match('/(\d+)\s*Year/i', $warrantyStr, $matches)) {
                                $daysToAdd = (int)$matches[1] * 365;
                            }
                            
                            if ($daysToAdd > 0) {
                                $calculatedWarrantyDate = $today->copy()->addDays($daysToAdd)->format('Y-m-d');
                                $warranties[] = [
                                    'item' => $itemName,
                                    'warranty_end' => $calculatedWarrantyDate,
                                    'original_warranty' => $warrantyStr
                                ];
                            } else {
                                $warranties[] = [
                                    'item' => $itemName,
                                    'warranty_end' => null,
                                    'original_warranty' => $warrantyStr
                                ];
                            }
                        }

                        // Expiration Handling
                        $expirationDate = null;
                        if ($itemExpiration) {
                            $expirationDate = date('Y-m-d', strtotime($itemExpiration));
                            $expirations[] = [
                                'item' => $itemName,
                                'expiration_date' => $expirationDate
                            ];
                        }

                        // Create PurchaseProduct Record
                        // Generate ID: PCPD + YYYYMMDD + 5 random alphanumeric chars
                        $prefix = 'PCPD';
                        $dateCode = now()->format('Ymd');
                        $randomCode = strtoupper(Str::random(5));
                        $purchaseProductId = $prefix . $dateCode . $randomCode;

                        // Get real product ID if available
                        $prodId = isset($products[$itemName]) ? $products[$itemName]->prod_id : ($item['itemId'] ?? Str::random(10));
                        
                        // Calculate quantity (always 1 for dissected items)
                        $prodUnit = 1;

                        PurchaseProduct::create([
                            'purcprod_id' => $purchaseProductId,
                            'purcprod_prod_id' => $prodId,
                            'purcprod_prod_name' => $itemName,
                            'purcprod_prod_price' => $item['price'] ?? 0,
                            'purcprod_prod_unit' => $prodUnit,
                            'purcprod_prod_type' => $purchase->pur_ven_type,
                            'purcprod_status' => 'Delivered',
                            'purcprod_date' => now(),
                            'purcprod_warranty' => $calculatedWarrantyDate,
                            'purcprod_expiration' => $expirationDate,
                            'purcprod_desc' => $purchase->pur_desc,
                        ]);
                    }
                }
                
                $purchase->pur_warranty = !empty($warranties) ? json_encode($warranties) : null;
                $purchase->pur_expiration = !empty($expirations) ? json_encode($expirations) : null;
            }

            // Update purchase status
            $purchase->pur_status = $status;
            $purchase->save();

            // Sync purchase request mirror
            if ($status === 'Pending') {
                $this->psmRepository->upsertPurchaseRequestFromPurchase($purchase);
            } else {
                $this->psmRepository->deletePurchaseRequestByPreqId($purchase->pur_id);
            }

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
     * Process purchase approval or rejection
     */
    public function processPurchaseApproval($id, $action, $approvedBy)
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

            if ($action === 'approve') {
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
                'message' => 'Failed to process purchase approval: '.$e->getMessage(),
                'data' => null,
            ];
        }
    }
}
