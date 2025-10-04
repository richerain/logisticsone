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
                <div class="stat shadow-lg border-l-4 border-primary rounded">
                    <div class="stat-figure text-primary">
                        <i class="bx bx-store text-3xl"></i>
                    </div>
                    <div class="stat-title">Total Vendors</div>
                    <div class="stat-value text-primary" id="total-vendors">0</div>
                </div>
                <div class="stat shadow-lg border-l-4 border-success rounded">
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
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Vendor Name</span>
                    </label>
                    <input type="text" name="ven_name" class="input input-bordered w-full" required>
                </div>
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Email</span>
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

<script>
    let vendors = [];

    // ==================== CONFIGURATION ====================
    const API_BASE_URL = 'http://localhost:8001/api/psm';

    // Load vendors on page load
    document.addEventListener('DOMContentLoaded', function() {
        loadVendors();
    });

    async function loadVendors() {
        try {
            const response = await fetch(`${API_BASE_URL}/vendors`);
            const data = await response.json();
            
            if (response.ok) {
                vendors = data;
                renderVendors();
                updateStats();
            } else {
                throw new Error(data.message || 'Failed to load vendors');
            }
        } catch (error) {
            console.error('Error loading vendors:', error);
            Swal.fire('Error', 'Failed to load vendors: ' + error.message, 'error');
        }
    }

    function renderVendors() {
        const tbody = document.getElementById('vendors-table-body');
        
        if (vendors.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="text-center py-8">
                        <i class="bx bx-package text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500">No vendors found</p>
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = vendors.map(vendor => `
            <tr>
                <td>
                    <div class="font-semibold">${vendor.ven_name}</div>
                </td>
                <td>${vendor.ven_email}</td>
                <td>${vendor.ven_contacts || 'N/A'}</td>
                <td>
                    <div class="flex items-center">
                        <div class="rating rating-sm">
                            ${Array.from({length: 5}, (_, i) => `
                                <input type="radio" class="mask mask-star-2" 
                                       ${i < Math.floor(vendor.ven_rating) ? 'checked' : ''} disabled>
                            `).join('')}
                        </div>
                        <span class="ml-2 text-sm">${vendor.ven_rating}</span>
                    </div>
                </td>
                <td>
                    <span class="badge ${vendor.ven_status === 'active' ? 'badge-success' : 'badge-error'}">
                        ${vendor.ven_status}
                    </span>
                </td>
                <td>
                    <div class="flex space-x-2">
                        <button class="btn btn-sm btn-outline btn-info" onclick="editVendor(${vendor.ven_id})">
                            <i class="bx bx-edit"></i>
                        </button>
                        <button class="btn btn-sm btn-outline btn-error" onclick="deleteVendor(${vendor.ven_id})">
                            <i class="bx bx-trash"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
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

    document.getElementById('addVendorForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const vendorData = Object.fromEntries(formData);
        
        try {
            const response = await fetch(`${API_BASE_URL}/vendors`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(vendorData)
            });

            const result = await response.json();

            if (response.ok) {
                Swal.fire('Success', 'Vendor added successfully!', 'success');
                closeAddVendorModal();
                loadVendors();
            } else {
                throw new Error(result.message || 'Failed to add vendor');
            }
        } catch (error) {
            Swal.fire('Error', 'Failed to add vendor: ' + error.message, 'error');
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
                    method: 'DELETE'
                });

                if (response.ok) {
                    Swal.fire('Deleted!', 'Vendor has been deleted.', 'success');
                    loadVendors();
                } else {
                    throw new Error('Failed to delete vendor');
                }
            } catch (error) {
                Swal.fire('Error', 'Failed to delete vendor: ' + error.message, 'error');
            }
        }
    }

    function editVendor(vendorId) {
        Swal.fire('Info', 'Edit functionality will be implemented soon!', 'info');
    }
</script>
@endsection