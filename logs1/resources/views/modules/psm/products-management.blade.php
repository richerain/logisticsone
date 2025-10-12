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
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-primary">
                <div class="stat-figure text-primary">
                    <i class="bx bx-package text-3xl"></i>
                </div>
                <div class="stat-title">Total Products</div>
                <div class="stat-value text-primary" id="total-products">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-success">
                <div class="stat-figure text-success">
                    <i class="bx bx-check-circle text-3xl"></i>
                </div>
                <div class="stat-title">In Stock</div>
                <div class="stat-value text-success" id="instock-products">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-warning">
                <div class="stat-figure text-warning">
                    <i class="bx bx-error-circle text-3xl"></i>
                </div>
                <div class="stat-title">Low Stock</div>
                <div class="stat-value text-warning" id="lowstock-products">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-error">
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
            <form id="addProductForm" class="space-y-4" enctype="multipart/form-data">
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
                        <select name="prod_category" class="select select-bordered w-full">
                            <option value="">Select Category</option>
                            <option value="Document">Document</option>
                            <option value="Supplies">Supplies</option>
                            <option value="Equipment">Equipment</option>
                            <option value="Furniture">Furniture</option>
                        </select>
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Price</span>
                        </label>
                        <input type="number" name="prod_price" step="0.01" class="input input-bordered w-full" placeholder="₱0.00" required>
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
                        <span class="label-text font-semibold">Product Image</span>
                    </label>
                    <input type="file" name="prod_img" class="file-input file-input-bordered w-full" accept="image/jpeg,image/png,image/gif,image/webp">
                    <label class="label">
                        <span class="label-text-alt text-gray-500">Upload product image (JPG, PNG, GIF, WebP, max 2MB). Leave empty to use default image.</span>
                    </label>
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

    <!-- Edit Product Modal -->
    <div id="editProductModal" class="modal">
        <div class="modal-box max-w-2xl">
            <h3 class="font-bold text-lg mb-4">Edit Product</h3>
            <form id="editProductForm" class="space-y-4" enctype="multipart/form-data">
                <input type="hidden" id="editProductId" name="prod_id">
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Product Name</span>
                        </label>
                        <input type="text" id="editProductName" name="prod_name" class="input input-bordered w-full" required>
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Shop</span>
                        </label>
                        <select id="editShopId" name="shop_id" class="select select-bordered w-full" required>
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
                        <select id="editProductCategory" name="prod_category" class="select select-bordered w-full">
                            <option value="">Select Category</option>
                            <option value="Document">Document</option>
                            <option value="Supplies">Supplies</option>
                            <option value="Equipment">Equipment</option>
                            <option value="Furniture">Furniture</option>
                        </select>
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Price</span>
                        </label>
                        <input type="number" id="editProductPrice" name="prod_price" step="0.01" class="input input-bordered w-full" placeholder="₱0.00" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Stock Quantity</span>
                        </label>
                        <input type="number" id="editProductStock" name="prod_stock" class="input input-bordered w-full">
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Visibility</span>
                        </label>
                        <select id="editProductPublish" name="prod_publish" class="select select-bordered w-full">
                            <option value="not posted">Not Posted</option>
                            <option value="posted">Posted to Market</option>
                        </select>
                    </div>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Product Image</span>
                    </label>
                    <input type="file" name="prod_img" class="file-input file-input-bordered w-full" accept="image/jpeg,image/png,image/gif,image/webp">
                    <label class="label">
                        <span class="label-text-alt text-gray-500">Upload new image to replace current one. Leave empty to keep current image.</span>
                    </label>
                    <div id="currentImage" class="mt-2"></div>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Description</span>
                    </label>
                    <textarea id="editProductDesc" name="prod_desc" class="textarea textarea-bordered h-24" placeholder="Product description..."></textarea>
                </div>

                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" onclick="closeEditProductModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save mr-2"></i>Update Product
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
            showLoadingState();
            const response = await fetch(`${API_BASE_URL}/products`);
            const data = await response.json();
            
            if (response.ok && data.success) {
                products = data.data || [];
                renderProducts(products);
                updateProductStats();
                populateCategoryFilter();
            } else {
                throw new Error(data.message || 'Failed to load products');
            }
        } catch (error) {
            console.error('Error loading products:', error);
            showErrorState('Failed to load products: ' + error.message);
        }
    }

    function showLoadingState() {
        const tbody = document.getElementById('products-table-body');
        tbody.innerHTML = `
            <tr>
                <td colspan="9" class="text-center py-8">
                    <div class="loading loading-spinner loading-lg"></div>
                    <p class="text-gray-500 mt-2">Loading products...</p>
                </td>
            </tr>
        `;
    }

    function showErrorState(message) {
        const tbody = document.getElementById('products-table-body');
        tbody.innerHTML = `
            <tr>
                <td colspan="9" class="text-center py-8">
                    <i class="bx bx-error text-4xl text-red-400 mb-2"></i>
                    <p class="text-red-500">${message}</p>
                    <button class="btn btn-sm btn-outline mt-2" onclick="loadProducts()">Retry</button>
                </td>
            </tr>
        `;
        Swal.fire('Error', message, 'error');
    }

    async function loadShops() {
        try {
            const response = await fetch(`${API_BASE_URL}/shops`);
            const data = await response.json();
            
            if (response.ok && data.success) {
                // Filter only active shops
                shops = data.data.filter(shop => shop.shop_status === 'active') || [];
                populateShopDropdown();
                populateEditShopDropdown();
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
                                <img src="${product.prod_img}" 
                                     alt="${product.prod_name}" 
                                     class="object-cover" />
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
                <td class="font-semibold">₱${parseFloat(product.prod_price || 0).toFixed(2)}</td>
                <td>
                    <div class="flex items-center space-x-2">
                        <span>${product.prod_stock || 0}</span>
                        ${(product.prod_stock || 0) <= 10 ? '<i class="bx bx-error text-warning"></i>' : ''}
                    </div>
                </td>
                <td>
                    <span class="badge ${getStockStatusBadgeClass(product.prod_stock_status)}">
                        ${product.prod_stock_status || 'onstock'}
                    </span>
                </td>
                <td>
                    <span class="badge ${product.prod_publish === 'posted' ? 'badge-success' : 'badge-ghost'}">
                        ${product.prod_publish || 'not posted'}
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
        const select = document.querySelector('#addProductForm select[name="shop_id"]');
        select.innerHTML = '<option value="">Select Shop</option>' +
            shops.map(shop => `
                <option value="${shop.shop_id}">
                    ${shop.shop_name} - ${shop.vendor?.ven_name || 'N/A'}
                </option>
            `).join('');
    }

    function populateEditShopDropdown() {
        const select = document.querySelector('#editProductForm select[name="shop_id"]');
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
                (product.prod_desc && product.prod_desc.toLowerCase().includes(searchTerm)) ||
                (product.shop?.shop_name && product.shop.shop_name.toLowerCase().includes(searchTerm));

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
                <div class="text-left space-y-3">
                    <div class="flex justify-center mb-4">
                        <img src="${product.prod_img}" 
                             alt="${product.prod_name}" 
                             class="w-32 h-32 object-cover rounded-lg border">
                    </div>
                    <p><strong>Category:</strong> ${product.prod_category || 'Uncategorized'}</p>
                    <p><strong>Shop:</strong> ${product.shop?.shop_name || 'N/A'}</p>
                    <p><strong>Vendor:</strong> ${product.shop?.vendor?.ven_name || 'N/A'}</p>
                    <p><strong>Price:</strong> ₱${parseFloat(product.prod_price || 0).toFixed(2)}</p>
                    <p><strong>Stock:</strong> ${product.prod_stock || 0}</p>
                    <p><strong>Stock Status:</strong> <span class="badge ${getStockStatusBadgeClass(product.prod_stock_status)}">${product.prod_stock_status || 'onstock'}</span></p>
                    <p><strong>Visibility:</strong> <span class="badge ${product.prod_publish === 'posted' ? 'badge-success' : 'badge-ghost'}">${product.prod_publish || 'not posted'}</span></p>
                    <p><strong>Description:</strong> ${product.prod_desc || 'No description'}</p>
                    <p><strong>Created:</strong> ${product.created_at ? new Date(product.created_at).toLocaleString() : 'N/A'}</p>
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

    function openEditProductModal() {
        document.getElementById('editProductModal').classList.add('modal-open');
    }

    function closeEditProductModal() {
        document.getElementById('editProductModal').classList.remove('modal-open');
        document.getElementById('editProductForm').reset();
        document.getElementById('currentImage').innerHTML = '';
    }

    // Add Product Form Submission
    document.getElementById('addProductForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        try {
            const response = await fetch(`${API_BASE_URL}/products`, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (response.ok && result.success) {
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

    // Edit Product Form Submission - FIXED: Use PUT method directly
    document.getElementById('editProductForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const productId = document.getElementById('editProductId').value;
        
        try {
            const response = await fetch(`${API_BASE_URL}/products/${productId}`, {
                method: 'PUT', // Use PUT method directly
                body: formData // Don't set Content-Type for FormData
            });

            const result = await response.json();

            if (response.ok && result.success) {
                Swal.fire('Success', 'Product updated successfully!', 'success');
                closeEditProductModal();
                loadProducts();
            } else {
                throw new Error(result.message || 'Failed to update product');
            }
        } catch (error) {
            Swal.fire('Error', 'Failed to update product: ' + error.message, 'error');
        }
    });

    function editProduct(productId) {
        const product = products.find(p => p.prod_id === productId);
        if (!product) return;

        // Populate edit form with product data
        document.getElementById('editProductId').value = product.prod_id;
        document.getElementById('editProductName').value = product.prod_name || '';
        document.getElementById('editShopId').value = product.shop_id || '';
        document.getElementById('editProductCategory').value = product.prod_category || '';
        document.getElementById('editProductPrice').value = parseFloat(product.prod_price || 0).toFixed(2);
        document.getElementById('editProductStock').value = product.prod_stock || 0;
        document.getElementById('editProductDesc').value = product.prod_desc || '';
        document.getElementById('editProductPublish').value = product.prod_publish || 'not posted';

        // Show current image if exists
        const currentImageDiv = document.getElementById('currentImage');
        currentImageDiv.innerHTML = `
            <p class="text-sm text-gray-600 mb-1">Current Image:</p>
            <img src="${product.prod_img}" 
                 alt="${product.prod_name}" 
                 class="w-20 h-20 object-cover rounded-lg border">
        `;

        openEditProductModal();
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

                const result = await response.json();

                if (response.ok && result.success) {
                    Swal.fire('Deleted!', 'Product has been deleted.', 'success');
                    loadProducts();
                } else {
                    throw new Error(result.message || 'Failed to delete product');
                }
            } catch (error) {
                Swal.fire('Error', 'Failed to delete product: ' + error.message, 'error');
            }
        }
    }
</script>
@endsection