<!-- views/psm/budgeting.blade.php -->
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bxs-wallet'></i>Budgeting</h2>
    </div>
    <div class="flex items-center gap-4">
        <span class="text-md text-gray-600">Procurement & Sourcing Management</span>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg p-6 mb-6">
    <!-- Consolidated Requisition Section -->
    <div class="">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div class="flex items-center gap-4">
                <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                    <i class='bx bx-git-pull-request text-blue-600'></i>
                    Consolidated Requisitions
                </h3>
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

<script>
    (function() {
        console.log('Budgeting Module Initialized (Cleaned)');
        
        // Variables for Consolidated Budget Requests
        let currentConsolidatedPage = 1;
        const consolidatedPageSize = 10;
        let allApprovedRequisitions = [];
        let filteredRequisitions = [];

        function init() {
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
        }

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

        init();
    })();
</script>