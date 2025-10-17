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
        // ==================== CONFIGURATION ====================
        const API_BASE_URL = 'http://localhost:8001/api/plt';

        // ==================== STATE MANAGEMENT ====================
        let currentPage = 1;
        let totalPages = 1;
        let searchTerm = '';
        let statusFilter = '';

        // ==================== DOM ELEMENTS ====================
        const milestonesTableBody = document.getElementById('milestones-table-body');
        const pagination = document.getElementById('pagination');
        const searchInput = document.getElementById('search-input');
        const statusFilterSelect = document.getElementById('status-filter');
        const milestoneModal = document.getElementById('milestone-modal');
        const milestoneForm = document.getElementById('milestone-form');
        const modalTitle = document.getElementById('modal-title');
        const milestoneIdInput = document.getElementById('milestone-id');
        const projectSelect = document.getElementById('project-select');
        const dispatchSelect = document.getElementById('dispatch-select');
        const deleteModal = document.getElementById('delete-modal');
        const confirmDeleteBtn = document.getElementById('confirm-delete-btn');

        // ==================== EVENT LISTENERS ====================
        document.addEventListener('DOMContentLoaded', function() {
            loadMilestones();
            loadProjectsForSelect();
            loadDispatchesForSelect();
            setupEventListeners();
        });

        function setupEventListeners() {
            searchInput.addEventListener('input', debounce(() => {
                searchTerm = searchInput.value;
                currentPage = 1;
                loadMilestones();
            }, 500));

            statusFilterSelect.addEventListener('change', () => {
                statusFilter = statusFilterSelect.value;
                currentPage = 1;
                loadMilestones();
            });

            milestoneForm.addEventListener('submit', handleMilestoneSubmit);
        }

        // ==================== API FUNCTIONS ====================
        async function loadMilestones() {
            try {
                showLoading();
                const params = new URLSearchParams({
                    page: currentPage,
                    search: searchTerm,
                    status: statusFilter
                });

                const response = await fetch(`${API_BASE_URL}/milestones?${params}`);
                const result = await response.json();

                if (result.success) {
                    displayMilestones(result.data);
                    updateStats();
                } else {
                    throw new Error(result.message || 'Failed to load milestones');
                }
            } catch (error) {
                console.error('Error loading milestones:', error);
                showError('Failed to load milestones: ' + error.message);
            } finally {
                hideLoading();
            }
        }

        async function loadProjectsForSelect() {
            try {
                const response = await fetch(`${API_BASE_URL}/projects`);
                const result = await response.json();

                if (result.success) {
                    projectSelect.innerHTML = '<option value="">Select Project</option>';
                    result.data.data.forEach(project => {
                        const option = document.createElement('option');
                        option.value = project.id;
                        option.textContent = project.name;
                        projectSelect.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Error loading projects:', error);
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
                        option.textContent = `DSP${String(dispatch.id).padStart(5, '0')} - ${getMaterialTypeText(dispatch.material_type)}`;
                        dispatchSelect.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Error loading dispatches:', error);
            }
        }

        async function updateStats() {
            try {
                const response = await fetch(`${API_BASE_URL}/milestones/stats`);
                const result = await response.json();

                if (result.success) {
                    const stats = result.data;
                    document.getElementById('total-milestones').textContent = stats.total_milestones;
                    document.getElementById('pending').textContent = stats.pending;
                    document.getElementById('completed').textContent = stats.completed;
                    document.getElementById('delayed').textContent = stats.delayed;
                }
            } catch (error) {
                console.error('Error loading stats:', error);
            }
        }

        async function createMilestone(milestoneData) {
            const response = await fetch(`${API_BASE_URL}/milestones`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(milestoneData)
            });
            return await response.json();
        }

        async function updateMilestone(id, milestoneData) {
            const response = await fetch(`${API_BASE_URL}/milestones/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(milestoneData)
            });
            return await response.json();
        }

        async function deleteMilestone(id) {
            const response = await fetch(`${API_BASE_URL}/milestones/${id}`, {
                method: 'DELETE'
            });
            return await response.json();
        }

        // ==================== UI FUNCTIONS ====================
        function displayMilestones(milestonesData) {
            const milestones = milestonesData.data;
            totalPages = milestonesData.last_page;

            milestonesTableBody.innerHTML = '';

            if (milestones.length === 0) {
                milestonesTableBody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500">
                            <i class="bx bx-flag text-4xl mb-2"></i>
                            <p>No milestones found</p>
                        </td>
                    </tr>
                `;
                return;
            }

            milestones.forEach(milestone => {
                const isOverdue = new Date(milestone.due_date) < new Date() && milestone.status !== 'completed';
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="font-mono">MIL${String(milestone.id).padStart(5, '0')}</td>
                    <td>${milestone.project ? escapeHtml(milestone.project.name) : 'N/A'}</td>
                    <td class="font-semibold">${escapeHtml(milestone.name)}</td>
                    <td>
                        <span class="badge ${getStatusBadgeClass(milestone.status)}">
                            ${getStatusText(milestone.status)}
                        </span>
                    </td>
                    <td>
                        ${milestone.delay_alert ? 
                            '<span class="badge badge-warning"><i class="bx bx-alarm-exclamation mr-1"></i>Delayed</span>' : 
                            '<span class="text-gray-400">-</span>'
                        }
                    </td>
                    <td>
                        <div class="flex gap-1">
                            <button class="btn btn-sm btn-circle btn-outline btn-info" onclick="viewMilestone(${milestone.id})">
                                <i class="bx bx-show"></i>
                            </button>
                            <button class="btn btn-sm btn-circle btn-outline btn-warning" onclick="editMilestone(${milestone.id})">
                                <i class="bx bx-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-circle btn-outline btn-error" onclick="openDeleteModal(${milestone.id})">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                milestonesTableBody.appendChild(row);
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
                    loadMilestones();
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
                    loadMilestones();
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
                    loadMilestones();
                }
            };
            pagination.appendChild(nextButton);
        }

        // ==================== MODAL FUNCTIONS ====================
        function openAddModal() {
            modalTitle.textContent = 'Add New Milestone';
            milestoneForm.reset();
            milestoneIdInput.value = '';
            milestoneModal.showModal();
        }

        async function viewMilestone(id) {
            try {
                showLoading();
                const response = await fetch(`${API_BASE_URL}/milestones/${id}`);
                const result = await response.json();

                if (result.success) {
                    const milestone = result.data;
                    const isOverdue = new Date(milestone.due_date) < new Date() && milestone.status !== 'completed';
                    
                    // Create and show view modal
                    const viewModal = document.createElement('dialog');
                    viewModal.className = 'modal modal-middle';
                    viewModal.innerHTML = `
                        <div class="modal-box max-w-2xl">
                            <h3 class="font-bold text-lg mb-4">Milestone Details - MIL${String(milestone.id).padStart(5, '0')}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Project</span>
                                    </label>
                                    <div class="p-2 bg-base-200 rounded">${milestone.project ? escapeHtml(milestone.project.name) : 'N/A'}</div>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Dispatch</span>
                                    </label>
                                    <div class="p-2 bg-base-200 rounded">${milestone.dispatch ? `DSP${String(milestone.dispatch.id).padStart(5, '0')}` : 'Not linked'}</div>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Milestone Name</span>
                                    </label>
                                    <div class="p-2 bg-base-200 rounded font-semibold">${escapeHtml(milestone.name)}</div>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Status</span>
                                    </label>
                                    <div class="p-2">
                                        <span class="badge ${getStatusBadgeClass(milestone.status)}">
                                            ${getStatusText(milestone.status)}
                                        </span>
                                    </div>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Due Date</span>
                                    </label>
                                    <div class="p-2 bg-base-200 rounded ${isOverdue ? 'bg-warning text-warning-content' : ''}">
                                        ${formatDate(milestone.due_date)}
                                        ${isOverdue ? '<span class="badge badge-error ml-2">Overdue</span>' : ''}
                                    </div>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Actual Date</span>
                                    </label>
                                    <div class="p-2 bg-base-200 rounded">${milestone.actual_date ? formatDate(milestone.actual_date) : 'Not completed'}</div>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Delay Alert</span>
                                    </label>
                                    <div class="p-2">
                                        ${milestone.delay_alert ? 
                                            '<span class="badge badge-warning"><i class="bx bx-alarm-exclamation mr-1"></i>Delayed</span>' : 
                                            '<span class="badge badge-success"><i class="bx bx-check mr-1"></i>On Track</span>'
                                        }
                                    </div>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Days Status</span>
                                    </label>
                                    <div class="p-2 bg-base-200 rounded text-center">
                                        ${calculateDaysStatus(milestone.due_date, milestone.actual_date, milestone.status)}
                                    </div>
                                </div>
                                <div class="form-control md:col-span-2">
                                    <label class="label">
                                        <span class="label-text font-semibold">Description</span>
                                    </label>
                                    <div class="p-3 bg-base-200 rounded min-h-20">${milestone.description ? escapeHtml(milestone.description) : 'No description provided'}</div>
                                </div>
                            </div>
                            <div class="modal-action">
                                <button class="btn btn-ghost" onclick="this.closest('dialog').close()">Close</button>
                            </div>
                        </div>
                    `;
                    document.body.appendChild(viewModal);
                    viewModal.showModal();
                } else {
                    throw new Error(result.message || 'Failed to load milestone');
                }
            } catch (error) {
                console.error('Error loading milestone:', error);
                showError('Failed to load milestone: ' + error.message);
            } finally {
                hideLoading();
            }
        }

        async function editMilestone(id) {
            try {
                showLoading();
                const response = await fetch(`${API_BASE_URL}/milestones/${id}`);
                const result = await response.json();

                if (result.success) {
                    const milestone = result.data;
                    modalTitle.textContent = 'Edit Milestone';
                    milestoneIdInput.value = milestone.id;
                    
                    // Fill form with milestone data
                    Object.keys(milestone).forEach(key => {
                        const input = milestoneForm.querySelector(`[name="${key}"]`);
                        if (input) {
                            if (input.type === 'date') {
                                input.value = milestone[key] ? milestone[key].split('T')[0] : '';
                            } else if (input.type === 'checkbox') {
                                input.checked = milestone[key] || false;
                            } else {
                                input.value = milestone[key] || '';
                            }
                        }
                    });

                    milestoneModal.showModal();
                } else {
                    throw new Error(result.message || 'Failed to load milestone');
                }
            } catch (error) {
                console.error('Error loading milestone:', error);
                showError('Failed to load milestone: ' + error.message);
            } finally {
                hideLoading();
            }
        }

        function closeModal() {
            milestoneModal.close();
        }

        let milestoneToDelete = null;

        function openDeleteModal(id) {
            milestoneToDelete = id;
            deleteModal.showModal();
        }

        function closeDeleteModal() {
            milestoneToDelete = null;
            deleteModal.close();
        }

        async function confirmDelete() {
            if (!milestoneToDelete) return;

            try {
                showLoading();
                const result = await deleteMilestone(milestoneToDelete);

                if (result.success) {
                    showSuccess('Milestone deleted successfully');
                    closeDeleteModal();
                    loadMilestones();
                } else {
                    throw new Error(result.message || 'Failed to delete milestone');
                }
            } catch (error) {
                console.error('Error deleting milestone:', error);
                showError('Failed to delete milestone: ' + error.message);
            } finally {
                hideLoading();
            }
        }

        // ==================== FORM HANDLING ====================
        async function handleMilestoneSubmit(e) {
            e.preventDefault();
            
            const formData = new FormData(milestoneForm);
            const milestoneData = Object.fromEntries(formData);
            
            // Convert delay_alert to boolean
            milestoneData.delay_alert = milestoneData.delay_alert === 'on';

            try {
                showLoading();
                let result;

                if (milestoneIdInput.value) {
                    result = await updateMilestone(milestoneIdInput.value, milestoneData);
                } else {
                    result = await createMilestone(milestoneData);
                }

                if (result.success) {
                    showSuccess(`Milestone ${milestoneIdInput.value ? 'updated' : 'created'} successfully`);
                    closeModal();
                    loadMilestones();
                } else {
                    throw new Error(result.message || `Failed to ${milestoneIdInput.value ? 'update' : 'create'} milestone`);
                }
            } catch (error) {
                console.error('Error saving milestone:', error);
                showError('Failed to save milestone: ' + error.message);
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

        function formatDate(dateString) {
            return new Date(dateString).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        }

        function calculateDaysStatus(dueDate, actualDate, status) {
            const due = new Date(dueDate);
            const now = new Date();
            
            if (status === 'completed' && actualDate) {
                const actual = new Date(actualDate);
                const diffTime = actual - due;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                
                if (diffDays < 0) {
                    return `<span class="text-success">Completed ${Math.abs(diffDays)} days early</span>`;
                } else if (diffDays > 0) {
                    return `<span class="text-warning">Completed ${diffDays} days late</span>`;
                } else {
                    return `<span class="text-success">Completed on time</span>`;
                }
            } else {
                const diffTime = due - now;
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));
                
                if (diffDays < 0) {
                    return `<span class="text-error">Overdue by ${Math.abs(diffDays)} days</span>`;
                } else if (diffDays === 0) {
                    return `<span class="text-warning">Due today</span>`;
                } else {
                    return `<span class="text-success">Due in ${diffDays} days</span>`;
                }
            }
        }

        function getStatusBadgeClass(status) {
            const statusClasses = {
                'pending': 'badge-info',
                'in_progress': 'badge-primary',
                'completed': 'badge-success',
                'delayed': 'badge-warning'
            };
            return statusClasses[status] || 'badge-info';
        }

        function getStatusText(status) {
            const statusTexts = {
                'pending': 'Pending',
                'in_progress': 'In Progress',
                'completed': 'Completed',
                'delayed': 'Delayed'
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