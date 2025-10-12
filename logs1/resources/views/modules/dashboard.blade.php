@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="bg-white rounded-lg shadow p-6">
    <h1 class="text-2xl font-bold mb-4">Dashboard</h1>
    
    <!-- Welcome message with user data -->
    <div class="mb-6 p-4 bg-green-50 rounded-lg">
        <h2 class="text-xl font-semibold">Welcome back, <span id="welcome-user">User</span>!</h2>
        <p class="text-gray-600">You are successfully logged in to the Microfinancial Logistics System.</p>
        <p class="text-sm text-gray-500 mt-2">Your Role: <span id="user-role" class="font-semibold"></span></p>
    </div>

    <!-- Stats Grid: 4 Columns x 2 Rows (8 Cards Total) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 p-5">
        <!-- Row 1: Cards 1-4 -->
        <div class="stat card bg-base-100 shadow-lg border-l-4 border-green-600">
            <div class="stat-title"><i class='bx-fw bx bx-xs bxs-heart'></i>Ma'am Riche<i class='bx-fw bx bx-xs bxs-heart'></i></div>
            <div class="stat-value text-primary">100000%</div>
            <div class="stat-desc text-success">Ultimate rating achieved</div>
        </div>
        
        <div class="stat card bg-base-100 shadow-lg border-l-4 border-green-600">
            <div class="stat-title">Total Page Views</div>
            <div class="stat-value">89,400</div>
            <div class="stat-desc text-success">21% more than last month</div>
        </div>
        
        <div class="stat card bg-base-100 shadow-lg border-l-4 border-green-600">
            <div class="stat-title">New Users</div>
            <div class="stat-value">1,200</div>
            <div class="stat-desc text-success">15% increase from previous</div>
        </div>
        
        <div class="stat card bg-base-100 shadow-lg border-l-4 border-green-600">
            <div class="stat-title">Revenue</div>
            <div class="stat-value">$45,230</div>
            <div class="stat-desc text-success">8% growth YTD</div>
        </div>
        
        <!-- Row 2: Cards 5-8 -->
        <div class="stat card bg-base-100 shadow-lg border-l-4 border-green-600">
            <div class="stat-title">Active Sessions</div>
            <div class="stat-value">2,500</div>
            <div class="stat-desc text-success">Up 12% today</div>
        </div>
        
        <div class="stat card bg-base-100 shadow-lg border-l-4 border-green-600">
            <div class="stat-title">Error Rate</div>
            <div class="stat-value">0.5%</div>
            <div class="stat-desc text-error">Below target threshold</div>
        </div>
        
        <div class="stat card bg-base-100 shadow-lg border-l-4 border-green-600">
            <div class="stat-title">Bounce Rate</div>
            <div class="stat-value">32%</div>
            <div class="stat-desc text-warning">Monitor closely</div>
        </div>
        
        <div class="stat card bg-base-100 shadow-lg border-l-4 border-green-600">
            <div class="stat-title">Conversion Rate</div>
            <div class="stat-value">4.2%</div>
            <div class="stat-desc text-success">On track for Q4 goals</div>
        </div>
    </div>

    <div class="border-b border-green-900 p-1 mb-5"></div>

    <!-- Role-based visibility -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="role-based-panels">
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
</script>
@endsection