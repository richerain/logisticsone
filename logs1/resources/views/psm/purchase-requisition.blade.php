<!-- resources/views/psm/purchase-requisition.blade.php -->
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bx-clipboard'></i>Purchase Requisition</h2>
    </div>
    <div class="text-right">
        <span class="text-md text-gray-600">Procurement & Sourcing Management</span>
    </div>
</div>

<!-- Stats Section -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
    <!-- Total Requisitions -->
    <div onclick="filterByStatus('')" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative overflow-hidden group hover:shadow-md transition-all duration-300 cursor-pointer active:scale-95">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform duration-300">
            <i class='bx bx-file text-6xl text-blue-600'></i>
        </div>
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-4">
                <div class="p-3 bg-blue-50 rounded-xl text-blue-600">
                    <i class='bx bx-file text-2xl'></i>
                </div>
                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Total Requisitions</h4>
            </div>
            <div class="flex items-end gap-2">
                <span id="totalReqCount" class="text-4xl font-black text-gray-800 leading-none">0</span>
                <span class="text-xs font-bold text-gray-400 mb-1 uppercase">Records</span>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 w-full h-1 bg-blue-500"></div>
    </div>
    
    <!-- Approved -->
    <div onclick="filterByStatus('Approved')" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative overflow-hidden group hover:shadow-md transition-all duration-300 cursor-pointer active:scale-95">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform duration-300">
            <i class='bx bx-check-circle text-6xl text-green-600'></i>
        </div>
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-4">
                <div class="p-3 bg-green-50 rounded-xl text-green-600">
                    <i class='bx bx-check-circle text-2xl'></i>
                </div>
                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Approved</h4>
            </div>
            <div class="flex items-end gap-2">
                <span id="approvedReqCount" class="text-4xl font-black text-gray-800 leading-none">0</span>
                <span class="text-xs font-bold text-green-500 mb-1 uppercase">Cleared</span>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 w-full h-1 bg-green-500"></div>
    </div>
    
    <!-- Pending -->
    <div onclick="filterByStatus('Pending')" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative overflow-hidden group hover:shadow-md transition-all duration-300 cursor-pointer active:scale-95">
        <!-- Pulse Notification Badge -->
        <div id="pendingBadgePulse" class="hidden absolute top-4 right-4 z-20">
            <span class="relative flex h-6 w-6">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-yellow-400 opacity-75"></span>
                <span id="pendingPulseCount" class="relative inline-flex rounded-full h-6 w-6 bg-yellow-500 text-[10px] font-bold text-white items-center justify-center border-2 border-white">0</span>
            </span>
        </div>
        
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform duration-300">
            <i class='bx bx-time text-6xl text-yellow-600'></i>
        </div>
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-4">
                <div class="p-3 bg-yellow-50 rounded-xl text-yellow-600">
                    <i class='bx bx-time text-2xl'></i>
                </div>
                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Pending</h4>
            </div>
            <div class="flex items-end gap-2">
                <span id="pendingReqCount" class="text-4xl font-black text-gray-800 leading-none">0</span>
                <span class="text-xs font-bold text-yellow-600 mb-1 uppercase">Waiting</span>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 w-full h-1 bg-yellow-500"></div>
    </div>
    
    <!-- Rejected -->
    <div onclick="filterByStatus('Rejected')" class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative overflow-hidden group hover:shadow-md transition-all duration-300 cursor-pointer active:scale-95">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform duration-300">
            <i class='bx bx-x-circle text-6xl text-red-600'></i>
        </div>
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-4">
                <div class="p-3 bg-red-50 rounded-xl text-red-600">
                    <i class='bx bx-x-circle text-2xl'></i>
                </div>
                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Rejected</h4>
            </div>
            <div class="flex items-end gap-2">
                <span id="rejectedReqCount" class="text-4xl font-black text-gray-800 leading-none">0</span>
                <span class="text-xs font-bold text-red-500 mb-1 uppercase">Denied</span>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 w-full h-1 bg-red-500"></div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg p-6">
    <!-- Header Section -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <h3 class="text-xl font-semibold text-gray-800 flex items-center gap-2">
            <i class='bx bx-list-ul text-blue-600'></i>
            Requisition Records
        </h3>
        <button id="addRequisitionBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2.5 rounded-lg flex items-center gap-2 transition-all shadow-md hover:shadow-lg active:scale-95">
            <i class='bx bx-plus text-xl'></i>
            New Requisition
        </button>
    </div>

    <!-- Redesigned Filters Section -->
    <div class="bg-gray-50 rounded-xl p-4 mb-6 border border-gray-100">
        <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
            <!-- Search Bar -->
            <div class="md:col-span-6 relative">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <i class='bx bx-search text-gray-400 text-xl'></i>
                </div>
                <input type="text" id="searchInput" placeholder="Search by ID, requester, or department..." 
                    class="block w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm transition-all text-sm">
            </div>

            <!-- Department Filter -->
            <div class="md:col-span-3">
                <select id="deptFilter" class="block w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm transition-all text-sm">
                    <option value="">All Departments</option>
                    <option value="Human Resource Department">Human Resource Department</option>
                    <option value="Core Transaction Office">Core Transaction Office</option>
                    <option value="Logistics Office">Logistics Office</option>
                    <option value="Administrative Office">Administrative Office</option>
                    <option value="Financial Department">Financial Department</option>
                </select>
            </div>

            <!-- Status Filter -->
            <div class="md:col-span-3">
                <select id="statusFilter" class="block w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm transition-all text-sm">
                    <option value="">All Status</option>
                    <option value="Pending">Pending</option>
                    <option value="Approved">Approved</option>
                    <option value="Rejected">Rejected</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Requisition Table -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-800 font-bold text-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Requisition ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Items</th>
                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Requester / Dept</th>
                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Note</th>
                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase text-right">Actions</th>
                    </tr>
                </thead>
                <tbody id="requisitionTableBody" class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex justify-center items-center py-4">
                                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mr-3"></div>
                                Loading requisitions...
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div id="purchasesPager" class="flex items-center justify-between mt-3"> 
        <div id="purchasesPagerInfo" class="text-sm text-gray-600"></div> 
        <div class="join"> 
            <button class="btn btn-sm join-item" id="purchasesPrevBtn" data-action="prev">Prev</button> 
            <span class="btn btn-sm join-item" id="purchasesPageDisplay">1 / 1</span> 
            <button class="btn btn-sm join-item" id="purchasesNextBtn" data-action="next">Next</button> 
        </div> 
    </div>
</div>

<!-- Main Requisition Modal (New/View) -->
<div id="requisitionModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div id="modalContainer" class="bg-white rounded-xl p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto shadow-2xl transition-all scale-95 transform">
        <div class="flex justify-between items-center mb-6 border-b pb-4">
            <h3 id="modalTitle" class="text-xl font-bold text-gray-800">New Purchase Requisition</h3>
        </div>
        
        <form id="requisitionForm" class="space-y-4">
            <!-- Hidden Fields for Background Functionality -->
            <input type="hidden" id="req_id" name="req_id">
            <input type="hidden" id="req_date" name="req_date">

            <!-- New/Create Mode Content -->
            <div id="createModeContent" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Requester Name *</label>
                        <input type="text" id="req_requester" name="req_requester" required placeholder="Enter requester name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Department *</label>
                        <select id="req_dept" name="req_dept" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Department</option>
                            <option value="Human Resource Department">Human Resource Department</option>
                            <option value="Core Transaction Office">Core Transaction Office</option>
                            <option value="Logistics Office">Logistics Office</option>
                            <option value="Administrative Office">Administrative Office</option>
                            <option value="Financial Department">Financial Department</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Requested Items *</label>
                    <div id="itemsContainer" class="space-y-2 mb-2">
                        <div class="flex gap-2 item-row">
                            <input type="text" name="items[]" required placeholder="Enter item name/description" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <button type="button" class="remove-item px-3 py-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                                <i class='bx bx-trash text-xl'></i>
                            </button>
                        </div>
                    </div>
                    <button type="button" id="addItemBtn" class="text-sm text-blue-600 hover:text-blue-800 font-semibold flex items-center gap-1">
                        <i class='bx bx-plus-circle'></i> Add Another Item
                    </button>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Notes / Remarks</label>
                    <textarea id="req_note" name="req_note" rows="3" placeholder="Additional details about the requisition..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>
            </div>

            <!-- View Mode Content (Professional Structured Layout) -->
            <div id="viewModeContent" class="hidden space-y-6">
                <div class="bg-gray-50 rounded-xl p-4 border border-gray-100 grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Requisition ID</p>
                        <p id="view_req_id" class="text-sm font-bold text-blue-600"></p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Date Requested</p>
                        <p id="view_req_date" class="text-sm font-bold text-gray-700"></p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Requester</p>
                        <p id="view_req_requester" class="text-sm font-bold text-gray-700"></p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Department</p>
                        <p id="view_req_dept" class="text-sm font-bold text-gray-700"></p>
                    </div>
                </div>

                <div>
                    <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider mb-2">Requested Items</p>
                    <div id="view_items_list" class="space-y-2">
                        <!-- Items will be injected here -->
                    </div>
                </div>

                <div>
                    <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider mb-1">Notes / Remarks</p>
                    <div id="view_req_note" class="text-sm text-gray-600 bg-gray-50 p-3 rounded-lg italic border-l-4 border-gray-200"></div>
                </div>

                <div class="flex items-center justify-between pt-4 border-t">
                    <p class="text-[10px] uppercase font-bold text-gray-400 tracking-wider">Current Status</p>
                    <div id="view_req_status"></div>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-6 border-t" id="modalActions">
                <button type="button" id="cancelModalBtn" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-semibold transition-colors">Close</button>
                <button type="submit" id="submitBtn" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold shadow-md transition-colors flex items-center gap-2">
                    <i class='bx bx-send'></i> Submit Requisition
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Update Status Modal -->
<div id="statusModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-[60]">
    <div class="bg-white rounded-xl p-6 w-full max-w-md shadow-2xl transition-all transform">
        <div class="flex justify-between items-center mb-4 border-b pb-2">
            <h3 class="text-lg font-bold text-gray-800">Update Status</h3>
        </div>
        <div class="space-y-4">
            <p class="text-sm text-gray-600">Change status for <span id="statusTargetId" class="font-bold text-blue-600"></span></p>
            <select id="newStatus" class="w-full px-4 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="Pending">Pending</option>
                <option value="Approved">Approved</option>
                <option value="Rejected">Rejected</option>
            </select>
            <div class="flex justify-end gap-3 pt-4">
                <button id="cancelStatusBtn" class="px-4 py-2 text-gray-700 font-semibold hover:bg-gray-50 rounded-lg">Cancel</button>
                <button id="updateStatusBtn" class="px-6 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 shadow-md transition-all">Update Status</button>
            </div>
        </div>
    </div>
</div>

<!-- Vendor Products Side Modal -->
<div id="vendorProductsModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-[55]">
    <div class="flex gap-6 w-full max-w-7xl px-4 items-start">
        <!-- Left Side: Vendor Product Modal -->
        <div class="bg-white rounded-xl p-6 w-full max-w-xl max-h-[85vh] overflow-y-auto shadow-2xl transition-all transform">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class='bx bx-package text-blue-600'></i>
                    <span id="sideModalVendorName">Vendor Products</span>
                </h3>
            </div>

            <!-- Vendor Selection moved here -->
            <div class="mb-4">
                <label class="block text-sm font-semibold text-gray-700 mb-1">Select Vendor Company</label>
                <div class="relative">
                    <i class='bx bx-buildings absolute left-3 top-1/2 -translate-y-1/2 text-gray-400'></i>
                    <select id="req_vendor_select" class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 appearance-none bg-white">
                        <option value="">Choose a Vendor Company...</option>
                    </select>
                </div>
            </div>
            
            <div class="mb-4 relative">
                <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400'></i>
                <input type="text" id="productSearch" placeholder="Search products..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 text-sm">
            </div>

            <div class="overflow-hidden border border-gray-100 rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-2 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Product</th>
                            <th class="px-4 py-2 text-left text-[10px] font-bold text-gray-500 uppercase tracking-wider">Description</th>
                            <th class="px-4 py-2 text-right text-[10px] font-bold text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody id="vendorProductsTableBody" class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-gray-500 text-sm">
                                Please select a vendor to see products
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right Side: New Purchase Requisition Modal Content -->
        <div id="sideRequisitionContainer" class="bg-white rounded-xl p-6 w-full max-w-2xl max-h-[85vh] overflow-y-auto shadow-2xl transition-all transform">
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h3 class="text-xl font-bold text-gray-800">New Purchase Requisition</h3>
                <button type="button" id="closeSideModal" class="text-gray-400 hover:text-gray-600">
                    <i class='bx bx-x text-2xl'></i>
                </button>
            </div>
            
            <form id="sideRequisitionForm" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Requester Name *</label>
                        <input type="text" id="side_req_requester" name="req_requester" required placeholder="Enter requester name" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Department *</label>
                        <select id="side_req_dept" name="req_dept" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Select Department</option>
                            <option value="Human Resource Department">Human Resource Department</option>
                            <option value="Core Transaction Office">Core Transaction Office</option>
                            <option value="Logistics Office">Logistics Office</option>
                            <option value="Administrative Office">Administrative Office</option>
                            <option value="Financial Department">Financial Department</option>
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Requested Items *</label>
                    <div id="sideItemsContainer" class="space-y-2 mb-2">
                        <!-- Items will be injected here via JS -->
                    </div>
                    <button type="button" id="sideAddItemBtn" class="text-sm text-blue-600 hover:text-blue-800 font-semibold flex items-center gap-1">
                        <i class='bx bx-plus-circle'></i> Add Another Item
                    </button>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-1">Notes / Remarks</label>
                    <textarea id="side_req_note" name="req_note" rows="3" placeholder="Additional details about the requisition..." class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
                </div>

                <div class="flex justify-end gap-3 pt-6 border-t">
                    <button type="button" id="sideCancelBtn" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 font-semibold transition-colors">Close</button>
                    <button type="submit" id="sideSubmitBtn" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold shadow-md transition-colors flex items-center gap-2">
                        <i class='bx bx-send'></i> Submit Requisition
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
(function() {
    const API_URL = '/api/v1/psm/requisitions';
    const JWT_TOKEN = localStorage.getItem('jwt');
    let currentPage = 1;
    let totalPages = 1;
    let currentFilters = {
        status: '',
        dept: '',
        search: '',
        per_page: 10
    };
    let currentRequisitions = [];

    // Swal Mixin
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

    // Elements
    const modal = document.getElementById('requisitionModal');
    const form = document.getElementById('requisitionForm');
    const addBtn = document.getElementById('addRequisitionBtn');
    const cancelBtn = document.getElementById('cancelModalBtn');
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const deptFilter = document.getElementById('deptFilter');
    const prevBtn = document.getElementById('purchasesPrevBtn');
    const nextBtn = document.getElementById('purchasesNextBtn');
    const pageDisplay = document.getElementById('purchasesPageDisplay');
    const pagerInfo = document.getElementById('purchasesPagerInfo');
    const addItemBtn = document.getElementById('addItemBtn');
    const itemsContainer = document.getElementById('itemsContainer');
    
    // Vendor Selection Elements
    const vendorSelect = document.getElementById('req_vendor_select');
    const vendorProductsModal = document.getElementById('vendorProductsModal');
    const closeSideModalBtn = document.getElementById('closeSideModal');
    const vendorProductsTableBody = document.getElementById('vendorProductsTableBody');
    const productSearchInput = document.getElementById('productSearch');
    const sideModalVendorName = document.getElementById('sideModalVendorName');
    
    // Side Requisition Elements
    const sideRequisitionForm = document.getElementById('sideRequisitionForm');
    const sideItemsContainer = document.getElementById('sideItemsContainer');
    const sideAddItemBtn = document.getElementById('sideAddItemBtn');
    const sideCancelBtn = document.getElementById('sideCancelBtn');
    
    let allVendors = [];
    let currentVendorProducts = [];
    let selectedItems = new Map(); // Track items and counts

    // Fetch Vendors for dropdown
    async function fetchVendors() {
        try {
            const response = await fetch('/api/v1/psm/vendors', {
                headers: { 'Authorization': `Bearer ${JWT_TOKEN}`, 'Accept': 'application/json' }
            });
            const result = await response.json();
            if (result.success) {
                allVendors = result.data;
                populateVendorDropdown(allVendors);
            }
        } catch (error) {
            console.error('Error fetching vendors:', error);
        }
    }

    function populateVendorDropdown(vendors) {
        vendorSelect.innerHTML = '<option value="">Choose a Vendor Company...</option>';
        vendors.forEach(vendor => {
            const option = document.createElement('option');
            option.value = vendor.ven_id; // Changed from vendor.id to vendor.ven_id
            option.textContent = `${vendor.ven_company_name} (${vendor.ven_type})`;
            vendorSelect.appendChild(option);
        });
    }

    // Fetch products for selected vendor
    async function fetchVendorProducts(vendorId) {
        try {
            const response = await fetch(`/api/v1/psm/product-management/by-vendor/${vendorId}`, {
                headers: { 'Authorization': `Bearer ${JWT_TOKEN}`, 'Accept': 'application/json' }
            });
            const result = await response.json();
            if (result.success) {
                currentVendorProducts = result.data;
                renderVendorProducts(currentVendorProducts);
            }
        } catch (error) {
            console.error('Error fetching vendor products:', error);
        }
    }

    function renderVendorProducts(products) {
        const searchTerm = productSearchInput.value.toLowerCase();
        const filtered = products.filter(p => 
            p.prod_name.toLowerCase().includes(searchTerm) || 
            (p.prod_desc && p.prod_desc.toLowerCase().includes(searchTerm)) ||
            p.prod_id.toLowerCase().includes(searchTerm)
        );

        if (filtered.length === 0) {
            vendorProductsTableBody.innerHTML = `
                <tr>
                    <td colspan="3" class="px-4 py-8 text-center text-gray-500 text-sm">
                        No products found matching "${searchTerm}"
                    </td>
                </tr>
            `;
            return;
        }

        vendorProductsTableBody.innerHTML = filtered.map(p => `
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-4 py-3 whitespace-nowrap">
                    <div class="text-xs font-bold text-gray-900">${p.prod_name}</div>
                    <div class="text-[10px] text-gray-500">${p.prod_id}</div>
                </td>
                <td class="px-4 py-3 text-[11px] text-gray-600 max-w-[150px] truncate" title="${p.prod_desc || ''}">
                    ${p.prod_desc || '-'}
                </td>
                <td class="px-4 py-3 text-right">
                    <button type="button" onclick="window.addItemToRequisition('${p.prod_name.replace(/'/g, "\\'")}')" 
                        class="p-1.5 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-lg transition-all active:scale-90">
                        <i class='bx bx-plus text-lg'></i>
                    </button>
                </td>
            </tr>
        `).join('');
    }

    window.addItemToRequisition = (itemName) => {
        if (selectedItems.has(itemName)) {
            selectedItems.set(itemName, selectedItems.get(itemName) + 1);
        } else {
            selectedItems.set(itemName, 1);
        }
        updateSideItemsContainer();
        Toast.fire({ icon: 'success', title: `Added ${itemName} to list` });
    };

    function updateSideItemsContainer() {
        sideItemsContainer.innerHTML = '';
        if (selectedItems.size === 0) {
            addEmptySideItemRow();
            return;
        }

        selectedItems.forEach((count, name) => {
            const displayValue = count > 1 ? `${name} (x${count})` : name;
            addSideItemRow(displayValue, false, name);
        });
    }

    function addSideItemRow(value = '', disabled = false, originalName = null) {
        const div = document.createElement('div');
        div.className = 'flex gap-2 item-row';
        div.dataset.originalName = originalName || value;
        div.innerHTML = `
            <input type="text" name="items[]" required value="${value}" ${disabled ? 'disabled' : ''} 
                placeholder="Enter item name/description" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            ${!disabled ? `
            <button type="button" class="remove-side-item px-3 py-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                <i class='bx bx-trash text-xl'></i>
            </button>` : ''}
        `;
        sideItemsContainer.appendChild(div);
    }

    function addEmptySideItemRow() {
        addSideItemRow('', false);
    }

    // Event Listeners for Vendor Selection
    vendorSelect.addEventListener('change', (e) => {
        const venId = e.target.value;
        if (venId) {
            const vendor = allVendors.find(v => v.ven_id == venId);
            sideModalVendorName.textContent = `${vendor.ven_company_name} Products`;
            fetchVendorProducts(venId);
        } else {
            vendorProductsTableBody.innerHTML = `
                <tr>
                    <td colspan="3" class="px-4 py-8 text-center text-gray-500 text-sm">
                        Please select a vendor to see products
                    </td>
                </tr>
            `;
            sideModalVendorName.textContent = 'Vendor Products';
        }
    });

    closeSideModalBtn.addEventListener('click', () => {
        vendorProductsModal.classList.add('hidden');
    });

    sideCancelBtn.addEventListener('click', () => {
        vendorProductsModal.classList.add('hidden');
    });

    productSearchInput.addEventListener('input', () => {
        renderVendorProducts(currentVendorProducts);
    });

    sideAddItemBtn.addEventListener('click', addEmptySideItemRow);

    sideItemsContainer.addEventListener('click', (e) => {
        if (e.target.closest('.remove-side-item')) {
            const row = e.target.closest('.item-row');
            const name = row.dataset.originalName;
            
            if (selectedItems.has(name)) {
                selectedItems.delete(name);
            }
            
            const rows = sideItemsContainer.querySelectorAll('.item-row');
            if (rows.length > 1) row.remove();
            else {
                row.remove();
                addEmptySideItemRow();
            }
        }
    });

    // Modified sideRequisitionForm submit
    sideRequisitionForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(sideRequisitionForm);
        const data = {
            req_id: generateReqID(),
            req_date: new Date().toISOString().split('T')[0],
            req_requester: formData.get('req_requester'),
            req_dept: formData.get('req_dept'),
            req_note: formData.get('req_note'),
            req_items: Array.from(formData.getAll('items[]')).filter(i => i.trim() !== ''),
            req_status: 'Pending'
        };

        if (data.req_items.length === 0) {
            Toast.fire({ icon: 'error', title: 'Please add at least one item' });
            return;
        }

        try {
            const response = await fetch(API_URL, {
                method: 'POST',
                headers: {
                    'Authorization': `Bearer ${JWT_TOKEN}`,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });
            const result = await response.json();
            if (result.success) {
                Toast.fire({ icon: 'success', title: 'Requisition submitted successfully' });
                vendorProductsModal.classList.add('hidden');
                fetchRequisitions(1);
            } else {
                Toast.fire({ icon: 'error', title: result.message || 'Failed to submit' });
            }
        } catch (error) {
            console.error('Error submitting requisition:', error);
            Toast.fire({ icon: 'error', title: 'Error connecting to server' });
        }
    });

    // Handle New Requisition Button
    addBtn.addEventListener('click', () => {
        sideRequisitionForm.reset();
        selectedItems.clear();
        updateSideItemsContainer();
        fetchVendors();
        vendorProductsModal.classList.remove('hidden');
    });

    // Original Modal Logic for View/Edit (remains for viewing)
    // Elements
    const statusModal = document.getElementById('statusModal');
    const cancelStatusBtn = document.getElementById('cancelStatusBtn');
    const updateStatusBtn = document.getElementById('updateStatusBtn');
    const newStatusSelect = document.getElementById('newStatus');
    const hideModal = () => modal.classList.add('hidden');
    cancelBtn.addEventListener('click', hideModal);
    let activeStatusId = null;

    // Generate Requisition ID
    function generateReqID() {
        const now = new Date();
        const dateStr = now.getFullYear().toString() + 
                       (now.getMonth() + 1).toString().padStart(2, '0') + 
                       now.getDate().toString().padStart(2, '0');
        const random = Math.random().toString(36).substring(2, 7).toUpperCase();
        return `REQN${dateStr}${random}`;
    }

    // Modal Display
    function showModal(mode = 'new', data = null) {
        form.reset();
        itemsContainer.innerHTML = '';
        const modalTitle = document.getElementById('modalTitle');
        const submitBtn = document.getElementById('submitBtn');
        const createContent = document.getElementById('createModeContent');
        const viewContent = document.getElementById('viewModeContent');
        const inputs = form.querySelectorAll('input, select, textarea');
        
        if (mode === 'new') {
            modalTitle.textContent = 'New Purchase Requisition';
            submitBtn.classList.remove('hidden');
            createContent.classList.remove('hidden');
            viewContent.classList.add('hidden');
            inputs.forEach(i => { if(i.id !== 'req_id') i.disabled = false; });
            document.getElementById('req_id').value = generateReqID();
            document.getElementById('req_date').value = new Date().toISOString().split('T')[0];
            addEmptyItemRow();
        } else if (mode === 'view') {
            modalTitle.textContent = `Requisition Details`;
            submitBtn.classList.add('hidden');
            createContent.classList.add('hidden');
            viewContent.classList.remove('hidden');
            
            // Structured View Mapping
            document.getElementById('view_req_id').textContent = data.req_id;
            document.getElementById('view_req_date').textContent = new Date(data.req_date).toLocaleDateString();
            document.getElementById('view_req_requester').textContent = data.req_requester;
            document.getElementById('view_req_dept').textContent = data.req_dept;
            document.getElementById('view_req_note').textContent = data.req_note || 'No additional notes.';
            
            // Status Badge in View
            const statusBadge = document.getElementById('view_req_status');
            statusBadge.innerHTML = `
                <span class="px-3 py-1.5 rounded-full text-sm font-bold flex items-center gap-1.5 ${getStatusBadgeClass(data.req_status)}">
                    ${getStatusIcon(data.req_status)}
                    ${data.req_status}
                </span>
            `;

            // Items List in View
            const items = Array.isArray(data.req_items) ? data.req_items : JSON.parse(data.req_items || '[]');
            const itemsList = document.getElementById('view_items_list');
            itemsList.innerHTML = items.map(item => `
                <div class="flex items-center gap-3 p-3 bg-white border border-gray-100 rounded-lg shadow-sm">
                    <i class='bx bx-check text-green-500 font-bold'></i>
                    <span class="text-sm text-gray-700 font-medium">${item}</span>
                </div>
            `).join('');
        }
        
        modal.classList.remove('hidden');
        setTimeout(() => modal.querySelector('div').classList.remove('scale-95'), 10);
    }

    function addEmptyItemRow() {
        addItemRow('', false);
    }

    function addItemRow(value = '', disabled = false) {
        const div = document.createElement('div');
        div.className = 'flex gap-2 item-row';
        div.innerHTML = `
            <input type="text" name="items[]" required value="${value}" ${disabled ? 'disabled' : ''} 
                placeholder="Enter item name/description" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            ${!disabled ? `
            <button type="button" class="remove-item px-3 py-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">
                <i class='bx bx-trash text-xl'></i>
            </button>` : ''}
        `;
        itemsContainer.appendChild(div);
    }

    addItemBtn.addEventListener('click', addEmptyItemRow);

    itemsContainer.addEventListener('click', (e) => {
        if (e.target.closest('.remove-item')) {
            const rows = itemsContainer.querySelectorAll('.item-row');
            if (rows.length > 1) e.target.closest('.item-row').remove();
            else Toast.fire({ icon: 'warning', title: 'At least one item is required' });
        }
    });

    // Fetch and Render
    async function fetchRequisitions(page = 1) {
        try {
            const params = new URLSearchParams({ page, ...currentFilters });
            const response = await fetch(`${API_URL}?${params}`, {
                headers: { 'Authorization': `Bearer ${JWT_TOKEN}`, 'Accept': 'application/json' }
            });
            const result = await response.json();
            
            if (result.success) {
                currentRequisitions = result.data;
                renderRequisitions(result.data);
                updatePager(result.meta);
                updateStats(result.stats);
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }

    function renderRequisitions(requisitions) {
        const tbody = document.getElementById('requisitionTableBody');
        if (!tbody) return;

        if (requisitions.length === 0) {
            tbody.innerHTML = `<tr><td colspan="7" class="px-6 py-12 text-center text-gray-500"><i class='bx bx-clipboard text-4xl mb-2 block text-gray-300'></i>No records found</td></tr>`;
            return;
        }

        tbody.innerHTML = requisitions.map(req => {
            const items = Array.isArray(req.req_items) ? req.req_items : JSON.parse(req.req_items || '[]');
            return `
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-800">${req.req_id}</td>
                    <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate" title="${items.join(', ')}">
                        ${items.length > 0 ? items[0] : 'No items'} 
                        ${items.length > 1 ? `<span class="text-blue-600 font-semibold">(+${items.length - 1})</span>` : ''}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-bold text-gray-800">${req.req_requester}</div>
                        <div class="text-xs text-gray-500">${req.req_dept}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${new Date(req.req_date).toLocaleDateString()}</td>
                    <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate" title="${req.req_note || ''}">${req.req_note || '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1.5 rounded-full text-sm font-bold flex items-center gap-1.5 w-fit ${getStatusBadgeClass(req.req_status)}">
                            ${getStatusIcon(req.req_status)}
                            ${req.req_status}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <div class="flex justify-end gap-2">
                            <button onclick="viewRequisition(${req.id})" title="View Details" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-all active:scale-90">
                                <i class='bx bx-show text-xl'></i>
                            </button>
                            <button onclick="openStatusUpdate(${req.id}, '${req.req_id}', '${req.req_status}')" title="Update Status" class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-all active:scale-90">
                                <i class='bx bx-edit text-xl'></i>
                            </button>
                            <button onclick="confirmDelete(${req.id}, '${req.req_id}')" title="Delete Requisition" class="p-2 text-red-600 hover:bg-red-50 rounded-lg transition-all active:scale-90">
                                <i class='bx bx-trash text-xl'></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
        }).join('');
    }

    function formatItemsList(items) {
        if (!items) return '-';
        const parsed = Array.isArray(items) ? items : JSON.parse(items || '[]');
        return parsed.join(', ');
    }

    function formatDate(dateStr) {
        if (!dateStr) return '-';
        return new Date(dateStr).toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
    }

    function getStatusClass(status) {
        switch (status.toLowerCase()) {
            case 'approved': return 'bg-green-100 text-green-700 border border-green-200';
            case 'pending': return 'bg-yellow-100 text-yellow-700 border border-yellow-200';
            case 'rejected': return 'bg-red-100 text-red-700 border border-red-200';
            default: return 'bg-gray-100 text-gray-700 border border-gray-200';
        }
    }

    function getStatusIcon(status) {
        switch (status.toLowerCase()) {
            case 'approved': return "<i class='bx bx-check-circle'></i>";
            case 'pending': return "<i class='bx bx-time-five'></i>";
            case 'rejected': return "<i class='bx bx-x-circle'></i>";
            default: return "<i class='bx bx-help-circle'></i>";
        }
    }

    function getStatusBadgeClass(status) {
        switch (status.toLowerCase()) {
            case 'approved': return "bg-green-700 text-white shadow-sm border border-green-800";
            case 'pending': return "bg-yellow-600 text-white shadow-sm border border-yellow-700";
            case 'rejected': return "bg-red-700 text-white shadow-sm border border-red-800";
            default: return "bg-gray-600 text-white border border-gray-700";
        }
    }

    function updateStats(stats) {
        if (!stats) return;
        document.getElementById('totalReqCount').textContent = stats.total || 0;
        document.getElementById('approvedReqCount').textContent = stats.approved || 0;
        document.getElementById('pendingReqCount').textContent = stats.pending || 0;
        document.getElementById('rejectedReqCount').textContent = stats.rejected || 0;

        // Pulse Notification for Pending
        const pendingPulse = document.getElementById('pendingBadgePulse');
        const pendingPulseCount = document.getElementById('pendingPulseCount');
        const pendingCount = parseInt(stats.pending) || 0;

        if (pendingCount > 0) {
            pendingPulseCount.textContent = pendingCount;
            pendingPulse.classList.remove('hidden');
        } else {
            pendingPulse.classList.add('hidden');
        }
    }

    function updatePager(meta) {
        if (!meta) return;
        currentPage = meta.current_page;
        totalPages = meta.last_page;
        
        pageDisplay.textContent = `${currentPage} / ${totalPages}`;
         pagerInfo.textContent = `Showing ${meta.from || 0}-${meta.to || 0} of ${meta.total}`;
         
         prevBtn.disabled = currentPage === 1;
        nextBtn.disabled = currentPage === totalPages;
    }

    // Action Functions
    window.filterByStatus = (status) => {
        currentFilters.status = status;
        statusFilter.value = status;
        fetchRequisitions(1);
    };

    window.viewRequisition = (id) => {
        const req = currentRequisitions.find(r => r.id === id);
        if (req) showModal('view', req);
    };

    window.openStatusUpdate = (id, reqId, currentStatus) => {
        activeStatusId = id;
        document.getElementById('statusTargetId').textContent = reqId;
        newStatusSelect.value = currentStatus;
        statusModal.classList.remove('hidden');
    };

    const hideStatusModal = () => {
        statusModal.classList.add('hidden');
        activeStatusId = null;
    };
    cancelStatusBtn.addEventListener('click', hideStatusModal);

    updateStatusBtn.addEventListener('click', async () => {
        const status = newStatusSelect.value;
        try {
            const response = await fetch(`${API_URL}/${activeStatusId}/status`, {
                method: 'PATCH',
                headers: { 
                    'Authorization': `Bearer ${JWT_TOKEN}`, 
                    'Content-Type': 'application/json',
                    'Accept': 'application/json' 
                },
                body: JSON.stringify({ status })
            });
            const result = await response.json();
            if (result.success) {
                hideStatusModal();
                Toast.fire({ icon: 'success', title: 'Status updated successfully' });
                fetchRequisitions(currentPage);
            }
        } catch (error) {
            Toast.fire({ icon: 'error', title: 'Failed to update status' });
        }
    });

    window.confirmDelete = (id, reqId) => {
        Swal.fire({
            title: 'Delete Requisition?',
            text: `Are you sure you want to delete ${reqId}? This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#ef4444',
            cancelButtonColor: '#6b7280',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, keep it'
        }).then(async (result) => {
            if (result.isConfirmed) {
                try {
                    const response = await fetch(`${API_URL}/${id}`, {
                        method: 'DELETE',
                        headers: { 'Authorization': `Bearer ${JWT_TOKEN}`, 'Accept': 'application/json' }
                    });
                    const res = await response.json();
                    if (res.success) {
                        Toast.fire({ icon: 'success', title: 'Requisition deleted successfully' });
                        fetchRequisitions(currentPage);
                    }
                } catch (error) {
                    Toast.fire({ icon: 'error', title: 'Failed to delete requisition' });
                }
            }
        });
    };

    // Filters & Pagination
    let searchTimeout;
    searchInput.addEventListener('input', (e) => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            currentFilters.search = e.target.value;
            fetchRequisitions(1);
        }, 500);
    });

    statusFilter.addEventListener('change', (e) => {
        currentFilters.status = e.target.value;
        fetchRequisitions(1);
    });

    deptFilter.addEventListener('change', (e) => {
        currentFilters.dept = e.target.value;
        fetchRequisitions(1);
    });

    prevBtn.addEventListener('click', () => { if (currentPage > 1) fetchRequisitions(currentPage - 1); });
    nextBtn.addEventListener('click', () => { if (currentPage < totalPages) fetchRequisitions(currentPage + 1); });

    // Submit New
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(form);
        const payload = {
            req_id: formData.get('req_id'),
            req_date: formData.get('req_date'),
            req_requester: formData.get('req_requester'),
            req_dept: formData.get('req_dept'),
            req_items: Array.from(formData.getAll('items[]')).filter(i => i.trim() !== ''),
            req_note: formData.get('req_note')
        };

        try {
            const response = await fetch(API_URL, {
                method: 'POST',
                headers: { 
                    'Authorization': `Bearer ${JWT_TOKEN}`, 
                    'Content-Type': 'application/json',
                    'Accept': 'application/json' 
                },
                body: JSON.stringify(payload)
            });
            const result = await response.json();
            if (result.success) {
                hideModal();
                Toast.fire({ icon: 'success', title: 'Requisition submitted successfully' });
                fetchRequisitions(1);
            }
        } catch (error) {
            Toast.fire({ icon: 'error', title: 'An error occurred during submission' });
        }
    });

    fetchRequisitions();
})();
</script>
