@extends('layouts.app')

@section('title', 'Logistics Projects - PLT')

@section('content')
    <div class="module-content bg-white rounded-xl p-6 shadow block">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Logistics Projects</h2>
            <button class="btn btn-primary" id="addLogisticsBtn">
                <i class="bx bx-plus mr-2"></i>New Delivery
            </button>
        </div>
        <p>user admin and manager</p>
        <p>edit remove</p>
        <p>delete sadmin</p>
        <p>update only manager and admin</p>
        <p>view staf</p>
        <p></p>
        <p></p>

        <!-- Stats Section -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-primary">
                <div class="stat-figure text-primary">
                    <i class="bx bx-package text-3xl"></i>
                </div>
                <div class="stat-title">Total Deliveries</div>
                <div class="stat-value text-primary" id="total-deliveries">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-warning">
                <div class="stat-figure text-warning">
                    <i class="bx bx-time text-3xl"></i>
                </div>
                <div class="stat-title">Scheduled</div>
                <div class="stat-value text-warning" id="scheduled-deliveries">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-info">
                <div class="stat-figure text-info">
                    <i class="bx bx-trending-up text-3xl"></i>
                </div>
                <div class="stat-title">In Transit</div>
                <div class="stat-value text-info" id="transit-deliveries">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-success">
                <div class="stat-figure text-success">
                    <i class="bx bx-check-circle text-3xl"></i>
                </div>
                <div class="stat-title">Delivered</div>
                <div class="stat-value text-success" id="delivered-deliveries">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-secondary">
                <div class="stat-figure text-secondary">
                    <i class="bx bx-calendar text-3xl"></i>
                </div>
                <div class="stat-title">This Week</div>
                <div class="stat-value text-secondary" id="recent-deliveries">0</div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="flex gap-4 mb-6">
            <div class="form-control flex-1">
                <input type="text" placeholder="Search deliveries..." class="input input-bordered w-full" id="searchLogistics">
            </div>
            <select class="select select-bordered" id="statusFilter">
                <option value="">All Status</option>
                <option value="Scheduled">Scheduled</option>
                <option value="In Transit">In Transit</option>
                <option value="Delivered">Delivered</option>
            </select>
            <input type="date" class="input input-bordered" id="dateFilter" placeholder="Delivery Date">
            <button class="btn btn-outline" id="clearFilters">Clear</button>
        </div>

        <!-- Logistics Projects Table -->
        <div class="overflow-x-auto bg-base-100 rounded-lg">
            <table class="table table-zebra w-full">
                <thead>
                    <tr class="bg-gray-900 text-white">
                        <th>Delivery ID</th>
                        <th>Driver</th>
                        <th>Destination</th>
                        <th>Items</th>
                        <th>Status</th>
                        <th>Receiver</th>
                        <th>Delivery Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="logistics-table-body">
                    <tr>
                        <td colspan="9" class="text-center py-8">
                            <div class="loading loading-spinner loading-lg"></div>
                            <p class="text-gray-500 mt-2">Loading logistics projects...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add/Edit Logistics Modal -->
    <div id="logisticsModal" class="modal modal-lg">
        <div class="modal-box max-w-4xl p-0 overflow-visible">
            <div class="flex justify-between items-center bg-green-700 p-4 rounded-t-lg">
                <h3 class="font-bold text-white text-lg" id="logisticsModalTitle">New Delivery</h3>
                <button class="btn btn-sm btn-circle btn-ghost hover:bg-white/20 text-white" id="closeLogisticsModalX">✕</button>
            </div>
            <div class="p-4 max-h-[70vh] overflow-y-auto">
                <form id="logisticsForm" class="space-y-4">
                    @csrf
                    <input type="hidden" id="logisticsId" name="logistics_id">
                    
                    <!-- Auto-generated Delivery ID -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Delivery ID</span>
                        </label>
                        <input type="text" id="deliveryId" class="input input-bordered input-sm w-full bg-gray-100" 
                               readonly placeholder="Auto-generated (DLY00001)">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Vehicle ID *</span>
                            </label>
                            <input type="text" id="vehicleId" name="vehicle_id" class="input input-bordered input-sm w-full" 
                                   placeholder="e.g., CAR00001" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Driver Name *</span>
                            </label>
                            <input type="text" id="driverName" name="driver_name" class="input input-bordered input-sm w-full" 
                                   placeholder="Enter driver name" required>
                        </div>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Route *</span>
                        </label>
                        <input type="text" id="route" name="route" class="input input-bordered input-sm w-full" 
                               placeholder="e.g., From Warehouse to Branch A" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Destination *</span>
                            </label>
                            <input type="text" id="destination" name="destination" class="input input-bordered input-sm w-full" 
                                   placeholder="e.g., Branch A" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Receiver Name *</span>
                            </label>
                            <input type="text" id="receiverName" name="receiver_name" class="input input-bordered input-sm w-full" 
                                   placeholder="Enter receiver name" required>
                        </div>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Items *</span>
                        </label>
                        <textarea id="items" name="items" class="textarea textarea-bordered textarea-sm h-16" 
                                  placeholder="List items being delivered (e.g., 10 Laptops, 5 Printers)" required></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Status *</span>
                            </label>
                            <select id="status" name="status" class="select select-bordered select-sm w-full" required>
                                <option value="Scheduled">Scheduled</option>
                                <option value="In Transit">In Transit</option>
                                <option value="Delivered">Delivered</option>
                            </select>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Delivery Date *</span>
                            </label>
                            <input type="date" id="deliveryDate" name="delivery_date" class="input input-bordered input-sm w-full" required>
                        </div>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Notes</span>
                        </label>
                        <textarea id="notes" name="notes" class="textarea textarea-bordered textarea-sm h-16" 
                                  placeholder="Additional notes..."></textarea>
                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-action flex justify-end space-x-3 pt-4 border-t">
                        <button type="button" class="btn btn-ghost btn-sm hover:bg-gray-100 transition-colors px-4" id="closeLogisticsModal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm bg-gradient-to-r from-primary to-primary/90 hover:from-primary/90 hover:to-primary transition-all duration-300 shadow-lg px-4" id="logisticsSubmitBtn">
                            <i class="bx bx-save mr-1"></i><span id="logisticsModalSubmitText">Save Delivery</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Logistics Modal -->
    <div id="viewLogisticsModal" class="modal modal-lg">
        <div class="modal-box max-w-4xl p-0 overflow-visible">
            <div class="flex justify-between items-center bg-green-700 p-4 rounded-t-lg">
                <h3 class="font-bold text-white text-lg">Delivery Details</h3>
                <button class="btn btn-sm btn-circle btn-ghost hover:bg-white/20 text-white" id="closeViewLogisticsModalX">✕</button>
            </div>
            <div class="p-4 max-h-[70vh] overflow-y-auto">
                <div class="space-y-4" id="logisticsDetails">
                    <!-- Logistics details will be populated here -->
                </div>
                <div class="modal-action flex justify-end pt-4 border-t">
                    <button type="button" class="btn btn-ghost btn-sm hover:bg-gray-100 transition-colors px-4" id="closeViewLogisticsModal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Status Update Modal -->
    <div id="statusModal" class="modal">
        <div class="modal-box max-w-sm">
            <h3 class="font-bold text-lg mb-4">Update Delivery Status</h3>
            <form id="statusForm">
                <input type="hidden" id="statusDeliveryId">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Status</span>
                    </label>
                    <select id="newStatus" class="select select-bordered select-sm w-full" required>
                        <option value="Scheduled">Scheduled</option>
                        <option value="In Transit">In Transit</option>
                        <option value="Delivered">Delivered</option>
                    </select>
                </div>
                <div class="modal-action">
                    <button type="button" class="btn btn-ghost btn-sm" id="closeStatusModal">Cancel</button>
                    <button type="submit" class="btn btn-primary btn-sm">Update Status</button>
                </div>
            </form>
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
    let logistics = [];

    // ==================== CONFIGURATION ====================
    const API_BASE_URL = 'http://localhost:8001/api/plt/logistics';

    // Utility functions
    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        return new Date(dateString).toLocaleDateString('en-US', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit'
        });
    }

    function getStatusBadge(status) {
        const statusClasses = {
            'Scheduled': 'bg-yellow-400 uppercase',
            'In Transit': 'bg-blue-400 uppercase',
            'Delivered': 'bg-green-600 uppercase'
        };
        
        return `<span class="badge text-white font-bold tracking-wider text-xs px-3 py-2 ${statusClasses[status] || 'bg-gray-400'} border-0">
            ${status}
        </span>`;
    }

    // Show loading modal
    function showLoadingModal(title = 'Processing...') {
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

    // Load data on page load
    document.addEventListener('DOMContentLoaded', function() {
        initializeEventListeners();
        loadLogistics();
        loadStats();
    });

    function initializeEventListeners() {
        // Add logistics button
        document.getElementById('addLogisticsBtn').addEventListener('click', openAddLogisticsModal);

        // Close modal buttons
        document.getElementById('closeLogisticsModal').addEventListener('click', closeLogisticsModal);
        document.getElementById('closeLogisticsModalX').addEventListener('click', closeLogisticsModal);
        document.getElementById('closeViewLogisticsModal').addEventListener('click', closeViewLogisticsModal);
        document.getElementById('closeViewLogisticsModalX').addEventListener('click', closeViewLogisticsModal);
        document.getElementById('closeStatusModal').addEventListener('click', closeStatusModal);

        // Form submission
        document.getElementById('logisticsForm').addEventListener('submit', handleLogisticsSubmit);
        document.getElementById('statusForm').addEventListener('submit', handleStatusUpdate);

        // Search and filter
        document.getElementById('searchLogistics').addEventListener('input', filterLogistics);
        document.getElementById('statusFilter').addEventListener('change', filterLogistics);
        document.getElementById('dateFilter').addEventListener('change', filterLogistics);
        document.getElementById('clearFilters').addEventListener('click', clearFilters);
    }

    async function loadLogistics() {
        try {
            showLogisticsLoadingState();
            const response = await fetch(`${API_BASE_URL}`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const result = await response.json();
            
            if (result.success) {
                logistics = result.data || [];
                renderLogistics(logistics);
            } else {
                throw new Error(result.message || 'Failed to load logistics projects');
            }
        } catch (error) {
            console.error('Error loading logistics projects:', error);
            showLogisticsErrorState('Failed to load logistics projects: ' + error.message);
        }
    }

    async function loadStats() {
        try {
            const response = await fetch(`${API_BASE_URL}/stats`);
            
            if (response.ok) {
                const result = await response.json();
                if (result.success) {
                    updateStats(result.data);
                }
            }
        } catch (error) {
            console.error('Error loading stats:', error);
        }
    }

    function showLogisticsLoadingState() {
        const tbody = document.getElementById('logistics-table-body');
        tbody.innerHTML = `
            <tr>
                <td colspan="9" class="text-center py-8">
                    <div class="loading loading-spinner loading-lg"></div>
                    <p class="text-gray-500 mt-2">Loading logistics projects...</p>
                </td>
            </tr>
        `;
    }

    function showLogisticsErrorState(message) {
        const tbody = document.getElementById('logistics-table-body');
        tbody.innerHTML = `
            <tr>
                <td colspan="9" class="text-center py-8">
                    <i class="bx bx-error text-4xl text-red-400 mb-2"></i>
                    <p class="text-red-500">${message}</p>
                    <button class="btn btn-sm btn-outline mt-2" onclick="loadLogistics()">Retry</button>
                </td>
            </tr>
        `;
    }

    function renderLogistics(logisticsData) {
        const tbody = document.getElementById('logistics-table-body');
        
        if (logisticsData.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="9" class="text-center py-8">
                        <i class="bx bx-package text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500">No logistics projects found</p>
                        <button class="btn btn-sm btn-primary mt-2" id="addFirstLogisticsBtn">Create First Delivery</button>
                    </td>
                </tr>
            `;
            document.getElementById('addFirstLogisticsBtn')?.addEventListener('click', openAddLogisticsModal);
            return;
        }

        tbody.innerHTML = logisticsData.map(logistic => {
            return `
            <tr>
                <td class="font-mono font-semibold text-sm">${logistic.delivery_id}</td>
                <td class="text-sm">${logistic.driver_name}</td>
                <td class="text-sm">${logistic.destination}</td>
                <td class="text-sm max-w-xs truncate" title="${logistic.items}">${logistic.items}</td>
                <td>${getStatusBadge(logistic.status)}</td>
                <td class="text-sm">${logistic.receiver_name}</td>
                <td class="text-sm">${formatDate(logistic.delivery_date)}</td>
                <td>
                    <div class="flex space-x-1">
                        <button title="View" class="btn btn-sm btn-circle btn-info view-logistics-btn" data-logistics-id="${logistic.delivery_id}">
                            <i class="bx bx-show-alt text-sm"></i>
                        </button>
                        <button title="Edit" class="btn btn-sm btn-circle btn-warning edit-logistics-btn" data-logistics-id="${logistic.delivery_id}">
                            <i class="bx bx-edit text-sm"></i>
                        </button>
                        <button title="Update Status" class="btn btn-sm btn-circle btn-secondary status-logistics-btn" data-logistics-id="${logistic.delivery_id}">
                            <i class="bx bx-refresh text-sm"></i>
                        </button>
                        <button title="Delete" class="btn btn-sm btn-circle btn-error delete-logistics-btn" data-logistics-id="${logistic.delivery_id}">
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
        document.querySelectorAll('.view-logistics-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const logisticsId = this.getAttribute('data-logistics-id');
                viewLogistics(logisticsId);
            });
        });

        document.querySelectorAll('.edit-logistics-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const logisticsId = this.getAttribute('data-logistics-id');
                editLogistics(logisticsId);
            });
        });

        document.querySelectorAll('.status-logistics-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const logisticsId = this.getAttribute('data-logistics-id');
                openStatusModal(logisticsId);
            });
        });

        document.querySelectorAll('.delete-logistics-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const logisticsId = this.getAttribute('data-logistics-id');
                deleteLogistics(logisticsId);
            });
        });
    }

    function updateStats(statsData) {
        document.getElementById('total-deliveries').textContent = statsData.total_deliveries || 0;
        document.getElementById('scheduled-deliveries').textContent = statsData.scheduled || 0;
        document.getElementById('transit-deliveries').textContent = statsData.in_transit || 0;
        document.getElementById('delivered-deliveries').textContent = statsData.delivered || 0;
        document.getElementById('recent-deliveries').textContent = statsData.recent_deliveries || 0;
    }

    function filterLogistics() {
        const searchTerm = document.getElementById('searchLogistics').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const dateFilter = document.getElementById('dateFilter').value;
        
        const filtered = logistics.filter(logistic => {
            const matchesSearch = searchTerm === '' || 
                logistic.delivery_id.toLowerCase().includes(searchTerm) ||
                logistic.vehicle_id.toLowerCase().includes(searchTerm) ||
                logistic.driver_name.toLowerCase().includes(searchTerm) ||
                logistic.destination.toLowerCase().includes(searchTerm) ||
                logistic.receiver_name.toLowerCase().includes(searchTerm) ||
                logistic.items.toLowerCase().includes(searchTerm);
            
            const matchesStatus = statusFilter === '' || logistic.status === statusFilter;
            
            const matchesDate = dateFilter === '' || 
                (logistic.delivery_date && logistic.delivery_date === dateFilter);
            
            return matchesSearch && matchesStatus && matchesDate;
        });
        
        renderLogistics(filtered);
    }

    function clearFilters() {
        document.getElementById('searchLogistics').value = '';
        document.getElementById('statusFilter').value = '';
        document.getElementById('dateFilter').value = '';
        filterLogistics();
    }

    // Modal Functions
    function openAddLogisticsModal() {
        document.getElementById('logisticsModalTitle').textContent = 'New Delivery';
        document.getElementById('logisticsModalSubmitText').textContent = 'Save Delivery';
        document.getElementById('logisticsForm').reset();
        document.getElementById('logisticsId').value = '';
        document.getElementById('deliveryId').value = 'Auto-generated';
        document.getElementById('status').value = 'Scheduled';
        document.getElementById('deliveryDate').value = new Date().toISOString().split('T')[0];
        
        document.getElementById('logisticsModal').classList.add('modal-open');
    }

    function closeLogisticsModal() {
        document.getElementById('logisticsModal').classList.remove('modal-open');
        document.getElementById('logisticsForm').reset();
    }

    function openViewLogisticsModal() {
        document.getElementById('viewLogisticsModal').classList.add('modal-open');
    }

    function closeViewLogisticsModal() {
        document.getElementById('viewLogisticsModal').classList.remove('modal-open');
    }

    function openStatusModal(logisticsId) {
        const logistic = logistics.find(l => l.delivery_id === logisticsId);
        if (!logistic) return;

        document.getElementById('statusDeliveryId').value = logisticsId;
        document.getElementById('newStatus').value = logistic.status;
        document.getElementById('statusModal').classList.add('modal-open');
    }

    function closeStatusModal() {
        document.getElementById('statusModal').classList.remove('modal-open');
    }

    // Logistics Actions
    function viewLogistics(logisticsId) {
        const logistic = logistics.find(l => l.delivery_id === logisticsId);
        if (!logistic) return;

        const logisticsDetails = `
            <div class="space-y-4">
                <!-- Basic Information -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <strong class="text-gray-700 text-xs">Delivery ID:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1 font-mono">${logistic.delivery_id}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Vehicle ID:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1 font-mono">${logistic.vehicle_id}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Driver Name:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${logistic.driver_name}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Receiver Name:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${logistic.receiver_name}</p>
                    </div>
                </div>

                <!-- Route and Destination -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <strong class="text-gray-700 text-xs">Route:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${logistic.route}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Destination:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${logistic.destination}</p>
                    </div>
                </div>

                <!-- Items -->
                <div>
                    <strong class="text-gray-700 text-xs">Items:</strong>
                    <p class="text-sm p-2 bg-gray-50 rounded border mt-1 whitespace-pre-wrap">${logistic.items}</p>
                </div>

                <!-- Status and Date -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <strong class="text-gray-700 text-xs">Status:</strong>
                        <p class="mt-1 p-2">${getStatusBadge(logistic.status)}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Delivery Date:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${formatDate(logistic.delivery_date)}</p>
                    </div>
                </div>

                <!-- Notes -->
                ${logistic.notes ? `
                <div>
                    <strong class="text-gray-700 text-xs">Notes:</strong>
                    <p class="text-sm p-2 bg-gray-50 rounded border mt-1 whitespace-pre-wrap">${logistic.notes}</p>
                </div>
                ` : ''}

                <!-- Timestamps -->
                <div class="grid grid-cols-2 gap-4">
                    ${logistic.created_at ? `
                    <div>
                        <strong class="text-gray-700 text-xs">Created:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${new Date(logistic.created_at).toLocaleString()}</p>
                    </div>
                    ` : ''}
                    ${logistic.updated_at ? `
                    <div>
                        <strong class="text-gray-700 text-xs">Last Updated:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${new Date(logistic.updated_at).toLocaleString()}</p>
                    </div>
                    ` : ''}
                </div>
            </div>
        `;

        document.getElementById('logisticsDetails').innerHTML = logisticsDetails;
        openViewLogisticsModal();
    }

    function editLogistics(logisticsId) {
        const logistic = logistics.find(l => l.delivery_id === logisticsId);
        if (!logistic) return;

        document.getElementById('logisticsModalTitle').textContent = 'Edit Delivery';
        document.getElementById('logisticsModalSubmitText').textContent = 'Update Delivery';
        
        document.getElementById('logisticsId').value = logistic.logistics_id;
        document.getElementById('deliveryId').value = logistic.delivery_id;
        document.getElementById('vehicleId').value = logistic.vehicle_id;
        document.getElementById('driverName').value = logistic.driver_name;
        document.getElementById('route').value = logistic.route;
        document.getElementById('destination').value = logistic.destination;
        document.getElementById('receiverName').value = logistic.receiver_name;
        document.getElementById('items').value = logistic.items;
        document.getElementById('status').value = logistic.status;
        document.getElementById('deliveryDate').value = logistic.delivery_date;
        document.getElementById('notes').value = logistic.notes || '';

        document.getElementById('logisticsModal').classList.add('modal-open');
    }

    async function handleLogisticsSubmit(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const logisticsId = document.getElementById('logisticsId').value;
        const isEdit = !!logisticsId;

        const logisticsData = {
            vehicle_id: formData.get('vehicle_id'),
            driver_name: formData.get('driver_name'),
            route: formData.get('route'),
            destination: formData.get('destination'),
            items: formData.get('items'),
            status: formData.get('status'),
            receiver_name: formData.get('receiver_name'),
            delivery_date: formData.get('delivery_date'),
            notes: formData.get('notes')
        };
        
        try {
            showLoadingModal(
                isEdit ? 'Updating Delivery...' : 'Creating Delivery...'
            );

            let response;
            if (isEdit) {
                response = await fetch(`${API_BASE_URL}/${logisticsId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(logisticsData)
                });
            } else {
                response = await fetch(`${API_BASE_URL}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(logisticsData)
                });
            }

            const result = await response.json();

            if (response.ok && result.success) {
                hideLoadingModal();
                closeLogisticsModal();
                
                // Wait for data to reload before showing success message
                await loadLogistics();
                await loadStats();
                
                showSuccessToast(
                    isEdit ? 'Delivery updated successfully!' : 'Delivery created successfully!'
                );
            } else {
                throw new Error(result.message || `Failed to ${isEdit ? 'update' : 'create'} delivery`);
            }
        } catch (error) {
            hideLoadingModal();
            Swal.fire('Error', `Failed to ${isEdit ? 'update' : 'create'} delivery: ` + error.message, 'error');
        }
    }

    async function handleStatusUpdate(e) {
        e.preventDefault();
        
        const logisticsId = document.getElementById('statusDeliveryId').value;
        const newStatus = document.getElementById('newStatus').value;

        try {
            showLoadingModal('Updating Status...');

            const response = await fetch(`${API_BASE_URL}/${logisticsId}/status`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ status: newStatus })
            });

            const result = await response.json();

            if (response.ok && result.success) {
                hideLoadingModal();
                closeStatusModal();
                
                // Wait for data to reload before showing success message
                await loadLogistics();
                await loadStats();
                
                showSuccessToast('Delivery status updated successfully!');
            } else {
                throw new Error(result.message || 'Failed to update delivery status');
            }
        } catch (error) {
            hideLoadingModal();
            Swal.fire('Error', 'Failed to update delivery status: ' + error.message, 'error');
        }
    }

    async function deleteLogistics(logisticsId) {
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: "This will permanently delete the delivery record!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        });

        if (result.isConfirmed) {
            try {
                showLoadingModal('Deleting Delivery...');

                const response = await fetch(`${API_BASE_URL}/${logisticsId}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    hideLoadingModal();
                    
                    // Wait for data to reload before showing success message
                    await loadLogistics();
                    await loadStats();
                    
                    showSuccessToast('Delivery deleted successfully!');
                } else {
                    throw new Error(result.message || 'Failed to delete delivery');
                }
            } catch (error) {
                hideLoadingModal();
                Swal.fire('Error', 'Failed to delete delivery: ' + error.message, 'error');
            }
        }
    }
</script>

<style>
    .modal-box {
        max-height: 85vh;
    }
    input:read-only {
        background-color: #f3f4f6;
        cursor: not-allowed;
    }
    .modal-box .max-h-\[70vh\] {
        max-height: 70vh;
    }
    .table td {
        white-space: nowrap;
    }
    .max-w-xs {
        max-width: 12rem;
    }
</style>
@endsection