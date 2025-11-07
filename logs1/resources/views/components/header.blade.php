<!-- resources/views/components/header.blade.php -->
<nav class="w-full p-3 h-16 bg-[#28644c] text-white shadow-md relative z-40">
    <div class="flex justify-between items-center h-full">
        <div class="flex items-center space-x-4">
            <!-- Sidebar toggle button -->
            <button id="toggle-btn" class="pl-2 focus:outline-none"><i class="bx bx-menu text-2xl cursor-pointer"></i></button>
            <!-- company name & logo button -->
            <button type="button" onclick="window.location.href='/home'" class="flex items-center pl-2 focus:outline-none">
                <h1 class="text-2xl font-bold tracking-tight">Microfinancial</h1>
            </button>
        </div>
        <!-- profile section -->
        <div class="relative dropdown dropdown-end" id="profile-dropdown">
            <button id="profile-btn" type="button" class="flex items-center btn m-1 bg-transparent border-none hover:bg-white/10 focus:outline-none" aria-expanded="false" aria-controls="profile-menu">
                <img src="{{ Auth::guard('sws')->user()->picture ?? asset('images/default.jpg') }}" alt="profile" class="h-10 w-10 rounded-full object-cover" loading="lazy" />
                <div class="text-left ml-2">
                    <div class="text-sm font-medium text-white">{{ Auth::guard('sws')->user()->firstname ?? 'Firstname' }}</div>
                    <div class="text-xs text-white/80 capitalize">{{ Auth::guard('sws')->user()->roles ?? 'Role' }}</div>
                </div>
                <i id="profile-chevron" class='bx bx-chevron-down text-white transition-transform duration-200 ml-2'></i>
            </button>

            <ul id="profile-menu" class="hidden text-gray-700 dropdown-content menu bg-white rounded-box z-50 w-52 p-2 shadow-sm mt-2 right-0">
                <li><a href="#" id="profile-modal-btn" class="flex items-center gap-2 hover:bg-gray-100 rounded-lg"><i class='bx bxs-user'></i>Profile</a></li>
                <li><a href="#" id="logout-btn" class="flex items-center gap-2 text-red-600 hover:text-red-700 hover:bg-red-50 rounded-lg"><i class='bx bxs-log-out'></i>Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Profile Details Modal -->
<div id="profile-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9998] flex items-center justify-center p-4">
    <div class="bg-white rounded-lg w-full max-w-5xl mx-auto">
        <!-- Modal Header - More Compact -->
        <div class="flex bg-green-700 items-center justify-between p-4 border-b border-gray-200">
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                    <i class='bx bxs-user text-green-600 text-lg'></i>
                </div>
                <div>
                    <h3 class="text-base font-semibold text-gray-100">User Profile</h3>
                    <p class="text-xs text-gray-200">View your account details</p>
                </div>
            </div>
            <button id="close-profile-modal" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class='bx bx-x text-lg'></i>
            </button>
        </div>

        <!-- Modal Body - No scrolling needed -->
        <div class="p-4 bg-green-100">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-3">
                
                <!-- Column 1: Profile & Basic Info -->
                <div class="space-y-3">
                    <!-- Profile Picture -->
                    <div class="flex flex-col items-center text-center">
                        <div class="relative mb-2">
                            <img src="{{ Auth::guard('sws')->user()->picture ?? asset('images/default.jpg') }}" 
                                 alt="Profile Picture" 
                                 class="w-20 h-20 rounded-full object-cover border-3 border-green-200 shadow">
                        </div>
                        <h2 class="text-sm font-bold text-gray-800 leading-tight">{{ Auth::guard('sws')->user()->firstname }} {{ Auth::guard('sws')->user()->lastname }}</h2>
                        <p class="text-green-600 text-xs font-medium capitalize">{{ Auth::guard('sws')->user()->roles }}</p>
                    </div>

                    <!-- Account Status -->
                    <div class="space-y-1">
                        <div class="flex justify-evenly items-center text-xs">
                            <span class="text-gray-600">Status:</span>
                            <span class="px-2 py-1 rounded-full text-xs font-medium bg-green-400 text-green-800 capitalize">
                                {{ Auth::guard('sws')->user()->status }}
                            </span> 
                            <span class="text-gray-600">Verified:</span>
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ Auth::guard('sws')->user()->email_verified_at ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800' }}">
                                {{ Auth::guard('sws')->user()->email_verified_at ? 'Yes' : 'No' }}
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Column 2: Personal Information -->
                <div class="space-y-2">
                    <h4 class="font-semibold text-gray-800 text-sm border-b pb-1 flex items-center">
                        <i class='bx bxs-id-card mr-1 text-green-600 text-sm'></i>
                        Personal Info
                    </h4>
                    
                    <div class="space-y-2">
                        <div>
                            <label class="text-xs font-medium text-gray-600 block">Employee ID</label>
                            <p class="text-gray-800 text-sm font-semibold">{{ Auth::guard('sws')->user()->employeeid }}</p>
                        </div>
                        
                        <div>
                            <label class="text-xs font-medium text-gray-600 block">Full Name</label>
                            <p class="text-gray-800 text-xs">{{ Auth::guard('sws')->user()->firstname }} {{ Auth::guard('sws')->user()->middlename }} {{ Auth::guard('sws')->user()->lastname }}</p>
                        </div>
                        
                        <div class="space-y-1">
                            <div class="flex justify-start items-center text-xs gap-2">
                                <label class="text-xs font-medium text-gray-600 block">Sex:</label><p class="text-gray-800 text-xs capitalize">{{ Auth::guard('sws')->user()->sex }}</p>

                                <label class="text-xs font-medium text-gray-600 block">Age:</label><p class="text-gray-800 text-xs">{{ Auth::guard('sws')->user()->age }}</p>
                            </div> 
                        </div>
                    </div>
                </div>

                <!-- Column 3: Contact Information -->
                <div class="space-y-2">
                    <h4 class="font-semibold text-gray-800 text-sm border-b pb-1 flex items-center">
                        <i class='bx bxs-contact mr-1 text-green-600 text-sm'></i>
                        Contact
                    </h4>
                    
                    <div class="space-y-2">
                        <div>
                            <label class="text-xs font-medium text-gray-600 block">Email</label>
                            <p class="text-gray-800 text-xs break-all">{{ Auth::guard('sws')->user()->email }}</p>
                        </div>
                        
                        <div>
                            <label class="text-xs font-medium text-gray-600 block">Phone</label>
                            <p class="text-gray-800 text-xs">{{ Auth::guard('sws')->user()->contactnum }}</p>
                        </div>
                        
                        <div>
                            <label class="text-xs font-medium text-gray-600 block">Birthdate</label>
                            <p class="text-gray-800 text-xs">{{ \Carbon\Carbon::parse(Auth::guard('sws')->user()->birthdate)->format('M d, Y') }}</p>
                        </div>
                    </div>
                </div>

                <!-- Column 4: System & Additional Info -->
                <div class="space-y-2">
                    <h4 class="font-semibold text-gray-800 text-sm border-b pb-1 flex items-center">
                        <i class='bx bxs-cog mr-1 text-green-600 text-sm'></i>
                        System Info
                    </h4>

                    <div class="flex justify-evenly items-center">
                        <div>
                            <label class="text-xs font-medium text-gray-600 block">Member Since</label>
                            <p class="text-gray-800 text-xs">{{ \Carbon\Carbon::parse(Auth::guard('sws')->user()->created_at)->format('M d, Y') }}</p>
                        </div>
                        
                        <div>
                            <label class="text-xs font-medium text-gray-600 block">Last Updated</label>
                            <p class="text-gray-800 text-xs">{{ \Carbon\Carbon::parse(Auth::guard('sws')->user()->updated_at)->format('M d, Y') }}</p>
                        </div>
                    </div>

                    <!-- Address Section -->
                    <div class="pt-1">
                        <h4 class="font-semibold text-gray-800 text-sm border-b pb-1 flex items-center">
                            <i class='bx bxs-map mr-1 text-green-600 text-sm'></i>
                            Address
                        </h4>
                        <p class="text-gray-800 text-xs mt-1">
                            @if(Auth::guard('sws')->user()->address)
                                {{ Auth::guard('sws')->user()->address }}
                            @else
                                <span class="text-gray-500 italic">No address provided</span>
                            @endif
                        </p>
                    </div>
                </div>

            </div>
        </div>

        <!-- Modal Footer - Minimal -->
        <div class="flex justify-end p-3 border-t border-gray-200 bg-green-700 rounded-b-lg">
            <button id="close-profile-modal-bottom" class="btn btn-ghost text-gray-100 hover:bg-gray-100 hover:text-gray-800 btn-sm">
                Close
            </button>
        </div>
    </div>
</div>

<!-- Logout Confirmation Modal -->
<div id="logout-modal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-[9999] flex items-center justify-center">
    <div class="bg-white rounded-lg p-6 w-80 mx-4">
        <div class="flex items-center mb-4">
            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center mr-3">
                <i class='bx bx-log-out text-red-600 text-xl'></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800">Confirm Logout</h3>
        </div>
        
        <p class="text-gray-600 mb-6">Are you sure you want to logout from the system?</p>
        
        <div class="flex justify-end space-x-3">
            <button id="cancel-logout" class="btn btn-ghost text-gray-600 hover:bg-gray-100">Cancel</button>
            <button id="confirm-logout" class="btn btn-error text-white bg-red-600 hover:bg-red-700">
                <span id="logout-text">Yes, Logout</span>
                <span id="logout-spinner" class="loading loading-spinner loading-sm hidden"></span>
            </button>
        </div>
    </div>
</div>

<script>
    // Profile dropdown toggle start
    (function () {
        const btn = document.getElementById('profile-btn');
        const menu = document.getElementById('profile-menu');
        const chevron = document.getElementById('profile-chevron');

        btn.addEventListener('click', (e) => {
            e.preventDefault();
            const isOpen = !menu.classList.contains('hidden');
            if (isOpen) {
                menu.classList.add('hidden');
                chevron.classList.remove('rotate-180');
                btn.setAttribute('aria-expanded', 'false');
            } else {
                menu.classList.remove('hidden');
                chevron.classList.add('rotate-180');
                btn.setAttribute('aria-expanded', 'true');
            }
        });

        // Close when clicking outside
        document.addEventListener('click', (e) => {
            if (!btn.contains(e.target) && !menu.contains(e.target)) {
                if (!menu.classList.contains('hidden')) {
                    menu.classList.add('hidden');
                    chevron.classList.remove('rotate-180');
                    btn.setAttribute('aria-expanded', 'false');
                }
            }
        });
    })();
    // Profile dropdown toggle end

    // Profile Modal functionality
    document.addEventListener('DOMContentLoaded', function() {
        const profileModalBtn = document.getElementById('profile-modal-btn');
        const profileModal = document.getElementById('profile-modal');
        const closeProfileModal = document.getElementById('close-profile-modal');
        const closeProfileModalBottom = document.getElementById('close-profile-modal-bottom');

        // Function to disable sidebar interactions
        function disableSidebar() {
            const sidebar = document.getElementById('sidebar');
            const sidebarLinks = document.querySelectorAll('.sidebar-link, .has-dropdown > div');
            
            if (sidebar) {
                sidebar.style.pointerEvents = 'none';
                sidebar.style.userSelect = 'none';
            }
            
            sidebarLinks.forEach(link => {
                link.style.pointerEvents = 'none';
                link.style.cursor = 'default';
            });
        }

        // Function to enable sidebar interactions
        function enableSidebar() {
            const sidebar = document.getElementById('sidebar');
            const sidebarLinks = document.querySelectorAll('.sidebar-link, .has-dropdown > div');
            
            if (sidebar) {
                sidebar.style.pointerEvents = 'auto';
                sidebar.style.userSelect = 'auto';
            }
            
            sidebarLinks.forEach(link => {
                link.style.pointerEvents = 'auto';
                link.style.cursor = 'pointer';
            });
        }

        // Open profile modal
        profileModalBtn.addEventListener('click', function(e) {
            e.preventDefault();
            profileModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            disableSidebar();
            
            // Close profile dropdown
            document.getElementById('profile-menu').classList.add('hidden');
            document.getElementById('profile-chevron').classList.remove('rotate-180');
            document.getElementById('profile-btn').setAttribute('aria-expanded', 'false');
        });

        // Close profile modal
        function closeProfileModalFunc() {
            profileModal.classList.add('hidden');
            document.body.style.overflow = '';
            enableSidebar();
        }

        closeProfileModal.addEventListener('click', closeProfileModalFunc);
        closeProfileModalBottom.addEventListener('click', closeProfileModalFunc);

        // Close modal when clicking outside
        profileModal.addEventListener('click', function(e) {
            if (e.target === profileModal) {
                closeProfileModalFunc();
            }
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !profileModal.classList.contains('hidden')) {
                closeProfileModalFunc();
            }
        });
    });

    // Logout functionality start
    document.addEventListener('DOMContentLoaded', function() {
        const logoutBtn = document.getElementById('logout-btn');
        const logoutModal = document.getElementById('logout-modal');
        const cancelLogout = document.getElementById('cancel-logout');
        const confirmLogout = document.getElementById('confirm-logout');
        const logoutText = document.getElementById('logout-text');
        const logoutSpinner = document.getElementById('logout-spinner');

        // Function to disable sidebar interactions
        function disableSidebar() {
            const sidebar = document.getElementById('sidebar');
            const sidebarLinks = document.querySelectorAll('.sidebar-link, .has-dropdown > div');
            
            if (sidebar) {
                sidebar.style.pointerEvents = 'none';
                sidebar.style.userSelect = 'none';
            }
            
            sidebarLinks.forEach(link => {
                link.style.pointerEvents = 'none';
                link.style.cursor = 'default';
            });
        }

        // Function to enable sidebar interactions
        function enableSidebar() {
            const sidebar = document.getElementById('sidebar');
            const sidebarLinks = document.querySelectorAll('.sidebar-link, .has-dropdown > div');
            
            if (sidebar) {
                sidebar.style.pointerEvents = 'auto';
                sidebar.style.userSelect = 'auto';
            }
            
            sidebarLinks.forEach(link => {
                link.style.pointerEvents = 'auto';
                link.style.cursor = 'pointer';
            });
        }

        // Open logout confirmation modal
        logoutBtn.addEventListener('click', function(e) {
            e.preventDefault();
            logoutModal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            disableSidebar();
            
            // Close profile dropdown
            document.getElementById('profile-menu').classList.add('hidden');
            document.getElementById('profile-chevron').classList.remove('rotate-180');
            document.getElementById('profile-btn').setAttribute('aria-expanded', 'false');
        });

        // Close modal when cancel is clicked
        cancelLogout.addEventListener('click', function() {
            logoutModal.classList.add('hidden');
            document.body.style.overflow = '';
            enableSidebar();
        });

        // Close modal when clicking outside
        logoutModal.addEventListener('click', function(e) {
            if (e.target === logoutModal) {
                logoutModal.classList.add('hidden');
                document.body.style.overflow = '';
                enableSidebar();
            }
        });

        // Handle logout confirmation
        confirmLogout.addEventListener('click', function() {
            // Show loading state
            logoutText.textContent = 'Logging out...';
            logoutSpinner.classList.remove('hidden');
            confirmLogout.disabled = true;

            // Make logout API call
            fetch('/api/logout', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Logout failed');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Redirect to splash logout page
                    window.location.href = '/splash-logout';
                } else {
                    throw new Error(data.message || 'Logout failed');
                }
            })
            .catch(error => {
                console.error('Logout error:', error);
                // Reset button state
                logoutText.textContent = 'Yes, Logout';
                logoutSpinner.classList.add('hidden');
                confirmLogout.disabled = false;
                
                // Show error message
                Swal.fire({
                    icon: 'error',
                    title: 'Logout Failed',
                    text: 'An error occurred during logout. Please try again.'
                });
            });
        });

        // Close modal with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && !logoutModal.classList.contains('hidden')) {
                logoutModal.classList.add('hidden');
                document.body.style.overflow = '';
                enableSidebar();
            }
        });
    });
    // Logout functionality end
</script>