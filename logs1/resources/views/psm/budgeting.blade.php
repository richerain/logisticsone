<!-- views/psm/budgeting.blade.php -->
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bxs-wallet'></i>Budgeting</h2>
    </div>
    <div class="flex items-center gap-4">
        <span class="text-md text-gray-600">Procurement & Sourcing Management</span>
    </div>
</div>

<!-- Consolidated Budget Request Section -->
<div class="bg-white rounded-lg shadow-lg p-6 mb-6">
    <div class="">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div class="flex items-center gap-4 w-full justify-between">
                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <i class='bx bx-git-pull-request text-blue-600'></i>
                    Consolidated Budget Request
                </h3>
                <button onclick="openBudgetStatusModal()" class="btn btn-primary btn-sm flex items-center gap-2 shadow-lg hover:shadow-xl transition-all hover:-translate-y-0.5">
                    <i class='bx bx-info-circle'></i>
                    Request Budget Status
                </button>
            </div>
        </div>

        <!-- Filters (Matching Requisition Records design) -->
        <div class="bg-gray-50 rounded-xl p-4 mb-6 border border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <div class="md:col-span-6 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class='bx bx-search text-gray-400 text-xl'></i>
                    </div>
                    <input type="text" id="consolidatedSearchInput" placeholder="Search by ID, requester, or department..." 
                        class="block w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm transition-all text-sm">
                </div>
                <div class="md:col-span-6">
                    <select id="consolidatedDeptFilter" class="block w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm transition-all text-sm">
                        <option value="">All Departments</option>
                        <option value="Human Resource Department">Human Resource Department</option>
                        <option value="Core Transaction Office">Core Transaction Office</option>
                        <option value="Logistics Office">Logistics Office</option>
                        <option value="Administrative Office">Administrative Office</option>
                        <option value="Financial Department">Financial Department</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Table Container -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-800 font-bold text-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Requisition ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Items</th>
                            <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Chosen Vendor</th>
                            <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Total Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Requester / Dept</th>
                            <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Note</th>
                            <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Budget Approval</th>
                        </tr>
                    </thead>
                    <tbody id="consolidatedTableBody" class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex justify-center items-center py-4">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mr-3"></div>
                                    Loading approved requisitions...
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Calculation Area (Footer) -->
        <div class="bg-gray-50 border-x border-b border-gray-200 rounded-b-lg p-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-4">
                <div class="bg-blue-100 p-3 rounded-xl text-blue-600">
                    <i class='bx bx-calculator text-2xl'></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Consolidated Amount</p>
                    <h4 class="text-2xl font-black text-blue-600 leading-none" id="consolidatedTotalAmount">₱0.00</h4>
                </div>
            </div>
            <button onclick="requestConsolidatedBudget()" class="btn btn-primary btn-sm flex items-center gap-2 shadow-lg hover:shadow-xl transition-all hover:-translate-y-0.5">
                <i class='bx bx-send'></i>
                Submit Consolidated Budget Request
            </button>
        </div>

        <!-- Pagination (Matching Requisition Records design) -->
        <div id="consolidatedPager" class="flex items-center justify-between mt-4 px-2"> 
            <div id="consolidatedPagerInfo" class="text-sm text-gray-600"></div> 
            <div class="join"> 
                <button class="btn btn-sm join-item" id="consolidatedPrevBtn" data-action="prev">Prev</button> 
                <span class="btn btn-sm join-item" id="consolidatedPageDisplay">1 / 1</span> 
                <button class="btn btn-sm join-item" id="consolidatedNextBtn" data-action="next">Next</button> 
            </div> 
        </div>
    </div>
</div>

<!-- Budget Status Modal -->
<dialog id="budgetStatusModal" class="modal">
    <div class="modal-box w-11/12 max-w-6xl">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-xl flex items-center gap-2">
                <i class='bx bx-info-circle text-blue-600'></i>
                Budget Request Status
            </h3>
            <div class="flex items-center gap-4">
                <select id="budgetStatusFilter" class="select select-bordered select-sm w-full max-w-xs">
                    <option value="">All Statuses</option>
                    <option value="Pending">Pending</option>
                    <option value="Approved">Approved</option>
                    <option value="Rejected">Rejected</option>
                </select>
            </div>
        </div>
        
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-800 font-bold text-gray-100">
                        <tr>
                            <th class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Req ID</th>
                            <th class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Requested By</th>
                            <th class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Department</th>
                            <th class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Amount</th>
                            <th class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Purpose</th>
                            <th class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Date</th>
                            <th class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Action</th>
                        </tr>
                    </thead>
                    <tbody id="budgetStatusTableBody" class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex justify-center items-center py-4">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mr-3"></div>
                                    Loading budget requests...
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="modal-action">
            <button type="button" onclick="closeBudgetStatusModal()" class="btn">Close</button>
        </div>
    </div>
</dialog>

<!-- Consolidated Confirmation Modal -->
<dialog id="consolidatedConfirmModal" class="modal">
    <div class="modal-box w-11/12 max-w-md">
        <div class="flex flex-col items-center text-center py-4">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
                <i class='bx bx-help-circle text-4xl text-blue-600'></i>
            </div>
            <h3 class="font-bold text-xl mb-2">Confirm Consolidated Request</h3>
            <p class="text-gray-600 mb-6" id="consolidatedConfirmText">
                Are you sure you want to generate a budget request for the current consolidated requisitions?
            </p>
            
            <div class="w-full bg-gray-50 rounded-lg p-4 mb-6 border border-gray-100">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm text-gray-500">Total Amount:</span>
                    <span class="text-lg font-bold text-blue-600" id="consolidatedConfirmAmount">₱0.00</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-sm text-gray-500">Total Items:</span>
                    <span class="text-sm font-bold text-gray-800" id="consolidatedConfirmItems">0</span>
                </div>
            </div>
        </div>
        
        <div class="flex gap-3 justify-center">
            <button onclick="document.getElementById('consolidatedConfirmModal').close()" class="btn btn-ghost flex-1">Cancel</button>
            <button onclick="confirmConsolidatedBudget()" class="btn btn-primary flex-1">Proceed</button>
        </div>
    </div>
</dialog>

<!-- Budget Request View Modal -->
<dialog id="viewBudgetRequestModal" class="modal">
    <div class="modal-box w-11/12 max-w-md">
        <div class="flex justify-between items-center mb-6">
            <h3 class="font-bold text-lg flex items-center gap-2">
                <i class='bx bx-show-alt text-blue-600'></i>
                Budget Request Details
            </h3>
            <button onclick="document.getElementById('viewBudgetRequestModal').close()" class="btn btn-sm btn-circle btn-ghost">✕</button>
        </div>
        
        <div class="space-y-4" id="viewBudgetRequestDetails">
            <!-- Content will be injected via JS -->
        </div>
        
        <div class="modal-action mt-8">
            <button type="button" onclick="document.getElementById('viewBudgetRequestModal').close()" class="btn btn-primary w-full">Close</button>
        </div>
    </div>
</dialog>

<script>
    (function() {
        const currentUser = "{{ Auth::user()->name ?? 'PSM Admin' }}";
        let allVendors = [];
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        console.log('Budgeting Module Initialized');
        
        // Variables for Consolidated Requisitions
        let currentConsolidatedPage = 1;
        const consolidatedPageSize = 10;
        let allApprovedRequisitions = [];
        let filteredRequisitions = [];

        // Variables for Budget Requests
        let allBudgetRequests = [];

        async function fetchVendors() {
            try {
                const token = localStorage.getItem('jwt');
                const response = await fetch('/api/v1/psm/vendors', {
                    headers: { 'Authorization': `Bearer ${token}`, 'Accept': 'application/json' }
                });
                const result = await response.json();
                if (result.success) {
                    allVendors = result.data;
                    displayConsolidatedTable(); // Refresh table once vendors are loaded
                }
            } catch (error) {
                console.error('Error fetching vendors:', error);
            }
        }

        function getVendorName(vendorId) {
            if (!vendorId) return '-';
            const vendor = allVendors.find(v => v.ven_id == vendorId);
            return vendor ? vendor.ven_company_name : vendorId;
        }

        function init() {
            fetchVendors();
            fetchApprovedRequisitions();
            setupEventListeners();
        }

        function setupEventListeners() {
            // Consolidated pagination
            const consolidatedPager = document.getElementById('consolidatedPager');
            if (consolidatedPager) {
                consolidatedPager.addEventListener('click', function(ev){
                    const btn = ev.target.closest('button[data-action]');
                    if(!btn) return;
                    const act = btn.getAttribute('data-action');
                    const totalPages = Math.ceil(filteredRequisitions.length / consolidatedPageSize);
                    
                    if(act === 'prev'){ 
                        currentConsolidatedPage = Math.max(1, currentConsolidatedPage - 1); 
                        displayConsolidatedTable(); 
                    }
                    if(act === 'next'){ 
                        currentConsolidatedPage = Math.min(totalPages, currentConsolidatedPage + 1); 
                        displayConsolidatedTable(); 
                    }
                });
            }

            // Filters
            document.getElementById('consolidatedSearchInput')?.addEventListener('input', applyConsolidatedFilters);
            document.getElementById('consolidatedDeptFilter')?.addEventListener('change', applyConsolidatedFilters);
            document.getElementById('budgetStatusFilter')?.addEventListener('change', applyBudgetStatusFilters);
        }

        // --- Requisition Logic ---

        function fetchApprovedRequisitions() {
            const token = localStorage.getItem('jwt');
            
            // We fetch from consolidated view to see both CONS ID and Requisition ID
            fetch('/api/v1/psm/requisitions?view_consolidated=1', {
                method: 'GET',
                headers: {
                    'Authorization': token ? `Bearer ${token}` : '',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && Array.isArray(data.data)) {
                    allApprovedRequisitions = data.data;
                    allApprovedRequisitions.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
                    applyConsolidatedFilters();
                } else {
                    renderEmptyConsolidatedTable('No consolidated requisitions found.');
                }
            })
            .catch(error => {
                console.error('Error fetching requisitions:', error);
                renderEmptyConsolidatedTable('Error loading requisitions.');
            });
        }

        function applyConsolidatedFilters() {
            const searchTerm = document.getElementById('consolidatedSearchInput')?.value.toLowerCase() || '';
            const deptFilter = document.getElementById('consolidatedDeptFilter')?.value || '';

            filteredRequisitions = allApprovedRequisitions.filter(req => {
                // EXCLUDE: items that are already "Approved" in budget approval
                if (req.con_budget_approval === 'Approved') return false;

                const matchesSearch = 
                    (req.con_req_id && req.con_req_id.toLowerCase().includes(searchTerm)) ||
                    (req.req_id && req.req_id.toLowerCase().includes(searchTerm)) ||
                    (req.con_requester && req.con_requester.toLowerCase().includes(searchTerm)) ||
                    (req.req_dept && req.req_dept.toLowerCase().includes(searchTerm));
                
                const matchesDept = !deptFilter || req.req_dept === deptFilter;
                return matchesSearch && matchesDept;
            });

            currentConsolidatedPage = 1;
            displayConsolidatedTable();
            updateConsolidatedTotal();
        }

        function displayConsolidatedTable() {
            const tbody = document.getElementById('consolidatedTableBody');
            if (!tbody) return;

            if (filteredRequisitions.length === 0) {
                renderEmptyConsolidatedTable('No matching approved requisitions found.');
                return;
            }

            const totalPages = Math.ceil(filteredRequisitions.length / consolidatedPageSize);
            const start = (currentConsolidatedPage - 1) * consolidatedPageSize;
            const end = start + consolidatedPageSize;
            const paginatedData = filteredRequisitions.slice(start, end);

                tbody.innerHTML = '';
                paginatedData.forEach(req => {
                    const tr = document.createElement('tr');
                    tr.className = 'hover:bg-gray-50 transition-colors';
                    
                    let itemsList = '-';
                    try {
                        const items = typeof req.con_items === 'string' ? JSON.parse(req.con_items) : req.con_items;
                        if (Array.isArray(items)) {
                            itemsList = items.join(', ');
                        }
                    } catch (e) {
                        itemsList = req.con_items || '-';
                    }

                    const isBudgetApproved = req.con_budget_approval === 'Approved';

                    tr.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">${req.req_id || '-'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 max-w-xs truncate" title="${itemsList}">${itemsList}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-emerald-600">
                            ${getVendorName(req.req_chosen_vendor || req.con_chosen_vendor)}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-600">${window.formatCurrencyGlobal ? window.formatCurrencyGlobal(req.con_total_price || 0) : formatCurrency(req.con_total_price || 0)}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-bold text-gray-800">${req.con_requester || '-'}</div>
                            <div class="text-[10px] text-gray-500 uppercase font-semibold">${req.req_dept || req.con_dept || '-'}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDate(req.con_date)}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 italic max-w-xs truncate" title="${req.con_note || ''}">${req.con_note || '-'}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-4 py-1.5 text-xs font-black rounded-full bg-green-600 text-white border border-green-700 flex items-center gap-1.5 w-fit shadow-sm">
                                <i class='bx bxs-check-circle text-sm'></i> Approved
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-4 py-1.5 text-xs font-black rounded-full ${isBudgetApproved ? 'bg-green-600 border-green-700' : 'bg-yellow-500 border-yellow-600'} text-white border flex items-center gap-1.5 w-fit shadow-sm">
                                <i class='bx ${isBudgetApproved ? 'bxs-check-circle' : 'bx-time-five'} text-sm'></i> ${req.con_budget_approval || 'Pending'}
                            </span>
                        </td>
                    `;
                    tbody.appendChild(tr);
                });

            updateConsolidatedPager(filteredRequisitions.length, totalPages);
        }

        function updateConsolidatedTotal() {
            const total = calculateTotalAmount(filteredRequisitions);
            const totalEl = document.getElementById('consolidatedTotalAmount');
            if (totalEl) totalEl.textContent = window.formatCurrencyGlobal ? window.formatCurrencyGlobal(total) : formatCurrency(total);
        }

        function calculateTotalAmount(requisitions) {
            return requisitions.reduce((sum, req) => sum + parseFloat(req.con_total_price || 0), 0);
        }

        function updateConsolidatedPager(total, totalPages) {
            const info = document.getElementById('consolidatedPagerInfo');
            const display = document.getElementById('consolidatedPageDisplay');
            const start = total === 0 ? 0 : ((currentConsolidatedPage - 1) * consolidatedPageSize) + 1;
            const end = Math.min(currentConsolidatedPage * consolidatedPageSize, total);
            
            if (info) info.textContent = `Showing ${start}-${end} of ${total}`;
            if (display) display.textContent = `${currentConsolidatedPage} / ${totalPages || 1}`;
            
            const prev = document.getElementById('consolidatedPrevBtn');
            const next = document.getElementById('consolidatedNextBtn');
            
            if (prev) prev.disabled = currentConsolidatedPage <= 1;
            if (next) next.disabled = currentConsolidatedPage >= totalPages;
        }

        function renderEmptyConsolidatedTable(message) {
            const tbody = document.getElementById('consolidatedTableBody');
            if (tbody) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="9" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class='bx bx-clipboard text-6xl mb-4 text-gray-300'></i>
                                <p class="text-lg font-medium">${message}</p>
                            </div>
                        </td>
                    </tr>
                `;
            }
            updateConsolidatedTotal();
            updateConsolidatedPager(0, 1);
        }

        function formatCurrency(amount) {
            const val = parseFloat(amount || 0);
            return '₱' + val.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            if (isNaN(date.getTime())) return 'Invalid Date';
            return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
        }

        function applyBudgetStatusFilters() {
            const statusFilter = document.getElementById('budgetStatusFilter')?.value || '';
            const filteredRequests = allBudgetRequests.filter(req => {
                return !statusFilter || req.req_status === statusFilter;
            });
            renderBudgetStatusTable(filteredRequests);
        }

        // Global function for modal triggers
        window.openBudgetStatusModal = function() {
            document.getElementById('budgetStatusModal').showModal();
            fetchBudgetRequests();
        };

        window.closeBudgetStatusModal = function() {
            document.getElementById('budgetStatusModal').close();
        };

        function fetchBudgetRequests() {
            const token = localStorage.getItem('jwt');
            const tbody = document.getElementById('budgetStatusTableBody');
            if (tbody) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex justify-center items-center py-4">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mr-3"></div>
                                Loading budget requests...
                            </div>
                        </td>
                    </tr>
                `;
            }

            fetch('/api/v1/psm/budget-management/requests', {
                headers: { 'Authorization': `Bearer ${token}` }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && Array.isArray(data.data)) {
                    allBudgetRequests = data.data;
                    applyBudgetStatusFilters();
                } else {
                    renderEmptyBudgetStatusTable('No budget requests found.');
                }
            })
            .catch(err => {
                console.error('Error fetching budget requests:', err);
                renderEmptyBudgetStatusTable('Error loading budget requests.');
            });
        }

        function renderBudgetStatusTable(requests) {
            const tbody = document.getElementById('budgetStatusTableBody');
            if (!tbody) return;

            if (requests.length === 0) {
                renderEmptyBudgetStatusTable('No budget requests found.');
                return;
            }

            tbody.innerHTML = '';
            requests.forEach(req => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-gray-50 transition-colors';
                
                const isPending = req.req_status?.toLowerCase() === 'pending';

                tr.innerHTML = `
                    <td class="px-4 py-3 whitespace-nowrap text-sm font-bold text-gray-900">${req.req_id || '-'}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-700">${req.req_by || '-'}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-600 font-semibold">${req.req_dept || '-'}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm font-bold text-blue-600">${formatCurrency(req.req_amount || 0)}</td>
                    <td class="px-4 py-3 text-sm text-gray-500 truncate max-w-xs" title="${req.req_purpose || ''}">${req.req_purpose || '-'}</td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm text-gray-500">${formatDate(req.req_date)}</td>
                    <td class="px-4 py-3 whitespace-nowrap">
                        <span class="px-3 py-1 text-[10px] font-black rounded-full ${getStatusClass(req.req_status)} border shadow-sm uppercase">
                            ${req.req_status || 'Pending'}
                        </span>
                    </td>
                    <td class="px-4 py-3 whitespace-nowrap text-sm">
                        <div class="flex gap-2">
                            <button onclick="viewBudgetRequest('${req.req_id}')" class="btn btn-ghost btn-xs text-blue-600 hover:bg-blue-50" title="View Details">
                                <i class='bx bx-show-alt text-lg'></i>
                            </button>
                            ${isPending ? `
                                <button onclick="cancelBudgetRequest('${req.req_id}')" class="btn btn-ghost btn-xs text-red-600 hover:bg-red-50" title="Cancel Request">
                                    <i class='bx bx-block text-lg'></i>
                                </button>
                            ` : ''}
                        </div>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        function getStatusClass(status) {
            switch(status?.toLowerCase()) {
                case 'approved': return 'bg-green-600 text-white border-green-700';
                case 'rejected': return 'bg-red-600 text-white border-red-700';
                case 'pending': return 'bg-yellow-500 text-white border-yellow-600';
                default: return 'bg-blue-600 text-white border-blue-700';
            }
        }

        function renderEmptyBudgetStatusTable(message) {
            const tbody = document.getElementById('budgetStatusTableBody');
            if (tbody) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class='bx bx-clipboard text-6xl mb-4 text-gray-300'></i>
                                <p class="text-lg font-medium">${message}</p>
                            </div>
                        </td>
                    </tr>
                `;
            }
        }

        window.requestConsolidatedBudget = function() {
            // Only consolidate items that are NOT yet consolidated (con_req_id is '-')
            const pendingItems = filteredRequisitions.filter(req => req.con_req_id === '-');

            if (pendingItems.length === 0) {
                Swal.fire('Info', 'No pending approved requisitions to consolidate. Items already consolidated are shown for reference.', 'info');
                return;
            }

            const totalAmount = calculateTotalAmount(pendingItems);
            const totalItems = pendingItems.length;

            document.getElementById('consolidatedConfirmAmount').textContent = formatCurrency(totalAmount);
            document.getElementById('consolidatedConfirmItems').textContent = totalItems;
            document.getElementById('consolidatedConfirmModal').showModal();
        };

        window.confirmConsolidatedBudget = function() {
            const token = localStorage.getItem('jwt');
            const pendingItems = filteredRequisitions.filter(req => req.con_req_id === '-');
            const totalAmount = calculateTotalAmount(pendingItems);
            
            // Collect IDs to mark as consolidated
            const reqIds = pendingItems.map(r => r.req_id);
            const reqIdsStr = reqIds.join(', ');
            const purpose = `Consolidated budget for: ${reqIdsStr}`;

            // Determine the department for the budget request
            const departments = [...new Set(pendingItems.map(item => item.req_dept).filter(Boolean))];
            const deptFilter = document.getElementById('consolidatedDeptFilter')?.value;
            const consolidatedDept = deptFilter || (departments.length === 1 ? departments[0] : 'Logistics 1');

            fetch('/api/v1/psm/budget-management/requests', {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    req_amount: totalAmount,
                    req_purpose: purpose,
                    req_dept: consolidatedDept,
                    req_by: currentUser,
                    included_req_ids: reqIds
                })
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    Toast.fire({
                        icon: 'success',
                        title: 'Budget request generated successfully'
                    });
                    document.getElementById('consolidatedConfirmModal').close();
                    fetchApprovedRequisitions(); // Refresh the table to clear consolidated items
                    openBudgetStatusModal();
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: res.message,
                        target: document.getElementById('consolidatedConfirmModal')
                    });
                }
            })
            .catch(err => {
                console.error('Error creating budget request:', err);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Failed to generate budget request',
                    target: document.getElementById('consolidatedConfirmModal')
                });
            });
        };

        window.viewBudgetRequest = function(requestId) {
            const token = localStorage.getItem('jwt');
            const detailsEl = document.getElementById('viewBudgetRequestDetails');
            
            if (detailsEl) {
                detailsEl.innerHTML = `
                    <div class="flex justify-center py-8">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
                    </div>
                `;
            }

            document.getElementById('viewBudgetRequestModal').showModal();

            fetch('/api/v1/psm/budget-management/requests', {
                headers: { 'Authorization': `Bearer ${token}` }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && Array.isArray(data.data)) {
                    const req = data.data.find(r => r.req_id === requestId);
                    if (req) {
                        renderRequestDetails(req);
                    } else {
                        detailsEl.innerHTML = '<p class="text-center text-red-500">Request details not found.</p>';
                    }
                }
            })
            .catch(err => {
                console.error('Error fetching details:', err);
                detailsEl.innerHTML = '<p class="text-center text-red-500">Error loading details.</p>';
            });
        };

        function renderRequestDetails(req) {
            const detailsEl = document.getElementById('viewBudgetRequestDetails');
            if (!detailsEl) return;

            detailsEl.innerHTML = `
                <div class="bg-gray-50 rounded-xl p-5 border border-gray-100 space-y-4">
                    <div class="flex justify-between items-start border-b border-gray-100 pb-3">
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Request ID</p>
                            <h4 class="text-lg font-black text-gray-800">${req.req_id}</h4>
                        </div>
                        <span class="px-3 py-1 text-[10px] font-black rounded-full ${getStatusClass(req.req_status)} border shadow-sm uppercase">
                            ${req.req_status}
                        </span>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Requested By</p>
                            <p class="text-sm font-bold text-gray-700">${req.req_by || '-'}</p>
                        </div>
                        <div>
                            <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Department</p>
                            <p class="text-sm font-bold text-gray-700">${req.req_dept || '-'}</p>
                        </div>
                    </div>

                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Amount</p>
                        <p class="text-xl font-black text-blue-600">${formatCurrency(req.req_amount)}</p>
                    </div>

                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Purpose</p>
                        <p class="text-sm text-gray-600 leading-relaxed">${req.req_purpose || '-'}</p>
                    </div>

                    <div>
                        <p class="text-[10px] font-black text-gray-400 uppercase tracking-widest">Date Requested</p>
                        <p class="text-sm font-bold text-gray-700">${formatDate(req.req_date)}</p>
                    </div>
                </div>
            `;
        }

        window.cancelBudgetRequest = function(requestId) {
            Swal.fire({
                title: 'Cancel Request?',
                text: "Are you sure you want to cancel this budget request? This action cannot be undone.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#ef4444',
                cancelButtonColor: '#6b7280',
                confirmButtonText: 'Yes, Cancel it!',
                target: document.getElementById('budgetStatusModal')
            }).then((result) => {
                if (result.isConfirmed) {
                    const token = localStorage.getItem('jwt');
                    fetch(`/api/v1/psm/budget-management/requests/${requestId}/cancel`, {
                        method: 'POST',
                        headers: {
                            'Authorization': `Bearer ${token}`,
                            'Content-Type': 'application/json'
                        }
                    })
                    .then(res => res.json())
                    .then(res => {
                        if (res.success) {
                            Toast.fire({
                                icon: 'success',
                                title: res.message || 'Budget request cancelled successfully'
                            });
                            fetchBudgetRequests();
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: res.message,
                                target: document.getElementById('budgetStatusModal')
                            });
                        }
                    })
                    .catch(err => {
                        console.error('Error cancelling request:', err);
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to cancel request',
                            target: document.getElementById('budgetStatusModal')
                        });
                    });
                }
            });
        };

        init();
    })();
</script>
