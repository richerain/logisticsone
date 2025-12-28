<!-- resources/views/sws/inventory-flow.blade.php -->
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bx-sync'></i>Inventory Flow</h2>
    </div>
    <div class="text-right">
        <span class="text-md text-gray-600">Smart Warehousing System</span>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg p-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="stat-card bg-blue-50 p-4 rounded-lg text-center">
            <i class='bx bx-package text-3xl text-blue-600 mb-2'></i>
            <h3 class="font-semibold text-blue-800">Total Items</h3>
            <p id="flow_total_items" class="text-2xl font-bold text-blue-600">0</p>
        </div>
        <div class="stat-card bg-green-50 p-4 rounded-lg text-center">
            <i class='bx bx-import text-3xl text-green-600 mb-2'></i>
            <h3 class="font-semibold text-green-800">Incoming</h3>
            <p id="flow_incoming" class="text-2xl font-bold text-green-600">0</p>
        </div>
        <div class="stat-card bg-red-50 p-4 rounded-lg text-center">
            <i class='bx bx-export text-3xl text-red-600 mb-2'></i>
            <h3 class="font-semibold text-red-800">Outgoing</h3>
            <p id="flow_outgoing" class="text-2xl font-bold text-red-600">0</p>
        </div>
        <div class="stat-card bg-yellow-50 p-4 rounded-lg text-center">
            <i class='bx bx-transfer text-3xl text-yellow-600 mb-2'></i>
            <h3 class="font-semibold text-yellow-800">Transfers</h3>
            <p id="flow_transfers" class="text-2xl font-bold text-yellow-600">0</p>
        </div>
    </div>

    <div class="flex items-center gap-3 mb-4 whitespace-nowrap">
        <input id="flow_search" type="text" placeholder="Search by item, type, ref, from/to" class="px-3 py-2 border border-gray-300 rounded-lg w-full" />
        <select id="flow_status_filter" class="px-3 py-2 border border-gray-300 rounded-lg">
            <option value="">All Status</option>
            <option value="completed">Completed</option>
            <option value="pending">Pending</option>
            <option value="failed">Failed</option>
        </select>
    </div>

    <div class="overflow-x-auto">
        <table class="table table-zebra w-full">
            <thead>
                <tr class="bg-gray-700 font-bold text-white">
                    <th>Date</th>
                    <th>Type</th>
                    <th>Item</th>
                    <th>Quantity</th>
                    <th>From</th>
                    <th>To</th>
                    <th>Status</th>
                    <th>Reference</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody id="flow_table_body"></tbody>
        </table>
    </div>
    <div id="flowPager" class="flex items-center justify-between mt-3">
        <div id="flowPagerInfo" class="text-sm text-gray-600"></div>
        <div class="join">
            <button class="btn btn-sm join-item" id="flowPrevBtn" data-action="prev">Prev</button>
            <span class="btn btn-sm join-item" id="flowPageDisplay">1 / 1</span>
            <button class="btn btn-sm join-item" id="flowNextBtn" data-action="next">Next</button>
        </div>
    </div>
</div>

<script>
var API_BASE_URL = typeof API_BASE_URL !== 'undefined' ? API_BASE_URL : '<?php echo url('/api/v1'); ?>';
const FLOW_API = `${API_BASE_URL}/sws/inventory-flow`;
var CSRF_TOKEN = typeof CSRF_TOKEN !== 'undefined' ? CSRF_TOKEN : document.querySelector('meta[name="csrf-token"]').getAttribute('content');
var JWT_TOKEN = typeof JWT_TOKEN !== 'undefined' ? JWT_TOKEN : localStorage.getItem('jwt');
let currentFlowPage = 1;
const flowPageSize = 10;

function flowParams() {
    const range = document.getElementById('flow_quick_range').value;
    const from = document.getElementById('flow_from').value;
    const to = document.getElementById('flow_to').value;
    const params = new URLSearchParams();
    if (range) params.set('range', range);
    if (from) params.set('from', from);
    if (to) params.set('to', to);
    return params.toString();
}

async function loadInventoryFlow() {
    const url = FLOW_API;
    const res = await fetch(url, { 
        method: 'GET',
        headers: { 
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
        },
        credentials: 'include'
    });
    const payload = await res.json();
    if (!payload.success) return;
    const s = payload.data.summary;
    document.getElementById('flow_total_items').innerText = s.total_items;
    document.getElementById('flow_incoming').innerText = s.incoming;
    document.getElementById('flow_outgoing').innerText = s.outgoing;
    document.getElementById('flow_transfers').innerText = s.transfers;
    const tbody = document.getElementById('flow_table_body');
    const statusFilter = document.getElementById('flow_status_filter').value.trim().toLowerCase();
    const q = document.getElementById('flow_search').value.trim().toLowerCase();
    let tx = (payload.data.transactions || []);
    window.flowAllTransactions = tx.slice();
    if (statusFilter) { tx = tx.filter(t => (t.status || '').toLowerCase() === statusFilter); }
    if (q) {
        tx = tx.filter(t => {
            const hay = [
                t.item ? (t.item.item_name || '') : '',
                t.type || '',
                t.reference_id || '',
                t.from_location ? (t.from_location.loc_name || '') : '',
                t.to_location ? (t.to_location.loc_name || '') : ''
            ].join(' ').toLowerCase();
            return hay.includes(q);
        });
    }
    const total = tx.length;
    const totalPages = Math.max(1, Math.ceil(total / flowPageSize));
    if (currentFlowPage > totalPages) currentFlowPage = totalPages;
    if (currentFlowPage < 1) currentFlowPage = 1;
    const startIdx = (currentFlowPage - 1) * flowPageSize;
    const pageItems = tx.slice(startIdx, startIdx + flowPageSize);

    const rows = pageItems.map(t => `
        <tr>
            <td class="whitespace-nowrap">${new Date(t.transaction_date).toLocaleString()}</td>
            <td class="whitespace-nowrap">${t.type}</td>
            <td class="whitespace-nowrap">${t.item ? t.item.item_name : ''}</td>
            <td class="whitespace-nowrap">${t.quantity}</td>
            <td class="whitespace-nowrap">${t.from_location ? t.from_location.loc_name : ''}</td>
            <td class="whitespace-nowrap">${t.to_location ? t.to_location.loc_name : ''}</td>
            <td class="whitespace-nowrap">
                ${renderStatusBadge(t.status)}
            </td>
            <td class="whitespace-nowrap">${t.reference_id || ''}</td>
            <td class="whitespace-nowrap">
                <button class="text-gray-700 transition-colors p-2 rounded-lg hover:bg-gray-50" data-action="view" data-id="${t.tra_id}" title="View Full Details"><i class='bx bx-detail text-xl'></i></button>
                <button class="text-error transition-colors p-2 rounded-lg hover:bg-gray-50 ml-2" data-action="delete" data-id="${t.tra_id}" title="Delete"><i class='bx bx-trash text-xl'></i></button>
            </td>
        </tr>
    `);
    tbody.innerHTML = rows.length > 0 
        ? rows.join('') 
        : '<tr><td colspan="9" class="text-center py-4 text-gray-500">No inventory flows</td></tr>';

    renderFlowPager(total, totalPages);

    window._flowTransactions = {};
    (tx || []).forEach(t => { window._flowTransactions[t.tra_id] = t; });

    tbody.querySelectorAll('button').forEach(btn => {
        btn.addEventListener('click', async (e) => {
            const id = e.currentTarget.getAttribute('data-id');
            const action = e.currentTarget.getAttribute('data-action');
            if (action === 'view') {
                const t = window._flowTransactions[id];
                if (!t) return;
                renderFlowDetails(t);
                document.getElementById('flow_view_modal').classList.remove('hidden');
            } else if (action === 'delete') {
                window._flowPendingDeleteId = id;
                document.getElementById('flow_confirm_modal').classList.remove('hidden');
            }
        });
    });
}

function renderFlowPager(total, totalPages){
    const info = document.getElementById('flowPagerInfo');
    const display = document.getElementById('flowPageDisplay');
    const start = total === 0 ? 0 : ((currentFlowPage - 1) * flowPageSize) + 1;
    const end = Math.min(currentFlowPage * flowPageSize, total);
    if (info) info.textContent = `Showing ${start}-${end} of ${total}`;
    if (display) display.textContent = `${currentFlowPage} / ${totalPages}`;
    const prev = document.getElementById('flowPrevBtn');
    const next = document.getElementById('flowNextBtn');
    if (prev) prev.disabled = currentFlowPage <= 1;
    if (next) next.disabled = currentFlowPage >= totalPages;
}

function getFlowFiltered(){
    let list = (window.flowAllTransactions || []).slice();
    const statusFilter = document.getElementById('flow_status_filter').value.trim().toLowerCase();
    const q = document.getElementById('flow_search').value.trim().toLowerCase();
    if (statusFilter) { list = list.filter(t => (t.status || '').toLowerCase() === statusFilter); }
    if (q) {
        list = list.filter(t => {
            const hay = [
                t.item ? (t.item.item_name || '') : '',
                t.type || '',
                t.reference_id || '',
                t.from_location ? (t.from_location.loc_name || '') : '',
                t.to_location ? (t.to_location.loc_name || '') : ''
            ].join(' ').toLowerCase();
            return hay.includes(q);
        });
    }
    return list;
}

document.getElementById('flowPager').addEventListener('click', function(ev){
    const btn = ev.target.closest('button[data-action]');
    if(!btn) return;
    const act = btn.getAttribute('data-action');
    if(act === 'prev'){ currentFlowPage = Math.max(1, currentFlowPage - 1); loadInventoryFlow(); }
    if(act === 'next'){ const max = Math.max(1, Math.ceil((getFlowFiltered().length||0)/flowPageSize)); currentFlowPage = Math.min(max, currentFlowPage + 1); loadInventoryFlow(); }
});

function renderStatusBadge(status) {
    const s = String(status || '').toLowerCase();
    if (s === 'completed') return "<span class='badge badge-success flex items-center gap-1 px-3 py-2 rounded-full text-sm font-medium'><i class='bx bx-check-circle mr-1'></i>Completed</span>";
    if (s === 'pending') return "<span class='badge badge-warning flex items-center gap-1 px-3 py-2 rounded-full text-sm font-medium'><i class='bx bx-time mr-1'></i>Pending</span>";
    if (s === 'failed') return "<span class='badge badge-error flex items-center gap-1 px-3 py-2 rounded-full text-sm font-medium'><i class='bx bx-error-circle mr-1'></i>Failed</span>";
    return `<span class='badge flex items-center gap-1 px-3 py-2 rounded-full text-sm font-medium'><i class='bx bx-help-circle mr-1'></i>${status || 'Unknown'}</span>`;
}

async function renderFlowDetails(t) {
    const base = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><span class="text-sm text-gray-500">Date</span><p class="font-semibold">${new Date(t.transaction_date).toLocaleString()}</p></div>
            <div><span class="text-sm text-gray-500">Type</span><p class="font-semibold">${t.type}</p></div>
            <div><span class="text-sm text-gray-500">Quantity</span><p class="font-semibold">${t.quantity}</p></div>
            <div><span class="text-sm text-gray-500">From</span><p class="font-semibold">${t.from_location ? t.from_location.loc_name : ''}</p></div>
            <div><span class="text-sm text-gray-500">To</span><p class="font-semibold">${t.to_location ? t.to_location.loc_name : ''}</p></div>
            <div><span class="text-sm text-gray-500">Status</span><p class="font-semibold">${t.status}</p></div>
            <div><span class="text-sm text-gray-500">Reference</span><p class="font-semibold">${t.reference_id || ''}</p></div>
        </div>
        <hr class="my-4" />
        <div class="mb-2"><h4 class="font-semibold">Item Details</h4></div>
    `;
    let itemDetailsHtml = '<div class="text-gray-500">Loading item details...</div>';
    try {
        if (t.item && t.item.item_id) {
            const res = await fetch(`${API_BASE_URL}/sws/items/${t.item.item_id}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
                },
                credentials: 'include'
            });
            const result = await res.json();
            if (result.success && result.data) {
                const item = result.data;
                const unitPrice = parseFloat(item.item_unit_price) || 0;
                const quantity = parseInt(item.item_current_stock) || 0;
                const totalValue = unitPrice * quantity;
                const lastUpdated = item.item_updated_at || item.item_created_at;
                itemDetailsHtml = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><span class="text-sm text-gray-500">Item Code</span><p class="font-semibold font-mono">${item.item_code || 'N/A'}</p></div>
                        <div><span class="text-sm text-gray-500">SKU</span><p class="font-semibold font-mono">${item.item_stock_keeping_unit || 'N/A'}</p></div>
                        <div><span class="text-sm text-gray-500">Item Name</span><p class="font-semibold">${item.item_name || 'N/A'}</p></div>
                        <div><span class="text-sm text-gray-500">Category</span><p class="font-semibold">${item.category?.cat_name || 'Uncategorized'}</p></div>
                        <div><span class="text-sm text-gray-500">Stored From</span><p class="font-semibold">${item.item_stored_from || 'N/A'}</p></div>
                        <div><span class="text-sm text-gray-500">Item Type</span><p class="font-semibold">${item.item_item_type || 'N/A'}</p></div>
                        <div><span class="text-sm text-gray-500">Current Stock</span><p class="font-semibold">${Number(quantity).toLocaleString()}</p></div>
                        <div><span class="text-sm text-gray-500">Max Stock</span><p class="font-semibold">${Number(item.item_max_stock || 0).toLocaleString()}</p></div>
                        <div><span class="text-sm text-gray-500">Unit Price</span><p class="font-semibold">₱${unitPrice.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</p></div>
                        <div><span class="text-sm text-gray-500">Total Value</span><p class="font-semibold">₱${totalValue.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</p></div>
                        <div><span class="text-sm text-gray-500">Liquidity Risk</span><p class="font-semibold">${item.item_liquidity_risk_level || 'N/A'}</p></div>
                        <div><span class="text-sm text-gray-500">Fixed Asset</span><p class="font-semibold">${item.item_is_fixed ? 'Yes' : 'No'}</p></div>
                        <div><span class="text-sm text-gray-500">Is Collateral</span><p class="font-semibold">${item.item_is_collateral ? 'Yes' : 'No'}</p></div>
                        <div><span class="text-sm text-gray-500">Expiration Date</span><p class="font-semibold">${item.item_expiration_date ? new Date(item.item_expiration_date).toLocaleDateString('en-PH') : 'N/A'}</p></div>
                        <div><span class="text-sm text-gray-500">Warranty End</span><p class="font-semibold">${item.item_warranty_end ? new Date(item.item_warranty_end).toLocaleDateString('en-PH') : 'N/A'}</p></div>
                        <div><span class="text-sm text-gray-500">Last Updated</span><p class="font-semibold">${lastUpdated ? new Date(lastUpdated).toLocaleDateString('en-PH') : 'N/A'}</p></div>
                        <div class="md:col-span-2"><span class="text-sm text-gray-500">Description</span><p class="font-semibold break-words">${item.item_description || 'N/A'}</p></div>
                    </div>
                `;
            } else {
                itemDetailsHtml = '<div class="text-red-600">Failed to load item details</div>';
            }
        } else {
            itemDetailsHtml = '<div class="text-gray-500">No item data available</div>';
        }
    } catch (_) {
        itemDetailsHtml = '<div class="text-red-600">Failed to load item details</div>';
    }
    document.getElementById('flow_view_content').innerHTML = base + itemDetailsHtml;
}

document.getElementById('flow_status_filter').addEventListener('change', () => { currentFlowPage = 1; loadInventoryFlow(); });
document.getElementById('flow_search').addEventListener('input', () => { currentFlowPage = 1; loadInventoryFlow(); });

document.body.insertAdjacentHTML('beforeend', `
<div id="flow_view_modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-3xl max-h-[90vh] overflow-y-auto">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Inventory Flow Details</h3>
            <button id="flow_view_close" class="text-gray-500 hover:text-gray-700"><i class='bx bx-x text-2xl'></i></button>
        </div>
        <div id="flow_view_content"></div>
    </div>
</div>
<div id="flow_confirm_modal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-lg p-6 w-full max-w-md">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Delete Transaction</h3>
            <button id="flow_confirm_close" class="text-gray-500 hover:text-gray-700"><i class='bx bx-x text-2xl'></i></button>
        </div>
        <p class="text-gray-700 mb-4">Are you sure you want to delete this transaction?</p>
        <div class="flex justify-end gap-3">
            <button id="flow_confirm_cancel" class="px-4 py-2 bg-gray-200 rounded-lg">Cancel</button>
            <button id="flow_confirm_delete" class="px-4 py-2 bg-red-600 text-white rounded-lg">Delete</button>
        </div>
    </div>
</div>
`);

document.getElementById('flow_view_close').addEventListener('click', () => {
    document.getElementById('flow_view_modal').classList.add('hidden');
});
document.getElementById('flow_confirm_close').addEventListener('click', () => {
    document.getElementById('flow_confirm_modal').classList.add('hidden');
});
document.getElementById('flow_confirm_cancel').addEventListener('click', () => {
    document.getElementById('flow_confirm_modal').classList.add('hidden');
});
document.getElementById('flow_confirm_delete').addEventListener('click', async () => {
    const id = window._flowPendingDeleteId;
    if (!id) return;
    const url = `${API_BASE_URL}/sws/inventory-flow/${id}`;
    const res = await fetch(url, {
        method: 'DELETE',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
        },
        credentials: 'include'
    });
    const result = await res.json();
    document.getElementById('flow_confirm_modal').classList.add('hidden');
    if (result.success) {
        loadInventoryFlow();
    } else {
        alert(result.message || 'Failed to delete');
    }
});

loadInventoryFlow();
</script>