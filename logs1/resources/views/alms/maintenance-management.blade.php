<!-- resources/views/alms/maintenance-management.blade.php -->
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bxs-wrench'></i>Maintenance Management</h2>
    </div>
    <div class="text-right">
        <span class="text-md text-gray-600">Asset Lifecycle & Maintenance</span>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">Maintenance Schedule</h3>
        <div class="flex gap-2 whitespace-nowrap">
            <button id="repairPersonnelBtn" class="btn btn-warning">
                <i class='bx bxs-hard-hat mr-2 text-xl'></i>Repair Personnel List
            </button>
            <button id="requestMaintenanceBtn" class="btn btn-success relative">
                <i class='bx bxs-traffic-cone mr-2 text-xl'></i>Request Maintenance
                <span id="requestMaintenanceCountPing" class="absolute -top-2 -right-2 z-0 w-6 h-6 rounded-full bg-red-500 opacity-50 animate-ping hidden"></span>
                <span id="requestMaintenanceCountBadge" class="badge badge-error rounded-full w-6 h-6 text-[10px] leading-none flex items-center justify-center absolute -top-2 -right-2 text-white hidden">0</span>
            </button>
        </div>
    </div>

    <div id="scheduleFromRequestModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-[60]">
        <div class="bg-white rounded-lg p-6 w-full max-w-3xl">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-lg font-semibold">Schedule Maintenance</h4>
                <button id="closeScheduleFromRequestModal" class="text-gray-500 hover:text-gray-700"><i class='bx bx-x text-2xl'></i></button>
            </div>
            <form id="scheduleFromRequestForm" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm text-gray-700">Asset Name</label>
                        <input type="text" id="sch_req_asset_name" class="w-full px-3 py-2 border border-gray-300 rounded-lg" readonly>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700">Request Date</label>
                        <input type="date" id="sch_req_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg" readonly>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700">Priority</label>
                        <input type="text" id="sch_req_priority" class="w-full px-3 py-2 border border-gray-300 rounded-lg" readonly>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700">Assigned Repair Personnel</label>
                        <select id="sch_req_repair_personnel_id" class="w-full px-3 py-2 border border-gray-300 rounded-lg"></select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700">Scheduled Date</label>
                        <input type="date" id="sch_req_scheduled_date" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>
                    </div>
                    
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" id="cancelScheduleFromRequest" class="px-4 py-2 bg-gray-200 rounded-lg">Cancel</button>
                    <button type="submit" id="saveScheduleFromRequestBtn" class="btn btn-primary">Schedule Maintenance</button>
                </div>
            </form>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="table table-zebra w-full">
            <thead>
                <tr class="bg-gray-700 font-bold text-white">
                    <th>Maintenance ID</th>
                    <th>Asset Name</th>
                    <th>Maintenance Type</th>
                    <th>Scheduled Date</th>
                    <th>Repair Personnel</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="maintenanceBody">
                <tr id="loadingStateRow">
                    <td colspan="8">
                        <div id="loadingState" class="text-center py-8">
                            <div class="loading loading-spinner loading-lg"></div>
                            <p class="mt-2 text-gray-600">Loading Maintenance Schedules...</p>
                            <p class="text-sm text-gray-500" id="loading"></p>
                        </div>
                    </td>
                </tr>
                <tr id="noDataRow" class="hidden">
                    <td colspan="8">
                        <div class="text-center py-6 text-gray-600">
                            <span class="inline-flex items-center gap-2">
                                <i class='bx bx-fw bxs-wrench'></i>
                                <span>No Maintenance Schedule Found</span>
                            </span>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="maintenancePager" class="flex items-center justify-between mt-4">
        <div id="maintenancePagerInfo" class="text-sm text-gray-600"></div>
        <div class="join">
            <button class="btn btn-sm join-item" id="mntPrevBtn" data-action="prev">Prev</button>
            <span class="btn btn-sm join-item" id="mntPageDisplay">1 / 1</span>
            <button class="btn btn-sm join-item" id="mntNextBtn" data-action="next">Next</button>
        </div>
    </div>
    <div id="requestMaintenanceModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-4xl max-h-[85vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-lg font-semibold">Request Maintenance</h4>
                <button id="closeRequestMaintenanceModal" class="text-gray-500 hover:text-gray-700"><i class='bx bx-x text-2xl'></i></button>
            </div>
            <div class="flex flex-wrap items-center justify-between gap-3 mb-3">
                <div class="btn-group">
                    <button id="reqFilterAll" class="btn btn-sm btn-outline">All</button>
                    <button id="reqFilterUnread" class="btn btn-sm btn-outline">Unread</button>
                    <button id="reqFilterRead" class="btn btn-sm btn-outline">Read</button>
                </div>
                <div class="flex items-center gap-2">
                    <label class="text-sm text-gray-600">Priority</label>
                    <select id="reqFilterPriority" class="select select-bordered select-sm">
                        <option value="all">All</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr class="bg-gray-700 font-bold text-white">
                            <th>Request ID</th>
                            <th>Asset Name</th>
                            <th>Request Date</th>
                            <th>Priority</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="requestMaintenanceBody">
                        <tr id="requestLoadingRow">
                            <td colspan="5" class="text-center text-gray-600 py-6">
                                <div class="flex justify-center items-center gap-3">
                                    <div class="loading loading-spinner loading-lg"></div>
                                    <span>Loading requests...</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="requestPager" class="flex items-center justify-between mt-3">
                <div id="requestPagerInfo" class="text-sm text-gray-600"></div>
                <div class="join">
                    <button class="btn btn-sm join-item" id="reqPrevBtn" data-action="prev">Prev</button>
                    <span class="btn btn-sm join-item" id="reqPageDisplay">1 / 1</span>
                    <button class="btn btn-sm join-item" id="reqNextBtn" data-action="next">Next</button>
                </div>
            </div>
        </div>
    </div>

    <div id="repairPersonnelModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-lg p-6 w-full max-w-4xl max-h-[85vh] overflow-y-auto">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-lg font-semibold">Repair Personnel</h4>
                <div class="flex items-center gap-2">
                    <button id="addRepairPersonnelBtn" class="btn btn-primary"><i class='bx bx-plus mr-2'></i>Add new Repair Personnel</button>
                    <button id="closeRepairPersonnelModal" class="text-gray-500 hover:text-gray-700"><i class='bx bx-x text-2xl'></i></button>
                </div>
            </div>
            <div class="flex items-center justify-between gap-3 mb-3">
                <div class="flex items-center gap-2">
                    <label class="text-sm text-gray-600">Status</label>
                    <select id="rpFilterStatus" class="select select-bordered select-sm">
                        <option value="all">All</option>
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div class="flex items-center gap-2">
                    <label class="text-sm text-gray-600">Position</label>
                    <select id="rpFilterPosition" class="select select-bordered select-sm">
                        <option value="all">All</option>
                        <option value="Technician">Technician</option>
                        <option value="Mechanic">Mechanic</option>
                        <option value="Cleaning Staff">Cleaning Staff</option>
                    </select>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full" id="repairPersonnelTable">
                    <thead>
                        <tr class="bg-gray-700 font-bold text-white">
                            <th>ID</th>
                            <th>Repair Personnel Name</th>
                            <th>Position</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="repairPersonnelBody">
                        <tr>
                            <td colspan="5" class="text-center text-gray-600 py-6">
                                <div class="flex justify-center items-center gap-3">
                                    <div class="loading loading-spinner loading-lg"></div>
                                    <span>Loading personnel...</span>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id="repairPager" class="flex items-center justify-between mt-3">
                <div id="repairPagerInfo" class="text-sm text-gray-600"></div>
                <div class="join">
                    <button class="btn btn-sm join-item" id="repPrevBtn" data-action="prev">Prev</button>
                    <span class="btn btn-sm join-item" id="repPageDisplay">1 / 1</span>
                    <button class="btn btn-sm join-item" id="repNextBtn" data-action="next">Next</button>
                </div>
            </div>
        </div>
    </div>

    <div id="viewRepairPersonnelModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-[70]">
        <div class="bg-white rounded-lg p-6 w-full max-w-xl">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-lg font-semibold">Repair Personnel Details</h4>
                <button id="closeViewRepairPersonnelModal" class="text-gray-500 hover:text-gray-700"><i class='bx bx-x text-2xl'></i></button>
            </div>
            <div id="viewRepairPersonnelContent" class="mt-2"></div>
            <div class="flex justify-end mt-4">
                <button class="btn" id="dismissViewRepairPersonnel">Close</button>
            </div>
        </div>
    </div>

    <div id="addRepairPersonnelModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-[60]">
        <div class="bg-white rounded-lg p-6 w-full max-w-xl">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-lg font-semibold">Add new Repair Personnel</h4>
                <button id="closeAddRepairPersonnelModal" class="text-gray-500 hover:text-gray-700"><i class='bx bx-x text-2xl'></i></button>
            </div>
            <form id="addRepairPersonnelForm" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    <div>
                        <label class="block text-sm text-gray-700">Firstname</label>
                        <input type="text" id="rp_firstname" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700">Middlename</label>
                        <input type="text" id="rp_middlename" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700">Lastname</label>
                        <input type="text" id="rp_lastname" class="w-full px-3 py-2 border border-gray-300 rounded-lg" required>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700">Position</label>
                        <select id="rp_position" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            <option value="Technician" selected>Technician</option>
                            <option value="Mechanic">Mechanic</option>
                            <option value="Cleaning Staff">Cleaning Staff</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm text-gray-700">Status</label>
                        <select id="rp_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                            <option value="active" selected>Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" id="cancelAddRepairPersonnelModal" class="px-4 py-2 bg-gray-200 rounded-lg">Cancel</button>
                    <button type="submit" id="saveRepairPersonnelBtn" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>

    

    <div id="viewMaintenanceModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-[70]">
        <div class="bg-white rounded-lg p-6 w-full max-w-3xl">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-lg font-semibold">Maintenance Details</h4>
                <button id="closeViewMaintenanceModal" class="text-gray-500 hover:text-gray-700"><i class='bx bx-x text-2xl'></i></button>
            </div>
            <div id="viewMaintenanceContent" class="mt-2"></div>
            <div class="flex justify-end mt-4">
                <button class="btn" id="dismissViewMaintenance">Close</button>
            </div>
        </div>
    </div>

    <div id="updateMaintenanceStatusModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-[70]">
        <div class="bg-white rounded-lg p-6 w-full max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h4 class="text-lg font-semibold">Update Maintenance Status</h4>
                <button id="closeUpdateMaintenanceStatusModal" class="text-gray-500 hover:text-gray-700"><i class='bx bx-x text-2xl'></i></button>
            </div>
            <div class="space-y-4">
                <div>
                    <label class="block text-sm text-gray-700">Status</label>
                    <select id="mnt_status_update_select" class="w-full px-3 py-2 border border-gray-300 rounded-lg">
                        <option value="scheduled">Scheduled</option>
                        <option value="in_progress">In Progress</option>
                        <option value="completed">Completed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" id="cancelUpdateMaintenanceStatus" class="px-4 py-2 bg-gray-200 rounded-lg">Cancel</button>
                    <button type="button" id="saveMaintenanceStatusBtn" class="btn btn-primary">Save</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        (function() {
            function open(modalId){ var m=document.getElementById(modalId); if(m) m.classList.remove('hidden'); }
            function close(modalId){ var m=document.getElementById(modalId); if(m) m.classList.add('hidden'); }
            var reqBtn=document.getElementById('requestMaintenanceBtn');
            var repBtn=document.getElementById('repairPersonnelBtn');
            var closeReq=document.getElementById('closeRequestMaintenanceModal');
            var closeRep=document.getElementById('closeRepairPersonnelModal');
            
            var addBtn=document.getElementById('addRepairPersonnelBtn');
            var closeAdd=document.getElementById('closeAddRepairPersonnelModal');
            var cancelAdd=document.getElementById('cancelAddRepairPersonnelModal');
            if(reqBtn) reqBtn.addEventListener('click', function(){ open('requestMaintenanceModal'); loadRequestMaintenance(); });
            if(repBtn) repBtn.addEventListener('click', function(){ open('repairPersonnelModal'); loadRepairPersonnel(); });
            
            if(closeReq) closeReq.addEventListener('click', function(){ close('requestMaintenanceModal'); });
            if(closeRep) closeRep.addEventListener('click', function(){ close('repairPersonnelModal'); });
            
            if(addBtn) addBtn.addEventListener('click', function(){ open('addRepairPersonnelModal'); });
            if(closeAdd) closeAdd.addEventListener('click', function(){ close('addRepairPersonnelModal'); });
            if(cancelAdd) cancelAdd.addEventListener('click', function(){ close('addRepairPersonnelModal'); });

            function getCsrfToken(){ var m=document.querySelector('meta[name="csrf-token"]'); return m?m.getAttribute('content'):''; }
            var repairPersonnelState = [];
            var currentRepairPage = 1;
            var repairPageSize = 10;
            var rpFilterStatus = 'all';
            var rpFilterPosition = 'all';
            var maintenanceState = [];
            var currentMaintenancePage = 1;
            var maintenancePageSize = 10;
            function mntStatusBadge(s){
                if(s === 'scheduled') return "<span class='badge badge-info inline-flex items-center gap-1'><i class='bx bx-calendar text-current'></i>Scheduled</span>";
                if(s === 'in_progress') return "<span class='badge badge-warning inline-flex items-center gap-1'><i class='bx bx-time-five text-current'></i>In Progress</span>";
                if(s === 'completed') return "<span class='badge badge-success inline-flex items-center gap-1'><i class='bx bx-check-circle text-current'></i>Completed</span>";
                if(s === 'cancelled') return "<span class='badge badge-error inline-flex items-center gap-1'><i class='bx bx-x-circle text-current'></i>Cancelled</span>";
                return "<span class='badge inline-flex items-center gap-1'><i class='bx bx-pause-circle text-current'></i>On Hold</span>";
            }
            function mntPriorityBadge(p){
                if(p === 'low') return "<span class='badge badge-success inline-flex items-center gap-1'>Low</span>";
                if(p === 'medium') return "<span class='badge badge-warning inline-flex items-center gap-1'>Medium</span>";
                return "<span class='badge badge-error inline-flex items-center gap-1'>High</span>";
            }
            function statusBadge(s){
                if(s === 'active') return "<span class='badge badge-success inline-flex items-center gap-1'><i class='bx bx-check-circle text-current'></i>Active</span>";
                return "<span class='badge badge-error inline-flex items-center gap-1'><i class='bx bx-x-circle text-current'></i>Inactive</span>";
            }

            function renderMaintenance(list){
                var tBody=document.getElementById('maintenanceBody');
                var loadingRow=document.getElementById('loadingStateRow');
                var noDataRow=document.getElementById('noDataRow');
                if(!tBody) return;
                if(!list || list.length===0){
                    if(loadingRow) loadingRow.classList.add('hidden');
                    tBody.innerHTML = '<tr><td colspan="8"><div class="text-center py-6 text-gray-600"><span class="inline-flex items-center gap-2"><i class=\'bx bx-fw bxs-wrench\'></i><span>No Maintenance Schedule Found</span></span></div></td></tr>';
                    renderMaintenancePager(0,1);
                    return;
                }
                var total = list.length;
                var totalPages = Math.max(1, Math.ceil(total / maintenancePageSize));
                if(currentMaintenancePage > totalPages) currentMaintenancePage = totalPages;
                if(currentMaintenancePage < 1) currentMaintenancePage = 1;
                var start = (currentMaintenancePage - 1) * maintenancePageSize;
                var pageItems = list.slice(start, start + maintenancePageSize);
                var rows='';
                for(var i=0;i<pageItems.length;i++){
                    var m=pageItems[i];
                    var rpName = (m.rp_firstname ? [m.rp_firstname, m.rp_middlename, m.rp_lastname].filter(Boolean).join(' ') : '-');
                    var rpCell = rpName + (m.rp_position ? '<div class="text-xs text-gray-500">'+m.rp_position+'</div>' : '');
                    rows += '<tr>'+
                        '<td class="whitespace-nowrap">'+ (m.mnt_code||'') +'</td>'+
                        '<td class="whitespace-nowrap">'+ (m.mnt_asset_name||'') +'</td>'+
                        '<td class="whitespace-nowrap">'+ (m.mnt_type||'') +'</td>'+
                        '<td class="whitespace-nowrap">'+ (m.mnt_scheduled_date||'') +'</td>'+
                        '<td class="whitespace-nowrap">'+ rpCell +'</td>'+
                        '<td class="whitespace-nowrap">'+ mntStatusBadge(m.mnt_status) +'</td>'+
                        '<td class="whitespace-nowrap">'+ mntPriorityBadge(m.mnt_priority) +'</td>'+
                        '<td class="whitespace-nowrap">'+
                            '<div class="flex items-center gap-2">'+
                                '<button class="text-indigo-600 transition-colors p-2 rounded-lg hover:bg-gray-50" title="View Maintenance Detail" data-action="view-mnt" data-id="'+(m.mnt_code||'')+'"><i class="bx bx-show-alt text-xl"></i></button>'+
                                '<button class="text-amber-600 transition-colors p-2 rounded-lg hover:bg-gray-50" title="Update Maintenance Status" data-action="status-mnt" data-id="'+(m.mnt_code||'')+'"><i class="bx bx-edit-alt text-xl"></i></button>'+
                                '<button class="text-red-600 transition-colors p-2 rounded-lg hover:bg-gray-50" title="Delete Maintenance" data-action="delete-mnt" data-id="'+(m.mnt_code||'')+'"><i class="bx bx-trash text-xl"></i></button>'+
                            '</div>'+
                        '</td>'+
                    '</tr>';
                }
                if(loadingRow) loadingRow.classList.add('hidden');
                if(noDataRow) noDataRow.classList.add('hidden');
                tBody.innerHTML = rows;
                attachMaintenanceActionHandlers();
                renderMaintenancePager(total, totalPages);
            }

            function renderMaintenancePager(total, totalPages){
                var info = document.getElementById('maintenancePagerInfo');
                var display = document.getElementById('mntPageDisplay');
                var start = total === 0 ? 0 : ((currentMaintenancePage - 1) * maintenancePageSize) + 1;
                var end = Math.min(currentMaintenancePage * maintenancePageSize, total);
                if(info) info.textContent = 'Showing '+start+'-'+end+' of '+total;
                if(display) display.textContent = currentMaintenancePage+' / '+totalPages;
                var prev = document.getElementById('mntPrevBtn');
                var next = document.getElementById('mntNextBtn');
                if(prev) prev.disabled = currentMaintenancePage <= 1;
                if(next) next.disabled = currentMaintenancePage >= totalPages;
            }

            function loadRepairPersonnel(){
                var body=document.getElementById('repairPersonnelBody');
                if(body) body.innerHTML = '<tr><td colspan="5" class="text-center text-gray-600 py-6"><div class="flex justify-center items-center gap-3"><div class="loading loading-spinner loading-lg"></div><span>Loading personnel...</span></div></td></tr>';
                fetch('/alms/repair-personnel', { headers: { 'Accept': 'application/json' }})
                    .then(function(r){ return r.json(); })
                    .then(function(j){
                        repairPersonnelState = j.data || [];
                        renderRepairPersonnelTable();
                    })
                    .catch(function(){ if(body) body.innerHTML = '<tr><td colspan="5" class="text-center text-red-600 py-6">Failed to load personnel</td></tr>'; });
            }

            function renderRepairPersonnelTable(){
                var body=document.getElementById('repairPersonnelBody');
                var data = Array.isArray(repairPersonnelState) ? repairPersonnelState.slice() : [];
                var filtered = data.filter(function(p){
                    var sOk = (rpFilterStatus === 'all') || (String(p.status) === rpFilterStatus);
                    var pOk = (rpFilterPosition === 'all') || (String(p.position) === rpFilterPosition);
                    return sOk && pOk;
                });
                var rows='';
                if(filtered.length === 0){
                    rows = '<tr><td colspan="5" class="text-center py-6 text-gray-600"><span class="inline-flex items-center gap-2"><i class="bx bx-fw bxs-hard-hat"></i><span>No Repair Personnel found</span></span></td></tr>';
                    if(body) body.innerHTML = rows;
                    renderRepairPager(0,1);
                    return;
                }
                var total = filtered.length;
                var totalPages = Math.max(1, Math.ceil(total / repairPageSize));
                if(currentRepairPage > totalPages) currentRepairPage = totalPages;
                if(currentRepairPage < 1) currentRepairPage = 1;
                var start = (currentRepairPage - 1) * repairPageSize;
                var pageItems = filtered.slice(start, start + repairPageSize);
                for(var i=0;i<pageItems.length;i++){
                    var p=pageItems[i];
                    rows += '<tr>'+
                        '<td>'+ (p.rep_id||'') +'</td>'+
                        '<td>'+ [p.firstname, p.middlename, p.lastname].filter(Boolean).join(' ') +'</td>'+
                        '<td>'+ (p.position||'') +'</td>'+
                        '<td>'+ statusBadge(p.status) +'</td>'+
                        '<td>'+
                            '<div class="flex items-center gap-2">'+
                                '<button class="text-indigo-600 transition-colors p-2 rounded-lg hover:bg-gray-50" title="View Personnel" data-action="view" data-id="'+p.id+'"><i class="bx bx-show-alt text-xl"></i></button>'+
                                '<button class="text-red-600 transition-colors p-2 rounded-lg hover:bg-gray-50" title="Delete Personnel" data-action="delete" data-id="'+p.id+'"><i class="bx bx-trash text-xl"></i></button>'+
                            '</div>'+
                        '</td>'+
                    '</tr>';
                }
                if(body) body.innerHTML = rows;
                renderRepairPager(total, totalPages);
            }

            function renderRepairPager(total, totalPages){
                var info = document.getElementById('repairPagerInfo');
                var display = document.getElementById('repPageDisplay');
                var start = total === 0 ? 0 : ((currentRepairPage - 1) * repairPageSize) + 1;
                var end = Math.min(currentRepairPage * repairPageSize, total);
                if(info) info.textContent = 'Showing '+start+'-'+end+' of '+total;
                if(display) display.textContent = currentRepairPage+' / '+totalPages;
                var prev = document.getElementById('repPrevBtn');
                var next = document.getElementById('repNextBtn');
                if(prev) prev.disabled = currentRepairPage <= 1;
                if(next) next.disabled = currentRepairPage >= totalPages;
            }

            function loadRequestMaintenance(){
                var body = document.getElementById('requestMaintenanceBody');
                var loadingRow = document.getElementById('requestLoadingRow');
                if(body && loadingRow){ loadingRow.classList.remove('hidden'); }
                fetch('/alms/request-maintenance', { headers: { 'Accept': 'application/json' }})
                    .then(function(r){ return r.json(); })
                    .then(function(j){
                        var data = j.data || [];
                        window.requestListData = data;
                        renderRequestMaintenanceTable();
                    })
                    .catch(function(){ if(body){ body.innerHTML = '<tr><td colspan="5" class="text-center py-6 text-gray-600"><span class="inline-flex items-center gap-2"><i class="bx bx-fw bxs-traffic-cone"></i><span>Failed to load requests</span></span></td></tr>'; } })
                    .finally(function(){ if(loadingRow){ loadingRow.classList.add('hidden'); } attachRequestMaintenanceHandlers(); });
            }

            var reqFilterStatus = 'all';
            var reqFilterPriority = 'all';
            var currentRequestPage = 1;
            var requestPageSize = 10;

            function renderRequestMaintenanceTable(){
                var body = document.getElementById('requestMaintenanceBody');
                var data = Array.isArray(window.requestListData) ? window.requestListData.slice() : [];
                var filtered = data.filter(function(r){
                    var matchesStatus = (reqFilterStatus === 'all') || (reqFilterStatus === 'unread' && !r.req_processed) || (reqFilterStatus === 'read' && !!r.req_processed);
                    var matchesPriority = (reqFilterPriority === 'all') || (String(r.req_priority) === reqFilterPriority);
                    return matchesStatus && matchesPriority;
                });
                var rows = '';
                if(filtered.length === 0){
                    rows = '<tr><td colspan="5" class="text-center py-6 text-gray-600"><span class="inline-flex items-center gap-2"><i class="bx bx-fw bxs-traffic-cone"></i><span>No Request for Maintenance found</span></span></td></tr>';
                    if(body){ body.innerHTML = rows; }
                    renderRequestPager(0,1);
                } else {
                    var total = filtered.length;
                    var totalPages = Math.max(1, Math.ceil(total / requestPageSize));
                    if(currentRequestPage > totalPages) currentRequestPage = totalPages;
                    if(currentRequestPage < 1) currentRequestPage = 1;
                    var start = (currentRequestPage - 1) * requestPageSize;
                    var pageItems = filtered.slice(start, start + requestPageSize);
                    for(var i=0;i<pageItems.length;i++){
                        var r = pageItems[i];
                        var processed = !!r.req_processed;
                        var isExternal = !!r.is_external;
                        rows += '<tr class="'+(processed ? 'bg-gray-100 opacity-70' : '')+'">'+
                            '<td class="whitespace-nowrap font-mono text-xs">'+ 
                                (r.req_code||r.req_id||'') + 
                                (isExternal ? ' <span class="badge badge-xs badge-info ml-1">EXT</span>' : '') +
                            '</td>'+
                            '<td class="whitespace-nowrap">'+ (r.req_asset_name||'') +'</td>'+
                            '<td class="whitespace-nowrap">'+ (r.req_date||'') +'</td>'+
                            '<td class="whitespace-nowrap">'+ mntPriorityBadge(r.req_priority) +'</td>'+
                            '<td class="whitespace-nowrap">'+
                                '<div class="flex items-center gap-2">'+
                                    (processed ? '' : '<button class="btn btn-xs btn-success text-white" title="Schedule Maintenance" data-action="schedule-req" data-id="'+(r.real_id||'')+'"><i class="bx bx-calendar-plus mr-1"></i>Scheduled Maintenance</button>')+
                                    (isExternal ? '' : '<button class="text-red-600 transition-colors p-2 rounded-lg hover:bg-gray-50" title="Delete Request" data-action="delete-req" data-id="'+(r.real_id||'')+'"><i class="bx bx-trash text-xl"></i></button>')+
                                '</div>'+
                            '</td>'+
                        '</tr>';
                    }
                    if(body){ body.innerHTML = rows; }
                    renderRequestPager(total, totalPages);
                }
            }

            function renderRequestPager(total, totalPages){
                var info = document.getElementById('requestPagerInfo');
                var display = document.getElementById('reqPageDisplay');
                var start = total === 0 ? 0 : ((currentRequestPage - 1) * requestPageSize) + 1;
                var end = Math.min(currentRequestPage * requestPageSize, total);
                if(info) info.textContent = 'Showing '+start+'-'+end+' of '+total;
                if(display) display.textContent = currentRequestPage+' / '+totalPages;
                var prev = document.getElementById('reqPrevBtn');
                var next = document.getElementById('reqNextBtn');
                if(prev) prev.disabled = currentRequestPage <= 1;
                if(next) next.disabled = currentRequestPage >= totalPages;
            }

            var btnAll = document.getElementById('reqFilterAll');
            var btnUnread = document.getElementById('reqFilterUnread');
            var btnRead = document.getElementById('reqFilterRead');
            var selPriority = document.getElementById('reqFilterPriority');
            function setActiveStatusButton(){
                [btnAll, btnUnread, btnRead].forEach(function(b){ if(!b) return; b.classList.remove('btn-active'); });
                if(reqFilterStatus === 'all' && btnAll) btnAll.classList.add('btn-active');
                if(reqFilterStatus === 'unread' && btnUnread) btnUnread.classList.add('btn-active');
                if(reqFilterStatus === 'read' && btnRead) btnRead.classList.add('btn-active');
            }
            if(btnAll) btnAll.addEventListener('click', function(){ reqFilterStatus='all'; currentRequestPage=1; setActiveStatusButton(); renderRequestMaintenanceTable(); });
            if(btnUnread) btnUnread.addEventListener('click', function(){ reqFilterStatus='unread'; currentRequestPage=1; setActiveStatusButton(); renderRequestMaintenanceTable(); });
            if(btnRead) btnRead.addEventListener('click', function(){ reqFilterStatus='read'; currentRequestPage=1; setActiveStatusButton(); renderRequestMaintenanceTable(); });
            if(selPriority) selPriority.addEventListener('change', function(){ reqFilterPriority = selPriority.value || 'all'; currentRequestPage=1; renderRequestMaintenanceTable(); });
            setActiveStatusButton();

            function attachRequestMaintenanceHandlers(){
                var body = document.getElementById('requestMaintenanceBody');
                if(!body) return;
                body.addEventListener('click', async function(e){
                    var btn = e.target.closest('button');
                    if(!btn) return;
                    var action = btn.getAttribute('data-action');
                    var id = btn.getAttribute('data-id');
                    if(action === 'schedule-req'){
                        var dataRow = (Array.isArray(window.requestListData) ? window.requestListData : []);
                        var reqItem = dataRow.find(function(x){ return String(x.real_id) === String(id); });
                        if(!reqItem){ return; }
                        openScheduleFromRequestModal(reqItem);
                    } else if(action === 'delete-req'){
                        var proceed = true;
                        if(typeof Swal !== 'undefined'){
                            var res = await Swal.fire({ title: 'Delete Request?', text: 'This action cannot be undone.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Delete', cancelButtonText: 'Cancel' });
                            proceed = !!(res && res.isConfirmed);
                        }
                        if(!proceed) return;
                        fetch('/alms/request-maintenance/' + encodeURIComponent(id), {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': getCsrfToken()
                            }
                        })
                        .then(function(r){ return r.json(); })
                        .then(function(){
                            loadRequestMaintenance(); updateRequestMaintenanceCount();
                            var Toast = (typeof Swal !== 'undefined') ? Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 1500, timerProgressBar: true }) : null;
                            if(Toast) Toast.fire({ icon: 'success', title: 'Request deleted' });
                        })
                        .catch(function(){});
                    }
                });
            }

            var currentReqToSchedule = null;
            function openScheduleFromRequestModal(req){
                currentReqToSchedule = req || null;
                var nameEl = document.getElementById('sch_req_asset_name');
                var dateEl = document.getElementById('sch_req_date');
                var prioEl = document.getElementById('sch_req_priority');
                
                if(nameEl) nameEl.value = req.req_asset_name || '';
                if(dateEl) dateEl.value = req.req_date || '';
                if(prioEl) prioEl.value = (req.req_priority || '').replace(/^\w/, function(c){ return c.toUpperCase(); });
                loadActivePersonnelForReq();
                open('scheduleFromRequestModal');
            }

            function loadActivePersonnelForReq(){
                var sel=document.getElementById('sch_req_repair_personnel_id');
                if(sel) sel.innerHTML = '<option value="">Loading...</option>';
                fetch('/alms/repair-personnel?status=active', { headers: { 'Accept': 'application/json' }})
                    .then(function(r){ return r.json(); })
                    .then(function(j){
                        var data=j.data||[]; var opts='';
                        if(data.length===0){ opts = '<option value="">No personnel available</option>'; }
                        else { for(var i=0;i<data.length;i++){ var p=data[i]; opts += '<option value="'+p.id+'">'+[p.firstname,p.middlename,p.lastname].filter(Boolean).join(' ')+'</option>'; } }
                        if(sel) sel.innerHTML = opts;
                    })
                    .catch(function(){ if(sel) sel.innerHTML = '<option value="">Failed to load</option>'; });
            }

            var closeSchReq = document.getElementById('closeScheduleFromRequestModal');
            var cancelSchReq = document.getElementById('cancelScheduleFromRequest');
            if(closeSchReq) closeSchReq.addEventListener('click', function(){ close('scheduleFromRequestModal'); });
            if(cancelSchReq) cancelSchReq.addEventListener('click', function(){ close('scheduleFromRequestModal'); });

            var schReqForm = document.getElementById('scheduleFromRequestForm');
            if(schReqForm) schReqForm.addEventListener('submit', function(e){
                e.preventDefault();
                if(!currentReqToSchedule){ return; }
                var payload = {
                    mnt_asset_name: currentReqToSchedule.req_asset_name,
                    mnt_type: currentReqToSchedule.req_type || 'General',
                    mnt_scheduled_date: document.getElementById('sch_req_scheduled_date').value,
                    mnt_repair_personnel_id: document.getElementById('sch_req_repair_personnel_id').value || null,
                    mnt_status: 'scheduled',
                    mnt_priority: currentReqToSchedule.req_priority,
                    request_id: currentReqToSchedule.real_id,
                    is_external: !!currentReqToSchedule.is_external
                };
                fetch('/alms/maintenance', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': getCsrfToken() },
                    body: JSON.stringify(payload)
                }).then(function(r){ return r.json().then(function(j){ return {ok:r.ok, status:r.status, body:j}; }); })
                  .then(function(res){
                      var Toast = (typeof Swal !== 'undefined') ? Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 1500, timerProgressBar: true }) : null;
                      if(res.ok){
                          if(Toast) Toast.fire({ icon: 'success', title: 'Maintenance scheduled' });
                          close('scheduleFromRequestModal');
                          currentReqToSchedule = null;
                          loadMaintenanceSchedules();
                          // Reload requests to update status/processed flag
                          loadRequestMaintenance(); 
                          updateRequestMaintenanceCount();
                      }
                      else { if(Toast) { Swal.fire({ icon: 'error', title: 'Failed to schedule', text: (res.body && res.body.message) ? res.body.message : 'Please try again.' }); } }
                  })
                  .catch(function(){ if(typeof Swal !== 'undefined'){ Swal.fire({ icon: 'error', title: 'Failed to schedule', text: 'Please try again.' }); } });
            });

            updateRequestMaintenanceCount();

            

            function loadMaintenanceSchedules(){
                var tBody=document.getElementById('maintenanceBody');
                var loadingRow=document.getElementById('loadingStateRow');
                var noDataRow=document.getElementById('noDataRow');
                if(loadingRow) loadingRow.classList.remove('hidden');
                if(noDataRow) noDataRow.classList.add('hidden');
                fetch('/alms/maintenance', { headers: { 'Accept': 'application/json' }})
                    .then(function(r){ return r.json(); })
                    .then(function(j){
                        var data=j.data||[];
                        maintenanceState = data;
                        renderMaintenance(maintenanceState);
                    })
                    .catch(function(){
                        if(loadingRow) loadingRow.classList.add('hidden');
                        if(tBody){ tBody.innerHTML = '<tr><td colspan="8"><div class="text-center py-6 text-gray-600"><span class="inline-flex items-center gap-2"><i class=\'bx bx-fw bxs-wrench\'></i><span>No Maintenance Schedule Found</span></span></div></td></tr>'; }
                    });
            }

            function attachMaintenanceActionHandlers(){
                var tBody=document.getElementById('maintenanceBody');
                if(!tBody) return;
                var viewBtns = tBody.querySelectorAll('button[data-action="view-mnt"]');
                var statusBtns = tBody.querySelectorAll('button[data-action="status-mnt"]');
                viewBtns.forEach(function(btn){
                    btn.addEventListener('click', function(ev){ ev.preventDefault(); var id = btn.getAttribute('data-id'); openMaintenanceViewModal(id); });
                });
                statusBtns.forEach(function(btn){
                    btn.addEventListener('click', function(ev){ ev.preventDefault(); var id = btn.getAttribute('data-id'); openMaintenanceStatusModal(id); });
                });
            }

            function openMaintenanceViewModal(id){
                var m = (maintenanceState || []).find(function(x){ return String(x.id) === String(id); });
                if(!m) return;
                var rpName = (m.rp_firstname ? [m.rp_firstname, m.rp_middlename, m.rp_lastname].filter(Boolean).join(' ') : '-');
                var rpDisplay = rpName + (m.rp_position ? '<div class="text-xs text-gray-500">'+m.rp_position+'</div>' : '');
                var html = '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">'+
                    '<div><span class="text-sm text-gray-500">Maintenance ID</span><p class="font-semibold">'+(m.mnt_code||'')+'</p></div>'+
                    '<div><span class="text-sm text-gray-500">Asset Name</span><p class="font-semibold">'+(m.mnt_asset_name||'')+'</p></div>'+
                    '<div><span class="text-sm text-gray-500">Maintenance Type</span><p class="font-semibold">'+(m.mnt_type||'')+'</p></div>'+
                    '<div><span class="text-sm text-gray-500">Scheduled Date</span><p class="font-semibold">'+(m.mnt_scheduled_date||'')+'</p></div>'+
                    '<div><span class="text-sm text-gray-500">Repair Personnel</span><p class="font-semibold">'+rpDisplay+'</p></div>'+
                    '<div><span class="text-sm text-gray-500">Status</span><p class="font-semibold">'+(m.mnt_status||'')+'</p></div>'+
                    '<div><span class="text-sm text-gray-500">Priority</span><p class="font-semibold">'+(m.mnt_priority||'')+'</p></div>'+
                    '</div>';
                var c=document.getElementById('viewMaintenanceContent'); if(c){ c.innerHTML = html; open('viewMaintenanceModal'); }
            }

            function openMaintenanceStatusModal(id){
                var m2 = (maintenanceState || []).find(function(x){ return String(x.id) === String(id); });
                var statusSelect = document.getElementById('mnt_status_update_select');
                if(m2 && statusSelect){ statusTargetId = id; statusSelect.value = m2.mnt_status || 'scheduled'; open('updateMaintenanceStatusModal'); }
            }

            var form=document.getElementById('scheduleMaintenanceForm');
            var msg=null;
            if(form) form.addEventListener('submit', function(e){
                e.preventDefault();
                var payload = {
                    mnt_asset_name: document.getElementById('mnt_asset_name').value,
                    mnt_type: document.getElementById('mnt_type').value,
                    mnt_scheduled_date: document.getElementById('mnt_scheduled_date').value,
                    mnt_repair_personnel_id: document.getElementById('mnt_repair_personnel_id').value || null,
                    mnt_status: document.getElementById('mnt_status').value,
                    mnt_priority: document.getElementById('mnt_priority').value
                };
                fetch('/alms/maintenance', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken()
                    },
                    body: JSON.stringify(payload)
                }).then(function(r){ return r.json().then(function(j){ return {ok:r.ok, status:r.status, body:j}; }); })
                  .then(function(res){
                      if(res.ok){ if(typeof Swal !== 'undefined'){ var Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 1500, timerProgressBar: true }); Toast.fire({ icon: 'success', title: 'Schedule saved' }); } close('scheduleMaintenanceModal'); loadMaintenanceSchedules(); }
                      else { if(typeof Swal !== 'undefined'){ Swal.fire({ icon: 'error', title: 'Failed to save', text: (res.body && res.body.message) ? res.body.message : 'Please try again.' }); } }
                  })
                  .catch(function(){ if(typeof Swal !== 'undefined'){ Swal.fire({ icon: 'error', title: 'Failed to save', text: 'Please try again.' }); } });
            });

            var addForm=document.getElementById('addRepairPersonnelForm');
            var Toast = (typeof Swal !== 'undefined') ? Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 1500, timerProgressBar: true }) : null;
            if(addForm) addForm.addEventListener('submit', function(e){
                e.preventDefault();
                var fn = document.getElementById('rp_firstname').value.trim();
                var ln = document.getElementById('rp_lastname').value.trim();
                if(!fn || !ln){ if(typeof Swal !== 'undefined') { Swal.fire({ icon: 'error', title: 'Validation error', text: 'Firstname and Lastname are required' }); } return; }
                var payload = {
                    firstname: document.getElementById('rp_firstname').value,
                    middlename: document.getElementById('rp_middlename').value || null,
                    lastname: document.getElementById('rp_lastname').value,
                    position: document.getElementById('rp_position').value,
                    status: document.getElementById('rp_status').value
                };
                fetch('/alms/repair-personnel', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken()
                    },
                    body: JSON.stringify(payload)
                }).then(function(r){ return r.json().then(function(j){ return {ok:r.ok, status:r.status, body:j}; }); })
                  .then(function(res){
                      if(res.ok){ if(Toast) Toast.fire({ icon: 'success', title: 'Repair personnel added' }); close('addRepairPersonnelModal'); loadRepairPersonnel(); preloadPersonnelSelect(); }
                      else { if(Toast) { Swal.fire({ icon: 'error', title: 'Failed to save', text: (res.body && res.body.message) ? res.body.message : 'Please try again.' }); } }
                  })
                  .catch(function(){ if(Toast) { Swal.fire({ icon: 'error', title: 'Failed to save', text: 'Please try again.' }); } });
            });

            var viewModal = document.getElementById('viewRepairPersonnelModal');
            var closeView = document.getElementById('closeViewRepairPersonnelModal');
            var dismissView = document.getElementById('dismissViewRepairPersonnel');
            var viewContent = document.getElementById('viewRepairPersonnelContent');
            if(closeView) closeView.addEventListener('click', function(){ close('viewRepairPersonnelModal'); });
            if(dismissView) dismissView.addEventListener('click', function(){ close('viewRepairPersonnelModal'); });

            var personnelBody = document.getElementById('repairPersonnelBody');
            if(personnelBody) personnelBody.addEventListener('click', async function(e){
                var btn = e.target.closest('button');
                if(!btn) return;
                var action = btn.getAttribute('data-action');
                var id = btn.getAttribute('data-id');
                if(action === 'view'){
                    var p = (repairPersonnelState || []).find(function(x){ return String(x.id) === String(id); });
                    if(p){
                        var html = '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">'+
                            '<div><span class="text-sm text-gray-500">ID</span><p class="font-semibold">'+(p.rep_id||'')+'</p></div>'+
                            '<div><span class="text-sm text-gray-500">Name</span><p class="font-semibold">'+[p.firstname,p.middlename,p.lastname].filter(Boolean).join(' ')+'</p></div>'+
                            '<div><span class="text-sm text-gray-500">Position</span><p class="font-semibold">'+(p.position||'')+'</p></div>'+
                            '<div><span class="text-sm text-gray-500">Status</span><p class="font-semibold">'+(p.status||'')+'</p></div>'+
                            '</div>';
                        if(viewContent) viewContent.innerHTML = html;
                        open('viewRepairPersonnelModal');
                    }
                } else if(action === 'delete'){
                    if(typeof Swal !== 'undefined'){
                        var confirm = await Swal.fire({ title: 'Delete Personnel?', text: 'This action cannot be undone.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Delete', cancelButtonText: 'Cancel' });
                        if(confirm.isConfirmed){
                            fetch('/alms/repair-personnel/'+id, { method: 'DELETE', headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': getCsrfToken() }})
                                .then(function(r){ return r.json().then(function(j){ return {ok:r.ok, status:r.status, body:j}; }); })
                                .then(function(res){ if(res.ok){ if(Toast) Toast.fire({ icon: 'success', title: 'Personnel deleted' }); loadRepairPersonnel(); preloadPersonnelSelect(); } else { Swal.fire({ icon: 'error', title: 'Delete failed', text: (res.body && res.body.message) ? res.body.message : 'Please try again.' }); } })
                                .catch(function(){ Swal.fire({ icon: 'error', title: 'Delete failed', text: 'Please try again.' }); });
                        }
                    }
                }
            });
            var rpStatusSel = document.getElementById('rpFilterStatus');
            var rpPositionSel = document.getElementById('rpFilterPosition');
            if(rpStatusSel) rpStatusSel.addEventListener('change', function(){ rpFilterStatus = rpStatusSel.value || 'all'; currentRepairPage = 1; renderRepairPersonnelTable(); });
            if(rpPositionSel) rpPositionSel.addEventListener('change', function(){ rpFilterPosition = rpPositionSel.value || 'all'; currentRepairPage = 1; renderRepairPersonnelTable(); });
            var mBody=document.getElementById('maintenanceBody');
            var mViewModalId = 'viewMaintenanceModal';
            var mViewContent = document.getElementById('viewMaintenanceContent');
            var closeViewMnt = document.getElementById('closeViewMaintenanceModal');
            var dismissViewMnt = document.getElementById('dismissViewMaintenance');
            if(closeViewMnt) closeViewMnt.addEventListener('click', function(){ close(mViewModalId); });
            if(dismissViewMnt) dismissViewMnt.addEventListener('click', function(){ close(mViewModalId); });
            var statusModalId = 'updateMaintenanceStatusModal';
            var closeStatusMnt = document.getElementById('closeUpdateMaintenanceStatusModal');
            var cancelStatusMnt = document.getElementById('cancelUpdateMaintenanceStatus');
            var saveStatusBtn = document.getElementById('saveMaintenanceStatusBtn');
            var statusSelect = document.getElementById('mnt_status_update_select');
            var statusTargetId = null;
            if(closeStatusMnt) closeStatusMnt.addEventListener('click', function(){ close(statusModalId); });
            if(cancelStatusMnt) cancelStatusMnt.addEventListener('click', function(){ close(statusModalId); });
            var maintenancePager = document.getElementById('maintenancePager');
            if(maintenancePager) maintenancePager.addEventListener('click', function(ev){
                var btn = ev.target.closest('button[data-action]');
                if(!btn) return;
                var act = btn.getAttribute('data-action');
                if(act === 'prev'){ currentMaintenancePage = Math.max(1, currentMaintenancePage - 1); renderMaintenance(maintenanceState); }
                if(act === 'next'){
                    var max = Math.max(1, Math.ceil(((maintenanceState||[]).length)/maintenancePageSize));
                    currentMaintenancePage = Math.min(max, currentMaintenancePage + 1);
                    renderMaintenance(maintenanceState);
                }
            });
            var requestPager = document.getElementById('requestPager');
            if(requestPager) requestPager.addEventListener('click', function(ev){
                var btn = ev.target.closest('button[data-action]');
                if(!btn) return;
                var act = btn.getAttribute('data-action');
                if(act === 'prev'){ currentRequestPage = Math.max(1, currentRequestPage - 1); renderRequestMaintenanceTable(); }
                if(act === 'next'){
                    var data = Array.isArray(window.requestListData) ? window.requestListData.slice() : [];
                    var filtered = data.filter(function(r){
                        var matchesStatus = (reqFilterStatus === 'all') || (reqFilterStatus === 'unread' && !r.req_processed) || (reqFilterStatus === 'read' && !!r.req_processed);
                        var matchesPriority = (reqFilterPriority === 'all') || (String(r.req_priority) === reqFilterPriority);
                        return matchesStatus && matchesPriority;
                    });
                    var max = Math.max(1, Math.ceil((filtered.length)/requestPageSize));
                    currentRequestPage = Math.min(max, currentRequestPage + 1);
                    renderRequestMaintenanceTable();
                }
            });
            var repairPager = document.getElementById('repairPager');
            if(repairPager) repairPager.addEventListener('click', function(ev){
                var btn = ev.target.closest('button[data-action]');
                if(!btn) return;
                var act = btn.getAttribute('data-action');
                if(act === 'prev'){ currentRepairPage = Math.max(1, currentRepairPage - 1); renderRepairPersonnelTable(); }
                if(act === 'next'){
                    var data = Array.isArray(repairPersonnelState) ? repairPersonnelState.slice() : [];
                    var filtered = data.filter(function(p){
                        var sOk = (rpFilterStatus === 'all') || (String(p.status) === rpFilterStatus);
                        var pOk = (rpFilterPosition === 'all') || (String(p.position) === rpFilterPosition);
                        return sOk && pOk;
                    });
                    var max = Math.max(1, Math.ceil((filtered.length)/repairPageSize));
                    currentRepairPage = Math.min(max, currentRepairPage + 1);
                    renderRepairPersonnelTable();
                }
            });
            if(mBody) mBody.addEventListener('click', async function(e){
                var btn = e.target.closest('button');
                if(!btn) return;
                var action = btn.getAttribute('data-action');
                var id = btn.getAttribute('data-id');
                if(action === 'view-mnt'){
                    var m = (maintenanceState || []).find(function(x){ return String(x.mnt_code) === String(id); });
                    if(m){
                        var rpName = (m.rp_firstname ? [m.rp_firstname, m.rp_middlename, m.rp_lastname].filter(Boolean).join(' ') : '-');
                        var html = '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">'+
                            '<div><span class="text-sm text-gray-500">Maintenance ID</span><p class="font-semibold">'+(m.mnt_code||'')+'</p></div>'+
                            '<div><span class="text-sm text-gray-500">Asset Name</span><p class="font-semibold">'+(m.mnt_asset_name||'')+'</p></div>'+
                            '<div><span class="text-sm text-gray-500">Maintenance Type</span><p class="font-semibold">'+(m.mnt_type||'')+'</p></div>'+
                            '<div><span class="text-sm text-gray-500">Scheduled Date</span><p class="font-semibold">'+(m.mnt_scheduled_date||'')+'</p></div>'+
                            '<div><span class="text-sm text-gray-500">Repair Personnel</span><p class="font-semibold">'+rpName+'</p></div>'+
                            '<div><span class="text-sm text-gray-500">Status</span><p class="font-semibold">'+(m.mnt_status||'')+'</p></div>'+
                            '<div><span class="text-sm text-gray-500">Priority</span><p class="font-semibold">'+(m.mnt_priority||'')+'</p></div>'+
                            '</div>';
                        if(mViewContent) { mViewContent.innerHTML = html; open(mViewModalId); }
                    }
                } else if(action === 'status-mnt'){
                    var m2 = (maintenanceState || []).find(function(x){ return String(x.mnt_code) === String(id); });
                    if(m2 && statusSelect){ statusTargetId = id; statusSelect.value = m2.mnt_status || 'scheduled'; open(statusModalId); }
                } else if(action === 'delete-mnt'){
                    if(typeof Swal !== 'undefined'){
                        var confirm = await Swal.fire({ title: 'Delete Maintenance?', text: 'This action cannot be undone.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Delete', cancelButtonText: 'Cancel' });
                        if(confirm.isConfirmed){
                            fetch('/alms/maintenance/'+id, { method: 'DELETE', headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': getCsrfToken() }})
                                .then(function(r){ return r.json().then(function(j){ return {ok:r.ok, status:r.status, body:j}; }); })
                                .then(function(res){ if(res.ok){ var T = (typeof Swal !== 'undefined') ? Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 1500, timerProgressBar: true }) : null; if(T) T.fire({ icon: 'success', title: 'Maintenance deleted' }); loadMaintenanceSchedules(); } else { Swal.fire({ icon: 'error', title: 'Delete failed', text: (res.body && res.body.message) ? res.body.message : 'Please try again.' }); } })
                                .catch(function(){ Swal.fire({ icon: 'error', title: 'Delete failed', text: 'Please try again.' }); });
                        }
                    }
                }
            });
            if(saveStatusBtn) saveStatusBtn.addEventListener('click', function(){
                if(!statusTargetId || !statusSelect) return;
                var newStatus = statusSelect.value;
                fetch('/alms/maintenance/'+statusTargetId+'/status', {
                    method: 'PUT',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': getCsrfToken() },
                    body: JSON.stringify({ mnt_status: newStatus })
                }).then(function(r){ return r.json().then(function(j){ return {ok:r.ok, status:r.status, body:j}; }); })
                  .then(function(res){ if(res.ok){ var T = (typeof Swal !== 'undefined') ? Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 1500, timerProgressBar: true }) : null; if(T) T.fire({ icon: 'success', title: 'Status updated' }); close(statusModalId); statusTargetId = null; loadMaintenanceSchedules(); } else { Swal.fire({ icon: 'error', title: 'Update failed', text: (res.body && res.body.message) ? res.body.message : 'Please try again.' }); } })
                  .catch(function(){ Swal.fire({ icon: 'error', title: 'Update failed', text: 'Please try again.' }); });
            });
            loadMaintenanceSchedules();
        })();

            function updateRequestMaintenanceCount(){
                var badge=document.getElementById('requestMaintenanceCountBadge');
                var ping=document.getElementById('requestMaintenanceCountPing');
                fetch('/alms/request-maintenance', { headers: { 'Accept': 'application/json' }})
                    .then(function(r){ return r.json(); })
                    .then(function(j){
                        var list=(j.data||[]);
                        var c=list.filter(function(x){ return !x.req_processed; }).length;
                        var display = (c >= 10) ? '+9' : String(c);
                        if(badge){
                            badge.textContent = display;
                            if(c>0){ badge.classList.remove('hidden'); }
                            else { badge.classList.add('hidden'); }
                        }
                        if(ping){
                            if(c>0){ ping.classList.remove('hidden'); }
                            else { ping.classList.add('hidden'); }
                        }
                    })
                    .catch(function(){});
            }
    </script>
</div>
