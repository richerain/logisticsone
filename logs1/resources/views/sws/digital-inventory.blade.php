<!-- resources/views/sws/digital-inventory.blade.php -->
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bxs-archive-in'></i>Digital Inventory</h2>
    </div>
    <div class="text-right">
        <span class="text-md text-gray-600">Smart Warehousing System</span>
    </div>
</div>
<!-- Inventory Metrics section start (PM style) -->
<div class="mt-4 mb-1">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <!-- Total Items -->
        <div id="di_card_total" class="cursor-pointer bg-white rounded-2xl shadow-sm border border-gray-100 border-b-4 border-blue-500 p-6 relative overflow-hidden group hover:shadow-md transition-all duration-300" title="Show all items">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform duration-300">
                <i class='bx bx-package text-6xl text-blue-600'></i>
            </div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-3 bg-blue-50 rounded-xl text-blue-600">
                        <i class='bx bx-package text-2xl'></i>
                    </div>
                    <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Total Items</h4>
                </div>
                <div class="flex items-end gap-2">
                    <span id="totalItems" class="text-4xl font-black text-gray-800 leading-none">0</span>
                    <span class="text-xs text-gray-500 mb-1">All inventory</span>
                </div>
            </div>
        </div>
        <!-- Low Stock -->
        <div id="di_card_low" class="cursor-pointer bg-white rounded-2xl shadow-sm border border-gray-100 border-b-4 border-yellow-500 p-6 relative overflow-hidden group hover:shadow-md transition-all duration-300" title="Filter to Low Stock">
            <div id="lowStockBadgePulse" class="hidden absolute top-4 right-4 z-20">
                <span class="relative flex h-6 w-6">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                    <span id="lowStockPulseCount" class="relative inline-flex rounded-full h-6 w-6 bg-yellow-500 text-[10px] font-bold text-white items-center justify-center border-2 border-white">0</span>
                </span>
            </div>
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform duration-300">
                <i class='bx bx-error text-6xl text-yellow-600'></i>
            </div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-3 bg-yellow-50 rounded-xl text-yellow-600">
                        <i class='bx bx-error text-2xl'></i>
                    </div>
                    <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Low Stock</h4>
                </div>
                <div class="flex items-end gap-2">
                    <span id="lowStockItems" class="text-4xl font-black text-gray-800 leading-none">0</span>
                    <span class="text-xs text-gray-500 mb-1">Warning threshold</span>
                </div>
            </div>
        </div>
        <!-- Out of Stock -->
        <div id="di_card_out" class="cursor-pointer bg-white rounded-2xl shadow-sm border border-gray-100 border-b-4 border-red-500 p-6 relative overflow-hidden group hover:shadow-md transition-all duration-300" title="Filter to Out of Stock">
            <div id="outOfStockBadgePulse" class="hidden absolute top-4 right-4 z-20">
                <span class="relative flex h-6 w-6">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                    <span id="outOfStockPulseCount" class="relative inline-flex rounded-full h-6 w-6 bg-red-500 text-[10px] font-bold text-white items-center justify-center border-2 border-white">0</span>
                </span>
            </div>
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform duration-300">
                <i class='bx bx-error-circle text-6xl text-red-600'></i>
            </div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-3 bg-red-50 rounded-xl text-red-600">
                        <i class='bx bx-error-circle text-2xl'></i>
                    </div>
                    <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Out of Stock</h4>
                </div>
                <div class="flex items-end gap-2">
                    <span id="outOfStockItems" class="text-4xl font-black text-gray-800 leading-none">0</span>
                    <span class="text-xs text-gray-500 mb-1">Replenish needed</span>
                </div>
            </div>
        </div>
        <!-- Total Value (moved to 4th position) -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 border-b-4 border-emerald-500 p-6 relative overflow-hidden group hover:shadow-md transition-all duration-300">
            <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform duration-300">
                <i class='bx bx-money text-6xl text-emerald-600'></i>
            </div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-3">
                    <div class="p-3 bg-emerald-50 rounded-xl text-emerald-600">
                        <i class='bx bx-money text-2xl'></i>
                    </div>
                    <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Total Value</h4>
                </div>
                <div class="flex items-end gap-2">
                    <span id="totalValue" class="text-4xl font-black text-gray-800 leading-none">₱0.00</span>
                    <span class="text-xs text-gray-500 mb-1">Current valuation</span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Inventory Metrics section end -->
<!-- Quick Management section start -->
<div class="mt-4 mb-4">
    <div class="rounded-2xl overflow-visible">
        <div class="p-4">
            <div class="flex flex-wrap items-stretch gap-3 pb-1">
                <button id="transferBtn" class="h-12 px-4 flex-1 min-w-[160px] sm:min-w-[180px] inline-flex items-center gap-3 bg-white border border-gray-200 rounded-xl hover:border-brand-primary hover:text-brand-primary hover:shadow-md transition-all duration-200">
                    <span class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-600">
                        <i class='bx bx-transfer text-lg'></i>
                    </span>
                    <span class="text-[12px] font-bold uppercase tracking-tight whitespace-nowrap">Transfer</span>
                </button>

                <button id="viewLocationsBtn" class="hidden h-12 px-4 flex-shrink-0 inline-flex items-center gap-3 bg-white border border-gray-200 rounded-xl hover:border-brand-primary hover:text-brand-primary hover:shadow-md transition-all duration-200">
                    <span class="w-8 h-8 rounded-lg bg-yellow-50 flex items-center justify-center text-yellow-600">
                        <i class='bx bx-map text-lg'></i>
                    </span>
                    <span class="text-[12px] font-bold uppercase tracking-tight whitespace-nowrap">Location</span>
                </button>

                <button id="viewCategoriesBtn" class="hidden h-12 px-4 flex-1 min-w-[160px] sm:min-w-[180px] inline-flex items-center gap-3 bg-white border border-gray-200 rounded-xl hover:border-brand-primary hover:text-brand-primary hover:shadow-md transition-all duration-200">
                    <span class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center text-green-600">
                        <i class='bx bx-category-alt text-lg'></i>
                    </span>
                    <span class="text-[12px] font-bold uppercase tracking-tight whitespace-nowrap">Category</span>
                </button>

                <button id="generateReportBtn" class="h-12 px-4 flex-1 min-w-[160px] sm:min-w-[180px] inline-flex items-center gap-3 bg-white border border-gray-200 rounded-xl hover:border-brand-primary hover:text-brand-primary hover:shadow-md transition-all duration-200">
                    <span class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center text-purple-600">
                        <i class='bx bxs-report text-lg'></i>
                    </span>
                    <span class="text-[12px] font-bold uppercase tracking-tight whitespace-nowrap">Reports</span>
                </button>

                <button id="purchaseNewItemBtn" class="h-12 px-4 flex-1 min-w-[160px] sm:min-w-[180px] inline-flex items-center gap-3 bg-emerald-500 text-white rounded-xl hover:bg-emerald-600 shadow-sm hover:shadow-md transition-all duration-200">
                    <span class="w-8 h-8 rounded-lg bg-emerald-400/30 flex items-center justify-center">
                        <i class='bx bxs-purchase-tag text-lg'></i>
                    </span>
                    <span class="text-[12px] font-bold uppercase tracking-tight whitespace-nowrap">Purchase</span>
                </button>

                <button id="inventoryNewItemBtn" class="relative h-12 px-4 flex-1 min-w-[160px] sm:min-w-[180px] inline-flex items-center gap-3 bg-brand-primary text-white rounded-xl hover:bg-brand-primary/90 shadow-sm hover:shadow-md transition-all duration-200">
                    <span class="w-8 h-8 rounded-lg bg-white/20 flex items-center justify-center">
                        <i class='bx bxs-down-arrow-square text-lg'></i>
                    </span>
                    <span class="text-[12px] font-bold uppercase tracking-tight whitespace-nowrap">Inventory New Item</span>
                    <span id="incomingBadge" class="hidden absolute -top-1 -right-1">
                        <span class="relative flex h-5 w-5">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                            <span id="incomingBadgeCount" class="relative inline-flex rounded-full h-5 w-5 bg-red-500 text-[10px] font-bold text-white items-center justify-center border-2 border-white">0</span>
                        </span>
                    </span>
                </button>
                <!-- Incoming button merged into Inventory New Item -->
            </div>
        </div>
    </div>
</div>
<!-- Quick Management section (moved below Inventory Metrics) end -->
<!-- digital inventory section start -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-100 flex justify-between items-center">
        <div class="flex items-center gap-3">
            <i class='bx bx-line-chart text-brand-primary text-xl'></i>
            <h3 class="text-lg font-bold text-gray-800 tracking-tight">Inventory Overview</h3>
        </div>
    </div>
    
    <div class="p-5">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
            <!-- Stock Levels by Category section start -->
            <div class="lg:col-span-12">
                <div class="flex items-center justify-between mb-4">
                    <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider flex items-center gap-2">
                        <i class='bx bx-chart text-brand-primary'></i>
                        Stock Levels by Category
                    </h4>
                </div>
                
                <div id="stockLevelsContainer" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <!-- Equipment -->
                    <div class="group">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-blue-50 flex items-center justify-center text-blue-500 group-hover:bg-blue-500 group-hover:text-white transition-all duration-300">
                                    <i class='bx bx-wrench text-base'></i>
                                </div>
                                <span class="text-sm font-semibold text-gray-700 whitespace-nowrap">Equipment</span>
                            </div>
                            <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded whitespace-nowrap">0%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-blue-500 h-full rounded-full transition-all duration-500" style="width: 0%"></div>
                        </div>
                    </div>

                    <!-- Supplies -->
                    <div class="group">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-yellow-50 flex items-center justify-center text-yellow-500 group-hover:bg-yellow-500 group-hover:text-white transition-all duration-300">
                                    <i class='bx bx-package text-base'></i>
                                </div>
                                <span class="text-sm font-semibold text-gray-700 whitespace-nowrap">Supplies</span>
                            </div>
                            <span class="text-xs font-bold text-yellow-600 bg-yellow-50 px-2 py-1 rounded whitespace-nowrap">0%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-yellow-500 h-full rounded-full transition-all duration-500" style="width: 0%"></div>
                        </div>
                    </div>

                    <!-- Furniture -->
                    <div class="group">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-green-50 flex items-center justify-center text-green-500 group-hover:bg-green-500 group-hover:text-white transition-all duration-300">
                                    <i class='bx bx-chair text-base'></i>
                                </div>
                                <span class="text-sm font-semibold text-gray-700 whitespace-nowrap">Furniture</span>
                            </div>
                            <span class="text-xs font-bold text-green-600 bg-green-50 px-2 py-1 rounded whitespace-nowrap">0%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-green-500 h-full rounded-full transition-all duration-500" style="width: 0%"></div>
                        </div>
                    </div>

                    <!-- Automotive -->
                    <div class="group">
                        <div class="flex items-center justify-between mb-2">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-purple-50 flex items-center justify-center text-purple-500 group-hover:bg-purple-500 group-hover:text-white transition-all duration-300">
                                    <i class='bx bx-car text-base'></i>
                                </div>
                                <span class="text-sm font-semibold text-gray-700 whitespace-nowrap">Automotive</span>
                            </div>
                            <span class="text-xs font-bold text-purple-600 bg-purple-50 px-2 py-1 rounded whitespace-nowrap">0%</span>
                        </div>
                        <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                            <div class="bg-purple-500 h-full rounded-full transition-all duration-500" style="width: 0%"></div>
                        </div>
                    </div>
                </div>

                <!-- Relocated Search and Filter section -->
                <div class="mt-6 pt-4 border-t border-gray-100">
                    <div class="flex gap-3 items-center overflow-x-auto">
                        <!-- Search -->
                        <div class="relative flex-1 min-w-[260px]">
                            <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg'></i>
                            <input id="di_search" type="text" placeholder="Search item, code, category, stored from..." class="w-full pl-10 pr-4 py-2 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary transition-all duration-200 outline-none text-sm" />
                        </div>
                        <!-- Stock Status Dropdown -->
                        <div class="relative w-56">
                            <i class='bx bx-toggle-left absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg pointer-events-none'></i>
                            <select id="di_status" class="w-full pl-10 pr-8 py-2 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary appearance-none text-sm">
                                <option value="">All Status</option>
                                <option value="In Stock">In Stock</option>
                                <option value="Low Stock">Low Stock</option>
                                <option value="Out of Stock">Out of Stock</option>
                            </select>
                            <i class='bx bx-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xl pointer-events-none'></i>
                        </div>
                        <!-- Category Dropdown -->
                        <div class="relative w-56">
                            <i class='bx bx-category-alt absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg pointer-events-none'></i>
                            <select id="di_category" class="w-full pl-10 pr-8 py-2 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-brand-primary/20 focus:border-brand-primary appearance-none text-sm">
                                <option value="">All Categories</option>
                                <option value="Equipment">Equipment</option>
                                <option value="Supplies">Supplies</option>
                                <option value="Furniture">Furniture</option>
                                <option value="Automotive">Automotive</option>
                            </select>
                            <i class='bx bx-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 text-xl pointer-events-none'></i>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Stock Levels by Category section end -->

            
        </div>
    </div>
</div>

<!-- Inventory Metrics section moved above Inventory Overview -->

<!-- digital inventory main table area start -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden mt-6">
    <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-100">
        <div class="flex items-center gap-3">
            <i class='bx bx-package text-2xl text-gray-800'></i>
            <h3 class="text-lg font-bold text-gray-800 tracking-tight leading-none">Inventory Details</h3>
        </div>
    </div>
    <div class="p-6">
        <div class="overflow-x-auto rounded-xl border border-gray-100">
        <table class="table table-zebra w-full">
            <thead class="bg-gray-800 font-bold text-white">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Item Code</th>
                    <th class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Product ID</th>
                    <th class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Item Name</th>
                    <th class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Category</th>
                    <th class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Stored From</th>
                    <th class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Current Stock</th>
                    <th class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Max Stock</th>
                    <th class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Unit Price</th>
                    <th class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Total Value</th>
                    <th class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Status</th>
                    <th class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Last Updated</th>
                    <th class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase text-right">Actions</th>
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
        <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-100 flex items-center justify-between -mx-6 -mt-6 mb-6 rounded-t-lg">
            <div class="flex items-center gap-3">
                <i class='bx bx-package text-2xl text-gray-800'></i>
                <h3 class="text-lg font-bold text-gray-800 tracking-tight leading-none">Inventory New Item</h3>
            </div>
        </div>
        <form id="addItemForm">
            <input type="hidden" id="psm_purchase_id" name="psm_purchase_id">
            <input type="hidden" id="psm_item_index" name="psm_item_index">
            <input type="hidden" id="psm_prod_id" name="psm_prod_id">
            <input type="hidden" id="psm_purcprod_id" name="psm_purcprod_id">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Item Name *</label>
                    <input type="text" id="item_name" required readonly class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Select from Incoming Assets table">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Product ID</label>
                    <input type="text" id="item_stock_keeping_unit" readonly class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="From incoming asset product id">
                </div>
            </div>
            <div class="mb-4" style="display:none">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea id="item_description" rows="2" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Category *</label>
                    <input type="hidden" id="item_category_id">
                    <input type="text" id="item_category_name" readonly class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100 focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Auto-selected from Incoming Assets">
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
            
            <!-- Hidden Fields (Auto-populated/Defaulted) -->
            <div style="display:none">
                <input type="number" id="item_current_stock" required min="0">
                <input type="number" id="item_max_stock" required min="1" value="100">
                <input type="number" id="item_unit_price" required min="0" step="0.01">
                <input type="text" id="item_expiration_date">
                <input type="text" id="item_warranty_end">
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
                <button type="button" id="cancelAddItemModal" class="px-4 py-2 bg-gray-200 rounded-lg">Close</button>
                <button type="submit" id="saveItemBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Save Item</button>
            </div>
        </form>
    </div>
</div>

<!-- View Item Modal -->
<div id="viewItemModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center hidden z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-3xl max-h-[90vh] overflow-hidden flex flex-col transform transition-all">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-blue-50 flex items-center justify-center text-blue-600 shadow-sm">
                    <i class='bx bx-info-circle text-2xl'></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-800 leading-none">Item Details</h3>
                    <p class="text-xs text-gray-500 mt-1">Complete information for the selected inventory item</p>
                </div>
            </div>
            <button id="closeViewItemModal" class="p-2 hover:bg-gray-100 rounded-xl transition-colors text-gray-400 hover:text-gray-600">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div id="viewItemContent" class="p-6 overflow-y-auto custom-scrollbar bg-white">
            <!-- Content populated by JS -->
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex justify-end">
            <button type="button" onclick="closeViewItemModal()" class="px-6 py-2.5 bg-white border border-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all active:scale-95 shadow-sm">
                Close
            </button>
        </div>
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

<!-- Incoming Assets Modal -->
<div id="incomingAssetsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden w-full max-w-7xl max-h-[90vh] flex flex-col">
        <div class="bg-gradient-to-r from-gray-50 to-white px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div class="flex items-center gap-3">
                <i class='bx bx-import text-2xl text-gray-800'></i>
                <h3 class="text-lg font-bold text-gray-800 tracking-tight leading-none">Incoming Assets (Purchase Products)</h3>
            </div>
        </div>
        <div class="p-6 overflow-x-auto">
            <div class="overflow-x-auto rounded-xl border border-gray-100">
                <table class="table table-zebra w-full">
                    <thead class="bg-gray-800 font-bold text-white">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">ID</th>
                            <th class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Prod ID</th>
                            <th class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Name</th>
                            <th class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Price</th>
                            <th class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Unit</th>
                            <th class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Type</th>
                            <th class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Warranty</th>
                            <th class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Expiration</th>
                            <th class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Desc</th>
                            <th class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody id="incomingAssetsTableBody">
                        <tr>
                            <td colspan="12" class="text-center py-4">
                                <div class="flex justify-center items-center">
                                    <div class="loading loading-spinner mr-3"></div>
                                    Loading incoming assets...
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="incomingPager" class="flex items-center justify-between mt-3">
                <div id="incomingPagerInfo" class="text-sm text-gray-600"></div>
                <div class="join">
                    <button class="btn btn-sm join-item" id="incomingPrevBtn" data-action="prev">Prev</button>
                    <span class="btn btn-sm join-item" id="incomingPageDisplay">1 / 1</span>
                    <button class="btn btn-sm join-item" id="incomingNextBtn" data-action="next">Next</button>
                </div>
            </div>
        </div>
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

<!-- Dual Inventory Backdrop -->
<div id="dualInventoryBackdrop" class="fixed inset-0 bg-black bg-opacity-50 hidden z-[59]"></div>

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
<div id="editItemModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center hidden z-50 p-4">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden flex flex-col transform transition-all">
        <!-- Modal Header -->
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50/50">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 rounded-xl bg-amber-50 flex items-center justify-center text-amber-600 shadow-sm">
                    <i class='bx bx-edit text-2xl'></i>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-800 leading-none">Edit Inventory Item</h3>
                    <p class="text-xs text-gray-500 mt-1">Update existing inventory item information</p>
                </div>
            </div>
            <button id="closeEditItemModal" class="p-2 hover:bg-gray-100 rounded-xl transition-colors text-gray-400 hover:text-gray-600">
                <i class='bx bx-x text-2xl'></i>
            </button>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6 overflow-y-auto custom-scrollbar bg-white">
            <form id="editItemForm" class="space-y-6">
                <input type="hidden" id="edit_item_id">
                
                <!-- Read-only Identifiers Section -->
                <div class="bg-gray-50 p-4 rounded-2xl border border-gray-100 grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Item Code</label>
                        <input type="text" id="edit_item_code" readonly class="w-full px-4 py-2.5 bg-gray-100 border border-gray-200 text-gray-500 font-mono font-bold rounded-xl cursor-not-allowed text-sm">
                    </div>
                    <div>
                        <label class="block text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-1">Product ID / SKU</label>
                        <input type="text" id="edit_item_stock_keeping_unit" readonly class="w-full px-4 py-2.5 bg-gray-100 border border-gray-200 text-gray-500 font-mono font-bold rounded-xl cursor-not-allowed text-sm">
                    </div>
                </div>
                
                <!-- Main Form Fields -->
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Item Name <span class="text-red-500">*</span></label>
                        <input type="text" id="edit_item_name" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-4 focus:ring-brand-primary/10 focus:border-brand-primary transition-all outline-none font-medium text-gray-800">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1.5">Description</label>
                        <textarea id="edit_item_description" rows="3" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-4 focus:ring-brand-primary/10 focus:border-brand-primary transition-all outline-none font-medium text-gray-800 resize-none"></textarea>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">Category</label>
                            <select id="edit_item_category_id" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-4 focus:ring-brand-primary/10 focus:border-brand-primary transition-all outline-none font-medium text-gray-800 appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%234a5568%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C/polyline%3E%3C/svg%3E')] bg-[length:1.25em_1.25em] bg-[right_0.75rem_center] bg-no-repeat">
                                <option value="">Select Category</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">Stored Warehouse</label>
                            <select id="edit_item_stored_from" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-4 focus:ring-brand-primary/10 focus:border-brand-primary transition-all outline-none font-medium text-gray-800 appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%234a5568%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C/polyline%3E%3C/svg%3E')] bg-[length:1.25em_1.25em] bg-[right_0.75rem_center] bg-no-repeat">
                                <option value="">Select Warehouse</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">Item Type <span class="text-red-500">*</span></label>
                            <select id="edit_item_item_type" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-4 focus:ring-brand-primary/10 focus:border-brand-primary transition-all outline-none font-medium text-gray-800 appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%234a5568%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C/polyline%3E%3C/svg%3E')] bg-[length:1.25em_1.25em] bg-[right_0.75rem_center] bg-no-repeat">
                                <option value="liquid">Liquid</option>
                                <option value="illiquid">Illiquid</option>
                                <option value="hybrid">Hybrid</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">Risk Level <span class="text-red-500">*</span></label>
                            <select id="edit_item_liquidity_risk_level" required class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-4 focus:ring-brand-primary/10 focus:border-brand-primary transition-all outline-none font-medium text-gray-800 appearance-none bg-[url('data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns%3D%22http%3A//www.w3.org/2000/svg%22%20width%3D%2224%22%20height%3D%2224%22%20viewBox%3D%220%200%2024%2024%22%20fill%3D%22none%22%20stroke%3D%22%234a5568%22%20stroke-width%3D%222%22%20stroke-linecap%3D%22round%22%20stroke-linejoin%3D%22round%22%3E%3Cpolyline%20points%3D%226%209%2012%2015%2018%209%22%3E%3C/polyline%3E%3C/svg%3E')] bg-[length:1.25em_1.25em] bg-[right_0.75rem_center] bg-no-repeat">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">Current Stock <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="number" id="edit_item_current_stock" required min="0" class="w-full pl-4 pr-12 py-2.5 border border-gray-200 rounded-xl focus:ring-4 focus:ring-brand-primary/10 focus:border-brand-primary transition-all outline-none font-bold text-gray-800">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400">QTY</span>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">Max Capacity <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="number" id="edit_item_max_stock" required min="1" class="w-full pl-4 pr-12 py-2.5 border border-gray-200 rounded-xl focus:ring-4 focus:ring-brand-primary/10 focus:border-brand-primary transition-all outline-none font-bold text-gray-800">
                                <span class="absolute right-4 top-1/2 -translate-y-1/2 text-xs font-bold text-gray-400">MAX</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">Unit Price (₱)</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">₱</span>
                                <input type="number" id="edit_item_unit_price" min="0" step="0.01" class="w-full pl-8 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-4 focus:ring-brand-primary/10 focus:border-brand-primary transition-all outline-none font-bold text-emerald-600">
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">Expiration Date</label>
                            <input type="date" id="edit_item_expiration_date" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-4 focus:ring-brand-primary/10 focus:border-brand-primary transition-all outline-none font-medium text-gray-800">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-gray-700 mb-1.5">Warranty End</label>
                            <input type="date" id="edit_item_warranty_end" class="w-full px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-4 focus:ring-brand-primary/10 focus:border-brand-primary transition-all outline-none font-medium text-gray-800">
                        </div>
                    </div>

                    <div class="bg-gray-50/50 p-4 rounded-2xl border border-gray-100 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative flex items-center justify-center">
                                <input type="checkbox" id="edit_item_is_fixed" class="peer h-5 w-5 cursor-pointer appearance-none rounded border border-gray-300 bg-white checked:bg-brand-primary checked:border-brand-primary transition-all">
                                <i class='bx bx-check absolute text-white opacity-0 peer-checked:opacity-100 pointer-events-none'></i>
                            </div>
                            <span class="text-sm font-bold text-gray-700 group-hover:text-brand-primary transition-colors">Fixed Asset</span>
                        </label>
                        <label class="flex items-center gap-3 cursor-pointer group">
                            <div class="relative flex items-center justify-center">
                                <input type="checkbox" id="edit_item_is_collateral" class="peer h-5 w-5 cursor-pointer appearance-none rounded border border-gray-300 bg-white checked:bg-brand-primary checked:border-brand-primary transition-all">
                                <i class='bx bx-check absolute text-white opacity-0 peer-checked:opacity-100 pointer-events-none'></i>
                            </div>
                            <span class="text-sm font-bold text-gray-700 group-hover:text-brand-primary transition-colors">Is Collateral</span>
                        </label>
                    </div>
                </div>
            </form>
        </div>

        <!-- Modal Footer -->
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 flex justify-end gap-3">
            <button type="button" id="cancelEditItemModal" class="px-6 py-2.5 bg-white border border-gray-200 text-gray-700 font-bold rounded-xl hover:bg-gray-50 hover:border-gray-300 transition-all active:scale-95 shadow-sm">
                Cancel
            </button>
            <button type="submit" form="editItemForm" id="updateItemBtn" class="px-8 py-2.5 bg-brand-primary text-white font-bold rounded-xl hover:bg-brand-primary/90 transition-all active:scale-95 shadow-lg shadow-brand-primary/20 flex items-center gap-2">
                <i class='bx bx-save'></i>
                Update Item
            </button>
        </div>
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
        diStatusSelect: document.getElementById('di_status'),
        diCategorySelect: document.getElementById('di_category'),
    
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
    addCategoryForm: document.getElementById('addCategoryForm'),

    // Incoming Assets Modal
    incomingAssetsModal: document.getElementById('incomingAssetsModal'),
    closeIncomingAssetsModal: document.getElementById('closeIncomingAssetsModal'),
    incomingAssetsTableBody: document.getElementById('incomingAssetsTableBody'),
    incomingAssetsPagerInfo: document.getElementById('incomingAssetsPagerInfo'),
    incomingAssetsPageDisplay: document.getElementById('incomingAssetsPageDisplay'),
    incomingAssetsPrevBtn: document.getElementById('incomingAssetsPrevBtn'),
    incomingAssetsNextBtn: document.getElementById('incomingAssetsNextBtn'),
    incomingAssetsPrevBtnMobile: document.getElementById('incomingAssetsPrevBtnMobile'),
    incomingAssetsNextBtnMobile: document.getElementById('incomingAssetsNextBtnMobile'),
    incomingAssetsBtn: document.getElementById('incomingAssetsBtn')
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
        const name = String(category.name || '').trim();
        const utilization = Math.max(0, Math.min(100, Number(category.utilization) || 0));
        const key = name.toLowerCase();
        
        let icon = 'bx-box';
        let color = 'gray';
        let bg = 'bg-gray-100';
        let text = 'text-gray-700';
        let fill = 'bg-gray-400';
        let badgeBg = 'bg-gray-100';
        let badgeText = 'text-gray-700';
        
        if (key.includes('equip')) {
            icon = 'bx-wrench'; color = 'blue'; bg = 'bg-blue-50'; text = 'text-blue-500'; fill = 'bg-blue-500'; badgeBg = 'bg-blue-50'; badgeText = 'text-blue-600';
        } else if (key.includes('suppl')) {
            icon = 'bx-package'; color = 'yellow'; bg = 'bg-yellow-50'; text = 'text-yellow-500'; fill = 'bg-yellow-500'; badgeBg = 'bg-yellow-50'; badgeText = 'text-yellow-600';
        } else if (key.includes('furn')) {
            icon = 'bx-chair'; color = 'green'; bg = 'bg-green-50'; text = 'text-green-500'; fill = 'bg-green-500'; badgeBg = 'bg-green-50'; badgeText = 'text-green-600';
        } else if (key.includes('auto')) {
            icon = 'bx-car'; color = 'purple'; bg = 'bg-purple-50'; text = 'text-purple-500'; fill = 'bg-purple-500'; badgeBg = 'bg-purple-50'; badgeText = 'text-purple-600';
        }
        
        html += `
            <div class="group">
                <div class="flex items-center justify-between mb-2">
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-lg ${bg} flex items-center justify-center ${text} group-hover:${fill} group-hover:text-white transition-all duration-300">
                            <i class='bx ${icon} text-base'></i>
                        </div>
                        <span class="text-sm font-semibold text-gray-700 whitespace-nowrap">${name}</span>
                    </div>
                    <span class="text-xs font-bold ${badgeText} ${badgeBg} px-2 py-1 rounded whitespace-nowrap">${utilization}%</span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2 overflow-hidden">
                    <div class="${fill} h-full rounded-full transition-all duration-500" style="width: ${utilization}%"></div>
                </div>
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

    // Update pulsing notification for Low Stock
    const lowStockBadgePulse = document.getElementById('lowStockBadgePulse');
    const lowStockPulseCount = document.getElementById('lowStockPulseCount');
    const lowStockCount = stats.low_stock_items || 0;
    
    if (lowStockCount > 0) {
        lowStockBadgePulse.classList.remove('hidden');
        lowStockPulseCount.textContent = lowStockCount;
    } else {
        lowStockBadgePulse.classList.add('hidden');
    }

    // Update pulsing notification for Out of Stock
    const outOfStockBadgePulse = document.getElementById('outOfStockBadgePulse');
    const outOfStockPulseCount = document.getElementById('outOfStockPulseCount');
    const outOfStockCount = stats.out_of_stock_items || 0;
    
    if (outOfStockCount > 0) {
        outOfStockBadgePulse.classList.remove('hidden');
        outOfStockPulseCount.textContent = outOfStockCount;
    } else {
        outOfStockBadgePulse.classList.add('hidden');
    }
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
    const categoryVal = (window.diActiveCategory || '').trim();
    const q = (els.diSearch?.value || '').trim().toLowerCase();
    
    if (statusVal) {
        filtered = filtered.filter(i => (i.status || '').toLowerCase() === statusVal.toLowerCase());
    }
    
    if (categoryVal) {
        filtered = filtered.filter(i => (i.category || '').toLowerCase() === categoryVal.toLowerCase());
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
                        <i class='bx bx-fw bxs-store text-6xl text-gray-400 mb-2'></i>
                        <p class="text-lg font-medium">No inventory items found!</p>
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
        
        // Status & Category Badge Helper Functions
        const getStatusBadgeClass = (status) => {
            switch ((status || '').toLowerCase()) {
                case 'in stock': return "bg-green-700 text-white shadow-sm border border-green-800";
                case 'low stock': return "bg-yellow-600 text-white shadow-sm border border-yellow-700";
                case 'out of stock': return "bg-red-700 text-white shadow-sm border border-red-800";
                default: return "bg-gray-600 text-white border border-gray-700";
            }
        };

        const getStatusIcon = (status) => {
            switch ((status || '').toLowerCase()) {
                case 'in stock': return "<i class='bx bx-check-circle'></i>";
                case 'low stock': return "<i class='bx bx-time-five'></i>";
                case 'out of stock': return "<i class='bx bx-x-circle'></i>";
                default: return "<i class='bx bx-help-circle'></i>";
            }
        };

        const getCategoryBadgeClass = (cat) => {
            switch ((cat || '').toLowerCase()) {
                case 'equipment': return "bg-blue-700 text-white shadow-sm border border-blue-800";
                case 'supplies': return "bg-purple-700 text-white shadow-sm border border-purple-800";
                case 'furniture': return "bg-orange-700 text-white shadow-sm border border-orange-800";
                case 'automotive': return "bg-slate-700 text-white shadow-sm border border-slate-800";
                default: return "bg-gray-600 text-white border border-gray-700";
            }
        };

        const getCategoryIcon = (cat) => {
            switch ((cat || '').toLowerCase()) {
                case 'equipment': return "<i class='bx bx-wrench'></i>";
                case 'supplies': return "<i class='bx bx-package'></i>";
                case 'furniture': return "<i class='bx bx-chair'></i>";
                case 'automotive': return "<i class='bx bx-car'></i>";
                default: return "<i class='bx bx-category'></i>";
            }
        };

        const lastUpdated = item.last_updated || 'N/A';
        
        html += `
            <tr class="hover:bg-gray-50 transition-colors border-b border-gray-100 group">
                <td class="px-4 py-4 whitespace-nowrap">
                    <div class="text-sm font-bold text-gray-900">${itemCode}</div>
                </td>
                <td class="px-4 py-4 whitespace-nowrap font-mono text-sm text-gray-600">${sku}</td>
                <td class="px-4 py-4 whitespace-nowrap text-sm font-semibold text-gray-700">${itemName}</td>
                <td class="px-4 py-4 whitespace-nowrap">
                    <span class="px-3 py-1.5 rounded-full text-sm font-bold flex items-center gap-1.5 w-fit ${getCategoryBadgeClass(category)}">
                        ${getCategoryIcon(category)}
                        ${category}
                    </span>
                </td>
                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">${storedFrom}</td>
                <td class="px-4 py-4 whitespace-nowrap text-center text-sm font-bold text-gray-700">${formatNumber(currentStock)}</td>
                <td class="px-4 py-4 whitespace-nowrap text-center text-sm text-gray-500">${formatNumber(maxStock)}</td>
                <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-bold text-emerald-600">${formatCurrency(unitPrice)}</td>
                <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-black text-emerald-700">${formatCurrency(totalValue)}</td>
                <td class="px-4 py-4 whitespace-nowrap">
                    <span class="px-3 py-1.5 rounded-full text-sm font-bold flex items-center gap-1.5 w-fit ${getStatusBadgeClass(status)}">
                        ${getStatusIcon(status)}
                        ${status}
                    </span>
                </td>
                <td class="px-4 py-4 whitespace-nowrap text-sm font-medium text-gray-500">${formatDate(lastUpdated)}</td>
                <td class="px-4 py-4 whitespace-nowrap text-right">
                    <div class="flex justify-end gap-1">
                        <button class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-all active:scale-90 view-item-btn" 
                                title="View Details" data-id="${item.item_id}">
                            <i class='bx bx-show text-lg'></i>
                        </button>
                        <button class="p-1.5 text-amber-600 hover:bg-amber-50 rounded-lg transition-all active:scale-90 edit-item-btn" 
                                title="Edit Item" data-id="${item.item_id}">
                            <i class='bx bx-edit text-lg'></i>
                        </button>
                        <button class="p-1.5 text-red-600 hover:bg-red-50 rounded-lg transition-all active:scale-90 delete-item-btn" 
                                title="Delete Item" data-id="${item.item_id}">
                            <i class='bx bx-trash text-lg'></i>
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
    const categoryVal = (window.diActiveCategory || '').trim();
    const q = (els.diSearch?.value || '').trim().toLowerCase();
    
    if (statusVal) {
        filtered = filtered.filter(i => (i.status || '').toLowerCase() === statusVal.toLowerCase());
    }
    
    if (categoryVal) {
        filtered = filtered.filter(i => (i.category || '').toLowerCase() === categoryVal.toLowerCase());
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
    
    if (categorySelect && categorySelect.tagName === 'SELECT') {
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
    
    // Save current value if any
    const currentVal = select.value;
    
    select.innerHTML = '<option value="">Select Item from Incoming Assets</option>';
    
    if (!incomingAssetsData || incomingAssetsData.length === 0) return;

    incomingAssetsData.forEach((item, index) => {
        const option = document.createElement('option');
        // Use the index as the value to easily retrieve the item data later
        option.value = index; 
        // Display Name and Prod ID (or ID if Prod ID is missing)
        const displayId = item.sws_purcprod_prod_id || item.sws_purcprod_id;
        option.textContent = `${item.sws_purcprod_prod_name || 'Unknown Item'} (ID: ${displayId})`;
        
        // Store name in dataset for SKU generation
        option.dataset.itemName = item.sws_purcprod_prod_name;
        
        select.appendChild(option);
    });
    
    // Restore value if it still exists (though indices might change, this is best effort)
    if (currentVal && incomingAssetsData[currentVal]) {
        select.value = currentVal;
    }
}

function onPurchaseItemSelected(e) {
    const index = e.target.value;
    const skuInput = document.getElementById('item_stock_keeping_unit');
    
    // Clear fields initially
    document.getElementById('psm_purchase_id').value = '';
    document.getElementById('psm_item_index').value = '';
    document.getElementById('psm_prod_id').value = '';
    document.getElementById('psm_purcprod_id').value = '';
    
    if (index === '') return;
    
    const item = incomingAssetsData[index];
    if (!item) return;
    
    console.log('Selected item for autofill:', item);

    // Populate Form Fields
    
    // Set PSM tracking IDs
    if (item.sws_purcprod_prod_id) {
        document.getElementById('psm_prod_id').value = item.sws_purcprod_prod_id;
    }
    if (item.sws_purcprod_id) {
        document.getElementById('psm_purcprod_id').value = item.sws_purcprod_id;
    }
    
    // SKU - Use Prod ID
    if (item.sws_purcprod_prod_id) {
        skuInput.value = item.sws_purcprod_prod_id;
    } else {
        skuInput.value = '';
    }
    
    // Description
    document.getElementById('item_description').value = item.sws_purcprod_desc || '';
    
    // Stock (Aggregated Units)
    document.getElementById('item_current_stock').value = item.sws_purcprod_prod_unit || 0;
    
    // Price (Aggregated Price)
    document.getElementById('item_unit_price').value = item.sws_purcprod_prod_price || 0;

    // Expiration Date
    document.getElementById('item_expiration_date').value = item.sws_purcprod_expiration || '';

    // Warranty End
    document.getElementById('item_warranty_end').value = item.sws_purcprod_warranty || '';
    
    // Item Type (Map to lowercase if exists)
    if (item.sws_purcprod_prod_type) {
        const type = item.sws_purcprod_prod_type.toLowerCase();
        const typeSelect = document.getElementById('item_item_type');
        // Check if option exists
        if ([...typeSelect.options].some(o => o.value === type)) {
            typeSelect.value = type;
        }
    }
}


// Incoming Assets Logic
let incomingAssetsData = [];
let currentIncomingAssetsPage = 1;
const incomingAssetsPerPage = 10;

function groupIncomingAssets(data) {
    const groupedMap = new Map();

    data.forEach(item => {
        const prodId = item.sws_purcprod_prod_id;
        // Group by prodId if available, otherwise use unique record ID
        const key = prodId ? prodId : `unique_${item.sws_purcprod_id}`;

        if (!groupedMap.has(key)) {
            // Create new entry
            const newItem = { ...item };
            newItem.sws_purcprod_prod_unit = parseFloat(item.sws_purcprod_prod_unit) || 0;
            newItem.sws_purcprod_prod_price = parseFloat(item.sws_purcprod_prod_price) || 0;
            groupedMap.set(key, newItem);
        } else {
            // Aggregate existing entry
            const existingItem = groupedMap.get(key);
            existingItem.sws_purcprod_prod_unit += parseFloat(item.sws_purcprod_prod_unit) || 0;
            existingItem.sws_purcprod_prod_price += parseFloat(item.sws_purcprod_prod_price) || 0;
        }
    });

    return Array.from(groupedMap.values());
}

async function loadIncomingAssets() {
    try {
        const response = await fetch('/api/v1/sws/incoming-assets', {
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
            // Group data by Prod ID before assigning
            incomingAssetsData = groupIncomingAssets(result.data);
            renderIncomingAssets();
            updateIncomingBadge();
        } else {
            incomingAssetsData = [];
            renderIncomingAssets();
            updateIncomingBadge();
        }
    } catch (e) {
        console.error('Error loading incoming assets:', e);
        incomingAssetsData = [];
        renderIncomingAssets();
        notify('Error loading incoming assets', 'error');
    }
}

function renderIncomingAssets() {
    const start = (currentIncomingAssetsPage - 1) * incomingAssetsPerPage;
    const end = start + incomingAssetsPerPage;
    const paginatedData = incomingAssetsData.slice(start, end);
    const tbody = els.incomingAssetsTableBody;
    
    tbody.innerHTML = '';
    
    if (paginatedData.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="12" class="text-center py-8 text-gray-500">
                    <div class="flex flex-col items-center justify-center">
                        <i class='bx bx-fw bxs-store text-6xl text-gray-400 mb-2'></i>
                        <p class="text-lg font-medium">No incoming assets found!</p>
                    </div>
                </td>
            </tr>
        `;
        updateIncomingAssetsPagination(0);
        return;
    }

    paginatedData.forEach(item => {
        const tr = document.createElement('tr');
        tr.className = 'hover:bg-gray-50 transition-colors border-b border-gray-100 group';
        tr.innerHTML = `
            <td class="font-mono text-sm whitespace-nowrap">${item.sws_purcprod_id}</td>
            <td class="font-mono text-sm whitespace-nowrap">${item.sws_purcprod_prod_id || '-'}</td>
            <td class="font-semibold whitespace-nowrap">${item.sws_purcprod_prod_name || '-'}</td>
            <td class="whitespace-nowrap">${formatCurrency(item.sws_purcprod_prod_price)}</td>
            <td class="whitespace-nowrap">${item.sws_purcprod_prod_unit || '-'}</td>
            <td class="whitespace-nowrap capitalize">${item.sws_purcprod_prod_type || '-'}</td>
            <td class="whitespace-nowrap">
                <span class="px-2 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    ${item.sws_purcprod_status || 'Pending'}
                </span>
            </td>
            <td class="whitespace-nowrap">${formatDate(item.sws_purcprod_date)}</td>
            <td class="whitespace-nowrap">${item.sws_purcprod_warranty || '-'}</td>
            <td class="whitespace-nowrap">${item.sws_purcprod_expiration || '-'}</td>
            <td class="max-w-xs truncate whitespace-nowrap" title="${item.sws_purcprod_desc || ''}">${item.sws_purcprod_desc || '-'}</td>
            <td class="whitespace-nowrap">
                <div class="flex justify-end gap-1">
                    <button class="p-1.5 text-blue-600 hover:bg-blue-50 rounded-lg transition-all active:scale-90" title="View" onclick="viewIncomingAsset('${item.sws_purcprod_id}')">
                        <i class='bx bx-show-alt text-lg'></i>
                    </button>
                    <button class="p-1.5 text-emerald-600 hover:bg-emerald-50 rounded-lg transition-all active:scale-90" title="Add to Inventory" onclick="addIncomingAssetToForm('${item.sws_purcprod_id}')">
                        <i class='bx bx-plus-circle text-lg'></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(tr);
    });

    updateIncomingAssetsPagination(incomingAssetsData.length);
}

function updateIncomingBadge() {
    const badge = document.getElementById('incomingBadge');
    const countEl = document.getElementById('incomingBadgeCount');
    if (!badge || !countEl) return;
    const pending = (incomingAssetsData || []).filter(x => {
        const v = (x.sws_purcprod_inventory || '').toString().toLowerCase();
        return v !== 'yes';
    }).length;
    if (pending > 0) {
        countEl.textContent = pending > 99 ? '99+' : String(pending);
        badge.classList.remove('hidden');
    } else {
        badge.classList.add('hidden');
    }
}

window.deleteIncomingAsset = function(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(`/api/v1/sws/incoming-assets/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    closeIncomingAssetsModal(); // Close the modal first
                    Toast.fire({
                        icon: 'success',
                        title: 'Successfully deleted!'
                    });
                    // Refresh data in background or just wait until next open
                    loadIncomingAssets(); 
                } else {
                    notify(data.message || 'Error deleting item', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                notify('An error occurred while deleting', 'error');
            });
        }
    });
}

function updateIncomingAssetsPagination(totalItems) {
    const totalPages = Math.max(1, Math.ceil(totalItems / incomingAssetsPerPage));
    const start = totalItems === 0 ? 0 : (currentIncomingAssetsPage - 1) * incomingAssetsPerPage + 1;
    const end = Math.min(currentIncomingAssetsPage * incomingAssetsPerPage, totalItems);
    const info = document.getElementById('incomingPagerInfo');
    const display = document.getElementById('incomingPageDisplay');
    if (info) info.textContent = `Showing ${start}-${end} of ${totalItems}`;
    if (display) display.textContent = `${currentIncomingAssetsPage} / ${totalPages}`;
    const prev = document.getElementById('incomingPrevBtn');
    const next = document.getElementById('incomingNextBtn');
    if (prev) prev.disabled = currentIncomingAssetsPage <= 1;
    if (next) next.disabled = currentIncomingAssetsPage >= totalPages;
}

function openIncomingAssetsModal() {
    els.incomingAssetsModal.classList.remove('hidden');
    currentIncomingAssetsPage = 1;
    loadIncomingAssets();
}

function closeIncomingAssetsModal() {
    if (window.isDualInventoryOpen) {
        closeInventoryDualModal();
    } else {
        els.incomingAssetsModal.classList.add('hidden');
    }
}

async function openAddItemModal() {
    if (window.isDualInventoryOpen) {
        return; // handled by dual modal opener
    }
    await prepareAddItemPane();
    els.addItemModal.classList.remove('hidden');
}

function closeAddItemModal() {
    if (window.isDualInventoryOpen) {
        closeInventoryDualModal();
    } else {
        els.addItemModal.classList.add('hidden');
    }
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

function findIncomingById(id) {
    return (incomingAssetsData || []).find(x => String(x.sws_purcprod_id) === String(id));
}

window.viewIncomingAsset = function(id) {
    const item = findIncomingById(id);
    if (!item) return;
    Swal.fire({
        title: 'Incoming Asset',
        html: `
            <div class="text-left">
                <p><strong>ID:</strong> ${item.sws_purcprod_id}</p>
                <p><strong>Prod ID:</strong> ${item.sws_purcprod_prod_id || '-'}</p>
                <p><strong>Name:</strong> ${item.sws_purcprod_prod_name || '-'}</p>
                <p><strong>Price:</strong> ${formatCurrency(item.sws_purcprod_prod_price)}</p>
                <p><strong>Unit:</strong> ${item.sws_purcprod_prod_unit || '-'}</p>
                <p><strong>Type:</strong> ${item.sws_purcprod_prod_type || '-'}</p>
                <p><strong>Status:</strong> ${item.sws_purcprod_status || 'Pending'}</p>
                <p><strong>Date:</strong> ${formatDate(item.sws_purcprod_date)}</p>
                <p><strong>Warranty:</strong> ${item.sws_purcprod_warranty || '-'}</p>
                <p><strong>Expiration:</strong> ${item.sws_purcprod_expiration || '-'}</p>
                <p><strong>Description:</strong> ${item.sws_purcprod_desc || '-'}</p>
            </div>
        `,
        icon: 'info',
        confirmButtonText: 'Close'
    });
}

window.addIncomingAssetToForm = function(id) {
    const item = findIncomingById(id);
    if (!item) return;
    document.getElementById('item_name').value = item.sws_purcprod_prod_name || '';
    document.getElementById('psm_prod_id').value = item.sws_purcprod_prod_id || '';
    document.getElementById('psm_purcprod_id').value = item.sws_purcprod_id || '';
    const skuInput = document.getElementById('item_stock_keeping_unit');
    if (item.sws_purcprod_prod_id) {
        skuInput.value = item.sws_purcprod_prod_id;
    } else {
        skuInput.value = '';
    }
    document.getElementById('item_description').value = item.sws_purcprod_desc || '';
    document.getElementById('item_current_stock').value = item.sws_purcprod_prod_unit || 0;
    document.getElementById('item_unit_price').value = item.sws_purcprod_prod_price || 0;
    document.getElementById('item_expiration_date').value = item.sws_purcprod_expiration || '';
    document.getElementById('item_warranty_end').value = item.sws_purcprod_warranty || '';
    if (item.sws_purcprod_prod_type) {
        const type = item.sws_purcprod_prod_type.toLowerCase();
        const typeSelect = document.getElementById('item_item_type');
        if ([...typeSelect.options].some(o => o.value === type)) {
            typeSelect.value = type;
        }
        // Auto-select Category id and display name
        const catIdEl = document.getElementById('item_category_id');
        const catNameEl = document.getElementById('item_category_name');
        const t = (item.sws_purcprod_prod_type || '').trim().toLowerCase();
        let matched = null;
        if (Array.isArray(categories) && categories.length) {
            matched = categories.find(c => (c.cat_name || '').trim().toLowerCase() === t) 
                   || categories.find(c => (c.cat_name || '').trim().toLowerCase().includes(t));
        }
        if (matched) {
            if (catIdEl) catIdEl.value = matched.cat_id;
            if (catNameEl) catNameEl.value = matched.cat_name;
        } else {
            if (catIdEl) catIdEl.value = '';
            if (catNameEl) catNameEl.value = item.sws_purcprod_prod_type || '';
        }
    }
}

async function prepareAddItemPane() {
    els.addItemForm.reset();
    document.getElementById('item_max_stock').value = 100;
    document.getElementById('item_is_fixed').checked = false;
    document.getElementById('item_is_collateral').checked = false;
    updateItemCodePreview();
    await loadIncomingAssets();
    document.getElementById('item_name').value = '';
}

window.isDualInventoryOpen = false;

async function openInventoryDualModal() {
    window.isDualInventoryOpen = true;
    await prepareAddItemPane();
    els.incomingAssetsModal.className = "fixed inset-y-0 left-0 right-1/2 bg-transparent flex items-stretch p-4 z-[60]";
    els.addItemModal.className = "fixed inset-y-0 left-1/2 right-0 bg-transparent flex items-stretch p-4 z-[60]";
    const backdrop = document.getElementById('dualInventoryBackdrop');
    const inInner = els.incomingAssetsModal.querySelector('.bg-white');
    const addInner = els.addItemModal.querySelector('.bg-white');
    if (inInner) {
        inInner.classList.add('w-full','h-full','max-w-none','rounded-xl','shadow-lg','overflow-hidden');
    }
    if (addInner) {
        addInner.classList.add('w-full','h-full','max-w-none','rounded-xl','shadow-lg','overflow-hidden');
    }
    if (backdrop) backdrop.classList.remove('hidden');
    els.incomingAssetsModal.classList.remove('hidden');
    els.addItemModal.classList.remove('hidden');
    currentIncomingAssetsPage = 1;
    renderIncomingAssets();
}

function closeInventoryDualModal() {
    window.isDualInventoryOpen = false;
    els.incomingAssetsModal.className = "fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50";
    els.addItemModal.className = "fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50";
    const backdrop = document.getElementById('dualInventoryBackdrop');
    if (backdrop) backdrop.classList.add('hidden');
}

// Item CRUD Functions
async function saveItem(e) {
    e.preventDefault();
    const sku = (document.getElementById('item_stock_keeping_unit').value || '').trim();
    const deltaUnits = parseInt(document.getElementById('item_current_stock').value);
    const incomingId = (document.getElementById('psm_purcprod_id').value || '').trim();
    const itemNameVal = (document.getElementById('item_name').value || '').trim();

    if (!itemNameVal) {
        notify('Please choose an incoming asset via the Add button', 'error');
        return;
    }
    if (!sku) {
        notify('Missing Product ID from the selected incoming asset', 'error');
        return;
    }
    if (isNaN(deltaUnits) || deltaUnits <= 0) {
        notify('Incoming Units must be a positive number', 'error');
        return;
    }

    // Find existing inventory item by Product ID (SKU)
    const existing = (inventoryItems || []).find(i => String(i.item_stock_keeping_unit || '').trim() === sku);
    if (!existing) {
        notify(`No existing inventory item found with Product ID ${sku}`, 'error');
        return;
    }

    // Fetch latest item details to avoid overwriting other fields
    let itemDetail = existing;
    try {
        const r = await fetch(`${SWS_ITEMS_API}/${existing.item_id}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            credentials: 'include'
        });
        if (r.ok) {
            const j = await r.json();
            if (j.success && j.data) itemDetail = j.data;
        }
    } catch (_) {}

    const currentStock = parseInt(itemDetail.item_current_stock ?? itemDetail.current_stock ?? 0) || 0;
    const newStock = currentStock + deltaUnits;

    const payload = {
        item_name: itemDetail.item_name || '',
        item_description: itemDetail.item_description || null,
        item_category_id: itemDetail.item_category_id || null,
        item_stored_from: itemDetail.item_stored_from || null,
        item_item_type: itemDetail.item_item_type || 'illiquid',
        item_is_fixed: !!itemDetail.item_is_fixed,
        item_expiration_date: itemDetail.item_expiration_date || null,
        item_warranty_end: itemDetail.item_warranty_end || null,
        item_unit_price: itemDetail.item_unit_price ?? null, // keep unchanged
        item_current_stock: newStock, // increment only
        item_max_stock: itemDetail.item_max_stock || 100,
        item_liquidity_risk_level: itemDetail.item_liquidity_risk_level || 'medium',
        item_is_collateral: !!itemDetail.item_is_collateral
    };

    try {
        const response = await fetch(`${SWS_ITEMS_API}/${itemDetail.item_id}`, {
            method: 'PUT',
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
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        const result = await response.json();
        if (!result.success) throw new Error(result.message || 'Failed to update stock');

        // Mark incoming asset as inventoried = "yes"
        if (incomingId) {
            try {
                await fetch(`/api/v1/sws/incoming-assets/${incomingId}`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': CSRF_TOKEN,
                        'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
                    },
                    body: JSON.stringify({ sws_purcprod_inventory: 'yes' }),
                    credentials: 'include'
                });
            } catch (e) {
                console.warn('Failed to mark incoming as inventoried:', e);
            }
        }

        notify('Stock added and incoming marked as inventoried', 'success');
        closeAddItemModal();
        // Refresh UI: inventory, stats, and incoming list/badge
        await Promise.all([
            loadInventoryItems(),
            loadInventoryStats(),
            loadStockLevels(),
            loadIncomingAssets()
        ]);
    } catch (e) {
        console.error('Error updating stock:', e);
        notify(e.message || 'Error updating stock', 'error');
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
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <!-- Basic Info -->
                    <div class="bg-gray-50/50 p-4 rounded-2xl border border-gray-100">
                        <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2 block">Identity</span>
                        <div class="space-y-3">
                            <div>
                                <span class="text-xs text-gray-500 block">Item Code</span>
                                <p class="font-bold font-mono text-gray-800">${itemCode}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 block">SKU / Product ID</span>
                                <p class="font-bold font-mono text-gray-800">${item.item_stock_keeping_unit || 'N/A'}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 block">Item Name</span>
                                <p class="font-bold text-gray-800">${item.item_name || 'N/A'}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Category & Type -->
                    <div class="bg-gray-50/50 p-4 rounded-2xl border border-gray-100">
                        <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2 block">Classification</span>
                        <div class="space-y-3">
                            <div>
                                <span class="text-xs text-gray-500 block">Category</span>
                                <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-blue-100 text-blue-700 inline-block mt-1">
                                    <i class='bx bx-category-alt mr-1'></i>${item.category?.cat_name || 'Uncategorized'}
                                </span>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 block">Item Type</span>
                                <p class="font-bold text-gray-800 capitalize">${item.item_item_type || 'N/A'}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 block">Stored From</span>
                                <p class="font-bold text-gray-800">${item.item_stored_from || 'N/A'}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Stock Info -->
                    <div class="bg-gray-50/50 p-4 rounded-2xl border border-gray-100">
                        <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2 block">Inventory Level</span>
                        <div class="space-y-3">
                            <div>
                                <span class="text-xs text-gray-500 block">Current Stock</span>
                                <p class="text-xl font-black text-gray-800">${formatNumber(item.item_current_stock)}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 block">Max Stock Capacity</span>
                                <p class="font-bold text-gray-600">${formatNumber(item.item_max_stock)}</p>
                            </div>
                            <div>
                                <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                    <div class="bg-brand-primary h-2 rounded-full" style="width: ${Math.min((item.item_current_stock / item.item_max_stock) * 100, 100)}%"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Financials -->
                    <div class="bg-emerald-50/30 p-4 rounded-2xl border border-emerald-100/50">
                        <span class="text-[11px] font-bold text-emerald-600 uppercase tracking-wider mb-2 block">Financial Data</span>
                        <div class="space-y-3">
                            <div>
                                <span class="text-xs text-emerald-600/70 block">Unit Price</span>
                                <p class="text-lg font-bold text-emerald-700">${formatCurrency(unitPrice)}</p>
                            </div>
                            <div>
                                <span class="text-xs text-emerald-600/70 block">Total Inventory Value</span>
                                <p class="text-2xl font-black text-emerald-800">${formatCurrency(totalValue)}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Risk & Dates -->
                    <div class="bg-gray-50/50 p-4 rounded-2xl border border-gray-100">
                        <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2 block">Risk & Compliance</span>
                        <div class="space-y-3">
                            <div>
                                <span class="text-xs text-gray-500 block">Liquidity Risk</span>
                                <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase ${item.item_liquidity_risk_level === 'high' ? 'bg-red-100 text-red-700' : 'bg-blue-100 text-blue-700'}">
                                    ${item.item_liquidity_risk_level || 'N/A'}
                                </span>
                            </div>
                            <div class="flex gap-4">
                                <div>
                                    <span class="text-xs text-gray-500 block">Fixed Asset</span>
                                    <p class="font-bold text-gray-800">${item.item_is_fixed ? 'Yes' : 'No'}</p>
                                </div>
                                <div>
                                    <span class="text-xs text-gray-500 block">Collateral</span>
                                    <p class="font-bold text-gray-800">${item.item_is_collateral ? 'Yes' : 'No'}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Timestamps -->
                    <div class="bg-gray-50/50 p-4 rounded-2xl border border-gray-100">
                        <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2 block">Timeline</span>
                        <div class="space-y-3">
                            <div>
                                <span class="text-xs text-gray-500 block">Expiration Date</span>
                                <p class="font-bold text-gray-800">${formatDate(item.item_expiration_date)}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 block">Warranty End</span>
                                <p class="font-bold text-gray-800">${formatDate(item.item_warranty_end)}</p>
                            </div>
                            <div>
                                <span class="text-xs text-gray-500 block">Last Activity</span>
                                <p class="text-xs font-medium text-gray-500 italic">${formatDate(lastUpdated)}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2 lg:col-span-3 bg-gray-50/50 p-4 rounded-2xl border border-gray-100">
                        <span class="text-[11px] font-bold text-gray-400 uppercase tracking-wider mb-2 block">Item Description</span>
                        <p class="text-sm text-gray-700 leading-relaxed">${item.item_description || 'No description provided for this item.'}</p>
                    </div>
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
        loadLocations(),
        loadIncomingAssets()
    ]);
    
    // Quick Actions Event Listeners
    if (els.transferBtn) els.transferBtn.addEventListener('click', openTransferModal);
    if (els.viewLocationsBtn) els.viewLocationsBtn.addEventListener('click', openViewLocationsModal);
    if (els.viewCategoriesBtn) els.viewCategoriesBtn.addEventListener('click', openViewCategoriesModal);
    if (els.closeViewLocationsModalBtn) els.closeViewLocationsModalBtn.addEventListener('click', closeViewLocationsModalFunc);

    // Incoming Assets Event Listeners
    // Incoming button merged into Inventory New Item
    if (els.closeIncomingAssetsModal) els.closeIncomingAssetsModal.addEventListener('click', closeIncomingAssetsModal);
    if (els.incomingAssetsModal) {
        els.incomingAssetsModal.addEventListener('click', (e) => {
            if (e.target === els.incomingAssetsModal) closeIncomingAssetsModal();
        });
    }

    // Incoming Assets Pagination Listeners
    const changeIncomingAssetsPage = (delta) => {
        const totalPages = Math.max(1, Math.ceil(incomingAssetsData.length / incomingAssetsPerPage));
        const newPage = currentIncomingAssetsPage + delta;
        if (newPage >= 1 && newPage <= totalPages) {
            currentIncomingAssetsPage = newPage;
            renderIncomingAssets();
        }
    };

    const incomingPrev = document.getElementById('incomingPrevBtn');
    const incomingNext = document.getElementById('incomingNextBtn');
    if (incomingPrev) incomingPrev.addEventListener('click', () => changeIncomingAssetsPage(-1));
    if (incomingNext) incomingNext.addEventListener('click', () => changeIncomingAssetsPage(1));

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
    if (els.inventoryNewItemBtn) els.inventoryNewItemBtn.addEventListener('click', openInventoryDualModal);
    
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
    if (els.diStatusSelect) {
        els.diStatusSelect.addEventListener('change', () => {
            window.diActiveStatus = els.diStatusSelect.value || '';
            currentDiPage = 1; renderInventoryItems();
        });
    }
    if (els.diCategorySelect) {
        els.diCategorySelect.addEventListener('change', () => {
            window.diActiveCategory = els.diCategorySelect.value || '';
            currentDiPage = 1; renderInventoryItems();
        });
    }
    
    // Stat cards toggle filter
    const diCardLow = document.getElementById('di_card_low');
    if (diCardLow) {
        diCardLow.addEventListener('click', () => {
            window.diActiveStatus = 'Low Stock';
            if (els.diStatusSelect) {
                els.diStatusSelect.value = 'Low Stock';
                els.diStatusSelect.dispatchEvent(new Event('change'));
            } else {
                currentDiPage = 1; renderInventoryItems();
            }
        });
    }

    const diCardOut = document.getElementById('di_card_out');
    if (diCardOut) {
        diCardOut.addEventListener('click', () => {
            window.diActiveStatus = 'Out of Stock';
            if (els.diStatusSelect) {
                els.diStatusSelect.value = 'Out of Stock';
                els.diStatusSelect.dispatchEvent(new Event('change'));
            } else {
                currentDiPage = 1; renderInventoryItems();
            }
        });
    }

    const diCardTotal = document.getElementById('di_card_total');
    if (diCardTotal) {
        diCardTotal.addEventListener('click', () => {
            window.diActiveStatus = '';
            if (els.diStatusSelect) {
                els.diStatusSelect.value = '';
                els.diStatusSelect.dispatchEvent(new Event('change'));
            } else {
                currentDiPage = 1; renderInventoryItems();
            }
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
