@extends('layouts.app')

@section('title', 'Asset Registration - ALMS')

@section('content')
<div class="module-content bg-white rounded-xl p-6 shadow block">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Asset Registration</h2>
        <button class="btn btn-primary" onclick="openCreateModal()">
            <i class="bx bx-plus mr-2"></i>Register New Asset
        </button>
    </div>

    <!-- Stats Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
        <div class="stat text-primary-content rounded-lg  shadow-lg border-l-4 border-primary">
            <div class="stat-figure text-primary">
                <i class="bx bx-cube text-3xl"></i>
            </div>
            <div class="stat-title text-primary">Total Assets</div>
            <div class="stat-value" id="totalAssets">0</div>
        </div>
        
        <div class="stat text-success-content rounded-lg  shadow-lg border-l-4 border-success">
            <div class="stat-figure text-success">
                <i class="bx bx-check-circle text-3xl"></i>
            </div>
            <div class="stat-title text-success">Active</div>
            <div class="stat-value" id="activeAssets">0</div>
        </div>
        
        <div class="stat text-warning-content rounded-lg shadow-lg border-l-4 border-warning">
            <div class="stat-figure text-warning">
                <i class="bx bx-wrench text-3xl"></i>
            </div>
            <div class="stat-title text-warning">In Maintenance</div>
            <div class="stat-value" id="maintenanceAssets">0</div>
        </div>
        
        <div class="stat text-error-content rounded-lg shadow-lg border-l-4 border-error">
            <div class="stat-figure text-error">
                <i class="bx bx-trash text-3xl"></i>
            </div>
            <div class="stat-title text-error">Disposed</div>
            <div class="stat-value" id="disposedAssets">0</div>
        </div>

        <div class="stat text-info-content rounded-lg shadow-lg border-l-4 border-info">
            <div class="stat-figure text-info">
                <i class="bx bx-time text-3xl"></i>
            </div>
            <div class="stat-title text-info">Overdue</div>
            <div class="stat-value" id="overdueMaintenance">0</div>
        </div>
    </div>

    <!-- Filters and Search Section -->
    <div class="flex flex-col lg:flex-row gap-4 mb-6 p-4 bg-base-200 rounded-lg">
        <div class="flex-1">
            <div class="form-control">
                <div class="input-group">
                    <input type="text" id="searchInput" placeholder="Search by asset ID, serial, name..." class="input input-bordered w-full" />
                </div>
            </div>
        </div>
        <div class="flex gap-4">
            <select id="statusFilter" class="select select-bordered" onchange="loadAssets()">
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="in_maintenance">In Maintenance</option>
                <option value="disposed">Disposed</option>
            </select>
            <select id="categoryFilter" class="select select-bordered" onchange="loadAssets()">
                <option value="">All Categories</option>
            </select>
        </div>
    </div>

    <!-- Assets Table -->
    <div class="overflow-x-auto bg-base-100 rounded-lg">
        <table class="table table-zebra w-full">
            <thead>
                <tr>
                    <th>Asset ID</th>
                    <th>Serial Number</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Branch</th>
                    <th>Assigned To</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="assetsTableBody">
                <!-- Dynamic content will be loaded here -->
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-between items-center mt-4">
        <div class="text-sm text-gray-600" id="paginationInfo"></div>
        <div class="join" id="paginationControls">
            <!-- Pagination buttons will be loaded here -->
        </div>
    </div>
</div>

<!-- Create/Edit Asset Modal -->
<dialog id="assetModal" class="modal">
    <div class="modal-box w-11/12 max-w-3xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg mb-4" id="modalTitle">Register New Asset</h3>
        
        <form id="assetForm" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Serial Number *</span>
                    </label>
                    <input type="text" name="serial_number" class="input input-bordered" required />
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Asset Name *</span>
                    </label>
                    <input type="text" name="name" class="input input-bordered" required />
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Category *</span>
                    </label>
                    <select name="category_id" class="select select-bordered" required>
                        <option value="">Select Category</option>
                    </select>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Branch *</span>
                    </label>
                    <select name="current_branch_id" class="select select-bordered" required>
                        <option value="">Select Branch</option>
                    </select>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Assigned Employee</span>
                    </label>
                    <select name="assigned_employee_id" class="select select-bordered">
                        <option value="">Unassigned</option>
                    </select>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Acquisition Date *</span>
                    </label>
                    <input type="date" name="acquisition_date" class="input input-bordered" required />
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Acquisition Cost *</span>
                    </label>
                    <input type="number" step="0.01" name="acquisition_cost" class="input input-bordered" required />
                </div>
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text">Description</span>
                </label>
                <textarea name="description" class="textarea textarea-bordered h-20" placeholder="Asset description..."></textarea>
            </div>
            
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('assetModal').close()">Cancel</button>
                <button type="submit" class="btn btn-primary" id="submitButton">Register Asset</button>
            </div>
        </form>
    </div>
</dialog>

<!-- Loading Toast -->
<div id="loadingToast" class="toast toast-top toast-center hidden">
    <div class="alert alert-info">
        <span>Loading...</span>
    </div>
</div>

<script>
// ==================== CONFIGURATION ====================
const API_BASE_URL = 'http://localhost:8001/api/alms';

let currentPage = 1;
let currentAssetId = null;

// ==================== INITIALIZATION ====================
document.addEventListener('DOMContentLoaded', function() {
    loadStats();
    loadAssets();
    loadSupportingData();
});

// ==================== STATS FUNCTIONS ====================
async function loadStats() {
    try {
        const response = await fetch(`${API_BASE_URL}/assets/stats`);
        const result = await response.json();
        
        if (result.success) {
            const stats = result.data;
            document.getElementById('totalAssets').textContent = stats.total_assets;
            document.getElementById('activeAssets').textContent = stats.active_assets;
            document.getElementById('maintenanceAssets').textContent = stats.maintenance_assets;
            document.getElementById('disposedAssets').textContent = stats.disposed_assets;
            document.getElementById('overdueMaintenance').textContent = stats.overdue_maintenance;
        }
    } catch (error) {
        console.error('Error loading stats:', error);
        showToast('Error loading statistics', 'error');
    }
}

// ==================== ASSET FUNCTIONS ====================
async function loadAssets(page = 1) {
    showLoading();
    try {
        const search = document.getElementById('searchInput').value;
        const status = document.getElementById('statusFilter').value;
        const category = document.getElementById('categoryFilter').value;
        
        let url = `${API_BASE_URL}/assets?page=${page}`;
        if (search) url += `&search=${encodeURIComponent(search)}`;
        if (status) url += `&status=${status}`;
        if (category) url += `&category=${category}`;
        
        const response = await fetch(url);
        const result = await response.json();
        
        if (result.success) {
            displayAssets(result.data.data);
            setupPagination(result.data);
            currentPage = page;
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        console.error('Error loading assets:', error);
        showToast('Error loading assets', 'error');
        document.getElementById('assetsTableBody').innerHTML = '<tr><td colspan="8" class="text-center text-error">Failed to load assets</td></tr>';
    } finally {
        hideLoading();
    }
}

function displayAssets(assets) {
    const tbody = document.getElementById('assetsTableBody');
    
    if (assets.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" class="text-center">No assets found</td></tr>';
        return;
    }
    
    tbody.innerHTML = assets.map(asset => `
        <tr>
            <td class="font-mono font-bold">${asset.alms_id}</td>
            <td class="font-mono">${asset.serial_number}</td>
            <td>${asset.name}</td>
            <td>${asset.category.name}</td>
            <td>${asset.current_branch.name}</td>
            <td>${asset.assigned_employee ? asset.assigned_employee.name : 'Unassigned'}</td>
            <td>
                <span class="badge ${getStatusBadgeClass(asset.status)}">${asset.status.replace('_', ' ').toUpperCase()}</span>
            </td>
            <td>
                <div class="flex gap-1">
                    <button class="btn btn-sm btn-circle btn-info" title="View Details" onclick="viewAsset(${asset.id})">
                        <i class="bx bx-show"></i>
                    </button>
                    <button class="btn btn-sm btn-circle btn-warning" title="Edit" onclick="editAsset(${asset.id})">
                        <i class="bx bx-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-circle btn-error" title="Delete" onclick="deleteAsset(${asset.id})">
                        <i class="bx bx-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

function getStatusBadgeClass(status) {
    const classes = {
        'active': 'badge-success',
        'in_maintenance': 'badge-warning',
        'disposed': 'badge-error'
    };
    return classes[status] || 'badge-info';
}

// ==================== MODAL FUNCTIONS ====================
function openCreateModal() {
    currentAssetId = null;
    document.getElementById('modalTitle').textContent = 'Register New Asset';
    document.getElementById('submitButton').textContent = 'Register Asset';
    document.getElementById('assetForm').reset();
    document.getElementById('assetModal').showModal();
}

async function editAsset(id) {
    showLoading();
    try {
        const response = await fetch(`${API_BASE_URL}/assets/${id}`);
        const result = await response.json();
        
        if (result.success) {
            const asset = result.data;
            currentAssetId = id;
            
            // Populate form
            const form = document.getElementById('assetForm');
            form.serial_number.value = asset.serial_number;
            form.name.value = asset.name;
            form.category_id.value = asset.category_id;
            form.current_branch_id.value = asset.current_branch_id;
            form.assigned_employee_id.value = asset.assigned_employee_id || '';
            form.acquisition_date.value = asset.acquisition_date;
            form.acquisition_cost.value = asset.acquisition_cost;
            form.description.value = asset.description || '';
            
            document.getElementById('modalTitle').textContent = 'Edit Asset';
            document.getElementById('submitButton').textContent = 'Update Asset';
            document.getElementById('assetModal').showModal();
        }
    } catch (error) {
        console.error('Error loading asset:', error);
        showToast('Error loading asset details', 'error');
    } finally {
        hideLoading();
    }
}

// ==================== FORM HANDLING ====================
document.getElementById('assetForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    await saveAsset();
});

async function saveAsset() {
    showLoading();
    try {
        const formData = new FormData(document.getElementById('assetForm'));
        const data = Object.fromEntries(formData);
        
        // Convert numeric fields
        data.acquisition_cost = parseFloat(data.acquisition_cost);
        data.assigned_employee_id = data.assigned_employee_id || null;
        
        const url = currentAssetId ? `${API_BASE_URL}/assets/${currentAssetId}` : `${API_BASE_URL}/assets`;
        const method = currentAssetId ? 'PUT' : 'POST';
        
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast(currentAssetId ? 'Asset updated successfully!' : 'Asset registered successfully!', 'success');
            document.getElementById('assetModal').close();
            loadAssets(currentPage);
            loadStats();
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        console.error('Error saving asset:', error);
        showToast('Error saving asset: ' + error.message, 'error');
    } finally {
        hideLoading();
    }
}

// ==================== DELETE FUNCTION ====================
async function deleteAsset(id) {
    const result = await Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    });
    
    if (result.isConfirmed) {
        showLoading();
        try {
            const response = await fetch(`${API_BASE_URL}/assets/${id}`, {
                method: 'DELETE'
            });
            
            const result = await response.json();
            
            if (result.success) {
                showToast('Asset deleted successfully!', 'success');
                loadAssets(currentPage);
                loadStats();
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            console.error('Error deleting asset:', error);
            showToast('Error deleting asset: ' + error.message, 'error');
        } finally {
            hideLoading();
        }
    }
}

// ==================== SUPPORTING DATA ====================
async function loadSupportingData() {
    try {
        // Load categories
        const categoriesResponse = await fetch(`${API_BASE_URL}/asset-categories`);
        const categoriesResult = await categoriesResponse.json();
        if (categoriesResult.success) {
            const categorySelect = document.getElementById('categoryFilter');
            const formCategorySelect = document.querySelector('select[name="category_id"]');
            
            categoriesResult.data.forEach(category => {
                categorySelect.innerHTML += `<option value="${category.id}">${category.name}</option>`;
                formCategorySelect.innerHTML += `<option value="${category.id}">${category.name}</option>`;
            });
        }
        
        // Load branches
        const branchesResponse = await fetch(`${API_BASE_URL}/branches`);
        const branchesResult = await branchesResponse.json();
        if (branchesResult.success) {
            const branchSelect = document.querySelector('select[name="current_branch_id"]');
            branchesResult.data.forEach(branch => {
                branchSelect.innerHTML += `<option value="${branch.id}">${branch.name} (${branch.code})</option>`;
            });
        }
        
        // Load employees
        const employeesResponse = await fetch(`${API_BASE_URL}/employees`);
        const employeesResult = await employeesResponse.json();
        if (employeesResult.success) {
            const employeeSelect = document.querySelector('select[name="assigned_employee_id"]');
            employeesResult.data.forEach(employee => {
                employeeSelect.innerHTML += `<option value="${employee.id}">${employee.name} - ${employee.position}</option>`;
            });
        }
    } catch (error) {
        console.error('Error loading supporting data:', error);
    }
}

// ==================== PAGINATION ====================
function setupPagination(paginationData) {
    const infoDiv = document.getElementById('paginationInfo');
    const controlsDiv = document.getElementById('paginationControls');
    
    if (!paginationData) return;
    
    const { current_page, last_page, total, from, to } = paginationData;
    infoDiv.textContent = `Showing ${from} to ${to} of ${total} entries`;
    
    let paginationHTML = '';
    
    // Previous button
    if (current_page > 1) {
        paginationHTML += `<button class="join-item btn btn-sm" onclick="loadAssets(${current_page - 1})">«</button>`;
    }
    
    // Page numbers
    for (let i = 1; i <= last_page; i++) {
        if (i === current_page) {
            paginationHTML += `<button class="join-item btn btn-sm btn-active">${i}</button>`;
        } else {
            paginationHTML += `<button class="join-item btn btn-sm" onclick="loadAssets(${i})">${i}</button>`;
        }
    }
    
    // Next button
    if (current_page < last_page) {
        paginationHTML += `<button class="join-item btn btn-sm" onclick="loadAssets(${current_page + 1})">»</button>`;
    }
    
    controlsDiv.innerHTML = paginationHTML;
}

// ==================== UTILITY FUNCTIONS ====================
function showLoading() {
    document.getElementById('loadingToast').classList.remove('hidden');
}

function hideLoading() {
    document.getElementById('loadingToast').classList.add('hidden');
}

function showToast(message, type = 'info') {
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
        icon: type,
        title: message
    });
}

// Placeholder functions for future implementation
function viewAsset(id) {
    showToast('View feature coming soon!', 'info');
}

// Search on Enter key
document.getElementById('searchInput').addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        loadAssets();
    }
});
</script>
@endsection