@extends('layouts.app')

@section('title', 'Place Order Management')

@section('content')
    <div class="module-content bg-white rounded-xl p-6 shadow block">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Place Order Management</h2>
            <div class="text-sm text-gray-600">
                Process approved orders and manage order fulfillment
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="stat bg-base-100 rounded-lg">
                <div class="stat-figure text-primary">
                    <i class="bx bx-cart text-3xl"></i>
                </div>
                <div class="stat-title">Approved Orders</div>
                <div class="stat-value text-primary" id="approved-orders">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg">
                <div class="stat-figure text-info">
                    <i class="bx bx-send text-3xl"></i>
                </div>
                <div class="stat-title">Issued</div>
                <div class="stat-value text-info" id="issued-orders">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg">
                <div class="stat-figure text-success">
                    <i class="bx bx-package text-3xl"></i>
                </div>
                <div class="stat-title">Received</div>
                <div class="stat-value text-success" id="received-orders">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg">
                <div class="stat-figure text-warning">
                    <i class="bx bx-time text-3xl"></i>
                </div>
                <div class="stat-title">Pending</div>
                <div class="stat-value text-warning" id="pending-process">0</div>
            </div>
        </div>

        <!-- Orders for Processing -->
        <div class="bg-base-100 rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Orders Ready for Processing</h3>
            
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr class="bg-base-200">
                            <th>Order ID</th>
                            <th>Product</th>
                            <th>Vendor</th>
                            <th>Quantity</th>
                            <th>Total Amount</th>
                            <th>Current Status</th>
                            <th>Order Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="process-orders-body">
                        <tr>
                            <td colspan="8" class="text-center py-8">
                                <div class="loading loading-spinner loading-lg"></div>
                                <p class="text-gray-500 mt-2">Loading orders...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Status Update Modal -->
    <div id="statusModal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Update Order Status</h3>
            <div id="orderDetails" class="space-y-2 mb-4">
                <!-- Order details will be populated here -->
            </div>
            <form id="statusForm" class="space-y-4">
                <input type="hidden" id="processOrderId">
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">New Status</span>
                    </label>
                    <select name="order_status" class="select select-bordered w-full" id="statusSelect">
                        <option value="issued">Issued to Vendor</option>
                        <option value="received">Received from Vendor</option>
                        <option value="settled">Settled/Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Notes</span>
                    </label>
                    <textarea name="order_desc" class="textarea textarea-bordered h-20" placeholder="Add status update notes..."></textarea>
                </div>
                
                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" onclick="closeStatusModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-check-circle mr-2"></i>Update Status
                    </button>
                </div>
            </form>
        </div>
    </div>

<script>
    // ==================== CONFIGURATION ====================
    const API_BASE_URL = 'http://localhost:8001/api/psm';

    let processOrders = [];

    document.addEventListener('DOMContentLoaded', function() {
        loadProcessOrders();
    });

    async function loadProcessOrders() {
        try {
            const response = await fetch(`${API_BASE_URL}/orders`);
            const data = await response.json();
            
            if (response.ok) {
                // Filter only approved orders that are not settled or cancelled
                processOrders = data.filter(order => 
                    order.budget_approval_status === 'approved' && 
                    !['settled', 'cancelled'].includes(order.order_status)
                );
                renderProcessOrders();
                updateProcessStats();
            } else {
                throw new Error(data.message || 'Failed to load orders');
            }
        } catch (error) {
            console.error('Error loading process orders:', error);
            Swal.fire('Error', 'Failed to load orders: ' + error.message, 'error');
        }
    }

    function renderProcessOrders() {
        const tbody = document.getElementById('process-orders-body');
        
        if (processOrders.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center py-8">
                        <i class="bx bx-check-circle text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500">No orders ready for processing</p>
                        <p class="text-sm text-gray-400 mt-1">All approved orders have been processed</p>
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = processOrders.map(order => `
            <tr>
                <td class="font-mono font-semibold">#${order.order_id.toString().padStart(6, '0')}</td>
                <td>
                    <div class="font-semibold">${order.product?.prod_name || 'N/A'}</div>
                    <div class="text-sm text-gray-500">₱${parseFloat(order.order_price).toFixed(2)} each</div>
                </td>
                <td>
                    <div>${order.shop?.vendor?.ven_name || 'N/A'}</div>
                    <div class="text-sm text-gray-500">${order.shop?.shop_name || 'N/A'}</div>
                </td>
                <td>${order.quantity}</td>
                <td class="font-semibold">₱${parseFloat(order.total_amount).toFixed(2)}</td>
                <td>
                    <span class="badge ${getProcessStatusBadgeClass(order.order_status)}">
                        ${order.order_status}
                    </span>
                </td>
                <td>${new Date(order.created_at).toLocaleDateString()}</td>
                <td>
                    <div class="flex space-x-1">
                        <button class="btn btn-xs btn-outline btn-info" onclick="viewProcessOrderDetails(${order.order_id})">
                            <i class="bx bx-show"></i>
                        </button>
                        <button class="btn btn-xs btn-outline btn-warning" onclick="openStatusModal(${order.order_id})">
                            <i class="bx bx-edit"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    }

    function getProcessStatusBadgeClass(status) {
        switch(status) {
            case 'issued': return 'badge-info';
            case 'received': return 'badge-success';
            case 'settled': return 'badge-primary';
            case 'cancelled': return 'badge-error';
            case 'pending': return 'badge-warning';
            default: return 'badge-ghost';
        }
    }

    function updateProcessStats() {
        const approved = processOrders.filter(o => o.budget_approval_status === 'approved');
        const issued = processOrders.filter(o => o.order_status === 'issued');
        const received = processOrders.filter(o => o.order_status === 'received');
        const pending = processOrders.filter(o => o.order_status === 'pending');

        document.getElementById('approved-orders').textContent = approved.length;
        document.getElementById('issued-orders').textContent = issued.length;
        document.getElementById('received-orders').textContent = received.length;
        document.getElementById('pending-process').textContent = pending.length;
    }

    function viewProcessOrderDetails(orderId) {
        const order = processOrders.find(o => o.order_id === orderId);
        if (!order) return;

        Swal.fire({
            title: `Order #${order.order_id.toString().padStart(6, '0')}`,
            html: `
                <div class="text-left space-y-2">
                    <p><strong>Product:</strong> ${order.product?.prod_name || 'N/A'}</p>
                    <p><strong>Vendor:</strong> ${order.shop?.vendor?.ven_name || 'N/A'}</p>
                    <p><strong>Shop:</strong> ${order.shop?.shop_name || 'N/A'}</p>
                    <p><strong>Quantity:</strong> ${order.quantity}</p>
                    <p><strong>Unit Price:</strong> ₱${parseFloat(order.order_price).toFixed(2)}</p>
                    <p><strong>Total Amount:</strong> ₱${parseFloat(order.total_amount).toFixed(2)}</p>
                    <p><strong>Budget Status:</strong> <span class="badge badge-success">${order.budget_approval_status}</span></p>
                    <p><strong>Order Status:</strong> <span class="badge ${getProcessStatusBadgeClass(order.order_status)}">${order.order_status}</span></p>
                    <p><strong>Description:</strong> ${order.order_desc || 'No description'}</p>
                    <p><strong>Created:</strong> ${new Date(order.created_at).toLocaleString()}</p>
                </div>
            `,
            icon: 'info',
            confirmButtonText: 'Close'
        });
    }

    function openStatusModal(orderId) {
        const order = processOrders.find(o => o.order_id === orderId);
        if (!order) return;

        document.getElementById('processOrderId').value = order.order_id;

        // Populate order details
        document.getElementById('orderDetails').innerHTML = `
            <div class="bg-base-200 p-3 rounded-lg">
                <p><strong>Order:</strong> #${order.order_id.toString().padStart(6, '0')}</p>
                <p><strong>Product:</strong> ${order.product?.prod_name || 'N/A'}</p>
                <p><strong>Vendor:</strong> ${order.shop?.vendor?.ven_name || 'N/A'}</p>
                <p><strong>Current Status:</strong> <span class="badge ${getProcessStatusBadgeClass(order.order_status)}">${order.order_status}</span></p>
            </div>
        `;

        // Set current status as selected
        document.getElementById('statusSelect').value = order.order_status;
        document.getElementById('statusModal').classList.add('modal-open');
    }

    function closeStatusModal() {
        document.getElementById('statusModal').classList.remove('modal-open');
    }

    document.getElementById('statusForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const orderId = document.getElementById('processOrderId').value;

        const updateData = {
            order_status: formData.get('order_status'),
            order_desc: formData.get('order_desc')
        };

        // If status is settled, add settled_at timestamp
        if (updateData.order_status === 'settled') {
            updateData.settled_at = new Date().toISOString();
        }

        try {
            const response = await fetch(`${API_BASE_URL}/orders/${orderId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(updateData)
            });

            if (response.ok) {
                Swal.fire('Success', 'Order status updated successfully!', 'success');
                closeStatusModal();
                loadProcessOrders();
            } else {
                throw new Error('Failed to update order status');
            }
        } catch (error) {
            Swal.fire('Error', 'Failed to update order status: ' + error.message, 'error');
        }
    });
</script>
@endsection