<!-- resources/views/sws/digital-inventory.blade.php -->
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bxs-archive-in'></i>Digital Inventory</h2>
    </div>
    <div class="text-right">
        <span class="text-md text-gray-600">Smart Warehousing System</span>
    </div>
</div>
<!-- digital inventory section start -->
<div class="bg-white rounded-lg shadow-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">Inventory Overview</h3> 
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Stock Levels by Category section start -->
        <div class="inventory-section">
            <h4 class="font-semibold mb-4">Stock Levels by Category</h4>
            <div id="stockLevelsContainer" class="space-y-3">
                <div>
                    <div class="flex justify-between mb-1">
                        <span>Equipment</span>
                        <span>0%</span>
                    </div>
                    <progress class="progress progress-primary w-full" value="0" max="100"></progress>
                </div>
                <div>
                    <div class="flex justify-between mb-1">
                        <span>Supplies</span>
                        <span>0%</span>
                    </div>
                    <progress class="progress progress-warning w-full" value="0" max="100"></progress>
                </div>
                <div>
                    <div class="flex justify-between mb-1">
                        <span>Furniture</span>
                        <span>0%</span>
                    </div>
                    <progress class="progress progress-success w-full" value="0" max="100"></progress>
                </div>
                <div>
                    <div class="flex justify-between mb-1">
                        <span>Automotive</span>
                        <span>0%</span>
                    </div>
                    <progress class="progress progress-secondary w-full" value="0" max="100"></progress>
                </div>
            </div>
        </div>
        <!-- Stock Levels by Category section end -->
        <!-- Quick Actions section start -->
        <div class="inventory-section">
            <h4 class="font-semibold mb-4">Quick Actions</h4>
            <div class="grid grid-cols-2 gap-3">
                <button id="searchItemBtn" class="btn btn-outline">
                    <i class='bx bx-search mr-2'></i>Search Item
                </button>
                <button id="transferBtn" class="btn btn-outline">
                    <i class='bx bx-transfer mr-2'></i>Transfer
                </button>
                <button id="scanBarcodeBtn" class="btn btn-outline">
                    <i class='bx bx-barcode mr-2'></i>Scan Barcode
                </button>
                <button id="generateReportBtn" class="btn btn-outline">
                    <i class='bx bx-report mr-2'></i>Generate Report
                </button>
            </div>
            <hr class="border border-gray-300 mt-5 mb-5"/>
            <div class="flex justify-between">
                <button id="purchaseNewItemBtn" class="btn btn-success px-9">
                    <i class='bx bxs-purchase-tag mr-2'></i>Purchase New Item
                </button>
                <button id="inventoryNewItemBtn" class="btn btn-primary px-9">
                    <i class='bx bxs-down-arrow-square mr-2'></i>Inventory New Item
                </button>
            </div>
        </div>
        <!-- Quick Actions section end -->
    </div>
</div>
<!-- digital inventory main table area start -->
<div class="bg-white rounded-lg shadow-lg p-6 mt-5">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">Inventory</h3>
    </div>
    <!-- stats-card section start -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="stat-card bg-blue-50 p-4 rounded-lg text-center">
            <i class='bx bx-package text-3xl text-blue-600 mb-2'></i>
            <h3 class="font-semibold text-blue-800">Total Items</h3>
            <p id="totalItems" class="text-2xl font-bold text-blue-600">0</p>
        </div>
        <div class="stat-card bg-green-50 p-4 rounded-lg text-center">
            <i class='bx bx-money text-3xl text-green-600 mb-2'></i>
            <h3 class="font-semibold text-green-800">Total Value</h3>
            <p id="totalValue" class="text-2xl font-bold text-green-600">₱0.00</p>
        </div>
        <div class="stat-card bg-yellow-50 p-4 rounded-lg text-center">
            <i class='bx bx-error text-3xl text-yellow-600 mb-2'></i>
            <h3 class="font-semibold text-yellow-800">Low Stock</h3>
            <p id="lowStockItems" class="text-2xl font-bold text-yellow-600">0</p>
        </div>
        <div class="stat-card bg-red-50 p-4 rounded-lg text-center">
            <i class='bx bx-error-circle text-3xl text-red-600 mb-2'></i>
            <h3 class="font-semibold text-red-800">Out of Stock</h3>
            <p id="outOfStockItems" class="text-2xl font-bold text-red-600">0</p>
        </div>
    </div>
    <!-- stats-card section end -->
    <!-- digital inventory main table area section start -->
    <div class="overflow-x-auto">
        <table class="table table-zebra w-full rounded-lg">
            <thead>
                <tr class="bg-gray-700 font-bold text-white">
                    <th class="whitespace-nowrap">Item Code</th>
                    <th class="whitespace-nowrap">SKU</th>
                    <th class="whitespace-nowrap">Item Name</th>
                    <th class="whitespace-nowrap">Category</th>
                    <th class="whitespace-nowrap">Stored From</th>
                    <th class="whitespace-nowrap">Current Stock</th>
                    <th class="whitespace-nowrap">Max Stock</th>
                    <th class="whitespace-nowrap">Unit Price</th>
                    <th class="whitespace-nowrap">Total Value</th>
                    <th class="whitespace-nowrap">Status</th>
                    <th class="whitespace-nowrap">Last Updated</th>
                    <th class="whitespace-nowrap">Actions</th>
                </tr>
            </thead>
            <tbody id="inventoryTableBody">
                <tr>
                    <td colspan="12" class="text-center py-4">
                        <div class="flex justify-center items-center">
                            <div class="loading loading-spinner mr-3"></div>
                            Loading inventory...
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- digital inventory main table area section end -->
</div>
<!-- digital inventory section end -->

<!-- Quick Actions Modals -->
<div id="underDevelopmentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Under Development</h3>
            <button id="closeUnderDevelopmentModal" class="text-gray-500 hover:text-gray-700">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>
        <div class="mb-4">
            <p>This feature is currently under development and will be available soon.</p>
        </div>
        <div class="flex justify-end">
            <button id="confirmUnderDevelopmentModal" class="btn btn-primary">OK</button>
        </div>
    </div>
</div>

<!-- Add New Item Modal -->
<div id="addItemModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Add New Inventory Item</h3>
            <button id="closeAddItemModal" class="text-gray-500 hover:text-gray-700">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>
        <form id="addItemForm">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Item Name *</label>
                    <input type="text" id="item_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                    <input type="text" id="item_stock_keeping_unit" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Auto-generated if empty">
                    <p class="text-xs text-gray-500 mt-1">Product variant identifier (e.g., MF-LAPTOP-BLACK-16GB)</p>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea id="item_description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select id="item_category_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Category</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stored From</label>
                    <input type="text" id="item_stored_from" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="e.g., Warehouse A, Supplier X">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Item Type *</label>
                    <select id="item_item_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="liquid">Liquid</option>
                        <option value="illiquid">Illiquid</option>
                        <option value="hybrid">Hybrid</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Liquidity Risk Level *</label>
                    <select id="item_liquidity_risk_level" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Stock *</label>
                    <input type="number" id="item_current_stock" required min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Max Stock</label>
                    <input type="number" id="item_max_stock" min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="100">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit Price (₱)</label>
                    <input type="number" id="item_unit_price" min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="0.00">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Expiration Date</label>
                    <input type="date" id="item_expiration_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Warranty End</label>
                    <input type="date" id="item_warranty_end" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="flex items-center">
                    <input type="checkbox" id="item_is_fixed" class="mr-2">
                    <label class="text-sm font-medium text-gray-700">Fixed Asset</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="item_is_collateral" class="mr-2">
                    <label class="text-sm font-medium text-gray-700">Is Collateral</label>
                </div>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg mb-4">
                <h4 class="font-semibold text-gray-700 mb-2">Item Code Information</h4>
                <p class="text-sm text-gray-600">Item Code will be auto-generated as: <span id="itemCodePreview" class="font-mono font-bold">ITMYYYYMMDDRRRRR</span></p>
                <p class="text-xs text-gray-500 mt-1">Format: ITM + Year + Month + Day + 5 random alphanumeric characters</p>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" id="cancelAddItemModal" class="px-4 py-2 bg-gray-200 rounded-lg">Cancel</button>
                <button type="submit" id="saveItemBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Save Item</button>
            </div>
        </form>
    </div>
</div>

<!-- View Item Modal -->
<div id="viewItemModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-3xl max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Item Details</h3>
            <button id="closeViewItemModal" class="text-gray-500 hover:text-gray-700">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>
        <div id="viewItemContent"></div>
    </div>
</div>

<!-- Edit Item Modal -->
<div id="editItemModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Edit Inventory Item</h3>
            <button id="closeEditItemModal" class="text-gray-500 hover:text-gray-700">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>
        <form id="editItemForm">
            <input type="hidden" id="edit_item_id">
            
            <!-- Read-only fields for Item Code and SKU -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Item Code</label>
                    <input type="text" id="edit_item_code" readonly class="w-full px-3 py-2 border border-gray-300 bg-gray-100 rounded-lg cursor-not-allowed">
                    <p class="text-xs text-gray-500 mt-1">Item Code cannot be changed</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                    <input type="text" id="edit_item_stock_keeping_unit" readonly class="w-full px-3 py-2 border border-gray-300 bg-gray-100 rounded-lg cursor-not-allowed">
                    <p class="text-xs text-gray-500 mt-1">SKU cannot be changed</p>
                </div>
            </div>
            
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Item Name *</label>
                <input type="text" id="edit_item_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea id="edit_item_description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select id="edit_item_category_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Category</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stored From</label>
                    <input type="text" id="edit_item_stored_from" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="e.g., Warehouse A, Supplier X">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Item Type *</label>
                    <select id="edit_item_item_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="liquid">Liquid</option>
                        <option value="illiquid">Illiquid</option>
                        <option value="hybrid">Hybrid</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Liquidity Risk Level *</label>
                    <select id="edit_item_liquidity_risk_level" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Stock *</label>
                    <input type="number" id="edit_item_current_stock" required min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Max Stock</label>
                    <input type="number" id="edit_item_max_stock" required min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit Price (₱)</label>
                    <input type="number" id="edit_item_unit_price" min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Expiration Date</label>
                    <input type="date" id="edit_item_expiration_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Warranty End</label>
                    <input type="date" id="edit_item_warranty_end" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div class="flex items-center">
                    <input type="checkbox" id="edit_item_is_fixed" class="mr-2">
                    <label class="text-sm font-medium text-gray-700">Fixed Asset</label>
                </div>
                <div class="flex items-center">
                    <input type="checkbox" id="edit_item_is_collateral" class="mr-2">
                    <label class="text-sm font-medium text-gray-700">Is Collateral</label>
                </div>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" id="cancelEditItemModal" class="px-4 py-2 bg-gray-200 rounded-lg">Cancel</button>
                <button type="submit" id="updateItemBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Update Item</button>
            </div>
        </form>
    </div>
</div>

<script>
var API_BASE_URL = typeof API_BASE_URL !== 'undefined' ? API_BASE_URL : '<?php echo url('/api/v1'); ?>';
var SWS_DIGITAL_INVENTORY_API = `${API_BASE_URL}/sws/digital-inventory`;
var SWS_ITEMS_API = `${API_BASE_URL}/sws/items`;
var SWS_CATEGORIES_API = `${API_BASE_URL}/sws/categories`;
var SWS_INVENTORY_STATS_API = `${API_BASE_URL}/sws/inventory-stats`;
var SWS_STOCK_LEVELS_API = `${API_BASE_URL}/sws/stock-levels`;
var CSRF_TOKEN = typeof CSRF_TOKEN !== 'undefined' ? CSRF_TOKEN : document.querySelector('meta[name="csrf-token"]').getAttribute('content');
var JWT_TOKEN = typeof JWT_TOKEN !== 'undefined' ? JWT_TOKEN : localStorage.getItem('jwt');

var inventoryItems = [];
var categories = [];

const els = {
    // Stock Levels
    stockLevelsContainer: document.getElementById('stockLevelsContainer'),
    
    // Quick Actions
    searchItemBtn: document.getElementById('searchItemBtn'),
    transferBtn: document.getElementById('transferBtn'),
    scanBarcodeBtn: document.getElementById('scanBarcodeBtn'),
    generateReportBtn: document.getElementById('generateReportBtn'),
    purchaseNewItemBtn: document.getElementById('purchaseNewItemBtn'),
    inventoryNewItemBtn: document.getElementById('inventoryNewItemBtn'),
    
    // Stats Cards
    totalItems: document.getElementById('totalItems'),
    totalValue: document.getElementById('totalValue'),
    lowStockItems: document.getElementById('lowStockItems'),
    outOfStockItems: document.getElementById('outOfStockItems'),
    
    // Table
    inventoryTableBody: document.getElementById('inventoryTableBody'),
    
    // Modals
    underDevelopmentModal: document.getElementById('underDevelopmentModal'),
    closeUnderDevelopmentModal: document.getElementById('closeUnderDevelopmentModal'),
    confirmUnderDevelopmentModal: document.getElementById('confirmUnderDevelopmentModal'),
    
    addItemModal: document.getElementById('addItemModal'),
    closeAddItemModal: document.getElementById('closeAddItemModal'),
    cancelAddItemModal: document.getElementById('cancelAddItemModal'),
    addItemForm: document.getElementById('addItemForm'),
    itemCodePreview: document.getElementById('itemCodePreview'),
    
    viewItemModal: document.getElementById('viewItemModal'),
    closeViewItemModal: document.getElementById('closeViewItemModal'),
    viewItemContent: document.getElementById('viewItemContent'),
    
    editItemModal: document.getElementById('editItemModal'),
    closeEditItemModal: document.getElementById('closeEditItemModal'),
    cancelEditItemModal: document.getElementById('cancelEditItemModal'),
    editItemForm: document.getElementById('editItemForm')
};

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

function notify(message, type = 'info') { 
    Toast.fire({ icon: type, title: message }); 
}

function formatNumber(n) { return Number(n || 0).toLocaleString(); }
function formatCurrency(n) { 
    return '₱' + (Number(n || 0)).toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}
function formatPercent(n) { return `${(Number(n || 0)).toFixed(2)}%`; }
function formatDate(dateString) { 
    if (!dateString) return 'N/A';
    try {
        const date = new Date(dateString);
        return !isNaN(date.getTime()) ? date.toLocaleDateString('en-PH') : 'N/A';
    } catch (e) {
        return 'N/A';
    }
}

// Format date for input fields (YYYY-MM-DD)
function formatDateForInput(dateString) {
    if (!dateString) return '';
    try {
        const date = new Date(dateString);
        if (!isNaN(date.getTime())) {
            const year = date.getFullYear();
            const month = String(date.getMonth() + 1).padStart(2, '0');
            const day = String(date.getDate()).padStart(2, '0');
            return `${year}-${month}-${day}`;
        }
        return '';
    } catch (e) {
        return '';
    }
}

// Generate Item Code: ITM + YYYY + MM + DD + 5 random alphanumeric characters
function generateItemCode() {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    
    // Generate 5 random alphanumeric characters (numbers and letters only)
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let randomPart = '';
    for (let i = 0; i < 5; i++) {
        randomPart += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    
    return `ITM${year}${month}${day}${randomPart}`;
}

// Generate SKU if not provided (product variant identifier)
function generateSKU(itemName, category) {
    if (!itemName) return null;
    
    // Create base from item name and category
    const base = itemName.toUpperCase().replace(/[^A-Z0-9]/g, '').substring(0, 6);
    const cat = category ? category.toUpperCase().replace(/[^A-Z0-9]/g, '').substring(0, 3) : 'GEN';
    const random = Math.random().toString(36).substring(2, 6).toUpperCase();
    
    return `${cat}-${base}-${random}`;
}

// Update item code preview
function updateItemCodePreview() {
    els.itemCodePreview.textContent = generateItemCode();
}

// Stock Levels Functions
async function loadStockLevels() {
    try {
        const response = await fetch(SWS_STOCK_LEVELS_API, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            credentials: 'include'
        });
        
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        const result = await response.json();
        
        if (result.success && result.data) {
            renderStockLevels(result.data);
        } else {
            renderStockLevels([]);
        }
    } catch (e) {
        console.error('Error loading stock levels:', e);
        renderStockLevels([]);
    }
}

function renderStockLevels(stockLevels) {
    const defaultCategories = [
        { name: 'Equipment', utilization: 0, total_quantity: 0 },
        { name: 'Supplies', utilization: 0, total_quantity: 0 },
        { name: 'Furniture', utilization: 0, total_quantity: 0 },
        { name: 'Automotive', utilization: 0, total_quantity: 0 }
    ];
    
    const categoriesToRender = stockLevels.length > 0 ? stockLevels : defaultCategories;
    
    let html = '';
    categoriesToRender.forEach(category => {
        const utilization = Number(category.utilization) || 0;
        const progressClass = utilization >= 80 ? 'progress-error' : 
                            utilization >= 60 ? 'progress-warning' : 
                            utilization >= 40 ? 'progress-success' : 'progress-primary';
        
        html += `
            <div>
                <div class="flex justify-between mb-1">
                    <span>${category.name}</span>
                    <span>${utilization}%</span>
                </div>
                <progress class="progress ${progressClass} w-full" value="${utilization}" max="100"></progress>
            </div>
        `;
    });
    
    els.stockLevelsContainer.innerHTML = html;
}

// Inventory Stats Functions
async function loadInventoryStats() {
    try {
        const response = await fetch(SWS_INVENTORY_STATS_API, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            credentials: 'include'
        });
        
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        const result = await response.json();
        
        if (result.success && result.data) {
            renderInventoryStats(result.data);
        } else {
            renderInventoryStats({
                total_items: 0,
                total_value: 0,
                low_stock_items: 0,
                out_of_stock_items: 0
            });
        }
    } catch (e) {
        console.error('Error loading inventory stats:', e);
        renderInventoryStats({
            total_items: 0,
            total_value: 0,
            low_stock_items: 0,
            out_of_stock_items: 0
        });
    }
}

function renderInventoryStats(stats) {
    els.totalItems.textContent = formatNumber(stats.total_items || 0);
    els.totalValue.textContent = formatCurrency(stats.total_value || 0);
    els.lowStockItems.textContent = formatNumber(stats.low_stock_items || 0);
    els.outOfStockItems.textContent = formatNumber(stats.out_of_stock_items || 0);
}

// Calculate total value from inventory items
function calculateTotalValue(items) {
    return items.reduce((total, item) => {
        const unitPrice = parseFloat(item.unit_price) || 0;
        const quantity = parseInt(item.current_stock) || 0;
        return total + (unitPrice * quantity);
    }, 0);
}

// Inventory Items Functions
async function loadInventoryItems() {
    try {
        const response = await fetch(SWS_ITEMS_API, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            credentials: 'include'
        });
        
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        const result = await response.json();
        
        if (result.success && result.data) {
            // Items are already sorted by backend (newest first)
            inventoryItems = result.data;
            
            // Calculate and update total value
            const totalValue = calculateTotalValue(inventoryItems);
            els.totalValue.textContent = formatCurrency(totalValue);
            
            renderInventoryItems();
        } else {
            inventoryItems = [];
            renderInventoryItems();
        }
    } catch (e) {
        console.error('Error loading inventory items:', e);
        inventoryItems = [];
        renderInventoryItems();
        notify('Error loading inventory items', 'error');
    }
}

function renderInventoryItems() {
    if (inventoryItems.length === 0) {
        els.inventoryTableBody.innerHTML = `
            <tr>
                <td colspan="12" class="text-center py-4 text-gray-500">
                    No inventory items found
                </td>
            </tr>
        `;
        return;
    }
    
    let html = '';
    inventoryItems.forEach(item => {
        // Use the actual item_code from database (backend generated)
        const itemCode = item.item_code || 'N/A';
        const sku = item.item_stock_keeping_unit || 'N/A';
        const itemName = item.item_name || 'N/A';
        const category = item.category || 'Uncategorized';
        const storedFrom = item.item_stored_from || 'N/A';
        const currentStock = item.current_stock || 0;
        const maxStock = item.max_stock || 100;
        const unitPrice = item.unit_price || 0;
        const totalValue = item.total_value || 0;
        const status = item.status || 'Unknown';
        const statusClass = item.status_class || 'badge-info';
        const lastUpdated = item.last_updated || 'N/A';
        
        html += `
            <tr>
                <td class="font-semibold font-mono whitespace-nowrap">${itemCode}</td>
                <td class="font-mono text-sm whitespace-nowrap">${sku}</td>
                <td class="whitespace-nowrap">${itemName}</td>
                <td class="whitespace-nowrap">${category}</td>
                <td class="whitespace-nowrap">${storedFrom}</td>
                <td class="text-center whitespace-nowrap">${formatNumber(currentStock)}</td>
                <td class="text-center whitespace-nowrap">${formatNumber(maxStock)}</td>
                <td class="text-right whitespace-nowrap">${formatCurrency(unitPrice)}</td>
                <td class="text-right font-semibold whitespace-nowrap">${formatCurrency(totalValue)}</td>
                <td class="whitespace-nowrap">
                    <span class="badge ${statusClass} whitespace-nowrap">
                        ${status}
                    </span>
                </td>
                <td class="whitespace-nowrap">${formatDate(lastUpdated)}</td>
                <td class="whitespace-nowrap">
                    <div class="flex gap-2">
                        <button class="text-primary transition-colors p-2 rounded-lg hover:bg-gray-50 view-item-btn" 
                                title="View Details" data-id="${item.item_id}">
                            <i class='bx bx-show-alt text-xl'></i>
                        </button>
                        <button class="text-warning transition-colors p-2 rounded-lg hover:bg-gray-50 edit-item-btn" 
                                title="Edit Item" data-id="${item.item_id}">
                            <i class='bx bx-edit text-xl'></i>
                        </button>
                        <button class="text-error transition-colors p-2 rounded-lg hover:bg-gray-50 delete-item-btn" 
                                title="Delete Item" data-id="${item.item_id}">
                            <i class='bx bx-trash text-xl'></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
    
    els.inventoryTableBody.innerHTML = html;
    
    // Add event listeners to action buttons
    document.querySelectorAll('.view-item-btn').forEach(btn => {
        btn.addEventListener('click', () => viewItem(btn.dataset.id));
    });
    
    document.querySelectorAll('.edit-item-btn').forEach(btn => {
        btn.addEventListener('click', () => editItem(btn.dataset.id));
    });
    
    document.querySelectorAll('.delete-item-btn').forEach(btn => {
        btn.addEventListener('click', () => deleteItem(btn.dataset.id));
    });
}

// Categories Functions
async function loadCategories() {
    try {
        const response = await fetch(SWS_CATEGORIES_API, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            credentials: 'include'
        });
        
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        const result = await response.json();
        
        if (result.success && result.data) {
            categories = result.data;
            renderCategoryOptions();
        } else {
            categories = [];
            renderCategoryOptions();
        }
    } catch (e) {
        console.error('Error loading categories:', e);
        categories = [];
        renderCategoryOptions();
    }
}

function renderCategoryOptions() {
    const categorySelect = document.getElementById('item_category_id');
    const editCategorySelect = document.getElementById('edit_item_category_id');
    
    if (categorySelect) {
        let html = '<option value="">Select Category</option>';
        categories.forEach(category => {
            html += `<option value="${category.cat_id}">${category.cat_name}</option>`;
        });
        categorySelect.innerHTML = html;
    }
    
    if (editCategorySelect) {
        let html = '<option value="">Select Category</option>';
        categories.forEach(category => {
            html += `<option value="${category.cat_id}">${category.cat_name}</option>`;
        });
        editCategorySelect.innerHTML = html;
    }
}

// Modal Functions
function openUnderDevelopmentModal() {
    els.underDevelopmentModal.classList.remove('hidden');
}

function closeUnderDevelopmentModal() {
    els.underDevelopmentModal.classList.add('hidden');
}

function openAddItemModal() {
    els.addItemModal.classList.remove('hidden');
    // Reset form
    els.addItemForm.reset();
    document.getElementById('item_is_fixed').checked = false;
    document.getElementById('item_is_collateral').checked = false;
    
    // Update item code preview
    updateItemCodePreview();
    
    // Auto-generate SKU based on item name if empty
    const itemNameInput = document.getElementById('item_name');
    const skuInput = document.getElementById('item_stock_keeping_unit');
    
    itemNameInput.addEventListener('input', function() {
        if (!skuInput.value) {
            const categorySelect = document.getElementById('item_category_id');
            const categoryName = categorySelect.options[categorySelect.selectedIndex]?.text || '';
            skuInput.value = generateSKU(this.value, categoryName);
        }
    });
    
    // Also generate SKU when category changes
    const categorySelect = document.getElementById('item_category_id');
    categorySelect.addEventListener('change', function() {
        if (!skuInput.value && itemNameInput.value) {
            const categoryName = this.options[this.selectedIndex]?.text || '';
            skuInput.value = generateSKU(itemNameInput.value, categoryName);
        }
    });
}

function closeAddItemModal() {
    els.addItemModal.classList.add('hidden');
}

function openViewItemModal() {
    els.viewItemModal.classList.remove('hidden');
}

function closeViewItemModal() {
    els.viewItemModal.classList.add('hidden');
}

function openEditItemModal() {
    els.editItemModal.classList.remove('hidden');
}

function closeEditItemModal() {
    els.editItemModal.classList.add('hidden');
}

// Item CRUD Functions
async function saveItem(e) {
    e.preventDefault();
    
    const itemName = document.getElementById('item_name').value.trim();
    const formData = {
        item_name: itemName,
        item_description: document.getElementById('item_description').value.trim() || null,
        item_stock_keeping_unit: document.getElementById('item_stock_keeping_unit').value.trim() || null,
        item_category_id: document.getElementById('item_category_id').value || null,
        item_stored_from: document.getElementById('item_stored_from').value.trim() || null,
        item_item_type: document.getElementById('item_item_type').value,
        item_is_fixed: document.getElementById('item_is_fixed').checked,
        item_expiration_date: document.getElementById('item_expiration_date').value || null,
        item_warranty_end: document.getElementById('item_warranty_end').value || null,
        item_unit_price: document.getElementById('item_unit_price').value ? parseFloat(document.getElementById('item_unit_price').value) : null,
        item_current_stock: parseInt(document.getElementById('item_current_stock').value),
        item_max_stock: document.getElementById('item_max_stock').value ? parseInt(document.getElementById('item_max_stock').value) : null,
        item_liquidity_risk_level: document.getElementById('item_liquidity_risk_level').value,
        item_is_collateral: document.getElementById('item_is_collateral').checked,
        item_code: generateItemCode()
    };
    
    if (!formData.item_name) {
        notify('Please enter item name', 'error');
        return;
    }
    
    if (isNaN(formData.item_current_stock) || formData.item_current_stock < 0) {
        notify('Please enter a valid current stock', 'error');
        return;
    }
    
    // Auto-generate SKU if empty
    if (!formData.item_stock_keeping_unit) {
        const categorySelect = document.getElementById('item_category_id');
        const categoryName = categorySelect.options[categorySelect.selectedIndex]?.text || '';
        formData.item_stock_keeping_unit = generateSKU(itemName, categoryName);
    }
    
    try {
        const response = await fetch(SWS_ITEMS_API, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            body: JSON.stringify(formData),
            credentials: 'include'
        });
        
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        
        const result = await response.json();
        
        if (result.success) {
            notify('Item created successfully', 'success');
            closeAddItemModal();
            // Reload all data
            await Promise.all([
                loadInventoryItems(),
                loadInventoryStats(),
                loadStockLevels()
            ]);
        } else {
            notify(result.message || 'Error creating item', 'error');
        }
    } catch (e) {
        console.error('Error creating item:', e);
        notify('Error creating item', 'error');
    }
}

async function updateItem(e) {
    e.preventDefault();
    
    const itemId = document.getElementById('edit_item_id').value;
    const formData = {
        item_name: document.getElementById('edit_item_name').value.trim(),
        item_description: document.getElementById('edit_item_description').value.trim() || null,
        item_category_id: document.getElementById('edit_item_category_id').value || null,
        item_stored_from: document.getElementById('edit_item_stored_from').value.trim() || null,
        item_item_type: document.getElementById('edit_item_item_type').value,
        item_is_fixed: document.getElementById('edit_item_is_fixed').checked,
        item_expiration_date: document.getElementById('edit_item_expiration_date').value || null,
        item_warranty_end: document.getElementById('edit_item_warranty_end').value || null,
        item_unit_price: document.getElementById('edit_item_unit_price').value ? parseFloat(document.getElementById('edit_item_unit_price').value) : null,
        item_current_stock: parseInt(document.getElementById('edit_item_current_stock').value),
        item_max_stock: parseInt(document.getElementById('edit_item_max_stock').value),
        item_liquidity_risk_level: document.getElementById('edit_item_liquidity_risk_level').value,
        item_is_collateral: document.getElementById('edit_item_is_collateral').checked
        // Note: item_code and item_stock_keeping_unit are intentionally excluded to make them uneditable
    };
    
    if (!formData.item_name) {
        notify('Please enter item name', 'error');
        return;
    }
    
    if (isNaN(formData.item_current_stock) || formData.item_current_stock < 0) {
        notify('Please enter a valid current stock', 'error');
        return;
    }
    
    if (isNaN(formData.item_max_stock) || formData.item_max_stock < 1) {
        notify('Please enter a valid max stock (minimum 1)', 'error');
        return;
    }
    
    try {
        const response = await fetch(`${SWS_ITEMS_API}/${itemId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            body: JSON.stringify(formData),
            credentials: 'include'
        });
        
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        
        const result = await response.json();
        
        if (result.success) {
            notify('Item updated successfully', 'success');
            closeEditItemModal();
            // Reload all data
            await Promise.all([
                loadInventoryItems(),
                loadInventoryStats(),
                loadStockLevels()
            ]);
        } else {
            notify(result.message || 'Error updating item', 'error');
        }
    } catch (e) {
        console.error('Error updating item:', e);
        notify('Error updating item', 'error');
    }
}

async function viewItem(itemId) {
    try {
        const response = await fetch(`${SWS_ITEMS_API}/${itemId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            credentials: 'include'
        });
        
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        const result = await response.json();
        
        if (result.success && result.data) {
            const item = result.data;
            const unitPrice = parseFloat(item.item_unit_price) || 0;
            const quantity = parseInt(item.item_current_stock) || 0;
            const totalValue = unitPrice * quantity;
            const lastUpdated = item.item_updated_at || item.item_created_at;
            const itemCode = item.item_code || 'N/A';
            
            const content = `
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div><span class="text-sm text-gray-500">Item Code</span><p class="font-semibold font-mono">${itemCode}</p></div>
                    <div><span class="text-sm text-gray-500">SKU</span><p class="font-semibold font-mono">${item.item_stock_keeping_unit || 'N/A'}</p></div>
                    <div><span class="text-sm text-gray-500">Item Name</span><p class="font-semibold">${item.item_name || 'N/A'}</p></div>
                    <div><span class="text-sm text-gray-500">Category</span><p class="font-semibold">${item.category?.cat_name || 'Uncategorized'}</p></div>
                    <div><span class="text-sm text-gray-500">Stored From</span><p class="font-semibold">${item.item_stored_from || 'N/A'}</p></div>
                    <div><span class="text-sm text-gray-500">Item Type</span><p class="font-semibold">${item.item_item_type || 'N/A'}</p></div>
                    <div><span class="text-sm text-gray-500">Current Stock</span><p class="font-semibold">${formatNumber(item.item_current_stock)}</p></div>
                    <div><span class="text-sm text-gray-500">Max Stock</span><p class="font-semibold">${formatNumber(item.item_max_stock)}</p></div>
                    <div><span class="text-sm text-gray-500">Unit Price</span><p class="font-semibold">${formatCurrency(unitPrice)}</p></div>
                    <div><span class="text-sm text-gray-500">Total Value</span><p class="font-semibold">${formatCurrency(totalValue)}</p></div>
                    <div><span class="text-sm text-gray-500">Liquidity Risk</span><p class="font-semibold">${item.item_liquidity_risk_level || 'N/A'}</p></div>
                    <div><span class="text-sm text-gray-500">Fixed Asset</span><p class="font-semibold">${item.item_is_fixed ? 'Yes' : 'No'}</p></div>
                    <div><span class="text-sm text-gray-500">Is Collateral</span><p class="font-semibold">${item.item_is_collateral ? 'Yes' : 'No'}</p></div>
                    <div><span class="text-sm text-gray-500">Expiration Date</span><p class="font-semibold">${formatDate(item.item_expiration_date)}</p></div>
                    <div><span class="text-sm text-gray-500">Warranty End</span><p class="font-semibold">${formatDate(item.item_warranty_end)}</p></div>
                    <div><span class="text-sm text-gray-500">Last Updated</span><p class="font-semibold">${formatDate(lastUpdated)}</p></div>
                    <div class="md:col-span-2"><span class="text-sm text-gray-500">Description</span><p class="font-semibold break-words">${item.item_description || 'N/A'}</p></div>
                </div>
            `;
            els.viewItemContent.innerHTML = content;
            openViewItemModal();
        } else {
            notify('Item not found', 'error');
        }
    } catch (e) {
        console.error('Error loading item:', e);
        notify('Error loading item details', 'error');
    }
}

async function editItem(itemId) {
    try {
        const response = await fetch(`${SWS_ITEMS_API}/${itemId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            credentials: 'include'
        });
        
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        const result = await response.json();
        
        if (result.success && result.data) {
            const item = result.data;
            
            // Populate edit form
            document.getElementById('edit_item_id').value = item.item_id;
            document.getElementById('edit_item_code').value = item.item_code || '';
            document.getElementById('edit_item_stock_keeping_unit').value = item.item_stock_keeping_unit || '';
            document.getElementById('edit_item_name').value = item.item_name || '';
            document.getElementById('edit_item_description').value = item.item_description || '';
            document.getElementById('edit_item_category_id').value = item.item_category_id || '';
            document.getElementById('edit_item_stored_from').value = item.item_stored_from || '';
            document.getElementById('edit_item_item_type').value = item.item_item_type || 'illiquid';
            document.getElementById('edit_item_current_stock').value = item.item_current_stock || 0;
            document.getElementById('edit_item_max_stock').value = item.item_max_stock || 100;
            document.getElementById('edit_item_unit_price').value = item.item_unit_price || '';
            document.getElementById('edit_item_liquidity_risk_level').value = item.item_liquidity_risk_level || 'medium';
            document.getElementById('edit_item_expiration_date').value = formatDateForInput(item.item_expiration_date);
            document.getElementById('edit_item_warranty_end').value = formatDateForInput(item.item_warranty_end);
            document.getElementById('edit_item_is_fixed').checked = item.item_is_fixed || false;
            document.getElementById('edit_item_is_collateral').checked = item.item_is_collateral || false;
            
            openEditItemModal();
        } else {
            notify('Item not found', 'error');
        }
    } catch (e) {
        console.error('Error loading item for edit:', e);
        notify('Error loading item details', 'error');
    }
}

async function deleteItem(itemId) {
    const confirmResult = await Swal.fire({
        title: 'Delete Item?',
        text: 'This action cannot be undone',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel'
    });
    
    if (!confirmResult.isConfirmed) return;
    
    try {
        const response = await fetch(`${SWS_ITEMS_API}/${itemId}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            credentials: 'include'
        });
        
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        
        const result = await response.json();
        
        if (result.success) {
            notify('Item deleted successfully', 'success');
            // Reload all data
            await Promise.all([
                loadInventoryItems(),
                loadInventoryStats(),
                loadStockLevels()
            ]);
        } else {
            notify(result.message || 'Error deleting item', 'error');
        }
    } catch (e) {
        console.error('Error deleting item:', e);
        notify('Error deleting item', 'error');
    }
}

// Initialize Digital Inventory
function initDigitalInventory() {
    // Load initial data
    Promise.all([
        loadInventoryItems(),
        loadInventoryStats(),
        loadStockLevels(),
        loadCategories()
    ]);
    
    // Quick Actions Event Listeners
    els.searchItemBtn.addEventListener('click', openUnderDevelopmentModal);
    els.transferBtn.addEventListener('click', openUnderDevelopmentModal);
    els.scanBarcodeBtn.addEventListener('click', openUnderDevelopmentModal);
    els.generateReportBtn.addEventListener('click', openUnderDevelopmentModal);
    els.purchaseNewItemBtn.addEventListener('click', openUnderDevelopmentModal);
    els.inventoryNewItemBtn.addEventListener('click', openAddItemModal);
    
    // Modal Event Listeners
    els.closeUnderDevelopmentModal.addEventListener('click', closeUnderDevelopmentModal);
    els.confirmUnderDevelopmentModal.addEventListener('click', closeUnderDevelopmentModal);
    els.underDevelopmentModal.addEventListener('click', function(e) {
        if (e.target === els.underDevelopmentModal) closeUnderDevelopmentModal();
    });
    
    els.closeAddItemModal.addEventListener('click', closeAddItemModal);
    els.cancelAddItemModal.addEventListener('click', closeAddItemModal);
    els.addItemModal.addEventListener('click', function(e) {
        if (e.target === els.addItemModal) closeAddItemModal();
    });
    els.addItemForm.addEventListener('submit', saveItem);
    
    els.closeViewItemModal.addEventListener('click', closeViewItemModal);
    els.viewItemModal.addEventListener('click', function(e) {
        if (e.target === els.viewItemModal) closeViewItemModal();
    });
    
    els.closeEditItemModal.addEventListener('click', closeEditItemModal);
    els.cancelEditItemModal.addEventListener('click', closeEditItemModal);
    els.editItemModal.addEventListener('click', function(e) {
        if (e.target === els.editItemModal) closeEditItemModal();
    });
    els.editItemForm.addEventListener('submit', updateItem);
}

// Initialize when DOM is loaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initDigitalInventory);
} else {
    initDigitalInventory();
}
</script>