
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bxs-wallet'></i>Budgeting</h2>
    </div>
    <div class="text-right">
        <span class="text-md text-gray-600">Procurement & Sourcing Management</span>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg p-6">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">Budget Overview</h3>
        <div class="flex gap-3">
            <button id="addBudgetBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-plus'></i>
                Create Budget Plan
            </button>
        </div>
    </div>

    <!-- Stats Section -->
    <div id="statsSection" class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-blue-500 rounded-lg">
                    <i class='bx bx-wallet text-white text-2xl'></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500 font-medium">Total Budget</p>
                    <p class="text-2xl font-bold text-gray-800" id="totalBudget">₱0.00</p>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 rounded-lg p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-green-500 rounded-lg">
                    <i class='bx bx-check-circle text-white text-2xl'></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500 font-medium">Spent</p>
                    <p class="text-2xl font-bold text-gray-800" id="spentBudget">₱0.00</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 border border-yellow-200 rounded-lg p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-500 rounded-lg">
                    <i class='bx bx-time-five text-white text-2xl'></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500 font-medium">Remaining</p>
                    <p class="text-2xl font-bold text-gray-800" id="remainingBudget">₱0.00</p>
                </div>
            </div>
        </div>

        <div class="bg-gradient-to-r from-purple-50 to-purple-100 border border-purple-200 rounded-lg p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-purple-500 rounded-lg">
                    <i class='bx bx-bar-chart-alt-2 text-white text-2xl'></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm text-gray-500 font-medium">Utilization</p>
                    <p class="text-2xl font-bold text-gray-800" id="utilizationRate">0%</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Budgets Table Container -->
    <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="min-w-full divide-y divide-gray-200" id="budgetsTable">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Budget ID</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Allocated</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Spent</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Remaining</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Status</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Validity Type</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Valid From</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Valid To</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Assigned Date</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Department</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Module</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Submodule</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider whitespace-nowrap">Description</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200" id="budgetsTableBody">
                <tr>
                    <td colspan="13" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class='bx bx-loader-alt bx-spin text-4xl mb-2'></i>
                            <p>Loading budgets...</p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        console.log('Budgeting Module Initialized');
        fetchBudgets();
    });

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
            } else if (data.success && Array.isArray(data.data) && data.data.length === 0) {
                renderEmptyState('No budgets found.');
                updateStats([]); 
            } else {
                renderEmptyState(data.message || 'No budgets found.');
                updateStats([]);
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
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${budget.bud_id}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 font-bold">${formatCurrency(budget.bud_allocated_amount)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-orange-600 font-bold">${formatCurrency(budget.bud_spent_amount)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-bold">${formatCurrency(budget.bud_remaining_amount)}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${healthColor.bg} ${healthColor.text}">
                        ${budget.bud_amount_status_health}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${budget.bud_validity_type}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDate(budget.bud_valid_from)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDate(budget.bud_valid_to)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDate(budget.bud_assigned_date)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${budget.bud_for_department}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${budget.bud_for_module}</td>
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
                        <button class="mt-4 text-blue-600 hover:text-blue-800 text-sm font-medium">Create your first budget plan</button>
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

        document.getElementById('totalBudget').textContent = formatCurrency(total);
        document.getElementById('spentBudget').textContent = formatCurrency(spent);
        document.getElementById('remainingBudget').textContent = formatCurrency(remaining);
        
        const utilization = total > 0 ? (spent / total) * 100 : 0;
        document.getElementById('utilizationRate').textContent = utilization.toFixed(1) + '%';
    }

    function getHealthColor(status) {
        switch(status) {
            case 'Healthy': return { bg: 'bg-green-100', text: 'text-green-800' };
            case 'Stable': return { bg: 'bg-blue-100', text: 'text-blue-800' };
            case 'Alert': return { bg: 'bg-yellow-100', text: 'text-yellow-800' };
            case 'Exceeded': return { bg: 'bg-red-100', text: 'text-red-800' };
            default: return { bg: 'bg-gray-100', text: 'text-gray-800' };
        }
    }

    function formatCurrency(amount) {
        const val = parseFloat(amount || 0);
        return '₱' + val.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    }

    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        return new Date(dateString).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
    }
</script>

