@extends('layouts.app')

@section('title', 'Smart Warehousing System - Warehouse Management')

@section('content')
    <div class="module-content bg-white rounded-xl p-6 shadow block">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Warehouse Management</h2>
            <button class="btn btn-primary" id="addGrnBtn">
                <i class="bx bx-plus mr-2"></i>New Entry
            </button>
        </div>

        <!-- Stats Section -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-primary">
                <div class="stat-figure text-primary">
                    <i class="bx bx-file text-3xl"></i>
                </div>
                <div class="stat-title">Total Records</div>
                <div class="stat-value text-primary" id="total-records">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-success">
                <div class="stat-figure text-success">
                    <i class="bx bx-check-circle text-3xl"></i>
                </div>
                <div class="stat-title">Good Condition</div>
                <div class="stat-value text-success" id="good-condition">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-warning">
                <div class="stat-figure text-warning">
                    <i class="bx bx-error text-3xl"></i>
                </div>
                <div class="stat-title">Damaged</div>
                <div class="stat-value text-warning" id="damaged-condition">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-error">
                <div class="stat-figure text-error">
                    <i class="bx bx-x-circle text-3xl"></i>
                </div>
                <div class="stat-title">Missing</div>
                <div class="stat-value text-error" id="missing-condition">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-info">
                <div class="stat-figure text-info">
                    <i class="bx bx-task text-3xl"></i>
                </div>
                <div class="stat-title">Completed</div>
                <div class="stat-value text-info" id="completed-status">0</div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="flex gap-4 mb-6">
            <div class="form-control flex-1">
                <input type="text" placeholder="Search by GRN ID, PO Number, Item, Warehouse Location, or Receiver..." class="input input-bordered w-full" id="searchGrn">
            </div>
            <select class="select select-bordered" id="conditionFilter">
                <option value="">All Conditions</option>
                <option value="Good">Good</option>
                <option value="Damaged">Damaged</option>
                <option value="Missing">Missing</option>
            </select>
            <select class="select select-bordered" id="statusFilter">
                <option value="">All Status</option>
                <option value="Pending">Pending</option>
                <option value="Completed">Completed</option>
                <option value="Cancelled">Cancelled</option>
            </select>
        </div>

        <!-- GRN Records Table -->
        <div class="overflow-x-auto bg-base-100 rounded-lg">
            <table class="table table-zebra w-full">
                <thead>
                    <tr class="bg-gray-900 text-white">
                        <th>GRN ID</th>
                        <th>PO Number</th>
                        <th>Item</th>
                        <th>Qty Ordered</th>
                        <th>Qty Received</th>
                        <th>Condition</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="grn-table-body">
                    <tr>
                        <td colspan="10" class="text-center py-8">
                            <div class="loading loading-spinner loading-lg"></div>
                            <p class="text-gray-500 mt-2">Loading GRN records...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add/Edit GRN Modal -->
    <div id="grnModal" class="modal modal-lg">
        <div class="modal-box max-w-4xl p-0 overflow-visible">
            <div class="flex justify-between items-center bg-green-700 p-4 rounded-t-lg">
                <h3 class="font-bold text-white text-lg" id="grnModalTitle">New GRN Entry</h3>
                <button class="btn btn-sm btn-circle btn-ghost hover:bg-white/20 text-white" id="closeGrnModalX">✕</button>
            </div>
            <div class="p-4 max-h-[70vh] overflow-y-auto">
                <form id="grnForm" class="space-y-4">
                    @csrf
                    <input type="hidden" id="grnId" name="id">
                    
                    <!-- Auto-generated GRN ID -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">GRN ID</span>
                        </label>
                        <input type="text" id="grnIdDisplay" class="input input-bordered input-sm w-full bg-gray-100" 
                               readonly placeholder="Auto-generated (GRN00001)">
                    </div>

                    <!-- Auto-generated PO Number -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">PO Number</span>
                        </label>
                        <input type="text" id="poNumberDisplay" class="input input-bordered input-sm w-full bg-gray-100" 
                               readonly placeholder="Auto-generated (PO00001)">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Item Name *</span>
                            </label>
                            <input type="text" id="item" name="item" class="input input-bordered input-sm w-full" 
                                   placeholder="Enter item name" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Quantity Ordered *</span>
                            </label>
                            <input type="number" id="qtyOrdered" name="qty_ordered" class="input input-bordered input-sm w-full" 
                                   min="1" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Quantity Received *</span>
                            </label>
                            <input type="number" id="qtyReceived" name="qty_received" class="input input-bordered input-sm w-full" 
                                   min="0" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Condition *</span>
                            </label>
                            <select id="condition" name="condition" class="select select-bordered select-sm w-full" required>
                                <option value="Good">Good</option>
                                <option value="Damaged">Damaged</option>
                                <option value="Missing">Missing</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Warehouse Location *</span>
                            </label>
                            <input type="text" id="warehouseLocation" name="warehouse_location" class="input input-bordered input-sm w-full" 
                                   placeholder="Enter warehouse location" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Received By *</span>
                            </label>
                            <input type="text" id="receivedBy" name="received_by" class="input input-bordered input-sm w-full" 
                                   placeholder="Enter receiver's name" required>
                        </div>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Status</span>
                        </label>
                        <select id="status" name="status" class="select select-bordered select-sm w-full">
                            <option value="Pending">Pending</option>
                            <option value="Completed">Completed</option>
                            <option value="Cancelled">Cancelled</option>
                        </select>
                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-action flex justify-end space-x-3 pt-4 border-t">
                        <button type="button" class="btn btn-ghost btn-sm hover:bg-gray-100 transition-colors px-4" id="closeGrnModal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm bg-gradient-to-r from-primary to-primary/90 hover:from-primary/90 hover:to-primary transition-all duration-300 shadow-lg px-4" id="grnSubmitBtn">
                            <i class="bx bx-save mr-1"></i><span id="grnModalSubmitText">Save GRN</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View GRN Modal -->
    <div id="viewGrnModal" class="modal modal-lg">
        <div class="modal-box max-w-4xl p-0 overflow-visible">
            <div class="flex justify-between items-center bg-green-700 p-4 rounded-t-lg">
                <h3 class="font-bold text-white text-lg">GRN Record Details</h3>
                <button class="btn btn-sm btn-circle btn-ghost hover:bg-white/20 text-white" id="closeViewGrnModalX">✕</button>
            </div>
            <div class="p-4 max-h-[70vh] overflow-y-auto">
                <div class="space-y-4" id="grnDetails">
                    <!-- GRN details will be populated here -->
                </div>
                <div class="modal-action flex justify-end pt-4 border-t">
                    <button type="button" class="btn btn-ghost btn-sm hover:bg-gray-100 transition-colors px-4" id="closeViewGrnModal">Close</button>
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
    let grnRecords = [];

    // ==================== CONFIGURATION ====================
    const API_BASE_URL = 'http://localhost:8001/api/sws/warehousing';

    // Utility functions
    function formatDate(dateString) {
        return new Date(dateString).toLocaleDateString('en-US', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function getConditionBadge(condition) {
        const conditionClasses = {
            'Good': 'bg-green-500 text-white uppercase',
            'Damaged': 'bg-yellow-500 text-white uppercase',
            'Missing': 'bg-red-500 text-white uppercase'
        };
        
        return `<span class="badge font-bold tracking-wider text-xs px-3 py-2 ${conditionClasses[condition] || 'bg-gray-400'} border-0">
            ${condition}
        </span>`;
    }

    function getStatusBadge(status) {
        const statusClasses = {
            'Pending': 'bg-yellow-400 text-white uppercase',
            'Completed': 'bg-green-600 text-white uppercase',
            'Cancelled': 'bg-red-400 text-white uppercase'
        };
        
        return `<span class="badge font-bold tracking-wider text-xs px-3 py-2 ${statusClasses[status] || 'bg-gray-400'} border-0">
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
        loadGrnRecords();
        loadStats();
    });

    function initializeEventListeners() {
        // Add GRN button
        document.getElementById('addGrnBtn').addEventListener('click', openAddGrnModal);

        // Close modal buttons
        document.getElementById('closeGrnModal').addEventListener('click', closeGrnModal);
        document.getElementById('closeGrnModalX').addEventListener('click', closeGrnModal);
        document.getElementById('closeViewGrnModal').addEventListener('click', closeViewGrnModal);
        document.getElementById('closeViewGrnModalX').addEventListener('click', closeViewGrnModal);

        // Form submission
        document.getElementById('grnForm').addEventListener('submit', handleGrnSubmit);

        // Search and filter
        document.getElementById('searchGrn').addEventListener('input', filterGrnRecords);
        document.getElementById('conditionFilter').addEventListener('change', filterGrnRecords);
        document.getElementById('statusFilter').addEventListener('change', filterGrnRecords);
    }

    async function loadGrnRecords() {
        try {
            showGrnLoadingState();
            const response = await fetch(`${API_BASE_URL}`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const result = await response.json();
            
            if (result.success) {
                grnRecords = result.data || [];
                renderGrnRecords(grnRecords);
            } else {
                throw new Error(result.message || 'Failed to load GRN records');
            }
        } catch (error) {
            console.error('Error loading GRN records:', error);
            showGrnErrorState('Failed to load GRN records: ' + error.message);
        }
    }

    async function loadStats() {
        try {
            const response = await fetch(`${API_BASE_URL}/stats/overview`);
            
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

    function showGrnLoadingState() {
        const tbody = document.getElementById('grn-table-body');
        tbody.innerHTML = `
            <tr>
                <td colspan="10" class="text-center py-8">
                    <div class="loading loading-spinner loading-lg"></div>
                    <p class="text-gray-500 mt-2">Loading GRN records...</p>
                </td>
            </tr>
        `;
    }

    function showGrnErrorState(message) {
        const tbody = document.getElementById('grn-table-body');
        tbody.innerHTML = `
            <tr>
                <td colspan="10" class="text-center py-8">
                    <i class="bx bx-error text-4xl text-red-400 mb-2"></i>
                    <p class="text-red-500">${message}</p>
                    <button class="btn btn-sm btn-outline mt-2" onclick="loadGrnRecords()">Retry</button>
                </td>
            </tr>
        `;
    }

    function renderGrnRecords(grnData) {
        const tbody = document.getElementById('grn-table-body');
        
        if (grnData.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="10" class="text-center py-8">
                        <i class="bx bx-package text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500">No GRN records found</p>
                        <button class="btn btn-sm btn-primary mt-2" id="addFirstGrnBtn">Create First GRN</button>
                    </td>
                </tr>
            `;
            document.getElementById('addFirstGrnBtn')?.addEventListener('click', openAddGrnModal);
            return;
        }

        tbody.innerHTML = grnData.map(record => {
            const quantityDiff = record.qty_ordered - record.qty_received;
            const diffClass = quantityDiff > 0 ? 'text-red-500' : quantityDiff < 0 ? 'text-orange-500' : 'text-green-500';
            const diffSymbol = quantityDiff > 0 ? '▼' : quantityDiff < 0 ? '▲' : '✓';
            
            return `
            <tr>
                <td class="font-mono font-semibold text-sm">${record.grn_id}</td>
                <td class="font-mono text-sm">${record.po_number}</td>
                <td class="text-sm">${record.item}</td>
                <td class="text-center text-sm">${record.qty_ordered}</td>
                <td class="text-center text-sm">
                    <span class="${diffClass} font-semibold">
                        ${record.qty_received} ${diffSymbol}
                    </span>
                </td>
                <td>${getConditionBadge(record.condition)}</td>
                <td>${getStatusBadge(record.status)}</td>
                <td>
                    <div class="flex space-x-1">
                        <button title="View" class="btn btn-sm btn-circle btn-info view-grn-btn" data-grn-id="${record.id}">
                            <i class="bx bx-show-alt text-sm"></i>
                        </button>
                        <button title="Edit" class="btn btn-sm btn-circle btn-warning edit-grn-btn" data-grn-id="${record.id}">
                            <i class="bx bx-edit text-sm"></i>
                        </button>
                        <button title="Delete" class="btn btn-sm btn-circle btn-error delete-grn-btn" data-grn-id="${record.id}">
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
        document.querySelectorAll('.view-grn-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const grnId = this.getAttribute('data-grn-id');
                viewGrn(parseInt(grnId));
            });
        });

        document.querySelectorAll('.edit-grn-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const grnId = this.getAttribute('data-grn-id');
                editGrn(parseInt(grnId));
            });
        });

        document.querySelectorAll('.delete-grn-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const grnId = this.getAttribute('data-grn-id');
                deleteGrn(parseInt(grnId));
            });
        });
    }

    function updateStats(statsData) {
        document.getElementById('total-records').textContent = statsData.total_records || 0;
        document.getElementById('good-condition').textContent = statsData.good_condition || 0;
        document.getElementById('damaged-condition').textContent = statsData.damaged_condition || 0;
        document.getElementById('missing-condition').textContent = statsData.missing_condition || 0;
        document.getElementById('completed-status').textContent = statsData.completed_status || 0;
    }

    function filterGrnRecords() {
        const searchTerm = document.getElementById('searchGrn').value.toLowerCase();
        const conditionFilter = document.getElementById('conditionFilter').value;
        const statusFilter = document.getElementById('statusFilter').value;
        
        const filtered = grnRecords.filter(record => {
            const matchesSearch = searchTerm === '' || 
                record.grn_id.toLowerCase().includes(searchTerm) ||
                record.po_number.toLowerCase().includes(searchTerm) ||
                record.item.toLowerCase().includes(searchTerm) ||
                record.warehouse_location.toLowerCase().includes(searchTerm) ||
                record.received_by.toLowerCase().includes(searchTerm);
            
            const matchesCondition = conditionFilter === '' || record.condition === conditionFilter;
            const matchesStatus = statusFilter === '' || record.status === statusFilter;
            
            return matchesSearch && matchesCondition && matchesStatus;
        });
        
        renderGrnRecords(filtered);
    }

    // Modal Functions
    function openAddGrnModal() {
        document.getElementById('grnModalTitle').textContent = 'New GRN Entry';
        document.getElementById('grnModalSubmitText').textContent = 'Save GRN';
        document.getElementById('grnForm').reset();
        document.getElementById('grnId').value = '';
        document.getElementById('grnIdDisplay').value = 'Auto-generated (GRN00001)';
        document.getElementById('poNumberDisplay').value = 'Auto-generated (PO00001)';
        document.getElementById('status').value = 'Pending';
        
        document.getElementById('grnModal').classList.add('modal-open');
    }

    function closeGrnModal() {
        document.getElementById('grnModal').classList.remove('modal-open');
        document.getElementById('grnForm').reset();
    }

    function openViewGrnModal() {
        document.getElementById('viewGrnModal').classList.add('modal-open');
    }

    function closeViewGrnModal() {
        document.getElementById('viewGrnModal').classList.remove('modal-open');
    }

    // GRN Actions
    function viewGrn(grnId) {
        const record = grnRecords.find(r => r.id === grnId);
        if (!record) return;

        const quantityDiff = record.qty_ordered - record.qty_received;
        const diffStatus = quantityDiff > 0 ? 'Shortage' : quantityDiff < 0 ? 'Overage' : 'Exact Match';
        const diffClass = quantityDiff > 0 ? 'text-red-500' : quantityDiff < 0 ? 'text-orange-500' : 'text-green-500';

        const grnDetails = `
            <div class="space-y-4">
                <!-- Basic Information -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <strong class="text-gray-700 text-xs">GRN ID:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1 font-mono font-semibold">${record.grn_id}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">PO Number:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1 font-mono">${record.po_number}</p>
                    </div>
                </div>

                <!-- Item Information -->
                <div>
                    <strong class="text-gray-700 text-xs">Item Name:</strong>
                    <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${record.item}</p>
                </div>

                <!-- Quantity Information -->
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <strong class="text-gray-700 text-xs">Quantity Ordered:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1 text-center font-semibold">${record.qty_ordered}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Quantity Received:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1 text-center font-semibold">${record.qty_received}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Difference:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1 text-center font-semibold ${diffClass}">
                            ${Math.abs(quantityDiff)} (${diffStatus})
                        </p>
                    </div>
                </div>

                <!-- Location and Receiver -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <strong class="text-gray-700 text-xs">Warehouse Location:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${record.warehouse_location}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Received By:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${record.received_by}</p>
                    </div>
                </div>

                <!-- Status and Condition -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <strong class="text-gray-700 text-xs">Condition:</strong>
                        <p class="mt-1 p-2">${getConditionBadge(record.condition)}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Status:</strong>
                        <p class="mt-1 p-2">${getStatusBadge(record.status)}</p>
                    </div>
                </div>

                <!-- Timestamps -->
                <div class="grid grid-cols-2 gap-4">
                    ${record.created_at ? `
                    <div>
                        <strong class="text-gray-700 text-xs">Created:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${formatDate(record.created_at)}</p>
                    </div>
                    ` : ''}
                    ${record.updated_at ? `
                    <div>
                        <strong class="text-gray-700 text-xs">Last Updated:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${formatDate(record.updated_at)}</p>
                    </div>
                    ` : ''}
                </div>
            </div>
        `;

        document.getElementById('grnDetails').innerHTML = grnDetails;
        openViewGrnModal();
    }

    function editGrn(grnId) {
        const record = grnRecords.find(r => r.id === grnId);
        if (!record) return;

        document.getElementById('grnModalTitle').textContent = 'Edit GRN Record';
        document.getElementById('grnModalSubmitText').textContent = 'Update GRN';
        
        document.getElementById('grnId').value = record.id;
        document.getElementById('grnIdDisplay').value = record.grn_id;
        document.getElementById('poNumberDisplay').value = record.po_number;
        document.getElementById('item').value = record.item;
        document.getElementById('qtyOrdered').value = record.qty_ordered;
        document.getElementById('qtyReceived').value = record.qty_received;
        document.getElementById('condition').value = record.condition;
        document.getElementById('warehouseLocation').value = record.warehouse_location;
        document.getElementById('receivedBy').value = record.received_by;
        document.getElementById('status').value = record.status;

        document.getElementById('grnModal').classList.add('modal-open');
    }

    async function handleGrnSubmit(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const grnId = document.getElementById('grnId').value;
        const isEdit = !!grnId;

        const grnData = {
            item: formData.get('item'),
            qty_ordered: parseInt(formData.get('qty_ordered')) || 0,
            qty_received: parseInt(formData.get('qty_received')) || 0,
            condition: formData.get('condition'),
            warehouse_location: formData.get('warehouse_location'),
            received_by: formData.get('received_by'),
            status: formData.get('status')
        };
        
        try {
            showLoadingModal(
                isEdit ? 'Updating GRN Record...' : 'Creating GRN Record...'
            );

            let response;
            if (isEdit) {
                response = await fetch(`${API_BASE_URL}/${grnId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(grnData)
                });
            } else {
                response = await fetch(`${API_BASE_URL}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(grnData)
                });
            }

            const result = await response.json();

            if (response.ok && result.success) {
                hideLoadingModal();
                closeGrnModal();
                
                // Wait for data to reload before showing success message
                await loadGrnRecords();
                await loadStats();
                
                showSuccessToast(
                    isEdit ? 'GRN record updated successfully!' : 'GRN record created successfully!'
                );
            } else {
                throw new Error(result.message || `Failed to ${isEdit ? 'update' : 'create'} GRN record`);
            }
        } catch (error) {
            hideLoadingModal();
            Swal.fire('Error', `Failed to ${isEdit ? 'update' : 'create'} GRN record: ` + error.message, 'error');
        }
    }

    async function deleteGrn(grnId) {
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: "This will permanently delete the GRN record!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        });

        if (result.isConfirmed) {
            try {
                showLoadingModal('Deleting GRN Record...');

                const response = await fetch(`${API_BASE_URL}/${grnId}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    hideLoadingModal();
                    
                    // Wait for data to reload before showing success message
                    await loadGrnRecords();
                    await loadStats();
                    
                    showSuccessToast('GRN record deleted successfully!');
                } else {
                    throw new Error(result.message || 'Failed to delete GRN record');
                }
            } catch (error) {
                hideLoadingModal();
                Swal.fire('Error', 'Failed to delete GRN record: ' + error.message, 'error');
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
</style>
@endsection