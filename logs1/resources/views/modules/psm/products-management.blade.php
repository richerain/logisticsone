@extends('layouts.app')

@section('title', 'Products Management')

@section('content')
    <div class="module-content bg-white rounded-xl p-6 shadow block">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Products Management</h2>
            <button class="btn btn-primary" onclick="openAddProductModal()">
                <i class="bx bx-plus mr-2"></i>Add Product
            </button>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="stat bg-base-100 rounded-lg">
                <div class="stat-figure text-primary">
                    <i class="bx bx-package text-3xl"></i>
                </div>
                <div class="stat-title">Total Products</div>
                <div class="stat-value text-primary" id="total-products">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg">
                <div class="stat-figure text-success">
                    <i class="bx bx-check-circle text-3xl"></i>
                </div>
                <div class="stat-title">In Stock</div>
                <div class="stat-value text-success" id="instock-products">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg">
                <div class="stat-figure text-warning">
                    <i class="bx bx-error-circle text-3xl"></i>
                </div>
                <div class="stat-title">Low Stock</div>
                <div class="stat-value text-warning" id="lowstock-products">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg">
                <div class="stat-figure text-error">
                    <i class="bx bx-x-circle text-3xl"></i>
                </div>
                <div class="stat-title">Out of Stock</div>
                <div class="stat-value text-error" id="outofstock-products">0</div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="flex flex-wrap gap-4 mb-6">
            <div class="form-control">
                <input type="text" placeholder="Search products..." class="input input-bordered w-64" id="searchProducts">
            </div>
            <div class="form-control">
                <select class="select select-bordered" id="categoryFilter">
                    <option value="">All Categories</option>
                    <!-- Categories will be populated via JavaScript -->
                </select>
            </div>
            <div class="form-control">
                <select class="select select-bordered" id="statusFilter">
                    <option value="">All Status</option>
                    <option value="onstock">In Stock</option>
                    <option value="lowstock">Low Stock</option>
                    <option value="outofstock">Out of Stock</option>
                </select>
            </div>
            <div class="form-control">
                <select class="select select-bordered" id="publishFilter">
                    <option value="">All Visibility</option>
                    <option value="posted">Posted</option>
                    <option value="not posted">Not Posted</option>
                </select>
            </div>
        </div>

        <!-- Products Table -->
        <div class="overflow-x-auto bg-base-100 rounded-lg">
            <table class="table table-zebra w-full">
                <thead>
                    <tr class="bg-base-200">
                        <th>Product</th>
                        <th>Category</th>
                        <th>Shop</th>
                        <th>Vendor</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Visibility</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="products-table-body">
                    <tr>
                        <td colspan="9" class="text-center py-8">
                            <div class="loading loading-spinner loading-lg"></div>
                            <p class="text-gray-500 mt-2">Loading products...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Product Modal -->
    <div id="addProductModal" class="modal">
        <div class="modal-box max-w-2xl">
            <h3 class="font-bold text-lg mb-4">Add New Product</h3>
            <form id="addProductForm" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Product Name</span>
                        </label>
                        <input type="text" name="prod_name" class="input input-bordered w-full" required>
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Shop</span>
                        </label>
                        <select name="shop_id" class="select select-bordered w-full" required>
                            <option value="">Select Shop</option>
                            <!-- Shops will be populated via JavaScript -->
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Category</span>
                        </label>
                        <input type="text" name="prod_category" class="input input-bordered w-full" placeholder="e.g., Office Supplies">
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Price</span>
                        </label>
                        <input type="number" name="prod_price" step="0.01" class="input input-bordered w-full" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Stock Quantity</span>
                        </label>
                        <input type="number" name="prod_stock" class="input input-bordered w-full" value="0">
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Visibility</span>
                        </label>
                        <select name="prod_publish" class="select select-bordered w-full">
                            <option value="not posted">Not Posted</option>
                            <option value="posted">Posted to Market</option>
                        </select>
                    </div>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Image URL</span>
                    </label>
                    <input type="url" name="prod_img" class="input input-bordered w-full" placeholder="https://example.com/image.jpg">
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Description</span>
                    </label>
                    <textarea name="prod_desc" class="textarea textarea-bordered h-24" placeholder="Product description..."></textarea>
                </div>

                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" onclick="closeAddProductModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save mr-2"></i>Save Product
                    </button>
                </div>
            </form>
        </div>
    </div>

<script>
    // ==================== CONFIGURATION ====================
    const API_BASE_URL = 'http://localhost:8001/api/psm';

    let products = [];
    let shops = [];

    document.addEventListener('DOMContentLoaded', function() {
        loadProducts();
        loadShops();
        setupFilters();
    });

    async function loadProducts() {
        try {
            const response = await fetch(`${API_BASE_URL}/products`);
            const data = await response.json();
            
            if (response.ok) {
                products = data;
                renderProducts(products);
                updateProductStats();
                populateCategoryFilter();
            } else {
                throw new Error(data.message || 'Failed to load products');
            }
        } catch (error) {
            console.error('Error loading products:', error);
            Swal.fire('Error', 'Failed to load products: ' + error.message, 'error');
        }
    }

    async function loadShops() {
        try {
            const response = await fetch(`${API_BASE_URL}/shops`);
            if (response.ok) {
                shops = await response.json();
                populateShopDropdown();
            }
        } catch (error) {
            console.error('Error loading shops:', error);
        }
    }

    function renderProducts(productsToRender) {
        const tbody = document.getElementById('products-table-body');
        
        if (productsToRender.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="9" class="text-center py-8">
                        <i class="bx bx-package text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500">No products found</p>
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = productsToRender.map(product => `
            <tr>
                <td>
                    <div class="flex items-center space-x-3">
                        <div class="avatar">
                            <div class="mask mask-squircle w-12 h-12">
                                <img src="${product.prod_img || '/images/placeholder-product.jpg'}" 
                                     alt="${product.prod_name}" />
                            </div>
                        </div>
                        <div>
                            <div class="font-bold">${product.prod_name}</div>
                        </div>
                    </div>
                </td>
                <td>${product.prod_category || 'Uncategorized'}</td>
                <td>${product.shop?.shop_name || 'N/A'}</td>
                <td>${product.shop?.vendor?.ven_name || 'N/A'}</td>
                <td class="font-semibold">₱${parseFloat(product.prod_price).toFixed(2)}</td>
                <td>
                    <div class="flex items-center space-x-2">
                        <span>${product.prod_stock}</span>
                        ${product.prod_stock <= 10 ? '<i class="bx bx-error text-warning"></i>' : ''}
                    </div>
                </td>
                <td>
                    <span class="badge ${getStockStatusBadgeClass(product.prod_stock_status)}">
                        ${product.prod_stock_status}
                    </span>
                </td>
                <td>
                    <span class="badge ${product.prod_publish === 'posted' ? 'badge-success' : 'badge-ghost'}">
                        ${product.prod_publish}
                    </span>
                </td>
                <td>
                    <div class="flex space-x-1">
                        <button class="btn btn-xs btn-outline btn-info" onclick="viewProductDetails(${product.prod_id})">
                            <i class="bx bx-show"></i>
                        </button>
                        <button class="btn btn-xs btn-outline btn-warning" onclick="editProduct(${product.prod_id})">
                            <i class="bx bx-edit"></i>
                        </button>
                        <button class="btn btn-xs btn-outline btn-error" onclick="deleteProduct(${product.prod_id})">
                            <i class="bx bx-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    }

    function getStockStatusBadgeClass(status) {
        switch(status) {
            case 'onstock': return 'badge-success';
            case 'lowstock': return 'badge-warning';
            case 'outofstock': return 'badge-error';
            default: return 'badge-ghost';
        }
    }

    function updateProductStats() {
        document.getElementById('total-products').textContent = products.length;
        document.getElementById('instock-products').textContent = 
            products.filter(p => p.prod_stock_status === 'onstock').length;
        document.getElementById('lowstock-products').textContent = 
            products.filter(p => p.prod_stock_status === 'lowstock').length;
        document.getElementById('outofstock-products').textContent = 
            products.filter(p => p.prod_stock_status === 'outofstock').length;
    }

    function populateCategoryFilter() {
        const categories = [...new Set(products.map(p => p.prod_category).filter(Boolean))];
        const select = document.getElementById('categoryFilter');
        select.innerHTML = '<option value="">All Categories</option>' +
            categories.map(cat => `<option value="${cat}">${cat}</option>`).join('');
    }

    function populateShopDropdown() {
        const select = document.querySelector('select[name="shop_id"]');
        select.innerHTML = '<option value="">Select Shop</option>' +
            shops.map(shop => `
                <option value="${shop.shop_id}">
                    ${shop.shop_name} - ${shop.vendor?.ven_name || 'N/A'}
                </option>
            `).join('');
    }

    function setupFilters() {
        document.getElementById('searchProducts').addEventListener('input', filterProducts);
        document.getElementById('categoryFilter').addEventListener('change', filterProducts);
        document.getElementById('statusFilter').addEventListener('change', filterProducts);
        document.getElementById('publishFilter').addEventListener('change', filterProducts);
    }

    function filterProducts() {
        const searchTerm = document.getElementById('searchProducts').value.toLowerCase();
        const category = document.getElementById('categoryFilter').value;
        const status = document.getElementById('statusFilter').value;
        const publish = document.getElementById('publishFilter').value;

        let filtered = products.filter(product => {
            const matchesSearch = !searchTerm || 
                product.prod_name.toLowerCase().includes(searchTerm) ||
                product.prod_desc?.toLowerCase().includes(searchTerm) ||
                product.shop?.shop_name.toLowerCase().includes(searchTerm);

            const matchesCategory = !category || product.prod_category === category;
            const matchesStatus = !status || product.prod_stock_status === status;
            const matchesPublish = !publish || product.prod_publish === publish;

            return matchesSearch && matchesCategory && matchesStatus && matchesPublish;
        });

        renderProducts(filtered);
    }

    function viewProductDetails(productId) {
        const product = products.find(p => p.prod_id === productId);
        if (!product) return;

        Swal.fire({
            title: product.prod_name,
            html: `
                <div class="text-left space-y-2">
                    <div class="flex justify-center mb-4">
                        <img src="${product.prod_img || '/images/placeholder-product.jpg'}" 
                             alt="${product.prod_name}" class="w-32 h-32 object-cover rounded-lg">
                    </div>
                    <p><strong>Category:</strong> ${product.prod_category || 'Uncategorized'}</p>
                    <p><strong>Shop:</strong> ${product.shop?.shop_name || 'N/A'}</p>
                    <p><strong>Vendor:</strong> ${product.shop?.vendor?.ven_name || 'N/A'}</p>
                    <p><strong>Price:</strong> ₱${parseFloat(product.prod_price).toFixed(2)}</p>
                    <p><strong>Stock:</strong> ${product.prod_stock}</p>
                    <p><strong>Stock Status:</strong> <span class="badge ${getStockStatusBadgeClass(product.prod_stock_status)}">${product.prod_stock_status}</span></p>
                    <p><strong>Visibility:</strong> <span class="badge ${product.prod_publish === 'posted' ? 'badge-success' : 'badge-ghost'}">${product.prod_publish}</span></p>
                    <p><strong>Description:</strong> ${product.prod_desc || 'No description'}</p>
                    <p><strong>Created:</strong> ${new Date(product.created_at).toLocaleString()}</p>
                </div>
            `,
            icon: 'info',
            confirmButtonText: 'Close',
            width: 600
        });
    }

    function openAddProductModal() {
        document.getElementById('addProductModal').classList.add('modal-open');
    }

    function closeAddProductModal() {
        document.getElementById('addProductModal').classList.remove('modal-open');
        document.getElementById('addProductForm').reset();
    }

    document.getElementById('addProductForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const productData = Object.fromEntries(formData);
        
        // Convert numeric fields
        productData.prod_price = parseFloat(productData.prod_price);
        productData.prod_stock = parseInt(productData.prod_stock);

        try {
            const response = await fetch(`${API_BASE_URL}/products`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(productData)
            });

            const result = await response.json();

            if (response.ok) {
                Swal.fire('Success', 'Product added successfully!', 'success');
                closeAddProductModal();
                loadProducts();
            } else {
                throw new Error(result.message || 'Failed to add product');
            }
        } catch (error) {
            Swal.fire('Error', 'Failed to add product: ' + error.message, 'error');
        }
    });

    function editProduct(productId) {
        Swal.fire('Info', 'Edit functionality will be implemented soon!', 'info');
    }

    async function deleteProduct(productId) {
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!'
        });

        if (result.isConfirmed) {
            try {
                const response = await fetch(`${API_BASE_URL}/products/${productId}`, {
                    method: 'DELETE'
                });

                if (response.ok) {
                    Swal.fire('Deleted!', 'Product has been deleted.', 'success');
                    loadProducts();
                } else {
                    throw new Error('Failed to delete product');
                }
            } catch (error) {
                Swal.fire('Error', 'Failed to delete product: ' + error.message, 'error');
            }
        }
    }
</script>
@endsection