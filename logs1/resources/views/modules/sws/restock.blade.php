@extends('layouts.app')

@section('title', 'SWS Restock Management')

@section('content')
<div class="module-content bg-white rounded-xl p-6 shadow block">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Restock Management</h2>
        <button onclick="openCreateModal()" class="btn btn-primary">
            <i class="bx bxs-plus-circle mr-2"></i>Add Restock
        </button>
    </div>

    <!-- Filters and Search Section -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <!-- Search -->
        <div class="md:col-span-2">
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Search restocks..." 
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
                <option value="pending">Pending</option>
                <option value="approve">Approve</option>
                <option value="delivered">Delivered</option>
            </select>
        </div>
    </div>

    <!-- Restock Table -->
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="w-full table-auto">
            <thead class="bg-gray-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Restock ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Item Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Units</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Capacity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Description</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Created</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody id="restockTableBody" class="bg-white divide-y divide-gray-200">
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
<div id="restockModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box">
        <h3 class="font-bold text-lg" id="modalTitle">Add New Restock Request</h3>
        <form id="restockForm" class="mt-4 space-y-4">
            @csrf
            <input type="hidden" id="restock_id" name="restock_id">
            
            <div>
                <label class="label">
                    <span class="label-text">Select Item</span>
                </label>
                <select id="restock_item_id" name="restock_item_id" class="select select-bordered w-full" required onchange="updateItemDetails()">
                    <option value="">Select Item</option>
                    <!-- Options will be populated by JavaScript -->
                </select>
            </div>

            <div class="bg-gray-50 p-4 rounded-lg">
                <h4 class="font-semibold mb-2">Item Details</h4>
                <div class="grid grid-cols-2 gap-2 text-sm">
                    <div><strong>Current Stock:</strong> <span id="item_current_stock">-</span></div>
                    <div><strong>Capacity:</strong> <span id="item_capacity">-</span></div>
                    <div><strong>Type:</strong> <span id="item_type">-</span></div>
                    <div><strong>Storage:</strong> <span id="item_storage">-</span></div>
                </div>
            </div>

            <div>
                <label class="label">
                    <span class="label-text">Restock Units</span>
                </label>
                <input type="number" id="restock_item_unit" name="restock_item_unit" class="input input-bordered w-full" min="1" required>
            </div>

            <div>
                <label class="label">
                    <span class="label-text">Description</span>
                </label>
                <textarea id="restock_desc" name="restock_desc" class="textarea textarea-bordered w-full" rows="3" placeholder="Reason for restock..."></textarea>
            </div>

            <div>
                <label class="label">
                    <span class="label-text">Status</span>
                </label>
                <select id="restock_status" name="restock_status" class="select select-bordered w-full" required>
                    <option value="pending">Pending</option>
                    <option value="approve">Approve</option>
                    <option value="delivered">Delivered</option>
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
        <h3 class="font-bold text-lg">Restock Request Details</h3>
        <div class="mt-4 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">
                        <span class="label-text font-semibold">Restock ID</span>
                    </label>
                    <p id="view_restock_id" class="text-gray-700">-</p>
                </div>
                <div>
                    <label class="label">
                        <span class="label-text font-semibold">Item ID</span>
                    </label>
                    <p id="view_item_id" class="text-gray-700">-</p>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">
                        <span class="label-text font-semibold">Item Name</span>
                    </label>
                    <p id="view_item_name" class="text-gray-700">-</p>
                </div>
                <div>
                    <label class="label">
                        <span class="label-text font-semibold">Item Type</span>
                    </label>
                    <p id="view_item_type" class="text-gray-700">-</p>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="label">
                        <span class="label-text font-semibold">Restock Units</span>
                    </label>
                    <p id="view_restock_units" class="text-gray-700">-</p>
                </div>
                <div>
                    <label class="label">
                        <span class="label-text font-semibold">Item Capacity</span>
                    </label>
                    <p id="view_item_capacity" class="text-gray-700">-</p>
                </div>
                <div>
                    <label class="label">
                        <span class="label-text font-semibold">Status</span>
                    </label>
                    <p id="view_restock_status" class="text-gray-700">-</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">
                        <span class="label-text font-semibold">Created Date</span>
                    </label>
                    <p id="view_created_at" class="text-gray-700">-</p>
                </div>
                <div>
                    <label class="label">
                        <span class="label-text font-semibold">Last Updated</span>
                    </label>
                    <p id="view_updated_at" class="text-gray-700">-</p>
                </div>
            </div>

            <div>
                <label class="label">
                    <span class="label-text font-semibold">Description</span>
                </label>
                <p id="view_restock_desc" class="text-gray-700">-</p>
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
let inventoryItems = [];
let searchTimeout;

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    loadRestocks();
    loadInventoryItems();
    
    // Add event listeners for filters
    document.getElementById('searchInput').addEventListener('input', handleSearch);
    document.getElementById('typeFilter').addEventListener('change', loadRestocks);
    document.getElementById('statusFilter').addEventListener('change', loadRestocks);
});

// Handle search with debounce
function handleSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        loadRestocks();
    }, 500);
}

// Load restock data
async function loadRestocks(page = 1) {
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
        
        const response = await fetch(`http://localhost:8001/api/sws/restock?${params}`);
        const result = await response.json();
        
        if (result.success) {
            currentData = result.data;
            displayRestockData(result.data);
            setupPagination(result.data);
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        showToast('error', 'Failed to load restock data');
        console.error('Error loading restocks:', error);
    }
}

// Display restock data in table
function displayRestockData(data) {
    const tbody = document.getElementById('restockTableBody');
    
    if (data.data.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                    No restock requests found
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = data.data.map(restock => `
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${restock.restock_id}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                <div>
                    <div class="font-medium">${restock.restock_item_name}</div>
                    <div class="text-xs text-gray-500">${restock.restock_item_id}</div>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                <span class="badge badge-outline">${restock.restock_item_type}</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${restock.restock_item_unit}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${restock.restock_item_capacity}</td>
            <td class="px-6 py-4 text-sm text-gray-500">
                ${restock.restock_desc ? restock.restock_desc.substring(0, 50) + (restock.restock_desc.length > 50 ? '...' : '') : '-'}
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="status-badge ${getStatusBadgeClass(restock.restock_status)}">
                    ${restock.restock_status}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                ${new Date(restock.created_at).toLocaleDateString()}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                    <button onclick="viewRestock('${restock.restock_id}')" class="btn btn-sm btn-circle bg-blue-300btn-info" title="View full Detail">
                        <i class="bx bx-show-alt"></i>
                    </button>
                    <button onclick="editRestock('${restock.restock_id}')" class="btn btn-sm btn-circle bg-yellow-300" title="Edit">
                        <i class="bx bx-edit"></i>
                    </button>
                    <button onclick="deleteRestock('${restock.restock_id}')" class="btn btn-sm btn-circle bg-red-300" title="Delete">
                        <i class="bx bx-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

// Get status badge class
function getStatusBadgeClass(status) {
    const classes = {
        'pending': 'status-pending',
        'approve': 'status-approve',
        'delivered': 'status-delivered'
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
        paginationHTML += `<button class="join-item btn btn-outline" onclick="loadRestocks(${data.current_page - 1})">Previous</button>`;
    }
    
    for (let i = 1; i <= data.last_page; i++) {
        if (i === data.current_page) {
            paginationHTML += `<button class="join-item btn btn-active">${i}</button>`;
        } else {
            paginationHTML += `<button class="join-item btn btn-outline" onclick="loadRestocks(${i})">${i}</button>`;
        }
    }
    
    if (data.current_page < data.last_page) {
        paginationHTML += `<button class="join-item btn btn-outline" onclick="loadRestocks(${data.current_page + 1})">Next</button>`;
    }
    
    container.innerHTML = paginationHTML;
}

// Load inventory items for dropdown
async function loadInventoryItems() {
    try {
        const response = await fetch('http://localhost:8001/api/sws/inventory');
        const result = await response.json();
        
        if (result.success) {
            inventoryItems = result.data.data;
            const select = document.getElementById('restock_item_id');
            select.innerHTML = '<option value="">Select Item</option>' +
                inventoryItems.map(item => 
                    `<option value="${item.item_id}" data-stock="${item.item_stock}" data-capacity="${item.item_stock_capacity}" data-type="${item.item_type}" data-storage="${item.item_storage_from}">
                        ${item.item_name} (${item.item_id})
                    </option>`
                ).join('');
        }
    } catch (error) {
        console.error('Error loading inventory items:', error);
    }
}

// Update item details when selection changes
function updateItemDetails() {
    const select = document.getElementById('restock_item_id');
    const selectedOption = select.options[select.selectedIndex];
    
    if (selectedOption.value) {
        document.getElementById('item_current_stock').textContent = selectedOption.getAttribute('data-stock');
        document.getElementById('item_capacity').textContent = selectedOption.getAttribute('data-capacity');
        document.getElementById('item_type').textContent = selectedOption.getAttribute('data-type');
        document.getElementById('item_storage').textContent = selectedOption.getAttribute('data-storage');
    } else {
        document.getElementById('item_current_stock').textContent = '-';
        document.getElementById('item_capacity').textContent = '-';
        document.getElementById('item_type').textContent = '-';
        document.getElementById('item_storage').textContent = '-';
    }
}

// Modal functions
function openCreateModal() {
    document.getElementById('modalTitle').textContent = 'Add New Restock Request';
    document.getElementById('restockForm').reset();
    document.getElementById('restock_id').value = '';
    document.getElementById('restockModal').classList.add('modal-open');
    updateItemDetails();
}

function closeModal() {
    document.getElementById('restockModal').classList.remove('modal-open');
}

function closeViewModal() {
    document.getElementById('viewModal').classList.remove('modal-open');
}

// View restock details
async function viewRestock(restockId) {
    try {
        const response = await fetch(`http://localhost:8001/api/sws/restock/${restockId}`);
        const result = await response.json();
        
        if (result.success) {
            const restock = result.data;
            document.getElementById('view_restock_id').textContent = restock.restock_id;
            document.getElementById('view_item_id').textContent = restock.restock_item_id;
            document.getElementById('view_item_name').textContent = restock.restock_item_name;
            document.getElementById('view_item_type').textContent = restock.restock_item_type;
            document.getElementById('view_restock_units').textContent = restock.restock_item_unit;
            document.getElementById('view_item_capacity').textContent = restock.restock_item_capacity;
            document.getElementById('view_restock_status').innerHTML = `<span class="status-badge ${getStatusBadgeClass(restock.restock_status)}">${restock.restock_status}</span>`;
            document.getElementById('view_created_at').textContent = new Date(restock.created_at).toLocaleString();
            document.getElementById('view_updated_at').textContent = new Date(restock.updated_at).toLocaleString();
            document.getElementById('view_restock_desc').textContent = restock.restock_desc || 'No description';
            
            document.getElementById('viewModal').classList.add('modal-open');
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        showToast('error', 'Failed to load restock details');
    }
}

// Edit restock
async function editRestock(restockId) {
    try {
        const response = await fetch(`http://localhost:8001/api/sws/restock/${restockId}`);
        const result = await response.json();
        
        if (result.success) {
            const restock = result.data;
            document.getElementById('modalTitle').textContent = 'Edit Restock Request';
            document.getElementById('restock_id').value = restock.restock_id;
            document.getElementById('restock_item_id').value = restock.restock_item_id;
            document.getElementById('restock_item_unit').value = restock.restock_item_unit;
            document.getElementById('restock_desc').value = restock.restock_desc || '';
            document.getElementById('restock_status').value = restock.restock_status;
            
            // Update item details
            updateItemDetails();
            
            document.getElementById('restockModal').classList.add('modal-open');
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        showToast('error', 'Failed to load restock data');
    }
}

// Submit restock form
async function submitForm() {
    const form = document.getElementById('restockForm');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);
    const isEdit = !!data.restock_id;
    
    showLoading(isEdit ? 'Updating restock...' : 'Creating restock request...');
    
    try {
        const url = isEdit 
            ? `http://localhost:8001/api/sws/restock/${data.restock_id}`
            : 'http://localhost:8001/api/sws/restock';
            
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
            showToast('success', isEdit ? 'Restock updated successfully' : 'Successfully added new Restock request');
            closeModal();
            loadRestocks(currentPage);
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        hideLoading();
        showToast('error', 'Failed to save restock request');
        console.error('Error saving restock:', error);
    }
}

// Delete restock
async function deleteRestock(restockId) {
    const restock = currentData.data.find(r => r.restock_id === restockId);
    
    Swal.fire({
        title: 'Are you sure?',
        text: `You are about to delete restock request for "${restock.restock_item_name}". This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then(async (result) => {
        if (result.isConfirmed) {
            showLoading('Deleting restock request...');
            
            try {
                const response = await fetch(`http://localhost:8001/api/sws/restock/${restockId}`, {
                    method: 'DELETE'
                });
                
                const result = await response.json();
                
                if (result.success) {
                    hideLoading();
                    showToast('success', 'Restock request deleted successfully');
                    loadRestocks(currentPage);
                } else {
                    throw new Error(result.message);
                }
            } catch (error) {
                hideLoading();
                showToast('error', 'Failed to delete restock request');
                console.error('Error deleting restock:', error);
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

.status-pending { background-color: #fef3c7; color: #92400e; }
.status-approve { background-color: #d1fae5; color: #065f46; }
.status-delivered { background-color: #d1fae5; color: #065f46; }
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