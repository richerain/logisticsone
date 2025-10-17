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
                    <option value="equipment">Equipment</option>
                    <option value="document">Document</option>
                    <option value="supplies">Supplies</option>
                    <option value="furniture">Furniture</option>
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
                                <option value="equipment">Equipment</option>
                                <option value="document">Document</option>
                                <option value="supplies">Supplies</option>
                                <option value="furniture">Furniture</option>
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
        // ==================== CONFIGURATION ====================
        const API_BASE_URL = 'http://localhost:8001/api/plt';

        // ==================== STATE MANAGEMENT ====================
        let currentPage = 1;
        let totalPages = 1;
        let searchTerm = '';
        let statusFilter = '';
        let materialTypeFilter = '';

        // ==================== DOM ELEMENTS ====================
        const dispatchesTableBody = document.getElementById('dispatches-table-body');
        const pagination = document.getElementById('pagination');
        const searchInput = document.getElementById('search-input');
        const statusFilterSelect = document.getElementById('status-filter');
        const materialTypeFilterSelect = document.getElementById('material-type-filter');
        const dispatchModal = document.getElementById('dispatch-modal');
        const dispatchForm = document.getElementById('dispatch-form');
        const modalTitle = document.getElementById('modal-title');
        const dispatchIdInput = document.getElementById('dispatch-id');
        const projectSelect = document.getElementById('project-select');
        const deleteModal = document.getElementById('delete-modal');
        const confirmDeleteBtn = document.getElementById('confirm-delete-btn');

        // ==================== EVENT LISTENERS ====================
        document.addEventListener('DOMContentLoaded', function() {
            loadDispatches();
            loadProjectsForSelect();
            setupEventListeners();
        });

        function setupEventListeners() {
            searchInput.addEventListener('input', debounce(() => {
                searchTerm = searchInput.value;
                currentPage = 1;
                loadDispatches();
            }, 500));

            statusFilterSelect.addEventListener('change', () => {
                statusFilter = statusFilterSelect.value;
                currentPage = 1;
                loadDispatches();
            });

            materialTypeFilterSelect.addEventListener('change', () => {
                materialTypeFilter = materialTypeFilterSelect.value;
                currentPage = 1;
                loadDispatches();
            });

            dispatchForm.addEventListener('submit', handleDispatchSubmit);
        }

        // ==================== API FUNCTIONS ====================
        async function loadDispatches() {
            try {
                showLoading();
                const params = new URLSearchParams({
                    page: currentPage,
                    search: searchTerm,
                    status: statusFilter,
                    material_type: materialTypeFilter
                });

                const response = await fetch(`${API_BASE_URL}/dispatches?${params}`);
                const result = await response.json();

                if (result.success) {
                    displayDispatches(result.data);
                    updateStats();
                } else {
                    throw new Error(result.message || 'Failed to load dispatches');
                }
            } catch (error) {
                console.error('Error loading dispatches:', error);
                showError('Failed to load dispatches: ' + error.message);
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

        async function updateStats() {
            try {
                const response = await fetch(`${API_BASE_URL}/dispatches/stats`);
                const result = await response.json();

                if (result.success) {
                    const stats = result.data;
                    document.getElementById('total-dispatches').textContent = stats.total_dispatches;
                    document.getElementById('in-transit').textContent = stats.in_transit;
                    document.getElementById('delivered').textContent = stats.delivered;
                    document.getElementById('delayed').textContent = stats.delayed;
                }
            } catch (error) {
                console.error('Error loading stats:', error);
            }
        }

        async function createDispatch(dispatchData) {
            // Generate receipt reference
            dispatchData.receipt_reference = 'REC' + Date.now().toString().slice(-6);
            
            const response = await fetch(`${API_BASE_URL}/dispatches`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(dispatchData)
            });
            return await response.json();
        }

        async function updateDispatch(id, dispatchData) {
            const response = await fetch(`${API_BASE_URL}/dispatches/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(dispatchData)
            });
            return await response.json();
        }

        async function deleteDispatch(id) {
            const response = await fetch(`${API_BASE_URL}/dispatches/${id}`, {
                method: 'DELETE'
            });
            return await response.json();
        }

        // ==================== UI FUNCTIONS ====================
        function displayDispatches(dispatchesData) {
            const dispatches = dispatchesData.data;
            totalPages = dispatchesData.last_page;

            dispatchesTableBody.innerHTML = '';

            if (dispatches.length === 0) {
                dispatchesTableBody.innerHTML = `
                    <tr>
                        <td colspan="7" class="text-center py-8 text-gray-500">
                            <i class="bx bx-package text-4xl mb-2"></i>
                            <p>No dispatches found</p>
                        </td>
                    </tr>
                `;
                return;
            }

            dispatches.forEach(dispatch => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="font-mono">DSP${String(dispatch.id).padStart(5, '0')}</td>
                    <td>${dispatch.project ? escapeHtml(dispatch.project.name) : 'N/A'}</td>
                    <td>
                        <span class="badge badge-outline">
                            ${getMaterialTypeText(dispatch.material_type)}
                        </span>
                    </td>
                    <td>${dispatch.quantity} Unit${dispatch.quantity !== 1 ? 's' : ''}</td>
                    <td>${formatDate(dispatch.expected_delivery_date)}</td>
                    <td>
                        <span class="badge ${getStatusBadgeClass(dispatch.status)}">
                            ${getStatusText(dispatch.status)}
                        </span>
                    </td>
                    <td>
                        <div class="flex gap-1">
                            <button class="btn btn-sm btn-circle btn-outline btn-info" onclick="viewDispatch(${dispatch.id})">
                                <i class="bx bx-show"></i>
                            </button>
                            <button class="btn btn-sm btn-circle btn-outline btn-warning" onclick="editDispatch(${dispatch.id})">
                                <i class="bx bx-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-circle btn-outline btn-error" onclick="openDeleteModal(${dispatch.id})">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                dispatchesTableBody.appendChild(row);
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
                    loadDispatches();
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
                    loadDispatches();
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
                    loadDispatches();
                }
            };
            pagination.appendChild(nextButton);
        }

        // ==================== MODAL FUNCTIONS ====================
        function openAddModal() {
            modalTitle.textContent = 'Add New Dispatch';
            dispatchForm.reset();
            dispatchIdInput.value = '';
            
            // Auto-generate receipt reference
            const receiptInput = dispatchForm.querySelector('input[name="receipt_reference"]');
            receiptInput.value = 'REC' + Date.now().toString().slice(-6);
            
            dispatchModal.showModal();
        }

        async function viewDispatch(id) {
            try {
                showLoading();
                const response = await fetch(`${API_BASE_URL}/dispatches/${id}`);
                const result = await response.json();

                if (result.success) {
                    const dispatch = result.data;
                    
                    // Create and show view modal
                    const viewModal = document.createElement('dialog');
                    viewModal.className = 'modal modal-middle';
                    viewModal.innerHTML = `
                        <div class="modal-box max-w-2xl">
                            <h3 class="font-bold text-lg mb-4">Dispatch Details - DSP${String(dispatch.id).padStart(5, '0')}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Project</span>
                                    </label>
                                    <div class="p-2 bg-base-200 rounded">${dispatch.project ? escapeHtml(dispatch.project.name) : 'N/A'}</div>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Material Type</span>
                                    </label>
                                    <div class="p-2">
                                        <span class="badge badge-outline">
                                            ${getMaterialTypeText(dispatch.material_type)}
                                        </span>
                                    </div>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Quantity</span>
                                    </label>
                                    <div class="p-2 bg-base-200 rounded">${dispatch.quantity} Unit${dispatch.quantity !== 1 ? 's' : ''}</div>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Status</span>
                                    </label>
                                    <div class="p-2">
                                        <span class="badge ${getStatusBadgeClass(dispatch.status)}">
                                            ${getStatusText(dispatch.status)}
                                        </span>
                                    </div>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">From Location</span>
                                    </label>
                                    <div class="p-2 bg-base-200 rounded">${escapeHtml(dispatch.from_location)}</div>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">To Location</span>
                                    </label>
                                    <div class="p-2 bg-base-200 rounded">${escapeHtml(dispatch.to_location)}</div>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Dispatch Date</span>
                                    </label>
                                    <div class="p-2 bg-base-200 rounded">${formatDate(dispatch.dispatch_date)}</div>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Expected Delivery</span>
                                    </label>
                                    <div class="p-2 bg-base-200 rounded">${formatDate(dispatch.expected_delivery_date)}</div>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Actual Delivery</span>
                                    </label>
                                    <div class="p-2 bg-base-200 rounded">${dispatch.actual_delivery_date ? formatDate(dispatch.actual_delivery_date) : 'Not delivered yet'}</div>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Receipt Reference</span>
                                    </label>
                                    <div class="p-2 bg-base-200 rounded font-mono">${dispatch.receipt_reference || 'N/A'}</div>
                                </div>
                                ${dispatch.courier_info ? `
                                <div class="form-control md:col-span-2">
                                    <label class="label">
                                        <span class="label-text font-semibold">Courier Information</span>
                                    </label>
                                    <div class="p-3 bg-base-200 rounded">
                                        <pre class="text-sm">${JSON.stringify(dispatch.courier_info, null, 2)}</pre>
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
                    throw new Error(result.message || 'Failed to load dispatch');
                }
            } catch (error) {
                console.error('Error loading dispatch:', error);
                showError('Failed to load dispatch: ' + error.message);
            } finally {
                hideLoading();
            }
        }

        async function editDispatch(id) {
            try {
                showLoading();
                const response = await fetch(`${API_BASE_URL}/dispatches/${id}`);
                const result = await response.json();

                if (result.success) {
                    const dispatch = result.data;
                    modalTitle.textContent = 'Edit Dispatch';
                    dispatchIdInput.value = dispatch.id;
                    
                    // Fill form with dispatch data
                    Object.keys(dispatch).forEach(key => {
                        const input = dispatchForm.querySelector(`[name="${key}"]`);
                        if (input) {
                            if (input.type === 'date') {
                                input.value = dispatch[key] ? dispatch[key].split('T')[0] : '';
                            } else {
                                input.value = dispatch[key] || '';
                            }
                        }
                    });

                    dispatchModal.showModal();
                } else {
                    throw new Error(result.message || 'Failed to load dispatch');
                }
            } catch (error) {
                console.error('Error loading dispatch:', error);
                showError('Failed to load dispatch: ' + error.message);
            } finally {
                hideLoading();
            }
        }

        function closeModal() {
            dispatchModal.close();
        }

        let dispatchToDelete = null;

        function openDeleteModal(id) {
            dispatchToDelete = id;
            deleteModal.showModal();
        }

        function closeDeleteModal() {
            dispatchToDelete = null;
            deleteModal.close();
        }

        async function confirmDelete() {
            if (!dispatchToDelete) return;

            try {
                showLoading();
                const result = await deleteDispatch(dispatchToDelete);

                if (result.success) {
                    showSuccess('Dispatch deleted successfully');
                    closeDeleteModal();
                    loadDispatches();
                } else {
                    throw new Error(result.message || 'Failed to delete dispatch');
                }
            } catch (error) {
                console.error('Error deleting dispatch:', error);
                showError('Failed to delete dispatch: ' + error.message);
            } finally {
                hideLoading();
            }
        }

        // ==================== FORM HANDLING ====================
        async function handleDispatchSubmit(e) {
            e.preventDefault();
            
            const formData = new FormData(dispatchForm);
            const dispatchData = Object.fromEntries(formData);
            
            // Convert quantity to number
            dispatchData.quantity = parseInt(dispatchData.quantity);

            try {
                showLoading();
                let result;

                if (dispatchIdInput.value) {
                    result = await updateDispatch(dispatchIdInput.value, dispatchData);
                } else {
                    result = await createDispatch(dispatchData);
                }

                if (result.success) {
                    showSuccess(`Dispatch ${dispatchIdInput.value ? 'updated' : 'created'} successfully`);
                    closeModal();
                    loadDispatches();
                } else {
                    throw new Error(result.message || `Failed to ${dispatchIdInput.value ? 'update' : 'create'} dispatch`);
                }
            } catch (error) {
                console.error('Error saving dispatch:', error);
                showError('Failed to save dispatch: ' + error.message);
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