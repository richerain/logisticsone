<!-- resources/views/dashboard/index.blade.php -->
@php
    $currentUser = null;
    $currentRole = 'user';
    $isSws = false;
    $isVendor = false;

    try {
        if (Auth::guard('vendor')->check()) {
            $isVendor = true;
            $currentUser = Auth::guard('vendor')->user();
        } elseif (Auth::guard('sws')->check()) {
            $isSws = true;
            $currentUser = Auth::guard('sws')->user();
            $currentRole = strtolower($currentUser->roles ?? 'user');
        }
    } catch (\Throwable $e) {
        \Illuminate\Support\Facades\Log::error('Dashboard auth check failed: ' . $e->getMessage());
    }
@endphp

@if($isVendor)
    <div class="mb-6 flex items-center justify-between gap-4">
        <div class="flex items-center">
            <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bxs-dashboard'></i>Vendor Dashboard</h2>
        </div>
        <div class="text-right">
            <span class="text-md text-gray-600">Welcome back, {{ optional($currentUser)->ven_contact_person ?? optional($currentUser)->ven_company_name ?? 'Vendor' }} - Vendor</span>
        </div>
    </div>

    <!-- Vendor Statistics Section start -->
    <div class="mb-3 bg-white p-5 rounded-lg shadow-xl overflow-visible">
        <div class="flex items-center mb-2 space-x-2 text-gray-700">
            <h2 class="text-lg font-semibold"><i class='bx bx-fw bx-stats'></i>Overview Metrics</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
            <!-- Stats 01: Active Quotes -->
            <div class="stat card bg-white shadow-xl hover:shadow-2xl transition-shadow rounded-lg border-l-4 border-t-0 border-r-0 border-b-0 border-blue-700">
                <div class="stat-title flex items-center justify-between">
                    <span class="font-semibold text-blue-900">Active Quotes</span>
                    <span class="flex items-center justify-center w-10 h-10 rounded-full bg-white text-blue-900 shadow-sm">
                        <i class="bx bxs-file-find text-xl" aria-hidden="true"></i>
                    </span>
                </div>
                <div class="stat-value text-blue-900">12</div>
                <div class="stat-desc text-blue-700">3 Pending Review</div>
            </div>

            <!-- Stats 02: My Products -->
            <div class="stat card bg-white shadow-xl hover:shadow-2xl transition-shadow rounded-lg border-l-4 border-t-0 border-r-0 border-b-0 border-green-700">
                <div class="stat-title flex items-center justify-between">
                    <span class="font-semibold text-green-900">My Products</span>
                    <span class="flex items-center justify-center w-10 h-10 rounded-full bg-white text-green-900 shadow-sm">
                        <i class="bx bxs-package text-xl" aria-hidden="true"></i>
                    </span>
                </div>
                <div class="stat-value text-green-900">45</div>
                <div class="stat-desc text-green-700">Updated Recently</div>
            </div>

            <!-- Stats 03: Purchase Orders -->
            <div class="stat card bg-white shadow-xl hover:shadow-2xl transition-shadow rounded-lg border-l-4 border-t-0 border-r-0 border-b-0 border-purple-700">
                <div class="stat-title flex items-center justify-between">
                    <span class="font-semibold text-purple-900">Purchase Orders</span>
                    <span class="flex items-center justify-center w-10 h-10 rounded-full bg-white text-purple-900 shadow-sm">
                        <i class="bx bxs-purchase-tag text-xl" aria-hidden="true"></i>
                    </span>
                </div>
                <div class="stat-value text-purple-900">8</div>
                <div class="stat-desc text-purple-700">2 New Orders</div>
            </div>
        </div>
    </div>
    <!-- Vendor Statistics Section end -->

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Recent RFQs -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Recent RFQs</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-white rounded-lg hover:bg-gray-100 transition-colors">
                    <div>
                        <h4 class="font-medium text-gray-900">Office Furniture Supply</h4>
                        <p class="text-sm text-gray-500">ID: RFQ-2025-001</p>
                    </div>
                    <span class="px-3 py-1 text-xs font-semibold text-blue-800 bg-white rounded-full">Open</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-white rounded-lg hover:bg-gray-100 transition-colors">
                    <div>
                        <h4 class="font-medium text-gray-900">Laptop Procurement</h4>
                        <p class="text-sm text-gray-500">ID: RFQ-2025-002</p>
                    </div>
                    <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-white rounded-full">Awarded</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-white rounded-lg hover:bg-gray-100 transition-colors">
                    <div>
                        <h4 class="font-medium text-gray-900">Network Equipment</h4>
                        <p class="text-sm text-gray-500">ID: RFQ-2025-003</p>
                    </div>
                    <span class="px-3 py-1 text-xs font-semibold text-yellow-800 bg-white rounded-full">Pending</span>
                </div>
            </div>
        </div>

        <!-- Notifications -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Notifications</h3>
            <div class="space-y-4">
                <div class="flex items-start p-3 bg-white rounded-lg">
                    <i class='bx bx-info-circle text-blue-500 text-xl mr-3 mt-0.5'></i>
                    <div>
                        <p class="text-sm text-gray-800">Your quote for <strong>Office Chairs</strong> has been viewed.</p>
                        <span class="text-xs text-gray-500">2 hours ago</span>
                    </div>
                </div>
                <div class="flex items-start p-3 bg-white rounded-lg">
                    <i class='bx bx-check-circle text-green-500 text-xl mr-3 mt-0.5'></i>
                    <div>
                        <p class="text-sm text-gray-800">New Purchase Order received #PO-4821.</p>
                        <span class="text-xs text-gray-500">Yesterday</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="mb-6 flex items-center justify-between gap-4">
        <div class="flex items-center">
            <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bxs-dashboard'></i>Dashboard</h2>
        </div>
        <div class="text-right">
            <span class="text-md text-gray-600">Welcome back, {{ optional($currentUser)->firstname }} - {{ ucfirst(optional($currentUser)->roles ?? 'User') }}</span>
        </div>
    </div>

    <!-- announcement board section removed -->

    <!-- Modals removed -->


    
    <!-- Statistics Section start -->
    <div class="mb-3 bg-white p-5 rounded-lg shadow-xl overflow-visible">
        <div class="flex items-center mb-2 space-x-2 text-gray-700">
            <h2 class="text-lg font-semibold"><i class='bx bx-fw bx-stats'></i>System Overview Metrics</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
            <!-- Stats 01: Total Purchase Orders -->
            <div id="metricPurchaseOrders" class="stat card bg-white shadow-xl hover:shadow-2xl transition-shadow rounded-lg border-l-4 border-t-0 border-r-0 border-b-0 border-blue-700 cursor-pointer">
                <div class="stat-title flex items-center justify-between">
                    <span class="font-semibold text-blue-900">Purchase Orders</span>
                    <span class="flex items-center justify-center w-10 h-10 rounded-full bg-white text-blue-900 shadow-sm">
                        <i class="bx bxs-purchase-tag text-xl" aria-hidden="true"></i>
                    </span>
                </div>
                <div id="poTotalValue" class="stat-value text-blue-900">0</div>
                <div id="poPendingValue" class="stat-desc text-blue-700">0 Pending Approval</div>
            </div>

            <!-- Stats 02: Active Vendors -->
            <div id="metricActiveVendors" class="stat card bg-white shadow-xl hover:shadow-2xl transition-shadow rounded-lg border-l-4 border-t-0 border-r-0 border-b-0 border-green-700 cursor-pointer">
                <div class="stat-title flex items-center justify-between">
                    <span class="font-semibold text-green-900">Active Vendors</span>
                    <span class="flex items-center justify-center w-10 h-10 rounded-full bg-white text-green-900 shadow-sm">
                        <i class="bx bxs-user-detail text-xl" aria-hidden="true"></i>
                    </span>
                </div>
                <div id="vendorActiveValue" class="stat-value text-green-900">0</div>
                <div id="vendorProductsValue" class="stat-desc text-green-700">0 Products</div>
            </div>

            <!-- Stats 03: Warehouse Inventory -->
            <div id="metricTotalInventory" class="stat card bg-white shadow-xl hover:shadow-2xl transition-shadow rounded-lg border-l-4 border-t-0 border-r-0 border-b-0 border-purple-700 cursor-pointer">
                <div class="stat-title flex items-center justify-between">
                    <span class="font-semibold text-purple-900">Total Inventory</span>
                    <span class="flex items-center justify-center w-10 h-10 rounded-full bg-white text-purple-900 shadow-sm">
                        <i class="bx bxs-archive-in text-xl" aria-hidden="true"></i>
                    </span>
                </div>
                <div id="invTotalValue" class="stat-value text-purple-900">0</div>
                <div id="invLowValue" class="stat-desc text-purple-700">0 Low Stock Items</div>
            </div>

            <!-- Stats 04: Active Projects -->
            <div id="metricActiveProjects" class="stat card bg-white shadow-xl hover:shadow-2xl transition-shadow rounded-lg border-l-4 border-t-0 border-r-0 border-b-0 border-orange-700 cursor-pointer">
                <div class="stat-title flex items-center justify-between">
                    <span class="font-semibold text-orange-900">Active Projects</span>
                    <span class="flex items-center justify-center w-10 h-10 rounded-full bg-white text-orange-900 shadow-sm">
                        <i class="bx bxs-package text-xl" aria-hidden="true"></i>
                    </span>
                </div>
                <div id="projActiveValue" class="stat-value text-orange-900">0</div>
                <div id="projDelayedValue" class="stat-desc text-orange-700">0 Delayed</div>
            </div>

            <!-- Stats 05: Total Assets -->
            <div id="metricManagedAssets" class="stat card bg-white shadow-xl hover:shadow-2xl transition-shadow rounded-lg border-l-4 border-t-0 border-r-0 border-b-0 border-cyan-700 cursor-pointer">
                <div class="stat-title flex items-center justify-between">
                    <span class="font-semibold text-cyan-900">Managed Assets</span>
                    <span class="flex items-center justify-center w-10 h-10 rounded-full bg-white text-cyan-900 shadow-sm">
                        <i class="bx bxs-archive text-xl" aria-hidden="true"></i>
                    </span>
                </div>
                <div id="assetsTotalValue" class="stat-value text-cyan-900">0</div>
                <div id="assetsMaintValue" class="stat-desc text-cyan-700">0 Under Maintenance</div>
            </div>

            <!-- Stats 06: Pending Documents -->
            <div id="metricPendingDocs" class="stat card bg-white shadow-xl hover:shadow-2xl transition-shadow rounded-lg border-l-4 border-t-0 border-r-0 border-b-0 border-red-700 cursor-pointer">
                <div class="stat-title flex items-center justify-between">
                    <span class="font-semibold text-red-900">Pending Docs</span>
                    <span class="flex items-center justify-center w-10 h-10 rounded-full bg-white text-red-900 shadow-sm">
                        <i class="bx bxs-file text-xl" aria-hidden="true"></i>
                    </span>
                </div>
                <div id="docsPendingValue" class="stat-value text-red-900">0</div>
                <div class="stat-desc text-red-700">Require Attention</div>
            </div>
        </div>

        <!-- Quick Module Status Overview -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- SWS Status -->
            <div class="bg-white rounded-lg p-3 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-gray-700">SWS</span>
                    <span class="flex items-center text-xs px-2 py-1 rounded-full bg-white text-green-800">
                        <i class='bx bxs-circle text-xs mr-1'></i>Active
                    </span>
                </div>
                <p class="text-xs text-gray-600 mt-1">Smart Warehousing</p>
                <div class="mt-2 flex justify-between text-xs">
                    <span id="swsItemsCount">0 Items</span>
                    <span id="swsLowCount" class="text-red-600">0 Low</span>
                </div>
            </div>

            <!-- PSM Status -->
            <div class="bg-white rounded-lg p-3 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-gray-700">PSM</span>
                    <span class="flex items-center text-xs px-2 py-1 rounded-full bg-white text-green-800">
                        <i class='bx bxs-circle text-xs mr-1'></i>Active
                    </span>
                </div>
                <p class="text-xs text-gray-600 mt-1">Procurement & Sourcing</p>
                <div class="mt-2 flex justify-between text-xs">
                    <span id="psmActivePO">0 Active PO</span>
                    <span id="psmPendingPO" class="text-orange-600">0 Pending</span>
                </div>
            </div>

            <!-- PLT Status -->
            <div class="bg-white rounded-lg p-3 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-gray-700">PLT</span>
                    <span class="flex items-center text-xs px-2 py-1 rounded-full bg-white text-green-800">
                        <i class='bx bxs-circle text-xs mr-1'></i>Active
                    </span>
                </div>
                <p class="text-xs text-gray-600 mt-1">Project Logistics</p>
                <div class="mt-2 flex justify-between text-xs">
                    <span id="pltProjectsCount">0 Projects</span>
                    <span id="pltOngoingCount" class="text-yellow-600">0 Ongoing</span>
                </div>
            </div>

            <!-- ALMS Status -->
            <div class="bg-white rounded-lg p-3 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-gray-700">ALMS</span>
                    <span class="flex items-center text-xs px-2 py-1 rounded-full bg-white text-green-800">
                        <i class='bx bxs-circle text-xs mr-1'></i>Active
                    </span>
                </div>
                <p class="text-xs text-gray-600 mt-1">Asset Management</p>
                <div class="mt-2 flex justify-between text-xs">
                    <span id="almsAssetsCount">0 Assets</span>
                    <span id="almsMaintCount" class="text-blue-600">0 Maintenance</span>
                </div>
            </div>

            <!-- DTLR Status -->
            <div class="bg-white rounded-lg p-3 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-gray-700">DTLR</span>
                    <span class="flex items-center text-xs px-2 py-1 rounded-full bg-white text-green-800">
                        <i class='bx bxs-circle text-xs mr-1'></i>Active
                    </span>
                </div>
                <p class="text-xs text-gray-600 mt-1">Document Tracking</p>
                <div class="mt-2 flex justify-between text-xs">
                    <span id="dtlrDocsCount">0 Docs</span>
                    <span id="dtlrPendingCount" class="text-red-600">0 Pending</span>
                </div>
            </div>
        </div>
    </div>
    <!-- Statistics Section end -->

    <!-- statistics charts section start -->
    <div class="bg-white rounded-lg p-5 shadow-xl overflow-visible mb-3">
        <h2 class="text-gray-700 text-lg font-semibold mb-4"><i class='bx bx-fw bxs-pie-chart-alt-2'></i>Module Performance Charts</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Chart 1: Purchase Order Status (PSM) -->
            <div class="chart-card bg-white p-4 rounded-lg shadow">
                <h3 class="font-bold text-gray-800 flex items-center">
                    <i class='bx bxs-purchase-tag mr-2 text-blue-600'></i>
                    PO Status Distribution
                </h3>
                <div class="chart-placeholder h-32 bg-white rounded-lg p-1">
                    <canvas id="poStatusChart" style="width:100%;height:160px;"></canvas>
                </div>
            </div>

            <!-- Chart 2: Inventory Categories (SWS) -->
            <div class="chart-card bg-white p-4 rounded-lg shadow">
                <h3 class="font-bold text-gray-800 flex items-center">
                    <i class='bx bxs-archive-in mr-2 text-purple-600'></i>
                    Inventory by Category
                </h3>
                <div class="chart-placeholder h-32 bg-white rounded-lg p-1">
                    <canvas id="inventoryChart" style="width:100%;height:160px;"></canvas>
                </div>
            </div>

            <!-- Chart 3: Project Progress (PLT) -->
            <div class="chart-card bg-white p-4 rounded-lg shadow">
                <h3 class="font-bold text-gray-800 flex items-center">
                    <i class='bx bxs-package mr-2 text-orange-600'></i>
                    Project Progress
                </h3>
                <div class="chart-placeholder h-32 bg-white rounded-lg p-1">
                    <canvas id="projectProgressChart" style="width:100%;height:160px;"></canvas>
                </div>
            </div>

            <!-- Chart 4: Asset Status (ALMS) -->
            <div class="chart-card bg-white p-4 rounded-lg shadow">
                <h3 class="font-bold text-gray-800 flex items-center">
                    <i class='bx bxs-archive mr-2 text-cyan-600'></i>
                    Asset Status Overview
                </h3>
                <div class="chart-placeholder h-32 bg-white rounded-lg p-1">
                    <canvas id="assetStatusChart" style="width:100%;height:160px;"></canvas>
                </div>
            </div>

            <!-- Chart 5: Document Status (DTLR) -->
            <div class="chart-card bg-white p-4 rounded-lg shadow">
                <h3 class="font-bold text-gray-800 flex items-center">
                    <i class='bx bxs-file mr-2 text-red-600'></i>
                    Document Status
                </h3>
                <div class="chart-placeholder h-32 bg-white rounded-lg p-1">
                    <canvas id="documentStatusChart" style="width:100%;height:160px;"></canvas>
                </div>
            </div>

            <!-- Chart 6: Vendor Performance (PSM) -->
            <div class="chart-card bg-white p-4 rounded-lg shadow">
                <h3 class="font-bold text-gray-800 flex items-center">
                    <i class='bx bxs-user-detail mr-2 text-green-600'></i>
                    Vendor Performance
                </h3>
                <div class="chart-placeholder h-32 bg-white rounded-lg p-1">
                    <canvas id="vendorPerformanceChart" style="width:100%;height:160px;"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- statistics charts section end -->
@endif

<script>
// Module Charts Data Initialization start
// Ensure JWT token available for API calls (jwt.auth middleware)
var JWT_TOKEN = (typeof JWT_TOKEN !== 'undefined' && JWT_TOKEN) ? JWT_TOKEN : '';
if (!JWT_TOKEN && typeof window !== 'undefined' && typeof window.SERVER_JWT_TOKEN !== 'undefined' && window.SERVER_JWT_TOKEN) {
    JWT_TOKEN = window.SERVER_JWT_TOKEN;
    try { localStorage.setItem('jwt', JWT_TOKEN); } catch(e){}
}
if (!JWT_TOKEN) {
    try { JWT_TOKEN = localStorage.getItem('jwt') || ''; } catch(e){ JWT_TOKEN = ''; }
}
// CSRF fallback
var CSRF_TOKEN = (typeof CSRF_TOKEN !== 'undefined' && CSRF_TOKEN) ? CSRF_TOKEN : (document.querySelector('meta[name="csrf-token"]') ? document.querySelector('meta[name="csrf-token"]').getAttribute('content') : '');

document.addEventListener('DOMContentLoaded', function () {
    initializeModuleCharts();
    wireDashboardMetricLinks();
    loadDashboardStats();
});

function initializeModuleCharts() {
    // Chart 1: Purchase Order Status (PSM)
    const poCtx = document.getElementById('poStatusChart');
    if (poCtx) {
        new Chart(poCtx, {
            type: 'doughnut',
            data: {
                labels: ['Approved', 'Pending', 'Rejected', 'Draft'],
                datasets: [{
                    data: [25, 12, 5, 5],
                    backgroundColor: ['#10B981', '#F59E0B', '#EF4444', '#6B7280'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        display: true,
                        position: 'bottom',
                        labels: { boxWidth: 12, font: { size: 10 } }
                    }
                }
            }
        });
    }

    // Chart 2: Inventory Categories (SWS)
    const inventoryCtx = document.getElementById('inventoryChart');
    if (inventoryCtx) {
        new Chart(inventoryCtx, {
            type: 'pie',
            data: {
                labels: ['Furniture', 'Electronics', 'Office Supplies', 'Equipment', 'Raw Materials'],
                datasets: [{
                    data: [350, 280, 420, 150, 47],
                    backgroundColor: ['#8B5CF6', '#3B82F6', '#10B981', '#F59E0B', '#EF4444'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        display: true,
                        position: 'bottom',
                        labels: { boxWidth: 12, font: { size: 9 } }
                    }
                }
            }
        });
    }

    // Chart 3: Project Progress (PLT)
    const projectCtx = document.getElementById('projectProgressChart');
    if (projectCtx) {
        new Chart(projectCtx, {
            type: 'bar',
            data: {
                labels: ['Warehouse Relocation', 'System Implementation', 'Fleet Upgrade', 'Process Optimization', 'Training Program'],
                datasets: [{
                    label: 'Completion %',
                    data: [65, 90, 45, 75, 30],
                    backgroundColor: '#F59E0B',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        beginAtZero: true,
                        max: 100,
                        ticks: { callback: function(value) { return value + '%'; } }
                    },
                    x: { ticks: { font: { size: 8 } } }
                }
            }
        });
    }

    // Chart 4: Asset Status (ALMS)
    const assetCtx = document.getElementById('assetStatusChart');
    if (assetCtx) {
        new Chart(assetCtx, {
            type: 'doughnut',
            data: {
                labels: ['Operational', 'Under Maintenance', 'Out of Service', 'In Storage'],
                datasets: [{
                    data: [245, 18, 21, 15],
                    backgroundColor: ['#06B6D4', '#F59E0B', '#EF4444', '#6B7280'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        display: true,
                        position: 'bottom',
                        labels: { boxWidth: 12, font: { size: 10 } }
                    }
                }
            }
        });
    }

    // Chart 5: Document Status (DTLR)
    const documentCtx = document.getElementById('documentStatusChart');
    if (documentCtx) {
        new Chart(documentCtx, {
            type: 'pie',
            data: {
                labels: ['Approved', 'Pending Review', 'Rejected', 'Archived'],
                datasets: [{
                    data: [1245, 387, 210, 125],
                    backgroundColor: ['#10B981', '#F59E0B', '#EF4444', '#6B7280'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        display: true,
                        position: 'bottom',
                        labels: { boxWidth: 12, font: { size: 9 } }
                    }
                }
            }
        });
    }

    // Chart 6: Vendor Performance (PSM)
    const vendorCtx = document.getElementById('vendorPerformanceChart');
    if (vendorCtx) {
        new Chart(vendorCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'On-Time Delivery Rate',
                    data: [85, 88, 92, 90, 87, 94],
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#10B981'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        beginAtZero: false,
                        min: 80,
                        max: 100,
                        ticks: { callback: function(value) { return value + '%'; } }
                    }
                }
            }
        });
    }
}// Module Charts Data Initialization end

function wireDashboardMetricLinks(){
    var go = function(path){ window.location.href = '/module/' + path; };
    var a = document.getElementById('metricPurchaseOrders'); if(a) a.addEventListener('click', function(){ go('psm-purchase'); });
    var b = document.getElementById('metricActiveVendors'); if(b) b.addEventListener('click', function(){ go('psm-vendor-management'); });
    var c = document.getElementById('metricTotalInventory'); if(c) c.addEventListener('click', function(){ go('sws-digital-inventory'); });
    var d = document.getElementById('metricActiveProjects'); if(d) d.addEventListener('click', function(){ go('plt-logistics-projects'); });
    var e = document.getElementById('metricManagedAssets'); if(e) e.addEventListener('click', function(){ go('alms-asset-management'); });
    var f = document.getElementById('metricPendingDocs'); if(f) f.addEventListener('click', function(){ go('dtlr-document-tracker'); });
}

async function loadDashboardStats(){
    var headers = {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': typeof CSRF_TOKEN !== 'undefined' ? CSRF_TOKEN : '',
        'Authorization': typeof JWT_TOKEN !== 'undefined' && JWT_TOKEN ? ('Bearer ' + JWT_TOKEN) : ''
    };
    try{
        var [poRes, vendorRes, invRes, projRes, assetsRes, docsRes] = await Promise.all([
            fetch('/api/v1/psm/purchase-management', { headers: headers, credentials: 'include' }),
            fetch('/api/v1/psm/vendor-management/stats', { headers: headers, credentials: 'include' }),
            fetch('/api/v1/sws/inventory-stats', { headers: headers, credentials: 'include' }),
            fetch('/api/v1/plt/projects/stats', { headers: headers, credentials: 'include' }),
            fetch('/api/v1/alms/assets', { headers: headers, credentials: 'include' }),
            fetch('/api/v1/dtlr/document-tracker', { headers: headers, credentials: 'include' })
        ]);
        var poJson = await poRes.json().catch(function(){ return {}; });
        var vendorJson = await vendorRes.json().catch(function(){ return {}; });
        var invJson = await invRes.json().catch(function(){ return {}; });
        var projJson = await projRes.json().catch(function(){ return {}; });
        var assetsJson = await assetsRes.json().catch(function(){ return {}; });
        var docsJson = await docsRes.json().catch(function(){ return {}; });

        var purchases = Array.isArray(poJson.data) ? poJson.data : (Array.isArray(poJson) ? poJson : []);
        var poTotal = purchases.length || 0;
        var poPending = purchases.filter(function(p){
            var s = String((p.pur_status || '')).toLowerCase();
            return s.indexOf('pending') !== -1;
        }).length;
        var poActive = purchases.filter(function(p){
            var s = String((p.pur_status || '')).toLowerCase();
            return s !== 'cancel' && s !== 'delivered';
        }).length;

        var vStats = vendorJson && vendorJson.data ? vendorJson.data : {};
        var activeVendors = parseInt(vStats.active_vendors || vStats.total_vendors || 0);
        var totalProducts = parseInt(vStats.total_products || 0);

        var invStats = invJson && invJson.data ? invJson.data : {};
        var totalItems = parseInt(invStats.total_items || 0);
        var lowItems = parseInt(invStats.low_stock_items || 0);

        var projStats = projJson && projJson.data ? projJson.data : {};
        var activeProjects = parseInt(projStats.active || 0);
        var delayedProjects = parseInt(projStats.delayed || 0);
        var totalProjects = parseInt(projStats.total || (activeProjects + delayedProjects) || 0);

        var assets = Array.isArray(assetsJson.data) ? assetsJson.data : (Array.isArray(assetsJson) ? assetsJson : []);
        var assetsTotal = assets.length || 0;
        var assetsMaint = assets.filter(function(x){
            return String((x.asset_status || '')).toLowerCase() === 'under_maintenance';
        }).length;

        var docs = Array.isArray(docsJson.data) ? docsJson.data : (Array.isArray(docsJson) ? docsJson : []);
        var docsPending = docs.filter(function(d){
            return String((d.doc_status || '')).toLowerCase() === 'pending_review';
        }).length;
        var docsTotal = docs.length || 0;

        var el;
        el = document.getElementById('poTotalValue'); if(el) el.textContent = poTotal.toLocaleString();
        el = document.getElementById('poPendingValue'); if(el) el.textContent = (poPending.toLocaleString()) + ' Pending Approval';
        el = document.getElementById('vendorActiveValue'); if(el) el.textContent = activeVendors.toLocaleString();
        el = document.getElementById('vendorProductsValue'); if(el) el.textContent = (totalProducts.toLocaleString()) + ' Products';
        el = document.getElementById('invTotalValue'); if(el) el.textContent = totalItems.toLocaleString();
        el = document.getElementById('invLowValue'); if(el) el.textContent = (lowItems.toLocaleString()) + ' Low Stock Items';
        el = document.getElementById('projActiveValue'); if(el) el.textContent = activeProjects.toLocaleString();
        el = document.getElementById('projDelayedValue'); if(el) el.textContent = (delayedProjects.toLocaleString()) + ' Delayed';
        el = document.getElementById('assetsTotalValue'); if(el) el.textContent = assetsTotal.toLocaleString();
        el = document.getElementById('assetsMaintValue'); if(el) el.textContent = (assetsMaint.toLocaleString()) + ' Under Maintenance';
        el = document.getElementById('docsPendingValue'); if(el) el.textContent = docsPending.toLocaleString();

        el = document.getElementById('swsItemsCount'); if(el) el.textContent = (totalItems.toLocaleString()) + ' Items';
        el = document.getElementById('swsLowCount'); if(el) el.textContent = (lowItems.toLocaleString()) + ' Low';
        el = document.getElementById('psmActivePO'); if(el) el.textContent = (poActive.toLocaleString()) + ' Active PO';
        el = document.getElementById('psmPendingPO'); if(el) el.textContent = (poPending.toLocaleString()) + ' Pending';
        el = document.getElementById('pltProjectsCount'); if(el) el.textContent = (totalProjects.toLocaleString()) + ' Projects';
        el = document.getElementById('pltOngoingCount'); if(el) el.textContent = (activeProjects.toLocaleString()) + ' Ongoing';
        el = document.getElementById('almsAssetsCount'); if(el) el.textContent = (assetsTotal.toLocaleString()) + ' Assets';
        el = document.getElementById('almsMaintCount'); if(el) el.textContent = (assetsMaint.toLocaleString()) + ' Maintenance';
        el = document.getElementById('dtlrDocsCount'); if(el) el.textContent = (docsTotal.toLocaleString()) + ' Docs';
        el = document.getElementById('dtlrPendingCount'); if(el) el.textContent = (docsPending.toLocaleString()) + ' Pending';
    }catch(e){}
}
</script>
