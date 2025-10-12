@extends('layouts.app')

@section('title', 'PLT Dispatches')

@section('content')
    <div class="module-content bg-white rounded-xl p-6 shadow block">
        <h2 class="text-2xl font-bold mb-4">Dispatch Tracking</h2>
        
        <!-- Stats Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
            <div class="stats">
                <div class="stat shadow-lg border-l-4 border-primary">
                    <div class="stat-figure text-primary">
                        <i class="bx bxs-truck text-3xl"></i>
                    </div>
                    <div class="stat-title">Total Dispatches</div>
                    <div class="stat-value text-primary" id="total-dispatches">0</div>
                </div>
            </div>
            
            <div class="stats">
                <div class="stat shadow-lg border-l-4 border-blue-400">
                    <div class="stat-figure text-info">
                        <i class="bx bxs-package text-3xl"></i>
                    </div>
                    <div class="stat-title">In Transit</div>
                    <div class="stat-value text-info" id="in-transit">0</div>
                </div>
            </div>
            
            <div class="stats">
                <div class="stat shadow-lg border-l-4 border-success">
                    <div class="stat-figure text-success">
                        <i class="bx bxs-check-circle text-3xl"></i>
                    </div>
                    <div class="stat-title">Delivered</div>
                    <div class="stat-value text-success" id="delivered">0</div>
                </div>
            </div>
            
            <div class="stats">
                <div class="stat shadow-lg border-l-4 border-warning">
                    <div class="stat-figure text-warning">
                        <i class="bx bxs-time text-3xl"></i>
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
                        <input type="text" placeholder="Search dispatches..." class="input input-bordered w-full" id="search-input" />
                    </div>
                </div>
            </div>
            <div class="flex gap-2">
                <select class="select select-bordered" id="status-filter" onchange="loadDispatches()">
                    <option value="">All Status</option>
                    <option value="dispatched">Dispatched</option>
                    <option value="in_transit">In Transit</option>
                    <option value="delayed">Delayed</option>
                    <option value="delivered">Delivered</option>
                    <option value="failed">Failed</option>
                </select>
                <select class="select select-bordered" id="material-type-filter" onchange="loadDispatches()">
                    <option value="">All Types</option>
                    <option value="loan_kits">Loan Kits</option>
                    <option value="promotional_banners">Promotional Banners</option>
                    <option value="delivery_van">Delivery Van</option>
                    <option value="passbooks">Passbooks</option>
                    <option value="forms">Forms</option>
                </select>
                <button class="btn btn-primary" onclick="openAddModal()">
                    <i class="bx bx-plus mr-2"></i> Add Dispatch
                </button>
            </div>
        </div>

        <!-- Dispatches Table -->
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr>
                        <th>Dispatch ID</th>
                        <th>Project</th>
                        <th>Material Type</th>
                        <th>Quantity</th>
                        <th>From Location</th>
                        <th>To Location</th>
                        <th>Expected Delivery</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="dispatches-table-body">
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

    <!-- Add/Edit Dispatch Modal -->
    <dialog id="dispatch-modal" class="modal">
        <div class="modal-box w-11/12 max-w-3xl">
            <h3 class="font-bold text-lg" id="modal-title">Add New Dispatch</h3>
            <form id="dispatch-form" class="mt-4">
                <input type="hidden" name="id" id="dispatch-id">
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
                            <span class="label-text">Material Type</span>
                        </label>
                        <select name="material_type" class="select select-bordered" required>
                            <option value="loan_kits">Loan Kits</option>
                            <option value="promotional_banners">Promotional Banners</option>
                            <option value="delivery_van">Delivery Van</option>
                            <option value="passbooks">Passbooks</option>
                            <option value="forms">Forms</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Quantity</span>
                        </label>
                        <input type="number" name="quantity" class="input input-bordered" min="1" required />
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Status</span>
                        </label>
                        <select name="status" class="select select-bordered" required>
                            <option value="dispatched">Dispatched</option>
                            <option value="in_transit">In Transit</option>
                            <option value="delayed">Delayed</option>
                            <option value="delivered">Delivered</option>
                            <option value="failed">Failed</option>
                        </select>
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">From Location</span>
                        </label>
                        <input type="text" name="from_location" class="input input-bordered" required />
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">To Location</span>
                        </label>
                        <input type="text" name="to_location" class="input input-bordered" required />
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Dispatch Date</span>
                        </label>
                        <input type="date" name="dispatch_date" class="input input-bordered" required />
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Expected Delivery</span>
                        </label>
                        <input type="date" name="expected_delivery_date" class="input input-bordered" required />
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Actual Delivery</span>
                        </label>
                        <input type="date" name="actual_delivery_date" class="input input-bordered" />
                    </div>
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text">Receipt Reference</span>
                        </label>
                        <input type="text" name="receipt_reference" class="input input-bordered" />
                    </div>
                </div>
                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" onclick="closeModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary" id="submit-btn">Save Dispatch</button>
                </div>
            </form>
        </div>
    </dialog>

    <!-- Delete Confirmation Modal -->
    <dialog id="delete-modal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Confirm Delete</h3>
            <p class="py-4">Are you sure you want to delete this dispatch? This action cannot be undone.</p>
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

        // Load dispatches on page load
        document.addEventListener('DOMContentLoaded', function() {
            loadDispatches();
            loadStats();
            loadProjectsForSelect();
        });

        // Load dispatches with pagination and filters
        async function loadDispatches(page = 1) {
            currentPage = page;
            const search = document.getElementById('search-input').value;
            const status = document.getElementById('status-filter').value;
            const materialType = document.getElementById('material-type-filter').value;

            try {
                showLoading();
                const response = await fetch(`/api/plt/dispatches?page=${page}&search=${search}&status=${status}&material_type=${materialType}`);
                const data = await response.json();

                if (data.success) {
                    renderDispatchesTable(data.data.data);
                    renderPagination(data.data);
                } else {
                    showToast('Error loading dispatches', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error loading dispatches', 'error');
            } finally {
                hideLoading();
            }
        }

        // Load stats
        async function loadStats() {
            try {
                const response = await fetch('/api/plt/dispatches/stats');
                const data = await response.json();

                if (data.success) {
                    document.getElementById('total-dispatches').textContent = data.data.total_dispatches;
                    document.getElementById('in-transit').textContent = data.data.in_transit;
                    document.getElementById('delivered').textContent = data.data.delivered;
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

        // Render dispatches table
        function renderDispatchesTable(dispatches) {
            const tbody = document.getElementById('dispatches-table-body');
            tbody.innerHTML = '';

            if (dispatches.length === 0) {
                tbody.innerHTML = '<tr><td colspan="9" class="text-center py-4">No dispatches found</td></tr>';
                return;
            }

            dispatches.forEach(dispatch => {
                const statusBadge = getStatusBadge(dispatch.status);
                const materialType = dispatch.material_type.replace('_', ' ');

                const row = `
                    <tr>
                        <td class="font-mono">DSP${String(dispatch.id).padStart(5, '0')}</td>
                        <td>${dispatch.project?.name || 'N/A'}</td>
                        <td>${materialType}</td>
                        <td>${dispatch.quantity}</td>
                        <td>${dispatch.from_location}</td>
                        <td>${dispatch.to_location}</td>
                        <td>${formatDate(dispatch.expected_delivery_date)}</td>
                        <td>${statusBadge}</td>
                        <td>
                            <div class="flex gap-1">
                                <button class="btn btn-sm btn-circle btn-info" title="Edit" onclick="editDispatch(${dispatch.id})">
                                    <i class="bx bx-edit"></i>
                                </button>
                                <button class="btn btn-sm btn-circle btn-error" title="Delete" onclick="confirmDelete(${dispatch.id})">
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
                    <button class="join-item btn" onclick="loadDispatches(${currentPage - 1})">«</button>
                `;
            }

            // Page numbers
            for (let i = 1; i <= totalPages; i++) {
                const activeClass = i === currentPage ? 'btn-active' : '';
                paginationDiv.innerHTML += `
                    <button class="join-item btn ${activeClass}" onclick="loadDispatches(${i})">${i}</button>
                `;
            }

            // Next button
            if (currentPage < totalPages) {
                paginationDiv.innerHTML += `
                    <button class="join-item btn" onclick="loadDispatches(${currentPage + 1})">»</button>
                `;
            }
        }

        // Open add modal
        function openAddModal() {
            editingId = null;
            document.getElementById('modal-title').textContent = 'Add New Dispatch';
            document.getElementById('dispatch-form').reset();
            document.getElementById('dispatch-id').value = '';
            document.getElementById('dispatch-modal').showModal();
        }

        // Edit dispatch
        async function editDispatch(id) {
            try {
                showLoading();
                const response = await fetch(`/api/plt/dispatches/${id}`);
                const data = await response.json();

                if (data.success) {
                    const dispatch = data.data;
                    editingId = id;
                    
                    document.getElementById('modal-title').textContent = 'Edit Dispatch';
                    document.getElementById('dispatch-id').value = dispatch.id;
                    document.getElementById('dispatch-form').project_id.value = dispatch.project_id;
                    document.getElementById('dispatch-form').material_type.value = dispatch.material_type;
                    document.getElementById('dispatch-form').quantity.value = dispatch.quantity;
                    document.getElementById('dispatch-form').from_location.value = dispatch.from_location;
                    document.getElementById('dispatch-form').to_location.value = dispatch.to_location;
                    document.getElementById('dispatch-form').dispatch_date.value = dispatch.dispatch_date;
                    document.getElementById('dispatch-form').expected_delivery_date.value = dispatch.expected_delivery_date;
                    document.getElementById('dispatch-form').actual_delivery_date.value = dispatch.actual_delivery_date || '';
                    document.getElementById('dispatch-form').status.value = dispatch.status;
                    document.getElementById('dispatch-form').receipt_reference.value = dispatch.receipt_reference || '';
                    
                    document.getElementById('dispatch-modal').showModal();
                } else {
                    showToast('Error loading dispatch', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error loading dispatch', 'error');
            } finally {
                hideLoading();
            }
        }

        // Handle form submission
        document.getElementById('dispatch-form').addEventListener('submit', async function(e) {
            e.preventDefault();
            await saveDispatch();
        });

        // Save dispatch
        async function saveDispatch() {
            const formData = new FormData(document.getElementById('dispatch-form'));
            const data = Object.fromEntries(formData);
            const url = editingId ? `/api/plt/dispatches/${editingId}` : '/api/plt/dispatches';
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
                    showToast(`Dispatch ${editingId ? 'updated' : 'created'} successfully`, 'success');
                    closeModal();
                    loadDispatches();
                    loadStats();
                } else {
                    showToast(result.message || 'Error saving dispatch', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error saving dispatch', 'error');
            } finally {
                hideLoading();
            }
        }

        // Confirm delete
        function confirmDelete(id) {
            deleteId = id;
            document.getElementById('delete-modal').showModal();
        }

        // Delete dispatch
        document.getElementById('confirm-delete-btn').addEventListener('click', async function() {
            try {
                showLoading();
                const response = await fetch(`/api/plt/dispatches/${deleteId}`, {
                    method: 'DELETE'
                });

                const result = await response.json();

                if (result.success) {
                    showToast('Dispatch deleted successfully', 'success');
                    closeDeleteModal();
                    loadDispatches();
                    loadStats();
                } else {
                    showToast(result.message || 'Error deleting dispatch', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showToast('Error deleting dispatch', 'error');
            } finally {
                hideLoading();
            }
        });

        // Close modals
        function closeModal() {
            document.getElementById('dispatch-modal').close();
        }

        function closeDeleteModal() {
            document.getElementById('delete-modal').close();
            deleteId = null;
        }

        // Utility functions
        function getStatusBadge(status) {
            const statusMap = {
                'dispatched': 'badge-info',
                'in_transit': 'badge-primary',
                'delayed': 'badge-warning',
                'delivered': 'badge-success',
                'failed': 'badge-error'
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