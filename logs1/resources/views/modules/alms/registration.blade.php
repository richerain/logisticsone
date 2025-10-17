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
        <div class="stat text-primary-content rounded-lg shadow-lg border-l-4 border-primary">
            <div class="stat-figure text-primary">
                <i class="bx bx-cube text-3xl"></i>
            </div>
            <div class="stat-title text-primary">Total Assets</div>
            <div class="stat-value text-primary" id="totalAssets">0</div>
        </div>
        
        <div class="stat text-success-content rounded-lg shadow-lg border-l-4 border-success">
            <div class="stat-figure text-success">
                <i class="bx bx-check-circle text-3xl"></i>
            </div>
            <div class="stat-title text-success">Active</div>
            <div class="stat-value text-success" id="activeAssets">0</div>
        </div>
        
        <div class="stat text-warning-content rounded-lg shadow-lg border-l-4 border-warning">
            <div class="stat-figure text-warning">
                <i class="bx bx-wrench text-3xl"></i>
            </div>
            <div class="stat-title text-warning">In Maintenance</div>
            <div class="stat-value text-warning" id="maintenanceAssets">0</div>
        </div>
        
        <div class="stat text-error-content rounded-lg shadow-lg border-l-4 border-error">
            <div class="stat-figure text-error">
                <i class="bx bx-trash text-3xl"></i>
            </div>
            <div class="stat-title text-error">Disposed</div>
            <div class="stat-value text-error" id="disposedAssets">0</div>
        </div>

        <div class="stat text-info-content rounded-lg shadow-lg border-l-4 border-info">
            <div class="stat-figure text-info">
                <i class="bx bx-time text-3xl"></i>
            </div>
            <div class="stat-title text-info">Overdue</div>
            <div class="stat-value text-info" id="overdueMaintenance">0</div>
        </div>
    </div>

    <!-- Filters and Search Section -->
    <div class="flex flex-col lg:flex-row gap-4 mb-6 p-4 bg-base-200 rounded-lg">
        <div class="flex-1">
            <div class="form-control">
                <div class="input-group">
                    <input type="text" id="searchInput" placeholder="Search by asset ID, serial, name..." class="input input-bordered w-full" onkeyup="loadAssets()" />
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
            <input type="hidden" name="id" id="assetId">
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Asset ID</span>
                    </label>
                    <input type="text" id="almsIdDisplay" class="input input-bordered" readonly disabled />
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Serial Number *</span>
                    </label>
                    <input type="text" name="serial_number" class="input input-bordered" required id="serialNumberInput" />
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
                    <div class="input-group">
                        <span class="bg-gray-200 px-3 py-2 border border-r-0 rounded-l-lg">₱</span>
                        <input type="number" step="0.01" name="acquisition_cost" class="input input-bordered rounded-l-none w-full" required />
                    </div>
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

<!-- View Asset Modal -->
<dialog id="viewAssetModal" class="modal">
    <div class="modal-box w-11/12 max-w-2xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg mb-4">Asset Details</h3>
        
        <div id="assetDetails" class="space-y-4">
            <!-- Dynamic content will be loaded here -->
        </div>
        
        <div class="modal-action">
            <button type="button" class="btn btn-ghost" onclick="document.getElementById('viewAssetModal').close()">Close</button>
        </div>
    </div>
</dialog>

<!-- Loading Toast -->
<div id="loadingToast" class="toast toast-middle toast-center hidden">
    <div class="alert alert-info">
        <span>Loading...</span>
    </div>
</div>

<script>
// ==================== CONFIGURATION ====================
const API_BASE_URL = 'http://localhost:8001/api/alms';
let currentPage = 1;
let totalPages = 1;

// ==================== INITIALIZATION ====================
document.addEventListener('DOMContentLoaded', function() {
    loadAssets();
    loadAssetStats();
    loadSupportingData();
    generateSerialNumber();
});

// ==================== ASSET MANAGEMENT ====================
async function loadAssets() {
    showLoading();
    
    const search = document.getElementById('searchInput').value;
    const status = document.getElementById('statusFilter').value;
    const category = document.getElementById('categoryFilter').value;
    
    const params = new URLSearchParams({
        page: currentPage,
        search: search,
        status: status,
        category: category
    });
    
    try {
        const response = await fetch(`${API_BASE_URL}/assets?${params}`);
        const data = await response.json();
        
        if (data.success) {
            displayAssets(data.data.data);
            setupPagination(data.data);
        } else {
            showError('Failed to load assets');
        }
    } catch (error) {
        showError('Error loading assets: ' + error.message);
    } finally {
        hideLoading();
    }
}

function displayAssets(assets) {
    const tbody = document.getElementById('assetsTableBody');
    tbody.innerHTML = '';
    
    if (assets.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-8 text-gray-500">
                    <i class="bx bx-package text-4xl mb-2"></i>
                    <p>No assets found</p>
                </td>
            </tr>
        `;
        return;
    }
    
    assets.forEach(asset => {
        const statusBadge = getStatusBadge(asset.status);
        const assignedTo = asset.assigned_employee ? asset.assigned_employee.name : 'Unassigned';
        
        tbody.innerHTML += `
            <tr>
                <td class="font-mono">${asset.alms_id}</td>
                <td>${asset.serial_number}</td>
                <td>${asset.name}</td>
                <td>${asset.category.name}</td>
                <td>${assignedTo}</td>
                <td>${statusBadge}</td>
                <td>
                    <div class="flex gap-2">
                        <button class="btn btn-sm btn-circle btn-outline btn-info" onclick="viewAsset(${asset.id})">
                            <i class="bx bx-show"></i>
                        </button>
                        <button class="btn btn-sm btn-circle btn-outline btn-warning" onclick="editAsset(${asset.id})">
                            <i class="bx bx-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-circle btn-outline btn-error" onclick="deleteAsset(${asset.id})">
                            <i class="bx bx-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
}

function getStatusBadge(status) {
    const statusConfig = {
        'active': { class: 'badge-success', text: 'Active' },
        'in_maintenance': { class: 'badge-warning', text: 'In Maintenance' },
        'disposed': { class: 'badge-error', text: 'Disposed' }
    };
    
    const config = statusConfig[status] || { class: 'badge-neutral', text: status };
    return `<span class="badge ${config.class}">${config.text}</span>`;
}

// ==================== MODAL MANAGEMENT ====================
function openCreateModal() {
    document.getElementById('modalTitle').textContent = 'Register New Asset';
    document.getElementById('submitButton').textContent = 'Register Asset';
    document.getElementById('assetForm').reset();
    document.getElementById('assetId').value = '';
    generateSerialNumber();
    generateAssetId();
    document.getElementById('assetModal').showModal();
}

function generateSerialNumber() {
    const prefix = 'SN';
    const timestamp = Date.now().toString().slice(-6);
    const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
    document.getElementById('serialNumberInput').value = `${prefix}${timestamp}${random}`;
}

function generateAssetId() {
    // This would typically come from the backend, but for now we'll generate a placeholder
    document.getElementById('almsIdDisplay').value = 'Auto-generated upon save';
}

async function viewAsset(id) {
    showLoading();
    
    try {
        const response = await fetch(`${API_BASE_URL}/assets/${id}`);
        const data = await response.json();
        
        if (data.success) {
            displayAssetDetails(data.data);
            document.getElementById('viewAssetModal').showModal();
        } else {
            showError('Failed to load asset data');
        }
    } catch (error) {
        showError('Error loading asset: ' + error.message);
    } finally {
        hideLoading();
    }
}

function displayAssetDetails(asset) {
    const detailsDiv = document.getElementById('assetDetails');
    const acquisitionDate = new Date(asset.acquisition_date).toLocaleDateString();
    const acquisitionCost = `₱${parseFloat(asset.acquisition_cost).toLocaleString('en-PH', { minimumFractionDigits: 2 })}`;
    const assignedTo = asset.assigned_employee ? asset.assigned_employee.name : 'Unassigned';
    
    detailsDiv.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Asset ID</span>
                </label>
                <div class="text-lg font-mono">${asset.alms_id}</div>
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Serial Number</span>
                </label>
                <div class="text-lg">${asset.serial_number}</div>
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Asset Name</span>
                </label>
                <div class="text-lg">${asset.name}</div>
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Category</span>
                </label>
                <div class="text-lg">${asset.category.name}</div>
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Branch</span>
                </label>
                <div class="text-lg">${asset.current_branch.name}</div>
                <div class="text-sm text-gray-500">${asset.current_branch.code}</div>
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Assigned To</span>
                </label>
                <div class="text-lg">${assignedTo}</div>
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Acquisition Date</span>
                </label>
                <div class="text-lg">${acquisitionDate}</div>
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Acquisition Cost</span>
                </label>
                <div class="text-lg font-mono">${acquisitionCost}</div>
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Status</span>
                </label>
                <div class="text-lg">${getStatusText(asset.status)}</div>
            </div>
        </div>
        
        ${asset.description ? `
        <div class="form-control">
            <label class="label">
                <span class="label-text font-semibold">Description</span>
            </label>
            <div class="p-3 bg-base-200 rounded-lg">${asset.description}</div>
        </div>
        ` : ''}
    `;
}

function getStatusText(status) {
    const statusTexts = {
        'active': 'Active',
        'in_maintenance': 'In Maintenance',
        'disposed': 'Disposed'
    };
    return statusTexts[status] || status;
}

async function editAsset(id) {
    showLoading();
    
    try {
        const response = await fetch(`${API_BASE_URL}/assets/${id}`);
        const data = await response.json();
        
        if (data.success) {
            const asset = data.data;
            document.getElementById('modalTitle').textContent = 'Edit Asset';
            document.getElementById('submitButton').textContent = 'Update Asset';
            document.getElementById('assetId').value = asset.id;
            document.getElementById('almsIdDisplay').value = asset.alms_id;
            document.getElementById('assetForm').serial_number.value = asset.serial_number;
            document.getElementById('assetForm').name.value = asset.name;
            document.getElementById('assetForm').category_id.value = asset.category_id;
            document.getElementById('assetForm').current_branch_id.value = asset.current_branch_id;
            document.getElementById('assetForm').assigned_employee_id.value = asset.assigned_employee_id || '';
            document.getElementById('assetForm').acquisition_date.value = asset.acquisition_date;
            document.getElementById('assetForm').acquisition_cost.value = asset.acquisition_cost;
            document.getElementById('assetForm').description.value = asset.description || '';
            
            document.getElementById('assetModal').showModal();
        } else {
            showError('Failed to load asset data');
        }
    } catch (error) {
        showError('Error loading asset: ' + error.message);
    } finally {
        hideLoading();
    }
}

// ==================== FORM HANDLING ====================
document.getElementById('assetForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const assetId = document.getElementById('assetId').value;
    const isEdit = !!assetId;
    
    showLoading();
    
    try {
        const url = isEdit ? `${API_BASE_URL}/assets/${assetId}` : `${API_BASE_URL}/assets`;
        const method = isEdit ? 'PUT' : 'POST';
        
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(Object.fromEntries(formData))
        });
        
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('assetModal').close();
            showSuccess(isEdit ? 'Asset updated successfully' : 'Asset registered successfully');
            loadAssets();
            loadAssetStats();
        } else {
            showError(data.message || 'Operation failed');
        }
    } catch (error) {
        showError('Error saving asset: ' + error.message);
    } finally {
        hideLoading();
    }
});

// ==================== DELETE ASSET ====================
async function deleteAsset(id) {
    if (!confirm('Are you sure you want to delete this asset?')) return;
    
    showLoading();
    
    try {
        const response = await fetch(`${API_BASE_URL}/assets/${id}`, {
            method: 'DELETE'
        });
        
        const data = await response.json();
        
        if (data.success) {
            showSuccess('Asset deleted successfully');
            loadAssets();
            loadAssetStats();
        } else {
            showError(data.message || 'Delete failed');
        }
    } catch (error) {
        showError('Error deleting asset: ' + error.message);
    } finally {
        hideLoading();
    }
}

// ==================== SUPPORTING DATA ====================
async function loadSupportingData() {
    try {
        const [categoriesRes, branchesRes, employeesRes] = await Promise.all([
            fetch(`${API_BASE_URL}/asset-categories`),
            fetch(`${API_BASE_URL}/branches`),
            fetch(`${API_BASE_URL}/employees`)
        ]);
        
        const categoriesData = await categoriesRes.json();
        const branchesData = await branchesRes.json();
        const employeesData = await employeesRes.json();
        
        if (categoriesData.success) {
            const categorySelect = document.querySelector('select[name="category_id"]');
            categorySelect.innerHTML = '<option value="">Select Category</option>';
            categoriesData.data.forEach(category => {
                categorySelect.innerHTML += `<option value="${category.id}">${category.name}</option>`;
            });
            
            // Also populate filter
            const categoryFilter = document.getElementById('categoryFilter');
            categoryFilter.innerHTML = '<option value="">All Categories</option>';
            categoriesData.data.forEach(category => {
                categoryFilter.innerHTML += `<option value="${category.id}">${category.name}</option>`;
            });
        }
        
        if (branchesData.success) {
            const branchSelect = document.querySelector('select[name="current_branch_id"]');
            branchSelect.innerHTML = '<option value="">Select Branch</option>';
            branchesData.data.forEach(branch => {
                branchSelect.innerHTML += `<option value="${branch.id}">${branch.name} (${branch.code})</option>`;
            });
        }
        
        if (employeesData.success) {
            const employeeSelect = document.querySelector('select[name="assigned_employee_id"]');
            employeeSelect.innerHTML = '<option value="">Unassigned</option>';
            employeesData.data.forEach(employee => {
                employeeSelect.innerHTML += `<option value="${employee.id}">${employee.name} - ${employee.position}</option>`;
            });
        }
    } catch (error) {
        console.error('Error loading supporting data:', error);
    }
}

// ==================== STATS ====================
async function loadAssetStats() {
    try {
        const response = await fetch(`${API_BASE_URL}/assets/stats`);
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('totalAssets').textContent = data.data.total_assets;
            document.getElementById('activeAssets').textContent = data.data.active_assets;
            document.getElementById('maintenanceAssets').textContent = data.data.maintenance_assets;
            document.getElementById('disposedAssets').textContent = data.data.disposed_assets;
            document.getElementById('overdueMaintenance').textContent = data.data.overdue_maintenance;
        }
    } catch (error) {
        console.error('Error loading stats:', error);
    }
}

// ==================== PAGINATION ====================
function setupPagination(pagination) {
    totalPages = pagination.last_page;
    currentPage = pagination.current_page;
    
    document.getElementById('paginationInfo').textContent = 
        `Showing ${pagination.from || 0} to ${pagination.to || 0} of ${pagination.total} entries`;
    
    const paginationControls = document.getElementById('paginationControls');
    paginationControls.innerHTML = '';
    
    // Previous button
    const prevButton = document.createElement('button');
    prevButton.className = `join-item btn ${currentPage === 1 ? 'btn-disabled' : ''}`;
    prevButton.innerHTML = '«';
    prevButton.onclick = () => changePage(currentPage - 1);
    paginationControls.appendChild(prevButton);
    
    // Page buttons
    for (let i = 1; i <= totalPages; i++) {
        const pageButton = document.createElement('button');
        pageButton.className = `join-item btn ${currentPage === i ? 'btn-active' : ''}`;
        pageButton.textContent = i;
        pageButton.onclick = () => changePage(i);
        paginationControls.appendChild(pageButton);
    }
    
    // Next button
    const nextButton = document.createElement('button');
    nextButton.className = `join-item btn ${currentPage === totalPages ? 'btn-disabled' : ''}`;
    nextButton.innerHTML = '»';
    nextButton.onclick = () => changePage(currentPage + 1);
    paginationControls.appendChild(nextButton);
}

function changePage(page) {
    if (page < 1 || page > totalPages) return;
    currentPage = page;
    loadAssets();
}

// ==================== UTILITY FUNCTIONS ====================
function showLoading() {
    document.getElementById('loadingToast').classList.remove('hidden');
}

function hideLoading() {
    document.getElementById('loadingToast').classList.add('hidden');
}

function showSuccess(message) {
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: message,
        timer: 2000,
        showConfirmButton: false
    });
}

function showError(message) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: message
    });
}
</script>
@endsection