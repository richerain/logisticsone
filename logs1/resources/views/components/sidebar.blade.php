<!-- resources/views/components/sidebar.blade.php -->
<aside id="sidebar" class="bg-[#2f855A] text-white flex flex-col z-50 absolute md:relative w-72 -ml-72 md:ml-0 transition-all duration-300 ease-in-out h-full overflow-hidden">
    <div class="flex-1 overflow-y-auto sidebar-scrollbar px-3 py-5">
        @php($isVendor = Auth::guard('vendor')->check())
        @php($user = $isVendor ? Auth::guard('vendor')->user() : Auth::guard('sws')->user())
        @php($role = strtolower(optional($user)->roles ?? ''))
        <ul class="space-y-1">
            
            <!-- Group 1: Main Menu -->
            <li class="px-3 pt-2 pb-1 text-xs font-semibold text-gray-200 uppercase tracking-wider opacity-80">Main Menu</li>

            <!-- dashboard sidebar btn start -->
            @if($isVendor || in_array($role, ['vendor', 'staff', 'manager', 'admin', 'superadmin']))
            <li>
                <a href="#" data-module="dashboard" class="sidebar-link flex items-center font-medium text-md hover:bg-white/30 px-3 py-2.5 rounded-lg whitespace-normal wrap-break-word" title="Dashboard">
                    <i class="bx bxs-dashboard mr-2 shrink-0"></i>
                    <span class="flex-1">Dashboard</span>
                </a>
            </li>
            @endif
            <!-- dashboard sidebar btn end -->

            <!-- Group 2: Vendor Management -->
            @if($isVendor)
            <li class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-200 uppercase tracking-wider opacity-80">Vendor Management</li>
            
            <li>
                <a href="#" data-module="vendor-quote" class="sidebar-link flex items-center font-medium text-md hover:bg-white/30 px-3 py-2.5 rounded-lg whitespace-normal wrap-break-word" title="Vendor Quote">
                    <i class='bx bx-fw bxs-quote-left'></i>
                    <span class="flex-1">Vendor Quote</span>
                </a>
            </li>
            <li>
                <a href="#" data-module="vendor-products" class="sidebar-link flex items-center font-medium text-md hover:bg-white/30 px-3 py-2.5 rounded-lg whitespace-normal wrap-break-word" title="Vendor Products">
                    <i class='bx bx-fw bx-cube-alt'></i>
                    <span class="flex-1">Vendor Products</span>
                </a>
            </li>
            @endif
            
            <!-- Group 3: Team Management -->
            @if(!$isVendor && in_array($role, ['staff', 'manager', 'admin', 'superadmin']))
            <li class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-200 uppercase tracking-wider opacity-80">Team Management</li>

            <!-- Procurement & Sourcing Management btn start -->
            <li class="has-dropdown">
                <div class="flex items-center font-medium justify-between text-sm hover:bg-white/30 px-3 py-2.5 rounded-lg whitespace-normal wrap-break-words cursor-pointer">
                    <div class="flex items-center flex-1 min-w-0" title="Procurement & Sourcing Management">
                        <i class="bx bxs-cart mr-2 shrink-0"></i>
                        <span class="module-text flex-1">Procurement & Sourcing Management</span>
                    </div>
                    <i class="bx bx-chevron-down text-2xl transition-transform duration-300 shrink-0 ml-2"></i>
                </div>
                <ul class="dropdown-menu hidden bg-white/20 mt-2 rounded-lg px-2 py-2">
                    <li><a href="#" data-module="psm-purchase"  title="Purchase Management" class="sidebar-link flex items-center px-3 py-2 text-sm hover:bg-white/30 rounded-lg whitespace-normal wrap-break-words"><span class="module-text"><i class='bx bx-fw bxs-purchase-tag' ></i>Purchase Management</span></a></li>
                    @if(in_array($role, ['superadmin', 'admin', 'manager']))
                    <li><a href="#" data-module="psm-budgeting"  title="Budgeting" class="sidebar-link flex items-center px-3 py-2 text-sm hover:bg-white/30 rounded-lg whitespace-normal wrap-break-words"><span class="module-text"><i class='bx bx-fw bxs-wallet'></i>Budgeting</span></a></li>
                    @endif
                    <li><a href="#" data-module="psm-vendor-management"  title="Vendors" class="sidebar-link flex items-center px-3 py-2 text-sm hover:bg-white/30 rounded-lg whitespace-normal wrap-break-words"><span class="module-text"><i class='bx bx-fw bxs-user-detail' ></i>Vendors</span></a></li>
                </ul>
            </li>
            <!-- Procurement & Sourcing Management btn end -->
            
            <!-- Smart Warehousing System btn start -->
            <li class="has-dropdown">
                <div class="flex items-center font-medium justify-between text-sm hover:bg-white/30 px-3 py-2.5 rounded-lg whitespace-normal wrap-break-words cursor-pointer">
                    <div class="flex items-center flex-1 min-w-0" title="Smart Warehousing System">
                        <i class="bx bxs-store mr-2 shrink-0"></i>
                        <span class="flex-1">Smart Warehousing System</span>
                    </div>
                    <i class="bx bx-chevron-down text-2xl transition-transform duration-300 shrink-0 ml-2"></i>
                </div>
                <ul class="dropdown-menu hidden bg-white/20 mt-2 rounded-lg px-2 py-2">
                    <li><a href="#" data-module="sws-inventory-flow"  title="Inventory Flow" class="sidebar-link flex items-center px-3 py-2 text-sm hover:bg-white/30 rounded-lg whitespace-normal wrap-break-words"><span class="module-text"><i class='bx bx-fw bx-sync'></i>Inventory Flow</span></a></li>
                    <li><a href="#" data-module="sws-digital-inventory"  title="Digital Inventory" class="sidebar-link flex items-center px-3 py-2 text-sm hover:bg-white/30 rounded-lg whitespace-normal wrap-break-words"><span class="module-text"><i class='bx bx-fw bxs-archive-in'></i>Digital Inventory</span></a></li>
                    <li><a href="#" data-module="sws-warehouse-management" title="Warehouse Management" class="sidebar-link flex items-center px-3 py-2 text-sm hover:bg-white/30 rounded-lg whitespace-normal wrap-break-words"><span class="module-text"><i class='bx bx-fw bx-store'></i>Warehouse Management</span></a></li>
                </ul>
            </li>
            <!-- Smart Warehousing System btn end -->
            
            <!-- Project Logistics Tracker btn start -->
            <li class="has-dropdown">
                <div class="flex items-center font-medium justify-between text-sm hover:bg-white/30 px-3 py-2.5 rounded-lg whitespace-normal wrap-break-words cursor-pointer">
                    <div class="flex items-center flex-1 min-w-0"  title="Project Logistics Tracker" >
                        <i class="bx bxs-truck mr-2 shrink-0"></i>
                        <span class="flex-1">Project Logistics Tracker</span>
                    </div>
                    <i class="bx bx-chevron-down text-2xl transition-transform duration-300 shrink-0 ml-2"></i>
                </div>
                <ul class="dropdown-menu hidden bg-white/20 mt-2 rounded-lg px-2 py-2 space-y-2">
                    <li><a href="#" data-module="plt-logistics-projects" title="Logistics Projects" class="sidebar-link flex items-center px-3 py-2 text-sm hover:bg-white/30 rounded-lg whitespace-normal wrap-break-words"><i class='bx bx-fw bxs-package' ></i>Logistics Projects</a></li>
                </ul>
            </li>
            <!-- Project Logistics Tracker btn end -->
            
            <!-- Asset Lifecycle & Maintenance btn start -->
            <li class="has-dropdown">
                <div class="flex items-center font-medium justify-between text-sm hover:bg-white/30 px-3 py-2.5 rounded-lg whitespace-normal wrap-break-words cursor-pointer">
                    <div class="flex items-center flex-1 min-w-0" title="Asset Lifecycle & Maintenance" >
                        <i class="bx bxs-hard-hat mr-2 shrink-0"></i>
                        <span class="flex-1">Asset Lifecycle & Maintenance</span>
                    </div>
                    <i class="bx bx-chevron-down text-2xl transition-transform duration-300 shrink-0 ml-2"></i>
                </div>
                <ul class="dropdown-menu hidden bg-white/20 mt-2 rounded-lg px-2 py-2">
                    <li><a href="#" data-module="alms-asset-management"  title="Asset Management" class="sidebar-link flex items-center px-3 py-2 text-sm hover:bg-white/30 rounded-lg whitespace-normal wrap-break-words"><i class='bx bx-fw bxs-archive'></i>Asset Management</a></li>
                    <li><a href="#" data-module="alms-maintenance-management"  title="Maintenance Management" class="sidebar-link flex items-center px-3 py-2 text-sm hover:bg-white/30 rounded-lg whitespace-normal wrap-break-words"><i class='bx bx-fw bxs-wrench'></i>Maintenance Management</a></li>
                </ul>
            </li>
            <!-- Asset Lifecycle & Maintenance btn end -->
            
            <!-- Document Tracking & Logistics Record btn start -->
            <li class="has-dropdown">
                <div class="flex items-center font-medium justify-between text-sm hover:bg-white/30 px-3 py-2.5 rounded-lg whitespace-normal wrap-break-words cursor-pointer">
                    <div class="flex items-center flex-1 min-w-0" title="Document Tracking & Logistics Record" >
                        <i class="bx bxs-map mr-2 shrink-0"></i>
                        <span class="flex-1">Document Tracking & Logistics Record</span>
                    </div>
                    <i class="bx bx-chevron-down text-2xl transition-transform duration-300 shrink-0 ml-2"></i>
                </div>
                <ul class="dropdown-menu hidden bg-white/20 mt-2 rounded-lg px-2 py-2">
                    <li><a href="#" data-module="dtlr-document-tracker" title="Document Tracker" class="sidebar-link flex items-center px-3 py-2 text-sm hover:bg-white/30 rounded-lg whitespace-normal wrap-break-words"><i class='bx bx-fw bxs-file'></i>Document Tracker</a></li>
                    <li><a href="#" data-module="dtlr-logistics-record" title="Logistics Record" class="sidebar-link flex items-center px-3 py-2 text-sm hover:bg-white/30 rounded-lg whitespace-normal wrap-break-words"><i class='bx bx-fw bxs-package'></i>Logistics Record</a></li>
                </ul>
            </li>
            <!-- Document Tracking & Logistics Record btn end -->
            @endif
            
            <!-- Group 4: System Admin -->
            @if($role === 'superadmin')
            <li class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-200 uppercase tracking-wider opacity-80">System Admin</li>

            <!-- User Management btn start -->
            <li class="has-dropdown">
                <div class="flex items-center font-medium justify-between text-sm hover:bg-white/30 px-3 py-2.5 rounded-lg whitespace-normal wrap-break-words cursor-pointer">
                    <div class="flex items-center flex-1 min-w-0" title="User Management">
                        <i class="bx bxs-user-account mr-2 shrink-0"></i>
                        <span class="flex-1">User Management</span>
                    </div>
                    <i class="bx bx-chevron-down text-2xl transition-transform duration-300 shrink-0 ml-2"></i>
                </div>
                <ul class="dropdown-menu hidden bg-white/20 mt-2 rounded-lg px-2 py-2">
                    <li><a href="#" data-module="um-account-management" title="Account Management" class="sidebar-link flex items-center px-3 py-2 text-sm hover:bg-white/30 rounded-lg whitespace-normal wrap-break-words"><i class='bx bx-fw bxs-user-detail'></i>Account Management</a></li>
                    <li><a href="#" data-module="um-audit-trail" title="Audit Trail" class="sidebar-link flex items-center px-3 py-2 text-sm hover:bg-white/30 rounded-lg whitespace-normal wrap-break-words"><i class='bx bx-fw bx-history'></i>Audit Trail</a></li>
                </ul>
            </li>
            <!-- User Management btn end -->
            @endif
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
        const dropdownToggles = document.querySelectorAll('.has-dropdown > div');
        
        // Function to close all dropdowns except the one passed as argument (optional)
        function closeAllDropdowns(exceptMenu = null) {
            dropdownToggles.forEach(toggle => {
                const menu = toggle.nextElementSibling;
                const icon = toggle.querySelector('.bx-chevron-down');
                
                // If this is the menu we want to keep open, skip it
                if (menu === exceptMenu) return;

                // Close the menu
                menu.classList.add('hidden');
                if (icon) {
                    icon.style.transform = 'rotate(0deg)';
                }
            });
        }

        // Function to deactivate all sidebar links
        function deactivateAllLinks() {
            // Re-query to be safe
            document.querySelectorAll('.sidebar-link').forEach(link => {
                link.classList.remove('bg-white/20');
            });
            // Explicitly force dashboard to deactivate just in case
            const dashboardBtn = document.querySelector('a[data-module="dashboard"]');
            if(dashboardBtn) dashboardBtn.classList.remove('bg-white/20');
        }

        // Function to load module content
        function loadModuleContent(module, pushState = true) {
            // Show loading state
            moduleContent.innerHTML = '<div class="flex justify-center items-center h-64"><div class="loading loading-spinner loading-lg"></div></div>';

            const isVendor = {{ Auth::guard('vendor')->check() ? 'true' : 'false' }};
            const moduleBase = isVendor ? '/vendor/module/' : '/module/';
            // Send AJAX request to load module content
            fetch(`${moduleBase}${module}`, {
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
                const tempContainer = document.createElement('div');
                tempContainer.innerHTML = html;

                const scriptTags = tempContainer.querySelectorAll('script');
                scriptTags.forEach(tag => tag.parentNode && tag.parentNode.removeChild(tag));

                moduleContent.innerHTML = tempContainer.innerHTML;

                // Remove previously loaded scripts for this module to avoid duplicates
                const existingModuleScripts = document.querySelectorAll(`script[data-module="${module}"]`);
                existingModuleScripts.forEach(s => s.parentNode && s.parentNode.removeChild(s));

                scriptTags.forEach(oldScript => {
                    if (oldScript.src) {
                        const newScript = document.createElement('script');
                        newScript.setAttribute('data-module', module);
                        newScript.src = oldScript.src;
                        document.body.appendChild(newScript);
                    } else {
                        try {
                            (new Function(oldScript.textContent))();
                        } catch (e) {
                            console.error("Error executing inline script:", e);
                        }
                    }
                });

                // Update URL without reloading
                if (pushState) {
                    const url = isVendor ? `/vendor/home?module=${module}` : `/home?module=${module}`;
                    history.pushState({ module: module }, '', url);
                }

                // Update active state in sidebar
                deactivateAllLinks();
                let activeLinkFound = false;
                
                sidebarLinks.forEach(link => {
                    if (link.getAttribute('data-module') === module) {
                        link.classList.add('bg-white/20');
                        activeLinkFound = true;
                        
                        // Handle parent dropdown
                        const parentDropdown = link.closest('.dropdown-menu');
                        if (parentDropdown) {
                            // Ensure this dropdown is open and others are closed
                            closeAllDropdowns(parentDropdown);
                            parentDropdown.classList.remove('hidden');
                            const icon = parentDropdown.previousElementSibling.querySelector('.bx-chevron-down');
                            if(icon) icon.style.transform = 'rotate(180deg)';
                        } else {
                            // If it's a root item (like Dashboard), close all dropdowns
                            closeAllDropdowns();
                        }
                    }
                });
                
                // Fallback: if no link matches (shouldn't happen often), ensure dropdowns are closed
                if (!activeLinkFound) {
                    closeAllDropdowns();
                }

            })
            .catch(error => {
                console.error('Error loading module:', error);
                moduleContent.innerHTML = `
                    <div class="alert alert-error">
                        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        <span>Error loading content. Please try again.</span>
                    </div>`;
            });
        }

        // Handle sidebar link clicks
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const module = this.getAttribute('data-module');
                loadModuleContent(module);
            });
        });

        // Handle dropdown toggles
        dropdownToggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                const menu = this.nextElementSibling;
                const icon = this.querySelector('.bx-chevron-down');
                const isHidden = menu.classList.contains('hidden');

                // First, close all other dropdowns
                closeAllDropdowns();

                // If the clicked one was hidden, open it now
                // (If it was open, closeAllDropdowns already closed it, effectively toggling it off)
                if (isHidden) {
                    menu.classList.remove('hidden');
                    if(icon) icon.style.transform = 'rotate(180deg)';
                }
            });
        });

        // Handle browser back/forward buttons
        window.addEventListener('popstate', function(event) {
            if (event.state && event.state.module) {
                loadModuleContent(event.state.module, false);
            } else {
                // If no state, check URL params or load default
                const urlParams = new URLSearchParams(window.location.search);
                const module = urlParams.get('module') || 'dashboard';
                loadModuleContent(module, false);
            }
        });

        // Initial load based on URL parameter or default
        const urlParams = new URLSearchParams(window.location.search);
        const initialModule = urlParams.get('module') || 'dashboard';
        loadModuleContent(initialModule, false);
        
        // Mobile sidebar toggle (if needed, assumed present in header)
        const toggleBtn = document.getElementById('toggle-btn');
        const sidebar = document.getElementById('sidebar');
        if (toggleBtn && sidebar) {
            toggleBtn.addEventListener('click', () => {
                sidebar.classList.toggle('-ml-72');
            });
        }
    });
</script>