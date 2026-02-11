<?php

namespace App\Http\Controllers;

use App\Services\PSMService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PSMController extends Controller
{
    protected $psmService;

    public function __construct(PSMService $psMService)
    {
        $this->psmService = $psMService;
    }

    /**
     * Get purchase products
     */
    public function getPurchaseProducts()
    {
        try {
            $products = $this->psmService->getPurchaseProducts();
            return response()->json([
                'success' => true,
                'data' => $products
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Delete purchase product
     */
    public function deletePurchaseProduct($id)
    {
        try {
            $result = $this->psmService->deletePurchaseProduct($id);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete purchase product: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get active vendors for purchase orders
     */
    public function getActiveVendorsForPurchase()
    {
        try {
            $result = $this->psmService->getActiveVendorsForPurchase();

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch active vendors: '.$e->getMessage(),
                'data' => [],
            ], 500);
        }
    }

    /**
     * Get all purchases with optional filters
     */
    public function getPurchases(Request $request)
    {
        try {
            $filters = $request->only(['status']);
            $result = $this->psmService->getPurchases($filters);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch purchases: '.$e->getMessage(),
                'data' => [],
            ], 500);
        }
    }

    /**
     * Get purchase by ID
     */
    public function getPurchase($id)
    {
        try {
            $result = $this->psmService->getPurchase($id);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch purchase: '.$e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**
     * Get purchase by purchase ID
     */
    public function getPurchaseByPurchaseId($purId)
    {
        try {
            $result = $this->psmService->getPurchaseByPurchaseId($purId);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch purchase: '.$e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**
     * Create new purchase
     */
    public function createPurchase(Request $request)
    {
        $validated = $request->validate([
            'pur_name_items' => 'required|array|min:1',
            'pur_name_items.*.name' => 'required|string|max:255',
            'pur_name_items.*.price' => 'required|numeric|min:0',
            'pur_name_items.*.warranty' => 'nullable|string|max:255',
            'pur_name_items.*.expiration' => 'nullable|date',
            'pur_company_name' => 'required|string|max:255',
            'pur_ven_type' => 'required|in:equipment,supplies,furniture,automotive',
            'pur_order_by' => 'nullable|string|max:255',
            'pur_desc' => 'nullable|string',
        ]);

        try {
            $result = $this->psmService->createPurchase($validated);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create purchase: '.$e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**
     * Update purchase
     */
    public function updatePurchase(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'pur_name_items' => 'sometimes|required|array|min:1',
                'pur_name_items.*.name' => 'sometimes|required|string|max:255',
                'pur_name_items.*.price' => 'sometimes|required|numeric|min:0',
                'pur_company_name' => 'sometimes|required|string|max:255',
                'pur_ven_type' => 'sometimes|required|in:equipment,supplies,furniture,automotive',
                'pur_order_by' => 'sometimes|required|string|max:255',
                'pur_desc' => 'nullable|string',
            ]);

            $result = $this->psmService->updatePurchase($id, $validated);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update purchase: '.$e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**
     * Delete purchase
     */
    public function deletePurchase($id)
    {
        try {
            $result = $this->psmService->deletePurchase($id);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete purchase: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get purchase statistics
     */

    /**
     * Update purchase status
     */
    public function updatePurchaseStatus(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'status' => 'required|in:Pending,Approved,Rejected,Cancel,Vendor-Review,In-Progress,Completed',
                'budget_check' => 'sometimes|boolean',
                'approved_by' => 'nullable|string|max:255',
            ]);

            $budgetCheck = $validated['budget_check'] ?? false;
            $approvedBy = $validated['approved_by'] ?? null;
            $result = $this->psmService->updatePurchaseStatus($id, $validated['status'], $budgetCheck, $approvedBy);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update purchase status: '.$e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**
     * Cancel purchase
     */
    public function cancelPurchase(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'cancel_by' => 'required|string|max:255',
            ]);
            $result = $this->psmService->cancelPurchase($id, $validated['cancel_by']);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel purchase: '.$e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function listQuotes()
    {
        try {
            $result = $this->psmService->getQuotes();

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch quotes: '.$e->getMessage(),
            ], 500);
        }
    }

    public function createQuote(Request $request)
    {
        try {
            $validated = $request->validate([
                'quo_items' => 'required',
                'quo_units' => 'nullable|integer',
                'quo_total_amount' => 'nullable|numeric',
                'quo_delivery_date' => 'nullable|date',
                'quo_status' => 'nullable|string',
                'quo_item_drop_to' => 'nullable|string',
                'quo_payment' => 'nullable|string',
                'quo_stored_from' => 'nullable|string',
                'quo_purchase_id' => 'nullable|integer',
            ]);
            $result = $this->psmService->createQuote($validated);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create quote: '.$e->getMessage(),
            ], 500);
        }
    }

    public function updateQuote(Request $request, $id)
    {
        try {
            $result = $this->psmService->updateQuote($id, $request->all());

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update quote: '.$e->getMessage(),
            ], 500);
        }
    }

    public function deleteQuote($id)
    {
        try {
            $result = $this->psmService->deleteQuote($id);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete quote: '.$e->getMessage(),
            ], 500);
        }
    }

    public function listApprovedPurchasesForQuote()
    {
        try {
            $result = $this->psmService->getApprovedPurchasesForQuote();

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch approved purchases: '.$e->getMessage(),
            ], 500);
        }
    }

    public function reviewPurchaseToQuote($purchaseId)
    {
        try {
            $result = $this->psmService->reviewPurchaseToQuote($purchaseId);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to review purchase: '.$e->getMessage(),
            ], 500);
        }
    }

    public function getVendorQuotes()
    {
        try {
            $user = \Auth::guard('vendor')->user();
            
            if (! $user) {
                $user = \Auth::user();
            }

            if (! $user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'data' => [],
                ], 401);
            }

            $result = $this->psmService->getVendorQuotes($user);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch vendor quotes: '.$e->getMessage(),
                'data' => [],
            ], 500);
        }
    }

    public function getVendorQuoteNotifications()
    {
        try {
            $user = \Auth::guard('vendor')->user();
            
            if (! $user) {
                $user = \Auth::user();
            }

            if (! $user) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized',
                    'data' => [],
                ], 401);
            }

            $result = $this->psmService->getVendorQuoteNotifications($user);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch notifications: '.$e->getMessage(),
                'data' => [],
            ], 500);
        }
    }

    public function getVendors()
    {
        return response()->json([
            'message' => 'PSM Vendors data',
            'data' => [],
        ]);
    }

    /**
     * Get all vendors with optional filters
     */
    public function getVendorManagement(Request $request)
    {
        try {
            $filters = [
                'search' => $request->get('search'),
                'status' => $request->get('status'),
                'type' => $request->get('type'),
                'sort_field' => $request->get('sort_field', 'created_at'),
                'sort_order' => $request->get('sort_order', 'desc'),
            ];

            $result = $this->psmService->getVendors($filters);

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch vendors: '.$e->getMessage(),
                'data' => [],
            ], 500);
        }
    }

    /**
     * Get vendor by ID
     */
    public function getVendor($id)
    {
        try {
            $result = $this->psmService->getVendor($id);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch vendor: '.$e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function getVendorByVendorId($venId)
    {
        try {
            $result = $this->psmService->getVendorByVendorId($venId);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch vendor: '.$e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**
     * Create new vendor
     */
    public function createVendor(Request $request)
    {
        try {
            $validated = $request->validate([
                'ven_company_name' => 'required|string|max:255',
                'ven_contact_person' => 'required|string|max:255',
                'ven_email' => 'required|email|max:255',
                'ven_phone' => 'required|string|max:20',
                'ven_address' => 'required|string',
                'ven_rating' => 'nullable|integer|min:1|max:5',
                'ven_type' => 'required|in:equipment,supplies,furniture,automotive',
                'ven_status' => 'nullable|in:active,inactive',
                'ven_desc' => 'nullable|string',
            ]);

            $result = $this->psmService->createVendor($validated);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create vendor: '.$e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**
     * Update vendor
     */
    public function updateVendor(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'ven_company_name' => 'sometimes|required|string|max:255',
                'ven_contact_person' => 'sometimes|required|string|max:255',
                'ven_email' => 'sometimes|required|email|max:255',
                'ven_phone' => 'sometimes|required|string|max:20',
                'ven_address' => 'sometimes|required|string',
                'ven_rating' => 'nullable|integer|min:1|max:5',
                'ven_type' => 'sometimes|required|in:equipment,supplies,furniture,automotive',
                'ven_status' => 'sometimes|required|in:active,inactive',
                'ven_desc' => 'nullable|string',
            ]);

            $result = $this->psmService->updateVendor($id, $validated);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update vendor: '.$e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function getVendorStats()
    {
        try {
            $result = $this->psmService->getVendorStats();

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch vendor stats: '.$e->getMessage(),
                'data' => [],
            ], 500);
        }
    }

    public function getProducts(Request $request)
    {
        try {
            $filters = [
                'vendor' => $request->get('vendor'),
                'search' => $request->get('search'),
                'type' => $request->get('type'),
                'sort_field' => $request->get('sort_field', 'created_at'),
                'sort_order' => $request->get('sort_order', 'desc'),
            ];

            $result = $this->psmService->getProducts($filters);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch products: '.$e->getMessage(),
                'data' => [],
            ], 500);
        }
    }

    /**
     * Get all requisitions
     */
    public function getRequisitions(Request $request)
    {
        try {
            $filters = [
                'status' => $request->get('status'),
                'dept' => $request->get('dept'),
                'search' => $request->get('search'),
                'page_size' => $request->get('per_page', 10),
            ];
            $result = $this->psmService->getRequisitions($filters);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch requisitions: '.$e->getMessage(),
                'data' => [],
            ], 500);
        }
    }

    /**
     * Update requisition status
     */
    public function updateRequisitionStatus(Request $request, $id)
    {
        try {
            $status = $request->get('status');
            $result = $this->psmService->updateRequisitionStatus($id, $status);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Delete requisition
     */
    public function deleteRequisition($id)
    {
        try {
            $result = $this->psmService->deleteRequisition($id);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete requisition: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create new requisition
     */
    public function storeRequisition(Request $request)
    {
        try {
            $data = $request->only([
                'req_id',
                'req_items',
                'req_requester',
                'req_dept',
                'req_date',
                'req_note',
            ]);

            // Set default status
            $data['req_status'] = 'Pending';

            $result = $this->psmService->createRequisition($data);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create requisition: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get products for external API (restricted columns)
     */
    public function getExternalProducts(Request $request)
    {
        try {
            $filters = [
                'vendor' => $request->get('vendor'),
                'search' => $request->get('search'),
                'type' => $request->get('type'),
                'sort_field' => $request->get('sort_field', 'created_at'),
                'sort_order' => $request->get('sort_order', 'desc'),
            ];

            // Restricted columns for external API
            $columns = ['prod_id', 'prod_name', 'prod_desc', 'prod_type'];

            $result = $this->psmService->getProducts($filters, $columns);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch products: '.$e->getMessage(),
                'data' => [],
            ], 500);
        }
    }

    public function getProduct($id)
    {
        try {
            $result = $this->psmService->getProduct($id);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch product: '.$e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**
     * Get single product for external API (restricted columns)
     */
    public function getExternalProduct($id)
    {
        try {
            // Restricted columns for external API
            $columns = ['prod_id', 'prod_name', 'prod_desc', 'prod_type'];

            $result = $this->psmService->getProduct($id, $columns);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch product: '.$e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function getProductsByVendor($venId)
    {
        try {
            $result = $this->psmService->getVendorProducts($venId);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch vendor products: '.$e->getMessage(),
                'data' => [],
            ], 500);
        }
    }

    /**
     * Delete vendor
     */
    public function deleteVendor($id)
    {
        try {
            $result = $this->psmService->deleteVendor($id);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete vendor: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Create product for vendor
     */
    public function createProduct(Request $request)
    {
        try {
            \Log::info('Creating product payload:', $request->all());

            $vendorId = null;
            if (\Auth::guard('vendor')->check()) {
                $vendorId = \Auth::guard('vendor')->user()->vendorid;
                \Log::info('Vendor Authenticated: ' . $vendorId);
            } else {
                \Log::info('Vendor NOT Authenticated in Controller');
            }

            // Merge authenticated vendor ID into request if available
            if ($vendorId) {
                // Check if vendor exists in PSM, if not sync it
                $psmVendor = $this->psmService->getPSMVendor($vendorId);
                if (!$psmVendor['success']) {
                    $user = \Auth::guard('vendor')->user();
                    if ($user) {
                        $vendorData = [
                            'ven_id' => $user->vendorid,
                            'ven_company_name' => $user->company_name,
                            'ven_contact_person' => trim($user->firstname . ' ' . $user->lastname),
                            'ven_email' => $user->email,
                            'ven_phone' => $user->contactnum,
                            'ven_address' => $user->address,
                            'ven_type' => $user->company_type ?? 'supplies',
                            'ven_status' => $user->status,
                            'ven_rating' => $user->rating ?? 0,
                            'ven_product' => 0,
                            'ven_desc' => $user->company_desc,
                            'ven_module_from' => 'main',
                            'ven_submodule_from' => 'sync',
                        ];
                        \Log::info('Syncing vendor to PSM:', $vendorData);
                        $syncResult = $this->psmService->createVendor($vendorData);
                        
                        if (!$syncResult['success']) {
                            throw new \Exception('Failed to sync vendor profile: ' . $syncResult['message']);
                        }
                    }
                }

                $request->merge(['prod_vendor' => $vendorId]);
            }

            $validated = $request->validate([
                'prod_vendor' => ['required', 'string', 'exists:main.vendor_account,vendorid'],
                'prod_name' => 'required|string|max:255',
                'prod_price' => 'required|numeric|min:0',
                'prod_stock' => 'required|integer|min:0',
                'prod_type' => 'required|in:equipment,supplies,furniture,automotive',
                'prod_warranty' => 'required|string|max:255',
                'prod_expiration' => 'nullable|date',
                'prod_desc' => 'nullable|string',
                'prod_picture' => 'nullable|image|max:2048', // 2MB max
            ]);

            $result = $this->psmService->createProduct($validated);
            return response()->json($result);
        } catch (\Throwable $e) {
            \Log::error('Product creation failed: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create product: '.$e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function updateProduct(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'prod_vendor' => 'sometimes|required|string|exists:main.vendor_account,vendorid',
                'prod_name' => 'sometimes|required|string|max:255',
                'prod_price' => 'sometimes|required|numeric|min:0',
                'prod_stock' => 'sometimes|required|integer|min:0',
                'prod_type' => 'sometimes|required|in:equipment,supplies,furniture,automotive',
                'prod_warranty' => 'sometimes|required|string|max:255',
                'prod_expiration' => 'nullable|date',
                'prod_desc' => 'nullable|string',
                'prod_picture' => 'nullable|image|max:2048', // 2MB max
                'remove_picture' => 'nullable|boolean',
            ]);

            $result = $this->psmService->updateProduct($id, $validated);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product: '.$e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    public function deleteProduct($id)
    {
        try {
            $result = $this->psmService->deleteProduct($id);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product: '.$e->getMessage(),
            ], 500);
        }
    }

    /**
     * Get vendor info for public API
     */
    public function getVendorInfo(Request $request)
    {
        try {
            $filters = [
                'search' => $request->get('search'),
                'status' => 'active',
                'type' => $request->get('type'),
            ];

            $result = $this->psmService->getVendors($filters);

            return response()->json([
                'success' => $result['success'],
                'data' => $result['data'] ?? [],
                'message' => $result['message'] ?? 'Vendor data retrieved successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch vendor info: '.$e->getMessage(),
                'data' => [],
            ], 500);
        }
    }

    /**
     * Update vendor info for public API
     */
    public function updateVendorInfo(Request $request)
    {
        return response()->json([
            'success' => false,
            'message' => 'Vendor updates must be done through the PSM module',
        ], 405);
    }

    /**
     * Budget Management Endpoints
     */


    /**
     * Get current budget
     */
    public function getCurrentBudget()
    {
        try {
            $result = $this->psmService->getCurrentBudget();

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch budget: '.$e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**
     * Get all budgets
     */
    public function getAllBudgets()
    {
        try {
            // Restrict access
            $user = \Auth::guard('sws')->user();
            $role = $user ? strtolower($user->roles ?? '') : '';
            
            if (!$user || !in_array($role, ['superadmin', 'admin', 'manager'])) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $result = $this->psmService->getAllBudgets();

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch budgets: '.$e->getMessage(),
                'data' => [],
            ], 500);
        }
    }

    /**
     * Get all budget logs
     */
    public function getBudgetLogs()
    {
        try {
            // Restrict access
            $user = \Auth::guard('sws')->user();
            $role = $user ? strtolower($user->roles ?? '') : '';
            
            if (!$user || !in_array($role, ['superadmin', 'admin', 'manager'])) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $result = $this->psmService->getAllBudgetLogs();

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch budget logs: '.$e->getMessage(),
                'data' => [],
            ], 500);
        }
    }

    /**
     * Extend budget validity
     */
    public function extendBudgetValidity(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'validity_type' => 'required|in:Week,Month,Year',
                'additional_amount' => 'nullable|numeric|min:0',
            ]);

            $result = $this->psmService->extendBudgetValidity(
                $id,
                $validated['validity_type'],
                $validated['additional_amount'] ?? 0
            );

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to extend budget: '.$e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**
     * Budget approval for purchase
     */
    public function budgetApproval(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'action' => 'required|in:approve,reject',
                'approved_by' => 'required|string|max:255',
            ]);

            $result = $this->psmService->processBudgetApproval(
                $id,
                $validated['action'],
                $validated['approved_by']
            );

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to process budget approval: '.$e->getMessage(),
                'data' => null,
            ], 500);
        }
    }

    /**
     * Store a new budget request
     */
    public function storeRequestBudget(Request $request)
    {
        try {
            $validated = $request->validate([
                'req_by' => 'required|string|max:255',
                'req_date' => 'required|date',
                'req_dept' => 'required|string|max:255',
                'req_amount' => 'required|numeric|min:0',
                'req_purpose' => 'required|string',
                'req_contact' => 'required|string|max:255',
            ]);

            $validated['req_status'] = 'Pending';

            $budgetRequest = \App\Models\PSM\RequestBudget::create($validated);

            return response()->json([
                'success' => true,
                'message' => 'Budget request created successfully.',
                'data' => $budgetRequest
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create budget request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get all budget requests
     */
    public function getRequestBudgets()
    {
        try {
            $requests = \App\Models\PSM\RequestBudget::orderBy('created_at', 'desc')->get();
            
            return response()->json([
                'success' => true,
                'data' => $requests
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch budget requests: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Cancel a budget request
     */
    public function cancelRequestBudget($id)
    {
        try {
            $request = \App\Models\PSM\RequestBudget::find($id);

            if (!$request) {
                return response()->json([
                    'success' => false,
                    'message' => 'Budget request not found.'
                ], 404);
            }

            if ($request->req_status !== 'Pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Only pending requests can be cancelled.'
                ], 400);
            }

            $request->req_status = 'Cancelled';
            $request->save();

            return response()->json([
                'success' => true,
                'message' => 'Budget request cancelled successfully.',
                'data' => $request
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to cancel budget request: ' . $e->getMessage()
            ], 500);
        }
    }
}
