@extends('layouts.app')

@section('title', 'Shop Management')

@section('content')
    <div class="module-content bg-white rounded-xl p-6 shadow block">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Shop Management</h2>
            <button class="btn btn-primary" onclick="openAddShopModal()">
                <i class="bx bx-plus mr-2"></i>Add Shop
            </button>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="stat bg-base-100 rounded- shadow-lg border-l-4 border-primary">
                <div class="stat-figure text-primary">
                    <i class="bx bx-store text-3xl"></i>
                </div>
                <div class="stat-title">Total Shops</div>
                <div class="stat-value text-primary" id="total-shops">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-success">
                <div class="stat-figure text-success">
                    <i class="bx bx-check-circle text-3xl"></i>
                </div>
                <div class="stat-title">Active</div>
                <div class="stat-value text-success" id="active-shops">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-warning">
                <div class="stat-figure text-warning">
                    <i class="bx bx-wrench text-3xl"></i>
                </div>
                <div class="stat-title">Maintenance</div>
                <div class="stat-value text-warning" id="maintenance-shops">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-error">
                <div class="stat-figure text-error">
                    <i class="bx bx-x-circle text-3xl"></i>
                </div>
                <div class="stat-title">Inactive</div>
                <div class="stat-value text-error" id="inactive-shops">0</div>
            </div>
        </div>

        <!-- Search -->
        <div class="flex gap-4 mb-6">
            <div class="form-control flex-1">
                <input type="text" placeholder="Search shops..." class="input input-bordered w-full" id="searchShops">
            </div>
        </div>

        <!-- Shops Table -->
        <div class="overflow-x-auto bg-base-100 rounded-lg">
            <table class="table table-zebra w-full">
                <thead>
                    <tr class="bg-base-200">
                        <th>Shop Name</th>
                        <th>Vendor</th>
                        <th>Products</th>
                        <th>Status</th>
                        <th>Created Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="shops-table-body">
                    <tr>
                        <td colspan="6" class="text-center py-8">
                            <div class="loading loading-spinner loading-lg"></div>
                            <p class="text-gray-500 mt-2">Loading shops...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Shop Modal -->
    <div id="addShopModal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Add New Shop</h3>
            <form id="addShopForm" class="space-y-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Shop Name</span>
                    </label>
                    <input type="text" name="shop_name" class="input input-bordered w-full" required>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Vendor</span>
                    </label>
                    <select name="ven_id" class="select select-bordered w-full" required>
                        <option value="">Select Vendor</option>
                        <!-- Vendors will be populated via JavaScript -->
                    </select>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Status</span>
                    </label>
                    <select name="shop_status" class="select select-bordered w-full">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                        <option value="maintenance">Maintenance</option>
                    </select>
                </div>

                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" onclick="closeAddShopModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save mr-2"></i>Save Shop
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // ==================== CONFIGURATION ====================
        const API_BASE_URL = 'http://localhost:8001/api/psm';
        
        let shops = [];
        let vendors = [];

        document.addEventListener('DOMContentLoaded', function() {
            loadShops();
            loadVendors();
            
            document.getElementById('searchShops').addEventListener('input', function(e) {
                filterShops(e.target.value);
            });
        });

        async function loadShops() {
            try {
                showLoadingState();
                const response = await fetch(`${API_BASE_URL}/shops`);
                const data = await response.json();
                
                if (response.ok && data.success) {
                    shops = data.data || [];
                    renderShops(shops);
                    updateShopStats();
                } else {
                    throw new Error(data.message || 'Failed to load shops');
                }
            } catch (error) {
                console.error('Error loading shops:', error);
                showErrorState('Failed to load shops: ' + error.message);
            }
        }

        function showLoadingState() {
            const tbody = document.getElementById('shops-table-body');
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-8">
                        <div class="loading loading-spinner loading-lg"></div>
                        <p class="text-gray-500 mt-2">Loading shops...</p>
                    </td>
                </tr>
            `;
        }

        function showErrorState(message) {
            const tbody = document.getElementById('shops-table-body');
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-8">
                        <i class="bx bx-error text-4xl text-red-400 mb-2"></i>
                        <p class="text-red-500">${message}</p>
                        <button class="btn btn-sm btn-outline mt-2" onclick="loadShops()">Retry</button>
                    </td>
                </tr>
            `;
            Swal.fire('Error', message, 'error');
        }

        async function loadVendors() {
            try {
                const response = await fetch(`${API_BASE_URL}/vendors`);
                const data = await response.json();
                
                if (response.ok && data.success) {
                    vendors = data.data || [];
                    populateVendorDropdown();
                }
            } catch (error) {
                console.error('Error loading vendors:', error);
            }
        }

        function renderShops(shopsToRender) {
            const tbody = document.getElementById('shops-table-body');
            
            if (shopsToRender.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-8">
                            <i class="bx bx-store text-4xl text-gray-400 mb-2"></i>
                            <p class="text-gray-500">No shops found</p>
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = shopsToRender.map(shop => `
                <tr>
                    <td>
                        <div class="font-semibold">${shop.shop_name}</div>
                    </td>
                    <td>
                        <div>${shop.vendor?.ven_name || 'N/A'}</div>
                        <div class="text-sm text-gray-500">${shop.vendor?.ven_email || ''}</div>
                    </td>
                    <td>
                        <div class="flex items-center space-x-2">
                            <span class="font-semibold">${shop.shop_prods || 0}</span>
                            <span class="text-sm text-gray-500">products</span>
                        </div>
                    </td>
                    <td>
                        <span class="badge ${getShopStatusBadgeClass(shop.shop_status)}">
                            ${shop.shop_status || 'active'}
                        </span>
                    </td>
                    <td>${shop.created_at ? new Date(shop.created_at).toLocaleDateString() : 'N/A'}</td>
                    <td>
                        <div class="flex space-x-1">
                            <button class="btn btn-xs btn-outline btn-info" onclick="viewShopDetails(${shop.shop_id})">
                                <i class="bx bx-show"></i>
                            </button>
                            <button class="btn btn-xs btn-outline btn-warning" onclick="editShop(${shop.shop_id})">
                                <i class="bx bx-edit"></i>
                            </button>
                            <button class="btn btn-xs btn-outline btn-error" onclick="deleteShop(${shop.shop_id})">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function getShopStatusBadgeClass(status) {
            switch(status) {
                case 'active': return 'badge-success';
                case 'maintenance': return 'badge-warning';
                case 'inactive': return 'badge-error';
                default: return 'badge-ghost';
            }
        }

        function updateShopStats() {
            document.getElementById('total-shops').textContent = shops.length;
            document.getElementById('active-shops').textContent = 
                shops.filter(s => s.shop_status === 'active').length;
            document.getElementById('maintenance-shops').textContent = 
                shops.filter(s => s.shop_status === 'maintenance').length;
            document.getElementById('inactive-shops').textContent = 
                shops.filter(s => s.shop_status === 'inactive').length;
        }

        function populateVendorDropdown() {
            const select = document.querySelector('select[name="ven_id"]');
            select.innerHTML = '<option value="">Select Vendor</option>' +
                vendors.map(vendor => `
                    <option value="${vendor.ven_id}">
                        ${vendor.ven_name} - ${vendor.ven_email || 'N/A'}
                    </option>
                `).join('');
        }

        function filterShops(searchTerm) {
            const filtered = shops.filter(shop => 
                shop.shop_name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                (shop.vendor?.ven_name && shop.vendor.ven_name.toLowerCase().includes(searchTerm.toLowerCase())) ||
                (shop.vendor?.ven_email && shop.vendor.ven_email.toLowerCase().includes(searchTerm.toLowerCase()))
            );
            renderShops(filtered);
        }

        function viewShopDetails(shopId) {
            const shop = shops.find(s => s.shop_id === shopId);
            if (!shop) return;

            Swal.fire({
                title: shop.shop_name,
                html: `
                    <div class="text-left space-y-2">
                        <p><strong>Vendor:</strong> ${shop.vendor?.ven_name || 'N/A'}</p>
                        <p><strong>Vendor Email:</strong> ${shop.vendor?.ven_email || 'N/A'}</p>
                        <p><strong>Vendor Contact:</strong> ${shop.vendor?.ven_contacts || 'N/A'}</p>
                        <p><strong>Products:</strong> ${shop.shop_prods || 0}</p>
                        <p><strong>Status:</strong> <span class="badge ${getShopStatusBadgeClass(shop.shop_status)}">${shop.shop_status || 'active'}</span></p>
                        <p><strong>Created:</strong> ${shop.created_at ? new Date(shop.created_at).toLocaleString() : 'N/A'}</p>
                        <p><strong>Last Updated:</strong> ${shop.updated_at ? new Date(shop.updated_at).toLocaleString() : 'N/A'}</p>
                    </div>
                `,
                icon: 'info',
                confirmButtonText: 'Close'
            });
        }

        function openAddShopModal() {
            document.getElementById('addShopModal').classList.add('modal-open');
        }

        function closeAddShopModal() {
            document.getElementById('addShopModal').classList.remove('modal-open');
            document.getElementById('addShopForm').reset();
        }

        document.getElementById('addShopForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const shopData = Object.fromEntries(formData);

            try {
                const response = await fetch(`${API_BASE_URL}/shops`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(shopData)
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    Swal.fire('Success', 'Shop added successfully!', 'success');
                    closeAddShopModal();
                    loadShops();
                } else {
                    throw new Error(result.message || 'Failed to add shop');
                }
            } catch (error) {
                Swal.fire('Error', 'Failed to add shop: ' + error.message, 'error');
            }
        });

        function editShop(shopId) {
            Swal.fire('Info', 'Edit functionality will be implemented soon!', 'info');
        }

        async function deleteShop(shopId) {
            const result = await Swal.fire({
                title: 'Are you sure?',
                text: "This will also delete all products associated with this shop!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch(`${API_BASE_URL}/shops/${shopId}`, {
                        method: 'DELETE'
                    });

                    const result = await response.json();

                    if (response.ok && result.success) {
                        Swal.fire('Deleted!', 'Shop has been deleted.', 'success');
                        loadShops();
                    } else {
                        throw new Error(result.message || 'Failed to delete shop');
                    }
                } catch (error) {
                    Swal.fire('Error', 'Failed to delete shop: ' + error.message, 'error');
                }
            }
        }
    </script>
@endsection