<!-- resources/views/psm/vendor-management.blade.php -->
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bxs-user-detail'></i>Logistics Vendor</h2>
    </div>
    <div class="text-right">
        <span class="text-md text-gray-600">Procurement & Sourcing Management</span>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg p-6">
    <!-- Filters and Search Section -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
        <!-- Search -->
        <div class="md:col-span-2 relative group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class='bx bx-search text-gray-400 text-xl group-hover:text-blue-500 transition-colors'></i>
            </div>
            <input type="text" id="searchInput" placeholder="Search vendors by company, contact, or email..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white hover:border-blue-300 transition-colors text-gray-700">
        </div>
        
        <!-- Status Filter -->
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class='bx bx-toggle-left text-gray-400 text-xl group-hover:text-blue-500 transition-colors'></i>
            </div>
            <select id="statusFilter" class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white hover:border-blue-300 transition-colors cursor-pointer text-gray-700">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
            </select>
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <i class='bx bx-chevron-down text-gray-400 group-hover:text-blue-500 transition-colors'></i>
            </div>
        </div>
        
        <!-- Type Filter -->
        <div class="relative group">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class='bx bx-category text-gray-400 text-xl group-hover:text-blue-500 transition-colors'></i>
            </div>
            <select id="typeFilter" class="w-full pl-10 pr-10 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white hover:border-blue-300 transition-colors cursor-pointer text-gray-700">
                <option value="">All Types</option>
                <option value="equipment">Equipment</option>
                <option value="supplies">Supplies</option>
                <option value="furniture">Furniture</option>
                <option value="automotive">Automotive</option>
            </select>
            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <i class='bx bx-chevron-down text-gray-400 group-hover:text-blue-500 transition-colors'></i>
            </div>
        </div>
    </div>

    <!-- Vendors Grid -->
    <div id="vendorsGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <!-- Cards will be populated here -->
    </div>
    <div id="vendorsPager" class="flex items-center justify-between mt-3">
        <div id="vendorsPagerInfo" class="text-sm text-gray-600"></div>
        <div class="join">
            <button class="btn btn-sm join-item" id="vendorsPrevBtn" data-action="prev">Prev</button>
            <span class="btn btn-sm join-item" id="vendorsPageDisplay">1 / 1</span>
            <button class="btn btn-sm join-item" id="vendorsNextBtn" data-action="next">Next</button>
        </div>
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
var APP_URL = typeof APP_URL !== 'undefined' ? APP_URL : '<?php echo url('/'); ?>';
var PSM_VENDORS_API = typeof PSM_VENDORS_API !== 'undefined' ? PSM_VENDORS_API : `${API_BASE_URL}/psm/vendor-management`;
var CSRF_TOKEN = typeof CSRF_TOKEN !== 'undefined' ? CSRF_TOKEN : document.querySelector('meta[name="csrf-token"]').getAttribute('content');
var JWT_TOKEN = typeof JWT_TOKEN !== 'undefined' ? JWT_TOKEN : localStorage.getItem('jwt');

var currentVendors = typeof currentVendors !== 'undefined' ? currentVendors : [];
let currentVendorsPage = 1;
const vendorsPageSize = 4;

// DOM Elements
const elements = {
    vendorsGrid: document.getElementById('vendorsGrid'),
    noVendorsMessage: document.getElementById('noVendorsMessage'),
    searchInput: document.getElementById('searchInput'),
    statusFilter: document.getElementById('statusFilter'),
    typeFilter: document.getElementById('typeFilter')
};

function initVendorManagement() {
    console.log('🚀 Vendor Management Initialized');
    console.log('API Base URL:', API_BASE_URL);
    console.log('Vendors API:', PSM_VENDORS_API);
    initializeEventListeners();
    loadVendors();
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
    const viewVendorModal = document.getElementById('viewVendorModal');
    const closeViewVendorModalBtn = document.getElementById('closeViewVendorModal');
    const viewProductsModal = document.getElementById('viewProductsModal');
    const closeViewProductsModalBtn = document.getElementById('closeViewProductsModal');
    const closeProductModalBtn = document.getElementById('closeProductModal');
    const cancelProductModalBtn = document.getElementById('cancelProductModal');
    const productForm = document.getElementById('productForm');

    if (searchInput) searchInput.addEventListener('input', debounce(function(){ currentVendorsPage = 1; loadVendors(); }, 500));
    if (statusFilter) statusFilter.addEventListener('change', function(){ currentVendorsPage = 1; loadVendors(); });
    if (typeFilter) typeFilter.addEventListener('change', function(){ currentVendorsPage = 1; loadVendors(); });
    
    // Close modal when clicking outside
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
            // Sort by id descending (newest first)
            currentVendors.sort((a, b) => b.id - a.id);
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

function displayVendors(vendors) {
    const filtered = getVendorsFiltered(vendors);
    const total = filtered.length;
    if (!filtered || total === 0) {
        elements.vendorsGrid.innerHTML = `
            <div class="col-span-full text-center py-12">
                <i class='bx bx-package text-6xl text-gray-200 mb-4'></i>
                <p class="text-xl text-gray-500 font-medium">No vendors found</p>
                <p class="text-sm text-gray-400 mt-2">Try adjusting your search or filters</p>
            </div>
        `;
        if (elements.noVendorsMessage) elements.noVendorsMessage.classList.remove('hidden');
        renderVendorsPager(0, 1);
        return;
    }
    if (elements.noVendorsMessage) elements.noVendorsMessage.classList.add('hidden');
    const totalPages = Math.max(1, Math.ceil(total / vendorsPageSize));
    if (currentVendorsPage > totalPages) currentVendorsPage = totalPages;
    if (currentVendorsPage < 1) currentVendorsPage = 1;
    const startIdx = (currentVendorsPage - 1) * vendorsPageSize;
    const pageItems = filtered.slice(startIdx, startIdx + vendorsPageSize);
    
    const bgColors = [
        'bg-blue-50',
        'bg-green-50',
        'bg-purple-50',
        'bg-orange-50',
        'bg-pink-50',
        'bg-teal-50',
        'bg-indigo-50',
        'bg-cyan-50'
    ];

    const vendorsHtml = pageItems.map((vendor, index) => {
        const bgColor = bgColors[index % bgColors.length];
        return `
        <div class="${bgColor} border border-gray-200 rounded-xl p-5 hover:shadow-xl hover:-translate-y-1 transition-all duration-300 group relative overflow-hidden">
            <div class="absolute top-0 right-0 p-3">
                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-bold capitalize shadow-sm ${
                    vendor.ven_status === 'active' 
                        ? 'bg-emerald-200 text-emerald-900 ring-1 ring-emerald-700/20' 
                        : 'bg-rose-200 text-rose-900 ring-1 ring-rose-700/20'
                }">
                    <i class='bx bxs-circle text-[0.5rem] mr-1.5'></i>
                    ${vendor.ven_status}
                </span>
            </div>
            
            <div class="flex flex-col items-center text-center mb-4 mt-2">
                ${vendor.ven_picture 
                    ? `<img src="${APP_URL}/${vendor.ven_picture}" alt="${vendor.ven_company_name}" class="w-16 h-16 rounded-full object-cover mb-3 border-2 border-white group-hover:border-blue-200 transition-colors shadow-sm">`
                    : `<div class="w-16 h-16 bg-white/80 rounded-full flex items-center justify-center mb-3 group-hover:bg-white transition-colors shadow-sm">
                        <i class='bx bx-building-house text-3xl text-gray-400 group-hover:text-blue-500 transition-colors'></i>
                       </div>`
                }
                <h3 class="text-lg font-bold text-gray-900 mb-1 line-clamp-1" title="${vendor.ven_company_name}">${vendor.ven_company_name}</h3>
                <span class="text-xs font-medium text-gray-600 bg-white/60 px-2.5 py-1 rounded-md capitalize mb-2 border border-gray-200/50">
                    ${vendor.ven_type}
                </span>
                <div class="flex items-center gap-1 mb-2">
                    ${Array.from({length: 5}, (_, i) => `
                        <i class='bx ${i < vendor.ven_rating ? 'bxs-star text-amber-400' : 'bx-star text-gray-300'} text-sm'></i>
                    `).join('')}
                    <span class="text-xs text-gray-500 ml-1">(${vendor.ven_rating})</span>
                </div>
            </div>

            <div class="space-y-2 mb-4 text-sm text-left px-1">
                <div class="flex items-center text-gray-700 group-hover:text-gray-900 transition-colors">
                    <div class="w-8 flex justify-center"><i class='bx bx-user w-5 text-gray-500 group-hover:text-blue-600 transition-colors'></i></div>
                    <span class="truncate font-medium" title="${vendor.ven_contact_person}">${vendor.ven_contact_person}</span>
                </div>
                <div class="flex items-center text-gray-700 group-hover:text-gray-900 transition-colors">
                    <div class="w-8 flex justify-center"><i class='bx bx-envelope w-5 text-gray-500 group-hover:text-blue-600 transition-colors'></i></div>
                    <span class="truncate font-medium" title="${vendor.ven_email}">${vendor.ven_email}</span>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-3 mt-auto pt-2">
                <button onclick="viewVendor(${vendor.id})" 
                        title="View details for ${vendor.ven_company_name}"
                        class="flex items-center justify-center px-3 py-2.5 rounded-lg text-sm font-semibold text-white bg-blue-600 hover:bg-blue-700 shadow-sm hover:shadow-md transition-all duration-200 group/btn">
                    <i class='bx bx-show-alt mr-2 text-lg group-hover/btn:scale-110 transition-transform'></i> Details
                </button>
                <button onclick="viewVendorProducts('${vendor.ven_id}')" 
                        title="Manage products for ${vendor.ven_company_name}"
                        class="flex items-center justify-center px-3 py-2.5 rounded-lg text-sm font-semibold text-white bg-orange-500 hover:bg-orange-600 shadow-sm hover:shadow-md transition-all duration-200 group/btn">
                    <i class='bx bx-package mr-2 text-lg group-hover/btn:scale-110 transition-transform'></i> Products
                </button>
            </div>
        </div>
    `;
    }).join('');
    
    elements.vendorsGrid.innerHTML = vendorsHtml;
    renderVendorsPager(total, totalPages);
}

function renderVendorsPager(total, totalPages){
    const info = document.getElementById('vendorsPagerInfo');
    const display = document.getElementById('vendorsPageDisplay');
    const start = total === 0 ? 0 : ((currentVendorsPage - 1) * vendorsPageSize) + 1;
    const end = Math.min(currentVendorsPage * vendorsPageSize, total);
    if (info) info.textContent = `Showing ${start}-${end} of ${total}`;
    if (display) display.textContent = `${currentVendorsPage} / ${totalPages}`;
    const prev = document.getElementById('vendorsPrevBtn');
    const next = document.getElementById('vendorsNextBtn');
    if (prev) prev.disabled = currentVendorsPage <= 1;
    if (next) next.disabled = currentVendorsPage >= totalPages;
}

function getVendorsFiltered(list){
    let vendors = (list || []).slice();
    const q = (elements.searchInput?.value || '').trim().toLowerCase();
    const statusVal = (elements.statusFilter?.value || '').trim().toLowerCase();
    const typeVal = (elements.typeFilter?.value || '').trim().toLowerCase();
    if (statusVal) vendors = vendors.filter(v => (v.ven_status || '').toLowerCase() === statusVal);
    if (typeVal) vendors = vendors.filter(v => (v.ven_type || '').toLowerCase() === typeVal);
    if (q) {
        vendors = vendors.filter(v => {
            const hay = [
                v.ven_company_name || '',
                v.ven_contact_person || '',
                v.ven_email || ''
            ].join(' ').toLowerCase();
            return hay.includes(q);
        });
    }
    return vendors;
}

document.getElementById('vendorsPager').addEventListener('click', function(ev){
    const btn = ev.target.closest('button[data-action]');
    if(!btn) return;
    const act = btn.getAttribute('data-action');
    if(act === 'prev'){ currentVendorsPage = Math.max(1, currentVendorsPage - 1); displayVendors(currentVendors); }
    if(act === 'next'){ const max = Math.max(1, Math.ceil((getVendorsFiltered(currentVendors).length||0)/vendorsPageSize)); currentVendorsPage = Math.min(max, currentVendorsPage + 1); displayVendors(currentVendors); }
});

// Modal Functions
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
    } catch (error) {
        showNotification('Error loading products: ' + error.message, 'error');
    }
}

function closeViewProductsModal() {
    const modal = document.getElementById('viewProductsModal');
    if (modal) modal.classList.add('hidden');
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


window.viewVendor = viewVendor;
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