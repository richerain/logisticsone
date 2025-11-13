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

    public function getPurchases()
    {
        return response()->json([
            'message' => 'PSM Purchases data',
            'data' => []
        ]);
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