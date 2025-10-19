@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h1 class="text-2xl font-bold mb-4">Dashboard</h1>
    
    <!-- Welcome message with user data -->
    <div class="mb-2 p-2 bg-green-50 rounded-lg border-2 border-green-200 border-dotted hidden">
        <h2 class="text-md font-semibold">Welcome back <span id="user-role" class="font-semibold"></span>, <span id="welcome-user">User</span> !</h2>
    </div>

<!-- stats metrics section -->
 <div class="p-5 bg-green-100 rounded-lg mb-5">
       <div class="flex items-center mb-4 space-x-2 text-gray-700">
            <i class='bx bxs-bar-chart-alt-2' ></i>
            <h2 class="text-lg font-semibold">Metrics Stat</h2>
        </div> 
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5">
        <!-- Row 1: Cards 1-4 -->
        <div class="stat card bg-base-100 shadow-lg border-l-4 border-primary">
            <div class="stat-title font-bold">Module Services</div>
            <div class="stat-value text-primary">6</div>
            <div class="stat-desc font-bold">Active modules</div>
        </div>
        
        <div class="stat card bg-base-100 shadow-lg border-l-4 border-secondary">
            <div class="stat-title font-bold">Total Page Views</div>
            <div class="stat-value text-secondary">89,400</div>
            <div class="stat-desc font-bold">21% more than last month</div>
        </div>
        
        <div class="stat card bg-base-100 shadow-lg border-l-4 border-accent">
            <div class="stat-title font-bold">New Users</div>
            <div class="stat-value text-accent">1,200</div>
            <div class="stat-desc font-bold">15% increase from previous</div>
        </div>
        
        <div class="stat card bg-base-100 shadow-lg border-l-4 border-info">
            <div class="stat-title font-bold">Revenue</div>
            <div class="stat-value text-info">₱45,230</div>
            <div class="stat-desc font-bold">8% growth YTD</div>
        </div>
        
        <!-- Row 2: Cards 5-8 -->
        <div class="stat card bg-base-100 shadow-lg border-l-4 border-success">
            <div class="stat-title font-bold">Active Sessions</div>
            <div class="stat-value text-success">2,500</div>
            <div class="stat-desc font-bold">Up 12% today</div>
        </div>
        
        <div class="stat card bg-base-100 shadow-lg border-l-4 border-warning">
            <div class="stat-title font-bold">Error Rate</div>
            <div class="stat-value text-warning">0.5%</div>
            <div class="stat-desc font-bold">Below target threshold</div>
        </div>
        
        <div class="stat card bg-base-100 shadow-lg border-l-4 border-error">
            <div class="stat-title font-bold">Bounce Rate</div>
            <div class="stat-value text-error">32%</div>
            <div class="stat-desc font-bold">Monitor closely</div>
        </div>
        
        <div class="stat card bg-base-100 shadow-lg border-l-4 border-yellow-700">
            <div class="stat-title font-bold">Conversion Rate</div>
            <div class="stat-value text-yellow-700">4.2%</div>
            <div class="stat-desc font-bold">On track for Q4 goals</div>
        </div>
    </div>
 </div>
 

<!-- announcement board section -->
    <div class="bg-green-100 rounded-lg p-5 mb-5">
        <div class="flex items-center mb-4 space-x-2 text-gray-700">
            <i class='bx bxs-megaphone'></i>
            <h2 class="text-lg font-semibold">Announcement Board</h2>
        </div>
        <div class="space-y-4 bg-gray-50 p-10 rounded-lg shadow">
            <div class="p-4 bg-white rounded-lg shadow-md border-l-4 border-blue-500">
                <h3 class="font-bold text-blue-600">System Maintenance - July 15, 2024</h3>
                <p class="text-gray-700 mt-2">Scheduled maintenance will occur on July 15 from 1:00 AM to 5:00 AM. Please save your work accordingly.</p>
            </div>
            <div class="p-4 bg-white rounded-lg shadow-md border-l-4 border-green-500">
                <h3 class="font-bold text-green-600">New Feature Release: Analytics Dashboard</h3>
                <p class="text-gray-700 mt-2">We are excited to announce the launch of our new Analytics Dashboard, providing deeper insights into your logistics data. Check it out under the Reports section!</p>
            </div>
            <div class="p-4 bg-white rounded-lg shadow-md border-l-4 border-yellow-500">
                <h3 class="font-bold text-yellow-600">Reminder: Update Your Profile Information</h3>
                <p class="text-gray-700 mt-2">Please take a moment to review and update your profile information to ensure accurate records.</p>
            </div>
        </div>
    </div>

<!-- statistics charts section -->
 <div class="bg-green-100 rounded-lg p-5 mb-5">
    <div class="flex items-center mb-4 space-x-2 text-gray-700">
        <i class='bx bx-line-chart' ></i>
        <h2 class="text-lg font-semibold">Statistics Charts</h2>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-2 gap-5">
        <!-- Box 1: Bar Chart (Market Share by Provider) -->
        <div class="p-4 bg-white rounded-lg shadow-md">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Market Share by Vendors</h3>
            <canvas id="barChart" width="400" height="200"></canvas>
        </div>

        <!-- Box 2: Line Chart (Market Growth Projection) -->
        <div class="p-4 bg-white rounded-lg shadow-md">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Vendor Market Growth (2025-2026)</h3>
            <canvas id="lineChart" width="400" height="200"></canvas>
        </div>
        
        <!-- Box 3: Pie Chart (Key Segments) -->
        <div class="p-4 bg-white rounded-lg shadow-md">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Vendors Share</h3>
            <canvas id="pieChart" width="100" height="100"></canvas>
        </div>
        
        <!-- Box 4: Doughnut Chart (PoP Distribution Example) -->
        <div class="p-4 bg-white rounded-lg shadow-md">
            <h3 class="text-lg font-semibold mb-4 text-gray-800">Inventory Items</h3>
            <canvas id="doughnutChart" width="100" height="100"></canvas>
        </div>
    </div>
</div>

<!-- role-based visibility section -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 p-4 rounded-lg bg-green-100" id="role-based-panels">
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

    // Bar Chart: Market Share by Provider
    const barCtx = document.getElementById('barChart').getContext('2d');
    new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: ['market1', 'market2', 'market3'],
            datasets: [{
                label: 'Market Share (%)',
                data: [34, 28, 22],
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56'],
                borderColor: ['#FF6384', '#36A2EB', '#FFCE56'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    max: 40
                }
            }
        }
    });

    // Pie Chart: Key Segments
    const pieCtx = document.getElementById('pieChart').getContext('2d');
    new Chart(pieCtx, {
        type: 'pie',
        data: {
            labels: ['vendor1', 'vendor2'],
            datasets: [{
                data: [42.7, 71.2],  // Note: These are approximate shares; adjust for full 100% if needed
                backgroundColor: ['#FF6384', '#36A2EB']
            }]
        },
        options: {
            responsive: true
        }
    });

    // Line Chart: Market Growth Projection
    const lineCtx = document.getElementById('lineChart').getContext('2d');
    new Chart(lineCtx, {
        type: 'line',
        data: {
            labels: ['2025', '2030', '2035'],
            datasets: [{
                label: 'Vendors Market Value (₱ PHP)',
                data: [24.25, 50, 103.4],
                borderColor: '#36A2EB',
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Doughnut Chart: PoP Count by Top Providers
    const doughnutCtx = document.getElementById('doughnutChart').getContext('2d');
    new Chart(doughnutCtx, {
        type: 'doughnut',
        data: {
            labels: ['Equiptment', 'Document', 'Supplies'],
            datasets: [{
                data: [1400, 2000, 1200],
                backgroundColor: ['#FF6384', '#36A2EB', '#FFCE56']
            }]
        },
        options: {
            responsive: true
        }
    });
</script>
@endsection