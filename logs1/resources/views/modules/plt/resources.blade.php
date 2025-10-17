@extends('layouts.app')

@section('title', 'PLT Resources')

@section('content')
    <div class="module-content bg-white rounded-xl p-6 shadow block">
        <h2 class="text-2xl font-bold mb-4">Resource Management</h2>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="stats">
                <div class="stat shadow-lg border-l-4 border-primary">
                    <div class="stat-figure text-primary">
                        <i class="bx bxs-cube text-3xl"></i>
                    </div>
                    <div class="stat-title">Total Resources</div>
                    <div class="stat-value text-primary" id="total-resources">0</div>
                </div>
            </div>
            
            <div class="stats">
                <div class="stat shadow-lg border-l-4 border-blue-400">
                    <div class="stat-figure text-info">
                        <i class="bx bxs-cog text-3xl"></i>
                    </div>
                    <div class="stat-title">Assets</div>
                    <div class="stat-value text-info" id="assets">0</div>
                </div>
            </div>
            
            <div class="stats">
                <div class="stat shadow-lg border-l-4 border-success">
                    <div class="stat-figure text-success">
                        <i class="bx bxs-package text-3xl"></i>
                    </div>
                    <div class="stat-title">Supplies</div>
                    <div class="stat-value text-success" id="supplies">0</div>
                </div>
            </div>
            
            <div class="stats">
                <div class="stat shadow-lg border-l-4 border-warning">
                    <div class="stat-figure text-warning">
                        <i class="bx bxs-user text-3xl"></i>
                    </div>
                    <div class="stat-title">Personnel</div>
                    <div class="stat-value text-warning" id="personnel">0</div>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <div class="flex-1">
                <div class="form-control">
                    <div class="input-group">
                        <input type="text" placeholder="Search resources..." class="input input-bordered w-full" id="search-input" />
                    </div>
                </div>
            </div>
            <div class="flex gap-2">
                <select class="select select-bordered" id="type-filter" onchange="loadResources()">
                    <option value="">All Types</option>
                    <option value="asset">Asset</option>
                    <option value="supply">Supply</option>
                    <option value="personnel">Personnel</option>
                </select>
                <button class="btn btn-primary" onclick="openAddModal()">
                    <i class="bx bx-plus mr-2"></i> Add Resource
                </button>
            </div>
        </div>

        <!-- Resources Table -->
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th>Resource ID</th>
                        <th>Resource Name</th>
                        <th>Type</th>
                        <th>Quantity Available</th>
                        <th>Allocations</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="resources-table-body">
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

    <!-- Add/Edit Resource Modal -->
    <dialog id="resource-modal" class="modal">
        <div class="modal-box w-11/12 max-w-2xl">
            <h3 class="font-bold text-lg" id="modal-title">Add New Resource</h3>
            <form id="resource-form" class="mt-4">
                <input type="hidden" name="id" id="resource-id">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Resource Name</span>
                        </label>
                        <input type="text" name="name" class="input input-bordered" required />
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Type</span>
                        </label>
                        <select name="type" class="select select-bordered" required>
                            <option value="asset">Asset</option>
                            <option value="supply">Supply</option>
                            <option value="personnel">Personnel</option>
                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Quantity Available</span>
                        </label>
                        <input type="number" name="quantity_available" class="input input-bordered" min="0" required />
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Location</span>
                        </label>
                        <input type="text" name="location" class="input input-bordered" />
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
                    <button type="submit" class="btn btn-primary" id="submit-btn">Save Resource</button>
                </div>
            </form>
        </div>
    </dialog>

    <!-- Delete Confirmation Modal -->
    <dialog id="delete-modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Confirm Delete</h3>
            <p class="py-4">Are you sure you want to delete this resource? This action cannot be undone.</p>
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
        let typeFilter = '';

        // ==================== DOM ELEMENTS ====================
        const resourcesTableBody = document.getElementById('resources-table-body');
        const pagination = document.getElementById('pagination');
        const searchInput = document.getElementById('search-input');
        const typeFilterSelect = document.getElementById('type-filter');
        const resourceModal = document.getElementById('resource-modal');
        const resourceForm = document.getElementById('resource-form');
        const modalTitle = document.getElementById('modal-title');
        const resourceIdInput = document.getElementById('resource-id');
        const deleteModal = document.getElementById('delete-modal');
        const confirmDeleteBtn = document.getElementById('confirm-delete-btn');

        // ==================== EVENT LISTENERS ====================
        document.addEventListener('DOMContentLoaded', function() {
            loadResources();
            setupEventListeners();
        });

        function setupEventListeners() {
            searchInput.addEventListener('input', debounce(() => {
                searchTerm = searchInput.value;
                currentPage = 1;
                loadResources();
            }, 500));

            typeFilterSelect.addEventListener('change', () => {
                typeFilter = typeFilterSelect.value;
                currentPage = 1;
                loadResources();
            });

            resourceForm.addEventListener('submit', handleResourceSubmit);
        }

        // ==================== API FUNCTIONS ====================
        async function loadResources() {
            try {
                showLoading();
                const params = new URLSearchParams({
                    page: currentPage,
                    search: searchTerm,
                    type: typeFilter
                });

                const response = await fetch(`${API_BASE_URL}/resources?${params}`);
                const result = await response.json();

                if (result.success) {
                    displayResources(result.data);
                    updateStats();
                } else {
                    throw new Error(result.message || 'Failed to load resources');
                }
            } catch (error) {
                console.error('Error loading resources:', error);
                showError('Failed to load resources: ' + error.message);
            } finally {
                hideLoading();
            }
        }

        async function updateStats() {
            try {
                const response = await fetch(`${API_BASE_URL}/resources/stats`);
                const result = await response.json();

                if (result.success) {
                    const stats = result.data;
                    document.getElementById('total-resources').textContent = stats.total_resources;
                    document.getElementById('assets').textContent = stats.assets;
                    document.getElementById('supplies').textContent = stats.supplies;
                    document.getElementById('personnel').textContent = stats.personnel;
                }
            } catch (error) {
                console.error('Error loading stats:', error);
            }
        }

        async function createResource(resourceData) {
            const response = await fetch(`${API_BASE_URL}/resources`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(resourceData)
            });
            return await response.json();
        }

        async function updateResource(id, resourceData) {
            const response = await fetch(`${API_BASE_URL}/resources/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(resourceData)
            });
            return await response.json();
        }

        async function deleteResource(id) {
            const response = await fetch(`${API_BASE_URL}/resources/${id}`, {
                method: 'DELETE'
            });
            return await response.json();
        }

        // ==================== UI FUNCTIONS ====================
        function displayResources(resourcesData) {
            const resources = resourcesData.data;
            totalPages = resourcesData.last_page;

            resourcesTableBody.innerHTML = '';

            if (resources.length === 0) {
                resourcesTableBody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500">
                            <i class="bx bx-cube text-4xl mb-2"></i>
                            <p>No resources found</p>
                        </td>
                    </tr>
                `;
                return;
            }

            resources.forEach(resource => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="font-mono">RES${String(resource.id).padStart(5, '0')}</td>
                    <td class="font-semibold">${escapeHtml(resource.name)}</td>
                    <td>
                        <span class="badge ${getTypeBadgeClass(resource.type)}">
                            ${getTypeText(resource.type)}
                        </span>
                    </td>
                    <td>
                        <div class="flex items-center gap-2">
                            <span class="font-semibold">${resource.quantity_available}</span>
                            <span class="text-sm text-gray-500">Unit${resource.quantity_available !== 1 ? 's' : ''}</span>
                            ${resource.quantity_available < 10 ? '<span class="badge badge-warning badge-xs">Low</span>' : ''}
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-outline">
                            ${resource.allocations_count || 0} allocation${resource.allocations_count !== 1 ? 's' : ''}
                        </span>
                    </td>
                    <td>
                        <div class="flex gap-1">
                            <button class="btn btn-sm btn-circle btn-outline btn-info" onclick="viewResource(${resource.id})">
                                <i class="bx bx-show"></i>
                            </button>
                            <button class="btn btn-sm btn-circle btn-outline btn-warning" onclick="editResource(${resource.id})">
                                <i class="bx bx-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-circle btn-outline btn-error" onclick="openDeleteModal(${resource.id})">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                resourcesTableBody.appendChild(row);
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
                    loadResources();
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
                    loadResources();
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
                    loadResources();
                }
            };
            pagination.appendChild(nextButton);
        }

        // ==================== MODAL FUNCTIONS ====================
        function openAddModal() {
            modalTitle.textContent = 'Add New Resource';
            resourceForm.reset();
            resourceIdInput.value = '';
            resourceModal.showModal();
        }

        async function viewResource(id) {
            try {
                showLoading();
                const response = await fetch(`${API_BASE_URL}/resources/${id}`);
                const result = await response.json();

                if (result.success) {
                    const resource = result.data;
                    
                    // Create and show view modal
                    const viewModal = document.createElement('dialog');
                    viewModal.className = 'modal modal-middle';
                    viewModal.innerHTML = `
                        <div class="modal-box max-w-2xl">
                            <h3 class="font-bold text-lg mb-4">Resource Details - RES${String(resource.id).padStart(5, '0')}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Resource Name</span>
                                    </label>
                                    <div class="p-2 bg-base-200 rounded">${escapeHtml(resource.name)}</div>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Type</span>
                                    </label>
                                    <div class="p-2">
                                        <span class="badge ${getTypeBadgeClass(resource.type)}">
                                            ${getTypeText(resource.type)}
                                        </span>
                                    </div>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Unit Available</span>
                                    </label>
                                    <div class="p-2 bg-base-200 rounded">
                                        <span class="font-semibold">${resource.quantity_available}</span>
                                        <span class="text-sm text-gray-500 ml-2">Unit${resource.quantity_available !== 1 ? 's' : ''}</span>
                                        ${resource.quantity_available < 10 ? '<span class="badge badge-warning ml-2">Low Stock</span>' : ''}
                                    </div>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Location</span>
                                    </label>
                                    <div class="p-2 bg-base-200 rounded">${resource.location || 'Not specified'}</div>
                                </div>
                                <div class="form-control md:col-span-2">
                                    <label class="label">
                                        <span class="label-text font-semibold">Description</span>
                                    </label>
                                    <div class="p-3 bg-base-200 rounded min-h-20">${resource.description ? escapeHtml(resource.description) : 'No description provided'}</div>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Active Allocations</span>
                                    </label>
                                    <div class="p-2 bg-base-200 rounded text-center">
                                        <span class="text-2xl font-bold text-primary">${resource.allocations_count || 0}</span>
                                        <div class="text-sm text-gray-500">allocation${resource.allocations_count !== 1 ? 's' : ''}</div>
                                    </div>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Created Date</span>
                                    </label>
                                    <div class="p-2 bg-base-200 rounded">${formatDate(resource.created_at)}</div>
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
                    throw new Error(result.message || 'Failed to load resource');
                }
            } catch (error) {
                console.error('Error loading resource:', error);
                showError('Failed to load resource: ' + error.message);
            } finally {
                hideLoading();
            }
        }

        async function editResource(id) {
            try {
                showLoading();
                const response = await fetch(`${API_BASE_URL}/resources/${id}`);
                const result = await response.json();

                if (result.success) {
                    const resource = result.data;
                    modalTitle.textContent = 'Edit Resource';
                    resourceIdInput.value = resource.id;
                    
                    // Fill form with resource data
                    Object.keys(resource).forEach(key => {
                        const input = resourceForm.querySelector(`[name="${key}"]`);
                        if (input) {
                            input.value = resource[key] || '';
                        }
                    });

                    resourceModal.showModal();
                } else {
                    throw new Error(result.message || 'Failed to load resource');
                }
            } catch (error) {
                console.error('Error loading resource:', error);
                showError('Failed to load resource: ' + error.message);
            } finally {
                hideLoading();
            }
        }

        function closeModal() {
            resourceModal.close();
        }

        let resourceToDelete = null;

        function openDeleteModal(id) {
            resourceToDelete = id;
            deleteModal.showModal();
        }

        function closeDeleteModal() {
            resourceToDelete = null;
            deleteModal.close();
        }

        async function confirmDelete() {
            if (!resourceToDelete) return;

            try {
                showLoading();
                const result = await deleteResource(resourceToDelete);

                if (result.success) {
                    showSuccess('Resource deleted successfully');
                    closeDeleteModal();
                    loadResources();
                } else {
                    throw new Error(result.message || 'Failed to delete resource');
                }
            } catch (error) {
                console.error('Error deleting resource:', error);
                showError('Failed to delete resource: ' + error.message);
            } finally {
                hideLoading();
            }
        }

        // ==================== FORM HANDLING ====================
        async function handleResourceSubmit(e) {
            e.preventDefault();
            
            const formData = new FormData(resourceForm);
            const resourceData = Object.fromEntries(formData);
            
            // Convert quantity_available to number
            resourceData.quantity_available = parseInt(resourceData.quantity_available);

            try {
                showLoading();
                let result;

                if (resourceIdInput.value) {
                    result = await updateResource(resourceIdInput.value, resourceData);
                } else {
                    result = await createResource(resourceData);
                }

                if (result.success) {
                    showSuccess(`Resource ${resourceIdInput.value ? 'updated' : 'created'} successfully`);
                    closeModal();
                    loadResources();
                } else {
                    throw new Error(result.message || `Failed to ${resourceIdInput.value ? 'update' : 'create'} resource`);
                }
            } catch (error) {
                console.error('Error saving resource:', error);
                showError('Failed to save resource: ' + error.message);
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

        function getTypeBadgeClass(type) {
            const typeClasses = {
                'asset': 'badge-primary',
                'supply': 'badge-success',
                'personnel': 'badge-warning'
            };
            return typeClasses[type] || 'badge-info';
        }

        function getTypeText(type) {
            const typeTexts = {
                'asset': 'Asset',
                'supply': 'Supply',
                'personnel': 'Personnel'
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