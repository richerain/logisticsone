@extends('layouts.app')

@section('title', 'DTLR Tracking Logs')

@section('content')
<div class="module-content bg-white rounded-xl p-6 shadow block">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Document Tracking Logs</h2>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
        <div class="stat bg-primary text-primary-content rounded-lg p-4">
            <div class="stat-figure text-secondary">
                <i class="bx bxs-notepad text-3xl"></i>
            </div>
            <div class="stat-title text-white">Total Logs</div>
            <div class="stat-value text-white text-2xl" id="statTotal">0</div>
        </div>
        <div class="stat bg-info text-info-content rounded-lg p-4">
            <div class="stat-figure text-secondary">
                <i class="bx bxs-show text-3xl"></i>
            </div>
            <div class="stat-title text-white">Accessed</div>
            <div class="stat-value text-white text-2xl" id="statAccessed">0</div>
        </div>
        <div class="stat bg-warning text-warning-content rounded-lg p-4">
            <div class="stat-figure text-secondary">
                <i class="bx bxs-printer text-3xl"></i>
            </div>
            <div class="stat-title text-white">Printed</div>
            <div class="stat-value text-white text-2xl" id="statPrinted">0</div>
        </div>
        <div class="stat bg-success text-success-content rounded-lg p-4">
            <div class="stat-figure text-secondary">
                <i class="bx bxs-transfer text-3xl"></i>
            </div>
            <div class="stat-title text-white">Transferred</div>
            <div class="stat-value text-white text-2xl" id="statTransferred">0</div>
        </div>
        <div class="stat bg-secondary text-secondary-content rounded-lg p-4">
            <div class="stat-figure text-secondary">
                <i class="bx bxs-check-shield text-3xl"></i>
            </div>
            <div class="stat-title text-white">Reviewed</div>
            <div class="stat-value text-white text-2xl" id="statReviewed">0</div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card bg-base-100 shadow-sm mb-6">
        <div class="card-body">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" id="searchInput" placeholder="Search by tracking number, document title, or notes..." class="input input-bordered w-full">
                </div>
                <div class="flex gap-2">
                    <select id="actionFilter" class="select select-bordered">
                        <option value="">All Actions</option>
                        <option value="accessed">Accessed</option>
                        <option value="printed">Printed</option>
                        <option value="transferred">Transferred</option>
                        <option value="reviewed">Reviewed</option>
                        <option value="status_changed">Status Changed</option>
                    </select>
                    <button class="btn btn-outline" onclick="resetFilters()">
                        <i class="bx bx-reset"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Tracking Logs Table -->
    <div class="overflow-x-auto">
        <table class="table table-zebra w-full">
            <thead>
                <tr>
                    <th>Log ID</th>
                    <th>Document</th>
                    <th>Action</th>
                    <th>Performed By</th>
                    <th>From/To Branch</th>
                    <th>Timestamp</th>
                    <th>IP Address</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="logsTableBody">
                <!-- Data will be populated by JavaScript -->
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-center mt-6">
        <div class="join" id="paginationContainer">
            <!-- Pagination will be populated by JavaScript -->
        </div>
    </div>
</div>

<!-- Log Details Modal -->
<div id="logDetailsModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box max-w-2xl">
        <h3 class="font-bold text-lg mb-4">Log Details</h3>
        <div id="logDetailsContent">
            <!-- Content will be populated by JavaScript -->
        </div>
        <div class="modal-action">
            <button class="btn btn-ghost" onclick="closeLogDetailsModal()">Close</button>
        </div>
    </div>
</div>

<script>
// ==================== CONFIGURATION ====================
const API_BASE_URL = 'http://localhost:8001/api/dtlr';
let currentPage = 1;
let currentFilters = {
    search: '',
    action: ''
};

// ==================== INITIALIZATION ====================
document.addEventListener('DOMContentLoaded', function() {
    loadTrackingLogs();
    setupEventListeners();
});

function setupEventListeners() {
    // Search with debounce
    let searchTimeout;
    document.getElementById('searchInput').addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            currentFilters.search = e.target.value;
            currentPage = 1;
            loadTrackingLogs();
        }, 500);
    });

    // Filter changes
    document.getElementById('actionFilter').addEventListener('change', function(e) {
        currentFilters.action = e.target.value;
        currentPage = 1;
        loadTrackingLogs();
    });
}

// ==================== DATA LOADING ====================
async function loadTrackingLogs() {
    showLoading();
    try {
        const params = new URLSearchParams({
            page: currentPage,
            ...currentFilters
        });

        const response = await fetch(`${API_BASE_URL}/document-logs?${params}`);
        const result = await response.json();

        if (result.success) {
            updateStats(result.stats);
            populateLogsTable(result.data.data);
            setupPagination(result.data);
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        showToast('error', 'Failed to load tracking logs: ' + error.message);
    } finally {
        hideLoading();
    }
}

// ==================== TABLE MANAGEMENT ====================
function populateLogsTable(logs) {
    const tbody = document.getElementById('logsTableBody');
    
    if (logs.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-8 text-gray-500">
                    <i class="bx bxs-notepad text-4xl mb-2 block"></i>
                    No tracking logs found
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = logs.map(log => `
        <tr>
            <td class="font-mono">LOG${String(log.id).padStart(5, '0')}</td>
            <td>
                <div class="flex items-center gap-2">
                    <i class="bx bxs-file text-primary"></i>
                    <div>
                        <div class="font-medium">${log.document.title}</div>
                        <div class="text-xs text-gray-500 font-mono">${log.document.tracking_number}</div>
                    </div>
                </div>
            </td>
            <td>
                <span class="badge badge-${getActionBadgeClass(log.action)}">
                    ${getActionIcon(log.action)} ${log.action.replace('_', ' ').toUpperCase()}
                </span>
            </td>
            <td>
                <div class="flex items-center gap-2">
                    <div class="avatar placeholder">
                        <div class="bg-neutral text-neutral-content rounded-full w-8">
                            <span class="text-xs">${log.performer.username.charAt(0).toUpperCase()}</span>
                        </div>
                    </div>
                    <span>${log.performer.username}</span>
                </div>
            </td>
            <td>
                ${log.action === 'transferred' ? `
                    <div class="text-xs">
                        <div>From: ${log.from_branch.name}</div>
                        <div>To: ${log.to_branch.name}</div>
                    </div>
                ` : log.from_branch ? `
                    <div class="text-xs">${log.from_branch.name}</div>
                ` : 'N/A'}
            </td>
            <td>
                <div class="text-sm">
                    <div>${new Date(log.timestamp).toLocaleDateString()}</div>
                    <div class="text-gray-500">${new Date(log.timestamp).toLocaleTimeString()}</div>
                </div>
            </td>
            <td class="font-mono text-xs">${log.ip_address || 'N/A'}</td>
            <td>
                <button class="btn btn-sm btn-circle btn-outline" title="View Details" onclick="viewLogDetails(${log.id})">
                    <i class="bx bx-show"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

function getActionBadgeClass(action) {
    const classes = {
        'accessed': 'info',
        'printed': 'warning',
        'transferred': 'success',
        'reviewed': 'secondary',
        'status_changed': 'primary'
    };
    return classes[action] || 'neutral';
}

function getActionIcon(action) {
    const icons = {
        'accessed': 'bx-show',
        'printed': 'bx-printer',
        'transferred': 'bx-transfer',
        'reviewed': 'bx-check-shield',
        'status_changed': 'bx-edit'
    };
    return `<i class="bx ${icons[action] || 'bx-notepad'}"></i>`;
}

// ==================== MODAL MANAGEMENT ====================
async function viewLogDetails(logId) {
    try {
        showLoading();
        const response = await fetch(`${API_BASE_URL}/document-logs/${logId}`);
        const result = await response.json();
        
        if (result.success) {
            const log = result.data;
            document.getElementById('logDetailsContent').innerHTML = `
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="font-semibold">Log ID:</label>
                            <p class="font-mono">LOG${String(log.id).padStart(5, '0')}</p>
                        </div>
                        <div>
                            <label class="font-semibold">Action:</label>
                            <p><span class="badge badge-${getActionBadgeClass(log.action)}">${log.action.replace('_', ' ').toUpperCase()}</span></p>
                        </div>
                    </div>
                    
                    <div class="border-t pt-4">
                        <label class="font-semibold">Document Information:</label>
                        <div class="bg-base-200 p-3 rounded mt-2">
                            <div class="grid grid-cols-2 gap-2 text-sm">
                                <div>
                                    <span class="font-medium">Tracking No:</span>
                                    <p class="font-mono">${log.document.tracking_number}</p>
                                </div>
                                <div>
                                    <span class="font-medium">Title:</span>
                                    <p>${log.document.title}</p>
                                </div>
                                <div>
                                    <span class="font-medium">Type:</span>
                                    <p>${log.document.document_type.name}</p>
                                </div>
                                <div>
                                    <span class="font-medium">Status:</span>
                                    <p><span class="badge badge-${getStatusBadgeClass(log.document.status)}">${log.document.status}</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="font-semibold">Performed By:</label>
                            <div class="flex items-center gap-2 mt-1">
                                <div class="avatar placeholder">
                                    <div class="bg-neutral text-neutral-content rounded-full w-8">
                                        <span class="text-xs">${log.performer.username.charAt(0).toUpperCase()}</span>
                                    </div>
                                </div>
                                <div>
                                    <p>${log.performer.username}</p>
                                    <p class="text-xs text-gray-500">${log.performer.role}</p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="font-semibold">Timestamp:</label>
                            <p>${new Date(log.timestamp).toLocaleString()}</p>
                        </div>
                    </div>
                    
                    ${log.from_branch || log.to_branch ? `
                    <div class="grid grid-cols-2 gap-4">
                        ${log.from_branch ? `
                        <div>
                            <label class="font-semibold">From Branch:</label>
                            <p>${log.from_branch.name}</p>
                            ${log.from_branch.location ? `<p class="text-xs text-gray-500">${log.from_branch.location}</p>` : ''}
                        </div>
                        ` : ''}
                        ${log.to_branch ? `
                        <div>
                            <label class="font-semibold">To Branch:</label>
                            <p>${log.to_branch.name}</p>
                            ${log.to_branch.location ? `<p class="text-xs text-gray-500">${log.to_branch.location}</p>` : ''}
                        </div>
                        ` : ''}
                    </div>
                    ` : ''}
                    
                    ${log.notes ? `
                    <div>
                        <label class="font-semibold">Notes:</label>
                        <p class="bg-base-200 p-3 rounded mt-1">${log.notes}</p>
                    </div>
                    ` : ''}
                    
                    <div>
                        <label class="font-semibold">IP Address:</label>
                        <p class="font-mono">${log.ip_address || 'Not recorded'}</p>
                    </div>
                </div>
            `;
            document.getElementById('logDetailsModal').showModal();
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        showToast('error', 'Failed to load log details: ' + error.message);
    } finally {
        hideLoading();
    }
}

function closeLogDetailsModal() {
    document.getElementById('logDetailsModal').close();
}

function getStatusBadgeClass(status) {
    const classes = {
        'pending': 'warning',
        'processed': 'info',
        'approved': 'success',
        'archived': 'neutral',
        'rejected': 'error'
    };
    return classes[status] || 'neutral';
}

// ==================== UTILITY FUNCTIONS ====================
function updateStats(stats) {
    document.getElementById('statTotal').textContent = stats.total;
    document.getElementById('statAccessed').textContent = stats.accessed;
    document.getElementById('statPrinted').textContent = stats.printed;
    document.getElementById('statTransferred').textContent = stats.transferred;
    document.getElementById('statReviewed').textContent = stats.reviewed;
}

function setupPagination(paginationData) {
    const container = document.getElementById('paginationContainer');
    const { current_page, last_page } = paginationData;
    
    let paginationHTML = '';
    
    // Previous button
    paginationHTML += `
        <button class="join-item btn btn-sm ${current_page === 1 ? 'btn-disabled' : ''}" 
                onclick="changePage(${current_page - 1})">
            <i class="bx bx-chevron-left"></i>
        </button>
    `;
    
    // Page numbers
    for (let i = 1; i <= last_page; i++) {
        if (i === 1 || i === last_page || (i >= current_page - 1 && i <= current_page + 1)) {
            paginationHTML += `
                <button class="join-item btn btn-sm ${current_page === i ? 'btn-active' : ''}" 
                        onclick="changePage(${i})">
                    ${i}
                </button>
            `;
        } else if (i === current_page - 2 || i === current_page + 2) {
            paginationHTML += `<button class="join-item btn btn-sm btn-disabled">...</button>`;
        }
    }
    
    // Next button
    paginationHTML += `
        <button class="join-item btn btn-sm ${current_page === last_page ? 'btn-disabled' : ''}" 
                onclick="changePage(${current_page + 1})">
            <i class="bx bx-chevron-right"></i>
        </button>
    `;
    
    container.innerHTML = paginationHTML;
}

function changePage(page) {
    currentPage = page;
    loadTrackingLogs();
}

function resetFilters() {
    currentFilters = { search: '', action: '' };
    document.getElementById('searchInput').value = '';
    document.getElementById('actionFilter').value = '';
    currentPage = 1;
    loadTrackingLogs();
}

function showLoading() {
    document.body.style.cursor = 'wait';
}

function hideLoading() {
    document.body.style.cursor = 'default';
}

function showToast(type, message) {
    const toast = document.createElement('div');
    toast.className = `toast toast-top toast-end`;
    toast.innerHTML = `
        <div class="alert alert-${type}">
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}
</script>
@endsection