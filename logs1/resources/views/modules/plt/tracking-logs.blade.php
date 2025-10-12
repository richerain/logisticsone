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
                        <th>Project</th>
                        <th>Timestamp</th>
                        <th>Location</th>
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
                        <input type="text" name="status_update" class="input input-bordered" required placeholder="e.g., Arrived at checkpoint" />
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
        let currentPage = 1;
        let editingId = null;
        let deleteId = null;

        // Load tracking logs on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadTrackingLogs();
            loadDispatchesForSelect();
        });

        // Load tracking logs with pagination and filters
        async function loadTrackingLogs(page = 1) {
            currentPage = page;
            const search = document.getElementById('search-input').value;

            try {
                showLoading();
                const response = await fetch(`/api/plt/tracking-logs?page=${page}&search=${search}`);
                const data = await response.json();

                if (data.success) {
                    renderTrackingLogsTable(data.data.data);
                    renderPagination(data.data);
                } else {
                    showToast('Error loading tracking logs', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error loading tracking logs', 'error');
            } finally {
                hideLoading();
            }
        }

        // Load dispatches for select dropdown
        async function loadDispatchesForSelect() {
            try {
                const response = await fetch('/api/plt/dispatches?per_page=100');
                const data = await response.json();

                if (data.success) {
                    const select = document.getElementById('dispatch-select');
                    select.innerHTML = '<option value="">Select Dispatch</option>';
                    
                    data.data.data.forEach(dispatch => {
                        const option = document.createElement('option');
                        option.value = dispatch.id;
                        option.textContent = `DSP${String(dispatch.id).padStart(5, '0')} - ${dispatch.material_type} (${dispatch.project?.name || 'N/A'})`;
                        select.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Error loading dispatches:', error);
            }
        }

        // Render tracking logs table
        function renderTrackingLogsTable(trackingLogs) {
            const tbody = document.getElementById('tracking-logs-table-body');
            tbody.innerHTML = '';

            if (trackingLogs.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4">No tracking logs found</td></tr>';
                return;
            }

            trackingLogs.forEach(log => {
                const row = `
                    <tr>
                        <td class="font-mono">LOG${String(log.id).padStart(5, '0')}</td>
                        <td>DSP${String(log.dispatch_id).padStart(5, '0')}</td>
                        <td>${log.dispatch?.project?.name || 'N/A'}</td>
                        <td>${formatDateTime(log.timestamp)}</td>
                        <td>${log.location || 'N/A'}</td>
                        <td><span class="badge badge-info">${log.status_update}</span></td>
                        <td>
                            <div class="flex gap-1">
                                <button class="btn btn-sm btn-circle btn-info" title="Edit" onclick="editTrackingLog(${log.id})">
                                    <i class="bx bx-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-circle btn-error" title="Delete" onclick="confirmDelete(${log.id})">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });
        }

        // Render pagination
        function renderPagination(pagination) {
            const paginationDiv = document.getElementById('pagination');
            paginationDiv.innerHTML = '';

            const totalPages = pagination.last_page;
            const currentPage = pagination.current_page;

            // Previous button
            if (currentPage > 1) {
                paginationDiv.innerHTML += `
                    <button class="join-item btn" onclick="loadTrackingLogs(${currentPage - 1})">«</button>
                `;
            }

            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                const activeClass = i === currentPage ? 'btn-active' : '';
                paginationDiv.innerHTML += `
                    <button class="join-item btn ${activeClass}" onclick="loadTrackingLogs(${i})">${i}</button>
                `;
            }

            // Next button
            if (currentPage < totalPages) {
                paginationDiv.innerHTML += `
                    <button class="join-item btn" onclick="loadTrackingLogs(${currentPage + 1})">»</button>
                `;
            }
        }

        // Open add modal
        function openAddModal() {
            editingId = null;
            document.getElementById('modal-title').textContent = 'Add New Tracking Log';
            document.getElementById('tracking-log-form').reset();
            document.getElementById('tracking-log-id').value = '';
            
            // Set current datetime as default
            const now = new Date();
            const localDateTime = now.toISOString().slice(0, 16);
            document.getElementById('tracking-log-form').timestamp.value = localDateTime;
            
            document.getElementById('tracking-log-modal').showModal();
        }

        // Edit tracking log
        async function editTrackingLog(id) {
            try {
                showLoading();
                const response = await fetch(`/api/plt/tracking-logs/${id}`);
                const data = await response.json();

                if (data.success) {
                    const log = data.data;
                    editingId = id;
                    
                    document.getElementById('modal-title').textContent = 'Edit Tracking Log';
                    document.getElementById('tracking-log-id').value = log.id;
                    document.getElementById('tracking-log-form').dispatch_id.value = log.dispatch_id;
                    
                    // Format datetime for input
                    const timestamp = new Date(log.timestamp);
                    const localDateTime = timestamp.toISOString().slice(0, 16);
                    document.getElementById('tracking-log-form').timestamp.value = localDateTime;
                    
                    document.getElementById('tracking-log-form').location.value = log.location || '';
                    document.getElementById('tracking-log-form').status_update.value = log.status_update;
                    document.getElementById('tracking-log-form').notes.value = log.notes || '';
                    
                    document.getElementById('tracking-log-modal').showModal();
                } else {
                    showToast('Error loading tracking log', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error loading tracking log', 'error');
            } finally {
                hideLoading();
            }
        }

        // Handle form submission
        document.getElementById('tracking-log-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            await saveTrackingLog();
        });

        // Save tracking log
        async function saveTrackingLog() {
            const formData = new FormData(document.getElementById('tracking-log-form'));
            const data = Object.fromEntries(formData);
            const url = editingId ? `/api/plt/tracking-logs/${editingId}` : '/api/plt/tracking-logs';
            const method = editingId ? 'PUT' : 'POST';

            try {
                showLoading();
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                const result = await response.json();

                if (result.success) {
                    showToast(`Tracking log ${editingId ? 'updated' : 'created'} successfully`, 'success');
                    closeModal();
                    loadTrackingLogs();
                } else {
                    showToast(result.message || 'Error saving tracking log', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error saving tracking log', 'error');
            } finally {
                hideLoading();
            }
        }

        // Confirm delete
        function confirmDelete(id) {
            deleteId = id;
            document.getElementById('delete-modal').showModal();
        }

        // Delete tracking log
        document.getElementById('confirm-delete-btn').addEventListener('click', async function() {
            try {
                showLoading();
                const response = await fetch(`/api/plt/tracking-logs/${deleteId}`, {
                    method: 'DELETE'
                });

                const result = await response.json();

                if (result.success) {
                    showToast('Tracking log deleted successfully', 'success');
                    closeDeleteModal();
                    loadTrackingLogs();
                } else {
                    showToast(result.message || 'Error deleting tracking log', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error deleting tracking log', 'error');
            } finally {
                hideLoading();
            }
        });

        // Close modals
        function closeModal() {
            document.getElementById('tracking-log-modal').close();
        }

        function closeDeleteModal() {
            document.getElementById('delete-modal').close();
            deleteId = null;
        }

        // Utility functions
        function formatDateTime(dateTimeString) {
            return new Date(dateTimeString).toLocaleString();
        }

        function showLoading() {
            // You can implement a loading spinner here
            console.log('Loading...');
        }

        function hideLoading() {
            // Hide loading spinner
            console.log('Loading complete');
        }

        function showToast(message, type = 'info') {
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