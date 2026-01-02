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
    <div class="flex flex-wrap items-center justify-between gap-3 mb-4">
        <label class="input input-bordered flex items-center gap-2 flex-1 min-w-0">
            <i class='bx bx-search text-xl text-gray-400'></i>
            <input id="logRecSearch" type="text" class="grow" placeholder="Search records..." />
        </label>
        <div class="flex items-center gap-2">
            <label class="text-sm text-gray-600 whitespace-nowrap">Module</label>
            <select id="logRecFilterModule" class="select select-bordered select-sm">
                <option value="all">All</option>
                <option value="Procurement &amp; Sourcing Management">Procurement &amp; Sourcing Management</option>
                <option value="Smart Warehousing System">Smart Warehousing System</option>
                <option value="Project Logistics Tracker">Project Logistics Tracker</option>
                <option value="Asset Lifecycle &amp; Maintenance">Asset Lifecycle &amp; Maintenance</option>
                <option value="Document Tracking &amp; Logistics Record">Document Tracking &amp; Logistics Record</option>
            </select>
        </div>
        <div class="flex items-center gap-2">
            <label class="text-sm text-gray-600 whitespace-nowrap">Performed Action</label>
            <select id="logRecFilterAction" class="select select-bordered select-sm">
                <option value="all">All</option>
                <option value="Upload document">Upload document</option>
                <option value="Download document">Download document</option>
                <option value="Update status">Update status</option>
            </select>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="table table-zebra w-full" id="logRecTable">
            <thead>
                <tr class="bg-gray-700 font-bold text-white">
                    <th>Log ID</th>
                    <th>Module</th>
                    <th>Submodule</th>
                    <th>Created at</th>
                    <th>Performed Action</th>
                    <th>Performed by</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="logRecTableBody">
                <tr id="logRecLoadingRow">
                    <td colspan="7">
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
var JWT_TOKEN = typeof JWT_TOKEN !== 'undefined' ? JWT_TOKEN : localStorage.getItem('jwt');

let allRecordsState = [];
let recordsState = [];
let currentRecordsPage = 1;
const recordsPageSize = 10;
let searchQuery = '';
let filterModule = 'all';
let filterAction = 'all';

const Toast = (typeof Swal !== 'undefined') ? Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 2500,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    }
}) : null;

function toast(message, type = 'info'){
    if(Toast){
        Toast.fire({ icon: type, title: message });
        return;
    }
    try { alert(message); } catch (e) {}
}

function escapeHtml(str){
    return String(str ?? '').replace(/[&<>"']/g, (m) => ({
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#39;',
    }[m]));
}

function formatDateTime(value){
    if(!value) return '';
    const d = new Date(value);
    if(Number.isNaN(d.getTime())) return '';
    const mm = String(d.getMonth() + 1).padStart(2, '0');
    const dd = String(d.getDate()).padStart(2, '0');
    const yy = String(d.getFullYear()).slice(-2);
    let hours = d.getHours();
    const ampm = hours >= 12 ? 'PM' : 'AM';
    hours = hours % 12;
    if(hours === 0) hours = 12;
    const hh = String(hours).padStart(2, '0');
    const min = String(d.getMinutes()).padStart(2, '0');
    const sec = String(d.getSeconds()).padStart(2, '0');
    return `${mm}/${dd}/${yy}, ${hh}:${min}:${sec} ${ampm}`;
}

function titleCase(str){
    const s = String(str ?? '').trim();
    if(!s) return '';
    const parts = s.split(/\s+/g);
    return parts.map(w => {
        const lower = w.toLowerCase();
        return lower.charAt(0).toUpperCase() + lower.slice(1);
    }).join(' ');
}

function normalizeKey(str){
    return String(str ?? '').trim().toLowerCase();
}

function applyFilters(){
    const q = normalizeKey(searchQuery);
    const mod = normalizeKey(filterModule);
    const act = normalizeKey(filterAction);
    const filtered = (Array.isArray(allRecordsState) ? allRecordsState : []).filter(r => {
        const moduleOk = (mod === 'all') || normalizeKey(r?.module) === mod;
        const actionOk = (act === 'all') || normalizeKey(r?.performed_action) === act;
        if(!moduleOk || !actionOk) return false;
        if(!q) return true;
        const hay = [
            r?.log_id,
            r?.doc_id,
            r?.doc_type,
            r?.doc_title,
            r?.doc_status,
            r?.module,
            r?.submodule,
            r?.performed_action,
            r?.performed_by,
            r?.created_at,
        ].map(v => normalizeKey(v)).join(' ');
        return hay.includes(q);
    });
    recordsState = filtered;
    currentRecordsPage = 1;
    renderRecords(recordsState);
}

function renderRecords(rows){
    const body = document.getElementById('logRecTableBody');
    body.innerHTML = '';
    if(!rows || rows.length === 0){
        body.innerHTML = `
            <tr>
                <td colspan="7" class="px-4 py-10">
                    <div class="flex flex-col items-center justify-center text-gray-500">
                        <i class='bx bxs-inbox text-6xl text-gray-300 mb-2'></i>
                        <div class="text-sm">No records found</div>
                    </div>
                </td>
            </tr>
        `;
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
            <td class="whitespace-nowrap font-mono">${escapeHtml(r.log_id || '')}</td>
            <td class="whitespace-nowrap truncate max-w-xs" title="${escapeHtml(r.module || '')}">${escapeHtml(r.module || '')}</td>
            <td class="whitespace-nowrap truncate max-w-xs" title="${escapeHtml(r.submodule || '')}">${escapeHtml(r.submodule || '')}</td>
            <td class="whitespace-nowrap">${escapeHtml(formatDateTime(r.created_at || ''))}</td>
            <td class="whitespace-nowrap truncate max-w-xs" title="${escapeHtml(r.performed_action || '')}">${escapeHtml(r.performed_action || '')}</td>
            <td class="whitespace-nowrap truncate max-w-xs" title="${escapeHtml(titleCase(r.performed_by || ''))}">${escapeHtml(titleCase(r.performed_by || ''))}</td>
            <td class="whitespace-nowrap">
                <div class="flex items-center gap-2">
                    <button class="text-indigo-600 transition-colors p-2 rounded-lg hover:bg-gray-50" data-action="view" data-id="${escapeHtml(r.log_id || '')}" title="View record detail">
                        <i class='bx bx-show-alt text-xl'></i>
                    </button>
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
        const res = await fetch(`${DTLR_API}/logistics-record`, {
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            credentials: 'include'
        });
        const json = await res.json();
        const rows = (json && json.success) ? (json.data || []) : (json.data || []);
        allRecordsState = Array.isArray(rows) ? rows : [];
        applyFilters();
    }catch(e){
        const info = document.getElementById('logRecLoadingInfo');
        if(info) info.textContent = 'Failed to load records';
    }
}

function openDetailModal(record){
    const modal = document.getElementById('logRecDetailModal');
    const content = document.getElementById('logRecDetailContent');
    if(content){
        const createdAt = formatDateTime(record?.created_at || '');
        const fields = [
            { label: 'Log ID', value: record?.log_id || '', mono: true },
            { label: 'Document ID', value: record?.doc_id || '', mono: true },
            { label: 'Document Type', value: record?.doc_type || '' },
            { label: 'Title', value: record?.doc_title || '' },
            { label: 'Status', value: record?.doc_status || '' },
            { label: 'Module', value: record?.module || '' },
            { label: 'Submodule', value: record?.submodule || '' },
            { label: 'Created at', value: createdAt },
            { label: 'Performed Action', value: record?.performed_action || '' },
            { label: 'Performed by', value: titleCase(record?.performed_by || '') },
        ];
        content.innerHTML = fields.map(f => {
            const valueClass = f.mono ? 'font-mono' : 'font-semibold';
            return `
                <div>
                    <div class="text-sm text-gray-500 whitespace-nowrap">${escapeHtml(f.label)}</div>
                    <div class="${valueClass} whitespace-nowrap truncate max-w-full" title="${escapeHtml(f.value)}">${escapeHtml(f.value)}</div>
                </div>
            `;
        }).join('');
    }
    if(modal && typeof modal.showModal === 'function') modal.showModal();
}

document.getElementById('logRecTableBody').addEventListener('click', async function(ev){
    const btn = ev.target.closest('button[data-action]');
    if(!btn) return;
    const action = btn.getAttribute('data-action');
    const logId = btn.getAttribute('data-id') || '';
    const record = allRecordsState.find(x => String(x.log_id) === String(logId));

    if(action === 'view'){
        openDetailModal(record || { log_id: logId });
        return;
    }
});

const logRecSearch = document.getElementById('logRecSearch');
const logRecFilterModule = document.getElementById('logRecFilterModule');
const logRecFilterAction = document.getElementById('logRecFilterAction');

if(logRecSearch){
    logRecSearch.addEventListener('input', function(){
        searchQuery = String(this.value || '');
        applyFilters();
    });
}
if(logRecFilterModule){
    logRecFilterModule.addEventListener('change', function(){
        filterModule = String(this.value || 'all');
        applyFilters();
    });
}
if(logRecFilterAction){
    logRecFilterAction.addEventListener('change', function(){
        filterAction = String(this.value || 'all');
        applyFilters();
    });
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

<dialog id="logRecDetailModal" class="modal">
    <div class="modal-box w-11/12 max-w-3xl p-0">
        <div class="flex items-center justify-between px-5 py-4 border-b">
            <div class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class='bx bx-detail text-xl'></i>
                Record Details
            </div>
            <form method="dialog">
                <button type="submit" class="p-2 rounded-lg hover:bg-gray-100" title="Close">
                    <i class='bx bx-x text-2xl'></i>
                </button>
            </form>
        </div>
        <div id="logRecDetailContent" class="p-5 grid grid-cols-1 md:grid-cols-2 gap-4"></div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button type="submit">close</button>
    </form>
</dialog>
