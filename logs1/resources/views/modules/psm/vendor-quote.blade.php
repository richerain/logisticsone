@extends('layouts.app')

@section('title', 'Vendor Quote Management')

@section('content')
    <div class="module-content bg-white rounded-xl p-6 shadow block">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Vendor Quote Management</h2>
            <button class="btn btn-primary" id="addQuoteBtn">
                <i class="bx bx-plus mr-2"></i>Add New Quote
            </button>
        </div>
        <p>for vendor acc only </p>
        <p>remove reject status</p>
        <p>can view</p>
        <p>can change status</p>
        <p></p>
        <p></p>
        <p></p>
        <p></p>

        <!-- Stats Section -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-primary">
                <div class="stat-figure text-primary">
                    <i class="bx bx-file text-3xl"></i>
                </div>
                <div class="stat-title">Total Quotes</div>
                <div class="stat-value text-primary" id="total-quotes">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-warning">
                <div class="stat-figure text-warning">
                    <i class="bx bx-time text-3xl"></i>
                </div>
                <div class="stat-title">Pending</div>
                <div class="stat-value text-warning" id="pending-quotes">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-success">
                <div class="stat-figure text-success">
                    <i class="bx bx-check-circle text-3xl"></i>
                </div>
                <div class="stat-title">Approved</div>
                <div class="stat-value text-success" id="approved-quotes">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-info">
                <div class="stat-figure text-info">
                    <i class="bx bx-calendar text-3xl"></i>
                </div>
                <div class="stat-title">This Month</div>
                <div class="stat-value text-info" id="monthly-quotes">0</div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="flex gap-4 mb-6">
            <div class="form-control flex-1">
                <input type="text" placeholder="Search quotes..." class="input input-bordered w-full" id="searchQuotes">
            </div>
            <select class="select select-bordered" id="statusFilter">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="received">Received</option>
                <option value="approved">Approved</option>
                <option value="rejected">Rejected</option>
            </select>
            <input type="date" class="input input-bordered" id="dateFilter" placeholder="Filter by date">
        </div>

        <!-- Quotes Table -->
        <div class="overflow-x-auto bg-base-100 rounded-lg">
            <table class="table table-zebra w-full">
                <thead>
                    <tr class="bg-gray-900 text-white">
                        <th>Quote ID</th>
                        <th>Request ID</th>
                        <th>Vendor</th>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Unit Price</th>
                        <th>Total Quote</th>
                        <th>Delivery Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="quotes-table-body">
                    <tr>
                        <td colspan="10" class="text-center py-8">
                            <div class="loading loading-spinner loading-lg"></div>
                            <p class="text-gray-500 mt-2">Loading quotes...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add/Edit Quote Modal -->
    <div id="quoteModal" class="modal modal-lg">
        <div class="modal-box max-w-2xl p-0 overflow-visible">
            <div class="flex justify-between items-center bg-green-700 p-4 rounded-t-lg">
                <h3 class="font-bold text-white text-lg" id="quoteModalTitle">New Quote</h3>
                <button class="btn btn-sm btn-circle btn-ghost hover:bg-white/20 text-white" id="closeQuoteModalX">✕</button>
            </div>
            <div class="p-4 max-h-[70vh] overflow-y-auto">
                <form id="quoteForm" class="space-y-4">
                    @csrf
                    <input type="hidden" id="quoteId" name="quote_id">
                    
                    <!-- Auto-generated IDs Section -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Quote ID</span>
                            </label>
                            <input type="text" id="quoteCode" class="input input-bordered input-sm w-full bg-gray-100" 
                                   readonly placeholder="Auto-generated">
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Request ID *</span>
                            </label>
                            <select id="requestId" name="request_id" class="select select-bordered select-sm w-full" required>
                                <option value="">Select Request ID</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Vendor *</span>
                        </label>
                        <select id="vendorId" name="ven_id" class="select select-bordered select-sm w-full" required>
                            <option value="">Select Vendor</option>
                        </select>
                    </div>

                    <!-- Auto-filled fields from Purchase Management -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Item Name</span>
                            </label>
                            <input type="text" id="itemName" class="input input-bordered input-sm w-full bg-gray-100" 
                                   readonly placeholder="Auto-filled from purchase">
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Quantity</span>
                            </label>
                            <input type="text" id="quantity" class="input input-bordered input-sm w-full bg-gray-100" 
                                   readonly placeholder="Auto-filled from purchase">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Unit Price (₱)</span>
                            </label>
                            <input type="text" id="unitPrice" class="input input-bordered input-sm w-full bg-gray-100" 
                                   readonly placeholder="Auto-filled from purchase">
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Total Quote (₱)</span>
                            </label>
                            <input type="text" id="totalQuote" class="input input-bordered input-sm w-full bg-gray-100" 
                                   readonly placeholder="Auto-calculated">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Delivery Lead Time (Days)</span>
                            </label>
                            <input type="text" id="deliveryLeadTime" class="input input-bordered input-sm w-full bg-gray-100" 
                                   readonly placeholder="Auto-filled from purchase">
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Quote Date</span>
                            </label>
                            <input type="text" id="quoteDate" class="input input-bordered input-sm w-full bg-gray-100" 
                                   readonly placeholder="Auto-filled from purchase">
                        </div>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Status</span>
                        </label>
                        <select id="quoteStatus" name="status" class="select select-bordered select-sm w-full">
                            <option value="pending">Pending</option>
                            <option value="received">Received</option>
                            <option value="approved">Approved</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Notes</span>
                        </label>
                        <textarea id="quoteNotes" name="notes" class="textarea textarea-bordered textarea-sm h-16" 
                                  readonly placeholder="Auto-filled from purchase description"></textarea>
                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-action flex justify-end space-x-3 pt-4 border-t">
                        <button type="button" class="btn btn-ghost btn-sm hover:bg-gray-100 transition-colors px-4" id="closeQuoteModal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm bg-gradient-to-r from-primary to-primary/90 hover:from-primary/90 hover:to-primary transition-all duration-300 shadow-lg px-4" id="quoteSubmitBtn">
                            <i class="bx bx-save mr-1"></i><span id="quoteModalSubmitText">Save Quote</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Quote Modal -->
    <div id="viewQuoteModal" class="modal modal-lg">
        <div class="modal-box max-w-2xl p-0 overflow-visible">
            <div class="flex justify-between items-center bg-green-700 p-4 rounded-t-lg">
                <h3 class="font-bold text-white text-lg">Quote Details</h3>
                <button class="btn btn-sm btn-circle btn-ghost hover:bg-white/20 text-white" id="closeViewQuoteModalX">✕</button>
            </div>
            <div class="p-4 max-h-[70vh] overflow-y-auto">
                <div class="space-y-4" id="quoteDetails">
                    <!-- Quote details will be populated here -->
                </div>
                <div class="modal-action flex justify-end pt-4 border-t">
                    <button type="button" class="btn btn-ghost btn-sm hover:bg-gray-100 transition-colors px-4" id="closeViewQuoteModal">Close</button>
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
    let quotes = [];
    let vendors = [];
    let purchaseRequests = [];

    // ==================== CONFIGURATION ====================
    const API_BASE_URL = 'http://localhost:8001/api/psm';

    // Utility functions
    function formatCurrency(amount) {
        return '₱' + parseFloat(amount).toLocaleString('en-PH', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

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
            'pending': 'bg-yellow-400',
            'received': 'bg-blue-400',
            'approved': 'bg-green-400',
            'rejected': 'bg-red-400'
        };
        
        return `<span class="badge text-white font-bold tracking-wider text-xs px-3 py-2 ${statusClasses[status] || 'bg-gray-400'} border-0">
            ${status.toUpperCase()}
        </span>`;
    }

    // Map purchase status to quote status
    function mapPurchaseStatusToQuote(purchaseStatus) {
        const statusMapping = {
            'Pending': 'pending',
            'In Progress': 'received',
            'Received': 'received',
            'Approved': 'approved',
            'Rejected': 'rejected'
        };
        return statusMapping[purchaseStatus] || 'pending';
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
        loadPurchaseRequests();
        loadQuotes();
    });

    function initializeEventListeners() {
        // Add quote button
        document.getElementById('addQuoteBtn').addEventListener('click', openAddQuoteModal);

        // Close modal buttons
        document.getElementById('closeQuoteModal').addEventListener('click', closeQuoteModal);
        document.getElementById('closeQuoteModalX').addEventListener('click', closeQuoteModal);
        document.getElementById('closeViewQuoteModal').addEventListener('click', closeViewQuoteModal);
        document.getElementById('closeViewQuoteModalX').addEventListener('click', closeViewQuoteModal);

        // Form submission
        document.getElementById('quoteForm').addEventListener('submit', handleQuoteSubmit);

        // Request ID change event
        document.getElementById('requestId').addEventListener('change', function() {
            const selectedRequestId = this.value;
            if (selectedRequestId) {
                const purchase = purchaseRequests.find(p => p.request_id === selectedRequestId);
                if (purchase) {
                    // Auto-fill all fields from purchase request
                    document.getElementById('itemName').value = purchase.item || '';
                    document.getElementById('quantity').value = purchase.quantity || '';
                    document.getElementById('unitPrice').value = formatCurrency(purchase.unit_price || 0);
                    document.getElementById('totalQuote').value = formatCurrency(purchase.total_quote || 0);
                    
                    // Extract number from expected_delivery (e.g., "5 Days" -> 5)
                    const deliveryMatch = purchase.expected_delivery ? purchase.expected_delivery.match(/\d+/) : null;
                    const deliveryDays = deliveryMatch ? deliveryMatch[0] : '';
                    document.getElementById('deliveryLeadTime').value = deliveryDays;
                    
                    // Format quote date
                    document.getElementById('quoteDate').value = formatDate(purchase.quote_date);
                    
                    // Set status based on purchase status
                    const quoteStatus = mapPurchaseStatusToQuote(purchase.status);
                    document.getElementById('quoteStatus').value = quoteStatus;
                    
                    // Set notes from purchase description
                    document.getElementById('quoteNotes').value = purchase.description || '';
                    
                    // Auto-select vendor if name matches
                    const vendor = vendors.find(v => v.ven_name === purchase.vendor);
                    if (vendor) {
                        document.getElementById('vendorId').value = vendor.ven_id;
                    }
                }
            } else {
                // Clear auto-filled fields
                clearAutoFilledFields();
            }
        });

        // Search and filter
        document.getElementById('searchQuotes').addEventListener('input', filterQuotes);
        document.getElementById('statusFilter').addEventListener('change', filterQuotes);
        document.getElementById('dateFilter').addEventListener('change', filterQuotes);
    }

    function clearAutoFilledFields() {
        document.getElementById('itemName').value = '';
        document.getElementById('quantity').value = '';
        document.getElementById('unitPrice').value = '';
        document.getElementById('totalQuote').value = '';
        document.getElementById('deliveryLeadTime').value = '';
        document.getElementById('quoteDate').value = '';
        document.getElementById('quoteStatus').value = 'pending';
        document.getElementById('quoteNotes').value = '';
        document.getElementById('vendorId').value = '';
    }

    async function loadPurchaseRequests() {
        try {
            const response = await fetch(`${API_BASE_URL}/purchase/requests-for-quotes`);
            const result = await response.json();
            
            if (result.success) {
                purchaseRequests = result.data || [];
                populateRequestIdDropdown();
            } else {
                console.error('Failed to load purchase requests:', result.message);
            }
        } catch (error) {
            console.error('Error loading purchase requests:', error);
        }
    }

    function populateRequestIdDropdown() {
        const requestSelect = document.getElementById('requestId');
        requestSelect.innerHTML = '<option value="">Select Request ID</option>';
        
        purchaseRequests.forEach(purchase => {
            const option = document.createElement('option');
            option.value = purchase.request_id;
            option.textContent = `${purchase.request_id} - ${purchase.item} (${purchase.vendor})`;
            option.setAttribute('data-purchase', JSON.stringify(purchase));
            requestSelect.appendChild(option);
        });
    }

    async function loadVendors() {
        try {
            const response = await fetch(`${API_BASE_URL}/vendors`);
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
        const vendorSelect = document.getElementById('vendorId');
        vendorSelect.innerHTML = '<option value="">Select Vendor</option>';
        
        vendors.forEach(vendor => {
            const option = document.createElement('option');
            option.value = vendor.ven_id;
            option.textContent = `${vendor.ven_name} (${vendor.ven_code || vendor.ven_email})`;
            vendorSelect.appendChild(option);
        });
    }

    async function loadQuotes() {
        try {
            showQuotesLoadingState();
            const response = await fetch(`${API_BASE_URL}/quotes`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const result = await response.json();
            
            if (result.success) {
                quotes = result.data || [];
                renderQuotes(quotes);
                updateStats(quotes);
            } else {
                throw new Error(result.message || 'Failed to load quotes');
            }
        } catch (error) {
            console.error('Error loading quotes:', error);
            showQuotesErrorState('Failed to load quotes: ' + error.message);
        }
    }

    function showQuotesLoadingState() {
        const tbody = document.getElementById('quotes-table-body');
        tbody.innerHTML = `
            <tr>
                <td colspan="10" class="text-center py-8">
                    <div class="loading loading-spinner loading-lg"></div>
                    <p class="text-gray-500 mt-2">Loading quotes...</p>
                </td>
            </tr>
        `;
    }

    function showQuotesErrorState(message) {
        const tbody = document.getElementById('quotes-table-body');
        tbody.innerHTML = `
            <tr>
                <td colspan="10" class="text-center py-8">
                    <i class="bx bx-error text-4xl text-red-400 mb-2"></i>
                    <p class="text-red-500">${message}</p>
                    <button class="btn btn-sm btn-outline mt-2" onclick="loadQuotes()">Retry</button>
                </td>
            </tr>
        `;
    }

    function renderQuotes(quotesData) {
        const tbody = document.getElementById('quotes-table-body');
        
        if (quotesData.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="10" class="text-center py-8">
                        <i class="bx bx-file text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500">No quotes found</p>
                        <button class="btn btn-sm btn-primary mt-2" id="addFirstQuoteBtn">Add First Quote</button>
                    </td>
                </tr>
            `;
            document.getElementById('addFirstQuoteBtn')?.addEventListener('click', openAddQuoteModal);
            return;
        }

        tbody.innerHTML = quotesData.map(quote => {
            return `
            <tr>
                <td class="font-mono font-semibold text-sm">${quote.quote_code}</td>
                <td class="font-mono font-semibold text-sm">${quote.request_id}</td>
                <td>
                    <div class="font-semibold text-sm">${quote.vendor?.ven_name || 'N/A'}</div>
                    <div class="text-xs text-gray-500">${quote.vendor?.ven_code || ''}</div>
                </td>
                <td class="text-sm">${quote.item_name}</td>
                <td class="text-center text-sm">${quote.quantity}</td>
                <td class="text-right font-mono text-sm">${formatCurrency(quote.unit_price)}</td>
                <td class="text-right font-mono text-sm font-semibold">${formatCurrency(quote.total_quote)}</td>
                <td class="text-center text-sm">${quote.delivery_lead_time} Days</td>
                <td>${getStatusBadge(quote.status)}</td>
                <td>
                    <div class="flex space-x-1">
                        <button title="View" class="btn btn-sm btn-circle btn-info view-quote-btn" data-quote-id="${quote.quote_id}">
                            <i class="bx bx-show-alt text-sm"></i>
                        </button>
                        <button title="Edit" class="btn btn-sm btn-circle btn-warning edit-quote-btn" data-quote-id="${quote.quote_id}">
                            <i class="bx bx-edit text-sm"></i>
                        </button>
                        <button title="Delete" class="btn btn-sm btn-circle btn-error delete-quote-btn" data-quote-id="${quote.quote_id}">
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
        document.querySelectorAll('.view-quote-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const quoteId = this.getAttribute('data-quote-id');
                viewQuote(parseInt(quoteId));
            });
        });

        document.querySelectorAll('.edit-quote-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const quoteId = this.getAttribute('data-quote-id');
                editQuote(parseInt(quoteId));
            });
        });

        document.querySelectorAll('.delete-quote-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const quoteId = this.getAttribute('data-quote-id');
                deleteQuote(parseInt(quoteId));
            });
        });
    }

    function updateStats(quotesData) {
        const now = new Date();
        const currentMonth = now.getMonth();
        const currentYear = now.getFullYear();
        
        document.getElementById('total-quotes').textContent = quotesData.length;
        document.getElementById('pending-quotes').textContent = 
            quotesData.filter(q => q.status === 'pending').length;
        document.getElementById('approved-quotes').textContent = 
            quotesData.filter(q => q.status === 'approved').length;
        document.getElementById('monthly-quotes').textContent = 
            quotesData.filter(q => {
                const quoteDate = new Date(q.quote_date);
                return quoteDate.getMonth() === currentMonth && quoteDate.getFullYear() === currentYear;
            }).length;
    }

    function filterQuotes() {
        const searchTerm = document.getElementById('searchQuotes').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const dateFilter = document.getElementById('dateFilter').value;
        
        const filtered = quotes.filter(quote => {
            const matchesSearch = searchTerm === '' || 
                quote.quote_code.toLowerCase().includes(searchTerm) ||
                (quote.request_id && quote.request_id.toLowerCase().includes(searchTerm)) ||
                quote.item_name.toLowerCase().includes(searchTerm) ||
                (quote.vendor?.ven_name && quote.vendor.ven_name.toLowerCase().includes(searchTerm));
            
            const matchesStatus = statusFilter === '' || quote.status === statusFilter;
            const matchesDate = dateFilter === '' || quote.quote_date === dateFilter;
            
            return matchesSearch && matchesStatus && matchesDate;
        });
        
        renderQuotes(filtered);
        updateStats(filtered);
    }

    // Modal Functions
    function openAddQuoteModal() {
        document.getElementById('quoteModalTitle').textContent = 'Add New Quote';
        document.getElementById('quoteModalSubmitText').textContent = 'Save Quote';
        document.getElementById('quoteForm').reset();
        document.getElementById('quoteId').value = '';
        
        // Clear auto-generated ID fields for new quotes
        document.getElementById('quoteCode').value = 'Auto-generated';
        
        // Clear auto-filled fields
        clearAutoFilledFields();
        
        // Enable all fields for new quote
        document.getElementById('requestId').disabled = false;
        document.getElementById('vendorId').disabled = false;
        document.getElementById('quoteStatus').disabled = false;
        document.getElementById('quoteNotes').readOnly = false;
        
        document.getElementById('quoteModal').classList.add('modal-open');
    }

    function closeQuoteModal() {
        document.getElementById('quoteModal').classList.remove('modal-open');
        document.getElementById('quoteForm').reset();
    }

    function openViewQuoteModal() {
        document.getElementById('viewQuoteModal').classList.add('modal-open');
    }

    function closeViewQuoteModal() {
        document.getElementById('viewQuoteModal').classList.remove('modal-open');
    }

    // Quote Actions
    function viewQuote(quoteId) {
        const quote = quotes.find(q => q.quote_id === quoteId);
        if (!quote) return;

        const quoteDetails = `
            <div class="space-y-4">
                <!-- Basic Information -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <strong class="text-gray-700 text-xs">Quote ID:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1 font-mono">${quote.quote_code}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Request ID:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1 font-mono">${quote.request_id}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Vendor:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${quote.vendor?.ven_name || 'N/A'}</p>
                        <p class="text-xs text-gray-500 mt-1">${quote.vendor?.ven_code || ''}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Status:</strong>
                        <p class="mt-1 p-2">${getStatusBadge(quote.status)}</p>
                    </div>
                </div>

                <!-- Item Information -->
                <div>
                    <strong class="text-gray-700 text-xs">Item Name:</strong>
                    <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${quote.item_name}</p>
                </div>

                <!-- Quantity and Pricing -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <strong class="text-gray-700 text-xs">Quantity:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1 text-center">${quote.quantity}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Units:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1 text-center">${quote.units?.toLocaleString() || '0'}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Unit Price:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1 text-right font-mono">${formatCurrency(quote.unit_price)}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Total Quote:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1 text-right font-mono font-semibold">${formatCurrency(quote.total_quote)}</p>
                    </div>
                </div>

                <!-- Delivery and Date -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <strong class="text-gray-700 text-xs">Delivery Lead Time:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1 text-center">${quote.delivery_lead_time} Days</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Quote Date:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${formatDate(quote.quote_date)}</p>
                    </div>
                </div>

                <!-- Notes -->
                ${quote.notes ? `
                <div>
                    <strong class="text-gray-700 text-xs">Notes:</strong>
                    <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${quote.notes}</p>
                </div>
                ` : ''}

                <!-- Timestamps -->
                <div class="grid grid-cols-2 gap-4">
                    ${quote.created_at ? `
                    <div>
                        <strong class="text-gray-700 text-xs">Created:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${new Date(quote.created_at).toLocaleString()}</p>
                    </div>
                    ` : ''}
                    ${quote.updated_at ? `
                    <div>
                        <strong class="text-gray-700 text-xs">Last Updated:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${new Date(quote.updated_at).toLocaleString()}</p>
                    </div>
                    ` : ''}
                </div>
            </div>
        `;

        document.getElementById('quoteDetails').innerHTML = quoteDetails;
        openViewQuoteModal();
    }

    function editQuote(quoteId) {
        const quote = quotes.find(q => q.quote_id === quoteId);
        if (!quote) return;

        document.getElementById('quoteModalTitle').textContent = 'Edit Quote';
        document.getElementById('quoteModalSubmitText').textContent = 'Update Quote';
        
        document.getElementById('quoteId').value = quote.quote_id;
        document.getElementById('quoteCode').value = quote.quote_code;
        document.getElementById('requestId').value = quote.request_id;
        document.getElementById('vendorId').value = quote.ven_id;
        document.getElementById('itemName').value = quote.item_name;
        document.getElementById('quantity').value = quote.quantity;
        document.getElementById('unitPrice').value = formatCurrency(quote.unit_price);
        document.getElementById('totalQuote').value = formatCurrency(quote.total_quote);
        document.getElementById('deliveryLeadTime').value = quote.delivery_lead_time;
        document.getElementById('quoteDate').value = formatDate(quote.quote_date);
        document.getElementById('quoteStatus').value = quote.status;
        document.getElementById('quoteNotes').value = quote.notes || '';

        // Disable fields that shouldn't be edited
        document.getElementById('requestId').disabled = true;
        document.getElementById('itemName').disabled = true;
        document.getElementById('quantity').disabled = true;
        document.getElementById('unitPrice').disabled = true;
        document.getElementById('totalQuote').disabled = true;
        document.getElementById('deliveryLeadTime').disabled = true;
        document.getElementById('quoteDate').disabled = true;
        document.getElementById('vendorId').disabled = true;
        
        // Only allow editing status and notes
        document.getElementById('quoteStatus').disabled = false;
        document.getElementById('quoteNotes').readOnly = false;

        document.getElementById('quoteModal').classList.add('modal-open');
    }

async function handleQuoteSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const quoteId = document.getElementById('quoteId').value;
    const isEdit = !!quoteId;

    // Format the date properly for the backend
    const quoteDateInput = document.getElementById('quoteDate').value;
    const quoteDate = quoteDateInput ? new Date(quoteDateInput).toISOString().split('T')[0] : '';

    const quoteData = {
        request_id: formData.get('request_id'),
        ven_id: parseInt(formData.get('ven_id')),
        delivery_lead_time: parseInt(document.getElementById('deliveryLeadTime').value) || 0,
        quote_date: quoteDate,
        status: formData.get('status'),
        notes: formData.get('notes')
    };
    
    try {
        showLoadingModal(
            isEdit ? 'Updating Quote...' : 'Creating Quote...',
            isEdit ? 'Please wait while we update quote information.' : 'Please wait while we create new quote.'
        );

        let response;
        if (isEdit) {
            response = await fetch(`${API_BASE_URL}/quotes/${quoteId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(quoteData)
            });
        } else {
            response = await fetch(`${API_BASE_URL}/quotes`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(quoteData)
            });
        }

        const result = await response.json();

        if (response.ok && result.success) {
            hideLoadingModal();
            closeQuoteModal();
            
            // Wait for data to reload before showing success message
            await loadQuotes();
            
            showSuccessToast(
                isEdit ? 'Quote updated successfully!' : 'Quote created successfully!'
            );
        } else {
            throw new Error(result.message || `Failed to ${isEdit ? 'update' : 'create'} quote`);
        }
    } catch (error) {
        hideLoadingModal();
        Swal.fire('Error', `Failed to ${isEdit ? 'update' : 'create'} quote: ` + error.message, 'error');
    }
}

    async function deleteQuote(quoteId) {
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: "This will permanently delete the quote!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        });

        if (result.isConfirmed) {
            try {
                showLoadingModal('Deleting Quote...', 'Please wait while we remove the quote.');

                const response = await fetch(`${API_BASE_URL}/quotes/${quoteId}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    hideLoadingModal();
                    
                    // Wait for data to reload before showing success message
                    await loadQuotes();
                    
                    showSuccessToast('Quote deleted successfully!');
                } else {
                    throw new Error(result.message || 'Failed to delete quote');
                }
            } catch (error) {
                hideLoadingModal();
                Swal.fire('Error', 'Failed to delete quote: ' + error.message, 'error');
            }
        }
    }
</script>

<style>
    .modal-box {
        max-height: 85vh;
    }
    input:read-only, select:disabled, textarea:read-only {
        background-color: #f3f4f6;
        cursor: not-allowed;
    }
    .modal-box .max-h-\[70vh\] {
        max-height: 70vh;
    }
</style>
@endsection