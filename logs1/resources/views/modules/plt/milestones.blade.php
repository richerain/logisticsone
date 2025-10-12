@extends('layouts.app')

@section('title', 'PLT Milestones')

@section('content')
    <div class="module-content bg-white rounded-xl p-6 shadow block">
        <h2 class="text-2xl font-bold mb-4">Milestone Tracking</h2>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="stats">
                <div class="stat shadow-lg border-l-4 border-prinmary">
                    <div class="stat-figure text-primary">
                        <i class="bx bxs-flag text-3xl"></i>
                    </div>
                    <div class="stat-title">Total Milestones</div>
                    <div class="stat-value text-primary" id="total-milestones">0</div>
                </div>
            </div>
            
            <div class="stats">
                <div class="stat shadow-lg border-l-4 border-blue-400">
                    <div class="stat-figure text-info">
                        <i class="bx bxs-time-five text-3xl"></i>
                    </div>
                    <div class="stat-title">Pending</div>
                    <div class="stat-value text-info" id="pending">0</div>
                </div>
            </div>
            
            <div class="stats">
                <div class="stat shadow-lg border-l-4 border-success">
                    <div class="stat-figure text-success">
                        <i class="bx bxs-check-circle text-3xl"></i>
                    </div>
                    <div class="stat-title">Completed</div>
                    <div class="stat-value text-success" id="completed">0</div>
                </div>
            </div>
            
            <div class="stats">
                <div class="stat shadow-lg border-l-4 border-warning">
                    <div class="stat-figure text-warning">
                        <i class="bx bxs-alarm-exclamation text-3xl"></i>
                    </div>
                    <div class="stat-title">Delayed</div>
                    <div class="stat-value text-warning" id="delayed">0</div>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <div class="flex-1">
                <div class="form-control">
                    <div class="input-group">
                        <input type="text" placeholder="Search milestones..." class="input input-bordered w-full" id="search-input" />
                    </div>
                </div>
            </div>
            <div class="flex gap-2">
                <select class="select select-bordered" id="status-filter" onchange="loadMilestones()">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="in_progress">In Progress</option>
                    <option value="completed">Completed</option>
                    <option value="delayed">Delayed</option>
                </select>
                <button class="btn btn-primary" onclick="openAddModal()">
                    <i class="bx bx-plus mr-2"></i> Add Milestone
                </button>
            </div>
        </div>

        <!-- Milestones Table -->
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th>Milestone ID</th>
                        <th>Project</th>
                        <th>Milestone Name</th>
                        <th>Due Date</th>
                        <th>Actual Date</th>
                        <th>Status</th>
                        <th>Delay Alert</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="milestones-table-body">
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

    <!-- Add/Edit Milestone Modal -->
    <dialog id="milestone-modal" class="modal">
        <div class="modal-box w-11/12 max-w-3xl">
            <h3 class="font-bold text-lg" id="modal-title">Add New Milestone</h3>
            <form id="milestone-form" class="mt-4">
                <input type="hidden" name="id" id="milestone-id">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Project</span>
                        </label>
                        <select name="project_id" class="select select-bordered" required id="project-select">
                            <option value="">Select Project</option>
                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Dispatch (Optional)</span>
                        </label>
                        <select name="dispatch_id" class="select select-bordered" id="dispatch-select">
                            <option value="">Select Dispatch</option>
                        </select>
                    </div>
                    <div class="form-control md:col-span-2">
                        <label class="label">
                            <span class="label-text">Milestone Name</span>
                        </label>
                        <input type="text" name="name" class="input input-bordered" required />
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Status</span>
                        </label>
                        <select name="status" class="select select-bordered" required>
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                            <option value="delayed">Delayed</option>
                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Due Date</span>
                        </label>
                        <input type="date" name="due_date" class="input input-bordered" required />
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Actual Date</span>
                        </label>
                        <input type="date" name="actual_date" class="input input-bordered" />
                    </div>
                    <div class="form-control md:col-span-2">
                        <label class="label">
                            <span class="label-text">Delay Alert</span>
                        </label>
                        <label class="cursor-pointer label justify-start">
                            <input type="checkbox" name="delay_alert" class="checkbox checkbox-primary mr-2" />
                            <span class="label-text">Mark as delayed</span>
                        </label>
                    </div>
                    <div class="form-control md:col-span-2">
                        <label class="label">
                            <span class="label-text">Description</span>
                        </label>
                        <textarea name="description" class="textarea textarea-bordered h-24"></textarea>
                    </div>
                </div>
                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submit-btn">Save Milestone</button>
                </div>
            </form>
        </div>
    </dialog>

    <!-- Delete Confirmation Modal -->
    <dialog id="delete-modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Confirm Delete</h3>
            <p class="py-4">Are you sure you want to delete this milestone? This action cannot be undone.</p>
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

        // Load milestones on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadMilestones();
            loadStats();
            loadProjectsForSelect();
            loadDispatchesForSelect();
        });

        // Load milestones with pagination and filters
        async function loadMilestones(page = 1) {
            currentPage = page;
            const search = document.getElementById('search-input').value;
            const status = document.getElementById('status-filter').value;

            try {
                showLoading();
                const response = await fetch(`/api/plt/milestones?page=${page}&search=${search}&status=${status}`);
                const data = await response.json();

                if (data.success) {
                    renderMilestonesTable(data.data.data);
                    renderPagination(data.data);
                } else {
                    showToast('Error loading milestones', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error loading milestones', 'error');
            } finally {
                hideLoading();
            }
        }

        // Load stats
        async function loadStats() {
            try {
                const response = await fetch('/api/plt/milestones/stats');
                const data = await response.json();

                if (data.success) {
                    document.getElementById('total-milestones').textContent = data.data.total_milestones;
                    document.getElementById('pending').textContent = data.data.pending;
                    document.getElementById('completed').textContent = data.data.completed;
                    document.getElementById('delayed').textContent = data.data.delayed;
                }
            } catch (error) {
                console.error('Error loading stats:', error);
            }
        }

        // Load projects for select dropdown
        async function loadProjectsForSelect() {
            try {
                const response = await fetch('/api/plt/projects?per_page=100');
                const data = await response.json();

                if (data.success) {
                    const select = document.getElementById('project-select');
                    select.innerHTML = '<option value="">Select Project</option>';
                    
                    data.data.data.forEach(project => {
                        const option = document.createElement('option');
                        option.value = project.id;
                        option.textContent = project.name;
                        select.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Error loading projects:', error);
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
                        option.textContent = `DSP${String(dispatch.id).padStart(5, '0')} - ${dispatch.material_type}`;
                        select.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Error loading dispatches:', error);
            }
        }

        // Render milestones table
        function renderMilestonesTable(milestones) {
            const tbody = document.getElementById('milestones-table-body');
            tbody.innerHTML = '';

            if (milestones.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="text-center py-4">No milestones found</td></tr>';
                return;
            }

            milestones.forEach(milestone => {
                const statusBadge = getStatusBadge(milestone.status);
                const delayAlert = milestone.delay_alert ? 
                    '<span class="badge badge-warning">Delayed</span>' : 
                    '<span class="badge badge-success">On Time</span>';

                const row = `
                    <tr>
                        <td class="font-mono">MST${String(milestone.id).padStart(5, '0')}</td>
                        <td>${milestone.project?.name || 'N/A'}</td>
                        <td>${milestone.name}</td>
                        <td>${formatDate(milestone.due_date)}</td>
                        <td>${milestone.actual_date ? formatDate(milestone.actual_date) : 'Not completed'}</td>
                        <td>${statusBadge}</td>
                        <td>${delayAlert}</td>
                        <td>
                            <div class="flex gap-1">
                                <button class="btn btn-sm btn-circle btn-info" title="Edit" onclick="editMilestone(${milestone.id})">
                                    <i class="bx bx-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-circle btn-error" title="Delete" onclick="confirmDelete(${milestone.id})">
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
                    <button class="join-item btn" onclick="loadMilestones(${currentPage - 1})">«</button>
                `;
            }

            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                const activeClass = i === currentPage ? 'btn-active' : '';
                paginationDiv.innerHTML += `
                    <button class="join-item btn ${activeClass}" onclick="loadMilestones(${i})">${i}</button>
                `;
            }

            // Next button
            if (currentPage < totalPages) {
                paginationDiv.innerHTML += `
                    <button class="join-item btn" onclick="loadMilestones(${currentPage + 1})">»</button>
                `;
            }
        }

        // Open add modal
        function openAddModal() {
            editingId = null;
            document.getElementById('modal-title').textContent = 'Add New Milestone';
            document.getElementById('milestone-form').reset();
            document.getElementById('milestone-id').value = '';
            document.getElementById('milestone-modal').showModal();
        }

        // Edit milestone
        async function editMilestone(id) {
            try {
                showLoading();
                const response = await fetch(`/api/plt/milestones/${id}`);
                const data = await response.json();

                if (data.success) {
                    const milestone = data.data;
                    editingId = id;
                    
                    document.getElementById('modal-title').textContent = 'Edit Milestone';
                    document.getElementById('milestone-id').value = milestone.id;
                    document.getElementById('milestone-form').project_id.value = milestone.project_id;
                    document.getElementById('milestone-form').dispatch_id.value = milestone.dispatch_id || '';
                    document.getElementById('milestone-form').name.value = milestone.name;
                    document.getElementById('milestone-form').status.value = milestone.status;
                    document.getElementById('milestone-form').due_date.value = milestone.due_date;
                    document.getElementById('milestone-form').actual_date.value = milestone.actual_date || '';
                    document.getElementById('milestone-form').description.value = milestone.description || '';
                    document.getElementById('milestone-form').delay_alert.checked = milestone.delay_alert;
                    
                    document.getElementById('milestone-modal').showModal();
                } else {
                    showToast('Error loading milestone', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error loading milestone', 'error');
            } finally {
                hideLoading();
            }
        }

        // Handle form submission
        document.getElementById('milestone-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            await saveMilestone();
        });

        // Save milestone
        async function saveMilestone() {
            const formData = new FormData(document.getElementById('milestone-form'));
            const data = Object.fromEntries(formData);
            data.delay_alert = document.getElementById('milestone-form').delay_alert.checked;
            
            const url = editingId ? `/api/plt/milestones/${editingId}` : '/api/plt/milestones';
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
                    showToast(`Milestone ${editingId ? 'updated' : 'created'} successfully`, 'success');
                    closeModal();
                    loadMilestones();
                    loadStats();
                } else {
                    showToast(result.message || 'Error saving milestone', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error saving milestone', 'error');
            } finally {
                hideLoading();
            }
        }

        // Confirm delete
        function confirmDelete(id) {
            deleteId = id;
            document.getElementById('delete-modal').showModal();
        }

        // Delete milestone
        document.getElementById('confirm-delete-btn').addEventListener('click', async function() {
            try {
                showLoading();
                const response = await fetch(`/api/plt/milestones/${deleteId}`, {
                    method: 'DELETE'
                });

                const result = await response.json();

                if (result.success) {
                    showToast('Milestone deleted successfully', 'success');
                    closeDeleteModal();
                    loadMilestones();
                    loadStats();
                } else {
                    showToast(result.message || 'Error deleting milestone', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error deleting milestone', 'error');
            } finally {
                hideLoading();
            }
        });

        // Close modals
        function closeModal() {
            document.getElementById('milestone-modal').close();
        }

        function closeDeleteModal() {
            document.getElementById('delete-modal').close();
            deleteId = null;
        }

        // Utility functions
        function getStatusBadge(status) {
            const statusMap = {
                'pending': 'badge-info',
                'in_progress': 'badge-primary',
                'completed': 'badge-success',
                'delayed': 'badge-warning'
            };
            return `<span class="badge ${statusMap[status]}">${status.replace('_', ' ')}</span>`;
        }

        function formatDate(dateString) {
            return new Date(dateString).toLocaleDateString();
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