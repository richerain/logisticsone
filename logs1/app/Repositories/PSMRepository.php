<?php

namespace App\Repositories;

use App\Models\PSM\Budget;
use App\Models\PSM\BudgetLog;
use App\Models\PSM\Product;
use App\Models\PSM\Purchase;
use App\Models\PSM\Quote;
use App\Models\PSM\Vendor;
use App\Models\PSM\PurchaseProduct;
use App\Models\PSM\Requisition;

class PSMRepository
{
    /**
     * Get all purchase products with optional filters
     */
    public function getPurchaseProducts($filters = [])
    {
        $query = PurchaseProduct::where('purcprod_status', '!=', 'Received')
            ->orderBy('created_at', 'desc');
        return $query->get();
    }

    public function markProductsAsReceivedByProdId($prodId)
    {
        return PurchaseProduct::where('purcprod_prod_id', $prodId)
            ->update(['purcprod_status' => 'Received']);
    }

    public function markProductAsReceivedById($id)
    {
        return PurchaseProduct::where('purcprod_id', $id)
            ->update(['purcprod_status' => 'Received']);
    }

    /**
     * Delete purchase product by ID (purcprod_id)
     */
    public function deletePurchaseProduct($id)
    {
        $product = PurchaseProduct::where('purcprod_id', $id)->first();
        if ($product) {
            return $product->delete();
        }
        return false;
    }

    /**
     * Get all vendors with optional filters and search
     */
    public function getVendors($filters = [])
    {
        $query = Vendor::withCount('products');

        if (! empty($filters['search'])) {
            $query->search($filters['search']);
        }

        if (! empty($filters['status'])) {
            $query->where('ven_status', $filters['status']);
        }

        if (! empty($filters['type'])) {
            $query->where('ven_type', $filters['type']);
        }

        $sortField = $filters['sort_field'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortField, $sortOrder);

        return $query->get();
    }

    /**
     * Get all purchases with optional filters and search
     */
    public function getPurchases($filters = [])
    {
        $query = Purchase::orderBy('created_at', 'desc');

        if (! empty($filters['status'])) {
            $query->where('pur_status', $filters['status']);
        }

        return $query->get();
    }

    public function getApprovedPurchasesWithoutQuote()
    {
        $quotedPurchaseIds = Quote::whereNotNull('quo_purchase_id')->pluck('quo_purchase_id')->toArray();

        return Purchase::where('pur_status', 'Approved')
            ->whereNotIn('id', $quotedPurchaseIds)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function getApprovedPurchasesForVendor($companyName)
    {
        $quotedPurchaseIds = Quote::whereNotNull('quo_purchase_id')->pluck('quo_purchase_id')->toArray();

        return Purchase::where('pur_status', 'Approved')
            ->where('pur_company_name', $companyName)
            ->whereNotIn('id', $quotedPurchaseIds)
            ->orderBy('created_at', 'desc')
            ->get();
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
     * Get purchase by ID
     */
    public function getPurchaseById($id)
    {
        return Purchase::find($id);
    }

    /**
     * Get purchase by purchase ID
     */
    public function getPurchaseByPurchaseId($purId)
    {
        return Purchase::where('pur_id', $purId)->first();
    }

    /**
     * Create new vendor
     */
    public function createVendor($data)
    {
        return Vendor::create($data);
    }

    /**
     * Create new purchase
     */
    public function createPurchase($data)
    {
        return Purchase::create($data);
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
     * Update purchase
     */
    public function updatePurchase($id, $data)
    {
        $purchase = Purchase::find($id);
        if ($purchase) {
            $purchase->update($data);

            return $purchase;
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
     * Delete purchase
     */
    public function deletePurchase($id)
    {
        $purchase = Purchase::find($id);
        if ($purchase) {
            return $purchase->delete();
        }

        return false;
    }

    /**
     * Get products by vendor
     */
    public function getVendorProducts($venId)
    {
        return Product::where('prod_vendor', $venId)->orderBy('id', 'desc')->get();
    }

    public function getQuotes()
    {
        return Quote::orderBy('created_at', 'desc')->get();
    }

    public function getQuotesForVendor($companyName)
    {
        return Quote::select('psm_quote.*')
            ->join('psm_purchase', 'psm_quote.quo_purchase_id', '=', 'psm_purchase.id')
            ->where('psm_purchase.pur_company_name', $companyName)
            ->orderBy('psm_quote.created_at', 'desc')
            ->get();
    }

    public function getQuoteById($id)
    {
        return Quote::find($id);
    }

    public function getQuoteByQuoId($quoId)
    {
        return Quote::where('quo_id', $quoId)->first();
    }

    public function createQuote($data)
    {
        return Quote::create($data);
    }

    public function updateQuote($id, $data)
    {
        $quote = Quote::find($id);
        if ($quote) {
            $quote->update($data);

            return $quote;
        }

        return null;
    }

    public function deleteQuote($id)
    {
        $quote = Quote::find($id);
        if ($quote) {
            return $quote->delete();
        }

        return false;
    }

    public function getProducts($filters = [], $columns = ['*'])
    {
        $query = Product::query();
        if (! empty($filters['vendor'])) {
            $query->where('prod_vendor', $filters['vendor']);
        }
        if (! empty($filters['type'])) {
            $query->where('prod_type', $filters['type']);
        }
        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('prod_name', 'like', "%{$search}%")
                    ->orWhere('prod_desc', 'like', "%{$search}%");
            });
        }
        $sortField = $filters['sort_field'] ?? 'created_at';
        $sortOrder = $filters['sort_order'] ?? 'desc';
        $query->orderBy($sortField, $sortOrder);

        return $query->get($columns);
    }

    /**
     * Get all requisitions with pagination
     */
    public function getRequisitions($filters = [])
    {
        $query = Requisition::orderBy('created_at', 'desc');

        if (! empty($filters['status'])) {
            $query->where('req_status', $filters['status']);
        }

        if (! empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('req_id', 'like', "%{$search}%")
                    ->orWhere('req_requester', 'like', "%{$search}%")
                    ->orWhere('req_dept', 'like', "%{$search}%");
            });
        }

        $pageSize = $filters['page_size'] ?? 10;
        return $query->paginate($pageSize);
    }

    /**
     * Create new requisition
     */
    public function createRequisition($data)
    {
        return Requisition::create($data);
    }

    /**
     * Get requisition stats
     */
    public function getRequisitionStats()
    {
        return [
            'total' => Requisition::count(),
            'approved' => Requisition::where('req_status', 'Approved')->count(),
            'pending' => Requisition::where('req_status', 'Pending')->count(),
            'rejected' => Requisition::where('req_status', 'Rejected')->count(),
        ];
    }

    public function getVendorStats()
    {
        return [
            'total_vendors' => Vendor::count(),
            'active_vendors' => Vendor::where('ven_status', 'active')->count(),
            'inactive_vendors' => Vendor::where('ven_status', 'inactive')->count(),
            'total_products' => Product::count(),
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
     * Get product by ID
     */
    public function getProduct($id, $columns = ['*'])
    {
        return Product::select($columns)
            ->where('id', $id)
            ->orWhere('prod_id', $id)
            ->first();
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

    /**
     * Budget Methods
     */

    /**
     * Get current budget
     */
    public function getCurrentBudget()
    {
        return Budget::active()
            ->byDepartmentModule('Logistics 1', 'Procurement & Sourcing Management', 'Purchase Management')
            ->first();
    }

    /**
     * Get all budgets
     */
    public function getAllBudgets()
    {
        return Budget::orderBy('created_at', 'desc')->get();
    }

    /**
     * Get budget by ID
     */
    public function getBudgetById($id)
    {
        return Budget::find($id);
    }

    /**
     * Create new budget
     */
    public function createBudget($data)
    {
        return Budget::create($data);
    }

    /**
     * Update budget
     */
    public function updateBudget($id, $data)
    {
        $budget = Budget::find($id);
        if ($budget) {
            $budget->update($data);

            return $budget;
        }

        return null;
    }

    /**
     * Extend budget validity
     */
    public function extendBudgetValidity($id, $newValidTo, $additionalAmount = 0)
    {
        $budget = Budget::find($id);
        if ($budget) {
            $budget->bud_valid_to = $newValidTo;
            if ($additionalAmount > 0) {
                $budget->bud_allocated_amount += $additionalAmount;
                $budget->bud_remaining_amount += $additionalAmount;
            }
            $budget->updateHealthStatus();

            return $budget;
        }

        return null;
    }

    /**
     * Update budget spent amount
     */
    public function updateBudgetSpent($id, $amount)
    {
        $budget = Budget::find($id);
        if ($budget) {
            $budget->bud_spent_amount += $amount;
            $budget->bud_remaining_amount = $budget->bud_allocated_amount - $budget->bud_spent_amount;
            $budget->updateHealthStatus();

            return $budget;
        }

        return null;
    }

    /**
     * Get all budget logs
     */
    public function getAllBudgetLogs()
    {
        return BudgetLog::orderBy('created_at', 'desc')->get();
    }
}
