<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\Purchase;
use App\Models\Quote;
use Illuminate\Support\Facades\Log;

class PSMController extends Controller
{
    // Vendor Management
    public function getVendors(Request $request)
    {
        try {
            $vendors = Vendor::all();
            return response()->json([
                'success' => true,
                'data' => $vendors
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch vendors: ' . $e->getMessage()
            ], 500);
        }
    }

    public function createVendor(Request $request)
    {
        $request->validate([
            'ven_name' => 'required|string|max:255',
            'ven_email' => 'required|email|unique:psm_vendors,ven_email',
            'ven_contacts' => 'nullable|string|max:255',
            'ven_address' => 'nullable|string',
            'ven_rating' => 'nullable|numeric|min:0|max:5'
        ]);

        try {
            // Remove ven_code from request - let the model handle auto-generation
            $vendorData = $request->all();
            $vendor = Vendor::create($vendorData);
            
            return response()->json([
                'success' => true,
                'message' => 'Vendor created successfully',
                'data' => $vendor
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create vendor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateVendor(Request $request, $id)
    {
        try {
            $vendor = Vendor::findOrFail($id);
            
            $request->validate([
                'ven_name' => 'sometimes|required|string|max:255',
                'ven_email' => 'sometimes|required|email|unique:psm_vendors,ven_email,' . $id . ',ven_id',
                'ven_contacts' => 'nullable|string|max:255',
                'ven_address' => 'nullable|string',
                'ven_rating' => 'nullable|numeric|min:0|max:5',
                'ven_status' => 'sometimes|in:active,inactive'
            ]);

            $vendor->update($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Vendor updated successfully',
                'data' => $vendor
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update vendor: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteVendor($id)
    {
        try {
            $vendor = Vendor::findOrFail($id);
            $vendor->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Vendor deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete vendor: ' . $e->getMessage()
            ], 500);
        }
    }

    // Vendor Quote Management
    public function getQuotes(Request $request)
    {
        try {
            $quotes = Quote::with(['vendor', 'purchase'])->orderBy('created_at', 'desc')->get();
            
            return response()->json([
                'success' => true,
                'data' => $quotes
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch quotes: ' . $e->getMessage()
            ], 500);
        }
    }

    public function createQuote(Request $request)
    {
        $request->validate([
            'request_id' => 'required|exists:psm_purchase_orders,request_id',
            'ven_id' => 'required|exists:psm_vendors,ven_id',
            'delivery_lead_time' => 'required|integer|min:1',
            'quote_date' => 'required|date',
            'notes' => 'nullable|string'
        ]);

        try {
            // Get the purchase request data
            $purchase = Purchase::where('request_id', $request->request_id)->firstOrFail();
            
            // Map purchase status to quote status
            $statusMapping = [
                'Pending' => 'pending',
                'In Progress' => 'received',
                'Received' => 'received',
                'Approved' => 'approved',
                'Rejected' => 'rejected'
            ];
            
            $quoteStatus = $statusMapping[$purchase->status] ?? 'pending';

            // Create quote with data from purchase request
            $quoteData = [
                'request_id' => $purchase->request_id,
                'ven_id' => $request->ven_id,
                'item_name' => $purchase->item,
                'quantity' => $purchase->quantity,
                'unit_price' => $purchase->unit_price,
                'delivery_lead_time' => $request->delivery_lead_time,
                'quote_date' => $request->quote_date,
                'notes' => $request->notes ?: $purchase->description,
                'status' => $quoteStatus
            ];
            
            // TEMPORARY FIX: If request_code column exists, add it
            $tableColumns = \Schema::getColumnListing('psm_quotes');
            if (in_array('request_code', $tableColumns)) {
                $quoteData['request_code'] = $purchase->request_id;
            }
            
            $quote = Quote::create($quoteData);
            
            // Load relationships for response
            $quote->load(['vendor', 'purchase']);

            return response()->json([
                'success' => true,
                'message' => 'Quote created successfully',
                'data' => $quote
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create quote: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateQuote(Request $request, $id)
    {
        try {
            $quote = Quote::findOrFail($id);
            
            $request->validate([
                'status' => 'sometimes|in:pending,received,approved,rejected',
                'notes' => 'nullable|string'
            ]);

            // Only allow updating status and notes
            $updateData = $request->only(['status', 'notes']);
            
            $quote->update($updateData);
            $quote->load(['vendor', 'purchase']);
            
            return response()->json([
                'success' => true,
                'message' => 'Quote updated successfully',
                'data' => $quote
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update quote: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteQuote($id)
    {
        try {
            $quote = Quote::findOrFail($id);
            $quote->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Quote deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete quote: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getQuote($id)
    {
        try {
            $quote = Quote::with(['vendor', 'purchase'])->findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $quote
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch quote: ' . $e->getMessage()
            ], 500);
        }
    }

    // Get purchase requests for dropdown
    public function getPurchaseRequestsForQuotes(Request $request)
    {
        try {
            $purchases = Purchase::whereNotIn('status', ['Rejected'])
                ->orderBy('created_at', 'desc')
                ->get(['purchase_id', 'request_id', 'vendor', 'item', 'quantity', 'unit_price', 'total_quote', 'expected_delivery', 'description', 'quote_date', 'status']);
            
            return response()->json([
                'success' => true,
                'data' => $purchases
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch purchase requests: ' . $e->getMessage()
            ], 500);
        }
    }

    // Purchase Management
    public function getPurchaseRequests(Request $request)
    {
        try {
            $purchases = Purchase::orderBy('created_at', 'desc')->get();
            
            return response()->json([
                'success' => true,
                'data' => $purchases
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch purchase requests: ' . $e->getMessage()
            ], 500);
        }
    }

    public function createPurchaseRequest(Request $request)
    {
        $request->validate([
            'branch' => 'required|string|max:255',
            'vendor' => 'required|string|max:255',
            'item' => 'required|string|max:255',
            'quantity' => 'required|integer|min:1',
            'unit_price' => 'required|numeric|min:0',
            'estimated_budget' => 'required|numeric|min:0',
            'expected_delivery' => 'required|string|max:50',
            'quote_date' => 'required|date',
            'status' => 'sometimes|in:Pending,In Progress,Received,Approved,Rejected',
            'description' => 'nullable|string'
        ]);

        try {
            // Remove manual ID generation - let the model handle it
            $purchaseData = $request->all();
            
            // The request_id and po_number will be automatically generated by the model
            $purchase = Purchase::create($purchaseData);

            return response()->json([
                'success' => true,
                'message' => 'Purchase request created successfully',
                'data' => $purchase
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create purchase request: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updatePurchaseRequest(Request $request, $id)
    {
        try {
            $purchase = Purchase::findOrFail($id);
            
            $request->validate([
                'branch' => 'sometimes|required|string|max:255',
                'vendor' => 'sometimes|required|string|max:255',
                'item' => 'sometimes|required|string|max:255',
                'quantity' => 'sometimes|required|integer|min:1',
                'unit_price' => 'sometimes|required|numeric|min:0',
                'estimated_budget' => 'sometimes|required|numeric|min:0',
                'expected_delivery' => 'sometimes|required|string|max:50',
                'quote_date' => 'sometimes|required|date',
                'description' => 'nullable|string'
                // Remove status from update - status can only be changed in Vendor Quote
            ]);

            // Remove status from update data
            $updateData = $request->except(['status']);
            
            $purchase->update($updateData);
            
            return response()->json([
                'success' => true,
                'message' => 'Purchase request updated successfully',
                'data' => $purchase
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update purchase request: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deletePurchaseRequest($id)
    {
        try {
            $purchase = Purchase::findOrFail($id);
            $purchase->delete();
            
            return response()->json([
                'success' => true,
                'message' => 'Purchase request deleted successfully'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete purchase request: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getPurchaseRequest($id)
    {
        try {
            $purchase = Purchase::findOrFail($id);
            
            return response()->json([
                'success' => true,
                'data' => $purchase
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch purchase request: ' . $e->getMessage()
            ], 500);
        }
    }
}