<!-- resources/views/psm/vendor-management.blade.php -->
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bxs-user-detail'></i>Vendor Management</h2>
    </div>
    <div class="text-right">
        <span class="text-md text-gray-600">Procurement & Sourcing Management</span>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg p-6">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">Vendor List</h3>
        <button id="addVendorBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
            <i class='bx bx-plus'></i>
            Add Vendor
        </button>
    </div>

    <!-- Filters and Search Section -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Search -->
        <div class="md:col-span-2">
            <input type="text" id="searchInput" placeholder="Search vendors by company, contact, or email..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        
        <!-- Status Filter -->
        <div>
            <select id="statusFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
        </div>
        
        <!-- Type Filter -->
        <div>
            <select id="typeFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Types</option>
                <option value="equipment">Equipment</option>
                <option value="supplies">Supplies</option>
                <option value="furniture">Furniture</option>
                <option value="automotive">Automotive</option>
            </select>
        </div>
    </div>

    <!-- Vendors Table -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Vendor ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Company</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Contact Person</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rating</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="vendorsTableBody" class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                            <div class="flex justify-center items-center py-4">
                                <div class="loading loading-spinner mr-3"></div>
                                Loading vendors...
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add/Edit Vendor Modal -->
<div id="vendorModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 id="modalTitle" class="text-xl font-semibold">Add Vendor</h3>
            <button id="closeModal" class="text-gray-500 hover:text-gray-700">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>
        
        <form id="vendorForm">
            <input type="hidden" id="vendorId">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="ven_company_name" class="block text-sm font-medium text-gray-700 mb-1">Company Name *</label>
                    <input type="text" id="ven_company_name" name="ven_company_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label for="ven_contact_person" class="block text-sm font-medium text-gray-700 mb-1">Contact Person *</label>
                    <input type="text" id="ven_contact_person" name="ven_contact_person" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="ven_email" class="block text-sm font-medium text-gray-700 mb-1">Email *</label>
                    <input type="email" id="ven_email" name="ven_email" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                
                <div>
                    <label for="ven_phone" class="block text-sm font-medium text-gray-700 mb-1">Phone *</label>
                    <input type="text" id="ven_phone" name="ven_phone" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            
            <div class="mb-4">
                <label for="ven_address" class="block text-sm font-medium text-gray-700 mb-1">Address *</label>
                <textarea id="ven_address" name="ven_address" required rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                <div>
                    <label for="ven_type" class="block text-sm font-medium text-gray-700 mb-1">Vendor Type *</label>
                    <select id="ven_type" name="ven_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Type</option>
                        <option value="equipment">Equipment</option>
                        <option value="supplies">Supplies</option>
                        <option value="furniture">Furniture</option>
                        <option value="automotive">Automotive</option>
                    </select>
                </div>
                
                <div>
                    <label for="ven_rating" class="block text-sm font-medium text-gray-700 mb-1">Rating</label>
                    <select id="ven_rating" name="ven_rating" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="1">1 Star</option>
                        <option value="2">2 Stars</option>
                        <option value="3" selected>3 Stars</option>
                        <option value="4">4 Stars</option>
                        <option value="5">5 Stars</option>
                    </select>
                </div>
                
                <div>
                    <label for="ven_status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="ven_status" name="ven_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="active" selected>Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            
            <div class="mb-4">
                <label for="ven_desc" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea id="ven_desc" name="ven_desc" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Optional vendor description..."></textarea>
            </div>
            
            <div class="flex justify-end gap-3">
                <button type="button" id="cancelModal" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                <button type="submit" id="saveVendorBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">Save Vendor</button>
            </div>
        </form>
    </div>
</div>

<div id="viewVendorModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Vendor Details</h3>
            <button id="closeViewVendorModal" class="text-gray-500 hover:text-gray-700">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>
        <div id="viewVendorContent" class="space-y-2"></div>
    </div>
    </div>

<div id="productModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50" style="z-index: 60">
    <div class="bg-white rounded-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 id="productModalTitle" class="text-xl font-semibold">Add Product</h3>
            <button id="closeProductModal" class="text-gray-500 hover:text-gray-700">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>
        <form id="productForm">
            <input type="hidden" id="productId">
            <input type="hidden" id="prod_vendor" name="prod_vendor">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="prod_name" class="block text-sm font-medium text-gray-700 mb-1">Product Name *</label>
                    <input type="text" id="prod_name" name="prod_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="prod_price" class="block text-sm font-medium text-gray-700 mb-1">Price *</label>
                    <div class="flex items-center border border-gray-300 rounded-lg">
                        <span class="px-3 text-gray-700">₱</span>
                        <input type="number" step="0.01" id="prod_price" name="prod_price" required class="w-full px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 border-0">
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="prod_stock" class="block text-sm font-medium text-gray-700 mb-1">Stock *</label>
                    <input type="number" id="prod_stock" name="prod_stock" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="prod_type" class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
                    <select id="prod_type" name="prod_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Type</option>
                        <option value="equipment">Equipment</option>
                        <option value="supplies">Supplies</option>
                        <option value="furniture">Furniture</option>
                        <option value="automotive">Automotive</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label for="prod_warranty" class="block text-sm font-medium text-gray-700 mb-1">Warranty</label>
                    <input type="text" id="prod_warranty" name="prod_warranty" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="prod_expiration" class="block text-sm font-medium text-gray-700 mb-1">Expiration</label>
                    <input type="date" id="prod_expiration" name="prod_expiration" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            <div class="mb-4">
                <label for="prod_desc" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea id="prod_desc" name="prod_desc" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Optional product description..."></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" id="cancelProductModal" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                <button type="submit" id="saveProductBtn" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">Save Product</button>
            </div>
        </form>
    </div>
</div>
<div id="viewProductsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-3xl max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Vendor Products</h3>
            <div class="flex items-center gap-3">
                <button id="addProductBtn" class="bg-orange-600 hover:bg-orange-700 text-white px-3 py-2 rounded-lg flex items-center gap-2 transition-colors">
                    <i class='bx bx-plus'></i>
                    Add Product
                </button>
                <button id="closeViewProductsModal" class="text-gray-500 hover:text-gray-700">
                    <i class='bx bx-x text-2xl'></i>
                </button>
            </div>
        </div>
        <div id="viewProductsContent" class="space-y-2"></div>
    </div>
    </div>

<script>
// API Configuration
var API_BASE_URL = typeof API_BASE_URL !== 'undefined' ? API_BASE_URL : '<?php echo url('/api/v1'); ?>';
var PSM_VENDORS_API = typeof PSM_VENDORS_API !== 'undefined' ? PSM_VENDORS_API : `${API_BASE_URL}/psm/vendor-management`;
var CSRF_TOKEN = typeof CSRF_TOKEN !== 'undefined' ? CSRF_TOKEN : document.querySelector('meta[name="csrf-token"]').getAttribute('content');
var JWT_TOKEN = typeof JWT_TOKEN !== 'undefined' ? JWT_TOKEN : localStorage.getItem('jwt');

var currentVendors = typeof currentVendors !== 'undefined' ? currentVendors : [];

// DOM Elements
const elements = {
    vendorsTableBody: document.getElementById('vendorsTableBody'),
    noVendorsMessage: document.getElementById('noVendorsMessage'),
    searchInput: document.getElementById('searchInput'),
    statusFilter: document.getElementById('statusFilter'),
    typeFilter: document.getElementById('typeFilter'),
    addVendorBtn: document.getElementById('addVendorBtn'),
    addFirstVendorBtn: document.getElementById('addFirstVendorBtn'),
    vendorModal: document.getElementById('vendorModal'),
    vendorForm: document.getElementById('vendorForm'),
    modalTitle: document.getElementById('modalTitle'),
    vendorId: document.getElementById('vendorId'),
    closeModal: document.getElementById('closeModal'),
    cancelModal: document.getElementById('cancelModal'),
    saveVendorBtn: document.getElementById('saveVendorBtn'),
    statsSection: document.getElementById('statsSection')
};

function initVendorManagement() {
    console.log('🚀 Vendor Management Initialized');
    console.log('API Base URL:', API_BASE_URL);
    console.log('Vendors API:', PSM_VENDORS_API);
    initializeEventListeners();
    loadVendors();
    loadStats();
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initVendorManagement);
} else {
    initVendorManagement();
}

function initializeEventListeners() {
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const typeFilter = document.getElementById('typeFilter');
    const addVendorBtn = document.getElementById('addVendorBtn');
    const addFirstVendorBtn = document.getElementById('addFirstVendorBtn');
    const closeModal = document.getElementById('closeModal');
    const cancelModal = document.getElementById('cancelModal');
    const vendorForm = document.getElementById('vendorForm');
    const vendorModal = document.getElementById('vendorModal');
    const viewVendorModal = document.getElementById('viewVendorModal');
    const closeViewVendorModalBtn = document.getElementById('closeViewVendorModal');
    const viewProductsModal = document.getElementById('viewProductsModal');
    const closeViewProductsModalBtn = document.getElementById('closeViewProductsModal');
    const closeProductModalBtn = document.getElementById('closeProductModal');
    const cancelProductModalBtn = document.getElementById('cancelProductModal');
    const productForm = document.getElementById('productForm');

    if (searchInput) searchInput.addEventListener('input', debounce(loadVendors, 500));
    if (statusFilter) statusFilter.addEventListener('change', loadVendors);
    if (typeFilter) typeFilter.addEventListener('change', loadVendors);
    if (addVendorBtn) addVendorBtn.addEventListener('click', openAddModal);
    if (addFirstVendorBtn) addFirstVendorBtn.addEventListener('click', openAddModal);
    if (closeModal) closeModal.addEventListener('click', closeVendorModal);
    if (cancelModal) cancelModal.addEventListener('click', closeVendorModal);
    if (vendorForm) vendorForm.addEventListener('submit', handleVendorSubmit);
    
    // Close modal when clicking outside
    if (vendorModal) {
        vendorModal.addEventListener('click', function(e) {
            if (e.target === vendorModal) {
                closeVendorModal();
            }
        });
    }
    if (closeViewVendorModalBtn) closeViewVendorModalBtn.addEventListener('click', closeViewVendorModal);
    if (viewVendorModal) {
        viewVendorModal.addEventListener('click', function(e) {
            if (e.target === viewVendorModal) {
                closeViewVendorModal();
            }
        });
    }
    if (closeViewProductsModalBtn) closeViewProductsModalBtn.addEventListener('click', closeViewProductsModal);
    if (viewProductsModal) {
        viewProductsModal.addEventListener('click', function(e) {
            if (e.target === viewProductsModal) {
                closeViewProductsModal();
            }
        });
    }
    if (closeProductModalBtn) closeProductModalBtn.addEventListener('click', closeProductModal);
    if (cancelProductModalBtn) cancelProductModalBtn.addEventListener('click', closeProductModal);
    if (productForm) productForm.addEventListener('submit', handleProductSubmit);
}

async function loadVendors() {
    showLoading();
    
    const params = new URLSearchParams();
    if (elements.searchInput.value) params.append('search', elements.searchInput.value);
    if (elements.statusFilter.value) params.append('status', elements.statusFilter.value);
    if (elements.typeFilter.value) params.append('type', elements.typeFilter.value);
    
    try {
        const vendorsUrl = `${PSM_VENDORS_API}`;
        console.log('📡 Fetching vendors from:', `${vendorsUrl}?${params}`);
        
        const response = await fetch(`${vendorsUrl}?${params}`, {
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
        console.log('📊 Vendors response:', result);
        
        if (result.success) {
            currentVendors = result.data || [];
            displayVendors(currentVendors);
        } else {
            throw new Error(result.message || 'Failed to load vendors');
        }
        
    } catch (error) {
        console.error('❌ Error loading vendors:', error);
        showNotification('Error loading vendors: ' + error.message, 'error');
        displayVendors([]);
    } finally {
        hideLoading();
    }
}

async function loadStats() {
    try {
        const response = await fetch(`${PSM_VENDORS_API}/stats`, {
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

function displayVendors(vendors) {
    if (!vendors || vendors.length === 0) {
        elements.vendorsTableBody.innerHTML = `
            <tr>
                <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                    <i class='bx bx-package text-4xl text-gray-300 mb-3'></i>
                    <p class="text-lg">No vendors found</p>
                    <p class="text-sm text-gray-400 mt-1">Try adjusting your search or filters</p>
                </td>
            </tr>
        `;
        if (elements.noVendorsMessage) elements.noVendorsMessage.classList.remove('hidden');
        return;
    }
    
    if (elements.noVendorsMessage) elements.noVendorsMessage.classList.add('hidden');
    
    const vendorsHtml = vendors.map(vendor => `
        <tr class="hover:bg-gray-50 transition-colors" data-vendor-id="${vendor.id}">
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="text-sm font-mono text-gray-900 bg-gray-100 px-2 py-1 rounded">${vendor.ven_id}</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">
                ${vendor.ven_company_name}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${vendor.ven_contact_person}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                <a href="mailto:${vendor.ven_email}" class="text-blue-600 hover:text-blue-900 underline">
                    ${vendor.ven_email}
                </a>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 capitalize">
                    <i class='bx bx-category mr-1'></i>
                    ${vendor.ven_type}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                <div class="flex items-center">
                    ${Array.from({length: 5}, (_, i) => `
                        <i class='bx ${i < vendor.ven_rating ? 'bxs-star text-yellow-400' : 'bx-star text-gray-300'} text-lg'></i>
                    `).join('')}
                    <span class="ml-2 text-xs text-gray-500">(${vendor.ven_rating}/5)</span>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${
                    vendor.ven_status === 'active' 
                        ? 'bg-green-100 text-green-800' 
                        : 'bg-red-100 text-red-800'
                }">
                    <i class='bx ${vendor.ven_status === 'active' ? 'bx-check-circle' : 'bx-x-circle'} mr-1'></i>
                    ${vendor.ven_status}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex items-center space-x-2">
                    <button onclick="viewVendor(${vendor.id})" 
                            class="text-gray-700 hover:text-gray-900 transition-colors p-2 rounded-lg hover:bg-gray-50"
                            title="View Vendor">
                        <i class='bx bx-show-alt text-xl'></i>
                    </button>
                    <button onclick="editVendor(${vendor.id})" 
                            class="text-blue-600 hover:text-blue-900 transition-colors p-2 rounded-lg hover:bg-blue-50"
                            title="Edit Vendor">
                        <i class='bx bx-edit-alt text-xl'></i>
                    </button>
                    <button onclick="deleteVendor(${vendor.id})" 
                            class="text-red-600 hover:text-red-900 transition-colors p-2 rounded-lg hover:bg-red-50"
                            title="Delete Vendor">
                        <i class='bx bx-trash text-xl'></i>
                    </button>
                    <button onclick="viewVendorProducts('${vendor.ven_id}')" 
                            class="text-orange-600 hover:text-orange-900 transition-colors p-2 rounded-lg hover:bg-orange-50"
                            title="View Products">
                        <i class='bx bx-list-ul text-xl'></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
    
    elements.vendorsTableBody.innerHTML = vendorsHtml;
}

function displayStats(stats) {
    if (!stats) return;
    
    elements.statsSection.innerHTML = `
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-blue-500 rounded-lg">
                    <i class='bx bx-buildings text-white text-2xl'></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-blue-700">Total Vendors</p>
                    <p class="text-2xl font-bold text-blue-900">${stats.total_vendors || 0}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 rounded-lg p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-green-500 rounded-lg">
                    <i class='bx bx-check-circle text-white text-2xl'></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-green-700">Active Vendors</p>
                    <p class="text-2xl font-bold text-green-900">${stats.active_vendors || 0}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-purple-50 to-purple-100 border border-purple-200 rounded-lg p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-purple-500 rounded-lg">
                    <i class='bx bx-category text-white text-2xl'></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-purple-700">Vendor Types</p>
                    <p class="text-2xl font-bold text-purple-900">${Object.keys(stats.vendors_by_type || {}).length}</p>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-orange-50 to-orange-100 border border-orange-200 rounded-lg p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-orange-500 rounded-lg">
                    <i class='bx bx-cube-alt text-white text-2xl'></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-orange-700">Total Products</p>
                    <p class="text-2xl font-bold text-orange-900">${stats.total_products || 0}</p>
                </div>
            </div>
        </div>
    `;
}

// Modal Functions
function openAddModal() {
    elements.modalTitle.textContent = 'Add New Vendor';
    elements.vendorId.value = '';
    elements.vendorForm.reset();
    elements.vendorModal.classList.remove('hidden');
}

function openEditModal(vendor) {
    elements.modalTitle.textContent = 'Edit Vendor';
    elements.vendorId.value = vendor.id;
    document.getElementById('ven_company_name').value = vendor.ven_company_name;
    document.getElementById('ven_contact_person').value = vendor.ven_contact_person;
    document.getElementById('ven_email').value = vendor.ven_email;
    document.getElementById('ven_phone').value = vendor.ven_phone;
    document.getElementById('ven_address').value = vendor.ven_address;
    document.getElementById('ven_type').value = vendor.ven_type;
    document.getElementById('ven_rating').value = vendor.ven_rating;
    document.getElementById('ven_status').value = vendor.ven_status;
    document.getElementById('ven_desc').value = vendor.ven_desc || '';
    elements.vendorModal.classList.remove('hidden');
}

function closeVendorModal() {
    elements.vendorModal.classList.add('hidden');
}

function viewVendor(id) {
    const vendor = currentVendors.find(v => v.id == id);
    if (!vendor) return;
    const content = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><span class="text-sm text-gray-500">Vendor ID</span><p class="font-semibold">${vendor.ven_id}</p></div>
            <div><span class="text-sm text-gray-500">Company</span><p class="font-semibold">${vendor.ven_company_name}</p></div>
            <div><span class="text-sm text-gray-500">Contact</span><p class="font-semibold">${vendor.ven_contact_person}</p></div>
            <div><span class="text-sm text-gray-500">Email</span><p class="font-semibold">${vendor.ven_email}</p></div>
            <div><span class="text-sm text-gray-500">Phone</span><p class="font-semibold">${vendor.ven_phone}</p></div>
            <div><span class="text-sm text-gray-500">Type</span><p class="font-semibold capitalize">${vendor.ven_type}</p></div>
            <div><span class="text-sm text-gray-500">Rating</span><p class="font-semibold">${vendor.ven_rating}</p></div>
            <div><span class="text-sm text-gray-500">Status</span><p class="font-semibold capitalize">${vendor.ven_status}</p></div>
        </div>
        <div class="mt-4">
            <span class="text-sm text-gray-500">Address</span>
            <p class="font-semibold">${vendor.ven_address}</p>
        </div>
        <div class="mt-4">
            <span class="text-sm text-gray-500">Description</span>
            <p class="font-semibold">${vendor.ven_desc || ''}</p>
        </div>
    `;
    const container = document.getElementById('viewVendorContent');
    if (container) container.innerHTML = content;
    const modal = document.getElementById('viewVendorModal');
    if (modal) modal.classList.remove('hidden');
}

function closeViewVendorModal() {
    const modal = document.getElementById('viewVendorModal');
    if (modal) modal.classList.add('hidden');
}

async function viewVendorProducts(venId) {
    const modal = document.getElementById('viewProductsModal');
    const container = document.getElementById('viewProductsContent');
    if (container) container.innerHTML = '';
    try {
        const response = await fetch(`${API_BASE_URL}/psm/product-management/by-vendor/${venId}`, {
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
        const products = result.data || [];
        const html = products.length === 0
            ? `<div class=\"text-center text-gray-500\">No products found for this vendor.</div>`
            : `<div class=\"overflow-x-auto\"><table class=\"min-w-full divide-y divide-gray-200\">\n                <thead class=\"bg-gray-50\">\n                    <tr>\n                        <th class=\"px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider\">Product</th>\n                        <th class=\"px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider\">Price</th>\n                        <th class=\"px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider\">Stock</th>\n                        <th class=\"px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider\">Type</th>\n                        <th class=\"px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider\">Actions</th>\n                    </tr>\n                </thead>\n                <tbody class=\"bg-white divide-y divide-gray-200\">\n                    ${products.map(p => `\n                        <tr>\n                            <td class=\"px-6 py-4\">${p.prod_name}</td>\n                            <td class=\"px-6 py-4\">${formatCurrency(p.prod_price)}</td>\n                            <td class=\"px-6 py-4\">${p.prod_stock}</td>\n                            <td class=\"px-6 py-4 capitalize\">${p.prod_type}</td>\n                            <td class=\"px-6 py-4\">\n                                <button class=\"text-blue-600 hover:text-blue-900 transition-colors p-2 rounded-lg hover:bg-blue-50\" onclick=\"editProduct(${p.id})\" title=\"Edit Product\">\n                                    <i class='bx bx-edit-alt text-xl'></i>\n                                </button>\n                                <button class=\"text-red-600 hover:text-red-900 transition-colors p-2 rounded-lg hover:bg-red-50\" onclick=\"deleteProduct(${p.id})\" title=\"Delete Product\">\n                                    <i class='bx bx-trash text-xl'></i>\n                                </button>\n                            </td>\n                        </tr>\n                    `).join('')}\n                </tbody>\n            </table></div>`;
        if (container) container.innerHTML = html;
        if (modal) modal.classList.remove('hidden');
        const addBtn = document.getElementById('addProductBtn');
        if (addBtn) { addBtn.onclick = function() { openAddProductModal(venId); }; }
    } catch (error) {
        showNotification('Error loading products: ' + error.message, 'error');
    }
}

function closeViewProductsModal() {
    const modal = document.getElementById('viewProductsModal');
    if (modal) modal.classList.add('hidden');
}

function openAddProductModal(venId) {
    document.getElementById('productModalTitle').textContent = 'Add Product';
    document.getElementById('productId').value = '';
    document.getElementById('prod_vendor').value = venId;
    document.getElementById('productForm').reset();
    document.getElementById('productModal').classList.remove('hidden');
}

function openEditProductModal(product) {
    document.getElementById('productModalTitle').textContent = 'Edit Product';
    document.getElementById('productId').value = product.id;
    document.getElementById('prod_vendor').value = product.prod_vendor;
    document.getElementById('prod_name').value = product.prod_name;
    document.getElementById('prod_price').value = product.prod_price;
    document.getElementById('prod_stock').value = product.prod_stock;
    document.getElementById('prod_type').value = product.prod_type;
    document.getElementById('prod_warranty').value = product.prod_warranty || '';
    document.getElementById('prod_expiration').value = product.prod_expiration ? product.prod_expiration.substring(0,10) : '';
    document.getElementById('prod_desc').value = product.prod_desc || '';
    document.getElementById('productModal').classList.remove('hidden');
}

function closeProductModal() {
    document.getElementById('productModal').classList.add('hidden');
}

async function handleProductSubmit(e) {
    e.preventDefault();
    const form = document.getElementById('productForm');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);
    data.prod_price = parseFloat(data.prod_price);
    data.prod_stock = parseInt(data.prod_stock);
    const id = document.getElementById('productId').value;
    const url = id ? `${API_BASE_URL}/psm/product-management/${id}` : `${API_BASE_URL}/psm/product-management`;
    const method = id ? 'PUT' : 'POST';
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
            closeProductModal();
            const venId = data.prod_vendor;
            await viewVendorProducts(venId);
        } else {
            throw new Error(result.message || 'Failed to save product');
        }
    } catch (error) {
        showNotification('Error saving product: ' + error.message, 'error');
    }
}

async function editProduct(id) {
    try {
        const response = await fetch(`${API_BASE_URL}/psm/product-management`, {
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
        const product = (result.data || []).find(p => p.id == id);
        if (!product) return;
        openEditProductModal(product);
    } catch (error) {
        showNotification('Error loading product: ' + error.message, 'error');
    }
}

async function deleteProduct(id) {
    const confirmResult = await Swal.fire({
        title: 'Delete Product?',
        text: 'Are you sure you want to delete this product?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    });
    if (!confirmResult.isConfirmed) return;
    try {
        const response = await fetch(`${API_BASE_URL}/psm/product-management/${id}`, {
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
            const venIdField = document.getElementById('prod_vendor');
            const venId = venIdField && venIdField.value ? venIdField.value : null;
            if (venId) await viewVendorProducts(venId);
        } else {
            throw new Error(result.message || 'Failed to delete product');
        }
    } catch (error) {
        showNotification('Error deleting product: ' + error.message, 'error');
    }
}

function formatCurrency(value) {
    const num = Number(value || 0);
    return '₱' + num.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

// Form Handling
async function handleVendorSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(elements.vendorForm);
    const data = Object.fromEntries(formData);
    data.ven_rating = parseInt(data.ven_rating);
    
    const vendorId = elements.vendorId.value;
    const url = vendorId ? `${PSM_VENDORS_API}/${vendorId}` : `${PSM_VENDORS_API}`;
    const method = vendorId ? 'PUT' : 'POST';
    
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
            closeVendorModal();
            loadVendors();
            loadStats();
        } else {
            throw new Error(result.message || 'Failed to save vendor');
        }
        
    } catch (error) {
        console.error('Error saving vendor:', error);
        showNotification('Error saving vendor: ' + error.message, 'error');
    } finally {
        hideLoading();
    }
}

function editVendor(id) {
    const vendor = currentVendors.find(v => v.id == id);
    if (vendor) {
        openEditModal(vendor);
    }
}

async function deleteVendor(id) {
    const vendor = currentVendors.find(v => v.id == id);
    if (!vendor) return;
    const confirmResult = await Swal.fire({
        title: 'Delete Vendor?',
        text: `Are you sure you want to delete "${vendor.ven_company_name}"? This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel',
        reverseButtons: true
    });
    if (!confirmResult.isConfirmed) return;
    
    showLoading();
    
    try {
        const response = await fetch(`${PSM_VENDORS_API}/${id}`, {
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
            loadVendors();
            loadStats();
        } else {
            throw new Error(result.message || 'Failed to delete vendor');
        }
        
    } catch (error) {
        console.error('Error deleting vendor:', error);
        showNotification('Error deleting vendor: ' + error.message, 'error');
    } finally {
        hideLoading();
    }
}

window.viewVendor = viewVendor;
window.editVendor = editVendor;
window.deleteVendor = deleteVendor;
window.viewVendorProducts = viewVendorProducts;
window.editProduct = editProduct;
window.deleteProduct = deleteProduct;

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

const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true, didOpen: (toast) => { toast.onmouseenter = Swal.stopTimer; toast.onmouseleave = Swal.resumeTimer; } });
function showNotification(message, type = 'info') { Toast.fire({ icon: type === 'success' ? 'success' : type === 'error' ? 'error' : 'info', title: message }); }
</script>