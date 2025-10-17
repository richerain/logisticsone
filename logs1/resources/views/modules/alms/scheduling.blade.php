@extends('layouts.app')

@section('title', 'Maintenance Scheduling - ALMS')

@section('content')
<div class="module-content bg-white rounded-xl p-6 shadow block">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Maintenance Scheduling</h2>
        <button class="btn btn-primary" onclick="openCreateScheduleModal()">
            <i class="bx bx-calendar-plus mr-2"></i>Schedule Maintenance
        </button>
    </div>

    <!-- Stats Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="stat text-primary-content rounded-lg shadow-lg border-l-4 border-primary">
            <div class="stat-figure text-primary">
                <i class="bx bx-calendar text-3xl"></i>
            </div>
            <div class="stat-title text-primary">Total Schedules</div>
            <div class="stat-value text-primary" id="totalSchedules">0</div>
        </div>
        
        <div class="stat text-warning-content rounded-lg shadow-lg border-l-4 border-warning">
            <div class="stat-figure text-warning">
                <i class="bx bx-time-five text-3xl"></i>
            </div>
            <div class="stat-title text-warning">Pending</div>
            <div class="stat-value text-warning" id="pendingSchedules">0</div>
        </div>
        
        <div class="stat text-error-content rounded-lg shadow-lg border-l-4 border-error">
            <div class="stat-figure text-error">
                <i class="bx bx-alarm-exclamation text-3xl"></i>
            </div>
            <div class="stat-title text-error">Overdue</div>
            <div class="stat-value text-error" id="overdueSchedules">0</div>
        </div>
        
        <div class="stat text-success-content rounded-lg shadow-lg border-l-4 border-success">
            <div class="stat-figure text-success">
                <i class="bx bx-check-double text-3xl"></i>
            </div>
            <div class="stat-title text-success">Completed This Month</div>
            <div class="stat-value text-success" id="completedThisMonth">0</div>
        </div>
    </div>

    <!-- Filters and Search Section -->
    <div class="flex flex-col lg:flex-row gap-4 mb-6 p-4 bg-base-200 rounded-lg">
        <div class="flex-1">
            <div class="form-control">
                <div class="input-group">
                    <input type="text" id="searchInput" placeholder="Search by schedule ID, asset..." class="input input-bordered w-full" onkeyup="loadSchedules()" />
                </div>
            </div>
        </div>
        <div class="flex gap-4">
            <select id="statusFilter" class="select select-bordered" onchange="loadSchedules()">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
            <select id="overdueFilter" class="select select-bordered" onchange="loadSchedules()">
                <option value="">All</option>
                <option value="true">Overdue Only</option>
                <option value="false">Not Overdue</option>
            </select>
        </div>
    </div>

    <!-- Schedules Table -->
    <div class="overflow-x-auto bg-base-100 rounded-lg">
        <table class="table table-zebra w-full">
            <thead>
                <tr>
                    <th>Schedule ID</th>
                    <th>Asset</th>
                    <th>Maintenance Type</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Overdue</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="schedulesTableBody">
                <!-- Dynamic content will be loaded here -->
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-between items-center mt-4">
        <div class="text-sm text-gray-600" id="paginationInfo"></div>
        <div class="join" id="paginationControls">
            <!-- Pagination buttons will be loaded here -->
        </div>
    </div>
</div>

<!-- Create Schedule Modal -->
<dialog id="scheduleModal" class="modal">
    <div class="modal-box w-11/12 max-w-3xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg mb-4" id="modalTitle">Schedule Maintenance</h3>
        
        <form id="scheduleForm" class="space-y-4">
            <input type="hidden" name="id" id="scheduleId">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Schedule ID</span>
                    </label>
                    <input type="text" id="scheduleIdDisplay" class="input input-bordered" readonly disabled />
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Asset *</span>
                    </label>
                    <select name="asset_id" class="select select-bordered" required>
                        <option value="">Select Asset</option>
                    </select>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Maintenance Type *</span>
                    </label>
                    <select name="maintenance_type_id" class="select select-bordered" required>
                        <option value="">Select Type</option>
                    </select>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Due Date *</span>
                    </label>
                    <input type="date" name="due_date" class="input input-bordered" required />
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Frequency Value *</span>
                    </label>
                    <input type="number" name="frequency_value" class="input input-bordered" placeholder="e.g., 12 for months" required />
                </div>
            </div>
            
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('scheduleModal').close()">Cancel</button>
                <button type="submit" class="btn btn-primary">Schedule Maintenance</button>
            </div>
        </form>
    </div>
</dialog>

<!-- View Schedule Modal -->
<dialog id="viewScheduleModal" class="modal">
    <div class="modal-box w-11/12 max-w-2xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg mb-4">Schedule Details</h3>
        
        <div id="scheduleDetails" class="space-y-4">
            <!-- Dynamic content will be loaded here -->
        </div>
        
        <div class="modal-action">
            <button type="button" class="btn btn-ghost" onclick="document.getElementById('viewScheduleModal').close()">Close</button>
        </div>
    </div>
</dialog>

<!-- Complete Maintenance Modal -->
<dialog id="completeModal" class="modal">
    <div class="modal-box w-11/12 max-w-2xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg mb-4">Complete Maintenance</h3>
        
        <form id="completeForm" class="space-y-4">
            <input type="hidden" name="schedule_id" id="completeScheduleId">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Actual Cost</span>
                    </label>
                    <div class="input-group">
                        <span class="bg-gray-200 px-3 py-2 border border-r-0 rounded-l-lg">₱</span>
                        <input type="number" step="0.01" name="cost" class="input input-bordered rounded-l-none w-full" placeholder="0.00" />
                    </div>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Performed By</span>
                    </label>
                    <input type="text" name="performed_by" class="input input-bordered" placeholder="Technician/Vendor name" />
                </div>
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text">Work Description</span>
                </label>
                <textarea name="description" class="textarea textarea-bordered h-20" placeholder="Describe the maintenance work performed..."></textarea>
            </div>
            
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('completeModal').close()">Cancel</button>
                <button type="submit" class="btn btn-success">Mark as Completed</button>
            </div>
        </form>
    </div>
</dialog>

<!-- Loading Toast -->
<div id="loadingToast" class="toast toast-middle toast-center hidden">
    <div class="alert alert-info">
        <span>Loading...</span>
    </div>
</div>

<script>
// ==================== CONFIGURATION ====================
const API_BASE_URL = 'http://localhost:8001/api/alms';
let currentPage = 1;
let totalPages = 1;

// ==================== INITIALIZATION ====================
document.addEventListener('DOMContentLoaded', function() {
    loadSchedules();
    loadMaintenanceStats();
    loadSupportingData();
});

// ==================== SCHEDULE MANAGEMENT ====================
async function loadSchedules() {
    showLoading();
    
    const search = document.getElementById('searchInput').value;
    const status = document.getElementById('statusFilter').value;
    const overdue = document.getElementById('overdueFilter').value;
    
    const params = new URLSearchParams({
        page: currentPage,
        search: search,
        status: status,
        is_overdue: overdue
    });
    
    try {
        const response = await fetch(`${API_BASE_URL}/maintenance-schedules?${params}`);
        const data = await response.json();
        
        if (data.success) {
            displaySchedules(data.data.data);
            setupPagination(data.data);
        } else {
            showError('Failed to load maintenance schedules');
        }
    } catch (error) {
        showError('Error loading schedules: ' + error.message);
    } finally {
        hideLoading();
    }
}

function displaySchedules(schedules) {
    const tbody = document.getElementById('schedulesTableBody');
    tbody.innerHTML = '';
    
    if (schedules.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-8 text-gray-500">
                    <i class="bx bx-calendar text-4xl mb-2"></i>
                    <p>No maintenance schedules found</p>
                </td>
            </tr>
        `;
        return;
    }
    
    // Sort by due date (newest first)
    schedules.sort((a, b) => new Date(b.due_date) - new Date(a.due_date));
    
    schedules.forEach(schedule => {
        const statusBadge = getScheduleStatusBadge(schedule.status);
        const overdueBadge = schedule.is_overdue ? 
            '<span class="badge badge-error">Overdue</span>' : 
            '<span class="badge badge-success">On Time</span>';
        
        const dueDate = new Date(schedule.due_date).toLocaleDateString();
        const isOverdue = new Date(schedule.due_date) < new Date() && schedule.status !== 'completed';
        
        tbody.innerHTML += `
            <tr class="${isOverdue ? 'bg-error/10' : ''}">
                <td class="font-mono">${schedule.schedule_id}</td>
                <td>
                    <div class="font-semibold">${schedule.asset.name}</div>
                    <div class="text-sm text-gray-500">${schedule.asset.alms_id}</div>
                </td>
                <td>${schedule.maintenance_type.name}</td>
                <td>
                    <div class="font-semibold ${isOverdue ? 'text-error' : ''}">${dueDate}</div>
                    ${isOverdue ? '<div class="text-xs text-error">Overdue!</div>' : ''}
                </td>
                <td>${statusBadge}</td>
                <td>${overdueBadge}</td>
                <td>
                    <div class="flex gap-2">
                        <button class="btn btn-sm btn-circle btn-outline btn-info" onclick="viewSchedule(${schedule.id})">
                            <i class="bx bx-show"></i>
                        </button>
                        ${schedule.status === 'pending' || schedule.status === 'in_progress' ? `
                            <button class="btn btn-sm btn-circle btn-outline btn-success" onclick="openCompleteModal(${schedule.id})">
                                <i class="bx bx-check"></i>
                            </button>
                        ` : ''}
                        <button class="btn btn-sm btn-circle btn-outline btn-warning" onclick="editSchedule(${schedule.id})">
                            <i class="bx bx-edit"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
}

function getScheduleStatusBadge(status) {
    const statusConfig = {
        'pending': { class: 'badge-warning', text: 'Pending' },
        'in_progress': { class: 'badge-info', text: 'In Progress' },
        'completed': { class: 'badge-success', text: 'Completed' },
        'cancelled': { class: 'badge-error', text: 'Cancelled' }
    };
    
    const config = statusConfig[status] || { class: 'badge-neutral', text: status };
    return `<span class="badge ${config.class}">${config.text}</span>`;
}

// ==================== MODAL MANAGEMENT ====================
function openCreateScheduleModal() {
    document.getElementById('modalTitle').textContent = 'Schedule Maintenance';
    document.getElementById('scheduleForm').reset();
    document.getElementById('scheduleId').value = '';
    generateScheduleId();
    document.getElementById('scheduleModal').showModal();
}

function generateScheduleId() {
    document.getElementById('scheduleIdDisplay').value = 'Auto-generated upon save';
}

async function viewSchedule(id) {
    showLoading();
    
    try {
        const response = await fetch(`${API_BASE_URL}/maintenance-schedules/${id}`);
        const data = await response.json();
        
        if (data.success) {
            displayScheduleDetails(data.data);
            document.getElementById('viewScheduleModal').showModal();
        } else {
            showError('Failed to load schedule data');
        }
    } catch (error) {
        showError('Error loading schedule: ' + error.message);
    } finally {
        hideLoading();
    }
}

function displayScheduleDetails(schedule) {
    const detailsDiv = document.getElementById('scheduleDetails');
    const dueDate = new Date(schedule.due_date).toLocaleDateString();
    const lastMaintained = schedule.last_maintained_date ? 
        new Date(schedule.last_maintained_date).toLocaleDateString() : 'Never';
    
    detailsDiv.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Schedule ID</span>
                </label>
                <div class="text-lg font-mono">${schedule.schedule_id}</div>
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Due Date</span>
                </label>
                <div class="text-lg">${dueDate}</div>
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Asset</span>
                </label>
                <div class="text-lg">${schedule.asset.name}</div>
                <div class="text-sm text-gray-500">${schedule.asset.alms_id} - ${schedule.asset.serial_number}</div>
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Category</span>
                </label>
                <div class="text-lg">${schedule.asset.category.name}</div>
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Maintenance Type</span>
                </label>
                <div class="text-lg">${schedule.maintenance_type.name}</div>
                <div class="text-sm text-gray-500">${schedule.maintenance_type.frequency_unit}</div>
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Frequency Value</span>
                </label>
                <div class="text-lg">${schedule.frequency_value}</div>
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Status</span>
                </label>
                <div class="text-lg">${getScheduleStatusText(schedule.status)}</div>
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Last Maintained</span>
                </label>
                <div class="text-lg">${lastMaintained}</div>
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Overdue</span>
                </label>
                <div class="text-lg">${schedule.is_overdue ? 'Yes' : 'No'}</div>
            </div>
        </div>
    `;
}

function getScheduleStatusText(status) {
    const statusTexts = {
        'pending': 'Pending',
        'in_progress': 'In Progress',
        'completed': 'Completed',
        'cancelled': 'Cancelled'
    };
    return statusTexts[status] || status;
}

async function editSchedule(id) {
    showLoading();
    
    try {
        const response = await fetch(`${API_BASE_URL}/maintenance-schedules/${id}`);
        const data = await response.json();
        
        if (data.success) {
            const schedule = data.data;
            document.getElementById('modalTitle').textContent = 'Edit Maintenance Schedule';
            document.getElementById('scheduleId').value = schedule.id;
            document.getElementById('scheduleIdDisplay').value = schedule.schedule_id;
            document.getElementById('scheduleForm').asset_id.value = schedule.asset_id;
            document.getElementById('scheduleForm').maintenance_type_id.value = schedule.maintenance_type_id;
            document.getElementById('scheduleForm').due_date.value = schedule.due_date;
            document.getElementById('scheduleForm').frequency_value.value = schedule.frequency_value;
            
            document.getElementById('scheduleModal').showModal();
        } else {
            showError('Failed to load schedule data');
        }
    } catch (error) {
        showError('Error loading schedule: ' + error.message);
    } finally {
        hideLoading();
    }
}

function openCompleteModal(scheduleId) {
    document.getElementById('completeScheduleId').value = scheduleId;
    document.getElementById('completeForm').reset();
    document.getElementById('completeModal').showModal();
}

// ==================== FORM HANDLING ====================
document.getElementById('scheduleForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const scheduleId = document.getElementById('scheduleId').value;
    const isEdit = !!scheduleId;
    
    showLoading();
    
    try {
        const url = isEdit ? `${API_BASE_URL}/maintenance-schedules/${scheduleId}` : `${API_BASE_URL}/maintenance-schedules`;
        const method = isEdit ? 'PUT' : 'POST';
        
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(Object.fromEntries(formData))
        });
        
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('scheduleModal').close();
            showSuccess(isEdit ? 'Schedule updated successfully' : 'Maintenance scheduled successfully');
            loadSchedules();
            loadMaintenanceStats();
        } else {
            showError(data.message || 'Operation failed');
        }
    } catch (error) {
        showError('Error saving schedule: ' + error.message);
    } finally {
        hideLoading();
    }
});

document.getElementById('completeForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const scheduleId = document.getElementById('completeScheduleId').value;
    
    showLoading();
    
    try {
        const response = await fetch(`${API_BASE_URL}/maintenance-schedules/${scheduleId}/complete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(Object.fromEntries(formData))
        });
        
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('completeModal').close();
            showSuccess('Maintenance completed successfully');
            loadSchedules();
            loadMaintenanceStats();
        } else {
            showError(data.message || 'Completion failed');
        }
    } catch (error) {
        showError('Error completing maintenance: ' + error.message);
    } finally {
        hideLoading();
    }
});

// ==================== SUPPORTING DATA ====================
async function loadSupportingData() {
    try {
        const [assetsRes, typesRes] = await Promise.all([
            fetch(`${API_BASE_URL}/assets`),
            fetch(`${API_BASE_URL}/maintenance-types`)
        ]);
        
        const assetsData = await assetsRes.json();
        const typesData = await typesRes.json();
        
        if (assetsData.success) {
            const assetSelect = document.querySelector('select[name="asset_id"]');
            assetSelect.innerHTML = '<option value="">Select Asset</option>';
            assetsData.data.data.forEach(asset => {
                if (asset.status === 'active') {
                    assetSelect.innerHTML += `<option value="${asset.id}">${asset.alms_id} - ${asset.name}</option>`;
                }
            });
        }
        
        if (typesData.success) {
            const typeSelect = document.querySelector('select[name="maintenance_type_id"]');
            typeSelect.innerHTML = '<option value="">Select Type</option>';
            typesData.data.forEach(type => {
                typeSelect.innerHTML += `<option value="${type.id}">${type.name} (${type.frequency_unit})</option>`;
            });
        }
    } catch (error) {
        console.error('Error loading supporting data:', error);
    }
}

// ==================== STATS ====================
async function loadMaintenanceStats() {
    try {
        const response = await fetch(`${API_BASE_URL}/maintenance-schedules/stats`);
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('totalSchedules').textContent = data.data.total_schedules;
            document.getElementById('pendingSchedules').textContent = data.data.pending_schedules;
            document.getElementById('overdueSchedules').textContent = data.data.overdue_schedules;
            document.getElementById('completedThisMonth').textContent = data.data.completed_this_month;
        }
    } catch (error) {
        console.error('Error loading stats:', error);
    }
}

// ==================== PAGINATION ====================
function setupPagination(pagination) {
    totalPages = pagination.last_page;
    currentPage = pagination.current_page;
    
    document.getElementById('paginationInfo').textContent = 
        `Showing ${pagination.from || 0} to ${pagination.to || 0} of ${pagination.total} entries`;
    
    const paginationControls = document.getElementById('paginationControls');
    paginationControls.innerHTML = '';
    
    // Previous button
    const prevButton = document.createElement('button');
    prevButton.className = `join-item btn ${currentPage === 1 ? 'btn-disabled' : ''}`;
    prevButton.innerHTML = '«';
    prevButton.onclick = () => changePage(currentPage - 1);
    paginationControls.appendChild(prevButton);
    
    // Page buttons
    for (let i = 1; i <= totalPages; i++) {
        const pageButton = document.createElement('button');
        pageButton.className = `join-item btn ${currentPage === i ? 'btn-active' : ''}`;
        pageButton.textContent = i;
        pageButton.onclick = () => changePage(i);
        paginationControls.appendChild(pageButton);
    }
    
    // Next button
    const nextButton = document.createElement('button');
    nextButton.className = `join-item btn ${currentPage === totalPages ? 'btn-disabled' : ''}`;
    nextButton.innerHTML = '»';
    nextButton.onclick = () => changePage(currentPage + 1);
    paginationControls.appendChild(nextButton);
}

function changePage(page) {
    if (page < 1 || page > totalPages) return;
    currentPage = page;
    loadSchedules();
}

// ==================== UTILITY FUNCTIONS ====================
function showLoading() {
    document.getElementById('loadingToast').classList.remove('hidden');
}

function hideLoading() {
    document.getElementById('loadingToast').classList.add('hidden');
}

function showSuccess(message) {
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: message,
        timer: 2000,
        showConfirmButton: false
    });
}

function showError(message) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: message
    });
}
</script>
@endsection