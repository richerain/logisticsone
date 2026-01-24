<!-- views/psm/budgeting.blade.php -->
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bxs-wallet'></i>Budgeting</h2>
    </div>
    <div class="flex items-center gap-4">
        <span class="text-md text-gray-600">Procurement & Sourcing Management</span>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg p-6">
    <!-- Stats Section -->
    <div id="statsSection" class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Budget</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-2" id="totalBudget">₱0.00</h3>
                </div>
                <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                    <i class='bx bx-wallet text-xl'></i>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Spent</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-2" id="spentBudget">₱0.00</h3>
                </div>
                <div class="p-2 bg-orange-50 rounded-lg text-orange-600">
                    <i class='bx bx-cart text-xl'></i>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-xl shadow-sm p-6 border border-gray-100 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider">Remaining</p>
                    <h3 class="text-2xl font-bold text-gray-900 mt-2" id="remainingBudget">₱0.00</h3>
                </div>
                <div class="p-2 bg-green-50 rounded-lg text-green-600">
                    <i class='bx bx-pie-chart-alt-2 text-xl'></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Logistic I Budget Panel (Renamed from Latest Budget Details) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="text-md font-semibold text-gray-800">Logistic I Budget</h3>
            <span id="detailStatus" class="px-3 py-1 text-xs rounded-full bg-gray-200 text-gray-600 flex items-center gap-1">
                <i class='bx bx-question-mark'></i> No Data
            </span>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- New Fields -->
            <div class="flex items-start gap-3">
                <div class="p-2 bg-gray-100 rounded-lg text-gray-600">
                    <i class='bx bx-id-card text-xl'></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase mb-1">Budget ID</p>
                    <p class="font-medium text-gray-900" id="detailBudgetId">-</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                    <i class='bx bx-wallet text-xl'></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase mb-1">Allocated</p>
                    <p class="font-medium text-blue-700" id="detailAllocated">-</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="p-2 bg-orange-50 rounded-lg text-orange-600">
                    <i class='bx bx-purchase-tag text-xl'></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase mb-1">Spent</p>
                    <p class="font-medium text-orange-700" id="detailSpent">-</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="p-2 bg-green-50 rounded-lg text-green-600">
                    <i class='bx bx-pie-chart-alt-2 text-xl'></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase mb-1">Remaining</p>
                    <p class="font-medium text-green-700" id="detailRemaining">-</p>
                </div>
            </div>
            
            <!-- Existing Fields -->
            <div class="flex items-start gap-3">
                <div class="p-2 bg-purple-50 rounded-lg text-purple-600">
                    <i class='bx bx-calendar-check text-xl'></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase mb-1">Validity Type</p>
                    <p class="font-medium text-gray-900" id="detailValidityType">-</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600">
                    <i class='bx bx-calendar text-xl'></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase mb-1">Valid From</p>
                    <p class="font-medium text-gray-900" id="detailValidFrom">-</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="p-2 bg-pink-50 rounded-lg text-pink-600">
                    <i class='bx bx-calendar-x text-xl'></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase mb-1">Valid To</p>
                    <p class="font-medium text-gray-900" id="detailValidTo">-</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="p-2 bg-teal-50 rounded-lg text-teal-600">
                    <i class='bx bx-time-five text-xl'></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase mb-1">Assigned Date</p>
                    <p class="font-medium text-gray-900" id="detailAssignedDate">-</p>
                </div>
            </div>
             <div class="md:col-span-2 lg:col-span-4 flex items-start gap-3">
                <div class="p-2 bg-gray-50 rounded-lg text-gray-600">
                    <i class='bx bx-detail text-xl'></i>
                </div>
                <div class="flex-1">
                    <p class="text-xs text-gray-500 uppercase mb-1">Description</p>
                    <p class="font-medium text-gray-900" id="detailDesc">-</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Budget Logs Table Container -->
    <div class="mt-8">
        <h3 class="text-md font-semibold text-gray-800 mb-4">Budget Logs</h3>
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200" id="budgetLogsTable">
                <thead class="bg-gray-100">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 capitalize tracking-wider whitespace-nowrap">Log ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 capitalize tracking-wider whitespace-nowrap">Spent Amount</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 capitalize tracking-wider whitespace-nowrap">Spent To</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 capitalize tracking-wider whitespace-nowrap">Type</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 capitalize tracking-wider whitespace-nowrap">Spent Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 capitalize tracking-wider whitespace-nowrap">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="budgetLogsTableBody">
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class='bx bx-loader-alt bx-spin text-4xl mb-2'></i>
                                <p>Loading logs...</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Budget Log Details Modal -->
<div id="budgetLogModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-80 transition-opacity" aria-hidden="true" onclick="closeBudgetLogModal()"></div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 sm:mx-0 sm:h-10 sm:w-10">
                        <i class='bx bx-file text-blue-600 text-xl'></i>
                    </div>
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Budget Log Details
                        </h3>
                        <div class="mt-4 space-y-3">
                            <div>
                                <p class="text-sm font-medium text-gray-500">Log ID</p>
                                <p class="text-sm text-gray-900" id="modalLogId">-</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Budget ID</p>
                                <p class="text-sm text-gray-900" id="modalBudgetId">-</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Spent Amount</p>
                                <p class="text-sm font-bold text-orange-700" id="modalSpentAmount">-</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Spent To</p>
                                <p class="text-sm text-gray-900 break-words" id="modalSpentTo">-</p>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Type</p>
                                <div id="modalType">-</div>
                            </div>
                            <div>
                                <p class="text-sm font-medium text-gray-500">Spent Date</p>
                                <p class="text-sm text-gray-900" id="modalSpentDate">-</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeBudgetLogModal()">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Global functions for Modal
    window.allBudgetLogs = []; // Store logs for modal access

    window.openBudgetLogModal = function(logId) {
        const log = window.allBudgetLogs.find(l => (l.log_code || l.id) == logId);
        if (!log) return;

        document.getElementById('modalLogId').textContent = log.log_code || log.id || '-';
        document.getElementById('modalBudgetId').textContent = log.bud_id || '-';
        document.getElementById('modalSpentAmount').textContent = window.formatCurrencyGlobal(log.bud_spent);
        document.getElementById('modalSpentTo').textContent = log.spent_to || '-';
        document.getElementById('modalSpentDate').textContent = window.formatDateGlobal(log.bud_spent_date);

        // Style Type
        let typeClass = 'bg-gray-100 text-gray-800';
        let typeIcon = 'bx-question-mark';
        
        if (log.bud_type === 'Purchase Payment') {
            typeClass = 'bg-blue-100 text-blue-800';
            typeIcon = 'bx-purchase-tag';
        } else if (log.bud_type === 'Project Payment') {
            typeClass = 'bg-purple-100 text-purple-800';
            typeIcon = 'bx-building-house';
        }

        document.getElementById('modalType').innerHTML = `
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${typeClass} items-center gap-1">
                <i class='bx ${typeIcon}'></i> ${log.bud_type || '-'}
            </span>
        `;

        document.getElementById('budgetLogModal').classList.remove('hidden');
    };

    window.closeBudgetLogModal = function() {
        document.getElementById('budgetLogModal').classList.add('hidden');
    };
    
    // Helper functions for global scope (needed for modal)
    window.formatCurrencyGlobal = function(amount) {
        const val = parseFloat(amount || 0);
        return '₱' + val.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    };

    window.formatDateGlobal = function(dateString) {
        if (!dateString) return 'N/A';
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return 'Invalid Date';
        return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
    };

    // Initialize Budgeting Module
    (function() {
        console.log('Budgeting Module Initialized');
        
        fetchBudgets();
        fetchBudgetLogs();

        function fetchBudgets() {
            const token = localStorage.getItem('jwt');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            // Show loading state
            renderLoadingState();

            // Use the internal session-based route instead of the API route
            fetch('/psm/budget-management/all', {
                method: 'GET',
                headers: {
                    'Authorization': token ? `Bearer ${token}` : '',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || '',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    // Try to read error body if possible
                    return response.text().then(text => {
                        console.error('Fetch error response:', text);
                        throw new Error(`HTTP error! status: ${response.status}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Budgets data:', data);
                if (data.success && Array.isArray(data.data) && data.data.length > 0) {
                    renderBudgetTable(data.data);
                    updateStats(data.data);
                    renderBudgetDetails(data.data[0]);
                } else if (data.success && Array.isArray(data.data) && data.data.length === 0) {
                    renderEmptyState('No budgets found.');
                    updateStats([]); 
                    renderBudgetDetails(null);
                } else {
                    renderEmptyState(data.message || 'No budgets found.');
                    updateStats([]);
                    renderBudgetDetails(null);
                }
            })
            .catch(error => {
                console.error('Error fetching budgets:', error);
                renderEmptyState('Error loading budgets. Please try again.');
                updateStats([]);
            });
        }

        function renderLoadingState() {
            const tbody = document.getElementById('budgetsTableBody');
            if (!tbody) return;
            tbody.innerHTML = `
                <tr>
                    <td colspan="13" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class='bx bx-loader-alt bx-spin text-4xl mb-2'></i>
                            <p>Loading budgets...</p>
                        </div>
                    </td>
                </tr>
            `;
        }

        function renderBudgetTable(budgets) {
            const tbody = document.getElementById('budgetsTableBody');
            if (!tbody) return;
            tbody.innerHTML = '';

            budgets.forEach(budget => {
                const healthColor = getHealthColor(budget.bud_amount_status_health);
                
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-gray-50 transition-colors';
                
                tr.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${budget.bud_id || '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 font-bold">${formatCurrency(budget.bud_allocated_amount)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-orange-600 font-bold">${formatCurrency(budget.bud_spent_amount)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-bold">${formatCurrency(budget.bud_remaining_amount)}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${healthColor.bg} ${healthColor.text}">
                            ${budget.bud_amount_status_health || 'Unknown'}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${budget.bud_validity_type || '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDate(budget.bud_valid_from)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDate(budget.bud_valid_to)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDate(budget.bud_assigned_date)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${budget.bud_for_department || '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${budget.bud_for_module || '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${budget.bud_for_submodule || '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 max-w-xs truncate" title="${budget.bud_desc || ''}">${budget.bud_desc || '-'}</td>
                `;
                tbody.appendChild(tr);
            });
        }

        function renderEmptyState(message = 'No budgets found.') {
            const tbody = document.getElementById('budgetsTableBody');
            if (!tbody) return;
            tbody.innerHTML = `
                <tr>
                    <td colspan="13" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class='bx bx-wallet text-6xl mb-4 text-gray-300'></i>
                            <p class="text-lg font-medium">${message}</p>
                        </div>
                    </td>
                </tr>
            `;
        }

        function updateStats(budgets) {
            let total = 0;
            let spent = 0;
            let remaining = 0;

            budgets.forEach(b => {
                total += parseFloat(b.bud_allocated_amount || 0);
                spent += parseFloat(b.bud_spent_amount || 0);
                remaining += parseFloat(b.bud_remaining_amount || 0);
            });

            const totalEl = document.getElementById('totalBudget');
            if(totalEl) totalEl.textContent = formatCurrency(total);
            
            const spentEl = document.getElementById('spentBudget');
            if(spentEl) spentEl.textContent = formatCurrency(spent);
            
            const remainingEl = document.getElementById('remainingBudget');
            if(remainingEl) remainingEl.textContent = formatCurrency(remaining);
        }

        function renderBudgetDetails(budget) {
            const healthData = budget ? getHealthColor(budget.bud_amount_status_health) : { bg: 'bg-gray-200', text: 'text-gray-600', icon: 'bx-question-mark' };
            const statusText = budget ? (budget.bud_amount_status_health || 'Unknown') : 'No Data';

            const fields = {
                'detailBudgetId': budget ? (budget.bud_id || '-') : '-',
                'detailAllocated': budget ? formatCurrency(budget.bud_allocated_amount) : '-',
                'detailSpent': budget ? formatCurrency(budget.bud_spent_amount) : '-',
                'detailRemaining': budget ? formatCurrency(budget.bud_remaining_amount) : '-',
                'detailValidityType': budget ? (budget.bud_validity_type || '-') : '-',
                'detailValidFrom': budget ? formatDate(budget.bud_valid_from) : '-',
                'detailValidTo': budget ? formatDate(budget.bud_valid_to) : '-',
                'detailAssignedDate': budget ? formatDate(budget.bud_assigned_date) : '-',
                'detailDesc': budget ? (budget.bud_desc || '-') : '-'
            };

            for (const [id, value] of Object.entries(fields)) {
                const el = document.getElementById(id);
                if (el) el.textContent = value;
            }

            const statusEl = document.getElementById('detailStatus');
            if (statusEl) {
                statusEl.innerHTML = `<i class='bx ${healthData.icon}'></i> ${statusText}`;
                statusEl.className = `px-3 py-1 text-xs rounded-full ${healthData.bg} ${healthData.text} flex items-center gap-1`;
            }
        }

        function getHealthColor(status) {
            switch(status) {
                case 'Healthy': return { bg: 'bg-green-100', text: 'text-green-800', icon: 'bx-check-circle' };
                case 'Stable': return { bg: 'bg-blue-100', text: 'text-blue-800', icon: 'bx-info-circle' };
                case 'Alert': return { bg: 'bg-yellow-100', text: 'text-yellow-800', icon: 'bx-error-circle' };
                case 'Exceeded': return { bg: 'bg-red-100', text: 'text-red-800', icon: 'bx-x-circle' };
                default: return { bg: 'bg-gray-100', text: 'text-gray-800', icon: 'bx-question-mark' };
            }
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

        function fetchBudgetLogs() {
            const token = localStorage.getItem('jwt');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            renderLogsLoadingState();

            fetch('/psm/budget-logs/all', {
                method: 'GET',
                headers: {
                    'Authorization': token ? `Bearer ${token}` : '',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || '',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                return response.json();
            })
            .then(data => {
                console.log('Budget Logs data:', data);
                if (data.success && Array.isArray(data.data) && data.data.length > 0) {
                    window.allBudgetLogs = data.data; // Store globally for modal
                    renderBudgetLogsTable(data.data);
                } else {
                    window.allBudgetLogs = [];
                    renderLogsEmptyState(data.message || 'No budget logs found.');
                }
            })
            .catch(error => {
                console.error('Error fetching budget logs:', error);
                renderLogsEmptyState('Error loading logs. Please try again.');
            });
        }

        function renderLogsLoadingState() {
            const tbody = document.getElementById('budgetLogsTableBody');
            if (!tbody) return;
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class='bx bx-loader-alt bx-spin text-4xl mb-2'></i>
                            <p>Loading logs...</p>
                        </div>
                    </td>
                </tr>
            `;
        }

        function renderBudgetLogsTable(logs) {
            const tbody = document.getElementById('budgetLogsTableBody');
            if (!tbody) return;
            tbody.innerHTML = '';

            logs.forEach(log => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-gray-50 transition-colors';
                
                // Style for Type
                let typeClass = 'bg-gray-100 text-gray-800';
                let typeIcon = 'bx-question-mark';
                
                if (log.bud_type === 'Purchase Payment') {
                    typeClass = 'bg-blue-100 text-blue-800';
                    typeIcon = 'bx-purchase-tag';
                } else if (log.bud_type === 'Project Payment') {
                    typeClass = 'bg-purple-100 text-purple-800';
                    typeIcon = 'bx-building-house';
                }

                // Truncate Spent To
                let spentTo = log.spent_to || '-';
                if (spentTo.length > 30) {
                    spentTo = spentTo.substring(0, 30) + '...';
                }
                
                tr.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${log.log_code || log.id}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-orange-700">${formatCurrency(log.bud_spent)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" title="${log.spent_to || ''}">${spentTo}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${typeClass} items-center gap-1">
                            <i class='bx ${typeIcon}'></i> ${log.bud_type || '-'}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDate(log.bud_spent_date)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-full transition-colors" title="View Full Budget Logs" onclick="openBudgetLogModal('${log.log_code || log.id}')">
                            <i class='bx bx-show-alt text-lg'></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });
        }

        function renderLogsEmptyState(message = 'No budget logs yet.') {
            const tbody = document.getElementById('budgetLogsTableBody');
            if (!tbody) return;
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class='bx bx-list-ul text-6xl mb-4 text-gray-300'></i>
                            <p class="text-lg font-medium">${message}</p>
                        </div>
                    </td>
                </tr>
            `;
        }
    })();
</script>

