@extends('layouts.app')

@section('title', 'Purchase Management')

@section('content')
    <div class="module-content bg-white rounded-xl p-6 shadow block">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Purchase Management</h2>
        </div>
        
        <!-- Stats Section -->
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-primary">
                <div class="stat-figure text-primary">
                    <i class="bx bx-file text-3xl"></i>
                </div>
                <div class="stat-title">Total Requests</div>
                <div class="stat-value text-primary" id="total-requests">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-warning">
                <div class="stat-figure text-warning">
                    <i class="bx bx-time text-3xl"></i>
                </div>
                <div class="stat-title">Pending</div>
                <div class="stat-value text-warning" id="pending-requests">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-info">
                <div class="stat-figure text-info">
                    <i class="bx bx-trending-up text-3xl"></i>
                </div>
                <div class="stat-title">In Progress</div>
                <div class="stat-value text-info" id="progress-requests">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-success">
                <div class="stat-figure text-success">
                    <i class="bx bx-check-circle text-3xl"></i>
                </div>
                <div class="stat-title">Approved</div>
                <div class="stat-value text-success" id="approved-requests">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-error">
                <div class="stat-figure text-error">
                    <i class="bx bx-x-circle text-3xl"></i>
                </div>
                <div class="stat-title">Rejected</div>
                <div class="stat-value text-error" id="rejected-requests">0</div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="flex gap-4 mb-6">
            <div class="form-control flex-1">
                <input type="text" placeholder="Search requests..." class="input input-bordered w-full" id="searchPurchases">
            </div>
            <select class="select select-bordered" id="statusFilter">
                <option value="">All Status</option>
                <option value="Pending">Pending</option>
                <option value="In Progress">In Progress</option>
                <option value="Received">Received</option>
                <option value="Approved">Approved</option>
                <option value="Rejected">Rejected</option>
            </select>
        </div>
        
        <!-- Action Buttons -->
        <div class="flex gap-2 mb-4">
            <button class=" btn btn-primary" id="procureRequisitionBtn">
                <i class=" bx bx-sm bxs-cart-download" title="Procure Requisition"></i>Procure Requisition
            </button>
            <button class="btn btn-primary" id="addPurchaseBtn">
                <i class="bx bx-sm bxs-cart" title="Purchase Requisition"></i> Purchase Requisition
            </button>
            <button class="hidden btn btn-success" id="viewEmailBtn">
                <i class="bx bx-sm bxs-envelope" title="Email"></i> Requisition Email
            </button>
        </div>

        <!-- Purchase Requests Table -->
        <div class="overflow-x-auto bg-base-100 rounded-lg">
            <table class="table table-zebra w-full">
                <thead>
                    <tr class="bg-gray-900 text-white">
                        <th>Request ID</th>
                        <th>Item</th>
                        <th>Quantity</th>
                        <th>Units</th>
                        <th>Total Quote</th>
                        <th>Est. Budget</th>
                        <th>PO Number</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="purchases-table-body">
                    <tr>
                        <td colspan="9" class="text-center py-8">
                            <div class="loading loading-spinner loading-lg"></div>
                            <p class="text-gray-500 mt-2">Loading purchase requests...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add/Edit Purchase Modal -->
    <div id="purchaseModal" class="modal modal-lg">
        <div class="modal-box max-w-4xl p-0 overflow-visible">
            <div class="flex justify-between items-center bg-green-700 p-4 rounded-t-lg">
                <h3 class="font-bold text-white text-lg" id="purchaseModalTitle">Requisition Form</h3>
                <button class="btn btn-sm btn-circle btn-ghost hover:bg-white/20 text-white" id="closePurchaseModalX">✕</button>
            </div>
            <div class="p-4 max-h-[70vh] overflow-y-auto">
                <form id="purchaseForm" class="space-y-4">
                    @csrf
                    <input type="hidden" id="purchaseId" name="purchase_id">
                    
                    <!-- Auto-generated IDs Section -->
                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Request ID</span>
                            </label>
                            <input type="text" id="requestId" class="input input-bordered input-sm w-full bg-gray-100" 
                                   readonly placeholder="Auto-generated">
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">PO Number</span>
                            </label>
                            <input type="text" id="poNumber" class="input input-bordered input-sm w-full bg-gray-100" 
                                   readonly placeholder="Auto-generated">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Branch *</span>
                            </label>
                            <input type="text" id="branch" name="branch" class="input input-bordered input-sm w-full" 
                                   placeholder="Enter branch name" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Vendor *</span>
                            </label>
                            <select id="vendor" name="vendor" class="select select-bordered select-sm w-full" required>
                                <option value="">Select Vendor</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Item Name *</span>
                        </label>
                        <input type="text" id="item" name="item" class="input input-bordered input-sm w-full" 
                               placeholder="Enter item name" required>
                    </div>

                    <div class="grid grid-cols-3 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Quantity *</span>
                            </label>
                            <input type="number" id="quantity" name="quantity" class="input input-bordered input-sm w-full" 
                                   min="1" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Units *</span>
                            </label>
                            <input type="number" id="units" name="units" class="input input-bordered input-sm w-full" 
                                   min="1" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Unit Price (₱) *</span>
                            </label>
                            <input type="number" id="unitPrice" name="unit_price" class="input input-bordered input-sm w-full" 
                                   min="0" step="0.01" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Total Quote (₱)</span>
                            </label>
                            <input type="text" id="totalQuote" class="input input-bordered input-sm w-full bg-gray-100" 
                                   readonly placeholder="Auto-calculated">
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Estimated Budget (₱) *</span>
                            </label>
                            <input type="number" id="estimatedBudget" name="estimated_budget" class="input input-bordered input-sm w-full" 
                                   min="0" step="0.01" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Expected Delivery *</span>
                            </label>
                            <input type="text" id="expectedDelivery" name="expected_delivery" class="input input-bordered input-sm w-full" 
                                   placeholder="e.g., 5 Days, 7 Days, 15 Days" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Quote Date *</span>
                            </label>
                            <input type="date" id="quoteDate" name="quote_date" class="input input-bordered input-sm w-full" required>
                        </div>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Status</span>
                        </label>
                        <select id="purchaseStatus" name="status" class="select select-bordered select-sm w-full">
                            <option value="Pending">Pending</option>
                            <option value="In Progress">In Progress</option>
                            <option value="Received">Received</option>
                            <option value="Approved">Approved</option>
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
                        <button type="button" class="btn btn-ghost btn-sm hover:bg-gray-100 transition-colors px-4" id="closePurchaseModal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm bg-gradient-to-r from-primary to-primary/90 hover:from-primary/90 hover:to-primary transition-all duration-300 shadow-lg px-4" id="purchaseSubmitBtn">
                            <i class="bx bx-save mr-1"></i><span id="purchaseModalSubmitText">Save Request</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Purchase Modal -->
    <div id="viewPurchaseModal" class="modal modal-lg">
        <div class="modal-box max-w-4xl p-0 overflow-visible">
            <div class="flex justify-between items-center bg-green-700 p-4 rounded-t-lg">
                <h3 class="font-bold text-white text-lg">Purchase Request Details</h3>
                <button class="btn btn-sm btn-circle btn-ghost hover:bg-white/20 text-white" id="closeViewPurchaseModalX">✕</button>
            </div>
            <div class="p-4 max-h-[70vh] overflow-y-auto">
                <div class="space-y-4" id="purchaseDetails">
                    <!-- Purchase details will be populated here -->
                </div>
                <div class="modal-action flex justify-end pt-4 border-t">
                    <button type="button" class="btn btn-ghost btn-sm hover:bg-gray-100 transition-colors px-4" id="closeViewPurchaseModal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Procure Requisition Modal -->
    <div id="procureRequisitionModal" class="modal modal-lg">
        <div class="modal-box max-w-4xl p-0 overflow-visible">
            <div class="flex justify-between items-center bg-blue-700 p-4 rounded-t-lg">
                <h3 class="font-bold text-white text-lg">Procure Requisition</h3>
                <button class="btn btn-sm btn-circle btn-ghost hover:bg-white/20 text-white" id="closeProcureRequisitionModalX">✕</button>
            </div>
            <div class="p-4 max-h-[70vh] overflow-y-auto">
                <form id="procureRequisitionForm" class="space-y-4">
                    @csrf
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Requisition ID</span>
                            </label>
                            <input type="text" id="procureRequisitionId" class="input input-bordered input-sm w-full bg-gray-100" 
                                   readonly placeholder="Auto-generated">
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Department *</span>
                            </label>
                            <select id="procureDepartment" name="department" class="select select-bordered select-sm w-full" required>
                                <option value="HR">Human Resources Department</option>
                                <option value="Core">Core Department</option>
                                <option value="Logs">Logistics Department</option>
                                <option value="Finance">Financial Department</option>
                                <option value="Admin">Administrative Department</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Item Description *</span>
                        </label>
                        <textarea id="procureItemDescription" name="item_description" class="textarea textarea-bordered textarea-sm h-20" 
                                  placeholder="Describe the items to be procured..." required></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Priority Level *</span>
                            </label>
                            <select id="procurePriority" name="priority" class="select select-bordered select-sm w-full" required>
                                <option value="Low">Low</option>
                                <option value="Medium" selected>Medium</option>
                                <option value="High">High</option>
                                <option value="Urgent">Urgent</option>
                            </select>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Required By Date *</span>
                            </label>
                            <input type="date" id="procureRequiredDate" name="required_date" class="input input-bordered input-sm w-full" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Estimated Budget (₱) *</span>
                            </label>
                            <input type="number" id="procureEstimatedBudget" name="estimated_budget" class="input input-bordered input-sm w-full" 
                                   min="0" step="0.01" required>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Preferred Vendor</span>
                            </label>
                            <select id="procureVendor" name="vendor" class="select select-bordered select-sm w-full">
                                <option value="">Select Preferred Vendor</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Justification *</span>
                        </label>
                        <textarea id="procureJustification" name="justification" class="textarea textarea-bordered textarea-sm h-16" 
                                  placeholder="Explain why this procurement is necessary..." required></textarea>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Additional Notes</span>
                        </label>
                        <textarea id="procureNotes" name="notes" class="textarea textarea-bordered textarea-sm h-12" 
                                  placeholder="Any additional information..."></textarea>
                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-action flex justify-end space-x-3 pt-4 border-t">
                        <button type="button" class="btn btn-ghost btn-sm hover:bg-gray-100 transition-colors px-4" id="closeProcureRequisitionModal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 transition-all duration-300 shadow-lg px-4">
                            <i class="bx bx-send mr-1"></i>Submit Requisition
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Email Modal -->
    <div id="emailModal" class="modal modal-lg">
        <div class="modal-box max-w-3xl p-0 overflow-visible">
            <div class="flex justify-between items-center bg-purple-700 p-4 rounded-t-lg">
                <h3 class="font-bold text-white text-lg">Received Request Emails</h3>
                <button class="btn btn-sm btn-circle btn-ghost hover:bg-white/20 text-white" id="closeEmailModalX">✕</button>
            </div>
            <div class="p-4 max-h-[70vh] overflow-y-auto">
                <div class="space-y-4">
                    <!-- Email List -->
                    <div class="space-y-3" id="emailList">
                        <!-- Equipment Requests -->
                        <div class="email-item bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-800 text-sm mb-1">Computer Equipment Request</h4>
                                    <p class="text-xs text-gray-600 mb-1">From: IT Department</p>
                                    <p class="text-xs text-gray-500">Date: 01/15/2024</p>
                                </div>
                            </div>
                            <div class="file-attachment flex items-center justify-between p-3 bg-gray-50 rounded border">
                                <div class="flex items-center space-x-3">
                                    <i class="bx bx-file-pdf text-red-500 text-2xl"></i>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">computer_equipment_request.pdf</p>
                                        <p class="text-xs text-gray-500">1.2 MB</p>
                                    </div>
                                </div>
                                <button class="btn btn-sm btn-primary download-file" data-filename="computer_equipment_request.pdf">
                                    <i class="bx bx-download mr-1"></i>Download
                                </button>
                            </div>
                        </div>

                        <div class="email-item bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-800 text-sm mb-1">Network Equipment Purchase</h4>
                                    <p class="text-xs text-gray-600 mb-1">From: Network Team</p>
                                    <p class="text-xs text-gray-500">Date: 01/14/2024</p>
                                </div>
                            </div>
                            <div class="file-attachment flex items-center justify-between p-3 bg-gray-50 rounded border">
                                <div class="flex items-center space-x-3">
                                    <i class="bx bx-file-pdf text-red-500 text-2xl"></i>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">network_equipment_quote.pdf</p>
                                        <p class="text-xs text-gray-500">0.9 MB</p>
                                    </div>
                                </div>
                                <button class="btn btn-sm btn-primary download-file" data-filename="network_equipment_quote.pdf">
                                    <i class="bx bx-download mr-1"></i>Download
                                </button>
                            </div>
                        </div>

                        <!-- Supplies Requests -->
                        <div class="email-item bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-800 text-sm mb-1">Office Supplies Request</h4>
                                    <p class="text-xs text-gray-600 mb-1">From: Admin Department</p>
                                    <p class="text-xs text-gray-500">Date: 01/13/2024</p>
                                </div>
                            </div>
                            <div class="file-attachment flex items-center justify-between p-3 bg-gray-50 rounded border">
                                <div class="flex items-center space-x-3">
                                    <i class="bx bx-file-word text-blue-500 text-2xl"></i>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">office_supplies_list.docx</p>
                                        <p class="text-xs text-gray-500">0.8 MB</p>
                                    </div>
                                </div>
                                <button class="btn btn-sm btn-primary download-file" data-filename="office_supplies_list.docx">
                                    <i class="bx bx-download mr-1"></i>Download
                                </button>
                            </div>
                        </div>

                        <div class="email-item bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-800 text-sm mb-1">Cleaning Supplies Order</h4>
                                    <p class="text-xs text-gray-600 mb-1">From: Facilities Management</p>
                                    <p class="text-xs text-gray-500">Date: 01/12/2024</p>
                                </div>
                            </div>
                            <div class="file-attachment flex items-center justify-between p-3 bg-gray-50 rounded border">
                                <div class="flex items-center space-x-3">
                                    <i class="bx bx-file-excel text-green-500 text-2xl"></i>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">cleaning_supplies_order.xlsx</p>
                                        <p class="text-xs text-gray-500">0.7 MB</p>
                                    </div>
                                </div>
                                <button class="btn btn-sm btn-primary download-file" data-filename="cleaning_supplies_order.xlsx">
                                    <i class="bx bx-download mr-1"></i>Download
                                </button>
                            </div>
                        </div>

                        <!-- Furniture Requests -->
                        <div class="email-item bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-800 text-sm mb-1">Office Furniture Purchase</h4>
                                    <p class="text-xs text-gray-600 mb-1">From: HR Department</p>
                                    <p class="text-xs text-gray-500">Date: 01/11/2024</p>
                                </div>
                            </div>
                            <div class="file-attachment flex items-center justify-between p-3 bg-gray-50 rounded border">
                                <div class="flex items-center space-x-3">
                                    <i class="bx bx-file-pdf text-red-500 text-2xl"></i>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">office_furniture_quotation.pdf</p>
                                        <p class="text-xs text-gray-500">1.5 MB</p>
                                    </div>
                                </div>
                                <button class="btn btn-sm btn-primary download-file" data-filename="office_furniture_quotation.pdf">
                                    <i class="bx bx-download mr-1"></i>Download
                                </button>
                            </div>
                        </div>

                        <div class="email-item bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                            <div class="flex justify-between items-start mb-3">
                                <div class="flex-1">
                                    <h4 class="font-semibold text-gray-800 text-sm mb-1">Conference Room Furniture</h4>
                                    <p class="text-xs text-gray-600 mb-1">From: Executive Office</p>
                                    <p class="text-xs text-gray-500">Date: 01/10/2024</p>
                                </div>
                            </div>
                            <div class="file-attachment flex items-center justify-between p-3 bg-gray-50 rounded border">
                                <div class="flex items-center space-x-3">
                                    <i class="bx bx-file-pdf text-red-500 text-2xl"></i>
                                    <div>
                                        <p class="text-sm font-medium text-gray-800">conference_furniture_specs.pdf</p>
                                        <p class="text-xs text-gray-500">2.1 MB</p>
                                    </div>
                                </div>
                                <button class="btn btn-sm btn-primary download-file" data-filename="conference_furniture_specs.pdf">
                                    <i class="bx bx-download mr-1"></i>Download
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="modal-action flex justify-end pt-4 border-t">
                    <button type="button" class="btn btn-ghost btn-sm hover:bg-gray-100 transition-colors px-4" id="closeEmailModal">Close</button>
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
    let purchases = [];
    let vendors = [];

    // ==================== CONFIGURATION ====================
    const API_BASE_URL = 'http://localhost:8001/api/psm/purchase';

    // Utility functions
    function formatCurrency(amount) {
        return '₱' + parseFloat(amount).toLocaleString('en-PH', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        });
    }

    function formatDate(dateString) {
        return new Date(dateString).toLocaleDateString('en-US', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit'
        });
    }

    function getStatusBadge(status) {
        const statusClasses = {
            'Pending': 'bg-yellow-400 uppercase',
            'In Progress': 'bg-blue-400 uppercase',
            'Received': 'bg-green-400 uppercase',
            'Approved': 'bg-green-600 uppercase',
            'Rejected': 'bg-red-400 uppercase'
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

    // Generate random ID
    function generateRandomId(prefix = 'REQ') {
        const timestamp = Date.now().toString().slice(-6);
        const random = Math.random().toString(36).substring(2, 5).toUpperCase();
        return `${prefix}-${timestamp}-${random}`;
    }

    // Load data on page load
    document.addEventListener('DOMContentLoaded', function() {
        initializeEventListeners();
        loadVendors();
        loadPurchases();
    });

    function initializeEventListeners() {
        // Add purchase button
        document.getElementById('addPurchaseBtn').addEventListener('click', openAddPurchaseModal);
        
        // Procure requisition button
        document.getElementById('procureRequisitionBtn').addEventListener('click', openProcureRequisitionModal);
        
        // Email button
        document.getElementById('viewEmailBtn').addEventListener('click', openEmailModal);

        // Close modal buttons
        document.getElementById('closePurchaseModal').addEventListener('click', closePurchaseModal);
        document.getElementById('closePurchaseModalX').addEventListener('click', closePurchaseModal);
        document.getElementById('closeViewPurchaseModal').addEventListener('click', closeViewPurchaseModal);
        document.getElementById('closeViewPurchaseModalX').addEventListener('click', closeViewPurchaseModal);
        document.getElementById('closeProcureRequisitionModal').addEventListener('click', closeProcureRequisitionModal);
        document.getElementById('closeProcureRequisitionModalX').addEventListener('click', closeProcureRequisitionModal);
        document.getElementById('closeEmailModal').addEventListener('click', closeEmailModal);
        document.getElementById('closeEmailModalX').addEventListener('click', closeEmailModal);

        // Form submission
        document.getElementById('purchaseForm').addEventListener('submit', handlePurchaseSubmit);
        document.getElementById('procureRequisitionForm').addEventListener('submit', handleProcureRequisitionSubmit);

        // Auto-calculate total quote when units or unit price changes
        document.getElementById('units').addEventListener('input', calculatePurchaseTotals);
        document.getElementById('unitPrice').addEventListener('input', calculatePurchaseTotals);

        // Search and filter
        document.getElementById('searchPurchases').addEventListener('input', filterPurchases);
        document.getElementById('statusFilter').addEventListener('change', filterPurchases);

        // Email download buttons
        document.querySelectorAll('.download-file').forEach(btn => {
            btn.addEventListener('click', function() {
                const filename = this.getAttribute('data-filename');
                downloadFile(filename);
            });
        });
    }

    function calculatePurchaseTotals() {
        const units = parseInt(document.getElementById('units').value) || 0;
        const unitPrice = parseFloat(document.getElementById('unitPrice').value) || 0;
        
        // Calculate total quote based on units and unit price
        const totalQuote = units * unitPrice;
        
        document.getElementById('totalQuote').value = formatCurrency(totalQuote);
    }

    function downloadFile(filename) {
        showLoadingModal('Downloading File...');
        
        // Simulate download process
        setTimeout(() => {
            hideLoadingModal();
            
            // Create a temporary link to trigger download
            const link = document.createElement('a');
            link.href = '#'; // In real implementation, this would be the actual file URL
            link.download = filename;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            showSuccessToast(`Successfully Download`);
            
            console.log(`Downloading file...`);
        }, 1000);
    }

    async function loadVendors() {
        try {
            const response = await fetch('http://localhost:8001/api/psm/vendors');
            const result = await response.json();
            
            if (result.success) {
                vendors = result.data || [];
                populateVendorDropdown();
                populateProcureVendorDropdown();
            }
        } catch (error) {
            console.error('Error loading vendors:', error);
        }
    }

    function populateVendorDropdown() {
        const vendorSelect = document.getElementById('vendor');
        vendorSelect.innerHTML = '<option value="">Select Vendor</option>';
        
        vendors.forEach(vendor => {
            const option = document.createElement('option');
            option.value = vendor.ven_name;
            option.textContent = `${vendor.ven_name} (${vendor.ven_code || vendor.ven_email})`;
            vendorSelect.appendChild(option);
        });
    }

    function populateProcureVendorDropdown() {
        const vendorSelect = document.getElementById('procureVendor');
        vendorSelect.innerHTML = '<option value="">Select Preferred Vendor</option>';
        
        vendors.forEach(vendor => {
            const option = document.createElement('option');
            option.value = vendor.ven_name;
            option.textContent = `${vendor.ven_name} (${vendor.ven_code || vendor.ven_email})`;
            vendorSelect.appendChild(option);
        });
    }

    async function loadPurchases() {
        try {
            showPurchasesLoadingState();
            const response = await fetch(`${API_BASE_URL}/requests`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const result = await response.json();
            
            if (result.success) {
                purchases = result.data || [];
                renderPurchases(purchases);
                updateStats(purchases);
            } else {
                throw new Error(result.message || 'Failed to load purchase requests');
            }
        } catch (error) {
            console.error('Error loading purchase requests:', error);
            showPurchasesErrorState('Failed to load purchase requests: ' + error.message);
        }
    }

    function showPurchasesLoadingState() {
        const tbody = document.getElementById('purchases-table-body');
        tbody.innerHTML = `
            <tr>
                <td colspan="9" class="text-center py-8">
                    <div class="loading loading-spinner loading-lg"></div>
                    <p class="text-gray-500 mt-2">Loading purchase requests...</p>
                </td>
            </tr>
        `;
    }

    function showPurchasesErrorState(message) {
        const tbody = document.getElementById('purchases-table-body');
        tbody.innerHTML = `
            <tr>
                <td colspan="9" class="text-center py-8">
                    <i class="bx bx-error text-4xl text-red-400 mb-2"></i>
                    <p class="text-red-500">${message}</p>
                    <button class="btn btn-sm btn-outline mt-2" onclick="loadPurchases()">Retry</button>
                </td>
            </tr>
        `;
    }

    function renderPurchases(purchasesData) {
        const tbody = document.getElementById('purchases-table-body');
        
        if (purchasesData.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="9" class="text-center py-8">
                        <i class="bx bx-file text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500">No purchase requests found</p>
                        <button class="btn btn-sm btn-primary mt-2" id="addFirstPurchaseBtn">Create First Request</button>
                    </td>
                </tr>
            `;
            document.getElementById('addFirstPurchaseBtn')?.addEventListener('click', openAddPurchaseModal);
            return;
        }

        tbody.innerHTML = purchasesData.map(purchase => {
            return `
            <tr>
                <td class="font-mono font-semibold text-sm">${purchase.request_id}</td>
                <td class="text-sm">${purchase.item}</td>
                <td class="text-center text-sm">${purchase.quantity}</td>
                <td class="text-center text-sm">${purchase.units}</td>
                <td class="text-right font-mono text-sm font-semibold">${formatCurrency(purchase.total_quote)}</td>
                <td class="text-right font-mono text-sm">${formatCurrency(purchase.estimated_budget)}</td>
                <td class="font-mono font-semibold text-sm">${purchase.po_number}</td>
                <td>${getStatusBadge(purchase.status)}</td>
                <td>
                    <div class="flex space-x-1">
                        <button title="View" class="btn btn-sm btn-circle btn-info view-purchase-btn" data-purchase-id="${purchase.purchase_id}">
                            <i class="bx bx-show-alt text-sm"></i>
                        </button>
                        <button title="Edit" class="hidden btn btn-sm btn-circle btn-warning edit-purchase-btn" data-purchase-id="${purchase.purchase_id}">
                            <i class="bx bx-edit text-sm"></i>
                        </button>
                        <button title="Delete" class="btn btn-sm btn-circle btn-error delete-purchase-btn" data-purchase-id="${purchase.purchase_id}">
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
        document.querySelectorAll('.view-purchase-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const purchaseId = this.getAttribute('data-purchase-id');
                viewPurchase(parseInt(purchaseId));
            });
        });

        document.querySelectorAll('.edit-purchase-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const purchaseId = this.getAttribute('data-purchase-id');
                editPurchase(parseInt(purchaseId));
            });
        });

        document.querySelectorAll('.delete-purchase-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const purchaseId = this.getAttribute('data-purchase-id');
                deletePurchase(parseInt(purchaseId));
            });
        });
    }

    function updateStats(purchasesData) {
        document.getElementById('total-requests').textContent = purchasesData.length;
        document.getElementById('pending-requests').textContent = 
            purchasesData.filter(p => p.status === 'Pending').length;
        document.getElementById('progress-requests').textContent = 
            purchasesData.filter(p => p.status === 'In Progress').length;
        document.getElementById('approved-requests').textContent = 
            purchasesData.filter(p => p.status === 'Approved').length;
        document.getElementById('rejected-requests').textContent = 
            purchasesData.filter(p => p.status === 'Rejected').length;
    }

    function filterPurchases() {
        const searchTerm = document.getElementById('searchPurchases').value.toLowerCase();
        const statusFilter = document.getElementById('statusFilter').value;
        
        const filtered = purchases.filter(purchase => {
            const matchesSearch = searchTerm === '' || 
                purchase.request_id.toLowerCase().includes(searchTerm) ||
                purchase.item.toLowerCase().includes(searchTerm) ||
                purchase.po_number.toLowerCase().includes(searchTerm);
            
            const matchesStatus = statusFilter === '' || purchase.status === statusFilter;
            
            return matchesSearch && matchesStatus;
        });
        
        renderPurchases(filtered);
        updateStats(filtered);
    }

    // Modal Functions
    function openAddPurchaseModal() {
        document.getElementById('purchaseModalTitle').textContent = 'Requisition Form';
        document.getElementById('purchaseModalSubmitText').textContent = 'Save Request';
        document.getElementById('purchaseForm').reset();
        document.getElementById('purchaseId').value = '';
        document.getElementById('purchaseStatus').value = 'Pending';
        document.getElementById('quoteDate').value = new Date().toISOString().split('T')[0];
        
        // Clear auto-generated ID fields for new requests
        document.getElementById('requestId').value = 'Auto-generated';
        document.getElementById('poNumber').value = 'Auto-generated';
        
        calculatePurchaseTotals();
        document.getElementById('purchaseModal').classList.add('modal-open');
    }

    function closePurchaseModal() {
        document.getElementById('purchaseModal').classList.remove('modal-open');
        document.getElementById('purchaseForm').reset();
    }

    function openViewPurchaseModal() {
        document.getElementById('viewPurchaseModal').classList.add('modal-open');
    }

    function closeViewPurchaseModal() {
        document.getElementById('viewPurchaseModal').classList.remove('modal-open');
    }

    function openProcureRequisitionModal() {
        document.getElementById('procureRequisitionForm').reset();
        document.getElementById('procureRequisitionId').value = generateRandomId('PROC');
        document.getElementById('procureRequiredDate').value = new Date(Date.now() + 7 * 24 * 60 * 60 * 1000).toISOString().split('T')[0];
        document.getElementById('procureRequisitionModal').classList.add('modal-open');
    }

    function closeProcureRequisitionModal() {
        document.getElementById('procureRequisitionModal').classList.remove('modal-open');
        document.getElementById('procureRequisitionForm').reset();
    }

    function openEmailModal() {
        document.getElementById('emailModal').classList.add('modal-open');
    }

    function closeEmailModal() {
        document.getElementById('emailModal').classList.remove('modal-open');
    }

    // Purchase Actions
    function viewPurchase(purchaseId) {
        const purchase = purchases.find(p => p.purchase_id === purchaseId);
        if (!purchase) return;

        const purchaseDetails = `
            <div class="space-y-4">
                <!-- Basic Information -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <strong class="text-gray-700 text-xs">Request ID:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1 font-mono">${purchase.request_id}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">PO Number:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1 font-mono">${purchase.po_number}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Branch:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${purchase.branch}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Vendor:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${purchase.vendor}</p>
                    </div>
                </div>

                <!-- Item Information -->
                <div>
                    <strong class="text-gray-700 text-xs">Item Name:</strong>
                    <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${purchase.item}</p>
                </div>

                <!-- Quantity and Pricing -->
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <strong class="text-gray-700 text-xs">Quantity:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1 text-center">${purchase.quantity}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Units:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1 text-center">${purchase.units}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Unit Price:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1 text-right font-mono">${formatCurrency(purchase.unit_price)}</p>
                    </div>
                </div>

                <!-- Financial Information -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <strong class="text-gray-700 text-xs">Total Quote:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1 text-right font-mono font-semibold">${formatCurrency(purchase.total_quote)}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Estimated Budget:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1 text-right font-mono">${formatCurrency(purchase.estimated_budget)}</p>
                    </div>
                </div>

                <!-- Delivery and Date -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <strong class="text-gray-700 text-xs">Expected Delivery:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1 text-center">${purchase.expected_delivery} Days</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Quote Date:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${formatDate(purchase.quote_date)}</p>
                    </div>
                </div>

                <!-- Status -->
                <div>
                    <strong class="text-gray-700 text-xs">Status:</strong>
                    <p class="mt-1 p-2">${getStatusBadge(purchase.status)}</p>
                </div>

                <!-- Description -->
                ${purchase.description ? `
                <div>
                    <strong class="text-gray-700 text-xs">Description:</strong>
                    <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${purchase.description}</p>
                </div>
                ` : ''}

                <!-- Timestamps -->
                <div class="grid grid-cols-2 gap-4">
                    ${purchase.created_at ? `
                    <div>
                        <strong class="text-gray-700 text-xs">Created:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${new Date(purchase.created_at).toLocaleString()}</p>
                    </div>
                    ` : ''}
                    ${purchase.updated_at ? `
                    <div>
                        <strong class="text-gray-700 text-xs">Last Updated:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${new Date(purchase.updated_at).toLocaleString()}</p>
                    </div>
                    ` : ''}
                </div>
            </div>
        `;

        document.getElementById('purchaseDetails').innerHTML = purchaseDetails;
        openViewPurchaseModal();
    }

    function editPurchase(purchaseId) {
        const purchase = purchases.find(p => p.purchase_id === purchaseId);
        if (!purchase) return;

        document.getElementById('purchaseModalTitle').textContent = 'Edit Purchase Request';
        document.getElementById('purchaseModalSubmitText').textContent = 'Update Request';
        
        document.getElementById('purchaseId').value = purchase.purchase_id;
        document.getElementById('requestId').value = purchase.request_id;
        document.getElementById('poNumber').value = purchase.po_number;
        document.getElementById('branch').value = purchase.branch;
        document.getElementById('vendor').value = purchase.vendor;
        document.getElementById('item').value = purchase.item;
        document.getElementById('quantity').value = purchase.quantity;
        document.getElementById('units').value = purchase.units;
        document.getElementById('unitPrice').value = purchase.unit_price;
        document.getElementById('estimatedBudget').value = purchase.estimated_budget;
        document.getElementById('expectedDelivery').value = purchase.expected_delivery;
        document.getElementById('quoteDate').value = purchase.quote_date;
        document.getElementById('purchaseStatus').value = purchase.status;
        document.getElementById('description').value = purchase.description || '';

        calculatePurchaseTotals();
        document.getElementById('purchaseModal').classList.add('modal-open');
    }

    async function handlePurchaseSubmit(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const purchaseId = document.getElementById('purchaseId').value;
        const isEdit = !!purchaseId;

        const purchaseData = {
            branch: formData.get('branch'),
            vendor: formData.get('vendor'),
            item: formData.get('item'),
            quantity: parseInt(formData.get('quantity')) || 1,
            units: parseInt(formData.get('units')) || 1,
            unit_price: parseFloat(formData.get('unit_price')) || 0,
            estimated_budget: parseFloat(formData.get('estimated_budget')) || 0,
            expected_delivery: formData.get('expected_delivery'),
            quote_date: formData.get('quote_date'),
            status: formData.get('status'),
            description: formData.get('description')
        };
        
        try {
            showLoadingModal(
                isEdit ? 'Updating Request...' : 'Creating Request...'
            );

            let response;
            if (isEdit) {
                response = await fetch(`${API_BASE_URL}/requests/${purchaseId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(purchaseData)
                });
            } else {
                response = await fetch(`${API_BASE_URL}/requests`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(purchaseData)
                });
            }

            const result = await response.json();

            if (response.ok && result.success) {
                hideLoadingModal();
                closePurchaseModal();
                
                // Wait for data to reload before showing success message
                await loadPurchases();
                
                showSuccessToast(
                    isEdit ? 'Purchase request updated successfully!' : 'Purchase request created successfully!'
                );
            } else {
                throw new Error(result.message || `Failed to ${isEdit ? 'update' : 'create'} purchase request`);
            }
        } catch (error) {
            hideLoadingModal();
            Swal.fire('Error', `Failed to ${isEdit ? 'update' : 'create'} purchase request: ` + error.message, 'error');
        }
    }

    async function handleProcureRequisitionSubmit(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        
        const procureData = {
            requisition_id: formData.get('requisition_id'),
            department: formData.get('department'),
            item_description: formData.get('item_description'),
            priority: formData.get('priority'),
            required_date: formData.get('required_date'),
            estimated_budget: parseFloat(formData.get('estimated_budget')) || 0,
            vendor: formData.get('vendor'),
            justification: formData.get('justification'),
            notes: formData.get('notes')
        };
        
        try {
            showLoadingModal('Submitting Procure Requisition...');

            // Simulate API call - replace with actual API endpoint
            await new Promise(resolve => setTimeout(resolve, 2000));
            
            hideLoadingModal();
            closeProcureRequisitionModal();
            
            showSuccessToast('Procure requisition submitted successfully!');
            
        } catch (error) {
            hideLoadingModal();
            Swal.fire('Error', 'Failed to submit procure requisition: ' + error.message, 'error');
        }
    }

    async function deletePurchase(purchaseId) {
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: "This will permanently delete the purchase request!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        });

        if (result.isConfirmed) {
            try {
                showLoadingModal('Deleting Request...');

                const response = await fetch(`${API_BASE_URL}/requests/${purchaseId}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    hideLoadingModal();
                    
                    // Wait for data to reload before showing success message
                    await loadPurchases();
                    
                    showSuccessToast('Purchase request deleted successfully!');
                } else {
                    throw new Error(result.message || 'Failed to delete purchase request');
                }
            } catch (error) {
                hideLoadingModal();
                Swal.fire('Error', 'Failed to delete purchase request: ' + error.message, 'error');
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
    .checkbox:checked {
        background-color: #4f46e5;
        border-color: #4f46e5;
    }
    .email-item {
        border-left: 4px solid #8b5cf6;
    }
    .email-item:hover {
        border-left-color: #7c3aed;
    }
</style>
@endsection