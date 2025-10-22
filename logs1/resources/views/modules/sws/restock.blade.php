@extends('layouts.app')

@section('title', 'SWS Restock Management')

@section('content')
<div class="module-content bg-white rounded-xl p-6 shadow block">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Digital Inventory</h2>
        <button onclick="openCreateModal()" class="btn btn-primary">
            <i class="bx bx-plus mr-2"></i>New Inventory
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
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Stock ID</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Item Name</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Type</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Units</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">available item</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Status</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Actions</th>
                    <p>status lowstock onstock outofstock</p>
                    <p>action view</p>
                    <p>s/admin can delete</p>
                    <p>access by s/admin manager staff</p>
                    <p>type into asset type</p>

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