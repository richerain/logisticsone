@extends('layouts.app')

@section('title', 'Smart Warehousing System - Digital Inventory')

@section('content')
    <div class="module-content bg-white rounded-xl p-6 shadow block">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Digital Inventory</h2>
            <div class="flex space-x-2">
                <button class="btn btn-primary" id="addStockBtn">
                    <i class="bx bx-plus mr-2"></i>New Stock
                </button>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="flex gap-4 mb-6">
            <div class="form-control flex-1">
                <input type="text" placeholder="Search by Stock ID, Item Name, Type, Vendor, or Quote Code..." class="input input-bordered w-full" id="searchStock">
            </div>
            <select class="select select-bordered" id="statusFilter">
                <option value="">All Status</option>
                <option value="lowstock">Low Stock</option>
                <option value="onstock">On Stock</option>
                <option value="outofstock">Out of Stock</option>
            </select>
            <select class="select select-bordered" id="typeFilter">
                <option value="">All Types</option>
                <option value="Equipment">Equipment</option>
                <option value="Supplies">Supplies</option>
                <option value="Furniture">Furniture</option>
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
                        <th>Vendor</th>
                        <th>Units</th>
                        <th>Available</th>
                        <th>Purchase Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="stock-table-body">
                    <tr>
                        <td colspan="9" class="text-center py-8">
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
        <div class="modal-box max-w-6xl p-0 overflow-visible">
            <div class="flex justify-between items-center bg-green-700 p-4 rounded-t-lg">
                <h3 class="font-bold text-white text-lg" id="stockModalTitle">New Stock Item</h3>
                <button class="btn btn-sm btn-circle btn-ghost hover:bg-white/20 text-white" id="closeStockModalX">✕</button>
            </div>
            <div class="p-4 max-h-[70vh] overflow-y-auto">
                <form id="stockForm" class="space-y-4">
                    @csrf
                    <input type="hidden" id="stockId" name="id">
                    <input type="hidden" id="vendorId" name="vendor_id">
                    <input type="hidden" id="quoteId" name="quote_id">
                    
                    <!-- Auto-generated Stock ID -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Stock ID</span>
                        </label>
                        <input type="text" id="stockIdDisplay" class="input input-bordered input-sm w-full bg-gray-100" 
                               readonly placeholder="Auto-generated (STK00001)">
                    </div>

                    <!-- 4-Column Layout -->
                    <div class="grid grid-cols-4 gap-4">
                        <!-- Column 1: Item Information -->
                        <div class="space-y-4">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Item Name *</span>
                                </label>
                                <select id="itemName" name="item_name" class="select select-bordered select-sm w-full" required>
                                    <option value="">Select Item from Received Quotes</option>
                                    <!-- Options will be populated dynamically -->
                                </select>
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Type *</span>
                                </label>
                                <select id="type" name="type" class="select select-bordered select-sm w-full" required>
                                    <option value="">Select Type</option>
                                    <option value="Equipment">Equipment</option>
                                    <option value="Supplies">Supplies</option>
                                    <option value="Furniture">Furniture</option>
                                </select>
                            </div>
                        </div>

                        <!-- Column 2: Stock Information -->
                        <div class="space-y-4">
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

                        <!-- Column 3: Vendor Information -->
                        <div class="space-y-4">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Vendor Name</span>
                                </label>
                                <input type="text" id="vendorName" name="vendor_name" class="input input-bordered input-sm w-full bg-gray-100" 
                                       readonly placeholder="Auto-filled from quote">
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Quote Code</span>
                                </label>
                                <input type="text" id="quoteCode" name="quote_code" class="input input-bordered input-sm w-full bg-gray-100" 
                                       readonly placeholder="Auto-filled from quote">
                            </div>
                        </div>

                        <!-- Column 4: Pricing & Warranty -->
                        <div class="space-y-4">
                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Purchase Price</span>
                                </label>
                                <input type="number" id="purchasePrice" name="purchase_price" class="input input-bordered input-sm w-full" 
                                       min="0" step="0.01" placeholder="0.00">
                            </div>

                            <div class="form-control">
                                <label class="label">
                                    <span class="label-text font-semibold">Warranty Info</span>
                                </label>
                                <textarea id="warrantyInfo" name="warranty_info" class="textarea textarea-bordered textarea-sm w-full" 
                                          placeholder="Warranty information" rows="3"></textarea>
                            </div>
                        </div>
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

    <!-- View Stock Modal -->
    <div id="viewStockModal" class="modal modal-lg">
        <div class="modal-box max-w-6xl p-0 overflow-visible">
            <div class="flex justify-between items-center bg-green-700 p-4 rounded-t-lg">
                <h3 class="font-bold text-white text-lg">Stock Item Details</h3>
                <button class="btn btn-sm btn-circle btn-ghost hover:bg-white/20 text-white" id="closeViewStockModalX">✕</button>
            </div>
            <div class="p-4 max-h-[70vh] overflow-y-auto">
                <div class="space-y-6" id="stockDetails">
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
    let receivedQuotes = [];

    // ==================== CONFIGURATION ====================
    const API_BASE_URL = 'http://localhost:8001/api/sws/digital';

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

    function formatCurrency(amount) {
        return '₱' + parseFloat(amount || 0).toFixed(2).replace(/\d(?=(\d{3})+\.)/g, '$&,');
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
        loadReceivedQuotes();
    });

    function initializeEventListeners() {
        // Add Stock button
        document.getElementById('addStockBtn').addEventListener('click', openAddStockModal);

        // Close modal buttons
        document.getElementById('closeStockModal').addEventListener('click', closeStockModal);
        document.getElementById('closeStockModalX').addEventListener('click', closeStockModal);
        document.getElementById('closeViewStockModal').addEventListener('click', closeViewStockModal);
        document.getElementById('closeViewStockModalX').addEventListener('click', closeViewStockModal);

        // Form submission
        document.getElementById('stockForm').addEventListener('submit', handleStockSubmit);

        // Item name dropdown change
        document.getElementById('itemName').addEventListener('change', handleItemSelection);

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

async function loadReceivedQuotes() {
    try {
        console.log('Loading received quotes...');
        const response = await fetch(`${API_BASE_URL}/received-quotes`);
        
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        
        const result = await response.json();
        console.log('Received quotes response:', result);
        
        if (result.success) {
            receivedQuotes = result.data || [];
            populateItemDropdown();
        } else {
            console.warn('Received quotes loaded with warnings:', result.message);
            // Still use available data if any
            receivedQuotes = result.data || [];
            populateItemDropdown();
            
            // Show gentle warning message
            if (receivedQuotes.length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Connection Issue',
                    text: 'Unable to fetch quotes from vendor system. You can still manually enter stock items.',
                    timer: 5000,
                    showConfirmButton: false
                });
            }
        }
    } catch (error) {
        console.error('Error loading received quotes:', error);
        // Show error but don't block the UI
        receivedQuotes = [];
        populateItemDropdown();
        
        // Show gentle error message
        Swal.fire({
            icon: 'warning',
            title: 'Connection Issue',
            text: 'Unable to fetch quotes from vendor system. You can still manually enter stock items.',
            timer: 5000,
            showConfirmButton: false
        });
    }
}

    function populateItemDropdown() {
        const itemSelect = document.getElementById('itemName');
        // Clear existing options except the first one
        while (itemSelect.options.length > 1) {
            itemSelect.remove(1);
        }

        receivedQuotes.forEach(quote => {
            const option = document.createElement('option');
            option.value = quote.item_name;
            option.textContent = `${quote.item_name} (Qty: ${quote.quantity})`;
            option.setAttribute('data-quote', JSON.stringify(quote));
            itemSelect.appendChild(option);
        });
    }

    function handleItemSelection(event) {
        const selectedOption = event.target.options[event.target.selectedIndex];
        const quoteData = selectedOption.getAttribute('data-quote');
        
        if (quoteData) {
            const quote = JSON.parse(quoteData);
            // Auto-fill form fields
            document.getElementById('type').value = quote.vendor_type;
            document.getElementById('units').value = quote.units;
            document.getElementById('availableItem').value = quote.quantity;
            document.getElementById('vendorName').value = quote.vendor_name;
            document.getElementById('quoteCode').value = quote.quote_code;
            document.getElementById('purchasePrice').value = quote.unit_price;
            document.getElementById('warrantyInfo').value = quote.warranty_info;
            
            // Set hidden fields
            document.getElementById('vendorId').value = quote.vendor_id;
            document.getElementById('quoteId').value = quote.quote_id;
        } else {
            // Clear auto-filled fields if no quote selected
            document.getElementById('type').value = '';
            document.getElementById('units').value = '';
            document.getElementById('availableItem').value = '';
            document.getElementById('vendorName').value = '';
            document.getElementById('quoteCode').value = '';
            document.getElementById('purchasePrice').value = '';
            document.getElementById('warrantyInfo').value = '';
            document.getElementById('vendorId').value = '';
            document.getElementById('quoteId').value = '';
        }
    }

    function showStockLoadingState() {
        const tbody = document.getElementById('stock-table-body');
        tbody.innerHTML = `
            <tr>
                <td colspan="9" class="text-center py-8">
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
                <td colspan="9" class="text-center py-8">
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
                    <td colspan="9" class="text-center py-8">
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
                <td class="text-sm">${record.vendor_name || 'N/A'}</td>
                <td class="text-sm">${record.units}</td>
                <td class="text-center text-sm">
                    ${getStockLevelBadge(record.available_item, record.status)}
                </td>
                <td class="text-sm text-right">${formatCurrency(record.purchase_price)}</td>
                <td>${getStatusBadge(record.status)}</td>
                <td>
                    <div class="flex space-x-1">
                        <button title="View" class="btn btn-sm btn-circle btn-info view-stock-btn" data-stock-id="${record.id}">
                            <i class="bx bx-show-alt text-sm"></i>
                        </button>
                        <button title="Edit" class="btn btn-sm btn-circle btn-warning edit-stock-btn" data-stock-id="${record.id}">
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

    function filterStockRecords() {
        const searchTerm = document.getElementById('searchStock').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const typeFilter = document.getElementById('typeFilter').value;
        
        const filtered = stockRecords.filter(record => {
            const matchesSearch = searchTerm === '' || 
                record.stock_id.toLowerCase().includes(searchTerm) ||
                record.item_name.toLowerCase().includes(searchTerm) ||
                record.type.toLowerCase().includes(searchTerm) ||
                (record.vendor_name && record.vendor_name.toLowerCase().includes(searchTerm)) ||
                (record.quote_code && record.quote_code.toLowerCase().includes(searchTerm));
            
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

    function openViewStockModal() {
        document.getElementById('viewStockModal').classList.add('modal-open');
    }

    function closeViewStockModal() {
        document.getElementById('viewStockModal').classList.remove('modal-open');
    }

    // Actions
    function viewStock(stockId) {
        const record = stockRecords.find(r => r.id === stockId);
        if (!record) return;

        const stockDetails = `
            <div class="grid grid-cols-4 gap-6">
                <!-- Column 1: Basic Information -->
                <div class="space-y-4">
                    <h4 class="font-bold text-gray-700 border-b pb-2">Basic Information</h4>
                    <div>
                        <strong class="text-gray-700 text-xs">Stock ID:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1 font-mono font-semibold">${record.stock_id}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Item Name:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${record.item_name}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Type:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${record.type}</p>
                    </div>
                </div>

                <!-- Column 2: Stock Information -->
                <div class="space-y-4">
                    <h4 class="font-bold text-gray-700 border-b pb-2">Stock Information</h4>
                    <div>
                        <strong class="text-gray-700 text-xs">Units:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${record.units}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Available Items:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1 text-center font-semibold">${record.available_item}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Status:</strong>
                        <p class="mt-1 p-2">${getStatusBadge(record.status)}</p>
                    </div>
                </div>

                <!-- Column 3: Vendor Information -->
                <div class="space-y-4">
                    <h4 class="font-bold text-gray-700 border-b pb-2">Vendor Information</h4>
                    <div>
                        <strong class="text-gray-700 text-xs">Vendor Name:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${record.vendor_name || 'N/A'}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Quote Code:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1 font-mono">${record.quote_code || 'N/A'}</p>
                    </div>
                </div>

                <!-- Column 4: Pricing & Additional Info -->
                <div class="space-y-4">
                    <h4 class="font-bold text-gray-700 border-b pb-2">Pricing & Additional Info</h4>
                    <div>
                        <strong class="text-gray-700 text-xs">Purchase Price:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1 text-right font-semibold">${formatCurrency(record.purchase_price)}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Warranty Info:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${record.warranty_info || 'N/A'}</p>
                    </div>
                </div>
            </div>

            <!-- Timestamps -->
            <div class="grid grid-cols-2 gap-4 mt-6 pt-6 border-t">
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
        document.getElementById('vendorName').value = record.vendor_name || '';
        document.getElementById('quoteCode').value = record.quote_code || '';
        document.getElementById('purchasePrice').value = record.purchase_price || '';
        document.getElementById('warrantyInfo').value = record.warranty_info || '';
        document.getElementById('vendorId').value = record.vendor_id || '';
        document.getElementById('quoteId').value = record.quote_id || '';
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
            vendor_id: formData.get('vendor_id') || null,
            vendor_name: formData.get('vendor_name') || null,
            quote_id: formData.get('quote_id') || null,
            quote_code: formData.get('quote_code') || null,
            purchase_price: parseFloat(formData.get('purchase_price')) || null,
            warranty_info: formData.get('warranty_info') || null
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
    input:read-only, textarea:read-only {
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