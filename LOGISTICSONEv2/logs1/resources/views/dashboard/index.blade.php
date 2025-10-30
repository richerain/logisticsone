<!-- resources/views/dashboard/index.blade.php -->
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bxs-dashboard'></i>Dashboard</h2>
    </div>
    <div class="text-right">
        <span class="text-md text-gray-600">Welcome back, Firstname - Role</span>
    </div>
</div>

<!-- announcement board section start -->
<div class="bg-green-100 rounded-lg p-5 mb-3 shadow-xl overflow-visible min-h-[200px] flex flex-col">
    <div class="flex items-center mb-4 space-x-2 text-gray-700">
        <h2 class="text-lg font-semibold"><i class='bx bx-fw bxs-megaphone'></i>Announcement Board</h2>
    </div>

    <div class="flex-1 flex items-start">
        <div class="w-full stat card bg-transparent p-4">
            <!-- Announcements grid: show max 3 per page, responsive columns (no overlap when sidebar toggles) -->
            <div id="announcements-grid" class="announcements-grid grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                <!-- Latest (left-most) -->
                <article class="announcement-card bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="{{ asset('images/announcement.png') }}" alt="Announcement image 3" class="h-36 w-full object-cover" loading="lazy" />
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800 truncate">New Warehouse Integration Launched</h3>
                        <p class="text-sm text-gray-600 mt-2 h-14 overflow-hidden">We are excited to announce the rollout of the new warehouse integration that will streamline inventory updates in real-time across all hubs.</p>
                        <div class="mt-3 text-xs text-gray-500">Posted: Oct 28, 2025</div>
                    </div>
                    <span class="absolute top-3 left-3 bg-green-600 text-white text-xs px-2 py-1 rounded">Latest</span>
                </article>

                <!-- Announcement 2 -->
                <article class="announcement-card bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="{{ asset('images/announcement.png') }}" alt="Announcement image 2" class="h-36 w-full object-cover" loading="lazy" />
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800 truncate">Maintenance Window Scheduled</h3>
                        <p class="text-sm text-gray-600 mt-2 h-14 overflow-hidden">Planned maintenance will occur this weekend. Some services may be intermittently unavailable during this period.</p>
                        <div class="mt-3 text-xs text-gray-500">Posted: Oct 20, 2025</div>
                    </div>
                </article>

                <!-- Announcement 1 -->
                <article class="announcement-card bg-white rounded-lg shadow-md overflow-hidden">
                    <img src="{{ asset('images/announcement.png') }}" alt="Announcement image 1" class="h-36 w-full object-cover" loading="lazy" />
                    <div class="p-4">
                        <h3 class="font-semibold text-gray-800 truncate">Quarterly Logistics Review</h3>
                        <p class="text-sm text-gray-600 mt-2 h-14 overflow-hidden">Join the Q3 logistics review to discuss performance metrics, bottlenecks, and improvement plans for the next quarter.</p>
                        <div class="mt-3 text-xs text-gray-500">Posted: Oct 10, 2025</div>
                    </div>
                </article>
            </div>

            <!-- Pagination controls: show 3 per page, can view previous announcements on next pages -->
            <div class="join justify-end mt-4">
                <button class="join-item bg-white btn btn-sm">«</button>
                <button class="join-item bg-white btn btn-sm">Page 1</button>
                <button class="join-item bg-white btn btn-sm">»</button>
            </div>
        </div>
    </div>
</div>
<!-- announcement board section end -->
 
<!-- Statistics Section start -->
<div class="mb-3 bg-green-100 p-5 rounded-lg shadow-xl overflow-visible">
    <div class="flex items-center mb-2 space-x-2 text-gray-700">
        <h2 class="text-lg font-semibold"><i class='bx bx-fw bx-stats'></i>System Overview Metrics</h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
        <!-- Stats 01: Total Purchase Orders -->
        <div class="stat card bg-white shadow-xl hover:shadow-2xl transition-shadow rounded-lg border-l-4 border-t-0 border-r-0 border-b-0 border-blue-700">
            <div class="stat-title flex items-center justify-between">
                <span class="font-semibold text-blue-900">Purchase Orders</span>
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 text-blue-900 shadow-sm">
                    <i class="bx bxs-purchase-tag text-xl" aria-hidden="true"></i>
                </span>
            </div>
            <div class="stat-value text-blue-900">47</div>
            <div class="stat-desc text-blue-700">12 Pending Approval</div>
        </div>

        <!-- Stats 02: Active Vendors -->
        <div class="stat card bg-white shadow-xl hover:shadow-2xl transition-shadow rounded-lg border-l-4 border-t-0 border-r-0 border-b-0 border-green-700">
            <div class="stat-title flex items-center justify-between">
                <span class="font-semibold text-green-900">Active Vendors</span>
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-green-100 text-green-900 shadow-sm">
                    <i class="bx bxs-user-detail text-xl" aria-hidden="true"></i>
                </span>
            </div>
            <div class="stat-value text-green-900">28</div>
            <div class="stat-desc text-green-700">5 New This Month</div>
        </div>

        <!-- Stats 03: Warehouse Inventory -->
        <div class="stat card bg-white shadow-xl hover:shadow-2xl transition-shadow rounded-lg border-l-4 border-t-0 border-r-0 border-b-0 border-purple-700">
            <div class="stat-title flex items-center justify-between">
                <span class="font-semibold text-purple-900">Total Inventory</span>
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-purple-100 text-purple-900 shadow-sm">
                    <i class="bx bxs-archive-in text-xl" aria-hidden="true"></i>
                </span>
            </div>
            <div class="stat-value text-purple-900">1,247</div>
            <div class="stat-desc text-purple-700">45 Low Stock Items</div>
        </div>

        <!-- Stats 04: Active Projects -->
        <div class="stat card bg-white shadow-xl hover:shadow-2xl transition-shadow rounded-lg border-l-4 border-t-0 border-r-0 border-b-0 border-orange-700">
            <div class="stat-title flex items-center justify-between">
                <span class="font-semibold text-orange-900">Active Projects</span>
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-orange-100 text-orange-900 shadow-sm">
                    <i class="bx bxs-package text-xl" aria-hidden="true"></i>
                </span>
            </div>
            <div class="stat-value text-orange-900">15</div>
            <div class="stat-desc text-orange-700">5 Behind Schedule</div>
        </div>

        <!-- Stats 05: Total Assets -->
        <div class="stat card bg-white shadow-xl hover:shadow-2xl transition-shadow rounded-lg border-l-4 border-t-0 border-r-0 border-b-0 border-cyan-700">
            <div class="stat-title flex items-center justify-between">
                <span class="font-semibold text-cyan-900">Managed Assets</span>
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-cyan-100 text-cyan-900 shadow-sm">
                    <i class="bx bxs-archive text-xl" aria-hidden="true"></i>
                </span>
            </div>
            <div class="stat-value text-cyan-900">284</div>
            <div class="stat-desc text-cyan-700">18 Under Maintenance</div>
        </div>

        <!-- Stats 06: Pending Documents -->
        <div class="stat card bg-white shadow-xl hover:shadow-2xl transition-shadow rounded-lg border-l-4 border-t-0 border-r-0 border-b-0 border-red-700">
            <div class="stat-title flex items-center justify-between">
                <span class="font-semibold text-red-900">Pending Docs</span>
                <span class="flex items-center justify-center w-10 h-10 rounded-full bg-red-100 text-red-900 shadow-sm">
                    <i class="bx bxs-file text-xl" aria-hidden="true"></i>
                </span>
            </div>
            <div class="stat-value text-red-900">387</div>
            <div class="stat-desc text-red-700">Require Attention</div>
        </div>
    </div>

    <!-- Quick Module Status Overview -->
    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
        <!-- PSM Status -->
        <div class="bg-white/80 rounded-lg p-3 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-sm font-semibold text-gray-700">PSM</span>
                <span class="flex items-center text-xs px-2 py-1 rounded-full bg-green-100 text-green-800">
                    <i class='bx bxs-circle text-xs mr-1'></i>Active
                </span>
            </div>
            <p class="text-xs text-gray-600 mt-1">Procurement & Sourcing</p>
            <div class="mt-2 flex justify-between text-xs">
                <span>23 Active PO</span>
                <span class="text-orange-600">5 Pending</span>
            </div>
        </div>

        <!-- SWS Status -->
        <div class="bg-white/80 rounded-lg p-3 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-sm font-semibold text-gray-700">SWS</span>
                <span class="flex items-center text-xs px-2 py-1 rounded-full bg-green-100 text-green-800">
                    <i class='bx bxs-circle text-xs mr-1'></i>Active
                </span>
            </div>
            <p class="text-xs text-gray-600 mt-1">Smart Warehousing</p>
            <div class="mt-2 flex justify-between text-xs">
                <span>1.2K Items</span>
                <span class="text-red-600">45 Low</span>
            </div>
        </div>

        <!-- PLT Status -->
        <div class="bg-white/80 rounded-lg p-3 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-sm font-semibold text-gray-700">PLT</span>
                <span class="flex items-center text-xs px-2 py-1 rounded-full bg-green-100 text-green-800">
                    <i class='bx bxs-circle text-xs mr-1'></i>Active
                </span>
            </div>
            <p class="text-xs text-gray-600 mt-1">Project Logistics</p>
            <div class="mt-2 flex justify-between text-xs">
                <span>15 Projects</span>
                <span class="text-yellow-600">5 Ongoing</span>
            </div>
        </div>

        <!-- ALMS Status -->
        <div class="bg-white/80 rounded-lg p-3 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-sm font-semibold text-gray-700">ALMS</span>
                <span class="flex items-center text-xs px-2 py-1 rounded-full bg-green-100 text-green-800">
                    <i class='bx bxs-circle text-xs mr-1'></i>Active
                </span>
            </div>
            <p class="text-xs text-gray-600 mt-1">Asset Management</p>
            <div class="mt-2 flex justify-between text-xs">
                <span>284 Assets</span>
                <span class="text-blue-600">18 Maintenance</span>
            </div>
        </div>

        <!-- DTLR Status -->
        <div class="bg-white/80 rounded-lg p-3 shadow-sm">
            <div class="flex items-center justify-between">
                <span class="text-sm font-semibold text-gray-700">DTLR</span>
                <span class="flex items-center text-xs px-2py-1 rounded-full bg-green-100 text-green-800">
                    <i class='bx bxs-circle text-xs mr-1'></i>Active
                </span>
            </div>
            <p class="text-xs text-gray-600 mt-1">Document Tracking</p>
            <div class="mt-2 flex justify-between text-xs">
                <span>1.8K Docs</span>
                <span class="text-red-600">387 Pending</span>
            </div>
        </div>
    </div>
</div>
<!-- Statistics Section end -->

<!-- statistics charts section start -->
<div class="bg-green-100 rounded-lg p-5 shadow-xl overflow-visible mb-3">
    <h2 class="text-gray-700 text-lg font-semibold mb-4"><i class='bx bx-fw bxs-pie-chart-alt-2'></i>Module Performance Charts</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <!-- Chart 1: Purchase Order Status (PSM) -->
        <div class="chart-card bg-gray-50 p-4 rounded-lg shadow">
            <h3 class="font-bold text-gray-800 flex items-center">
                <i class='bx bxs-purchase-tag mr-2 text-blue-600'></i>
                PO Status Distribution
            </h3>
            <div class="chart-placeholder h-32 bg-gray-200 rounded-lg p-1">
                <canvas id="poStatusChart" style="width:100%;height:160px;"></canvas>
            </div>
        </div>

        <!-- Chart 2: Inventory Categories (SWS) -->
        <div class="chart-card bg-gray-50 p-4 rounded-lg shadow">
            <h3 class="font-bold text-gray-800 flex items-center">
                <i class='bx bxs-archive-in mr-2 text-purple-600'></i>
                Inventory by Category
            </h3>
            <div class="chart-placeholder h-32 bg-gray-200 rounded-lg p-1">
                <canvas id="inventoryChart" style="width:100%;height:160px;"></canvas>
            </div>
        </div>

        <!-- Chart 3: Project Progress (PLT) -->
        <div class="chart-card bg-gray-50 p-4 rounded-lg shadow">
            <h3 class="font-bold text-gray-800 flex items-center">
                <i class='bx bxs-package mr-2 text-orange-600'></i>
                Project Progress
            </h3>
            <div class="chart-placeholder h-32 bg-gray-200 rounded-lg p-1">
                <canvas id="projectProgressChart" style="width:100%;height:160px;"></canvas>
            </div>
        </div>

        <!-- Chart 4: Asset Status (ALMS) -->
        <div class="chart-card bg-gray-50 p-4 rounded-lg shadow">
            <h3 class="font-bold text-gray-800 flex items-center">
                <i class='bx bxs-archive mr-2 text-cyan-600'></i>
                Asset Status Overview
            </h3>
            <div class="chart-placeholder h-32 bg-gray-200 rounded-lg p-1">
                <canvas id="assetStatusChart" style="width:100%;height:160px;"></canvas>
            </div>
        </div>

        <!-- Chart 5: Document Status (DTLR) -->
        <div class="chart-card bg-gray-50 p-4 rounded-lg shadow">
            <h3 class="font-bold text-gray-800 flex items-center">
                <i class='bx bxs-file mr-2 text-red-600'></i>
                Document Status
            </h3>
            <div class="chart-placeholder h-32 bg-gray-200 rounded-lg p-1">
                <canvas id="documentStatusChart" style="width:100%;height:160px;"></canvas>
            </div>
        </div>

        <!-- Chart 6: Vendor Performance (PSM) -->
        <div class="chart-card bg-gray-50 p-4 rounded-lg shadow">
            <h3 class="font-bold text-gray-800 flex items-center">
                <i class='bx bxs-user-detail mr-2 text-green-600'></i>
                Vendor Performance
            </h3>
            <div class="chart-placeholder h-32 bg-gray-200 rounded-lg p-1">
                <canvas id="vendorPerformanceChart" style="width:100%;height:160px;"></canvas>
            </div>
        </div>
    </div>
</div>
<!-- statistics charts section end -->

<script>
// Module Charts Data Initialization start
document.addEventListener('DOMContentLoaded', function () {
    initializeModuleCharts();
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
</script>