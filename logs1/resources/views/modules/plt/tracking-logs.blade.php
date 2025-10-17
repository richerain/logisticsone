@extends('layouts.app')

@section('title', 'PLT Tracking Logs')

@section('content')
    <div class="module-content bg-white rounded-xl p-6 shadow block">
        <h2 class="text-2xl font-bold mb-4">Tracking Logs</h2>

        <!-- Search and Filters -->
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <div class="flex-1">
                <div class="form-control">
                    <div class="input-group">
                        <input type="text" placeholder="Search tracking logs..." class="input input-bordered w-full" id="search-input" />
                    </div>
                </div>
            </div>
            <div class="flex gap-2">
                <select class="select select-bordered" id="status-filter" onchange="loadTrackingLogs()">
                    <option value="">All Status</option>
                    <option value="dispatched">Dispatched</option>
                    <option value="in_transit">In Transit</option>
                    <option value="arrived_checkpoint">Arrived at Checkpoint</option>
                    <option value="delayed">Delayed</option>
                    <option value="delivered">Delivered</option>
                    <option value="failed">Failed</option>
                </select>
                <button class="btn btn-primary" onclick="openAddModal()">
                    <i class="bx bx-plus mr-2"></i> Add Tracking Log
                </button>
            </div>
        </div>

        <!-- Tracking Logs Table -->
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th>Log ID</th>
                        <th>Dispatch</th>
                        <th>Timestamp</th>
                        <th>Status Update</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="tracking-logs-table-body">
                    <!-- Data will be populated by JavaScript -->
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="flex justify-center mt-6">
            <div class="join" id="pagination">
                <!-- Pagination will be populated by JavaScript -->
            </div>
        </div>
    </div>

    <!-- Add/Edit Tracking Log Modal -->
    <dialog id="tracking-log-modal" class="modal">
        <div class="modal-box w-11/12 max-w-3xl">
            <h3 class="font-bold text-lg" id="modal-title">Add New Tracking Log</h3>
            <form id="tracking-log-form" class="mt-4">
                <input type="hidden" name="id" id="tracking-log-id">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Dispatch</span>
                        </label>
                        <select name="dispatch_id" class="select select-bordered" required id="dispatch-select">
                            <option value="">Select Dispatch</option>
                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Timestamp</span>
                        </label>
                        <input type="datetime-local" name="timestamp" class="input input-bordered" required />
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Location</span>
                        </label>
                        <input type="text" name="location" class="input input-bordered" placeholder="GPS coordinates or location name" />
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Status Update</span>
                        </label>
                        <select name="status_update" class="select select-bordered" required>
                            <option value="dispatched">Dispatched</option>
                            <option value="in_transit">In Transit</option>
                            <option value="arrived_checkpoint">Arrived at Checkpoint</option>
                            <option value="delayed">Delayed</option>
                            <option value="delivered">Delivered</option>
                            <option value="failed">Failed</option>
                        </select>
                    </div>
                    <div class="form-control md:col-span-2">
                        <label class="label">
                            <span class="label-text">Notes</span>
                        </label>
                        <textarea name="notes" class="textarea textarea-bordered h-24" placeholder="Additional notes or observations"></textarea>
                    </div>
                </div>
                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submit-btn">Save Tracking Log</button>
                </div>
            </form>
        </div>
    </dialog>

    <!-- Delete Confirmation Modal -->
    <dialog id="delete-modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Confirm Delete</h3>
            <p class="py-4">Are you sure you want to delete this tracking log? This action cannot be undone.</p>
            <div class="modal-action">
                <button class="btn btn-ghost" onclick="closeDeleteModal()">Cancel</button>
                <button class="btn btn-error" id="confirm-delete-btn">Delete</button>
            </div>
        </div>
    </dialog>

    <script>
        // ==================== CONFIGURATION ====================
        const API_BASE_URL = 'http://localhost:8001/api/plt';

        // ==================== STATE MANAGEMENT ====================
        let currentPage = 1;
        let totalPages = 1;
        let searchTerm = '';
        let statusFilter = '';

        // ==================== DOM ELEMENTS ====================
        const trackingLogsTableBody = document.getElementById('tracking-logs-table-body');
        const pagination = document.getElementById('pagination');
        const searchInput = document.getElementById('search-input');
        const statusFilterSelect = document.getElementById('status-filter');
        const trackingLogModal = document.getElementById('tracking-log-modal');
        const trackingLogForm = document.getElementById('tracking-log-form');
        const modalTitle = document.getElementById('modal-title');
        const trackingLogIdInput = document.getElementById('tracking-log-id');
        const dispatchSelect = document.getElementById('dispatch-select');
        const deleteModal = document.getElementById('delete-modal');
        const confirmDeleteBtn = document.getElementById('confirm-delete-btn');

        // ==================== EVENT LISTENERS ====================
        document.addEventListener('DOMContentLoaded', function() {
            loadTrackingLogs();
            loadDispatchesForSelect();
            setupEventListeners();
        });

        function setupEventListeners() {
            searchInput.addEventListener('input', debounce(() => {
                searchTerm = searchInput.value;
                currentPage = 1;
                loadTrackingLogs();
            }, 500));

            statusFilterSelect.addEventListener('change', () => {
                statusFilter = statusFilterSelect.value;
                currentPage = 1;
                loadTrackingLogs();
            });

            trackingLogForm.addEventListener('submit', handleTrackingLogSubmit);
        }

        // ==================== API FUNCTIONS ====================
        async function loadTrackingLogs() {
            try {
                showLoading();
                const params = new URLSearchParams({
                    page: currentPage,
                    search: searchTerm,
                    status_update: statusFilter
                });

                const response = await fetch(`${API_BASE_URL}/tracking-logs?${params}`);
                const result = await response.json();

                if (result.success) {
                    displayTrackingLogs(result.data);
                } else {
                    throw new Error(result.message || 'Failed to load tracking logs');
                }
            } catch (error) {
                console.error('Error loading tracking logs:', error);
                showError('Failed to load tracking logs: ' + error.message);
            } finally {
                hideLoading();
            }
        }

        async function loadDispatchesForSelect() {
            try {
                const response = await fetch(`${API_BASE_URL}/dispatches`);
                const result = await response.json();

                if (result.success) {
                    dispatchSelect.innerHTML = '<option value="">Select Dispatch</option>';
                    result.data.data.forEach(dispatch => {
                        const option = document.createElement('option');
                        option.value = dispatch.id;
                        option.textContent = `DSP${String(dispatch.id).padStart(5, '0')} - ${dispatch.project?.name || 'N/A'} (${getMaterialTypeText(dispatch.material_type)})`;
                        dispatchSelect.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Error loading dispatches:', error);
            }
        }

        async function createTrackingLog(trackingLogData) {
            const response = await fetch(`${API_BASE_URL}/tracking-logs`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(trackingLogData)
            });
            return await response.json();
        }

        async function updateTrackingLog(id, trackingLogData) {
            const response = await fetch(`${API_BASE_URL}/tracking-logs/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(trackingLogData)
            });
            return await response.json();
        }

        async function deleteTrackingLog(id) {
            const response = await fetch(`${API_BASE_URL}/tracking-logs/${id}`, {
                method: 'DELETE'
            });
            return await response.json();
        }

        // ==================== UI FUNCTIONS ====================
        function displayTrackingLogs(trackingLogsData) {
            const trackingLogs = trackingLogsData.data;
            totalPages = trackingLogsData.last_page;

            trackingLogsTableBody.innerHTML = '';

            if (trackingLogs.length === 0) {
                trackingLogsTableBody.innerHTML = `
                    <tr>
                        <td colspan="5" class="text-center py-8 text-gray-500">
                            <i class="bx bx-notepad text-4xl mb-2"></i>
                            <p>No tracking logs found</p>
                        </td>
                    </tr>
                `;
                return;
            }

            trackingLogs.forEach(log => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="font-mono">LOG${String(log.id).padStart(5, '0')}</td>
                    <td>
                        ${log.dispatch ? 
                            `DSP${String(log.dispatch.id).padStart(5, '0')}` : 
                            'N/A'
                        }
                    </td>
                    <td>
                        <div class="flex flex-col">
                            <span>${formatDateTime(log.timestamp)}</span>
                            <span class="text-xs text-gray-500">${formatRelativeTime(log.timestamp)}</span>
                        </div>
                    </td>
                    <td>
                        <span class="badge ${getStatusUpdateBadgeClass(log.status_update)}">
                            ${getStatusUpdateText(log.status_update)}
                        </span>
                    </td>
                    <td>
                        <div class="flex gap-1">
                            <button class="btn btn-sm btn-circle btn-outline btn-info" onclick="viewTrackingLog(${log.id})">
                                <i class="bx bx-show"></i>
                            </button>
                            <button class="btn btn-sm btn-circle btn-outline btn-warning" onclick="editTrackingLog(${log.id})">
                                <i class="bx bx-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-circle btn-outline btn-error" onclick="openDeleteModal(${log.id})">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                trackingLogsTableBody.appendChild(row);
            });

            updatePagination();
        }

        function updatePagination() {
            pagination.innerHTML = '';

            // Previous button
            const prevButton = document.createElement('button');
            prevButton.className = `join-item btn ${currentPage === 1 ? 'btn-disabled' : ''}`;
            prevButton.innerHTML = '<i class="bx bx-chevron-left"></i>';
            prevButton.onclick = () => {
                if (currentPage > 1) {
                    currentPage--;
                    loadTrackingLogs();
                }
            };
            pagination.appendChild(prevButton);

            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                const pageButton = document.createElement('button');
                pageButton.className = `join-item btn ${currentPage === i ? 'btn-active' : ''}`;
                pageButton.textContent = i;
                pageButton.onclick = () => {
                    currentPage = i;
                    loadTrackingLogs();
                };
                pagination.appendChild(pageButton);
            }

            // Next button
            const nextButton = document.createElement('button');
            nextButton.className = `join-item btn ${currentPage === totalPages ? 'btn-disabled' : ''}`;
            nextButton.innerHTML = '<i class="bx bx-chevron-right"></i>';
            nextButton.onclick = () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    loadTrackingLogs();
                }
            };
            pagination.appendChild(nextButton);
        }

        // ==================== MODAL FUNCTIONS ====================
        function openAddModal() {
            modalTitle.textContent = 'Add New Tracking Log';
            trackingLogForm.reset();
            trackingLogIdInput.value = '';
            
            // Set default timestamp to current time
            const now = new Date();
            const localDateTime = now.toISOString().slice(0, 16);
            trackingLogForm.querySelector('input[name="timestamp"]').value = localDateTime;
            
            trackingLogModal.showModal();
        }

        async function viewTrackingLog(id) {
            try {
                showLoading();
                const response = await fetch(`${API_BASE_URL}/tracking-logs/${id}`);
                const result = await response.json();

                if (result.success) {
                    const log = result.data;
                    
                    // Create and show view modal
                    const viewModal = document.createElement('dialog');
                    viewModal.className = 'modal modal-middle';
                    viewModal.innerHTML = `
                        <div class="modal-box max-w-2xl">
                            <h3 class="font-bold text-lg mb-4">Tracking Log Details - LOG${String(log.id).padStart(5, '0')}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Dispatch</span>
                                    </label>
                                    <div class="p-2 bg-base-200 rounded font-mono">
                                        ${log.dispatch ? 
                                            `DSP${String(log.dispatch.id).padStart(5, '0')}` : 
                                            'N/A'
                                        }
                                    </div>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Project</span>
                                    </label>
                                    <div class="p-2 bg-base-200 rounded">${log.dispatch?.project ? escapeHtml(log.dispatch.project.name) : 'N/A'}</div>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Timestamp</span>
                                    </label>
                                    <div class="p-2 bg-base-200 rounded">
                                        <div>${formatDateTime(log.timestamp)}</div>
                                        <div class="text-xs text-gray-500">${formatRelativeTime(log.timestamp)}</div>
                                    </div>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Status Update</span>
                                    </label>
                                    <div class="p-2">
                                        <span class="badge ${getStatusUpdateBadgeClass(log.status_update)}">
                                            ${getStatusUpdateText(log.status_update)}
                                        </span>
                                    </div>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Location</span>
                                    </label>
                                    <div class="p-2 bg-base-200 rounded">${log.location || 'Not specified'}</div>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Dispatch Status</span>
                                    </label>
                                    <div class="p-2">
                                        <span class="badge ${getStatusBadgeClass(log.dispatch?.status)}">
                                            ${getStatusText(log.dispatch?.status)}
                                        </span>
                                    </div>
                                </div>
                                <div class="form-control md:col-span-2">
                                    <label class="label">
                                        <span class="label-text font-semibold">Notes</span>
                                    </label>
                                    <div class="p-3 bg-base-200 rounded min-h-20">${log.notes ? escapeHtml(log.notes) : 'No additional notes'}</div>
                                </div>
                                ${log.dispatch ? `
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Material Type</span>
                                    </label>
                                    <div class="p-2 bg-base-200 rounded">
                                        <span class="badge badge-outline">
                                            ${getMaterialTypeText(log.dispatch.material_type)}
                                        </span>
                                    </div>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">From/To</span>
                                    </label>
                                    <div class="p-2 bg-base-200 rounded text-sm">
                                        <div>${escapeHtml(log.dispatch.from_location)}</div>
                                        <div class="text-gray-500">→</div>
                                        <div>${escapeHtml(log.dispatch.to_location)}</div>
                                    </div>
                                </div>
                                ` : ''}
                            </div>
                            <div class="modal-action">
                                <button class="btn btn-ghost" onclick="this.closest('dialog').close()">Close</button>
                            </div>
                        </div>
                    `;
                    document.body.appendChild(viewModal);
                    viewModal.showModal();
                } else {
                    throw new Error(result.message || 'Failed to load tracking log');
                }
            } catch (error) {
                console.error('Error loading tracking log:', error);
                showError('Failed to load tracking log: ' + error.message);
            } finally {
                hideLoading();
            }
        }

        async function editTrackingLog(id) {
            try {
                showLoading();
                const response = await fetch(`${API_BASE_URL}/tracking-logs/${id}`);
                const result = await response.json();

                if (result.success) {
                    const log = result.data;
                    modalTitle.textContent = 'Edit Tracking Log';
                    trackingLogIdInput.value = log.id;
                    
                    // Fill form with tracking log data
                    Object.keys(log).forEach(key => {
                        const input = trackingLogForm.querySelector(`[name="${key}"]`);
                        if (input) {
                            if (input.type === 'datetime-local') {
                                // Convert ISO string to local datetime format
                                const date = new Date(log[key]);
                                const localDateTime = date.toISOString().slice(0, 16);
                                input.value = localDateTime;
                            } else {
                                input.value = log[key] || '';
                            }
                        }
                    });

                    trackingLogModal.showModal();
                } else {
                    throw new Error(result.message || 'Failed to load tracking log');
                }
            } catch (error) {
                console.error('Error loading tracking log:', error);
                showError('Failed to load tracking log: ' + error.message);
            } finally {
                hideLoading();
            }
        }

        function closeModal() {
            trackingLogModal.close();
        }

        let trackingLogToDelete = null;

        function openDeleteModal(id) {
            trackingLogToDelete = id;
            deleteModal.showModal();
        }

        function closeDeleteModal() {
            trackingLogToDelete = null;
            deleteModal.close();
        }

        async function confirmDelete() {
            if (!trackingLogToDelete) return;

            try {
                showLoading();
                const result = await deleteTrackingLog(trackingLogToDelete);

                if (result.success) {
                    showSuccess('Tracking log deleted successfully');
                    closeDeleteModal();
                    loadTrackingLogs();
                } else {
                    throw new Error(result.message || 'Failed to delete tracking log');
                }
            } catch (error) {
                console.error('Error deleting tracking log:', error);
                showError('Failed to delete tracking log: ' + error.message);
            } finally {
                hideLoading();
            }
        }

        // ==================== FORM HANDLING ====================
        async function handleTrackingLogSubmit(e) {
            e.preventDefault();
            
            const formData = new FormData(trackingLogForm);
            const trackingLogData = Object.fromEntries(formData);

            try {
                showLoading();
                let result;

                if (trackingLogIdInput.value) {
                    result = await updateTrackingLog(trackingLogIdInput.value, trackingLogData);
                } else {
                    result = await createTrackingLog(trackingLogData);
                }

                if (result.success) {
                    showSuccess(`Tracking log ${trackingLogIdInput.value ? 'updated' : 'created'} successfully`);
                    closeModal();
                    loadTrackingLogs();
                } else {
                    throw new Error(result.message || `Failed to ${trackingLogIdInput.value ? 'update' : 'create'} tracking log`);
                }
            } catch (error) {
                console.error('Error saving tracking log:', error);
                showError('Failed to save tracking log: ' + error.message);
            } finally {
                hideLoading();
            }
        }

        // ==================== UTILITY FUNCTIONS ====================
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        function escapeHtml(unsafe) {
            return unsafe
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }

        function formatDateTime(dateTimeString) {
            return new Date(dateTimeString).toLocaleString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function formatRelativeTime(dateTimeString) {
            const date = new Date(dateTimeString);
            const now = new Date();
            const diffInSeconds = Math.floor((now - date) / 1000);

            if (diffInSeconds < 60) {
                return 'just now';
            } else if (diffInSeconds < 3600) {
                const minutes = Math.floor(diffInSeconds / 60);
                return `${minutes} minute${minutes > 1 ? 's' : ''} ago`;
            } else if (diffInSeconds < 86400) {
                const hours = Math.floor(diffInSeconds / 3600);
                return `${hours} hour${hours > 1 ? 's' : ''} ago`;
            } else {
                const days = Math.floor(diffInSeconds / 86400);
                return `${days} day${days > 1 ? 's' : ''} ago`;
            }
        }

        function getStatusUpdateBadgeClass(status) {
            const statusClasses = {
                'dispatched': 'badge-info',
                'in_transit': 'badge-primary',
                'arrived_checkpoint': 'badge-warning',
                'delayed': 'badge-error',
                'delivered': 'badge-success',
                'failed': 'badge-error'
            };
            return statusClasses[status] || 'badge-outline';
        }

        function getStatusUpdateText(status) {
            const statusTexts = {
                'dispatched': 'Dispatched',
                'in_transit': 'In Transit',
                'arrived_checkpoint': 'Arrived at Checkpoint',
                'delayed': 'Delayed',
                'delivered': 'Delivered',
                'failed': 'Failed'
            };
            return statusTexts[status] || status;
        }

        function getStatusBadgeClass(status) {
            const statusClasses = {
                'dispatched': 'badge-info',
                'in_transit': 'badge-primary',
                'delayed': 'badge-warning',
                'delivered': 'badge-success',
                'failed': 'badge-error'
            };
            return statusClasses[status] || 'badge-info';
        }

        function getStatusText(status) {
            const statusTexts = {
                'dispatched': 'Dispatched',
                'in_transit': 'In Transit',
                'delayed': 'Delayed',
                'delivered': 'Delivered',
                'failed': 'Failed'
            };
            return statusTexts[status] || status;
        }

        function getMaterialTypeText(type) {
            const typeTexts = {
                'equipment': 'Equipment',
                'document': 'Document',
                'supplies': 'Supplies',
                'furniture': 'Furniture'
            };
            return typeTexts[type] || type;
        }

        function showLoading() {
            // You can implement a loading spinner here
            console.log('Loading...');
        }

        function hideLoading() {
            // Hide loading spinner
            console.log('Loading complete');
        }

        function showSuccess(message) {
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: message,
                timer: 3000,
                showConfirmButton: false
            });
        }

        function showError(message) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: message,
                timer: 5000
            });
        }
    </script>
@endsection