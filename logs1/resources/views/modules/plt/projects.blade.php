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
                        <th>Start Date</th>
                        <th>End Date</th>
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

        let currentPage = 1;
        let editingId = null;
        let deleteId = null;

        // Make functions globally available
        window.loadProjects = loadProjects;
        window.openAddModal = openAddModal;
        window.editProject = editProject;
        window.confirmDelete = confirmDelete;
        window.closeModal = closeModal;
        window.closeDeleteModal = closeDeleteModal;

        // Load projects on page load
        document.addEventListener('DOMContentLoaded', function() {
            initializeEventListeners();
            loadProjects();
            loadStats();
        });

        function initializeEventListeners() {
            // Search input - search on Enter key or when typing stops
            document.getElementById('search-input').addEventListener('input', debounce(loadProjects, 500));
            
            // Status filter
            document.getElementById('status-filter').addEventListener('change', loadProjects);
            
            // Add project button
            document.getElementById('add-project-btn').addEventListener('click', openAddModal);
            
            // Form submission
            document.getElementById('project-form').addEventListener('submit', handleFormSubmit);
            
            // Modal cancel buttons
            document.getElementById('cancel-btn').addEventListener('click', closeModal);
            document.getElementById('cancel-delete-btn').addEventListener('click', closeDeleteModal);
            
            // Delete confirmation
            document.getElementById('confirm-delete-btn').addEventListener('click', deleteProject);
        }

        // Debounce function for search
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

        // Load projects with pagination and filters
        async function loadProjects(page = 1) {
            currentPage = page;
            const search = document.getElementById('search-input').value;
            const status = document.getElementById('status-filter').value;

            try {
                showLoading();
                const response = await fetch(`${API_BASE_URL}/projects?page=${page}&search=${search}&status=${status}`);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const contentType = response.headers.get("content-type");
                if (!contentType || !contentType.includes("application/json")) {
                    throw new Error("Response is not JSON");
                }
                
                const data = await response.json();

                if (data.success) {
                    renderProjectsTable(data.data.data);
                    renderPagination(data.data);
                } else {
                    showToast('Error loading projects: ' + (data.message || 'Unknown error'), 'error');
                }
            } catch (error) {
                console.error('Error loading projects:', error);
                showToast('Error loading projects. Please check if the PLT service is running.', 'error');
                renderProjectsTable([]);
            } finally {
                hideLoading();
            }
        }

        // Load stats
        async function loadStats() {
            try {
                const response = await fetch(`${API_BASE_URL}/projects/stats`);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const contentType = response.headers.get("content-type");
                if (!contentType || !contentType.includes("application/json")) {
                    throw new Error("Response is not JSON");
                }
                
                const data = await response.json();

                if (data.success) {
                    document.getElementById('total-projects').textContent = data.data.total_projects;
                    document.getElementById('active-projects').textContent = data.data.active_projects;
                    document.getElementById('delayed-projects').textContent = data.data.delayed_projects;
                    document.getElementById('completed-projects').textContent = data.data.completed_projects;
                }
            } catch (error) {
                console.error('Error loading stats:', error);
                // Don't show error for stats to avoid spamming
            }
        }

        // Render projects table
        function renderProjectsTable(projects) {
            const tbody = document.getElementById('projects-table-body');
            tbody.innerHTML = '';

            if (!projects || projects.length === 0) {
                tbody.innerHTML = '<tr><td colspan="9" class="text-center py-4">No projects found</td></tr>';
                return;
            }

            projects.forEach(project => {
                const statusBadge = getStatusBadge(project.status);
                const progressBar = `
                    <div class="flex items-center gap-2">
                        <progress class="progress progress-primary w-20" value="${project.progress_percent}" max="100"></progress>
                        <span class="text-sm">${project.progress_percent}%</span>
                    </div>
                `;

                const row = `
                    <tr>
                        <td class="font-mono">PRJ${String(project.id).padStart(5, '0')}</td>
                        <td>${project.name}</td>
                        <td>${project.branch_from}</td>
                        <td>${project.branch_to}</td>
                        <td>${formatDate(project.start_date)}</td>
                        <td>${formatDate(project.end_date)}</td>
                        <td>${progressBar}</td>
                        <td>${statusBadge}</td>
                        <td>
                            <div class="flex gap-1">
                                <button class="btn btn-sm btn-circle btn-info edit-btn" title="Edit" data-id="${project.id}">
                                    <i class="bx bx-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-circle btn-error delete-btn" title="Delete" data-id="${project.id}">
                                    <i class="bx bx-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                `;
                tbody.innerHTML += row;
            });

            // Add event listeners to action buttons
            document.querySelectorAll('.edit-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    editProject(parseInt(this.getAttribute('data-id')));
                });
            });

            document.querySelectorAll('.delete-btn').forEach(btn => {
                btn.addEventListener('click', function() {
                    confirmDelete(parseInt(this.getAttribute('data-id')));
                });
            });
        }

        // Render pagination
        function renderPagination(pagination) {
            const paginationDiv = document.getElementById('pagination');
            paginationDiv.innerHTML = '';

            if (!pagination) {
                return;
            }

            const totalPages = pagination.last_page;
            const currentPage = pagination.current_page;

            // Previous button
            if (currentPage > 1) {
                const prevBtn = document.createElement('button');
                prevBtn.className = 'join-item btn';
                prevBtn.textContent = '«';
                prevBtn.addEventListener('click', () => loadProjects(currentPage - 1));
                paginationDiv.appendChild(prevBtn);
            }

            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                const pageBtn = document.createElement('button');
                pageBtn.className = i === currentPage ? 'join-item btn btn-active' : 'join-item btn';
                pageBtn.textContent = i;
                pageBtn.addEventListener('click', () => loadProjects(i));
                paginationDiv.appendChild(pageBtn);
            }

            // Next button
            if (currentPage < totalPages) {
                const nextBtn = document.createElement('button');
                nextBtn.className = 'join-item btn';
                nextBtn.textContent = '»';
                nextBtn.addEventListener('click', () => loadProjects(currentPage + 1));
                paginationDiv.appendChild(nextBtn);
            }
        }

        // Open add modal
        function openAddModal() {
            editingId = null;
            document.getElementById('modal-title').textContent = 'Add New Project';
            document.getElementById('project-form').reset();
            document.getElementById('project-id').value = '';
            document.getElementById('project-modal').showModal();
        }

        // Edit project
        async function editProject(id) {
            try {
                showLoading();
                const response = await fetch(`${API_BASE_URL}/projects/${id}`);
                
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const contentType = response.headers.get("content-type");
                if (!contentType || !contentType.includes("application/json")) {
                    throw new Error("Response is not JSON");
                }
                
                const data = await response.json();

                if (data.success) {
                    const project = data.data;
                    editingId = id;
                    
                    document.getElementById('modal-title').textContent = 'Edit Project';
                    document.getElementById('project-id').value = project.id;
                    document.getElementById('project-form').name.value = project.name;
                    document.getElementById('project-form').status.value = project.status;
                    document.getElementById('project-form').branch_from.value = project.branch_from;
                    document.getElementById('project-form').branch_to.value = project.branch_to;
                    document.getElementById('project-form').start_date.value = project.start_date;
                    document.getElementById('project-form').end_date.value = project.end_date;
                    document.getElementById('project-form').progress_percent.value = project.progress_percent;
                    document.getElementById('project-form').description.value = project.description || '';
                    
                    document.getElementById('project-modal').showModal();
                } else {
                    showToast('Error loading project: ' + (data.message || 'Unknown error'), 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error loading project. Please check if the PLT service is running.', 'error');
            } finally {
                hideLoading();
            }
        }

        // Handle form submission
        async function handleFormSubmit(e) {
            e.preventDefault();
            await saveProject();
        }

        // Save project
        async function saveProject() {
            const formData = new FormData(document.getElementById('project-form'));
            const data = Object.fromEntries(formData);
            const url = editingId ? `${API_BASE_URL}/projects/${editingId}` : `${API_BASE_URL}/projects`;
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

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const contentType = response.headers.get("content-type");
                if (!contentType || !contentType.includes("application/json")) {
                    throw new Error("Response is not JSON");
                }

                const result = await response.json();

                if (result.success) {
                    showToast(`Project ${editingId ? 'updated' : 'created'} successfully`, 'success');
                    closeModal();
                    loadProjects();
                    loadStats();
                } else {
                    showToast(result.message || 'Error saving project', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error saving project. Please check if the PLT service is running.', 'error');
            } finally {
                hideLoading();
            }
        }

        // Confirm delete
        function confirmDelete(id) {
            deleteId = id;
            document.getElementById('delete-modal').showModal();
        }

        // Delete project
        async function deleteProject() {
            try {
                showLoading();
                const response = await fetch(`${API_BASE_URL}/projects/${deleteId}`, {
                    method: 'DELETE'
                });

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                const contentType = response.headers.get("content-type");
                if (!contentType || !contentType.includes("application/json")) {
                    throw new Error("Response is not JSON");
                }

                const result = await response.json();

                if (result.success) {
                    showToast('Project deleted successfully', 'success');
                    closeDeleteModal();
                    loadProjects();
                    loadStats();
                } else {
                    showToast(result.message || 'Error deleting project', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error deleting project. Please check if the PLT service is running.', 'error');
            } finally {
                hideLoading();
            }
        }

        // Close modals
        function closeModal() {
            document.getElementById('project-modal').close();
        }

        function closeDeleteModal() {
            document.getElementById('delete-modal').close();
            deleteId = null;
        }

        // Utility functions
        function getStatusBadge(status) {
            const statusMap = {
                'planned': 'badge-info',
                'in_progress': 'badge-primary',
                'delayed': 'badge-warning',
                'completed': 'badge-success',
                'cancelled': 'badge-error'
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
            toast.className = 'toast toast-top toast-end';
            
            let alertClass = 'alert-info';
            if (type === 'success') alertClass = 'alert-success';
            if (type === 'error') alertClass = 'alert-error';
            if (type === 'warning') alertClass = 'alert-warning';
            
            toast.innerHTML = '<div class="alert ' + alertClass + '"><span>' + message + '</span></div>';
            
            document.body.appendChild(toast);
            
            setTimeout(() => {
                toast.remove();
            }, 3000);
        }
    </script>
@endsection