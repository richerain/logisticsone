@extends('layouts.app')

@section('title', 'SWS Inventory Management')

@section('content')
<div class="module-content bg-white rounded-xl p-6 shadow block">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Inventory Management</h2>
        <button onclick="openCreateModal()" class="btn btn-primary">
            <i class="bx bxs-plus-circle mr-2"></i>Add Item
        </button>
    </div>

    <!-- Filters and Search Section -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <!-- Search -->
        <div class="md:col-span-2">
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Search items..." 
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
                <option value="restocking">Restocking</option>
                <option value="reserved">Reserved</option>
                <option value="distributed">Distributed</option>
            </select>
        </div>
    </div>

    <!-- Inventory Table -->
    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="w-full table-auto">
            <thead class="bg-gray-800">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Item ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Item Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Stock</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Capacity</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Storage</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Stock Level</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody id="inventoryTableBody" class="bg-white divide-y divide-gray-200">
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
<div id="inventoryModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box">
        <h3 class="font-bold text-lg" id="modalTitle">Add New Item</h3>
        <form id="inventoryForm" class="mt-4 space-y-4">
            @csrf
            <input type="hidden" id="item_id" name="item_id">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="label">
                        <span class="label-text">Item Name</span>
                    </label>
                    <input type="text" id="item_name" name="item_name" class="input input-bordered w-full" required>
                </div>
                
                <div>
                    <label class="label">
                        <span class="label-text">Item Type</span>
                    </label>
                    <select id="item_type" name="item_type" class="select select-bordered w-full" required>
                        <option value="">Select Type</option>
                        <option value="Document">Document</option>
                        <option value="Supplies">Supplies</option>
                        <option value="Equipment">Equipment</option>
                        <option value="Furniture">Furniture</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="label">
                        <span class="label-text">Current Stock</span>
                    </label>
                    <input type="number" id="item_stock" name="item_stock" class="input input-bordered w-full" min="0" required>
                </div>
                
                <div>
                    <label class="label">
                        <span class="label-text">Stock Capacity</span>
                    </label>
                    <input type="number" id="item_stock_capacity" name="item_stock_capacity" class="input input-bordered w-full" min="1" required>
                </div>
                
                <div>
                    <label class="label">
                        <span class="label-text">Storage Location</span>
                    </label>
                    <select id="item_storage_from" name="item_storage_from" class="select select-bordered w-full" required>
                        <option value="">Select Storage</option>
                        <!-- Options will be populated by JavaScript -->
                    </select>
                </div>
            </div>

            <div>
                <label class="label">
                    <span class="label-text">Description</span>
                </label>
                <textarea id="item_desc" name="item_desc" class="textarea textarea-bordered w-full" rows="3"></textarea>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="label">
                        <span class="label-text">Stock Level</span>
                    </label>
                    <select id="item_stock_level" name="item_stock_level" class="select select-bordered w-full" required>
                        <option value="instock">In Stock</option>
                        <option value="lowstock">Low Stock</option>
                    </select>
                </div>
                
                <div>
                    <label class="label">
                        <span class="label-text">Status</span>
                    </label>
                    <select id="item_status" name="item_status" class="select select-bordered w-full" required>
                        <option value="pending">Pending</option>
                        <option value="restocking">Restocking</option>
                        <option value="reserved">Reserved</option>
                        <option value="distributed">Distributed</option>
                    </select>
                </div>
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
        <h3 class="font-bold text-lg">Item Details</h3>
        <div class="mt-4 space-y-4">
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">
                        <span class="label-text font-semibold">Item ID</span>
                    </label>
                    <p id="view_item_id" class="text-gray-700">-</p>
                </div>
                <div>
                    <label class="label">
                        <span class="label-text font-semibold">Item Name</span>
                    </label>
                    <p id="view_item_name" class="text-gray-700">-</p>
                </div>
            </div>
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">
                        <span class="label-text font-semibold">Item Type</span>
                    </label>
                    <p id="view_item_type" class="text-gray-700">-</p>
                </div>
                <div>
                    <label class="label">
                        <span class="label-text font-semibold">Storage Location</span>
                    </label>
                    <p id="view_item_storage" class="text-gray-700">-</p>
                </div>
            </div>

            <div class="grid grid-cols-3 gap-4">
                <div>
                    <label class="label">
                        <span class="label-text font-semibold">Current Stock</span>
                    </label>
                    <p id="view_item_stock" class="text-gray-700">-</p>
                </div>
                <div>
                    <label class="label">
                        <span class="label-text font-semibold">Stock Capacity</span>
                    </label>
                    <p id="view_item_capacity" class="text-gray-700">-</p>
                </div>
                <div>
                    <label class="label">
                        <span class="label-text font-semibold">Stock Level</span>
                    </label>
                    <p id="view_stock_level" class="text-gray-700">-</p>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="label">
                        <span class="label-text font-semibold">Status</span>
                    </label>
                    <p id="view_item_status" class="text-gray-700">-</p>
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
                <p id="view_item_desc" class="text-gray-700">-</p>
            </div>
        </div>
        <div class="modal-action">
            <button type="button" onclick="closeViewModal()" class="btn btn-ghost">Close</button>
        </div>
    </div>
</div>

<!-- Request Restock Modal -->
<div id="restockModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box">
        <h3 class="font-bold text-lg">Request Restock</h3>
        <form id="restockForm" class="mt-4 space-y-4">
            @csrf
            <input type="hidden" id="restock_item_id" name="restock_item_id">
            
            <div class="bg-gray-50 p-4 rounded-lg">
                <h4 class="font-semibold mb-2">Item Details</h4>
                <p><strong>Name:</strong> <span id="restock_item_name"></span></p>
                <p><strong>Current Stock:</strong> <span id="restock_current_stock"></span></p>
                <p><strong>Capacity:</strong> <span id="restock_capacity"></span></p>
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
            <button type="button" onclick="closeRestockModal()" class="btn btn-ghost">Cancel</button>
            <button type="button" onclick="submitRestockForm()" class="btn btn-primary">
                <i class="bx bxs-truck mr-2"></i>Request Restock
            </button>
        </div>
    </div>
</div>

<script>
let currentPage = 1;
let currentData = [];
let storageLocations = [];
let searchTimeout;

// Initialize the page
document.addEventListener('DOMContentLoaded', function() {
    loadInventory();
    loadStorageLocations();
    
    // Add event listeners for filters
    document.getElementById('searchInput').addEventListener('input', handleSearch);
    document.getElementById('typeFilter').addEventListener('change', loadInventory);
    document.getElementById('statusFilter').addEventListener('change', loadInventory);
});

// Handle search with debounce
function handleSearch() {
    clearTimeout(searchTimeout);
    searchTimeout = setTimeout(() => {
        loadInventory();
    }, 500);
}

// Load inventory data
async function loadInventory(page = 1) {
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
        
        const response = await fetch(`http://localhost:8001/api/sws/inventory?${params}`);
        const result = await response.json();
        
        if (result.success) {
            currentData = result.data;
            displayInventoryData(result.data);
            setupPagination(result.data);
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        showToast('error', 'Failed to load inventory data');
        console.error('Error loading inventory:', error);
    }
}

// Display inventory data in table
function displayInventoryData(data) {
    const tbody = document.getElementById('inventoryTableBody');
    
    if (data.data.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                    No items found
                </td>
            </tr>
        `;
        return;
    }
    
    tbody.innerHTML = data.data.map(item => `
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${item.item_id}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.item_name}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                <span class="badge badge-outline">${item.item_type}</span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.item_stock}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${item.item_stock_capacity}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${item.item_storage_from}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="status-badge ${getStatusBadgeClass(item.item_stock_level)}">
                    ${item.item_stock_level}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="status-badge ${getStatusBadgeClass(item.item_status)}">
                    ${item.item_status}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                    <button onclick="viewItem('${item.item_id}')" class="btn btn-sm btn-circle btn-info" title="View full Detail">
                        <i class="bx bxs-show"></i>
                    </button>
                    <button onclick="editItem('${item.item_id}')" class="btn btn-sm btn-circle btn-warning" title="Edit">
                        <i class="bx bxs-edit"></i>
                    </button>
                    <button onclick="requestRestock('${item.item_id}')" class="btn btn-sm btn-circle btn-primary" title="Request Restock">
                        <i class="bx bxs-cart"></i>
                    </button>
                    <button onclick="deleteItem('${item.item_id}')" class="btn btn-sm btn-circle btn-error" title="Delete">
                        <i class="bx bxs-trash"></i>
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
        'restocking': 'status-restocking',
        'reserved': 'status-reserved',
        'distributed': 'status-distributed',
        'instock': 'status-instock',
        'lowstock': 'status-lowstock'
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
        paginationHTML += `<button class="join-item btn btn-outline" onclick="loadInventory(${data.current_page - 1})">Previous</button>`;
    }
    
    for (let i = 1; i <= data.last_page; i++) {
        if (i === data.current_page) {
            paginationHTML += `<button class="join-item btn btn-active">${i}</button>`;
        } else {
            paginationHTML += `<button class="join-item btn btn-outline" onclick="loadInventory(${i})">${i}</button>`;
        }
    }
    
    if (data.current_page < data.last_page) {
        paginationHTML += `<button class="join-item btn btn-outline" onclick="loadInventory(${data.current_page + 1})">Next</button>`;
    }
    
    container.innerHTML = paginationHTML;
}

// Load storage locations for dropdown
async function loadStorageLocations() {
    try {
        const response = await fetch('http://localhost:8001/api/sws/storage');
        const result = await response.json();
        
        if (result.success) {
            storageLocations = result.data.data;
            const select = document.getElementById('item_storage_from');
            select.innerHTML = '<option value="">Select Storage</option>' +
                storageLocations.map(storage => 
                    `<option value="${storage.storage_id}">${storage.storage_name} (${storage.storage_location})</option>`
                ).join('');
        }
    } catch (error) {
        console.error('Error loading storage locations:', error);
    }
}

// Modal functions
function openCreateModal() {
    document.getElementById('modalTitle').textContent = 'Add New Item';
    document.getElementById('inventoryForm').reset();
    document.getElementById('item_id').value = '';
    document.getElementById('inventoryModal').classList.add('modal-open');
}

function closeModal() {
    document.getElementById('inventoryModal').classList.remove('modal-open');
}

function closeViewModal() {
    document.getElementById('viewModal').classList.remove('modal-open');
}

function closeRestockModal() {
    document.getElementById('restockModal').classList.remove('modal-open');
}

// View item details
async function viewItem(itemId) {
    try {
        const response = await fetch(`http://localhost:8001/api/sws/inventory/${itemId}`);
        const result = await response.json();
        
        if (result.success) {
            const item = result.data;
            document.getElementById('view_item_id').textContent = item.item_id;
            document.getElementById('view_item_name').textContent = item.item_name;
            document.getElementById('view_item_type').textContent = item.item_type;
            document.getElementById('view_item_storage').textContent = item.item_storage_from;
            document.getElementById('view_item_stock').textContent = item.item_stock;
            document.getElementById('view_item_capacity').textContent = item.item_stock_capacity;
            document.getElementById('view_stock_level').innerHTML = `<span class="status-badge ${getStatusBadgeClass(item.item_stock_level)}">${item.item_stock_level}</span>`;
            document.getElementById('view_item_status').innerHTML = `<span class="status-badge ${getStatusBadgeClass(item.item_status)}">${item.item_status}</span>`;
            document.getElementById('view_updated_at').textContent = new Date(item.updated_at).toLocaleString();
            document.getElementById('view_item_desc').textContent = item.item_desc || 'No description';
            
            document.getElementById('viewModal').classList.add('modal-open');
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        showToast('error', 'Failed to load item details');
    }
}

// Edit item
async function editItem(itemId) {
    try {
        const response = await fetch(`http://localhost:8001/api/sws/inventory/${itemId}`);
        const result = await response.json();
        
        if (result.success) {
            const item = result.data;
            document.getElementById('modalTitle').textContent = 'Edit Item';
            document.getElementById('item_id').value = item.item_id;
            document.getElementById('item_name').value = item.item_name;
            document.getElementById('item_type').value = item.item_type;
            document.getElementById('item_stock').value = item.item_stock;
            document.getElementById('item_stock_capacity').value = item.item_stock_capacity;
            document.getElementById('item_desc').value = item.item_desc || '';
            document.getElementById('item_storage_from').value = item.item_storage_from;
            document.getElementById('item_stock_level').value = item.item_stock_level;
            document.getElementById('item_status').value = item.item_status;
            
            document.getElementById('inventoryModal').classList.add('modal-open');
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        showToast('error', 'Failed to load item data');
    }
}

// Request restock
async function requestRestock(itemId) {
    try {
        const response = await fetch(`http://localhost:8001/api/sws/inventory/${itemId}`);
        const result = await response.json();
        
        if (result.success) {
            const item = result.data;
            document.getElementById('restock_item_id').value = item.item_id;
            document.getElementById('restock_item_name').textContent = item.item_name;
            document.getElementById('restock_current_stock').textContent = item.item_stock;
            document.getElementById('restock_capacity').textContent = item.item_stock_capacity;
            document.getElementById('restock_item_unit').value = '';
            document.getElementById('restock_desc').value = '';
            document.getElementById('restock_status').value = 'pending';
            
            document.getElementById('restockModal').classList.add('modal-open');
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        showToast('error', 'Failed to load item data');
    }
}

// Submit inventory form
async function submitForm() {
    const form = document.getElementById('inventoryForm');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);
    const isEdit = !!data.item_id;
    
    showLoading(isEdit ? 'Updating item...' : 'Creating item...');
    
    try {
        const url = isEdit 
            ? `http://localhost:8001/api/sws/inventory/${data.item_id}`
            : 'http://localhost:8001/api/sws/inventory';
            
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
            showToast('success', isEdit ? 'Item updated successfully' : 'Successfully added new Item');
            closeModal();
            loadInventory(currentPage);
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        hideLoading();
        showToast('error', 'Failed to save item');
        console.error('Error saving item:', error);
    }
}

// Submit restock form
async function submitRestockForm() {
    const form = document.getElementById('restockForm');
    const formData = new FormData(form);
    const data = Object.fromEntries(formData);
    
    showLoading('Creating restock request...');
    
    try {
        const response = await fetch('http://localhost:8001/api/sws/restock', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            hideLoading();
            showToast('success', 'Successfully added new Restock request');
            closeRestockModal();
            loadInventory(currentPage);
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        hideLoading();
        showToast('error', 'Failed to create restock request');
        console.error('Error creating restock request:', error);
    }
}

// Delete item
async function deleteItem(itemId) {
    const item = currentData.data.find(i => i.item_id === itemId);
    
    Swal.fire({
        title: 'Are you sure?',
        text: `You are about to delete "${item.item_name}". This action cannot be undone.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!',
        cancelButtonText: 'Cancel'
    }).then(async (result) => {
        if (result.isConfirmed) {
            showLoading('Deleting item...');
            
            try {
                const response = await fetch(`http://localhost:8001/api/sws/inventory/${itemId}`, {
                    method: 'DELETE'
                });
                
                const result = await response.json();
                
                if (result.success) {
                    hideLoading();
                    showToast('success', 'Item deleted successfully');
                    loadInventory(currentPage);
                } else {
                    throw new Error(result.message);
                }
            } catch (error) {
                hideLoading();
                showToast('error', 'Failed to delete item');
                console.error('Error deleting item:', error);
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
.status-restocking { background-color: #dbeafe; color: #1e40af; }
.status-reserved { background-color: #e0e7ff; color: #3730a3; }
.status-distributed { background-color: #d1fae5; color: #065f46; }
.status-instock { background-color: #d1fae5; color: #065f46; }
.status-lowstock { background-color: #fef3c7; color: #92400e; }
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