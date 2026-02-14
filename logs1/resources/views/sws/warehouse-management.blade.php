<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bxs-store'></i>Warehouse Management</h2>
    </div>
    <div class="text-right">
        <span class="text-md text-gray-600">Smart Warehousing System</span>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg p-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-100 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-3 bg-blue-500 rounded-lg"><i class='bx bx-store text-white text-2xl'></i></div>
                <div class="ml-4"><p class="text-sm font-medium text-blue-700">Warehouses</p><p id="statWarehouses" class="text-2xl font-bold text-blue-900">0</p></div>
            </div>
        </div>
        <div class="bg-green-100 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-3 bg-green-500 rounded-lg"><i class='bx bx-box text-white text-2xl'></i></div>
                <div class="ml-4"><p class="text-sm font-medium text-green-700">Capacity</p><p id="statCapacity" class="text-2xl font-bold text-green-900">0</p></div>
            </div>
        </div>
        <div class="bg-yellow-100 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-500 rounded-lg"><i class='bx bx-data text-white text-2xl'></i></div>
                <div class="ml-4"><p class="text-sm font-medium text-yellow-700">Used</p><p id="statUsed" class="text-2xl font-bold text-yellow-900">0</p></div>
            </div>
        </div>
        <div class="bg-indigo-100 border border-indigo-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-3 bg-indigo-500 rounded-lg"><i class='bx bx-bar-chart-alt text-white text-2xl'></i></div>
                <div class="ml-4"><p class="text-sm font-medium text-indigo-700">Free</p><p id="statFree" class="text-2xl font-bold text-indigo-900">0</p></div>
            </div>
        </div>
    </div>

    <div class="flex justify-end mb-4 gap-2">
        <button id="requestRoomStatusBtn" title="Request Room Status" class="bg-amber-600 hover:bg-amber-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 whitespace-nowrap">
            <i class='bx bx-list-ul'></i> Request Room Status
        </button>
        <button id="addWarehouseBtn" title="Add New Warehouse" class="bg-primary hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 whitespace-nowrap">
            <i class='bx bx-plus'></i> Add Warehouse
        </button>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-800 font-bold text-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider whitespace-nowrap">Warehouse ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider whitespace-nowrap">Warehouse</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider whitespace-nowrap">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider whitespace-nowrap">Capacity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider whitespace-nowrap">Used</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider whitespace-nowrap">Free</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider whitespace-nowrap">Utilization</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider whitespace-nowrap">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody id="warehousesTableBody" class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="9" class="px-6 py-4 text-center text-gray-500"><div class="flex justify-center items-center py-4"><div class="loading loading-spinner mr-3"></div>Loading warehouses...</div></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div id="warePager" class="flex items-center justify-between mt-3">
        <div id="warePagerInfo" class="text-sm text-gray-600"></div>
        <div class="join">
            <button class="btn btn-sm join-item" id="warePrevBtn" data-action="prev">Prev</button>
            <span class="btn btn-sm join-item" id="warePageDisplay">1 / 1</span>
            <button class="btn btn-sm join-item" id="wareNextBtn" data-action="next">Next</button>
        </div>
    </div>
</div>

<div id="warehouseModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-2xl max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 id="warehouseModalTitle" class="text-xl font-semibold">New Warehouse</h3>
            <button id="closeWarehouseModal" class="text-gray-500 hover:text-gray-700"><i class='bx bx-x text-2xl'></i></button>
        </div>
        <form id="warehouseForm">
            <input type="hidden" id="ware_id">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Name *</label>
                <input type="text" id="ware_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Location</label>
                <input type="text" id="ware_location" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Capacity</label>
                    <input type="number" id="ware_capacity" min="0" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Supports Fixed Items</label>
                    <select id="ware_supports_fixed_items" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"><option value="1">Yes</option><option value="0">No</option></select>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="ware_status" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"><option value="active">Active</option><option value="inactive">Inactive</option><option value="maintenance">Maintenance</option></select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Zone Type</label>
                    <select id="ware_zone_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"><option value="general">General</option><option value="liquid">Liquid</option><option value="illiquid">Illiquid</option><option value="climate_controlled">Climate Controlled</option></select>
                </div>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" id="cancelWarehouseModal" class="px-4 py-2 bg-gray-200 rounded-lg">Cancel</button>
                <button type="submit" id="saveWarehouseBtn" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Save</button>
            </div>
        </form>
    </div>
</div>

<div id="viewWarehouseModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-3xl max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Warehouse Details</h3>
            <button id="closeViewWarehouseModal" class="text-gray-500 hover:text-gray-700"><i class='bx bx-x text-2xl'></i></button>
        </div>
        <div id="viewWarehouseContent"></div>
    </div>
</div>

<div id="requestRoomModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden" style="z-index: 60;">
    <div class="bg-white rounded-lg p-6 w-full max-w-lg max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Request Room for Warehouse</h3>
            <button id="closeRequestRoomModal" class="text-gray-500 hover:text-gray-700"><i class='bx bx-x text-2xl'></i></button>
        </div>
        <form id="requestRoomForm">
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Room Type</label>
                <select id="rmreq_room_type" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="office">Office</option>
                    <option value="department">Department</option>
                    <option value="facility">Facility</option>
                    <option value="room">Room</option>
                    <option value="storage">Storage</option>
                </select>
            </div>
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-1">Note</label>
                <textarea id="rmreq_note" rows="4" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>
            <div class="flex justify-end gap-3">
                <button type="button" id="cancelRequestRoomModal" class="px-4 py-2 bg-gray-200 rounded-lg">Cancel</button>
                <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">Submit Request</button>
            </div>
        </form>
    </div>
    </div>

<div id="roomStatusModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-4xl max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4 gap-3">
            <h3 class="text-xl font-semibold">Room Requests Status</h3>
            <div class="flex items-center gap-2">
                <button id="openRequestRoomFromStatusBtn" class="px-3 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 whitespace-nowrap flex items-center gap-2">
                    <i class='bx bx-door-open'></i> Request Room for Warehouse
                </button>
                <button id="closeRoomStatusModal" class="text-gray-500 hover:text-gray-700"><i class='bx bx-x text-2xl'></i></button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-800 text-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider whitespace-nowrap">ID</th>
                        <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider whitespace-nowrap">Requester</th>
                        <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider whitespace-nowrap">Room Type</th>
                        <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider whitespace-nowrap">Note</th>
                        <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider whitespace-nowrap">Date</th>
                        <th class="px-4 py-2 text-left text-xs font-medium uppercase tracking-wider whitespace-nowrap">Status</th>
                    </tr>
                </thead>
                <tbody id="roomStatusTableBody" class="bg-white divide-y divide-gray-200">
                    <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No data</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
var API_BASE_URL = typeof API_BASE_URL !== 'undefined' ? API_BASE_URL : '<?php echo url('/api/v1'); ?>';
var SWS_WAREHOUSE_API = `${API_BASE_URL}/sws/warehouse`;
var SWS_ROOM_REQ_PRIMARY = `${API_BASE_URL}/sws/warehouse/room-requests`;
var SWS_ROOM_REQ_FALLBACK = `${API_BASE_URL}/sws/room-requests`;
var ROOM_REQ_ENDPOINT = SWS_ROOM_REQ_PRIMARY;
var ROOM_REQ_ENDPOINT_LOCKED = false;
var CSRF_TOKEN = typeof CSRF_TOKEN !== 'undefined' ? CSRF_TOKEN : document.querySelector('meta[name="csrf-token"]').getAttribute('content');
var JWT_TOKEN = typeof JWT_TOKEN !== 'undefined' ? JWT_TOKEN : localStorage.getItem('jwt');

var warehouses = [];
let currentWarePage = 1;
const warePageSize = 10;

const els = {
    statWarehouses: document.getElementById('statWarehouses'),
    statCapacity: document.getElementById('statCapacity'),
    statUsed: document.getElementById('statUsed'),
    statFree: document.getElementById('statFree'),
    addBtn: document.getElementById('addWarehouseBtn'),
    tableBody: document.getElementById('warehousesTableBody'),
    modal: document.getElementById('warehouseModal'),
    modalTitle: document.getElementById('warehouseModalTitle'),
    modalClose: document.getElementById('closeWarehouseModal'),
    modalCancel: document.getElementById('cancelWarehouseModal'),
    form: document.getElementById('warehouseForm'),
    wareId: document.getElementById('ware_id'),
    wareName: document.getElementById('ware_name'),
    wareLocation: document.getElementById('ware_location'),
    wareCapacity: document.getElementById('ware_capacity'),
    wareSupportsFixed: document.getElementById('ware_supports_fixed_items'),
    wareStatus: document.getElementById('ware_status'),
    wareZoneType: document.getElementById('ware_zone_type')
};

const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, timerProgressBar: true, didOpen: (toast) => { toast.onmouseenter = Swal.stopTimer; toast.onmouseleave = Swal.resumeTimer; } });

function notify(message, type = 'info') { Toast.fire({ icon: type, title: message }); }

function formatNumber(n) { return Number(n || 0).toLocaleString(); }
function formatPercent(n) { return `${(Number(n || 0)).toFixed(2)}%`; }
function truncate(str, n) { if (!str) return ''; return str.length > n ? (str.slice(0, n) + '…') : str; }

async function loadWarehouses() {
    try {
        const response = await fetch(SWS_WAREHOUSE_API, { method: 'GET', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : '' }, credentials: 'include' });
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        const result = await response.json();
        warehouses = result.data || [];
        // Sort warehouses by creation date (newest first)
        warehouses.sort((a, b) => new Date(b.ware_created_at || 0) - new Date(a.ware_created_at || 0));
        renderWarehouses();
        renderStats();
    } catch (e) {
        els.tableBody.innerHTML = `<tr><td colspan="9" class="px-6 py-4 text-center text-red-600">Failed to load warehouses</td></tr>`;
        notify('Error loading warehouses', 'error');
    }
}

function renderStats() {
    const total = warehouses.length;
    const capacity = warehouses.reduce((s, w) => s + (Number(w.ware_capacity) || 0), 0);
    const used = warehouses.reduce((s, w) => s + (Number(w.ware_capacity_used) || 0), 0);
    const free = warehouses.reduce((s, w) => s + (Number(w.ware_capacity_free) || 0), 0);
    els.statWarehouses.textContent = formatNumber(total);
    els.statCapacity.textContent = formatNumber(capacity);
    els.statUsed.textContent = formatNumber(used);
    els.statFree.textContent = formatNumber(free);
}

function renderWarehouses() {
    const total = warehouses.length;
    const totalPages = Math.max(1, Math.ceil(total / warePageSize));
    if (currentWarePage > totalPages) currentWarePage = totalPages;
    if (currentWarePage < 1) currentWarePage = 1;
    const startIdx = (currentWarePage - 1) * warePageSize;
    const pageItems = warehouses.slice(startIdx, startIdx + warePageSize);

    if (pageItems.length === 0) {
        els.tableBody.innerHTML = `
            <tr>
                <td colspan="9" class="px-6 py-8 text-center text-gray-500">
                    <div class="flex flex-col items-center justify-center space-y-2">
                        <i class='bx bx-fw bxs-store text-4xl text-gray-400'></i>
                        <div>No Warehouses</div>
                    </div>
                </td>
            </tr>`;
    } else {
        els.tableBody.innerHTML = '';
        pageItems.forEach(w => {
            const tr = document.createElement('tr');
            let badgeClass = '';
            let statusIcon = '';
            if (w.ware_status === 'active') {
                badgeClass = 'badge-success';
                statusIcon = '<i class="bx bx-check-circle mr-1"></i>';
            } else if (w.ware_status === 'inactive') {
                badgeClass = 'badge-error';
                statusIcon = '<i class="bx bx-x-circle mr-1"></i>';
            } else if (w.ware_status === 'maintenance') {
                badgeClass = 'badge-warning';
                statusIcon = '<i class="bx bx-traffic-cone mr-1"></i>';
            }
            tr.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap">${w.ware_id || ''}</td>
                <td class="px-6 py-4 whitespace-nowrap">${w.ware_name || ''}</td>
                <td class="px-6 py-4 whitespace-nowrap"><span class="inline-block max-w-xs truncate" title="${w.ware_location || ''}">${truncate(String(w.ware_location || ''), 28)}</span></td>
                <td class="px-6 py-4 whitespace-nowrap">${formatNumber(w.ware_capacity)}</td>
                <td class="px-6 py-4 whitespace-nowrap">${formatNumber(w.ware_capacity_used)}</td>
                <td class="px-6 py-4 whitespace-nowrap">${formatNumber(w.ware_capacity_free)}</td>
                <td class="px-6 py-4 whitespace-nowrap">${formatPercent(w.ware_utilization)}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="badge ${badgeClass} flex items-center justify-center gap-1 px-3 py-2 rounded-full text-sm font-medium">
                        ${statusIcon}
                        ${(w.ware_status || '').charAt(0).toUpperCase() + (w.ware_status || '').slice(1)}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex gap-2">
                        <button class="text-primary transition-colors p-2 rounded-lg hover:bg-gray-50 budget-approval-btn" title="View Detail" data-action="view" data-id="${w.ware_id}"><i class='bx bx-show-alt text-xl'></i></button>
                        <button class="text-warning transition-colors p-2 rounded-lg hover:bg-gray-50 budget-approval-btn" title="Edit Detail" data-action="edit" data-id="${w.ware_id}"><i class='bx bx-edit text-xl'></i></button>
                        <button class="text-error transition-colors p-2 rounded-lg hover:bg-gray-50 budget-approval-btn" title="Delete Warehouse" data-action="delete" data-id="${w.ware_id}"><i class='bx bx-trash text-xl'></i></button>
                    </div>
                </td>`;
            els.tableBody.appendChild(tr);
        });
    }

    renderWarePager(total, totalPages);
}

function renderWarePager(total, totalPages){
    const info = document.getElementById('warePagerInfo');
    const display = document.getElementById('warePageDisplay');
    const start = total === 0 ? 0 : ((currentWarePage - 1) * warePageSize) + 1;
    const end = Math.min(currentWarePage * warePageSize, total);
    if (info) info.textContent = `Showing ${start}-${end} of ${total}`;
    if (display) display.textContent = `${currentWarePage} / ${totalPages}`;
    const prev = document.getElementById('warePrevBtn');
    const next = document.getElementById('wareNextBtn');
    if (prev) prev.disabled = currentWarePage <= 1;
    if (next) next.disabled = currentWarePage >= totalPages;
}

document.getElementById('warePager').addEventListener('click', function(ev){
    const btn = ev.target.closest('button[data-action]');
    if(!btn) return;
    const act = btn.getAttribute('data-action');
    if(act === 'prev'){ currentWarePage = Math.max(1, currentWarePage - 1); renderWarehouses(); }
    if(act === 'next'){ const max = Math.max(1, Math.ceil((warehouses.length||0)/warePageSize)); currentWarePage = Math.min(max, currentWarePage + 1); renderWarehouses(); }
});

function openModal(edit = false, w = null) {
    els.modalTitle.textContent = edit ? 'Edit Warehouse' : 'New Warehouse';
    els.wareId.value = edit && w ? w.ware_id : '';
    els.wareName.value = edit && w ? (w.ware_name || '') : '';
    els.wareLocation.value = edit && w ? (w.ware_location || '') : '';
    els.wareCapacity.value = edit && w ? (w.ware_capacity || '') : '';
    els.wareSupportsFixed.value = edit && w ? ((w.ware_supports_fixed_items ? 1 : 0)) : '1';
    els.wareStatus.value = edit && w ? (w.ware_status || 'active') : 'active';
    els.wareZoneType.value = edit && w ? (w.ware_zone_type || 'general') : 'general';
    els.modal.classList.remove('hidden');
}

function closeModal() { els.modal.classList.add('hidden'); }

async function saveWarehouse(e) {
    e.preventDefault();
    const id = els.wareId.value;
    const payload = {
        ware_name: els.wareName.value.trim(),
        ware_location: els.wareLocation.value.trim() || null,
        ware_capacity: els.wareCapacity.value ? Number(els.wareCapacity.value) : null,
        ware_status: els.wareStatus.value,
        ware_zone_type: els.wareZoneType.value,
        ware_supports_fixed_items: els.wareSupportsFixed.value === '1'
    };
    if (!payload.ware_name) { notify('Please enter warehouse name', 'error'); return; }
    try {
        const url = id ? `${SWS_WAREHOUSE_API}/${id}` : SWS_WAREHOUSE_API;
        const method = id ? 'PUT' : 'POST';
        const response = await fetch(url, { method, headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : '' }, body: JSON.stringify(payload), credentials: 'include' });
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        await loadWarehouses();
        closeModal();
        notify(id ? 'Warehouse updated' : 'Warehouse created', 'success');
    } catch (e) {
        notify('Error saving warehouse', 'error');
    }
}

async function deleteWarehouse(id) {
    const confirmResult = await Swal.fire({ title: 'Delete Warehouse?', text: 'This action cannot be undone', icon: 'warning', showCancelButton: true, confirmButtonText: 'Delete', cancelButtonText: 'Cancel' });
    if (!confirmResult.isConfirmed) return;
    try {
        const response = await fetch(`${SWS_WAREHOUSE_API}/${id}`, { method: 'DELETE', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : '' }, credentials: 'include' });
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        await loadWarehouses();
        notify('Warehouse deleted', 'success');
    } catch (e) {
        notify('Error deleting warehouse', 'error');
    }
}

function handleTableClick(e) {
    const btn = e.target.closest('button');
    if (!btn) return;
    const action = btn.dataset.action;
    const id = btn.dataset.id;
    if (action === 'edit') {
        const w = warehouses.find(x => String(x.ware_id) === String(id));
        if (w) openModal(true, w);
    } else if (action === 'delete') {
        deleteWarehouse(id);
    } else if (action === 'view') {
        viewWarehouse(id);
    }
}

function initWarehouseManagement() {
    els.addBtn && els.addBtn.addEventListener('click', () => openModal(false));
    els.modalClose && els.modalClose.addEventListener('click', closeModal);
    els.modalCancel && els.modalCancel.addEventListener('click', closeModal);
    els.form && els.form.addEventListener('submit', saveWarehouse);
    els.tableBody && els.tableBody.addEventListener('click', handleTableClick);
    const viewModal = document.getElementById('viewWarehouseModal');
    const closeViewBtn = document.getElementById('closeViewWarehouseModal');
    closeViewBtn && closeViewBtn.addEventListener('click', function() { viewModal.classList.add('hidden'); });
    viewModal && viewModal.addEventListener('click', function(e) { if (e.target === viewModal) { viewModal.classList.add('hidden'); } });
    loadWarehouses();

    const reqStatusBtn = document.getElementById('requestRoomStatusBtn');
    const reqModal = document.getElementById('requestRoomModal');
    const reqClose = document.getElementById('closeRequestRoomModal');
    const reqCancel = document.getElementById('cancelRequestRoomModal');
    const reqForm = document.getElementById('requestRoomForm');
    const statusModal = document.getElementById('roomStatusModal');
    const statusClose = document.getElementById('closeRoomStatusModal');
    const openReqInside = document.getElementById('openRequestRoomFromStatusBtn');

    openReqInside && openReqInside.addEventListener('click', () => { reqModal.classList.remove('hidden'); });
    reqClose && reqClose.addEventListener('click', () => { reqModal.classList.add('hidden'); });
    reqCancel && reqCancel.addEventListener('click', () => { reqModal.classList.add('hidden'); });
    reqModal && reqModal.addEventListener('click', function(e){ if(e.target === reqModal){ reqModal.classList.add('hidden'); } });
    statusClose && statusClose.addEventListener('click', () => { statusModal.classList.add('hidden'); });
    statusModal && statusModal.addEventListener('click', function(e){ if(e.target === statusModal){ statusModal.classList.add('hidden'); } });
    reqForm && reqForm.addEventListener('submit', submitRoomRequest);
    reqStatusBtn && reqStatusBtn.addEventListener('click', loadRoomRequests);
}

if (document.readyState === 'loading') { document.addEventListener('DOMContentLoaded', initWarehouseManagement); } else { initWarehouseManagement(); }

async function viewWarehouse(id) {
    const modal = document.getElementById('viewWarehouseModal');
    const container = document.getElementById('viewWarehouseContent');
    if (container) container.innerHTML = '<div class="flex justify-center items-center py-6"><div class="loading loading-spinner mr-3"></div>Loading...</div>';
    modal && modal.classList.remove('hidden');
    try {
        const response = await fetch(`${SWS_WAREHOUSE_API}/${id}`, { method: 'GET', headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : '' }, credentials: 'include' });
        if (!response.ok) throw new Error(`HTTP ${response.status}`);
        const result = await response.json();
        const w = result.data;
        const content = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div><span class="text-sm text-gray-500">ID</span><p class="font-semibold">${w.ware_id || ''}</p></div>
                <div><span class="text-sm text-gray-500">Name</span><p class="font-semibold">${w.ware_name || ''}</p></div>
                <div><span class="text-sm text-gray-500">Status</span><p class="font-semibold">${w.ware_status || ''}</p></div>
                <div class="md:col-span-2"><span class="text-sm text-gray-500">Location</span><p class="font-semibold break-words">${w.ware_location || ''}</p></div>
                <div><span class="text-sm text-gray-500">Capacity</span><p class="font-semibold">${formatNumber(w.ware_capacity)}</p></div>
                <div><span class="text-sm text-gray-500">Used</span><p class="font-semibold">${formatNumber(w.ware_capacity_used)}</p></div>
                <div><span class="text-sm text-gray-500">Free</span><p class="font-semibold">${formatNumber(w.ware_capacity_free)}</p></div>
                <div><span class="text-sm text-gray-500">Utilization</span><p class="font-semibold">${formatPercent(w.ware_utilization)}</p></div>
                <div><span class="text-sm text-gray-500">Zone Type</span><p class="font-semibold">${w.ware_zone_type || ''}</p></div>
                <div><span class="text-sm text-gray-500">Supports Fixed</span><p class="font-semibold">${w.ware_supports_fixed_items ? 'Yes' : 'No'}</p></div>
            </div>`;
        if (container) container.innerHTML = content;
    } catch (e) {
        if (container) container.innerHTML = '<div class="text-red-600">Failed to load warehouse</div>';
        notify('Error loading warehouse', 'error');
    }
}

async function submitRoomRequest(e){
    e.preventDefault();
    const type = document.getElementById('rmreq_room_type').value;
    const note = document.getElementById('rmreq_note').value.trim() || null;
    const payload = { rmreq_room_type: type, rmreq_note: note };
    try {
        let response = await fetch(ROOM_REQ_ENDPOINT, {
            method: 'POST',
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
        if (response.status === 404 && !ROOM_REQ_ENDPOINT_LOCKED) {
            ROOM_REQ_ENDPOINT = SWS_ROOM_REQ_FALLBACK;
            ROOM_REQ_ENDPOINT_LOCKED = true;
            response = await fetch(ROOM_REQ_ENDPOINT, {
                method: 'POST',
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
        }
        if (!response.ok) {
            const t = await response.text();
            throw new Error(`HTTP ${response.status} ${t}`);
        }
        notify('Room request submitted', 'success');
        document.getElementById('requestRoomModal').classList.add('hidden');
        document.getElementById('rmreq_note').value = '';
        loadRoomRequests();
    } catch (e) {
        notify('Failed to submit room request', 'error');
    }
}

async function loadRoomRequests(){
    const modal = document.getElementById('roomStatusModal');
    const tbody = document.getElementById('roomStatusTableBody');
    tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500"><div class="flex justify-center items-center py-4"><div class="loading loading-spinner mr-3"></div>Loading...</div></td></tr>`;
    modal.classList.remove('hidden');
    try{
        let response = await fetch(ROOM_REQ_ENDPOINT, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            credentials: 'include'
        });
        if (response.status === 404 && !ROOM_REQ_ENDPOINT_LOCKED) {
            ROOM_REQ_ENDPOINT = SWS_ROOM_REQ_FALLBACK;
            ROOM_REQ_ENDPOINT_LOCKED = true;
            response = await fetch(ROOM_REQ_ENDPOINT, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
                },
                credentials: 'include'
            });
        }
        if(!response.ok) throw new Error(`HTTP ${response.status}`);
        const result = await response.json();
        const rows = result.data || [];
        rows.sort((a,b)=> {
            const da = new Date(a.rmreq_date || 0).getTime();
            const db = new Date(b.rmreq_date || 0).getTime();
            if (db !== da) return db - da;
            const ia = Number(a.rmreq_id || 0);
            const ib = Number(b.rmreq_id || 0);
            return ib - ia;
        });
        if(rows.length === 0){
            tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No requests</td></tr>`;
            return;
        }
        tbody.innerHTML = '';
        rows.forEach(r => {
            const tr = document.createElement('tr');
            const badge = r.rmreq_status === 'approved' ? 'badge-success' : (r.rmreq_status === 'rejected' ? 'badge-error' : 'badge-warning');
            tr.innerHTML = `
                <td class="px-4 py-2 whitespace-nowrap">${r.rmreq_id}</td>
                <td class="px-4 py-2 whitespace-nowrap">${r.rmreq_requester || ''}</td>
                <td class="px-4 py-2 whitespace-nowrap capitalize">${r.rmreq_room_type || ''}</td>
                <td class="px-4 py-2 whitespace-nowrap"><span class="inline-block max-w-[28rem] truncate" title="${(r.rmreq_note || '').replace(/\"/g, '&quot;')}">${r.rmreq_note || ''}</span></td>
                <td class="px-4 py-2 whitespace-nowrap">${r.rmreq_date ? new Date(r.rmreq_date).toLocaleDateString('en-US') : ''}</td>
                <td class="px-4 py-2 whitespace-nowrap"><span class="badge ${badge}">${r.rmreq_status}</span></td>
            `;
            tbody.appendChild(tr);
        });
    } catch(e){
        tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-4 text-center text-red-600">Failed to load room requests</td></tr>`;
        notify('Error loading room requests', 'error');
    }
}
</script>
