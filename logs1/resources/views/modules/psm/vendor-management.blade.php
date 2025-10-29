@extends('layouts.app')

@section('title', 'Vendor Management')

@section('content')
    <div class="module-content bg-white rounded-xl p-6 shadow block">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Vendor List</h2>
            <button class="hidden btn btn-primary" id="addVendorBtn">
                <i class="bx bx-plus mr-2"></i>Add New Vendor
            </button>            
        </div>

        <!-- Search and Filters -->
        <div class="flex gap-4 mb-6">
            <div class="form-control flex-1">
                <input type="text" placeholder="Search vendors..." class="input input-bordered w-full" id="searchVendors">
            </div>
            <select class="select select-bordered" id="statusFilter">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            <select class="select select-bordered" id="vendorTypeFilter">
                <option value="">All Types</option>
                <option value="Equipment">Equipment</option>
                <option value="Supplies">Supplies</option>
                <option value="Furniture">Furniture</option>
            </select>
        </div>

        <!-- Vendor Cards Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" id="vendors-cards-container">
            <!-- Loading State -->
            <div class="col-span-3 text-center py-12">
                <div class="loading loading-spinner loading-lg"></div>
                <p class="text-gray-500 mt-4">Loading vendors...</p>
            </div>
        </div>

        <!-- No Vendors State (hidden by default) -->
        <div id="no-vendors-state" class="hidden text-center py-12">
            <i class="bx bx-package text-6xl text-gray-400 mb-4"></i>
            <h3 class="text-xl font-semibold text-gray-600 mb-2">No Vendors Found</h3>
            <button class="hidden btn btn-primary" id="addFirstVendorBtn">
                Add First Vendor
            </button>
        </div>
    </div>

    <!-- Add/Edit Vendor Modal -->
    <div id="vendorModal" class="modal modal-xl">
        <div class="modal-box max-w-5xl p-0 overflow-visible">
            <div class="flex justify-between items-center bg-green-700 p-4 rounded-t-lg">
                <h3 class="font-bold text-white text-lg" id="vendorModalTitle">New Vendor</h3>
                <button class="btn btn-sm btn-circle btn-ghost hover:bg-white/20 text-white" id="closeVendorModalX">✕</button>
            </div>
            <div class="p-4">
                <form id="vendorForm" class="space-y-4">
                    @csrf
                    <input type="hidden" id="vendorId" name="ven_id">
                    
                    <!-- Vendor Information -->
                    <div class="space-y-3">
                        <!-- Row 1: Vendor Name, Email, Phone, Status -->
                        <div class="grid grid-cols-1 xl:grid-cols-4 gap-4">
                            <div class="form-control">
                                <label class="label py-1">
                                    <span class="label-text font-semibold text-gray-700 text-xs">Company Name *</span>
                                </label>
                                <input type="text" id="vendorName" name="ven_name" class="input input-bordered input-sm w-full focus:ring-2 focus:ring-primary/20" required>
                            </div>
                            
                            <div class="form-control">
                                <label class="label py-1">
                                    <span class="label-text font-semibold text-gray-700 text-xs">Email *</span>
                                </label>
                                <input type="email" id="vendorEmail" name="ven_email" class="input input-bordered input-sm w-full focus:ring-2 focus:ring-primary/20" required>
                            </div>
                            
                            <div class="form-control">
                                <label class="label py-1">
                                    <span class="label-text font-semibold text-gray-700 text-xs">Phone *</span>
                                </label>
                                <input type="text" id="vendorPhone" name="ven_contacts" class="input input-bordered input-sm w-full focus:ring-2 focus:ring-primary/20" required oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                            </div>

                            <div class="form-control">
                                <label class="label py-1">
                                    <span class="label-text font-semibold text-gray-700 text-xs">Status</span>
                                </label>
                                <select id="vendorStatus" name="ven_status" class="select select-bordered select-sm w-full focus:ring-2 focus:ring-primary/20">
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>
                        </div>

                        <!-- Row 2: Vendor Type, Owner, Rating -->
                        <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
                            <div class="form-control">
                                <label class="label py-1">
                                    <span class="label-text font-semibold text-gray-700 text-xs">Vendor Type *</span>
                                </label>
                                <select id="vendorType" name="vendor_type" class="select select-bordered select-sm w-full focus:ring-2 focus:ring-primary/20" required>
                                    <option value="Equipment">Equipment</option>
                                    <option value="Supplies" selected>Supplies</option>
                                    <option value="Furniture">Furniture</option>
                                </select>
                            </div>

                            <div class="form-control">
                                <label class="label py-1">
                                    <span class="label-text font-semibold text-gray-700 text-xs">Owner</span>
                                </label>
                                <input type="text" id="owner" name="owner" class="input input-bordered input-sm w-full focus:ring-2 focus:ring-primary/20" placeholder="Enter owner name">
                            </div>

                            <div class="form-control">
                                <label class="label py-1">
                                    <span class="label-text font-semibold text-gray-700 text-xs">Rating</span>
                                </label>
                                <div class="rating rating-sm space-x-1" id="starRating">
                                    <input type="radio" name="ven_rating" value="1" class="mask mask-star-2 bg-orange-400 hover:bg-orange-500 transition-colors size-6" />
                                    <input type="radio" name="ven_rating" value="2" class="mask mask-star-2 bg-orange-400 hover:bg-orange-500 transition-colors size-6" />
                                    <input type="radio" name="ven_rating" value="3" class="mask mask-star-2 bg-orange-400 hover:bg-orange-500 transition-colors size-6" />
                                    <input type="radio" name="ven_rating" value="4" class="mask mask-star-2 bg-orange-400 hover:bg-orange-500 transition-colors size-6" />
                                    <input type="radio" name="ven_rating" value="5" class="mask mask-star-2 bg-orange-400 hover:bg-orange-500 transition-colors size-6" checked />
                                </div>
                                <div class="text-xs text-gray-500 mt-1">Click on stars to set rating (1-5)</div>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="form-control">
                            <label class="label py-1">
                                <span class="label-text font-semibold text-gray-700 text-xs">Address</span>
                            </label>
                            <textarea id="vendorAddress" name="ven_address" class="textarea textarea-bordered textarea-sm h-16 w-full focus:ring-2 focus:ring-primary/20" placeholder="Complete vendor address"></textarea>
                        </div>
                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-action flex justify-end space-x-3 pt-4 border-t">
                        <button type="button" class="btn btn-ghost hover:bg-gray-100 transition-colors px-4" id="closeVendorModal">Cancel</button>
                        <button type="submit" class="btn btn-primary bg-gradient-to-r from-primary to-primary/90 hover:from-primary/90 hover:to-primary transition-all duration-300 shadow-lg px-4" id="vendorSubmitBtn">
                            <i class="bx bx-save mr-1"></i><span id="vendorModalSubmitText">Save Vendor</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Vendor Modal -->
    <div id="viewVendorModal" class="modal modal-xl">
        <div class="modal-box max-w-4xl p-0 overflow-visible">
            <div class="flex justify-between items-center bg-green-700 p-4 rounded-t-lg">
                <h3 class="font-bold text-white text-lg">Vendor Details</h3>
                <button class="btn btn-sm btn-circle btn-ghost hover:bg-white/20 text-white" id="closeViewVendorModalX">✕</button>
            </div>
            <div class="p-4">
                <div class="space-y-4" id="vendorDetails">
                    <!-- Vendor details will be populated here -->
                </div>
                <div class="modal-action flex justify-end pt-4 border-t">
                    <button type="button" class="btn btn-ghost hover:bg-gray-100 transition-colors px-4" id="closeViewVendorModal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Vendor Products Modal -->
    <div id="vendorProductsModal" class="modal modal-xl">
        <div class="modal-box max-w-6xl p-0 overflow-visible">
            <div class="flex justify-between items-center bg-blue-700 p-4 rounded-t-lg">
                <h3 class="font-bold text-white text-lg" id="vendorProductsModalTitle">Vendor Products</h3>
                <button class="btn btn-sm btn-circle btn-ghost hover:bg-white/20 text-white" id="closeVendorProductsModalX">✕</button>
            </div>
            <div class="p-4">
                <!-- Products Header -->
                <div class="flex justify-between items-center mb-6">
                    <div>
                        <h4 class="text-lg font-semibold text-gray-800" id="vendorProductsVendorName"></h4>
                        <p class="text-sm text-gray-600" id="vendorProductsVendorInfo"></p>
                    </div>
                    <button class="hidden btn btn-primary btn-sm" id="addProductBtn">
                        <i class="bx bx-plus mr-1"></i>Add New Product
                    </button>
                </div>

                <!-- Products Table -->
                <div class="overflow-x-auto bg-base-100 rounded-lg">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr class="bg-gray-900 text-white">
                                <th>Product ID</th>
                                <th>Product Name</th>
                                <th>Description</th>
                                <th>Price</th>
                                <th>Stock</th>
                                <th>Warranty</th>
                                <th>Expiration</th>
                                <th>Status</th>
                                <th class="hidden">Actions</th>
                            </tr>
                        </thead>
                        <tbody id="vendor-products-table-body">
                            <tr>
                                <td colspan="9" class="text-center py-8">
                                    <div class="loading loading-spinner loading-lg"></div>
                                    <p class="text-gray-500 mt-2">Loading products...</p>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- No Products State -->
                <div id="no-products-state" class="hidden text-center py-12">
                    <i class="bx bx-package text-4xl text-gray-400 mb-2"></i>
                    <p class="text-gray-500">No products found for this vendor.</p>
                    <button class="btn btn-sm btn-primary mt-4" id="addFirstProductBtn">
                        <i class="bx bx-plus mr-1"></i>Add First Product
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Product Modal -->
    <div id="productModal" class="modal modal-lg">
        <div class="modal-box max-w-3xl p-0 overflow-visible">
            <div class="flex justify-between items-center bg-blue-700 p-4 rounded-t-lg">
                <h3 class="font-bold text-white text-lg" id="productModalTitle">Add New Product</h3>
                <button class="btn btn-sm btn-circle btn-ghost hover:bg-white/20 text-white" id="closeProductModalX">✕</button>
            </div>
            <div class="p-4">
                <form id="productForm" class="space-y-4">
                    @csrf
                    <input type="hidden" id="productId" name="product_id">
                    <input type="hidden" id="productVendorId" name="ven_id">
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Product ID</span>
                            </label>
                            <input type="text" id="productCode" class="input input-bordered input-sm w-full bg-gray-100" 
                                   readonly placeholder="Auto-generated">
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Vendor</span>
                            </label>
                            <input type="text" id="productVendorName" class="input input-bordered input-sm w-full bg-gray-100" 
                                   readonly>
                        </div>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Product Name *</span>
                        </label>
                        <input type="text" id="productName" name="product_name" class="input input-bordered input-sm w-full" 
                               placeholder="Enter product name" required>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Description</span>
                        </label>
                        <textarea id="productDescription" name="product_description" class="textarea textarea-bordered textarea-sm h-16" 
                                  placeholder="Product description..."></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Price (₱) *</span>
                            </label>
                            <input type="number" id="productPrice" name="product_price" class="input input-bordered input-sm w-full" 
                                   min="0" step="0.01" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Stock *</span>
                            </label>
                            <input type="number" id="productStock" name="product_stock" class="input input-bordered input-sm w-full" 
                                   min="0" required>
                        </div>
                    </div>

                    <!-- New Warranty and Expiration Fields -->
                    <div class="grid grid-cols-3 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Warranty From</span>
                            </label>
                            <input type="date" id="warrantyFrom" name="warranty_from" class="input input-bordered input-sm w-full">
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Warranty To</span>
                            </label>
                            <input type="date" id="warrantyTo" name="warranty_to" class="input input-bordered input-sm w-full">
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Expiration Date</span>
                            </label>
                            <input type="date" id="expiration" name="expiration" class="input input-bordered input-sm w-full">
                        </div>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Status</span>
                        </label>
                        <select id="productStatus" name="product_status" class="select select-bordered select-sm w-full">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-action flex justify-end space-x-3 pt-4 border-t">
                        <button type="button" class="btn btn-ghost btn-sm hover:bg-gray-100 transition-colors px-4" id="closeProductModal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow-lg px-4" id="productSubmitBtn">
                            <i class="bx bx-save mr-1"></i><span id="productModalSubmitText">Save Product</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Loading Modal -->
    <div id="loadingModal" class="modal">
        <div class="modal-box max-w-sm text-center p-4">
            <div class="loading loading-spinner loading-lg text-primary mb-2"></div>
            <h3 class="font-bold text-sm mb-1" id="loadingTitle">Processing...</h3>
        </div>
    </div>

<script>
    let vendors = [];
    let currentVendorProducts = [];
    let currentVendorId = null;

    // ==================== CONFIGURATION ====================
    const API_BASE_URL = 'http://localhost:8001/api/psm';

    // Utility function to safely convert to number
    function safeNumber(value, defaultValue = 0) {
        if (value === null || value === undefined) return defaultValue;
        const num = parseFloat(value);
        return isNaN(num) ? defaultValue : num;
    }

    // Utility function to safely format rating
    function formatRating(rating) {
        const num = safeNumber(rating, 0);
        return num.toFixed(1);
    }

    // Format date for display (month-day-year)
    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        const date = new Date(dateString);
        return date.toLocaleDateString('en-US', {
            month: '2-digit',
            day: '2-digit',
            year: 'numeric'
        });
    }

    // Generate star rating display
    function generateStarRating(rating) {
        const numRating = safeNumber(rating, 0);
        let starsHTML = '';
        
        for (let i = 1; i <= 5; i++) {
            if (i <= numRating) {
                starsHTML += `<span class="text-orange-400 text-lg">★</span>`;
            } else {
                starsHTML += `<span class="text-gray-300 text-lg">★</span>`;
            }
        }
        
        return `<div class="flex items-center">
            <div class="flex space-x-1">${starsHTML}</div>
            <span class="ml-2 text-sm font-semibold">${formatRating(rating)}</span>
        </div>`;
    }

    // Format currency
    function formatCurrency(amount) {
        return '₱' + parseFloat(amount).toLocaleString('en-PH', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    // Show loading modal
    function showLoadingModal(title = 'Processing...', message = 'Please wait while we save your changes.') {
        document.getElementById('loadingTitle').textContent = title;
        document.getElementById('loadingModal').classList.add('modal-open');
    }

    // Hide loading modal
    function hideLoadingModal() {
        document.getElementById('loadingModal').classList.remove('modal-open');
    }

    // Show success toast
    function showSuccessToast(message) {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        Toast.fire({
            icon: 'success',
            title: message
        });
    }

    // Product Modal Functions (DEFINED FIRST)
    function openAddProductModal() {
        if (!currentVendorId) return;

        const vendor = vendors.find(v => v.ven_id === currentVendorId);
        if (!vendor) return;

        document.getElementById('productModalTitle').textContent = 'Add New Product';
        document.getElementById('productModalSubmitText').textContent = 'Save Product';
        document.getElementById('productForm').reset();
        document.getElementById('productId').value = '';
        document.getElementById('productVendorId').value = currentVendorId;
        document.getElementById('productVendorName').value = vendor.ven_name;
        document.getElementById('productCode').value = 'Auto-generated';
        document.getElementById('productStatus').value = 'active';
        
        // Clear date fields
        document.getElementById('warrantyFrom').value = '';
        document.getElementById('warrantyTo').value = '';
        document.getElementById('expiration').value = '';
        
        document.getElementById('productModal').classList.add('modal-open');
    }

    function closeProductModal() {
        document.getElementById('productModal').classList.remove('modal-open');
        document.getElementById('productForm').reset();
    }

    // Vendor Products Functions
    async function viewVendorProducts(vendorId, vendorName) {
        currentVendorId = vendorId;
        
        // Update modal title and vendor info
        document.getElementById('vendorProductsModalTitle').textContent = 'Vendor Products - ' + vendorName;
        
        // Show loading state
        document.getElementById('vendor-products-table-body').innerHTML = `
            <tr>
                <td colspan="9" class="text-center py-8">
                    <div class="loading loading-spinner loading-lg"></div>
                    <p class="text-gray-500 mt-2">Loading products...</p>
                </td>
            </tr>
        `;
        document.getElementById('no-products-state').classList.add('hidden');
        
        // Open modal
        document.getElementById('vendorProductsModal').classList.add('modal-open');
        
        // Load products
        await loadVendorProducts(vendorId);
    }

    async function loadVendorProducts(vendorId) {
        try {
            const response = await fetch(`${API_BASE_URL}/vendors/${vendorId}/products`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const result = await response.json();
            
            if (result.success) {
                currentVendorProducts = result.data || [];
                renderVendorProducts(currentVendorProducts);
            } else {
                throw new Error(result.message || 'Failed to load vendor products');
            }
        } catch (error) {
            console.error('Error loading vendor products:', error);
            showVendorProductsErrorState('Failed to load products: ' + error.message);
        }
    }

    function renderVendorProducts(productsData) {
        const tbody = document.getElementById('vendor-products-table-body');
        
        if (productsData.length === 0) {
            tbody.innerHTML = '';
            document.getElementById('no-products-state').classList.remove('hidden');
            return;
        }

        document.getElementById('no-products-state').classList.add('hidden');

        tbody.innerHTML = productsData.map(product => {
            const statusClass = product.product_status === 'active' ? 'bg-green-400' : 'bg-red-400';
            const stockStatus = product.product_stock <= 0 ? 'Out of Stock' : 
                              product.product_stock <= 10 ? 'Low Stock' : 'In Stock';
            const stockClass = product.product_stock <= 0 ? 'text-red-600 font-semibold' : 
                             product.product_stock <= 10 ? 'text-orange-600 font-semibold' : 'text-green-600 font-semibold';
            
            // Warranty status
            const warrantyFrom = formatDate(product.warranty_from);
            const warrantyTo = formatDate(product.warranty_to);
            const expiration = formatDate(product.expiration);
            
            return `
            <tr>
                <td class="font-mono font-semibold text-sm">${product.product_code}</td>
                <td>
                    <div class="font-semibold text-sm">${product.product_name}</div>
                </td>
                <td class="text-sm">${product.product_description || 'No description'}</td>
                <td class="text-right font-mono text-sm font-semibold">${formatCurrency(product.product_price)}</td>
                <td class="text-center">
                    <span class="${stockClass} text-sm">${product.product_stock}</span>
                    <div class="text-xs text-gray-500">${stockStatus}</div>
                </td>
                <td class="text-sm">
                    ${warrantyFrom !== 'N/A' && warrantyTo !== 'N/A' ? 
                      `${warrantyFrom} to ${warrantyTo}` : 'No Warranty'}
                </td>
                <td class="text-sm">${expiration}</td>
                <td>
                    <span class="badge text-white font-bold tracking-wider text-xs px-3 py-2 ${statusClass} border-0">
                        ${product.product_status.toUpperCase()}
                    </span>
                </td>
                <td class="hidden">
                    <div class="flex space-x-1">
                        <button title="Edit" class=" btn btn-sm btn-circle btn-warning edit-product-btn" data-product-id="${product.product_id}">
                            <i class="bx bx-edit text-sm"></i>
                        </button>
                        <button title="Delete" class=" btn btn-sm btn-circle btn-error delete-product-btn" data-product-id="${product.product_id}">
                            <i class="bx bx-trash text-sm"></i>
                        </button>
                    </div>
                </td>
            </tr>
            `;
        }).join('');

        // Add event listeners to product buttons
        addProductEventListeners();
    }

    function addProductEventListeners() {
        document.querySelectorAll('.edit-product-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                editProduct(parseInt(productId));
            });
        });

        document.querySelectorAll('.delete-product-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const productId = this.getAttribute('data-product-id');
                deleteProduct(parseInt(productId));
            });
        });
    }

    function showVendorProductsErrorState(message) {
        const tbody = document.getElementById('vendor-products-table-body');
        tbody.innerHTML = `
            <tr>
                <td colspan="9" class="text-center py-8">
                    <i class="bx bx-error text-4xl text-red-400 mb-2"></i>
                    <p class="text-red-500">${message}</p>
                    <button class="btn btn-sm btn-outline mt-2" onclick="loadVendorProducts(${currentVendorId})">Retry</button>
                </td>
            </tr>
        `;
        document.getElementById('no-products-state').classList.add('hidden');
    }

    function editProduct(productId) {
        const product = currentVendorProducts.find(p => p.product_id === productId);
        if (!product) return;

        document.getElementById('productModalTitle').textContent = 'Edit Product';
        document.getElementById('productModalSubmitText').textContent = 'Update Product';
        
        document.getElementById('productId').value = product.product_id;
        document.getElementById('productVendorId').value = product.ven_id;
        document.getElementById('productVendorName').value = product.vendor?.ven_name || 'N/A';
        document.getElementById('productCode').value = product.product_code;
        document.getElementById('productName').value = product.product_name;
        document.getElementById('productDescription').value = product.product_description || '';
        document.getElementById('productPrice').value = product.product_price;
        document.getElementById('productStock').value = product.product_stock;
        document.getElementById('productStatus').value = product.product_status;

        // Set warranty and expiration dates
        document.getElementById('warrantyFrom').value = product.warranty_from || '';
        document.getElementById('warrantyTo').value = product.warranty_to || '';
        document.getElementById('expiration').value = product.expiration || '';

        document.getElementById('productModal').classList.add('modal-open');
    }

    async function handleProductSubmit(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const productId = document.getElementById('productId').value;
        const isEdit = !!productId;

        const productData = {
            ven_id: parseInt(formData.get('ven_id')),
            product_name: formData.get('product_name'),
            product_description: formData.get('product_description'),
            product_price: parseFloat(formData.get('product_price')),
            product_stock: parseInt(formData.get('product_stock')),
            product_status: formData.get('product_status'),
            warranty_from: formData.get('warranty_from') || null,
            warranty_to: formData.get('warranty_to') || null,
            expiration: formData.get('expiration') || null
        };
        
        try {
            showLoadingModal(
                isEdit ? 'Updating Product...' : 'Creating Product...'
            );

            let response;
            if (isEdit) {
                response = await fetch(`${API_BASE_URL}/vendor-products/${productId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(productData)
                });
            } else {
                response = await fetch(`${API_BASE_URL}/vendor-products`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(productData)
                });
            }

            const result = await response.json();

            if (response.ok && result.success) {
                hideLoadingModal();
                closeProductModal();
                
                // Reload products and vendors to update counts
                await loadVendorProducts(currentVendorId);
                await loadVendors();
                
                showSuccessToast(
                    isEdit ? 'Product updated successfully!' : 'Product created successfully!'
                );
            } else {
                throw new Error(result.message || `Failed to ${isEdit ? 'update' : 'create'} product`);
            }
        } catch (error) {
            hideLoadingModal();
            Swal.fire('Error', `Failed to ${isEdit ? 'update' : 'create'} product: ` + error.message, 'error');
        }
    }

    async function deleteProduct(productId) {
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: "This will permanently delete the product!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        });

        if (result.isConfirmed) {
            try {
                showLoadingModal('Deleting Product...');

                const response = await fetch(`${API_BASE_URL}/vendor-products/${productId}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    hideLoadingModal();
                    
                    // Reload products and vendors to update counts
                    await loadVendorProducts(currentVendorId);
                    await loadVendors();
                    
                    showSuccessToast('Product deleted successfully!');
                } else {
                    throw new Error(result.message || 'Failed to delete product');
                }
            } catch (error) {
                hideLoadingModal();
                Swal.fire('Error', 'Failed to delete product: ' + error.message, 'error');
            }
        }
    }

    function closeVendorProductsModal() {
        document.getElementById('vendorProductsModal').classList.remove('modal-open');
        currentVendorId = null;
        currentVendorProducts = [];
    }

    // Modal Functions
    function openAddVendorModal() {
        document.getElementById('vendorModalTitle').textContent = 'Add New Vendor';
        document.getElementById('vendorModalSubmitText').textContent = 'Save Vendor';
        document.getElementById('vendorForm').reset();
        document.getElementById('vendorId').value = '';
        
        // Set default rating to 5 stars
        const ratingInput = document.querySelector('input[name="ven_rating"][value="5"]');
        if (ratingInput) {
            ratingInput.checked = true;
        }
        document.getElementById('vendorStatus').value = 'active';
        document.getElementById('vendorType').value = 'Supplies';
        
        document.getElementById('vendorModal').classList.add('modal-open');
    }

    function closeVendorModal() {
        document.getElementById('vendorModal').classList.remove('modal-open');
        document.getElementById('vendorForm').reset();
    }

    function openViewVendorModal() {
        document.getElementById('viewVendorModal').classList.add('modal-open');
    }

    function closeViewVendorModal() {
        document.getElementById('viewVendorModal').classList.remove('modal-open');
    }

    // Load vendors on page load
    document.addEventListener('DOMContentLoaded', function() {
        initializeEventListeners();
        loadVendors();
    });

    function initializeEventListeners() {
        // Add vendor button
        document.getElementById('addVendorBtn').addEventListener('click', openAddVendorModal);
        document.getElementById('addFirstVendorBtn').addEventListener('click', openAddVendorModal);

        // Close modal buttons
        document.getElementById('closeVendorModal').addEventListener('click', closeVendorModal);
        document.getElementById('closeVendorModalX').addEventListener('click', closeVendorModal);
        document.getElementById('closeViewVendorModal').addEventListener('click', closeViewVendorModal);
        document.getElementById('closeViewVendorModalX').addEventListener('click', closeViewVendorModal);
        document.getElementById('closeVendorProductsModalX').addEventListener('click', closeVendorProductsModal);
        document.getElementById('closeProductModal').addEventListener('click', closeProductModal);
        document.getElementById('closeProductModalX').addEventListener('click', closeProductModal);

        // Form submission
        document.getElementById('vendorForm').addEventListener('submit', handleVendorSubmit);
        document.getElementById('productForm').addEventListener('submit', handleProductSubmit);

        // Search and filter
        document.getElementById('searchVendors').addEventListener('input', function(e) {
            filterVendors(e.target.value, document.getElementById('statusFilter').value, document.getElementById('vendorTypeFilter').value);
        });

        document.getElementById('statusFilter').addEventListener('change', function(e) {
            filterVendors(document.getElementById('searchVendors').value, e.target.value, document.getElementById('vendorTypeFilter').value);
        });

        document.getElementById('vendorTypeFilter').addEventListener('change', function(e) {
            filterVendors(document.getElementById('searchVendors').value, document.getElementById('statusFilter').value, e.target.value);
        });

        // Product management buttons
        document.getElementById('addProductBtn').addEventListener('click', openAddProductModal);
        document.getElementById('addFirstProductBtn').addEventListener('click', openAddProductModal);
    }

    async function loadVendors() {
        try {
            showVendorsLoadingState();
            const response = await fetch(`${API_BASE_URL}/vendors`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const result = await response.json();
            
            if (result.success) {
                vendors = result.data || [];
                
                // Sort vendors by creation date (newest first)
                vendors.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
                
                renderVendorCards(vendors);
            } else {
                throw new Error(result.message || 'Failed to load vendors');
            }
        } catch (error) {
            console.error('Error loading vendors:', error);
            showVendorsErrorState('Failed to load vendors: ' + error.message);
        }
    }

    function showVendorsLoadingState() {
        const container = document.getElementById('vendors-cards-container');
        container.innerHTML = `
            <div class="col-span-3 text-center py-12">
                <div class="loading loading-spinner loading-lg"></div>
                <p class="text-gray-500 mt-4">Loading vendors...</p>
            </div>
        `;
        document.getElementById('no-vendors-state').classList.add('hidden');
    }

    function showVendorsErrorState(message) {
        const container = document.getElementById('vendors-cards-container');
        container.innerHTML = `
            <div class="col-span-3 text-center py-12">
                <i class="bx bx-error text-4xl text-red-400 mb-2"></i>
                <p class="text-red-500">${message}</p>
                <button class="btn btn-sm btn-outline mt-2" onclick="loadVendors()">Retry</button>
            </div>
        `;
        document.getElementById('no-vendors-state').classList.add('hidden');
    }

    function renderVendorCards(vendorsData) {
        const container = document.getElementById('vendors-cards-container');
        
        if (vendorsData.length === 0) {
            container.innerHTML = '';
            document.getElementById('no-vendors-state').classList.remove('hidden');
            return;
        }

        document.getElementById('no-vendors-state').classList.add('hidden');

        container.innerHTML = vendorsData.map(vendor => {
            const statusClass = vendor.ven_status === 'active' ? 'bg-green-400' : 'bg-red-400';
            const statusText = vendor.ven_status === 'active' ? 'Active' : 'Inactive';
            const ratingDisplay = generateStarRating(vendor.ven_rating);
            const productCount = vendor.shop_prods || 0;
            
            // Vendor type badge
            const typeClass = {
                'Equipment': 'bg-blue-400',
                'Supplies': 'bg-purple-400',
                'Furniture': 'bg-orange-400'
            }[vendor.vendor_type] || 'bg-gray-400';
            
            return `
            <div class="card bg-base-100 shadow-xl hover:shadow-2xl transition-all duration-300 border border-gray-200">
                <div class="card-body p-6">
                    <!-- Vendor Header -->
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="card-title text-lg font-bold text-gray-800">${vendor.ven_name || 'N/A'}</h3>
                            <p class="text-sm text-gray-600">${vendor.owner || 'No Owner'}</p>
                        </div>
                        <div class="flex flex-col items-end space-y-1">
                            <span class="badge text-white font-bold tracking-wider text-xs px-3 py-2 ${statusClass} border-0">
                                ${statusText}
                            </span>
                            <span class="badge text-white font-bold tracking-wider text-xs px-3 py-2 ${typeClass} border-0">
                                ${vendor.vendor_type || 'Supplies'}
                            </span>
                        </div>
                    </div>

                    <!-- Vendor Info -->
                    <div class="space-y-3 mb-4">
                        <div class="flex items-center text-sm">
                            <i class="bx bx-id-card text-gray-500 mr-2"></i>
                            <span class="font-mono text-gray-700">${vendor.ven_code || 'N/A'}</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <i class="bx bx-envelope text-gray-500 mr-2"></i>
                            <span class="text-gray-700">${vendor.ven_email || 'N/A'}</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <i class="bx bx-phone text-gray-500 mr-2"></i>
                            <span class="text-gray-700">${vendor.ven_contacts || 'N/A'}</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <i class="bx bx-package text-gray-500 mr-2"></i>
                            <span class="text-gray-700">${productCount} Products</span>
                        </div>
                    </div>

                    <!-- Rating -->
                    <div class="mb-4">
                        ${ratingDisplay}
                    </div>

                    <!-- Address (truncated) -->
                    ${vendor.ven_address ? `
                    <div class="text-xs text-gray-600 mb-4">
                        <i class="bx bx-map mr-1"></i>
                        <span class="truncate">${vendor.ven_address}</span>
                    </div>
                    ` : ''}

                    <!-- Action Buttons -->
                    <div class="card-actions justify-end">
                        <button class="btn btn-sm btn-info view-vendor-btn" data-vendor-id="${vendor.ven_id}">
                            <i class="bx bx-show-alt mr-1"></i>Info
                        </button>
                        <button class="hidden btn btn-sm btn-warning edit-vendor-btn" data-vendor-id="${vendor.ven_id}">
                            <i class="bx bx-edit mr-1"></i>Edit
                        </button>
                        <button class="btn btn-sm btn-primary products-vendor-btn" data-vendor-id="${vendor.ven_id}" data-vendor-name="${vendor.ven_name}">
                            <i class="bx bx-package mr-1"></i>Products
                        </button>
                        <button class="hidden btn btn-sm btn-error delete-vendor-btn" data-vendor-id="${vendor.ven_id}">
                            <i class="bx bx-trash mr-1"></i>Delete
                        </button>
                    </div>
                </div>
            </div>
            `;
        }).join('');

        // Add event listeners to dynamically created buttons
        addDynamicEventListeners();
    }

    function addDynamicEventListeners() {
        document.querySelectorAll('.view-vendor-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const vendorId = this.getAttribute('data-vendor-id');
                viewVendor(parseInt(vendorId));
            });
        });

        document.querySelectorAll('.edit-vendor-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const vendorId = this.getAttribute('data-vendor-id');
                editVendor(parseInt(vendorId));
            });
        });

        document.querySelectorAll('.products-vendor-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const vendorId = this.getAttribute('data-vendor-id');
                const vendorName = this.getAttribute('data-vendor-name');
                viewVendorProducts(parseInt(vendorId), vendorName);
            });
        });

        document.querySelectorAll('.delete-vendor-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const vendorId = this.getAttribute('data-vendor-id');
                deleteVendor(parseInt(vendorId));
            });
        });
    }

    function filterVendors(searchTerm, statusFilter, vendorTypeFilter) {
        const filtered = vendors.filter(vendor => {
            const matchesSearch = searchTerm === '' || 
                vendor.ven_name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                vendor.ven_email.toLowerCase().includes(searchTerm.toLowerCase()) ||
                (vendor.owner && vendor.owner.toLowerCase().includes(searchTerm.toLowerCase())) ||
                (vendor.ven_code && vendor.ven_code.toLowerCase().includes(searchTerm.toLowerCase()));
            
            const matchesStatus = statusFilter === '' || vendor.ven_status === statusFilter;
            const matchesType = vendorTypeFilter === '' || vendor.vendor_type === vendorTypeFilter;
            
            return matchesSearch && matchesStatus && matchesType;
        });
        
        renderVendorCards(filtered);
    }

    // Vendor Actions
    function viewVendor(vendorId) {
        const vendor = vendors.find(v => v.ven_id === vendorId);
        if (!vendor) return;

        const vendorDetails = `
            <div class="space-y-4">
                <!-- Vendor Information -->
                <div class="space-y-3">
                    <!-- Row 1: Vendor Name, Email, Phone, Status -->
                    <div class="grid grid-cols-1 xl:grid-cols-4 gap-4">
                        <div>
                            <strong class="text-gray-700 text-xs">Vendor ID:</strong>
                            <p class="text-sm p-3 bg-gray-50 rounded-lg border mt-1 font-mono">${vendor.ven_code || 'N/A'}</p>
                        </div>
                        <div>
                            <strong class="text-gray-700 text-xs">Company Name:</strong>
                            <p class="text-sm p-3 bg-gray-50 rounded-lg border mt-1">${vendor.ven_name || 'N/A'}</p>
                        </div>
                        <div>
                            <strong class="text-gray-700 text-xs">Email:</strong>
                            <p class="text-sm p-3 bg-gray-50 rounded-lg border mt-1">${vendor.ven_email || 'N/A'}</p>
                        </div>
                        <div>
                            <strong class="text-gray-700 text-xs">Phone:</strong>
                            <p class="text-sm p-3 bg-gray-50 rounded-lg border mt-1">${vendor.ven_contacts || 'N/A'}</p>
                        </div>
                    </div>

                    <!-- Row 2: Vendor Type, Owner, Rating -->
                    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
                        <div>
                            <strong class="text-gray-700 text-xs">Vendor Type:</strong>
                            <p class="text-sm p-3 bg-gray-50 rounded-lg border mt-1">${vendor.vendor_type || 'Supplies'}</p>
                        </div>
                        <div>
                            <strong class="text-gray-700 text-xs">Owner:</strong>
                            <p class="text-sm p-3 bg-gray-50 rounded-lg border mt-1">${vendor.owner || 'No Owner'}</p>
                        </div>
                        <div>
                            <strong class="text-gray-700 text-xs">Rating:</strong>
                            <div class="mt-1 p-3 bg-gray-50 rounded-lg border">
                                ${generateStarRating(vendor.ven_rating)}
                            </div>
                        </div>
                    </div>

                    <!-- Row 3: Address and Status -->
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                        <div>
                            <strong class="text-gray-700 text-xs">Address:</strong>
                            <p class="mt-1 p-3 bg-gray-50 rounded-lg border text-sm">${vendor.ven_address || 'No address provided'}</p>
                        </div>
                        <div>
                            <strong class="text-gray-700 text-xs">Status:</strong>
                            <p class="mt-1 p-3"><span class="badge ${vendor.ven_status === 'active' ? 'bg-green-400' : 'bg-red-400'} text-white font-bold tracking-wider text-xs px-3 py-2 border-0">${(vendor.ven_status || 'unknown').toUpperCase()}</span></p>
                        </div>
                    </div>

                    <!-- Row 4: Product Count -->
                    <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                        <div>
                            <strong class="text-gray-700 text-xs">Product Count:</strong>
                            <p class="text-sm p-3 bg-gray-50 rounded-lg border mt-1">${vendor.shop_prods || 0} products</p>
                        </div>
                    </div>
                </div>

                <!-- Timestamps -->
                <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                    ${vendor.created_at ? `
                    <div>
                        <strong class="text-gray-700 text-xs">Created:</strong>
                        <p class="text-sm p-3 bg-gray-50 rounded-lg border mt-1">${new Date(vendor.created_at).toLocaleString()}</p>
                    </div>
                    ` : ''}
                    ${vendor.updated_at ? `
                    <div>
                        <strong class="text-gray-700 text-xs">Last Updated:</strong>
                        <p class="text-sm p-3 bg-gray-50 rounded-lg border mt-1">${new Date(vendor.updated_at).toLocaleString()}</p>
                    </div>
                    ` : ''}
                </div>
            </div>
        `;

        document.getElementById('vendorDetails').innerHTML = vendorDetails;
        openViewVendorModal();
    }

    function editVendor(vendorId) {
        const vendor = vendors.find(v => v.ven_id === vendorId);
        if (!vendor) return;

        document.getElementById('vendorModalTitle').textContent = 'Edit Vendor';
        document.getElementById('vendorModalSubmitText').textContent = 'Update Vendor';
        
        document.getElementById('vendorId').value = vendor.ven_id;
        document.getElementById('vendorName').value = vendor.ven_name || '';
        document.getElementById('vendorType').value = vendor.vendor_type || 'Supplies';
        document.getElementById('owner').value = vendor.owner || '';
        document.getElementById('vendorEmail').value = vendor.ven_email || '';
        document.getElementById('vendorPhone').value = vendor.ven_contacts || '';
        document.getElementById('vendorAddress').value = vendor.ven_address || '';
        document.getElementById('vendorStatus').value = vendor.ven_status || 'active';

        // Set rating - safely handle null rating input
        const rating = Math.round(safeNumber(vendor.ven_rating, 5));
        const ratingInput = document.querySelector(`input[name="ven_rating"][value="${rating}"]`);
        if (ratingInput) {
            ratingInput.checked = true;
        } else {
            // Fallback to 5 stars if rating input not found
            const fallbackInput = document.querySelector('input[name="ven_rating"][value="5"]');
            if (fallbackInput) {
                fallbackInput.checked = true;
            }
        }

        document.getElementById('vendorModal').classList.add('modal-open');
    }

    async function handleVendorSubmit(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const vendorId = document.getElementById('vendorId').value;
        const isEdit = !!vendorId;

        const vendorData = {
            ven_name: formData.get('ven_name'),
            ven_email: formData.get('ven_email'),
            ven_contacts: formData.get('ven_contacts'),
            ven_address: formData.get('ven_address'),
            ven_rating: safeNumber(formData.get('ven_rating'), 5),
            ven_status: formData.get('ven_status'),
            vendor_type: formData.get('vendor_type'),
            owner: formData.get('owner')
        };
        
        try {
            showLoadingModal(
                isEdit ? 'Updating Vendor...' : 'Creating Vendor...',
                isEdit ? 'Please wait while we update vendor information.' : 'Please wait while we create new vendor.'
            );

            let response;
            if (isEdit) {
                response = await fetch(`${API_BASE_URL}/vendors/${vendorId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(vendorData)
                });
            } else {
                response = await fetch(`${API_BASE_URL}/vendors`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(vendorData)
                });
            }

            const result = await response.json();

            if (response.ok && result.success) {
                hideLoadingModal();
                closeVendorModal();
                
                // Wait for data to reload before showing success message
                await loadVendors();
                
                showSuccessToast(
                    isEdit ? 'Vendor updated successfully!' : 'Vendor created successfully!'
                );
            } else {
                throw new Error(result.message || `Failed to ${isEdit ? 'update' : 'create'} vendor`);
            }
        } catch (error) {
            hideLoadingModal();
            Swal.fire('Error', `Failed to ${isEdit ? 'update' : 'create'} vendor: ` + error.message, 'error');
        }
    }

    async function deleteVendor(vendorId) {
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: "This will permanently delete the vendor and all associated products!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        });

        if (result.isConfirmed) {
            try {
                showLoadingModal('Deleting Vendor...', 'Please wait while we remove vendor and associated data.');

                const response = await fetch(`${API_BASE_URL}/vendors/${vendorId}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    hideLoadingModal();
                    
                    // Wait for data to reload before showing success message
                    await loadVendors();
                    
                    showSuccessToast('Vendor deleted successfully!');
                } else {
                    throw new Error(result.message || 'Failed to delete vendor');
                }
            } catch (error) {
                hideLoadingModal();
                Swal.fire('Error', 'Failed to delete vendor: ' + error.message, 'error');
            }
        }
    }
</script>

<style>
    .rating input {
        cursor: pointer !important;
        transition: all 0.2s ease-in-out;
    }
    .rating input:hover {
        transform: scale(1.1);
    }
    .rating input:checked ~ input {
        color: #d1d5db !important;
    }
    .modal-box {
        max-height: 95vh;
    }
    .card {
        transition: all 0.3s ease;
    }
    .card:hover {
        transform: translateY(-2px);
    }
    .truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>
@endsection