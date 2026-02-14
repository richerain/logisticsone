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
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
        <div onclick="window.location.href='?module=psm-purchase'" class="bg-white rounded-2xl shadow-sm border border-gray-100 border-b-4 border-blue-600 p-6 relative overflow-hidden group hover:shadow-md transition-all duration-300 cursor-pointer active:scale-95">
            <div class="absolute top-2 right-2 opacity-10 group-hover:scale-110 transition-transform duration-300 pointer-events-none select-none z-0 w-20 h-20 flex items-center justify-center">
                <i class='bx bxs-purchase-tag text-6xl text-blue-700 leading-none'></i>
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
        <div onclick="window.location.href='?module=psm-purchase-requisition'" class="bg-white rounded-2xl shadow-sm border border-gray-100 border-b-4 border-green-600 p-6 relative overflow-hidden group hover:shadow-md transition-all duration-300 cursor-pointer active:scale-95">
            <div class="absolute top-2 right-2 opacity-10 group-hover:scale-110 transition-transform duration-300 pointer-events-none select-none z-0 w-20 h-20 flex items-center justify-center">
                <i class='bx bxs-clipboard text-6xl text-green-700 leading-none'></i>
            </div>
            <div class="relative z-10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="p-3 bg-green-50 rounded-xl text-green-700">
                        <i class='bx bxs-clipboard text-2xl'></i>
                    </div>
                    <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Purchase Requisitions</h4>
                </div>
                <div class="flex items-end gap-2">
                    <span id="prTotalValue" class="text-4xl font-black text-gray-800 leading-none">0</span>
                    <span id="prPendingValue" class="text-xs text-green-700 mb-1">0 Pending</span>
                </div>
            </div>
        </div>
        <div onclick="window.location.href='?module=sws-digital-inventory'" class="bg-white rounded-2xl shadow-sm border border-gray-100 border-b-4 border-purple-600 p-6 relative overflow-hidden group hover:shadow-md transition-all duration-300 cursor-pointer active:scale-95">
            <div class="absolute top-2 right-2 opacity-10 group-hover:scale-110 transition-transform duration-300 pointer-events-none select-none z-0 w-20 h-20 flex items-center justify-center">
                <i class='bx bxs-archive-in text-6xl text-purple-700 leading-none'></i>
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
        <div onclick="window.location.href='?module=plt-logistics-projects'" class="bg-white rounded-2xl shadow-sm border border-gray-100 border-b-4 border-orange-600 p-6 relative overflow-hidden group hover:shadow-md transition-all duration-300 cursor-pointer active:scale-95">
            <div class="absolute top-2 right-2 opacity-10 group-hover:scale-110 transition-transform duration-300 pointer-events-none select-none z-0 w-20 h-20 flex items-center justify-center">
                <i class='bx bxs-package text-6xl text-orange-700 leading-none'></i>
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
        <div onclick="window.location.href='?module=alms-asset-management'" class="bg-white rounded-2xl shadow-sm border border-gray-100 border-b-4 border-cyan-600 p-6 relative overflow-hidden group hover:shadow-md transition-all duration-300 cursor-pointer active:scale-95">
            <div class="absolute top-2 right-2 opacity-10 group-hover:scale-110 transition-transform duration-300 pointer-events-none select-none z-0 w-20 h-20 flex items-center justify-center">
                <i class='bx bxs-archive text-6xl text-cyan-700 leading-none'></i>
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
        <div onclick="window.location.href='?module=dtlr-document-tracker'" class="bg-white rounded-2xl shadow-sm border border-gray-100 border-b-4 border-red-600 p-6 relative overflow-hidden group hover:shadow-md transition-all duration-300 cursor-pointer active:scale-95">
            <div class="absolute top-2 right-2 opacity-10 group-hover:scale-110 transition-transform duration-300 pointer-events-none select-none z-0 w-20 h-20 flex items-center justify-center">
                <i class='bx bxs-file text-6xl text-red-700 leading-none'></i>
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

    <!-- Statistics Section start -->
    <div class="mb-3 bg-white p-5 rounded-lg shadow-xl overflow-visible">
        <!-- Quick Module Status Overview -->
        <div class="mt-1 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- SWS Status -->
            <div class="bg-white rounded-lg p-3 shadow-sm border border-gray-100 border-b-4 border-purple-600 hover:shadow-md transition">
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
            <div class="bg-white rounded-lg p-3 shadow-sm border border-gray-100 border-b-4 border-blue-600 hover:shadow-md transition">
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
            <div class="bg-white rounded-lg p-3 shadow-sm border border-gray-100 border-b-4 border-orange-600 hover:shadow-md transition">
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
            <div class="bg-white rounded-lg p-3 shadow-sm border border-gray-100 border-b-4 border-cyan-600 hover:shadow-md transition">
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
            <div class="bg-white rounded-lg p-3 shadow-sm border border-gray-100 border-b-4 border-red-600 hover:shadow-md transition">
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
    <div class="bg-white rounded-lg p-6 shadow-xl overflow-visible mb-4">
        <h2 class="text-gray-700 text-lg font-semibold mb-4"><i class='bx bx-fw bxs-pie-chart-alt-2'></i>Module Performance Charts</h2>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Chart 1: Purchase Order Status (PSM) -->
            <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 border-b-4 border-blue-600">
                <h3 class="font-bold text-gray-800 flex items-center mb-3"><i class='bx bxs-purchase-tag mr-2 text-blue-600'></i>PO Status Distribution</h3>
                <div class="bg-white rounded-lg">
                    <canvas id="poStatusChart" style="width:100%;height:320px;"></canvas>
                </div>
            </div>

            <!-- Chart 2: Inventory Categories (SWS) -->
            <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 border-b-4 border-purple-600">
                <h3 class="font-bold text-gray-800 flex items-center mb-3"><i class='bx bxs-archive-in mr-2 text-purple-600'></i>Inventory by Category</h3>
                <div class="bg-white rounded-lg">
                    <canvas id="inventoryChart" style="width:100%;height:320px;"></canvas>
                </div>
            </div>

            <!-- Chart 3: Project Progress (PLT) -->
            <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 border-b-4 border-orange-600">
                <h3 class="font-bold text-gray-800 flex items-center mb-3"><i class='bx bxs-package mr-2 text-orange-600'></i>Project Progress</h3>
                <div class="bg-white rounded-lg">
                    <canvas id="projectProgressChart" style="width:100%;height:320px;"></canvas>
                </div>
            </div>

            <!-- Chart 4: Asset Status (ALMS) -->
            <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 border-b-4 border-cyan-600">
                <h3 class="font-bold text-gray-800 flex items-center mb-3"><i class='bx bxs-archive mr-2 text-cyan-600'></i>Asset Status Overview</h3>
                <div class="bg-white rounded-lg">
                    <canvas id="assetStatusChart" style="width:100%;height:320px;"></canvas>
                </div>
            </div>

            <!-- Chart 5: Document Status (DTLR) -->
            <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 border-b-4 border-red-600">
                <h3 class="font-bold text-gray-800 flex items-center mb-3"><i class='bx bxs-file mr-2 text-red-600'></i>Document Status</h3>
                <div class="bg-white rounded-lg">
                    <canvas id="documentStatusChart" style="width:100%;height:320px;"></canvas>
                </div>
            </div>

            <!-- Chart 6: Purchase Requisitions (PSM) -->
            <div class="bg-white p-6 rounded-2xl shadow-md border border-gray-100 border-b-4 border-green-600">
                <h3 class="font-bold text-gray-800 flex items-center mb-3"><i class='bx bxs-clipboard mr-2 text-green-600'></i>Purchase Requisitions</h3>
                <div class="bg-white rounded-lg">
                    <canvas id="purchaseRequisitionChart" style="width:100%;height:320px;"></canvas>
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
    configureChartsTheme();
    initializeModuleCharts();
    wireDashboardMetricLinks();
    ensureAuth().then(loadDashboardStats);
    initRouteAwareMetrics();
});

function configureChartsTheme(){
    if (typeof Chart === 'undefined') return;
    Chart.defaults.color = '#334155';
    Chart.defaults.font.family = 'Inter, ui-sans-serif, system-ui, -apple-system, Segoe UI, Roboto, Helvetica Neue, Arial';
    Chart.defaults.font.size = 12;
    Chart.defaults.plugins.legend.labels.boxWidth = 12;
    Chart.defaults.plugins.legend.labels.font.size = 10;
    Chart.defaults.borderColor = 'rgba(148, 163, 184, 0.25)';
}

function initializeModuleCharts() {
    window.__charts = window.__charts || {};
}
// helpers: create or update charts
function upsertChart(canvasId, chartType, labels, dataset, options){
    var ctx = document.getElementById(canvasId);
    if (!ctx || typeof Chart === 'undefined') return;
    window.__charts = window.__charts || {};
    var existing = window.__charts[canvasId];
    if (existing){
        existing.data.labels = labels;
        existing.data.datasets = [dataset];
        existing.update();
        return;
    }
    var cfg = {
        type: chartType,
        data: { labels: labels, datasets: [dataset] },
        options: options || { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: true, position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } } } }
    };
    window.__charts[canvasId] = new Chart(ctx, cfg);
}

function wireDashboardMetricLinks(){
    var go = function(path){ window.location.href = '?module=' + path; };
    var a = document.getElementById('metricPurchaseOrders'); if(a) a.addEventListener('click', function(){ go('psm-purchase'); });
    var b = document.getElementById('metricPurchaseRequisitions'); if(b) b.addEventListener('click', function(){ go('psm-purchase-requisition'); });
    var c = document.getElementById('metricTotalInventory'); if(c) c.addEventListener('click', function(){ go('sws-digital-inventory'); });
    var d = document.getElementById('metricActiveProjects'); if(d) d.addEventListener('click', function(){ go('plt-logistics-projects'); });
    var e = document.getElementById('metricManagedAssets'); if(e) e.addEventListener('click', function(){ go('alms-asset-management'); });
    var f = document.getElementById('metricPendingDocs'); if(f) f.addEventListener('click', function(){ go('dtlr-document-tracker'); });
}

async function ensureAuth(){
    if (typeof JWT_TOKEN === 'string' && JWT_TOKEN) return;
    try{
        var resp = await fetch('/auth/token', { credentials: 'include' });
        if (resp.ok){
            var j = await resp.json();
            if (j && j.token){
                JWT_TOKEN = j.token;
                try { localStorage.setItem('jwt', JWT_TOKEN); } catch(e){}
            }
        }
    }catch(e){}
}

function isDashboardRoute(){
    try{
        var params = new URLSearchParams(window.location.search);
        var m = params.get('module');
        return !m || m === 'dashboard';
    }catch(e){ return true; }
}

function initRouteAwareMetrics(){
    if (isDashboardRoute()){
        try { ensureAuth().then(loadDashboardStats); } catch(e){}
    }
    (function(){
        var origPush = history.pushState;
        var origReplace = history.replaceState;
        history.pushState = function(){ var r = origPush.apply(this, arguments); window.dispatchEvent(new Event('locationchange')); return r; };
        history.replaceState = function(){ var r = origReplace.apply(this, arguments); window.dispatchEvent(new Event('locationchange')); return r; };
        window.addEventListener('popstate', function(){ window.dispatchEvent(new Event('locationchange')); });
    })();
    var reinit = function(){
        if (isDashboardRoute()){
            ensureAuth().then(loadDashboardStats);
        }
    };
    window.addEventListener('locationchange', reinit);
    window.addEventListener('pageshow', reinit);
    window.addEventListener('visibilitychange', function(){ if (!document.hidden) reinit(); });
    window.addEventListener('focus', reinit);
}

async function loadDashboardStats(){
    var headers = {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
        'X-CSRF-TOKEN': typeof CSRF_TOKEN !== 'undefined' ? CSRF_TOKEN : '',
        'Authorization': typeof JWT_TOKEN !== 'undefined' && JWT_TOKEN ? ('Bearer ' + JWT_TOKEN) : ''
    };
    try{
        async function doFetchSet(){
            var reqs = [
                ['po','/api/v1/psm/purchase-management'],
                ['preq','/api/v1/psm/requisitions'],
                ['inv','/api/v1/sws/inventory-stats'],
                ['proj','/api/v1/plt/projects/stats'],
                ['assets','/api/v1/alms/assets'],
                ['docs','/api/v1/dtlr/document-tracker'],
                ['cats','/api/v1/sws/stock-levels']
            ];
            var settled = await Promise.allSettled(reqs.map(function(e){ return fetch(e[1], { headers: headers, credentials: 'include' }); }));
            var map = {};
            var had401 = false;
            settled.forEach(function(r, i){
                var key = reqs[i][0];
                if (r.status === 'fulfilled'){
                    map[key] = r.value;
                    if (r.value && r.value.status === 401) had401 = true;
                }else{
                    map[key] = null;
                }
            });
            return { map: map, had401: had401 };
        }
        var r1 = await doFetchSet();
        if (r1.had401){
            await ensureAuth();
            headers['Authorization'] = typeof JWT_TOKEN !== 'undefined' && JWT_TOKEN ? ('Bearer ' + JWT_TOKEN) : '';
            var r2 = await doFetchSet();
            r1 = r2;
        }
        async function safeJson(res, def){
            try{
                if (res && (res.ok || res.status === 200)) { return await res.json(); }
            }catch(e){}
            return def || {};
        }
        var poJson = await safeJson(r1.map.po, {});
        var prJson = await safeJson(r1.map.preq, {});
        var invJson = await safeJson(r1.map.inv, {});
        var projJson = await safeJson(r1.map.proj, {});
        var assetsJson = await safeJson(r1.map.assets, {});
        var docsJson = await safeJson(r1.map.docs, {});
        var catJson = await safeJson(r1.map.cats, {});

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

        var preqs = Array.isArray(prJson.data) ? prJson.data : (Array.isArray(prJson) ? prJson : []);
        var prTotal = preqs.length || 0;
        var prPending = preqs.filter(function(p){
            var s = String((p.status || p.requisition_status || '')).toLowerCase();
            return s.indexOf('pending') !== -1 || s.indexOf('review') !== -1 || s.indexOf('approval') !== -1;
        }).length;

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

        // CHARTS: Purchase Orders status distribution
        var poMap = {};
        purchases.forEach(function(p){
            var raw = String((p.pur_status || p.status || '')).trim().toLowerCase();
            var key = raw.replace(/_/g,' ');
            if (!key) key = 'other';
            poMap[key] = (poMap[key] || 0) + 1;
        });
        var poOrder = ['pending','approved','processing order','vendor-review','po received','dispatched','delivered','in-progress','completed','rejected','cancel','other'];
        var poLabels = [];
        var poValues = [];
        poOrder.forEach(function(k){
            if (poMap[k]){ poLabels.push(k.replace(/\b\w/g, c => c.toUpperCase())); poValues.push(poMap[k]); }
        });
        if (poLabels.length === 0){
            Object.keys(poMap).forEach(function(k){ poLabels.push(k.replace(/\b\w/g, c => c.toUpperCase())); poValues.push(poMap[k]); });
        }
        upsertChart('poStatusChart', 'doughnut', poLabels, {
            data: poValues,
            backgroundColor: ['#F59E0B','#10B981','#3B82F6','#06B6D4','#8B5CF6','#F97316','#22C55E','#84CC16','#A855F7','#EF4444','#6B7280','#94A3B8']
        }, { responsive: true, maintainAspectRatio: false, cutout: '58%', plugins: { legend: { display: true, position: 'bottom' } } });

        // CHARTS: Inventory by Category (total_quantity)
        var cats = Array.isArray(catJson.data) ? catJson.data : (Array.isArray(catJson) ? catJson : []);
        var catLabels = cats.map(function(c){ return c.name || 'Unknown'; }).slice(0, 12);
        var catValues = cats.map(function(c){ return parseInt(c.total_quantity || 0); }).slice(0, 12);
        upsertChart('inventoryChart', 'pie', catLabels, {
            data: catValues,
            backgroundColor: ['#8B5CF6','#3B82F6','#10B981','#F59E0B','#EF4444','#06B6D4','#A78BFA','#34D399','#FBBF24','#60A5FA','#FB7185','#14B8A6']
        }, { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: true, position: 'bottom', labels: { boxWidth: 12, font: { size: 9 } } } } });

        // CHARTS: Project Progress (Active/Delayed/Completed)
        var completedProjects = Math.max(0, totalProjects - activeProjects - delayedProjects);
        upsertChart('projectProgressChart', 'bar', ['Active','Delayed','Completed'], {
            label: 'Projects',
            data: [activeProjects, delayedProjects, completedProjects],
            backgroundColor: ['#3B82F6','#F59E0B','#10B981'],
            borderRadius: 8
        }, { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: 'rgba(148,163,184,0.2)' } }, x: { ticks: { font: { size: 11 } } } } });

        // CHARTS: Asset Status Overview
        var assetMap = {};
        assets.forEach(function(a){
            var s = String((a.asset_status || a.status || '')).toLowerCase();
            if (s.indexOf('maint') !== -1) s = 'Under Maintenance';
            else if (s.indexOf('retir') !== -1 || s.indexOf('dispose') !== -1) s = 'Retired';
            else if (s.indexOf('use') !== -1 || s === 'in_use') s = 'In Use';
            else if (s.indexOf('avail') !== -1) s = 'Available';
            else s = s ? s.replace(/\b\w/g, c => c.toUpperCase()) : 'Other';
            assetMap[s] = (assetMap[s] || 0) + 1;
        });
        var assetLabels = Object.keys(assetMap);
        var assetValues = assetLabels.map(function(k){ return assetMap[k]; });
        upsertChart('assetStatusChart', 'doughnut', assetLabels, {
            data: assetValues,
            backgroundColor: ['#06B6D4','#F59E0B','#EF4444','#10B981','#6B7280','#A78BFA']
        }, { responsive: true, maintainAspectRatio: false, cutout: '55%', plugins: { legend: { display: true, position: 'bottom' } } });

        // CHARTS: Document Status
        var docMap = {};
        docs.forEach(function(d){
            var s = String((d.doc_status || d.status || '')).toLowerCase();
            if (s === 'pending_review' || s.indexOf('pending') !== -1) s = 'Pending Review';
            else if (s.indexOf('index') !== -1) s = 'Indexed';
            else if (s.indexOf('arch') !== -1) s = 'Archived';
            else s = s ? s.replace(/\b\w/g, c => c.toUpperCase()) : 'Other';
            docMap[s] = (docMap[s] || 0) + 1;
        });
        var docLabels = Object.keys(docMap);
        var docValues = docLabels.map(function(k){ return docMap[k]; });
        upsertChart('documentStatusChart', 'pie', docLabels, {
            data: docValues,
            backgroundColor: ['#F59E0B','#10B981','#6B7280','#EF4444','#3B82F6','#A78BFA']
        }, { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: true, position: 'bottom', labels: { boxWidth: 12, font: { size: 10 } } } } });

        // CHARTS: Purchase Requisitions by Status
        var prMap = {};
        preqs.forEach(function(p){
            var s = String((p.status || p.requisition_status || '')).trim().toLowerCase();
            if (!s) s = 'other';
            s = s.replace(/_/g,' ');
            prMap[s] = (prMap[s] || 0) + 1;
        });
        var prLabels = Object.keys(prMap).map(function(k){ return k.replace(/\b\w/g, c => c.toUpperCase()); });
        var prValues = prLabels.map(function(lbl){ var k = lbl.toLowerCase(); return prMap[k]; });
        upsertChart('purchaseRequisitionChart', 'bar', prLabels, {
            label: 'Requisitions',
            data: prValues,
            backgroundColor: '#10B981',
            borderRadius: 8
        }, { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { y: { beginAtZero: true, grid: { color: 'rgba(148,163,184,0.2)' } } } });

        var el;
        el = document.getElementById('poTotalValue'); if(el) el.textContent = poTotal.toLocaleString();
        el = document.getElementById('poPendingValue'); if(el) el.textContent = (poPending.toLocaleString()) + ' Pending Approval';
        el = document.getElementById('prTotalValue'); if(el) el.textContent = prTotal.toLocaleString();
        el = document.getElementById('prPendingValue'); if(el) el.textContent = (prPending.toLocaleString()) + ' Pending';
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
