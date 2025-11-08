<!-- resources/views/components/sidebar.blade.php -->
<aside id="sidebar" class="bg-[#2f855A] text-white flex flex-col z-50 absolute md:relative w-72 -ml-72 md:ml-0 transition-all duration-300 ease-in-out h-full overflow-hidden">
    <div class="department-header text-center py-5 mx-2 border-b border-white/50 shrink-0">
        <h1 class="text-xl font-bold">Logistics I</h1>
    </div>
    <div class="flex-1 overflow-y-auto sidebar-scrollbar px-3 py-5">
        <ul class="space-y-1">
            <!-- dashboard sidebar btn start -->
            @if(in_array(auth()->guard('sws')->user()->roles, ['vendor', 'staff', 'manager', 'admin', 'superadmin']))
            <li>
                <a href="#" data-module="dashboard" class="sidebar-link flex items-center font-medium text-md hover:bg-white/30 px-3 py-2.5 rounded-lg whitespace-normal wrap-break-word">
                    <i class="bx bxs-dashboard mr-2 shrink-0"></i>
                    <span class="flex-1">Dashboard</span>
                </a>
            </li>
            @endif
            <!-- dashboard sidebar btn end -->
            
            <!-- Procurement & Sourcing Management btn start -->
            @if(in_array(auth()->guard('sws')->user()->roles, ['staff', 'manager', 'admin', 'superadmin']))
            <li class="has-dropdown">
                <div class="flex items-center font-medium justify-between text-sm hover:bg-white/30 px-3 py-2.5 rounded-lg whitespace-normal wrap-break-words cursor-pointer">
                    <div class="flex items-center flex-1 min-w-0">
                        <i class="bx bxs-cart mr-2 shrink-0"></i>
                        <span class="module-text flex-1">Procurement & Sourcing Management</span>
                    </div>
                    <i class="bx bx-chevron-down text-2xl transition-transform duration-300 shrink-0 ml-2"></i>
                </div>
                <ul class="dropdown-menu hidden bg-white/20 mt-2 rounded-lg px-2 py-2">
                    <li><a href="#" data-module="psm-purchase" class="sidebar-link flex items-center px-3 py-2 text-sm hover:bg-white/30 rounded-lg whitespace-normal wrap-break-words"><span class="module-text"><i class='bx bx-fw bxs-purchase-tag' ></i>Purchase Management</span></a></li>
                    <li><a href="#" data-module="psm-vendor-management" class="sidebar-link flex items-center px-3 py-2 text-sm hover:bg-white/30 rounded-lg whitespace-normal wrap-break-words"><span class="module-text"><i class='bx bx-fw bxs-user-detail' ></i>Vendor Management</span></a></li>
                </ul>
            </li>
            @endif
            <!-- Procurement & Sourcing Management btn end -->
            
            <!-- Smart Warehousing System btn start -->
            @if(in_array(auth()->guard('sws')->user()->roles, ['staff', 'manager', 'admin', 'superadmin']))
            <li class="has-dropdown">
                <div class="flex items-center font-medium justify-between text-sm hover:bg-white/30 px-3 py-2.5 rounded-lg whitespace-normal wrap-break-words cursor-pointer">
                    <div class="flex items-center flex-1 min-w-0">
                        <i class="bx bxs-store mr-2 shrink-0"></i>
                        <span class="flex-1">Smart Warehousing System</span>
                    </div>
                    <i class="bx bx-chevron-down text-2xl transition-transform duration-300 shrink-0 ml-2"></i>
                </div>
                <ul class="dropdown-menu hidden bg-white/20 mt-2 rounded-lg px-2 py-2">
                    <li><a href="#" data-module="sws-inventory-flow" class="sidebar-link flex items-center px-3 py-2 text-sm hover:bg-white/30 rounded-lg whitespace-normal wrap-break-words"><span class="module-text"><i class='bx bx-fw bx-sync'></i>Inventory Flow</span></a></li>
                    <li><a href="#" data-module="sws-digital-inventory" class="sidebar-link flex items-center px-3 py-2 text-sm hover:bg-white/30 rounded-lg whitespace-normal wrap-break-words"><span class="module-text"><i class='bx bx-fw bxs-archive-in'></i>Digital Inventory</span></a></li>
                </ul>
            </li>
            @endif
            <!-- Smart Warehousing System btn end -->
            
            <!-- Project Logistics Tracker btn start -->
            @if(in_array(auth()->guard('sws')->user()->roles, ['staff', 'manager', 'admin', 'superadmin']))
            <li class="has-dropdown">
                <div class="flex items-center font-medium justify-between text-sm hover:bg-white/30 px-3 py-2.5 rounded-lg whitespace-normal wrap-break-words cursor-pointer">
                    <div class="flex items-center flex-1 min-w-0">
                        <i class="bx bxs-truck mr-2 shrink-0"></i>
                        <span class="flex-1">Project Logistics Tracker</span>
                    </div>
                    <i class="bx bx-chevron-down text-2xl transition-transform duration-300 shrink-0 ml-2"></i>
                </div>
                <ul class="dropdown-menu hidden bg-white/20 mt-2 rounded-lg px-2 py-2 space-y-2">
                    <li><a href="#" data-module="plt-logistics-projects" class="sidebar-link flex items-center px-3 py-2 text-sm hover:bg-white/30 rounded-lg whitespace-normal wrap-break-words"><i class='bx bx-fw bxs-package' ></i>Logistics Projects</a></li>
                </ul>
            </li>
            @endif
            <!-- Project Logistics Tracker btn end -->
            
            <!-- Asset Lifecycle & Maintenance btn start -->
            @if(in_array(auth()->guard('sws')->user()->roles, ['staff', 'manager', 'admin', 'superadmin']))
            <li class="has-dropdown">
                <div class="flex items-center font-medium justify-between text-sm hover:bg-white/30 px-3 py-2.5 rounded-lg whitespace-normal wrap-break-words cursor-pointer">
                    <div class="flex items-center flex-1 min-w-0">
                        <i class="bx bxs-hard-hat mr-2 shrink-0"></i>
                        <span class="flex-1">Asset Lifecycle & Maintenance</span>
                    </div>
                    <i class="bx bx-chevron-down text-2xl transition-transform duration-300 shrink-0 ml-2"></i>
                </div>
                <ul class="dropdown-menu hidden bg-white/20 mt-2 rounded-lg px-2 py-2">
                    <li><a href="#" data-module="alms-asset-management" class="sidebar-link flex items-center px-3 py-2 text-sm hover:bg-white/30 rounded-lg whitespace-normal wrap-break-words"><i class='bx bx-fw bxs-archive'></i>Asset Management</a></li>
                    <li><a href="#" data-module="alms-maintenance-management" class="sidebar-link flex items-center px-3 py-2 text-sm hover:bg-white/30 rounded-lg whitespace-normal wrap-break-words"><i class='bx bx-fw bxs-wrench'></i>Maintenance Management</a></li>
                </ul>
            </li>
            @endif
            <!-- Asset Lifecycle & Maintenance btn end -->
            
            <!-- Document Tracking & Logistics Record btn start -->
            @if(in_array(auth()->guard('sws')->user()->roles, ['staff', 'manager', 'admin', 'superadmin']))
            <li class="has-dropdown">
                <div class="flex items-center font-medium justify-between text-sm hover:bg-white/30 px-3 py-2.5 rounded-lg whitespace-normal wrap-break-words cursor-pointer">
                    <div class="flex items-center flex-1 min-w-0">
                        <i class="bx bxs-map mr-2 shrink-0"></i>
                        <span class="flex-1">Document Tracking & Logistics Record</span>
                    </div>
                    <i class="bx bx-chevron-down text-2xl transition-transform duration-300 shrink-0 ml-2"></i>
                </div>
                <ul class="dropdown-menu hidden bg-white/20 mt-2 rounded-lg px-2 py-2">
                    <li><a href="#" data-module="dtlr-document-tracker" class="sidebar-link flex items-center px-3 py-2 text-sm hover:bg-white/30 rounded-lg whitespace-normal wrap-break-words"><i class='bx bx-fw bxs-file'></i>Document Tracker</a></li>
                    <li><a href="#" data-module="dtlr-logistics-record" class="sidebar-link flex items-center px-3 py-2 text-sm hover:bg-white/30 rounded-lg whitespace-normal wrap-break-words"><i class='bx bx-fw bxs-package'></i>Logistics Record</a></li>
                </ul>
            </li>
            @endif
            <!-- Document Tracking & Logistics Record btn end -->
            
            <!-- Vendor module btn start -->
            @if(auth()->guard('sws')->user()->roles === 'vendor')
            <li class="has-dropdown">
                <div class="flex items-center font-medium justify-between text-sm hover:bg-white/30 px-3 py-2.5 rounded-lg whitespace-normal wrap-break-words cursor-pointer">
                    <div class="flex items-center flex-1 min-w-0">
                        <i class="bx bxs-user-detail mr-2 shrink-0"></i>
                        <span class="flex-1">Vendor Module</span>
                    </div>
                    <i class="bx bx-chevron-down text-2xl transition-transform duration-300 shrink-0 ml-2"></i>
                </div>
                <ul class="dropdown-menu hidden bg-white/20 mt-2 rounded-lg px-2 py-2">
                    <li><a href="#" data-module="psm-vendor-quote" class="sidebar-link flex items-center px-3 py-2 text-sm hover:bg-white/30 rounded-lg whitespace-normal wrap-break-words"><span class="module-text"><i class='bx bx-fw bxs-quote-left' ></i>Vendor Quote</span></a></li>
                    <li><a href="#" data-module="psm-product-management" class="sidebar-link flex items-center px-3 py-2 text-sm hover:bg-white/30 rounded-lg whitespace-normal wrap-break-words"><span class="module-text"><i class='bx bx-fw bxs-package' ></i>Product Management</span></a></li>
                    <li><a href="#" data-module="psm-vendor-info" class="sidebar-link flex items-center px-3 py-2 text-sm hover:bg-white/30 rounded-lg whitespace-normal wrap-break-words"><span class="module-text"><i class='bx bx-fw bxs-user-account' ></i>Vendor Info</span></a></li>
                </ul>
            </li>
            @endif
            <!-- Vendor module btn end -->
        </ul>
        <div class="mt-1 flex justify-center space-x-1 opacity-10">
            <img src="{{ asset('images/micrologo.png') }}" alt="Micro logo" class="h-32 w-32 rounded-full object-cover" loading="lazy" />
        </div>
    </div>
</aside>

<script>
    // Enhanced module loading functionality
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarLinks = document.querySelectorAll('.sidebar-link');
        const moduleContent = document.getElementById('module-content');
        
        // Function to load module content
        function loadModuleContent(module, pushState = true) {
            // Show loading state
            moduleContent.innerHTML = '<div class="flex justify-center items-center h-64"><div class="loading loading-spinner loading-lg"></div></div>';

            // Send AJAX request to load module content
            fetch(`/module/${module}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            })
            .then(response => {
                if (!response.ok) {
                    if (response.status === 403) {
                        throw new Error('Access denied');
                    }
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(html => {
                moduleContent.innerHTML = html;
                
                // Update browser history if needed
                if (pushState) {
                    history.pushState({ module: module }, '', `/module/${module}`);
                }
                
                // Set active sidebar link
                setActiveSidebarLink(module);
                
                // Re-initialize any charts or dynamic content
                if (typeof initializeCharts === 'function') {
                    initializeCharts();
                }
            })
            .catch(error => {
                console.error('Error loading module:', error);
                if (error.message === 'Access denied') {
                    moduleContent.innerHTML = `
                        <div class="alert alert-error">
                            <div class="flex-1">
                                <label>Access denied. You don't have permission to access this module.</label>
                            </div>
                        </div>
                    `;
                } else {
                    moduleContent.innerHTML = `
                        <div class="alert alert-error">
                            <div class="flex-1">
                                <label>Error loading module content. Please try again.</label>
                            </div>
                        </div>
                    `;
                }
            });
        }

        // Function to set active sidebar link
        function setActiveSidebarLink(module) {
            // Remove active classes from all sidebar links
            const allLinks = document.querySelectorAll('.sidebar-link');
            allLinks.forEach(link => {
                link.classList.remove('active', 'active-submodule');
            });
            
            // Add active class to the clicked module
            const activeLink = document.querySelector(`[data-module="${module}"]`);
            if (activeLink) {
                if (module === 'dashboard') {
                    activeLink.classList.add('active');
                } else {
                    activeLink.classList.add('active-submodule');
                    
                    // Auto-expand the parent dropdown if it's a submodule
                    const parentDropdown = activeLink.closest('.dropdown-menu');
                    if (parentDropdown && parentDropdown.classList.contains('hidden')) {
                        const dropdownToggle = parentDropdown.previousElementSibling;
                        if (dropdownToggle) {
                            const chevron = dropdownToggle.querySelector('.bx-chevron-down');
                            parentDropdown.classList.remove('hidden');
                            if (chevron) {
                                chevron.classList.add('rotate-180');
                            }
                        }
                    }
                }
            }
        }

        // Add click event listeners to sidebar links
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const module = this.getAttribute('data-module');
                loadModuleContent(module);
            });
        });

        // Handle browser back/forward buttons
        window.addEventListener('popstate', function(event) {
            if (event.state && event.state.module) {
                loadModuleContent(event.state.module, false);
            } else {
                // Load dashboard if no module state
                window.location.href = '/home';
            }
        });

        // Check if we're on a module URL and load the appropriate content
        const currentPath = window.location.pathname;
        if (currentPath.startsWith('/module/')) {
            const module = currentPath.split('/').pop();
            loadModuleContent(module, false);
        }

        // Handle direct access to home - ensure dashboard is loaded
        if (currentPath === '/home' || currentPath === '/') {
            // Dashboard is already loaded via server-side include
            history.replaceState({ module: 'dashboard' }, '', '/home');
            setActiveSidebarLink('dashboard');
        }
    });

    // Sidebar toggle logics (keep the existing sidebar toggle code)
    document.addEventListener("DOMContentLoaded", () => {
        const sidebar = document.getElementById("sidebar");
        const mainContent = document.getElementById("main-content");
        const toggleBtn = document.getElementById("toggle-btn");
        const overlay = document.getElementById("overlay");
        const dropdownToggles = document.querySelectorAll(".has-dropdown > div");

        function toggleSidebar() {
            if (window.innerWidth >= 768) {
                if (sidebar.classList.contains("md:-ml-72")) {
                    sidebar.classList.remove("md:-ml-72");
                } else {
                    sidebar.classList.add("md:-ml-72");
                    mainContent.classList.remove("md:ml-72");
                }
            } else {
                sidebar.classList.toggle("ml-0");
                overlay.classList.toggle("hidden");
                if (sidebar.classList.contains("ml-0")) {
                    document.body.style.overflow = "hidden";
                } else {
                    document.body.style.overflow = "";
                }
            }
        }

        function closeAllDropdowns() {
            dropdownToggles.forEach((toggle) => {
                const dropdown = toggle.nextElementSibling;
                const chevron = toggle.querySelector(".bx-chevron-down");
                if (!dropdown.classList.contains("hidden")) {
                    dropdown.classList.add("hidden");
                    chevron.classList.remove("rotate-180");
                }
            });
        }

        dropdownToggles.forEach((toggle) => {
            toggle.addEventListener("click", () => {
                const dropdown = toggle.nextElementSibling;
                const chevron = toggle.querySelector(".bx-chevron-down");
                dropdownToggles.forEach((otherToggle) => {
                    if (otherToggle !== toggle) {
                        otherToggle.nextElementSibling.classList.add("hidden");
                        otherToggle.querySelector(".bx-chevron-down").classList.remove("rotate-180");
                    }
                });
                dropdown.classList.toggle("hidden");
                chevron.classList.toggle("rotate-180");
            });
        });

        overlay.addEventListener("click", () => {
            sidebar.classList.remove("ml-0");
            overlay.classList.add("hidden");
            document.body.style.overflow = "";
        });

        toggleBtn.addEventListener("click", toggleSidebar);

        window.addEventListener("resize", () => {
            if (window.innerWidth >= 768) {
                sidebar.classList.remove("ml-0");
                overlay.classList.add("hidden");
                document.body.style.overflow = "";
                if (!sidebar.classList.contains("md:-ml-72") && !mainContent.classList.contains("md:ml-72")) {
                    sidebar.classList.add("md:-ml-72");
                }
            } else {
                sidebar.classList.remove("md:-ml-72");
                mainContent.classList.remove("md:ml-72");
            }
            closeAllDropdowns();
        });
    });
</script>