<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\Shop;
use App\Models\Product;
use App\Models\Order;
use App\Models\Reorder;
use App\Models\BudgetApproval;

class PSMController extends Controller
{
    // Vendor Management
    public function getVendors()
    {
        $vendors = Vendor::with('shops')->get();
        return response()->json($vendors);
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

        $vendor = Vendor::create($request->all());
        return response()->json($vendor, 201);
    }

    public function updateVendor(Request $request, $id)
    {
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
        return response()->json($vendor);
    }

    public function deleteVendor($id)
    {
        $vendor = Vendor::findOrFail($id);
        $vendor->delete();
        
        return response()->json(['message' => 'Vendor deleted successfully']);
    }

    // Shop Management
    public function getShops()
    {
        $shops = Shop::with(['vendor', 'products'])->get();
        return response()->json($shops);
    }

    public function createShop(Request $request)
    {
        $request->validate([
            'shop_name' => 'required|string|max:255',
            'ven_id' => 'required|exists:psm_vendors,ven_id',
            'shop_status' => 'sometimes|in:active,inactive,maintenance'
        ]);

        $shop = Shop::create($request->all());
        return response()->json($shop, 201);
    }

    // Product Management
    public function getProducts()
    {
        $products = Product::with(['shop.vendor'])->get();
        return response()->json($products);
    }

    public function createProduct(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|exists:psm_shops,shop_id',
            'prod_name' => 'required|string|max:255',
            'prod_category' => 'nullable|string|max:100',
            'prod_stock' => 'nullable|integer|min:0',
            'prod_price' => 'required|numeric|min:0',
            'prod_desc' => 'nullable|string',
            'prod_publish' => 'sometimes|in:posted,not posted'
        ]);

        $product = Product::create($request->all());
        
        // Update shop product count
        $shop = Shop::find($request->shop_id);
        $shop->update([
            'shop_prods' => $shop->products()->count()
        ]);

        return response()->json($product, 201);
    }

    // Order Management
    public function getOrders()
    {
        $orders = Order::with(['shop.vendor', 'product', 'budgetApprovals'])->get();
        return response()->json($orders);
    }

    public function createOrder(Request $request)
    {
        $request->validate([
            'shop_id' => 'required|exists:psm_shops,shop_id',
            'prod_id' => 'required|exists:psm_products,prod_id',
            'quantity' => 'required|integer|min:1',
            'order_price' => 'required|numeric|min:0',
            'order_desc' => 'nullable|string'
        ]);

        $order = Order::create($request->all());
        
        // Create budget approval entry
        BudgetApproval::create([
            'entity_type' => 'order',
            'entity_id' => $order->order_id,
            'bud_name' => 'Order #' . $order->order_id,
            'quantity' => $order->quantity,
            'unit_price' => $order->order_price,
            'total_budget' => $order->total_amount,
            'bud_desc' => $order->order_desc,
            'bud_status' => 'pending'
        ]);

        return response()->json($order, 201);
    }

    // Vendor Market - Get published products
    public function getPublishedProducts()
    {
        $products = Product::with(['shop.vendor'])
            ->where('prod_publish', 'posted')
            ->where('prod_stock', '>', 0)
            ->get();
            
        return response()->json($products);
    }

    // Budget Approval
    public function getBudgetApprovals()
    {
        $approvals = BudgetApproval::with(['order'])->get();
        return response()->json($approvals);
    }

    public function updateBudgetApproval(Request $request, $id)
    {
        $approval = BudgetApproval::findOrFail($id);
        
        $request->validate([
            'bud_status' => 'required|in:approved,rejected,pending',
            'approver_user_id' => 'nullable|integer'
        ]);

        if ($request->bud_status === 'approved') {
            $request->merge(['approved_at' => now()]);
        }

        $approval->update($request->all());

        // Update corresponding order status if approved
        if ($approval->entity_type === 'order' && $request->bud_status === 'approved') {
            $order = Order::find($approval->entity_id);
            if ($order) {
                $order->update(['budget_approval_status' => 'approved']);
            }
        }

        return response()->json($approval);
    }

    // Reorder Management
    public function getReorders()
    {
        $reorders = Reorder::with(['order', 'shop', 'product'])->get();
        return response()->json($reorders);
    }

    public function createReorder(Request $request)
    {
        $request->validate([
            'order_id' => 'nullable|exists:psm_orders,order_id',
            'shop_id' => 'required|exists:psm_shops,shop_id',
            'prod_id' => 'required|exists:psm_products,prod_id',
            'quantity' => 'required|integer|min:1',
            'restock_price' => 'required|numeric|min:0',
            'sws_stock_id' => 'required|integer'
        ]);

        $reorder = Reorder::create($request->all());
        
        // Create budget approval for reorder
        BudgetApproval::create([
            'entity_type' => 'restock',
            'entity_id' => $reorder->restock_id,
            'bud_name' => 'Restock #' . $reorder->restock_id,
            'quantity' => $reorder->quantity,
            'unit_price' => $reorder->restock_price,
            'total_budget' => $reorder->total_amount,
            'bud_desc' => $reorder->restock_desc,
            'bud_status' => 'pending'
        ]);

        return response()->json($reorder, 201);
    }
}