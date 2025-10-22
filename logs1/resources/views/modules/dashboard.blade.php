@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h1 class="text-2xl font-bold mb-4">Dashboard</h1>
    
    <!-- Welcome message with user data -->
    <div class="mb-2 p-2 bg-green-50 rounded-lg border-2 border-green-200 border-dotted hidden">
        <h2 class="text-md font-semibold">Welcome back <span id="user-role" class="font-semibold"></span>, <span id="welcome-user">User</span> !</h2>
    </div>

<!-- Announcement Board Section -->
<div class="bg-green-100 rounded-lg p-5 mb-5 min-h-[200px] flex flex-col">
  <div class="flex items-center mb-4 space-x-2 text-gray-700">
    <i class='bx bxs-megaphone'></i>
    <h2 class="text-lg font-semibold">Announcement Board</h2>
  </div>
  <div class="flex-1 flex items-center justify-center">
    <div class="stat card bg-gray-50 shadow-lg border-4 border-gray-200">
        <div class="flex stat-title items-center justify-center italic pt-40 pb-40">No Announcement yet...</div>
    </div>
  </div>
</div>

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