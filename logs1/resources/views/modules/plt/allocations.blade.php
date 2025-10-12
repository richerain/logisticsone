@extends('layouts.app')

@section('title', 'PLT Allocations')

@section('content')
    <div class="module-content bg-white rounded-xl p-6 shadow block">
        <h2 class="text-2xl font-bold mb-4">Resource Allocation</h2>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="stats">
                <div class="stat shadow-lg border-l-4 border-primary">
                    <div class="stat-figure text-primary">
                        <i class="bx bxs-user-check text-3xl"></i>
                    </div>
                    <div class="stat-title">Total Allocations</div>
                    <div class="stat-value text-primary" id="total-allocations">0</div>
                </div>
            </div>
            
            <div class="stats">
                <div class="stat shadow-lg border-l-4 border-blue-400">
                    <div class="stat-figure text-info">
                        <i class="bx bxs-user-plus text-3xl"></i>
                    </div>
                    <div class="stat-title">Assigned</div>
                    <div class="stat-value text-info" id="assigned">0</div>
                </div>
            </div>
            
            <div class="stats">
                <div class="stat shadow-lg border-l-4 border-success">
                    <div class="stat-figure text-success">
                        <i class="bx bxs-check-circle text-3xl"></i>
                    </div>
                    <div class="stat-title">In Use</div>
                    <div class="stat-value text-success" id="in-use">0</div>
                </div>
            </div>
            
            <div class="stats">
                <div class="stat shadow-lg border-l-4 border-warning">
                    <div class="stat-figure text-warning">
                        <i class="bx bxs-archive text-3xl"></i>
                    </div>
                    <div class="stat-title">Returned</div>
                    <div class="stat-value text-warning" id="returned">0</div>
                </div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="flex flex-col md:flex-row gap-4 mb-6">
            <div class="flex-1">
                <div class="form-control">
                    <div class="input-group">
                        <input type="text" placeholder="Search allocations..." class="input input-bordered w-full" id="search-input" />
                    </div>
                </div>
            </div>
            <div class="flex gap-2">
                <select class="select select-bordered" id="status-filter" onchange="loadAllocations()">
                    <option value="">All Status</option>
                    <option value="assigned">Assigned</option>
                    <option value="in_use">In Use</option>
                    <option value="returned">Returned</option>
                    <option value="issue">Issue</option>
                </select>
                <button class="btn btn-primary" onclick="openAddModal()">
                    <i class="bx bx-plus mr-2"></i> Add Allocation
                </button>
            </div>
        </div>

        <!-- Allocations Table -->
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th>Allocation ID</th>
                        <th>Project</th>
                        <th>Resource</th>
                        <th>Type</th>
                        <th>Quantity</th>
                        <th>Assigned Date</th>
                        <th>Return Date</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="allocations-table-body">
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

    <!-- Add/Edit Allocation Modal -->
    <dialog id="allocation-modal" class="modal">
        <div class="modal-box w-11/12 max-w-3xl">
            <h3 class="font-bold text-lg" id="modal-title">Add New Allocation</h3>
            <form id="allocation-form" class="mt-4">
                <input type="hidden" name="id" id="allocation-id">
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
                            <span class="label-text">Resource</span>
                        </label>
                        <select name="resource_id" class="select select-bordered" required id="resource-select">
                            <option value="">Select Resource</option>
                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Quantity Assigned</span>
                        </label>
                        <input type="number" name="quantity_assigned" class="input input-bordered" min="1" required id="quantity-input" />
                        <label class="label">
                            <span class="label-text-alt" id="quantity-available">Available: 0</span>
                        </label>
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Status</span>
                        </label>
                        <select name="status" class="select select-bordered" required>
                            <option value="assigned">Assigned</option>
                            <option value="in_use">In Use</option>
                            <option value="returned">Returned</option>
                            <option value="issue">Issue</option>
                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Assigned Date</span>
                        </label>
                        <input type="date" name="assigned_date" class="input input-bordered" required />
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Return Date</span>
                        </label>
                        <input type="date" name="return_date" class="input input-bordered" />
                    </div>
                </div>
                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submit-btn">Save Allocation</button>
                </div>
            </form>
        </div>
    </dialog>

    <!-- Delete Confirmation Modal -->
    <dialog id="delete-modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Confirm Delete</h3>
            <p class="py-4">Are you sure you want to delete this allocation? This action cannot be undone.</p>
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

        // Load allocations on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadAllocations();
            loadStats();
            loadProjectsForSelect();
            loadResourcesForSelect();
        });

        // Load allocations with pagination and filters
        async function loadAllocations(page = 1) {
            currentPage = page;
            const search = document.getElementById('search-input').value;
            const status = document.getElementById('status-filter').value;

            try {
                showLoading();
                const response = await fetch(`/api/plt/allocations?page=${page}&search=${search}&status=${status}`);
                const data = await response.json();

                if (data.success) {
                    renderAllocationsTable(data.data.data);
                    renderPagination(data.data);
                } else {
                    showToast('Error loading allocations', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error loading allocations', 'error');
            } finally {
                hideLoading();
            }
        }

        // Load stats
        async function loadStats() {
            try {
                const response = await fetch('/api/plt/allocations/stats');
                const data = await response.json();

                if (data.success) {
                    document.getElementById('total-allocations').textContent = data.data.total_allocations;
                    document.getElementById('assigned').textContent = data.data.assigned;
                    document.getElementById('in-use').textContent = data.data.in_use;
                    document.getElementById('returned').textContent = data.data.returned;
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

        // Load resources for select dropdown
        async function loadResourcesForSelect() {
            try {
                const response = await fetch('/api/plt/resources?per_page=100');
                const data = await response.json();

                if (data.success) {
                    const select = document.getElementById('resource-select');
                    select.innerHTML = '<option value="">Select Resource</option>';
                    
                    data.data.data.forEach(resource => {
                        const option = document.createElement('option');
                        option.value = resource.id;
                        option.textContent = `${resource.name} (${resource.quantity_available} available)`;
                        option.dataset.quantity = resource.quantity_available;
                        select.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Error loading resources:', error);
            }
        }

        // Update quantity available when resource is selected
        document.getElementById('resource-select').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const availableQuantity = selectedOption.dataset.quantity || 0;
            document.getElementById('quantity-available').textContent = `Available: ${availableQuantity}`;
            document.getElementById('quantity-input').max = availableQuantity;
        });

        // Render allocations table
        function renderAllocationsTable(allocations) {
            const tbody = document.getElementById('allocations-table-body');
            tbody.innerHTML = '';

            if (allocations.length === 0) {
                tbody.innerHTML = '<tr><td colspan="9" class="text-center py-4">No allocations found</td></tr>';
                return;
            }

            allocations.forEach(allocation => {
                const statusBadge = getStatusBadge(allocation.status);
                const resourceType = allocation.resource?.type || 'N/A';

                const row = `
                    <tr>
                        <td class="font-mono">ALC${String(allocation.id).padStart(5, '0')}</td>
                        <td>${allocation.project?.name || 'N/A'}</td>
                        <td>${allocation.resource?.name || 'N/A'}</td>
                        <td>${resourceType}</td>
                        <td>${allocation.quantity_assigned}</td>
                        <td>${formatDate(allocation.assigned_date)}</td>
                        <td>${allocation.return_date ? formatDate(allocation.return_date) : 'Not returned'}</td>
                        <td>${statusBadge}</td>
                        <td>
                            <div class="flex gap-1">
                                <button class="btn btn-sm btn-circle btn-info" title="Edit" onclick="editAllocation(${allocation.id})">
                                    <i class="bx bx-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-circle btn-error" title="Delete" onclick="confirmDelete(${allocation.id})">
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
                    <button class="join-item btn" onclick="loadAllocations(${currentPage - 1})">«</button>
                `;
            }

            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                const activeClass = i === currentPage ? 'btn-active' : '';
                paginationDiv.innerHTML += `
                    <button class="join-item btn ${activeClass}" onclick="loadAllocations(${i})">${i}</button>
                `;
            }

            // Next button
            if (currentPage < totalPages) {
                paginationDiv.innerHTML += `
                    <button class="join-item btn" onclick="loadAllocations(${currentPage + 1})">»</button>
                `;
            }
        }

        // Open add modal
        function openAddModal() {
            editingId = null;
            document.getElementById('modal-title').textContent = 'Add New Allocation';
            document.getElementById('allocation-form').reset();
            document.getElementById('allocation-id').value = '';
            document.getElementById('quantity-available').textContent = 'Available: 0';
            document.getElementById('allocation-modal').showModal();
        }

        // Edit allocation
        async function editAllocation(id) {
            try {
                showLoading();
                const response = await fetch(`/api/plt/allocations/${id}`);
                const data = await response.json();

                if (data.success) {
                    const allocation = data.data;
                    editingId = id;
                    
                    document.getElementById('modal-title').textContent = 'Edit Allocation';
                    document.getElementById('allocation-id').value = allocation.id;
                    document.getElementById('allocation-form').project_id.value = allocation.project_id;
                    document.getElementById('allocation-form').resource_id.value = allocation.resource_id;
                    document.getElementById('allocation-form').quantity_assigned.value = allocation.quantity_assigned;
                    document.getElementById('allocation-form').status.value = allocation.status;
                    document.getElementById('allocation-form').assigned_date.value = allocation.assigned_date;
                    document.getElementById('allocation-form').return_date.value = allocation.return_date || '';
                    
                    // Update quantity available display
                    const resourceSelect = document.getElementById('resource-select');
                    const selectedOption = resourceSelect.querySelector(`option[value="${allocation.resource_id}"]`);
                    if (selectedOption) {
                        document.getElementById('quantity-available').textContent = `Available: ${selectedOption.dataset.quantity || 0}`;
                    }
                    
                    document.getElementById('allocation-modal').showModal();
                } else {
                    showToast('Error loading allocation', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error loading allocation', 'error');
            } finally {
                hideLoading();
            }
        }

        // Handle form submission
        document.getElementById('allocation-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            await saveAllocation();
        });

        // Save allocation
        async function saveAllocation() {
            const formData = new FormData(document.getElementById('allocation-form'));
            const data = Object.fromEntries(formData);
            const url = editingId ? `/api/plt/allocations/${editingId}` : '/api/plt/allocations';
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
                    showToast(`Allocation ${editingId ? 'updated' : 'created'} successfully`, 'success');
                    closeModal();
                    loadAllocations();
                    loadStats();
                } else {
                    showToast(result.message || 'Error saving allocation', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error saving allocation', 'error');
            } finally {
                hideLoading();
            }
        }

        // Confirm delete
        function confirmDelete(id) {
            deleteId = id;
            document.getElementById('delete-modal').showModal();
        }

        // Delete allocation
        document.getElementById('confirm-delete-btn').addEventListener('click', async function() {
            try {
                showLoading();
                const response = await fetch(`/api/plt/allocations/${deleteId}`, {
                    method: 'DELETE'
                });

                const result = await response.json();

                if (result.success) {
                    showToast('Allocation deleted successfully', 'success');
                    closeDeleteModal();
                    loadAllocations();
                    loadStats();
                } else {
                    showToast(result.message || 'Error deleting allocation', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error deleting allocation', 'error');
            } finally {
                hideLoading();
            }
        });

        // Close modals
        function closeModal() {
            document.getElementById('allocation-modal').close();
        }

        function closeDeleteModal() {
            document.getElementById('delete-modal').close();
            deleteId = null;
        }

        // Utility functions
        function getStatusBadge(status) {
            const statusMap = {
                'assigned': 'badge-info',
                'in_use': 'badge-primary',
                'returned': 'badge-success',
                'issue': 'badge-error'
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