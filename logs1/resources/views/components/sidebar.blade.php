<!-- resources/views/components/sidebar.blade.php -->
<!-- Overlay (mobile) -->
<div id="sidebar-overlay" class="fixed inset-0 bg-black/30 hidden opacity-0 transition-opacity duration-300 z-40 lg:hidden"></div>

<!-- SIDEBAR -->
<aside id="sidebar" 
  class="fixed top-0 left-0 h-full w-72 bg-white border-r border-gray-100 shadow-sm z-50 
         transform -translate-x-full lg:translate-x-0 transition-transform duration-300 flex flex-col">

  <div class="h-16 flex items-center px-4 border-b border-gray-100 shrink-0">
    <a href="#" 
      class="flex items-center gap-3 w-full rounded-xl px-2 py-2 
             hover:bg-gray-100 active:bg-gray-200 transition group">
      <img src="{{ asset('images/micrologo.png') }}" alt="Logo" class="w-10 h-10 object-contain">
      <div class="leading-tight">
        <div class="font-bold text-gray-800 group-hover:text-brand-primary transition-colors">
          Microfinance Logistics
        </div>
        <div class="text-[11px] text-gray-500 font-semibold uppercase group-hover:text-brand-primary transition-colors">
          Logistics I
        </div>
      </div>
    </a>
  </div>

  <!-- Sidebar content -->
  <div class="px-4 py-4 overflow-y-auto flex-1 custom-scrollbar">
    @php
        $isVendor = false;
        $user = null;
        $role = '';
        try {
            $isVendor = Auth::guard('vendor')->check();
            $user = $isVendor ? Auth::guard('vendor')->user() : Auth::guard('sws')->user();
            $role = strtolower(optional($user)->roles ?? '');
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('Sidebar auth check failed: ' . $e->getMessage());
        }
    @endphp

    <!-- MAIN MENU -->
    <div class="text-xs font-bold text-gray-400 tracking-wider px-2 mb-2">MAIN MENU</div>

    @if($isVendor || in_array($role, ['vendor', 'staff', 'manager', 'admin', 'superadmin']))
    <a href="#" data-module="dashboard" 
      class="sidebar-link mt-1 flex items-center justify-between px-4 py-3 rounded-xl text-gray-700 hover:bg-green-50 hover:text-brand-primary transition-all duration-200 active:scale-[0.99] group">
      <span class="flex items-center gap-3 font-semibold">
        <span class="inline-flex w-9 h-9 rounded-lg bg-emerald-50 items-center justify-center text-emerald-600 group-hover:bg-emerald-100 transition-colors">
          <i class='bx bxs-dashboard text-xl'></i>
        </span>
        Dashboard
      </span>
    </a>
    @endif

    <!-- VENDOR MANAGEMENT -->
    @if($isVendor)
    <div class="text-xs font-bold text-gray-400 tracking-wider px-2 mt-6 mb-2">VENDOR MANAGEMENT</div>
    
    <a href="#" data-module="vendor-quote" 
      class="sidebar-link mt-1 flex items-center justify-between px-4 py-3 rounded-xl text-gray-700 hover:bg-green-50 hover:text-brand-primary transition-all duration-200 active:scale-[0.99] group">
      <span class="flex items-center gap-3 font-semibold">
        <span class="inline-flex w-9 h-9 rounded-lg bg-emerald-50 items-center justify-center text-emerald-600 group-hover:bg-emerald-100 transition-colors">
          <i class='bx bxs-quote-left text-xl'></i>
        </span>
        Vendor Quote
      </span>
    </a>

    <a href="#" data-module="vendor-products" 
      class="sidebar-link mt-1 flex items-center justify-between px-4 py-3 rounded-xl text-gray-700 hover:bg-green-50 hover:text-brand-primary transition-all duration-200 active:scale-[0.99] group">
      <span class="flex items-center gap-3 font-semibold">
        <span class="inline-flex w-9 h-9 rounded-lg bg-emerald-50 items-center justify-center text-emerald-600 group-hover:bg-emerald-100 transition-colors">
          <i class='bx bx-cube-alt text-xl'></i>
        </span>
        Vendor Products
      </span>
    </a>
    @endif

    <!-- TEAM MANAGEMENT -->
    @if(!$isVendor && in_array($role, ['staff', 'manager', 'admin', 'superadmin']))
    <div class="text-xs font-bold text-gray-400 tracking-wider px-2 mt-6 mb-2">TEAM MANAGEMENT</div>

    <!-- Smart Warehousing System -->
    <button class="dropdown-toggle mt-1 w-full flex items-center justify-between px-4 py-3 rounded-xl text-gray-700 hover:bg-green-50 hover:text-brand-primary transition-all duration-200 hover:translate-x-1 active:translate-x-0 active:scale-[0.99] font-semibold group">
      <span class="flex items-center gap-3 text-left">
        <span class="inline-flex w-9 h-9 rounded-lg bg-emerald-50 items-center justify-center text-emerald-600 group-hover:bg-emerald-100 transition-colors shrink-0">
          <i class='bx bxs-store text-xl'></i>
        </span>
        <span class="leading-tight">Smart Warehousing System</span>
      </span>
      <i class='bx bx-chevron-down text-emerald-400 transition-transform duration-300 text-xl'></i>
    </button>
    <div class="submenu hidden mt-1">
      <div class="pl-4 pr-2 py-2 space-y-1 border-l-2 border-gray-100 ml-8">
        <a href="#" data-module="sws-digital-inventory" class="sidebar-link block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-green-50 hover:text-brand-primary transition-all duration-200 hover:translate-x-1 flex items-center gap-2">
          <i class='bx bxs-archive-in'></i> Digital Inventory
        </a>
        <a href="#" data-module="sws-inventory-flow" class="sidebar-link block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-green-50 hover:text-brand-primary transition-all duration-200 hover:translate-x-1 flex items-center gap-2">
          <i class='bx bx-sync'></i> Inventory Flow
        </a>
        <a href="#" data-module="sws-warehouse-management" class="sidebar-link block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-green-50 hover:text-brand-primary transition-all duration-200 hover:translate-x-1 flex items-center gap-2">
          <i class='bx bx-store'></i> Warehouse Management
        </a>
      </div>
    </div>

    <!-- Procurement & Sourcing Management -->
    <button class="dropdown-toggle mt-1 w-full flex items-center justify-between px-4 py-3 rounded-xl text-gray-700 hover:bg-green-50 hover:text-brand-primary transition-all duration-200 hover:translate-x-1 active:translate-x-0 active:scale-[0.99] font-semibold group">
      <span class="flex items-center gap-3 text-left">
        <span class="inline-flex w-9 h-9 rounded-lg bg-emerald-50 items-center justify-center text-emerald-600 group-hover:bg-emerald-100 transition-colors shrink-0">
          <i class='bx bxs-cart text-xl'></i>
        </span>
        <span class="leading-tight">Procurement & Sourcing Management</span>
      </span>
      <i class='bx bx-chevron-down text-emerald-400 transition-transform duration-300 text-xl'></i>
    </button>
    <div class="submenu hidden mt-1">
      <div class="pl-4 pr-2 py-2 space-y-1 border-l-2 border-gray-100 ml-8">
        <a href="#" data-module="psm-purchase-requisition" class="sidebar-link block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-green-50 hover:text-brand-primary transition-all duration-200 hover:translate-x-1 flex items-center gap-2">
          <i class='bx bx-clipboard'></i> Purchase Requisition
        </a>
        @if(in_array($role, ['superadmin', 'admin', 'manager']))
        <a href="#" data-module="psm-budgeting" class="sidebar-link block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-green-50 hover:text-brand-primary transition-all duration-200 hover:translate-x-1 flex items-center gap-2">
          <i class='bx bxs-wallet'></i> Budgeting
        </a>
        @endif
        <a href="#" data-module="psm-purchase" class="sidebar-link block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-green-50 hover:text-brand-primary transition-all duration-200 hover:translate-x-1 flex items-center gap-2">
          <i class='bx bxs-purchase-tag'></i> Purchase Management
        </a>
        <a href="#" data-module="psm-vendor-management" class="sidebar-link block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-green-50 hover:text-brand-primary transition-all duration-200 hover:translate-x-1 flex items-center gap-2">
          <i class='bx bxs-user-detail'></i> Logistics Vendors
        </a>
      </div>
    </div>

    <!-- Project Logistics Tracker -->
    <a href="#" data-module="plt-logistics-projects"
       class="sidebar-link mt-1 flex items-center justify-between px-4 py-3 rounded-xl text-gray-700 hover:bg-green-50 hover:text-brand-primary transition-all duration-200 active:scale-[0.99] group">
      <span class="flex items-center gap-3 font-semibold">
        <span class="inline-flex w-9 h-9 rounded-lg bg-emerald-50 items-center justify-center text-emerald-600 group-hover:bg-emerald-100 transition-colors shrink-0">
          <i class='bx bxs-truck text-xl'></i>
        </span>
        Project Logistics Tracker
      </span>
    </a>

    <!-- Asset Lifecycle & Maintenance System -->
    <button class="dropdown-toggle mt-1 w-full flex items-center justify-between px-4 py-3 rounded-xl text-gray-700 hover:bg-green-50 hover:text-brand-primary transition-all duration-200 hover:translate-x-1 active:translate-x-0 active:scale-[0.99] font-semibold group">
      <span class="flex items-center gap-3 text-left">
        <span class="inline-flex w-9 h-9 rounded-lg bg-emerald-50 items-center justify-center text-emerald-600 group-hover:bg-emerald-100 transition-colors shrink-0">
          <i class='bx bxs-hard-hat text-xl'></i>
        </span>
        <span class="leading-tight">Asset Lifecycle & Maintenance System</span>
      </span>
      <i class='bx bx-chevron-down text-emerald-400 transition-transform duration-300 text-xl'></i>
    </button>
    <div class="submenu hidden mt-1">
      <div class="pl-4 pr-2 py-2 space-y-1 border-l-2 border-gray-100 ml-8">
        <a href="#" data-module="alms-asset-management" class="sidebar-link block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-green-50 hover:text-brand-primary transition-all duration-200 hover:translate-x-1 flex items-center gap-2">
          <i class='bx bxs-archive'></i> Asset Management
        </a>
        <a href="#" data-module="alms-maintenance-management" class="sidebar-link block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-green-50 hover:text-brand-primary transition-all duration-200 hover:translate-x-1 flex items-center gap-2">
          <i class='bx bxs-wrench'></i> Maintenance Management
        </a>
        <a href="#" data-module="alms-maintenance-personnel" class="hidden sidebar-link block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-green-50 hover:text-brand-primary transition-all duration-200 hover:translate-x-1 flex items-center gap-2">
          <i class='bx bxs-user-badge'></i> Maintenance Personnel
        </a>
      </div>
    </div>

    <!-- Document Tracking & Logistics Records -->
    <button class="dropdown-toggle mt-1 w-full flex items-center justify-between px-4 py-3 rounded-xl text-gray-700 hover:bg-green-50 hover:text-brand-primary transition-all duration-200 hover:translate-x-1 active:translate-x-0 active:scale-[0.99] font-semibold group">
      <span class="flex items-center gap-3 text-left">
        <span class="inline-flex w-9 h-9 rounded-lg bg-emerald-50 items-center justify-center text-emerald-600 group-hover:bg-emerald-100 transition-colors shrink-0">
          <i class='bx bxs-map text-xl'></i>
        </span>
        <span class="leading-tight">Document Tracking & Logistics Records</span>
      </span>
      <i class='bx bx-chevron-down text-emerald-400 transition-transform duration-300 text-xl'></i>
    </button>
    <div class="submenu hidden mt-1">
      <div class="pl-4 pr-2 py-2 space-y-1 border-l-2 border-gray-100 ml-8">
        <a href="#" data-module="dtlr-document-tracker" class="sidebar-link block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-green-50 hover:text-brand-primary transition-all duration-200 hover:translate-x-1 flex items-center gap-2">
          <i class='bx bxs-file'></i> Document Tracker
        </a>
        <a href="#" data-module="dtlr-logistics-record" class="sidebar-link block px-3 py-2 rounded-lg text-sm text-gray-600 hover:bg-green-50 hover:text-brand-primary transition-all duration-200 hover:translate-x-1 flex items-center gap-2">
          <i class='bx bxs-package'></i> Logistics Record
        </a>
      </div>
    </div>
    @endif

    <!-- SYSTEM ADMIN -->
    @if($role === 'superadmin')
    <div class="text-xs font-bold text-gray-400 tracking-wider px-2 mt-6 mb-2">SYSTEM ADMIN</div>

    <a href="#" data-module="um-account-management"
       class="sidebar-link mt-1 flex items-center justify-between px-4 py-3 rounded-xl text-gray-700 hover:bg-green-50 hover:text-brand-primary transition-all duration-200 active:scale-[0.99] font-semibold group">
      <span class="flex items-center gap-3 font-semibold">
        <span class="inline-flex w-9 h-9 rounded-lg bg-emerald-50 items-center justify-center text-emerald-600 group-hover:bg-emerald-100 transition-colors shrink-0">
          <i class='bx bxs-user-account text-xl'></i>
        </span>
        User Management
      </span>
    </a>
    @endif

    <!-- Footer -->
    <div class="mt-8 px-2 pb-6">
      <div class="flex items-center gap-2 text-xs font-bold text-emerald-600">
        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
        SYSTEM ONLINE
      </div>
      <div class="text-[11px] text-gray-400 mt-2 leading-snug">
        Microfinace Logistics © 2026<br/>
        Logistics I System
      </div>
    </div>

  </div>
</aside>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarLinks = document.querySelectorAll('.sidebar-link');
        const moduleContent = document.getElementById('module-content');
        const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
        const toggleBtn = document.getElementById('toggle-btn');
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('sidebar-overlay');
        
        // Mobile Sidebar Toggle
        function toggleSidebar() {
            if (sidebar) {
                sidebar.classList.toggle('-translate-x-full');
            }
            if (overlay) {
                overlay.classList.toggle('hidden');
                overlay.classList.toggle('opacity-0');
            }
        }

        if (toggleBtn) {
            toggleBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                toggleSidebar();
            });
        }

        if (overlay) {
            overlay.addEventListener('click', toggleSidebar);
        }

        // Function to close all dropdowns except the one passed as argument
        function closeAllDropdowns(exceptMenu = null) {
            dropdownToggles.forEach(toggle => {
                const menu = toggle.nextElementSibling;
                const icon = toggle.querySelector('.bx-chevron-down');
                
                // If this is the menu we want to keep open, skip it
                if (menu === exceptMenu) return;

                // Close the menu
                if(menu) menu.classList.add('hidden');
                if (icon) {
                    icon.style.transform = 'rotate(0deg)';
                }
            });
        }

        // Function to deactivate all sidebar links
        function deactivateAllLinks() {
            document.querySelectorAll('.sidebar-link').forEach(link => {
                // Remove active styling
                link.classList.remove('bg-brand-primary', 'text-white', 'shadow');
                link.classList.add('text-gray-700', 'hover:bg-green-50', 'hover:text-brand-primary');
                
                // Reset icon background
                const iconBg = link.querySelector('.inline-flex');
                if(iconBg) {
                    iconBg.classList.remove('bg-white/15', 'text-white');
                    iconBg.classList.add('bg-emerald-50', 'text-emerald-600');
                }
            });
        }

        // Function to activate a specific link
        function activateLink(link) {
            // Apply active styling
            link.classList.remove('text-gray-700', 'hover:bg-green-50', 'hover:text-brand-primary');
            link.classList.add('bg-brand-primary', 'text-white', 'shadow');
            
            // Update icon background
            const iconBg = link.querySelector('.inline-flex');
            if(iconBg) {
                iconBg.classList.remove('bg-emerald-50', 'text-emerald-600');
                iconBg.classList.add('bg-white/15', 'text-white');
            }
        }

        // Function to load module content
        function loadModuleContent(module, pushState = true) {
            // Show loading state
            if(moduleContent) {
                moduleContent.innerHTML = '<div class="flex justify-center items-center h-64"><div class="loading loading-spinner loading-lg text-brand-primary"></div></div>';
            }

            const isVendor = {{ Auth::guard('vendor')->check() ? 'true' : 'false' }};
            const moduleBase = isVendor ? '/vendor/module/' : '/module/';

            fetch(`${moduleBase}${module}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                credentials: 'include'
            })
            .then(response => {
                if (!response.ok) {
                    if (response.status === 403) throw new Error('Access denied');
                    throw new Error('Network response was not ok');
                }
                return response.text();
            })
            .then(html => {
                const tempContainer = document.createElement('div');
                tempContainer.innerHTML = html;

                const scriptTags = tempContainer.querySelectorAll('script');
                scriptTags.forEach(tag => tag.parentNode && tag.parentNode.removeChild(tag));

                if(moduleContent) moduleContent.innerHTML = tempContainer.innerHTML;

                // Remove previously loaded scripts for this module
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

                if (pushState) {
                    const url = isVendor ? `/vendor/home?module=${module}` : `/home?module=${module}`;
                    history.pushState({ module: module }, '', url);
                }

                // Update active state
                deactivateAllLinks();
                let activeLinkFound = false;
                
                sidebarLinks.forEach(link => {
                    if (link.getAttribute('data-module') === module) {
                        activateLink(link);
                        activeLinkFound = true;
                        
                        // Handle parent dropdown
                        const parentDropdown = link.closest('.submenu');
                        if (parentDropdown) {
                            closeAllDropdowns(parentDropdown);
                            parentDropdown.classList.remove('hidden');
                            const toggle = parentDropdown.previousElementSibling;
                            if(toggle) {
                                const icon = toggle.querySelector('.bx-chevron-down');
                                if(icon) icon.style.transform = 'rotate(180deg)';
                            }
                        } else {
                            closeAllDropdowns();
                        }
                    }
                });
                
                if (!activeLinkFound) closeAllDropdowns();
                
                // On mobile, close sidebar after clicking a link
                if(window.innerWidth < 1024) {
                   if(sidebar && !sidebar.classList.contains('-translate-x-full')) {
                       toggleSidebar();
                   }
                }

            })
            .catch(error => {
                console.error('Error loading module:', error);
                if(moduleContent) {
                    moduleContent.innerHTML = `
                        <div class="alert alert-error">
                            <i class='bx bx-error-circle text-2xl'></i>
                            <span>Error loading content. Please try again.</span>
                        </div>`;
                }
            });
        }

        // Handle sidebar link clicks
        sidebarLinks.forEach(link => {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const module = this.getAttribute('data-module');
                if(module) loadModuleContent(module);
            });
        });

        // Handle dropdown toggles
        dropdownToggles.forEach(toggle => {
            toggle.addEventListener('click', function() {
                const menu = this.nextElementSibling;
                const icon = this.querySelector('.bx-chevron-down');
                const isHidden = menu.classList.contains('hidden');

                // Close all other dropdowns
                closeAllDropdowns();

                // Toggle current
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
                const urlParams = new URLSearchParams(window.location.search);
                const module = urlParams.get('module') || 'dashboard';
                loadModuleContent(module, false);
            }
        });

        // Initial load
        const urlParams = new URLSearchParams(window.location.search);
        const module = urlParams.get('module') || 'dashboard';
        loadModuleContent(module, false);
    });
</script>
