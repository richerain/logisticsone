<!-- resources/views/components/sidebar.blade.php -->
<aside id="sidebar" class="bg-[#2f855A] text-white flex flex-col z-50 absolute md:relative w-72 -ml-72 md:ml-0 transition-all duration-300 ease-in-out h-auto">
    <div class="department-header text-center py-5 mx-2 border-b border-white/50">
        <h1 class="text-xl font-bold">Logistics I</h1>
    </div>
    <div class="px-3 py-5 flex-1">
        <ul class="space-y-1">
            <!-- dashboard sidebar btn start -->
            <li>
                <a href="#" data-module="dashboard" class="sidebar-link flex items-center font-medium text-md hover:bg-white/30 px-3 py-2.5 rounded-lg whitespace-normal wrap-break-word">
                    <i class="bx bxs-dashboard mr-2 shrink-0"></i>
                    <span class="flex-1">Dashboard</span>
                </a>
            </li>
            <!-- dashboard sidebar btn end -->
            <!-- Procurement & Sourcing Management btn start -->
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
                    <li><a href="#" data-module="psm-vendor-quote" class="sidebar-link flex items-center px-3 py-2 text-sm hover:bg-white/30 rounded-lg whitespace-normal wrap-break-words"><span class="module-text"><i class='bx bx-fw bxs-quote-single-left' ></i>Vendor Quote</span></a></li>
                    <li><a href="#" data-module="psm-vendor-management" class="sidebar-link flex items-center px-3 py-2 text-sm hover:bg-white/30 rounded-lg whitespace-normal wrap-break-words"><span class="module-text"><i class='bx bx-fw bxs-user-detail' ></i>Vendor Management</span></a></li>
                </ul>
            </li>
            <!-- Procurement & Sourcing Management btn end -->
            <!-- Smart Warehousing System btn start -->
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
            <!-- Smart Warehousing System btn end -->
            <!-- Project Logistics Tracker btn start -->
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
            <!-- Project Logistics Tracker btn end -->
            <!-- Asset Lifecycle & Maintenance btn start -->
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
            <!-- Asset Lifecycle & Maintenance btn end -->
            <!-- Document Tracking & Logistics Record btn start -->
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
            <!-- Document Tracking & Logistics Record btn end -->
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
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
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
                
                // Re-initialize any charts or dynamic content
                if (typeof initializeCharts === 'function') {
                    initializeCharts();
                }
            })
            .catch(error => {
                console.error('Error loading module:', error);
                moduleContent.innerHTML = `
                    <div class="alert alert-error">
                        <div class="flex-1">
                            <label>Error loading module content. Please try again.</label>
                        </div>
                    </div>
                `;
            });
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