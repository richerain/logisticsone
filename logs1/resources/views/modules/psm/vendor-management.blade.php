@extends('layouts.app')

@section('title', 'Vendor Management')

@section('content')
    <div class="module-content bg-white rounded-xl p-6 shadow block">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Vendor Management</h2>
            <button class="btn btn-primary" onclick="openAddVendorModal()">
                <i class="bx bx-plus mr-2"></i>Add Vendor
            </button>
        </div>

        <div class="bg-base-100 rounded-lg p-1 mb-6">
            <div class="gap-4 grid grid-cols-2">
                <div class="stat shadow-lg border-l-4 border-primary rounded-lg">
                    <div class="stat-figure text-primary">
                        <i class="bx bx-store text-3xl"></i>
                    </div>
                    <div class="stat-title">Total Vendors</div>
                    <div class="stat-value text-primary" id="total-vendors">0</div>
                </div>
                <div class="stat shadow-lg border-l-4 border-success rounded-lg">
                    <div class="stat-figure text-success">
                        <i class="bx bx-check-circle text-3xl"></i>
                    </div>
                    <div class="stat-title">Active Vendors</div>
                    <div class="stat-value text-success" id="active-vendors">0</div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto bg-base-100 rounded-lg">
            <table class="table table-zebra w-full">
                <thead>
                    <tr class="bg-base-200">
                        <th>Vendor Name</th>
                        <th>Email</th>
                        <th>Contact</th>
                        <th>Rating</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="vendors-table-body">
                    <tr>
                        <td colspan="6" class="text-center py-8">
                            <div class="loading loading-spinner loading-lg"></div>
                            <p class="text-gray-500 mt-2">Loading vendors...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add Vendor Modal -->
    <div id="addVendorModal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Add New Vendor</h3>
            <form id="addVendorForm" class="space-y-4">
                @csrf
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Vendor Name *</span>
                    </label>
                    <input type="text" name="ven_name" class="input input-bordered w-full" required>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Email *</span>
                    </label>
                    <input type="email" name="ven_email" class="input input-bordered w-full" required>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Contact</span>
                    </label>
                    <input type="text" name="ven_contacts" class="input input-bordered w-full" placeholder="Phone number">
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Address</span>
                    </label>
                    <textarea name="ven_address" class="textarea textarea-bordered h-24" placeholder="Vendor address"></textarea>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Rating</span>
                    </label>
                    <input type="number" name="ven_rating" step="0.1" min="0" max="5" class="input input-bordered w-full" value="0.0">
                </div>
                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" onclick="closeAddVendorModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save mr-2"></i>Save Vendor
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Edit Vendor Modal -->
    <div id="editVendorModal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Edit Vendor</h3>
            <form id="editVendorForm" class="space-y-4">
                @csrf
                @method('PUT')
                <input type="hidden" id="editVendorId" name="ven_id">
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Vendor Name *</span>
                    </label>
                    <input type="text" id="editVendorName" name="ven_name" class="input input-bordered w-full" required>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Email *</span>
                    </label>
                    <input type="email" id="editVendorEmail" name="ven_email" class="input input-bordered w-full" required>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Contact</span>
                    </label>
                    <input type="text" id="editVendorContacts" name="ven_contacts" class="input input-bordered w-full" placeholder="Phone number">
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Address</span>
                    </label>
                    <textarea id="editVendorAddress" name="ven_address" class="textarea textarea-bordered h-24" placeholder="Vendor address"></textarea>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Rating</span>
                    </label>
                    <input type="number" id="editVendorRating" name="ven_rating" step="0.1" min="0" max="5" class="input input-bordered w-full">
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Status</span>
                    </label>
                    <select id="editVendorStatus" name="ven_status" class="select select-bordered w-full">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" onclick="closeEditVendorModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-save mr-2"></i>Update Vendor
                    </button>
                </div>
            </form>
        </div>
    </div>

<script>
    let vendors = [];

    // ==================== CONFIGURATION ====================
    const API_BASE_URL = 'http://localhost:8001/api/psm';

    // Utility function to safely convert to number
    function safeNumber(value, defaultValue = 0) {
        if (value === null || value === undefined) return defaultValue;
        const num = parseFloat(value);
        return isNaN(num) ? defaultValue : num;
    }

    // Utility function to safely format rating
    function formatRating(rating) {
        const num = safeNumber(rating, 0);
        return num.toFixed(1);
    }

    // Function to generate accurate star rating HTML
    function generateStarRating(rating) {
        const numRating = safeNumber(rating, 0);
        let starsHTML = '';
        
        // For DaisyUI rating, we need to set the value attribute on the container
        // and use the correct approach for displaying stars
        for (let i = 1; i <= 5; i++) {
            const isChecked = i <= numRating;
            starsHTML += `<input type="radio" name="rating-${vendor.ven_id}" class="mask mask-star-2 bg-orange-400" ${isChecked ? 'checked' : ''} />`;
        }
        
        return starsHTML;
    }

    // Alternative approach using DaisyUI's rating system
    function generateDaisyUIRating(rating) {
        const numRating = Math.round(safeNumber(rating, 0)); // Round to nearest whole number for star display
        let starsHTML = '';
        
        for (let i = 1; i <= 5; i++) {
            if (i <= numRating) {
                starsHTML += `<input type="radio" name="rating-${vendor.ven_id}" class="mask mask-star-2 bg-orange-400" checked disabled />`;
            } else {
                starsHTML += `<input type="radio" name="rating-${vendor.ven_id}" class="mask mask-star-2 bg-orange-400" disabled />`;
            }
        }
        
        return starsHTML;
    }

    // Simple star display without DaisyUI complications
    function generateSimpleStarRating(rating) {
        const numRating = safeNumber(rating, 0);
        let starsHTML = '';
        
        for (let i = 1; i <= 5; i++) {
            if (i <= numRating) {
                starsHTML += `<span class="text-orange-400">★</span>`;
            } else {
                starsHTML += `<span class="text-gray-300">★</span>`;
            }
        }
        
        return starsHTML;
    }

    // Sort vendors by creation date (newest first)
    function sortVendorsByDate(vendorsArray) {
        return vendorsArray.sort((a, b) => {
            const dateA = new Date(a.created_at || 0);
            const dateB = new Date(b.created_at || 0);
            return dateB - dateA; // Descending order (newest first)
        });
    }

    // Load vendors on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadVendors();
    });

    async function loadVendors() {
        try {
            showLoadingState();
            const response = await fetch(`${API_BASE_URL}/vendors`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const result = await response.json();
            
            if (result.success) {
                // Sort vendors by creation date (newest first)
                vendors = sortVendorsByDate(result.data || []);
                renderVendors();
                updateStats();
            } else {
                throw new Error(result.message || 'Failed to load vendors');
            }
        } catch (error) {
            console.error('Error loading vendors:', error);
            showErrorState('Failed to load vendors: ' + error.message);
        }
    }

    function showLoadingState() {
        const tbody = document.getElementById('vendors-table-body');
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-8">
                    <div class="loading loading-spinner loading-lg"></div>
                    <p class="text-gray-500 mt-2">Loading vendors...</p>
                </td>
            </tr>
        `;
    }

    function showErrorState(message) {
        const tbody = document.getElementById('vendors-table-body');
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-8">
                    <i class="bx bx-error text-4xl text-red-400 mb-2"></i>
                    <p class="text-red-500">${message}</p>
                    <button class="btn btn-sm btn-outline mt-2" onclick="loadVendors()">Retry</button>
                </td>
            </tr>
        `;
    }

    function renderVendors() {
        const tbody = document.getElementById('vendors-table-body');
        
        if (vendors.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-8">
                        <i class="bx bx-package text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500">No vendors found</p>
                        <button class="btn btn-sm btn-primary mt-2" onclick="openAddVendorModal()">Add First Vendor</button>
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = vendors.map(vendor => {
            const rating = safeNumber(vendor.ven_rating, 0);
            const displayRating = formatRating(rating);
            // Use the simple star rating to avoid DaisyUI complications
            const starRatingHTML = generateSimpleStarRating(rating);
            
            return `
            <tr>
                <td>
                    <div class="font-semibold">${vendor.ven_name || 'N/A'}</div>
                    ${vendor.created_at ? `
                    <div class="text-xs text-gray-500 mt-1">
                        Added: ${new Date(vendor.created_at).toLocaleDateString()}
                    </div>
                    ` : ''}
                </td>
                <td>${vendor.ven_email || 'N/A'}</td>
                <td>${vendor.ven_contacts || 'N/A'}</td>
                <td>
                    <div class="flex items-center">
                        <div class="text-lg rating rating-sm">
                            ${starRatingHTML}
                        </div>
                        <span class="ml-2 text-sm font-medium">${displayRating}</span>
                    </div>
                </td>
                <td>
                    <span class="badge ${vendor.ven_status === 'active' ? 'badge-warning' : 'badge-error'}">
                        ${vendor.ven_status || 'unknown'}
                    </span>
                </td>
                <td>
                    <div class="flex space-x-2">
                        <button title="Edit" class="btn btn-sm btn-circle btn-info" onclick="editVendor(${vendor.ven_id})">
                            <i class="bx bx-edit"></i>
                        </button>
                        <button title="Delete" class="btn btn-sm btn-circle btn-error" onclick="deleteVendor(${vendor.ven_id})">
                            <i class="bx bx-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
            `;
        }).join('');
    }

    function updateStats() {
        document.getElementById('total-vendors').textContent = vendors.length;
        document.getElementById('active-vendors').textContent = 
            vendors.filter(v => v.ven_status === 'active').length;
    }

    function openAddVendorModal() {
        document.getElementById('addVendorModal').classList.add('modal-open');
    }

    function closeAddVendorModal() {
        document.getElementById('addVendorModal').classList.remove('modal-open');
        document.getElementById('addVendorForm').reset();
    }

    function openEditVendorModal() {
        document.getElementById('editVendorModal').classList.add('modal-open');
    }

    function closeEditVendorModal() {
        document.getElementById('editVendorModal').classList.remove('modal-open');
        document.getElementById('editVendorForm').reset();
    }

    // Add Vendor Form Submission
    document.getElementById('addVendorForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const vendorData = {
            ven_name: formData.get('ven_name'),
            ven_email: formData.get('ven_email'),
            ven_contacts: formData.get('ven_contacts'),
            ven_address: formData.get('ven_address'),
            ven_rating: safeNumber(formData.get('ven_rating'), 0.0)
        };
        
        try {
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin mr-2"></i>Saving...';
            submitBtn.disabled = true;

            const response = await fetch(`${API_BASE_URL}/vendors`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(vendorData)
            });

            const result = await response.json();

            if (response.ok && result.success) {
                Swal.fire('Success', result.message || 'Vendor added successfully!', 'success');
                closeAddVendorModal();
                loadVendors();
            } else {
                throw new Error(result.message || 'Failed to add vendor');
            }
        } catch (error) {
            Swal.fire('Error', 'Failed to add vendor: ' + error.message, 'error');
        } finally {
            const submitBtn = document.querySelector('#addVendorForm button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="bx bx-save mr-2"></i>Save Vendor';
                submitBtn.disabled = false;
            }
        }
    });

    // Edit Vendor Functionality
    function editVendor(vendorId) {
        const vendor = vendors.find(v => v.ven_id === vendorId);
        if (!vendor) return;

        document.getElementById('editVendorId').value = vendor.ven_id;
        document.getElementById('editVendorName').value = vendor.ven_name || '';
        document.getElementById('editVendorEmail').value = vendor.ven_email || '';
        document.getElementById('editVendorContacts').value = vendor.ven_contacts || '';
        document.getElementById('editVendorAddress').value = vendor.ven_address || '';
        document.getElementById('editVendorRating').value = safeNumber(vendor.ven_rating, 0.0);
        document.getElementById('editVendorStatus').value = vendor.ven_status || 'active';

        openEditVendorModal();
    }

    // Edit Vendor Form Submission
    document.getElementById('editVendorForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const vendorId = document.getElementById('editVendorId').value;
        const formData = new FormData(this);
        const vendorData = {
            ven_name: formData.get('ven_name'),
            ven_email: formData.get('ven_email'),
            ven_contacts: formData.get('ven_contacts'),
            ven_address: formData.get('ven_address'),
            ven_rating: safeNumber(formData.get('ven_rating'), 0.0),
            ven_status: formData.get('ven_status')
        };
        
        try {
            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.innerHTML;
            submitBtn.innerHTML = '<i class="bx bx-loader-alt bx-spin mr-2"></i>Updating...';
            submitBtn.disabled = true;

            const response = await fetch(`${API_BASE_URL}/vendors/${vendorId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(vendorData)
            });

            const result = await response.json();

            if (response.ok && result.success) {
                Swal.fire('Success', result.message || 'Vendor updated successfully!', 'success');
                closeEditVendorModal();
                loadVendors();
            } else {
                throw new Error(result.message || 'Failed to update vendor');
            }
        } catch (error) {
            Swal.fire('Error', 'Failed to update vendor: ' + error.message, 'error');
        } finally {
            const submitBtn = document.querySelector('#editVendorForm button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<i class="bx bx-save mr-2"></i>Update Vendor';
                submitBtn.disabled = false;
            }
        }
    });

    async function deleteVendor(vendorId) {
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
            try {
                const response = await fetch(`${API_BASE_URL}/vendors/${vendorId}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    Swal.fire('Deleted!', result.message || 'Vendor has been deleted.', 'success');
                    loadVendors();
                } else {
                    throw new Error(result.message || 'Failed to delete vendor');
                }
            } catch (error) {
                Swal.fire('Error', 'Failed to delete vendor: ' + error.message, 'error');
            }
        }
    }
</script>

<style>
    .rating input {
        cursor: default !important;
    }
    .rating input:checked ~ input {
        color: #d1d5db !important;
    }
</style>
@endsection