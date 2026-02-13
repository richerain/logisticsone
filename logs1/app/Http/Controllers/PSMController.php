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
            // Optional link to consolidated request for post-processing
            'consolidated_id' => 'nullable|integer',
            'con_req_id' => 'nullable|string|max:255',
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
     * Get purchase requests (mirror of Pending purchases)
     */
    public function getPurchaseRequests(Request $request)
    {
        try {
            $filters = [
                'status' => $request->get('status'),
            ];
            $data = $this->psmService->getPurchaseRequests($filters);
            return response()->json([
                'success' => true,
                'data' => $data,
                'message' => 'Purchase requests retrieved successfully',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch purchase requests: '.$e->getMessage(),
                'data' => [],
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
                'approved_by' => 'nullable|string|max:255',
            ]);

            $approvedBy = $validated['approved_by'] ?? null;
            $result = $this->psmService->updatePurchaseStatus($id, $validated['status'], $approvedBy);

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
            // cancel_by no longer persisted; keep validation optional for backward compatibility
            $cancelBy = $request->get('cancel_by');
            $result = $this->psmService->cancelPurchase($id, $cancelBy);

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

    /**
     * Get all budgets
     */
    public function getBudgets()
    {
        try {
            $budgets = $this->psmService->getBudgets();
            return response()->json([
                'success' => true,
                'data' => $budgets
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get current active budget
     */
    public function getCurrentBudget()
    {
        try {
            $budget = $this->psmService->getCurrentBudget();
            return response()->json([
                'success' => true,
                'data' => $budget
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create new budget
     */
    public function createBudget(Request $request)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after:valid_from',
            'description' => 'nullable|string',
        ]);

        try {
            $budget = $this->psmService->createBudget($validated);
            return response()->json([
                'success' => true,
                'message' => 'Budget created successfully',
                'data' => $budget
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update budget
     */
    public function updateBudget(Request $request, $id)
    {
        $validated = $request->validate([
            'amount' => 'required|numeric|min:0',
            'valid_from' => 'required|date',
            'valid_to' => 'required|date|after:valid_from',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive,expired',
        ]);

        try {
            $budget = $this->psmService->updateBudget($id, $validated);
            return response()->json([
                'success' => true,
                'message' => 'Budget updated successfully',
                'data' => $budget
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Extend budget validity
     */
    public function extendBudget(Request $request, $id)
    {
        $validated = $request->validate([
            'extension_days' => 'required|integer|min:1',
        ]);

        try {
            $budget = $this->psmService->extendBudget($id, $validated['extension_days']);
            return response()->json([
                'success' => true,
                'message' => 'Budget extended successfully',
                'data' => $budget
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get budget logs
     */
    public function getBudgetLogs()
    {
        try {
            $logs = $this->psmService->getBudgetLogs();
            return response()->json([
                'success' => true,
                'data' => $logs
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get allocated budgets
     */
    public function getAllocatedBudgets()
    {
        try {
            $allocated = $this->psmService->getAllocatedBudgets();
            return response()->json([
                'success' => true,
                'data' => $allocated
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get budget requests
     */
    public function getBudgetRequests()
    {
        try {
            $requests = $this->psmService->getBudgetRequests();
            return response()->json([
                'success' => true,
                'data' => $requests
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create budget request
     */
    public function createBudgetRequest(Request $request)
    {
        try {
            // AUTO-FIX DATABASE SCHEMA ON THE FLY
            try {
                if (!\Illuminate\Support\Facades\Schema::connection('psm')->hasColumn('psm_consolidated', 'req_id')) {
                    \Illuminate\Support\Facades\Schema::connection('psm')->table('psm_consolidated', function ($table) {
                        $table->string('req_id')->nullable()->after('id');
                    });
                }
                if (!\Illuminate\Support\Facades\Schema::connection('psm')->hasColumn('psm_consolidated', 'parent_budget_req_id')) {
                    \Illuminate\Support\Facades\Schema::connection('psm')->table('psm_consolidated', function ($table) {
                        $table->string('parent_budget_req_id')->nullable()->after('con_budget_approval');
                    });
                }
                if (!\Illuminate\Support\Facades\Schema::connection('psm')->hasColumn('psm_consolidated', 'con_chosen_vendor')) {
                    \Illuminate\Support\Facades\Schema::connection('psm')->table('psm_consolidated', function ($table) {
                        $table->string('con_chosen_vendor')->nullable()->after('con_items');
                    });
                }
                if (!\Illuminate\Support\Facades\Schema::connection('psm')->hasColumn('psm_consolidated', 'con_dept')) {
                    \Illuminate\Support\Facades\Schema::connection('psm')->table('psm_consolidated', function ($table) {
                        $table->string('con_dept')->nullable()->after('con_chosen_vendor');
                    });
                }
                if (!\Illuminate\Support\Facades\Schema::connection('psm')->hasColumn('psm_requisition', 'req_chosen_vendor')) {
                    \Illuminate\Support\Facades\Schema::connection('psm')->table('psm_requisition', function ($table) {
                        $table->string('req_chosen_vendor')->nullable()->after('req_items');
                    });
                }
            } catch (\Exception $dbEx) {
                // Log but continue
                \Illuminate\Support\Facades\Log::error("PSM DB Fix Error: " . $dbEx->getMessage());
            }

            $data = $request->all();
            $result = $this->psmService->createBudgetRequest($data);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create budget request: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cancel budget request
     */
    public function cancelBudgetRequest($id)
    {
        try {
            $result = $this->psmService->cancelBudgetRequest($id);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get external budget requests
     */
    public function getExternalBudgetRequests(Request $request)
    {
        try {
            $requests = $this->psmService->getExternalBudgetRequests();
            return response()->json([
                'success' => true,
                'data' => $requests
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getExternalRequisitions(Request $request)
    {
        try {
            $response = \Illuminate\Support\Facades\Http::get('https://log2.microfinancial-1.com/api/purchase_requisition.php');
            
            if ($response->successful()) {
                return response()->json($response->json());
            }

            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch external requisitions'
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get single external budget request
     */
    public function getExternalBudgetRequest(Request $request, $id)
    {
        try {
            $budgetRequest = $this->psmService->getExternalBudgetRequest($id);
            
            if ($budgetRequest) {
                return response()->json([
                    'success' => true,
                    'data' => $budgetRequest
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Budget request not found'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update external budget request status
     */
    public function updateExternalBudgetRequestStatus(Request $request, $id)
    {
        try {
            // Check both body and query for req_status
            $status = $request->input('req_status') ?? $request->query('req_status');
            
            if (!$status) {
                return response()->json([
                    'success' => false,
                    'message' => 'Status is required'
                ], 400);
            }

            $result = $this->psmService->updateBudgetRequestStatus($id, $status);

            if ($result) {
                return response()->json([
                    'success' => true,
                    'message' => 'Budget request status updated successfully'
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Budget request not found or no changes made'
                ], 404);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
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
     * Get requisitions
     */
    public function getRequisitions(Request $request)
    {
        try {
            // NEW: Support fetching only approved consolidated budget requests
            if ($request->has('approved_consolidated')) {
                $approved = \App\Models\PSM\Consolidated::where('con_budget_approval', 'Approved')
                    ->where(function ($q) {
                        $q->whereNull('con_purchase_order')
                          ->orWhere('con_purchase_order', '');
                    })
                    ->orderBy('created_at', 'desc')
                    ->get();
                
                return response()->json([
                    'success' => true,
                    'data' => $approved
                ]);
            }

            $filters = $request->only(['status', 'is_consolidated', 'page_size', 'search', 'dept']);
            $result = $this->psmService->getRequisitions($filters);

            // If we are looking for consolidated view, we should merge already consolidated
            // and those approved requisitions that are not yet consolidated.
            if ($request->has('view_consolidated')) {
                // 1. Get already consolidated items
                $consolidated = \App\Models\PSM\Consolidated::orderBy('created_at', 'desc')->get();
                
                // 2. Get approved requisitions not yet consolidated
                $pending = \App\Models\PSM\Requisition::where('req_status', 'Approved')
                    ->where('is_consolidated', 0)
                    ->orderBy('created_at', 'desc')
                    ->get();
                
                // 3. Map pending to match consolidated structure for UI consistency
                $mappedPending = $pending->map(function($item) {
                    return [
                        'id' => $item->id,
                        'con_req_id' => '-', // No consolidated ID yet
                        'req_id' => $item->req_id,
                        'con_items' => $item->req_items,
                        'con_chosen_vendor' => $item->req_chosen_vendor,
                        'con_dept' => $item->req_dept,
                        'con_total_price' => $item->req_price,
                        'con_requester' => $item->req_requester,
                        'con_date' => $item->req_date,
                        'con_note' => $item->req_note,
                        'con_status' => $item->req_status,
                        'con_budget_approval' => 'Pending', // Default for non-consolidated
                        'req_dept' => $item->req_dept,
                        'req_chosen_vendor' => $item->req_chosen_vendor,
                        'created_at' => $item->created_at,
                    ];
                });

                // Merge them
                $merged = $consolidated->concat($mappedPending)->sortByDesc('created_at')->values();

                return response()->json([
                    'success' => true,
                    'data' => $merged
                ]);
            }

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
     * Get single requisition
     */
    public function getRequisition($id)
    {
        try {
            $result = $this->psmService->getRequisition($id);

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch requisition: '.$e->getMessage(),
                'data' => null,
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
                'req_price',
                'req_chosen_vendor',
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
            $columns = ['prod_id', 'prod_vendor', 'prod_name', 'prod_desc', 'prod_type', 'prod_price'];

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
            $columns = ['prod_id', 'prod_vendor', 'prod_name', 'prod_desc', 'prod_type', 'prod_price'];

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
     * Purchase approval
     */
    public function purchaseApproval(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'action' => 'required|in:approve,reject',
                'approved_by' => 'required|string|max:255',
            ]);

            $result = $this->psmService->processPurchaseApproval(
                $id,
                $validated['action'],
                $validated['approved_by']
            );

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to process purchase approval: '.$e->getMessage(),
                'data' => null,
            ], 500);
        }
    }
}
