@extends('layouts.app')

@section('title', 'Vendor Market')

@section('content')
    <div class="module-content bg-white rounded-xl p-6 shadow block">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Vendor Market</h2>
            <div class="form-control">
                <input type="text" placeholder="Search products..." class="input input-bordered w-64" id="searchProducts">
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="products-grid">
            <div class="col-span-full text-center py-8">
                <div class="loading loading-spinner loading-lg"></div>
                <p class="text-gray-500 mt-2">Loading products...</p>
            </div>
        </div>
    </div>

    <!-- Order Modal -->
    <div id="orderModal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Place Order</h3>
            <form id="orderForm" class="space-y-4">
                <input type="hidden" id="orderProductId">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Product</span>
                    </label>
                    <input type="text" id="orderProductName" class="input input-bordered w-full" readonly>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Price</span>
                    </label>
                    <input type="text" id="orderProductPrice" class="input input-bordered w-full" readonly>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Available Stock</span>
                    </label>
                    <input type="text" id="orderProductStock" class="input input-bordered w-full" readonly>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Quantity</span>
                    </label>
                    <input type="number" name="quantity" id="orderQuantity" class="input input-bordered w-full" 
                           min="1" value="1" required>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Description</span>
                    </label>
                    <textarea name="order_desc" class="textarea textarea-bordered h-20" placeholder="Order notes..."></textarea>
                </div>
                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" onclick="closeOrderModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-cart mr-2"></i>Place Order
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // ==================== CONFIGURATION ====================
        const API_BASE_URL = 'http://localhost:8001/api/psm';
        
        let products = [];

        document.addEventListener('DOMContentLoaded', function() {
            loadProducts();
            
            // Search functionality
            document.getElementById('searchProducts').addEventListener('input', function(e) {
                filterProducts(e.target.value);
            });
        });

        async function loadProducts() {
            try {
                const response = await fetch(`${API_BASE_URL}/market/products`);
                const data = await response.json();
                
                if (response.ok) {
                    products = data;
                    renderProducts(products);
                } else {
                    throw new Error(data.message || 'Failed to load products');
                }
            } catch (error) {
                console.error('Error loading products:', error);
                Swal.fire('Error', 'Failed to load products: ' + error.message, 'error');
            }
        }

        function renderProducts(productsToRender) {
            const grid = document.getElementById('products-grid');
            
            if (productsToRender.length === 0) {
                grid.innerHTML = `
                    <div class="col-span-full text-center py-12">
                        <i class="bx bx-search-alt text-6xl text-gray-400 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">No products found</h3>
                        <p class="text-gray-500">Try adjusting your search criteria</p>
                    </div>
                `;
                return;
            }

            grid.innerHTML = productsToRender.map(product => `
                <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-shadow duration-300">
                    <figure class="px-4 pt-4">
                        <img src="${product.prod_img || '/images/placeholder-product.jpg'}" 
                             alt="${product.prod_name}" 
                             class="rounded-xl h-48 w-full object-cover">
                    </figure>
                    <div class="card-body">
                        <h3 class="card-title text-lg">${product.prod_name}</h3>
                        <p class="text-gray-600 text-sm">${product.prod_category || 'Uncategorized'}</p>
                        <p class="text-gray-500 text-sm line-clamp-2">${product.prod_desc || 'No description'}</p>
                        
                        <div class="flex justify-between items-center mt-2">
                            <span class="text-2xl font-bold text-primary">₱${parseFloat(product.prod_price).toFixed(2)}</span>
                            <span class="badge ${getStockBadgeClass(product.prod_stock_status)}">
                                ${product.prod_stock_status}
                            </span>
                        </div>
                        
                        <div class="flex justify-between items-center text-sm text-gray-500">
                            <span>Stock: ${product.prod_stock}</span>
                            <span>${product.shop?.shop_name || 'Unknown Shop'}</span>
                        </div>
                        
                        <div class="card-actions justify-end mt-4">
                            <button class="btn btn-primary btn-sm flex-1" 
                                    onclick="openOrderModal(${product.prod_id})"
                                    ${product.prod_stock <= 0 ? 'disabled' : ''}>
                                <i class="bx bx-cart mr-1"></i>
                                ${product.prod_stock <= 0 ? 'Out of Stock' : 'Order Now'}
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        function getStockBadgeClass(stockStatus) {
            switch(stockStatus) {
                case 'onstock': return 'badge-success';
                case 'lowstock': return 'badge-warning';
                case 'outofstock': return 'badge-error';
                default: return 'badge-ghost';
            }
        }

        function filterProducts(searchTerm) {
            const filtered = products.filter(product => 
                product.prod_name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                product.prod_category?.toLowerCase().includes(searchTerm.toLowerCase()) ||
                product.shop?.shop_name.toLowerCase().includes(searchTerm.toLowerCase())
            );
            renderProducts(filtered);
        }

        function openOrderModal(productId) {
            const product = products.find(p => p.prod_id === productId);
            if (!product) return;

            document.getElementById('orderProductId').value = product.prod_id;
            document.getElementById('orderProductName').value = product.prod_name;
            document.getElementById('orderProductPrice').value = `₱${parseFloat(product.prod_price).toFixed(2)}`;
            document.getElementById('orderProductStock').value = product.prod_stock;
            document.getElementById('orderQuantity').max = product.prod_stock;
            document.getElementById('orderQuantity').value = 1;

            document.getElementById('orderModal').classList.add('modal-open');
        }

        function closeOrderModal() {
            document.getElementById('orderModal').classList.remove('modal-open');
            document.getElementById('orderForm').reset();
        }

        document.getElementById('orderForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const productId = document.getElementById('orderProductId').value;
            const product = products.find(p => p.prod_id == productId);
            
            const orderData = {
                shop_id: product.shop_id,
                prod_id: productId,
                quantity: parseInt(formData.get('quantity')),
                order_price: parseFloat(product.prod_price),
                order_desc: formData.get('order_desc')
            };

            try {
                const response = await fetch(`${API_BASE_URL}/orders`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(orderData)
                });

                const result = await response.json();

                if (response.ok) {
                    Swal.fire('Success', 'Order placed successfully!', 'success');
                    closeOrderModal();
                    loadProducts(); // Refresh products to update stock
                } else {
                    throw new Error(result.message || 'Failed to place order');
                }
            } catch (error) {
                Swal.fire('Error', 'Failed to place order: ' + error.message, 'error');
            }
        });
    </script>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
@endsection