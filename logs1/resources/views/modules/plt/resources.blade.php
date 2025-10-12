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
                        <th>Location</th>
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
        let currentPage = 1;
        let editingId = null;
        let deleteId = null;

        // Load resources on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadResources();
            loadStats();
        });

        // Load resources with pagination and filters
        async function loadResources(page = 1) {
            currentPage = page;
            const search = document.getElementById('search-input').value;
            const type = document.getElementById('type-filter').value;

            try {
                showLoading();
                const response = await fetch(`/api/plt/resources?page=${page}&search=${search}&type=${type}`);
                const data = await response.json();

                if (data.success) {
                    renderResourcesTable(data.data.data);
                    renderPagination(data.data);
                } else {
                    showToast('Error loading resources', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error loading resources', 'error');
            } finally {
                hideLoading();
            }
        }

        // Load stats
        async function loadStats() {
            try {
                const response = await fetch('/api/plt/resources/stats');
                const data = await response.json();

                if (data.success) {
                    document.getElementById('total-resources').textContent = data.data.total_resources;
                    document.getElementById('assets').textContent = data.data.assets;
                    document.getElementById('supplies').textContent = data.data.supplies;
                    document.getElementById('personnel').textContent = data.data.personnel;
                }
            } catch (error) {
                console.error('Error loading stats:', error);
            }
        }

        // Render resources table
        function renderResourcesTable(resources) {
            const tbody = document.getElementById('resources-table-body');
            tbody.innerHTML = '';

            if (resources.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4">No resources found</td></tr>';
                return;
            }

            resources.forEach(resource => {
                const typeBadge = getTypeBadge(resource.type);

                const row = `
                    <tr>
                        <td class="font-mono">RES${String(resource.id).padStart(5, '0')}</td>
                        <td>${resource.name}</td>
                        <td>${typeBadge}</td>
                        <td>${resource.quantity_available}</td>
                        <td>${resource.location || 'N/A'}</td>
                        <td>${resource.allocations_count || 0}</td>
                        <td>
                            <div class="flex gap-1">
                                <button class="btn btn-sm btn-circle btn-info" title="Edit" onclick="editResource(${resource.id})">
                                    <i class="bx bx-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-circle btn-error" title="Delete" onclick="confirmDelete(${resource.id})">
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
                    <button class="join-item btn" onclick="loadResources(${currentPage - 1})">«</button>
                `;
            }

            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                const activeClass = i === currentPage ? 'btn-active' : '';
                paginationDiv.innerHTML += `
                    <button class="join-item btn ${activeClass}" onclick="loadResources(${i})">${i}</button>
                `;
            }

            // Next button
            if (currentPage < totalPages) {
                paginationDiv.innerHTML += `
                    <button class="join-item btn" onclick="loadResources(${currentPage + 1})">»</button>
                `;
            }
        }

        // Open add modal
        function openAddModal() {
            editingId = null;
            document.getElementById('modal-title').textContent = 'Add New Resource';
            document.getElementById('resource-form').reset();
            document.getElementById('resource-id').value = '';
            document.getElementById('resource-modal').showModal();
        }

        // Edit resource
        async function editResource(id) {
            try {
                showLoading();
                const response = await fetch(`/api/plt/resources/${id}`);
                const data = await response.json();

                if (data.success) {
                    const resource = data.data;
                    editingId = id;
                    
                    document.getElementById('modal-title').textContent = 'Edit Resource';
                    document.getElementById('resource-id').value = resource.id;
                    document.getElementById('resource-form').name.value = resource.name;
                    document.getElementById('resource-form').type.value = resource.type;
                    document.getElementById('resource-form').quantity_available.value = resource.quantity_available;
                    document.getElementById('resource-form').location.value = resource.location || '';
                    document.getElementById('resource-form').description.value = resource.description || '';
                    
                    document.getElementById('resource-modal').showModal();
                } else {
                    showToast('Error loading resource', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error loading resource', 'error');
            } finally {
                hideLoading();
            }
        }

        // Handle form submission
        document.getElementById('resource-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            await saveResource();
        });

        // Save resource
        async function saveResource() {
            const formData = new FormData(document.getElementById('resource-form'));
            const data = Object.fromEntries(formData);
            const url = editingId ? `/api/plt/resources/${editingId}` : '/api/plt/resources';
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
                    showToast(`Resource ${editingId ? 'updated' : 'created'} successfully`, 'success');
                    closeModal();
                    loadResources();
                    loadStats();
                } else {
                    showToast(result.message || 'Error saving resource', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error saving resource', 'error');
            } finally {
                hideLoading();
            }
        }

        // Confirm delete
        function confirmDelete(id) {
            deleteId = id;
            document.getElementById('delete-modal').showModal();
        }

        // Delete resource
        document.getElementById('confirm-delete-btn').addEventListener('click', async function() {
            try {
                showLoading();
                const response = await fetch(`/api/plt/resources/${deleteId}`, {
                    method: 'DELETE'
                });

                const result = await response.json();

                if (result.success) {
                    showToast('Resource deleted successfully', 'success');
                    closeDeleteModal();
                    loadResources();
                    loadStats();
                } else {
                    showToast(result.message || 'Error deleting resource', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error deleting resource', 'error');
            } finally {
                hideLoading();
            }
        });

        // Close modals
        function closeModal() {
            document.getElementById('resource-modal').close();
        }

        function closeDeleteModal() {
            document.getElementById('delete-modal').close();
            deleteId = null;
        }

        // Utility functions
        function getTypeBadge(type) {
            const typeMap = {
                'asset': 'badge-info',
                'supply': 'badge-success',
                'personnel': 'badge-warning'
            };
            return `<span class="badge ${typeMap[type]}">${type}</span>`;
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