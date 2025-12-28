<!-- resources/views/dtlr/document-tracker.blade.php -->
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bxs-file'></i>Document Tracker</h2>
    </div>
    <div class="text-right">
        <span class="text-md text-gray-600">Document Tracking & Logistics Record</span>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg p-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="stat-card bg-blue-50 p-4 rounded-lg text-center">
            <i class='bx bx-file text-3xl text-blue-600 mb-2'></i>
            <h3 class="font-semibold text-blue-800">Total Documents</h3>
            <p class="text-2xl font-bold text-blue-600">1,842</p>
        </div>
        <div class="stat-card bg-green-50 p-4 rounded-lg text-center">
            <i class='bx bx-check-circle text-3xl text-green-600 mb-2'></i>
            <h3 class="font-semibold text-green-800">Approved</h3>
            <p class="text-2xl font-bold text-green-600">1,245</p>
        </div>
        <div class="stat-card bg-yellow-50 p-4 rounded-lg text-center">
            <i class='bx bx-time text-3xl text-yellow-600 mb-2'></i>
            <h3 class="font-semibold text-yellow-800">Pending Review</h3>
            <p class="text-2xl font-bold text-yellow-600">387</p>
        </div>
        <div class="stat-card bg-red-50 p-4 rounded-lg text-center">
            <i class='bx bx-error-circle text-3xl text-red-600 mb-2'></i>
            <h3 class="font-semibold text-red-800">Rejected</h3>
            <p class="text-2xl font-bold text-red-600">210</p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="table table-zebra w-full" id="dtlrTable">
            <thead>
                <tr class="bg-gray-700 font-bold text-white">
                    <th>Document ID</th>
                    <th>Document Type</th>
                    <th>Title</th>
                    <th>Owner</th>
                    <th>Created Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="dtlrTableBody">
                <tr id="dtlrLoadingRow">
                    <td colspan="7">
                        <div class="text-center py-8">
                            <div class="loading loading-spinner loading-lg"></div>
                            <p class="mt-2 text-gray-600">Loading Documents...</p>
                            <p class="text-sm text-gray-500" id="dtlrLoadingInfo"></p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="dtlrPager" class="flex items-center justify-between mt-4">
        <div id="dtlrPagerInfo" class="text-sm text-gray-600"></div>
        <div class="join">
            <button class="btn btn-sm join-item" id="dtlrPrevBtn" data-action="prev">Prev</button>
            <span class="btn btn-sm join-item" id="dtlrPageDisplay">1 / 1</span>
            <button class="btn btn-sm join-item" id="dtlrNextBtn" data-action="next">Next</button>
        </div>
    </div>
</div>

<script>
var API_BASE_URL = typeof API_BASE_URL !== 'undefined' ? API_BASE_URL : '<?php echo url('/api/v1'); ?>';
var DTLR_API = `${API_BASE_URL}/dtlr`;
var CSRF_TOKEN = typeof CSRF_TOKEN !== 'undefined' ? CSRF_TOKEN : document.querySelector('meta[name="csrf-token"]').getAttribute('content');

let documentsState = [];
let currentDocsPage = 1;
const docsPageSize = 10;

function docStatusBadge(status){
    if(status === 'approved') return "<span class='badge badge-success'>Approved</span>";
    if(status === 'pending' || status === 'pending_review') return "<span class='badge badge-warning'>Pending Review</span>";
    if(status === 'rejected') return "<span class='badge badge-error'>Rejected</span>";
    if(status === 'archived') return "<span class='badge badge-ghost'>Archived</span>";
    return "<span class='badge'>N/A</span>";
}

function renderDocuments(docs){
    const body = document.getElementById('dtlrTableBody');
    body.innerHTML = '';
    if(!docs || docs.length === 0){
        body.innerHTML = `<tr><td colspan="7" class="px-4 py-3 text-center text-gray-500">No documents found</td></tr>`;
        renderDTLRPager(0, 1);
        return;
    }
    const total = docs.length;
    const totalPages = Math.max(1, Math.ceil(total / docsPageSize));
    if(currentDocsPage > totalPages) currentDocsPage = totalPages;
    if(currentDocsPage < 1) currentDocsPage = 1;
    const start = (currentDocsPage - 1) * docsPageSize;
    const pageItems = docs.slice(start, start + docsPageSize);
    const rows = pageItems.map(d => `
        <tr>
            <td class="whitespace-nowrap">${d.doc_code || d.id || ''}</td>
            <td class="whitespace-nowrap">${d.doc_type || ''}</td>
            <td class="whitespace-nowrap">${d.doc_title || d.title || ''}</td>
            <td class="whitespace-nowrap">${d.doc_owner || d.owner || ''}</td>
            <td class="whitespace-nowrap">${d.created_at || d.created || ''}</td>
            <td class="whitespace-nowrap">${docStatusBadge(d.doc_status || d.status)}</td>
            <td class="whitespace-nowrap">
                <div class="flex items-center gap-2">
                    <button class="text-indigo-600 transition-colors p-2 rounded-lg hover:bg-gray-50" data-action="view" data-id="${d.id || ''}"><i class='bx bx-show-alt text-xl'></i></button>
                    <button class="text-primary transition-colors p-2 rounded-lg hover:bg-gray-50" data-action="track" data-id="${d.id || ''}"><i class='bx bx-compass text-xl'></i></button>
                </div>
            </td>
        </tr>
    `).join('');
    body.innerHTML = rows;
    renderDTLRPager(total, totalPages);
}

function renderDTLRPager(total, pages){
    const info = document.getElementById('dtlrPagerInfo');
    const display = document.getElementById('dtlrPageDisplay');
    const start = total === 0 ? 0 : ((currentDocsPage - 1) * docsPageSize) + 1;
    const end = Math.min(currentDocsPage * docsPageSize, total);
    if(info) info.textContent = `Showing ${start}-${end} of ${total}`;
    if(display) display.textContent = `${currentDocsPage} / ${Math.max(1, pages)}`;
    const prev = document.getElementById('dtlrPrevBtn');
    const next = document.getElementById('dtlrNextBtn');
    if(prev) prev.disabled = currentDocsPage <= 1;
    if(next) next.disabled = currentDocsPage >= pages;
}

async function loadDocuments(){
    try{
        const res = await fetch(`${DTLR_API}/document-tracker`, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF_TOKEN } });
        const json = await res.json();
        const docs = json.data || [];
        documentsState = docs;
        renderDocuments(documentsState);
    }catch(e){
        const info = document.getElementById('dtlrLoadingInfo');
        if(info) info.textContent = 'Failed to load documents';
    }
}

document.getElementById('dtlrPager').addEventListener('click', function(ev){
    const btn = ev.target.closest('button[data-action]');
    if(!btn) return;
    const act = btn.getAttribute('data-action');
    if(act === 'prev'){ currentDocsPage = Math.max(1, currentDocsPage - 1); renderDocuments(documentsState); }
    if(act === 'next'){ const max = Math.max(1, Math.ceil((documentsState.length||0)/docsPageSize)); currentDocsPage = Math.min(max, currentDocsPage + 1); renderDocuments(documentsState); }
});

if(document.readyState === 'loading'){
    document.addEventListener('DOMContentLoaded', loadDocuments);
}else{
    loadDocuments();
}
</script>