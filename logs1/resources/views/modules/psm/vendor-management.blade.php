@extends('layouts.app')

@section('title', 'Vendor Management')

@section('content')
    <div class="module-content bg-white rounded-xl p-6 shadow block">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Vendor Management</h2>
            <button class="btn btn-primary hidden" id="addVendorBtn">
                <i class="bx bx-plus mr-2"></i>Add New Vendor
            </button>
        </div>
        <!-- Combined Stats Section -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-primary">
                <div class="stat-figure text-primary">
                    <i class="bx bx-store text-3xl"></i>
                </div>
                <div class="stat-title">Total Vendors</div>
                <div class="stat-value text-primary" id="total-vendors">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-success">
                <div class="stat-figure text-success">
                    <i class="bx bx-check-circle text-3xl"></i>
                </div>
                <div class="stat-title">Active Vendors</div>
                <div class="stat-value text-success" id="active-vendors">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-warning">
                <div class="stat-figure text-warning">
                    <i class="bx bx-buildings text-3xl"></i>
                </div>
                <div class="stat-title">Total Shops</div>
                <div class="stat-value text-warning" id="total-shops">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-info">
                <div class="stat-figure text-info">
                    <i class="bx bx-star text-3xl"></i>
                </div>
                <div class="stat-title">Avg Rating</div>
                <div class="stat-value text-info" id="avg-rating">0.0</div>
            </div>
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
        </div>

        <!-- Vendors Table -->
        <div class="overflow-x-auto bg-base-100 rounded-lg">
            <table class="table table-zebra w-full">
                <thead>
                    <tr class="bg-gray-900 text-white">
                        <th>Vendor ID</th>
                        <th>Vendor Name</th>
                        <th>Email</th>
                        <th>Shop Name</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="vendors-table-body">
                    <tr>
                        <td colspan="7" class="text-center py-8">
                            <div class="loading loading-spinner loading-lg"></div>
                            <p class="text-gray-500 mt-2">Loading vendors...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
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
                    <input type="hidden" id="shopId" name="shop_id">
                    
                    <!-- Vendor Information -->
                    <div class="space-y-3">
                        <!-- Row 1: Vendor Name, Email, Phone, Status -->
                        <div class="grid grid-cols-1 xl:grid-cols-4 gap-4">
                            <div class="form-control">
                                <label class="label py-1">
                                    <span class="label-text font-semibold text-gray-700 text-xs">Vendor Name *</span>
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

                        <!-- Row 2: Address -->
                        <div class="form-control">
                            <label class="label py-1">
                                <span class="label-text font-semibold text-gray-700 text-xs">Address</span>
                            </label>
                            <textarea id="vendorAddress" name="ven_address" class="textarea textarea-bordered textarea-sm h-16 w-full focus:ring-2 focus:ring-primary/20" placeholder="Complete vendor address"></textarea>
                        </div>
                    </div>

                    <!-- Shop Information -->
                    <div class="space-y-3">
                        <!-- Row 1: Shop Name and Rating -->
                        <div class="grid grid-cols-1 xl:grid-cols-2 gap-4">
                            <div class="form-control">
                                <label class="label py-1">
                                    <span class="label-text font-semibold text-gray-700 text-xs">Shop Name *</span>
                                </label>
                                <input type="text" id="shopName" name="shop_name" class="input input-bordered input-sm w-full focus:ring-2 focus:ring-primary/20" required>
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

    <!-- Loading Modal -->
    <div id="loadingModal" class="modal">
        <div class="modal-box max-w-sm text-center p-4">
            <div class="loading loading-spinner loading-lg text-primary mb-2"></div>
            <h3 class="font-bold text-sm mb-1" id="loadingTitle">Processing...</h3>
        </div>
    </div>

<script>
    let vendors = [];
    let shops = [];

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
            <span class="ml-2 text-sm">${formatRating(rating)}</span>
        </div>`;
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

    // Load vendors on page load
    document.addEventListener('DOMContentLoaded', function() {
        initializeEventListeners();
        loadVendors();
    });

    function initializeEventListeners() {
        // Add vendor button
        document.getElementById('addVendorBtn').addEventListener('click', openAddVendorModal);

        // Close modal buttons
        document.getElementById('closeVendorModal').addEventListener('click', closeVendorModal);
        document.getElementById('closeVendorModalX').addEventListener('click', closeVendorModal);
        document.getElementById('closeViewVendorModal').addEventListener('click', closeViewVendorModal);
        document.getElementById('closeViewVendorModalX').addEventListener('click', closeViewVendorModal);

        // Form submission
        document.getElementById('vendorForm').addEventListener('submit', handleVendorSubmit);

        // Search and filter
        document.getElementById('searchVendors').addEventListener('input', function(e) {
            filterVendors(e.target.value, document.getElementById('statusFilter').value);
        });

        document.getElementById('statusFilter').addEventListener('change', function(e) {
            filterVendors(document.getElementById('searchVendors').value, e.target.value);
        });
    }

    async function loadVendors() {
        try {
            showVendorsLoadingState();
            const [vendorsResponse, shopsResponse] = await Promise.all([
                fetch(`${API_BASE_URL}/vendors`),
                fetch(`${API_BASE_URL}/shops`)
            ]);
            
            if (!vendorsResponse.ok) {
                throw new Error(`HTTP error! status: ${vendorsResponse.status}`);
            }
            
            const vendorsResult = await vendorsResponse.json();
            const shopsResult = await shopsResponse.json();
            
            if (vendorsResult.success) {
                vendors = vendorsResult.data || [];
                shops = shopsResult.success ? shopsResult.data || [] : [];
                
                // Sort vendors by creation date (newest first)
                vendors.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
                
                // Combine vendor and shop data
                const combinedData = combineVendorShopData(vendors, shops);
                renderVendors(combinedData);
                updateStats(combinedData);
            } else {
                throw new Error(vendorsResult.message || 'Failed to load vendors');
            }
        } catch (error) {
            console.error('Error loading vendors:', error);
            showVendorsErrorState('Failed to load vendors: ' + error.message);
        }
    }

    function combineVendorShopData(vendorsData, shopsData) {
        return vendorsData.map(vendor => {
            // Find shops for this vendor
            const vendorShops = shopsData.filter(shop => shop.ven_id === vendor.ven_id);
            const primaryShop = vendorShops[0] || {}; // Use first shop as primary
            
            return {
                ...vendor,
                // Use ven_code as the vendor ID for display
                vendor_id: vendor.ven_code || 'N/A',
                shop_name: primaryShop.shop_name || 'No Shop',
                shop_id: primaryShop.shop_id || null,
                shop_prods: primaryShop.shop_prods || 0
            };
        });
    }

    function showVendorsLoadingState() {
        const tbody = document.getElementById('vendors-table-body');
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-8">
                    <div class="loading loading-spinner loading-lg"></div>
                    <p class="text-gray-500 mt-2">Loading vendors...</p>
                </td>
            </tr>
        `;
    }

    function showVendorsErrorState(message) {
        const tbody = document.getElementById('vendors-table-body');
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-8">
                    <i class="bx bx-error text-4xl text-red-400 mb-2"></i>
                    <p class="text-red-500">${message}</p>
                    <button class="btn btn-sm btn-outline mt-2" onclick="loadVendors()">Retry</button>
                </td>
            </tr>
        `;
    }

    function renderVendors(vendorsData) {
        const tbody = document.getElementById('vendors-table-body');
        
        if (vendorsData.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-8">
                        <i class="bx bx-package text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500">No vendors found</p>
                        <button class="btn btn-sm btn-primary mt-2" id="addFirstVendorBtn">Add First Vendor</button>
                    </td>
                </tr>
            `;
            document.getElementById('addFirstVendorBtn')?.addEventListener('click', openAddVendorModal);
            return;
        }

        tbody.innerHTML = vendorsData.map(vendor => {
            const ratingDisplay = generateStarRating(vendor.ven_rating);
            const statusClass = vendor.ven_status === 'active' ? 'bg-green-400' : 'bg-red-400';
            
            return `
            <tr>
                <td class="font-mono font-semibold text-sm">${vendor.vendor_id}</td>
                <td>
                    <div class="font-semibold text-sm">${vendor.ven_name || 'N/A'}</div>
                </td>
                <td class="text-sm">${vendor.ven_email || 'N/A'}</td>
                <td>
                    <div class="text-sm">${vendor.shop_name}</div>
                </td>
                <td>${ratingDisplay}</td>
                <td>
                    <span class="badge text-white font-bold tracking-wider text-xs px-3 py-2 ${statusClass} border-0">
                        ${(vendor.ven_status || 'unknown').toUpperCase()}
                    </span>
                </td>
                <td>
                    <div class="flex space-x-3">
                        <button title="View" class="btn btn-sm btn-circle btn-info view-vendor-btn" data-vendor-id="${vendor.ven_id}">
                            <i class="bx bx-show-alt text-sm"></i>
                        </button>
                        <button title="Edit" class="hidden btn btn-sm btn-circle btn-warning edit-vendor-btn" data-vendor-id="${vendor.ven_id}">
                            <i class="bx bx-edit text-sm"></i>
                        </button>
                        <button title="Delete" class="btn btn-sm btn-circle btn-error delete-vendor-btn" data-vendor-id="${vendor.ven_id}" data-shop-id="${vendor.shop_id}">
                            <i class="bx bx-trash text-sm"></i>
                        </button>
                    </div>
                </td>
            </tr>
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

        document.querySelectorAll('.delete-vendor-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const vendorId = this.getAttribute('data-vendor-id');
                const shopId = this.getAttribute('data-shop-id');
                deleteVendor(parseInt(vendorId), shopId ? parseInt(shopId) : null);
            });
        });
    }

    function updateStats(vendorsData) {
        document.getElementById('total-vendors').textContent = vendorsData.length;
        document.getElementById('active-vendors').textContent = 
            vendorsData.filter(v => v.ven_status === 'active').length;
        
        // Calculate total shops
        const totalShops = vendorsData.filter(v => v.shop_name !== 'No Shop').length;
        document.getElementById('total-shops').textContent = totalShops;
        
        // Calculate average rating
        const totalRating = vendorsData.reduce((sum, vendor) => sum + safeNumber(vendor.ven_rating, 0), 0);
        const avgRating = vendorsData.length > 0 ? totalRating / vendorsData.length : 0;
        document.getElementById('avg-rating').textContent = avgRating.toFixed(1);
    }

    function filterVendors(searchTerm, statusFilter) {
        const filtered = vendors.map(vendor => {
            const vendorShops = shops.filter(shop => shop.ven_id === vendor.ven_id);
            const primaryShop = vendorShops[0] || {};
            
            return {
                ...vendor,
                vendor_id: vendor.ven_code || 'N/A',
                shop_name: primaryShop.shop_name || 'No Shop',
                shop_id: primaryShop.shop_id || null,
                shop_prods: primaryShop.shop_prods || 0
            };
        }).filter(vendor => {
            const matchesSearch = searchTerm === '' || 
                vendor.ven_name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                vendor.ven_email.toLowerCase().includes(searchTerm.toLowerCase()) ||
                vendor.shop_name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                vendor.vendor_id.toLowerCase().includes(searchTerm.toLowerCase());
            
            const matchesStatus = statusFilter === '' || vendor.ven_status === statusFilter;
            
            return matchesSearch && matchesStatus;
        });
        
        renderVendors(filtered);
        updateStats(filtered);
    }

    // Modal Functions
    function openAddVendorModal() {
        document.getElementById('vendorModalTitle').textContent = 'Add New Vendor';
        document.getElementById('vendorModalSubmitText').textContent = 'Save Vendor';
        document.getElementById('vendorForm').reset();
        document.getElementById('vendorId').value = '';
        document.getElementById('shopId').value = '';
        
        // Set default rating to 5 stars
        const ratingInput = document.querySelector('input[name="ven_rating"][value="5"]');
        if (ratingInput) {
            ratingInput.checked = true;
        }
        document.getElementById('vendorStatus').value = 'active';
        
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

    // Vendor Actions
    function viewVendor(vendorId) {
        const vendor = vendors.find(v => v.ven_id === vendorId);
        if (!vendor) return;

        const vendorShops = shops.filter(shop => shop.ven_id === vendorId);
        const primaryShop = vendorShops[0] || {};

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
                            <strong class="text-gray-700 text-xs">Vendor Name:</strong>
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

                    <!-- Row 2: Address and Status -->
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
                </div>

                <!-- Shop Information -->
                <div class="space-y-3">
                    <!-- Row 1: Shop Name, Rating -->
                    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
                        <div>
                            <strong class="text-gray-700 text-xs">Shop Name:</strong>
                            <p class="text-sm p-3 bg-gray-50 rounded-lg border mt-1">${primaryShop.shop_name || 'No Shop'}</p>
                        </div>
                        <div>
                            <strong class="text-gray-700 text-xs">Rating:</strong>
                            <div class="mt-1 p-3 bg-gray-50 rounded-lg border">
                                ${generateStarRating(vendor.ven_rating)}
                            </div>
                        </div>
                    </div>

                    <!-- Row 2: Created and Last Updated -->
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
            </div>
        `;

        document.getElementById('vendorDetails').innerHTML = vendorDetails;
        openViewVendorModal();
    }

    function editVendor(vendorId) {
        const vendor = vendors.find(v => v.ven_id === vendorId);
        if (!vendor) return;

        const vendorShops = shops.filter(shop => shop.ven_id === vendorId);
        const primaryShop = vendorShops[0] || {};

        document.getElementById('vendorModalTitle').textContent = 'Edit Vendor';
        document.getElementById('vendorModalSubmitText').textContent = 'Update Vendor';
        
        document.getElementById('vendorId').value = vendor.ven_id;
        document.getElementById('shopId').value = primaryShop.shop_id || '';
        document.getElementById('vendorName').value = vendor.ven_name || '';
        document.getElementById('shopName').value = primaryShop.shop_name || '';
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
        const shopId = document.getElementById('shopId').value;
        const isEdit = !!vendorId;

        const vendorData = {
            ven_name: formData.get('ven_name'),
            ven_email: formData.get('ven_email'),
            ven_contacts: formData.get('ven_contacts'),
            ven_address: formData.get('ven_address'),
            ven_rating: safeNumber(formData.get('ven_rating'), 5),
            ven_status: formData.get('ven_status')
        };

        const shopName = formData.get('shop_name');
        
        try {
            showLoadingModal(
                isEdit ? 'Updating Vendor...' : 'Creating Vendor...',
                isEdit ? 'Please wait while we update vendor information.' : 'Please wait while we create new vendor.'
            );

            let vendorResponse;
            if (isEdit) {
                // Update vendor
                vendorResponse = await fetch(`${API_BASE_URL}/vendors/${vendorId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(vendorData)
                });
            } else {
                // Create vendor
                vendorResponse = await fetch(`${API_BASE_URL}/vendors`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(vendorData)
                });
            }

            const vendorResult = await vendorResponse.json();

            if (vendorResponse.ok && vendorResult.success) {
                // Handle shop creation/update
                if (shopName) {
                    if (isEdit && shopId) {
                        // Update existing shop using the shops endpoint
                        const shopData = {
                            shop_name: shopName,
                            ven_id: vendorId,
                            shop_status: 'active'
                        };
                        
                        const shopResponse = await fetch(`${API_BASE_URL}/shops`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(shopData)
                        });

                        if (!shopResponse.ok) {
                            throw new Error('Failed to update shop information');
                        }
                    } else if (!isEdit) {
                        // Create new shop for new vendor
                        const shopData = {
                            shop_name: shopName,
                            ven_id: vendorResult.data.ven_id,
                            shop_status: 'active'
                        };
                        
                        const shopResponse = await fetch(`${API_BASE_URL}/shops`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                            },
                            body: JSON.stringify(shopData)
                        });

                        if (!shopResponse.ok) {
                            throw new Error('Failed to create shop');
                        }
                    }
                }

                hideLoadingModal();
                closeVendorModal();
                
                // Wait for data to reload before showing success message
                await loadVendors();
                
                showSuccessToast(
                    isEdit ? 'Vendor updated successfully!' : 'Vendor created successfully!'
                );
            } else {
                throw new Error(vendorResult.message || `Failed to ${isEdit ? 'update' : 'create'} vendor`);
            }
        } catch (error) {
            hideLoadingModal();
            Swal.fire('Error', `Failed to ${isEdit ? 'update' : 'create'} vendor: ` + error.message, 'error');
        }
    }

    async function deleteVendor(vendorId, shopId) {
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: "This will permanently delete the vendor and all associated shops!",
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

                // Delete vendor (this should cascade delete shops due to foreign key relationship)
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
</style>
@endsection