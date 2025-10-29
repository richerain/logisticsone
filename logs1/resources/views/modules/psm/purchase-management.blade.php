@extends('layouts.app')

@section('title', 'Purchase Management')

@section('content')
    <div class="module-content bg-white rounded-xl p-6 shadow block">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Purchase Management</h2>            
        </div>

        <!-- Search and Filters -->
        <div class="flex gap-4 mb-5">
            <div class="form-control flex-1">
                <input type="text" placeholder="Search requests..." class="input input-bordered w-full" id="searchPurchases">
            </div>
            <select class="select select-bordered" id="statusFilter">
                <option value="">All Status</option>
                <option value="Pending">Pending</option>
                <option value="In Progress">In Progress</option>
                <option value="Received">Received</option>
                <option value="Approved">Approved</option>
                <option value="Rejected">Rejected</option>
            </select>
        </div>
        <div class="flex justify-end  mb-5">
            <button class="btn btn-primary" id="addPurchaseBtn">
                <i class="bx bx-plus mr-2"></i>Purchase Requisition
            </button>
        </div>

        <!-- Purchase Requests Table -->
        <div class="overflow-x-auto bg-base-100 rounded-lg">
            <table class="table table-zebra w-full">
                <thead>
                    <tr class="bg-gray-900 text-white">
                        <th>Request ID</th>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Units</th>
                        <th>Total Quote</th>
                        <th>Est. Budget</th>
                        <th>PO Number</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="purchases-table-body">
                    <tr>
                        <td colspan="9" class="text-center py-8">
                            <div class="loading loading-spinner loading-lg"></div>
                            <p class="text-gray-500 mt-2">Loading purchase requests...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add/Edit Purchase Modal -->
    <div id="purchaseModal" class="modal modal-lg">
        <div class="modal-box max-w-4xl p-0 overflow-visible">
            <div class="flex justify-between items-center bg-green-700 p-4 rounded-t-lg">
                <h3 class="font-bold text-white text-lg" id="purchaseModalTitle">Requisition Form</h3>
                <button class="btn btn-sm btn-circle btn-ghost hover:bg-white/20 text-white" id="closePurchaseModalX">✕</button>
            </div>
            <div class="p-4 max-h-[70vh] overflow-y-auto">
                <form id="purchaseForm" class="space-y-4">
                    @csrf
                    <input type="hidden" id="purchaseId" name="purchase_id">
                    
                    <!-- Auto-generated IDs Section -->
                    <div class="grid grid-cols-3 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Request ID</span>
                            </label>
                            <input type="text" id="requestId" class="input input-bordered input-sm w-full bg-gray-100" 
                                   readonly placeholder="Auto-generated">
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">PO Number</span>
                            </label>
                            <input type="text" id="poNumber" class="input input-bordered input-sm w-full bg-gray-100" 
                                   readonly placeholder="Auto-generated">
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Branch *</span>
                            </label>
                            <input type="text" id="branch" name="branch" class="input input-bordered input-sm w-full" 
                                   placeholder="Enter branch name" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Vendor *</span>
                            </label>
                            <select id="vendor" name="vendor" class="select select-bordered select-sm w-full" required>
                                <option value="">Select Vendor</option>
                            </select>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Item Name *</span>
                            </label>
                            <select id="item" name="item" class="select select-bordered select-sm w-full" required>
                                <option value="">Select Item</option>
                            </select>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Unit Price (₱)</span>
                            </label>
                            <input type="text" id="unitPrice" class="input input-bordered input-sm w-full bg-gray-100" 
                                   readonly placeholder="Auto-fetched from vendor">
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Quantity (Pack) *</span>
                            </label>
                            <input type="number" id="quantity" name="quantity" class="input input-bordered input-sm w-full" 
                                   min="1" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Units (Pcs) *</span>
                            </label>
                            <input type="number" id="units" name="units" class="input input-bordered input-sm w-full" 
                                   min="1" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Total Quote (₱)</span>
                            </label>
                            <input type="text" id="totalQuote" class="input input-bordered input-sm w-full bg-gray-100" 
                                   readonly placeholder="Auto-calculated">
                        </div>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Estimated Budget (₱) *</span>
                            </label>
                            <input type="number" id="estimatedBudget" name="estimated_budget" class="input input-bordered input-sm w-full" 
                                   min="0" step="0.01" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Expected Delivery *</span>
                            </label>
                            <input type="text" id="expectedDelivery" name="expected_delivery" class="input input-bordered input-sm w-full" 
                                   placeholder="e.g., 5 Days, 7 Days, 15 Days" required>
                        </div>

                        <!-- Empty column to maintain 3-column layout -->
                        <div class="form-control"></div>
                    </div>

                    <!-- Description Field -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Description</span>
                        </label>
                        <textarea id="description" name="description" class="textarea textarea-bordered textarea-sm h-16" 
                                  placeholder="Additional description..."></textarea>
                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-action flex justify-end space-x-3 pt-4 border-t">
                        <button type="button" class="btn btn-ghost btn-sm hover:bg-gray-100 transition-colors px-4" id="closePurchaseModal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm bg-gradient-to-r from-primary to-primary/90 hover:from-primary/90 hover:to-primary transition-all duration-300 shadow-lg px-4" id="purchaseSubmitBtn">
                            <i class="bx bx-save mr-1"></i><span id="purchaseModalSubmitText">Save Request</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Purchase Modal -->
    <div id="viewPurchaseModal" class="modal modal-lg">
        <div class="modal-box max-w-6xl p-0 overflow-visible">
            <div class="flex justify-between items-center bg-green-700 p-4 rounded-t-lg">
                <h3 class="font-bold text-white text-lg">Purchase Request Details</h3>
                <button class="btn btn-sm btn-circle btn-ghost hover:bg-white/20 text-white" id="closeViewPurchaseModalX">✕</button>
            </div>
            <div class="p-4 max-h-[70vh] overflow-y-auto">
                <div class="space-y-4" id="purchaseDetails">
                    <!-- Purchase details will be populated here -->
                </div>
                <div class="modal-action flex justify-end pt-4 border-t">
                    <button type="button" class="btn btn-ghost btn-sm hover:bg-gray-100 transition-colors px-4" id="closeViewPurchaseModal">Close</button>
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
    let purchases = [];
    let vendors = [];
    let vendorProducts = [];

    // ==================== CONFIGURATION ====================
    const API_BASE_URL = 'http://localhost:8001/api/psm/purchase';

    // Utility functions
    function formatCurrency(amount) {
        return '₱' + parseFloat(amount).toLocaleString('en-PH', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function formatDate(dateString) {
        return new Date(dateString).toLocaleDateString('en-US', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit'
        });
    }

    function getStatusBadge(status) {
        const statusClasses = {
            'Pending': 'bg-yellow-400 uppercase',
            'In Progress': 'bg-blue-400 uppercase',
            'Received': 'bg-green-400 uppercase',
            'Approved': 'bg-green-600 uppercase',
            'Rejected': 'bg-red-400 uppercase'
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
        loadVendors();
        loadPurchases();
    });

    function initializeEventListeners() {
        // Add purchase button
        document.getElementById('addPurchaseBtn').addEventListener('click', openAddPurchaseModal);

        // Close modal buttons
        document.getElementById('closePurchaseModal').addEventListener('click', closePurchaseModal);
        document.getElementById('closePurchaseModalX').addEventListener('click', closePurchaseModal);
        document.getElementById('closeViewPurchaseModal').addEventListener('click', closeViewPurchaseModal);
        document.getElementById('closeViewPurchaseModalX').addEventListener('click', closeViewPurchaseModal);

        // Form submission
        document.getElementById('purchaseForm').addEventListener('submit', handlePurchaseSubmit);

        // Auto-calculate total quote when quantity or units change
        document.getElementById('quantity').addEventListener('input', calculatePurchaseTotals);
        document.getElementById('units').addEventListener('input', calculatePurchaseTotals);

        // Vendor and item selection events
        document.getElementById('vendor').addEventListener('change', loadVendorProducts);
        document.getElementById('item').addEventListener('change', updateUnitPrice);

        // Search and filter
        document.getElementById('searchPurchases').addEventListener('input', filterPurchases);
        document.getElementById('statusFilter').addEventListener('change', filterPurchases);
    }

    function calculatePurchaseTotals() {
        const quantity = parseInt(document.getElementById('quantity').value) || 0;
        const units = parseInt(document.getElementById('units').value) || 0;
        const unitPrice = parseFloat(document.getElementById('unitPrice').value.replace('₱', '').replace(/,/g, '')) || 0;
        
        // Calculate total quote based on quantity, units, and unit price
        const totalQuote = quantity * units * unitPrice;
        
        document.getElementById('totalQuote').value = formatCurrency(totalQuote);
    }

    async function loadVendors() {
        try {
            const response = await fetch('http://localhost:8001/api/psm/vendors');
            const result = await response.json();
            
            if (result.success) {
                vendors = result.data || [];
                populateVendorDropdown();
            }
        } catch (error) {
            console.error('Error loading vendors:', error);
        }
    }

    function populateVendorDropdown() {
        const vendorSelect = document.getElementById('vendor');
        vendorSelect.innerHTML = '<option value="">Select Vendor</option>';
        
        vendors.forEach(vendor => {
            const option = document.createElement('option');
            option.value = vendor.ven_name;
            option.textContent = `${vendor.ven_name} (${vendor.vendor_type || 'Supplies'})`;
            vendorSelect.appendChild(option);
        });
    }

    async function loadVendorProducts() {
        const vendorName = document.getElementById('vendor').value;
        if (!vendorName) {
            document.getElementById('item').innerHTML = '<option value="">Select Item</option>';
            document.getElementById('unitPrice').value = '';
            return;
        }

        try {
            // Find vendor by name
            const vendor = vendors.find(v => v.ven_name === vendorName);
            if (!vendor) return;

            const response = await fetch(`http://localhost:8001/api/psm/vendors/${vendor.ven_id}/products`);
            const result = await response.json();
            
            if (result.success) {
                vendorProducts = result.data || [];
                populateItemDropdown();
            }
        } catch (error) {
            console.error('Error loading vendor products:', error);
        }
    }

    function populateItemDropdown() {
        const itemSelect = document.getElementById('item');
        itemSelect.innerHTML = '<option value="">Select Item</option>';
        
        // Filter only active products
        const activeProducts = vendorProducts.filter(product => product.product_status === 'active');
        
        activeProducts.forEach(product => {
            const option = document.createElement('option');
            option.value = product.product_name;
            option.textContent = `${product.product_name} - ${formatCurrency(product.product_price)} (Stock: ${product.product_stock})`;
            option.setAttribute('data-price', product.product_price);
            itemSelect.appendChild(option);
        });
    }

    function updateUnitPrice() {
        const itemName = document.getElementById('item').value;
        const selectedOption = document.getElementById('item').selectedOptions[0];
        
        if (selectedOption && selectedOption.getAttribute('data-price')) {
            const price = parseFloat(selectedOption.getAttribute('data-price'));
            document.getElementById('unitPrice').value = formatCurrency(price);
            calculatePurchaseTotals(); // Recalculate totals when price changes
        } else {
            document.getElementById('unitPrice').value = '';
            document.getElementById('totalQuote').value = '';
        }
    }

    async function loadPurchases() {
        try {
            showPurchasesLoadingState();
            const response = await fetch(`${API_BASE_URL}/requests`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const result = await response.json();
            
            if (result.success) {
                purchases = result.data || [];
                renderPurchases(purchases);
            } else {
                throw new Error(result.message || 'Failed to load purchase requests');
            }
        } catch (error) {
            console.error('Error loading purchase requests:', error);
            showPurchasesErrorState('Failed to load purchase requests: ' + error.message);
        }
    }

    function showPurchasesLoadingState() {
        const tbody = document.getElementById('purchases-table-body');
        tbody.innerHTML = `
            <tr>
                <td colspan="9" class="text-center py-8">
                    <div class="loading loading-spinner loading-lg"></div>
                    <p class="text-gray-500 mt-2">Loading purchase requests...</p>
                </td>
            </tr>
        `;
    }

    function showPurchasesErrorState(message) {
        const tbody = document.getElementById('purchases-table-body');
        tbody.innerHTML = `
            <tr>
                <td colspan="9" class="text-center py-8">
                    <i class="bx bx-error text-4xl text-red-400 mb-2"></i>
                    <p class="text-red-500">${message}</p>
                    <button class="btn btn-sm btn-outline mt-2" onclick="loadPurchases()">Retry</button>
                </td>
            </tr>
        `;
    }

    function renderPurchases(purchasesData) {
        const tbody = document.getElementById('purchases-table-body');
        
        if (purchasesData.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="9" class="text-center py-8">
                        <i class="bx bx-file text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500">No purchase requests found</p>
                        <button class="btn btn-sm btn-primary mt-2" id="addFirstPurchaseBtn">Create First Request</button>
                    </td>
                </tr>
            `;
            document.getElementById('addFirstPurchaseBtn')?.addEventListener('click', openAddPurchaseModal);
            return;
        }

        tbody.innerHTML = purchasesData.map(purchase => {
            return `
            <tr>
                <td class="font-mono font-semibold text-sm">${purchase.request_id}</td>
                <td class="text-sm">${purchase.item}</td>
                <td class="text-center text-sm">${purchase.quantity} Pack</td>
                <td class="text-center text-sm">${purchase.units} Pcs</td>
                <td class="text-right font-mono text-sm font-semibold">${formatCurrency(purchase.total_quote)}</td>
                <td class="text-right font-mono text-sm">${formatCurrency(purchase.estimated_budget)}</td>
                <td class="font-mono font-semibold text-sm">${purchase.po_number}</td>
                <td>${getStatusBadge(purchase.status)}</td>
                <td>
                    <div class="flex space-x-1">
                        <button title="View" class="btn btn-sm btn-circle btn-info view-purchase-btn" data-purchase-id="${purchase.purchase_id}">
                            <i class="bx bx-show-alt text-sm"></i>
                        </button>
                        <button title="Delete" class="btn btn-sm btn-circle btn-error delete-purchase-btn" data-purchase-id="${purchase.purchase_id}">
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
        document.querySelectorAll('.view-purchase-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const purchaseId = this.getAttribute('data-purchase-id');
                viewPurchase(parseInt(purchaseId));
            });
        });

        document.querySelectorAll('.delete-purchase-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const purchaseId = this.getAttribute('data-purchase-id');
                deletePurchase(parseInt(purchaseId));
            });
        });
    }

    function filterPurchases() {
        const searchTerm = document.getElementById('searchPurchases').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        
        const filtered = purchases.filter(purchase => {
            const matchesSearch = searchTerm === '' || 
                purchase.request_id.toLowerCase().includes(searchTerm) ||
                purchase.item.toLowerCase().includes(searchTerm) ||
                purchase.po_number.toLowerCase().includes(searchTerm);
            
            const matchesStatus = statusFilter === '' || purchase.status === statusFilter;
            
            return matchesSearch && matchesStatus;
        });
        
        renderPurchases(filtered);
    }

    // Modal Functions
    function openAddPurchaseModal() {
        document.getElementById('purchaseModalTitle').textContent = 'Requisition Form';
        document.getElementById('purchaseModalSubmitText').textContent = 'Save Request';
        document.getElementById('purchaseForm').reset();
        document.getElementById('purchaseId').value = '';
        
        // Clear auto-generated ID fields for new requests
        document.getElementById('requestId').value = 'Auto-generated';
        document.getElementById('poNumber').value = 'Auto-generated';
        
        // Reset vendor and item dropdowns
        document.getElementById('vendor').value = '';
        document.getElementById('item').innerHTML = '<option value="">Select Item</option>';
        document.getElementById('unitPrice').value = '';
        document.getElementById('totalQuote').value = '';
        
        calculatePurchaseTotals();
        document.getElementById('purchaseModal').classList.add('modal-open');
    }

    function closePurchaseModal() {
        document.getElementById('purchaseModal').classList.remove('modal-open');
        document.getElementById('purchaseForm').reset();
    }

    function openViewPurchaseModal() {
        document.getElementById('viewPurchaseModal').classList.add('modal-open');
    }

    function closeViewPurchaseModal() {
        document.getElementById('viewPurchaseModal').classList.remove('modal-open');
    }

    // Purchase Actions
    function viewPurchase(purchaseId) {
        const purchase = purchases.find(p => p.purchase_id === purchaseId);
        if (!purchase) return;

        const purchaseDetails = `
            <div class="space-y-4">
                <!-- Basic Information - 4 Column Layout -->
                <div class="grid grid-cols-4 gap-4">
                    <div>
                        <strong class="text-gray-700 text-xs">Request ID:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1 font-mono">${purchase.request_id}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">PO Number:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1 font-mono">${purchase.po_number}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Branch:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${purchase.branch}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Vendor:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${purchase.vendor}</p>
                    </div>
                </div>

                <!-- Item Information - 4 Column Layout -->
                <div class="grid grid-cols-4 gap-4">
                    <div>
                        <strong class="text-gray-700 text-xs">Item Name:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${purchase.item}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Quantity:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1 text-center">${purchase.quantity} Pack</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Units:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1 text-center">${purchase.units} Pcs</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Unit Price:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1 text-right font-mono">${formatCurrency(purchase.unit_price)}</p>
                    </div>
                </div>

                <!-- Financial Information - 4 Column Layout -->
                <div class="grid grid-cols-4 gap-4">
                    <div>
                        <strong class="text-gray-700 text-xs">Total Quote:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1 text-right font-mono font-semibold">${formatCurrency(purchase.total_quote)}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Estimated Budget:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1 text-right font-mono">${formatCurrency(purchase.estimated_budget)}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Expected Delivery:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1 text-center">${purchase.expected_delivery}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Quote Date:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${formatDate(purchase.quote_date)}</p>
                    </div>
                </div>

                <!-- Status - 4 Column Layout -->
                <div class="grid grid-cols-4 gap-4">
                    <div>
                        <strong class="text-gray-700 text-xs">Status:</strong>
                        <p class="mt-1 p-2">${getStatusBadge(purchase.status)}</p>
                    </div>
                    <!-- Empty columns to maintain 4-column layout -->
                    <div></div>
                    <div></div>
                    <div></div>
                </div>

                <!-- Description -->
                ${purchase.description ? `
                <div>
                    <strong class="text-gray-700 text-xs">Description:</strong>
                    <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${purchase.description}</p>
                </div>
                ` : ''}

                <!-- Timestamps -->
                <div class="grid grid-cols-2 gap-4">
                    ${purchase.created_at ? `
                    <div>
                        <strong class="text-gray-700 text-xs">Created:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${new Date(purchase.created_at).toLocaleString()}</p>
                    </div>
                    ` : ''}
                    ${purchase.updated_at ? `
                    <div>
                        <strong class="text-gray-700 text-xs">Last Updated:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${new Date(purchase.updated_at).toLocaleString()}</p>
                    </div>
                    ` : ''}
                </div>
            </div>
        `;

        document.getElementById('purchaseDetails').innerHTML = purchaseDetails;
        openViewPurchaseModal();
    }

    async function handlePurchaseSubmit(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const purchaseId = document.getElementById('purchaseId').value;
        const isEdit = !!purchaseId;

        // Auto-set current date for quote_date and status as "Pending" for new requests
        const purchaseData = {
            branch: formData.get('branch'),
            vendor: formData.get('vendor'),
            item: formData.get('item'),
            quantity: parseInt(formData.get('quantity')) || 1,
            units: parseInt(formData.get('units')) || 1,
            unit_price: parseFloat(document.getElementById('unitPrice').value.replace('₱', '').replace(/,/g, '')) || 0,
            estimated_budget: parseFloat(formData.get('estimated_budget')) || 0,
            expected_delivery: formData.get('expected_delivery'),
            quote_date: new Date().toISOString().split('T')[0], // Auto-set current date
            status: isEdit ? undefined : 'Pending', // Auto-set to "Pending" for new requests
            description: formData.get('description')
        };
        
        try {
            showLoadingModal(
                isEdit ? 'Updating Request...' : 'Creating Request...'
            );

            let response;
            if (isEdit) {
                response = await fetch(`${API_BASE_URL}/requests/${purchaseId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(purchaseData)
                });
            } else {
                response = await fetch(`${API_BASE_URL}/requests`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(purchaseData)
                });
            }

            const result = await response.json();

            if (response.ok && result.success) {
                hideLoadingModal();
                closePurchaseModal();
                
                // Wait for data to reload before showing success message
                await loadPurchases();
                
                showSuccessToast(
                    isEdit ? 'Purchase request updated successfully!' : 'Purchase request created successfully!'
                );
            } else {
                throw new Error(result.message || `Failed to ${isEdit ? 'update' : 'create'} purchase request`);
            }
        } catch (error) {
            hideLoadingModal();
            Swal.fire('Error', `Failed to ${isEdit ? 'update' : 'create'} purchase request: ` + error.message, 'error');
        }
    }

    async function deletePurchase(purchaseId) {
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: "This will permanently delete the purchase request!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        });

        if (result.isConfirmed) {
            try {
                showLoadingModal('Deleting Request...');

                const response = await fetch(`${API_BASE_URL}/requests/${purchaseId}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    hideLoadingModal();
                    
                    // Wait for data to reload before showing success message
                    await loadPurchases();
                    
                    showSuccessToast('Purchase request deleted successfully!');
                } else {
                    throw new Error(result.message || 'Failed to delete purchase request');
                }
            } catch (error) {
                hideLoadingModal();
                Swal.fire('Error', 'Failed to delete purchase request: ' + error.message, 'error');
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