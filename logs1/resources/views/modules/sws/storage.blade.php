@extends('layouts.app')

@section('title', 'SWS Storage Management')

@section('content')
<div class="module-content bg-white rounded-xl p-6 shadow block">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Storage Management</h2>
        <button onclick="openCreateModal()" class="btn btn-primary">
            <i class="bx bxs-plus-circle mr-2"></i>Add Storage
        </button>
    </div>

    <!-- Filters and Search Section -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <!-- Search -->
        <div class="md:col-span-2">
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Search storage..." 
                       class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-transparent">
                <i class="bx bx-search absolute left-3 top-3 text-gray-400"></i>
            </div>
        </div>
        
        <!-- Type Filter -->
        <div>
            <select id="typeFilter" class="w-full select select-bordered">
                <option value="">All Types</option>
                <option value="Document">Document</option>
                <option value="Supplies">Supplies</option>
                <option value="Equipment">Equipment</option>
                <option value="Furniture">Furniture</option>
            </select>
        </div>
        
        <!-- Status Filter -->
        <div>
            <select id="statusFilter" class="w-full select select-bordered">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="inactive">Inactive</option>
                <option value="maintenance">Maintenance</option>
            </select>
        </div>
    </div>

    <!-- Storage Table -->
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="w-full table-auto">
            <thead class="bg-gray-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Storage ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Storage Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Location</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Capacity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Used</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Utilization</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody id="storageTableBody" class="bg-white divide-y divide-gray-200">
                <!-- Data will be populated by JavaScript -->
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="mt-6 flex justify-between items-center">
        <div class="text-sm text-gray-700">
            Showing <span id="paginationFrom">0</span> to <span id="paginationTo">0</span> of <span id="paginationTotal">0</span> results
        </div>
        <div class="join" id="paginationContainer">
            <!-- Pagination buttons will be populated by JavaScript -->
        </div>
    </div>
</div>

<!-- Create/Edit Modal -->
<div id="storageModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box">
        <h3 class="font-bold text-lg" id="modalTitle">Add New Storage</h3>
        <form id="storageForm" class="mt-4 space-y-4">
            @csrf
            <input type="hidden" id="storage_id" name="storage_id">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="label">
                        <span class="label-text">Storage Name</span>
                    </label>
                    <input type="text" id="storage_name" name="storage_name" class="input input-bordered w-full" required>
                </div>
                
                <div>
                    <label class="label">
                        <span class="label-text">Location</span>
                    </label>
                    <input type="text" id="storage_location" name="storage_location" class="input input-bordered w-full" required>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="label">
                        <span class="label-text">Storage Type</span>
                    </label>
                    <select id="storage_type" name="storage_type" class="select select-bordered w-full" required>
                        <option value="">Select Type</option>
                        <option value="Document">Document</option>
                        <option value="Supplies">Supplies</option>
                        <option value="Equipment">Equipment</option>
                        <option value="Furniture">Furniture</option>
                    </select>
                </div>
                
                <div>
                    <label class="label">
                        <span class="label-text">Max Capacity (Units)</span>
                    </label>
                    <input type="number" id="storage_max_unit" name="storage_max_unit" class="input input-bordered w-full" min="1" required>
                </div>
            </div>

            <div>
                <label class="label">
                    <span class="label-text">Status</span>
                </label>
                <select id="storage_status" name="storage_status" class="select select-bordered w-full" required>
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                    <option value="maintenance">Maintenance</option>
                </select>
            </div>
        </form>
        <div class="modal-action">
            <button type="button" onclick="closeModal()" class="btn btn-ghost">Cancel</button>
            <button type="button" onclick="submitForm()" class="btn btn-primary" id="submitBtn">
                <i class="bx bxs-save mr-2"></i>Save
            </button>
        </div>
    </div>
</div>

<!-- View Details Modal -->
<div id="viewModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Storage Details</h3>
        <div class="mt-4 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">
                        <span class="label-text font-semibold">Storage ID</span>
                    </label>
                    <p id="view_storage_id" class="text-gray-700">-</p>
                </div>
                <div>
                    <label class="label">
                        <span class="label-text font-semibold">Storage Name</span>
                    </label>
                    <p id="view_storage_name" class="text-gray-700">-</p>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">
                        <span class="label-text font-semibold">Location</span>
                    </label>
                    <p id="view_storage_location" class="text-gray-700">-</p>
                </div>
                <div>
                    <label class="label">
                        <span class="label-text font-semibold">Storage Type</span>
                    </label>
                    <p id="view_storage_type" class="text-gray-700">-</p>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="label">
                        <span class="label-text font-semibold">Max Capacity</span>
                    </label>
                    <p id="view_storage_max_unit" class="text-gray-700">-</p>
                </div>
                <div>
                    <label class="label">
                        <span class="label-text font-semibold">Used Units</span>
                    </label>
                    <p id="view_storage_used_unit" class="text-gray-700">-</p>
                </div>
                <div>
                    <label class="label">
                        <span class="label-text font-semibold">Free Units</span>
                    </label>
                    <p id="view_storage_free_unit" class="text-gray-700">-</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">
                        <span class="label-text font-semibold">Utilization Rate</span>
                    </label>
                    <p id="view_storage_utilization" class="text-gray-700">-</p>
                </div>
                <div>
                    <label class="label">
                        <span class="label-text font-semibold">Status</span>
                    </label>
                    <p id="view_storage_status" class="text-gray-700">-</p>
                </div>
            </div>

            <div>
                <label class="label">
                    <span class="label-text font-semibold">Last Updated</span>
                </label>
                <p id="view_updated_at" class="text-gray-700">-</p>
            </div>
        </div>
        <div class="modal-action">
            <button type="button" onclick="closeViewModal()" class="btn btn-ghost">Close</button>
        </div>
    </div>
</div>

<script>
let currentPage = 1;
let currentData = [];
let searchTimeout;

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    loadStorage();
    
    // Add event listeners for filters
    document.getElementById('searchInput').addEventListener('input', handleSearch);
    document.getElementById('typeFilter').addEventListener('change', loadStorage);
    document.getElementById('statusFilter').addEventListener('change', loadStorage);
});

// Handle search with debounce
function handleSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        loadStorage();
    }, 500);
}

// Load storage data
async function loadStorage(page = 1) {
    const search = document.getElementById('searchInput').value;
    const type = document.getElementById('typeFilter').value;
    const status = document.getElementById('statusFilter').value;
    
    try {
        const params = new URLSearchParams({
            page: page,
            search: search,
            type: type,
            status: status
        });
        
        const response = await fetch(`http://localhost:8001/api/sws/storage?${params}`);
        const result = await response.json();
        
        if (result.success) {
            currentData = result.data;
            displayStorageData(result.data);
            setupPagination(result.data);
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        showToast('error', 'Failed to load storage data');
        console.error('Error loading storage:', error);
    }
}

// Display storage data in table
function displayStorageData(data) {
    const tbody = document.getElementById('storageTableBody');
    
    if (data.data.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                    No storage found
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = data.data.map(storage => `
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${storage.storage_id}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${storage.storage_name}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${storage.storage_location}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                <span class="badge badge-outline">${storage.storage_type}</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${storage.storage_max_unit}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${storage.storage_used_unit}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                    <div class="w-16 bg-gray-200 rounded-full h-2 mr-2">
                        <div class="bg-${getUtilizationColor(storage.storage_utilization_rate)} h-2 rounded-full" 
                             style="width: ${Math.min(storage.storage_utilization_rate, 100)}%"></div>
                    </div>
                    <span class="text-sm text-gray-600">${storage.storage_utilization_rate}%</span>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="status-badge ${getStatusBadgeClass(storage.storage_status)}">
                    ${storage.storage_status}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                    <button onclick="viewStorage('${storage.storage_id}')" class="btn btn-sm btn-circle bg-blue-300" title="View full Detail">
                        <i class="bx bx-show-alt"></i>
                    </button>
                    <button onclick="editStorage('${storage.storage_id}')" class="btn btn-sm btn-circle bg-yellow-300" title="Edit">
                        <i class="bx bx-edit"></i>
                    </button>
                    <button onclick="deleteStorage('${storage.storage_id}')" class="btn btn-sm btn-circle bg-red-300" title="Delete">
                        <i class="bx bx-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

// Get utilization color
function getUtilizationColor(rate) {
    if (rate < 50) return 'green-500';
    if (rate < 80) return 'yellow-500';
    return 'red-500';
}

// Get status badge class
function getStatusBadgeClass(status) {
    const classes = {
        'active': 'status-active',
        'inactive': 'status-inactive',
        'maintenance': 'status-maintenance'
    };
    return classes[status] || 'status-default';
}

// Setup pagination
function setupPagination(data) {
    const container = document.getElementById('paginationContainer');
    const from = document.getElementById('paginationFrom');
    const to = document.getElementById('paginationTo');
    const total = document.getElementById('paginationTotal');
    
    from.textContent = data.from || 0;
    to.textContent = data.to || 0;
    total.textContent = data.total || 0;
    
    let paginationHTML = '';
    
    if (data.current_page > 1) {
        paginationHTML += `<button class="join-item btn btn-outline" onclick="loadStorage(${data.current_page - 1})">Previous</button>`;
    }
    
    for (let i = 1; i <= data.last_page; i++) {
        if (i === data.current_page) {
            paginationHTML += `<button class="join-item btn btn-active">${i}</button>`;
        } else {
            paginationHTML += `<button class="join-item btn btn-outline" onclick="loadStorage(${i})">${i}</button>`;
        }
    }
    
    if (data.current_page < data.last_page) {
        paginationHTML += `<button class="join-item btn btn-outline" onclick="loadStorage(${data.current_page + 1})">Next</button>`;
    }
    
    container.innerHTML = paginationHTML;
}

// Modal functions
function openCreateModal() {
    document.getElementById('modalTitle').textContent = 'Add New Storage';
    document.getElementById('storageForm').reset();
    document.getElementById('storage_id').value = '';
    document.getElementById('storageModal').classList.add('modal-open');
}

function closeModal() {
    document.getElementById('storageModal').classList.remove('modal-open');
}

function closeViewModal() {
    document.getElementById('viewModal').classList.remove('modal-open');
}

// View storage details
async function viewStorage(storageId) {
    try {
        const response = await fetch(`http://localhost:8001/api/sws/storage/${storageId}`);
        const result = await response.json();
        
        if (result.success) {
            const storage = result.data;
            document.getElementById('view_storage_id').textContent = storage.storage_id;
            document.getElementById('view_storage_name').textContent = storage.storage_name;
            document.getElementById('view_storage_location').textContent = storage.storage_location;
            document.getElementById('view_storage_type').textContent = storage.storage_type;
            document.getElementById('view_storage_max_unit').textContent = storage.storage_max_unit;
            document.getElementById('view_storage_used_unit').textContent = storage.storage_used_unit;
            document.getElementById('view_storage_free_unit').textContent = storage.storage_free_unit;
            document.getElementById('view_storage_utilization').textContent = storage.storage_utilization_rate + '%';
            document.getElementById('view_storage_status').innerHTML = `<span class="status-badge ${getStatusBadgeClass(storage.storage_status)}">${storage.storage_status}</span>`;
            document.getElementById('view_updated_at').textContent = new Date(storage.updated_at).toLocaleString();
            
            document.getElementById('viewModal').classList.add('modal-open');
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        showToast('error', 'Failed to load storage details');
    }
}

// Edit storage
async function editStorage(storageId) {
    try {
        const response = await fetch(`http://localhost:8001/api/sws/storage/${storageId}`);
        const result = await response.json();
        
        if (result.success) {
            const storage = result.data;
            document.getElementById('modalTitle').textContent = 'Edit Storage';
            document.getElementById('storage_id').value = storage.storage_id;
            document.getElementById('storage_name').value = storage.storage_name;
            document.getElementById('storage_location').value = storage.storage_location;
            document.getElementById('storage_type').value = storage.storage_type;
            document.getElementById('storage_max_unit').value = storage.storage_max_unit;
            document.getElementById('storage_status').value = storage.storage_status;
            
            document.getElementById('storageModal').classList.add('modal-open');
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        showToast('error', 'Failed to load storage data');
    }
}

// Submit storage form
async function submitForm() {
    const form = document.getElementById('storageForm');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);
    const isEdit = !!data.storage_id;
    
    showLoading(isEdit ? 'Updating storage...' : 'Creating storage...');
    
    try {
        const url = isEdit 
            ? `http://localhost:8001/api/sws/storage/${data.storage_id}`
            : 'http://localhost:8001/api/sws/storage';
            
        const method = isEdit ? 'PUT' : 'POST';
        
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            hideLoading();
            showToast('success', isEdit ? 'Storage updated successfully' : 'Successfully added new Storage');
            closeModal();
            loadStorage(currentPage);
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        hideLoading();
        showToast('error', 'Failed to save storage');
        console.error('Error saving storage:', error);
    }
}

// Delete storage
async function deleteStorage(storageId) {
    const storage = currentData.data.find(s => s.storage_id === storageId);
    
    Swal.fire({
        title: 'Are you sure?',
        text: `You are about to delete "${storage.storage_name}". This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then(async (result) => {
        if (result.isConfirmed) {
            showLoading('Deleting storage...');
            
            try {
                const response = await fetch(`http://localhost:8001/api/sws/storage/${storageId}`, {
                    method: 'DELETE'
                });
                
                const result = await response.json();
                
                if (result.success) {
                    hideLoading();
                    showToast('success', 'Storage deleted successfully');
                    loadStorage(currentPage);
                } else {
                    throw new Error(result.message);
                }
            } catch (error) {
                hideLoading();
                showToast('error', 'Failed to delete storage');
                console.error('Error deleting storage:', error);
            }
        }
    });
}

// Utility functions
function showLoading(message = 'Loading...') {
    Swal.fire({
        title: message,
        allowOutsideClick: false,
        didOpen: () => {
            Swal.showLoading();
        }
    });
}

function hideLoading() {
    Swal.close();
}

function showToast(icon, message) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });
    
    Toast.fire({
        icon: icon,
        title: message
    });
}
</script>

<style>
.status-badge {
    font-size: 0.875rem;
    font-variant: small-caps;
    font-weight: normal;
    letter-spacing: 0.05em;
    padding: 0.25rem 0.75rem;
    border-radius: 0.375rem;
}

.status-active { background-color: #d1fae5; color: #065f46; }
.status-inactive { background-color: #f3f4f6; color: #374151; }
.status-maintenance { background-color: #fee2e2; color: #991b1b; }
.status-default { background-color: #f3f4f6; color: #374151; }

/* Wider modals */
.modal-box {
    max-width: 48rem !important;
    width: 90% !important;
}

/* Table header styling */
.table thead {
    background-color: #4b5563 !important;
}

.table thead th {
    color: white !important;
    font-weight: 600;
}
</style>
@endsection