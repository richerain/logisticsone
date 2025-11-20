<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bxs-warehouse'></i>Warehouse Management</h2>
    </div>
    <div class="text-right">
        <span class="text-md text-gray-600">Smart Warehousing System</span>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">Warehouse Overview</h3>
        <div class="flex gap-2">
            <button id="addWarehouseBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2"><i class='bx bx-plus'></i> Add Warehouse</button>
        </div>
    </div>

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

    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-800 font-bold text-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Warehouse</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Location</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Capacity</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Used</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Free</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Utilization</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider">Actions</th>
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
                    <label class="block text-sm font-medium text-gray-700 mb-1">ID</label>
                    <input type="text" id="ware_code_display" class="w-full px-3 py-2 border border-gray-300 rounded-lg bg-gray-100" placeholder="Auto-generated on save" disabled>
                </div>
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

<script>
var API_BASE_URL = typeof API_BASE_URL !== 'undefined' ? API_BASE_URL : '<?php echo url('/api/v1'); ?>';
var SWS_WAREHOUSE_API = `${API_BASE_URL}/sws/warehouse`;
var CSRF_TOKEN = typeof CSRF_TOKEN !== 'undefined' ? CSRF_TOKEN : document.querySelector('meta[name="csrf-token"]').getAttribute('content');
var JWT_TOKEN = typeof JWT_TOKEN !== 'undefined' ? JWT_TOKEN : localStorage.getItem('jwt');

var warehouses = [];

const els = {
    statWarehouses: document.getElementById('statWarehouses'),
    statCapacity: document.getElementById('statCapacity'),
    statUsed: document.getElementById('statUsed'),
    statFree: document.getElementById('statFree'),
    addBtn: document.getElementById('addWarehouseBtn'), // This was already present, no change needed here.
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
        renderWarehouses();
        renderStats();
    } catch (e) {
        els.tableBody.innerHTML = `<tr><td colspan="8" class="px-6 py-4 text-center text-red-600">Failed to load warehouses</td></tr>`;
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
    if (warehouses.length === 0) {
        els.tableBody.innerHTML = `<tr><td colspan="9" class="px-6 py-4 text-center text-gray-500">No warehouses</td></tr>`;
        return;
    }
    els.tableBody.innerHTML = '';
    warehouses.forEach(w => {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td class="px-6 py-4">${w.ware_id || ''}</td>
            <td class="px-6 py-4">${w.ware_name || ''}</td>
            <td class="px-6 py-4"><span class="inline-block max-w-xs truncate" title="${w.ware_location || ''}">${truncate(String(w.ware_location || ''), 28)}</span></td>
            <td class="px-6 py-4">${formatNumber(w.ware_capacity)}</td>
            <td class="px-6 py-4">${formatNumber(w.ware_capacity_used)}</td>
            <td class="px-6 py-4">${formatNumber(w.ware_capacity_free)}</td>
            <td class="px-6 py-4">${formatPercent(w.ware_utilization)}</td>
            <td class="px-6 py-4"><span class="badge ${w.ware_status === 'active' ? 'badge-warning' : w.ware_status === 'maintenance' ? 'badge-success' : 'badge-error'}">${w.ware_status}</span></td>
            <td class="px-6 py-4">
                <div class="flex gap-2">
                    <button class="text-primary transition-colors p-2 rounded-lg hover:bg-gray-50 budget-approval-btn" title="View Detail" data-action="view" data-id="${w.ware_id}"><i class='bx bx-show-alt text-xl'></i></button>
                    <button class="text-warning transition-colors p-2 rounded-lg hover:bg-gray-50 budget-approval-btn" title="Edit Detail" data-action="edit" data-id="${w.ware_id}"><i class='bx bx-edit text-xl'></i></button>
                    <button class="text-error transition-colors p-2 rounded-lg hover:bg-gray-50 budget-approval-btn" title="Delete Warehouse" data-action="delete" data-id="${w.ware_id}"><i class='bx bx-trash text-xl'></i></button>
                </div>
            </td>`;
        els.tableBody.appendChild(tr);
    });
}

function openModal(edit = false, w = null) {
    els.modalTitle.textContent = edit ? 'Edit Warehouse' : 'New Warehouse';
    els.wareId.value = edit && w ? w.ware_id : '';
    els.wareName.value = edit && w ? (w.ware_name || '') : '';
    els.wareLocation.value = edit && w ? (w.ware_location || '') : '';
    els.wareCapacity.value = edit && w ? (w.ware_capacity || '') : '';
    els.wareSupportsFixed.value = edit && w ? ((w.ware_supports_fixed_items ? 1 : 0)) : '1';
    els.wareStatus.value = edit && w ? (w.ware_status || 'active') : 'active';
    els.wareZoneType.value = edit && w ? (w.ware_zone_type || 'general') : 'general';
    const codeDisplay = document.getElementById('ware_code_display');
    if (codeDisplay) codeDisplay.value = edit && w ? (w.ware_id || '') : '';
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
        // ware_capacity_used is not editable in modal; server keeps existing value
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
    els.form && els.form.addEventListener('submit', saveWarehouse); // This was already present, no change needed here.
    els.tableBody && els.tableBody.addEventListener('click', handleTableClick);
    const viewModal = document.getElementById('viewWarehouseModal');
    const closeViewBtn = document.getElementById('closeViewWarehouseModal');
    closeViewBtn && closeViewBtn.addEventListener('click', function() { viewModal.classList.add('hidden'); });
    viewModal && viewModal.addEventListener('click', function(e) { if (e.target === viewModal) { viewModal.classList.add('hidden'); } });
    loadWarehouses();
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


</script>