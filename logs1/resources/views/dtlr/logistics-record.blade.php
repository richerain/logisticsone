<!-- resources/views/dtlr/logistics-record.blade.php -->
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bxs-package'></i>Logistics Record</h2>
    </div>
    <div class="text-right">
        <span class="text-md text-gray-600">Document Tracking & Logistics Record</span>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">Shipment Records</h3>
        <div class="flex space-x-2">
            <button class="btn btn-outline">
                <i class='bx bx-filter mr-2'></i>Filter
            </button>
            <button class="btn btn-primary">
                <i class='bx bx-plus mr-2'></i>New Record
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="table table-zebra w-full" id="logRecTable">
            <thead>
                <tr class="bg-gray-700 font-bold text-white">
                    <th>Shipment ID</th>
                    <th>Origin</th>
                    <th>Destination</th>
                    <th>Carrier</th>
                    <th>Status</th>
                    <th>Estimated Delivery</th>
                    <th>Actual Delivery</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="logRecTableBody">
                <tr id="logRecLoadingRow">
                    <td colspan="8">
                        <div class="text-center py-8">
                            <div class="loading loading-spinner loading-lg"></div>
                            <p class="mt-2 text-gray-600">Loading Records...</p>
                            <p class="text-sm text-gray-500" id="logRecLoadingInfo"></p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="logRecPager" class="flex items-center justify-between mt-4">
        <div id="logRecPagerInfo" class="text-sm text-gray-600"></div>
        <div class="join">
            <button class="btn btn-sm join-item" id="logRecPrevBtn" data-action="prev">Prev</button>
            <span class="btn btn-sm join-item" id="logRecPageDisplay">1 / 1</span>
            <button class="btn btn-sm join-item" id="logRecNextBtn" data-action="next">Next</button>
        </div>
    </div>
</div>

<script>
var API_BASE_URL = typeof API_BASE_URL !== 'undefined' ? API_BASE_URL : '<?php echo url('/api/v1'); ?>';
var DTLR_API = `${API_BASE_URL}/dtlr`;
var CSRF_TOKEN = typeof CSRF_TOKEN !== 'undefined' ? CSRF_TOKEN : document.querySelector('meta[name="csrf-token"]').getAttribute('content');

let recordsState = [];
let currentRecordsPage = 1;
const recordsPageSize = 10;

function recStatusBadge(status){
    if(status === 'delivered' || status === 'completed') return "<span class='badge badge-success'>Delivered</span>";
    if(status === 'in_transit' || status === 'shipped') return "<span class='badge badge-info'>In Transit</span>";
    if(status === 'delayed') return "<span class='badge badge-warning'>Delayed</span>";
    if(status === 'cancelled') return "<span class='badge badge-error'>Cancelled</span>";
    return "<span class='badge'>N/A</span>";
}

function renderRecords(rows){
    const body = document.getElementById('logRecTableBody');
    body.innerHTML = '';
    if(!rows || rows.length === 0){
        body.innerHTML = `<tr><td colspan="8" class="px-4 py-3 text-center text-gray-500">No records found</td></tr>`;
        renderLogRecPager(0, 1);
        return;
    }
    const total = rows.length;
    const totalPages = Math.max(1, Math.ceil(total / recordsPageSize));
    if(currentRecordsPage > totalPages) currentRecordsPage = totalPages;
    if(currentRecordsPage < 1) currentRecordsPage = 1;
    const start = (currentRecordsPage - 1) * recordsPageSize;
    const pageItems = rows.slice(start, start + recordsPageSize);
    const html = pageItems.map(r => `
        <tr>
            <td class="whitespace-nowrap">${r.shipment_code || r.shipment_id || r.id || ''}</td>
            <td class="whitespace-nowrap">${r.origin || ''}</td>
            <td class="whitespace-nowrap">${r.destination || ''}</td>
            <td class="whitespace-nowrap">${r.carrier || ''}</td>
            <td class="whitespace-nowrap">${recStatusBadge(r.status)}</td>
            <td class="whitespace-nowrap">${r.estimated_delivery || r.eta || ''}</td>
            <td class="whitespace-nowrap">${r.actual_delivery || r.etd || ''}</td>
            <td class="whitespace-nowrap">
                <div class="flex items-center gap-2">
                    <button class="text-indigo-600 transition-colors p-2 rounded-lg hover:bg-gray-50" data-action="view" data-id="${r.id || ''}"><i class='bx bx-show-alt text-xl'></i></button>
                </div>
            </td>
        </tr>
    `).join('');
    body.innerHTML = html;
    renderLogRecPager(total, totalPages);
}

function renderLogRecPager(total, pages){
    const info = document.getElementById('logRecPagerInfo');
    const display = document.getElementById('logRecPageDisplay');
    const start = total === 0 ? 0 : ((currentRecordsPage - 1) * recordsPageSize) + 1;
    const end = Math.min(currentRecordsPage * recordsPageSize, total);
    if(info) info.textContent = `Showing ${start}-${end} of ${total}`;
    if(display) display.textContent = `${currentRecordsPage} / ${Math.max(1, pages)}`;
    const prev = document.getElementById('logRecPrevBtn');
    const next = document.getElementById('logRecNextBtn');
    if(prev) prev.disabled = currentRecordsPage <= 1;
    if(next) next.disabled = currentRecordsPage >= pages;
}

async function loadRecords(){
    try{
        const res = await fetch(`${DTLR_API}/logistics-record`, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF_TOKEN } });
        const json = await res.json();
        const rows = json.data || [];
        recordsState = rows;
        renderRecords(recordsState);
    }catch(e){
        const info = document.getElementById('logRecLoadingInfo');
        if(info) info.textContent = 'Failed to load records';
    }
}

document.getElementById('logRecPager').addEventListener('click', function(ev){
    const btn = ev.target.closest('button[data-action]');
    if(!btn) return;
    const act = btn.getAttribute('data-action');
    if(act === 'prev'){ currentRecordsPage = Math.max(1, currentRecordsPage - 1); renderRecords(recordsState); }
    if(act === 'next'){ const max = Math.max(1, Math.ceil((recordsState.length||0)/recordsPageSize)); currentRecordsPage = Math.min(max, currentRecordsPage + 1); renderRecords(recordsState); }
});

if(document.readyState === 'loading'){
    document.addEventListener('DOMContentLoaded', loadRecords);
}else{
    loadRecords();
}
</script>