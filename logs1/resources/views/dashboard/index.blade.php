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
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5 hidden">
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
            <div onclick="window.location.href='?module=psm-purchase'" class="bg-white rounded-2xl shadow-sm border border-gray-100 border-b-4 border-blue-600 p-6 relative overflow-hidden group hover:shadow-md transition-all duration-300 cursor-pointer active:scale-95">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform duration-300">
                    <i class='bx bxs-purchase-tag text-6xl text-blue-700'></i>
                </div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-3 bg-blue-50 rounded-xl text-blue-700">
                            <i class='bx bxs-purchase-tag text-2xl'></i>
                        </div>
                        <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Purchase Orders</h4>
                    </div>
                    <div class="flex items-end gap-2">
                        <span id="poTotalValue" class="text-4xl font-black text-gray-800 leading-none">0</span>
                        <span id="poPendingValue" class="text-xs text-blue-700 mb-1">0 Pending Approval</span>
                    </div>
                </div>
            </div>

            <!-- Stats 02: Active Vendors -->
            <div onclick="window.location.href='?module=psm-vendor-management'" class="bg-white rounded-2xl shadow-sm border border-gray-100 border-b-4 border-green-600 p-6 relative overflow-hidden group hover:shadow-md transition-all duration-300 cursor-pointer active:scale-95">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform duration-300">
                    <i class='bx bxs-user-detail text-6xl text-green-700'></i>
                </div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-3 bg-green-50 rounded-xl text-green-700">
                            <i class='bx bxs-user-detail text-2xl'></i>
                        </div>
                        <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Active Vendors</h4>
                    </div>
                    <div class="flex items-end gap-2">
                        <span id="vendorActiveValue" class="text-4xl font-black text-gray-800 leading-none">0</span>
                        <span id="vendorProductsValue" class="text-xs text-green-700 mb-1">0 Products</span>
                    </div>
                </div>
            </div>

            <!-- Stats 03: Warehouse Inventory -->
            <div onclick="window.location.href='?module=sws-digital-inventory'" class="bg-white rounded-2xl shadow-sm border border-gray-100 border-b-4 border-purple-600 p-6 relative overflow-hidden group hover:shadow-md transition-all duration-300 cursor-pointer active:scale-95">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform duration-300">
                    <i class='bx bxs-archive-in text-6xl text-purple-700'></i>
                </div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-3 bg-purple-50 rounded-xl text-purple-700">
                            <i class='bx bxs-archive-in text-2xl'></i>
                        </div>
                        <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Total Inventory</h4>
                    </div>
                    <div class="flex items-end gap-2">
                        <span id="invTotalValue" class="text-4xl font-black text-gray-800 leading-none">0</span>
                        <span id="invLowValue" class="text-xs text-purple-700 mb-1">0 Low Stock Items</span>
                    </div>
                </div>
            </div>

            <!-- Stats 04: Active Projects -->
            <div onclick="window.location.href='?module=plt-logistics-projects'" class="bg-white rounded-2xl shadow-sm border border-gray-100 border-b-4 border-orange-600 p-6 relative overflow-hidden group hover:shadow-md transition-all duration-300 cursor-pointer active:scale-95">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform duration-300">
                    <i class='bx bxs-package text-6xl text-orange-700'></i>
                </div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-3 bg-orange-50 rounded-xl text-orange-700">
                            <i class='bx bxs-package text-2xl'></i>
                        </div>
                        <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Active Projects</h4>
                    </div>
                    <div class="flex items-end gap-2">
                        <span id="projActiveValue" class="text-4xl font-black text-gray-800 leading-none">0</span>
                        <span id="projDelayedValue" class="text-xs text-orange-700 mb-1">0 Delayed</span>
                    </div>
                </div>
            </div>

            <!-- Stats 05: Total Assets -->
            <div onclick="window.location.href='?module=alms-asset-management'" class="bg-white rounded-2xl shadow-sm border border-gray-100 border-b-4 border-cyan-600 p-6 relative overflow-hidden group hover:shadow-md transition-all duration-300 cursor-pointer active:scale-95">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform duration-300">
                    <i class='bx bxs-archive text-6xl text-cyan-700'></i>
                </div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-3 bg-cyan-50 rounded-xl text-cyan-700">
                            <i class='bx bxs-archive text-2xl'></i>
                        </div>
                        <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Managed Assets</h4>
                    </div>
                    <div class="flex items-end gap-2">
                        <span id="assetsTotalValue" class="text-4xl font-black text-gray-800 leading-none">0</span>
                        <span id="assetsMaintValue" class="text-xs text-cyan-700 mb-1">0 Under Maintenance</span>
                    </div>
                </div>
            </div>

            <!-- Stats 06: Pending Documents -->
            <div onclick="window.location.href='?module=dtlr-document-tracker'" class="bg-white rounded-2xl shadow-sm border border-gray-100 border-b-4 border-red-600 p-6 relative overflow-hidden group hover:shadow-md transition-all duration-300 cursor-pointer active:scale-95">
                <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform duration-300">
                    <i class='bx bxs-file text-6xl text-red-700'></i>
                </div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="p-3 bg-red-50 rounded-xl text-red-700">
                            <i class='bx bxs-file text-2xl'></i>
                        </div>
                        <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Pending Docs</h4>
                    </div>
                    <div class="flex items-end gap-2">
                        <span id="docsPendingValue" class="text-4xl font-black text-gray-800 leading-none">0</span>
                        <span class="text-xs text-red-700 mb-1">Require Attention</span>
                    </div>
                </div>
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
    var go = function(path){ window.location.href = '?module=' + path; };
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

        var purchases = Array.isArray(poJson.data) ? poJson.data : (Array.isArray(poJson.items) ? poJson.items : (Array.isArray(poJson) ? poJson : []));
        var poTotal = purchases.length || 0;
        var poPending = purchases.filter(function(p){
            var s = String((p.pur_status || p.status || '')).toLowerCase();
            return s.indexOf('pending') !== -1;
        }).length;
        var poActive = purchases.filter(function(p){
            var s = String((p.pur_status || p.status || '')).toLowerCase();
            return s.indexOf('cancel') === -1 && s.indexOf('deliver') === -1 && s.indexOf('closed') === -1;
        }).length;

        var vStats = vendorJson && (vendorJson.data || vendorJson.stats) ? (vendorJson.data || vendorJson.stats) : {};
        var activeVendors = parseInt(vStats.active_vendors || vStats.vendors_active || vStats.total_vendors || 0);
        var totalProducts = parseInt(vStats.total_products || vStats.products_total || 0);

        var invStats = invJson && (invJson.data || invJson.stats) ? (invJson.data || invJson.stats) : {};
        var totalItems = parseInt(invStats.total_items || invStats.items_total || 0);
        var lowItems = parseInt(invStats.low_stock_items || invStats.low_stock || 0);

        var projStats = projJson && (projJson.data || projJson.stats) ? (projJson.data || projJson.stats) : {};
        var activeProjects = parseInt(projStats.active || projStats.ongoing || 0);
        var delayedProjects = parseInt(projStats.delayed || projStats.behind || 0);
        var totalProjects = parseInt(projStats.total || projStats.projects_total || (activeProjects + delayedProjects) || 0);

        var assets = Array.isArray(assetsJson.data) ? assetsJson.data : (Array.isArray(assetsJson) ? assetsJson : []);
        var assetsTotal = assets.length || 0;
        var assetsMaint = assets.filter(function(x){
            var s = String((x.asset_status || x.status || '')).toLowerCase();
            return s.indexOf('maint') !== -1;
        }).length;

        var docs = Array.isArray(docsJson.data) ? docsJson.data : (Array.isArray(docsJson) ? docsJson : []);
        var docsPending = docs.filter(function(d){
            var s = String((d.doc_status || d.status || '')).toLowerCase();
            return s === 'pending_review' || s === 'pending' || s.indexOf('pending') !== -1;
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
