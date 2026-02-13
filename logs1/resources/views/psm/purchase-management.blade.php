<!-- resources/views/psm/purchase-management.blade.php -->
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bxs-purchase-tag'></i>Purchase Management</h2>
    </div>
    <div class="text-right">
        <span class="text-md text-gray-600">Procurement & Sourcing Management</span>
    </div>
</div>

<!-- Stats Section -->
<div id="statsSection" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-gradient-to-br from-blue-500 to-blue-600 rounded-2xl p-5 shadow-lg shadow-blue-100 group hover:scale-[1.02] transition-all duration-300">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-blue-100 text-sm font-medium mb-1">Total Orders</p>
                <h3 id="totalOrders" class="text-3xl font-bold text-white tracking-tight">0</h3>
            </div>
            <div class="p-3 bg-white/20 rounded-xl backdrop-blur-md group-hover:rotate-12 transition-transform">
                <i class='bx bx-shopping-bag text-white text-2xl'></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-blue-100 text-xs">
            <span class="bg-white/20 px-2 py-0.5 rounded-full mr-2">Overview</span>
            <span>Cumulative total</span>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-2xl p-5 shadow-lg shadow-emerald-100 group hover:scale-[1.02] transition-all duration-300">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-emerald-100 text-sm font-medium mb-1">Approved</p>
                <h3 id="approvedOrders" class="text-3xl font-bold text-white tracking-tight">0</h3>
            </div>
            <div class="p-3 bg-white/20 rounded-xl backdrop-blur-md group-hover:rotate-12 transition-transform">
                <i class='bx bx-check-double text-white text-2xl'></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-emerald-100 text-xs">
            <span class="bg-white/20 px-2 py-0.5 rounded-full mr-2">Verified</span>
            <span>Ready for processing</span>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-amber-500 to-amber-600 rounded-2xl p-5 shadow-lg shadow-amber-100 group hover:scale-[1.02] transition-all duration-300 relative overflow-hidden">
        <div class="absolute top-2 right-2 flex space-x-1" id="pendingPulse" style="display: none;">
            <span class="relative flex h-3 w-3">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-white opacity-75"></span>
                <span class="relative inline-flex rounded-full h-3 w-3 bg-white"></span>
            </span>
        </div>
        <div class="flex justify-between items-start">
            <div>
                <p class="text-amber-100 text-sm font-medium mb-1">Pending</p>
                <h3 id="pendingOrders" class="text-3xl font-bold text-white tracking-tight">0</h3>
            </div>
            <div class="p-3 bg-white/20 rounded-xl backdrop-blur-md group-hover:rotate-12 transition-transform">
                <i class='bx bx-time-five text-white text-2xl'></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-amber-100 text-xs">
            <span class="bg-white/20 px-2 py-0.5 rounded-full mr-2">Action Required</span>
            <span>Awaiting review</span>
        </div>
    </div>
    
    <div class="bg-gradient-to-br from-rose-500 to-rose-600 rounded-2xl p-5 shadow-lg shadow-rose-100 group hover:scale-[1.02] transition-all duration-300">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-rose-100 text-sm font-medium mb-1">Cancelled</p>
                <h3 id="cancelledOrders" class="text-3xl font-bold text-white tracking-tight">0</h3>
            </div>
            <div class="p-3 bg-white/20 rounded-xl backdrop-blur-md group-hover:rotate-12 transition-transform">
                <i class='bx bx-x-circle text-white text-2xl'></i>
            </div>
        </div>
        <div class="mt-4 flex items-center text-rose-100 text-xs">
            <span class="bg-white/20 px-2 py-0.5 rounded-full mr-2">Inactive</span>
            <span>Voided orders</span>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg p-6">
    <!-- Header Section -->
    <div class="flex flex-col space-y-4 mb-6">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <i class='bx bx-cart-alt text-2xl text-gray-800'></i>
                <h3 class="text-lg font-bold text-gray-800 tracking-tight leading-none">Purchase Orders</h3>
            </div>
            <div class="flex gap-2">
                <div class="relative">
                    <button id="openRequisitionsBtn" class="bg-emerald-600 hover:bg-emerald-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-all shadow-md hover:shadow-emerald-100 active:scale-95">
                        <i class='bx bx-list-check text-lg'></i>
                        Budget Approved Requisitions
                    </button>
                    <!-- Pulse Notification Badge for Approved Requisitions -->
                    <div id="approvedReqBadgePulse" class="hidden absolute -top-2 -right-2 z-20">
                        <span class="relative flex h-5 w-5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                            <span id="approvedReqPulseCount" class="relative inline-flex rounded-full h-5 w-5 bg-emerald-500 text-[10px] font-bold text-white items-center justify-center border-2 border-white shadow-sm">0</span>
                        </span>
                    </div>
                </div>
                <button id="addPurchaseBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-all shadow-md hover:shadow-blue-100 active:scale-95">
                    <i class='bx bx-plus-circle text-lg'></i>
                    New Purchase Order
                </button>
            </div>
        </div>

        <!-- Filters and Search Section -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-3 bg-gray-50 p-4 rounded-xl border border-gray-100">
            <!-- Search -->
            <div class="md:col-span-2 relative">
                <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg'></i>
                <input type="text" id="searchInput" placeholder="Search by PO number, company, items..." class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all">
            </div>
            
            <!-- Vendor Type Filter -->
            <div class="relative">
                <i class='bx bx-buildings absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg'></i>
                <select id="vendorTypeFilter" class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none transition-all">
                    <option value="">Company / Type</option>
                    <option value="equipment">Equipment</option>
                    <option value="supplies">Supplies</option>
                    <option value="furniture">Furniture</option>
                    <option value="automotive">Automotive</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div class="relative">
                <i class='bx bx-filter-alt absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg'></i>
                <select id="statusFilter" class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none transition-all">
                    <option value="">Status</option>
                    <option value="Pending">Pending</option>
                    <option value="Approved">Approved</option>
                    <option value="Rejected">Rejected</option>
                    <option value="Cancel">Cancelled</option>
                    <option value="Vendor-Review">Vendor Review</option>
                    <option value="In-Progress">In Progress</option>
                    <option value="Completed">Completed</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Purchases Table -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-800 font-bold text-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap">Purchase Order No.</th>
                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap">Items</th>
                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap">Company / Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap">Units</th>
                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap">Delivery Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap">Ordered By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap">Approved By</th>
                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody id="purchasesTableBody" class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="10" class="px-6 py-4 text-center text-gray-500">
                            <div class="flex justify-center items-center py-4">
                                <div class="loading loading-spinner mr-3"></div>
                                Loading purchases...
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div id="purchasesPager" class="flex items-center justify-between mt-3">
        <div id="purchasesPagerInfo" class="text-sm text-gray-600"></div>
        <div class="join">
            <button class="btn btn-sm join-item" id="purchasesPrevBtn" data-action="prev">Prev</button>
            <span class="btn btn-sm join-item" id="purchasesPageDisplay">1 / 1</span>
            <button class="btn btn-sm join-item" id="purchasesNextBtn" data-action="next">Next</button>
        </div>
    </div>
</div>

<!-- Add/Edit Purchase Modal (supports dual-panel mode) -->
<div id="purchaseModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="w-full max-w-6xl">
        <div class="flex flex-col lg:flex-row gap-4">
            <!-- Consolidated Details Side Panel (hidden by default) -->
            <div id="consolidatedSidePanel" class="hidden bg-white rounded-lg p-6 w-full lg:w-1/2 max-h-[90vh] overflow-y-auto">
                <div class="flex items-center gap-2 mb-4">
                    <i class='bx bx-file text-blue-600 text-2xl'></i>
                    <h3 class="text-xl font-semibold">Consolidated Details</h3>
                </div>
                <div id="consolidatedDetailsContent" class="space-y-4">
                    <!-- Filled dynamically -->
                </div>
            </div>

            <!-- Purchase Form Panel -->
            <div id="purchaseFormPanel" class="bg-white rounded-lg p-6 w-full lg:w-1/2 max-h-[90vh] overflow-y-auto">
                <div class="flex justify-between items-center mb-4">
                    <h3 id="modalTitle" class="text-xl font-semibold">New Purchase Order</h3>
                    <button id="closeModal" class="text-gray-500 hover:text-gray-700">
                        <i class='bx bx-x text-2xl'></i>
                    </button>
                </div>
                
                <form id="purchaseForm">
                    <input type="hidden" id="purchaseId">
                    
                    <!-- Company Selection -->
                    <div class="mb-4">
                        <label for="pur_company_name" class="block text-sm font-medium text-gray-700 mb-1">Company Name *</label>
                        <select id="pur_company_name" name="pur_company_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Company</option>
                        </select>
                        <input type="hidden" id="pur_ven_type" name="pur_ven_type">
                    </div>
                    
                    <input type="hidden" id="pur_order_by" name="pur_order_by">
                    
                    <!-- Items Selection Section -->
                    <div id="itemsSection" class="mb-4 hidden">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Select Items *</label>
                        
                        <!-- Add Item Button -->
                        <button type="button" id="addItemBtn" class="mb-3 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors flex items-center gap-2">
                            <i class='bx bx-plus'></i>
                            Add Item
                        </button>
                        
                        <!-- Selected Items Container -->
                        <div id="selectedItemsContainer" class="space-y-3 border border-gray-200 rounded-lg p-4 min-h-20">
                            <p class="text-sm text-gray-500 text-center">No items selected yet</p>
                        </div>
                        
                        <!-- Auto-calculated fields -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Total Units</label>
                                <input type="text" id="pur_unit" name="pur_unit" readonly class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-50" value="0">
                            </div>
                            
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Total Amount (₱)</label>
                                <div class="flex items-center border border-gray-300 rounded-lg bg-gray-50">
                                    <span class="px-3 text-gray-700">₱</span>
                                    <input type="text" id="pur_total_amount" name="pur_total_amount" readonly class="w-full px-3 py-2 border-0 bg-transparent" value="0.00">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Description -->
                    <div class="mb-4">
                        <label for="pur_desc" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea id="pur_desc" name="pur_desc" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Optional purchase order description..."></textarea>
                    </div>
                    
                    <div class="flex justify-end gap-3">
                        <button type="button" id="cancelModal" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                        <button type="submit" id="savePurchaseBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">Save Purchase Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Add Item Modal -->
<div id="addItemModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-2xl max-h-[80vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Select Item</h3>
            <button id="closeAddItemModal" class="text-gray-500 hover:text-gray-700">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>
        
        <div class="mb-4">
            <input type="text" id="itemSearch" placeholder="Search items..." class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        
        <div id="availableItemsList" class="space-y-2 max-h-96 overflow-y-auto">
            <!-- Items will be populated here -->
        </div>
        
        <div class="flex justify-end gap-3 mt-4">
            <button type="button" id="cancelAddItemModal" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
        </div>
    </div>
</div>

<!-- View Purchase Modal -->
<div id="viewPurchaseModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-3xl max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Purchase Order Details</h3>
            <button id="closeViewPurchaseModal" class="text-gray-500 hover:text-gray-700">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>
        <div id="viewPurchaseContent" class="space-y-4"></div>
    </div>
</div>

<!-- Cancel Purchase Modal -->
<div id="cancelPurchaseModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Cancel Purchase Order</h3>
            <button id="closeCancelPurchaseModal" class="text-gray-500 hover:text-gray-700">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>
        <div id="cancelPurchaseContent" class="space-y-4 mb-4">
            <!-- Cancel purchase content will be populated here -->
        </div>
        <div class="flex justify-end gap-3">
            <button type="button" id="cancelCancelPurchaseBtn" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Close</button>
            <button type="button" id="confirmCancelPurchaseBtn" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">Cancel Purchase</button>
        </div>
    </div>
</div>

<!-- Approved Requisitions Modal -->
<div id="requisitionsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-5xl max-h-[90vh] flex flex-col">
        <div class="flex justify-between items-center mb-4">
            <div class="flex items-center gap-3">
                <i class='bx bx-list-check text-2xl text-emerald-600'></i>
                <h3 class="text-xl font-bold text-gray-800">Approved Purchase Requisitions</h3>
            </div>
            <button id="closeRequisitionsModal" class="text-gray-500 hover:text-gray-700 transition-colors">
                <i class='bx bx-x text-3xl'></i>
            </button>
        </div>
        
        <div class="flex-1 overflow-y-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap">Req ID</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap">Items</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap">Requester / Dept</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap">Chosen Vendor</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider text-right whitespace-nowrap">Total Amount</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider whitespace-nowrap">Date</th>
                        <th class="px-4 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider text-right whitespace-nowrap">Action</th>
                    </tr>
                </thead>
                <tbody id="requisitionsTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Requisitions will be populated here -->
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 flex justify-end">
            <button type="button" id="closeRequisitionsModalBtn" class="px-6 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors font-medium">Close</button>
        </div>
    </div>
</div>

<script>
// API Configuration
var API_BASE_URL = '<?php echo url('/api/v1'); ?>';
var PSM_PURCHASES_API = API_BASE_URL + '/psm/purchase-management';
var PSM_REQUISITIONS_API = API_BASE_URL + '/psm/requisitions';
var PSM_ACTIVE_VENDORS_API = API_BASE_URL + '/psm/active-vendors';
var CSRF_TOKEN = typeof CSRF_TOKEN !== 'undefined' ? CSRF_TOKEN : document.querySelector('meta[name="csrf-token"]').getAttribute('content');

// JWT Token Handling with LocalStorage Sync
var SERVER_JWT = '{{ $jwtToken ?? "" }}';

// Debug logging
console.log('PSM: Server JWT:', SERVER_JWT ? 'Present' : 'Empty');
console.log('PSM: Window JWT:', typeof window.SERVER_JWT_TOKEN !== 'undefined' ? (window.SERVER_JWT_TOKEN ? 'Present' : 'Empty') : 'Undefined');
console.log('PSM: LocalStorage JWT:', localStorage.getItem('jwt') ? 'Present' : 'Empty');

if (SERVER_JWT) {
    localStorage.setItem('jwt', SERVER_JWT);
    console.log('JWT updated from server');
} else if (typeof window.SERVER_JWT_TOKEN !== 'undefined' && window.SERVER_JWT_TOKEN) {
    // Fallback to window global from home.blade.php
    SERVER_JWT = window.SERVER_JWT_TOKEN;
    localStorage.setItem('jwt', SERVER_JWT);
    console.log('JWT recovered from window global');
}

var JWT_TOKEN = SERVER_JWT || localStorage.getItem('jwt');

if (!JWT_TOKEN) {
    console.error('JWT Token is missing! API requests will fail.');
    // Optional: Redirect to login or show error
}

var CURRENT_USER_NAME = '<?php echo e(auth()->check() ? trim((auth()->user()->firstname ?? '').' '.(auth()->user()->lastname ?? '')) : ''); ?>';
var CURRENT_USER_ROLE = '<?php echo e(auth()->check() ? (auth()->user()->roles ?? '') : ''); ?>';
var CURRENT_USER_LABEL = (function() {
    var name = (CURRENT_USER_NAME || '').trim();
    var role = (CURRENT_USER_ROLE || '').trim();
    if (name && role) return name + ' - ' + role;
    if (name) return name;
    if (role) return role;
    return '';
})();

var currentPurchases = typeof currentPurchases !== 'undefined' ? currentPurchases : [];
let currentPurchasesPage = 1;
const purchasesPageSize = 10;
var activeVendors = [];
var selectedVendor = null;
var selectedItems = [];
var availableItems = [];

if (typeof Toast === 'undefined') {
    window.Toast = Swal.mixin({ 
        toast: true, 
        position: 'top-end', 
        showConfirmButton: false, 
        timer: 3000, 
        timerProgressBar: true, 
        didOpen: (toast) => { 
            toast.onmouseenter = Swal.stopTimer; 
            toast.onmouseleave = Swal.resumeTimer; 
        } 
    });
}

function showNotification(message, type = 'info') { 
    if (typeof Toast !== 'undefined') {
        Toast.fire({ 
            icon: type === 'success' ? 'success' : type === 'error' ? 'error' : type === 'warning' ? 'warning' : 'info', 
            title: message 
        }); 
    } else {
        // Fallback if Toast is not yet initialized
        Swal.fire({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            icon: type === 'success' ? 'success' : type === 'error' ? 'error' : type === 'warning' ? 'warning' : 'info',
            title: message
        });
    }
}

// DOM Elements
const elements = {
    purchasesTableBody: document.getElementById('purchasesTableBody'),
    searchInput: document.getElementById('searchInput'),
    statusFilter: document.getElementById('statusFilter'),
    vendorTypeFilter: document.getElementById('vendorTypeFilter'),
    addPurchaseBtn: document.getElementById('addPurchaseBtn'),
    purchaseModal: document.getElementById('purchaseModal'),
    purchaseForm: document.getElementById('purchaseForm'),
    modalTitle: document.getElementById('modalTitle'),
    purchaseId: document.getElementById('purchaseId'),
    closeModal: document.getElementById('closeModal'),
    cancelModal: document.getElementById('cancelModal'),
    savePurchaseBtn: document.getElementById('savePurchaseBtn'),
    statsSection: document.getElementById('statsSection'),
    totalOrders: document.getElementById('totalOrders'),
    approvedOrders: document.getElementById('approvedOrders'),
    pendingOrders: document.getElementById('pendingOrders'),
    cancelledOrders: document.getElementById('cancelledOrders'),
    companySelect: document.getElementById('pur_company_name'),
    vendorType: document.getElementById('pur_ven_type'),
    purOrderBy: document.getElementById('pur_order_by'),
    itemsSection: document.getElementById('itemsSection'),
    addItemBtn: document.getElementById('addItemBtn'),
    selectedItemsContainer: document.getElementById('selectedItemsContainer'),
    purUnit: document.getElementById('pur_unit'),
    purTotalAmount: document.getElementById('pur_total_amount'),
    addItemModal: document.getElementById('addItemModal'),
    closeAddItemModal: document.getElementById('closeAddItemModal'),
    cancelAddItemModal: document.getElementById('cancelAddItemModal'),
    itemSearch: document.getElementById('itemSearch'),
    availableItemsList: document.getElementById('availableItemsList'),
    purDesc: document.getElementById('pur_desc'),
    cancelPurchaseModal: document.getElementById('cancelPurchaseModal'),
    closeCancelPurchaseModal: document.getElementById('closeCancelPurchaseModal'),
    cancelPurchaseContent: document.getElementById('cancelPurchaseContent'),
    cancelCancelPurchaseBtn: document.getElementById('cancelCancelPurchaseBtn'),
            confirmCancelPurchaseBtn: document.getElementById('confirmCancelPurchaseBtn'),
            requisitionsModal: document.getElementById('requisitionsModal'),
            closeRequisitionsModal: document.getElementById('closeRequisitionsModal'),
            closeRequisitionsModalBtn: document.getElementById('closeRequisitionsModalBtn'),
            openRequisitionsBtn: document.getElementById('openRequisitionsBtn'),
            requisitionsTableBody: document.getElementById('requisitionsTableBody')
        };

function getProductImageUrl(path) {
    if (!path) return '';
    if (path.startsWith('data:')) return path;
    if (path.startsWith('http')) return path;
    var filename = path.split(/[/\\]/).pop();
    return '/images/product-picture/' + filename;
}

function resolveChosenVendorName(v) {
    if (v === null || v === undefined || v === '') return 'Not chosen';
    if (typeof v === 'object') {
        if (v.company_name) return v.company_name;
        if (v.name) return v.name;
        if (v.id !== undefined && v.id !== null) {
            var foundObj = (activeVendors || []).find(function(av){ return av.id == v.id; });
            return foundObj ? foundObj.company_name : String(v.id);
        }
        return JSON.stringify(v);
    }
    var s = String(v).trim();
    if (/^\d+$/.test(s)) {
        var found = (activeVendors || []).find(function(av){ return av.id == s; });
        return found ? found.company_name : s;
    }
    return s;
}

async function ensureJwtToken() {
    if (JWT_TOKEN) return true;
    
    console.log('JWT Token missing, attempting to fetch from server...');
    try {
        const tokenUrl = API_BASE_URL.includes('://') ? API_BASE_URL.replace('/api/v1', '') + '/auth/token' : '/auth/token';
        const response = await fetch(tokenUrl, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN
            },
            credentials: 'include'
        });
        
        if (response.ok) {
            const data = await response.json();
            if (data.success && data.token) {
                JWT_TOKEN = data.token;
                localStorage.setItem('jwt', JWT_TOKEN);
                console.log('JWT Token recovered from server');
                return true;
            }
        }
        console.error('Failed to recover JWT token');
        return false;
    } catch (e) {
        console.error('Error fetching JWT token:', e);
        return false;
    }
}

async function initPurchaseManagement() {
    console.log('🚀 Purchase Management Initialized');
    console.log('API Base URL:', API_BASE_URL);
    console.log('Purchases API:', PSM_PURCHASES_API);
    
    await ensureJwtToken();
    
    initializeEventListeners();
    loadPurchases();
    loadActiveVendors();
    loadApprovedRequisitions();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPurchaseManagement);
} else {
    initPurchaseManagement();
}

function initializeEventListeners() {
    
    
    // Main modal
    if (elements.addPurchaseBtn) elements.addPurchaseBtn.addEventListener('click', openAddModal);
    if (elements.closeModal) elements.closeModal.addEventListener('click', closePurchaseModal);
    if (elements.cancelModal) elements.cancelModal.addEventListener('click', closePurchaseModal);
    if (elements.purchaseForm) elements.purchaseForm.addEventListener('submit', handlePurchaseSubmit);
    
    // Company selection
    if (elements.companySelect) elements.companySelect.addEventListener('change', handleCompanyChange);
    
    // Items management
    if (elements.addItemBtn) elements.addItemBtn.addEventListener('click', openAddItemModal);
    
    // Add item modal
    if (elements.closeAddItemModal) elements.closeAddItemModal.addEventListener('click', closeAddItemModal);
    if (elements.cancelAddItemModal) elements.cancelAddItemModal.addEventListener('click', closeAddItemModal);
    if (elements.itemSearch) elements.itemSearch.addEventListener('input', debounce(filterAvailableItems, 300));
    
    // Cancel purchase modal
    if (elements.closeCancelPurchaseModal) elements.closeCancelPurchaseModal.addEventListener('click', closeCancelPurchaseModal);
    if (elements.cancelCancelPurchaseBtn) elements.cancelCancelPurchaseBtn.addEventListener('click', closeCancelPurchaseModal);
    if (elements.confirmCancelPurchaseBtn) elements.confirmCancelPurchaseBtn.addEventListener('click', handleConfirmCancelPurchase);
    
    // Approved Requisitions modal
    if (elements.openRequisitionsBtn) elements.openRequisitionsBtn.addEventListener('click', openRequisitionsModal);
    if (elements.closeRequisitionsModal) elements.closeRequisitionsModal.addEventListener('click', closeRequisitionsModal);
    if (elements.closeRequisitionsModalBtn) elements.closeRequisitionsModalBtn.addEventListener('click', closeRequisitionsModal);
    
    // Close modals when clicking outside
    if (elements.purchaseModal) {
        elements.purchaseModal.addEventListener('click', function(e) {
            if (e.target === elements.purchaseModal) {
                closePurchaseModal();
            }
        });
    }
    
    if (elements.addItemModal) {
        elements.addItemModal.addEventListener('click', function(e) {
            if (e.target === elements.addItemModal) {
                closeAddItemModal();
            }
        });
    }
    
    if (elements.cancelPurchaseModal) {
        elements.cancelPurchaseModal.addEventListener('click', function(e) {
            if (e.target === elements.cancelPurchaseModal) {
                closeCancelPurchaseModal();
            }
        });
    }

    if (elements.requisitionsModal) {
        elements.requisitionsModal.addEventListener('click', function(e) {
            if (e.target === elements.requisitionsModal) {
                closeRequisitionsModal();
            }
        });
    }
    
    // View purchase modal
    const closeViewPurchaseModalBtn = document.getElementById('closeViewPurchaseModal');
    const viewPurchaseModal = document.getElementById('viewPurchaseModal');
    
    if (closeViewPurchaseModalBtn) {
        closeViewPurchaseModalBtn.addEventListener('click', closeViewPurchaseModal);
    }
    
    if (viewPurchaseModal) {
        viewPurchaseModal.addEventListener('click', function(e) {
            if (e.target === viewPurchaseModal) {
                closeViewPurchaseModal();
            }
        });
    }
}

async function loadActiveVendors() {
    try {
        const response = await fetch(PSM_ACTIVE_VENDORS_API, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? 'Bearer ' + JWT_TOKEN : ''
            },
            credentials: 'include'
        });
        
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || 'HTTP ' + response.status + ': ' + response.statusText);
        }
        
        const result = await response.json();
        
        if (result.success) {
            activeVendors = result.data || [];
            populateCompanySelect();
        } else {
            throw new Error(result.message || 'Failed to load active vendors');
        }
        
    } catch (error) {
        console.error('Error loading active vendors:', error);
        showNotification('Error loading vendors: ' + error.message, 'error');
    }
}

function populateCompanySelect() {
    if (!elements.companySelect) return;
    
    elements.companySelect.innerHTML = '<option value="">Select Company</option>';
    
    activeVendors.forEach(vendor => {
        const option = document.createElement('option');
        option.value = vendor.company_name;
        option.textContent = vendor.company_name + ' (' + vendor.type + ')';
        option.dataset.vendorType = vendor.type;
        option.dataset.vendorId = vendor.id;
        elements.companySelect.appendChild(option);
    });
}

function handleCompanyChange() {
    if (!elements.companySelect) return;
    
    const selectedOption = elements.companySelect.options[elements.companySelect.selectedIndex];
    
    if (selectedOption.value) {
        // Check if there are existing items and show confirmation
        if (selectedItems.length > 0 && selectedVendor && selectedVendor.id != selectedOption.dataset.vendorId) {
            showChangeCompanyConfirmation(selectedOption);
        } else {
            setSelectedVendor(selectedOption);
        }
    } else {
        // No company selected
        selectedVendor = null;
        if (elements.itemsSection) elements.itemsSection.classList.add('hidden');
        clearSelectedItems();
    }
}

function showChangeCompanyConfirmation(selectedOption) {
    Swal.fire({
        title: 'Change Company?',
        html: 'Your current added items from <strong>' + selectedVendor.company_name + '</strong> will be cleared if you change the company.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Yes, clear items',
        cancelButtonText: 'Keep current company',
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            // User confirmed - clear items and change company
            clearSelectedItems();
            setSelectedVendor(selectedOption);
        } else {
            // User cancelled - revert to previous selection
            if (elements.companySelect && selectedVendor) {
                elements.companySelect.value = selectedVendor.company_name;
            }
        }
    });
}

function setSelectedVendor(selectedOption) {
    const vendorId = selectedOption.dataset.vendorId;
    selectedVendor = activeVendors.find(v => v.id == vendorId);
    
    if (selectedVendor) {
        if (elements.vendorType) elements.vendorType.value = selectedVendor.type;
        if (elements.itemsSection) elements.itemsSection.classList.remove('hidden');
        availableItems = selectedVendor.products || [];
    } else {
        if (elements.itemsSection) elements.itemsSection.classList.add('hidden');
        clearSelectedItems();
    }
}

function openAddItemModal() {
    if (!selectedVendor) {
        showNotification('Please select a company first', 'warning');
        return;
    }
    
    populateAvailableItems();
    if (elements.addItemModal) elements.addItemModal.classList.remove('hidden');
}

function closeAddItemModal() {
    if (elements.addItemModal) elements.addItemModal.classList.add('hidden');
    if (elements.itemSearch) elements.itemSearch.value = '';
}

function populateAvailableItems() {
    if (!elements.availableItemsList) return;
    
    elements.availableItemsList.innerHTML = '';
    
    if (availableItems.length === 0) {
        elements.availableItemsList.innerHTML = '<p class="text-center text-gray-500 py-4">No products available for this vendor</p>';
        return;
    }
    
    availableItems.forEach(item => {
        const itemElement = document.createElement('div');
        itemElement.className = 'p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors';
        const imageUrl = item.picture 
            ? getProductImageUrl(item.picture)
            : 'https://placehold.co/100x100?text=No+Image';
        
        itemElement.innerHTML = 
            '<div class="flex justify-between items-center">' +
                '<div class="flex items-center gap-3">' +
                    '<img src="' + imageUrl + '" alt="' + item.name + '" class="w-12 h-12 object-cover rounded-md bg-gray-100" onerror="this.src=\'https://placehold.co/100x100?text=No+Image\'">' +
                    '<div>' +
                        '<h4 class="font-medium text-gray-900">' + item.name + '</h4>' +
                        '<p class="text-sm text-gray-600">Price: ' + formatCurrency(item.price) + ' | Stock: ' + item.stock + ' | Warranty: ' + (item.warranty || 'N/A') + ' | Expiration: ' + (item.expiration ? formatDate(item.expiration) : 'N/A') + '</p>' +
                    '</div>' +
                '</div>' +
                '<div class="flex items-center gap-2">' +
                    '<button type="button" onclick="addItemToPurchase(' + item.id + ')" ' +
                            'class="px-3 py-1 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition-colors">' +
                        'Add' +
                    '</button>' +
                '</div>' +
            '</div>';
        elements.availableItemsList.appendChild(itemElement);
    });
}

function filterAvailableItems() {
    if (!elements.itemSearch || !elements.availableItemsList) return;
    
    const searchTerm = elements.itemSearch.value.toLowerCase();
    const filteredItems = availableItems.filter(item => 
        item.name.toLowerCase().includes(searchTerm)
    );
    
    elements.availableItemsList.innerHTML = '';
    
    if (filteredItems.length === 0) {
        elements.availableItemsList.innerHTML = '<p class="text-center text-gray-500 py-4">No products found</p>';
        return;
    }
    
    filteredItems.forEach(item => {
        const itemElement = document.createElement('div');
        itemElement.className = 'p-3 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors';
        const imageUrl = item.picture 
            ? getProductImageUrl(item.picture)
            : 'https://placehold.co/100x100?text=No+Image';
        
        itemElement.innerHTML = 
            '<div class="flex justify-between items-center">' +
                '<div class="flex items-center gap-3">' +
                    '<img src="' + imageUrl + '" alt="' + item.name + '" class="w-12 h-12 object-cover rounded-md bg-gray-100" onerror="this.src=\'https://placehold.co/100x100?text=No+Image\'">' +
                    '<div>' +
                        '<h4 class="font-medium text-gray-900">' + item.name + '</h4>' +
                        '<p class="text-sm text-gray-600">Price: ' + formatCurrency(item.price) + ' | Stock: ' + item.stock + ' | Warranty: ' + (item.warranty || 'N/A') + ' | Expiration: ' + (item.expiration ? formatDate(item.expiration) : 'N/A') + '</p>' +
                    '</div>' +
                '</div>' +
                '<div class="flex items-center gap-2">' +
                    '<button type="button" onclick="addItemToPurchase(' + item.id + ')" ' +
                            'class="px-3 py-1 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition-colors">' +
                        'Add' +
                    '</button>' +
                '</div>' +
            '</div>';
        elements.availableItemsList.appendChild(itemElement);
    });
}

function addItemToPurchase(itemId) {
    const item = availableItems.find(i => i.id == itemId);
    
    if (!item) return;
    
    // Generate unique ID for this specific item instance
    const itemInstanceId = Date.now() + Math.random();
    
    // Add item to selected items
    selectedItems.push({
        id: itemInstanceId,
        itemId: item.id,
        name: item.name,
        price: item.price,
        picture: item.picture,
        warranty: item.warranty,
        expiration: item.expiration
    });
    
    showNotification('Added ' + item.name + ' to purchase order', 'success');
    updateSelectedItemsDisplay();
    closeAddItemModal();
}

function removeSelectedItem(itemInstanceId) {
    selectedItems = selectedItems.filter(item => item.id != itemInstanceId);
    updateSelectedItemsDisplay();
    showNotification('Item removed from purchase order', 'info');
}

function updateSelectedItemsDisplay() {
    if (!elements.selectedItemsContainer) return;
    
    if (selectedItems.length === 0) {
        elements.selectedItemsContainer.innerHTML = '<p class="text-sm text-gray-500 text-center">No items selected yet</p>';
    } else {
        // Group items by itemId to show quantity
        const itemCounts = {};
        selectedItems.forEach(item => {
            if (!itemCounts[item.itemId]) {
                itemCounts[item.itemId] = {
                    name: item.name,
                    price: item.price,
                    picture: item.picture,
                    count: 0,
                    instances: []
                };
            }
            itemCounts[item.itemId].count++;
            itemCounts[item.itemId].instances.push(item.id);
        });
        
        elements.selectedItemsContainer.innerHTML = Object.values(itemCounts).map(group => {
            const imageUrl = group.picture 
                ? getProductImageUrl(group.picture)
                : 'https://placehold.co/100x100?text=No+Image';
            return '<div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-200">' +
                '<div class="flex items-center gap-3">' +
                    '<img src="' + imageUrl + '" alt="' + group.name + '" class="w-12 h-12 object-cover rounded-md bg-white border border-gray-200" onerror="this.src=\'https://placehold.co/100x100?text=No+Image\'">' +
                    '<div>' +
                        '<h4 class="font-medium text-gray-900">' + group.name + '</h4>' +
                        '<p class="text-sm text-gray-600">' +
                            formatCurrency(group.price) + ' each × ' + group.count + ' = ' + formatCurrency(group.price * group.count) +
                        '</p>' +
                    '</div>' +
                '</div>' +
                '<div class="flex items-center gap-2">' +
                    '<span class="text-sm text-gray-600 bg-white px-2 py-1 rounded border">Qty: ' + group.count + '</span>' +
                    '<button type="button" onclick="removeItemGroup([' + group.instances.join(',') + '])" ' +
                            'class="px-3 py-1 bg-red-600 text-white rounded-lg text-sm hover:bg-red-700 transition-colors">' +
                        'Remove All' +
                    '</button>' +
                    '<button type="button" onclick="removeSingleItem(' + group.instances[0] + ')" ' +
                            'class="px-3 py-1 bg-yellow-600 text-white rounded-lg text-sm hover:bg-yellow-700 transition-colors">' +
                        'Remove One' +
                    '</button>' +
                '</div>' +
            '</div>';
        }).join('');
    }
    
    // Update calculated fields
    updateCalculatedFields();
}

function removeItemGroup(itemInstanceIds) {
    selectedItems = selectedItems.filter(item => !itemInstanceIds.includes(item.id));
    updateSelectedItemsDisplay();
    showNotification('Items removed from purchase order', 'info');
}

function removeSingleItem(itemInstanceId) {
    selectedItems = selectedItems.filter(item => item.id != itemInstanceId);
    updateSelectedItemsDisplay();
    showNotification('One item removed from purchase order', 'info');
}

function updateCalculatedFields() {
    if (!elements.purUnit || !elements.purTotalAmount) return;
    
    const totalUnits = selectedItems.length;
    const totalAmount = selectedItems.reduce((sum, item) => sum + parseFloat(item.price), 0);
    
    elements.purUnit.value = totalUnits;
    elements.purTotalAmount.value = totalAmount.toFixed(2);
}

function clearSelectedItems() {
    selectedItems = [];
    updateSelectedItemsDisplay();
}

async function loadPurchases() {
    showLoading();
    
    const params = new URLSearchParams();
    
    try {
        const purchasesUrl = PSM_PURCHASES_API;
        params.append('t', new Date().getTime());
        console.log('📡 Fetching purchases from:', purchasesUrl + '?' + params);
        
        const response = await fetch(purchasesUrl + '?' + params.toString(), {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? 'Bearer ' + JWT_TOKEN : ''
            },
            credentials: 'include'
        });
        
        console.log('📨 Response status:', response.status);
        
        if (!response.ok) {
            throw new Error('HTTP ' + response.status + ': ' + response.statusText);
        }
        
        const result = await response.json();
        console.log('📊 Purchases response:', result);
        
        if (result.success) {
            currentPurchases = result.data || [];
            displayPurchases(currentPurchases);
            loadStats();
        } else {
            throw new Error(result.message || 'Failed to load purchases');
        }
        
    } catch (error) {
        console.error('❌ Error loading purchases:', error);
        showNotification('Error loading purchases: ' + error.message, 'error');
        displayPurchases([]);
    } finally {
        hideLoading();
    }
}

function loadStats() {
    if (!currentPurchases) return;
    
    const stats = {
        total: currentPurchases.length,
        approved: currentPurchases.filter(p => p.pur_status === 'Approved').length,
        pending: currentPurchases.filter(p => p.pur_status === 'Pending').length,
        cancelled: currentPurchases.filter(p => p.pur_status === 'Cancel').length
    };
    
    if (elements.totalOrders) elements.totalOrders.textContent = stats.total;
    if (elements.approvedOrders) elements.approvedOrders.textContent = stats.approved;
    if (elements.pendingOrders) elements.pendingOrders.textContent = stats.pending;
    if (elements.cancelledOrders) elements.cancelledOrders.textContent = stats.cancelled;
    
    // Toggle pulse notification for pending
    const pulse = document.getElementById('pendingPulse');
    if (pulse) {
        pulse.style.display = stats.pending > 0 ? 'flex' : 'none';
    }
}

function displayPurchases(purchases) {
    if (!elements.purchasesTableBody) return;
    const filtered = getPurchasesFiltered(purchases);
    const total = filtered.length;
    if (!filtered || total === 0) {
        elements.purchasesTableBody.innerHTML = 
            '<tr>' +
                '<td colspan="10" class="px-6 py-8 text-center text-gray-500">' +
                    '<i class=\'bx bxs-purchase-tag text-4xl text-gray-300 mb-3\'></i>' +
                    '<p class="text-lg">No purchase orders found</p>' +
                '</td>' +
            '</tr>';
        renderPurchasesPager(0, 1);
        return;
    }
    const totalPages = Math.max(1, Math.ceil(total / purchasesPageSize));
    if (currentPurchasesPage > totalPages) currentPurchasesPage = totalPages;
    if (currentPurchasesPage < 1) currentPurchasesPage = 1;
    const startIdx = (currentPurchasesPage - 1) * purchasesPageSize;
    const pageItems = filtered.slice(startIdx, startIdx + purchasesPageSize);
    const purchasesHtml = pageItems.map(purchase => {
        const items = Array.isArray(purchase.pur_name_items) ? purchase.pur_name_items : [];
        const itemsString = items.slice(0, 3).map(item => 
            typeof item === 'object' ? item.name : item
        ).join(', ') + (items.length > 3 ? '...' : '');
        
        // Determine which action buttons to show based on status
        const canCancel = purchase.pur_status === 'Pending';
        const canApprove = purchase.pur_status === 'Pending';
        const isApproved = purchase.pur_status === 'Approved';
        const isCompleted = purchase.pur_status === 'Completed';
        const isInProgress = purchase.pur_status === 'In-Progress';
        const isCancelled = purchase.pur_status === 'Cancel';
        const rowClass = isCompleted ? 'bg-gray-200' : 'hover:bg-gray-50 transition-colors';
        
        const orderedByHtml = formatUserLabelDisplay(purchase.pur_order_by, 'Not specified');
        const approvedSource = purchase.pur_status === 'Cancel'
            ? (purchase.pur_cancel_by || purchase.pur_approved_by || 'Unknown Approver')
            : (purchase.pur_approved_by || 'Not approved');
        const approvedByHtml = formatUserLabelDisplay(approvedSource, purchase.pur_status === 'Cancel' ? 'Unknown Approver' : 'Not approved');
        
        const companyTypeHtml = 
            '<div class="flex flex-col leading-tight">' +
                '<span class="text-sm font-semibold text-gray-900">' + (purchase.pur_company_name || 'N/A') + '</span>' +
                '<span class="text-xs text-gray-500 capitalize">' + purchase.pur_ven_type + '</span>' +
            '</div>';
        
        const currencyFormatted = formatCurrency(purchase.pur_total_amount);
        const deliveryDateFormatted = formatDate(purchase.pur_delivery_date);
        const statusBadgeClass = getStatusBadgeClass(purchase.pur_status);
        const statusIcon = getStatusIcon(purchase.pur_status);

        let actionButtons = 
            '<button onclick="viewPurchase(' + purchase.id + ')" class="text-gray-700 hover:text-gray-900 transition-colors p-2 rounded-lg hover:bg-gray-50" title="View Purchase">' +
                '<i class=\'bx bx-show-alt text-xl\'></i>' +
            '</button>';

        if (!(isApproved || isCompleted || isCancelled || isInProgress)) {
            actionButtons += 
                '<button onclick="editPurchase(' + purchase.id + ')" class="text-blue-600 hover:text-blue-900 transition-colors p-2 rounded-lg hover:bg-blue-50" title="Edit Purchase">' +
                    '<i class=\'bx bx-edit-alt text-xl\'></i>' +
                '</button>';
        }

        if (!isCompleted && canCancel) {
            actionButtons += 
                '<button onclick="cancelPurchase(' + purchase.id + ')" class="text-red-600 hover:text-red-900 transition-colors p-2 rounded-lg hover:bg-red-50 cancel-purchase-btn" title="Cancel Purchase">' +
                    '<i class=\'bx bx-x-circle text-xl\'></i>' +
                '</button>';
        }

        actionButtons += 
            '<button onclick="deletePurchase(' + purchase.id + ')" class="text-red-600 hover:text-red-900 transition-colors p-2 rounded-lg hover:bg-red-50" title="Delete Purchase">' +
                '<i class=\'bx bx-trash text-xl\'></i>' +
            '</button>';

        return '<tr class="' + rowClass + '" data-purchase-id="' + purchase.id + '">' +
            '<td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">' + purchase.pur_id + '</td>' +
            '<td class="px-6 py-4 text-sm text-gray-900">' +
                '<div class="max-w-xs truncate" title="' + items.map(item => typeof item === 'object' ? item.name : item).join(', ') + '">' +
                    itemsString +
                '</div>' +
                '<small class="text-xs text-gray-500">' + items.length + ' item(s)</small>' +
            '</td>' +
            '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">' + companyTypeHtml + '</td>' +
            '<td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">' + purchase.pur_unit + '</td>' +
            '<td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">' + currencyFormatted + '</td>' +
            '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">' + deliveryDateFormatted + '</td>' +
            '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">' + orderedByHtml + '</td>' +
            '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">' + approvedByHtml + '</td>' +
            '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">' +
                '<span class="' + statusBadgeClass + '">' +
                    statusIcon + ' ' + purchase.pur_status +
                '</span>' +
            '</td>' +
            '<td class="px-6 py-4 whitespace-nowrap text-sm font-medium">' +
                '<div class="flex items-center space-x-2">' + actionButtons + '</div>' +
            '</td>' +
        '</tr>';
    }).join('');
    
    elements.purchasesTableBody.innerHTML = purchasesHtml;
    renderPurchasesPager(total, totalPages);
}

function renderPurchasesPager(total, totalPages){
    const info = document.getElementById('purchasesPagerInfo');
    const display = document.getElementById('purchasesPageDisplay');
    const start = total === 0 ? 0 : ((currentPurchasesPage - 1) * purchasesPageSize) + 1;
    const end = Math.min(currentPurchasesPage * purchasesPageSize, total);
    if (info) info.textContent = 'Showing ' + start + '-' + end + ' of ' + total;
    if (display) display.textContent = currentPurchasesPage + ' / ' + totalPages;
    const prev = document.getElementById('purchasesPrevBtn');
    const next = document.getElementById('purchasesNextBtn');
    if (prev) prev.disabled = currentPurchasesPage <= 1;
    if (next) next.disabled = currentPurchasesPage >= totalPages;
}

function getPurchasesFiltered(list){
    let purchases = (list || []).slice();
    const statusVal = (elements.statusFilter?.value || '').trim().toLowerCase();
    const typeVal = (elements.vendorTypeFilter?.value || '').trim().toLowerCase();
    const q = (elements.searchInput?.value || '').trim().toLowerCase();
    if (statusVal) purchases = purchases.filter(p => (p.pur_status || '').toLowerCase() === statusVal);
    if (typeVal) purchases = purchases.filter(p => (p.pur_ven_type || '').toLowerCase() === typeVal);
    if (q) {
        purchases = purchases.filter(p => {
            const items = Array.isArray(p.pur_name_items) ? p.pur_name_items.map(i => typeof i === 'object' ? (i.name || '') : (i || '')).join(' ') : '';
            const hay = [
                p.pur_id || '',
                p.pur_company_name || '',
                items,
                p.pur_order_by || '',
                p.pur_approved_by || ''
            ].join(' ').toLowerCase();
            return hay.includes(q);
        });
    }
    return purchases;
}

document.getElementById('purchasesPager').addEventListener('click', function(ev){
    const btn = ev.target.closest('button[data-action]');
    if(!btn) return;
    const act = btn.getAttribute('data-action');
    if(act === 'prev'){ currentPurchasesPage = Math.max(1, currentPurchasesPage - 1); displayPurchases(currentPurchases); }
    if(act === 'next'){ const max = Math.max(1, Math.ceil((getPurchasesFiltered(currentPurchases).length||0)/purchasesPageSize)); currentPurchasesPage = Math.min(max, currentPurchasesPage + 1); displayPurchases(currentPurchases); }
});

if (elements.searchInput) elements.searchInput.addEventListener('input', function(){ currentPurchasesPage = 1; displayPurchases(currentPurchases); });
if (elements.statusFilter) elements.statusFilter.addEventListener('change', function(){ currentPurchasesPage = 1; displayPurchases(currentPurchases); });
if (elements.vendorTypeFilter) elements.vendorTypeFilter.addEventListener('change', function(){ currentPurchasesPage = 1; displayPurchases(currentPurchases); });



function getStatusBadgeClass(status) {
    const statusClasses = {
        'Approved': 'bg-green-700 text-white shadow-sm border border-green-800',
        'Pending': 'bg-yellow-600 text-white shadow-sm border border-yellow-700',
        'Rejected': 'bg-red-700 text-white shadow-sm border border-red-700',
        'Cancel': 'bg-red-700 text-white shadow-sm border border-red-700',
        'Vendor-Review': 'bg-purple-700 text-white shadow-sm border border-purple-800',
        'In-Progress': 'bg-indigo-700 text-white shadow-sm border border-indigo-800',
        'Completed': 'bg-emerald-700 text-white shadow-sm border border-emerald-800'
    };
    return (statusClasses[status] || 'bg-gray-600 text-white border border-gray-700') + ' px-3 py-1.5 rounded-full text-[11px] font-bold flex items-center gap-1.5 w-fit';
}

function getStatusIcon(status) {
    const statusIcons = {
        'Pending': "<i class='bx bx-time-five'></i>",
        'Approved': "<i class='bx bx-check-circle'></i>",
        'Rejected': "<i class='bx bx-x-circle'></i>",
        'Cancel': "<i class='bx bx-x-circle'></i>",
        'Vendor-Review': "<i class='bx bx-user-voice'></i>",
        'In-Progress': "<i class='bx bx-cog'></i>",
        'Completed': "<i class='bx bx-check-double'></i>"
    };
    return statusIcons[status] || "<i class='bx bx-help-circle'></i>";
}

function formatUserLabelDisplay(label, fallback) {
    const raw = (label || '').trim();
    if (!raw) {
        return '<span class="text-sm text-gray-500">' + fallback + '</span>';
    }
    
    const parts = raw.split('-');
    const name = parts[0].trim();
    const roleRaw = parts.slice(1).join('-').trim();
    
    if (!roleRaw) {
        return '<span class="text-sm font-semibold text-gray-900">' + name + '</span>';
    }
    
    const role = roleRaw.toLowerCase().split(' ').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ');
    
    return '<div class="flex flex-col leading-tight">' +
            '<span class="text-sm font-semibold text-gray-900">' + name + '</span>' +
            '<span class="text-xs text-gray-500">' + role + '</span>' +
        '</div>';
}

function openRequisitionsModal() {
    if (elements.requisitionsModal) {
        elements.requisitionsModal.classList.remove('hidden');
        loadApprovedRequisitions();
    }
}

function closeRequisitionsModal() {
    if (elements.requisitionsModal) {
        elements.requisitionsModal.classList.add('hidden');
    }
}

async function loadApprovedRequisitions() {
    if (!elements.requisitionsTableBody) return;
    
    elements.requisitionsTableBody.innerHTML = 
        '<tr>' +
            '<td colspan="6" class="px-6 py-8 text-center text-gray-500">' +
                '<div class="flex justify-center items-center py-4">' +
                    '<div class="loading loading-spinner mr-3"></div>' +
                    'Loading approved requisitions...' +
                '</div>' +
            '</td>' +
        '</tr>';
    
    try {
        // Fetch approved consolidated requests instead of individual requisitions
        const response = await fetch(PSM_REQUISITIONS_API + '?approved_consolidated=1', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? 'Bearer ' + JWT_TOKEN : ''
            },
            credentials: 'include'
        });
        
        if (!response.ok) throw new Error('HTTP ' + response.status);
        
        const result = await response.json();
        
        if (result.success) {
            displayApprovedRequisitions(result.data || []);
            updateRequisitionBadge(result.data ? result.data.length : 0);
        } else {
            throw new Error(result.message || 'Failed to load approved consolidated requests');
        }
    } catch (error) {
        console.error('Error loading approved consolidated requests:', error);
        elements.requisitionsTableBody.innerHTML = 
            '<tr>' +
                '<td colspan="5" class="px-6 py-8 text-center text-red-500">' +
                    '<i class=\'bx bx-error-circle text-4xl mb-2\'></i>' +
                    '<p>Error loading budget approved requests: ' + error.message + '</p>' +
                '</td>' +
            '</tr>';
    }
}

function updateRequisitionBadge(count) {
    const badge = document.getElementById('approvedReqBadgePulse');
    const countDisplay = document.getElementById('approvedReqPulseCount');
    if (badge && countDisplay) {
        if (count > 0) {
            countDisplay.textContent = count;
            badge.classList.remove('hidden');
        } else {
            badge.classList.add('hidden');
        }
    }
}

function displayApprovedRequisitions(consolidatedRequests) {
    if (!elements.requisitionsTableBody) return;
    
    if (consolidatedRequests.length === 0) {
        elements.requisitionsTableBody.innerHTML = 
            '<tr>' +
                '<td colspan="5" class="px-6 py-8 text-center text-gray-500">' +
                    '<i class=\'bx bx-list-check text-4xl text-gray-300 mb-2\'></i>' +
                    '<p>No budget approved requests found</p>' +
                '</td>' +
            '</tr>';
        return;
    }
    
    elements.requisitionsTableBody.innerHTML = consolidatedRequests.map(req => {
        const items = Array.isArray(req.con_items) ? req.con_items : (typeof req.con_items === 'string' ? JSON.parse(req.con_items) : []);
        const itemsString = items.slice(0, 2).map(item => 
            typeof item === 'object' ? item.name : item
        ).join(', ') + (items.length > 2 ? '...' : '');
        
        return '<tr class="hover:bg-gray-50 transition-colors">' +
                '<td class="px-4 py-4 whitespace-nowrap text-sm font-bold text-gray-900">' +
                    (req.con_req_id || 'CON-' + req.id) +
                '</td>' +
                '<td class="px-4 py-4 text-sm text-gray-700">' +
                    '<div class="max-w-xs truncate" title="' + items.map(i => typeof i === 'object' ? i.name : i).join(', ') + '">' +
                        itemsString +
                    '</div>' +
                    '<small class="text-xs text-gray-500">' + items.length + ' item(s)</small>' +
                '</td>' +
                '<td class="px-4 py-4 whitespace-nowrap">' +
                    '<div class="text-sm font-bold text-gray-800">' + (req.con_requester || 'Unknown') + '</div>' +
                    '<div class="text-xs text-gray-500">' + (req.con_dept || 'N/A') + '</div>' +
                '</td>' +
                '<td class="px-4 py-4 whitespace-nowrap">' +
                    '<div class="text-sm font-semibold text-gray-700">' + resolveChosenVendorName(req.con_chosen_vendor) + '</div>' +
                '</td>' +
                '<td class="px-4 py-4 whitespace-nowrap text-right text-sm font-bold text-green-600">' +
                    formatCurrency(req.con_total_price || 0) +
                '</td>' +
                '<td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">' +
                    formatDate(req.con_date || req.created_at) +
                '</td>' +
                '<td class="px-4 py-4 whitespace-nowrap text-sm text-right">' +
                    '<div class="flex justify-end gap-2">' +
                        '<button onclick="viewConsolidatedInModal(' + req.id + ')" ' +
                                'class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" ' +
                                'title="View Details">' +
                            '<i class=\'bx bx-show-alt text-xl\'></i>' +
                        '</button>' +
                        '<button onclick="convertConsolidatedToPOInModal(\'' + req.id + '\')" ' +
                                'class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-colors" ' +
                                'title="Convert to PO">' +
                            '<i class=\'bx bx-transfer-alt text-xl\'></i>' +
                        '</button>' +
                    '</div>' +
                '</td>' +
            '</tr>';
    }).join('');
}

window.viewConsolidatedInModal = async function(id) {
    try {
        showLoading();
        const response = await fetch(PSM_REQUISITIONS_API + '?approved_consolidated=1', {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? 'Bearer ' + JWT_TOKEN : ''
            },
            credentials: 'include'
        });
        
        if (!response.ok) throw new Error('HTTP ' + response.status);
        
        const result = await response.json();
        if (result.success && result.data) {
            const req = result.data.find(r => r.id == id);
            if (!req) throw new Error('Request not found');

            const items = Array.isArray(req.con_items) ? req.con_items : (typeof req.con_items === 'string' ? JSON.parse(req.con_items) : []);
            
            const itemsHtml = items.map(item => 
                '<div class="flex items-center gap-2 p-2 bg-gray-50 rounded border border-gray-100 text-left">' +
                    '<i class=\'bx bx-check text-green-500\'></i>' +
                    '<span class="text-sm">' + (typeof item === 'object' ? item.name : item) + '</span>' +
                '</div>'
            ).join('');

            Swal.fire({
                title: '<div class="flex items-center gap-2 justify-center"><i class=\'bx bx-file text-blue-600\'></i> Consolidated Details</div>',
                html: '<div class="space-y-4 text-left">' +
                        '<div class="grid grid-cols-2 gap-4 bg-blue-50 p-4 rounded-lg">' +
                            '<div>' +
                                '<p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Consolidated ID</p>' +
                                '<p class="text-sm font-bold text-blue-600">' + (req.con_req_id || 'CON-' + req.id) + '</p>' +
                            '</div>' +
                            '<div>' +
                                '<p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Date</p>' +
                                '<p class="text-sm font-bold text-gray-700">' + formatDate(req.con_date) + '</p>' +
                            '</div>' +
                            '<div>' +
                                '<p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Requester</p>' +
                                '<p class="text-sm font-bold text-gray-700">' + (req.con_requester || 'Unknown') + '</p>' +
                            '</div>' +
                            '<div>' +
                                '<p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Department</p>' +
                                '<p class="text-sm font-bold text-gray-700">' + (req.con_dept || 'N/A') + '</p>' +
                            '</div>' +
                            '<div>' +
                                '<p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Chosen Vendor</p>' +
                                '<p class="text-sm font-bold text-blue-600">' + resolveChosenVendorName(req.con_chosen_vendor) + '</p>' +
                            '</div>' +
                            '<div>' +
                                '<p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Total Amount</p>' +
                                '<p class="text-sm font-bold text-green-600">' + formatCurrency(req.con_total_price || 0) + '</p>' +
                            '</div>' +
                        '</div>' +
                        '<div>' +
                            '<p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider mb-2">Items</p>' +
                            '<div class="space-y-2 max-h-48 overflow-y-auto pr-2">' +
                                itemsHtml +
                            '</div>' +
                        '</div>' +
                        (req.con_note ? 
                        '<div>' +
                            '<p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider mb-1">Notes</p>' +
                            '<p class="text-sm text-gray-600 italic bg-gray-50 p-3 rounded-lg border-l-4 border-gray-200">' + req.con_note + '</p>' +
                        '</div>' : '') +
                    '</div>',
                width: '600px',
                showCloseButton: true,
                showConfirmButton: false,
                customClass: {
                    container: 'z-[70]'
                }
            });
        }
    } catch (error) {
        console.error('Error viewing details:', error);
        Swal.fire('Error', 'Failed to load details: ' + error.message, 'error');
    } finally {
        hideLoading();
    }
}

function showConsolidatedPanelData(req) {
    var panel = document.getElementById('consolidatedSidePanel');
    var content = document.getElementById('consolidatedDetailsContent');
    if (!panel || !content) return;
    
    const items = Array.isArray(req.con_items) ? req.con_items : (typeof req.con_items === 'string' ? JSON.parse(req.con_items) : []);
    const itemsHtml = items.map(item => 
        '<div class="flex items-center gap-2 p-2 bg-gray-50 rounded border border-gray-100 text-left">' +
            '<i class="bx bx-check text-green-500"></i>' +
            '<span class="text-sm">' + (typeof item === 'object' ? (item.name || '') : item) + '</span>' +
        '</div>'
    ).join('');
    
    content.innerHTML = 
        '<div class="grid grid-cols-2 gap-4 bg-blue-50 p-4 rounded-lg">' +
            '<div>' +
                '<p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Consolidated ID</p>' +
                '<p class="text-sm font-bold text-blue-600">' + (req.con_req_id || ('CON-' + req.id)) + '</p>' +
            '</div>' +
            '<div>' +
                '<p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Date</p>' +
                '<p class="text-sm font-bold text-gray-700">' + formatDate(req.con_date || req.created_at) + '</p>' +
            '</div>' +
            '<div>' +
                '<p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Requester</p>' +
                '<p class="text-sm font-bold text-gray-700">' + (req.con_requester || 'Unknown') + '</p>' +
            '</div>' +
            '<div>' +
                '<p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Department</p>' +
                '<p class="text-sm font-bold text-gray-700">' + (req.con_dept || 'N/A') + '</p>' +
            '</div>' +
            '<div>' +
                '<p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Chosen Vendor</p>' +
                '<p class="text-sm font-bold text-blue-600">' + resolveChosenVendorName(req.con_chosen_vendor) + '</p>' +
            '</div>' +
            '<div>' +
                '<p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Total Amount</p>' +
                '<p class="text-sm font-bold text-green-600">' + formatCurrency(req.con_total_price || 0) + '</p>' +
            '</div>' +
        '</div>' +
        '<div>' +
            '<p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider mb-2">Items</p>' +
            '<div class="space-y-2 max-h-48 overflow-y-auto pr-2">' +
                itemsHtml +
            '</div>' +
        '</div>' +
        (req.con_note ? (
            '<div>' +
                '<p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider mb-1">Notes</p>' +
                '<p class="text-sm text-gray-600 italic bg-gray-50 p-3 rounded-lg border-l-4 border-gray-200">' + req.con_note + '</p>' +
            '</div>'
        ) : '');
    
    panel.classList.remove('hidden');
}

function hideConsolidatedPanel() {
    var panel = document.getElementById('consolidatedSidePanel');
    var content = document.getElementById('consolidatedDetailsContent');
    if (panel) panel.classList.add('hidden');
    if (content) content.innerHTML = '';
}

window.convertConsolidatedToPOInModal = async function(id) {
    try {
        const result = await Swal.fire({
            title: 'Convert to PO?',
            text: 'This will start creating a new Purchase Order from this consolidated request.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#10b981',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, convert it!'
        });

        if (result.isConfirmed) {
            closeRequisitionsModal();
            openAddModal();
            const response = await fetch(PSM_REQUISITIONS_API + '?approved_consolidated=1', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Authorization': JWT_TOKEN ? 'Bearer ' + JWT_TOKEN : ''
                },
                credentials: 'include'
            });
            
            const res = await response.json();
            if (res.success && res.data) {
                const req = res.data.find(r => r.id == id);
                if (req) {
                    if (elements.purDesc) {
                        elements.purDesc.value = 'Converted from Consolidated Request: ' + (req.con_req_id || 'CON-' + req.id) + '. Original Department: ' + (req.con_dept || 'N/A');
                    }
                    // Do not auto-populate items or vendor; show side panel for reference
                    hideConsolidatedPanel();
                    showConsolidatedPanelData(req);
                    showNotification('Review consolidated details on the left and create the PO manually.', 'info');
                }
            }
        }
    } catch (error) {
        console.error('Error converting to PO:', error);
        Swal.fire('Error', 'Failed to convert to PO', 'error');
    }
}

// Global scope helper to convert req from modal
window.convertReqToPOInModal = async function(id) {
    // We need to fetch the specific requisition data or find it in a global cache
    // For now, let's fetch it to ensure we have the latest items
    try {
        showLoading();
        const response = await fetch(PSM_REQUISITIONS_API + '/' + id, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? 'Bearer ' + JWT_TOKEN : ''
            },
            credentials: 'include'
        });
        
        if (!response.ok) throw new Error('HTTP ' + response.status);
        
        const result = await response.json();
        if (result.success && result.data) {
            const req = result.data;
            
            const confirm = await Swal.fire({
                title: 'Convert to PO?',
                text: 'Do you want to convert Requisition ' + (req.req_id || 'REQ-' + req.id) + ' to a Purchase Order?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, convert',
                cancelButtonText: 'Cancel'
            });
            
            if (confirm.isConfirmed) {
                closeRequisitionsModal();
                openAddModal();
                
                // Set description
                if (elements.purDesc) {
                    elements.purDesc.value = 'Converted from Requisition: ' + (req.req_id || 'REQ-' + req.id) + '. Original Department: ' + req.req_department;
                }
                
                // Set items
                const items = Array.isArray(req.req_items) ? req.req_items : [];
                selectedItems = items.map(item => ({
                    id: Date.now() + Math.random(), 
                    itemId: Date.now() + Math.random(), 
                    name: typeof item === 'object' ? item.name : item,
                    price: typeof item === 'object' ? item.price : 0,
                    picture: null,
                    warranty: null,
                    expiration: null
                }));
                
                updateSelectedItemsDisplay();
                if (elements.itemsSection) elements.itemsSection.classList.remove('hidden');
                
                showNotification('Requisition items loaded. Please select a vendor to proceed.', 'info');
            }
        }
    } catch (error) {
        console.error('Error converting requisition:', error);
        showNotification('Error: ' + error.message, 'error');
    } finally {
        hideLoading();
    }
};

function viewRequisition(id) {
    window.location.href = "{{ url('/psm/purchase-requisition') }}?view=" + id;
}

// Modal Functions
function openAddModal() {
    if (!elements.modalTitle || !elements.purchaseId || !elements.purchaseModal) return;
    
    elements.modalTitle.textContent = 'New Purchase Order';
    elements.purchaseId.value = '';
    if (elements.purchaseForm) elements.purchaseForm.reset();
    if (elements.purOrderBy && CURRENT_USER_LABEL) {
        elements.purOrderBy.value = CURRENT_USER_LABEL;
    }
    
    // Show company selection in add mode
    if (elements.companySelect) {
        const container = elements.companySelect.closest('.mb-4');
        if (container) container.classList.remove('hidden');
    }

    if (elements.itemsSection) elements.itemsSection.classList.add('hidden');
    clearSelectedItems();
    selectedVendor = null;
    elements.purchaseModal.classList.remove('hidden');
}

function openEditModal(purchase) {
    if (!elements.modalTitle || !elements.purchaseId || !elements.purchaseModal) return;
    
    elements.modalTitle.textContent = 'Edit Purchase Order';
    elements.purchaseId.value = purchase.id;
    if (elements.purDesc) elements.purDesc.value = purchase.pur_desc || '';
    if (elements.purOrderBy) elements.purOrderBy.value = purchase.pur_order_by || '';
    
    // Set company
    if (elements.companySelect) {
        elements.companySelect.value = purchase.pur_company_name;
        
        // Manually set the selected vendor so adding items works
        const selectedOption = Array.from(elements.companySelect.options).find(opt => opt.value === purchase.pur_company_name);
        if (selectedOption) {
            setSelectedVendor(selectedOption);
        }

        // Hide company selection in edit mode
        const container = elements.companySelect.closest('.mb-4');
        if (container) container.classList.add('hidden');
    }
    
    // Set vendor type
    if (elements.vendorType) elements.vendorType.value = purchase.pur_ven_type;
    
    // Set items
    const items = Array.isArray(purchase.pur_name_items) ? purchase.pur_name_items : [];
    selectedItems = items.map(item => {
        const itemName = typeof item === 'object' ? item.name : item;
        const itemPrice = typeof item === 'object' ? item.price : 0;
        let itemPicture = typeof item === 'object' ? item.picture : null;
        let itemWarranty = typeof item === 'object' ? item.warranty : null;
        let itemExpiration = typeof item === 'object' ? item.expiration : null;
        
        // Try to find picture and details
        if (!itemPicture || !itemWarranty || !itemExpiration) {
            // Check availableItems first
            if (typeof availableItems !== 'undefined' && availableItems.length > 0) {
                 const foundItem = availableItems.find(i => i.name === itemName);
                 if (foundItem) {
                    if (!itemPicture) itemPicture = foundItem.picture;
                    if (!itemWarranty) itemWarranty = foundItem.warranty;
                    if (!itemExpiration) itemExpiration = foundItem.expiration;
                 }
            }
            
            // If still missing, check activeVendors
            if ((!itemPicture || !itemWarranty || !itemExpiration) && typeof activeVendors !== 'undefined') {
                for (const vendor of activeVendors) {
                    if (vendor.products) {
                        const product = vendor.products.find(p => p.name === itemName);
                        if (product) {
                            if (!itemPicture) itemPicture = product.picture;
                            if (!itemWarranty) itemWarranty = product.warranty;
                            if (!itemExpiration) itemExpiration = product.expiration;
                            break;
                        }
                    }
                }
            }
        }
        
        return {
            id: Date.now() + Math.random(), 
            itemId: Date.now() + Math.random(), 
            name: itemName,
            price: itemPrice,
            picture: itemPicture,
            warranty: itemWarranty,
            expiration: itemExpiration
        };
    });
    
    updateSelectedItemsDisplay();
    if (elements.itemsSection) elements.itemsSection.classList.remove('hidden');
    
    elements.purchaseModal.classList.remove('hidden');
}

function closePurchaseModal() {
    if (elements.purchaseModal) elements.purchaseModal.classList.add('hidden');
    hideConsolidatedPanel();
}

// Form Handling
async function handlePurchaseSubmit(e) {
    e.preventDefault();
    
    if (!selectedVendor) {
        showNotification('Please select a company', 'error');
        return;
    }
    
    if (selectedItems.length === 0) {
        showNotification('Please add at least one item', 'error');
        return;
    }
    
    if (elements.purOrderBy && !elements.purOrderBy.value.trim() && CURRENT_USER_LABEL) {
        elements.purOrderBy.value = CURRENT_USER_LABEL;
    }
    if (!elements.purOrderBy || !elements.purOrderBy.value.trim()) {
        showNotification('Unable to determine ordering user', 'error');
        return;
    }
    
    const data = {
        pur_company_name: elements.companySelect ? elements.companySelect.value : '',
        pur_ven_type: elements.vendorType ? elements.vendorType.value.toLowerCase() : '',
        pur_order_by: elements.purOrderBy ? elements.purOrderBy.value.trim() : '',
        pur_desc: elements.purDesc ? elements.purDesc.value : '',
        pur_name_items: selectedItems.map(item => ({
            name: item.name,
            price: parseFloat(item.price),
            picture: item.picture,
            warranty: item.warranty,
            expiration: item.expiration
        }))
    };
    
    const purchaseId = elements.purchaseId ? elements.purchaseId.value : '';
    const url = purchaseId ? PSM_PURCHASES_API + '/' + purchaseId : PSM_PURCHASES_API;
    const method = purchaseId ? 'PUT' : 'POST';
    
    showLoading();
    
    try {
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? 'Bearer ' + JWT_TOKEN : ''
            },
            credentials: 'include',
            body: JSON.stringify(data)
        });
        
        if (!response.ok) {
            throw new Error('HTTP ' + response.status + ': ' + response.statusText);
        }
        
        const result = await response.json();
        
        if (result.success) {
            showNotification(result.message, 'success');
            closePurchaseModal();
            loadPurchases();
        } else {
            throw new Error(result.message || 'Failed to save purchase');
        }
        
    } catch (error) {
        console.error('Error saving purchase:', error);
        showNotification('Error saving purchase: ' + error.message, 'error');
    } finally {
        hideLoading();
    }
}

function editPurchase(id) {
    const purchase = currentPurchases.find(p => p.id == id);
    if (purchase) {
        openEditModal(purchase);
    }
}

function openCancelPurchaseModal(id) {
    const purchase = currentPurchases.find(p => p.id == id);
    if (!purchase || !elements.cancelPurchaseModal || !elements.cancelPurchaseContent) return;
    
    elements.cancelPurchaseContent.innerHTML = '<div class="space-y-3">' +
            '<div class="bg-blue-50 p-3 rounded-lg">' +
                '<h4 class="font-semibold text-blue-800">Purchase Details</h4>' +
                '<p><strong>PO Number:</strong> ' + purchase.pur_id + '</p>' +
                '<p><strong>Company:</strong> ' + purchase.pur_company_name + '</p>' +
                '<p><strong>Ordered By:</strong> ' + (purchase.pur_order_by || 'Not specified') + '</p>' +
                '<p><strong>Total Amount:</strong> ' + formatCurrency(purchase.pur_total_amount) + '</p>' +
            '</div>' +
            '<div class="bg-red-50 p-3 rounded-lg">' +
                '<h4 class="font-semibold text-red-800">Warning</h4>' +
                '<p>Are you sure you want to cancel purchase order "' + purchase.pur_id + '"? This action cannot be undone.</p>' +
            '</div>' +
        '</div>';
    
    elements.cancelPurchaseModal.dataset.purchaseId = id;
    elements.cancelPurchaseModal.dataset.userLabel = CURRENT_USER_LABEL || '';
    elements.cancelPurchaseModal.classList.remove('hidden');
}

function closeCancelPurchaseModal() {
    if (!elements.cancelPurchaseModal) return;
    
    elements.cancelPurchaseModal.classList.add('hidden');
    delete elements.cancelPurchaseModal.dataset.purchaseId;
    delete elements.cancelPurchaseModal.dataset.userLabel;
    
    if (elements.cancelPurchaseContent) {
        elements.cancelPurchaseContent.innerHTML = '';
    }
}

async function handleConfirmCancelPurchase() {
    if (!elements.cancelPurchaseModal) return;
    
    const purchaseId = elements.cancelPurchaseModal.dataset.purchaseId;
    if (!purchaseId) {
        closeCancelPurchaseModal();
        return;
    }
    
    const cancelBy = elements.cancelPurchaseModal.dataset.userLabel || CURRENT_USER_LABEL || '';
    if (!cancelBy) {
        showNotification('Unable to determine cancelling user', 'error');
        return;
    }
    
    closeCancelPurchaseModal();
    showLoading();
    
    try {
        const response = await fetch(PSM_PURCHASES_API + '/' + purchaseId + '/cancel', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? 'Bearer ' + JWT_TOKEN : ''
            },
            credentials: 'include',
            body: JSON.stringify({ cancel_by: cancelBy })
        });
        
        if (!response.ok) {
            throw new Error('HTTP ' + response.status + ': ' + response.statusText);
        }
        
        const result = await response.json();
        
        if (result.success) {
            showNotification(result.message, 'success');
            loadPurchases();
            
            const cancelBtn = document.querySelector('[onclick="cancelPurchase(' + purchaseId + ')"]');
            if (cancelBtn) {
                cancelBtn.disabled = true;
                cancelBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        } else {
            throw new Error(result.message || 'Failed to cancel purchase');
        }
        
    } catch (error) {
        console.error('Error cancelling purchase:', error);
        showNotification('Error cancelling purchase: ' + error.message, 'error');
    } finally {
        hideLoading();
    }
}

function cancelPurchase(id) {
    openCancelPurchaseModal(id);
}

async function deletePurchase(id) {
    const purchase = currentPurchases.find(p => p.id == id);
    if (!purchase) return;
    
    const confirmResult = await Swal.fire({
        title: 'Delete Purchase Order?',
        text: 'Are you sure you want to delete purchase order "' + purchase.pur_id + '"? This action cannot be undone.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    });
    
    if (!confirmResult.isConfirmed) return;
    
    showLoading();
    
    try {
        const response = await fetch(PSM_PURCHASES_API + '/' + id, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? 'Bearer ' + JWT_TOKEN : ''
            },
            credentials: 'include'
        });
        
        if (!response.ok) {
            throw new Error('HTTP ' + response.status + ': ' + response.statusText);
        }
        
        const result = await response.json();
        
        if (result.success) {
            showNotification(result.message, 'success');
            loadPurchases();
        } else {
            throw new Error(result.message || 'Failed to delete purchase');
        }
        
    } catch (error) {
        console.error('Error deleting purchase:', error);
        showNotification('Error deleting purchase: ' + error.message, 'error');
    } finally {
        hideLoading();
    }
}

function findProductPicture(productName) {
    if (!activeVendors || activeVendors.length === 0) return null;
    
    for (const vendor of activeVendors) {
        if (vendor.products && Array.isArray(vendor.products)) {
            const product = vendor.products.find(p => p.name === productName);
            if (product && product.picture) {
                return product.picture;
            }
        }
    }
    return null;
}

function viewPurchase(id) {
    const purchase = currentPurchases.find(p => p.id == id);
    if (!purchase) return;
    
    const items = Array.isArray(purchase.pur_name_items) ? purchase.pur_name_items : [];
    
    const itemsHtml = items.map(item => {
        const itemName = typeof item === 'object' ? item.name : item;
        const itemPrice = typeof item === 'object' ? formatCurrency(item.price) : 'N/A';
        let itemPicture = typeof item === 'object' ? item.picture : null;
        let itemWarranty = typeof item === 'object' ? (item.warranty || 'N/A') : 'N/A';
        let itemExpiration = typeof item === 'object' ? (item.expiration ? formatDate(item.expiration) : 'N/A') : 'N/A';
        
        // Try to find details from active vendors if missing
        if ((!itemPicture || itemWarranty === 'N/A' || itemExpiration === 'N/A') && typeof activeVendors !== 'undefined') {
            for (const vendor of activeVendors) {
                if (vendor.products) {
                    const product = vendor.products.find(p => p.name === itemName);
                    if (product) {
                        if (!itemPicture) itemPicture = product.picture;
                        if (itemWarranty === 'N/A') itemWarranty = product.warranty || 'N/A';
                        if (itemExpiration === 'N/A') itemExpiration = product.expiration ? formatDate(product.expiration) : 'N/A';
                        // Break if we found everything we need, otherwise keep looking? 
                        // Assuming unique names, we can break.
                        break; 
                    }
                }
            }
        }
        
        const imageUrl = itemPicture 
            ? getProductImageUrl(itemPicture)
            : 'https://placehold.co/100x100?text=No+Image';

        return '<li class="flex items-center gap-4 py-3 border-b border-gray-100 last:border-0 hover:bg-gray-50 transition-colors rounded-lg px-2">' +
            '<div class="flex-shrink-0 h-12 w-12">' +
                '<img src="' + imageUrl + '" alt="' + itemName + '" class="h-12 w-12 rounded-lg object-cover border border-gray-200 bg-white" onerror="this.src=\'https://placehold.co/100x100?text=No+Image\'">' +
            '</div>' +
            '<div class="flex-1 min-w-0">' +
                '<p class="text-sm font-medium text-gray-900 truncate">' +
                    itemName +
                '</p>' +
                '<p class="text-xs text-gray-500 mt-1">' +
                    'Warranty: ' + itemWarranty + ' | Expiration: ' + itemExpiration +
                '</p>' +
            '</div>' +
            '<div class="inline-flex items-center text-sm font-semibold text-gray-900">' +
                itemPrice +
            '</div>' +
        '</li>';
    }).join('');
    
    const content = '<div class="space-y-6">' +
            '<!-- Header Status Banner -->' +
            '<div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg border border-gray-100">' +
                '<div>' +
                    '<span class="text-sm text-gray-500 block">Status</span>' +
                    '<span class="inline-flex items-center mt-1 px-2.5 py-0.5 rounded-full text-sm font-medium ' + getStatusBadgeClass(purchase.pur_status) + '">' +
                        purchase.pur_status +
                    '</span>' +
                '</div>' +
                '<div class="text-right">' +
                    '<span class="text-sm text-gray-500 block">Total Amount</span>' +
                    '<span class="text-xl font-bold text-green-600">' + formatCurrency(purchase.pur_total_amount) + '</span>' +
                '</div>' +
            '</div>' +

            '<div class="grid grid-cols-1 md:grid-cols-2 gap-6">' +
                '<!-- Order Information -->' +
                '<div>' +
                    '<h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Order Information</h4>' +
                    '<dl class="space-y-2">' +
                        '<div class="flex justify-between">' +
                            '<dt class="text-sm text-gray-600">PO Number</dt>' +
                            '<dd class="text-sm font-medium text-gray-900">' + purchase.pur_id + '</dd>' +
                        '</div>' +
                        '<div class="flex justify-between">' +
                            '<dt class="text-sm text-gray-600">Company</dt>' +
                            '<dd class="text-sm font-medium text-gray-900">' + purchase.pur_company_name + '</dd>' +
                        '</div>' +
                        '<div class="flex justify-between">' +
                            '<dt class="text-sm text-gray-600">Vendor Type</dt>' +
                            '<dd class="text-sm font-medium text-gray-900 capitalize">' + purchase.pur_ven_type + '</dd>' +
                        '</div>' +
                        '<div class="flex justify-between">' +
                            '<dt class="text-sm text-gray-600">Total Units</dt>' +
                            '<dd class="text-sm font-medium text-gray-900">' + purchase.pur_unit + '</dd>' +
                        '</div>' +
                    '</dl>' +
                '</div>' +

                '<!-- Approval & Tracking -->' +
                '<div>' +
                    '<h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Tracking & Approval</h4>' +
                    '<dl class="space-y-2">' +
                        '<div class="flex justify-between">' +
                            '<dt class="text-sm text-gray-600">Ordered By</dt>' +
                            '<dd class="text-sm font-medium text-gray-900">' + (purchase.pur_order_by || 'Not specified') + '</dd>' +
                        '</div>' +
                        '<div class="flex justify-between">' +
                            '<dt class="text-sm text-gray-600">Approved By</dt>' +
                            '<dd class="text-sm font-medium text-gray-900">' + (purchase.pur_approved_by || 'Not approved') + '</dd>' +
                        '</div>' +
                        '<div class="flex justify-between">' +
                            '<dt class="text-sm text-gray-600">Created Date</dt>' +
                            '<dd class="text-sm font-medium text-gray-900">' + new Date(purchase.created_at).toLocaleDateString() + '</dd>' +
                        '</div>' +
                        '<div class="flex justify-between">' +
                            '<dt class="text-sm text-gray-600">Last Updated</dt>' +
                            '<dd class="text-sm font-medium text-gray-900">' + new Date(purchase.updated_at).toLocaleDateString() + '</dd>' +
                        '</div>' +
                    '</dl>' +
                '</div>' +
            '</div>' +

            '<!-- Items Section -->' +
            '<div>' +
                '<h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Items (' + items.length + ')</h4>' +
                '<div class="bg-white border border-gray-200 rounded-lg overflow-hidden">' +
                    '<ul class="divide-y divide-gray-100">' +
                        (itemsHtml || '<li class="p-4 text-center text-gray-500">No items found</li>') +
                    '</ul>' +
                '</div>' +
            '</div>' +

            '<!-- Warranty Section -->' +
            (function() {
                try {
                    const warranties = purchase.pur_warranty ? JSON.parse(purchase.pur_warranty) : [];
                    if (!warranties || !Array.isArray(warranties) || warranties.length === 0) return '';
                    return '<div>' +
                        '<h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Warranty Details</h4>' +
                        '<div class="bg-white border border-gray-200 rounded-lg overflow-hidden">' +
                            '<ul class="divide-y divide-gray-100">' +
                                warranties.map(w => '<li class="p-3 text-sm">' +
                                        '<div class="flex justify-between">' +
                                            '<span class="font-medium text-gray-900">' + (w.item || 'Unknown Item') + '</span>' +
                                            '<span class="text-gray-600">Ends: ' + (w.warranty_end ? formatDate(w.warranty_end) : 'N/A') + '</span>' +
                                        '</div>' +
                                        '<div class="text-xs text-gray-500 mt-1">Duration: ' + (w.original_warranty || 'N/A') + '</div>' +
                                    '</li>').join('') +
                            '</ul>' +
                        '</div>' +
                    '</div>';
                } catch (e) { console.error('Error parsing warranty:', e); return ''; }
            })() +

            '<!-- Expiration Section -->' +
            (function() {
                try {
                    const expirations = purchase.pur_expiration ? JSON.parse(purchase.pur_expiration) : [];
                    if (!expirations || !Array.isArray(expirations) || expirations.length === 0) return '';
                    return '<div>' +
                        '<h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Expiration Details</h4>' +
                        '<div class="bg-white border border-gray-200 rounded-lg overflow-hidden">' +
                            '<ul class="divide-y divide-gray-100">' +
                                expirations.map(e => '<li class="p-3 text-sm flex justify-between">' +
                                        '<span class="font-medium text-gray-900">' + (e.item || 'Unknown Item') + '</span>' +
                                        '<span class="text-gray-600">Expires: ' + (e.expiration_date ? formatDate(e.expiration_date) : 'N/A') + '</span>' +
                                    '</li>').join('') +
                            '</ul>' +
                        '</div>' +
                    '</div>';
                } catch (e) { console.error('Error parsing expiration:', e); return ''; }
            })() +

            '<!-- Description Section -->' +
            (purchase.pur_desc ? '<div>' +
                '<h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Description</h4>' +
                '<div class="bg-gray-50 p-4 rounded-lg border border-gray-100 text-sm text-gray-700">' +
                    purchase.pur_desc +
                '</div>' +
            '</div>' : '') +
        '</div>';
    const container = document.getElementById('viewPurchaseContent');
    if (container) container.innerHTML = content;
    const modal = document.getElementById('viewPurchaseModal');
    if (modal) modal.classList.remove('hidden');
}

function closeViewPurchaseModal() {
    const modal = document.getElementById('viewPurchaseModal');
    if (modal) modal.classList.add('hidden');
}

// Global functions for HTML onclick
window.viewPurchase = viewPurchase;
window.editPurchase = editPurchase;
window.deletePurchase = deletePurchase;
window.cancelPurchase = cancelPurchase;
window.addItemToPurchase = addItemToPurchase;
window.removeSelectedItem = removeSelectedItem;
window.removeItemGroup = removeItemGroup;
window.removeSingleItem = removeSingleItem;

// Utility Functions
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

function showLoading() {
    document.body.style.cursor = 'wait';
}

function hideLoading() {
    document.body.style.cursor = 'default';
}

function formatCurrency(value) {
    const num = Number(value || 0);
    return '₱' + num.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

function formatDate(d) {
    if (!d) return '';
    const dt = new Date(d);
    const mm = String(dt.getMonth() + 1).padStart(2, '0');
    const dd = String(dt.getDate()).padStart(2, '0');
    const yyyy = dt.getFullYear();
    return mm + '-' + dd + '-' + yyyy;
}

function formatDateRange(a, b) {
    const A = formatDate(a);
    const B = formatDate(b);
    if (!A && !B) return '';
    if (A && B) return A + ' to ' + B;
    return A || B;
}

</script>
