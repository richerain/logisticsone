@extends('layouts.app')

@section('title', 'Asset Transfers - ALMS')

@section('content')
<div class="module-content bg-white rounded-xl p-6 shadow block">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Asset Transfers</h2>
        <button class="btn btn-primary" onclick="openCreateTransferModal()">
            <i class="bx bx-transfer mr-2"></i>Transfer Asset
        </button>
    </div>

    <!-- Stats Section -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="stat text-primary-content rounded-lg shadow-lg border-l-4 border-primary">
            <div class="stat-figure text-primary">
                <i class="bx bx-transfer-alt text-3xl"></i>
            </div>
            <div class="stat-title text-primary">Total Transfers</div>
            <div class="stat-value text-primary" id="totalTransfers">0</div>
        </div>
        
        <div class="stat text-info-content rounded-lg shadow-lg border-l-4 border-info">
            <div class="stat-figure text-info">
                <i class="bx bx-building text-3xl"></i>
            </div>
            <div class="stat-title text-info">Branches</div>
            <div class="stat-value text-info" id="totalBranches">0</div>
        </div>
        
        <div class="stat text-success-content rounded-lg shadow-lg border-l-4 border-success">
            <div class="stat-figure text-success">
                <i class="bx bx-cube text-3xl"></i>
            </div>
            <div class="stat-title text-success">Transferable Assets</div>
            <div class="stat-value text-success" id="transferableAssets">0</div>
        </div>
        
        <div class="stat text-warning-content rounded-lg shadow-lg border-l-4 border-warning">
            <div class="stat-figure text-warning">
                <i class="bx bx-calendar text-3xl"></i>
            </div>
            <div class="stat-title text-warning">This Month</div>
            <div class="stat-value text-warning" id="transfersThisMonth">0</div>
        </div>
    </div>

    <!-- Filters and Search Section -->
    <div class="flex flex-col lg:flex-row gap-4 mb-6 p-4 bg-base-200 rounded-lg">
        <div class="flex-1">
            <div class="form-control">
                <div class="input-group">
                    <input type="text" id="searchInput" placeholder="Search by transfer ID, asset..." class="input input-bordered w-full" onkeyup="loadTransfers()" />
                </div>
            </div>
        </div>
        <div class="flex gap-4 items-center">
            <div class="form-control">
                <label class="label">
                    <span class="label-text">From Date</span>
                </label>
                <input type="date" id="dateFromFilter" class="input input-bordered" placeholder="From Date" onchange="loadTransfers()">
            </div>
            <div class="form-control">
                <label class="label">
                    <span class="label-text">To Date</span>
                </label>
                <input type="date" id="dateToFilter" class="input input-bordered" placeholder="To Date" onchange="loadTransfers()">
            </div>
        </div>
    </div>

    <!-- Transfers Table -->
    <div class="overflow-x-auto bg-base-100 rounded-lg">
        <table class="table table-zebra w-full">
            <thead>
                <tr>
                    <th>Transfer ID</th>
                    <th>Asset</th>
                    <th>From Branch</th>
                    <th>To Branch</th>
                    <th>Transfer Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="transfersTableBody">
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

<!-- Create Transfer Modal -->
<dialog id="transferModal" class="modal">
    <div class="modal-box w-11/12 max-w-3xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg mb-4">Transfer Asset</h3>
        
        <form id="transferForm" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Transfer ID</span>
                    </label>
                    <input type="text" id="transferIdDisplay" class="input input-bordered" readonly disabled />
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Asset *</span>
                    </label>
                    <select name="asset_id" class="select select-bordered" required onchange="updateCurrentBranch(this.value)">
                        <option value="">Select Asset</option>
                    </select>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">Current Branch</span>
                    </label>
                    <input type="text" id="currentBranch" class="input input-bordered" readonly disabled />
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">From Branch *</span>
                    </label>
                    <select name="from_branch_id" class="select select-bordered" required>
                        <option value="">Select From Branch</option>
                    </select>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text">To Branch *</span>
                    </label>
                    <select name="to_branch_id" class="select select-bordered" required>
                        <option value="">Select To Branch</option>
                    </select>
                </div>
                
                <div class="form-control md:col-span-2">
                    <label class="label">
                        <span class="label-text">Transfer Date *</span>
                    </label>
                    <input type="date" name="transfer_date" class="input input-bordered" required />
                </div>
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text">Reason *</span>
                </label>
                <textarea name="reason" class="textarea textarea-bordered h-20" placeholder="Reason for transfer..." required></textarea>
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text">Additional Notes</span>
                </label>
                <textarea name="notes" class="textarea textarea-bordered h-20" placeholder="Any additional notes..."></textarea>
            </div>
            
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="document.getElementById('transferModal').close()">Cancel</button>
                <button type="submit" class="btn btn-primary">Transfer Asset</button>
            </div>
        </form>
    </div>
</dialog>

<!-- Transfer Details Modal -->
<dialog id="viewTransferModal" class="modal">
    <div class="modal-box w-11/12 max-w-2xl">
        <form method="dialog">
            <button class="btn btn-sm btn-circle btn-ghost absolute right-2 top-2">✕</button>
        </form>
        <h3 class="font-bold text-lg mb-4">Transfer Details</h3>
        
        <div id="transferDetails" class="space-y-4">
            <!-- Dynamic content will be loaded here -->
        </div>
        
        <div class="modal-action">
            <button type="button" class="btn btn-ghost" onclick="document.getElementById('viewTransferModal').close()">Close</button>
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
let assets = [];

// ==================== INITIALIZATION ====================
document.addEventListener('DOMContentLoaded', function() {
    loadTransfers();
    loadTransferStats();
    loadSupportingData();
});

// ==================== TRANSFER MANAGEMENT ====================
async function loadTransfers() {
    showLoading();
    
    const search = document.getElementById('searchInput').value;
    const dateFrom = document.getElementById('dateFromFilter').value;
    const dateTo = document.getElementById('dateToFilter').value;
    
    const params = new URLSearchParams({
        page: currentPage,
        search: search
    });
    
    if (dateFrom) params.append('date_from', dateFrom);
    if (dateTo) params.append('date_to', dateTo);
    
    try {
        const response = await fetch(`${API_BASE_URL}/asset-transfers?${params}`);
        const data = await response.json();
        
        if (data.success) {
            displayTransfers(data.data.data);
            setupPagination(data.data);
        } else {
            showError('Failed to load asset transfers');
        }
    } catch (error) {
        showError('Error loading transfers: ' + error.message);
    } finally {
        hideLoading();
    }
}

function displayTransfers(transfers) {
    const tbody = document.getElementById('transfersTableBody');
    tbody.innerHTML = '';
    
    if (transfers.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-8 text-gray-500">
                    <i class="bx bx-transfer-alt text-4xl mb-2"></i>
                    <p>No asset transfers found</p>
                </td>
            </tr>
        `;
        return;
    }
    
    transfers.forEach(transfer => {
        const transferDate = new Date(transfer.transfer_date).toLocaleDateString();
        
        tbody.innerHTML += `
            <tr>
                <td class="font-mono">${transfer.transfer_id}</td>
                <td>
                    <div class="font-semibold">${transfer.asset.name}</div>
                    <div class="text-sm text-gray-500">${transfer.asset.alms_id}</div>
                </td>
                <td>
                    <div class="font-semibold">${transfer.from_branch.name}</div>
                    <div class="text-sm text-gray-500">${transfer.from_branch.code}</div>
                </td>
                <td>
                    <div class="font-semibold">${transfer.to_branch.name}</div>
                    <div class="text-sm text-gray-500">${transfer.to_branch.code}</div>
                </td>
                <td>${transferDate}</td>
                <td>
                    <div class="flex gap-2">
                        <button class="btn btn-sm btn-circle btn-outline btn-info" onclick="viewTransfer(${transfer.id})">
                            <i class="bx bx-show"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    });
}

// ==================== MODAL MANAGEMENT ====================
function openCreateTransferModal() {
    document.getElementById('transferForm').reset();
    document.getElementById('currentBranch').value = '';
    generateTransferId();
    loadTransferableAssets();
    document.getElementById('transferModal').showModal();
}

function generateTransferId() {
    document.getElementById('transferIdDisplay').value = 'Auto-generated upon save';
}

async function updateCurrentBranch(assetId) {
    if (!assetId) {
        document.getElementById('currentBranch').value = '';
        return;
    }
    
    const asset = assets.find(a => a.id == assetId);
    if (asset) {
        document.getElementById('currentBranch').value = asset.current_branch.name;
        document.querySelector('select[name="from_branch_id"]').value = asset.current_branch_id;
    }
}

async function viewTransfer(transferId) {
    showLoading();
    
    try {
        const response = await fetch(`${API_BASE_URL}/asset-transfers/${transferId}`);
        const data = await response.json();
        
        if (data.success) {
            displayTransferDetails(data.data);
            document.getElementById('viewTransferModal').showModal();
        } else {
            showError('Transfer not found');
        }
    } catch (error) {
        showError('Error loading transfer details: ' + error.message);
    } finally {
        hideLoading();
    }
}

function displayTransferDetails(transfer) {
    const detailsDiv = document.getElementById('transferDetails');
    const transferDate = new Date(transfer.transfer_date).toLocaleDateString();
    const acquisitionCost = `₱${parseFloat(transfer.asset.acquisition_cost).toLocaleString('en-PH', { minimumFractionDigits: 2 })}`;
    
    detailsDiv.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Transfer ID</span>
                </label>
                <div class="text-lg font-mono">${transfer.transfer_id}</div>
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Transfer Date</span>
                </label>
                <div class="text-lg">${transferDate}</div>
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Asset</span>
                </label>
                <div class="text-lg">${transfer.asset.name}</div>
                <div class="text-sm text-gray-500">${transfer.asset.alms_id} - ${transfer.asset.serial_number}</div>
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">Category</span>
                </label>
                <div class="text-lg">${transfer.asset.category.name}</div>
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
                <div class="text-lg">${transfer.asset.status}</div>
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">From Branch</span>
                </label>
                <div class="text-lg">${transfer.from_branch.name}</div>
                <div class="text-sm text-gray-500">${transfer.from_branch.code}</div>
            </div>
            
            <div class="form-control">
                <label class="label">
                    <span class="label-text font-semibold">To Branch</span>
                </label>
                <div class="text-lg">${transfer.to_branch.name}</div>
                <div class="text-sm text-gray-500">${transfer.to_branch.code}</div>
            </div>
        </div>
        
        <div class="form-control">
            <label class="label">
                <span class="label-text font-semibold">Reason</span>
            </label>
            <div class="p-3 bg-base-200 rounded-lg">${transfer.reason}</div>
        </div>
        
        ${transfer.notes ? `
        <div class="form-control">
            <label class="label">
                <span class="label-text font-semibold">Additional Notes</span>
            </label>
            <div class="p-3 bg-base-200 rounded-lg">${transfer.notes}</div>
        </div>
        ` : ''}
    `;
}

// ==================== FORM HANDLING ====================
document.getElementById('transferForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    // Validate that from and to branches are different
    if (formData.get('from_branch_id') === formData.get('to_branch_id')) {
        showError('From and To branches must be different');
        return;
    }
    
    showLoading();
    
    try {
        const response = await fetch(`${API_BASE_URL}/asset-transfers`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(Object.fromEntries(formData))
        });
        
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('transferModal').close();
            showSuccess('Asset transferred successfully');
            loadTransfers();
            loadTransferStats();
        } else {
            showError(data.message || 'Transfer failed');
        }
    } catch (error) {
        showError('Error transferring asset: ' + error.message);
    } finally {
        hideLoading();
    }
});

// ==================== SUPPORTING DATA ====================
async function loadSupportingData() {
    try {
        const [branchesRes] = await Promise.all([
            fetch(`${API_BASE_URL}/branches`)
        ]);
        
        const branchesData = await branchesRes.json();
        
        if (branchesData.success) {
            const fromBranchSelect = document.querySelector('select[name="from_branch_id"]');
            const toBranchSelect = document.querySelector('select[name="to_branch_id"]');
            
            fromBranchSelect.innerHTML = '<option value="">Select From Branch</option>';
            toBranchSelect.innerHTML = '<option value="">Select To Branch</option>';
            
            branchesData.data.forEach(branch => {
                fromBranchSelect.innerHTML += `<option value="${branch.id}">${branch.name} (${branch.code})</option>`;
                toBranchSelect.innerHTML += `<option value="${branch.id}">${branch.name} (${branch.code})</option>`;
            });
        }
    } catch (error) {
        console.error('Error loading supporting data:', error);
    }
}

async function loadTransferableAssets() {
    try {
        const response = await fetch(`${API_BASE_URL}/assets?status=active`);
        const data = await response.json();
        
        if (data.success) {
            assets = data.data.data;
            const assetSelect = document.querySelector('select[name="asset_id"]');
            assetSelect.innerHTML = '<option value="">Select Asset</option>';
            
            assets.forEach(asset => {
                assetSelect.innerHTML += `<option value="${asset.id}">${asset.alms_id} - ${asset.name} (${asset.current_branch.name})</option>`;
            });
        }
    } catch (error) {
        console.error('Error loading transferable assets:', error);
    }
}

// ==================== STATS ====================
async function loadTransferStats() {
    try {
        const [transfersRes, branchesRes, assetsRes] = await Promise.all([
            fetch(`${API_BASE_URL}/asset-transfers`),
            fetch(`${API_BASE_URL}/branches`),
            fetch(`${API_BASE_URL}/assets?status=active`)
        ]);
        
        const transfersData = await transfersRes.json();
        const branchesData = await branchesRes.json();
        const assetsData = await assetsRes.json();
        
        if (transfersData.success) {
            document.getElementById('totalTransfers').textContent = transfersData.data.total || 0;
            
            // Calculate transfers this month
            const currentMonth = new Date().getMonth();
            const currentYear = new Date().getFullYear();
            const transfersThisMonth = transfersData.data.data.filter(transfer => {
                const transferDate = new Date(transfer.transfer_date);
                return transferDate.getMonth() === currentMonth && transferDate.getFullYear() === currentYear;
            }).length;
            
            document.getElementById('transfersThisMonth').textContent = transfersThisMonth;
        }
        
        if (branchesData.success) {
            document.getElementById('totalBranches').textContent = branchesData.data.length;
        }
        
        if (assetsData.success) {
            document.getElementById('transferableAssets').textContent = assetsData.data.total || 0;
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
    loadTransfers();
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