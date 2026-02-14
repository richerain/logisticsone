<?php

namespace App\Repositories;

use App\Models\PSM\Product;
use App\Models\PSM\Purchase;
use App\Models\PSM\Quote;
use App\Models\PSM\Vendor;
use App\Models\PSM\PurchaseProduct;
use App\Models\PSM\Requisition;
use App\Models\PSM\RequisitionItem;
use App\Models\PSM\Supplier;
use App\Models\PSM\PurchaseOrder;
use App\Models\PSM\PurchaseItem;
use App\Models\PSM\Budget;
use App\Models\PSM\BudgetLog;
use App\Models\PSM\Consolidated;
use App\Models\PSM\PurchaseRequest;
use Illuminate\Support\Str;

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

        if (isset($filters['is_consolidated'])) {
            $query->where('is_consolidated', $filters['is_consolidated']);
        }

        if (! empty($filters['dept'])) {
            $query->where('req_dept', $filters['dept']);
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
     * Mark requisitions as consolidated
     */
    public function markRequisitionsAsConsolidated($reqIds, $parentBudgetReqId = null)
    {
        // Get the requisitions to be consolidated
        $requisitions = Requisition::whereIn('req_id', $reqIds)->get();

        foreach ($requisitions as $req) {
            // Generate CONS ID
            $consId = 'CONS' . now()->format('Ymd') . strtoupper(Str::random(5));

            // Save to psm_consolidated table
            Consolidated::create([
                'con_req_id' => $consId,
                'req_id' => $req->req_id,
                'con_items' => $req->req_items,
                'con_chosen_vendor' => $req->req_chosen_vendor,
                'con_dept' => $req->req_dept,
                'con_total_price' => $req->req_price,
                'con_requester' => $req->req_requester,
                'con_date' => $req->req_date,
                'con_note' => $req->req_note,
                'con_status' => $req->req_status,
                'con_budget_approval' => 'pending',
                'parent_budget_req_id' => $parentBudgetReqId,
            ]);

            // Mark the original requisition as consolidated
            $req->update(['is_consolidated' => true]);
        }

        return true;
    }

    /**
     * Create new requisition
     */
    public function createRequisition($data)
    {
        return Requisition::create($data);
    }

    /**
     * Get requisition by ID
     */
    public function getRequisition($id)
    {
        return Requisition::find($id);
    }

    /**
     * Update requisition status
     */
    public function updateRequisitionStatus($id, $status)
    {
        $requisition = Requisition::find($id);
        if ($requisition) {
            $requisition->update(['req_status' => $status]);
            return $requisition;
        }
        return null;
    }

    /**
     * Delete requisition
     */
    public function deleteRequisition($id)
    {
        $requisition = Requisition::find($id);
        if ($requisition) {
            return $requisition->delete();
        }
        return false;
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
     * Purchase Request methods
     */
    public function getPurchaseRequests($filters = [])
    {
        $query = PurchaseRequest::orderBy('created_at', 'desc');
        if (!empty($filters['status'])) {
            $query->where('preq_status', $filters['status']);
        }
        // By default exclude processed requests (kept legacy compatibility with any non-null marker)
        if (empty($filters['include_reviewed'])) {
            $query->whereNull('preq_process');
        }
        return $query->get();
    }

    public function createPurchaseRequest($data)
    {
        return PurchaseRequest::create($data);
    }

    public function getPurchaseRequestByPreqId($preqId)
    {
        return PurchaseRequest::where('preq_id', $preqId)->first();
    }

    public function updatePurchaseRequestByPreqId($preqId, $data)
    {
        return PurchaseRequest::where('preq_id', $preqId)->update($data);
    }

    public function upsertPurchaseRequestFromPurchase(Purchase $purchase)
    {
        return PurchaseRequest::updateOrCreate(
            ['preq_id' => $purchase->pur_id],
            [
                'preq_name_items' => $purchase->pur_name_items,
                'preq_unit' => $purchase->pur_unit,
                'preq_total_amount' => $purchase->pur_total_amount,
                'preq_ven_id' => null,
                'preq_ven_company_name' => $purchase->pur_company_name,
                'preq_ven_type' => $purchase->pur_ven_type,
                'preq_status' => 'Pending',
                'preq_process' => null,
                'preq_order_by' => $purchase->pur_order_by,
                'preq_desc' => $purchase->pur_desc,
            ]
        );
    }

    public function deletePurchaseRequestByPreqId($preqId)
    {
        return PurchaseRequest::where('preq_id', $preqId)->delete();
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

    /**
     * Get all allocated budgets
     */
    public function getAllocatedBudgets()
    {
        return \DB::connection('psm')->table('psm_budget_allocated')->orderBy('all_date', 'desc')->get();
    }

    /**
     * Get all budget requests
     */
    public function getBudgetRequests()
    {
        return \DB::connection('psm')->table('psm_request_budget')->orderBy('created_at', 'desc')->get();
    }

    /**
     * Get pending budget requests
     */
    public function getPendingBudgetRequests()
    {
        return \DB::connection('psm')->table('psm_request_budget')
            ->where('req_status', 'Pending')
            ->orderBy('req_date', 'desc')
            ->get();
    }

    /**
     * Get budget request by ID
     */
    public function getBudgetRequestById($id)
    {
        return \DB::connection('psm')->table('psm_request_budget')->where('req_id', $id)->first();
    }

    /**
     * Create budget request
     */
    public function createBudgetRequest($data)
    {
        return \DB::connection('psm')->table('psm_request_budget')->insert($data);
    }

    /**
     * Update budget request status
     */
    public function updateBudgetRequestStatus($id, $status)
    {
        $result = \DB::connection('psm')->table('psm_request_budget')
            ->where('req_id', $id)
            ->update([
                'req_status' => $status,
                'updated_at' => now()
            ]);

        if ($result && $status === 'Approved') {
            // Sync status to psm_consolidated table
            Consolidated::where('parent_budget_req_id', $id)
                ->update(['con_budget_approval' => 'Approved']);
        }

        return $result;
    }
}
