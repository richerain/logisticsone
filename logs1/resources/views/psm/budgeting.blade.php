<!-- views/psm/budgeting.blade.php -->
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bxs-wallet'></i>Budgeting</h2>
    </div>
    <div class="flex items-center gap-4">
        <span class="text-md text-gray-600">Procurement & Sourcing Management</span>
    </div>
</div>

<!-- Budget Status Overview -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-blue-600">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Active Budget</h3>
            <div class="bg-blue-100 p-2 rounded-lg text-blue-600">
                <i class='bx bxs-wallet text-xl'></i>
            </div>
        </div>
        <div class="flex flex-col">
            <h4 class="text-2xl font-black text-gray-800" id="activeBudgetAmount">₱0.00</h4>
            <p class="text-xs text-gray-500 mt-1" id="activeBudgetPeriod">No active budget period</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-green-600">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Status</h3>
            <div class="bg-green-100 p-2 rounded-lg text-green-600">
                <i class='bx bxs-check-shield text-xl'></i>
            </div>
        </div>
        <div class="flex flex-col">
            <h4 class="text-2xl font-black text-gray-800" id="activeBudgetStatus">Inactive</h4>
            <p class="text-xs text-gray-500 mt-1" id="activeBudgetExpiry">N/A</p>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-lg p-6 border-l-4 border-purple-600">
        <div class="flex items-center justify-between mb-4">
            <h3 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Quick Actions</h3>
            <div class="bg-purple-100 p-2 rounded-lg text-purple-600">
                <i class='bx bxs-zap text-xl'></i>
            </div>
        </div>
        <div class="flex gap-2">
            <button onclick="openCreateBudgetModal()" class="btn btn-sm btn-primary">New Budget</button>
            <button onclick="openExtendBudgetModal()" id="extendBudgetBtn" class="btn btn-sm btn-outline btn-primary" disabled>Extend</button>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg p-6 mb-6">
    <!-- Consolidated Requisition Section -->
    <div class="">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div class="flex items-center gap-4 w-full justify-between">
                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <i class='bx bx-git-pull-request text-blue-600'></i>
                    Consolidated Budget Request
                </h3>
                <button onclick="requestConsolidatedBudget()" class="btn btn-primary btn-sm">Consolidated Budget Request</button>
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
                            <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Total Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Requester / Dept</th>
                            <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Note</th>
                            <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody id="consolidatedTableBody" class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
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

<!-- Budget Allocation Section -->
<div class="bg-white rounded-lg shadow-lg p-6 mb-6">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
            <i class='bx bx-history text-purple-600'></i>
            Budget Allocation
        </h3>
        <button onclick="requestBudgetStatus()" class="btn btn-outline btn-primary btn-sm">request budget status</button>
    </div>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody id="budgetHistoryBody" class="bg-white divide-y divide-gray-200">
                <tr>
                    <td colspan="5" class="px-6 py-4 text-center text-gray-500 italic">No budget history available</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- Budget Modal -->
<dialog id="budgetModal" class="modal">
    <div class="modal-box w-11/12 max-w-md">
        <h3 class="font-bold text-lg mb-4" id="budgetModalTitle">Create New Budget</h3>
        <form id="budgetForm" method="POST">
            <input type="hidden" id="budgetId">
            <div class="form-control mb-4">
                <label class="label"><span class="label-text font-bold">Budget Amount (₱)</span></label>
                <input type="number" id="budgetAmount" name="amount" step="0.01" min="0" required class="input input-bordered w-full">
            </div>
            <div class="form-control mb-4">
                <label class="label"><span class="label-text font-bold">Valid From</span></label>
                <input type="date" id="budgetValidFrom" name="valid_from" required class="input input-bordered w-full">
            </div>
            <div class="form-control mb-4">
                <label class="label"><span class="label-text font-bold">Valid To</span></label>
                <input type="date" id="budgetValidTo" name="valid_to" required class="input input-bordered w-full">
            </div>
            <div class="form-control mb-6">
                <label class="label"><span class="label-text font-bold">Description</span></label>
                <textarea id="budgetDescription" name="description" class="textarea textarea-bordered h-24" placeholder="Enter budget description..."></textarea>
            </div>
            <div class="modal-action">
                <button type="button" onclick="closeBudgetModal()" class="btn">Cancel</button>
                <button type="submit" class="btn btn-primary" id="saveBudgetBtn">Save Budget</button>
            </div>
        </form>
    </div>
</dialog>

<!-- Extension Modal -->
<dialog id="extendModal" class="modal">
    <div class="modal-box w-11/12 max-w-sm">
        <h3 class="font-bold text-lg mb-4">Extend Budget Validity</h3>
        <form id="extendForm" method="POST">
            <div class="form-control mb-6">
                <label class="label"><span class="label-text font-bold">Extension (Days)</span></label>
                <input type="number" id="extensionDays" name="extension_days" min="1" required class="input input-bordered w-full" placeholder="Enter number of days...">
            </div>
            <div class="modal-action">
                <button type="button" onclick="closeExtendModal()" class="btn">Cancel</button>
                <button type="submit" class="btn btn-primary">Confirm Extension</button>
            </div>
        </form>
    </div>
</dialog>

<script>
    (function() {
        console.log('Budgeting Module Initialized');
        
        // Variables for Consolidated Requisitions
        let currentConsolidatedPage = 1;
        const consolidatedPageSize = 10;
        let allApprovedRequisitions = [];
        let filteredRequisitions = [];
        let activeBudget = null;

        function init() {
            fetchActiveBudget();
            fetchBudgetHistory();
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

            // Consolidated filters
            document.getElementById('consolidatedSearchInput')?.addEventListener('input', applyConsolidatedFilters);
            document.getElementById('consolidatedDeptFilter')?.addEventListener('change', applyConsolidatedFilters);

            // Forms
            document.getElementById('budgetForm')?.addEventListener('submit', handleBudgetSubmit);
            document.getElementById('extendForm')?.addEventListener('submit', handleExtendSubmit);
        }

        // --- Budget Logic ---

        function fetchActiveBudget() {
            const token = localStorage.getItem('jwt');
            fetch('/api/v1/psm/budget-management/current', {
                headers: { 'Authorization': `Bearer ${token}` }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.data) {
                    activeBudget = data.data;
                    updateActiveBudgetUI(activeBudget);
                } else {
                    updateActiveBudgetUI(null);
                }
            })
            .catch(err => console.error('Error fetching budget:', err));
        }

        function updateActiveBudgetUI(budget) {
            const amountEl = document.getElementById('activeBudgetAmount');
            const periodEl = document.getElementById('activeBudgetPeriod');
            const statusEl = document.getElementById('activeBudgetStatus');
            const expiryEl = document.getElementById('activeBudgetExpiry');
            const extendBtn = document.getElementById('extendBudgetBtn');

            if (budget) {
                amountEl.textContent = formatCurrency(budget.amount);
                periodEl.textContent = `${formatDate(budget.valid_from)} - ${formatDate(budget.valid_to)}`;
                
                // Fix: Defensive check for budget.status
                const status = budget.status || 'unknown';
                statusEl.textContent = status.charAt(0).toUpperCase() + status.slice(1);
                statusEl.className = `text-2xl font-black ${status === 'active' ? 'text-green-600' : 'text-red-600'}`;
                
                const daysLeft = Math.ceil((new Date(budget.valid_to) - new Date()) / (1000 * 60 * 60 * 24));
                expiryEl.textContent = daysLeft > 0 ? `${daysLeft} days remaining` : 'Expired';
                
                if (extendBtn) extendBtn.disabled = false;
            } else {
                amountEl.textContent = '₱0.00';
                periodEl.textContent = 'No active budget period';
                statusEl.textContent = 'Inactive';
                statusEl.className = 'text-2xl font-black text-gray-400';
                expiryEl.textContent = 'N/A';
                if (extendBtn) extendBtn.disabled = true;
            }
        }

        function fetchBudgetHistory() {
            const token = localStorage.getItem('jwt');
            fetch('/api/v1/psm/budget-management', {
                headers: { 'Authorization': `Bearer ${token}` }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && Array.isArray(data.data)) {
                    renderBudgetHistory(data.data);
                }
            })
            .catch(err => console.error('Error fetching history:', err));
        }

        function renderBudgetHistory(budgets) {
            const tbody = document.getElementById('budgetHistoryBody');
            if (!tbody) return;

            if (budgets.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500 italic">No budget history available</td></tr>';
                return;
            }

            tbody.innerHTML = '';
            budgets.forEach(b => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-gray-50';
                
                // Fix: Defensive check for status
                const status = b.status || 'UNKNOWN';
                
                tr.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap font-bold text-gray-900">${formatCurrency(b.amount)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${formatDate(b.valid_from)} - ${formatDate(b.valid_to)}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 py-1 text-xs font-bold rounded-full ${status === 'active' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-600'}">
                            ${status.toUpperCase()}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-500 truncate max-w-xs">${b.description || '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <button onclick="editBudget(${b.id})" class="text-blue-600 hover:text-blue-900 mr-3"><i class='bx bx-edit-alt'></i></button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        function handleBudgetSubmit(e) {
            e.preventDefault();
            const id = document.getElementById('budgetId').value;
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData.entries());
            const token = localStorage.getItem('jwt');

            const url = id ? `/api/v1/psm/budget-management/${id}` : '/api/v1/psm/budget-management';
            const method = id ? 'PUT' : 'POST';

            fetch(url, {
                method: method,
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    Swal.fire('Success', res.message, 'success');
                    closeBudgetModal();
                    fetchActiveBudget();
                    fetchBudgetHistory();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            })
            .catch(err => Swal.fire('Error', 'Failed to save budget', 'error'));
        }

        function handleExtendSubmit(e) {
            e.preventDefault();
            if (!activeBudget) return;

            const days = document.getElementById('extensionDays').value;
            const token = localStorage.getItem('jwt');

            fetch(`/api/v1/psm/budget-management/${activeBudget.id}/extend`, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${token}`,
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ extension_days: days })
            })
            .then(res => res.json())
            .then(res => {
                if (res.success) {
                    Swal.fire('Success', res.message, 'success');
                    closeExtendModal();
                    fetchActiveBudget();
                    fetchBudgetHistory();
                } else {
                    Swal.fire('Error', res.message, 'error');
                }
            })
            .catch(err => Swal.fire('Error', 'Failed to extend budget', 'error'));
        }

        // --- Requisition Logic ---

        function fetchApprovedRequisitions() {
            const token = localStorage.getItem('jwt');
            
            fetch('/api/v1/psm/requisitions', {
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
                    allApprovedRequisitions = data.data.filter(req => req.req_status === 'Approved');
                    allApprovedRequisitions.sort((a, b) => (b.id || 0) - (a.id || 0));
                    applyConsolidatedFilters();
                } else {
                    renderEmptyConsolidatedTable('No approved requisitions found.');
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
                const matchesSearch = 
                    (req.req_id && req.req_id.toLowerCase().includes(searchTerm)) ||
                    (req.req_requester && req.req_requester.toLowerCase().includes(searchTerm)) ||
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
                    const items = typeof req.req_items === 'string' ? JSON.parse(req.req_items) : req.req_items;
                    if (Array.isArray(items)) {
                        itemsList = items.join(', ');
                    }
                } catch (e) {
                    itemsList = req.req_items || '-';
                }

                tr.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">${req.req_id || '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 max-w-xs truncate" title="${itemsList}">${itemsList}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-600">${window.formatCurrencyGlobal ? window.formatCurrencyGlobal(req.req_price || 0) : formatCurrency(req.req_price || 0)}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-bold text-gray-800">${req.req_requester || '-'}</div>
                        <div class="text-[10px] text-gray-500 uppercase font-semibold">${req.req_dept || '-'}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDate(req.req_date)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 italic max-w-xs truncate" title="${req.req_note || ''}">${req.req_note || '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-4 py-1.5 text-xs font-black rounded-full bg-green-600 text-white border border-green-700 flex items-center gap-1.5 w-fit shadow-sm">
                            <i class='bx bxs-check-circle text-sm'></i> Approved
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
            return requisitions.reduce((sum, req) => sum + parseFloat(req.req_price || 0), 0);
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
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
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

        // Global function for modal triggers
        window.openCreateBudgetModal = function() {
            document.getElementById('budgetForm').reset();
            document.getElementById('budgetId').value = '';
            document.getElementById('budgetModalTitle').textContent = 'Create New Budget';
            document.getElementById('budgetModal').showModal();
        };

        window.openExtendBudgetModal = function() {
            document.getElementById('extendForm').reset();
            document.getElementById('extendModal').showModal();
        };

        window.closeBudgetModal = function() {
            document.getElementById('budgetModal').close();
        };

        window.closeExtendModal = function() {
            document.getElementById('extendModal').close();
        };

        window.requestConsolidatedBudget = function() {
            if (filteredRequisitions.length === 0) {
                Swal.fire('Info', 'No approved requisitions to consolidate.', 'info');
                return;
            }
            const totalAmount = calculateTotalAmount(filteredRequisitions);
            Swal.fire({
                title: 'Request Consolidated Budget',
                text: `Are you sure you want to request a budget for the total amount of ${formatCurrency(totalAmount)}?`,
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes, request it',
                cancelButtonText: 'No, cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    Swal.fire('Success', 'Consolidated budget request has been submitted.', 'success');
                }
            });
        };

        window.requestBudgetStatus = function() {
            Swal.fire({
                title: 'Budget Allocation Status',
                text: 'Your budget allocation status request has been sent to the financial department.',
                icon: 'info',
                confirmButtonText: 'Close'
            });
        };

        window.editBudget = function(id) {
            const token = localStorage.getItem('jwt');
            fetch(`/api/v1/psm/budget-management/${id}`, {
                headers: { 'Authorization': `Bearer ${token}` }
            })
            .then(res => res.json())
            .then(data => {
                if (data.success && data.data) {
                    const b = data.data;
                    document.getElementById('budgetId').value = b.id;
                    document.getElementById('budgetAmount').value = b.amount;
                    document.getElementById('budgetValidFrom').value = b.valid_from;
                    document.getElementById('budgetValidTo').value = b.valid_to;
                    document.getElementById('budgetDescription').value = b.description;
                    document.getElementById('budgetModalTitle').textContent = 'Edit Budget';
                    document.getElementById('budgetModal').showModal();
                }
            });
        };

        init();
    })();
</script>
