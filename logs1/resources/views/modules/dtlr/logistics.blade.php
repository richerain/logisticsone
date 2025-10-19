@extends('layouts.app')

@section('title', 'DTLR Logistics Record')

@section('content')
<div class="module-content bg-white rounded-xl p-6 shadow block">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Logistics Record</h2>
        <button class="btn btn-primary" onclick="openAddLogModal()">
            <i class="bx bxs-plus-circle mr-2"></i>Add Log Entry
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="stat text-primary-content rounded-lg p-4 shadow-lg border-l-4 border-primary">
            <div class="stat-figure text-primary">
                <i class="bx bxs-notepad text-3xl"></i>
            </div>
            <div class="stat-title text-primary">Total Logs</div>
            <div class="stat-value text-primary" id="totalLogs">0</div>
        </div>
        <div class="stat text-info-content rounded-lg p-4 shadow-lg border-l-4 border-info">
            <div class="stat-figure text-info">
                <i class="bx bxs-upload text-3xl"></i>
            </div>
            <div class="stat-title text-info">Upload Actions</div>
            <div class="stat-value text-info" id="uploadLogs">0</div>
        </div>
        <div class="stat text-success-content rounded-lg p-4 shadow-lg border-l-4 border-success">
            <div class="stat-figure text-success">
                <i class="bx bxs-check-circle text-3xl"></i>
            </div>
            <div class="stat-title text-success">Approved</div>
            <div class="stat-value text-success" id="approvedLogs">0</div>
        </div>
        <div class="stat text-warning-content rounded-lg p-4 shadow-lg border-l-4 border-warning">
            <div class="stat-figure text-warning">
                <i class="bx bxs-ai text-3xl"></i>
            </div>
            <div class="stat-title text-warning">AI/OCR Used</div>
            <div class="stat-value text-warning" id="aiUsedLogs">0</div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card bg-base-100 shadow-sm mb-6">
        <div class="card-body">
            <div class="flex flex-col md:flex-row gap-4">
                <label class="input flex flex-1 items-center gap-2 border border-gray-300 rounded-lg px-4 py-2">
                    <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <g
                        stroke-linejoin="round"
                        stroke-linecap="round"
                        stroke-width="2.5"
                        fill="none"
                        stroke="currentColor"
                        >
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                        </g>
                    </svg>
                    <input class="w-full" type="search" id="searchInput" placeholder="Search logs..." onkeyup="handleSearch()" />
                </label>
                <div class="flex gap-2">
                    <select id="actionFilter" class="select select-bordered">
                        <option value="">All Actions</option>
                        <option value="uploaded">Uploaded</option>
                        <option value="approved">Approved</option>
                        <option value="delivered">Delivered</option>
                        <option value="verified">Verified</option>
                        <option value="created">Created</option>
                    </select>
                    <select id="moduleFilter" class="select select-bordered">
                        <option value="">All Modules</option>
                        <option value="Procurement">Procurement</option>
                        <option value="Smart Warehousing">Smart Warehousing</option>
                        <option value="Project Logistics">Project Logistics</option>
                        <option value="Asset Management">Asset Management</option>
                        <option value="Document Tracking">Document Tracking</option>
                    </select>
                </div>
                <button class="btn bg-teal-600 text-white hover:bg-teal-700" onclick="exportLogs()">
                    <i class='bx bx-export mr-2'></i>Export Logs
                </button>
            </div>
        </div>
    </div>

    <!-- Logistics Records Table -->
    <div class="overflow-x-auto">
        <table class="table table-zebra w-full">
            <thead>
                <tr class="bg-gray-800 text-white">
                    <th class="font-bold">Log ID</th>
                    <th>Action</th>
                    <th>Module</th>
                    <th>Timestamp</th>
                    <th>Performed By</th>
                    <th>AI/OCR</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="logisticsTableBody">
                <!-- Loading row -->
                <tr id="loadingRow">
                    <td colspan="7" class="text-center py-8">
                        <div class="flex justify-center items-center">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-primary"></div>
                            <span class="ml-2">Loading logs...</span>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination section -->
    <div class="flex justify-between items-center mt-6">
        <div class="text-sm text-gray-700">
            Showing <span id="paginationFrom">0</span> to <span id="paginationTo">0</span> of <span id="paginationTotal">0</span> results
        </div>
        <div class="join" id="paginationContainer">
            <!-- Pagination buttons will be generated here -->
        </div>
    </div>
</div>

<!-- Add Log Modal -->
<div id="addLogModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box max-w-2xl">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-lg">Add New Log Entry</h3>
            <button class="btn btn-sm btn-circle" onclick="closeAddLogModal()">✕</button>
        </div>
        <form id="addLogForm">
            <div class="grid grid-cols-1 gap-4">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text">Action</span>
                            </div>
                            <select class="select select-bordered w-full" name="action" required>
                                <option value="">Select Action</option>
                                <option value="uploaded">Uploaded</option>
                                <option value="approved">Approved</option>
                                <option value="delivered">Delivered</option>
                                <option value="verified">Verified</option>
                                <option value="created">Created</option>
                                <option value="updated">Updated</option>
                                <option value="deleted">Deleted</option>
                            </select>
                        </label>
                    </div>
                    <div>
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text">Module</span>
                            </div>
                            <select class="select select-bordered w-full" name="module" required>
                                <option value="">Select Module</option>
                                <option value="Procurement">Procurement</option>
                                <option value="Smart Warehousing">Smart Warehousing</option>
                                <option value="Project Logistics">Project Logistics</option>
                                <option value="Asset Management">Asset Management</option>
                                <option value="Document Tracking">Document Tracking</option>
                            </select>
                        </label>
                    </div>
                </div>
                <div>
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text">Description</span>
                        </div>
                        <textarea class="textarea textarea-bordered h-20" name="description" placeholder="Describe the action performed..." required></textarea>
                    </label>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text">Performed By</span>
                            </div>
                            <input type="text" class="input input-bordered w-full" name="performed_by" value="${getCurrentUser()}" required>
                        </label>
                    </div>
                    <div>
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text">AI/OCR Used</span>
                            </div>
                            <select class="select select-bordered w-full" name="ai_ocr_used" required>
                                <option value="no">No</option>
                                <option value="yes">Yes</option>
                            </select>
                        </label>
                    </div>
                </div>
                <div>
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text">Related Document/Transaction</span>
                        </div>
                        <input type="text" class="input input-bordered w-full" name="related_reference" placeholder="e.g., DOC-001, PO-005">
                    </label>
                </div>
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="closeAddLogModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Add Log Entry</button>
            </div>
        </form>
    </div>
</div>

<!-- View Log Modal -->
<div id="viewLogModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box max-w-2xl">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-lg">Log Entry Details</h3>
            <button class="btn btn-sm btn-circle" onclick="closeViewLogModal()">✕</button>
        </div>
        <div id="viewLogModalContent">
            <!-- Content will be loaded here -->
        </div>
        <div class="modal-action">
            <button class="btn btn-ghost" onclick="closeViewLogModal()">Close</button>
        </div>
    </div>
</div>

<!-- Edit Log Modal -->
<div id="editLogModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box max-w-2xl">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-lg">Edit Log Entry</h3>
            <button class="btn btn-sm btn-circle" onclick="closeEditLogModal()">✕</button>
        </div>
        <form id="editLogForm">
            <div id="editLogModalContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="closeEditLogModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
// ==================== CONFIGURATION ====================
const API_BASE_URL = 'http://localhost:8001/api/dtlr';
let currentPage = 1;
const itemsPerPage = 10;
let allLogs = [];
let filteredLogs = [];

// ==================== INITIALIZATION ====================
document.addEventListener('DOMContentLoaded', function() {
    loadLogs();
    loadLogStats();
});

// ==================== STATS FUNCTIONS ====================
async function loadLogStats() {
    try {
        const response = await fetch(`${API_BASE_URL}/stats/overview`);
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('totalLogs').textContent = data.data.logs.total_logs || 0;
            document.getElementById('uploadLogs').textContent = data.data.logs.upload_actions || 0;
            document.getElementById('approvedLogs').textContent = data.data.logs.approved_actions || 0;
            document.getElementById('aiUsedLogs').textContent = data.data.logs.ai_used || 0;
        }
    } catch (error) {
        console.error('Error loading stats:', error);
    }
}

// ==================== LOG FUNCTIONS ====================
async function loadLogs() {
    showLoading();
    try {
        const searchParams = new URLSearchParams({
            page: currentPage,
            limit: itemsPerPage,
            search: document.getElementById('searchInput').value,
            action: document.getElementById('actionFilter').value,
            module: document.getElementById('moduleFilter').value
        });

        const response = await fetch(`${API_BASE_URL}/logistics-records?${searchParams}`);
        const data = await response.json();
        
        if (data.success) {
            allLogs = data.data.records || [];
            filteredLogs = allLogs;
            renderLogs();
            updatePagination(data.data.total, data.data.current_page, data.data.last_page);
        } else {
            throw new Error(data.message);
        }
    } catch (error) {
        console.error('Error loading logs:', error);
        showError('Failed to load logs');
    } finally {
        hideLoading();
    }
}

function renderLogs() {
    const tbody = document.getElementById('logisticsTableBody');
    tbody.innerHTML = '';

    if (filteredLogs.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-8 text-gray-500">
                    No log entries found
                </td>
            </tr>
        `;
        return;
    }

    filteredLogs.forEach(log => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="font-semibold">${log.log_id}</td>
            <td>
                <span class="badge ${getActionBadgeClass(log.action)}">${log.action}</span>
            </td>
            <td>${log.module}</td>
            <td>${new Date(log.timestamp).toLocaleString()}</td>
            <td>${log.performed_by}</td>
            <td>
                <span class="badge ${log.ai_ocr_used ? 'badge-success' : 'badge-neutral'}">
                    ${log.ai_ocr_used ? 'Yes' : 'No'}
                </span>
            </td>
            <td>
                <div class="flex gap-1">
                    <button class="btn btn-sm bg-blue-400 btn-circle" onclick="viewLog('${log.id}')">
                        <i class='bx bx-show-alt'></i>
                    </button>
                    <button class="btn btn-sm bg-yellow-400 btn-circle" onclick="editLog('${log.id}')">
                        <i class='bx bx-edit'></i>
                    </button>
                    <button class="btn btn-sm bg-red-400 btn-circle" onclick="deleteLog('${log.id}')">
                        <i class='bx bx-trash'></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function getActionBadgeClass(action) {
    const classes = {
        'uploaded': 'badge-info',
        'approved': 'badge-success',
        'delivered': 'badge-primary',
        'verified': 'badge-warning',
        'created': 'badge-secondary',
        'updated': 'badge-accent',
        'deleted': 'badge-error'
    };
    return classes[action] || 'badge-outline';
}

// ==================== SEARCH AND FILTER ====================
function handleSearch() {
    currentPage = 1;
    loadLogs();
}

// ==================== PAGINATION ====================
function updatePagination(total, current, last) {
    document.getElementById('paginationFrom').textContent = ((current - 1) * itemsPerPage) + 1;
    document.getElementById('paginationTo').textContent = Math.min(current * itemsPerPage, total);
    document.getElementById('paginationTotal').textContent = total;

    const container = document.getElementById('paginationContainer');
    container.innerHTML = '';

    // Previous button
    const prevButton = document.createElement('button');
    prevButton.className = 'join-item btn';
    prevButton.innerHTML = '<i class="bx bxs-chevron-left"></i>';
    prevButton.disabled = current === 1;
    prevButton.onclick = () => changePage(current - 1);
    container.appendChild(prevButton);

    // Page numbers
    const startPage = Math.max(1, current - 2);
    const endPage = Math.min(last, startPage + 4);
    
    for (let i = startPage; i <= endPage; i++) {
        const pageButton = document.createElement('button');
        pageButton.className = `join-item btn ${i === current ? 'btn-active' : ''}`;
        pageButton.textContent = i;
        pageButton.onclick = () => changePage(i);
        container.appendChild(pageButton);
    }

    // Next button
    const nextButton = document.createElement('button');
    nextButton.className = 'join-item btn';
    nextButton.innerHTML = '<i class="bx bxs-chevron-right"></i>';
    nextButton.disabled = current === last;
    nextButton.onclick = () => changePage(current + 1);
    container.appendChild(nextButton);
}

function changePage(page) {
    currentPage = page;
    loadLogs();
}

// ==================== MODAL FUNCTIONS ====================
function openAddLogModal() {
    document.getElementById('addLogModal').classList.add('modal-open');
}

function closeAddLogModal() {
    document.getElementById('addLogModal').classList.remove('modal-open');
    document.getElementById('addLogForm').reset();
}

function closeViewLogModal() {
    document.getElementById('viewLogModal').classList.remove('modal-open');
}

function closeEditLogModal() {
    document.getElementById('editLogModal').classList.remove('modal-open');
}

// ==================== CRUD OPERATIONS ====================
async function viewLog(id) {
    showLoadingAlert('Loading log details...');
    try {
        const response = await fetch(`${API_BASE_URL}/logistics-records/${id}`);
        const data = await response.json();
        
        if (data.success) {
            const log = data.data;
            document.getElementById('viewLogModalContent').innerHTML = `
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="font-semibold">Log ID:</label>
                            <p>${log.log_id || 'N/A'}</p>
                        </div>
                        <div>
                            <label class="font-semibold">Action:</label>
                            <p><span class="badge ${getActionBadgeClass(log.action)}">${log.action || 'N/A'}</span></p>
                        </div>
                        <div>
                            <label class="font-semibold">Module:</label>
                            <p>${log.module || 'N/A'}</p>
                        </div>
                        <div>
                            <label class="font-semibold">AI/OCR Used:</label>
                            <p><span class="badge ${log.ai_ocr_used ? 'badge-success' : 'badge-neutral'}">${log.ai_ocr_used ? 'Yes' : 'No'}</span></p>
                        </div>
                    </div>
                    <div>
                        <label class="font-semibold">Description:</label>
                        <p class="mt-1 p-3 bg-gray-100 rounded">${log.description || 'No description'}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="font-semibold">Performed By:</label>
                            <p>${log.performed_by || 'N/A'}</p>
                        </div>
                        <div>
                            <label class="font-semibold">Timestamp:</label>
                            <p>${new Date(log.timestamp).toLocaleString() || 'N/A'}</p>
                        </div>
                    </div>
                    <div>
                        <label class="font-semibold">Related Reference:</label>
                        <p>${log.related_reference || 'N/A'}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="font-semibold">IP Address:</label>
                            <p>${log.ip_address || 'N/A'}</p>
                        </div>
                        <div>
                            <label class="font-semibold">User Agent:</label>
                            <p class="text-sm truncate">${log.user_agent || 'N/A'}</p>
                        </div>
                    </div>
                </div>
            `;
            document.getElementById('viewLogModal').classList.add('modal-open');
        } else {
            throw new Error(data.message);
        }
    } catch (error) {
        console.error('Error loading log:', error);
        showAlert('Failed to load log details: ' + error.message, 'error');
    }
}

async function editLog(id) {
    showLoadingAlert('Loading log for editing...');
    try {
        const response = await fetch(`${API_BASE_URL}/logistics-records/${id}`);
        const data = await response.json();
        
        if (data.success) {
            const log = data.data;
            document.getElementById('editLogModalContent').innerHTML = `
                <div class="grid grid-cols-1 gap-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text">Log ID</span>
                                </div>
                                <input type="text" class="input input-bordered w-full" name="log_id" value="${log.log_id || ''}" required>
                            </label>
                        </div>
                        <div>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text">Action</span>
                                </div>
                                <select class="select select-bordered w-full" name="action" required>
                                    <option value="uploaded" ${log.action === 'uploaded' ? 'selected' : ''}>Uploaded</option>
                                    <option value="approved" ${log.action === 'approved' ? 'selected' : ''}>Approved</option>
                                    <option value="delivered" ${log.action === 'delivered' ? 'selected' : ''}>Delivered</option>
                                    <option value="verified" ${log.action === 'verified' ? 'selected' : ''}>Verified</option>
                                    <option value="created" ${log.action === 'created' ? 'selected' : ''}>Created</option>
                                    <option value="updated" ${log.action === 'updated' ? 'selected' : ''}>Updated</option>
                                    <option value="deleted" ${log.action === 'deleted' ? 'selected' : ''}>Deleted</option>
                                </select>
                            </label>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text">Module</span>
                                </div>
                                <select class="select select-bordered w-full" name="module" required>
                                    <option value="Procurement" ${log.module === 'Procurement' ? 'selected' : ''}>Procurement</option>
                                    <option value="Smart Warehousing" ${log.module === 'Smart Warehousing' ? 'selected' : ''}>Smart Warehousing</option>
                                    <option value="Project Logistics" ${log.module === 'Project Logistics' ? 'selected' : ''}>Project Logistics</option>
                                    <option value="Asset Management" ${log.module === 'Asset Management' ? 'selected' : ''}>Asset Management</option>
                                    <option value="Document Tracking" ${log.module === 'Document Tracking' ? 'selected' : ''}>Document Tracking</option>
                                </select>
                            </label>
                        </div>
                        <div>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text">AI/OCR Used</span>
                                </div>
                                <select class="select select-bordered w-full" name="ai_ocr_used" required>
                                    <option value="0" ${!log.ai_ocr_used ? 'selected' : ''}>No</option>
                                    <option value="1" ${log.ai_ocr_used ? 'selected' : ''}>Yes</option>
                                </select>
                            </label>
                        </div>
                    </div>
                    <div>
                        <label class="form-control w-full">
                            <div class="label">
                                <span class="label-text">Description</span>
                            </div>
                            <textarea class="textarea textarea-bordered h-20" name="description" required>${log.description || ''}</textarea>
                        </label>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text">Performed By</span>
                                </div>
                                <input type="text" class="input input-bordered w-full" name="performed_by" value="${log.performed_by || ''}" required>
                            </label>
                        </div>
                        <div>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text">Related Reference</span>
                                </div>
                                <input type="text" class="input input-bordered w-full" name="related_reference" value="${log.related_reference || ''}">
                            </label>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="id" value="${id}">
            `;
            
            document.getElementById('editLogForm').onsubmit = function(e) {
                e.preventDefault();
                updateLog(id);
            };
            
            document.getElementById('editLogModal').classList.add('modal-open');
        } else {
            throw new Error(data.message);
        }
    } catch (error) {
        console.error('Error loading log for edit:', error);
        showAlert('Failed to load log for editing: ' + error.message, 'error');
    }
}

async function updateLog(id) {
    const formData = new FormData(document.getElementById('editLogForm'));
    const data = Object.fromEntries(formData);

    showLoadingAlert('Updating log entry...');

    try {
        const response = await fetch(`${API_BASE_URL}/logistics-records/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.success) {
            showAlert('Log entry updated successfully!', 'success');
            closeEditLogModal();
            loadLogs(); // Refresh the list
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        console.error('Update error:', error);
        showAlert('Failed to update log entry: ' + error.message, 'error');
    }
}

function deleteLog(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "This log entry will be permanently deleted!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then(async (result) => {
        if (result.isConfirmed) {
            showLoadingAlert('Deleting log entry...');
            try {
                const response = await fetch(`${API_BASE_URL}/logistics-records/${id}`, {
                    method: 'DELETE'
                });

                const data = await response.json();

                if (data.success) {
                    showAlert('Log entry deleted successfully!', 'success');
                    loadLogs(); // Refresh the list
                } else {
                    throw new Error(data.message);
                }
            } catch (error) {
                console.error('Delete error:', error);
                showAlert('Failed to delete log entry: ' + error.message, 'error');
            }
        }
    });
}

// ==================== ADD LOG FORM HANDLING ====================
document.getElementById('addLogForm').onsubmit = async function(e) {
    e.preventDefault();
    const formData = new FormData(this);
    const data = Object.fromEntries(formData);

    showLoadingAlert('Adding log entry...');

    try {
        const response = await fetch(`${API_BASE_URL}/logistics-records`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.success) {
            showAlert('Log entry added successfully!', 'success');
            closeAddLogModal();
            loadLogs(); // Refresh the list
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        console.error('Add error:', error);
        showAlert('Failed to add log entry: ' + error.message, 'error');
    }
};

// ==================== EXPORT FUNCTION ====================
function exportLogs() {
    showLoadingAlert('Preparing export...');
    
    try {
        // Create CSV content from filtered logs
        const headers = ['Log ID', 'Action', 'Module', 'Timestamp', 'Performed By', 'AI/OCR Used', 'Description', 'Related Reference'];
        const csvContent = [
            headers.join(','),
            ...filteredLogs.map(log => [
                log.log_id,
                log.action,
                log.module,
                new Date(log.timestamp).toLocaleString(),
                log.performed_by,
                log.ai_ocr_used ? 'Yes' : 'No',
                `"${log.description.replace(/"/g, '""')}"`,
                log.related_reference || ''
            ].join(','))
        ].join('\n');

        // Create and download file
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = `logistics-records-${new Date().toISOString().split('T')[0]}.csv`;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);

        showAlert('Logs exported successfully!', 'success');
    } catch (error) {
        console.error('Export error:', error);
        showAlert('Failed to export logs: ' + error.message, 'error');
    }
}

// ==================== UTILITY FUNCTIONS ====================
function showLoading() {
    document.getElementById('loadingRow').classList.remove('hidden');
}

function hideLoading() {
    document.getElementById('loadingRow').classList.add('hidden');
}

function showAlert(message, type = 'info') {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    });

    Toast.fire({
        icon: type,
        title: message
    });
}

function showLoadingAlert(message) {
    Swal.fire({
        title: message,
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
}

function showError(message) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: message,
        confirmButtonText: 'OK'
    });
}

function getCurrentUser() {
    // Get user from cookie or localStorage
    try {
        const userCookie = document.cookie.split('; ').find(row => row.startsWith('user='));
        if (userCookie) {
            const userData = JSON.parse(decodeURIComponent(userCookie.split('=')[1]));
            return userData.firstname + ' ' + userData.lastname;
        }
    } catch (e) {
        console.error('Error getting user data:', e);
    }
    return 'System';
}
</script>
@endsection