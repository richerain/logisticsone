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
                <button id="transferBtn" class="btn btn-info text-white">
                    <i class='bx bx-transfer mr-2'></i>Transfer
                </button>
                <button id="viewLocationsBtn" class="btn btn-warning text-white">
                    <i class='bx bx-map mr-2'></i>Location
                </button>
                <button id="viewCategoriesBtn" class="btn btn-warning text-white">
                    <i class='bx bx-category-alt mr-2'></i>Category
                </button>
                <button id="generateReportBtn" class="btn btn-secondary text-white">
                    <i class='bx bxs-report mr-2'></i>Generate Report
                </button>
                <button id="purchaseNewItemBtn" class="btn btn-success px-9 text-white">
                    <i class='bx bxs-purchase-tag mr-2'></i>Purchase New Item
                </button>
                <button id="inventoryNewItemBtn" class="btn btn-primary px-9 text-white">
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
        <div id="di_card_total" class="stat-card bg-blue-50 p-4 rounded-lg text-center cursor-pointer">
            <i class='bx bx-package text-3xl text-blue-600 mb-2'></i>
            <h3 class="font-semibold text-blue-800">Total Items</h3>
            <p id="totalItems" class="text-2xl font-bold text-blue-600">0</p>
        </div>
        <div class="stat-card bg-green-50 p-4 rounded-lg text-center">
            <i class='bx bx-money text-3xl text-green-600 mb-2'></i>
            <h3 class="font-semibold text-green-800">Total Value</h3>
            <p id="totalValue" class="text-2xl font-bold text-green-600">₱0.00</p>
        </div>
        <div id="di_card_low" class="stat-card bg-yellow-50 p-4 rounded-lg text-center cursor-pointer">
            <i class='bx bx-error text-3xl text-yellow-600 mb-2'></i>
            <h3 class="font-semibold text-yellow-800">Low Stock</h3>
            <p id="lowStockItems" class="text-2xl font-bold text-yellow-600">0</p>
        </div>
        <div id="di_card_out" class="stat-card bg-red-50 p-4 rounded-lg text-center cursor-pointer">
            <i class='bx bx-error-circle text-3xl text-red-600 mb-2'></i>
            <h3 class="font-semibold text-red-800">Out of Stock</h3>
            <p id="outOfStockItems" class="text-2xl font-bold text-red-600">0</p>
        </div>
    </div>
    <!-- stats-card section end -->
    <!-- digital inventory main table area section start -->
    <div class="mb-4 flex items-center gap-3">
        <input id="di_search" type="text" placeholder="Search item, code, category, stored from" class="px-3 py-2 border border-gray-300 rounded-lg flex-1" />
        <div class="flex items-center gap-2">
            <button class="btn btn-ghost btn-sm" data-di-status="">All</button>
            <button class="btn btn-ghost btn-sm" data-di-status="In Stock">In Stock</button>
            <button class="btn btn-ghost btn-sm" data-di-status="Low Stock">Low Stock</button>
            <button class="btn btn-ghost btn-sm" data-di-status="Out of Stock">Out of Stock</button>
        </div>
    </div>

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
    <div id="diPager" class="flex items-center justify-between mt-3">
        <div id="diPagerInfo" class="text-sm text-gray-600"></div>
        <div class="join">
            <button class="btn btn-sm join-item" id="diPrevBtn" data-action="prev">Prev</button>
            <span class="btn btn-sm join-item" id="diPageDisplay">1 / 1</span>
            <button class="btn btn-sm join-item" id="diNextBtn" data-action="next">Next</button>
        </div>
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
            <input type="hidden" id="psm_purchase_id" name="psm_purchase_id">
            <input type="hidden" id="psm_item_index" name="psm_item_index">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Item Name *</label>
                    <select id="item_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Item from Completed Purchase</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
                    <input type="text" id="item_stock_keeping_unit" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Auto-generated if empty">
                    <p class="text-xs text-gray-500 mt-1">Product variant identifier (e.g., MF-LAPTOP-BLACK-16GB)</p>
                </div>
            </div>
            <div class="mb-4" style="display:none">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea id="item_description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div style="display:none">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                    <select id="item_category_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Category</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Stored From *</label>
                    <select id="item_stored_from" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Warehouse</option>
                    </select>
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
                <div style="display:none">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Current Stock *</label>
                    <input type="number" id="item_current_stock" required min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Max Stock *</label>
                    <input type="number" id="item_max_stock" required min="1" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="100">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div style="display:none">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unit Price (₱) *</label>
                    <input type="number" id="item_unit_price" required min="0" step="0.01" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="0.00">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Expiration Date</label>
                    <input type="date" id="item_expiration_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div style="display:none">
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
            <div class="bg-gray-50 p-4 rounded-lg mb-4" style="display:none">
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
<!-- transfer modal-->
<div id="transferItemModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-4xl max-h-[85vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Transfer Inventory Item</h3>
            <button id="closeTransferItemModal" class="text-gray-500 hover:text-gray-700">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>
        <form id="transferItemForm">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Select Item *</label>
                <select id="transfer_item_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></select>
            </div>
            <div id="transferItemDetails" class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4"></div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Location From</label>
                    <input type="text" id="transfer_location_from" readonly class="w-full px-3 py-2 border border-gray-300 bg-gray-100 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Location To *</label>
                    <select id="transfer_location_to" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></select>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Transfer Units *</label>
                <input type="number" id="transfer_units" min="1" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <p id="transfer_units_warning" class="text-xs text-red-600 mt-1 hidden">Transfer units exceed current stock</p>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" id="cancelTransferItemModal" class="px-4 py-2 bg-gray-200 rounded-lg">Cancel</button>
                <button type="submit" id="confirmTransferBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Transfer</button>
            </div>
</form>
    </div>
</div>


<!-- View Locations Modal -->
<div id="viewLocationsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-6xl max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Company Location</h3>
            <button id="addNewLocationBtn" class="btn btn-primary btn-sm text-white">
                <i class='bx bx-plus mr-1'></i>Add New Location
            </button>
        </div>

        <div class="overflow-x-auto mb-4 border rounded-lg">
            <table class="table table-zebra w-full">
                <thead>
                    <tr class="bg-gray-700 text-white">
                        <th class="whitespace-nowrap">Location ID</th>
                        <th class="whitespace-nowrap">Name</th>
                        <th class="whitespace-nowrap">Type</th>
                        <th class="whitespace-nowrap">Zone</th>
                        <th class="whitespace-nowrap">Fixed Asset Support</th>
                        <th class="whitespace-nowrap">Capacity</th>
                        <th class="whitespace-nowrap">Parent Location</th>
                        <th class="whitespace-nowrap">Department</th>
                        <th class="whitespace-nowrap">Status</th>
                        <th class="whitespace-nowrap">Created At</th>
                        <th class="whitespace-nowrap">Action</th>
                    </tr>
                </thead>
                <tbody id="locationsTableBody">
                    <tr>
                        <td colspan="10" class="text-center py-8 text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class='bx bx-fw bx-map-alt text-4xl text-gray-400 mb-2'></i>
                                <p>No locations found</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div id="locationsPager" class="flex items-center justify-between mt-3">
            <div id="locationsPagerInfo" class="text-sm text-gray-600"></div>
            <div class="join">
                <button class="btn btn-sm join-item" id="locationsPrevBtn" data-action="prev">Prev</button>
                <span class="btn btn-sm join-item" id="locationsPageDisplay">1 / 1</span>
                <button class="btn btn-sm join-item" id="locationsNextBtn" data-action="next">Next</button>
            </div>
        </div>

        <div class="flex justify-end gap-2 mt-4">
            <button id="closeViewLocationsModalBtn" class="btn btn-ghost">Close</button>
        </div>
    </div>
</div>

<!-- View Location Modal -->
<div id="viewLocationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-[60]">
    <div class="bg-white rounded-lg p-6 w-full max-w-2xl">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">View Location Details</h3>
            <button id="closeViewLocationModal" class="text-gray-500 hover:text-gray-700">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>
        <div class="space-y-4" id="viewLocationContent">
            <!-- Content will be populated by JS -->
        </div>
        <div class="flex justify-end gap-2 mt-6">
            <button id="closeViewLocationModalBtn" class="btn btn-ghost">Close</button>
        </div>
    </div>
</div>

<!-- Edit Location Modal -->
<div id="editLocationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-[60]">
    <div class="bg-white rounded-lg p-6 w-full max-w-2xl">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Edit Location</h3>
            <button id="closeEditLocationModal" class="text-gray-500 hover:text-gray-700">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>
        <form id="editLocationForm">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Location ID</label>
                <input type="text" id="edit_loc_id" readonly class="w-full px-3 py-2 border border-gray-300 bg-gray-100 rounded-lg font-mono font-bold text-gray-500">
            </div>
            
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Location Name <span class="text-red-500">*</span></label>
                    <input type="text" id="edit_loc_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" placeholder="e.g. Warehouse A - Zone 1">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Location Type <span class="text-red-500">*</span></label>
                    <select id="edit_loc_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                        <option value="">Select Type</option>
                        <option value="warehouse">Warehouse</option>
                        <option value="storage_room">Storage Room</option>
                        <option value="office">Office</option>
                        <option value="facility">Facility</option>
                        <option value="drop_point">Drop Point</option>
                        <option value="bin">Bin</option>
                        <option value="department">Department</option>
                        <option value="room">Room</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Zone Type <span class="text-red-500">*</span></label>
                    <select id="edit_loc_zone_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500">
                        <option value="">Select Zone</option>
                        <option value="general">General</option>
                        <option value="liquid">Liquid Storage</option>
                        <option value="illiquid">Illiquid Storage</option>
                        <option value="climate_controlled">Climate Controlled</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Capacity</label>
                    <input type="number" id="edit_loc_capacity" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-blue-500" placeholder="Leave empty for unlimited">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Fixed Asset Support <span class="text-red-500">*</span></label>
                    <div class="flex gap-4 mt-2">
                        <label class="flex items-center">
                            <input type="radio" name="edit_loc_supports_fixed_items" value="1" class="radio radio-primary radio-sm mr-2">
                            <span>Yes</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="edit_loc_supports_fixed_items" value="0" class="radio radio-primary radio-sm mr-2">
                            <span>No</span>
                        </label>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <div class="flex gap-4 mt-2">
                        <label class="flex items-center">
                            <input type="radio" name="edit_loc_is_active" value="1" class="radio radio-success radio-sm mr-2">
                            <span>Active</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" name="edit_loc_is_active" value="0" class="radio radio-error radio-sm mr-2">
                            <span>Inactive</span>
                        </label>
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" id="cancelEditLocationModal" class="btn btn-ghost">Cancel</button>
                <button type="submit" class="btn btn-primary text-white">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<!-- Add New Location Modal -->
<div id="addLocationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-[60]">
    <div class="bg-white rounded-lg p-6 w-full max-w-2xl">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Add New Location</h3>
            <button id="closeAddLocationModal" class="text-gray-500 hover:text-gray-700">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>
        <form id="addLocationForm">
            <input type="hidden" id="new_loc_id">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Location Name *</label>
                    <input type="text" id="new_loc_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Type</label>
                    <select id="new_loc_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="warehouse">Warehouse</option>
                        <option value="storage_room">Storage Room</option>
                        <option value="office">Office</option>
                        <option value="facility">Facility</option>
                        <option value="drop_point">Drop Point</option>
                        <option value="bin">Bin</option>
                        <option value="department">Department</option>
                        <option value="room">Room</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Zone Type</label>
                    <select id="new_loc_zone_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="general">General</option>
                        <option value="liquid">Liquid</option>
                        <option value="illiquid">Illiquid</option>
                        <option value="climate_controlled">Climate Controlled</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Capacity</label>
                    <input type="number" id="new_loc_capacity" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            <div class="flex items-center mb-4">
                <input type="checkbox" id="new_loc_supports_fixed" class="mr-2">
                <label class="text-sm font-medium text-gray-700">Supports Fixed Items</label>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" id="cancelAddLocationModal" class="px-4 py-2 bg-gray-200 rounded-lg">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Save Location</button>
            </div>
        </form>
    </div>
</div>

<!-- View Categories Modal -->
<div id="viewCategoriesModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-5xl max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Inventory Categories</h3>
            <button id="addNewCategoryBtn" class="btn btn-primary btn-sm text-white">
                <i class='bx bx-plus mr-1'></i>Add New Category
            </button>
        </div>

        <div class="overflow-x-auto mb-4 border rounded-lg">
            <table class="table table-zebra w-full">
                <thead>
                    <tr class="bg-gray-700 text-white">
                        <th class="whitespace-nowrap">Category ID</th>
                        <th class="whitespace-nowrap">Name</th>
                        <th class="whitespace-nowrap">Description</th>
                        <th class="whitespace-nowrap">Created At</th>
                        <th class="whitespace-nowrap">Action</th>
                    </tr>
                </thead>
                <tbody id="categoriesTableBody">
                    <tr>
                        <td colspan="4" class="text-center py-8 text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class='bx bx-fw bx-category-alt text-4xl text-gray-400 mb-2'></i>
                                <p>No categories found</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div id="categoriesPager" class="flex items-center justify-between mt-3">
            <div id="categoriesPagerInfo" class="text-sm text-gray-600"></div>
            <div class="join">
                <button class="btn btn-sm join-item" id="categoriesPrevBtn" data-action="prev">Prev</button>
                <span class="btn btn-sm join-item" id="categoriesPageDisplay">1 / 1</span>
                <button class="btn btn-sm join-item" id="categoriesNextBtn" data-action="next">Next</button>
            </div>
        </div>

        <div class="flex justify-end gap-2 mt-4">
            <button id="closeViewCategoriesModalBtn" class="btn btn-ghost">Close</button>
        </div>
    </div>
</div>

<!-- Add Category Modal -->
<div id="addCategoryModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-[60]">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Add New Category</h3>
            <button id="closeAddCategoryModal" class="text-gray-500 hover:text-gray-700">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>
        <form id="addCategoryForm">
            <input type="hidden" id="edit_cat_id">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Category Name <span class="text-red-500">*</span></label>
                <input type="text" id="new_cat_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary" placeholder="e.g., Electronics" required>
            </div>
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea id="new_cat_description" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-primary" rows="3" placeholder="Category description..."></textarea>
            </div>
            <div class="flex justify-end gap-2">
                <button type="button" id="cancelAddCategoryModal" class="btn btn-ghost">Cancel</button>
                <button type="submit" class="btn btn-primary text-white">Save Category</button>
            </div>
        </form>
    </div>
</div>

<!-- Digital Inventory Report Modal -->
<div id="di_report_modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-4xl max-h-[85vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Generate Digital Inventory Report</h3>
            <button id="di_report_close" class="text-gray-500 hover:text-gray-700"><i class='bx bx-x text-2xl'></i></button>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Select Range</label>
                <select id="di_report_range" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    <option value="week">This Week</option>
                    <option value="month">This Month</option>
                    <option value="year">This Year</option>
                    <option value="custom">Custom</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">From</label>
                <input id="di_report_from" type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg" />
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">To</label>
                <input id="di_report_to" type="date" class="w-full px-3 py-2 border border-gray-300 rounded-lg" />
            </div>
        </div>
        <div class="flex justify-end gap-3 mb-4">
            <button id="di_report_preview" class="px-4 py-2 bg-gray-700 text-white rounded-lg"><i class='bx bx-show-alt mr-2'></i>Preview Report</button>
            <button id="di_report_clear" class="px-4 py-2 bg-gray-300 text-gray-800 rounded-lg"><i class='bx bx-eraser mr-2'></i>Clear Preview</button>
            <button id="di_report_download" class="px-4 py-2 bg-blue-600 text-white rounded-lg"><i class='bx bx-download mr-2'></i>Download PDF</button>
            <button id="di_report_print" class="px-4 py-2 bg-green-600 text-white rounded-lg"><i class='bx bx-printer mr-2'></i>Print Report</button>
        </div>
        <div id="di_report_preview_container" class="border rounded-lg p-4 bg-gray-50">
            <div class="flex items-center justify-center text-gray-500">
                <i class='bx bx-show-alt text-3xl mr-2'></i>
                <span>No preview yet</span>
            </div>
        </div>
    </div>
 </div>

<!-- Downloading Modal -->
<div id="di_download_modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-sm">
        <div class="flex items-center">
            <div class="loading loading-spinner mr-3"></div>
            <div>
                <h4 class="font-semibold">Preparing PDF...</h4>
                <p class="text-sm text-gray-600">Please wait while we generate your report.</p>
            </div>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div id="di_success_modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-sm">
        <div class="flex items-center">
            <i class='bx bx-check-circle text-3xl text-green-600 mr-3'></i>
            <div>
                <h4 class="font-semibold">Report Downloaded</h4>
                <p class="text-sm text-gray-600">Your PDF report has been saved.</p>
            </div>
        </div>
        <div class="flex justify-end mt-4">
            <button id="di_success_close" class="px-4 py-2 bg-green-600 text-white rounded-lg">OK</button>
        </div>
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
                    <select id="edit_item_stored_from" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Warehouse</option>
                    </select>
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
var PSM_PURCHASES_API = `${API_BASE_URL}/psm/purchase-management`;
var SWS_ITEMS_API = `${API_BASE_URL}/sws/items`;
var completedPurchases = [];
var SWS_CATEGORIES_API = `${API_BASE_URL}/sws/categories`;
var SWS_WAREHOUSES_API = `${API_BASE_URL}/sws/warehouse`;
var SWS_INVENTORY_STATS_API = `${API_BASE_URL}/sws/inventory-stats`;
var SWS_STOCK_LEVELS_API = `${API_BASE_URL}/sws/stock-levels`;
var SWS_LOCATIONS_API = `${API_BASE_URL}/sws/locations`;
var SWS_DIGITAL_INVENTORY_REPORT_API = `${API_BASE_URL}/sws/digital-inventory/report`;
var CSRF_TOKEN = typeof CSRF_TOKEN !== 'undefined' ? CSRF_TOKEN : document.querySelector('meta[name="csrf-token"]').getAttribute('content');
// Prioritize server-injected token, then global window token, then localStorage
var JWT_TOKEN = '{{ $jwtToken ?? "" }}';
if (!JWT_TOKEN && typeof window.SERVER_JWT_TOKEN !== 'undefined') {
    JWT_TOKEN = window.SERVER_JWT_TOKEN;
}
if (!JWT_TOKEN) {
    JWT_TOKEN = localStorage.getItem('jwt');
}

var inventoryItems = [];
let currentDiPage = 1;
const diPageSize = 10;
var categories = [];
var locations = [];
let currentLocationsPage = 1;
const locationsPageSize = 10;
let currentCategoriesPage = 1;
const categoriesPageSize = 10;

    const els = {
    // Stock Levels
    stockLevelsContainer: document.getElementById('stockLevelsContainer'),
    
    // Quick Actions
    transferBtn: document.getElementById('transferBtn'),
    viewLocationsBtn: document.getElementById('viewLocationsBtn'),
    viewCategoriesBtn: document.getElementById('viewCategoriesBtn'),
    generateReportBtn: document.getElementById('generateReportBtn'),
    purchaseNewItemBtn: document.getElementById('purchaseNewItemBtn'),
    inventoryNewItemBtn: document.getElementById('inventoryNewItemBtn'),
    
    // Stats Cards
    totalItems: document.getElementById('totalItems'),
    totalValue: document.getElementById('totalValue'),
    lowStockItems: document.getElementById('lowStockItems'),
    outOfStockItems: document.getElementById('outOfStockItems'),
        diSearch: document.getElementById('di_search'),
    
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
    editItemForm: document.getElementById('editItemForm'),
    transferItemModal: document.getElementById('transferItemModal'),
    closeTransferItemModal: document.getElementById('closeTransferItemModal'),
    cancelTransferItemModal: document.getElementById('cancelTransferItemModal'),
    transferItemForm: document.getElementById('transferItemForm'),
    transferItemDetails: document.getElementById('transferItemDetails'),
    transferItemSelect: document.getElementById('transfer_item_id'),
    transferLocationFrom: document.getElementById('transfer_location_from'),
    transferLocationTo: document.getElementById('transfer_location_to'),
    transferUnits: document.getElementById('transfer_units'),
    transferUnitsWarning: document.getElementById('transfer_units_warning'),
    
    // View Locations Modal
    viewLocationsModal: document.getElementById('viewLocationsModal'),
    closeViewLocationsModal: document.getElementById('closeViewLocationsModal'),
    closeViewLocationsModalBtn: document.getElementById('closeViewLocationsModalBtn'),
    locationsTableBody: document.getElementById('locationsTableBody'),
    locationsPagerInfo: document.getElementById('locationsPagerInfo'),
    locationsPageDisplay: document.getElementById('locationsPageDisplay'),
    locationsPrevBtn: document.getElementById('locationsPrevBtn'),
    locationsNextBtn: document.getElementById('locationsNextBtn'),
    addNewLocationBtn: document.getElementById('addNewLocationBtn'),
    
    // Add Location Modal
    addLocationModal: document.getElementById('addLocationModal'),
    closeAddLocationModal: document.getElementById('closeAddLocationModal'),
    cancelAddLocationModal: document.getElementById('cancelAddLocationModal'),
    addLocationForm: document.getElementById('addLocationForm'),
    newLocId: document.getElementById('new_loc_id'),

    // View Location Modal
    viewLocationModal: document.getElementById('viewLocationModal'),
    closeViewLocationModal: document.getElementById('closeViewLocationModal'),
    closeViewLocationModalBtn: document.getElementById('closeViewLocationModalBtn'),
    viewLocationContent: document.getElementById('viewLocationContent'),

    // Edit Location Modal
    editLocationModal: document.getElementById('editLocationModal'),
    closeEditLocationModal: document.getElementById('closeEditLocationModal'),
    cancelEditLocationModal: document.getElementById('cancelEditLocationModal'),
    editLocationForm: document.getElementById('editLocationForm'),

    // View Categories Modal
    viewCategoriesModal: document.getElementById('viewCategoriesModal'),
    closeViewCategoriesModal: document.getElementById('closeViewCategoriesModal'),
    closeViewCategoriesModalBtn: document.getElementById('closeViewCategoriesModalBtn'),
    categoriesTableBody: document.getElementById('categoriesTableBody'),
    categoriesPagerInfo: document.getElementById('categoriesPagerInfo'),
    categoriesPageDisplay: document.getElementById('categoriesPageDisplay'),
    categoriesPrevBtn: document.getElementById('categoriesPrevBtn'),
    categoriesNextBtn: document.getElementById('categoriesNextBtn'),
    addNewCategoryBtn: document.getElementById('addNewCategoryBtn'),

    // Add Category Modal
    addCategoryModal: document.getElementById('addCategoryModal'),
    closeAddCategoryModal: document.getElementById('closeAddCategoryModal'),
    cancelAddCategoryModal: document.getElementById('cancelAddCategoryModal'),
    addCategoryForm: document.getElementById('addCategoryForm')
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

function generateLocationId() {
    const now = new Date();
    const year = now.getFullYear();
    const month = String(now.getMonth() + 1).padStart(2, '0');
    const day = String(now.getDate()).padStart(2, '0');
    
    // Generate 5 random alphanumeric characters
    const chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let randomPart = '';
    for (let i = 0; i < 5; i++) {
        randomPart += chars.charAt(Math.floor(Math.random() * chars.length));
    }
    
    // Final ID: LCTN + YYYYMMDD + 5 random chars
    return `LCTN${year}${month}${day}${randomPart}`;
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
    let filtered = inventoryItems.slice();
    const statusVal = (window.diActiveStatus || '').trim();
    const q = (els.diSearch?.value || '').trim().toLowerCase();
    if (statusVal) {
        filtered = filtered.filter(i => (i.status || '').toLowerCase() === statusVal.toLowerCase());
    }
    if (q) {
        filtered = filtered.filter(i => {
            const hay = [
                i.item_name || '',
                i.item_code || '',
                i.category || '',
                i.item_stored_from || ''
            ].join(' ').toLowerCase();
            return hay.includes(q);
        });
    }
    const total = filtered.length;
    if (total === 0) {
        els.inventoryTableBody.innerHTML = `
            <tr>
                <td colspan="12" class="text-center py-8 text-gray-500">
                    <div class="flex flex-col items-center justify-center">
                        <i class='bx bx-fw bxs-store text-4xl text-gray-400 mb-2'></i>
                        <p>No inventory items found</p>
                    </div>
                </td>
            </tr>
        `;
        renderDiPager(0, 1);
        return;
    }
    const totalPages = Math.max(1, Math.ceil(total / diPageSize));
    if (currentDiPage > totalPages) currentDiPage = totalPages;
    if (currentDiPage < 1) currentDiPage = 1;
    const startIdx = (currentDiPage - 1) * diPageSize;
    const pageItems = filtered.slice(startIdx, startIdx + diPageSize);

    let html = '';
    pageItems.forEach(item => {
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
        const statusIcon = (status === 'In Stock') ? "<i class='bx bx-check-circle mr-1'></i>" :
                           (status === 'Low Stock') ? "<i class='bx bx-error mr-1'></i>" :
                           (status === 'Out of Stock') ? "<i class='bx bx-error-circle mr-1'></i>" :
                           "<i class='bx bx-help-circle mr-1 text-gray-600'></i>";
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
                    <span class="badge ${statusClass} whitespace-nowrap flex items-center">
                        ${statusIcon}${status}
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
    renderDiPager(total, totalPages);
    
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

function renderDiPager(total, totalPages){
    const info = document.getElementById('diPagerInfo');
    const display = document.getElementById('diPageDisplay');
    const start = total === 0 ? 0 : ((currentDiPage - 1) * diPageSize) + 1;
    const end = Math.min(currentDiPage * diPageSize, total);
    if (info) info.textContent = `Showing ${start}-${end} of ${total}`;
    if (display) display.textContent = `${currentDiPage} / ${totalPages}`;
    const prev = document.getElementById('diPrevBtn');
    const next = document.getElementById('diNextBtn');
    if (prev) prev.disabled = currentDiPage <= 1;
    if (next) next.disabled = currentDiPage >= totalPages;
}

function getDiFiltered(){
    let filtered = (inventoryItems || []).slice();
    const statusVal = (window.diActiveStatus || '').trim();
    const q = (els.diSearch?.value || '').trim().toLowerCase();
    if (statusVal) {
        filtered = filtered.filter(i => (i.status || '').toLowerCase() === statusVal.toLowerCase());
    }
    if (q) {
        filtered = filtered.filter(i => {
            const hay = [i.item_name || '', i.item_code || '', i.category || '', i.item_stored_from || ''].join(' ').toLowerCase();
            return hay.includes(q);
        });
    }
    return filtered;
}

document.getElementById('diPager').addEventListener('click', function(ev){
    const btn = ev.target.closest('button[data-action]');
    if(!btn) return;
    const act = btn.getAttribute('data-action');
    if(act === 'prev'){ currentDiPage = Math.max(1, currentDiPage - 1); renderInventoryItems(); }
    if(act === 'next'){ const max = Math.max(1, Math.ceil((getDiFiltered().length||0)/diPageSize)); currentDiPage = Math.min(max, currentDiPage + 1); renderInventoryItems(); }
});

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

async function loadWarehouses() {
    try {
        const response = await fetch(SWS_WAREHOUSES_API, {
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
        const data = (result.data || []).filter(w => String(w.ware_status).toLowerCase() === 'active');
        const addSelect = document.getElementById('item_stored_from');
        const editSelect = document.getElementById('edit_item_stored_from');
        const optionsHtml = ['<option value="">Select Warehouse</option>'].concat(
            data.map(w => `<option value="${w.ware_name}">${w.ware_name}</option>`)
        ).join('');
        if (addSelect) addSelect.innerHTML = optionsHtml;
        if (editSelect) editSelect.innerHTML = optionsHtml;
    } catch (e) {
        const addSelect = document.getElementById('item_stored_from');
        const editSelect = document.getElementById('edit_item_stored_from');
        const fallback = '<option value="">Select Warehouse</option>';
        if (addSelect) addSelect.innerHTML = fallback;
        if (editSelect) editSelect.innerHTML = fallback;
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

document.getElementById('locationsPager')?.addEventListener('click', function(ev){
    const btn = ev.target.closest('button[data-action]');
    if (!btn) return;
    const act = btn.getAttribute('data-action');
    if (act === 'prev') {
        currentLocationsPage = Math.max(1, currentLocationsPage - 1);
        renderLocationsTable();
    }
    if (act === 'next') {
        const max = Math.max(1, Math.ceil((locations.length || 0) / locationsPageSize));
        currentLocationsPage = Math.min(max, currentLocationsPage + 1);
        renderLocationsTable();
    }
});

document.getElementById('categoriesPager')?.addEventListener('click', function(ev){
    const btn = ev.target.closest('button[data-action]');
    if (!btn) return;
    const act = btn.getAttribute('data-action');
    if (act === 'prev') {
        currentCategoriesPage = Math.max(1, currentCategoriesPage - 1);
        renderCategoriesTable();
    }
    if (act === 'next') {
        const max = Math.max(1, Math.ceil((categories.length || 0) / categoriesPageSize));
        currentCategoriesPage = Math.min(max, currentCategoriesPage + 1);
        renderCategoriesTable();
    }
});

// Modal Functions
function openUnderDevelopmentModal() {
    els.underDevelopmentModal.classList.remove('hidden');
}

function closeUnderDevelopmentModal() {
    els.underDevelopmentModal.classList.add('hidden');
}

async function fetchCompletedPurchases() {
    try {
        const response = await fetch(`${PSM_PURCHASES_API}?status=Completed`, {
             headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            }
        });
        const result = await response.json();
        if (result.success) {
            completedPurchases = result.data;
            populateItemNameDropdown();
        }
    } catch (e) {
        console.error('Error fetching purchases:', e);
    }
}

function populateItemNameDropdown() {
    const select = document.getElementById('item_name');
    if (!select) return;
    const currentVal = select.value;
    select.innerHTML = '<option value="">Select Item from Completed Purchase</option>';
    
    completedPurchases.forEach(purchase => {
        let items = [];
        try {
            items = typeof purchase.pur_name_items === 'string' ? JSON.parse(purchase.pur_name_items) : purchase.pur_name_items;
        } catch(e) { items = []; }
        if (!Array.isArray(items)) items = [];
        
        items.forEach((item, index) => {
             // Skip if already added to inventory
             if (item.in_inventory) return;

             const option = document.createElement('option');
             option.value = `${purchase.pur_id}|${index}`;
             option.textContent = `${item.name} (PO: ${purchase.pur_id})`;
             option.dataset.purchaseId = purchase.pur_id;
             option.dataset.itemIndex = index;
             option.dataset.itemName = item.name;
             select.appendChild(option);
        });
    });
    if (currentVal) select.value = currentVal;
}

function onPurchaseItemSelected(e) {
    const val = e.target.value;
    const skuInput = document.getElementById('item_stock_keeping_unit');
    const categorySelect = document.getElementById('item_category_id');
    
    // Clear hidden fields initially
    document.getElementById('psm_purchase_id').value = '';
    document.getElementById('psm_item_index').value = '';
    
    if (!val) return;
    
    const [purId, itemIndex] = val.split('|');
    
    // Set hidden fields
    document.getElementById('psm_purchase_id').value = purId;
    document.getElementById('psm_item_index').value = itemIndex;

    const purchase = completedPurchases.find(p => p.pur_id === purId);
    if (!purchase) return;
    
    let items = typeof purchase.pur_name_items === 'string' ? JSON.parse(purchase.pur_name_items) : purchase.pur_name_items;
    const item = items[parseInt(itemIndex)];
    
    if (item) {
         document.getElementById('item_description').value = purchase.pur_desc || '';
         document.getElementById('item_current_stock').value = purchase.pur_unit || 0;
         document.getElementById('item_unit_price').value = purchase.pur_total_amount || 0;

         // Populate Expiration Date
         const expInput = document.getElementById('item_expiration_date');
         if (item.expiration) {
             expInput.value = item.expiration.split('T')[0];
         } else {
             expInput.value = '';
         }

         // Populate Warranty End
         const warrantyInput = document.getElementById('item_warranty_end');
         if (item.warranty) {
             if (item.warranty.match(/^\d{4}-\d{2}-\d{2}$/)) {
                 warrantyInput.value = item.warranty;
             } else {
                 const startDate = new Date(purchase.created_at || Date.now());
                 const warrantyStr = item.warranty.toLowerCase();
                 let monthsToAdd = 0;
                 if (warrantyStr.includes('year')) {
                     const years = parseInt(warrantyStr) || 0;
                     monthsToAdd = years * 12;
                 } else if (warrantyStr.includes('month')) {
                     monthsToAdd = parseInt(warrantyStr) || 0;
                 } else if (warrantyStr.includes('day')) {
                     const days = parseInt(warrantyStr) || 0;
                     startDate.setDate(startDate.getDate() + days);
                 }
                 if (monthsToAdd > 0) {
                     startDate.setMonth(startDate.getMonth() + monthsToAdd);
                 }
                 if (monthsToAdd > 0 || warrantyStr.includes('day')) {
                     warrantyInput.value = startDate.toISOString().split('T')[0];
                 } else {
                     warrantyInput.value = '';
                 }
             }
         } else {
             warrantyInput.value = '';
         }
         
         if (purchase.pur_ven_type) {
             for (let i = 0; i < categorySelect.options.length; i++) {
                 const opt = categorySelect.options[i];
                 if (opt.text.toLowerCase().includes(purchase.pur_ven_type.toLowerCase())) {
                     categorySelect.value = opt.value;
                     break;
                 }
             }
         }
         
         if (!skuInput.value) {
             const categoryName = categorySelect.options[categorySelect.selectedIndex]?.text || '';
             skuInput.value = generateSKU(item.name, categoryName);
         }
    }
}

function openAddItemModal() {
    els.addItemModal.classList.remove('hidden');
    els.addItemForm.reset();
    document.getElementById('item_is_fixed').checked = false;
    document.getElementById('item_is_collateral').checked = false;
    
    updateItemCodePreview();
    
    fetchCompletedPurchases();
    
    const itemNameSelect = document.getElementById('item_name');
    const skuInput = document.getElementById('item_stock_keeping_unit');
    
    itemNameSelect.onchange = onPurchaseItemSelected;
    
    const categorySelect = document.getElementById('item_category_id');
    categorySelect.onchange = function() {
        if (!skuInput.value && itemNameSelect.value) {
             const selectedOption = itemNameSelect.options[itemNameSelect.selectedIndex];
             const itemName = selectedOption ? (selectedOption.dataset.itemName || selectedOption.text) : '';
             const categoryName = this.options[this.selectedIndex]?.text || '';
             if (itemName) {
                skuInput.value = generateSKU(itemName, categoryName);
             }
        }
    };
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
    
    const itemNameSelect = document.getElementById('item_name');
    if (!itemNameSelect.value) {
        notify('Please select an item', 'error');
        return;
    }
    const selectedOption = itemNameSelect.options[itemNameSelect.selectedIndex];
    const itemName = selectedOption ? (selectedOption.dataset.itemName || selectedOption.text) : '';

    const formData = {
        item_name: itemName,
        psm_purchase_id: document.getElementById('psm_purchase_id').value || null,
        psm_item_index: document.getElementById('psm_item_index').value || null,
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
    if (!formData.item_category_id) {
        notify('Please select category', 'error');
        return;
    }
    if (!formData.item_stored_from) {
        notify('Please select stored from', 'error');
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
    if (formData.item_unit_price === null || isNaN(formData.item_unit_price) || formData.item_unit_price < 0) {
        notify('Please enter a valid unit price', 'error');
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
        loadCategories(),
        loadWarehouses(),
        loadLocations()
    ]);
    
    // Quick Actions Event Listeners
    if (els.transferBtn) els.transferBtn.addEventListener('click', openTransferModal);
    if (els.viewLocationsBtn) els.viewLocationsBtn.addEventListener('click', openViewLocationsModal);
    if (els.viewCategoriesBtn) els.viewCategoriesBtn.addEventListener('click', openViewCategoriesModal);
    if (els.closeViewLocationsModalBtn) els.closeViewLocationsModalBtn.addEventListener('click', closeViewLocationsModalFunc);

    // View Location Details Modal Listeners
    if (els.closeViewLocationModal) els.closeViewLocationModal.addEventListener('click', () => els.viewLocationModal.classList.add('hidden'));
    if (els.closeViewLocationModalBtn) els.closeViewLocationModalBtn.addEventListener('click', () => els.viewLocationModal.classList.add('hidden'));
    if (els.viewLocationModal) {
        els.viewLocationModal.addEventListener('click', (e) => {
            if (e.target === els.viewLocationModal) els.viewLocationModal.classList.add('hidden');
        });
    }

    // Edit Location Modal Listeners
    if (els.closeEditLocationModal) els.closeEditLocationModal.addEventListener('click', () => els.editLocationModal.classList.add('hidden'));
    if (els.cancelEditLocationModal) els.cancelEditLocationModal.addEventListener('click', () => els.editLocationModal.classList.add('hidden'));
    if (els.editLocationModal) {
        els.editLocationModal.addEventListener('click', (e) => {
            if (e.target === els.editLocationModal) els.editLocationModal.classList.add('hidden');
        });
    }
    if (els.editLocationForm) els.editLocationForm.addEventListener('submit', updateLocation);
    
    // Add Location Modal Listeners
    if(els.addNewLocationBtn) els.addNewLocationBtn.addEventListener('click', openAddLocationModal);
    if(els.closeAddLocationModal) els.closeAddLocationModal.addEventListener('click', closeAddLocationModalFunc);
    if(els.cancelAddLocationModal) els.cancelAddLocationModal.addEventListener('click', closeAddLocationModalFunc);
    if(els.addLocationModal) {
        els.addLocationModal.addEventListener('click', function(e) {
            if (e.target === els.addLocationModal) closeAddLocationModalFunc();
        });
    }
    if(els.addLocationForm) {
        els.addLocationForm.addEventListener('submit', saveLocation);
    }

    if (els.closeViewCategoriesModal) els.closeViewCategoriesModal.addEventListener('click', closeViewCategoriesModalFunc);
    if (els.closeViewCategoriesModalBtn) els.closeViewCategoriesModalBtn.addEventListener('click', closeViewCategoriesModalFunc);

    // Add Category Modal Listeners
    if (els.addNewCategoryBtn) els.addNewCategoryBtn.addEventListener('click', openAddCategoryModal);
    if (els.closeAddCategoryModal) els.closeAddCategoryModal.addEventListener('click', closeAddCategoryModalFunc);
    if (els.cancelAddCategoryModal) els.cancelAddCategoryModal.addEventListener('click', closeAddCategoryModalFunc);
    if (els.addCategoryModal) {
        els.addCategoryModal.addEventListener('click', (e) => {
            if (e.target === els.addCategoryModal) closeAddCategoryModalFunc();
        });
    }
    if (els.addCategoryForm) els.addCategoryForm.addEventListener('submit', saveCategory);

    if (els.generateReportBtn) els.generateReportBtn.addEventListener('click', openDigitalInventoryReportModal);
    if (els.purchaseNewItemBtn) els.purchaseNewItemBtn.addEventListener('click', openUnderDevelopmentModal);
    if (els.inventoryNewItemBtn) els.inventoryNewItemBtn.addEventListener('click', openAddItemModal);
    
    // Modal Event Listeners
    if (els.closeUnderDevelopmentModal) els.closeUnderDevelopmentModal.addEventListener('click', closeUnderDevelopmentModal);
    if (els.confirmUnderDevelopmentModal) els.confirmUnderDevelopmentModal.addEventListener('click', closeUnderDevelopmentModal);
    if (els.underDevelopmentModal) {
        els.underDevelopmentModal.addEventListener('click', function(e) {
            if (e.target === els.underDevelopmentModal) closeUnderDevelopmentModal();
        });
    }
    
    if (els.closeAddItemModal) els.closeAddItemModal.addEventListener('click', closeAddItemModal);
    if (els.cancelAddItemModal) els.cancelAddItemModal.addEventListener('click', closeAddItemModal);
    if (els.addItemModal) {
        els.addItemModal.addEventListener('click', function(e) {
            if (e.target === els.addItemModal) closeAddItemModal();
        });
    }
    if (els.addItemForm) els.addItemForm.addEventListener('submit', saveItem);
    
    if (els.closeTransferItemModal) els.closeTransferItemModal.addEventListener('click', closeTransferModal);
    if (els.cancelTransferItemModal) els.cancelTransferItemModal.addEventListener('click', closeTransferModal);
    if (els.transferItemModal) {
        els.transferItemModal.addEventListener('click', function(e) { if (e.target === els.transferItemModal) closeTransferModal(); });
    }
    if (els.transferItemSelect) els.transferItemSelect.addEventListener('change', onTransferItemSelectChange);
    if (els.transferUnits) els.transferUnits.addEventListener('input', validateTransferUnits);
    if (els.transferItemForm) els.transferItemForm.addEventListener('submit', submitTransfer);
    
    if (els.closeViewItemModal) els.closeViewItemModal.addEventListener('click', closeViewItemModal);
    if (els.viewItemModal) {
        els.viewItemModal.addEventListener('click', function(e) {
            if (e.target === els.viewItemModal) closeViewItemModal();
        });
    }
    
    if (els.closeEditItemModal) els.closeEditItemModal.addEventListener('click', closeEditItemModal);
    if (els.cancelEditItemModal) els.cancelEditItemModal.addEventListener('click', closeEditItemModal);
    if (els.editItemModal) {
        els.editItemModal.addEventListener('click', function(e) {
            if (e.target === els.editItemModal) closeEditItemModal();
        });
    }
    if (els.editItemForm) els.editItemForm.addEventListener('submit', updateItem);

    // Filters
    if (els.diSearch) els.diSearch.addEventListener('input', () => { currentDiPage = 1; renderInventoryItems(); });
    
    document.querySelectorAll('[data-di-status]').forEach(btn => {
        btn.addEventListener('click', () => {
            const val = btn.getAttribute('data-di-status');
            window.diActiveStatus = val;
            document.querySelectorAll('[data-di-status]').forEach(b => b.classList.remove('bg-gray-200'));
            btn.classList.add('bg-gray-200');
            currentDiPage = 1; renderInventoryItems();
        });
    });
    
    // Stat cards toggle filter
    const diCardLow = document.getElementById('di_card_low');
    if (diCardLow) {
        diCardLow.addEventListener('click', () => {
            window.diActiveStatus = 'Low Stock';
            document.querySelectorAll('[data-di-status]').forEach(b => b.classList.remove('bg-gray-200'));
            const target = document.querySelector('[data-di-status="Low Stock"]'); if (target) target.classList.add('bg-gray-200');
            currentDiPage = 1; renderInventoryItems();
        });
    }

    const diCardOut = document.getElementById('di_card_out');
    if (diCardOut) {
        diCardOut.addEventListener('click', () => {
            window.diActiveStatus = 'Out of Stock';
            document.querySelectorAll('[data-di-status]').forEach(b => b.classList.remove('bg-gray-200'));
            const target = document.querySelector('[data-di-status="Out of Stock"]'); if (target) target.classList.add('bg-gray-200');
            currentDiPage = 1; renderInventoryItems();
        });
    }

    const diCardTotal = document.getElementById('di_card_total');
    if (diCardTotal) {
        diCardTotal.addEventListener('click', () => {
            window.diActiveStatus = '';
            document.querySelectorAll('[data-di-status]').forEach(b => b.classList.remove('bg-gray-200'));
            const target = document.querySelector('[data-di-status=""]'); if (target) target.classList.add('bg-gray-200');
            currentDiPage = 1; renderInventoryItems();
        });
    }
}

// Initialize when DOM is loaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initDigitalInventory);
} else {
    initDigitalInventory();
}
function openViewLocationsModal() {
    els.viewLocationsModal.classList.remove('hidden');
    currentLocationsPage = 1;
    fetchLocationsForTable();
}

function closeViewLocationsModalFunc() {
    els.viewLocationsModal.classList.add('hidden');
}

function openAddLocationModal() {
    els.addLocationForm.reset();
    if(els.newLocId) els.newLocId.value = generateLocationId();
    els.addLocationModal.classList.remove('hidden');
}

async function saveLocation(e) {
    e.preventDefault();
    
    const id = document.getElementById('new_loc_id').value;
    const name = document.getElementById('new_loc_name').value.trim();
    const typeInput = document.getElementById('new_loc_type').value.trim();
    const zoneInput = document.getElementById('new_loc_zone_type').value.trim();
    const capacityInput = document.getElementById('new_loc_capacity').value;
    const supportsFixed = document.getElementById('new_loc_supports_fixed').checked;
    
    const payload = {
        loc_id: id,
        loc_name: name,
        loc_type: typeInput !== '' ? typeInput : null,
        loc_zone_type: zoneInput !== '' ? zoneInput : null,
        loc_capacity: capacityInput !== '' ? Number(capacityInput) : null,
        loc_supports_fixed_items: supportsFixed ? 1 : 0,
        loc_is_active: 1, // Default to active
        loc_parent_id: null
    };
    
    try {
        const response = await fetch(SWS_LOCATIONS_API, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            body: JSON.stringify(payload),
            credentials: 'include'
        });
        
        if (!response.ok) {
            if (response.status === 422) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Validation failed');
            }
            throw new Error(`HTTP ${response.status}`);
        }
        const result = await response.json();
        
        if (result.success) {
            notify('Location added successfully', 'success');
            closeAddLocationModalFunc();
            fetchLocationsForTable();
        } else {
            notify(result.message || 'Error adding location', 'error');
        }
    } catch (e) {
        console.error('Error adding location:', e);
        notify(e.message || 'Error adding location', 'error');
    }
}

function closeViewLocationModalFunc() {
    els.viewLocationModal.classList.add('hidden');
    els.viewLocationContent.innerHTML = '';
}

function openEditLocationModal(locationId) {
    const location = locations.find(l => l.loc_id === locationId);
    if (!location) return;

    document.getElementById('edit_loc_id').value = location.loc_id;
    document.getElementById('edit_loc_name').value = location.loc_name;
    document.getElementById('edit_loc_type').value = location.loc_type;
    document.getElementById('edit_loc_zone_type').value = location.loc_zone_type;
    document.getElementById('edit_loc_capacity').value = location.loc_capacity || '';
    
    // Set Radio Buttons
    const fixedItemsRadios = document.getElementsByName('edit_loc_supports_fixed_items');
    for(let radio of fixedItemsRadios) {
        if(radio.value == (location.loc_supports_fixed_items ? 1 : 0)) radio.checked = true;
    }

    const activeRadios = document.getElementsByName('edit_loc_is_active');
    for(let radio of activeRadios) {
        if(radio.value == (location.loc_is_active ? 1 : 0)) radio.checked = true;
    }

    els.editLocationModal.classList.remove('hidden');
}

function closeEditLocationModalFunc() {
    els.editLocationModal.classList.add('hidden');
    els.editLocationForm.reset();
}

async function updateLocation(e) {
    e.preventDefault();
    const locationId = document.getElementById('edit_loc_id').value;
    
    const formData = new FormData(els.editLocationForm);
    const payload = {
        loc_name: formData.get('edit_loc_name'), // Changed from getElementById to FormData usage or ensure ID matches
        loc_type: document.getElementById('edit_loc_type').value,
        loc_zone_type: document.getElementById('edit_loc_zone_type').value,
        loc_capacity: document.getElementById('edit_loc_capacity').value || null,
        loc_supports_fixed_items: document.querySelector('input[name="edit_loc_supports_fixed_items"]:checked').value === '1',
        loc_is_active: document.querySelector('input[name="edit_loc_is_active"]:checked').value === '1'
    };
    // Correcting payload construction since I used IDs in openEditLocationModal but formData in updateLocation might be safer or consistent
    // Let's be consistent with manual retrieval as I did above
    
    const finalPayload = {
        loc_name: document.getElementById('edit_loc_name').value,
        loc_type: document.getElementById('edit_loc_type').value,
        loc_zone_type: document.getElementById('edit_loc_zone_type').value,
        loc_capacity: document.getElementById('edit_loc_capacity').value || null,
        loc_supports_fixed_items: document.querySelector('input[name="edit_loc_supports_fixed_items"]:checked').value === '1',
        loc_is_active: document.querySelector('input[name="edit_loc_is_active"]:checked').value === '1'
    };

    try {
        const response = await fetch(`${SWS_LOCATIONS_API}/${locationId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            body: JSON.stringify(finalPayload),
            credentials: 'include'
        });
        
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        const result = await response.json();
        
        if (result.success) {
            notify('Location updated successfully', 'success');
            closeEditLocationModalFunc();
            fetchLocationsForTable();
        } else {
            notify(result.message || 'Error updating location', 'error');
        }
    } catch (e) {
        console.error('Error updating location:', e);
        notify('Error updating location', 'error');
    }
}

async function deleteLocation(locationId) {
    const confirmResult = await Swal.fire({
        title: 'Delete Location?',
        text: 'This action cannot be undone',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel'
    });
    
    if (!confirmResult.isConfirmed) return;
    
    try {
        const response = await fetch(`${SWS_LOCATIONS_API}/${locationId}`, {
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
            notify('Location deleted successfully', 'success');
            fetchLocationsForTable();
        } else {
            notify(result.message || 'Error deleting location', 'error');
        }
    } catch (e) {
        console.error('Error deleting location:', e);
        notify('Error deleting location', 'error');
    }
}

async function fetchLocationsForTable() {
    els.locationsTableBody.innerHTML = '<tr><td colspan="11" class="text-center py-4"><span class="loading loading-spinner loading-md text-primary"></span></td></tr>';
    try {
        const response = await fetch(SWS_LOCATIONS_API, {
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
        locations = result.success ? (result.data || []) : [];
        renderLocationsTable();
    } catch (e) {
        console.error('Error loading locations:', e);
        els.locationsTableBody.innerHTML = '<tr><td colspan="11" class="text-center py-4 text-red-600">Error loading locations</td></tr>';
        if (els.locationsPagerInfo) els.locationsPagerInfo.textContent = '';
        if (els.locationsPageDisplay) els.locationsPageDisplay.textContent = '1 / 1';
        notify('Error loading locations', 'error');
    }
}

function renderLocationsTable() {
    const total = locations.length;
    if (!total) {
        els.locationsTableBody.innerHTML = `
            <tr>
                <td colspan="11" class="text-center py-8 text-gray-500">
                    <div class="flex flex-col items-center justify-center">
                        <i class='bx bx-fw bx-map-alt text-4xl text-gray-400 mb-2'></i>
                        <p>No locations found</p>
                    </div>
                </td>
            </tr>
        `;
        renderLocationsPager(0, 1);
        return;
    }

    const totalPages = Math.max(1, Math.ceil(total / locationsPageSize));
    if (currentLocationsPage > totalPages) currentLocationsPage = totalPages;
    if (currentLocationsPage < 1) currentLocationsPage = 1;
    const startIdx = (currentLocationsPage - 1) * locationsPageSize;
    const pageItems = locations.slice(startIdx, startIdx + locationsPageSize);

    const html = pageItems.map(l => {
        // Type Icons and Colors
        let typeIcon = 'bx-building';
        let typeClass = 'bg-gray-100 text-gray-700';
        switch((l.loc_type || '').toLowerCase()) {
            case 'warehouse': typeIcon = 'bx-building-house'; typeClass = 'bg-blue-100 text-blue-700'; break;
            case 'storage_room': typeIcon = 'bx-archive'; typeClass = 'bg-orange-100 text-orange-700'; break;
            case 'office': typeIcon = 'bx-briefcase'; typeClass = 'bg-purple-100 text-purple-700'; break;
            case 'facility': typeIcon = 'bx-factory'; typeClass = 'bg-gray-100 text-gray-700'; break;
            case 'drop_point': typeIcon = 'bx-map-pin'; typeClass = 'bg-red-100 text-red-700'; break;
            case 'bin': typeIcon = 'bx-box'; typeClass = 'bg-yellow-100 text-yellow-700'; break;
            case 'department': typeIcon = 'bx-sitemap'; typeClass = 'bg-indigo-100 text-indigo-700'; break;
            case 'room': typeIcon = 'bx-door-open'; typeClass = 'bg-teal-100 text-teal-700'; break;
        }

        return `
        <tr>
            <td class="whitespace-nowrap font-mono">${l.loc_id || 'N/A'}</td>
            <td class="whitespace-nowrap font-semibold">${l.loc_name || 'N/A'}</td>
            <td class="whitespace-nowrap">
                <span class="flex items-center gap-2 capitalize px-2 py-1 rounded-md w-fit ${typeClass}">
                    <i class='bx ${typeIcon}'></i> ${l.loc_type || 'N/A'}
                </span>
            </td>
            <td class="whitespace-nowrap capitalize">${l.loc_zone_type || 'N/A'}</td>
            <td class="whitespace-nowrap">
                <span class="flex items-center justify-center gap-1 w-fit px-2 py-1 rounded-md ${l.loc_supports_fixed_items ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700'}">
                    <i class='bx ${l.loc_supports_fixed_items ? 'bx-check' : 'bx-x'} text-lg'></i>
                    ${l.loc_supports_fixed_items ? 'Yes' : 'No'}
                </span>
            </td>
            <td class="whitespace-nowrap text-center">${l.loc_capacity ? formatNumber(l.loc_capacity) : 'Unlimited'}</td>
            <td class="whitespace-nowrap">${l.loc_parent_id || '-'}</td>
            <td class="whitespace-nowrap">${l.loc_department_code || '-'}</td>
            <td class="whitespace-nowrap">
                <span class="badge ${l.loc_is_active ? 'badge-success' : 'badge-error'} badge-sm text-white gap-1">
                    <i class='bx ${l.loc_is_active ? 'bx-check-circle' : 'bx-x-circle'}'></i>
                    ${l.loc_is_active ? 'Active' : 'Inactive'}
                </span>
            </td>
            <td class="whitespace-nowrap">${formatDate(l.loc_created_at)}</td>
            <td class="whitespace-nowrap">
                <div class="flex gap-2">
                    <button class="text-primary transition-colors p-2 rounded-lg hover:bg-gray-50 view-location-btn" 
                            title="View Details" data-id="${l.loc_id}">
                        <i class='bx bx-show-alt text-xl'></i>
                    </button>
                    ${!l.is_virtual_warehouse ? `
                    <button class="text-warning transition-colors p-2 rounded-lg hover:bg-gray-50 edit-location-btn" 
                            title="Edit Location" data-id="${l.loc_id}">
                        <i class='bx bx-edit text-xl'></i>
                    </button>
                    <button class="text-error transition-colors p-2 rounded-lg hover:bg-gray-50 delete-location-btn" 
                            title="Delete Location" data-id="${l.loc_id}">
                        <i class='bx bx-trash text-xl'></i>
                    </button>
                    ` : `
                    <button class="text-gray-300 p-2 rounded-lg cursor-not-allowed" title="Managed in Warehouse Module" disabled>
                        <i class='bx bx-edit text-xl'></i>
                    </button>
                    <button class="text-gray-300 p-2 rounded-lg cursor-not-allowed" title="Managed in Warehouse Module" disabled>
                        <i class='bx bx-trash text-xl'></i>
                    </button>
                    `}
                </div>
            </td>
        </tr>
    `}).join('');

    els.locationsTableBody.innerHTML = html;
    renderLocationsPager(total, totalPages);

    // Add event listeners to action buttons
    els.locationsTableBody.querySelectorAll('.view-location-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const locationId = btn.dataset.id;
            const location = locations.find(l => l.loc_id === locationId);
            if(location) {
                els.viewLocationContent.innerHTML = `
                    <div class="grid grid-cols-2 gap-4">
                        <div><span class="font-bold text-gray-600">Location ID:</span> <span class="font-mono">${location.loc_id}</span></div>
                        <div><span class="font-bold text-gray-600">Name:</span> ${location.loc_name}</div>
                        <div><span class="font-bold text-gray-600">Type:</span> ${location.loc_type}</div>
                        <div><span class="font-bold text-gray-600">Zone Type:</span> ${location.loc_zone_type}</div>
                        <div><span class="font-bold text-gray-600">Capacity:</span> ${location.loc_capacity || 'Unlimited'}</div>
                        <div><span class="font-bold text-gray-600">Fixed Asset Support:</span> ${location.loc_supports_fixed_items ? 'Yes' : 'No'}</div>
                        <div><span class="font-bold text-gray-600">Parent Location:</span> ${location.loc_parent_id || 'None'}</div>
                        <div><span class="font-bold text-gray-600">Department:</span> ${location.loc_department_code || 'None'}</div>
                        <div><span class="font-bold text-gray-600">Status:</span> <span class="badge ${location.loc_is_active ? 'badge-success' : 'badge-error'} badge-sm text-white">${location.loc_is_active ? 'Active' : 'Inactive'}</span></div>
                        <div><span class="font-bold text-gray-600">Created At:</span> ${formatDate(location.loc_created_at)}</div>
                    </div>
                `;
                els.viewLocationModal.classList.remove('hidden');
            }
        });
    });
    
    els.locationsTableBody.querySelectorAll('.edit-location-btn').forEach(btn => {
        btn.addEventListener('click', () => openEditLocationModal(btn.dataset.id));
    });
    
    els.locationsTableBody.querySelectorAll('.delete-location-btn').forEach(btn => {
        btn.addEventListener('click', () => deleteLocation(btn.dataset.id));
    });
}

function closeAddLocationModalFunc() {
    els.addLocationModal.classList.add('hidden');
    if(els.addLocationForm) els.addLocationForm.reset();
}

function renderLocationsPager(total, totalPages) {
    if (!els.locationsPagerInfo || !els.locationsPageDisplay) return;
    const start = total === 0 ? 0 : ((currentLocationsPage - 1) * locationsPageSize) + 1;
    const end = Math.min(currentLocationsPage * locationsPageSize, total);
    els.locationsPagerInfo.textContent = `Showing ${start}-${end} of ${total}`;
    els.locationsPageDisplay.textContent = `${currentLocationsPage} / ${totalPages}`;
    if (els.locationsPrevBtn) els.locationsPrevBtn.disabled = currentLocationsPage <= 1;
    if (els.locationsNextBtn) els.locationsNextBtn.disabled = currentLocationsPage >= totalPages;
}

async function loadLocations() {
    try {
        const response = await fetch(SWS_LOCATIONS_API, {
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
        locations = result.success ? (result.data || []) : [];
        const options = ['<option value="">Select Location</option>'].concat(
            locations.map(l => `<option value="${l.loc_id}">${l.loc_name}</option>`)
        ).join('');
        if (els.transferLocationTo) els.transferLocationTo.innerHTML = options;
    } catch (e) {
        locations = [];
        if (els.transferLocationTo) els.transferLocationTo.innerHTML = '<option value="">Select Location</option>';
    }
}

function openViewCategoriesModal() {
    els.viewCategoriesModal.classList.remove('hidden');
    currentCategoriesPage = 1;
    fetchCategoriesForTable();
}

function closeViewCategoriesModalFunc() {
    els.viewCategoriesModal.classList.add('hidden');
}

async function fetchCategoriesForTable() {
    els.categoriesTableBody.innerHTML = `
        <tr>
            <td colspan="4" class="text-center py-8">
                <div class="flex justify-center items-center">
                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    <span class="ml-2 text-gray-600">Loading categories...</span>
                </div>
            </td>
        </tr>
    `;
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
        categories = result.success ? (result.data || []) : [];
        renderCategoriesTable();
    } catch (e) {
        console.error('Error loading categories:', e);
        els.categoriesTableBody.innerHTML = '<tr><td colspan="4" class="text-center py-4 text-red-600">Error loading categories</td></tr>';
        if (els.categoriesPagerInfo) els.categoriesPagerInfo.textContent = '';
        if (els.categoriesPageDisplay) els.categoriesPageDisplay.textContent = '1 / 1';
        notify('Error loading categories', 'error');
    }
}

function renderCategoriesTable() {
    const total = categories.length;
    if (!total) {
        els.categoriesTableBody.innerHTML = `
            <tr>
                <td colspan="4" class="text-center py-8 text-gray-500">
                    <div class="flex flex-col items-center justify-center">
                        <i class='bx bx-fw bx-category-alt text-4xl text-gray-400 mb-2'></i>
                        <p>No categories found</p>
                    </div>
                </td>
            </tr>
        `;
        renderCategoriesPager(0, 1);
        return;
    }

    const totalPages = Math.max(1, Math.ceil(total / categoriesPageSize));
    if (currentCategoriesPage > totalPages) currentCategoriesPage = totalPages;
    if (currentCategoriesPage < 1) currentCategoriesPage = 1;
    const startIdx = (currentCategoriesPage - 1) * categoriesPageSize;
    const pageItems = categories.slice(startIdx, startIdx + categoriesPageSize);

    const html = pageItems.map(c => `
        <tr>
            <td class="whitespace-nowrap font-mono">${c.cat_id || 'N/A'}</td>
            <td class="whitespace-nowrap font-semibold">${c.cat_name || 'N/A'}</td>
            <td class="whitespace-nowrap max-w-xs truncate" title="${c.cat_description || ''}">${c.cat_description || '-'}</td>
            <td class="whitespace-nowrap">${formatDate(c.cat_created_at)}</td>
            <td class="whitespace-nowrap">
                <button class="btn btn-xs btn-info text-white mr-1" onclick="openEditCategoryModal('${c.cat_id}')">
                    <i class='bx bx-edit'></i>
                </button>
                <button class="btn btn-xs btn-error text-white" onclick="deleteCategory('${c.cat_id}')">
                    <i class='bx bx-trash'></i>
                </button>
            </td>
        </tr>
    `).join('');

    els.categoriesTableBody.innerHTML = html;
    renderCategoriesPager(total, totalPages);
}

function renderCategoriesPager(total, totalPages) {
    if (!els.categoriesPagerInfo || !els.categoriesPageDisplay) return;
    const start = total === 0 ? 0 : ((currentCategoriesPage - 1) * categoriesPageSize) + 1;
    const end = Math.min(currentCategoriesPage * categoriesPageSize, total);
    els.categoriesPagerInfo.textContent = `Showing ${start}-${end} of ${total}`;
    els.categoriesPageDisplay.textContent = `${currentCategoriesPage} / ${totalPages}`;
    if (els.categoriesPrevBtn) els.categoriesPrevBtn.disabled = currentCategoriesPage <= 1;
    if (els.categoriesNextBtn) els.categoriesNextBtn.disabled = currentCategoriesPage >= totalPages;
}

function openAddCategoryModal() {
    if (els.addCategoryForm) els.addCategoryForm.reset();
    document.getElementById('edit_cat_id').value = '';
    document.querySelector('#addCategoryModal h3').textContent = 'Add New Category';
    document.querySelector('#addCategoryModal button[type="submit"]').textContent = 'Save Category';
    if (els.addCategoryModal) els.addCategoryModal.classList.remove('hidden');
}

function openEditCategoryModal(id) {
    const cat = categories.find(c => c.cat_id === id);
    if (!cat) return;

    document.getElementById('edit_cat_id').value = cat.cat_id;
    document.getElementById('new_cat_name').value = cat.cat_name;
    document.getElementById('new_cat_description').value = cat.cat_description || '';
    
    document.querySelector('#addCategoryModal h3').textContent = 'Edit Category';
    document.querySelector('#addCategoryModal button[type="submit"]').textContent = 'Update Category';
    
    if (els.addCategoryModal) els.addCategoryModal.classList.remove('hidden');
}

function closeAddCategoryModalFunc() {
    if (els.addCategoryModal) els.addCategoryModal.classList.add('hidden');
}

async function saveCategory(e) {
    e.preventDefault();
    const nameInput = document.getElementById('new_cat_name');
    const descInput = document.getElementById('new_cat_description');
    const idInput = document.getElementById('edit_cat_id');
    
    if (!nameInput) return;
    
    const name = nameInput.value.trim();
    const description = descInput ? descInput.value.trim() : '';
    const id = idInput ? idInput.value : '';
    
    if (!name) return;

    const url = id ? `${SWS_CATEGORIES_API}/${id}` : SWS_CATEGORIES_API;
    const method = id ? 'PUT' : 'POST';
    const successMsg = id ? 'Category updated successfully' : 'Category added successfully';
    const errorMsg = id ? 'Error updating category' : 'Error adding category';

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
            body: JSON.stringify({ cat_name: name, cat_description: description }),
            credentials: 'include'
        });

        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        const result = await response.json();

        if (result.success) {
            notify(successMsg, 'success');
            closeAddCategoryModalFunc();
            fetchCategoriesForTable();
        } else {
            notify(result.message || errorMsg, 'error');
        }
    } catch (e) {
        console.error(errorMsg + ':', e);
        notify(errorMsg, 'error');
    }
}

async function deleteCategory(id) {
    const result = await Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    });

    if (!result.isConfirmed) return;
    
    try {
        const response = await fetch(`${SWS_CATEGORIES_API}/${id}`, {
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
            notify('Category deleted successfully', 'success');
            fetchCategoriesForTable();
        } else {
            notify(result.message || 'Error deleting category', 'error');
        }
    } catch (e) {
        console.error('Error deleting category:', e);
        notify('Error deleting category', 'error');
    }
}

// Expose functions to global scope for onclick handlers
window.openEditCategoryModal = openEditCategoryModal;
window.deleteCategory = deleteCategory;

function openTransferModal() {
    els.transferItemModal.classList.remove('hidden');
    renderTransferItemOptions();
    els.transferItemDetails.innerHTML = '';
    els.transferLocationFrom.value = '';
    if (els.transferLocationTo) els.transferLocationTo.value = '';
    els.transferUnits.value = '';
    els.transferUnitsWarning.classList.add('hidden');
}

function closeTransferModal() {
    els.transferItemModal.classList.add('hidden');
}

function renderTransferItemOptions() {
    const selectable = inventoryItems.filter(i => i.status === 'In Stock' || i.status === 'Low Stock');
    const options = ['<option value="">Select Item</option>'].concat(
        selectable.map(i => `<option value="${i.item_id}">${i.item_name} (${i.item_code || 'N/A'})</option>`)
    ).join('');
    els.transferItemSelect.innerHTML = options;
}

async function onTransferItemSelectChange() {
    const id = els.transferItemSelect.value;
    els.transferItemDetails.innerHTML = '';
    els.transferLocationFrom.value = '';
    els.transferUnitsWarning.classList.add('hidden');
    if (!id) return;
    try {
        const response = await fetch(`${SWS_ITEMS_API}/${id}`, {
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
        const item = result.data;
        const unitPrice = parseFloat(item.item_unit_price) || 0;
        const quantity = parseInt(item.item_current_stock) || 0;
        els.transferLocationFrom.value = item.item_stored_from || '';
        els.transferItemDetails.innerHTML = `
            <div><span class="text-sm text-gray-500">Item Code</span><p class="font-semibold font-mono">${item.item_code || 'N/A'}</p></div>
            <div><span class="text-sm text-gray-500">SKU</span><p class="font-semibold font-mono">${item.item_stock_keeping_unit || 'N/A'}</p></div>
            <div><span class="text-sm text-gray-500">Item Name</span><p class="font-semibold">${item.item_name || 'N/A'}</p></div>
            <div><span class="text-sm text-gray-500">Category</span><p class="font-semibold">${item.category?.cat_name || 'Uncategorized'}</p></div>
            <div><span class="text-sm text-gray-500">Stored From</span><p class="font-semibold">${item.item_stored_from || 'N/A'}</p></div>
            <div><span class="text-sm text-gray-500">Item Type</span><p class="font-semibold">${item.item_item_type || 'N/A'}</p></div>
            <div><span class="text-sm text-gray-500">Liquidity Risk</span><p class="font-semibold">${item.item_liquidity_risk_level || 'N/A'}</p></div>
            <div><span class="text-sm text-gray-500">Current Stock</span><p class="font-semibold">${formatNumber(quantity)}</p></div>
            <div><span class="text-sm text-gray-500">Max Stock</span><p class="font-semibold">${formatNumber(item.item_max_stock || 0)}</p></div>
            <div><span class="text-sm text-gray-500">Unit Price</span><p class="font-semibold">${formatCurrency(unitPrice)}</p></div>
            <div><span class="text-sm text-gray-500">Expiration Date</span><p class="font-semibold">${formatDate(item.item_expiration_date)}</p></div>
            <div><span class="text-sm text-gray-500">Warranty End</span><p class="font-semibold">${formatDate(item.item_warranty_end)}</p></div>
            <div><span class="text-sm text-gray-500">Fixed Asset</span><p class="font-semibold">${item.item_is_fixed ? 'Yes' : 'No'}</p></div>
            <div><span class="text-sm text-gray-500">Is Collateral</span><p class="font-semibold">${item.item_is_collateral ? 'Yes' : 'No'}</p></div>
            <div class="md:col-span-2"><span class="text-sm text-gray-500">Description</span><p class="font-semibold break-words">${item.item_description || 'N/A'}</p></div>
        `;
        els.transferUnits.setAttribute('max', String(quantity));
    } catch (e) {
        els.transferItemDetails.innerHTML = '';
        els.transferLocationFrom.value = '';
    }
}

function validateTransferUnits() {
    const max = parseInt(els.transferUnits.getAttribute('max') || '0');
    const val = parseInt(els.transferUnits.value || '0');
    if (val > max) {
        els.transferUnitsWarning.classList.remove('hidden');
        return false;
    }
    els.transferUnitsWarning.classList.add('hidden');
    return true;
}

async function submitTransfer(e) {
    e.preventDefault();
    const itemId = els.transferItemSelect.value;
    const toLocId = els.transferLocationTo.value;
    const units = parseInt(els.transferUnits.value || '0');
    if (!itemId || !toLocId || !units || units < 1) return;
    if (!validateTransferUnits()) return;
    try {
        const response = await fetch(`${API_BASE_URL}/sws/items/transfer`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            body: JSON.stringify({ item_id: parseInt(itemId), location_to_id: parseInt(toLocId), transfer_units: units }),
            credentials: 'include'
        });
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        const result = await response.json();
        if (result.success) {
            notify('Item transferred successfully', 'success');
            closeTransferModal();
            await Promise.all([
                loadInventoryItems(),
                loadInventoryStats(),
                loadStockLevels()
            ]);
        } else {
            notify(result.message || 'Transfer failed', 'error');
        }
    } catch (e) {
        notify('Transfer failed', 'error');
    }
}
function openDigitalInventoryReportModal() {
    document.getElementById('di_report_modal').classList.remove('hidden');
    const rangeSel = document.getElementById('di_report_range');
    const from = document.getElementById('di_report_from');
    const to = document.getElementById('di_report_to');
    const now = new Date();
    const y = now.getFullYear();
    const m = now.getMonth();
    const startOfWeek = new Date(now); startOfWeek.setDate(now.getDate() - now.getDay());
    const endOfWeek = new Date(now); endOfWeek.setDate(now.getDate() + (6 - now.getDay()));
    const startOfMonth = new Date(y, m, 1);
    const endOfMonth = new Date(y, m + 1, 0);
    const startOfYear = new Date(y, 0, 1);
    const endOfYear = new Date(y, 11, 31);
    const setRange = (r) => {
        if (r === 'week') { from.value = formatDateForInput(startOfWeek); to.value = formatDateForInput(endOfWeek); }
        else if (r === 'month') { from.value = formatDateForInput(startOfMonth); to.value = formatDateForInput(endOfMonth); }
        else if (r === 'year') { from.value = formatDateForInput(startOfYear); to.value = formatDateForInput(endOfYear); }
    };
    setRange(rangeSel.value || 'week');
    rangeSel.addEventListener('change', () => {
        const r = rangeSel.value;
        if (r === 'custom') return;
        setRange(r);
    });
}

document.getElementById('di_report_close').addEventListener('click', () => {
    document.getElementById('di_report_modal').classList.add('hidden');
});

document.getElementById('di_report_preview').addEventListener('click', () => {
    const from = document.getElementById('di_report_from').value;
    const to = document.getElementById('di_report_to').value;
    const container = document.getElementById('di_report_preview_container');
    const fromD = from ? new Date(from) : null;
    const toD = to ? new Date(to) : null;
    const rows = inventoryItems.filter(i => {
        const d = i.item_created_at ? new Date(i.item_created_at) : null;
        if (!d) return false;
        if (fromD && d < fromD) return false;
        if (toD && d > toD) return false;
        return true;
    }).map(i => `
        <tr>
            <td class="font-mono">${i.item_code || ''}</td>
            <td>${i.item_name || ''}</td>
            <td>${i.category || ''}</td>
            <td>${i.item_stored_from || ''}</td>
            <td class="text-right">${formatNumber(i.current_stock || 0)}</td>
            <td class="text-right">${formatNumber(i.max_stock || 0)}</td>
            <td class="text-right">${formatCurrency(i.unit_price || 0)}</td>
            <td class="text-right font-semibold">${formatCurrency(i.total_value || 0)}</td>
        </tr>
    `).join('');
    container.innerHTML = `
        <div class="mb-3">
            <h4 class="font-semibold">Digital Inventory Report</h4>
            <p class="text-sm text-gray-600">Range: ${from || 'N/A'} to ${to || 'N/A'}</p>
        </div>
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr class="bg-gray-700 text-white">
                        <th>Item Code</th><th>Item</th><th>Category</th><th>Stored From</th>
                        <th>Current</th><th>Max</th><th>Unit Price</th><th>Total Value</th>
                    </tr>
                </thead>
                <tbody>${rows || '<tr><td colspan="8" class="text-center py-4 text-gray-500">No data</td></tr>'}</tbody>
            </table>
        </div>
    `;
});

document.getElementById('di_report_clear').addEventListener('click', () => {
    const container = document.getElementById('di_report_preview_container');
    container.innerHTML = `
        <div class="flex items-center justify-center text-gray-500">
            <i class='bx bx-show-alt text-3xl mr-2'></i>
            <span>No preview yet</span>
        </div>
    `;
});

document.getElementById('di_report_download').addEventListener('click', () => {
    const container = document.getElementById('di_report_preview_container');
    const hasTable = container && container.querySelector('table');
    if (!hasTable) {
        notify('Please generate a preview first', 'error');
        return;
    }
    document.getElementById('di_download_modal').classList.remove('hidden');
    const range = document.getElementById('di_report_range').value;
    const from = document.getElementById('di_report_from').value;
    const to = document.getElementById('di_report_to').value;
    const params = new URLSearchParams();
    if (range) params.set('range', range);
    if (from) params.set('from', from);
    if (to) params.set('to', to);
    params.set('format', 'pdf');
    const url = `${SWS_DIGITAL_INVENTORY_REPORT_API}?${params.toString()}`;
    fetch(url, {
        method: 'GET',
        headers: {
            'Accept': 'application/pdf',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
        },
        credentials: 'include'
    }).then(async res => {
        if (res.status === 401) {
            throw new Error('unauthorized');
        }
        if (!res.ok) {
            throw new Error('failed');
        }
        const blob = await res.blob();
        const urlObj = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = urlObj;
        a.download = `digital-inventory-report.pdf`;
        document.body.appendChild(a);
        a.click();
        a.remove();
        window.URL.revokeObjectURL(urlObj);
        document.getElementById('di_download_modal').classList.add('hidden');
        document.getElementById('di_success_modal').classList.remove('hidden');
    }).catch((e) => {
        document.getElementById('di_download_modal').classList.add('hidden');
        if ((e && String(e.message).toLowerCase().includes('unauthorized'))) {
            notify('Unauthorized. Please sign in again.', 'error');
        } else {
            notify('Failed to download PDF', 'error');
        }
    });
});

document.getElementById('di_success_close').addEventListener('click', () => {
    document.getElementById('di_success_modal').classList.add('hidden');
});

document.getElementById('di_report_print').addEventListener('click', () => {
    const w = window.open('', '_blank');
    const content = document.getElementById('di_report_preview_container').innerHTML;
    w.document.write(`<!doctype html><html><head><title>Digital Inventory Report</title><style>body{font-family:Arial,sans-serif;padding:20px} table{width:100%;border-collapse:collapse} th,td{border:1px solid #ddd;padding:8px} th{background:#333;color:#fff}</style></head><body>${content}</body></html>`);
    w.document.close();
    w.focus();
    w.print();
});
</script>
