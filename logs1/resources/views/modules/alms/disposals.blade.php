@extends('layouts.app')

@section('title', 'Disposal Management - ALMS')

@section('content')
<div class="module-content bg-white rounded-xl p-6 shadow block">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Disposal Management</h2>
        <button class="btn btn-primary" onclick="openCreateDisposalModal()">
            <i class="bx bx-trash-alt mr-2"></i>Record Disposal
        </button>
    </div>

    <!-- Stats Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="stat text-primary-content rounded-lg shadow-lg border-l-4 border-primary">
            <div class="stat-figure text-primary">
                <i class="bx bx-trash text-3xl"></i>
            </div>
            <div class="stat-title text-primary">Total Disposals</div>
            <div class="stat-value text-primary" id="totalDisposals">0</div>
        </div>
        
        <div class="stat text-warning-content rounded-lg shadow-lg border-l-4 border-warning">
            <div class="stat-figure text-warning">
                <i class="bx bx-recycle text-3xl"></i>
            </div>
            <div class="stat-title text-warning">Resale</div>
            <div class="stat-value text-warning" id="resaleCount">0</div>
        </div>
        
        <div class="stat text-error-content rounded-lg shadow-lg border-l-4 border-error">
            <div class="stat-figure text-error">
                <i class="bx bx-x-circle text-3xl"></i>
            </div>
            <div class="stat-title text-error">Decommissioned</div>
            <div class="stat-value text-error" id="decommissionCount">0</div>
        </div>
        
        <div class="stat text-success-content rounded-lg shadow-lg border-l-4 border-success">
            <div class="stat-figure text-success">
                <i class="bx bx-dollar-circle text-3xl"></i>
            </div>
            <div class="stat-title text-success">Total Recovered</div>
            <div class="stat-value text-success" id="totalRecovered">₱0</div>
        </div>
    </div>

    <!-- Filters and Search Section -->
    <div class="flex flex-col lg:flex-row gap-4 mb-6 p-4 bg-base-200 rounded-lg">
        <div class="flex-1">
            <div class="form-control">
                <div class="input-group">
                    <input type="text" id="searchInput" placeholder="Search by disposal ID, asset..." class="input input-bordered w-full" onkeyup="loadDisposals()" />
                </div>
            </div>
        </div>
        <div class="flex gap-4">
            <select id="methodFilter" class="select select-bordered" onchange="loadDisposals()">
                <option value="">All Methods</option>
                <option value="decommission">Decommission</option>
                <option value="disposal">Disposal</option>
                <option value="resale">Resale</option>
            </select>
            <input type="month" id="monthFilter" class="input input-bordered" onchange="loadDisposals()">
        </div>
    </div>

    <!-- Disposals Table -->
    <div class="overflow-x-auto bg-base-100 rounded-lg">
        <table class="table table-zebra w-full">
            <thead>
                <tr>
                    <th>Disposal ID</th>
                    <th>Asset</th>
                    <th>Disposal Date</th>
                    <th>Method</th>
                    <th>Disposal Value</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="disposalsTableBody">
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

<!-- Create Disposal Modal -->
<dialog id="disposalModal" class="modal">
    <div class="modal-box w-11/12 max-w-3xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg mb-4">Record Asset Disposal</h3>
        
        <form id="disposalForm" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Disposal ID</span>
                    </label>
                    <input type="text" id="disposalIdDisplay" class="input input-bordered" readonly disabled />
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Asset *</span>
                    </label>
                    <select name="asset_id" class="select select-bordered" required onchange="updateAssetDetails(this.value)">
                        <option value="">Select Asset</option>
                    </select>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Current Value</span>
                    </label>
                    <input type="text" id="currentAssetValue" class="input input-bordered" readonly disabled />
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Disposal Date *</span>
                    </label>
                    <input type="date" name="disposal_date" class="input input-bordered" required />
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Method *</span>
                    </label>
                    <select name="method" class="select select-bordered" required onchange="toggleDisposalValue(this.value)">
                        <option value="">Select Method</option>
                        <option value="decommission">Decommission</option>
                        <option value="disposal">Disposal</option>
                        <option value="resale">Resale</option>
                    </select>
                </div>
                
                <div class="form-control" id="disposalValueField">
                    <label class="label">
                        <span class="label-text">Disposal Value</span>
                    </label>
                    <div class="input-group">
                        <span class="bg-gray-200 px-3 py-2 border border-r-0 rounded-l-lg">₱</span>
                        <input type="number" step="0.01" name="disposal_value" class="input input-bordered rounded-l-none w-full" placeholder="0.00" />
                    </div>
                </div>
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text">Reason *</span>
                </label>
                <textarea name="reason" class="textarea textarea-bordered h-20" placeholder="Reason for disposal..." required></textarea>
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text">Compliance Notes</span>
                </label>
                <textarea name="compliance_notes" class="textarea textarea-bordered h-20" placeholder="Audit/compliance details..."></textarea>
            </div>
            
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('disposalModal').close()">Cancel</button>
                <button type="submit" class="btn btn-primary">Record Disposal</button>
            </div>
        </form>
    </div>
</dialog>

<!-- Disposal Details Modal -->
<dialog id="viewDisposalModal" class="modal">
    <div class="modal-box w-11/12 max-w-2xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg mb-4">Disposal Details</h3>
        
        <div id="disposalDetails" class="space-y-4">
            <!-- Dynamic content will be loaded here -->
        </div>
        
        <div class="modal-action">
            <button type="button" class="btn btn-ghost" onclick="document.getElementById('viewDisposalModal').close()">Close</button>
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
let disposableAssets = [];

// ==================== INITIALIZATION ====================
document.addEventListener('DOMContentLoaded', function() {
    loadDisposals();
    loadDisposalStats();
    setDefaultDate();
});

// ==================== DISPOSAL MANAGEMENT ====================
async function loadDisposals() {
    showLoading();
    
    const search = document.getElementById('searchInput').value;
    const method = document.getElementById('methodFilter').value;
    const month = document.getElementById('monthFilter').value;
    
    const params = new URLSearchParams({
        page: currentPage,
        search: search,
        method: method
    });
    
    if (month) {
        params.append('month', month);
    }
    
    try {
        const response = await fetch(`${API_BASE_URL}/disposals?${params}`);
        const data = await response.json();
        
        if (data.success) {
            displayDisposals(data.data.data);
            setupPagination(data.data);
        } else {
            showError('Failed to load disposals');
        }
    } catch (error) {
        showError('Error loading disposals: ' + error.message);
    } finally {
        hideLoading();
    }
}

function displayDisposals(disposals) {
    const tbody = document.getElementById('disposalsTableBody');
    tbody.innerHTML = '';
    
    if (disposals.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-8 text-gray-500">
                    <i class="bx bx-trash-alt text-4xl mb-2"></i>
                    <p>No disposal records found</p>
                </td>
            </tr>
        `;
        return;
    }
    
    disposals.forEach(disposal => {
        const disposalDate = new Date(disposal.disposal_date).toLocaleDateString();
        const methodBadge = getMethodBadge(disposal.method);
        const disposalValue = disposal.disposal_value ? 
            `₱${parseFloat(disposal.disposal_value).toLocaleString('en-PH', { minimumFractionDigits: 2 })}` : 
            'N/A';
        
        tbody.innerHTML += `
            <tr>
                <td class="font-mono">${disposal.disposal_id}</td>
                <td>
                    <div class="font-semibold">${disposal.asset.name}</div>
                    <div class="text-sm text-gray-500">${disposal.asset.alms_id}</div>
                </td>
                <td>${disposalDate}</td>
                <td>${methodBadge}</td>
                <td class="font-mono">${disposalValue}</td>
                <td>
                    <div class="flex gap-2">
                        <button class="btn btn-sm btn-circle btn-outline btn-info" onclick="viewDisposal(${disposal.id})">
                            <i class="bx bx-show"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
}

function getMethodBadge(method) {
    const methodConfig = {
        'decommission': { class: 'badge-warning', text: 'Decommission' },
        'disposal': { class: 'badge-error', text: 'Disposal' },
        'resale': { class: 'badge-success', text: 'Resale' }
    };
    
    const config = methodConfig[method] || { class: 'badge-neutral', text: method };
    return `<span class="badge ${config.class}">${config.text}</span>`;
}

// ==================== MODAL MANAGEMENT ====================
function openCreateDisposalModal() {
    document.getElementById('disposalForm').reset();
    document.getElementById('currentAssetValue').value = '';
    document.getElementById('disposalValueField').style.display = 'none';
    generateDisposalId();
    loadDisposableAssets();
    document.getElementById('disposalModal').showModal();
}

function generateDisposalId() {
    document.getElementById('disposalIdDisplay').value = 'Auto-generated upon save';
}

function toggleDisposalValue(method) {
    const disposalValueField = document.getElementById('disposalValueField');
    if (method === 'resale') {
        disposalValueField.style.display = 'block';
        disposalValueField.querySelector('input').required = true;
    } else {
        disposalValueField.style.display = 'none';
        disposalValueField.querySelector('input').required = false;
        disposalValueField.querySelector('input').value = '';
    }
}

async function updateAssetDetails(assetId) {
    if (!assetId) {
        document.getElementById('currentAssetValue').value = '';
        return;
    }
    
    const asset = disposableAssets.find(a => a.id == assetId);
    if (asset) {
        const currentValue = `₱${parseFloat(asset.acquisition_cost).toLocaleString('en-PH', { minimumFractionDigits: 2 })}`;
        document.getElementById('currentAssetValue').value = currentValue;
    }
}

async function viewDisposal(disposalId) {
    showLoading();
    
    try {
        const response = await fetch(`${API_BASE_URL}/disposals/${disposalId}`);
        const data = await response.json();
        
        if (data.success) {
            displayDisposalDetails(data.data);
            document.getElementById('viewDisposalModal').showModal();
        } else {
            showError('Disposal record not found');
        }
    } catch (error) {
        showError('Error loading disposal details: ' + error.message);
    } finally {
        hideLoading();
    }
}

function displayDisposalDetails(disposal) {
    const detailsDiv = document.getElementById('disposalDetails');
    const disposalDate = new Date(disposal.disposal_date).toLocaleDateString();
    const disposalValue = disposal.disposal_value ? 
        `₱${parseFloat(disposal.disposal_value).toLocaleString('en-PH', { minimumFractionDigits: 2 })}` : 
        'N/A';
    const acquisitionCost = `₱${parseFloat(disposal.asset.acquisition_cost).toLocaleString('en-PH', { minimumFractionDigits: 2 })}`;
    
    detailsDiv.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Disposal ID</span>
                </label>
                <div class="text-lg font-mono">${disposal.disposal_id}</div>
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Disposal Date</span>
                </label>
                <div class="text-lg">${disposalDate}</div>
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Asset</span>
                </label>
                <div class="text-lg">${disposal.asset.name}</div>
                <div class="text-sm text-gray-500">${disposal.asset.alms_id} - ${disposal.asset.serial_number}</div>
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Category</span>
                </label>
                <div class="text-lg">${disposal.asset.category.name}</div>
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Acquisition Cost</span>
                </label>
                <div class="text-lg font-mono">${acquisitionCost}</div>
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Disposal Method</span>
                </label>
                <div class="text-lg">${getMethodText(disposal.method)}</div>
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Disposal Value</span>
                </label>
                <div class="text-lg font-mono">${disposalValue}</div>
            </div>
        </div>
        
        <div class="form-control">
            <label class="label">
                <span class="label-text font-semibold">Reason</span>
            </label>
            <div class="p-3 bg-base-200 rounded-lg">${disposal.reason}</div>
        </div>
        
        ${disposal.compliance_notes ? `
        <div class="form-control">
            <label class="label">
                <span class="label-text font-semibold">Compliance Notes</span>
            </label>
            <div class="p-3 bg-base-200 rounded-lg">${disposal.compliance_notes}</div>
        </div>
        ` : ''}
    `;
}

function getMethodText(method) {
    const methodTexts = {
        'decommission': 'Decommission',
        'disposal': 'Disposal',
        'resale': 'Resale'
    };
    return methodTexts[method] || method;
}

// ==================== FORM HANDLING ====================
document.getElementById('disposalForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    showLoading();
    
    try {
        const response = await fetch(`${API_BASE_URL}/disposals`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(Object.fromEntries(formData))
        });
        
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('disposalModal').close();
            showSuccess('Asset disposal recorded successfully');
            loadDisposals();
            loadDisposalStats();
        } else {
            showError(data.message || 'Disposal recording failed');
        }
    } catch (error) {
        showError('Error recording disposal: ' + error.message);
    } finally {
        hideLoading();
    }
});

// ==================== SUPPORTING DATA ====================
async function loadDisposableAssets() {
    try {
        const response = await fetch(`${API_BASE_URL}/assets?status=active`);
        const data = await response.json();
        
        if (data.success) {
            disposableAssets = data.data.data;
            const assetSelect = document.querySelector('select[name="asset_id"]');
            assetSelect.innerHTML = '<option value="">Select Asset</option>';
            
            disposableAssets.forEach(asset => {
                assetSelect.innerHTML += `<option value="${asset.id}">${asset.alms_id} - ${asset.name} (${asset.current_branch.name})</option>`;
            });
        }
    } catch (error) {
        console.error('Error loading disposable assets:', error);
    }
}

// ==================== STATS ====================
async function loadDisposalStats() {
    try {
        const response = await fetch(`${API_BASE_URL}/disposals`);
        const data = await response.json();
        
        if (data.success) {
            const disposals = data.data.data;
            document.getElementById('totalDisposals').textContent = disposals.length;
            
            // Calculate stats by method
            const resaleCount = disposals.filter(d => d.method === 'resale').length;
            const decommissionCount = disposals.filter(d => d.method === 'decommission').length;
            const totalRecovered = disposals.reduce((sum, d) => sum + (parseFloat(d.disposal_value) || 0), 0);
            
            document.getElementById('resaleCount').textContent = resaleCount;
            document.getElementById('decommissionCount').textContent = decommissionCount;
            document.getElementById('totalRecovered').textContent = 
                `₱${totalRecovered.toLocaleString('en-PH', { minimumFractionDigits: 2 })}`;
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
    loadDisposals();
}

// ==================== UTILITY FUNCTIONS ====================
function setDefaultDate() {
    const today = new Date().toISOString().split('T')[0];
    document.querySelector('input[name="disposal_date"]').value = today;
}

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