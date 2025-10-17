@extends('layouts.app')

@section('title', 'PLT Projects')

@section('content')
    <div class="module-content bg-white rounded-xl p-6 shadow block">
        <h2 class="text-2xl font-bold mb-4">Project Management</h2>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="stats">
                <div class="stat bg-base-100 shadow-lg rounded-lg border-l-4 border-primary">
                    <div class="stat-figure text-primary">
                        <i class="bx bxs-briefcase text-3xl"></i>
                    </div>
                    <div class="stat-title">Total Projects</div>
                    <div class="stat-value text-primary" id="total-projects">0</div>
                </div>
            </div>
            
            <div class="stats">
                <div class="stat shadow-lg rounded-lg border-l-4 border-info">
                    <div class="stat-figure text-info">
                        <i class="bx bxs-rocket text-3xl"></i>
                    </div>
                    <div class="stat-title">Active Projects</div>
                    <div class="stat-value text-info" id="active-projects">0</div>
                </div>
            </div>
            
            <div class="stats">
                <div class="stat shadow-lg rounded-lgborder-l-4 border-warning">
                    <div class="stat-figure text-warning">
                        <i class="bx bxs-time text-3xl"></i>
                    </div>
                    <div class="stat-title">Delayed Projects</div>
                    <div class="stat-value text-warning" id="delayed-projects">0</div>
                </div>
            </div>
            
            <div class="stats">
                <div class="stat shadow-lg rounded-lg border-l-4 border-success">
                    <div class="stat-figure text-success">
                        <i class="bx bxs-check-circle text-3xl"></i>
                    </div>
                    <div class="stat-title">Completed</div>
                    <div class="stat-value text-success" id="completed-projects">0</div>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <div class="flex-1">
                <div class="form-control">
                    <div class="input-group">
                        <input type="text" placeholder="Search projects..." class="input input-bordered w-full" id="search-input" />
                    </div>
                </div>
            </div>
            <div class="flex gap-2">
                <select class="select select-bordered" id="status-filter">
                    <option value="">All Status</option>
                    <option value="planned">Planned</option>
                    <option value="in_progress">In Progress</option>
                    <option value="delayed">Delayed</option>
                    <option value="completed">Completed</option>
                    <option value="cancelled">Cancelled</option>
                </select>
                <button class="btn btn-primary" id="add-project-btn">
                    <i class="bx bx-plus mr-2"></i> Add Project
                </button>
            </div>
        </div>

        <!-- Projects Table -->
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th>Project ID</th>
                        <th>Project Name</th>
                        <th>From Branch</th>
                        <th>To Branch</th>
                        <th>Progress</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="projects-table-body">
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

    <!-- Add/Edit Project Modal -->
    <dialog id="project-modal" class="modal">
        <div class="modal-box w-11/12 max-w-2xl">
            <h3 class="font-bold text-lg" id="modal-title">Add New Project</h3>
            <form id="project-form" class="mt-4">
                <input type="hidden" name="id" id="project-id">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Project Name</span>
                        </label>
                        <input type="text" name="name" class="input input-bordered" required />
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Status</span>
                        </label>
                        <select name="status" class="select select-bordered" required>
                            <option value="planned">Planned</option>
                            <option value="in_progress">In Progress</option>
                            <option value="delayed">Delayed</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">From Branch</span>
                        </label>
                        <input type="text" name="branch_from" class="input input-bordered" required />
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">To Branch</span>
                        </label>
                        <input type="text" name="branch_to" class="input input-bordered" required />
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Start Date</span>
                        </label>
                        <input type="date" name="start_date" class="input input-bordered" required />
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">End Date</span>
                        </label>
                        <input type="date" name="end_date" class="input input-bordered" required />
                    </div>
                    <div class="form-control md:col-span-2">
                        <label class="label">
                            <span class="label-text">Progress (%)</span>
                        </label>
                        <input type="number" name="progress_percent" class="input input-bordered" min="0" max="100" required />
                    </div>
                    <div class="form-control md:col-span-2">
                        <label class="label">
                            <span class="label-text">Description</span>
                        </label>
                        <textarea name="description" class="textarea textarea-bordered h-24"></textarea>
                    </div>
                </div>
                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" id="cancel-btn">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submit-btn">Save Project</button>
                </div>
            </form>
        </div>
    </dialog>

    <!-- Delete Confirmation Modal -->
    <dialog id="delete-modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Confirm Delete</h3>
            <p class="py-4">Are you sure you want to delete this project? This action cannot be undone.</p>
            <div class="modal-action">
                <button class="btn btn-ghost" id="cancel-delete-btn">Cancel</button>
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
        const projectsTableBody = document.getElementById('projects-table-body');
        const pagination = document.getElementById('pagination');
        const searchInput = document.getElementById('search-input');
        const statusFilterSelect = document.getElementById('status-filter');
        const addProjectBtn = document.getElementById('add-project-btn');
        const projectModal = document.getElementById('project-modal');
        const projectForm = document.getElementById('project-form');
        const modalTitle = document.getElementById('modal-title');
        const projectIdInput = document.getElementById('project-id');
        const deleteModal = document.getElementById('delete-modal');
        const confirmDeleteBtn = document.getElementById('confirm-delete-btn');
        const cancelDeleteBtn = document.getElementById('cancel-delete-btn');
        const cancelBtn = document.getElementById('cancel-btn');

        // ==================== EVENT LISTENERS ====================
        document.addEventListener('DOMContentLoaded', function() {
            loadProjects();
            setupEventListeners();
        });

        function setupEventListeners() {
            searchInput.addEventListener('input', debounce(() => {
                searchTerm = searchInput.value;
                currentPage = 1;
                loadProjects();
            }, 500));

            statusFilterSelect.addEventListener('change', () => {
                statusFilter = statusFilterSelect.value;
                currentPage = 1;
                loadProjects();
            });

            addProjectBtn.addEventListener('click', openAddModal);
            projectForm.addEventListener('submit', handleProjectSubmit);
            cancelBtn.addEventListener('click', closeModal);
            cancelDeleteBtn.addEventListener('click', closeDeleteModal);
            confirmDeleteBtn.addEventListener('click', confirmDelete);
        }

        // ==================== API FUNCTIONS ====================
        async function loadProjects() {
            try {
                showLoading();
                const params = new URLSearchParams({
                    page: currentPage,
                    search: searchTerm,
                    status: statusFilter
                });

                const response = await fetch(`${API_BASE_URL}/projects?${params}`);
                const result = await response.json();

                if (result.success) {
                    displayProjects(result.data);
                    updateStats();
                } else {
                    throw new Error(result.message || 'Failed to load projects');
                }
            } catch (error) {
                console.error('Error loading projects:', error);
                showError('Failed to load projects: ' + error.message);
            } finally {
                hideLoading();
            }
        }

        async function updateStats() {
            try {
                const response = await fetch(`${API_BASE_URL}/projects/stats`);
                const result = await response.json();

                if (result.success) {
                    const stats = result.data;
                    document.getElementById('total-projects').textContent = stats.total_projects;
                    document.getElementById('active-projects').textContent = stats.active_projects;
                    document.getElementById('delayed-projects').textContent = stats.delayed_projects;
                    document.getElementById('completed-projects').textContent = stats.completed_projects;
                }
            } catch (error) {
                console.error('Error loading stats:', error);
            }
        }

        async function createProject(projectData) {
            const response = await fetch(`${API_BASE_URL}/projects`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(projectData)
            });
            return await response.json();
        }

        async function updateProject(id, projectData) {
            const response = await fetch(`${API_BASE_URL}/projects/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(projectData)
            });
            return await response.json();
        }

        async function deleteProject(id) {
            const response = await fetch(`${API_BASE_URL}/projects/${id}`, {
                method: 'DELETE'
            });
            return await response.json();
        }

        // ==================== UI FUNCTIONS ====================
        function displayProjects(projectsData) {
            const projects = projectsData.data;
            totalPages = projectsData.last_page;

            projectsTableBody.innerHTML = '';

            if (projects.length === 0) {
                projectsTableBody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center py-8 text-gray-500">
                            <i class="bx bx-package text-4xl mb-2"></i>
                            <p>No projects found</p>
                        </td>
                    </tr>
                `;
                return;
            }

            projects.forEach(project => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="font-mono">PRJ${String(project.id).padStart(5, '0')}</td>
                    <td class="font-semibold">${escapeHtml(project.name)}</td>
                    <td>${escapeHtml(project.branch_from)}</td>
                    <td>${escapeHtml(project.branch_to)}</td>
                    <td>
                        <div class="flex items-center gap-2">
                            <progress class="progress progress-primary w-20" value="${project.progress_percent}" max="100"></progress>
                            <span class="text-sm">${project.progress_percent}%</span>
                        </div>
                    </td>
                    <td>
                        <span class="badge ${getStatusBadgeClass(project.status)}">
                            ${getStatusText(project.status)}
                        </span>
                    </td>
                    <td>
                        <div class="flex gap-1">
                            <button class="btn btn-sm btn-circle btn-outline btn-info" onclick="viewProject(${project.id})">
                                <i class="bx bx-show"></i>
                            </button>
                            <button class="btn btn-sm btn-circle btn-outline btn-warning" onclick="editProject(${project.id})">
                                <i class="bx bx-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-circle btn-outline btn-error" onclick="openDeleteModal(${project.id})">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                projectsTableBody.appendChild(row);
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
                    loadProjects();
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
                    loadProjects();
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
                    loadProjects();
                }
            };
            pagination.appendChild(nextButton);
        }

        // ==================== MODAL FUNCTIONS ====================
        function openAddModal() {
            modalTitle.textContent = 'Add New Project';
            projectForm.reset();
            projectIdInput.value = '';
            projectModal.showModal();
        }

        async function viewProject(id) {
            try {
                showLoading();
                const response = await fetch(`${API_BASE_URL}/projects/${id}`);
                const result = await response.json();

                if (result.success) {
                    const project = result.data;
                    
                    // Create and show view modal
                    const viewModal = document.createElement('dialog');
                    viewModal.className = 'modal modal-middle';
                    viewModal.innerHTML = `
                        <div class="modal-box max-w-2xl">
                            <h3 class="font-bold text-lg mb-4">Project Details - PRJ${String(project.id).padStart(5, '0')}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Project Name</span>
                                    </label>
                                    <div class="p-2 bg-base-200 rounded">${escapeHtml(project.name)}</div>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Status</span>
                                    </label>
                                    <div class="p-2">
                                        <span class="badge ${getStatusBadgeClass(project.status)}">
                                            ${getStatusText(project.status)}
                                        </span>
                                    </div>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">From Branch</span>
                                    </label>
                                    <div class="p-2 bg-base-200 rounded">${escapeHtml(project.branch_from)}</div>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">To Branch</span>
                                    </label>
                                    <div class="p-2 bg-base-200 rounded">${escapeHtml(project.branch_to)}</div>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Start Date</span>
                                    </label>
                                    <div class="p-2 bg-base-200 rounded">${formatDate(project.start_date)}</div>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">End Date</span>
                                    </label>
                                    <div class="p-2 bg-base-200 rounded">${formatDate(project.end_date)}</div>
                                </div>
                                <div class="form-control md:col-span-2">
                                    <label class="label">
                                        <span class="label-text font-semibold">Progress</span>
                                    </label>
                                    <div class="flex items-center gap-4">
                                        <progress class="progress progress-primary w-full" value="${project.progress_percent}" max="100"></progress>
                                        <span class="text-lg font-semibold">${project.progress_percent}%</span>
                                    </div>
                                </div>
                                <div class="form-control md:col-span-2">
                                    <label class="label">
                                        <span class="label-text font-semibold">Description</span>
                                    </label>
                                    <div class="p-3 bg-base-200 rounded min-h-20">${project.description ? escapeHtml(project.description) : 'No description provided'}</div>
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
                    throw new Error(result.message || 'Failed to load project');
                }
            } catch (error) {
                console.error('Error loading project:', error);
                showError('Failed to load project: ' + error.message);
            } finally {
                hideLoading();
            }
        }

        async function editProject(id) {
            try {
                showLoading();
                const response = await fetch(`${API_BASE_URL}/projects/${id}`);
                const result = await response.json();

                if (result.success) {
                    const project = result.data;
                    modalTitle.textContent = 'Edit Project';
                    projectIdInput.value = project.id;
                    
                    // Fill form with project data
                    Object.keys(project).forEach(key => {
                        const input = projectForm.querySelector(`[name="${key}"]`);
                        if (input) {
                            if (input.type === 'date') {
                                input.value = project[key] ? project[key].split('T')[0] : '';
                            } else {
                                input.value = project[key] || '';
                            }
                        }
                    });

                    projectModal.showModal();
                } else {
                    throw new Error(result.message || 'Failed to load project');
                }
            } catch (error) {
                console.error('Error loading project:', error);
                showError('Failed to load project: ' + error.message);
            } finally {
                hideLoading();
            }
        }

        function closeModal() {
            projectModal.close();
        }

        let projectToDelete = null;

        function openDeleteModal(id) {
            projectToDelete = id;
            deleteModal.showModal();
        }

        function closeDeleteModal() {
            projectToDelete = null;
            deleteModal.close();
        }

        async function confirmDelete() {
            if (!projectToDelete) return;

            try {
                showLoading();
                const result = await deleteProject(projectToDelete);

                if (result.success) {
                    showSuccess('Project deleted successfully');
                    closeDeleteModal();
                    loadProjects();
                } else {
                    throw new Error(result.message || 'Failed to delete project');
                }
            } catch (error) {
                console.error('Error deleting project:', error);
                showError('Failed to delete project: ' + error.message);
            } finally {
                hideLoading();
            }
        }

        // ==================== FORM HANDLING ====================
        async function handleProjectSubmit(e) {
            e.preventDefault();
            
            const formData = new FormData(projectForm);
            const projectData = Object.fromEntries(formData);
            
            // Convert progress_percent to number
            projectData.progress_percent = parseInt(projectData.progress_percent);

            try {
                showLoading();
                let result;

                if (projectIdInput.value) {
                    result = await updateProject(projectIdInput.value, projectData);
                } else {
                    result = await createProject(projectData);
                }

                if (result.success) {
                    showSuccess(`Project ${projectIdInput.value ? 'updated' : 'created'} successfully`);
                    closeModal();
                    loadProjects();
                } else {
                    throw new Error(result.message || `Failed to ${projectIdInput.value ? 'update' : 'create'} project`);
                }
            } catch (error) {
                console.error('Error saving project:', error);
                showError('Failed to save project: ' + error.message);
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

        function getStatusBadgeClass(status) {
            const statusClasses = {
                'planned': 'badge-info',
                'in_progress': 'badge-primary',
                'delayed': 'badge-warning',
                'completed': 'badge-success',
                'cancelled': 'badge-error'
            };
            return statusClasses[status] || 'badge-info';
        }

        function getStatusText(status) {
            const statusTexts = {
                'planned': 'Planned',
                'in_progress': 'In Progress',
                'delayed': 'Delayed',
                'completed': 'Completed',
                'cancelled': 'Cancelled'
            };
            return statusTexts[status] || status;
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