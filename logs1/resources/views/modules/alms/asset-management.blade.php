@extends('layouts.app')

@section('title', 'Asset Management - ALMS')

@section('content')
    <div class="module-content bg-white rounded-xl p-6 shadow block">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Asset Management</h2>
            <button class="btn btn-primary" id="addAssetBtn">
                <i class="bx bx-plus mr-2"></i>Add New Asset
            </button>
        </div>

        <!-- Stats Section -->
        <div class="grid grid-cols-1 md:grid-cols-6 gap-4 mb-6">
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-primary">
                <div class="stat-figure text-primary">
                    <i class="bx bx-cube text-3xl"></i>
                </div>
                <div class="stat-title">Total Assets</div>
                <div class="stat-value text-primary text-lg" id="total-assets">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-success">
                <div class="stat-figure text-success">
                    <i class="bx bx-check-circle text-3xl"></i>
                </div>
                <div class="stat-title">Active</div>
                <div class="stat-value text-success text-lg" id="active-assets">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-warning">
                <div class="stat-figure text-warning">
                    <i class="bx bx-time text-3xl"></i>
                </div>
                <div class="stat-title">Issued</div>
                <div class="stat-value text-warning text-lg" id="issued-assets">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-info">
                <div class="stat-figure text-info">
                    <i class="bx bx-wrench text-3xl"></i>
                </div>
                <div class="stat-title">Under Maintenance</div>
                <div class="stat-value text-info text-lg" id="maintenance-assets">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-error">
                <div class="stat-figure text-error">
                    <i class="bx bx-x-circle text-3xl"></i>
                </div>
                <div class="stat-title">Rejected</div>
                <div class="stat-value text-error text-lg" id="rejected-assets">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-secondary">
                <div class="stat-figure text-secondary">
                    <i class="bx bx-refresh text-3xl"></i>
                </div>
                <div class="stat-title">Replacement</div>
                <div class="stat-value text-secondary text-lg" id="replacement-assets">0</div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="flex gap-4 mb-6">
            <div class="form-control flex-1">
                <input type="text" placeholder="Search assets..." class="input input-bordered w-full" id="searchAssets">
            </div>
            <select class="select select-bordered" id="statusFilter">
                <option value="">All Status</option>
                <option value="Active">Active</option>
                <option value="Issued">Issued</option>
                <option value="Under Maintenance">Under Maintenance</option>
                <option value="Re-Schedule">Re-Schedule</option>
                <option value="Replacement">Replacement</option>
                <option value="Rejected">Rejected</option>
            </select>
            <select class="select select-bordered" id="typeFilter">
                <option value="">All Types</option>
                <option value="Document">Document</option>
                <option value="Supplies">Supplies</option>
                <option value="Equipment">Equipment</option>
                <option value="Furniture">Furniture</option>
            </select>
        </div>

        <!-- Assets Table -->
        <div class="overflow-x-auto bg-base-100 rounded-lg">
            <table class="table table-zebra w-full">
                <thead>
                    <tr class="bg-gray-900 text-white">
                        <th>Asset ID</th>
                        <th>Asset Name</th>
                        <th>Asset Type</th>
                        <th>Status</th>
                        <th>Warranty Expiry</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="assets-table-body">
                    <tr>
                        <td colspan="8" class="text-center py-8">
                            <div class="loading loading-spinner loading-lg"></div>
                            <p class="text-gray-500 mt-2">Loading assets...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add/Edit Asset Modal -->
    <div id="assetModal" class="modal modal-lg">
        <div class="modal-box max-w-4xl p-0 overflow-visible">
            <div class="flex justify-between items-center bg-green-700 p-4 rounded-t-lg">
                <h3 class="font-bold text-white text-lg" id="assetModalTitle">Add New Asset</h3>
                <button class="btn btn-sm btn-circle btn-ghost hover:bg-white/20 text-white" id="closeAssetModalX">✕</button>
            </div>
            <div class="p-4 max-h-[70vh] overflow-y-auto">
                <form id="assetForm" class="space-y-4">
                    @csrf
                    <input type="hidden" id="assetId" name="asset_id">
                    
                    <!-- Auto-generated Asset ID -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Asset ID</span>
                        </label>
                        <input type="text" id="assetID" class="input input-bordered input-sm w-full bg-gray-100" 
                               readonly placeholder="Auto-generated">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Asset Name *</span>
                            </label>
                            <input type="text" id="assetName" name="asset_name" class="input input-bordered input-sm w-full" 
                                   placeholder="Enter asset name" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Asset Type *</span>
                            </label>
                            <select id="assetType" name="asset_type" class="select select-bordered select-sm w-full" required>
                                <option value="">Select Asset Type</option>
                                <option value="Document">Document</option>
                                <option value="Supplies">Supplies</option>
                                <option value="Equipment">Equipment</option>
                                <option value="Furniture">Furniture</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Assigned Location *</span>
                        </label>
                        <input type="text" id="assignedLocation" name="assigned_location" class="input input-bordered input-sm w-full" 
                               placeholder="Enter branch or department" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Deployment Date *</span>
                            </label>
                            <input type="date" id="deploymentDate" name="deployment_date" class="input input-bordered input-sm w-full" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Next Service Date</span>
                            </label>
                            <input type="date" id="nextServiceDate" name="next_service_date" class="input input-bordered input-sm w-full">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Warranty Start Date</span>
                            </label>
                            <input type="date" id="warrantyStart" name="warranty_start" class="input input-bordered input-sm w-full">
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Warranty End Date</span>
                            </label>
                            <input type="date" id="warrantyEnd" name="warranty_end" class="input input-bordered input-sm w-full">
                        </div>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Status</span>
                        </label>
                        <select id="assetStatus" name="status" class="select select-bordered select-sm w-full">
                            <option value="Active">Active</option>
                            <option value="Issued">Issued</option>
                            <option value="Under Maintenance">Under Maintenance</option>
                            <option value="Re-Schedule">Re-Schedule</option>
                            <option value="Replacement">Replacement</option>
                            <option value="Rejected">Rejected</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Description</span>
                        </label>
                        <textarea id="description" name="description" class="textarea textarea-bordered textarea-sm h-16" 
                                  placeholder="Additional description..."></textarea>
                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-action flex justify-end space-x-3 pt-4 border-t">
                        <button type="button" class="btn btn-ghost btn-sm hover:bg-gray-100 transition-colors px-4" id="closeAssetModal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm bg-gradient-to-r from-primary to-primary/90 hover:from-primary/90 hover:to-primary transition-all duration-300 shadow-lg px-4" id="assetSubmitBtn">
                            <i class="bx bx-save mr-1"></i><span id="assetModalSubmitText">Save Asset</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Asset Modal -->
    <div id="viewAssetModal" class="modal modal-lg">
        <div class="modal-box max-w-4xl p-0 overflow-visible">
            <div class="flex justify-between items-center bg-green-700 p-4 rounded-t-lg">
                <h3 class="font-bold text-white text-lg">Asset Details</h3>
                <button class="btn btn-sm btn-circle btn-ghost hover:bg-white/20 text-white" id="closeViewAssetModalX">✕</button>
            </div>
            <div class="p-4 max-h-[70vh] overflow-y-auto">
                <div class="space-y-4" id="assetDetails">
                    <!-- Asset details will be populated here -->
                </div>
                <div class="modal-action flex justify-end pt-4 border-t">
                    <button type="button" class="btn btn-ghost btn-sm hover:bg-gray-100 transition-colors px-4" id="closeViewAssetModal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Modal -->
    <div id="loadingModal" class="modal">
        <div class="modal-box max-w-sm text-center p-4">
            <div class="loading loading-spinner loading-lg text-primary mb-2"></div>
            <h3 class="font-bold text-sm mb-1" id="loadingTitle">Processing...</h3>
        </div>
    </div>

<script>
    let assets = [];

    // ==================== CONFIGURATION ====================
    const API_BASE_URL = 'http://localhost:8001/api/alms';

    // Utility functions
    function formatDate(dateString) {
        if (!dateString) return 'N/A';
        return new Date(dateString).toLocaleDateString('en-US', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit'
        });
    }

    function formatWarrantyPeriod(startDate, endDate) {
        if (!startDate || !endDate) return 'N/A';
        return `${formatDate(startDate)} to ${formatDate(endDate)}`;
    }

    function getStatusBadge(status) {
        const statusClasses = {
            'Active': 'bg-green-600 uppercase',
            'Issued': 'bg-blue-600 uppercase',
            'Under Maintenance': 'bg-yellow-600 uppercase',
            'Re-Schedule': 'bg-orange-500 uppercase',
            'Replacement': 'bg-purple-600 uppercase',
            'Rejected': 'bg-red-600 uppercase'
        };
        
        return `<span class="badge text-white font-bold tracking-wider text-xs px-3 py-2 ${statusClasses[status] || 'bg-gray-400'} border-0">
            ${status}
        </span>`;
    }

    // Show loading modal
    function showLoadingModal(title = 'Processing...') {
        document.getElementById('loadingTitle').textContent = title;
        document.getElementById('loadingModal').classList.add('modal-open');
    }

    // Hide loading modal
    function hideLoadingModal() {
        document.getElementById('loadingModal').classList.remove('modal-open');
    }

    // Show success toast
    function showSuccessToast(message) {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        Toast.fire({
            icon: 'success',
            title: message
        });
    }

    // Load data on page load
    document.addEventListener('DOMContentLoaded', function() {
        initializeEventListeners();
        loadAssets();
    });

    function initializeEventListeners() {
        // Add asset button
        document.getElementById('addAssetBtn').addEventListener('click', openAddAssetModal);

        // Close modal buttons
        document.getElementById('closeAssetModal').addEventListener('click', closeAssetModal);
        document.getElementById('closeAssetModalX').addEventListener('click', closeAssetModal);
        document.getElementById('closeViewAssetModal').addEventListener('click', closeViewAssetModal);
        document.getElementById('closeViewAssetModalX').addEventListener('click', closeViewAssetModal);

        // Form submission
        document.getElementById('assetForm').addEventListener('submit', handleAssetSubmit);

        // Search and filter
        document.getElementById('searchAssets').addEventListener('input', filterAssets);
        document.getElementById('statusFilter').addEventListener('change', filterAssets);
        document.getElementById('typeFilter').addEventListener('change', filterAssets);

        // Set default dates
        document.getElementById('deploymentDate').value = new Date().toISOString().split('T')[0];
    }

    async function loadAssets() {
        try {
            showAssetsLoadingState();
            const response = await fetch(`${API_BASE_URL}/assets`);
            
            if (!response.ok) {
                const errorData = await response.json().catch(() => ({ message: 'Unknown error' }));
                throw new Error(`HTTP error! status: ${response.status}, message: ${errorData.message}`);
            }
            
            const result = await response.json();
            
            if (result.success) {
                assets = result.data || [];
                renderAssets(assets);
                updateStats(assets);
            } else {
                throw new Error(result.message || 'Failed to load assets');
            }
        } catch (error) {
            console.error('Error loading assets:', error);
            showAssetsErrorState('Failed to load assets: ' + error.message);
        }
    }

    function showAssetsLoadingState() {
        const tbody = document.getElementById('assets-table-body');
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-8">
                    <div class="loading loading-spinner loading-lg"></div>
                    <p class="text-gray-500 mt-2">Loading assets...</p>
                </td>
            </tr>
        `;
    }

    function showAssetsErrorState(message) {
        const tbody = document.getElementById('assets-table-body');
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-8">
                    <i class="bx bx-error text-4xl text-red-400 mb-2"></i>
                    <p class="text-red-500">${message}</p>
                    <button class="btn btn-sm btn-outline mt-2" onclick="loadAssets()">Retry</button>
                </td>
            </tr>
        `;
    }

    function renderAssets(assetsData) {
        const tbody = document.getElementById('assets-table-body');
        
        if (assetsData.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center py-8">
                        <i class="bx bx-cube text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500">No assets found</p>
                        <button class="btn btn-sm btn-primary mt-2" id="addFirstAssetBtn">Add First Asset</button>
                    </td>
                </tr>
            `;
            document.getElementById('addFirstAssetBtn')?.addEventListener('click', openAddAssetModal);
            return;
        }

        tbody.innerHTML = assetsData.map(asset => {
            return `
            <tr>
                <td class="font-mono font-semibold text-sm">${asset.asset_id}</td>
                <td class="text-sm">${asset.asset_name}</td>
                <td class="text-sm"> ${asset.asset_type}</td>
                <td>${getStatusBadge(asset.status)}</td>
                <td class="text-sm">${formatWarrantyPeriod(asset.warranty_start, asset.warranty_end)}</td>
                <td>
                    <div class="flex space-x-1">
                        <button title="View" class="btn btn-sm btn-circle btn-info view-asset-btn" data-asset-id="${asset.id}">
                            <i class="bx bx-show-alt text-sm"></i>
                        </button>
                        <button title="Edit" class="btn btn-sm btn-circle btn-warning edit-asset-btn" data-asset-id="${asset.id}">
                            <i class="bx bx-edit text-sm"></i>
                        </button>
                        <button title="Delete" class="btn btn-sm btn-circle btn-error delete-asset-btn" data-asset-id="${asset.id}">
                            <i class="bx bx-trash text-sm"></i>
                        </button>
                    </div>
                </td>
            </tr>
            `;
        }).join('');

        // Add event listeners to dynamically created buttons
        addDynamicEventListeners();
    }

    function addDynamicEventListeners() {
        document.querySelectorAll('.view-asset-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const assetId = this.getAttribute('data-asset-id');
                viewAsset(parseInt(assetId));
            });
        });

        document.querySelectorAll('.edit-asset-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const assetId = this.getAttribute('data-asset-id');
                editAsset(parseInt(assetId));
            });
        });

        document.querySelectorAll('.delete-asset-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const assetId = this.getAttribute('data-asset-id');
                deleteAsset(parseInt(assetId));
            });
        });
    }

    function updateStats(assetsData) {
        document.getElementById('total-assets').textContent = assetsData.length;
        document.getElementById('active-assets').textContent = 
            assetsData.filter(a => a.status === 'Active').length;
        document.getElementById('issued-assets').textContent = 
            assetsData.filter(a => a.status === 'Issued').length;
        document.getElementById('maintenance-assets').textContent = 
            assetsData.filter(a => a.status === 'Under Maintenance').length;
        document.getElementById('rejected-assets').textContent = 
            assetsData.filter(a => a.status === 'Rejected').length;
        document.getElementById('replacement-assets').textContent = 
            assetsData.filter(a => a.status === 'Replacement').length;
    }

    function filterAssets() {
        const searchTerm = document.getElementById('searchAssets').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        const typeFilter = document.getElementById('typeFilter').value;
        
        const filtered = assets.filter(asset => {
            const matchesSearch = searchTerm === '' || 
                asset.asset_id.toLowerCase().includes(searchTerm) ||
                asset.asset_name.toLowerCase().includes(searchTerm) ||
                asset.assigned_location.toLowerCase().includes(searchTerm);
            
            const matchesStatus = statusFilter === '' || asset.status === statusFilter;
            const matchesType = typeFilter === '' || asset.asset_type === typeFilter;
            
            return matchesSearch && matchesStatus && matchesType;
        });
        
        renderAssets(filtered);
        updateStats(filtered);
    }

    // Modal Functions
    function openAddAssetModal() {
        document.getElementById('assetModalTitle').textContent = 'Add New Asset';
        document.getElementById('assetModalSubmitText').textContent = 'Save Asset';
        document.getElementById('assetForm').reset();
        document.getElementById('assetId').value = '';
        document.getElementById('assetStatus').value = 'Active';
        document.getElementById('deploymentDate').value = new Date().toISOString().split('T')[0];
        
        // Clear auto-generated ID field for new assets
        document.getElementById('assetID').value = 'Auto-generated';
        
        document.getElementById('assetModal').classList.add('modal-open');
    }

    function closeAssetModal() {
        document.getElementById('assetModal').classList.remove('modal-open');
        document.getElementById('assetForm').reset();
    }

    function openViewAssetModal() {
        document.getElementById('viewAssetModal').classList.add('modal-open');
    }

    function closeViewAssetModal() {
        document.getElementById('viewAssetModal').classList.remove('modal-open');
    }

    // Asset Actions
    function viewAsset(assetId) {
        const asset = assets.find(a => a.id === assetId);
        if (!asset) return;

        const assetDetails = `
            <div class="space-y-4">
                <!-- Basic Information -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <strong class="text-gray-700 text-xs">Asset ID:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1 font-mono">${asset.asset_id}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Asset Name:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${asset.asset_name}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Asset Type:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1"> ${asset.asset_type} </p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Assigned Location:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${asset.assigned_location}</p>
                    </div>
                </div>

                <!-- Dates Information -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <strong class="text-gray-700 text-xs">Deployment Date:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${formatDate(asset.deployment_date)}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Next Service Date:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${formatDate(asset.next_service_date)}</p>
                    </div>
                </div>

                <!-- Warranty Information -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <strong class="text-gray-700 text-xs">Warranty Period:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${formatWarrantyPeriod(asset.warranty_start, asset.warranty_end)}</p>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <strong class="text-gray-700 text-xs">Status:</strong>
                    <p class="mt-1 p-2">${getStatusBadge(asset.status)}</p>
                </div>

                <!-- Description -->
                ${asset.description ? `
                <div>
                    <strong class="text-gray-700 text-xs">Description:</strong>
                    <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${asset.description}</p>
                </div>
                ` : ''}

                <!-- Timestamps -->
                <div class="grid grid-cols-2 gap-4">
                    ${asset.created_at ? `
                    <div>
                        <strong class="text-gray-700 text-xs">Created:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${new Date(asset.created_at).toLocaleString()}</p>
                    </div>
                    ` : ''}
                    ${asset.updated_at ? `
                    <div>
                        <strong class="text-gray-700 text-xs">Last Updated:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${new Date(asset.updated_at).toLocaleString()}</p>
                    </div>
                    ` : ''}
                </div>
            </div>
        `;

        document.getElementById('assetDetails').innerHTML = assetDetails;
        openViewAssetModal();
    }

    function editAsset(assetId) {
        const asset = assets.find(a => a.id === assetId);
        if (!asset) return;

        document.getElementById('assetModalTitle').textContent = 'Edit Asset';
        document.getElementById('assetModalSubmitText').textContent = 'Update Asset';
        
        document.getElementById('assetId').value = asset.id;
        document.getElementById('assetID').value = asset.asset_id;
        document.getElementById('assetName').value = asset.asset_name;
        document.getElementById('assetType').value = asset.asset_type;
        document.getElementById('assignedLocation').value = asset.assigned_location;
        document.getElementById('deploymentDate').value = asset.deployment_date;
        document.getElementById('nextServiceDate').value = asset.next_service_date || '';
        document.getElementById('warrantyStart').value = asset.warranty_start || '';
        document.getElementById('warrantyEnd').value = asset.warranty_end || '';
        document.getElementById('assetStatus').value = asset.status;
        document.getElementById('description').value = asset.description || '';

        document.getElementById('assetModal').classList.add('modal-open');
    }

    async function handleAssetSubmit(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const assetId = document.getElementById('assetId').value;
        const isEdit = !!assetId;

        const assetData = {
            asset_name: formData.get('asset_name'),
            asset_type: formData.get('asset_type'),
            assigned_location: formData.get('assigned_location'),
            deployment_date: formData.get('deployment_date'),
            next_service_date: formData.get('next_service_date') || null,
            warranty_start: formData.get('warranty_start') || null,
            warranty_end: formData.get('warranty_end') || null,
            status: formData.get('status'),
            description: formData.get('description')
        };
        
        try {
            showLoadingModal(
                isEdit ? 'Updating Asset...' : 'Creating Asset...'
            );

            let response;
            if (isEdit) {
                response = await fetch(`${API_BASE_URL}/assets/${assetId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(assetData)
                });
            } else {
                response = await fetch(`${API_BASE_URL}/assets`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(assetData)
                });
            }

            const result = await response.json();

            if (response.ok && result.success) {
                hideLoadingModal();
                closeAssetModal();
                
                // Wait for data to reload before showing success message
                await loadAssets();
                
                showSuccessToast(
                    isEdit ? 'Asset updated successfully!' : 'Asset created successfully!'
                );
            } else {
                throw new Error(result.message || `Failed to ${isEdit ? 'update' : 'create'} asset`);
            }
        } catch (error) {
            hideLoadingModal();
            Swal.fire('Error', `Failed to ${isEdit ? 'update' : 'create'} asset: ` + error.message, 'error');
        }
    }

    async function deleteAsset(assetId) {
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: "This will permanently delete the asset!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        });

        if (result.isConfirmed) {
            try {
                showLoadingModal('Deleting Asset...');

                const response = await fetch(`${API_BASE_URL}/assets/${assetId}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    hideLoadingModal();
                    
                    // Wait for data to reload before showing success message
                    await loadAssets();
                    
                    showSuccessToast('Asset deleted successfully!');
                } else {
                    throw new Error(result.message || 'Failed to delete asset');
                }
            } catch (error) {
                hideLoadingModal();
                Swal.fire('Error', 'Failed to delete asset: ' + error.message, 'error');
            }
        }
    }
</script>

<style>
    .modal-box {
        max-height: 85vh;
    }
    input:read-only {
        background-color: #f3f4f6;
        cursor: not-allowed;
    }
    .modal-box .max-h-\[70vh\] {
        max-height: 70vh;
    }
    .table td {
        white-space: nowrap;
    }
</style>
@endsection