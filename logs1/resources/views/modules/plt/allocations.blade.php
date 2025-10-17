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
                        <th>Resource Type</th>
                        <th>Quantity</th>
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
        // ==================== CONFIGURATION ====================
        const API_BASE_URL = 'http://localhost:8001/api/plt';

        // ==================== STATE MANAGEMENT ====================
        let currentPage = 1;
        let totalPages = 1;
        let searchTerm = '';
        let statusFilter = '';

        // ==================== DOM ELEMENTS ====================
        const allocationsTableBody = document.getElementById('allocations-table-body');
        const pagination = document.getElementById('pagination');
        const searchInput = document.getElementById('search-input');
        const statusFilterSelect = document.getElementById('status-filter');
        const allocationModal = document.getElementById('allocation-modal');
        const allocationForm = document.getElementById('allocation-form');
        const modalTitle = document.getElementById('modal-title');
        const allocationIdInput = document.getElementById('allocation-id');
        const projectSelect = document.getElementById('project-select');
        const resourceSelect = document.getElementById('resource-select');
        const quantityInput = document.getElementById('quantity-input');
        const quantityAvailable = document.getElementById('quantity-available');
        const deleteModal = document.getElementById('delete-modal');
        const confirmDeleteBtn = document.getElementById('confirm-delete-btn');

        // ==================== EVENT LISTENERS ====================
        document.addEventListener('DOMContentLoaded', function() {
            loadAllocations();
            loadProjectsForSelect();
            loadResourcesForSelect();
            setupEventListeners();
        });

        function setupEventListeners() {
            searchInput.addEventListener('input', debounce(() => {
                searchTerm = searchInput.value;
                currentPage = 1;
                loadAllocations();
            }, 500));

            statusFilterSelect.addEventListener('change', () => {
                statusFilter = statusFilterSelect.value;
                currentPage = 1;
                loadAllocations();
            });

            resourceSelect.addEventListener('change', updateQuantityAvailable);
            quantityInput.addEventListener('input', validateQuantity);

            allocationForm.addEventListener('submit', handleAllocationSubmit);
        }

        // ==================== API FUNCTIONS ====================
        async function loadAllocations() {
            try {
                showLoading();
                const params = new URLSearchParams({
                    page: currentPage,
                    search: searchTerm,
                    status: statusFilter
                });

                const response = await fetch(`${API_BASE_URL}/allocations?${params}`);
                const result = await response.json();

                if (result.success) {
                    displayAllocations(result.data);
                    updateStats();
                } else {
                    throw new Error(result.message || 'Failed to load allocations');
                }
            } catch (error) {
                console.error('Error loading allocations:', error);
                showError('Failed to load allocations: ' + error.message);
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

        async function loadResourcesForSelect() {
            try {
                const response = await fetch(`${API_BASE_URL}/resources`);
                const result = await response.json();

                if (result.success) {
                    resourceSelect.innerHTML = '<option value="">Select Resource</option>';
                    result.data.data.forEach(resource => {
                        const option = document.createElement('option');
                        option.value = resource.id;
                        option.textContent = `${resource.name} (${resource.type}) - Available: ${resource.quantity_available}`;
                        option.dataset.quantity = resource.quantity_available;
                        resourceSelect.appendChild(option);
                    });
                }
            } catch (error) {
                console.error('Error loading resources:', error);
            }
        }

        async function updateStats() {
            try {
                const response = await fetch(`${API_BASE_URL}/allocations/stats`);
                const result = await response.json();

                if (result.success) {
                    const stats = result.data;
                    document.getElementById('total-allocations').textContent = stats.total_allocations;
                    document.getElementById('assigned').textContent = stats.assigned;
                    document.getElementById('in-use').textContent = stats.in_use;
                    document.getElementById('returned').textContent = stats.returned;
                }
            } catch (error) {
                console.error('Error loading stats:', error);
            }
        }

        async function createAllocation(allocationData) {
            const response = await fetch(`${API_BASE_URL}/allocations`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(allocationData)
            });
            return await response.json();
        }

        async function updateAllocation(id, allocationData) {
            const response = await fetch(`${API_BASE_URL}/allocations/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(allocationData)
            });
            return await response.json();
        }

        async function deleteAllocation(id) {
            const response = await fetch(`${API_BASE_URL}/allocations/${id}`, {
                method: 'DELETE'
            });
            return await response.json();
        }

        // ==================== UI FUNCTIONS ====================
        function displayAllocations(allocationsData) {
            const allocations = allocationsData.data;
            totalPages = allocationsData.last_page;

            allocationsTableBody.innerHTML = '';

            if (allocations.length === 0) {
                allocationsTableBody.innerHTML = `
                    <tr>
                        <td colspan="6" class="text-center py-8 text-gray-500">
                            <i class="bx bx-user-check text-4xl mb-2"></i>
                            <p>No allocations found</p>
                        </td>
                    </tr>
                `;
                return;
            }

            allocations.forEach(allocation => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td class="font-mono">ALC${String(allocation.id).padStart(5, '0')}</td>
                    <td>${allocation.project ? escapeHtml(allocation.project.name) : 'N/A'}</td>
                    <td>
                        <span class="badge ${getTypeBadgeClass(allocation.resource?.type)}">
                            ${getTypeText(allocation.resource?.type)}
                        </span>
                    </td>
                    <td>${allocation.quantity_assigned} Unit${allocation.quantity_assigned !== 1 ? 's' : ''}</td>
                    <td>
                        <span class="badge ${getStatusBadgeClass(allocation.status)}">
                            ${getStatusText(allocation.status)}
                        </span>
                    </td>
                    <td>
                        <div class="flex gap-1">
                            <button class="btn btn-sm btn-circle btn-outline btn-info" onclick="viewAllocation(${allocation.id})">
                                <i class="bx bx-show"></i>
                            </button>
                            <button class="btn btn-sm btn-circle btn-outline btn-warning" onclick="editAllocation(${allocation.id})">
                                <i class="bx bx-edit"></i>
                            </button>
                            <button class="btn btn-sm btn-circle btn-outline btn-error" onclick="openDeleteModal(${allocation.id})">
                                <i class="bx bx-trash"></i>
                            </button>
                        </div>
                    </td>
                `;
                allocationsTableBody.appendChild(row);
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
                    loadAllocations();
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
                    loadAllocations();
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
                    loadAllocations();
                }
            };
            pagination.appendChild(nextButton);
        }

        // ==================== MODAL FUNCTIONS ====================
        function openAddModal() {
            modalTitle.textContent = 'Add New Allocation';
            allocationForm.reset();
            allocationIdInput.value = '';
            quantityAvailable.textContent = 'Available: 0';
            allocationModal.showModal();
        }

        async function viewAllocation(id) {
            try {
                showLoading();
                const response = await fetch(`${API_BASE_URL}/allocations/${id}`);
                const result = await response.json();

                if (result.success) {
                    const allocation = result.data;
                    
                    // Create and show view modal
                    const viewModal = document.createElement('dialog');
                    viewModal.className = 'modal modal-middle';
                    viewModal.innerHTML = `
                        <div class="modal-box max-w-2xl">
                            <h3 class="font-bold text-lg mb-4">Allocation Details - ALC${String(allocation.id).padStart(5, '0')}</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Project</span>
                                    </label>
                                    <div class="p-2 bg-base-200 rounded">${allocation.project ? escapeHtml(allocation.project.name) : 'N/A'}</div>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Resource</span>
                                    </label>
                                    <div class="p-2 bg-base-200 rounded">${allocation.resource ? escapeHtml(allocation.resource.name) : 'N/A'}</div>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Resource Type</span>
                                    </label>
                                    <div class="p-2">
                                        <span class="badge ${getTypeBadgeClass(allocation.resource?.type)}">
                                            ${getTypeText(allocation.resource?.type)}
                                        </span>
                                    </div>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Status</span>
                                    </label>
                                    <div class="p-2">
                                        <span class="badge ${getStatusBadgeClass(allocation.status)}">
                                            ${getStatusText(allocation.status)}
                                        </span>
                                    </div>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Unit Allocated</span>
                                    </label>
                                    <div class="p-2 bg-base-200 rounded">
                                        <span class="font-semibold">${allocation.quantity_assigned}</span>
                                        <span class="text-sm text-gray-500 ml-2">Unit${allocation.quantity_assigned !== 1 ? 's' : ''}</span>
                                    </div>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Assigned Date</span>
                                    </label>
                                    <div class="p-2 bg-base-200 rounded">${formatDate(allocation.assigned_date)}</div>
                                </div>
                                <div class="form-control">
                                    <label class="label">
                                        <span class="label-text font-semibold">Return Date</span>
                                    </label>
                                    <div class="p-2 bg-base-200 rounded">${allocation.return_date ? formatDate(allocation.return_date) : 'Not returned'}</div>
                                </div>
                                <div class="form-control md:col-span-2">
                                    <label class="label">
                                        <span class="label-text font-semibold">Allocation Period</span>
                                    </label>
                                    <div class="p-2 bg-base-200 rounded text-center">
                                        <div class="text-sm text-gray-500">From ${formatDate(allocation.assigned_date)}</div>
                                        <div class="text-sm text-gray-500">${allocation.return_date ? `To ${formatDate(allocation.return_date)}` : 'Ongoing'}</div>
                                    </div>
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
                    throw new Error(result.message || 'Failed to load allocation');
                }
            } catch (error) {
                console.error('Error loading allocation:', error);
                showError('Failed to load allocation: ' + error.message);
            } finally {
                hideLoading();
            }
        }

        async function editAllocation(id) {
            try {
                showLoading();
                const response = await fetch(`${API_BASE_URL}/allocations/${id}`);
                const result = await response.json();

                if (result.success) {
                    const allocation = result.data;
                    modalTitle.textContent = 'Edit Allocation';
                    allocationIdInput.value = allocation.id;
                    
                    // Fill form with allocation data
                    Object.keys(allocation).forEach(key => {
                        const input = allocationForm.querySelector(`[name="${key}"]`);
                        if (input) {
                            if (input.type === 'date') {
                                input.value = allocation[key] ? allocation[key].split('T')[0] : '';
                            } else {
                                input.value = allocation[key] || '';
                            }
                        }
                    });

                    // Update quantity available for the selected resource
                    if (allocation.resource_id) {
                        updateQuantityAvailable();
                    }

                    allocationModal.showModal();
                } else {
                    throw new Error(result.message || 'Failed to load allocation');
                }
            } catch (error) {
                console.error('Error loading allocation:', error);
                showError('Failed to load allocation: ' + error.message);
            } finally {
                hideLoading();
            }
        }

        function closeModal() {
            allocationModal.close();
        }

        let allocationToDelete = null;

        function openDeleteModal(id) {
            allocationToDelete = id;
            deleteModal.showModal();
        }

        function closeDeleteModal() {
            allocationToDelete = null;
            deleteModal.close();
        }

        async function confirmDelete() {
            if (!allocationToDelete) return;

            try {
                showLoading();
                const result = await deleteAllocation(allocationToDelete);

                if (result.success) {
                    showSuccess('Allocation deleted successfully');
                    closeDeleteModal();
                    loadAllocations();
                } else {
                    throw new Error(result.message || 'Failed to delete allocation');
                }
            } catch (error) {
                console.error('Error deleting allocation:', error);
                showError('Failed to delete allocation: ' + error.message);
            } finally {
                hideLoading();
            }
        }

        // ==================== FORM HANDLING ====================
        async function handleAllocationSubmit(e) {
            e.preventDefault();
            
            const formData = new FormData(allocationForm);
            const allocationData = Object.fromEntries(formData);
            
            // Convert quantity_assigned to number
            allocationData.quantity_assigned = parseInt(allocationData.quantity_assigned);

            try {
                showLoading();
                let result;

                if (allocationIdInput.value) {
                    result = await updateAllocation(allocationIdInput.value, allocationData);
                } else {
                    result = await createAllocation(allocationData);
                }

                if (result.success) {
                    showSuccess(`Allocation ${allocationIdInput.value ? 'updated' : 'created'} successfully`);
                    closeModal();
                    loadAllocations();
                } else {
                    throw new Error(result.message || `Failed to ${allocationIdInput.value ? 'update' : 'create'} allocation`);
                }
            } catch (error) {
                console.error('Error saving allocation:', error);
                showError('Failed to save allocation: ' + error.message);
            } finally {
                hideLoading();
            }
        }

        // ==================== VALIDATION FUNCTIONS ====================
        function updateQuantityAvailable() {
            const selectedOption = resourceSelect.options[resourceSelect.selectedIndex];
            const available = selectedOption ? parseInt(selectedOption.dataset.quantity) || 0 : 0;
            quantityAvailable.textContent = `Available: ${available}`;
            validateQuantity();
        }

        function validateQuantity() {
            const selectedOption = resourceSelect.options[resourceSelect.selectedIndex];
            const available = selectedOption ? parseInt(selectedOption.dataset.quantity) || 0 : 0;
            const requested = parseInt(quantityInput.value) || 0;

            if (requested > available) {
                quantityInput.setCustomValidity(`Cannot allocate more than ${available} units`);
                quantityInput.classList.add('input-error');
            } else {
                quantityInput.setCustomValidity('');
                quantityInput.classList.remove('input-error');
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
                'assigned': 'badge-info',
                'in_use': 'badge-primary',
                'returned': 'badge-success',
                'issue': 'badge-error'
            };
            return statusClasses[status] || 'badge-info';
        }

        function getStatusText(status) {
            const statusTexts = {
                'assigned': 'Assigned',
                'in_use': 'In Use',
                'returned': 'Returned',
                'issue': 'Issue'
            };
            return statusTexts[status] || status;
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