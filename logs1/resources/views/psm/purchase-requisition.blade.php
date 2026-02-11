<!-- resources/views/psm/purchase-requisition.blade.php -->
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bx-clipboard'></i>Purchase Requisition</h2>
    </div>
    <div class="text-right">
        <span class="text-md text-gray-600">Procurement & Sourcing Management</span>
    </div>
</div>

<!-- Stats Section - Moved Above Requisition Records and Redesigned -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <div class="bg-white border-l-4 border-blue-500 rounded-lg shadow-sm p-4 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-blue-500 uppercase tracking-wider">Total Requisitions</p>
                <p id="totalReqCount" class="text-2xl font-bold text-gray-800">0</p>
            </div>
            <div class="p-3 bg-blue-50 rounded-full">
                <i class='bx bx-file text-blue-500 text-2xl'></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white border-l-4 border-green-500 rounded-lg shadow-sm p-4 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-green-500 uppercase tracking-wider">Approved</p>
                <p id="approvedReqCount" class="text-2xl font-bold text-gray-800">0</p>
            </div>
            <div class="p-3 bg-green-50 rounded-full">
                <i class='bx bx-check-circle text-green-500 text-2xl'></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white border-l-4 border-yellow-500 rounded-lg shadow-sm p-4 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-yellow-500 uppercase tracking-wider">Pending</p>
                <p id="pendingReqCount" class="text-2xl font-bold text-gray-800">0</p>
            </div>
            <div class="p-3 bg-yellow-50 rounded-full">
                <i class='bx bx-time text-yellow-500 text-2xl'></i>
            </div>
        </div>
    </div>
    
    <div class="bg-white border-l-4 border-red-500 rounded-lg shadow-sm p-4 hover:shadow-md transition-shadow">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-red-500 uppercase tracking-wider">Rejected</p>
                <p id="rejectedReqCount" class="text-2xl font-bold text-gray-800">0</p>
            </div>
            <div class="p-3 bg-red-50 rounded-full">
                <i class='bx bx-x-circle text-red-500 text-2xl'></i>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg p-6">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">Requisition Records</h3>
        <div class="flex gap-3">
            <button id="addRequisitionBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-plus'></i>
                New Requisition
            </button>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="mb-6 grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="md:col-span-2">
            <input type="text" id="searchInput" placeholder="Search by ID, requester, or department..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
        </div>
        <div>
            <select id="statusFilter" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Status</option>
                <option value="Pending">Pending</option>
                <option value="Approved">Approved</option>
                <option value="Rejected">Rejected</option>
            </select>
        </div>
    </div>

    <!-- Requisition Table - Redesigned to match Purchase Management -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-800 font-bold text-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Requisition ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Items</th>
                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Requester / Dept</th>
                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Note</th>
                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="requisitionTableBody" class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex justify-center items-center py-4">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mr-3"></div>
                                Loading requisitions...
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination - Matching Purchase Management -->
    <div id="requisitionPager" class="flex items-center justify-between mt-4">
        <div id="requisitionPagerInfo" class="text-sm text-gray-600"></div>
        <div class="flex items-center gap-2">
            <button id="prevBtn" class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">Prev</button>
            <span id="pageDisplay" class="text-sm font-medium">1 / 1</span>
            <button id="nextBtn" class="px-3 py-1 border border-gray-300 rounded hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed">Next</button>
        </div>
    </div>
</div>

<!-- New Requisition Modal -->
<div id="requisitionModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl">
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h3 class="text-xl font-bold text-gray-800">New Purchase Requisition</h3>
            <button id="closeModal" class="text-gray-400 hover:text-gray-600 transition-colors">
                <i class='bx bx-x text-3xl'></i>
            </button>
        </div>
        
        <form id="requisitionForm" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Requisition ID</label>
                    <input type="text" id="req_id" name="req_id" readonly class="w-full px-4 py-2 bg-gray-50 border border-gray-300 rounded-lg font-mono text-blue-600 font-bold" value="">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Date</label>
                    <input type="date" id="req_date" name="req_date" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Requester Name *</label>
                    <input type="text" id="req_requester" name="req_requester" required placeholder="Enter requester name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Department *</label>
                    <input type="text" id="req_dept" name="req_dept" required placeholder="Enter department" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Requested Items *</label>
                <div id="itemsContainer" class="space-y-2 mb-2">
                    <div class="flex gap-2 item-row">
                        <input type="text" name="items[]" required placeholder="Enter item name/description" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <button type="button" class="remove-item px-3 py-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                            <i class='bx bx-trash text-xl'></i>
                        </button>
                    </div>
                </div>
                <button type="button" id="addItemBtn" class="text-sm text-blue-600 hover:text-blue-800 font-semibold flex items-center gap-1">
                    <i class='bx bx-plus-circle'></i> Add Another Item
                </button>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-1">Notes / Remarks</label>
                <textarea id="req_note" name="req_note" rows="3" placeholder="Additional details about the requisition..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t">
                <button type="button" id="cancelModalBtn" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-semibold transition-colors">Cancel</button>
                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold shadow-md transition-colors flex items-center gap-2">
                    <i class='bx bx-send'></i> Submit Purchase Requisition
                </button>
            </div>
        </form>
    </div>
</div>

<script>
(function() {
    const API_URL = '/api/v1/psm/requisitions';
    const JWT_TOKEN = localStorage.getItem('jwt');
    let currentPage = 1;
    let totalPages = 1;
    let currentFilters = {
        status: '',
        search: '',
        per_page: 10
    };

    // Swal Mixin
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

    // Elements
    const modal = document.getElementById('requisitionModal');
    const form = document.getElementById('requisitionForm');
    const addBtn = document.getElementById('addRequisitionBtn');
    const closeBtn = document.getElementById('closeModal');
    const cancelBtn = document.getElementById('cancelModalBtn');
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const prevBtn = document.getElementById('prevBtn');
    const nextBtn = document.getElementById('nextBtn');
    const addItemBtn = document.getElementById('addItemBtn');
    const itemsContainer = document.getElementById('itemsContainer');

    // Generate Requisition ID: REQN + YYYYMMDD + 5 random alphanumeric
    function generateReqID() {
        const now = new Date();
        const dateStr = now.getFullYear().toString() + 
                       (now.getMonth() + 1).toString().padStart(2, '0') + 
                       now.getDate().toString().padStart(2, '0');
        const random = Math.random().toString(36).substring(2, 7).toUpperCase();
        return `REQN${dateStr}${random}`;
    }

    // Modal Logic
    addBtn.addEventListener('click', () => {
        document.getElementById('req_id').value = generateReqID();
        document.getElementById('req_date').valueAsDate = new Date();
        form.reset();
        document.getElementById('req_id').value = generateReqID(); // Reset clears everything
        document.getElementById('req_date').valueAsDate = new Date();
        itemsContainer.innerHTML = `
            <div class="flex gap-2 item-row">
                <input type="text" name="items[]" required placeholder="Enter item name/description" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <button type="button" class="remove-item px-3 py-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                    <i class='bx bx-trash text-xl'></i>
                </button>
            </div>
        `;
        modal.classList.remove('hidden');
    });

    const hideModal = () => modal.classList.add('hidden');
    closeBtn.addEventListener('click', hideModal);
    cancelBtn.addEventListener('click', hideModal);

    // Items Logic
    addItemBtn.addEventListener('click', () => {
        const div = document.createElement('div');
        div.className = 'flex gap-2 item-row';
        div.innerHTML = `
            <input type="text" name="items[]" required placeholder="Enter item name/description" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            <button type="button" class="remove-item px-3 py-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                <i class='bx bx-trash text-xl'></i>
            </button>
        `;
        itemsContainer.appendChild(div);
    });

    itemsContainer.addEventListener('click', (e) => {
        if (e.target.closest('.remove-item')) {
            const rows = itemsContainer.querySelectorAll('.item-row');
            if (rows.length > 1) {
                e.target.closest('.item-row').remove();
            } else {
                Toast.fire({ icon: 'warning', title: 'At least one item is required' });
            }
        }
    });

    // Fetch and Render
    async function fetchRequisitions(page = 1) {
        try {
            const params = new URLSearchParams({
                page,
                ...currentFilters
            });
            
            const response = await fetch(`${API_URL}?${params}`, {
                headers: {
                    'Authorization': `Bearer ${JWT_TOKEN}`,
                    'Accept': 'application/json'
                }
            });
            const result = await response.json();
            
            if (result.success) {
                renderRequisitions(result.data);
                updatePagination(result.meta);
                updateStats(result.stats);
            } else {
                showError('Failed to fetch requisitions');
            }
        } catch (error) {
            console.error('Error:', error);
            showError('An error occurred while fetching data');
        }
    }

    function renderRequisitions(requisitions) {
        const tbody = document.getElementById('requisitionTableBody');
        if (!tbody) return;

        if (requisitions.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center gap-2">
                            <i class='bx bx-clipboard text-4xl text-gray-300'></i>
                            <p>No requisition records found</p>
                        </div>
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = requisitions.map(req => `
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-600">
                    ${req.req_id}
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                    <div class="max-w-xs">
                        ${renderItems(req.req_items)}
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    <div class="font-semibold text-gray-800">${req.req_requester}</div>
                    <div class="text-xs text-gray-400 uppercase tracking-tighter">${req.req_dept}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    ${formatDate(req.req_date)}
                </td>
                <td class="px-6 py-4 text-sm text-gray-600">
                    <div class="max-w-xs truncate" title="${req.req_note || ''}">
                        ${req.req_note || '-'}
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-3 py-1 text-xs font-bold rounded-full ${getStatusClass(req.req_status)}">
                        ${req.req_status.toUpperCase()}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex justify-end gap-1">
                        <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg" title="View Details">
                            <i class='bx bx-show text-xl'></i>
                        </button>
                        <button class="p-2 text-red-600 hover:bg-red-50 rounded-lg" title="Delete">
                            <i class='bx bx-trash text-xl'></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    }

    function renderItems(items) {
        if (!items) return '-';
        const parsed = Array.isArray(items) ? items : (typeof items === 'string' ? JSON.parse(items) : []);
        return parsed.map(item => `<div class="flex items-start gap-1"><span class="mt-1.5 w-1 h-1 rounded-full bg-gray-400 shrink-0"></span><span>${item}</span></div>`).join('');
    }

    function formatDate(dateStr) {
        if (!dateStr) return '-';
        const date = new Date(dateStr);
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });
    }

    function getStatusClass(status) {
        switch (status.toLowerCase()) {
            case 'approved': return 'bg-green-100 text-green-700 border border-green-200';
            case 'pending': return 'bg-yellow-100 text-yellow-700 border border-yellow-200';
            case 'rejected': return 'bg-red-100 text-red-700 border border-red-200';
            default: return 'bg-gray-100 text-gray-700 border border-gray-200';
        }
    }

    function updateStats(stats) {
        if (!stats) return;
        document.getElementById('totalReqCount').textContent = stats.total || 0;
        document.getElementById('approvedReqCount').textContent = stats.approved || 0;
        document.getElementById('pendingReqCount').textContent = stats.pending || 0;
        document.getElementById('rejectedReqCount').textContent = stats.rejected || 0;
    }

    function updatePagination(meta) {
        if (!meta) return;
        currentPage = meta.current_page;
        totalPages = meta.last_page;
        document.getElementById('pageDisplay').textContent = `${currentPage} / ${totalPages}`;
        document.getElementById('requisitionPagerInfo').textContent = `Showing ${(meta.current_page - 1) * meta.per_page + 1} to ${Math.min(meta.current_page * meta.per_page, meta.total)} of ${meta.total} results`;
        
        prevBtn.disabled = currentPage === 1;
        nextBtn.disabled = currentPage === totalPages;
    }

    // Submit Logic
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const formData = new FormData(form);
        const items = Array.from(formData.getAll('items[]')).filter(i => i.trim() !== '');
        
        if (items.length === 0) {
            Toast.fire({ icon: 'error', title: 'Please add at least one item' });
            return;
        }

        const payload = {
            req_id: formData.get('req_id'),
            req_date: formData.get('req_date'),
            req_requester: formData.get('req_requester'),
            req_dept: formData.get('req_dept'),
            req_items: items,
            req_note: formData.get('req_note')
        };

        try {
            const response = await fetch(API_URL, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${JWT_TOKEN}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(payload)
            });
            const result = await response.json();

            if (result.success) {
                hideModal();
                Toast.fire({ icon: 'success', title: 'Requisition submitted successfully' });
                fetchRequisitions(1);
            } else {
                Toast.fire({ icon: 'error', title: result.message || 'Failed to submit requisition' });
            }
        } catch (error) {
            console.error('Error:', error);
            Toast.fire({ icon: 'error', title: 'An error occurred during submission' });
        }
    });

    // Filters & Pagination Events
    let searchTimeout;
    searchInput.addEventListener('input', (e) => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            currentFilters.search = e.target.value;
            fetchRequisitions(1);
        }, 500);
    });

    statusFilter.addEventListener('change', (e) => {
        currentFilters.status = e.target.value;
        fetchRequisitions(1);
    });

    prevBtn.addEventListener('click', () => {
        if (currentPage > 1) fetchRequisitions(currentPage - 1);
    });

    nextBtn.addEventListener('click', () => {
        if (currentPage < totalPages) fetchRequisitions(currentPage + 1);
    });

    function showError(message) {
        const tbody = document.getElementById('requisitionTableBody');
        if (tbody) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-red-500">
                        <div class="flex flex-col items-center gap-2">
                            <i class='bx bx-error-circle text-4xl'></i>
                            <p>${message}</p>
                            <button onclick="location.reload()" class="mt-2 text-sm underline">Retry</button>
                        </div>
                    </td>
                </tr>
            `;
        }
    }

    // Initial load
    fetchRequisitions();
})();
</script>
