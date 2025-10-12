<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vendor;
use App\Models\Shop;
use App\Models\Product;
use App\Models\Order;
use App\Models\Reorder;
use App\Models\BudgetApproval;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PSMController extends Controller
{
    // Vendor Management
    public function getVendors(Request $request)
    {
        try {
            $vendors = Vendor::with('shops')->get();
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
            $vendor = Vendor::create($request->all());
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

    // Shop Management
    public function getShops(Request $request)
    {
        try {
            $shops = Shop::with(['vendor', 'products'])->get();
            return response()->json([
                'success' => true,
                'data' => $shops
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch shops: ' . $e->getMessage()
            ], 500);
        }
    }

    public function createShop(Request $request)
    {
        $request->validate([
            'shop_name' => 'required|string|max:255',
            'ven_id' => 'required|exists:psm_vendors,ven_id',
            'shop_status' => 'sometimes|in:active,inactive,maintenance'
        ]);

        try {
            $shop = Shop::create($request->all());
            return response()->json([
                'success' => true,
                'message' => 'Shop created successfully',
                'data' => $shop
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create shop: ' . $e->getMessage()
            ], 500);
        }
    }

    // Product Management
    public function getProducts(Request $request)
    {
        try {
            $products = Product::with(['shop.vendor'])->get();
            
            // Add full image URLs and default image
            $products->transform(function ($product) {
                $product->prod_img = $this->getProductImageUrl($product->prod_img);
                return $product;
            });

            return response()->json([
                'success' => true,
                'data' => $products
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching products: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch products: ' . $e->getMessage()
            ], 500);
        }
    }

    public function createProduct(Request $request)
    {
        Log::info('Create product request received', $request->all());
        
        $request->validate([
            'shop_id' => 'required|exists:psm_shops,shop_id',
            'prod_name' => 'required|string|max:255',
            'prod_category' => 'nullable|string|max:100',
            'prod_stock' => 'nullable|integer|min:0',
            'prod_price' => 'required|numeric|min:0',
            'prod_desc' => 'nullable|string',
            'prod_publish' => 'sometimes|in:posted,not posted',
            'prod_img' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
        ]);

        try {
            $productData = $request->except(['prod_img']);

            // Handle image upload
            if ($request->hasFile('prod_img')) {
                $image = $request->file('prod_img');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('products', $imageName, 'public');
                $productData['prod_img'] = 'storage/' . $imagePath;
                Log::info('Image uploaded: ' . $productData['prod_img']);
            } else {
                // No image uploaded - product will use default image
                $productData['prod_img'] = null;
            }

            $product = Product::create($productData);
            
            // Update stock status based on quantity
            $this->updateProductStockStatus($product);
            
            // Update shop product count
            $shop = Shop::find($request->shop_id);
            $shop->update([
                'shop_prods' => $shop->products()->count()
            ]);

            // Add full image URL to response
            $product->prod_img = $this->getProductImageUrl($product->prod_img);

            return response()->json([
                'success' => true,
                'message' => 'Product created successfully',
                'data' => $product
            ], 201);
        } catch (\Exception $e) {
            Log::error('Error creating product: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to create product: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateProduct(Request $request, $id)
    {
        Log::info('Update product request received', ['id' => $id, 'data' => $request->all()]);
        
        try {
            $product = Product::findOrFail($id);
            
            $request->validate([
                'prod_name' => 'sometimes|required|string|max:255',
                'prod_category' => 'nullable|string|max:100',
                'prod_stock' => 'nullable|integer|min:0',
                'prod_price' => 'sometimes|required|numeric|min:0',
                'prod_desc' => 'nullable|string',
                'prod_publish' => 'sometimes|in:posted,not posted',
                'prod_img' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048'
            ]);

            $updateData = $request->except(['prod_img', '_method']);

            // Handle image upload for update
            if ($request->hasFile('prod_img')) {
                Log::info('Processing image upload for product update');
                
                // Delete old image if exists and it's not the default image
                if ($product->prod_img && $product->prod_img !== 'default' && Storage::disk('public')->exists(str_replace('storage/', '', $product->prod_img))) {
                    Storage::disk('public')->delete(str_replace('storage/', '', $product->prod_img));
                }
                
                $image = $request->file('prod_img');
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $imagePath = $image->storeAs('products', $imageName, 'public');
                $updateData['prod_img'] = 'storage/' . $imagePath;
                Log::info('New image uploaded: ' . $updateData['prod_img']);
            } else {
                // Keep existing image if no new image uploaded
                $updateData['prod_img'] = $product->prod_img;
            }

            $product->update($updateData);
            
            // Update stock status based on quantity
            $this->updateProductStockStatus($product);
            
            // Add full image URL to response
            $product->prod_img = $this->getProductImageUrl($product->prod_img);

            return response()->json([
                'success' => true,
                'message' => 'Product updated successfully',
                'data' => $product
            ]);
        } catch (\Exception $e) {
            Log::error('Error updating product: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to update product: ' . $e->getMessage()
            ], 500);
        }
    }

    public function deleteProduct($id)
    {
        try {
            $product = Product::findOrFail($id);
            
            // Delete image file if exists and it's not the default image
            if ($product->prod_img && $product->prod_img !== 'default' && Storage::disk('public')->exists(str_replace('storage/', '', $product->prod_img))) {
                Storage::disk('public')->delete(str_replace('storage/', '', $product->prod_img));
            }
            
            $shop_id = $product->shop_id;
            $product->delete();
            
            // Update shop product count
            $shop = Shop::find($shop_id);
            if ($shop) {
                $shop->update([
                    'shop_prods' => $shop->products()->count()
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully'
            ]);
        } catch (\Exception $e) {
            Log::error('Error deleting product: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete product: ' . $e->getMessage()
            ], 500);
        }
    }

    // Helper method to get product image URL
    private function getProductImageUrl($imagePath)
    {
        // If no image is set, return default image URL
        if (!$imagePath) {
            return url('images/def-prod.png');
        }
        
        // If it's already a full URL, return as is
        if (strpos($imagePath, 'http') === 0) {
            return $imagePath;
        }
        
        // If it's a stored image, return full URL
        if (strpos($imagePath, 'storage/') === 0) {
            $cleanPath = str_replace('storage/', '', $imagePath);
            return url('storage/' . $cleanPath);
        }
        
        // Default fallback
        return url('images/def-prod.png');
    }

    // Helper method to update product stock status
    private function updateProductStockStatus(Product $product)
    {
        $stock = $product->prod_stock;
        
        if ($stock <= 0) {
            $status = 'outofstock';
        } elseif ($stock <= 10) {
            $status = 'lowstock';
        } else {
            $status = 'onstock';
        }
        
        $product->update(['prod_stock_status' => $status]);
    }

    // Order Management
    public function getOrders(Request $request)
    {
        try {
            $orders = Order::with(['shop.vendor', 'product', 'budgetApprovals'])->get();
            return response()->json([
                'success' => true,
                'data' => $orders
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch orders: ' . $e->getMessage()
            ], 500);
        }
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

        try {
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

            return response()->json([
                'success' => true,
                'message' => 'Order created successfully',
                'data' => $order
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create order: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateOrder(Request $request, $id)
    {
        try {
            $order = Order::findOrFail($id);
            
            $request->validate([
                'quantity' => 'sometimes|required|integer|min:1',
                'order_price' => 'sometimes|required|numeric|min:0',
                'order_desc' => 'nullable|string',
                'order_status' => 'sometimes|in:pending,issued,received,settled,cancelled'
            ]);

            $order->update($request->all());
            
            return response()->json([
                'success' => true,
                'message' => 'Order updated successfully',
                'data' => $order
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update order: ' . $e->getMessage()
            ], 500);
        }
    }

    // Vendor Market - Get published products
    public function getPublishedProducts(Request $request)
    {
        try {
            $products = Product::with(['shop.vendor'])
                ->where('prod_publish', 'posted')
                ->where('prod_stock', '>', 0)
                ->get();
                
            // Add full image URLs
            $products->transform(function ($product) {
                $product->prod_img = $this->getProductImageUrl($product->prod_img);
                return $product;
            });

            return response()->json([
                'success' => true,
                'data' => $products
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch published products: ' . $e->getMessage()
            ], 500);
        }
    }

    // Budget Approval
    public function getBudgetApprovals(Request $request)
    {
        try {
            $approvals = BudgetApproval::all();
            return response()->json([
                'success' => true,
                'data' => $approvals
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch budget approvals: ' . $e->getMessage()
            ], 500);
        }
    }

    public function updateBudgetApproval(Request $request, $id)
    {
        try {
            $approval = BudgetApproval::findOrFail($id);
            
            $request->validate([
                'bud_status' => 'required|in:approved,rejected,pending',
                'approver_user_id' => 'nullable|integer',
                'bud_desc' => 'nullable|string'
            ]);

            $updateData = $request->all();

            // Set approved_at timestamp if status is approved
            if ($request->bud_status === 'approved') {
                $updateData['approved_at'] = now();
            }

            $approval->update($updateData);

            // Update corresponding entity status if approved
            if ($approval->entity_type === 'order' && $request->bud_status === 'approved') {
                $order = Order::find($approval->entity_id);
                if ($order) {
                    $order->update(['budget_approval_status' => 'approved']);
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Budget approval updated successfully',
                'data' => $approval
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update budget approval: ' . $e->getMessage()
            ], 500);
        }
    }

    // Reorder Management
    public function getReorders(Request $request)
    {
        try {
            $reorders = Reorder::with(['order', 'shop', 'product'])->get();
            return response()->json([
                'success' => true,
                'data' => $reorders
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch reorders: ' . $e->getMessage()
            ], 500);
        }
    }

    public function createReorder(Request $request)
    {
        $request->validate([
            'order_id' => 'nullable|exists:psm_orders,order_id',
            'shop_id' => 'required|exists:psm_shops,shop_id',
            'prod_id' => 'required|exists:psm_products,prod_id',
            'quantity' => 'required|integer|min:1',
            'restock_price' => 'required|numeric|min:0',
            'sws_stock_id' => 'required|integer',
            'restock_desc' => 'nullable|string'
        ]);

        try {
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

            return response()->json([
                'success' => true,
                'message' => 'Reorder created successfully',
                'data' => $reorder
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create reorder: ' . $e->getMessage()
            ], 500);
        }
    }
}