@extends('layouts.app')

@section('title', 'Smart Warehousing System - Digital Inventory')

@section('content')
    <div class="module-content bg-white rounded-xl p-6 shadow block">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Digital Inventory</h2>
            <div class="flex space-x-2">
                <button class="btn btn-primary" id="syncFromGrnBtn">
                    <i class="bx bx-transfer mr-2"></i>Sync from GRN
                </button>
                <button class="btn btn-primary" id="addStockBtn">
                    <i class="bx bx-plus mr-2"></i>New Stock
                </button>
            </div>
        </div>

        <!-- Stats Section -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-primary">
                <div class="stat-figure text-primary">
                    <i class="bx bx-package text-3xl"></i>
                </div>
                <div class="stat-title">Total Stock Items</div>
                <div class="stat-value text-primary" id="total-records">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-success">
                <div class="stat-figure text-success">
                    <i class="bx bx-check-circle text-3xl"></i>
                </div>
                <div class="stat-title">On Stock</div>
                <div class="stat-value text-success" id="on-stock">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-warning">
                <div class="stat-figure text-warning">
                    <i class="bx bx-error text-3xl"></i>
                </div>
                <div class="stat-title">Low Stock</div>
                <div class="stat-value text-warning" id="low-stock">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-error">
                <div class="stat-figure text-error">
                    <i class="bx bx-x-circle text-3xl"></i>
                </div>
                <div class="stat-title">Out of Stock</div>
                <div class="stat-value text-error" id="out-of-stock">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-info">
                <div class="stat-figure text-info">
                    <i class="bx bx-cube text-3xl"></i>
                </div>
                <div class="stat-title">Total Items</div>
                <div class="stat-value text-info" id="total-items">0</div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="flex gap-4 mb-6">
            <div class="form-control flex-1">
                <input type="text" placeholder="Search by Stock ID, Item Name, Type, or Units..." class="input input-bordered w-full" id="searchStock">
            </div>
            <select class="select select-bordered" id="statusFilter">
                <option value="">All Status</option>
                <option value="lowstock">Low Stock</option>
                <option value="onstock">On Stock</option>
                <option value="outofstock">Out of Stock</option>
            </select>
            <select class="select select-bordered" id="typeFilter">
                <option value="">All Types</option>
                <option value="Electronics">Electronics</option>
                <option value="Office Supplies">Office Supplies</option>
                <option value="Furniture">Furniture</option>
                <option value="Equipment">Equipment</option>
                <option value="Raw Materials">Raw Materials</option>
                <option value="General">General</option>
            </select>
        </div>

        <!-- Stock Table -->
        <div class="overflow-x-auto bg-base-100 rounded-lg">
            <table class="table table-zebra w-full">
                <thead>
                    <tr class="bg-gray-900 text-white">
                        <th>Stock ID</th>
                        <th>Item Name</th>
                        <th>Type</th>
                        <th>Units</th>
                        <th>Available Item</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="stock-table-body">
                    <tr>
                        <td colspan="7" class="text-center py-8">
                            <div class="loading loading-spinner loading-lg"></div>
                            <p class="text-gray-500 mt-2">Loading stock items...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add/Edit Stock Modal -->
    <div id="stockModal" class="modal modal-lg">
        <div class="modal-box max-w-4xl p-0 overflow-visible">
            <div class="flex justify-between items-center bg-green-700 p-4 rounded-t-lg">
                <h3 class="font-bold text-white text-lg" id="stockModalTitle">New Stock Item</h3>
                <button class="btn btn-sm btn-circle btn-ghost hover:bg-white/20 text-white" id="closeStockModalX">✕</button>
            </div>
            <div class="p-4 max-h-[70vh] overflow-y-auto">
                <form id="stockForm" class="space-y-4">
                    @csrf
                    <input type="hidden" id="stockId" name="id">
                    
                    <!-- Auto-generated Stock ID -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Stock ID</span>
                        </label>
                        <input type="text" id="stockIdDisplay" class="input input-bordered input-sm w-full bg-gray-100" 
                               readonly placeholder="Auto-generated (STK00001)">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Item Name *</span>
                            </label>
                            <input type="text" id="itemName" name="item_name" class="input input-bordered input-sm w-full" 
                                   placeholder="Enter item name" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Type *</span>
                            </label>
                            <select id="type" name="type" class="select select-bordered select-sm w-full" required>
                                <option value="">Select Type</option>
                                <option value="Electronics">Electronics</option>
                                <option value="Office Supplies">Office Supplies</option>
                                <option value="Furniture">Furniture</option>
                                <option value="Equipment">Equipment</option>
                                <option value="Raw Materials">Raw Materials</option>
                                <option value="General">General</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Units *</span>
                            </label>
                            <select id="units" name="units" class="select select-bordered select-sm w-full" required>
                                <option value="">Select Units</option>
                                <option value="pcs">Pieces</option>
                                <option value="kg">Kilograms</option>
                                <option value="g">Grams</option>
                                <option value="L">Liters</option>
                                <option value="ml">Milliliters</option>
                                <option value="boxes">Boxes</option>
                                <option value="units">Units</option>
                                <option value="sets">Sets</option>
                            </select>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Available Item *</span>
                            </label>
                            <input type="number" id="availableItem" name="available_item" class="input input-bordered input-sm w-full" 
                                   min="0" required>
                        </div>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">GRN Reference (Optional)</span>
                        </label>
                        <select id="grnId" name="grn_id" class="select select-bordered select-sm w-full">
                            <option value="">No GRN Reference</option>
                            <!-- GRN options will be populated dynamically -->
                        </select>
                    </div>

                    <!-- Status will be auto-calculated based on available items -->
                    <input type="hidden" id="status" name="status">

                    <!-- Modal Actions -->
                    <div class="modal-action flex justify-end space-x-3 pt-4 border-t">
                        <button type="button" class="btn btn-ghost btn-sm hover:bg-gray-100 transition-colors px-4" id="closeStockModal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm bg-gradient-to-r from-primary to-primary/90 hover:from-primary/90 hover:to-primary transition-all duration-300 shadow-lg px-4" id="stockSubmitBtn">
                            <i class="bx bx-save mr-1"></i><span id="stockModalSubmitText">Save Stock</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sync from GRN Modal -->
    <div id="syncGrnModal" class="modal modal-lg">
        <div class="modal-box max-w-4xl p-0 overflow-visible">
            <div class="flex justify-between items-center bg-blue-700 p-4 rounded-t-lg">
                <h3 class="font-bold text-white text-lg">Sync from Goods Received</h3>
                <button class="btn btn-sm btn-circle btn-ghost hover:bg-white/20 text-white" id="closeSyncGrnModalX">✕</button>
            </div>
            <div class="p-4 max-h-[70vh] overflow-y-auto">
                <div class="mb-4">
                    <p class="text-sm text-gray-600">Select completed GRN records to sync to digital inventory:</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="table table-zebra w-full">
                        <thead>
                            <tr class="bg-gray-900 text-white">
                                <th>Select</th>
                                <th>GRN ID</th>
                                <th>Item</th>
                                <th>Qty Received</th>
                                <th>Condition</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="grn-sync-table-body">
                            <!-- GRN records will be populated here -->
                        </tbody>
                    </table>
                </div>
                <div class="modal-action flex justify-end space-x-3 pt-4 border-t">
                    <button type="button" class="btn btn-ghost btn-sm hover:bg-gray-100 transition-colors px-4" id="closeSyncGrnModal">Cancel</button>
                    <button type="button" class="btn btn-primary btn-sm bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 transition-all duration-300 shadow-lg px-4" id="syncGrnSubmitBtn">
                        <i class="bx bx-transfer mr-1"></i>Sync Selected
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- View Stock Modal -->
    <div id="viewStockModal" class="modal modal-lg">
        <div class="modal-box max-w-4xl p-0 overflow-visible">
            <div class="flex justify-between items-center bg-green-700 p-4 rounded-t-lg">
                <h3 class="font-bold text-white text-lg">Stock Item Details</h3>
                <button class="btn btn-sm btn-circle btn-ghost hover:bg-white/20 text-white" id="closeViewStockModalX">✕</button>
            </div>
            <div class="p-4 max-h-[70vh] overflow-y-auto">
                <div class="space-y-4" id="stockDetails">
                    <!-- Stock details will be populated here -->
                </div>
                <div class="modal-action flex justify-end pt-4 border-t">
                    <button type="button" class="btn btn-ghost btn-sm hover:bg-gray-100 transition-colors px-4" id="closeViewStockModal">Close</button>
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
    let stockRecords = [];
    let grnRecords = [];

    // ==================== CONFIGURATION ====================
    const API_BASE_URL = 'http://localhost:8001/api/sws/digital';
    const GRN_API_BASE_URL = 'http://localhost:8001/api/sws/warehousing';

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

    function getStatusBadge(status) {
        const statusClasses = {
            'lowstock': 'bg-yellow-500 text-white uppercase',
            'onstock': 'bg-green-500 text-white uppercase',
            'outofstock': 'bg-red-500 text-white uppercase'
        };
        
        return `<span class="badge font-bold tracking-wider text-xs px-3 py-2 ${statusClasses[status] || 'bg-gray-400'} border-0">
            ${status}
        </span>`;
    }

    function getStockLevelBadge(available, status) {
        let levelClass = 'bg-green-500';
        let levelText = 'Good';
        
        if (status === 'outofstock') {
            levelClass = 'bg-red-500';
            levelText = 'Empty';
        } else if (status === 'lowstock') {
            levelClass = 'bg-yellow-500';
            levelText = 'Low';
        }
        
        return `<span class="badge font-bold text-xs px-3 py-2 ${levelClass} text-white border-0">
            ${available} (${levelText})
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
        loadStockRecords();
        loadStats();
        loadGrnOptions();
    });

    function initializeEventListeners() {
        // Add Stock button
        document.getElementById('addStockBtn').addEventListener('click', openAddStockModal);
        document.getElementById('syncFromGrnBtn').addEventListener('click', openSyncGrnModal);

        // Close modal buttons
        document.getElementById('closeStockModal').addEventListener('click', closeStockModal);
        document.getElementById('closeStockModalX').addEventListener('click', closeStockModal);
        document.getElementById('closeSyncGrnModal').addEventListener('click', closeSyncGrnModal);
        document.getElementById('closeSyncGrnModalX').addEventListener('click', closeSyncGrnModal);
        document.getElementById('closeViewStockModal').addEventListener('click', closeViewStockModal);
        document.getElementById('closeViewStockModalX').addEventListener('click', closeViewStockModal);

        // Form submission
        document.getElementById('stockForm').addEventListener('submit', handleStockSubmit);
        document.getElementById('syncGrnSubmitBtn').addEventListener('click', handleGrnSync);

        // Search and filter
        document.getElementById('searchStock').addEventListener('input', filterStockRecords);
        document.getElementById('statusFilter').addEventListener('change', filterStockRecords);
        document.getElementById('typeFilter').addEventListener('change', filterStockRecords);
    }

    async function loadStockRecords() {
        try {
            showStockLoadingState();
            const response = await fetch(`${API_BASE_URL}`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const result = await response.json();
            
            if (result.success) {
                stockRecords = result.data || [];
                renderStockRecords(stockRecords);
            } else {
                throw new Error(result.message || 'Failed to load stock records');
            }
        } catch (error) {
            console.error('Error loading stock records:', error);
            showStockErrorState('Failed to load stock records: ' + error.message);
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

    async function loadGrnOptions() {
        try {
            const response = await fetch(`${GRN_API_BASE_URL}`);
            if (response.ok) {
                const result = await response.json();
                if (result.success) {
                    grnRecords = result.data || [];
                    populateGrnSelect();
                }
            }
        } catch (error) {
            console.error('Error loading GRN options:', error);
        }
    }

    function populateGrnSelect() {
        const grnSelect = document.getElementById('grnId');
        grnSelect.innerHTML = '<option value="">No GRN Reference</option>';
        
        grnRecords
            .filter(grn => grn.status === 'Completed')
            .forEach(grn => {
                const option = document.createElement('option');
                option.value = grn.id;
                option.textContent = `${grn.grn_id} - ${grn.item} (Qty: ${grn.qty_received})`;
                grnSelect.appendChild(option);
            });
    }

    function showStockLoadingState() {
        const tbody = document.getElementById('stock-table-body');
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-8">
                    <div class="loading loading-spinner loading-lg"></div>
                    <p class="text-gray-500 mt-2">Loading stock items...</p>
                </td>
            </tr>
        `;
    }

    function showStockErrorState(message) {
        const tbody = document.getElementById('stock-table-body');
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-8">
                    <i class="bx bx-error text-4xl text-red-400 mb-2"></i>
                    <p class="text-red-500">${message}</p>
                    <button class="btn btn-sm btn-outline mt-2" onclick="loadStockRecords()">Retry</button>
                </td>
            </tr>
        `;
    }

    function renderStockRecords(stockData) {
        const tbody = document.getElementById('stock-table-body');
        
        if (stockData.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-8">
                        <i class="bx bx-package text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500">No stock items found</p>
                        <button class="btn btn-sm btn-primary mt-2" id="addFirstStockBtn">Add First Stock Item</button>
                    </td>
                </tr>
            `;
            document.getElementById('addFirstStockBtn')?.addEventListener('click', openAddStockModal);
            return;
        }

        tbody.innerHTML = stockData.map(record => {
            return `
            <tr>
                <td class="font-mono font-semibold text-sm">${record.stock_id}</td>
                <td class="text-sm">${record.item_name}</td>
                <td class="text-sm">${record.type}</td>
                <td class="text-sm">${record.units}</td>
                <td class="text-center text-sm">
                    ${getStockLevelBadge(record.available_item, record.status)}
                </td>
                <td>${getStatusBadge(record.status)}</td>
                <td>
                    <div class="flex space-x-1">
                        <button title="View" class="btn btn-sm btn-circle btn-info view-stock-btn" data-stock-id="${record.id}">
                            <i class="bx bx-show-alt text-sm"></i>
                        </button>
                        <button title="Edit" class="hidden btn btn-sm btn-circle btn-warning edit-stock-btn" data-stock-id="${record.id}">
                            <i class="bx bx-edit text-sm"></i>
                        </button>
                        <button title="Delete" class="btn btn-sm btn-circle btn-error delete-stock-btn" data-stock-id="${record.id}">
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
        document.querySelectorAll('.view-stock-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const stockId = this.getAttribute('data-stock-id');
                viewStock(parseInt(stockId));
            });
        });

        document.querySelectorAll('.edit-stock-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const stockId = this.getAttribute('data-stock-id');
                editStock(parseInt(stockId));
            });
        });

        document.querySelectorAll('.delete-stock-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const stockId = this.getAttribute('data-stock-id');
                deleteStock(parseInt(stockId));
            });
        });
    }

    function updateStats(statsData) {
        document.getElementById('total-records').textContent = statsData.total_records || 0;
        document.getElementById('on-stock').textContent = statsData.on_stock || 0;
        document.getElementById('low-stock').textContent = statsData.low_stock || 0;
        document.getElementById('out-of-stock').textContent = statsData.out_of_stock || 0;
        document.getElementById('total-items').textContent = statsData.total_items || 0;
    }

    function filterStockRecords() {
        const searchTerm = document.getElementById('searchStock').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const typeFilter = document.getElementById('typeFilter').value;
        
        const filtered = stockRecords.filter(record => {
            const matchesSearch = searchTerm === '' || 
                record.stock_id.toLowerCase().includes(searchTerm) ||
                record.item_name.toLowerCase().includes(searchTerm) ||
                record.type.toLowerCase().includes(searchTerm) ||
                record.units.toLowerCase().includes(searchTerm);
            
            const matchesStatus = statusFilter === '' || record.status === statusFilter;
            const matchesType = typeFilter === '' || record.type === typeFilter;
            
            return matchesSearch && matchesStatus && matchesType;
        });
        
        renderStockRecords(filtered);
    }

    // Modal Functions
    function openAddStockModal() {
        document.getElementById('stockModalTitle').textContent = 'New Stock Item';
        document.getElementById('stockModalSubmitText').textContent = 'Save Stock';
        document.getElementById('stockForm').reset();
        document.getElementById('stockId').value = '';
        document.getElementById('stockIdDisplay').value = 'Auto-generated (STK00001)';
        document.getElementById('status').value = 'onstock';
        
        document.getElementById('stockModal').classList.add('modal-open');
    }

    function closeStockModal() {
        document.getElementById('stockModal').classList.remove('modal-open');
        document.getElementById('stockForm').reset();
    }

    function openSyncGrnModal() {
        populateGrnSyncTable();
        document.getElementById('syncGrnModal').classList.add('modal-open');
    }

    function closeSyncGrnModal() {
        document.getElementById('syncGrnModal').classList.remove('modal-open');
    }

    function openViewStockModal() {
        document.getElementById('viewStockModal').classList.add('modal-open');
    }

    function closeViewStockModal() {
        document.getElementById('viewStockModal').classList.remove('modal-open');
    }

    function populateGrnSyncTable() {
        const tbody = document.getElementById('grn-sync-table-body');
        const completedGrnRecords = grnRecords.filter(grn => grn.status === 'Completed');
        
        if (completedGrnRecords.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-8">
                        <i class="bx bx-package text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500">No completed GRN records available for sync</p>
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = completedGrnRecords.map(grn => {
            return `
            <tr>
                <td>
                    <input type="checkbox" class="checkbox checkbox-sm grn-sync-checkbox" value="${grn.id}">
                </td>
                <td class="font-mono font-semibold text-sm">${grn.grn_id}</td>
                <td class="text-sm">${grn.item}</td>
                <td class="text-center text-sm">${grn.qty_received}</td>
                <td>${getStatusBadge(grn.condition)}</td>
                <td>${getStatusBadge(grn.status)}</td>
            </tr>
            `;
        }).join('');
    }

    // Actions
    function viewStock(stockId) {
        const record = stockRecords.find(r => r.id === stockId);
        if (!record) return;

        const stockDetails = `
            <div class="space-y-4">
                <!-- Basic Information -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <strong class="text-gray-700 text-xs">Stock ID:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1 font-mono font-semibold">${record.stock_id}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Item Name:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${record.item_name}</p>
                    </div>
                </div>

                <!-- Type and Units -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <strong class="text-gray-700 text-xs">Type:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${record.type}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Units:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${record.units}</p>
                    </div>
                </div>

                <!-- Stock Information -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <strong class="text-gray-700 text-xs">Available Items:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1 text-center font-semibold">${record.available_item}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Status:</strong>
                        <p class="mt-1 p-2">${getStatusBadge(record.status)}</p>
                    </div>
                </div>

                <!-- GRN Reference -->
                ${record.grn_id ? `
                <div>
                    <strong class="text-gray-700 text-xs">GRN Reference:</strong>
                    <p class="text-sm p-2 bg-gray-50 rounded border mt-1 font-mono">${record.warehousing?.grn_id || 'N/A'}</p>
                </div>
                ` : ''}

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

        document.getElementById('stockDetails').innerHTML = stockDetails;
        openViewStockModal();
    }

    function editStock(stockId) {
        const record = stockRecords.find(r => r.id === stockId);
        if (!record) return;

        document.getElementById('stockModalTitle').textContent = 'Edit Stock Item';
        document.getElementById('stockModalSubmitText').textContent = 'Update Stock';
        
        document.getElementById('stockId').value = record.id;
        document.getElementById('stockIdDisplay').value = record.stock_id;
        document.getElementById('itemName').value = record.item_name;
        document.getElementById('type').value = record.type;
        document.getElementById('units').value = record.units;
        document.getElementById('availableItem').value = record.available_item;
        document.getElementById('grnId').value = record.grn_id || '';
        document.getElementById('status').value = record.status;

        document.getElementById('stockModal').classList.add('modal-open');
    }

    async function handleStockSubmit(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const stockId = document.getElementById('stockId').value;
        const isEdit = !!stockId;

        const stockData = {
            item_name: formData.get('item_name'),
            type: formData.get('type'),
            units: formData.get('units'),
            available_item: parseInt(formData.get('available_item')) || 0,
            grn_id: formData.get('grn_id') || null
        };
        
        try {
            showLoadingModal(
                isEdit ? 'Updating Stock Item...' : 'Creating Stock Item...'
            );

            let response;
            if (isEdit) {
                response = await fetch(`${API_BASE_URL}/${stockId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(stockData)
                });
            } else {
                response = await fetch(`${API_BASE_URL}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(stockData)
                });
            }

            const result = await response.json();

            if (response.ok && result.success) {
                hideLoadingModal();
                closeStockModal();
                
                // Wait for data to reload before showing success message
                await loadStockRecords();
                await loadStats();
                
                showSuccessToast(
                    isEdit ? 'Stock item updated successfully!' : 'Stock item created successfully!'
                );
            } else {
                throw new Error(result.message || `Failed to ${isEdit ? 'update' : 'create'} stock item`);
            }
        } catch (error) {
            hideLoadingModal();
            Swal.fire('Error', `Failed to ${isEdit ? 'update' : 'create'} stock item: ` + error.message, 'error');
        }
    }

    async function handleGrnSync() {
        const selectedGrns = Array.from(document.querySelectorAll('.grn-sync-checkbox:checked'))
            .map(checkbox => checkbox.value);
        
        if (selectedGrns.length === 0) {
            Swal.fire('Warning', 'Please select at least one GRN record to sync', 'warning');
            return;
        }

        try {
            showLoadingModal('Syncing GRN records to digital inventory...');
            
            const syncPromises = selectedGrns.map(grnId => 
                fetch(`${API_BASE_URL}/sync-from-grn/${grnId}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    }
                })
            );

            const results = await Promise.all(syncPromises);
            const jsonResults = await Promise.all(results.map(r => r.json()));

            const successfulSyncs = jsonResults.filter(result => result.success).length;
            const failedSyncs = jsonResults.filter(result => !result.success).length;

            hideLoadingModal();
            closeSyncGrnModal();

            if (failedSyncs === 0) {
                showSuccessToast(`Successfully synced ${successfulSyncs} GRN record(s) to digital inventory`);
            } else {
                Swal.fire({
                    icon: 'info',
                    title: 'Sync Completed',
                    html: `
                        <p>Successfully synced: ${successfulSyncs} record(s)</p>
                        <p>Failed to sync: ${failedSyncs} record(s)</p>
                        <p class="text-sm text-gray-600 mt-2">Some records may have already been synced previously.</p>
                    `
                });
            }

            // Reload data
            await loadStockRecords();
            await loadStats();
            await loadGrnOptions();

        } catch (error) {
            hideLoadingModal();
            Swal.fire('Error', 'Failed to sync GRN records: ' + error.message, 'error');
        }
    }

    async function deleteStock(stockId) {
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: "This will permanently delete the stock item!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        });

        if (result.isConfirmed) {
            try {
                showLoadingModal('Deleting Stock Item...');

                const response = await fetch(`${API_BASE_URL}/${stockId}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    hideLoadingModal();
                    
                    // Wait for data to reload before showing success message
                    await loadStockRecords();
                    await loadStats();
                    
                    showSuccessToast('Stock item deleted successfully!');
                } else {
                    throw new Error(result.message || 'Failed to delete stock item');
                }
            } catch (error) {
                hideLoadingModal();
                Swal.fire('Error', 'Failed to delete stock item: ' + error.message, 'error');
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