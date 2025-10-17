<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Microfinancial Logistics - @yield('title', 'Dashboard')</title>
    <link rel="icon" type="image/png" href="{{ asset('images/micrologo.png') }}" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://cdn.jsdelivr.net/npm/chart.js@latest/dist/chart.umd.js"></script>
</head>
<body class="bg-gray-100 font-sans">
    <x-header />
    <x-sidebar />
    <main id="main-content" class="p-6 transition-all duration-500 ease-in-out min-h-screen mt-16 ml-64">
        @yield('content')
    </main>
    <x-footer />
    
<script>
// Global role-based visibility functions
window.roleBasedVisibility = {
    // Check if user has access to a specific role or set of roles
    hasAccess: function(allowedRoles) {
        const user = JSON.parse(localStorage.getItem('user') || '{}');
        const userRole = user.roles ? user.roles.toLowerCase() : '';
        
        if (typeof allowedRoles === 'string') {
            return allowedRoles.toLowerCase().split(',').includes(userRole);
        } else if (Array.isArray(allowedRoles)) {
            return allowedRoles.map(role => role.toLowerCase()).includes(userRole);
        }
        
        return false;
    },
    
    // Show/hide elements based on role
    showForRoles: function(element, allowedRoles) {
        if (this.hasAccess(allowedRoles)) {
            element.classList.remove('hidden');
        } else {
            element.classList.add('hidden');
        }
    },
    
    // Get current user role
    getCurrentRole: function() {
        const user = JSON.parse(localStorage.getItem('user') || '{}');
        return user.roles ? user.roles.toLowerCase() : '';
    },
    
    // Get current user data
    getCurrentUser: function() {
        return JSON.parse(localStorage.getItem('user') || '{}');
    }
};

// Session timeout monitoring DISABLED
// No automatic session checks or timeouts

console.log('🛡️ Session timeout features disabled - sessions will not expire automatically');

// Simple function to check if user is authenticated (for other components)
window.isUserAuthenticated = function() {
    const user = JSON.parse(localStorage.getItem('user') || '{}');
    const isAuth = localStorage.getItem('isAuthenticated') === 'true';
    return isAuth && user.id;
};

// Optional: Keep session API endpoints available for other components that might call them
window.sessionManager = {
    manualSessionCheck: async function() {
        console.log('Session check called - timeout disabled');
        return { session_valid: true };
    },
    getSessionInfo: async function() {
        return { session_active: true, minutes_remaining: 999 };
    }
};
</script>
</body>
</html>