<?php
    $jwtToken = '';
    if (auth()->check()) {
        try {
            $user = auth()->user();
            $secret = config('app.key');
            if (is_string($secret) && str_starts_with($secret, 'base64:')) {
                $secret = base64_decode(substr($secret, 7));
            }
            
            $payload = [
                'iss' => config('app.url') ?? 'logs1',
                'sub' => $user->id,
                'email' => $user->email,
                'roles' => $user->roles,
                'iat' => time(),
                'exp' => time() + (60 * 60 * 2)
            ];
            
            $jwtToken = \Firebase\JWT\JWT::encode($payload, $secret, 'HS256');
        } catch (\Exception $e) {
            // Silently fail, fallback to localStorage
        }
    }
?>
<!-- resources/views/psm/purchase-management.blade.php -->
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bxs-purchase-tag'></i>Purchase Management</h2>
    </div>
    <div class="text-right">
        <span class="text-md text-gray-600">Procurement & Sourcing Management</span>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg p-6">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">Purchase Orders</h3>
        <div class="flex gap-3">
            <button id="addPurchaseBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-plus'></i>
                New Purchase Order
            </button>
        </div>
    </div>

    <!-- Stats Section -->
    <div id="statsSection" class="hidden grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-blue-500 rounded-lg">
                    <i class='bx bx-purchase-tag text-white text-2xl'></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-blue-700">Total Orders</p>
                    <p id="totalOrders" class="text-2xl font-bold text-blue-900">0</p>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 rounded-lg p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-green-500 rounded-lg">
                    <i class='bx bx-check-circle text-white text-2xl'></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-green-700">Approved</p>
                    <p id="approvedOrders" class="text-2xl font-bold text-green-900">0</p>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 border border-yellow-200 rounded-lg p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-500 rounded-lg">
                    <i class='bx bx-time text-white text-2xl'></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-yellow-700">Pending</p>
                    <p id="pendingOrders" class="text-2xl font-bold text-yellow-900">0</p>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-red-50 to-red-100 border border-red-200 rounded-lg p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-red-500 rounded-lg">
                    <i class='bx bx-x-circle text-white text-2xl'></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-red-700">Cancelled</p>
                    <p id="cancelledOrders" class="text-2xl font-bold text-red-900">0</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters and Search Section -->
    <div class="hidden mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Search -->
        <div class="md:col-span-2">
            <input type="text" id="searchInput" placeholder="Search by PO number, company, items, order by, or approved by..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        
        <!-- Status Filter -->
        <div>
            <select id="statusFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Status</option>
                <option value="Pending">Pending</option>
                <option value="Approved">Approved</option>
                <option value="Rejected">Rejected</option>
                <option value="Cancel">Cancelled</option>
                <option value="Vendor-Review">Vendor Review</option>
                <option value="In-Progress">In Progress</option>
                <option value="Completed">Completed</option>
            </select>
        </div>
        
        <!-- Vendor Type Filter -->
        <div>
            <select id="vendorTypeFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Types</option>
                <option value="equipment">Equipment</option>
                <option value="supplies">Supplies</option>
                <option value="furniture">Furniture</option>
                <option value="automotive">Automotive</option>
            </select>
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
                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap">Delivery</th>
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

<!-- Add/Edit Purchase Modal -->
<div id="purchaseModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-4xl max-h-[90vh] overflow-y-auto">
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

<!-- Budget Approval Modal -->
<div id="budgetApprovalModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-4xl">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Budget Approval</h3>
            <button id="closeBudgetApprovalModal" class="text-gray-500 hover:text-gray-700">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>
        <div id="budgetApprovalContent" class="space-y-4 mb-4">
            
        </div>
        <div class="flex justify-end gap-3">
            <button type="button" id="rejectBudgetBtn" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">Reject</button>
            <button type="button" id="approveBudgetBtn" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">Approve</button>
        </div>
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

<script>
// API Configuration
var API_BASE_URL = typeof API_BASE_URL !== 'undefined' ? API_BASE_URL : '<?php echo url('/api/v1'); ?>';
var PSM_PURCHASES_API = typeof PSM_PURCHASES_API !== 'undefined' ? PSM_PURCHASES_API : `${API_BASE_URL}/psm/purchase-management`;
var PSM_ACTIVE_VENDORS_API = `${API_BASE_URL}/psm/active-vendors`;
var CSRF_TOKEN = typeof CSRF_TOKEN !== 'undefined' ? CSRF_TOKEN : document.querySelector('meta[name="csrf-token"]').getAttribute('content');
var JWT_TOKEN = '<?php echo $jwtToken; ?>' || (typeof JWT_TOKEN !== 'undefined' ? JWT_TOKEN : localStorage.getItem('jwt'));

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
    budgetApprovalModal: document.getElementById('budgetApprovalModal'),
    closeBudgetApprovalModal: document.getElementById('closeBudgetApprovalModal'),
    budgetApprovalContent: document.getElementById('budgetApprovalContent'),
    rejectBudgetBtn: document.getElementById('rejectBudgetBtn'),
    approveBudgetBtn: document.getElementById('approveBudgetBtn'),
    cancelPurchaseModal: document.getElementById('cancelPurchaseModal'),
    closeCancelPurchaseModal: document.getElementById('closeCancelPurchaseModal'),
    cancelPurchaseContent: document.getElementById('cancelPurchaseContent'),
    cancelCancelPurchaseBtn: document.getElementById('cancelCancelPurchaseBtn'),
    confirmCancelPurchaseBtn: document.getElementById('confirmCancelPurchaseBtn')
};

function initPurchaseManagement() {
    console.log('🚀 Purchase Management Initialized');
    console.log('API Base URL:', API_BASE_URL);
    console.log('Purchases API:', PSM_PURCHASES_API);
    initializeEventListeners();
    loadPurchases();
    loadActiveVendors();
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
    
    // Budget approval modal
    if (elements.closeBudgetApprovalModal) elements.closeBudgetApprovalModal.addEventListener('click', closeBudgetApprovalModal);
    if (elements.rejectBudgetBtn) elements.rejectBudgetBtn.addEventListener('click', handleBudgetRejection);
    if (elements.approveBudgetBtn) elements.approveBudgetBtn.addEventListener('click', handleBudgetApproval);
    
    // Cancel purchase modal
    if (elements.closeCancelPurchaseModal) elements.closeCancelPurchaseModal.addEventListener('click', closeCancelPurchaseModal);
    if (elements.cancelCancelPurchaseBtn) elements.cancelCancelPurchaseBtn.addEventListener('click', closeCancelPurchaseModal);
    if (elements.confirmCancelPurchaseBtn) elements.confirmCancelPurchaseBtn.addEventListener('click', handleConfirmCancelPurchase);
    
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
    
    if (elements.budgetApprovalModal) {
        elements.budgetApprovalModal.addEventListener('click', function(e) {
            if (e.target === elements.budgetApprovalModal) {
                closeBudgetApprovalModal();
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
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            credentials: 'include'
        });
        
        if (!response.ok) {
            const errorData = await response.json();
            throw new Error(errorData.message || `HTTP ${response.status}: ${response.statusText}`);
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
        option.textContent = `${vendor.company_name} (${vendor.type})`;
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
        html: `Your current added items from <strong>${selectedVendor.company_name}</strong> will be cleared if you change the company.`,
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
            ? (item.picture.startsWith('storage/') ? `/${item.picture}` : `/storage/${item.picture}`)
            : 'https://placehold.co/100x100?text=No+Image';
        
        itemElement.innerHTML = `
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <img src="${imageUrl}" alt="${item.name}" class="w-12 h-12 object-cover rounded-md bg-gray-100" onerror="this.src='https://placehold.co/100x100?text=No+Image'">
                    <div>
                        <h4 class="font-medium text-gray-900">${item.name}</h4>
                        <p class="text-sm text-gray-600">Price: ${formatCurrency(item.price)} | Stock: ${item.stock}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="addItemToPurchase(${item.id})" 
                            class="px-3 py-1 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition-colors">
                        Add
                    </button>
                </div>
            </div>
        `;
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
            ? (item.picture.startsWith('storage/') ? `/${item.picture}` : `/storage/${item.picture}`)
            : 'https://placehold.co/100x100?text=No+Image';
        
        itemElement.innerHTML = `
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <img src="${imageUrl}" alt="${item.name}" class="w-12 h-12 object-cover rounded-md bg-gray-100" onerror="this.src='https://placehold.co/100x100?text=No+Image'">
                    <div>
                        <h4 class="font-medium text-gray-900">${item.name}</h4>
                        <p class="text-sm text-gray-600">Price: ${formatCurrency(item.price)} | Stock: ${item.stock}</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <button type="button" onclick="addItemToPurchase(${item.id})" 
                            class="px-3 py-1 bg-blue-600 text-white rounded-lg text-sm hover:bg-blue-700 transition-colors">
                        Add
                    </button>
                </div>
            </div>
        `;
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
        picture: item.picture
    });
    
    showNotification(`Added ${item.name} to purchase order`, 'success');
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
                ? (group.picture.startsWith('storage/') ? `/${group.picture}` : `/storage/${group.picture}`)
                : 'https://placehold.co/100x100?text=No+Image';
            return `
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                <div class="flex items-center gap-3">
                    <img src="${imageUrl}" alt="${group.name}" class="w-12 h-12 object-cover rounded-md bg-white border border-gray-200" onerror="this.src='https://placehold.co/100x100?text=No+Image'">
                    <div>
                        <h4 class="font-medium text-gray-900">${group.name}</h4>
                        <p class="text-sm text-gray-600">
                            ${formatCurrency(group.price)} each × ${group.count} = ${formatCurrency(group.price * group.count)}
                        </p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-sm text-gray-600 bg-white px-2 py-1 rounded border">Qty: ${group.count}</span>
                    <button type="button" onclick="removeItemGroup([${group.instances.join(',')}])" 
                            class="px-3 py-1 bg-red-600 text-white rounded-lg text-sm hover:bg-red-700 transition-colors">
                        Remove All
                    </button>
                    <button type="button" onclick="removeSingleItem(${group.instances[0]})" 
                            class="px-3 py-1 bg-yellow-600 text-white rounded-lg text-sm hover:bg-yellow-700 transition-colors">
                        Remove One
                    </button>
                </div>
            </div>
        `}).join('');
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
        const purchasesUrl = `${PSM_PURCHASES_API}`;
        console.log('📡 Fetching purchases from:', `${purchasesUrl}?${params}`);
        
        const response = await fetch(`${purchasesUrl}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            credentials: 'include'
        });
        
        console.log('📨 Response status:', response.status);
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        const result = await response.json();
        console.log('📊 Purchases response:', result);
        
        if (result.success) {
            currentPurchases = result.data || [];
            displayPurchases(currentPurchases);
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

function loadStats() {}

function displayPurchases(purchases) {
    if (!elements.purchasesTableBody) return;
    const filtered = getPurchasesFiltered(purchases);
    const total = filtered.length;
    if (!filtered || total === 0) {
        elements.purchasesTableBody.innerHTML = `
            <tr>
                <td colspan="10" class="px-6 py-8 text-center text-gray-500">
                    <i class='bx bxs-purchase-tag text-4xl text-gray-300 mb-3'></i>
                    <p class="text-lg">No purchase orders found</p>
                </td>
            </tr>
        `;
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
        const isCancelled = purchase.pur_status === 'Cancel';
        const rowClass = isCompleted ? 'bg-gray-200' : 'hover:bg-gray-50 transition-colors';
        
        const orderedByHtml = formatUserLabelDisplay(purchase.pur_order_by, 'Not specified');
        const approvedSource = purchase.pur_status === 'Cancel'
            ? (purchase.pur_cancel_by || purchase.pur_approved_by || 'Unknown Approver')
            : (purchase.pur_approved_by || 'Not approved');
        const approvedByHtml = formatUserLabelDisplay(approvedSource, purchase.pur_status === 'Cancel' ? 'Unknown Approver' : 'Not approved');
        
        const companyTypeHtml = `
            <div class="flex flex-col leading-tight">
                <span class="text-sm font-semibold text-gray-900">${purchase.pur_company_name}</span>
                <span class="text-xs text-gray-500 capitalize">${purchase.pur_ven_type}</span>
            </div>
        `;
        
        return `
        <tr class="${rowClass}" data-purchase-id="${purchase.id}">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-mono text-gray-900">
                ${purchase.pur_id}
            </td>
            <td class="px-6 py-4 text-sm text-gray-900">
                <div class="max-w-xs truncate" title="${items.map(item => typeof item === 'object' ? item.name : item).join(', ')}">
                    ${itemsString}
                </div>
                <small class="text-xs text-gray-500">${items.length} item(s)</small>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${companyTypeHtml}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                ${purchase.pur_unit}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-green-600">
                ${formatCurrency(purchase.pur_total_amount)}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${formatDateRange(purchase.pur_delivery_date_from, purchase.pur_delivery_date_to) || ''}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${orderedByHtml}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${approvedByHtml}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusBadgeClass(purchase.pur_status)}">
                    <i class='bx ${getStatusIcon(purchase.pur_status)} mr-1'></i>
                    ${purchase.pur_status}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex items-center space-x-2">
                    <button onclick="viewPurchase(${purchase.id})" 
                            class="text-gray-700 hover:text-gray-900 transition-colors p-2 rounded-lg hover:bg-gray-50"
                            title="View Purchase">
                        <i class='bx bx-show-alt text-xl'></i>
                    </button>
                    ${canApprove ? `
                    <button onclick="openBudgetApproval(${purchase.id})" 
                            class="text-green-600 hover:text-green-900 transition-colors p-2 rounded-lg hover:bg-green-50"
                            title="Budget Approval">
                        <i class='bx bx-check-shield text-xl'></i>
                    </button>` : ''}
                    ${isApproved || isCompleted || isCancelled ? '' : `
                    <button onclick="editPurchase(${purchase.id})" 
                            class="text-blue-600 hover:text-blue-900 transition-colors p-2 rounded-lg hover:bg-blue-50"
                            title="Edit Purchase">
                        <i class='bx bx-edit-alt text-xl'></i>
                    </button>`}
                    
                    ${isCompleted ? '' : (canCancel ? `
                    <button onclick="cancelPurchase(${purchase.id})" 
                            class="text-red-600 hover:text-red-900 transition-colors p-2 rounded-lg hover:bg-red-50 cancel-purchase-btn"
                            title="Cancel Purchase">
                        <i class='bx bx-x-circle text-xl'></i>
                    </button>` : '')}
                    <button onclick="deletePurchase(${purchase.id})" 
                            class="text-red-600 hover:text-red-900 transition-colors p-2 rounded-lg hover:bg-red-50"
                            title="Delete Purchase">
                        <i class='bx bx-trash text-xl'></i>
                    </button>
                </div>
            </td>
        </tr>
    `}).join('');
    
    elements.purchasesTableBody.innerHTML = purchasesHtml;
    renderPurchasesPager(total, totalPages);
}

function renderPurchasesPager(total, totalPages){
    const info = document.getElementById('purchasesPagerInfo');
    const display = document.getElementById('purchasesPageDisplay');
    const start = total === 0 ? 0 : ((currentPurchasesPage - 1) * purchasesPageSize) + 1;
    const end = Math.min(currentPurchasesPage * purchasesPageSize, total);
    if (info) info.textContent = `Showing ${start}-${end} of ${total}`;
    if (display) display.textContent = `${currentPurchasesPage} / ${totalPages}`;
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
        'Pending': 'bg-yellow-100 text-yellow-800',
        'Approved': 'bg-blue-100 text-blue-800',
        'Rejected': 'bg-red-100 text-red-800',
        'Cancel': 'bg-red-100 text-red-800',
        'Vendor-Review': 'bg-purple-100 text-purple-800',
        'In-Progress': 'bg-indigo-100 text-indigo-800',
        'Completed': 'bg-green-100 text-green-800'
    };
    return statusClasses[status] || 'bg-gray-100 text-gray-800';
}

function getStatusIcon(status) {
    const statusIcons = {
        'Pending': 'bx-time',
        'Approved': 'bx-check-circle',
        'Rejected': 'bx-x-circle',
        'Cancel': 'bx-x-circle',
        'Vendor-Review': 'bx-user-voice',
        'In-Progress': 'bx-cog',
        'Completed': 'bx-check-circle'
    };
    return statusIcons[status] || 'bx-question-mark';
}

function formatUserLabelDisplay(label, fallback) {
    const raw = (label || '').trim();
    if (!raw) {
        return `<span class="text-sm text-gray-500">${fallback}</span>`;
    }
    
    const parts = raw.split('-');
    const name = parts[0].trim();
    const roleRaw = parts.slice(1).join('-').trim();
    
    if (!roleRaw) {
        return `<span class="text-sm font-semibold text-gray-900">${name}</span>`;
    }
    
    const role = roleRaw.toLowerCase().split(' ').map(w => w.charAt(0).toUpperCase() + w.slice(1)).join(' ');
    
    return `
        <div class="flex flex-col leading-tight">
            <span class="text-sm font-semibold text-gray-900">${name}</span>
            <span class="text-xs text-gray-500">${role}</span>
        </div>
    `;
}

// Budget Approval Functions
async function openBudgetApproval(purchaseId) {
    const purchase = currentPurchases.find(p => p.id == purchaseId);
    if (!purchase) return;
    
    // Store the purchase ID for the approval/rejection handlers
    if (elements.budgetApprovalModal) {
        elements.budgetApprovalModal.dataset.purchaseId = purchaseId;
        elements.budgetApprovalModal.classList.remove('hidden');
    }

    // Reset and disable approve button initially while loading
    if (elements.approveBudgetBtn) {
        elements.approveBudgetBtn.disabled = true;
        elements.approveBudgetBtn.classList.add('opacity-50', 'cursor-not-allowed');
    }

    if (elements.budgetApprovalContent) {
        elements.budgetApprovalContent.innerHTML = '<div class="text-center py-4"><i class="fas fa-spinner fa-spin"></i> Loading budget details...</div>';
    }

    try {
        const response = await fetch('/api/v1/psm/budget-management/current', {
            headers: {
                'Accept': 'application/json',
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            }
        });
        
        let budgetHtml = '';
        if (response.ok) {
            const result = await response.json();
            if (result.success && result.data) {
                const budget = result.data;
                const remaining = parseFloat(budget.bud_remaining_amount);
                const purchaseAmount = parseFloat(purchase.pur_total_amount);
                const newRemaining = remaining - purchaseAmount;
                const isInsufficient = newRemaining < 0;
                
                // Update approve button state based on budget sufficiency
                if (elements.approveBudgetBtn) {
                    elements.approveBudgetBtn.disabled = isInsufficient;
                    if (isInsufficient) {
                        elements.approveBudgetBtn.classList.add('opacity-50', 'cursor-not-allowed');
                        elements.approveBudgetBtn.title = "Insufficient Budget - Cannot Approve";
                    } else {
                        elements.approveBudgetBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        elements.approveBudgetBtn.title = "Approve Budget";
                    }
                }

                const colorClass = isInsufficient ? 'red' : 'green';
                
                budgetHtml = `
                    <div class="bg-${colorClass}-50 p-4 rounded-lg h-full border border-${colorClass}-200">
                        <h4 class="font-semibold text-${colorClass}-800 mb-4 text-lg border-b border-${colorClass}-200 pb-2">
                            Budget Calculation
                        </h4>
                        
                        <div class="space-y-4">
                            <div class="flex justify-between items-center">
                                <span class="text-${colorClass}-700 font-medium">Remaining Budget</span>
                                <span class="font-bold text-${colorClass}-900 text-lg">${formatCurrency(remaining)}</span>
                            </div>
                            
                            <div class="flex justify-between items-center text-${colorClass}-600 pl-4 relative">
                                <span class="absolute left-0 top-1/2 -translate-y-1/2 font-bold text-xl">-</span>
                                <span class="ml-2">Item Total Amount</span>
                                <span class="font-medium">${formatCurrency(purchaseAmount)}</span>
                            </div>
                            
                            <div class="border-t-2 border-${colorClass}-300 pt-3 flex justify-between items-center">
                                <span class="font-bold text-${colorClass}-800 text-lg">= Current Remaining Budget</span>
                                <span class="font-bold text-${colorClass}-900 text-xl ${isInsufficient ? 'text-red-600' : ''}">${formatCurrency(newRemaining)}</span>
                            </div>
                        </div>
                        
                        ${isInsufficient ? `
                            <div class="mt-6 p-3 bg-red-100 text-red-800 rounded-lg text-center font-bold flex items-center justify-center gap-2 border border-red-200">
                                <i class='bx bx-error-circle text-xl'></i> 
                                Insufficient Budget
                            </div>
                        ` : ''}
                    </div>
                `;
            } else {
                budgetHtml = `<div class="bg-red-50 p-3 rounded-lg h-full text-red-800">Failed to load budget info</div>`;
            }
        } else {
             budgetHtml = `<div class="bg-red-50 p-3 rounded-lg h-full text-red-800">Failed to load budget info</div>`;
        }
        
        if (elements.budgetApprovalContent) {
            elements.budgetApprovalContent.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-white p-4 rounded-lg border border-gray-200 shadow-sm h-full">
                        <h4 class="font-semibold text-gray-800 mb-4 text-lg border-b border-gray-100 pb-2">Purchase Details</h4>
                        <div class="space-y-3">
                            <div class="flex justify-between">
                                <span class="text-gray-500">PO Number:</span>
                                <span class="font-medium text-gray-900">${purchase.pur_id}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Company:</span>
                                <span class="font-medium text-gray-900">${purchase.pur_company_name}</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-500">Ordered By:</span>
                                <span class="font-medium text-gray-900">${purchase.pur_order_by || 'Not specified'}</span>
                            </div>
                            <div class="flex justify-between pt-2 border-t border-gray-100 mt-2">
                                <span class="text-gray-800 font-semibold">Total Amount:</span>
                                <span class="font-bold text-green-600 text-lg">${formatCurrency(purchase.pur_total_amount)}</span>
                            </div>
                        </div>
                    </div>
                    ${budgetHtml}
                </div>
            `;
        }

    } catch (e) {
        console.error("Error fetching budget", e);
        if (elements.budgetApprovalContent) {
             elements.budgetApprovalContent.innerHTML = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="bg-blue-50 p-3 rounded-lg">
                        <h4 class="font-semibold text-blue-800">Purchase Details</h4>
                        <p><strong>PO Number:</strong> ${purchase.pur_id}</p>
                        <p><strong>Company:</strong> ${purchase.pur_company_name}</p>
                        <p><strong>Ordered By:</strong> ${purchase.pur_order_by || 'Not specified'}</p>
                        <p><strong>Total Amount:</strong> ${formatCurrency(purchase.pur_total_amount)}</p>
                    </div>
                    <div class="bg-red-50 p-3 rounded-lg">
                        <p class="text-red-800">Error loading budget details.</p>
                    </div>
                </div>
            `;
        }
    }
}

function closeBudgetApprovalModal() {
    if (elements.budgetApprovalModal) {
        elements.budgetApprovalModal.classList.add('hidden');
        delete elements.budgetApprovalModal.dataset.purchaseId;
    }
}

async function handleBudgetApproval() {
    const purchaseId = elements.budgetApprovalModal ? elements.budgetApprovalModal.dataset.purchaseId : null;
    if (!purchaseId) return;
    
    const approvedBy = (CURRENT_USER_LABEL || '').trim();
    if (!approvedBy) {
        showNotification('Unable to determine approving user', 'error');
        return;
    }
    
    showLoading();
    
    try {
        const response = await fetch(`${PSM_PURCHASES_API}/${purchaseId}/budget-approval`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            credentials: 'include',
            body: JSON.stringify({ 
                action: 'approve',
                approved_by: approvedBy
            })
        });
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('Purchase approved successfully', 'success');
            closeBudgetApprovalModal();
            loadPurchases();
            
            // Disable the budget approval button for this purchase
            const approveBtn = document.querySelector(`[onclick="openBudgetApproval(${purchaseId})"]`);
            if (approveBtn) {
                approveBtn.disabled = true;
                approveBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        } else {
            throw new Error(result.message || 'Failed to approve purchase');
        }
        
    } catch (error) {
        console.error('Error approving purchase:', error);
        showNotification('Error approving purchase: ' + error.message, 'error');
    } finally {
        hideLoading();
    }
}

async function handleBudgetRejection() {
    const purchaseId = elements.budgetApprovalModal ? elements.budgetApprovalModal.dataset.purchaseId : null;
    if (!purchaseId) return;
    
    const approvedBy = (CURRENT_USER_LABEL || '').trim();
    if (!approvedBy) {
        showNotification('Unable to determine rejecting user', 'error');
        return;
    }
    
    showLoading();
    
    try {
        const response = await fetch(`${PSM_PURCHASES_API}/${purchaseId}/budget-approval`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            credentials: 'include',
            body: JSON.stringify({ 
                action: 'reject',
                approved_by: approvedBy
            })
        });
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        const result = await response.json();
        
        if (result.success) {
            showNotification('Purchase rejected successfully', 'success');
            closeBudgetApprovalModal();
            loadPurchases();
            
            // Disable the budget approval button for this purchase
            const approveBtn = document.querySelector(`[onclick="openBudgetApproval(${purchaseId})"]`);
            if (approveBtn) {
                approveBtn.disabled = true;
                approveBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }
        } else {
            throw new Error(result.message || 'Failed to reject purchase');
        }
        
    } catch (error) {
        console.error('Error rejecting purchase:', error);
        showNotification('Error rejecting purchase: ' + error.message, 'error');
    } finally {
        hideLoading();
    }
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
        
        // Try to find picture in availableItems if missing
        if (!itemPicture && typeof availableItems !== 'undefined' && availableItems.length > 0) {
            const foundItem = availableItems.find(i => i.name === itemName);
            if (foundItem) {
                itemPicture = foundItem.picture;
            }
        }
        
        return {
            id: Date.now() + Math.random(), 
            itemId: Date.now() + Math.random(), 
            name: itemName,
            price: itemPrice,
            picture: itemPicture
        };
    });
    
    updateSelectedItemsDisplay();
    if (elements.itemsSection) elements.itemsSection.classList.remove('hidden');
    
    elements.purchaseModal.classList.remove('hidden');
}

function closePurchaseModal() {
    if (elements.purchaseModal) elements.purchaseModal.classList.add('hidden');
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
            picture: item.picture
        }))
    };
    
    const purchaseId = elements.purchaseId ? elements.purchaseId.value : '';
    const url = purchaseId ? `${PSM_PURCHASES_API}/${purchaseId}` : `${PSM_PURCHASES_API}`;
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
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            credentials: 'include',
            body: JSON.stringify(data)
        });
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
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
    
    elements.cancelPurchaseContent.innerHTML = `
        <div class="space-y-3">
            <div class="bg-blue-50 p-3 rounded-lg">
                <h4 class="font-semibold text-blue-800">Purchase Details</h4>
                <p><strong>PO Number:</strong> ${purchase.pur_id}</p>
                <p><strong>Company:</strong> ${purchase.pur_company_name}</p>
                <p><strong>Ordered By:</strong> ${purchase.pur_order_by || 'Not specified'}</p>
                <p><strong>Total Amount:</strong> ${formatCurrency(purchase.pur_total_amount)}</p>
            </div>
            <div class="bg-red-50 p-3 rounded-lg">
                <h4 class="font-semibold text-red-800">Warning</h4>
                <p>Are you sure you want to cancel purchase order "${purchase.pur_id}"? This action cannot be undone.</p>
            </div>
        </div>
    `;
    
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
        const response = await fetch(`${PSM_PURCHASES_API}/${purchaseId}/cancel`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            credentials: 'include',
            body: JSON.stringify({ cancel_by: cancelBy })
        });
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        const result = await response.json();
        
        if (result.success) {
            showNotification(result.message, 'success');
            loadPurchases();
            
            const cancelBtn = document.querySelector(`[onclick="cancelPurchase(${purchaseId})"]`);
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
        text: `Are you sure you want to delete purchase order "${purchase.pur_id}"? This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    });
    
    if (!confirmResult.isConfirmed) return;
    
    showLoading();
    
    try {
        const response = await fetch(`${PSM_PURCHASES_API}/${id}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            credentials: 'include'
        });
        
        if (!response.ok) {
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
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
        
        // Try to find picture from active vendors if missing in the item itself
        if (!itemPicture) {
            itemPicture = findProductPicture(itemName);
        }
        
        const imageUrl = itemPicture 
            ? (itemPicture.startsWith('http') ? itemPicture : (itemPicture.startsWith('storage/') ? `/${itemPicture}` : (itemPicture.startsWith('/') ? itemPicture : `/storage/${itemPicture}`)))
            : 'https://placehold.co/100x100?text=No+Image';

        return `
        <li class="flex items-center gap-4 py-3 border-b border-gray-100 last:border-0 hover:bg-gray-50 transition-colors rounded-lg px-2">
            <div class="flex-shrink-0 h-12 w-12">
                <img src="${imageUrl}" alt="${itemName}" class="h-12 w-12 rounded-lg object-cover border border-gray-200 bg-white" onerror="this.src='https://placehold.co/100x100?text=No+Image'">
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-sm font-medium text-gray-900 truncate">
                    ${itemName}
                </p>
            </div>
            <div class="inline-flex items-center text-sm font-semibold text-gray-900">
                ${itemPrice}
            </div>
        </li>`;
    }).join('');
    
    const content = `
        <div class="space-y-6">
            <!-- Header Status Banner -->
            <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg border border-gray-100">
                <div>
                    <span class="text-sm text-gray-500 block">Status</span>
                    <span class="inline-flex items-center mt-1 px-2.5 py-0.5 rounded-full text-sm font-medium ${getStatusBadgeClass(purchase.pur_status)}">
                        ${purchase.pur_status}
                    </span>
                </div>
                <div class="text-right">
                    <span class="text-sm text-gray-500 block">Total Amount</span>
                    <span class="text-xl font-bold text-green-600">${formatCurrency(purchase.pur_total_amount)}</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Order Information -->
                <div>
                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Order Information</h4>
                    <dl class="space-y-2">
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600">PO Number</dt>
                            <dd class="text-sm font-medium text-gray-900">${purchase.pur_id}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600">Company</dt>
                            <dd class="text-sm font-medium text-gray-900">${purchase.pur_company_name}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600">Vendor Type</dt>
                            <dd class="text-sm font-medium text-gray-900 capitalize">${purchase.pur_ven_type}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600">Total Units</dt>
                            <dd class="text-sm font-medium text-gray-900">${purchase.pur_unit}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Approval & Tracking -->
                <div>
                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Tracking & Approval</h4>
                    <dl class="space-y-2">
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600">Ordered By</dt>
                            <dd class="text-sm font-medium text-gray-900">${purchase.pur_order_by || 'Not specified'}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600">Approved By</dt>
                            <dd class="text-sm font-medium text-gray-900">${purchase.pur_approved_by || 'Not approved'}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600">Created Date</dt>
                            <dd class="text-sm font-medium text-gray-900">${new Date(purchase.created_at).toLocaleDateString()}</dd>
                        </div>
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-600">Last Updated</dt>
                            <dd class="text-sm font-medium text-gray-900">${new Date(purchase.updated_at).toLocaleDateString()}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Items Section -->
            <div>
                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Items (${items.length})</h4>
                <div class="bg-white border border-gray-200 rounded-lg overflow-hidden">
                    <ul class="divide-y divide-gray-100">
                        ${itemsHtml || '<li class="p-4 text-center text-gray-500">No items found</li>'}
                    </ul>
                </div>
            </div>

            <!-- Description Section -->
            ${purchase.pur_desc ? `
            <div>
                <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3">Description</h4>
                <div class="bg-gray-50 p-4 rounded-lg border border-gray-100 text-sm text-gray-700">
                    ${purchase.pur_desc}
                </div>
            </div>
            ` : ''}
            
            <!-- System Info -->
            <div class="pt-4 border-t border-gray-100">
                 <p class="text-xs text-gray-400">
                    Source: ${purchase.pur_department_from || 'Logistics 1'} &bull; ${purchase.pur_module_from || 'Procurement & Sourcing Management'} &bull; ${purchase.pur_submodule_from || 'Purchase Management'}
                 </p>
            </div>
        </div>
    `;
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
window.openBudgetApproval = openBudgetApproval;
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
    const mm = dt.getMonth() + 1;
    const dd = dt.getDate();
    const yyyy = dt.getFullYear();
    return `${mm}-${dd}-${yyyy}`;
}

function formatDateRange(a, b) {
    const A = formatDate(a);
    const B = formatDate(b);
    if (!A && !B) return '';
    if (A && B) return `${A} to ${B}`;
    return A || B;
}

const Toast = Swal.mixin({ 
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

function showNotification(message, type = 'info') { 
    Toast.fire({ 
        icon: type === 'success' ? 'success' : type === 'error' ? 'error' : type === 'warning' ? 'warning' : 'info', 
        title: message 
    }); 
}
</script>
