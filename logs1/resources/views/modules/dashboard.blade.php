@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h1 class="text-2xl font-bold mb-4"><i class='bx bx-fw bxs-dashboard'></i>Dashboard</h1>
    
    <!-- Welcome message with user data -->
    <div class="mb-2 p-2 bg-green-50 rounded-lg border-2 border-green-200 border-dotted hidden">
        <h2 class="text-md font-semibold">Welcome back <span id="user-role" class="font-semibold"></span>, <span id="welcome-user">User</span> !</h2>
    </div>

    <!-- Statistics Section start -->
    <div class="mb-3 bg-green-100 p-5 rounded-lg">
        <div class="flex items-center mb-2 space-x-2 text-gray-700">
            <h2 class="text-lg font-semibold"><i class='bx bx-fw bx-stats'></i>Metric Stats</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
            <!-- Stats 01: Total Tasks -->
            <div class="stat card bg-white shadow-xl hover:shadow-2xl transition-shadow rounded-lg border-l-4 border-t-0 border-r-0 border-b-0 border-green-700">
                <div class="stat-title flex items-center justify-between">
                    <span class="font-semibold text-green-900">Total Tasks</span>
                    <span class="flex items-center justify-center w-10 h-10 rounded-full bg-green-100 text-green-900 shadow-sm">
                        <i class="bx bx-task text-xl" aria-hidden="true"></i>
                    </span>
                </div>
                <div class="stat-value text-green-900" id="total-tasks">0</div>
            </div>

            <!-- Stats 02: Pending Items -->
            <div class="stat card bg-white shadow-xl hover:shadow-2xl transition-shadow rounded-lg border-l-4 border-t-0 border-r-0 border-b-0 border-yellow-700">
                <div class="stat-title flex items-center justify-between">
                    <span class="font-semibold text-yellow-900">Pending Items</span>
                    <span class="flex items-center justify-center w-10 h-10 rounded-full bg-yellow-100 text-yellow-900 shadow-sm">
                        <i class="bx bx-time text-xl" aria-hidden="true"></i>
                    </span>
                </div>
                <div class="stat-value text-yellow-900" id="pending-items">0</div>
            </div>

            <!-- Stats 03: Completed Items -->
            <div class="stat card bg-white shadow-xl hover:shadow-2xl transition-shadow rounded-lg border-l-4 border-t-0 border-r-0 border-b-0 border-blue-700">
                <div class="stat-title flex items-center justify-between">
                    <span class="font-semibold text-blue-900">Completed Items</span>
                    <span class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 text-blue-900 shadow-sm">
                        <i class="bx bx-check text-xl" aria-hidden="true"></i>
                    </span>
                </div>
                <div class="stat-value text-blue-900" id="completed-items">0</div>
            </div>

            <!-- Stats 04: Overdue Items (distinct from first three) -->
            <div class="stat card bg-white shadow-xl hover:shadow-2xl transition-shadow rounded-lg border-l-4 border-t-0 border-r-0 border-b-0 border-red-700">
                <div class="stat-title flex items-center justify-between">
                    <span class="font-semibold text-red-900">Overdue Items</span>
                    <span class="flex items-center justify-center w-10 h-10 rounded-full bg-red-100 text-red-900 shadow-sm">
                        <i class="bx bx-bell text-xl" aria-hidden="true"></i>
                    </span>
                </div>
                <div class="stat-value text-red-900" id="overdue-items">0</div>
            </div>

            <!-- Stats 05: In Progress (distinct) -->
            <div class="stat card bg-white shadow-xl hover:shadow-2xl transition-shadow rounded-lg border-l-4 border-t-0 border-r-0 border-b-0 border-indigo-700">
                <div class="stat-title flex items-center justify-between">
                    <span class="font-semibold text-indigo-900">In Progress</span>
                    <span class="flex items-center justify-center w-10 h-10 rounded-full bg-indigo-100 text-indigo-900 shadow-sm">
                        <i class="bx bx-loader-alt text-xl" aria-hidden="true"></i>
                    </span>
                </div>
                <div class="stat-value text-indigo-900" id="inprogress-items">0</div>
            </div>

            <!-- Stats 06: On Hold (distinct) -->
            <div class="stat card bg-white shadow-xl hover:shadow-2xl transition-shadow rounded-lg border-l-4 border-t-0 border-r-0 border-b-0 border-purple-700">
                <div class="stat-title flex items-center justify-between">
                    <span class="font-semibold text-purple-900">On Hold</span>
                    <span class="flex items-center justify-center w-10 h-10 rounded-full bg-purple-100 text-purple-900 shadow-sm">
                        <i class="bx bx-pause text-xl" aria-hidden="true"></i>
                    </span>
                </div>
                <div class="stat-value text-purple-900" id="onhold-items">0</div>
            </div>
        </div>
    </div>
    <!-- Statistics Section end -->

<!-- Announcement Board Section -->
<div class="bg-green-100 rounded-lg p-5 mb-5 min-h-[200px] flex flex-col">
  <div class="flex items-center mb-4 space-x-2 text-gray-700">
    <h2 class="text-lg font-semibold"><i class='bx bx-fw bxs-megaphone'></i>Announcement Board</h2>
  </div>
  <div class="flex-1 flex items-center justify-center">
    <div class="stat card bg-gray-50 shadow-lg border-4 border-gray-200">
        <div class="flex stat-title items-center justify-center italic pt-40 pb-40">No Announcement yet...</div>
    </div>
  </div>
</div>

<!-- Statistics Charts Section start -->
<div class="bg-green-100 rounded-lg p-5 shadow-lg mb-5">
    <h2 class="text-gray-700 text-lg font-semibold mb-4"><i class='bx bx-fw bxs-pie-chart-alt-2' ></i>Statistics Charts</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
        <div class="chart-card bg-gray-50 p-4 rounded-lg shadow">
            <h3 class="font-bold">Bar Chart</h3>
            <div class="chart-placeholder h-32 bg-gray-200 rounded-lg p-1">
                <canvas id="chart1" style="width:100%;height:160px;"></canvas>
            </div>
        </div>
        <div class="chart-card bg-gray-50 p-4 rounded-lg shadow">
            <h3 class="font-bold">Doughnut Chart</h3>
            <div class="chart-placeholder h-32 bg-gray-200 rounded-lg p-1">
                <canvas id="chart2" style="width:100%;height:160px;"></canvas>
            </div>
        </div>
        <div class="chart-card bg-gray-50 p-4 rounded-lg shadow">
            <h3 class="font-bold">Line Chart</h3>
            <div class="chart-placeholder h-32 bg-gray-200 rounded-lg p-1">
                <canvas id="chart3" style="width:100%;height:160px;"></canvas>
            </div>
        </div>
    </div>
</div>
<!-- Statistics Charts Section end -->

<!-- role-based visibility section -->
    <div class="hidden grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-4 rounded-lg bg-green-100" id="role-based-panels">
        <!-- Super Admin Panel -->
        <div class="p-4 bg-green-900 text-white rounded-lg hidden" data-roles="superadmin">
            <h3 class="font-bold">Super Admin Panel</h3>
            <p class="text-sm mt-2">Full system access and administration privileges</p>
            <ul class="text-xs mt-2 list-disc list-inside">
                <li>User Management</li>
                <li>System Configuration</li>
                <li>All Module Access</li>
            </ul>
        </div>
        
        <!-- Admin Panel -->
        <div class="p-4 bg-green-800 text-white rounded-lg hidden" data-roles="superadmin,admin">
            <h3 class="font-bold">Admin Panel</h3>
            <p class="text-sm mt-2">Administrative functions and user management</p>
            <ul class="text-xs mt-2 list-disc list-inside">
                <li>User Management</li>
                <li>Content Management</li>
                <li>Reports & Analytics</li>
            </ul>
        </div>
        
        <!-- Manager Panel -->
        <div class="p-4 bg-green-700 text-white rounded-lg hidden" data-roles="superadmin,admin,manager">
            <h3 class="font-bold">Manager Panel</h3>
            <p class="text-sm mt-2">Team management and operational oversight</p>
            <ul class="text-xs mt-2 list-disc list-inside">
                <li>Team Management</li>
                <li>Performance Tracking</li>
                <li>Operational Reports</li>
            </ul>
        </div>
        
        <!-- Staff Panel -->
        <div class="p-4 bg-green-600 text-white rounded-lg hidden" data-roles="superadmin,admin,manager,staff">
            <h3 class="font-bold">Staff Panel</h3>
            <p class="text-sm mt-2">Daily operations and task management</p>
            <ul class="text-xs mt-2 list-disc list-inside">
                <li>Task Management</li>
                <li>Data Entry</li>
                <li>Basic Reports</li>
            </ul>
        </div>
        
        <!-- Vendor Panel -->
        <div class="p-4 bg-green-500 text-white rounded-lg hidden" data-roles="superadmin,vendor">
            <h3 class="font-bold">Vendor Panel</h3>
            <p class="text-sm mt-2">Vendor-specific functions and order management</p>
            <ul class="text-xs mt-2 list-disc list-inside">
                <li>Order Management</li>
                <li>Inventory Updates</li>
                <li>Vendor Reports</li>
            </ul>
        </div>
    </div>

</div>

<script>
    // Role-based visibility function
    function showPanelsBasedOnRole(userRole) {
        const panels = document.querySelectorAll('[data-roles]');
        
        panels.forEach(panel => {
            const allowedRoles = panel.getAttribute('data-roles').split(',');
            
            if (allowedRoles.includes(userRole.toLowerCase())) {
                panel.classList.remove('hidden');
            } else {
                panel.classList.add('hidden');
            }
        });
    }

    // Update dashboard content based on role
    function updateDashboardContent(userRole) {
        // Show/hide panels based on role
        showPanelsBasedOnRole(userRole);
        
        // Update stats based on role (you can customize this)
        const stats = getRoleBasedStats(userRole);
        document.getElementById('total-tasks').textContent = stats.totalTasks;
        document.getElementById('pending-items').textContent = stats.pendingItems;
        document.getElementById('completed-items').textContent = stats.completedItems;
    }

    // Get role-based statistics (mock data - replace with actual API calls)
    function getRoleBasedStats(userRole) {
        const stats = {
            superadmin: { totalTasks: 25, pendingItems: 8, completedItems: 17 },
            admin: { totalTasks: 18, pendingItems: 5, completedItems: 13 },
            manager: { totalTasks: 15, pendingItems: 4, completedItems: 11 },
            staff: { totalTasks: 12, pendingItems: 3, completedItems: 9 },
            vendor: { totalTasks: 8, pendingItems: 2, completedItems: 6 }
        };
        
        return stats[userRole.toLowerCase()] || { totalTasks: 0, pendingItems: 0, completedItems: 0 };
    }

    // Update welcome message with user name and role
    document.addEventListener('DOMContentLoaded', function() {
        const user = JSON.parse(localStorage.getItem('user') || '{}');
        const welcomeUser = document.getElementById('welcome-user');
        const userRole = document.getElementById('user-role');
        
        if (welcomeUser && user.firstname) {
            welcomeUser.textContent = user.firstname;
        }
        
        if (userRole && user.roles) {
            userRole.textContent = user.roles.charAt(0).toUpperCase() + user.roles.slice(1);
            
            // Update dashboard content based on role
            updateDashboardContent(user.roles);
        }
    });

    document.addEventListener('DOMContentLoaded', function () {
        // Bar (static)
        const ctx1 = document.getElementById('chart1').getContext('2d');
        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['Market A', 'Market B', 'Market C'],
                datasets: [{
                    label: 'Market Share',
                    data: [34, 28, 22],
                    backgroundColor: ['#36A2EB', '#FF6384', '#FFCE56'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, max: 40 } }
            }
        });

        // Doughnut (static)
        const ctx2 = document.getElementById('chart2').getContext('2d');
        new Chart(ctx2, {
            type: 'doughnut',
            data: {
                labels: ['Equipment', 'Document', 'Supplies'],
                datasets: [{
                    data: [1400, 2000, 1200],
                    backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { position: 'bottom' } }
            }
        });

        // Line (static)
        const ctx3 = document.getElementById('chart3').getContext('2d');
        new Chart(ctx3, {
            type: 'line',
            data: {
                labels: ['2025', '2030', '2035'],
                datasets: [{
                    label: 'Vendors Market Value (₱)',
                    data: [24.25, 50, 103.4],
                    borderColor: '#36A2EB',
                    backgroundColor: 'rgba(54,162,235,0.15)',
                    tension: 0.2,
                    fill: true,
                    pointRadius: 3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true } }
            }
        });
    });
</script>
@endsection