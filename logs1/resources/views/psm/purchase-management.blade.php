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
        <button id="addPurchaseBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
            <i class='bx bx-plus'></i>
            New Purchase Order
        </button>
    </div>

    <!-- Stats Section -->
    <div id="statsSection" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
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
    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Search -->
        <div class="md:col-span-2">
            <input type="text" id="searchInput" placeholder="Search by PO number, company, or items..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        
        <!-- Status Filter -->
        <div>
            <select id="statusFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="approved">Approved</option>
                <option value="processing">Processing</option>
                <option value="received">Received</option>
                <option value="cancel">Cancelled</option>
                <option value="rejected">Rejected</option>
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
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">PO Number</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Items</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Company</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Units</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="purchasesTableBody" class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
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
    <div class="bg-white rounded-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Purchase Order Details</h3>
            <button id="closeViewPurchaseModal" class="text-gray-500 hover:text-gray-700">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>
        <div id="viewPurchaseContent" class="space-y-4"></div>
    </div>
</div>

<script>
// API Configuration
var API_BASE_URL = typeof API_BASE_URL !== 'undefined' ? API_BASE_URL : '<?php echo url('/api/v1'); ?>';
var PSM_PURCHASES_API = typeof PSM_PURCHASES_API !== 'undefined' ? PSM_PURCHASES_API : `${API_BASE_URL}/psm/purchase-management`;
var PSM_ACTIVE_VENDORS_API = `${API_BASE_URL}/psm/active-vendors`;
var CSRF_TOKEN = typeof CSRF_TOKEN !== 'undefined' ? CSRF_TOKEN : document.querySelector('meta[name="csrf-token"]').getAttribute('content');
var JWT_TOKEN = typeof JWT_TOKEN !== 'undefined' ? JWT_TOKEN : localStorage.getItem('jwt');

var currentPurchases = typeof currentPurchases !== 'undefined' ? currentPurchases : [];
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
    purDesc: document.getElementById('pur_desc')
};

function initPurchaseManagement() {
    console.log('🚀 Purchase Management Initialized');
    console.log('API Base URL:', API_BASE_URL);
    console.log('Purchases API:', PSM_PURCHASES_API);
    initializeEventListeners();
    loadPurchases();
    loadStats();
    loadActiveVendors();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initPurchaseManagement);
} else {
    initPurchaseManagement();
}

function initializeEventListeners() {
    // Search and filters
    if (elements.searchInput) elements.searchInput.addEventListener('input', debounce(loadPurchases, 500));
    if (elements.statusFilter) elements.statusFilter.addEventListener('change', loadPurchases);
    if (elements.vendorTypeFilter) elements.vendorTypeFilter.addEventListener('change', loadPurchases);
    
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
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
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
        itemElement.innerHTML = `
            <div class="flex justify-between items-center">
                <div>
                    <h4 class="font-medium text-gray-900">${item.name}</h4>
                    <p class="text-sm text-gray-600">Price: ${formatCurrency(item.price)} | Stock: ${item.stock}</p>
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
        itemElement.innerHTML = `
            <div class="flex justify-between items-center">
                <div>
                    <h4 class="font-medium text-gray-900">${item.name}</h4>
                    <p class="text-sm text-gray-600">Price: ${formatCurrency(item.price)} | Stock: ${item.stock}</p>
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
        price: item.price
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
                    count: 0,
                    instances: []
                };
            }
            itemCounts[item.itemId].count++;
            itemCounts[item.itemId].instances.push(item.id);
        });
        
        elements.selectedItemsContainer.innerHTML = Object.values(itemCounts).map(group => `
            <div class="flex justify-between items-center p-3 bg-gray-50 rounded-lg border border-gray-200">
                <div>
                    <h4 class="font-medium text-gray-900">${group.name}</h4>
                    <p class="text-sm text-gray-600">
                        ${formatCurrency(group.price)} each × ${group.count} = ${formatCurrency(group.price * group.count)}
                    </p>
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
        `).join('');
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
    if (elements.searchInput && elements.searchInput.value) params.append('search', elements.searchInput.value);
    if (elements.statusFilter && elements.statusFilter.value) params.append('status', elements.statusFilter.value);
    if (elements.vendorTypeFilter && elements.vendorTypeFilter.value) params.append('vendor_type', elements.vendorTypeFilter.value);
    
    try {
        const purchasesUrl = `${PSM_PURCHASES_API}`;
        console.log('📡 Fetching purchases from:', `${purchasesUrl}?${params}`);
        
        const response = await fetch(`${purchasesUrl}?${params}`, {
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

async function loadStats() {
    try {
        const response = await fetch(`${PSM_PURCHASES_API}/stats`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            credentials: 'include'
        });
        
        if (response.ok) {
            const result = await response.json();
            if (result.success) {
                displayStats(result.data);
            }
        }
    } catch (error) {
        console.error('Error loading stats:', error);
    }
}

function displayPurchases(purchases) {
    if (!elements.purchasesTableBody) return;
    
    if (!purchases || purchases.length === 0) {
        elements.purchasesTableBody.innerHTML = `
            <tr>
                <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                    <i class='bx bx-package text-4xl text-gray-300 mb-3'></i>
                    <p class="text-lg">No purchase orders found</p>
                    <p class="text-sm text-gray-400 mt-1">Try adjusting your search or filters</p>
                </td>
            </tr>
        `;
        return;
    }
    
    const purchasesHtml = purchases.map(purchase => {
        const items = Array.isArray(purchase.pur_name_items) ? purchase.pur_name_items : [];
        const itemsString = items.slice(0, 3).map(item => 
            typeof item === 'object' ? item.name : item
        ).join(', ') + (items.length > 3 ? '...' : '');
        
        return `
        <tr class="hover:bg-gray-50 transition-colors" data-purchase-id="${purchase.id}">
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="text-sm font-mono text-gray-900 bg-gray-100 px-2 py-1 rounded">${purchase.pur_id}</span>
            </td>
            <td class="px-6 py-4 text-sm text-gray-900">
                <div class="max-w-xs truncate" title="${items.map(item => typeof item === 'object' ? item.name : item).join(', ')}">
                    ${itemsString}
                </div>
                <small class="text-xs text-gray-500">${items.length} item(s)</small>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                ${purchase.pur_company_name}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 capitalize">
                    <i class='bx bx-category mr-1'></i>
                    ${purchase.pur_ven_type}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                ${purchase.pur_unit}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                ${formatCurrency(purchase.pur_total_amount)}
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
                    <button onclick="editPurchase(${purchase.id})" 
                            class="text-blue-600 hover:text-blue-900 transition-colors p-2 rounded-lg hover:bg-blue-50"
                            title="Edit Purchase">
                        <i class='bx bx-edit-alt text-xl'></i>
                    </button>
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
}

function displayStats(stats) {
    if (!stats || !elements.totalOrders || !elements.approvedOrders || !elements.pendingOrders || !elements.cancelledOrders) return;
    
    elements.totalOrders.textContent = stats.total_purchases || 0;
    elements.approvedOrders.textContent = stats.approved_purchases || 0;
    elements.pendingOrders.textContent = stats.pending_purchases || 0;
    elements.cancelledOrders.textContent = stats.cancelled_purchases || 0;
}

function getStatusBadgeClass(status) {
    const statusClasses = {
        'pending': 'bg-yellow-100 text-yellow-800',
        'approved': 'bg-blue-100 text-blue-800',
        'processing': 'bg-purple-100 text-purple-800',
        'received': 'bg-green-100 text-green-800',
        'cancel': 'bg-red-100 text-red-800',
        'rejected': 'bg-red-100 text-red-800'
    };
    return statusClasses[status] || 'bg-gray-100 text-gray-800';
}

function getStatusIcon(status) {
    const statusIcons = {
        'pending': 'bx-time',
        'approved': 'bx-check-circle',
        'processing': 'bx-cog',
        'received': 'bx-package',
        'cancel': 'bx-x-circle',
        'rejected': 'bx-x-circle'
    };
    return statusIcons[status] || 'bx-question-mark';
}

// Modal Functions
function openAddModal() {
    if (!elements.modalTitle || !elements.purchaseId || !elements.purchaseModal) return;
    
    elements.modalTitle.textContent = 'New Purchase Order';
    elements.purchaseId.value = '';
    if (elements.purchaseForm) elements.purchaseForm.reset();
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
    
    // Set company
    if (elements.companySelect) elements.companySelect.value = purchase.pur_company_name;
    
    // Set vendor type
    if (elements.vendorType) elements.vendorType.value = purchase.pur_ven_type;
    
    // Set items
    const items = Array.isArray(purchase.pur_name_items) ? purchase.pur_name_items : [];
    selectedItems = items.map(item => {
        const itemName = typeof item === 'object' ? item.name : item;
        const itemPrice = typeof item === 'object' ? item.price : 0;
        return {
            id: Date.now() + Math.random(), // Generate temporary ID for existing items
            itemId: Date.now() + Math.random(), // Generate temporary itemId
            name: itemName,
            price: itemPrice
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
    
    const data = {
        pur_company_name: elements.companySelect ? elements.companySelect.value : '',
        pur_ven_type: elements.vendorType ? elements.vendorType.value : '',
        pur_desc: elements.purDesc ? elements.purDesc.value : '',
        pur_name_items: selectedItems.map(item => ({
            name: item.name,
            price: parseFloat(item.price)
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
            loadStats();
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
            loadStats();
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

function viewPurchase(id) {
    const purchase = currentPurchases.find(p => p.id == id);
    if (!purchase) return;
    
    const items = Array.isArray(purchase.pur_name_items) ? purchase.pur_name_items : [];
    const itemsHtml = items.map(item => {
        const itemName = typeof item === 'object' ? item.name : item;
        const itemPrice = typeof item === 'object' ? formatCurrency(item.price) : 'N/A';
        return `<li class="flex justify-between py-2 border-b border-gray-100">
            <span>${itemName}</span>
            <span class="font-semibold">${itemPrice}</span>
        </li>`;
    }).join('');
    
    const content = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><span class="text-sm text-gray-500">PO Number</span><p class="font-semibold">${purchase.pur_id}</p></div>
            <div><span class="text-sm text-gray-500">Company</span><p class="font-semibold">${purchase.pur_company_name}</p></div>
            <div><span class="text-sm text-gray-500">Vendor Type</span><p class="font-semibold capitalize">${purchase.pur_ven_type}</p></div>
            <div><span class="text-sm text-gray-500">Status</span><p class="font-semibold"><span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${getStatusBadgeClass(purchase.pur_status)}">${purchase.pur_status}</span></p></div>
            <div><span class="text-sm text-gray-500">Total Units</span><p class="font-semibold">${purchase.pur_unit}</p></div>
            <div><span class="text-sm text-gray-500">Total Amount</span><p class="font-semibold">${formatCurrency(purchase.pur_total_amount)}</p></div>
            <div><span class="text-sm text-gray-500">Created</span><p class="font-semibold">${new Date(purchase.created_at).toLocaleDateString()}</p></div>
            <div><span class="text-sm text-gray-500">Last Updated</span><p class="font-semibold">${new Date(purchase.updated_at).toLocaleDateString()}</p></div>
        </div>
        <div class="mt-4">
            <span class="text-sm text-gray-500">Items (${items.length})</span>
            <ul class="font-semibold mt-2 bg-gray-50 p-3 rounded-lg">
                ${itemsHtml || '<li class="py-2 text-gray-500">No items</li>'}
            </ul>
        </div>
        <div class="mt-4">
            <span class="text-sm text-gray-500">Description</span>
            <p class="font-semibold mt-1 bg-gray-50 p-3 rounded-lg">${purchase.pur_desc || 'No description provided'}</p>
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