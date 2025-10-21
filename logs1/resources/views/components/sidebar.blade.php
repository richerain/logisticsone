<aside id="sidebar" class="bg-[#2f855A] z-0 h-screen p-4 fixed top-15.5 shadow-lg w-64 transition-all duration-500 ease-in-out flex flex-col transform translate-x-0">
    <h2 class="text-xl font-bold text-green-50 text-center flex-shrink-0">
        <span class="full-title">Logistics I</span>
    </h2>
    <hr class="my-4 flex-shrink-0" />
    <div class="overflow-y-auto scrollbar scrollbar-opacity-30 flex-grow">
        <ul class="space-y-2 text-green-50" id="sidebar-menu">
            <li>
                <a href="{{ route('dashboard') }}" title="Dashboard" class="flex items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('dashboard') ? 'bg-white/30' : '' }}">
                    <i class="bx bxs-home mr-2"></i>
                    <span class="module-text">Dashboard</span>
                </a>
            </li>

            <!-- Procurement & Sourcing - Visible to superadmin, admin, manager -->
            <li class="has-sub" data-roles="superadmin,admin,manager,vendor">
                <a href="#" title="Procurement & Sourcing Management" class="flex items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('modules.psm.*') ? 'bg-white/30' : '' }}" onclick="toggleSubmodules(this); return false;">
                    <i class="bx bxs-cart mr-2"></i>
                    <span class="module-text">Procurement & Sourcing</span>
                    <i class="bx bx-chevron-right chevron ml-auto"></i>
                </a>
                <ul class="submodules hidden pl-4 space-y-2">
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
                    <li>
                        <a href="{{ route('modules.psm.vendor-market') }}" title="Vendor Market" class="hidden items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('modules.psm.vendor-market') ? 'bg-white/30' : '' }}">
                            <i class="bx bxs-store mr-2"></i>
                            <span class="module-text">Vendor Market</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('modules.psm.order-management') }}" title="Order Management" class="hidden items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('modules.psm.order-management') ? 'bg-white/30' : '' }}">
                            <i class="bx bxs-receipt mr-2"></i>
                            <span class="module-text">Order Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('modules.psm.budget-approval') }}" title="Budget Approval" class="hidden items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('modules.psm.budget-approval') ? 'bg-white/30' : '' }}">
                            <i class="bx bxs-credit-card mr-2"></i>
                            <span class="module-text">Budget Approval</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('modules.psm.place-order') }}" title="Place Order Management" class="hidden items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('modules.psm.place-order') ? 'bg-white/30' : '' }}">
                            <i class="bx bxs-package mr-2"></i>
                            <span class="module-text">Place Order Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('modules.psm.reorder-management') }}" title="Re-Order Management" class="hidden items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('modules.psm.reorder-management') ? 'bg-white/30' : '' }}">
                            <i class="bx bx-refresh mr-2"></i>
                            <span class="module-text">Re-Order Management <p class="badge badge-xs badge-warning">#</p></span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('modules.psm.products-management') }}" title="Products Management" class="hidden items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('modules.psm.products-management') ? 'bg-white/30' : '' }}">
                            <i class="bx bxs-purchase-tag mr-2"></i>
                            <span class="module-text">Products Management</span>
                        </a>
                    </li>
                    <!-- Shop Management removed - functionality merged into Vendor Management -->
                </ul>
            </li>

            <!-- Smart Warehousing System - Visible to all except vendor -->
            <li class="has-sub" data-roles="superadmin,admin,manager,staff">
                <a href="#" title="Smart Warehousing System" class="flex items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('modules.sws.*') ? 'bg-white/30' : '' }}" onclick="toggleSubmodules(this); return false;">
                    <i class="bx bxs-store mr-2"></i>
                    <span class="module-text">Smart Warehousing</span>
                    <i class="bx bx-chevron-right chevron ml-auto"></i>
                </a>
                <ul class="submodules hidden pl-4 space-y-2">
                    <li>
                        <a href="{{ route('modules.sws.inventory') }}" title="Inventory Management" class="flex items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('modules.sws.inventory') ? 'bg-white/30' : '' }}">
                            <i class="bx bxs-package mr-2"></i>
                            <span class="module-text">Inventory Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('modules.sws.storage') }}" title="Storage Management" class="flex items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('modules.sws.storage') ? 'bg-white/30' : '' }}"> 
                            <i class="bx bxs-archive mr-2"></i>
                            <span class="module-text">Storage Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('modules.sws.restock') }}" title="Restock Management" class="flex items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('modules.sws.restock') ? 'bg-white/30' : '' }}"> 
                            <i class="bx bxs-truck mr-2"></i>
                            <span class="module-text">Restock Management</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Project Logistics Tracker - Visible to all except vendor -->
            <li class="has-sub" data-roles="superadmin,admin,manager,staff">
                <a href="#" title="Project Logistics Tracker" class="flex items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('modules.plt.*') ? 'bg-white/30' : '' }}" onclick="toggleSubmodules(this); return false;">
                    <i class="bx bxs-map mr-2"></i>
                    <span class="module-text">Project Logistics Tracker</span>
                    <i class="bx bx-chevron-right chevron ml-auto"></i>
                </a>
                <ul class="submodules hidden pl-4 space-y-2">
                    <li>
                        <a href="{{ route('modules.plt.projects') }}" title="Project Management" class="flex items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('modules.plt.projects') ? 'bg-white/30' : '' }}">
                            <i class="bx bxs-briefcase mr-2"></i>
                            <span class="module-text">Project Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('modules.plt.dispatches') }}" title="Dispatch Tracking" class="flex items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('modules.plt.dispatches') ? 'bg-white/30' : '' }}">
                            <i class="bx bxs-truck mr-2"></i>
                            <span class="module-text">Dispatch Tracking</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('modules.plt.resources') }}" title="Resource Management" class="flex items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('modules.plt.resources') ? 'bg-white/30' : '' }}">
                            <i class="bx bxs-cube mr-2"></i>
                            <span class="module-text">Resource Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('modules.plt.allocations') }}" title="Resource Allocation" class="flex items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('modules.plt.allocations') ? 'bg-white/30' : '' }}">
                            <i class="bx bxs-user-check mr-2"></i>
                            <span class="module-text">Resource Allocation</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('modules.plt.milestones') }}" title="Milestone Tracking" class="flex items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('modules.plt.milestones') ? 'bg-white/30' : '' }}">
                            <i class="bx bxs-flag mr-2"></i>
                            <span class="module-text">Milestone Tracking</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('modules.plt.tracking-logs') }}" title="Tracking Logs" class="flex items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('modules.plt.tracking-logs') ? 'bg-white/30' : '' }}">
                            <i class="bx bxs-notepad mr-2"></i>
                            <span class="module-text">Tracking Logs</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Asset Lifecycle & Maintenance - Visible to superadmin, admin, manager, staff -->
            <li class="has-sub" data-roles="superadmin,admin,manager,staff">
                <a href="#" title="Asset Lifecycle & Maintenance" class="flex items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('modules.alms.*') ? 'bg-white/30' : '' }}" onclick="toggleSubmodules(this); return false;">
                    <i class="bx bxs-wrench mr-2"></i>
                    <span class="module-text">Lifecycle & Maintenance</span>
                    <i class="bx bx-chevron-right chevron ml-auto"></i>
                </a>
                <ul class="submodules hidden pl-4 space-y-2">
                    <li>
                        <a href="{{ route('modules.alms.registration') }}" title="Asset Registration" class="flex items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('modules.alms.registration') ? 'bg-white/30' : '' }}">
                            <i class="bx bxs-archive-in mr-2"></i>
                            <span class="module-text">Asset Registration</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('modules.alms.scheduling') }}" title="Maintenance Scheduling" class="flex items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('modules.alms.scheduling') ? 'bg-white/30' : '' }}">
                            <i class="bx bxs-calendar-event mr-2"></i>
                            <span class="module-text">Maintenance Scheduling</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('modules.alms.transfers') }}" title="Asset Transfers" class="flex items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('modules.alms.transfers') ? 'bg-white/30' : '' }}">
                            <i class="bx bxs-truck mr-2"></i>
                            <span class="module-text">Asset Transfers</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('modules.alms.disposals') }}" title="Disposal Management" class="flex items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('modules.alms.disposals') ? 'bg-white/30' : '' }}">
                            <i class="bx bxs-trash-alt mr-2"></i>
                            <span class="module-text">Disposal Management</span>
                        </a>
                    </li>
                    <li>
                        <a href="{{ route('modules.alms.reports') }}" title="Reports & Analytics" class="flex items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('modules.alms.reports') ? 'bg-white/30' : '' }}">
                            <i class="bx bxs-report mr-2"></i>
                            <span class="module-text">Reports & Analytics</span>
                        </a>
                    </li>
                </ul>
            </li>

            <!-- Document Tracking & Logistics Record - Visible to all roles except vendor -->
            <li class="has-sub" data-roles="superadmin,admin,manager,staff">
                <a href="#" title="Document Tracking & Logistics Record" class="flex items-center p-2 rounded hover:bg-white/50 {{ request()->routeIs('modules.dtlr.*') ? 'bg-white/30' : '' }}" onclick="toggleSubmodules(this); return false;">
                    <i class="bx bxs-file mr-2"></i>
                    <span class="module-text">Tracking & Record</span>
                    <i class="bx bx-chevron-right chevron ml-auto"></i>
                </a>
                <ul class="submodules hidden pl-4 space-y-2">
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
</script>