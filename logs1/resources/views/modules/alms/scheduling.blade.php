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
                    <input type="text" id="searchInput" placeholder="Search by schedule ID, asset..." class="input input-bordered w-full" />
                    <button class="btn btn-square" onclick="loadSchedules()">
                        <i class="bx bx-search"></i>
                    </button>
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
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
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

<!-- Complete Maintenance Modal -->
<dialog id="completeModal" class="modal">
    <div class="modal-box w-11/12 max-w-2xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg mb-4">Complete Maintenance</h3>
        
        <form id="completeForm" class="space-y-4">
            <input type="hidden" name="schedule_id" />
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Actual Cost</span>
                    </label>
                    <input type="number" step="0.01" name="cost" class="input input-bordered" placeholder="0.00" />
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
<div id="loadingToast" class="toast toast-top toast-center hidden">
    <div class="alert alert-info">
        <span>Loading...</span>
    </div>
</div>

<script>
// ==================== CONFIGURATION ====================
const API_BASE_URL = 'http://localhost:8001/api/alms';

let currentPage = 1;
let currentScheduleId = null;

// ==================== INITIALIZATION ====================
document.addEventListener('DOMContentLoaded', function() {
    loadStats();
    loadSchedules();
    loadSupportingData();
});

// ==================== STATS FUNCTIONS ====================
async function loadStats() {
    try {
        const response = await fetch(`${API_BASE_URL}/maintenance-schedules/stats`);
        const result = await response.json();
        
        if (result.success) {
            const stats = result.data;
            document.getElementById('totalSchedules').textContent = stats.total_schedules;
            document.getElementById('pendingSchedules').textContent = stats.pending_schedules;
            document.getElementById('overdueSchedules').textContent = stats.overdue_schedules;
            document.getElementById('completedThisMonth').textContent = stats.completed_this_month;
        }
    } catch (error) {
        console.error('Error loading stats:', error);
        showToast('Error loading statistics', 'error');
    }
}

// ==================== SCHEDULE FUNCTIONS ====================
async function loadSchedules(page = 1) {
    showLoading();
    try {
        const search = document.getElementById('searchInput').value;
        const status = document.getElementById('statusFilter').value;
        const overdue = document.getElementById('overdueFilter').value;
        
        let url = `${API_BASE_URL}/maintenance-schedules?page=${page}`;
        if (search) url += `&search=${encodeURIComponent(search)}`;
        if (status) url += `&status=${status}`;
        if (overdue) url += `&is_overdue=${overdue}`;
        
        const response = await fetch(url);
        const result = await response.json();
        
        if (result.success) {
            displaySchedules(result.data.data);
            setupPagination(result.data);
            currentPage = page;
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        console.error('Error loading schedules:', error);
        showToast('Error loading maintenance schedules', 'error');
        document.getElementById('schedulesTableBody').innerHTML = '<tr><td colspan="7" class="text-center text-error">Failed to load schedules</td></tr>';
    } finally {
        hideLoading();
    }
}

function displaySchedules(schedules) {
    const tbody = document.getElementById('schedulesTableBody');
    
    if (schedules.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" class="text-center">No maintenance schedules found</td></tr>';
        return;
    }
    
    tbody.innerHTML = schedules.map(schedule => `
        <tr class="${schedule.is_overdue ? 'bg-error/10' : ''}">
            <td class="font-mono font-bold">${schedule.schedule_id}</td>
            <td>
                <div>
                    <div class="font-semibold">${schedule.asset.alms_id}</div>
                    <div class="text-sm text-gray-600">${schedule.asset.name}</div>
                </div>
            </td>
            <td>${schedule.maintenance_type.name}</td>
            <td>
                <div class="flex items-center gap-2">
                    ${schedule.due_date}
                    ${schedule.is_overdue ? '<i class="bx bx-alarm-exclamation text-error" title="Overdue"></i>' : ''}
                </div>
            </td>
            <td>
                <span class="badge ${getScheduleStatusBadgeClass(schedule.status)}">${schedule.status.replace('_', ' ').toUpperCase()}</span>
            </td>
            <td>
                ${schedule.is_overdue ? 
                    '<span class="badge badge-error">OVERDUE</span>' : 
                    '<span class="badge badge-success">On Time</span>'
                }
            </td>
            <td>
                <div class="flex gap-1">
                    ${schedule.status === 'pending' || schedule.status === 'in_progress' ? `
                        <button class="btn btn-sm btn-circle btn-success" title="Complete" onclick="completeMaintenance(${schedule.id})">
                            <i class="bx bx-check"></i>
                        </button>
                    ` : ''}
                    <button class="btn btn-sm btn-circle btn-warning" title="Edit" onclick="editSchedule(${schedule.id})">
                        <i class="bx bx-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-circle btn-info" title="View Details" onclick="viewSchedule(${schedule.id})">
                        <i class="bx bx-show"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

function getScheduleStatusBadgeClass(status) {
    const classes = {
        'pending': 'badge-warning',
        'in_progress': 'badge-info',
        'completed': 'badge-success',
        'cancelled': 'badge-error'
    };
    return classes[status] || 'badge-info';
}

// ==================== MODAL FUNCTIONS ====================
function openCreateScheduleModal() {
    document.getElementById('modalTitle').textContent = 'Schedule Maintenance';
    document.getElementById('scheduleForm').reset();
    document.getElementById('scheduleModal').showModal();
}

async function editSchedule(id) {
    showLoading();
    try {
        const response = await fetch(`${API_BASE_URL}/maintenance-schedules/${id}`);
        const result = await response.json();
        
        if (result.success) {
            const schedule = result.data;
            currentScheduleId = id;
            
            // Populate form
            const form = document.getElementById('scheduleForm');
            form.asset_id.value = schedule.asset_id;
            form.maintenance_type_id.value = schedule.maintenance_type_id;
            form.due_date.value = schedule.due_date;
            form.frequency_value.value = schedule.frequency_value;
            
            document.getElementById('modalTitle').textContent = 'Edit Maintenance Schedule';
            document.getElementById('scheduleModal').showModal();
        }
    } catch (error) {
        console.error('Error loading schedule:', error);
        showToast('Error loading schedule details', 'error');
    } finally {
        hideLoading();
    }
}

function completeMaintenance(id) {
    document.getElementById('completeForm').schedule_id.value = id;
    document.getElementById('completeModal').showModal();
}

// ==================== FORM HANDLING ====================
document.getElementById('scheduleForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    await saveSchedule();
});

document.getElementById('completeForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    await completeSchedule();
});

async function saveSchedule() {
    showLoading();
    try {
        const formData = new FormData(document.getElementById('scheduleForm'));
        const data = Object.fromEntries(formData);
        
        // Convert numeric field
        data.frequency_value = parseInt(data.frequency_value);
        
        const url = currentScheduleId ? 
            `${API_BASE_URL}/maintenance-schedules/${currentScheduleId}` : 
            `${API_BASE_URL}/maintenance-schedules`;
        const method = currentScheduleId ? 'PUT' : 'POST';
        
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast(currentScheduleId ? 'Schedule updated successfully!' : 'Maintenance scheduled successfully!', 'success');
            document.getElementById('scheduleModal').close();
            loadSchedules(currentPage);
            loadStats();
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        console.error('Error saving schedule:', error);
        showToast('Error saving schedule: ' + error.message, 'error');
    } finally {
        hideLoading();
        currentScheduleId = null;
    }
}

async function completeSchedule() {
    showLoading();
    try {
        const formData = new FormData(document.getElementById('completeForm'));
        const data = Object.fromEntries(formData);
        const scheduleId = data.schedule_id;
        
        // Convert cost to float if provided
        if (data.cost) {
            data.cost = parseFloat(data.cost);
        }
        
        const response = await fetch(`${API_BASE_URL}/maintenance-schedules/${scheduleId}/complete`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast('Maintenance completed successfully!', 'success');
            document.getElementById('completeModal').close();
            document.getElementById('completeForm').reset();
            loadSchedules(currentPage);
            loadStats();
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        console.error('Error completing maintenance:', error);
        showToast('Error completing maintenance: ' + error.message, 'error');
    } finally {
        hideLoading();
    }
}

// ==================== SUPPORTING DATA ====================
async function loadSupportingData() {
    try {
        // Load assets
        const assetsResponse = await fetch(`${API_BASE_URL}/assets`);
        const assetsResult = await assetsResponse.json();
        if (assetsResult.success) {
            const assetSelect = document.querySelector('select[name="asset_id"]');
            assetsResult.data.data.forEach(asset => {
                if (asset.status === 'active') {
                    assetSelect.innerHTML += `<option value="${asset.id}">${asset.alms_id} - ${asset.name}</option>`;
                }
            });
        }
        
        // Load maintenance types
        const typesResponse = await fetch(`${API_BASE_URL}/maintenance-types`);
        const typesResult = await typesResponse.json();
        if (typesResult.success) {
            const typeSelect = document.querySelector('select[name="maintenance_type_id"]');
            typesResult.data.forEach(type => {
                typeSelect.innerHTML += `<option value="${type.id}">${type.name}</option>`;
            });
        }
    } catch (error) {
        console.error('Error loading supporting data:', error);
    }
}

// ==================== PAGINATION ====================
function setupPagination(paginationData) {
    const infoDiv = document.getElementById('paginationInfo');
    const controlsDiv = document.getElementById('paginationControls');
    
    if (!paginationData) return;
    
    const { current_page, last_page, total, from, to } = paginationData;
    infoDiv.textContent = `Showing ${from} to ${to} of ${total} entries`;
    
    let paginationHTML = '';
    
    // Previous button
    if (current_page > 1) {
        paginationHTML += `<button class="join-item btn btn-sm" onclick="loadSchedules(${current_page - 1})">«</button>`;
    }
    
    // Page numbers
    for (let i = 1; i <= last_page; i++) {
        if (i === current_page) {
            paginationHTML += `<button class="join-item btn btn-sm btn-active">${i}</button>`;
        } else {
            paginationHTML += `<button class="join-item btn btn-sm" onclick="loadSchedules(${i})">${i}</button>`;
        }
    }
    
    // Next button
    if (current_page < last_page) {
        paginationHTML += `<button class="join-item btn btn-sm" onclick="loadSchedules(${current_page + 1})">»</button>`;
    }
    
    controlsDiv.innerHTML = paginationHTML;
}

// ==================== UTILITY FUNCTIONS ====================
function showLoading() {
    document.getElementById('loadingToast').classList.remove('hidden');
}

function hideLoading() {
    document.getElementById('loadingToast').classList.add('hidden');
}

function showToast(message, type = 'info') {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });
    
    Toast.fire({
        icon: type,
        title: message
    });
}

// Placeholder functions for future implementation
function viewSchedule(id) {
    showToast('View schedule details feature coming soon!', 'info');
}

// Search on Enter key
document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        loadSchedules();
    }
});
</script>
@endsection