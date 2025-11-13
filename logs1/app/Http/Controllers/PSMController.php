<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\PSMService;

class PSMController extends Controller
{
    protected $psmService;

    public function __construct(PSMService $psMService)
    {
        $this->psmService = $psMService;
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
                'message' => 'Failed to fetch active vendors: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    /**
     * Get all purchases with optional filters
     */
    public function getPurchases(Request $request)
    {
        try {
            $filters = [
                'search' => $request->get('search'),
                'status' => $request->get('status'),
                'vendor_type' => $request->get('vendor_type'),
                'company' => $request->get('company'),
                'sort_field' => $request->get('sort_field', 'created_at'),
                'sort_order' => $request->get('sort_order', 'desc')
            ];

            $result = $this->psmService->getPurchases($filters);

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch purchases: ' . $e->getMessage(),
                'data' => []
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
                'message' => 'Failed to fetch purchase: ' . $e->getMessage(),
                'data' => null
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
                'message' => 'Failed to fetch purchase: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    /**
     * Create new purchase
     */
    public function createPurchase(Request $request)
    {
        try {
            $validated = $request->validate([
                'pur_name_items' => 'required|array|min:1',
                'pur_name_items.*.name' => 'required|string|max:255',
                'pur_name_items.*.price' => 'required|numeric|min:0',
                'pur_company_name' => 'required|string|max:255',
                'pur_ven_type' => 'required|in:equipment,supplies,furniture,automotive',
                'pur_desc' => 'nullable|string'
            ]);

            $result = $this->psmService->createPurchase($validated);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create purchase: ' . $e->getMessage(),
                'data' => null
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
                'pur_desc' => 'nullable|string'
            ]);

            $result = $this->psmService->updatePurchase($id, $validated);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update purchase: ' . $e->getMessage(),
                'data' => null
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
                'message' => 'Failed to delete purchase: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get purchase statistics
     */
    public function getPurchaseStats()
    {
        try {
            $result = $this->psmService->getPurchaseStats();
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch purchase stats: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    public function getVendorQuotes()
    {
        return response()->json([
            'message' => 'PSM Vendor Quotes data',
            'data' => []
        ]);
    }

    public function getVendors()
    {
        return response()->json([
            'message' => 'PSM Vendors data',
            'data' => []
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
                'sort_order' => $request->get('sort_order', 'desc')
            ];

            $result = $this->psmService->getVendors($filters);

            return response()->json($result);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch vendors: ' . $e->getMessage(),
                'data' => []
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
                'message' => 'Failed to fetch vendor: ' . $e->getMessage(),
                'data' => null
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
                'message' => 'Failed to fetch vendor: ' . $e->getMessage(),
                'data' => null
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
                'ven_desc' => 'nullable|string'
            ]);

            $result = $this->psmService->createVendor($validated);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create vendor: ' . $e->getMessage(),
                'data' => null
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
                'ven_desc' => 'nullable|string'
            ]);

            $result = $this->psmService->updateVendor($id, $validated);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update vendor: ' . $e->getMessage(),
                'data' => null
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
                'message' => 'Failed to fetch vendor stats: ' . $e->getMessage(),
                'data' => []
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
                'sort_order' => $request->get('sort_order', 'desc')
            ];

            $result = $this->psmService->getProducts($filters);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch products: ' . $e->getMessage(),
                'data' => []
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
                'message' => 'Failed to fetch vendor products: ' . $e->getMessage(),
                'data' => []
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
                'message' => 'Failed to delete vendor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Create product for vendor
     */
    public function createProduct(Request $request)
    {
        try {
            $validated = $request->validate([
                'prod_vendor' => 'required|string|exists:psm.psm_vendor,ven_id',
                'prod_name' => 'required|string|max:255',
                'prod_price' => 'required|numeric|min:0',
                'prod_stock' => 'required|integer|min:0',
                'prod_type' => 'required|in:equipment,supplies,furniture,automotive',
                'prod_warranty' => 'nullable|string|max:255',
                'prod_expiration' => 'nullable|date',
                'prod_desc' => 'nullable|string'
            ]);

            $result = $this->psmService->createProduct($validated);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create product: ' . $e->getMessage(),
                'data' => null
            ], 500);
        }
    }

    public function updateProduct(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'prod_vendor' => 'sometimes|required|string|exists:psm.psm_vendor,ven_id',
                'prod_name' => 'sometimes|required|string|max:255',
                'prod_price' => 'sometimes|required|numeric|min:0',
                'prod_stock' => 'sometimes|required|integer|min:0',
                'prod_type' => 'sometimes|required|in:equipment,supplies,furniture,automotive',
                'prod_warranty' => 'nullable|string|max:255',
                'prod_expiration' => 'nullable|date',
                'prod_desc' => 'nullable|string'
            ]);

            $result = $this->psmService->updateProduct($id, $validated);
            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product: ' . $e->getMessage(),
                'data' => null
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
                'message' => 'Failed to delete product: ' . $e->getMessage()
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
                'type' => $request->get('type')
            ];

            $result = $this->psmService->getVendors($filters);
            
            return response()->json([
                'success' => $result['success'],
                'data' => $result['data'] ?? [],
                'message' => $result['message'] ?? 'Vendor data retrieved successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch vendor info: ' . $e->getMessage(),
                'data' => []
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
            'message' => 'Vendor updates must be done through the PSM module'
        ], 405);
    }
}