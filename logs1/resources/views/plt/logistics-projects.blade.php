<!-- resources/views/plt/logistics-projects.blade.php -->
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bxs-package'></i>Logistics Projects</h2>
    </div>
    <div class="text-right">
        <span class="text-md text-gray-600">Project Logistics Tracker</span>
    </div>
</div>

<!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div id="statCardTotal" class="stat-card bg-white rounded-2xl shadow-sm border border-gray-100 border-b-4 border-blue-500 p-6 relative overflow-hidden cursor-pointer hover:bg-blue-50 transition">
            <div class="flex items-center">
                <div class="p-3 bg-blue-50 rounded-xl text-blue-600">
                    <i class='bx bx-transfer-alt text-2xl'></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Total Movements</p>
                    <p id="statTotalProjects" class="text-2xl font-black text-gray-800">0</p>
                </div>
            </div>
        </div>
        <div id="statCardCompleted" class="stat-card bg-white rounded-2xl shadow-sm border border-gray-100 border-b-4 border-green-500 p-6 relative overflow-hidden cursor-pointer hover:bg-green-50 transition">
            <div class="flex items-center">
                <div class="p-3 bg-green-50 rounded-xl text-green-600">
                    <i class='bx bx-check-circle text-2xl'></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Completed</p>
                    <p id="statCompleted" class="text-2xl font-black text-gray-800">0</p>
                </div>
            </div>
        </div>
        <div id="statCardInProgress" class="stat-card bg-white rounded-2xl shadow-sm border border-gray-100 border-b-4 border-yellow-500 p-6 relative overflow-hidden cursor-pointer hover:bg-yellow-50 transition">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-50 rounded-xl text-yellow-600">
                    <i class='bx bx-time text-2xl'></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">In Progress</p>
                    <p id="statInProgress" class="text-2xl font-black text-gray-800">0</p>
                </div>
            </div>
        </div>
        <div id="statCardDelayed" class="stat-card bg-white rounded-2xl shadow-sm border border-gray-100 border-b-4 border-red-500 p-6 relative overflow-hidden cursor-pointer hover:bg-red-50 transition">
            <div class="flex items-center">
                <div class="p-3 bg-red-50 rounded-xl text-red-600">
                    <i class='bx bx-error-circle text-2xl'></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-bold text-gray-500 uppercase tracking-wider">Delayed</p>
                    <p id="statDelayed" class="text-2xl font-black text-gray-800">0</p>
                </div>
            </div>
        </div>
    </div>

<div class="bg-white rounded-lg shadow-lg p-6">
    <!-- Section Title -->
    <div class="flex items-center gap-3 mb-3">
        <i class='bx bx-transfer-alt text-2xl text-gray-800'></i>
        <h3 class="text-lg font-bold text-gray-800 tracking-tight leading-none">Movement Records</h3>
    </div>

    <!-- Action Bar -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex gap-4 items-center">
            <!-- Search -->
            <div class="relative">
                <input type="text" id="searchInput" placeholder="Search movements..." class="pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 w-64">
                <i class='bx bx-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400'></i>
            </div>
            
            <!-- Status Filter -->
            <select id="statusFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="in-progress">In Progress</option>
                <option value="delayed">Delayed</option>
                <option value="completed">Completed</option>
            </select>
            <!-- Movement Type Filter -->
            <select id="movementTypeFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Movement Type</option>
                <option value="Stock Transfer">Stock Transfer</option>
                <option value="Asset Transfer">Asset Transfer</option>
            </select>
            <!-- Item Type Filter -->
            <select id="itemTypeFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                <option value="">All Item Type</option>
                <option value="supplies">Supplies</option>
                <option value="automotive">Automotive</option>
                <option value="furniture">Furniture</option>
                <option value="equipment">Equipment</option>
            </select>
        </div>
        
        <div class="flex gap-2"></div>
    </div>

    <!-- Projects Table -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-800 font-bold text-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider whitespace-nowrap">Movement ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider whitespace-nowrap">Item Name</th>
                        <th class="px-6 py-3 text-right text-xs font-medium uppercase tracking-wider whitespace-nowrap">Units</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider whitespace-nowrap">Stored From</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider whitespace-nowrap">Stored To</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider whitespace-nowrap">Item Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider whitespace-nowrap">Movement Type</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider whitespace-nowrap">Project Start</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider whitespace-nowrap">Project End</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider whitespace-nowrap">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider whitespace-nowrap">Action</th>
                    </tr>
                </thead>
                <tbody id="projectsTableBody" class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="11" class="px-6 py-4 text-center text-gray-500">
                            <div class="flex justify-center items-center py-4">
                                <div class="loading loading-spinner mr-3"></div>
                                Loading movements...
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div id="projectsPager" class="flex items-center justify-between mt-4">
        <div id="projectsPagerInfo" class="text-sm text-gray-600"></div>
        <div class="join">
            <button class="btn btn-sm join-item" id="projectsPrevBtn" data-action="prev">Prev</button>
            <span class="btn btn-sm join-item" id="projectsPageDisplay">1 / 1</span>
            <button class="btn btn-sm join-item" id="projectsNextBtn" data-action="next">Next</button>
        </div>
    </div>
</div>

<!-- View Movement Modal -->
<div id="viewMovementModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-lg">
        <h3 class="text-xl font-bold mb-4">Movement Details</h3>
        <div id="viewMovementContent" class="text-sm text-gray-700 space-y-2"></div>
        <div class="mt-6 text-right">
            <button id="closeViewMovement" class="btn btn-primary">Close</button>
        </div>
    </div>
</div>

<!-- Update Status Modal -->
<div id="updateStatusModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-md">
        <h3 class="text-xl font-bold mb-4">Update Status</h3>
        <div class="space-y-4">
            <label class="block text-sm font-medium text-gray-700">Status</label>
            <select id="updateStatusSelect" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                <option value="pending">Pending</option>
                <option value="in-progress">In Progress</option>
                <option value="delayed">Delayed</option>
                <option value="completed">Completed</option>
            </select>
        </div>
        <div class="mt-6 flex justify-end gap-2">
            <button id="cancelUpdateStatus" class="btn">Cancel</button>
            <button id="confirmUpdateStatus" class="btn btn-primary">Update</button>
        </div>
    </div>
</div>

<!-- Set End Date Modal -->
<div id="setEndDateModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-md">
        <h3 class="text-xl font-bold mb-4">Set End Date</h3>
        <div class="space-y-4">
            <label class="block text-sm font-medium text-gray-700">End Date</label>
            <input type="date" id="endDateInput" class="w-full px-3 py-2 border border-gray-300 rounded-lg" />
        </div>
        <div class="mt-6 flex justify-end gap-2">
            <button id="cancelEndDate" class="btn">Cancel</button>
            <button id="confirmEndDate" class="btn btn-primary">Set</button>
        </div>
    </div>
</div>

<!-- Delete Confirm Modal -->
<div id="deleteMovementModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
    <div class="bg-white rounded-xl p-6 w-full max-w-md">
        <h3 class="text-xl font-bold mb-4">Delete Movement</h3>
        <p class="text-sm text-gray-700">Are you sure you want to delete this movement?</p>
        <div class="mt-6 flex justify-end gap-2">
            <button id="cancelDeleteMovement" class="btn">Cancel</button>
            <button id="confirmDeleteMovement" class="btn btn-error">Delete</button>
        </div>
    </div>
</div>

<!-- Add/Edit Project Modal -->
<div id="projectModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 id="projectModalTitle" class="text-xl font-semibold">New Project</h3>
            <button id="closeProjectModal" class="text-gray-500 hover:text-gray-700"><i class='bx bx-x text-2xl'></i></button>
        </div>
        <form id="projectForm">
            <input type="hidden" id="pro_id">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Project Name *</label>
                    <input type="text" id="pro_project_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div id="field_pro_status">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                    <select id="pro_status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="planning">Planning</option>
                        <option value="active">Active</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea id="pro_description" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Start Date *</label>
                    <input type="date" id="pro_start_date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">End Date</label>
                    <input type="date" id="pro_end_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div id="field_pro_budget_allocated">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Budget Allocated (₱) *</label>
                    <input type="number" id="pro_budget_allocated" min="0" step="0.01" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div id="field_pro_assigned_manager_id">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Manager ID *</label>
                    <input type="number" id="pro_assigned_manager_id" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" id="cancelProjectModal" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300">Cancel</button>
                <button type="submit" id="saveProjectBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Save Project</button>
            </div>
        </form>
</div>
</div>

<!-- View Project Modal -->
<div id="viewProjectModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Project Details</h3>
            <button id="closeViewProjectModal" class="text-gray-500 hover:text-gray-700"><i class='bx bx-x text-2xl'></i></button>
        </div>
        <div id="viewProjectContent" class="space-y-3"></div>
    </div>
</div>

<!-- Milestones Modal -->
<div id="milestoneModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-5xl max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Project Milestones</h3>
            <button id="closeMilestoneModal" class="text-gray-500 hover:text-gray-700"><i class='bx bx-x text-2xl'></i></button>
        </div>
        <div class="mb-4 flex justify-between items-center">
            <button id="addMilestoneBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-2 rounded-lg flex items-center gap-2">
                <i class='bx bx-plus'></i> Add Milestone
            </button>
        </div>
        <div>
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-800 font-bold text-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider whitespace-nowrap">Name</th>
                        <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider whitespace-nowrap">Target Date</th>
                        <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider whitespace-nowrap">Actual Date</th>
                        <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider whitespace-nowrap">Status</th>
                        <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider whitespace-nowrap">Priority</th>
                        <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody id="milestoneTableBody" class="bg-white divide-y divide-gray-200">
                    <tr><td colspan="6" class="px-4 py-3 text-center text-gray-500">Loading milestones...</td></tr>
                </tbody>
            </table>
        </div>
    </div>
 </div>

<!-- Milestone Edit Modal -->
<div id="milestoneEditModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-[60]">
    <div class="bg-white rounded-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Edit Milestone</h3>
            <button id="closeMilestoneEditModal" class="text-gray-500 hover:text-gray-700"><i class='bx bx-x text-2xl'></i></button>
        </div>
        <form id="milestoneForm">
            <input type="hidden" id="mile_id" />
            <input type="hidden" id="mile_project_id" />
            <div id="group_name" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                    <input type="text" id="mile_milestone_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Priority *</label>
                    <select id="mile_priority" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
            </div>
            <div id="group_dates" class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Target Date *</label>
                    <input type="date" id="mile_target_date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg" />
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Actual Date</label>
                    <input type="date" id="mile_actual_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg" />
                </div>
            </div>
            <div id="group_state" class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status *</label>
                    <select id="mile_status" required class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="pending">Pending</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="overdue">Overdue</option>
                    </select>
                </div>
            </div>
            <div class="flex justify-end gap-3 mt-4">
                <button type="button" id="cancelMilestone" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg">Cancel</button>
                <button type="submit" id="saveMilestoneBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg">Save Milestone</button>
            </div>
        </form>
    </div>
</div>

<!-- Milestone Delete Modal -->
<div id="milestoneDeleteModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-[60]">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Delete Milestone</h3>
            <button id="closeMilestoneDeleteModal" class="text-gray-500 hover:text-gray-700"><i class='bx bx-x text-2xl'></i></button>
        </div>
        <p class="text-gray-700 mb-4">This action cannot be undone. Do you want to proceed?</p>
        <div class="flex justify-end gap-3">
            <button id="cancelDeleteMilestone" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg">Cancel</button>
            <button id="confirmDeleteMilestone" class="px-4 py-2 bg-red-600 text-white rounded-lg">Delete</button>
        </div>
    </div>
</div>

<!-- Generate Report Modal -->

<script>
var API_BASE_URL = typeof API_BASE_URL !== 'undefined' ? API_BASE_URL : '<?php echo url('/api/v1'); ?>';
var PLT_API = `${API_BASE_URL}/plt`;
var CSRF_TOKEN = typeof CSRF_TOKEN !== 'undefined' ? CSRF_TOKEN : document.querySelector('meta[name="csrf-token"]').getAttribute('content');
var JWT_TOKEN = typeof JWT_TOKEN !== 'undefined' ? JWT_TOKEN : localStorage.getItem('jwt');

var projects = [];
var currentPage = 1;
var totalPages = 1;
var totalItems = 0;
var perPage = 10;

const els = {
    // Stats
    statTotalProjects: document.getElementById('statTotalProjects'),
    statCompleted: document.getElementById('statCompleted'),
    statInProgress: document.getElementById('statInProgress'),
    statDelayed: document.getElementById('statDelayed'),
    statCardTotal: document.getElementById('statCardTotal'),
    statCardCompleted: document.getElementById('statCardCompleted'),
    statCardInProgress: document.getElementById('statCardInProgress'),
    statCardDelayed: document.getElementById('statCardDelayed'),
    
    // Search and Filter
    searchInput: document.getElementById('searchInput'),
    statusFilter: document.getElementById('statusFilter'),
    movementTypeFilter: document.getElementById('movementTypeFilter'),
    itemTypeFilter: document.getElementById('itemTypeFilter'),
    
    // Buttons
    addProjectBtn: document.getElementById('addProjectBtn'),
    
    // Table
    tableBody: document.getElementById('projectsTableBody'),
    projectsPager: document.getElementById('projectsPager'),
    
    // Modals
    viewProjectModal: document.getElementById('viewProjectModal'),
    closeViewProjectModal: document.getElementById('closeViewProjectModal'),
    viewProjectContent: document.getElementById('viewProjectContent'),
    milestoneModal: document.getElementById('milestoneModal'),
    closeMilestoneModal: document.getElementById('closeMilestoneModal'),
    milestoneTableBody: document.getElementById('milestoneTableBody'),
    addMilestoneBtn: document.getElementById('addMilestoneBtn'),
    milestoneForm: document.getElementById('milestoneForm'),
    mileId: document.getElementById('mile_id'),
    mileProjectId: document.getElementById('mile_project_id'),
    mileName: document.getElementById('mile_milestone_name'),
    mileTargetDate: document.getElementById('mile_target_date'),
    mileActualDate: document.getElementById('mile_actual_date'),
    mileStatus: document.getElementById('mile_status'),
    milePriority: document.getElementById('mile_priority'),
    milestoneEditModal: document.getElementById('milestoneEditModal'),
    closeMilestoneEditModal: document.getElementById('closeMilestoneEditModal'),
    milestoneDeleteModal: document.getElementById('milestoneDeleteModal'),
    closeMilestoneDeleteModal: document.getElementById('closeMilestoneDeleteModal'),
    cancelDeleteMilestone: document.getElementById('cancelDeleteMilestone'),
    confirmDeleteMilestone: document.getElementById('confirmDeleteMilestone'),
    group_name: document.getElementById('group_name'),
    group_dates: document.getElementById('group_dates'),
    group_state: document.getElementById('group_state'),
    projectModal: document.getElementById('projectModal'),
    projectModalTitle: document.getElementById('projectModalTitle'),
    closeProjectModal: document.getElementById('closeProjectModal'),
    cancelProjectModal: document.getElementById('cancelProjectModal'),
    projectForm: document.getElementById('projectForm'),
    
    
    
    // Form fields
    proId: document.getElementById('pro_id'),
    proProjectName: document.getElementById('pro_project_name'),
    proDescription: document.getElementById('pro_description'),
    proStartDate: document.getElementById('pro_start_date'),
    proEndDate: document.getElementById('pro_end_date'),
    proStatus: document.getElementById('pro_status'),
    proBudgetAllocated: document.getElementById('pro_budget_allocated'),
    proAssignedManagerId: document.getElementById('pro_assigned_manager_id'),
    fieldProStatus: document.getElementById('field_pro_status'),
    fieldProBudgetAllocated: document.getElementById('field_pro_budget_allocated'),
    fieldProAssignedManagerId: document.getElementById('field_pro_assigned_manager_id')
};

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

function notify(message, type = 'info') { 
    Toast.fire({ icon: type, title: message }); 
}

function formatCurrency(amount) {
    return '₱' + Number(amount || 0).toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}

function formatDate(dateString) {
    if (!dateString) return '-';
    return new Date(dateString).toLocaleDateString('en-PH');
}

function formatDateRange(start, end) {
    const s = formatDate(start);
    const e = formatDate(end);
    return `${s} - ${e}`;
}

function pad2(n) { return String(n).padStart(2, '0'); }
function yyyymmdd(dateString) {
    const d = dateString ? new Date(dateString) : new Date();
    return `${d.getFullYear()}${pad2(d.getMonth()+1)}${pad2(d.getDate())}`;
}
function stableTail(id) {
    const letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const nums = '0123456789';
    const n = Number(id || 0);
    function pick(seed, s) { return s[seed % s.length]; }
    return `${pick(n, nums)}${pick(n+1, letters)}${pick(n+2, nums)}${pick(n+3, letters)}${pick(n+4, nums)}`;
}
function formatProjectCode(p) {
    const datePart = yyyymmdd(p.created_at || p.pro_start_date);
    const tail = stableTail(p.pro_id);
    return `PRJ${datePart}${tail}`;
}

function formatMovementCode(m) {
    const d = m.created_at ? new Date(m.created_at) : new Date();
    const datePart = `${d.getFullYear()}${pad2(d.getMonth() + 1)}${pad2(d.getDate())}`;
    const alphabet = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let seed = Number(m.mp_id || 0) * 1103515245 + 12345;
    let tail = '';
    for (let i = 0; i < 5; i++) {
        seed = (seed >>> 1) ^ (seed << 3);
        if (seed < 0) seed = -seed;
        tail += alphabet[seed % alphabet.length];
    }
    return `MVMT${datePart}${tail}`;
}

function movementStatusBadge(status) {
    const v = String(status || '').toLowerCase();
    const map = {
        'completed': { bg: 'bg-green-600 text-white', icon: 'bx bx-check-circle' },
        'in-progress': { bg: 'bg-blue-600 text-white', icon: 'bx bx-time-five' },
        'delayed': { bg: 'bg-red-600 text-white', icon: 'bx bx-error-circle' },
        'pending': { bg: 'bg-yellow-600 text-white', icon: 'bx bx-time-five' }
    };
    const m = map[v] || map.pending;
    const label = (v.replace('-', ' ') || 'pending').replace(/\b\w/g, c => c.toUpperCase());
    return `<span class="inline-flex items-center gap-1 px-3 py-1.5 rounded-full text-base font-extrabold ${m.bg}">
        <i class="${m.icon} text-lg"></i><span>${label}</span>
    </span>`;
}

function itemTypeBadge(type) {
    const t = String(type || '').toLowerCase();
    const map = {
        'supplies': { bg: 'bg-sky-100 text-sky-800', label: 'Supplies' },
        'automotive': { bg: 'bg-amber-100 text-amber-800', label: 'Automotive' },
        'furniture': { bg: 'bg-rose-100 text-rose-800', label: 'Furniture' },
        'equipment': { bg: 'bg-violet-100 text-violet-800', label: 'Equipment' }
    };
    const m = map[t] || { bg: 'bg-gray-100 text-gray-800', label: type || '-' };
    return `<span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold ${m.bg}">${m.label}</span>`;
}

function getStatusBadge(status) {
    const statusConfig = {
        'planning': { class: 'badge-info', icon: 'bx bx-planet', text: 'Planning' },
        'active': { class: 'badge-success', icon: 'bx bx-play-circle', text: 'Active' },
        'completed': { class: 'badge-primary', icon: 'bx bx-check-circle', text: 'Completed' },
        'cancelled': { class: 'badge-error', icon: 'bx bx-x-circle', text: 'Cancelled' }
    };
    
    const config = statusConfig[status] || statusConfig.planning;
    return `<span class="badge ${config.class} flex items-center justify-center gap-1 px-3 py-2 rounded-full text-sm font-medium">
        <i class="${config.icon}"></i>
        ${config.text}
    </span>`;
}

function getProgressBar(progress) {
    const progressValue = Math.min(100, Math.max(0, progress));
    let colorClass = 'progress-primary';
    
    if (progressValue < 30) colorClass = 'progress-error';
    else if (progressValue < 70) colorClass = 'progress-warning';
    else if (progressValue < 100) colorClass = 'progress-info';
    
    return `
        <div class="flex items-center gap-2">
            <progress class="progress ${colorClass} w-24" value="${progressValue}" max="100"></progress>
            <span class="text-sm text-gray-600">${Math.round(progressValue)}%</span>
        </div>
    `;
}

function getProgressFromStatus(status, progress) {
    if (status === 'completed') return 100;
    if (status === 'cancelled') return 0;
    if (typeof progress === 'number' && progress > 0) return progress;
    if (status === 'active') return 50;
    if (status === 'planning') return 10;
    return 0;
}

function getProjectProgress(project) {
    if (Array.isArray(project.milestones) && project.milestones.length > 0) {
        const total = project.milestones.length;
        const completed = project.milestones.filter(m => m.mile_status === 'completed').length;
        return Math.round((completed / total) * 100);
    }
    return getProgressFromStatus(project.pro_status, project.progress);
}

async function loadProjects(page = 1) {
    try {
        const search = els.searchInput.value;
        const status = els.statusFilter.value;
        const movetype = els.movementTypeFilter.value;
        const itemtype = els.itemTypeFilter.value;
        
        let url = `${PLT_API}/movement-project?page=${page}`;
        if (search) url += `&search=${encodeURIComponent(search)}`;
        if (status) url += `&status=${encodeURIComponent(status)}`;

        const response = await fetch(url, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            credentials: 'include'
        });

        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        const result = await response.json();
        
        const payload = result.data !== undefined ? result.data : result;
        let data = Array.isArray(payload?.data) ? payload.data : (Array.isArray(payload) ? payload : []);
        if (movetype) {
            data = data.filter(x => String(x.mp_movement_type || '') === movetype);
        }
        if (itemtype) {
            data = data.filter(x => String(x.mp_item_type || '').toLowerCase() === itemtype.toLowerCase());
        }
        projects = data;
        currentPage = payload?.current_page || 1;
        totalPages = payload?.last_page || 1;
        totalItems = projects.length || (typeof payload?.total === 'number' ? payload.total : 0);
        perPage = typeof payload?.per_page === 'number' ? payload.per_page : perPage;
        renderProjects();
        renderProjectsPager(totalItems, totalPages);
        updateMovementStats();
    } catch (e) {
        els.tableBody.innerHTML = `<tr><td colspan="11" class="px-6 py-4 text-center text-red-600">Failed to load movements: ${e.message}</td></tr>`;
        notify('Error loading movements', 'error');
    }
}

function updateMovementStats() {
    const total = projects.length;
    const completed = projects.filter(m => String(m.mp_status || '').toLowerCase() === 'completed').length;
    const inProgress = projects.filter(m => String(m.mp_status || '').toLowerCase() === 'in-progress').length;
    const delayed = projects.filter(m => String(m.mp_status || '').toLowerCase() === 'delayed').length;
    if (els.statTotalProjects) els.statTotalProjects.textContent = total;
    if (els.statCompleted) els.statCompleted.textContent = completed;
    if (els.statInProgress) els.statInProgress.textContent = inProgress;
    if (els.statDelayed) els.statDelayed.textContent = delayed;
}

function renderProjects() {
    if (projects.length === 0) {
        els.tableBody.innerHTML = `<tr><td colspan="11" class="px-6 py-4 text-center text-gray-500">No records found</td></tr>`;
        return;
    }
    els.tableBody.innerHTML = projects.map(m => {
        const code = formatMovementCode(m);
        const itemType = String(m.mp_item_type || '').toLowerCase();
        const displayItemType = itemType ? itemType.replace(/\b\w/g, c => c.toUpperCase()) : '-';
        return `
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap font-mono text-sm">${code}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${m.mp_item_name || '-'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-right">${m.mp_unit_transfer ?? 0}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${m.mp_stored_from || '-'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${m.mp_stored_to || '-'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">${itemTypeBadge(displayItemType)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${m.mp_movement_type || '-'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${formatDate(m.mp_project_start)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">${formatDate(m.mp_project_end)}</td>
                <td class="px-6 py-4 whitespace-nowrap">${movementStatusBadge(m.mp_status)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <div class="flex items-center gap-3">
                        <button class="text-blue-600 hover:text-blue-800" title="View" data-action="view" data-id="${m.mp_id}">
                            <i class='bx bx-show-alt text-xl'></i>
                        </button>
                        <button class="text-amber-600 hover:text-amber-800" title="Update Status" data-action="status" data-id="${m.mp_id}">
                            <i class='bx bx-edit text-xl'></i>
                        </button>
                        <button class="text-purple-600 hover:text-purple-800" title="Set End Date" data-action="end" data-id="${m.mp_id}">
                            <i class='bx bx-calendar-check text-xl'></i>
                        </button>
                        <button class="text-red-600 hover:text-red-800" title="Delete" data-action="delete" data-id="${m.mp_id}">
                            <i class='bx bx-trash text-xl'></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
}

function renderProjectsPager(total, pages) {
    const info = document.getElementById('projectsPagerInfo');
    const display = document.getElementById('projectsPageDisplay');
    const start = total === 0 ? 0 : ((currentPage - 1) * perPage) + 1;
    const end = Math.min(currentPage * perPage, total);
    if (info) info.textContent = `Showing ${start}-${end} of ${total}`;
    if (display) display.textContent = `${currentPage} / ${Math.max(1, pages)}`;
    const prev = document.getElementById('projectsPrevBtn');
    const next = document.getElementById('projectsNextBtn');
    if (prev) prev.disabled = currentPage <= 1;
    if (next) next.disabled = currentPage >= pages;
}

function openProjectModal(edit = false, project = null) {
    els.projectModalTitle.textContent = edit ? 'Edit Project' : 'New Project';
    els.proId.value = edit && project ? project.pro_id : '';
    els.proProjectName.value = edit && project ? (project.pro_project_name || '') : '';
    els.proDescription.value = edit && project ? (project.pro_description || '') : '';
    els.proStartDate.value = edit && project && project.pro_start_date ? String(project.pro_start_date).substring(0,10) : '';
    els.proEndDate.value = edit && project && project.pro_end_date ? String(project.pro_end_date).substring(0,10) : '';
    if (els.fieldProStatus) els.fieldProStatus.classList.add('hidden');
    if (edit) {
        if (els.fieldProBudgetAllocated) els.fieldProBudgetAllocated.classList.add('hidden');
        if (els.fieldProAssignedManagerId) els.fieldProAssignedManagerId.classList.add('hidden');
    } else {
        if (els.fieldProBudgetAllocated) els.fieldProBudgetAllocated.classList.remove('hidden');
        if (els.fieldProAssignedManagerId) els.fieldProAssignedManagerId.classList.remove('hidden');
    }
    els.proBudgetAllocated.value = edit && project ? (project.pro_budget_allocated || '') : '';
    els.proAssignedManagerId.value = edit && project ? (project.pro_assigned_manager_id || '') : '';
    els.projectModal.classList.remove('hidden');
}

function closeProjectModal() {
    els.projectModal.classList.add('hidden');
    els.projectForm.reset();
}

async function saveProject(e) {
    e.preventDefault();
    
    const id = els.proId.value;
    const creating = !id;
    const payload = {
        pro_project_name: els.proProjectName.value.trim(),
        pro_description: els.proDescription.value.trim() || null,
        pro_start_date: els.proStartDate.value,
        pro_end_date: els.proEndDate.value || null
    };
    if (creating) {
        payload.pro_status = 'planning';
        payload.pro_budget_allocated = els.proBudgetAllocated.value ? parseFloat(els.proBudgetAllocated.value) : 0;
        payload.pro_assigned_manager_id = parseInt(els.proAssignedManagerId.value);
    }

    if (!payload.pro_project_name) {
        notify('Please enter project name', 'error');
        return;
    }

    try {
        const url = id ? `${PLT_API}/projects/${id}` : `${PLT_API}/projects`;
        const method = id ? 'PUT' : 'POST';
        
        const response = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            body: JSON.stringify(payload),
            credentials: 'include'
        });

        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        const result = await response.json();
        
        if (result.success) {
            notify(result.message, 'success');
        }
    } catch (e) {
        notify('Error saving project: ' + e.message, 'error');
    }
}

async function deleteProject(id) {
    const confirmResult = await Swal.fire({
        title: 'Delete Project?',
        text: 'This action cannot be undone',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Delete',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#dc2626'
    });

    if (!confirmResult.isConfirmed) return;

    try {
        const response = await fetch(`${PLT_API}/projects/${id}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            credentials: 'include'
        });

        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        const result = await response.json();
        
        if (result.success) {
            notify(result.message, 'success');
        }
    } catch (e) {
        notify('Error deleting project: ' + e.message, 'error');
    }
}

async function cancelProject(id) {
    const confirmResult = await Swal.fire({
        title: 'Cancel Project?',
        text: 'This will set the project status to Cancelled',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Cancel Project',
        cancelButtonText: 'Keep',
        confirmButtonColor: '#374151'
    });
    if (!confirmResult.isConfirmed) return;
    try {
        const response = await fetch(`${PLT_API}/projects/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            credentials: 'include',
            body: JSON.stringify({ pro_status: 'cancelled' })
        });
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        const result = await response.json();
        if (result.success) {
            notify('Project cancelled', 'success');
        }
    } catch (e) {
        notify('Error cancelling project: ' + e.message, 'error');
    }
}

function openReportModal() {
    els.reportModal.classList.remove('hidden');
    // Set default dates
    const today = new Date().toISOString().split('T')[0];
    els.startDate.value = '';
    els.endDate.value = today;
}

function closeReportModal() {
    els.reportModal.classList.add('hidden');
    els.reportForm.reset();
    els.customDateRange.classList.add('hidden');
}

function closeViewProjectModal() {
    els.viewProjectModal.classList.add('hidden');
}

async function viewProject(id) {
    try {
        const response = await fetch(`${PLT_API}/projects/${id}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            credentials: 'include'
        });
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        const result = await response.json();
        if (!result.success || !result.data) throw new Error(result.message || 'Not found');
        const p = result.data;
        const content = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <div class="text-sm text-gray-500">Project ID</div>
                    <div class="font-mono">${formatProjectCode(p)}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Manager ID</div>
                    <div>${p.pro_assigned_manager_id || ''}</div>
                </div>
                <div class="md:col-span-2">
                    <div class="text-sm text-gray-500">Name</div>
                    <div class="font-semibold">${p.pro_project_name || ''}</div>
                </div>
                <div class="md:col-span-2">
                    <div class="text-sm text-gray-500">Description</div>
                    <div class="text-gray-700">${p.pro_description || ''}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Date Range</div>
                    <div>${formatDateRange(p.pro_start_date, p.pro_end_date)}</div>
                </div>
                <div>
                    <div class="text-sm text-gray-500">Budget</div>
                    <div>${formatCurrency(p.pro_budget_allocated)}</div>
                </div>
                <div class="md:col-span-2">
                    <div class="flex items-center gap-3 whitespace-nowrap">
                        ${getStatusBadge(p.pro_status)}
                        ${getProgressBar(getProjectProgress(p))}
                    </div>
                </div>
            </div>
        `;
        els.viewProjectContent.innerHTML = content;
        els.viewProjectModal.classList.remove('hidden');
    } catch (e) {
        notify('Error loading project: ' + e.message, 'error');
    }
}

function openMilestoneModal(projectId) {
    els.milestoneModal.classList.remove('hidden');
    els.milestoneTableBody.innerHTML = `<tr><td colspan="6" class="px-4 py-3 text-center text-gray-500">Loading milestones...</td></tr>`;
    els.mileProjectId.value = projectId;
    loadMilestones(projectId);
}

function closeMilestoneModal() {
    els.milestoneModal.classList.add('hidden');
}

async function loadMilestones(projectId) {
    try {
        const response = await fetch(`${PLT_API}/projects/${projectId}/milestones`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            credentials: 'include'
        });
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        const result = await response.json();
        if (!result.success) throw new Error(result.message || 'Failed to load');
        const data = result.data || [];
        if (data.length === 0) {
            els.milestoneTableBody.innerHTML = `<tr><td colspan="6" class="px-4 py-3 text-center text-gray-500">No milestones yet</td></tr>`;
            return;
        }
        els.milestoneTableBody.innerHTML = '';
        let prevCompleted = true;
        data.forEach((m, idx) => {
            const tr = document.createElement('tr');
            const accessible = idx === 0 ? true : prevCompleted;
            const rowClass = accessible ? '' : 'opacity-60 bg-gray-100 cursor-not-allowed';
            const disableAttr = accessible ? '' : 'disabled';
            // Auto mark in progress visually if actual date exists
            const displayStatus = (m.mile_actual_date && m.mile_status !== 'completed') ? 'in_progress' : m.mile_status;
            tr.className = rowClass;
            const isCompleted = displayStatus === 'completed';
            tr.innerHTML = `
                <td class="px-4 py-2 whitespace-nowrap">${m.mile_milestone_name}</td>
                <td class="px-4 py-2 whitespace-nowrap">${formatDate(m.mile_target_date)}</td>
                <td class="px-4 py-2 whitespace-nowrap">${formatDate(m.mile_actual_date)}</td>
                <td class="px-4 py-2 whitespace-nowrap">${getMilestoneStatusBadge(displayStatus)}</td>
                <td class="px-4 py-2 whitespace-nowrap">${getPriorityChip(m.mile_priority)}</td>
                <td class="px-4 py-2 whitespace-nowrap">
                    ${isCompleted ? '' : `<button class="text-indigo-600 p-2" ${disableAttr} title="Set Dates" data-action="mile_set_dates" data-id="${m.mile_id}"><i class='bx bx-calendar-event'></i></button>`}
                    ${isCompleted ? '' : `<button class="text-blue-600 p-2" ${disableAttr} title="Update Status" data-action="mile_update_status" data-id="${m.mile_id}"><i class='bx bx-adjust'></i></button>`}
                    ${isCompleted ? '' : `<button class="text-warning p-2" ${disableAttr} title="Edit Milestone" data-action="mile_basic_edit" data-id="${m.mile_id}"><i class='bx bx-edit'></i></button>`}
                    <button class="text-error p-2" ${disableAttr && !isCompleted ? disableAttr : ''} title="Delete" data-action="delete_mile" data-id="${m.mile_id}"><i class='bx bx-trash'></i></button>
                </td>
            `;
            els.milestoneTableBody.appendChild(tr);
            prevCompleted = (displayStatus === 'completed');
        });
    } catch (e) {
        els.milestoneTableBody.innerHTML = `<tr><td colspan="6" class="px-4 py-3 text-center text-red-600">Failed to load milestones: ${e.message}</td></tr>`;
    }
}

function openMilestoneForm(edit = false, milestone = null, mode = 'basic') {
    els.mileId.value = edit && milestone ? milestone.mile_id : '';
    els.mileName.value = edit && milestone ? (milestone.mile_milestone_name || '') : '';
    els.mileTargetDate.value = edit && milestone && milestone.mile_target_date ? String(milestone.mile_target_date).substring(0,10) : '';
    els.mileActualDate.value = edit && milestone && milestone.mile_actual_date ? String(milestone.mile_actual_date).substring(0,10) : '';
    els.mileStatus.value = edit && milestone ? (milestone.mile_status || 'pending') : 'pending';
    els.milePriority.value = edit && milestone ? (milestone.mile_priority || 'medium') : 'medium';
    window._mileEditMode = mode;
    els.group_name.classList.toggle('hidden', mode !== 'basic');
    els.group_dates.classList.toggle('hidden', mode !== 'dates');
    els.group_state.classList.toggle('hidden', mode !== 'status');
    setMilestoneStatusOptions(mode);
    openMilestoneEditModal();
}

async function saveMilestone(e) {
    e.preventDefault();
    const id = els.mileId.value;
    const projectId = els.mileProjectId.value;
    let payload = {};
    const mode = window._mileEditMode || 'basic';
    if (mode === 'basic') {
        payload = {
            mile_milestone_name: els.mileName.value.trim(),
            mile_priority: els.milePriority.value
        };
        if (!payload.mile_milestone_name) {
            notify('Please fill required fields', 'error');
            return;
        }
    } else if (mode === 'dates') {
        payload = {
            mile_target_date: els.mileTargetDate.value,
            mile_actual_date: els.mileActualDate.value || null
        };
        if (!payload.mile_target_date) {
            notify('Please fill required fields', 'error');
            return;
        }
        if (payload.mile_actual_date) {
            payload.mile_status = 'in_progress';
        }
    } else if (mode === 'status') {
        payload = {
            mile_status: els.mileStatus.value
        };
        if (!payload.mile_status) {
            notify('Please select status', 'error');
            return;
        }
    }
    try {
        const url = id ? `${PLT_API}/milestones/${id}` : `${PLT_API}/projects/${projectId}/milestones`;
        const method = id ? 'PUT' : 'POST';
        const response = await fetch(url, {
            method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            credentials: 'include',
            body: JSON.stringify(payload)
        });
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        const result = await response.json();
        if (!result.success) throw new Error(result.message || 'Failed');
        notify(result.message, 'success');
        closeMilestoneEditModal();
        await loadMilestones(projectId);
        await loadProjects(currentPage);
    } catch (e) {
        notify('Error saving milestone: ' + e.message, 'error');
    }
}

async function deleteMilestone(id) {
    const projectId = els.mileProjectId.value;
    try {
        const response = await fetch(`${PLT_API}/milestones/${id}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            credentials: 'include'
        });
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        const result = await response.json();
        if (!result.success) throw new Error(result.message || 'Failed');
        notify(result.message, 'success');
        closeMilestoneDeleteModal();
        await loadMilestones(projectId);
        await loadProjects(currentPage);
    } catch (e) {
        notify('Error deleting milestone: ' + e.message, 'error');
    }
}

function openMilestoneEditModal() {
    els.milestoneEditModal.classList.remove('hidden');
}
function closeMilestoneEditModal() {
    els.milestoneEditModal.classList.add('hidden');
}
function openMilestoneDeleteModal(id) {
    window._milePendingDeleteId = id;
    els.milestoneDeleteModal.classList.remove('hidden');
}
function closeMilestoneDeleteModal() {
    window._milePendingDeleteId = null;
    els.milestoneDeleteModal.classList.add('hidden');
}

function getMilestoneStatusBadge(status) {
    const config = {
        'pending': { class: 'badge-info', icon: 'bx bx-time', text: 'Pending' },
        'in_progress': { class: 'badge-warning', icon: 'bx bx-run', text: 'In Progress' },
        'completed': { class: 'badge-success', icon: 'bx bx-check-circle', text: 'Completed' },
        'overdue': { class: 'badge-error', icon: 'bx bx-error', text: 'Overdue' }
    }[status] || { class: 'badge', icon: 'bx bx-time', text: status };
    return `<span class="badge ${config.class} flex items-center gap-1 px-3 py-2 rounded-full text-sm"><i class="${config.icon}"></i>${config.text}</span>`;
}

function getPriorityChip(priority) {
    const m = {
        'low': { bg: 'bg-green-100', fg: 'text-green-800', text: 'Low' },
        'medium': { bg: 'bg-yellow-100', fg: 'text-yellow-800', text: 'Medium' },
        'high': { bg: 'bg-red-100', fg: 'text-red-800', text: 'High' }
    }[priority] || { bg: 'bg-gray-100', fg: 'text-gray-800', text: priority };
    return `<span class="inline-flex items-center px-3 py-1 rounded-full text-xs ${m.bg} ${m.fg}">${m.text}</span>`;
}

function setMilestoneStatusOptions(mode) {
    if (mode === 'status') {
        els.mileStatus.innerHTML = `
            <option value="pending">Pending</option>
            <option value="in_progress">In Progress</option>
            <option value="completed">Completed</option>
        `;
    } else {
        els.mileStatus.innerHTML = `
            <option value="pending">Pending</option>
            <option value="in_progress">In Progress</option>
            <option value="completed">Completed</option>
            <option value="overdue">Overdue</option>
        `;
    }
}


// Movement actions
let selectedMovementId = null;
function openModal(el) { el.classList.remove('hidden'); el.classList.add('flex'); }
function closeModal(el) { el.classList.add('hidden'); el.classList.remove('flex'); }

function handleTableClick(e) {
    const btn = e.target.closest('button[data-action]');
    if (!btn) return;
    const id = btn.getAttribute('data-id');
    const action = btn.getAttribute('data-action');
    selectedMovementId = id;
    const movement = projects.find(p => String(p.mp_id) === String(id));
    if (!movement) return;
    if (action === 'view') {
        const html = `
            <div><span class="font-semibold">Movement ID:</span> ${formatMovementCode(movement)}</div>
            <div><span class="font-semibold">Item Name:</span> ${movement.mp_item_name || '-'}</div>
            <div><span class="font-semibold">Units:</span> ${movement.mp_unit_transfer ?? 0}</div>
            <div><span class="font-semibold">Stored From:</span> ${movement.mp_stored_from || '-'}</div>
            <div><span class="font-semibold">Stored To:</span> ${movement.mp_stored_to || '-'}</div>
            <div><span class="font-semibold">Item Type:</span> ${movement.mp_item_type || '-'}</div>
            <div><span class="font-semibold">Movement Type:</span> ${movement.mp_movement_type || '-'}</div>
            <div><span class="font-semibold">Status:</span> ${(String(movement.mp_status||'').replace('-', ' '))}</div>
            <div><span class="font-semibold">Project Start:</span> ${formatDate(movement.mp_project_start)}</div>
            <div><span class="font-semibold">Project End:</span> ${formatDate(movement.mp_project_end)}</div>
            <div><span class="font-semibold">Created At:</span> ${formatDate(movement.created_at)}</div>
        `;
        document.getElementById('viewMovementContent').innerHTML = html;
        openModal(document.getElementById('viewMovementModal'));
        return;
    }
    if (action === 'status') {
        const sel = document.getElementById('updateStatusSelect');
        sel.value = String(movement.mp_status || 'pending');
        openModal(document.getElementById('updateStatusModal'));
        return;
    }
    if (action === 'end') {
        const input = document.getElementById('endDateInput');
        input.value = movement.mp_project_end ? String(movement.mp_project_end).substring(0,10) : '';
        openModal(document.getElementById('setEndDateModal'));
        return;
    }
    if (action === 'delete') {
        openModal(document.getElementById('deleteMovementModal'));
        return;
    }
}
els.tableBody.addEventListener('click', handleTableClick);

// View modal close
document.getElementById('closeViewMovement').addEventListener('click', () => {
    closeModal(document.getElementById('viewMovementModal'));
});

// Update Status modal actions
document.getElementById('cancelUpdateStatus').addEventListener('click', () => {
    closeModal(document.getElementById('updateStatusModal'));
});
document.getElementById('confirmUpdateStatus').addEventListener('click', async () => {
    try {
        const status = document.getElementById('updateStatusSelect').value;
        const res = await fetch(`${PLT_API}/movement-project/${selectedMovementId}/status`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            body: JSON.stringify({ mp_status: status }),
            credentials: 'include'
        });
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        closeModal(document.getElementById('updateStatusModal'));
        notify('Status updated', 'success');
        loadProjects(currentPage);
    } catch (e) {
        notify('Failed to update status', 'error');
    }
});

// Set End Date modal actions
document.getElementById('cancelEndDate').addEventListener('click', () => {
    closeModal(document.getElementById('setEndDateModal'));
});
document.getElementById('confirmEndDate').addEventListener('click', async () => {
    try {
        const endDate = document.getElementById('endDateInput').value;
        if (!endDate) { notify('Please pick an end date', 'error'); return; }
        const res = await fetch(`${PLT_API}/movement-project/${selectedMovementId}/end-date`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            body: JSON.stringify({ mp_project_end: endDate }),
            credentials: 'include'
        });
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        closeModal(document.getElementById('setEndDateModal'));
        notify('End date set', 'success');
        loadProjects(currentPage);
    } catch (e) {
        notify('Failed to set end date', 'error');
    }
});

// Delete modal actions
document.getElementById('cancelDeleteMovement').addEventListener('click', () => {
    closeModal(document.getElementById('deleteMovementModal'));
});
document.getElementById('confirmDeleteMovement').addEventListener('click', async () => {
    try {
        const res = await fetch(`${PLT_API}/movement-project/${selectedMovementId}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            credentials: 'include'
        });
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        closeModal(document.getElementById('deleteMovementModal'));
        notify('Movement deleted', 'success');
        loadProjects(currentPage);
    } catch (e) {
        notify('Failed to delete movement', 'error');
    }
});

// Pager interactions
document.getElementById('projectsPager').addEventListener('click', function(ev){
    const btn = ev.target.closest('button[data-action]');
    if(!btn) return;
    const act = btn.getAttribute('data-action');
    if(act === 'prev' && currentPage > 1){ loadProjects(currentPage - 1); }
    if(act === 'next' && currentPage < totalPages){ loadProjects(currentPage + 1); }
});

function initLogisticsProjects() {
    // Search and filter
    els.searchInput.addEventListener('input', debounce(() => loadProjects(1), 300));
    els.statusFilter.addEventListener('change', () => loadProjects(1));
    els.movementTypeFilter.addEventListener('change', () => loadProjects(1));
    els.itemTypeFilter.addEventListener('change', () => loadProjects(1));
    // Stat cards click to filter
    if (els.statCardTotal) els.statCardTotal.addEventListener('click', () => { els.statusFilter.value=''; loadProjects(1); });
    if (els.statCardCompleted) els.statCardCompleted.addEventListener('click', () => { els.statusFilter.value='completed'; loadProjects(1); });
    if (els.statCardInProgress) els.statCardInProgress.addEventListener('click', () => { els.statusFilter.value='in-progress'; loadProjects(1); });
    if (els.statCardDelayed) els.statCardDelayed.addEventListener('click', () => { els.statusFilter.value='delayed'; loadProjects(1); });
    // Load initial data
    loadProjects();
}

// Utility function for debouncing
function debounce(func, wait) {
    let timeout;
    return function executedFunction(...args) {
        const later = () => {
            clearTimeout(timeout);
            func(...args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Initialize when DOM is loaded
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initLogisticsProjects);
} else {
    initLogisticsProjects();
}
</script>
