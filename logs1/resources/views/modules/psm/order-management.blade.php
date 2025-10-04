@extends('layouts.app')

@section('title', 'Order Management')

@section('content')
    <div class="module-content bg-white rounded-xl p-6 shadow block">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Order Management</h2>
            <div class="flex items-center space-x-4">
                <div class="form-control">
                    <input type="text" placeholder="Search orders..." class="input input-bordered w-64" id="searchOrders">
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="stat bg-base-100 rounded-lg">
                <div class="stat-figure text-primary">
                    <i class="bx bx-cart text-3xl"></i>
                </div>
                <div class="stat-title">Total Orders</div>
                <div class="stat-value text-primary" id="total-orders">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg">
                <div class="stat-figure text-info">
                    <i class="bx bx-time text-3xl"></i>
                </div>
                <div class="stat-title">Pending</div>
                <div class="stat-value text-info" id="pending-orders">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg">
                <div class="stat-figure text-success">
                    <i class="bx bx-check-circle text-3xl"></i>
                </div>
                <div class="stat-title">Approved</div>
                <div class="stat-value text-success" id="approved-orders">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg">
                <div class="stat-figure text-error">
                    <i class="bx bx-x-circle text-3xl"></i>
                </div>
                <div class="stat-title">Rejected</div>
                <div class="stat-value text-error" id="rejected-orders">0</div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabs tabs-boxed mb-6 bg-base-200 p-1">
            <button class="tab tab-active" data-tab="all">All Orders</button>
            <button class="tab" data-tab="pending">Pending</button>
            <button class="tab" data-tab="approved">Approved</button>
            <button class="tab" data-tab="rejected">Rejected</button>
            <button class="tab" data-tab="received">Received</button>
            <button class="tab" data-tab="settled">Settled</button>
        </div>

        <!-- Orders Table -->
        <div class="overflow-x-auto bg-base-100 rounded-lg">
            <table class="table table-zebra w-full">
                <thead>
                    <tr class="bg-base-200">
                        <th>Order ID</th>
                        <th>Product</th>
                        <th>Vendor</th>
                        <th>Quantity</th>
                        <th>Total Amount</th>
                        <th>Budget Status</th>
                        <th>Order Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="orders-table-body">
                    <tr>
                        <td colspan="9" class="text-center py-8">
                            <div class="loading loading-spinner loading-lg"></div>
                            <p class="text-gray-500 mt-2">Loading orders...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

<script>
    // ==================== CONFIGURATION ====================
    const API_BASE_URL = 'http://localhost:8001/api/psm';

    let orders = [];
    let currentTab = 'all';

    document.addEventListener('DOMContentLoaded', function() {
        loadOrders();
        setupTabs();
        
        document.getElementById('searchOrders').addEventListener('input', function(e) {
            filterOrders(e.target.value);
        });
    });

    function setupTabs() {
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', function() {
                // Update active tab
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('tab-active'));
                this.classList.add('tab-active');
                
                currentTab = this.getAttribute('data-tab');
                filterOrdersByTab();
            });
        });
    }

    async function loadOrders() {
        try {
            const response = await fetch(`${API_BASE_URL}/orders`);
            const data = await response.json();
            
            if (response.ok) {
                orders = data;
                filterOrdersByTab();
                updateStats();
            } else {
                throw new Error(data.message || 'Failed to load orders');
            }
        } catch (error) {
            console.error('Error loading orders:', error);
            Swal.fire('Error', 'Failed to load orders: ' + error.message, 'error');
        }
    }

    function filterOrdersByTab() {
        let filteredOrders = orders;

        if (currentTab !== 'all') {
            if (currentTab === 'pending') {
                filteredOrders = orders.filter(order => order.budget_approval_status === 'pending');
            } else if (currentTab === 'approved') {
                filteredOrders = orders.filter(order => order.budget_approval_status === 'approved');
            } else if (currentTab === 'rejected') {
                filteredOrders = orders.filter(order => order.budget_approval_status === 'rejected');
            } else {
                filteredOrders = orders.filter(order => order.order_status === currentTab);
            }
        }

        renderOrders(filteredOrders);
    }

    function filterOrders(searchTerm) {
        let filteredOrders = orders;

        if (currentTab !== 'all') {
            if (currentTab === 'pending') {
                filteredOrders = orders.filter(order => order.budget_approval_status === 'pending');
            } else if (currentTab === 'approved') {
                filteredOrders = orders.filter(order => order.budget_approval_status === 'approved');
            } else if (currentTab === 'rejected') {
                filteredOrders = orders.filter(order => order.budget_approval_status === 'rejected');
            } else {
                filteredOrders = orders.filter(order => order.order_status === currentTab);
            }
        }

        if (searchTerm) {
            filteredOrders = filteredOrders.filter(order => 
                order.order_id.toString().includes(searchTerm) ||
                order.product?.prod_name.toLowerCase().includes(searchTerm.toLowerCase()) ||
                order.shop?.vendor?.ven_name.toLowerCase().includes(searchTerm.toLowerCase())
            );
        }

        renderOrders(filteredOrders);
    }

    function renderOrders(ordersToRender) {
        const tbody = document.getElementById('orders-table-body');
        
        if (ordersToRender.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="9" class="text-center py-8">
                        <i class="bx bx-package text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500">No orders found</p>
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = ordersToRender.map(order => `
            <tr>
                <td class="font-mono font-semibold">#${order.order_id.toString().padStart(6, '0')}</td>
                <td>
                    <div class="font-semibold">${order.product?.prod_name || 'N/A'}</div>
                    <div class="text-sm text-gray-500">₱${parseFloat(order.order_price).toFixed(2)} each</div>
                </td>
                <td>${order.shop?.vendor?.ven_name || 'N/A'}</td>
                <td>${order.quantity}</td>
                <td class="font-semibold">₱${parseFloat(order.total_amount).toFixed(2)}</td>
                <td>
                    <span class="badge ${getBudgetStatusBadgeClass(order.budget_approval_status)}">
                        ${order.budget_approval_status}
                    </span>
                </td>
                <td>
                    <span class="badge ${getOrderStatusBadgeClass(order.order_status)}">
                        ${order.order_status}
                    </span>
                </td>
                <td>${new Date(order.created_at).toLocaleDateString()}</td>
                <td>
                    <div class="flex space-x-1">
                        <button class="btn btn-xs btn-outline btn-info" onclick="viewOrderDetails(${order.order_id})">
                            <i class="bx bx-show"></i>
                        </button>
                        ${order.budget_approval_status === 'approved' && order.order_status === 'pending' ? `
                            <button class="btn btn-xs btn-outline btn-success" onclick="updateOrderStatus(${order.order_id}, 'issued')">
                                <i class="bx bx-send"></i>
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

    function getOrderStatusBadgeClass(status) {
        switch(status) {
            case 'settled': return 'badge-success';
            case 'received': return 'badge-info';
            case 'issued': return 'badge-warning';
            case 'cancelled': return 'badge-error';
            case 'pending': return 'badge-ghost';
            default: return 'badge-ghost';
        }
    }

    function updateStats() {
        document.getElementById('total-orders').textContent = orders.length;
        document.getElementById('pending-orders').textContent = 
            orders.filter(o => o.budget_approval_status === 'pending').length;
        document.getElementById('approved-orders').textContent = 
            orders.filter(o => o.budget_approval_status === 'approved').length;
        document.getElementById('rejected-orders').textContent = 
            orders.filter(o => o.budget_approval_status === 'rejected').length;
    }

    function viewOrderDetails(orderId) {
        const order = orders.find(o => o.order_id === orderId);
        if (!order) return;

        Swal.fire({
            title: `Order #${order.order_id.toString().padStart(6, '0')}`,
            html: `
                <div class="text-left space-y-2">
                    <p><strong>Product:</strong> ${order.product?.prod_name || 'N/A'}</p>
                    <p><strong>Vendor:</strong> ${order.shop?.vendor?.ven_name || 'N/A'}</p>
                    <p><strong>Quantity:</strong> ${order.quantity}</p>
                    <p><strong>Unit Price:</strong> ₱${parseFloat(order.order_price).toFixed(2)}</p>
                    <p><strong>Total Amount:</strong> ₱${parseFloat(order.total_amount).toFixed(2)}</p>
                    <p><strong>Budget Status:</strong> <span class="badge ${getBudgetStatusBadgeClass(order.budget_approval_status)}">${order.budget_approval_status}</span></p>
                    <p><strong>Order Status:</strong> <span class="badge ${getOrderStatusBadgeClass(order.order_status)}">${order.order_status}</span></p>
                    <p><strong>Description:</strong> ${order.order_desc || 'No description'}</p>
                    <p><strong>Created:</strong> ${new Date(order.created_at).toLocaleString()}</p>
                </div>
            `,
            icon: 'info',
            confirmButtonText: 'Close'
        });
    }

    async function updateOrderStatus(orderId, status) {
        try {
            const response = await fetch(`${API_BASE_URL}/orders/${orderId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ order_status: status })
            });

            if (response.ok) {
                Swal.fire('Success', 'Order status updated successfully!', 'success');
                loadOrders();
            } else {
                throw new Error('Failed to update order status');
            }
        } catch (error) {
            Swal.fire('Error', 'Failed to update order status: ' + error.message, 'error');
        }
    }
</script>
@endsection