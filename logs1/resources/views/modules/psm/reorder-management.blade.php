@extends('layouts.app')

@section('title', 'Re-Order Management')

@section('content')
    <div class="module-content bg-white rounded-xl p-6 shadow block">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Re-Order Management</h2>
            <button class="btn btn-primary" onclick="openCreateReorderModal()">
                <i class="bx bx-plus mr-2"></i>Create Re-Order
            </button>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="stat bg-base-100 rounded-lg">
                <div class="stat-figure text-primary">
                    <i class="bx bx-refresh text-3xl"></i>
                </div>
                <div class="stat-title">Total Re-Orders</div>
                <div class="stat-value text-primary" id="total-reorders">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg">
                <div class="stat-figure text-warning">
                    <i class="bx bx-time text-3xl"></i>
                </div>
                <div class="stat-title">Pending</div>
                <div class="stat-value text-warning" id="pending-reorders">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg">
                <div class="stat-figure text-info">
                    <i class="bx bx-rocket text-3xl"></i>
                </div>
                <div class="stat-title">Auto-Triggered</div>
                <div class="stat-value text-info" id="auto-reorders">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg">
                <div class="stat-figure text-success">
                    <i class="bx bx-check-circle text-3xl"></i>
                </div>
                <div class="stat-title">Settled</div>
                <div class="stat-value text-success" id="settled-reorders">0</div>
            </div>
        </div>

        <!-- Re-Orders Table -->
        <div class="overflow-x-auto bg-base-100 rounded-lg">
            <table class="table table-zebra w-full">
                <thead>
                    <tr class="bg-base-200">
                        <th>Re-Order ID</th>
                        <th>Product</th>
                        <th>Vendor</th>
                        <th>Quantity</th>
                        <th>Total Amount</th>
                        <th>Budget Status</th>
                        <th>Re-Order Status</th>
                        <th>Trigger Type</th>
                        <th>Created Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="reorders-table-body">
                    <tr>
                        <td colspan="10" class="text-center py-8">
                            <div class="loading loading-spinner loading-lg"></div>
                            <p class="text-gray-500 mt-2">Loading re-orders...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create Re-Order Modal -->
    <div id="createReorderModal" class="modal">
        <div class="modal-box max-w-2xl">
            <h3 class="font-bold text-lg mb-4">Create New Re-Order</h3>
            <form id="createReorderForm" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Original Order</span>
                        </label>
                        <select name="order_id" class="select select-bordered w-full" id="orderSelect">
                            <option value="">Select Original Order</option>
                            <!-- Orders will be populated via JavaScript -->
                        </select>
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Product</span>
                        </label>
                        <select name="prod_id" class="select select-bordered w-full" id="productSelect" required>
                            <option value="">Select Product</option>
                            <!-- Products will be populated via JavaScript -->
                        </select>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Shop</span>
                        </label>
                        <select name="shop_id" class="select select-bordered w-full" id="shopSelect" required>
                            <option value="">Select Shop</option>
                            <!-- Shops will be populated via JavaScript -->
                        </select>
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Quantity</span>
                        </label>
                        <input type="number" name="quantity" class="input input-bordered w-full" min="1" value="1" required>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Restock Price</span>
                        </label>
                        <input type="number" name="restock_price" step="0.01" class="input input-bordered w-full" required>
                    </div>
                    
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">SWS Stock ID</span>
                        </label>
                        <input type="number" name="sws_stock_id" class="input input-bordered w-full" required>
                    </div>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Trigger Threshold</span>
                    </label>
                    <input type="number" name="trigger_threshold" class="input input-bordered w-full" value="10">
                    <label class="label">
                        <span class="label-text-alt">Auto-trigger re-order when stock falls below this level</span>
                    </label>
                </div>

                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Description</span>
                    </label>
                    <textarea name="restock_desc" class="textarea textarea-bordered h-20" placeholder="Re-order notes..."></textarea>
                </div>

                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" onclick="closeCreateReorderModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-plus mr-2"></i>Create Re-Order
                    </button>
                </div>
            </form>
        </div>
    </div>

<script>
    // ==================== CONFIGURATION ====================
    const API_BASE_URL = 'http://localhost:8001/api/psm';

    let reorders = [];
    let orders = [];
    let products = [];
    let shops = [];

    document.addEventListener('DOMContentLoaded', function() {
        loadReorders();
        loadFormData();
    });

    async function loadReorders() {
        try {
            const response = await fetch(`${API_BASE_URL}/reorders`);
            const data = await response.json();
            
            if (response.ok) {
                reorders = data;
                renderReorders();
                updateReorderStats();
            } else {
                throw new Error(data.message || 'Failed to load re-orders');
            }
        } catch (error) {
            console.error('Error loading re-orders:', error);
            Swal.fire('Error', 'Failed to load re-orders: ' + error.message, 'error');
        }
    }

    async function loadFormData() {
        try {
            // Load orders for dropdown
            const ordersResponse = await fetch(`${API_BASE_URL}/orders`);
            if (ordersResponse.ok) {
                orders = await ordersResponse.json();
            }

            // Load products for dropdown
            const productsResponse = await fetch(`${API_BASE_URL}/products`);
            if (productsResponse.ok) {
                products = await productsResponse.json();
            }

            // Load shops for dropdown
            const shopsResponse = await fetch(`${API_BASE_URL}/shops`);
            if (shopsResponse.ok) {
                shops = await shopsResponse.json();
            }
        } catch (error) {
            console.error('Error loading form data:', error);
        }
    }

    function renderReorders() {
        const tbody = document.getElementById('reorders-table-body');
        
        if (reorders.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="10" class="text-center py-8">
                        <i class="bx bx-refresh text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500">No re-orders found</p>
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = reorders.map(reorder => `
            <tr>
                <td class="font-mono font-semibold">#${reorder.restock_id.toString().padStart(6, '0')}</td>
                <td>
                    <div class="font-semibold">${reorder.product?.prod_name || 'N/A'}</div>
                    <div class="text-sm text-gray-500">Shop: ${reorder.shop?.shop_name || 'N/A'}</div>
                </td>
                <td>${reorder.shop?.vendor?.ven_name || 'N/A'}</td>
                <td>${reorder.quantity}</td>
                <td class="font-semibold">₱${parseFloat(reorder.total_amount).toFixed(2)}</td>
                <td>
                    <span class="badge ${getBudgetStatusBadgeClass(reorder.budget_approval_status)}">
                        ${reorder.budget_approval_status}
                    </span>
                </td>
                <td>
                    <span class="badge ${getReorderStatusBadgeClass(reorder.restock_status)}">
                        ${reorder.restock_status}
                    </span>
                </td>
                <td>
                    <span class="badge ${reorder.order_id ? 'badge-info' : 'badge-warning'}">
                        ${reorder.order_id ? 'Manual' : 'Auto'}
                    </span>
                </td>
                <td>${new Date(reorder.created_at).toLocaleDateString()}</td>
                <td>
                    <div class="flex space-x-1">
                        <button class="btn btn-xs btn-outline btn-info" onclick="viewReorderDetails(${reorder.restock_id})">
                            <i class="bx bx-show"></i>
                        </button>
                        ${reorder.budget_approval_status === 'pending' ? `
                            <button class="btn btn-xs btn-outline btn-warning" onclick="editReorder(${reorder.restock_id})">
                                <i class="bx bx-edit"></i>
                            </button>
                        ` : ''}
                    </div>
                </td>
            </tr>
        `).join('');
    }

    function getBudgetStatusBadgeClass(status) {
        switch(status) {
            case 'approved': return 'badge-success';
            case 'rejected': return 'badge-error';
            case 'pending': return 'badge-warning';
            default: return 'badge-ghost';
        }
    }

    function getReorderStatusBadgeClass(status) {
        switch(status) {
            case 'settled': return 'badge-success';
            case 'auto_triggered': return 'badge-info';
            case 'pending': return 'badge-warning';
            default: return 'badge-ghost';
        }
    }

    function updateReorderStats() {
        document.getElementById('total-reorders').textContent = reorders.length;
        document.getElementById('pending-reorders').textContent = 
            reorders.filter(r => r.restock_status === 'pending').length;
        document.getElementById('auto-reorders').textContent = 
            reorders.filter(r => r.restock_status === 'auto_triggered').length;
        document.getElementById('settled-reorders').textContent = 
            reorders.filter(r => r.restock_status === 'settled').length;
    }

    function viewReorderDetails(restockId) {
        const reorder = reorders.find(r => r.restock_id === restockId);
        if (!reorder) return;

        Swal.fire({
            title: `Re-Order #${reorder.restock_id.toString().padStart(6, '0')}`,
            html: `
                <div class="text-left space-y-2">
                    <p><strong>Product:</strong> ${reorder.product?.prod_name || 'N/A'}</p>
                    <p><strong>Vendor:</strong> ${reorder.shop?.vendor?.ven_name || 'N/A'}</p>
                    <p><strong>Shop:</strong> ${reorder.shop?.shop_name || 'N/A'}</p>
                    <p><strong>Quantity:</strong> ${reorder.quantity}</p>
                    <p><strong>Restock Price:</strong> ₱${parseFloat(reorder.restock_price).toFixed(2)}</p>
                    <p><strong>Total Amount:</strong> ₱${parseFloat(reorder.total_amount).toFixed(2)}</p>
                    <p><strong>Budget Status:</strong> <span class="badge ${getBudgetStatusBadgeClass(reorder.budget_approval_status)}">${reorder.budget_approval_status}</span></p>
                    <p><strong>Re-Order Status:</strong> <span class="badge ${getReorderStatusBadgeClass(reorder.restock_status)}">${reorder.restock_status}</span></p>
                    <p><strong>Trigger Threshold:</strong> ${reorder.trigger_threshold}</p>
                    <p><strong>SWS Stock ID:</strong> ${reorder.sws_stock_id}</p>
                    <p><strong>Description:</strong> ${reorder.restock_desc || 'No description'}</p>
                    <p><strong>Created:</strong> ${new Date(reorder.created_at).toLocaleString()}</p>
                </div>
            `,
            icon: 'info',
            confirmButtonText: 'Close'
        });
    }

    function openCreateReorderModal() {
        // Populate dropdowns
        populateOrderDropdown();
        populateProductDropdown();
        populateShopDropdown();
        
        document.getElementById('createReorderModal').classList.add('modal-open');
    }

    function closeCreateReorderModal() {
        document.getElementById('createReorderModal').classList.remove('modal-open');
        document.getElementById('createReorderForm').reset();
    }

    function populateOrderDropdown() {
        const select = document.getElementById('orderSelect');
        select.innerHTML = '<option value="">Select Original Order</option>' +
            orders.map(order => `
                <option value="${order.order_id}">
                    Order #${order.order_id.toString().padStart(6, '0')} - ${order.product?.prod_name || 'N/A'}
                </option>
            `).join('');
    }

    function populateProductDropdown() {
        const select = document.getElementById('productSelect');
        select.innerHTML = '<option value="">Select Product</option>' +
            products.map(product => `
                <option value="${product.prod_id}" data-shop="${product.shop_id}">
                    ${product.prod_name} - ${product.shop?.shop_name || 'N/A'}
                </option>
            `).join('');
    }

    function populateShopDropdown() {
        const select = document.getElementById('shopSelect');
        select.innerHTML = '<option value="">Select Shop</option>' +
            shops.map(shop => `
                <option value="${shop.shop_id}">
                    ${shop.shop_name} - ${shop.vendor?.ven_name || 'N/A'}
                </option>
            `).join('');
    }

    // Auto-select shop when product is selected
    document.getElementById('productSelect').addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        const shopId = selectedOption.getAttribute('data-shop');
        if (shopId) {
            document.getElementById('shopSelect').value = shopId;
        }
    });

    document.getElementById('createReorderForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const reorderData = Object.fromEntries(formData);
        
        // Convert numeric fields
        reorderData.quantity = parseInt(reorderData.quantity);
        reorderData.restock_price = parseFloat(reorderData.restock_price);
        reorderData.sws_stock_id = parseInt(reorderData.sws_stock_id);
        reorderData.trigger_threshold = parseInt(reorderData.trigger_threshold);
        
        // Remove empty order_id
        if (!reorderData.order_id) {
            delete reorderData.order_id;
        }

        try {
            const response = await fetch(`${API_BASE_URL}/reorders`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(reorderData)
            });

            const result = await response.json();

            if (response.ok) {
                Swal.fire('Success', 'Re-order created successfully!', 'success');
                closeCreateReorderModal();
                loadReorders();
            } else {
                throw new Error(result.message || 'Failed to create re-order');
            }
        } catch (error) {
            Swal.fire('Error', 'Failed to create re-order: ' + error.message, 'error');
        }
    });

    function editReorder(restockId) {
        Swal.fire('Info', 'Edit functionality will be implemented soon!', 'info');
    }
</script>
@endsection