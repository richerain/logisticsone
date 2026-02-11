<!-- resources/views/psm/purchase-requisition.blade.php -->
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bx-clipboard'></i>Purchase Requisition</h2>
    </div>
    <div class="text-right">
        <span class="text-md text-gray-600">Procurement & Sourcing Management</span>
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

    <!-- Stats Section -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-blue-500 rounded-lg">
                    <i class='bx bx-file text-white text-2xl'></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-blue-700">Total Requisitions</p>
                    <p id="totalReqCount" class="text-2xl font-bold text-blue-900">0</p>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 rounded-lg p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-green-500 rounded-lg">
                    <i class='bx bx-check-circle text-white text-2xl'></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-green-700">Approved</p>
                    <p id="approvedReqCount" class="text-2xl font-bold text-green-900">0</p>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 border border-yellow-200 rounded-lg p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-500 rounded-lg">
                    <i class='bx bx-time text-white text-2xl'></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-yellow-700">Pending</p>
                    <p id="pendingReqCount" class="text-2xl font-bold text-yellow-900">0</p>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-red-50 to-red-100 border border-red-200 rounded-lg p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-red-500 rounded-lg">
                    <i class='bx bx-x-circle text-white text-2xl'></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-red-700">Cancelled</p>
                    <p id="cancelledReqCount" class="text-2xl font-bold text-red-900">0</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Requisition Table -->
    <div class="overflow-x-auto border border-gray-100 rounded-xl">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Requisition ID</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Items</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Requester</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Department</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Note</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody id="requisitionTableBody" class="divide-y divide-gray-50">
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center gap-2">
                            <i class='bx bx-loader-alt bx-spin text-4xl text-brand-primary'></i>
                            <p>Loading requisitions...</p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
(function() {
    const API_URL = '/api/v1/psm/requisitions';
    const JWT_TOKEN = localStorage.getItem('jwt');

    async function fetchRequisitions() {
        try {
            const response = await fetch(API_URL, {
                headers: {
                    'Authorization': `Bearer ${JWT_TOKEN}`,
                    'Accept': 'application/json'
                }
            });
            const result = await response.json();
            
            if (result.success) {
                renderRequisitions(result.data);
                updateStats(result.data);
            } else {
                showError('Failed to fetch requisitions');
            }
        } catch (error) {
            console.error('Error:', error);
            showError('An error occurred while fetching requisitions');
        }
    }

    function renderRequisitions(requisitions) {
        const tbody = document.getElementById('requisitionTableBody');
        if (!tbody) return;

        if (requisitions.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-gray-500">
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
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 border-r border-gray-100">
                    ${req.req_id}
                </td>
                <td class="px-6 py-4 text-sm text-gray-600 border-r border-gray-100">
                    <div class="max-w-xs overflow-hidden">
                        ${renderItems(req.req_items)}
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 border-r border-gray-100">
                    ${req.req_requester}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 border-r border-gray-100">
                    ${req.req_dept}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 border-r border-gray-100">
                    ${formatDate(req.req_date)}
                </td>
                <td class="px-6 py-4 text-sm text-gray-600 border-r border-gray-100">
                    <div class="max-w-xs truncate" title="${req.req_note || ''}">
                        ${req.req_note || '-'}
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap border-r border-gray-100">
                    <span class="px-2 py-1 text-xs font-semibold rounded-full ${getStatusClass(req.req_status)}">
                        ${req.req_status.charAt(0).toUpperCase() + req.req_status.slice(1)}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex justify-end gap-2">
                        <button class="text-blue-600 hover:text-blue-900" title="View Details">
                            <i class='bx bx-show text-xl'></i>
                        </button>
                        <button class="text-red-600 hover:text-red-900" title="Delete">
                            <i class='bx bx-trash text-xl'></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    }

    function renderItems(items) {
        if (!items) return '-';
        try {
            const parsed = typeof items === 'string' ? JSON.parse(items) : items;
            if (Array.isArray(parsed)) {
                return parsed.map(item => `• ${item.name || item}`).join('<br>');
            }
            return items;
        } catch (e) {
            return items;
        }
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
            case 'approved': return 'bg-green-100 text-green-800';
            case 'pending': return 'bg-yellow-100 text-yellow-800';
            case 'cancelled': return 'bg-red-100 text-red-800';
            default: return 'bg-gray-100 text-gray-800';
        }
    }

    function updateStats(requisitions) {
        document.getElementById('totalReqCount').textContent = requisitions.length;
        document.getElementById('approvedReqCount').textContent = requisitions.filter(r => r.req_status.toLowerCase() === 'approved').length;
        document.getElementById('pendingReqCount').textContent = requisitions.filter(r => r.req_status.toLowerCase() === 'pending').length;
        document.getElementById('cancelledReqCount').textContent = requisitions.filter(r => r.req_status.toLowerCase() === 'cancelled').length;
    }

    function showError(message) {
        const tbody = document.getElementById('requisitionTableBody');
        if (tbody) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="px-6 py-12 text-center text-red-500">
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
