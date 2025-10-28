<aside id="sidebar" class="bg-[#2f855A] z-0 h-screen p-4 fixed top-15.5 shadow-lg w-64 transition-all duration-500 ease-in-out flex flex-col transform translate-x-0">
    <h2 class="text-xl font-bold text-green-50 text-center flex-shrink-0">
        <span class="full-title">Logistics I</span>
    </h2>
    <hr class="my-4 flex-shrink-0" />
    <div class="overflow-y-auto scrollbar scrollbar-opacity-30 flex-grow">
        <ul class="space-y-2 text-green-50" id="sidebar-menu">
            <li>
                <a href="{{ route('dashboard') }}" title="Dashboard" class="text-sm flex items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('dashboard') ? 'bg-white/30' : '' }}">
                    <i class="bx bxs-home mr-2"></i>
                    <span class="module-text">Dashboard</span>
                </a>
            </li>

            <!-- Procurement & Sourcing - Visible to superadmin, admin, manager -->
            <li class="has-sub" data-roles="superadmin,admin,manager,vendor">
                <a href="#" title="Procurement & Sourcing Management" class="text-sm flex items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('modules.psm.*') ? 'bg-white/30' : '' }}" onclick="toggleSubmodules(this); return false;">
                    <i class="bx bxs-cart mr-2"></i>
                    <span class="module-text">Procurement & Sourcing Management</span>
                    <i class="bx bx-chevron-right chevron ml-auto"></i>
                </a>
                <ul class="submodules hidden pl-4 space-y-2 text-sm">
                    <li>
                        <a href="{{ route('modules.psm.purchase-management') }}" title="Purchase Management" class="flex items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('modules.psm.purchase-management') ? 'bg-white/30' : '' }}">
                            <i class="bx bxs-purchase-tag-alt mr-2"></i>
                            <span class="module-text">Purchase Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('modules.psm.vendor-quote') }}" title="Vendor Quote" class="flex items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('modules.psm.vendor-quote') ? 'bg-white/30' : '' }}">
                            <i class="bx bxs-file-blank mr-2"></i>
                            <span class="module-text">Vendor Quote</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('modules.psm.vendor-management') }}" title="Vendor Management" class="flex items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('modules.psm.vendor-management') ? 'bg-white/30' : '' }}">
                            <i class="bx bxs-group mr-2"></i>
                            <span class="module-text">Vendor Management</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Smart Warehousing System - Visible to all except vendor -->
            <li class="has-sub" data-roles="superadmin,admin,manager,staff">
                <a href="#" title="Smart Warehousing System" class="text-sm flex items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('modules.sws.*') ? 'bg-white/30' : '' }}" onclick="toggleSubmodules(this); return false;">
                    <i class="bx bxs-store mr-2"></i>
                    <span class="module-text">Smart Warehousing System</span>
                    <i class="bx bx-chevron-right chevron ml-auto"></i>
                </a>
                <ul class="submodules hidden pl-4 space-y-2 text-sm">
                    <li>
                        <a href="{{ route('modules.sws.warehousing') }}" title="Inventory Flow" class="flex items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('modules.sws.warehousing') ? 'bg-white/30' : '' }}">
                            <i class="bx bxs-receipt mr-2"></i>
                            <span class="module-text">Inventory Flow</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('modules.sws.restock') }}" title="Digital inventory" class="flex items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('modules.sws.restock') ? 'bg-white/30' : '' }}"> 
                            <i class="bx bxs-truck mr-2"></i>
                            <span class="module-text">Digital Inventory</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Project Logistics Tracker - Visible to all except vendor -->
            <li class="has-sub" data-roles="superadmin,admin,manager,staff">
                <a href="#" title="Project Logistics Tracker" class="text-sm flex items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('modules.plt.*') ? 'bg-white/30' : '' }}" onclick="toggleSubmodules(this); return false;">
                    <i class="bx bxs-map mr-2"></i>
                    <span class="module-text">Project Logistics Tracker</span>
                    <i class="bx bx-chevron-right chevron ml-auto"></i>
                </a>
                <ul class="submodules hidden pl-4 space-y-2 text-sm">
                    <li>
                        <a href="{{ route('modules.plt.logistics') }}" title="Logistics Projects" class="flex items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('modules.plt.logistics') ? 'bg-white/30' : '' }}">
                            <i class="bx bxs-truck mr-2"></i>
                            <span class="module-text">Logistics Projects</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Asset Lifecycle & Maintenance - Visible to superadmin, admin, manager, staff -->
            <li class="has-sub" data-roles="superadmin,admin,manager,staff">
                <a href="#" title="Asset Lifecycle & Maintenance" class="text-sm flex items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('modules.alms.*') ? 'bg-white/30' : '' }}" onclick="toggleSubmodules(this); return false;">
                    <i class="bx bxs-wrench mr-2"></i>
                    <span class="module-text">Asset Lifecycle & Maintenance</span>
                    <i class="bx bx-chevron-right chevron ml-auto"></i>
                </a>
                <ul class="submodules hidden pl-4 space-y-2 text-sm">
                    <li>
                        <a href="{{ route('modules.alms.asset') }}" title="Asset Management" class="flex items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('modules.alms.asset') ? 'bg-white/30' : '' }}">
                            <i class="bx bxs-archive-in mr-2"></i>
                            <span class="module-text">Asset Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('modules.alms.maintenance') }}" title="Maintenance Management" class="flex items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('modules.alms.maintenance') ? 'bg-white/30' : '' }}">
                            <i class="bx bxs-calendar-event mr-2"></i>
                            <span class="module-text">Maintenance Management</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Document Tracking & Logistics Record - Visible to all roles except vendor -->
            <li class="has-sub" data-roles="superadmin,admin,manager,staff">
                <a href="#" title="Document Tracking & Logistics Record" class="text-sm flex items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('modules.dtlr.*') ? 'bg-white/30' : '' }}" onclick="toggleSubmodules(this); return false;">
                    <i class="bx bxs-file mr-2"></i>
                    <span class="module-text">Document Tracking & Logistics Record</span>
                    <i class="bx bx-chevron-right chevron ml-auto"></i>
                </a>
                <ul class="submodules hidden pl-4 space-y-2 text-sm">
                    <li>
                        <a href="{{ route('modules.dtlr.documents') }}" title="Document Tracker" class="flex items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('modules.dtlr.documents') ? 'bg-white/30' : '' }}">
                            <i class="bx bxs-file-doc mr-2"></i>
                            <span class="module-text">Document Tracker</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('modules.dtlr.logistics') }}" title="Logistics Record" class="flex items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('modules.dtlr.logistics') ? 'bg-white/30' : '' }}">
                            <i class="bx bxs-notepad mr-2"></i>
                            <span class="module-text">Logistics Record</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
    <hr class="my-4 flex-shrink-0" />
</aside>

<script>
    // Function to filter sidebar items based on user role
    function filterSidebarByRole() {
        const user = JSON.parse(localStorage.getItem('user') || '{}');
        const userRole = user.roles ? user.roles.toLowerCase() : '';
        
        const sidebarItems = document.querySelectorAll('#sidebar-menu li[data-roles]');
        
        sidebarItems.forEach(item => {
            const allowedRoles = item.getAttribute('data-roles').split(',');
            
            if (!allowedRoles.includes(userRole)) {
                item.style.display = 'none';
            } else {
                item.style.display = 'block';
            }
        });
    }

    // Apply role-based filtering when DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        filterSidebarByRole();
        
        // Auto-expand submenu if current route is in a submodule
        const currentPath = window.location.pathname;
        const submodules = document.querySelectorAll('.submodules');
        const chevrons = document.querySelectorAll('.chevron');
        
        submodules.forEach(function(sub, index) {
            const links = sub.querySelectorAll('a');
            for (let link of links) {
                if (link.href.includes(currentPath)) {
                    const parentA = sub.parentElement.querySelector('a');
                    const chevron = parentA.querySelector('.chevron');
                    sub.classList.remove('hidden');
                    chevron.classList.replace('bx-chevron-right', 'bx-chevron-down');
                    parentA.classList.add('bg-white/30');
                    break;
                }
            }
        });
    });

    // Toggle submodules function
    function toggleSubmodules(element) {
        const submodules = element.nextElementSibling;
        const chevron = element.querySelector('.chevron');
        
        if (submodules.classList.contains('hidden')) {
            submodules.classList.remove('hidden');
            chevron.classList.replace('bx-chevron-right', 'bx-chevron-down');
        } else {
            submodules.classList.add('hidden');
            chevron.classList.replace('bx-chevron-down', 'bx-chevron-right');
        }
    }
</script>