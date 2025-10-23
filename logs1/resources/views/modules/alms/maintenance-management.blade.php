@extends('layouts.app')

@section('title', 'Maintenance Management - ALMS')

@section('content')
    <div class="module-content bg-white rounded-xl p-6 shadow block">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Maintenance Management</h2>
            <button class="btn btn-primary" id="addMaintenanceBtn">
                <i class="bx bx-plus mr-2"></i>Schedule Maintenance
            </button>
        </div>

        <!-- Stats Section -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-primary">
                <div class="stat-figure text-primary">
                    <i class="bx bx-calendar text-3xl"></i>
                </div>
                <div class="stat-title">Total Schedules</div>
                <div class="stat-value text-primary text-lg" id="total-schedules">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-warning">
                <div class="stat-figure text-warning">
                    <i class="bx bx-time text-3xl"></i>
                </div>
                <div class="stat-title">Pending</div>
                <div class="stat-value text-warning text-lg" id="pending-schedules">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-info">
                <div class="stat-figure text-info">
                    <i class="bx bx-wrench text-3xl"></i>
                </div>
                <div class="stat-title">Under Maintenance</div>
                <div class="stat-value text-info text-lg" id="maintenance-schedules">0</div>
            </div>
            <div class="hidden stat bg-base-100 rounded-lg shadow-lg border-l-4 border-secondary">
                <div class="stat-figure text-secondary">
                    <i class="bx bx-refresh text-3xl"></i>
                </div>
                <div class="stat-title">Re-Schedule</div>
                <div class="stat-value text-secondary text-lg" id="reschedule-schedules">0</div>
            </div>
            <div class="hidden stat bg-base-100 rounded-lg shadow-lg border-l-4 border-purple-600">
                <div class="stat-figure text-purple-600">
                    <i class="bx bx-recycle text-3xl"></i>
                </div>
                <div class="stat-title">Replacement</div>
                <div class="stat-value text-purple-600 text-lg" id="replacement-schedules">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-success">
                <div class="stat-figure text-success">
                    <i class="bx bx-check-circle text-3xl"></i>
                </div>
                <div class="stat-title">Done</div>
                <div class="stat-value text-success text-lg" id="done-schedules">0</div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="flex gap-4 mb-6">
            <div class="form-control flex-1">
                <input type="text" placeholder="Search maintenance schedules..." class="input input-bordered w-full" id="searchMaintenance">
            </div>
            <select class="select select-bordered" id="statusFilter">
                <option value="">All Status</option>
                <option value="Pending">Pending</option>
                <option value="Under Maintenance">Under Maintenance</option>
                <option value="Re-Schedule">Re-Schedule</option>
                <option value="Replacement">Replacement</option>
                <option value="Reject">Reject</option>
                <option value="Done">Done</option>
            </select>
            <select class="select select-bordered" id="typeFilter">
                <option value="">All Types</option>
                <option value="Inspection">Inspection</option>
                <option value="Cleaning/Sanitization">Cleaning/Sanitization</option>
                <option value="Repair">Repair</option>
                <option value="Calibration/Testing">Calibration/Testing</option>
                <option value="Replacement">Replacement</option>
            </select>
        </div>

        <!-- Maintenance Schedules Table -->
        <div class="overflow-x-auto bg-base-100 rounded-lg">
            <table class="table table-zebra w-full">
                <thead>
                    <tr class="bg-gray-900 text-white">
                        <th>Schedule ID</th>
                        <th>Asset Name</th>
                        <th>Maintenance Type</th>
                        <th>Assigned Personnel</th>
                        <th>Schedule Date & Time</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="maintenance-table-body">
                    <tr>
                        <td colspan="7" class="text-center py-8">
                            <div class="loading loading-spinner loading-lg"></div>
                            <p class="text-gray-500 mt-2">Loading maintenance schedules...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add/Edit Maintenance Modal -->
    <div id="maintenanceModal" class="modal modal-lg">
        <div class="modal-box max-w-4xl p-0 overflow-visible">
            <div class="flex justify-between items-center bg-green-700 p-4 rounded-t-lg">
                <h3 class="font-bold text-white text-lg" id="maintenanceModalTitle">Schedule Maintenance</h3>
                <button class="btn btn-sm btn-circle btn-ghost hover:bg-white/20 text-white" id="closeMaintenanceModalX">✕</button>
            </div>
            <div class="p-4 max-h-[70vh] overflow-y-auto">
                <form id="maintenanceForm" class="space-y-4">
                    @csrf
                    <input type="hidden" id="maintenanceId" name="maintenance_id">
                    
                    <!-- Auto-generated Schedule ID -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Schedule ID</span>
                        </label>
                        <input type="text" id="scheduleID" class="input input-bordered input-sm w-full bg-gray-100" 
                               readonly placeholder="Auto-generated">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Asset Name *</span>
                            </label>
                            <select id="assetName" name="asset_name" class="select select-bordered select-sm w-full" required>
                                <option value="">Select Asset</option>
                            </select>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Maintenance Type *</span>
                            </label>
                            <select id="maintenanceType" name="maintenance_type" class="select select-bordered select-sm w-full" required>
                                <option value="">Select Type</option>
                                <option value="Inspection">Inspection</option>
                                <option value="Cleaning/Sanitization">Cleaning/Sanitization</option>
                                <option value="Repair">Repair</option>
                                <option value="Calibration/Testing">Calibration/Testing</option>
                                <option value="Replacement">Replacement</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Assigned Personnel *</span>
                        </label>
                        <input type="text" id="assignedPersonnel" name="assigned_personnel" class="input input-bordered input-sm w-full" 
                               placeholder="Enter personnel name" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Schedule Date *</span>
                            </label>
                            <input type="date" id="scheduleDate" name="schedule_date" class="input input-bordered input-sm w-full" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Schedule Time *</span>
                            </label>
                            <input type="time" id="scheduleTime" name="schedule_time" class="input input-bordered input-sm w-full" required>
                        </div>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Status</span>
                        </label>
                        <select id="maintenanceStatus" name="status" class="select select-bordered select-sm w-full">
                            <option value="Pending">Pending</option>
                            <option value="Under Maintenance">Under Maintenance</option>
                            <option value="Re-Schedule">Re-Schedule</option>
                            <option value="Replacement">Replacement</option>
                            <option value="Reject">Reject</option>
                            <option value="Done">Done</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Notes</span>
                        </label>
                        <textarea id="notes" name="notes" class="textarea textarea-bordered textarea-sm h-16" 
                                  placeholder="Maintenance notes..."></textarea>
                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-action flex justify-end space-x-3 pt-4 border-t">
                        <button type="button" class="btn btn-ghost btn-sm hover:bg-gray-100 transition-colors px-4" id="closeMaintenanceModal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm bg-gradient-to-r from-primary to-primary/90 hover:from-primary/90 hover:to-primary transition-all duration-300 shadow-lg px-4" id="maintenanceSubmitBtn">
                            <i class="bx bx-save mr-1"></i><span id="maintenanceModalSubmitText">Save Schedule</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Maintenance Modal -->
    <div id="viewMaintenanceModal" class="modal modal-lg">
        <div class="modal-box max-w-4xl p-0 overflow-visible">
            <div class="flex justify-between items-center bg-green-700 p-4 rounded-t-lg">
                <h3 class="font-bold text-white text-lg">Maintenance Schedule Details</h3>
                <button class="btn btn-sm btn-circle btn-ghost hover:bg-white/20 text-white" id="closeViewMaintenanceModalX">✕</button>
            </div>
            <div class="p-4 max-h-[70vh] overflow-y-auto">
                <div class="space-y-4" id="maintenanceDetails">
                    <!-- Maintenance details will be populated here -->
                </div>
                <div class="modal-action flex justify-end pt-4 border-t">
                    <button type="button" class="btn btn-ghost btn-sm hover:bg-gray-100 transition-colors px-4" id="closeViewMaintenanceModal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reschedule Modal -->
    <div id="rescheduleModal" class="modal">
        <div class="modal-box max-w-md p-0 overflow-visible">
            <div class="flex justify-between items-center bg-warning p-4 rounded-t-lg">
                <h3 class="font-bold text-white text-lg">Reschedule Maintenance</h3>
                <button class="btn btn-sm btn-circle btn-ghost hover:bg-white/20 text-white" id="closeRescheduleModalX">✕</button>
            </div>
            <div class="p-4">
                <form id="rescheduleForm" class="space-y-4">
                    @csrf
                    <input type="hidden" id="rescheduleId" name="maintenance_id">
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">New Date *</span>
                            </label>
                            <input type="date" id="newScheduleDate" name="schedule_date" class="input input-bordered input-sm w-full" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">New Time *</span>
                            </label>
                            <input type="time" id="newScheduleTime" name="schedule_time" class="input input-bordered input-sm w-full" required>
                        </div>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Reschedule Reason</span>
                        </label>
                        <textarea id="rescheduleReason" name="reschedule_reason" class="textarea textarea-bordered textarea-sm h-16" 
                                  placeholder="Reason for rescheduling..."></textarea>
                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-action flex justify-end space-x-3 pt-4 border-t">
                        <button type="button" class="btn btn-ghost btn-sm hover:bg-gray-100 transition-colors px-4" id="closeRescheduleModal">Cancel</button>
                        <button type="submit" class="btn btn-warning btn-sm bg-gradient-to-r from-warning to-warning/90 hover:from-warning/90 hover:to-warning transition-all duration-300 shadow-lg px-4">
                            <i class="bx bx-calendar-event mr-1"></i>Reschedule
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Loading Modal -->
    <div id="loadingModal" class="modal">
        <div class="modal-box max-w-sm text-center p-4">
            <div class="loading loading-spinner loading-lg text-primary mb-2"></div>
            <h3 class="font-bold text-sm mb-1" id="loadingTitle">Processing...</h3>
        </div>
    </div>

<script>
    let maintenanceSchedules = [];
    let assets = [];

    // ==================== CONFIGURATION ====================
    const API_BASE_URL = 'http://localhost:8001/api/alms';

    // Utility functions
    function formatDateTime(dateString, timeString) {
        if (!dateString) return 'N/A';
        
        const date = new Date(dateString);
        const time = timeString || '00:00:00';
        
        return date.toLocaleDateString('en-US', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit'
        }) + ', ' + formatTime(time);
    }

    function formatTime(timeString) {
        if (!timeString) return 'N/A';
        
        const [hours, minutes] = timeString.split(':');
        const hour = parseInt(hours);
        const ampm = hour >= 12 ? 'pm' : 'am';
        const displayHour = hour % 12 || 12;
        
        return `${displayHour}:${minutes}${ampm}`;
    }

    function getStatusBadge(status) {
        const statusClasses = {
            'Pending': 'bg-yellow-600 uppercase',
            'Under Maintenance': 'bg-blue-600 uppercase',
            'Re-Schedule': 'bg-orange-500 uppercase',
            'Replacement': 'bg-purple-600 uppercase',
            'Reject': 'bg-red-600 uppercase',
            'Done': 'bg-green-600 uppercase'
        };
        
        return `<span class="badge text-white font-bold tracking-wider text-xs px-3 py-2 ${statusClasses[status] || 'bg-gray-400'} border-0">
            ${status}
        </span>`;
    }

    // Show loading modal
    function showLoadingModal(title = 'Processing...') {
        document.getElementById('loadingTitle').textContent = title;
        document.getElementById('loadingModal').classList.add('modal-open');
    }

    // Hide loading modal
    function hideLoadingModal() {
        document.getElementById('loadingModal').classList.remove('modal-open');
    }

    // Show success toast
    function showSuccessToast(message) {
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

        Toast.fire({
            icon: 'success',
            title: message
        });
    }

    // Load data on page load
    document.addEventListener('DOMContentLoaded', function() {
        initializeEventListeners();
        loadAssets();
        loadMaintenanceSchedules();
    });

    function initializeEventListeners() {
        // Add maintenance button
        document.getElementById('addMaintenanceBtn').addEventListener('click', openAddMaintenanceModal);

        // Close modal buttons
        document.getElementById('closeMaintenanceModal').addEventListener('click', closeMaintenanceModal);
        document.getElementById('closeMaintenanceModalX').addEventListener('click', closeMaintenanceModal);
        document.getElementById('closeViewMaintenanceModal').addEventListener('click', closeViewMaintenanceModal);
        document.getElementById('closeViewMaintenanceModalX').addEventListener('click', closeViewMaintenanceModal);
        document.getElementById('closeRescheduleModal').addEventListener('click', closeRescheduleModal);
        document.getElementById('closeRescheduleModalX').addEventListener('click', closeRescheduleModal);

        // Form submission
        document.getElementById('maintenanceForm').addEventListener('submit', handleMaintenanceSubmit);
        document.getElementById('rescheduleForm').addEventListener('submit', handleRescheduleSubmit);

        // Search and filter
        document.getElementById('searchMaintenance').addEventListener('input', filterMaintenance);
        document.getElementById('statusFilter').addEventListener('change', filterMaintenance);
        document.getElementById('typeFilter').addEventListener('change', filterMaintenance);

        // Set default dates
        const now = new Date();
        document.getElementById('scheduleDate').value = now.toISOString().split('T')[0];
        document.getElementById('scheduleTime').value = now.toTimeString().slice(0, 5);
        document.getElementById('newScheduleDate').value = now.toISOString().split('T')[0];
        document.getElementById('newScheduleTime').value = now.toTimeString().slice(0, 5);
    }

    async function loadAssets() {
        try {
            const response = await fetch(`${API_BASE_URL}/assets`);
            if (!response.ok) {
                const errorData = await response.json().catch(() => ({ message: 'Unknown error' }));
                throw new Error(`HTTP error! status: ${response.status}, message: ${errorData.message}`);
            }
            
            const result = await response.json();
            
            if (result.success) {
                assets = result.data || [];
                populateAssetDropdown();
            }
        } catch (error) {
            console.error('Error loading assets:', error);
            Swal.fire('Error', 'Failed to load assets: ' + error.message, 'error');
        }
    }

    function populateAssetDropdown() {
        const assetSelect = document.getElementById('assetName');
        assetSelect.innerHTML = '<option value="">Select Asset</option>';
        
        assets.forEach(asset => {
            const option = document.createElement('option');
            option.value = asset.asset_name;
            option.textContent = `${asset.asset_name} (${asset.asset_id})`;
            option.setAttribute('data-asset-id', asset.id);
            assetSelect.appendChild(option);
        });
    }

    async function loadMaintenanceSchedules() {
        try {
            showMaintenanceLoadingState();
            const response = await fetch(`${API_BASE_URL}/maintenance-schedules`);
            
            if (!response.ok) {
                const errorData = await response.json().catch(() => ({ message: 'Unknown error' }));
                throw new Error(`HTTP error! status: ${response.status}, message: ${errorData.message}`);
            }
            
            const result = await response.json();
            
            if (result.success) {
                maintenanceSchedules = result.data || [];
                renderMaintenanceSchedules(maintenanceSchedules);
                updateStats(maintenanceSchedules);
            } else {
                throw new Error(result.message || 'Failed to load maintenance schedules');
            }
        } catch (error) {
            console.error('Error loading maintenance schedules:', error);
            showMaintenanceErrorState('Failed to load maintenance schedules: ' + error.message);
        }
    }

    function showMaintenanceLoadingState() {
        const tbody = document.getElementById('maintenance-table-body');
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-8">
                    <div class="loading loading-spinner loading-lg"></div>
                    <p class="text-gray-500 mt-2">Loading maintenance schedules...</p>
                </td>
            </tr>
        `;
    }

    function showMaintenanceErrorState(message) {
        const tbody = document.getElementById('maintenance-table-body');
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-8">
                    <i class="bx bx-error text-4xl text-red-400 mb-2"></i>
                    <p class="text-red-500">${message}</p>
                    <button class="btn btn-sm btn-outline mt-2" onclick="loadMaintenanceSchedules()">Retry</button>
                </td>
            </tr>
        `;
    }

    function renderMaintenanceSchedules(schedulesData) {
        const tbody = document.getElementById('maintenance-table-body');
        
        if (schedulesData.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="text-center py-8">
                        <i class="bx bx-calendar text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500">No maintenance schedules found</p>
                        <button class="btn btn-sm btn-primary mt-2" id="addFirstMaintenanceBtn">Schedule First Maintenance</button>
                    </td>
                </tr>
            `;
            document.getElementById('addFirstMaintenanceBtn')?.addEventListener('click', openAddMaintenanceModal);
            return;
        }

        tbody.innerHTML = schedulesData.map(schedule => {
            return `
            <tr>
                <td class="font-mono font-semibold text-sm">${schedule.schedule_id}</td>
                <td class="text-sm">${schedule.asset_name}</td>
                <td class="text-sm"> ${schedule.maintenance_type}</td>
                <td class="text-sm">${schedule.assigned_personnel}</td>
                <td class="text-sm">${formatDateTime(schedule.schedule_date, schedule.schedule_time)}</td>
                <td>${getStatusBadge(schedule.status)}</td>
                <td>
                    <div class="flex space-x-1">
                        <button title="View" class="btn btn-sm btn-circle btn-info view-maintenance-btn" data-maintenance-id="${schedule.id}">
                            <i class="bx bx-show-alt text-sm"></i>
                        </button>
                        <button title="Edit" class="hidden btn btn-sm btn-circle btn-warning edit-maintenance-btn" data-maintenance-id="${schedule.id}">
                            <i class="bx bx-edit text-sm"></i>
                        </button>
                        ${schedule.status !== 'Done' && schedule.status !== 'Reject' ? `
                        <button title="Reschedule" class="btn btn-sm btn-circle btn-secondary reschedule-maintenance-btn" data-maintenance-id="${schedule.id}">
                            <i class="bx bx-calendar-event text-sm"></i>
                        </button>
                        ` : ''}
                        <button title="Delete" class="btn btn-sm btn-circle btn-error delete-maintenance-btn" data-maintenance-id="${schedule.id}">
                            <i class="bx bx-trash text-sm"></i>
                        </button>
                    </div>
                </td>
            </tr>
            `;
        }).join('');

        // Add event listeners to dynamically created buttons
        addDynamicEventListeners();
    }

    function addDynamicEventListeners() {
        document.querySelectorAll('.view-maintenance-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const maintenanceId = this.getAttribute('data-maintenance-id');
                viewMaintenance(parseInt(maintenanceId));
            });
        });

        document.querySelectorAll('.edit-maintenance-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const maintenanceId = this.getAttribute('data-maintenance-id');
                editMaintenance(parseInt(maintenanceId));
            });
        });

        document.querySelectorAll('.reschedule-maintenance-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const maintenanceId = this.getAttribute('data-maintenance-id');
                openRescheduleModal(parseInt(maintenanceId));
            });
        });

        document.querySelectorAll('.delete-maintenance-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const maintenanceId = this.getAttribute('data-maintenance-id');
                deleteMaintenance(parseInt(maintenanceId));
            });
        });
    }

    function updateStats(schedulesData) {
        document.getElementById('total-schedules').textContent = schedulesData.length;
        document.getElementById('pending-schedules').textContent = 
            schedulesData.filter(s => s.status === 'Pending').length;
        document.getElementById('maintenance-schedules').textContent = 
            schedulesData.filter(s => s.status === 'Under Maintenance').length;
        document.getElementById('reschedule-schedules').textContent = 
            schedulesData.filter(s => s.status === 'Re-Schedule').length;
        document.getElementById('replacement-schedules').textContent = 
            schedulesData.filter(s => s.status === 'Replacement').length;
        document.getElementById('done-schedules').textContent = 
            schedulesData.filter(s => s.status === 'Done').length;
    }

    function filterMaintenance() {
        const searchTerm = document.getElementById('searchMaintenance').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const typeFilter = document.getElementById('typeFilter').value;
        
        const filtered = maintenanceSchedules.filter(schedule => {
            const matchesSearch = searchTerm === '' || 
                schedule.schedule_id.toLowerCase().includes(searchTerm) ||
                schedule.asset_name.toLowerCase().includes(searchTerm) ||
                schedule.assigned_personnel.toLowerCase().includes(searchTerm);
            
            const matchesStatus = statusFilter === '' || schedule.status === statusFilter;
            const matchesType = typeFilter === '' || schedule.maintenance_type === typeFilter;
            
            return matchesSearch && matchesStatus && matchesType;
        });
        
        renderMaintenanceSchedules(filtered);
        updateStats(filtered);
    }

    // Modal Functions
    function openAddMaintenanceModal() {
        document.getElementById('maintenanceModalTitle').textContent = 'Schedule Maintenance';
        document.getElementById('maintenanceModalSubmitText').textContent = 'Save Schedule';
        document.getElementById('maintenanceForm').reset();
        document.getElementById('maintenanceId').value = '';
        document.getElementById('maintenanceStatus').value = 'Pending';
        
        const now = new Date();
        document.getElementById('scheduleDate').value = now.toISOString().split('T')[0];
        document.getElementById('scheduleTime').value = now.toTimeString().slice(0, 5);
        
        // Clear auto-generated ID field for new schedules
        document.getElementById('scheduleID').value = 'Auto-generated';
        
        document.getElementById('maintenanceModal').classList.add('modal-open');
    }

    function closeMaintenanceModal() {
        document.getElementById('maintenanceModal').classList.remove('modal-open');
        document.getElementById('maintenanceForm').reset();
    }

    function openViewMaintenanceModal() {
        document.getElementById('viewMaintenanceModal').classList.add('modal-open');
    }

    function closeViewMaintenanceModal() {
        document.getElementById('viewMaintenanceModal').classList.remove('modal-open');
    }

    function openRescheduleModal(maintenanceId) {
        const schedule = maintenanceSchedules.find(s => s.id === maintenanceId);
        if (!schedule) return;

        document.getElementById('rescheduleId').value = maintenanceId;
        document.getElementById('newScheduleDate').value = schedule.schedule_date;
        document.getElementById('newScheduleTime').value = schedule.schedule_time || '00:00';
        document.getElementById('rescheduleReason').value = '';

        document.getElementById('rescheduleModal').classList.add('modal-open');
    }

    function closeRescheduleModal() {
        document.getElementById('rescheduleModal').classList.remove('modal-open');
        document.getElementById('rescheduleForm').reset();
    }

    // Maintenance Actions
    function viewMaintenance(maintenanceId) {
        const schedule = maintenanceSchedules.find(s => s.id === maintenanceId);
        if (!schedule) return;

        const maintenanceDetails = `
            <div class="space-y-4">
                <!-- Basic Information -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <strong class="text-gray-700 text-xs">Schedule ID:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1 font-mono">${schedule.schedule_id}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Asset Name:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${schedule.asset_name}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Maintenance Type:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1"> ${schedule.maintenance_type} </p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Assigned Personnel:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${schedule.assigned_personnel}</p>
                    </div>
                </div>

                <!-- Schedule Information -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <strong class="text-gray-700 text-xs">Schedule Date & Time:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${formatDateTime(schedule.schedule_date, schedule.schedule_time)}</p>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <strong class="text-gray-700 text-xs">Status:</strong>
                    <p class="mt-1 p-2">${getStatusBadge(schedule.status)}</p>
                </div>

                <!-- Notes -->
                ${schedule.notes ? `
                <div>
                    <strong class="text-gray-700 text-xs">Notes:</strong>
                    <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${schedule.notes}</p>
                </div>
                ` : ''}

                ${schedule.reschedule_reason ? `
                <div>
                    <strong class="text-gray-700 text-xs">Reschedule Reason:</strong>
                    <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${schedule.reschedule_reason}</p>
                </div>
                ` : ''}

                <!-- Timestamps -->
                <div class="grid grid-cols-2 gap-4">
                    ${schedule.created_at ? `
                    <div>
                        <strong class="text-gray-700 text-xs">Created:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${new Date(schedule.created_at).toLocaleString()}</p>
                    </div>
                    ` : ''}
                    ${schedule.updated_at ? `
                    <div>
                        <strong class="text-gray-700 text-xs">Last Updated:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${new Date(schedule.updated_at).toLocaleString()}</p>
                    </div>
                    ` : ''}
                </div>
            </div>
        `;

        document.getElementById('maintenanceDetails').innerHTML = maintenanceDetails;
        openViewMaintenanceModal();
    }

    function editMaintenance(maintenanceId) {
        const schedule = maintenanceSchedules.find(s => s.id === maintenanceId);
        if (!schedule) return;

        document.getElementById('maintenanceModalTitle').textContent = 'Edit Maintenance Schedule';
        document.getElementById('maintenanceModalSubmitText').textContent = 'Update Schedule';
        
        document.getElementById('maintenanceId').value = schedule.id;
        document.getElementById('scheduleID').value = schedule.schedule_id;
        document.getElementById('assetName').value = schedule.asset_name;
        document.getElementById('maintenanceType').value = schedule.maintenance_type;
        document.getElementById('assignedPersonnel').value = schedule.assigned_personnel;
        document.getElementById('scheduleDate').value = schedule.schedule_date;
        document.getElementById('scheduleTime').value = schedule.schedule_time || '00:00';
        document.getElementById('maintenanceStatus').value = schedule.status;
        document.getElementById('notes').value = schedule.notes || '';

        document.getElementById('maintenanceModal').classList.add('modal-open');
    }

    async function handleMaintenanceSubmit(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const maintenanceId = document.getElementById('maintenanceId').value;
        const isEdit = !!maintenanceId;

        const maintenanceData = {
            asset_name: formData.get('asset_name'),
            maintenance_type: formData.get('maintenance_type'),
            assigned_personnel: formData.get('assigned_personnel'),
            schedule_date: formData.get('schedule_date'),
            schedule_time: formData.get('schedule_time'),
            status: formData.get('status'),
            notes: formData.get('notes')
        };
        
        try {
            showLoadingModal(
                isEdit ? 'Updating Schedule...' : 'Creating Schedule...'
            );

            let response;
            if (isEdit) {
                response = await fetch(`${API_BASE_URL}/maintenance-schedules/${maintenanceId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(maintenanceData)
                });
            } else {
                response = await fetch(`${API_BASE_URL}/maintenance-schedules`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(maintenanceData)
                });
            }

            const result = await response.json();

            if (response.ok && result.success) {
                hideLoadingModal();
                closeMaintenanceModal();
                
                // Wait for data to reload before showing success message
                await loadMaintenanceSchedules();
                
                showSuccessToast(
                    isEdit ? 'Maintenance schedule updated successfully!' : 'Maintenance schedule created successfully!'
                );
            } else {
                throw new Error(result.message || `Failed to ${isEdit ? 'update' : 'create'} maintenance schedule`);
            }
        } catch (error) {
            hideLoadingModal();
            Swal.fire('Error', `Failed to ${isEdit ? 'update' : 'create'} maintenance schedule: ` + error.message, 'error');
        }
    }

    async function handleRescheduleSubmit(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const maintenanceId = document.getElementById('rescheduleId').value;

        const rescheduleData = {
            schedule_date: formData.get('schedule_date'),
            schedule_time: formData.get('schedule_time'),
            reschedule_reason: formData.get('reschedule_reason'),
            status: 'Re-Schedule'
        };
        
        try {
            showLoadingModal('Rescheduling Maintenance...');

            const response = await fetch(`${API_BASE_URL}/maintenance-schedules/${maintenanceId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(rescheduleData)
            });

            const result = await response.json();

            if (response.ok && result.success) {
                hideLoadingModal();
                closeRescheduleModal();
                
                // Wait for data to reload before showing success message
                await loadMaintenanceSchedules();
                
                showSuccessToast('Maintenance rescheduled successfully!');
            } else {
                throw new Error(result.message || 'Failed to reschedule maintenance');
            }
        } catch (error) {
            hideLoadingModal();
            Swal.fire('Error', 'Failed to reschedule maintenance: ' + error.message, 'error');
        }
    }

    async function deleteMaintenance(maintenanceId) {
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: "This will permanently delete the maintenance schedule!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        });

        if (result.isConfirmed) {
            try {
                showLoadingModal('Deleting Schedule...');

                const response = await fetch(`${API_BASE_URL}/maintenance-schedules/${maintenanceId}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    hideLoadingModal();
                    
                    // Wait for data to reload before showing success message
                    await loadMaintenanceSchedules();
                    
                    showSuccessToast('Maintenance schedule deleted successfully!');
                } else {
                    throw new Error(result.message || 'Failed to delete maintenance schedule');
                }
            } catch (error) {
                hideLoadingModal();
                Swal.fire('Error', 'Failed to delete maintenance schedule: ' + error.message, 'error');
            }
        }
    }
</script>

<style>
    .modal-box {
        max-height: 85vh;
    }
    input:read-only {
        background-color: #f3f4f6;
        cursor: not-allowed;
    }
    .modal-box .max-h-\[70vh\] {
        max-height: 70vh;
    }
    .table td {
        white-space: nowrap;
    }
</style>
@endsection