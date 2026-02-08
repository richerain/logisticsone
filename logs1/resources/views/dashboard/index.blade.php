<!-- resources/views/dashboard/index.blade.php -->
@if(Auth::guard('vendor')->check())
    <div class="mb-6 flex items-center justify-between gap-4">
        <div class="flex items-center">
            <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bxs-dashboard'></i>Vendor Dashboard</h2>
        </div>
        <div class="text-right">
            <span class="text-md text-gray-600">Welcome back, {{ optional(Auth::guard('vendor')->user())->ven_contact_person ?? optional(Auth::guard('vendor')->user())->ven_company_name ?? 'Vendor' }} - Vendor</span>
        </div>
    </div>

    <!-- Vendor Statistics Section start -->
    <div class="mb-3 bg-white p-5 rounded-lg shadow-xl overflow-visible">
        <div class="flex items-center mb-2 space-x-2 text-gray-700">
            <h2 class="text-lg font-semibold"><i class='bx bx-fw bx-stats'></i>Overview Metrics</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
            <!-- Stats 01: Active Quotes -->
            <div class="stat card bg-white shadow-xl hover:shadow-2xl transition-shadow rounded-lg border-l-4 border-t-0 border-r-0 border-b-0 border-blue-700">
                <div class="stat-title flex items-center justify-between">
                    <span class="font-semibold text-blue-900">Active Quotes</span>
                    <span class="flex items-center justify-center w-10 h-10 rounded-full bg-white text-blue-900 shadow-sm">
                        <i class="bx bxs-file-find text-xl" aria-hidden="true"></i>
                    </span>
                </div>
                <div class="stat-value text-blue-900">12</div>
                <div class="stat-desc text-blue-700">3 Pending Review</div>
            </div>

            <!-- Stats 02: My Products -->
            <div class="stat card bg-white shadow-xl hover:shadow-2xl transition-shadow rounded-lg border-l-4 border-t-0 border-r-0 border-b-0 border-green-700">
                <div class="stat-title flex items-center justify-between">
                    <span class="font-semibold text-green-900">My Products</span>
                    <span class="flex items-center justify-center w-10 h-10 rounded-full bg-white text-green-900 shadow-sm">
                        <i class="bx bxs-package text-xl" aria-hidden="true"></i>
                    </span>
                </div>
                <div class="stat-value text-green-900">45</div>
                <div class="stat-desc text-green-700">Updated Recently</div>
            </div>

            <!-- Stats 03: Purchase Orders -->
            <div class="stat card bg-white shadow-xl hover:shadow-2xl transition-shadow rounded-lg border-l-4 border-t-0 border-r-0 border-b-0 border-purple-700">
                <div class="stat-title flex items-center justify-between">
                    <span class="font-semibold text-purple-900">Purchase Orders</span>
                    <span class="flex items-center justify-center w-10 h-10 rounded-full bg-white text-purple-900 shadow-sm">
                        <i class="bx bxs-purchase-tag text-xl" aria-hidden="true"></i>
                    </span>
                </div>
                <div class="stat-value text-purple-900">8</div>
                <div class="stat-desc text-purple-700">2 New Orders</div>
            </div>
        </div>
    </div>
    <!-- Vendor Statistics Section end -->

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        <!-- Recent RFQs -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Recent RFQs</h3>
            <div class="space-y-4">
                <div class="flex items-center justify-between p-3 bg-white rounded-lg hover:bg-gray-100 transition-colors">
                    <div>
                        <h4 class="font-medium text-gray-900">Office Furniture Supply</h4>
                        <p class="text-sm text-gray-500">ID: RFQ-2025-001</p>
                    </div>
                    <span class="px-3 py-1 text-xs font-semibold text-blue-800 bg-white rounded-full">Open</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-white rounded-lg hover:bg-gray-100 transition-colors">
                    <div>
                        <h4 class="font-medium text-gray-900">Laptop Procurement</h4>
                        <p class="text-sm text-gray-500">ID: RFQ-2025-002</p>
                    </div>
                    <span class="px-3 py-1 text-xs font-semibold text-green-800 bg-white rounded-full">Awarded</span>
                </div>
                <div class="flex items-center justify-between p-3 bg-white rounded-lg hover:bg-gray-100 transition-colors">
                    <div>
                        <h4 class="font-medium text-gray-900">Network Equipment</h4>
                        <p class="text-sm text-gray-500">ID: RFQ-2025-003</p>
                    </div>
                    <span class="px-3 py-1 text-xs font-semibold text-yellow-800 bg-white rounded-full">Pending</span>
                </div>
            </div>
        </div>

        <!-- Notifications -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h3 class="text-lg font-semibold text-gray-800 mb-4 border-b pb-2">Notifications</h3>
            <div class="space-y-4">
                <div class="flex items-start p-3 bg-white rounded-lg">
                    <i class='bx bx-info-circle text-blue-500 text-xl mr-3 mt-0.5'></i>
                    <div>
                        <p class="text-sm text-gray-800">Your quote for <strong>Office Chairs</strong> has been viewed.</p>
                        <span class="text-xs text-gray-500">2 hours ago</span>
                    </div>
                </div>
                <div class="flex items-start p-3 bg-white rounded-lg">
                    <i class='bx bx-check-circle text-green-500 text-xl mr-3 mt-0.5'></i>
                    <div>
                        <p class="text-sm text-gray-800">New Purchase Order received #PO-4821.</p>
                        <span class="text-xs text-gray-500">Yesterday</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@else
    <div class="mb-6 flex items-center justify-between gap-4">
        <div class="flex items-center">
            <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bxs-dashboard'></i>Dashboard</h2>
        </div>
        <div class="text-right">
            <span class="text-md text-gray-600">Welcome back, {{ Auth::guard('sws')->user()?->firstname }} - {{ ucfirst(Auth::guard('sws')->user()?->roles ?? 'User') }}</span>
        </div>
    </div>

    <!-- announcement board section start -->
    <div class="bg-white shadow-lg rounded-lg p-5 mb-3 overflow-visible min-h-[200px] flex flex-col">
        <div class="flex items-center justify-between mb-4 text-gray-700">
            <div class="flex items-center space-x-2">
                <h2 class="text-lg font-semibold"><i class='bx bx-fw bxs-megaphone'></i>Announcement Board</h2>
            </div>
            @if(in_array(strtolower(Auth::guard('sws')->user()?->roles ?? ''), ['superadmin', 'admin']))
            <button onclick="openAnnouncementModal()" class="btn btn-sm btn-primary text-white flex items-center gap-1">
                <i class='bx bx-plus'></i> Create Announcement
            </button>
            @endif
        </div>

        <div class="flex-1">
            <div id="announcementGrid" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
                <!-- Cards will be populated here -->
                <div class="col-span-full text-center py-10">
                    <span class="loading loading-spinner loading-lg text-primary"></span>
                </div>
            </div>
            <div id="announcementPager" class="flex items-center justify-between mt-3 hidden">
                <div id="announcementPagerInfo" class="text-sm text-gray-600"></div>
                <div class="join">
                    <button class="btn btn-sm join-item" id="announcementPrevBtn" disabled>Prev</button>
                    <span class="btn btn-sm join-item bg-white" id="announcementPageDisplay">1 / 1</span>
                    <button class="btn btn-sm join-item" id="announcementNextBtn" disabled>Next</button>
                </div>
            </div>
        </div>
    </div>
    <!-- announcement board section end -->

    <!-- Create Announcement Modal -->
    <dialog id="announcement_modal" class="modal">
      <div class="modal-box w-11/12 max-w-2xl">
        <h3 class="font-bold text-lg mb-4">Create Announcement</h3>
        <form id="createAnnouncementForm" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="form-control w-full mb-3">
                <label class="label"><span class="label-text">Title</span></label>
                <input type="text" name="title" class="input input-bordered w-full" required />
            </div>
            <div class="form-control w-full mb-3">
                <label class="label"><span class="label-text">Description</span></label>
                <textarea name="desc" class="textarea textarea-bordered h-24" required></textarea>
            </div>
            <div class="form-control w-full mb-3">
                <label class="label"><span class="label-text">Announcement Image</span></label>
                <input type="file" name="announcement_image" class="file-input file-input-bordered w-full" accept="image/*" />
                <label class="label"><span class="label-text-alt text-gray-500">Stored in public/images/announcement</span></label>
            </div>
            <div class="modal-action">
                <button type="button" class="btn" onclick="closeAnnouncementModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Create</button>
            </div>
        </form>
      </div>
      <form method="dialog" class="modal-backdrop">
        <button type="button" onclick="closeAnnouncementModal()">close</button>
      </form>
    </dialog>

    <!-- View Announcement Modal -->
    <dialog id="view_announcement_modal" class="modal">
      <div class="modal-box w-11/12 max-w-2xl">
        <div class="mb-4">
             <img id="view_announcement_image" src="" alt="" class="w-full h-64 object-cover rounded-lg hidden mb-4" />
             <h3 class="font-bold text-lg mb-2" id="view_announcement_title"></h3>
             <div class="max-h-60 overflow-y-auto pr-2">
                 <p class="text-sm text-gray-600 break-words" id="view_announcement_desc"></p>
             </div>
             <div class="mt-4 text-xs text-gray-500" id="view_announcement_date"></div>
        </div>
        <div class="modal-action">
            <button type="button" class="btn" onclick="closeViewAnnouncementModal()">Close</button>
        </div>
      </div>
      <form method="dialog" class="modal-backdrop">
        <button type="button" onclick="closeViewAnnouncementModal()">close</button>
      </form>
    </dialog>

    <script>
    (function() {
        const initDashboard = function() {
            let currentPage = 1;
            let lastPage = 1;
            let currentAnnouncements = [];
            const fetchUrl = "{{ route('dashboard.announcements.fetch') }}";
            const storeUrl = "{{ route('dashboard.announcements.store') }}";

            function fetchAnnouncements(page = 1) {
                const grid = document.getElementById('announcementGrid');
                if (!grid) return;
                
                grid.innerHTML = '<div class="col-span-full text-center py-10"><span class="loading loading-spinner loading-lg text-primary"></span></div>';
                
                fetch(`${fetchUrl}?page=${page}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                    .then(response => {
                        if (!response.ok) {
                            throw new Error(`HTTP error! status: ${response.status}`);
                        }
                        return response.text().then(text => {
                            try {
                                return JSON.parse(text);
                            } catch (e) {
                                console.error('JSON Parse Error:', text);
                                throw new Error('Invalid JSON response from server');
                            }
                        });
                    })
                    .then(data => {
                        if(data.success) {
                            currentAnnouncements = data.data;
                            renderAnnouncements(data.data);
                            updatePagination(data.pagination);
                            currentPage = data.pagination.current_page;
                            lastPage = data.pagination.last_page;
                        } else {
                            if(grid) grid.innerHTML = `<div class="col-span-full text-center text-red-500">
                                <i class='bx bx-error-circle text-4xl mb-2'></i>
                                <p>${data.message || 'Failed to load announcements'}</p>
                            </div>`;
                        }
                    })
                    .catch(error => {
                        console.error('Fetch Error:', error);
                        if(grid) grid.innerHTML = `<div class="col-span-full text-center text-red-500">
                            <i class='bx bx-error text-4xl mb-2'></i>
                            <p>Error loading announcements: ${error.message}</p>
                        </div>`;
                    });
            }

            function renderAnnouncements(announcements) {
                const grid = document.getElementById('announcementGrid');
                if (!grid) return;
                
                grid.innerHTML = '';
                
                if (!Array.isArray(announcements) || announcements.length === 0) {
                    grid.innerHTML = `
                        <div class="col-span-full flex flex-col items-center justify-center py-10 text-gray-500">
                            <i class='bx bx-info-circle text-5xl mb-3 text-gray-300'></i>
                            <p class="text-lg font-medium">No Announcement yet!</p>
                        </div>
                    `;
                    return;
                }

                const baseUrl = "{{ asset('') }}";

                announcements.forEach((item, index) => {
                    let dateStr = 'Unknown Date';
                    try {
                        if(item.created_date) {
                            dateStr = new Date(item.created_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                        }
                    } catch(e) { console.error('Date parsing error', e); }

                    let imgUrl = "{{ asset('images/announcement.png') }}";
                    if (item.announcement_image) {
                         // Remove leading slash if present to avoid double slashes with baseUrl
                         const cleanPath = item.announcement_image.startsWith('/') ? item.announcement_image.substring(1) : item.announcement_image;
                         imgUrl = baseUrl + cleanPath;
                    }
                    
                    const card = `
                        <article class="bg-gray-50 rounded-lg shadow-md overflow-hidden flex flex-col h-full border border-gray-200">
                            <img src="${imgUrl}" alt="${item.title}" class="h-36 w-full object-cover" loading="lazy" onError="this.src='{{ asset('images/announcement.png') }}'" />
                            <div class="p-4 flex flex-col flex-1">
                                <h3 class="font-semibold text-gray-800 truncate" title="${item.title}">${item.title}</h3>
                                <p class="text-sm text-gray-600 mt-2 h-14 overflow-hidden line-clamp-3" title="${item.desc}">${item.desc}</p>
                                <div class="mt-auto pt-3 flex items-center justify-between">
                                    <div class="text-xs text-gray-500">Posted: ${dateStr}</div>
                                    <button class="btn btn-xs btn-outline btn-primary" onclick="window.openViewAnnouncementModal(${index})">Read More</button>
                                </div>
                            </div>
                        </article>
                    `;
                    grid.insertAdjacentHTML('beforeend', card);
                });
            }

            function updatePagination(pagination) {
                const pager = document.getElementById('announcementPager');
                if (!pager) return;

                if (pagination.total === 0) {
                    pager.classList.add('hidden');
                    return;
                }
                pager.classList.remove('hidden');
                
                const info = document.getElementById('announcementPagerInfo');
                if(info) info.textContent = `Showing ${pagination.current_page} of ${pagination.last_page} pages`;
                
                const display = document.getElementById('announcementPageDisplay');
                if(display) display.textContent = `${pagination.current_page} / ${pagination.last_page}`;
                
                const prev = document.getElementById('announcementPrevBtn');
                if(prev) prev.disabled = pagination.current_page <= 1;
                
                const next = document.getElementById('announcementNextBtn');
                if(next) next.disabled = pagination.current_page >= pagination.last_page;
            }

            window.openAnnouncementModal = function() {
                const modal = document.getElementById('announcement_modal');
                if(modal) modal.showModal();
            }

            window.closeAnnouncementModal = function() {
                const modal = document.getElementById('announcement_modal');
                if(modal) modal.close();
            }
            
            window.openViewAnnouncementModal = function(index) {
                const item = currentAnnouncements[index];
                if(!item) return;

                const modal = document.getElementById('view_announcement_modal');
                const title = document.getElementById('view_announcement_title');
                const desc = document.getElementById('view_announcement_desc');
                const date = document.getElementById('view_announcement_date');
                const img = document.getElementById('view_announcement_image');

                if(title) title.textContent = item.title;
                if(desc) desc.textContent = item.desc;
                
                if(date) {
                    let dateStr = 'Unknown Date';
                    try {
                        if(item.created_date) {
                            dateStr = new Date(item.created_date).toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' });
                        }
                    } catch(e) {}
                    date.textContent = 'Posted: ' + dateStr;
                }

                if(img) {
                    const baseUrl = "{{ asset('') }}";
                    if (item.announcement_image) {
                         const cleanPath = item.announcement_image.startsWith('/') ? item.announcement_image.substring(1) : item.announcement_image;
                         img.src = baseUrl + cleanPath;
                         img.classList.remove('hidden');
                    } else {
                        img.src = "";
                        img.classList.add('hidden');
                    }
                }

                if(modal) modal.showModal();
            }

            window.closeViewAnnouncementModal = function() {
                const modal = document.getElementById('view_announcement_modal');
                if(modal) modal.close();
            }
            
            const prevBtn = document.getElementById('announcementPrevBtn');
            if (prevBtn) {
                // Remove old listeners to avoid duplicates if re-initialized
                const newPrev = prevBtn.cloneNode(true);
                prevBtn.parentNode.replaceChild(newPrev, prevBtn);
                newPrev.addEventListener('click', () => {
                    if(currentPage > 1) fetchAnnouncements(currentPage - 1);
                });
            }
            
            const nextBtn = document.getElementById('announcementNextBtn');
            if (nextBtn) {
                const newNext = nextBtn.cloneNode(true);
                nextBtn.parentNode.replaceChild(newNext, nextBtn);
                newNext.addEventListener('click', () => {
                    if(currentPage < lastPage) fetchAnnouncements(currentPage + 1);
                });
            }
            
            const createForm = document.getElementById('createAnnouncementForm');
            if (createForm) {
                 // Clone to remove old listeners
                const newForm = createForm.cloneNode(true);
                createForm.parentNode.replaceChild(newForm, createForm);
                
                newForm.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const formData = new FormData(this);
                    const submitBtn = this.querySelector('button[type="submit"]');
                    const originalText = submitBtn.textContent;
                    submitBtn.disabled = true;
                    submitBtn.textContent = 'Creating...';

                    fetch(storeUrl, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}",
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if(data.success) {
                            closeAnnouncementModal();
                            this.reset();
                            fetchAnnouncements(1);
                        } else {
                            alert(data.message || 'Error creating announcement');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred');
                    })
                    .finally(() => {
                        submitBtn.disabled = false;
                        submitBtn.textContent = originalText;
                    });
                });
            }

            // Initial fetch
            fetchAnnouncements();
        };

        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initDashboard);
        } else {
            initDashboard();
        }
    })();
    </script>
    
    <!-- Statistics Section start -->
    <div class="mb-3 bg-white p-5 rounded-lg shadow-xl overflow-visible">
        <div class="flex items-center mb-2 space-x-2 text-gray-700">
            <h2 class="text-lg font-semibold"><i class='bx bx-fw bx-stats'></i>System Overview Metrics</h2>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-5">
            <!-- Stats 01: Total Purchase Orders -->
            <div class="stat card bg-white shadow-xl hover:shadow-2xl transition-shadow rounded-lg border-l-4 border-t-0 border-r-0 border-b-0 border-blue-700">
                <div class="stat-title flex items-center justify-between">
                    <span class="font-semibold text-blue-900">Purchase Orders</span>
                    <span class="flex items-center justify-center w-10 h-10 rounded-full bg-white text-blue-900 shadow-sm">
                        <i class="bx bxs-purchase-tag text-xl" aria-hidden="true"></i>
                    </span>
                </div>
                <div class="stat-value text-blue-900">47</div>
                <div class="stat-desc text-blue-700">12 Pending Approval</div>
            </div>

            <!-- Stats 02: Active Vendors -->
            <div class="stat card bg-white shadow-xl hover:shadow-2xl transition-shadow rounded-lg border-l-4 border-t-0 border-r-0 border-b-0 border-green-700">
                <div class="stat-title flex items-center justify-between">
                    <span class="font-semibold text-green-900">Active Vendors</span>
                    <span class="flex items-center justify-center w-10 h-10 rounded-full bg-white text-green-900 shadow-sm">
                        <i class="bx bxs-user-detail text-xl" aria-hidden="true"></i>
                    </span>
                </div>
                <div class="stat-value text-green-900">28</div>
                <div class="stat-desc text-green-700">5 New This Month</div>
            </div>

            <!-- Stats 03: Warehouse Inventory -->
            <div class="stat card bg-white shadow-xl hover:shadow-2xl transition-shadow rounded-lg border-l-4 border-t-0 border-r-0 border-b-0 border-purple-700">
                <div class="stat-title flex items-center justify-between">
                    <span class="font-semibold text-purple-900">Total Inventory</span>
                    <span class="flex items-center justify-center w-10 h-10 rounded-full bg-white text-purple-900 shadow-sm">
                        <i class="bx bxs-archive-in text-xl" aria-hidden="true"></i>
                    </span>
                </div>
                <div class="stat-value text-purple-900">1,247</div>
                <div class="stat-desc text-purple-700">45 Low Stock Items</div>
            </div>

            <!-- Stats 04: Active Projects -->
            <div class="stat card bg-white shadow-xl hover:shadow-2xl transition-shadow rounded-lg border-l-4 border-t-0 border-r-0 border-b-0 border-orange-700">
                <div class="stat-title flex items-center justify-between">
                    <span class="font-semibold text-orange-900">Active Projects</span>
                    <span class="flex items-center justify-center w-10 h-10 rounded-full bg-white text-orange-900 shadow-sm">
                        <i class="bx bxs-package text-xl" aria-hidden="true"></i>
                    </span>
                </div>
                <div class="stat-value text-orange-900">15</div>
                <div class="stat-desc text-orange-700">5 Behind Schedule</div>
            </div>

            <!-- Stats 05: Total Assets -->
            <div class="stat card bg-white shadow-xl hover:shadow-2xl transition-shadow rounded-lg border-l-4 border-t-0 border-r-0 border-b-0 border-cyan-700">
                <div class="stat-title flex items-center justify-between">
                    <span class="font-semibold text-cyan-900">Managed Assets</span>
                    <span class="flex items-center justify-center w-10 h-10 rounded-full bg-white text-cyan-900 shadow-sm">
                        <i class="bx bxs-archive text-xl" aria-hidden="true"></i>
                    </span>
                </div>
                <div class="stat-value text-cyan-900">284</div>
                <div class="stat-desc text-cyan-700">18 Under Maintenance</div>
            </div>

            <!-- Stats 06: Pending Documents -->
            <div class="stat card bg-white shadow-xl hover:shadow-2xl transition-shadow rounded-lg border-l-4 border-t-0 border-r-0 border-b-0 border-red-700">
                <div class="stat-title flex items-center justify-between">
                    <span class="font-semibold text-red-900">Pending Docs</span>
                    <span class="flex items-center justify-center w-10 h-10 rounded-full bg-white text-red-900 shadow-sm">
                        <i class="bx bxs-file text-xl" aria-hidden="true"></i>
                    </span>
                </div>
                <div class="stat-value text-red-900">387</div>
                <div class="stat-desc text-red-700">Require Attention</div>
            </div>
        </div>

        <!-- Quick Module Status Overview -->
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4">
            <!-- PSM Status -->
            <div class="bg-white rounded-lg p-3 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-gray-700">PSM</span>
                    <span class="flex items-center text-xs px-2 py-1 rounded-full bg-white text-green-800">
                        <i class='bx bxs-circle text-xs mr-1'></i>Active
                    </span>
                </div>
                <p class="text-xs text-gray-600 mt-1">Procurement & Sourcing</p>
                <div class="mt-2 flex justify-between text-xs">
                    <span>23 Active PO</span>
                    <span class="text-orange-600">5 Pending</span>
                </div>
            </div>

            <!-- SWS Status -->
            <div class="bg-white rounded-lg p-3 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-gray-700">SWS</span>
                    <span class="flex items-center text-xs px-2 py-1 rounded-full bg-white text-green-800">
                        <i class='bx bxs-circle text-xs mr-1'></i>Active
                    </span>
                </div>
                <p class="text-xs text-gray-600 mt-1">Smart Warehousing</p>
                <div class="mt-2 flex justify-between text-xs">
                    <span>1.2K Items</span>
                    <span class="text-red-600">45 Low</span>
                </div>
            </div>

            <!-- PLT Status -->
            <div class="bg-white rounded-lg p-3 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-gray-700">PLT</span>
                    <span class="flex items-center text-xs px-2 py-1 rounded-full bg-white text-green-800">
                        <i class='bx bxs-circle text-xs mr-1'></i>Active
                    </span>
                </div>
                <p class="text-xs text-gray-600 mt-1">Project Logistics</p>
                <div class="mt-2 flex justify-between text-xs">
                    <span>15 Projects</span>
                    <span class="text-yellow-600">5 Ongoing</span>
                </div>
            </div>

            <!-- ALMS Status -->
            <div class="bg-white rounded-lg p-3 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-gray-700">ALMS</span>
                    <span class="flex items-center text-xs px-2 py-1 rounded-full bg-white text-green-800">
                        <i class='bx bxs-circle text-xs mr-1'></i>Active
                    </span>
                </div>
                <p class="text-xs text-gray-600 mt-1">Asset Management</p>
                <div class="mt-2 flex justify-between text-xs">
                    <span>284 Assets</span>
                    <span class="text-blue-600">18 Maintenance</span>
                </div>
            </div>

            <!-- DTLR Status -->
            <div class="bg-white rounded-lg p-3 shadow-sm">
                <div class="flex items-center justify-between">
                    <span class="text-sm font-semibold text-gray-700">DTLR</span>
                    <span class="flex items-center text-xs px-2 py-1 rounded-full bg-white text-green-800">
                        <i class='bx bxs-circle text-xs mr-1'></i>Active
                    </span>
                </div>
                <p class="text-xs text-gray-600 mt-1">Document Tracking</p>
                <div class="mt-2 flex justify-between text-xs">
                    <span>1.8K Docs</span>
                    <span class="text-red-600">387 Pending</span>
                </div>
            </div>
        </div>
    </div>
    <!-- Statistics Section end -->

    <!-- statistics charts section start -->
    <div class="bg-white rounded-lg p-5 shadow-xl overflow-visible mb-3">
        <h2 class="text-gray-700 text-lg font-semibold mb-4"><i class='bx bx-fw bxs-pie-chart-alt-2'></i>Module Performance Charts</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <!-- Chart 1: Purchase Order Status (PSM) -->
            <div class="chart-card bg-white p-4 rounded-lg shadow">
                <h3 class="font-bold text-gray-800 flex items-center">
                    <i class='bx bxs-purchase-tag mr-2 text-blue-600'></i>
                    PO Status Distribution
                </h3>
                <div class="chart-placeholder h-32 bg-white rounded-lg p-1">
                    <canvas id="poStatusChart" style="width:100%;height:160px;"></canvas>
                </div>
            </div>

            <!-- Chart 2: Inventory Categories (SWS) -->
            <div class="chart-card bg-white p-4 rounded-lg shadow">
                <h3 class="font-bold text-gray-800 flex items-center">
                    <i class='bx bxs-archive-in mr-2 text-purple-600'></i>
                    Inventory by Category
                </h3>
                <div class="chart-placeholder h-32 bg-white rounded-lg p-1">
                    <canvas id="inventoryChart" style="width:100%;height:160px;"></canvas>
                </div>
            </div>

            <!-- Chart 3: Project Progress (PLT) -->
            <div class="chart-card bg-white p-4 rounded-lg shadow">
                <h3 class="font-bold text-gray-800 flex items-center">
                    <i class='bx bxs-package mr-2 text-orange-600'></i>
                    Project Progress
                </h3>
                <div class="chart-placeholder h-32 bg-white rounded-lg p-1">
                    <canvas id="projectProgressChart" style="width:100%;height:160px;"></canvas>
                </div>
            </div>

            <!-- Chart 4: Asset Status (ALMS) -->
            <div class="chart-card bg-white p-4 rounded-lg shadow">
                <h3 class="font-bold text-gray-800 flex items-center">
                    <i class='bx bxs-archive mr-2 text-cyan-600'></i>
                    Asset Status Overview
                </h3>
                <div class="chart-placeholder h-32 bg-white rounded-lg p-1">
                    <canvas id="assetStatusChart" style="width:100%;height:160px;"></canvas>
                </div>
            </div>

            <!-- Chart 5: Document Status (DTLR) -->
            <div class="chart-card bg-white p-4 rounded-lg shadow">
                <h3 class="font-bold text-gray-800 flex items-center">
                    <i class='bx bxs-file mr-2 text-red-600'></i>
                    Document Status
                </h3>
                <div class="chart-placeholder h-32 bg-white rounded-lg p-1">
                    <canvas id="documentStatusChart" style="width:100%;height:160px;"></canvas>
                </div>
            </div>

            <!-- Chart 6: Vendor Performance (PSM) -->
            <div class="chart-card bg-white p-4 rounded-lg shadow">
                <h3 class="font-bold text-gray-800 flex items-center">
                    <i class='bx bxs-user-detail mr-2 text-green-600'></i>
                    Vendor Performance
                </h3>
                <div class="chart-placeholder h-32 bg-white rounded-lg p-1">
                    <canvas id="vendorPerformanceChart" style="width:100%;height:160px;"></canvas>
                </div>
            </div>
        </div>
    </div>
    <!-- statistics charts section end -->
@endif

<script>
// Module Charts Data Initialization start
document.addEventListener('DOMContentLoaded', function () {
    initializeModuleCharts();
});

function initializeModuleCharts() {
    // Chart 1: Purchase Order Status (PSM)
    const poCtx = document.getElementById('poStatusChart');
    if (poCtx) {
        new Chart(poCtx, {
            type: 'doughnut',
            data: {
                labels: ['Approved', 'Pending', 'Rejected', 'Draft'],
                datasets: [{
                    data: [25, 12, 5, 5],
                    backgroundColor: ['#10B981', '#F59E0B', '#EF4444', '#6B7280'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        display: true,
                        position: 'bottom',
                        labels: { boxWidth: 12, font: { size: 10 } }
                    }
                }
            }
        });
    }

    // Chart 2: Inventory Categories (SWS)
    const inventoryCtx = document.getElementById('inventoryChart');
    if (inventoryCtx) {
        new Chart(inventoryCtx, {
            type: 'pie',
            data: {
                labels: ['Furniture', 'Electronics', 'Office Supplies', 'Equipment', 'Raw Materials'],
                datasets: [{
                    data: [350, 280, 420, 150, 47],
                    backgroundColor: ['#8B5CF6', '#3B82F6', '#10B981', '#F59E0B', '#EF4444'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        display: true,
                        position: 'bottom',
                        labels: { boxWidth: 12, font: { size: 9 } }
                    }
                }
            }
        });
    }

    // Chart 3: Project Progress (PLT)
    const projectCtx = document.getElementById('projectProgressChart');
    if (projectCtx) {
        new Chart(projectCtx, {
            type: 'bar',
            data: {
                labels: ['Warehouse Relocation', 'System Implementation', 'Fleet Upgrade', 'Process Optimization', 'Training Program'],
                datasets: [{
                    label: 'Completion %',
                    data: [65, 90, 45, 75, 30],
                    backgroundColor: '#F59E0B',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        beginAtZero: true,
                        max: 100,
                        ticks: { callback: function(value) { return value + '%'; } }
                    },
                    x: { ticks: { font: { size: 8 } } }
                }
            }
        });
    }

    // Chart 4: Asset Status (ALMS)
    const assetCtx = document.getElementById('assetStatusChart');
    if (assetCtx) {
        new Chart(assetCtx, {
            type: 'doughnut',
            data: {
                labels: ['Operational', 'Under Maintenance', 'Out of Service', 'In Storage'],
                datasets: [{
                    data: [245, 18, 21, 15],
                    backgroundColor: ['#06B6D4', '#F59E0B', '#EF4444', '#6B7280'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        display: true,
                        position: 'bottom',
                        labels: { boxWidth: 12, font: { size: 10 } }
                    }
                }
            }
        });
    }

    // Chart 5: Document Status (DTLR)
    const documentCtx = document.getElementById('documentStatusChart');
    if (documentCtx) {
        new Chart(documentCtx, {
            type: 'pie',
            data: {
                labels: ['Approved', 'Pending Review', 'Rejected', 'Archived'],
                datasets: [{
                    data: [1245, 387, 210, 125],
                    backgroundColor: ['#10B981', '#F59E0B', '#EF4444', '#6B7280'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { 
                        display: true,
                        position: 'bottom',
                        labels: { boxWidth: 12, font: { size: 9 } }
                    }
                }
            }
        });
    }

    // Chart 6: Vendor Performance (PSM)
    const vendorCtx = document.getElementById('vendorPerformanceChart');
    if (vendorCtx) {
        new Chart(vendorCtx, {
            type: 'line',
            data: {
                labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun'],
                datasets: [{
                    label: 'On-Time Delivery Rate',
                    data: [85, 88, 92, 90, 87, 94],
                    borderColor: '#10B981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: '#10B981'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    y: { 
                        beginAtZero: false,
                        min: 80,
                        max: 100,
                        ticks: { callback: function(value) { return value + '%'; } }
                    }
                }
            }
        });
    }
}// Module Charts Data Initialization end
</script>