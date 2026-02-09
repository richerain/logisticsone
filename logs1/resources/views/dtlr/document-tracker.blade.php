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
    <div class="flex items-center justify-end gap-4 mb-6">
        <button id="dtlrUploadBtn" type="button" class="btn btn-primary whitespace-nowrap">
            <i class='bx bx-upload text-xl mr-2'></i>
            Upload Document
        </button>
    </div>
    
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6" id="dtlrStats">
        <div class="stat-card bg-blue-50 p-4 rounded-lg text-center">
            <i class='bx bx-file text-3xl text-blue-600 mb-2'></i>
            <h3 class="font-semibold text-blue-800">Total Documents</h3>
            <p class="text-2xl font-bold text-blue-600" id="dtlrStatTotal">0</p>
        </div>
        <div class="stat-card bg-green-50 p-4 rounded-lg text-center">
            <i class='bx bx-check-circle text-3xl text-green-600 mb-2'></i>
            <h3 class="font-semibold text-green-800">Indexed</h3>
            <p class="text-2xl font-bold text-green-600" id="dtlrStatApproved">0</p>
        </div>
        <div class="stat-card bg-yellow-50 p-4 rounded-lg text-center">
            <i class='bx bx-time text-3xl text-yellow-600 mb-2'></i>
            <h3 class="font-semibold text-yellow-800">Pending Review</h3>
            <p class="text-2xl font-bold text-yellow-600" id="dtlrStatPending">0</p>
        </div>
        <div class="stat-card bg-slate-100 p-4 rounded-lg text-center">
            <i class='bx bx-archive text-3xl text-slate-700 mb-2'></i>
            <h3 class="font-semibold text-slate-800">Archived</h3>
            <p class="text-2xl font-bold text-slate-700" id="dtlrStatRejected">0</p>
        </div>
    </div>

    <div class="flex items-center gap-3 mb-6">
        <label class="input input-bordered flex items-center gap-2 flex-1 min-w-0">
            <i class='bx bx-search text-xl text-gray-400'></i>
            <input id="dtlrSearchInput" type="text" class="grow" placeholder="Search documents..." />
        </label>
        <div class="btn-group whitespace-nowrap">
            <button id="dtlrFilterAll" type="button" class="btn btn-sm btn-outline">All</button>
            <button id="dtlrFilterPending" type="button" class="btn btn-sm btn-outline">Pending Review</button>
            <button id="dtlrFilterIndexed" type="button" class="btn btn-sm btn-outline">Indexed</button>
            <button id="dtlrFilterArchived" type="button" class="btn btn-sm btn-outline">Archived</button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="table table-zebra w-full" id="dtlrTable">
            <thead>
                <tr class="bg-gray-700 font-bold text-white">
                    <th class="whitespace-nowrap">Document ID</th>
                    <th class="whitespace-nowrap">Document Type</th>
                    <th class="whitespace-nowrap">Title</th>
                    <th class="whitespace-nowrap">Created Date</th>
                    <th class="whitespace-nowrap">Status</th>
                    <th class="whitespace-nowrap">Actions</th>
                </tr>
            </thead>
            <tbody id="dtlrTableBody">
                <tr id="dtlrLoadingRow">
                    <td colspan="6">
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

<dialog id="dtlrUploadModal" class="modal">
    <div class="modal-box w-11/12 max-w-xl">
        <div class="flex items-center justify-between">
            <div class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class='bx bx-upload text-xl'></i>
                Upload Document
            </div>
            <form method="dialog">
                <button type="submit" class="p-2 rounded-lg hover:bg-gray-100"><i class='bx bx-x text-2xl'></i></button>
            </form>
        </div>

        <form id="dtlrUploadForm" class="mt-4 space-y-4">
            <div>
                <label class="block text-sm text-gray-600 mb-1">Upload File/Document</label>
                <input id="dtlrUploadFile" name="file" type="file" accept=".pdf,.doc,.docx,.xls,.xlsx,application/pdf,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document,application/vnd.ms-excel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet" class="file-input file-input-bordered w-full" required />
                <div class="mt-1 text-xs text-gray-500 whitespace-nowrap">Max file size: 20MB (PDF/DOC/DOCX/XLS/XLSX)</div>
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Document Type</label>
                <select id="dtlrUploadType" name="doc_type" class="select select-bordered w-full" required>
                    <option value="" selected disabled>Select Type</option>
                    <option value="Contract">Contract</option>
                    <option value="Purchase Order">Purchase Order</option>
                    <option value="Invoice">Invoice</option>
                    <option value="Quotation">Quotation</option>
                    <option value="Good Received Note">Good Received Note</option>
                </select>
            </div>

            <div>
                <label class="block text-sm text-gray-600 mb-1">Title</label>
                <input id="dtlrUploadTitle" name="doc_title" type="text" maxlength="255" class="input input-bordered w-full" placeholder="name of the document" required />
            </div>

            <div class="flex items-center justify-end gap-2 pt-2">
                <button type="button" class="btn btn-ghost" id="dtlrUploadCancelBtn">Cancel</button>
                <button type="submit" class="btn btn-primary whitespace-nowrap" id="dtlrUploadSubmitBtn">
                    <i class='bx bx-cloud-upload text-xl mr-2'></i>
                    Upload
                </button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button type="submit">close</button>
    </form>
</dialog>

<dialog id="dtlrDetailModal" class="modal">
    <div class="modal-box w-11/12 max-w-2xl">
        <div class="flex items-center justify-between">
            <div class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class='bx bx-detail text-xl'></i>
                View File Detail
            </div>
            <form method="dialog">
                <button type="submit" class="p-2 rounded-lg hover:bg-gray-100"><i class='bx bx-x text-2xl'></i></button>
            </form>
        </div>

        <div id="dtlrDetailContent" class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4"></div>

        <div class="flex items-center justify-between gap-2 mt-6">
            <div id="dtlrDetailViewNote" class="text-sm text-gray-500 hidden"></div>
            <button type="button" class="btn btn-primary whitespace-nowrap" id="dtlrDetailViewBtn">
                <i class='bx bx-show-alt text-xl mr-2'></i>
                View File
            </button>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button type="submit">close</button>
    </form>
</dialog>

<dialog id="dtlrReviewModal" class="modal">
    <div class="modal-box w-11/12 max-w-2xl">
        <div class="flex items-center justify-between">
            <div class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class='bx bx-task text-xl'></i>
                Review File/Document
            </div>
            <form method="dialog">
                <button type="submit" class="p-2 rounded-lg hover:bg-gray-100"><i class='bx bx-x text-2xl'></i></button>
            </form>
        </div>

        <div id="dtlrReviewContent" class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-4"></div>

        <div class="mt-6 space-y-4">
            <div>
                <label class="block text-sm text-gray-600 mb-1">Update Status</label>
                <select id="dtlrReviewStatus" class="select select-bordered w-full">
                    <option value="indexed">Indexed</option>
                    <option value="archived">Archived</option>
                </select>
            </div>

            <div class="flex items-center justify-between gap-2">
                <div id="dtlrReviewViewNote" class="text-sm text-gray-500 hidden"></div>
                <button type="button" class="btn btn-primary whitespace-nowrap" id="dtlrReviewViewBtn">
                    <i class='bx bx-show-alt text-xl mr-2'></i>
                    View File
                </button>
                <button type="button" class="btn btn-success whitespace-nowrap" id="dtlrReviewSaveBtn">
                    <i class='bx bx-save text-xl mr-2'></i>
                    Save Reviewed
                </button>
            </div>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button type="submit">close</button>
    </form>
</dialog>

<dialog id="dtlrViewModal" class="modal">
    <div class="modal-box w-11/12 max-w-6xl p-0">
        <div class="flex items-center justify-between px-5 py-4 border-b">
            <div class="text-lg font-bold text-gray-800 flex items-center gap-2">
                <i class='bx bx-show-alt text-xl'></i>
                View Document
            </div>
            <form method="dialog">
                <button type="submit" class="p-2 rounded-lg hover:bg-gray-100"><i class='bx bx-x text-2xl'></i></button>
            </form>
        </div>
        <div class="w-full" style="height: 75vh;">
            <iframe id="dtlrViewFrame" class="w-full h-full" src="about:blank"></iframe>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button type="submit">close</button>
    </form>
</dialog>

<script>
var API_BASE_URL = typeof API_BASE_URL !== 'undefined' ? API_BASE_URL : '<?php echo url('/api/v1'); ?>';
var DTLR_API = `${API_BASE_URL}/dtlr`;
var CSRF_TOKEN = typeof CSRF_TOKEN !== 'undefined' ? CSRF_TOKEN : document.querySelector('meta[name="csrf-token"]').getAttribute('content');
var JWT_TOKEN = typeof JWT_TOKEN !== 'undefined' ? JWT_TOKEN : localStorage.getItem('jwt');

let allDocumentsState = [];
let documentsState = [];
let searchQuery = '';
let dtlrFilterStatus = 'all';
let currentDocsPage = 1;
const docsPageSize = 10;

const Toast = (typeof Swal !== 'undefined') ? Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2500, timerProgressBar: true, didOpen: (toast) => { toast.onmouseenter = Swal.stopTimer; toast.onmouseleave = Swal.resumeTimer; } }) : null;

function toast(message, type = 'info') {
    if (Toast) {
        Toast.fire({ icon: type, title: message });
        return;
    }
    try { console.log(message); } catch (e) {}
}

function docStatusBadge(status){
    if(status === 'pending_review') return "<span class='badge badge-warning whitespace-nowrap inline-flex items-center gap-1'><i class='bx bx-time-five'></i>Pending Review</span>";
    if(status === 'indexed') return "<span class='badge badge-info whitespace-nowrap inline-flex items-center gap-1'><i class='bx bx-check-circle'></i>Indexed</span>";
    if(status === 'archived') return "<span class='badge badge-secondary whitespace-nowrap inline-flex items-center gap-1'><i class='bx bx-archive'></i>Archived</span>";
    return "<span class='badge'>N/A</span>";
}

function formatDate(value){
    if(!value) return '';
    try{
        const dt = new Date(value);
        if(!isNaN(dt.getTime())) return dt.toLocaleDateString('en-PH');
        return String(value);
    }catch(e){
        return String(value);
    }
}

function formatBytes(bytes){
    const b = Number(bytes);
    if(!isFinite(b) || b <= 0) return '';
    const units = ['B', 'KB', 'MB', 'GB', 'TB'];
    const i = Math.min(units.length - 1, Math.floor(Math.log(b) / Math.log(1024)));
    const value = b / Math.pow(1024, i);
    const digits = value >= 100 ? 0 : value >= 10 ? 1 : 2;
    return `${value.toFixed(digits)} ${units[i]}`;
}

function fileAvailabilityBadge(doc){
    const available = !!(doc && doc.doc_file_path) && (doc.doc_file_available !== false);
    if(available) return "<span class='badge badge-success whitespace-nowrap inline-flex items-center gap-1'><i class='bx bx-check-circle'></i>Available</span>";
    return "<span class='badge badge-error whitespace-nowrap inline-flex items-center gap-1'><i class='bx bx-x-circle'></i>Not Available</span>";
}

function renderDocuments(docs){
    const body = document.getElementById('dtlrTableBody');
    body.innerHTML = '';
    if(!docs || docs.length === 0){
        body.innerHTML = `
            <tr>
                <td colspan="6" class="px-4 py-8 text-center text-gray-500">
                    <div class="flex flex-col items-center">
                        <i class='bx bx-file-blank text-5xl text-gray-300 mb-2'></i>
                        <div>No documents found</div>
                    </div>
                </td>
            </tr>`;
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
            <td class="whitespace-nowrap font-mono">${d.doc_id || ''}</td>
            <td class="whitespace-nowrap">${d.doc_type || ''}</td>
            <td class="whitespace-nowrap">${d.doc_title || ''}</td>
            <td class="whitespace-nowrap">${formatDate(d.created_at)}</td>
            <td class="whitespace-nowrap">${docStatusBadge(d.doc_status)}</td>
            <td class="whitespace-nowrap">
                <div class="flex items-center gap-2">
                    <button class="text-indigo-600 transition-colors p-2 rounded-lg hover:bg-gray-50" title="View File Detail" data-action="detail" data-id="${d.doc_id || ''}"><i class='bx bx-detail text-xl'></i></button>
                    ${normalizeStr(d.doc_status) === 'pending_review'
                        ? `<button class="text-emerald-600 transition-colors p-2 rounded-lg hover:bg-gray-50" title="Review file/document" data-action="review" data-id="${d.doc_id || ''}"><i class='bx bx-task text-xl'></i></button>`
                        : ''}
                    <button class="text-indigo-600 transition-colors p-2 rounded-lg hover:bg-gray-50" title="Download file" data-action="download" data-id="${d.doc_id || ''}"><i class='bx bx-download text-xl'></i></button>
                    <button class="text-red-600 transition-colors p-2 rounded-lg hover:bg-gray-50" title="Delete file" data-action="delete" data-id="${d.doc_id || ''}"><i class='bx bx-trash text-xl'></i></button>
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
        const res = await fetch(`${DTLR_API}/document-tracker`, { headers: { 'Accept': 'application/json', 'X-Requested-With': 'XMLHttpRequest', 'X-CSRF-TOKEN': CSRF_TOKEN, 'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : '' }, credentials: 'include' });
        const json = await res.json();
        const docs = (json && json.success) ? (json.data || []) : (json.data || []);
        allDocumentsState = Array.isArray(docs) ? docs : [];
        updateStats(allDocumentsState);
        applySearchAndSort();
    }catch(e){
        allDocumentsState = [];
        updateStats([]);
        applySearchAndSort();
    }
}

async function fetchFileBlob(url){
    const res = await fetch(url, {
        method: 'GET',
        headers: {
            'Accept': '*/*',
            'X-Requested-With': 'XMLHttpRequest',
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
        },
        credentials: 'include'
    });
    if(!res.ok) throw new Error(`HTTP ${res.status}`);
    return await res.blob();
}

let currentViewObjectUrl = null;

function canInlinePreview(doc){
    if(!doc) return false;
    const mime = normalizeStr(doc.doc_file_mime);
    if(mime === 'application/pdf') return true;
    const name = normalizeStr(doc.doc_file_original_name);
    if(name.endsWith('.pdf')) return true;
    const path = normalizeStr(doc.doc_file_path);
    if(path.endsWith('.pdf')) return true;
    return false;
}

function openViewModal(urlObj){
    const modal = document.getElementById('dtlrViewModal');
    const frame = document.getElementById('dtlrViewFrame');
    if(frame) frame.src = urlObj || 'about:blank';
    if(modal && typeof modal.showModal === 'function') modal.showModal();
}

function cleanupViewModal(){
    const frame = document.getElementById('dtlrViewFrame');
    if(frame) frame.src = 'about:blank';
    if(currentViewObjectUrl){
        try { window.URL.revokeObjectURL(currentViewObjectUrl); } catch (e) {}
        currentViewObjectUrl = null;
    }
}

document.getElementById('dtlrViewModal').addEventListener('close', cleanupViewModal);

let currentDetailDocId = null;

function openDetailModal(doc){
    currentDetailDocId = doc && doc.doc_id ? doc.doc_id : null;
    const modal = document.getElementById('dtlrDetailModal');
    const content = document.getElementById('dtlrDetailContent');
    const viewBtn = document.getElementById('dtlrDetailViewBtn');
    const viewNote = document.getElementById('dtlrDetailViewNote');
    const hasFile = !!(doc && doc.doc_file_path) && (doc.doc_file_available !== false);
    const canPreview = hasFile && canInlinePreview(doc);
    const showNote = hasFile && !canInlinePreview(doc);
    if(viewBtn){
        viewBtn.disabled = !canPreview;
        viewBtn.classList.toggle('hidden', !canPreview);
    }
    if(viewNote){
        viewNote.textContent = "This file type can't be viewed here. Please use Download to open it in another app.";
        viewNote.classList.toggle('hidden', !showNote);
    }
    if(content){
        const fields = [
            { label: 'Document ID', value: doc && doc.doc_id ? doc.doc_id : '' , mono: true },
            { label: 'Document Type', value: doc && doc.doc_type ? doc.doc_type : '' },
            { label: 'Title', value: doc && doc.doc_title ? doc.doc_title : '' },
            { label: 'Created Date', value: formatDate(doc && doc.created_at ? doc.created_at : '') },
            { label: 'Status', value: docStatusBadge(doc && doc.doc_status ? doc.doc_status : '') , html: true },
            { label: 'File Status', value: fileAvailabilityBadge(doc), html: true },
            { label: 'Original Filename', value: doc && doc.doc_file_original_name ? doc.doc_file_original_name : '' },
            { label: 'MIME Type', value: doc && doc.doc_file_mime ? doc.doc_file_mime : '' },
            { label: 'File Size', value: formatBytes(doc && doc.doc_file_size ? doc.doc_file_size : '') },
        ];
        content.innerHTML = fields.map(f => {
            const valueHtml = f.html ? f.value : (f.value || '');
            const valueClass = f.mono ? 'font-mono' : 'font-semibold';
            return `
                <div>
                    <div class="text-sm text-gray-500 whitespace-nowrap">${f.label}</div>
                    <div class="${valueClass}">${valueHtml}</div>
                </div>
            `;
        }).join('');
    }
    if(modal && typeof modal.showModal === 'function') modal.showModal();
}

document.getElementById('dtlrDetailViewBtn').addEventListener('click', async function(){
    if(!currentDetailDocId) return;
    try{
        const doc = allDocumentsState.find(x => String(x.doc_id) === String(currentDetailDocId));
        if(!canInlinePreview(doc)){
            toast("This file type can't be viewed here. Please use Download to open it in another app.", 'info');
            return;
        }
        const blob = await fetchFileBlob(`${DTLR_API}/document-tracker/${encodeURIComponent(currentDetailDocId)}/view`);
        const urlObj = window.URL.createObjectURL(blob);
        if(currentViewObjectUrl){
            try { window.URL.revokeObjectURL(currentViewObjectUrl); } catch (e) {}
        }
        currentViewObjectUrl = urlObj;
        openViewModal(urlObj);
    }catch(e){
        toast(e && e.message ? e.message : 'Action failed', 'error');
    }
});

let currentReviewDocId = null;
let currentReviewCanView = false;

function openReviewModal(doc){
    currentReviewDocId = doc && doc.doc_id ? doc.doc_id : null;
    const modal = document.getElementById('dtlrReviewModal');
    const content = document.getElementById('dtlrReviewContent');
    const status = document.getElementById('dtlrReviewStatus');
    const viewBtn = document.getElementById('dtlrReviewViewBtn');
    const viewNote = document.getElementById('dtlrReviewViewNote');
    const saveBtn = document.getElementById('dtlrReviewSaveBtn');

    const hasFile = !!(doc && doc.doc_file_path) && (doc.doc_file_available !== false);
    const canPreview = hasFile && canInlinePreview(doc);
    currentReviewCanView = canPreview;
    if(viewBtn) viewBtn.disabled = !currentReviewCanView;
    if(saveBtn) saveBtn.disabled = !currentReviewDocId;
    if(viewBtn) viewBtn.classList.toggle('hidden', !canPreview);
    if(viewNote){
        viewNote.textContent = "This file type can't be viewed here. Please use Download to open it in another app.";
        viewNote.classList.toggle('hidden', !(hasFile && !canInlinePreview(doc)));
    }
    if(status){
        const s = doc && doc.doc_status ? String(doc.doc_status) : '';
        const sn = normalizeStr(s);
        if(sn === 'indexed') status.value = 'indexed';
        else if(sn === 'archived') status.value = 'archived';
        else status.value = 'indexed';
    }

    if(content){
        const fields = [
            { label: 'Document ID', value: doc && doc.doc_id ? doc.doc_id : '' , mono: true },
            { label: 'Document Type', value: doc && doc.doc_type ? doc.doc_type : '' },
            { label: 'Title', value: doc && doc.doc_title ? doc.doc_title : '' },
            { label: 'Created Date', value: formatDate(doc && doc.created_at ? doc.created_at : '') },
            { label: 'Current Status', value: docStatusBadge(doc && doc.doc_status ? doc.doc_status : '') , html: true },
            { label: 'File Status', value: fileAvailabilityBadge(doc), html: true },
            { label: 'Original Filename', value: doc && doc.doc_file_original_name ? doc.doc_file_original_name : '' },
            { label: 'MIME Type', value: doc && doc.doc_file_mime ? doc.doc_file_mime : '' },
            { label: 'File Size', value: formatBytes(doc && doc.doc_file_size ? doc.doc_file_size : '') },
        ];
        content.innerHTML = fields.map(f => {
            const valueHtml = f.html ? f.value : (f.value || '');
            const valueClass = f.mono ? 'font-mono' : 'font-semibold';
            return `
                <div>
                    <div class="text-sm text-gray-500 whitespace-nowrap">${f.label}</div>
                    <div class="${valueClass}">${valueHtml}</div>
                </div>
            `;
        }).join('');
    }

    if(modal && typeof modal.showModal === 'function') modal.showModal();
}

function setReviewSubmitting(isSubmitting){
    const status = document.getElementById('dtlrReviewStatus');
    const viewBtn = document.getElementById('dtlrReviewViewBtn');
    const saveBtn = document.getElementById('dtlrReviewSaveBtn');
    if(status) status.disabled = isSubmitting;
    if(viewBtn) viewBtn.disabled = isSubmitting || !currentReviewCanView;
    if(saveBtn) saveBtn.disabled = isSubmitting || !currentReviewDocId;
}

document.getElementById('dtlrReviewModal').addEventListener('close', function(){
    currentReviewDocId = null;
    currentReviewCanView = false;
});

document.getElementById('dtlrReviewViewBtn').addEventListener('click', async function(){
    if(!currentReviewDocId) return;
    try{
        const doc = allDocumentsState.find(x => String(x.doc_id) === String(currentReviewDocId));
        if(!canInlinePreview(doc)){
            toast("This file type can't be viewed here. Please use Download to open it in another app.", 'info');
            return;
        }
        const blob = await fetchFileBlob(`${DTLR_API}/document-tracker/${encodeURIComponent(currentReviewDocId)}/view`);
        const urlObj = window.URL.createObjectURL(blob);
        if(currentViewObjectUrl){
            try { window.URL.revokeObjectURL(currentViewObjectUrl); } catch (e) {}
        }
        currentViewObjectUrl = urlObj;
        openViewModal(urlObj);
    }catch(e){
        toast(e && e.message ? e.message : 'Action failed', 'error');
    }
});

document.getElementById('dtlrReviewSaveBtn').addEventListener('click', async function(){
    if(!currentReviewDocId) return;
    const statusEl = document.getElementById('dtlrReviewStatus');
    const nextStatus = statusEl ? statusEl.value : '';
    if(!nextStatus){
        toast('Please select a status', 'error');
        return;
    }

    setReviewSubmitting(true);
    try{
        const res = await fetch(`${DTLR_API}/document-tracker/${encodeURIComponent(currentReviewDocId)}/status`, {
            method: 'PATCH',
            headers: {
                'Accept': 'application/json',
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            credentials: 'include',
            body: JSON.stringify({ doc_status: nextStatus })
        });
        const json = await res.json().catch(() => null);
        if(!res.ok || (json && json.success === false)){
            if (json && json.errors && typeof json.errors === 'object') {
                const firstKey = Object.keys(json.errors)[0];
                const firstMsg = firstKey && Array.isArray(json.errors[firstKey]) ? json.errors[firstKey][0] : null;
                throw new Error(firstMsg || json.message || `HTTP ${res.status}`);
            }
            throw new Error((json && json.message) ? json.message : `HTTP ${res.status}`);
        }

        toast('Reviewed successfully', 'success');
        const modal = document.getElementById('dtlrReviewModal');
        if(modal && typeof modal.close === 'function') modal.close();
        await loadDocuments();
    }catch(e){
        toast(e && e.message ? e.message : 'Review failed', 'error');
    }finally{
        setReviewSubmitting(false);
    }
});

document.getElementById('dtlrTableBody').addEventListener('click', async function(ev){
    const btn = ev.target.closest('button[data-action]');
    if(!btn) return;
    const act = btn.getAttribute('data-action');
    const id = btn.getAttribute('data-id');
    if(!id) return;

    try{
        if(act === 'detail'){
            const doc = allDocumentsState.find(x => String(x.doc_id) === String(id));
            if(!doc){
                throw new Error('Document not found');
            }
            openDetailModal(doc);
        }
        if(act === 'review'){
            const doc = allDocumentsState.find(x => String(x.doc_id) === String(id));
            if(!doc){
                throw new Error('Document not found');
            }
            openReviewModal(doc);
        }
        if(act === 'download'){
            if (typeof Swal !== 'undefined') {
                Swal.fire({ title: 'Downloading...', timer: 8000, timerProgressBar: true, allowOutsideClick: false, didOpen: () => { Swal.showLoading(); Swal.stopTimer(); } });
            }
            const doc = allDocumentsState.find(x => String(x.doc_id) === String(id));
            const fromOriginal = doc && doc.doc_file_original_name ? String(doc.doc_file_original_name) : '';
            const fromPath = doc && doc.doc_file_path ? String(doc.doc_file_path).split('.').pop() : '';
            const ext = fromPath && fromPath.length <= 6 ? fromPath : '';
            const downloadName = fromOriginal || `${id}${ext ? `.${ext}` : ''}`;
            const blob = await fetchFileBlob(`${DTLR_API}/document-tracker/${encodeURIComponent(id)}/download`);
            const urlObj = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = urlObj;
            a.download = downloadName;
            document.body.appendChild(a);
            a.click();
            a.remove();
            setTimeout(() => window.URL.revokeObjectURL(urlObj), 30000);
            if (typeof Swal !== 'undefined') {
                Swal.hideLoading();
                Swal.update({ icon: 'success', title: 'Downloaded' });
                Swal.resumeTimer();
            }
            toast('Downloaded successfully', 'success');
        }
        if(act === 'delete'){
            if (typeof Swal !== 'undefined') {
                const confirm = await Swal.fire({ title: 'Delete this document?', text: id, icon: 'warning', showCancelButton: true, confirmButtonText: 'Delete', confirmButtonColor: '#dc2626' });
                if (!confirm.isConfirmed) return;
            }
            const res = await fetch(`${DTLR_API}/document-tracker/${encodeURIComponent(id)}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
                },
                credentials: 'include'
            });
            const json = await res.json().catch(() => null);
            if(!res.ok || (json && json.success === false)){
                throw new Error((json && json.message) ? json.message : `HTTP ${res.status}`);
            }
            toast('Deleted successfully', 'success');
            await loadDocuments();
        }
    }catch(e){
        if (typeof Swal !== 'undefined') {
            Swal.close();
        }
        toast(e && e.message ? e.message : 'Action failed', 'error');
    }
});

function updateStats(docs){
    const totalEl = document.getElementById('dtlrStatTotal');
    const approvedEl = document.getElementById('dtlrStatApproved');
    const pendingEl = document.getElementById('dtlrStatPending');
    const rejectedEl = document.getElementById('dtlrStatRejected');
    const list = Array.isArray(docs) ? docs : [];
    const total = list.length;
    const indexed = list.filter(d => String(d.doc_status).toLowerCase() === 'indexed').length;
    const pending = list.filter(d => String(d.doc_status).toLowerCase() === 'pending_review').length;
    const archived = list.filter(d => String(d.doc_status).toLowerCase() === 'archived').length;
    if(totalEl) totalEl.textContent = String(total);
    if(approvedEl) approvedEl.textContent = String(indexed);
    if(pendingEl) pendingEl.textContent = String(pending);
    if(rejectedEl) rejectedEl.textContent = String(archived);
}

function normalizeStr(value){
    return String(value || '').toLowerCase();
}

function setActiveFilterButton(){
    const btnAll = document.getElementById('dtlrFilterAll');
    const btnPending = document.getElementById('dtlrFilterPending');
    const btnIndexed = document.getElementById('dtlrFilterIndexed');
    const btnArchived = document.getElementById('dtlrFilterArchived');
    const buttons = [btnAll, btnPending, btnIndexed, btnArchived];
    buttons.forEach(function(b){
        if(!b) return;
        b.classList.remove('btn-active');
    });
    if(dtlrFilterStatus === 'all' && btnAll) btnAll.classList.add('btn-active');
    if(dtlrFilterStatus === 'pending_review' && btnPending) btnPending.classList.add('btn-active');
    if(dtlrFilterStatus === 'indexed' && btnIndexed) btnIndexed.classList.add('btn-active');
    if(dtlrFilterStatus === 'archived' && btnArchived) btnArchived.classList.add('btn-active');
}

function applySearchAndSort(){
    const q = normalizeStr(searchQuery).trim();
    let list = allDocumentsState.slice();

    if(q){
        list = list.filter(d => {
            return normalizeStr(d.doc_id).includes(q)
                || normalizeStr(d.doc_type).includes(q)
                || normalizeStr(d.doc_title).includes(q)
                || normalizeStr(d.doc_status).includes(q);
        });
    }

    const status = normalizeStr(dtlrFilterStatus);
    if(status && status !== 'all'){
        list = list.filter(d => normalizeStr(d.doc_status) === status);
    }

    documentsState = list;
    currentDocsPage = 1;
    renderDocuments(documentsState);
}

const dtlrSearchInput = document.getElementById('dtlrSearchInput');
if(dtlrSearchInput){
    dtlrSearchInput.addEventListener('input', function(ev){
        searchQuery = ev.target ? ev.target.value : '';
        applySearchAndSort();
    });
}

const dtlrFilterAll = document.getElementById('dtlrFilterAll');
const dtlrFilterPending = document.getElementById('dtlrFilterPending');
const dtlrFilterIndexed = document.getElementById('dtlrFilterIndexed');
const dtlrFilterArchived = document.getElementById('dtlrFilterArchived');

if(dtlrFilterAll){
    dtlrFilterAll.addEventListener('click', function(){
        dtlrFilterStatus = 'all';
        setActiveFilterButton();
        applySearchAndSort();
    });
}
if(dtlrFilterPending){
    dtlrFilterPending.addEventListener('click', function(){
        dtlrFilterStatus = 'pending_review';
        setActiveFilterButton();
        applySearchAndSort();
    });
}
if(dtlrFilterIndexed){
    dtlrFilterIndexed.addEventListener('click', function(){
        dtlrFilterStatus = 'indexed';
        setActiveFilterButton();
        applySearchAndSort();
    });
}
if(dtlrFilterArchived){
    dtlrFilterArchived.addEventListener('click', function(){
        dtlrFilterStatus = 'archived';
        setActiveFilterButton();
        applySearchAndSort();
    });
}

setActiveFilterButton();

document.getElementById('dtlrPager').addEventListener('click', function(ev){
    const btn = ev.target.closest('button[data-action]');
    if(!btn) return;
    const act = btn.getAttribute('data-action');
    if(act === 'prev'){ currentDocsPage = Math.max(1, currentDocsPage - 1); renderDocuments(documentsState); }
    if(act === 'next'){ const max = Math.max(1, Math.ceil((documentsState.length||0)/docsPageSize)); currentDocsPage = Math.min(max, currentDocsPage + 1); renderDocuments(documentsState); }
});

function setUploadSubmitting(isSubmitting){
    const submit = document.getElementById('dtlrUploadSubmitBtn');
    const cancel = document.getElementById('dtlrUploadCancelBtn');
    const file = document.getElementById('dtlrUploadFile');
    const type = document.getElementById('dtlrUploadType');
    const title = document.getElementById('dtlrUploadTitle');
    if(submit) submit.disabled = isSubmitting;
    if(cancel) cancel.disabled = isSubmitting;
    if(file) file.disabled = isSubmitting;
    if(type) type.disabled = isSubmitting;
    if(title) title.disabled = isSubmitting;
}

function openUploadModal(){
    const modal = document.getElementById('dtlrUploadModal');
    if(modal && typeof modal.showModal === 'function') modal.showModal();
}

function closeUploadModal(){
    const modal = document.getElementById('dtlrUploadModal');
    if(modal && typeof modal.close === 'function') modal.close();
}

document.getElementById('dtlrUploadBtn').addEventListener('click', function(){
    openUploadModal();
});

document.getElementById('dtlrUploadCancelBtn').addEventListener('click', function(){
    closeUploadModal();
});

document.getElementById('dtlrUploadForm').addEventListener('submit', async function(ev){
    ev.preventDefault();
    const form = ev.currentTarget;
    const fileInput = document.getElementById('dtlrUploadFile');
    const type = document.getElementById('dtlrUploadType');
    const title = document.getElementById('dtlrUploadTitle');

    if(!fileInput || !fileInput.files || !fileInput.files[0]){
        toast('Please select a file', 'error');
        return;
    }
    if(!type || !type.value){
        toast('Please select a document type', 'error');
        return;
    }
    if(!title || !title.value.trim()){
        toast('Please enter a title', 'error');
        return;
    }

    setUploadSubmitting(true);
    try{
        const formData = new FormData();
        formData.append('file', fileInput.files[0]);
        formData.append('doc_type', type.value);
        formData.append('doc_title', title.value.trim());
        const res = await fetch(`${DTLR_API}/document-tracker`, {
            method: 'POST',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            body: formData,
            credentials: 'include'
        });

        const json = await res.json().catch(() => null);
        if(!res.ok || (json && json.success === false)){
            if (json && json.errors && typeof json.errors === 'object') {
                const firstKey = Object.keys(json.errors)[0];
                const firstMsg = firstKey && Array.isArray(json.errors[firstKey]) ? json.errors[firstKey][0] : null;
                throw new Error(firstMsg || json.message || `HTTP ${res.status}`);
            }
            throw new Error((json && json.message) ? json.message : `HTTP ${res.status}`);
        }

        toast('Uploaded successfully', 'success');
        form.reset();
        closeUploadModal();
        await loadDocuments();
    }catch(e){
        toast(e && e.message ? e.message : 'Upload failed', 'error');
    }finally{
        setUploadSubmitting(false);
    }
});

if(document.readyState === 'loading'){
    document.addEventListener('DOMContentLoaded', loadDocuments);
}else{
    loadDocuments();
}
</script>
