@extends('layouts.app')

@section('title', 'DTLR Document Tracker')

@section('content')
<div class="module-content bg-white rounded-xl p-6 shadow block">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Document Tracker</h2>
        <button class="btn btn-primary" onclick="openUploadModal()">
            <i class="bx bxs-plus-circle mr-2"></i>Upload Document
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="stat text-primary-content rounded-lg p-4 shadow-lg border-l-4 border-primary">
            <div class="stat-figure text-primary">
                <i class="bx bxs-file text-3xl"></i>
            </div>
            <div class="stat-title text-primary">Total Documents</div>
            <div class="stat-value text-primary" id="totalDocuments">0</div>
        </div>
        <div class="stat text-info-content rounded-lg p-4 shadow-lg border-l-4 border-info">
            <div class="stat-figure text-info">
                <i class="bx bxs-check-circle text-3xl"></i>
            </div>
            <div class="stat-title text-info">Indexed</div>
            <div class="stat-value text-info" id="indexedDocuments">0</div>
        </div>
        <div class="stat text-success-content rounded-lg p-4 shadow-lg border-l-4 border-success">
            <div class="stat-figure text-success">
                <i class="bx bxs-time text-3xl"></i>
            </div>
            <div class="stat-title text-success">Pending Review</div>
            <div class="stat-value text-success" id="pendingDocuments">0</div>
        </div>
        <div class="stat text-warning-content rounded-lg p-4 shadow-lg border-l-4 border-warning">
            <div class="stat-figure text-warning">
                <i class="bx bxs-archive text-3xl"></i>
            </div>
            <div class="stat-title text-warning">Archived</div>
            <div class="stat-value text-warning" id="archivedDocuments">0</div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card bg-base-100 shadow-sm mb-6">
        <div class="card-body">
            <div class="flex flex-col md:flex-row gap-4">
                <label class="input flex flex-1 items-center gap-2 border border-gray-300 rounded-lg px-4 py-2">
                    <svg class="h-[1em] opacity-50" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <g
                        stroke-linejoin="round"
                        stroke-linecap="round"
                        stroke-width="2.5"
                        fill="none"
                        stroke="currentColor"
                        >
                        <circle cx="11" cy="11" r="8"></circle>
                        <path d="m21 21-4.3-4.3"></path>
                        </g>
                    </svg>
                    <input class="w-full" type="search" id="searchInput" placeholder="Search documents..." onkeyup="handleSearch()" />
                </label>
                <div class="flex gap-2">
                    <select id="documentTypeFilter" class="select select-bordered" onchange="handleSearch()">
                        <option value="">All Types</option>
                        <option value="PO">Purchase Order</option>
                        <option value="GRN">Goods Received Note</option>
                        <option value="Invoice">Invoice</option>
                        <option value="Delivery Note">Delivery Note</option>
                    </select>
                    <select id="statusFilter" class="select select-bordered" onchange="handleSearch()">
                        <option value="">All Status</option>
                        <option value="indexed">Indexed</option>
                        <option value="pending">Pending</option>
                        <option value="review">Under Review</option>
                        <option value="archived">Archived</option>
                    </select>
                </div>
                <button class="btn bg-teal-600 text-white hover:bg-teal-700" onclick="openOCRModal()">
                    <i class='bx bx-scan mr-2'></i>OCR Document
                </button>
            </div>
        </div>
    </div>

    <!-- Documents Table -->
    <div class="overflow-x-auto">
        <table class="table table-zebra w-full rounded-lg">
            <thead class="bg-gray-800 text-white">
                <tr>
                    <th>Doc ID</th>
                    <th>Type</th>
                    <th>Linked To</th>
                    <th>Extracted Fields</th>
                    <th>Date Uploaded</th>
                    <th>Uploaded By</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="documentsTableBody">
                <!-- Loading row -->
                <tr id="loadingRow">
                    <td colspan="8" class="text-center py-8">
                        <div class="flex justify-center items-center">
                            <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-black"></div>
                            <span class="ml-2">Loading documents...</span>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination section -->
    <div class="flex justify-between items-center mt-6">
        <div class="text-sm text-gray-700">
            Showing <span id="paginationFrom">0</span> to <span id="paginationTo">0</span> of <span id="paginationTotal">0</span> results
        </div>
        <div class="join" id="paginationContainer">
            <!-- Pagination buttons will be generated here -->
        </div>
    </div>
</div>

<!-- Upload Document Modal -->
<div id="uploadModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box max-w-4xl">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-lg">Upload New Document</h3>
            <button class="btn btn-sm btn-circle" onclick="closeUploadModal()">✕</button>
        </div>
        <form id="uploadForm" enctype="multipart/form-data">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text">Document Type</span>
                        </div>
                        <select class="select select-bordered w-full" name="document_type" required>
                            <option value="">Select Type</option>
                            <option value="PO">Purchase Order</option>
                            <option value="GRN">Goods Received Note</option>
                            <option value="Invoice">Invoice</option>
                            <option value="Delivery Note">Delivery Note</option>
                            <option value="Contract">Contract</option>
                            <option value="Other">Other</option>
                        </select>
                    </label>
                </div>
                <div>
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text">Linked Transaction</span>
                        </div>
                        <input type="text" class="input input-bordered w-full" name="linked_transaction" placeholder="e.g., PO-005, DLY-001">
                    </label>
                </div>
                <div class="md:col-span-2">
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text">Document File</span>
                        </div>
                        <input type="file" class="file-input file-input-bordered w-full" name="document_file" accept=".pdf,.jpg,.jpeg,.png" required>
                    </label>
                </div>
                <div class="md:col-span-2">
                    <label class="form-control">
                        <div class="label">
                            <span class="label-text">Description</span>
                        </div>
                        <textarea class="textarea textarea-bordered h-24" name="description" placeholder="Document description..."></textarea>
                    </label>
                </div>
                <div class="md:col-span-2">
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text">Uploaded By</span>
                        </div>
                        <input type="text" class="input input-bordered w-full" name="uploaded_by" placeholder="Enter your name" required>
                    </label>
                </div>
                <div class="md:col-span-2">
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text">Uploaded To (Department)</span>
                        </div>
                        <select class="select select-bordered w-full" name="uploaded_to" required>
                            <option value="">Select Department</option>
                            <option value="Procurement">Procurement</option>
                            <option value="Logistics">Logistics</option>
                            <option value="Warehousing">Warehousing</option>
                            <option value="Asset Management">Asset Management</option>
                            <option value="Document Tracking">Document Tracking</option>
                        </select>
                    </label>
                </div>
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="closeUploadModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Upload Document</button>
            </div>
        </form>
    </div>
</div>

<!-- OCR Modal -->
<div id="ocrModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box max-w-6xl">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-lg">OCR Document Processing</h3>
            <button class="btn btn-sm btn-circle" onclick="closeOCRModal()">✕</button>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Left Section - File Upload & Preview -->
            <div class="space-y-4">
                <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center">
                    <input type="file" id="ocrFileInput" class="hidden" accept=".pdf,.jpg,.jpeg,.png">
                    <div class="cursor-pointer" onclick="document.getElementById('ocrFileInput').click()">
                        <i class='bx bx-cloud-upload text-4xl text-gray-400 mb-2'></i>
                        <p class="text-gray-600">Click to upload document</p>
                        <p class="text-sm text-gray-500">Supports PDF, JPG, JPEG, PNG</p>
                    </div>
                </div>
                <div id="filePreview" class="hidden">
                    <h4 class="font-semibold mb-2">File Preview</h4>
                    <div class="border rounded-lg p-4 bg-gray-50">
                        <img id="previewImage" class="max-w-full max-h-64 mx-auto hidden">
                        <div id="pdfPreview" class="hidden text-center">
                            <i class='bx bxs-file-pdf text-4xl text-red-500 mb-2'></i>
                            <p class="text-sm" id="pdfFileName"></p>
                        </div>
                    </div>
                </div>
                <button id="processOCRBtn" class="btn btn-primary w-full hidden" onclick="processOCR()">
                    <i class='bx bx-scan mr-2'></i>Process OCR
                </button>
            </div>

            <!-- Right Section - Extracted Text -->
            <div class="space-y-4">
                <h4 class="font-semibold">Extracted Text</h4>
                <div class="border rounded-lg p-4 bg-gray-50 h-64 overflow-y-auto">
                    <pre id="extractedText" class="text-sm whitespace-pre-wrap">No text extracted yet. Upload a document and click Process OCR.</pre>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <input type="text" class="input input-bordered" placeholder="PO Number" id="poNumber">
                    <input type="text" class="input input-bordered" placeholder="Vendor Name" id="vendorName">
                    <input type="text" class="input input-bordered" placeholder="Amount" id="amount">
                    <input type="text" class="input input-bordered" placeholder="Date" id="documentDate">
                </div>
                <div class="md:col-span-2">
                    <label class="form-control w-full">
                        <div class="label">
                            <span class="label-text">Processed By</span>
                        </div>
                        <input type="text" class="input input-bordered w-full" id="processedBy" placeholder="Enter your name" required>
                    </label>
                </div>
                <button class="btn btn-success w-full" onclick="saveOCRExtraction()">
                    <i class='bx bx-save mr-2'></i>Save Extracted Data
                </button>
            </div>
        </div>
        <div class="modal-action">
            <button class="btn btn-ghost" onclick="closeOCRModal()">Close</button>
        </div>
    </div>
</div>

<!-- View Document Modal -->
<div id="viewModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box max-w-4xl">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-lg">Document Details</h3>
            <button class="btn btn-sm btn-circle" onclick="closeViewModal()">✕</button>
        </div>
        <div id="viewModalContent">
            <!-- Content will be loaded here -->
        </div>
        <div class="modal-action">
            <button class="btn btn-ghost" onclick="closeViewModal()">Close</button>
        </div>
    </div>
</div>

<!-- Edit Document Modal -->
<div id="editModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box max-w-4xl">
        <div class="flex justify-between items-center mb-4">
            <h3 class="font-bold text-lg">Edit Document</h3>
            <button class="btn btn-sm btn-circle" onclick="closeEditModal()">✕</button>
        </div>
        <form id="editForm">
            <div id="editModalContent">
                <!-- Content will be loaded here -->
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="closeEditModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Changes</button>
            </div>
        </form>
    </div>
</div>

<script>
// ==================== CONFIGURATION ====================
const API_BASE_URL = 'http://localhost:8001/api/dtlr';
let currentPage = 1;
const itemsPerPage = 10;
let allDocuments = [];
let filteredDocuments = [];
let currentDocumentId = null;

// ==================== INITIALIZATION ====================
document.addEventListener('DOMContentLoaded', function() {
    loadDocuments();
    loadStats();
});

// ==================== STATS FUNCTIONS ====================
async function loadStats() {
    try {
        const response = await fetch(`${API_BASE_URL}/stats/overview`);
        const data = await response.json();
        
        if (data.success) {
            document.getElementById('totalDocuments').textContent = data.data.documents.total_documents || 0;
            document.getElementById('indexedDocuments').textContent = data.data.documents.indexed || 0;
            document.getElementById('pendingDocuments').textContent = data.data.documents.pending || 0;
            document.getElementById('archivedDocuments').textContent = data.data.documents.archived || 0;
        }
    } catch (error) {
        console.error('Error loading stats:', error);
    }
}

// ==================== DOCUMENT FUNCTIONS ====================
async function loadDocuments() {
    showLoading();
    try {
        const searchParams = new URLSearchParams({
            page: currentPage,
            limit: itemsPerPage,
            search: document.getElementById('searchInput').value,
            document_type: document.getElementById('documentTypeFilter').value,
            status: document.getElementById('statusFilter').value
        });

        const response = await fetch(`${API_BASE_URL}/documents?${searchParams}`);
        const data = await response.json();
        
        if (data.success) {
            allDocuments = data.data.documents || [];
            filteredDocuments = allDocuments;
            renderDocuments();
            updatePagination(data.data.total, data.data.current_page, data.data.last_page);
        } else {
            throw new Error(data.message);
        }
    } catch (error) {
        console.error('Error loading documents:', error);
        showError('Failed to load documents: ' + error.message);
    } finally {
        hideLoading();
    }
}

function renderDocuments() {
    const tbody = document.getElementById('documentsTableBody');
    if (!tbody) {
        console.error('documentsTableBody not found');
        return;
    }
    
    tbody.innerHTML = '';

    if (filteredDocuments.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="grid grid-cols-1 text-gray-500 text-center">
                    <i class='bx bx-lg bxs-file-find'></i>
                    No documents found
                </td>
            </tr>
        `;
        return;
    }

    filteredDocuments.forEach(doc => {
        const extractedFields = doc.extracted_fields ? 
            (typeof doc.extracted_fields === 'string' ? doc.extracted_fields : JSON.stringify(doc.extracted_fields)) : 
            'No data extracted';
        
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="font-semibold">${doc.document_id || 'N/A'}</td>
            <td>
                <span class="badge badge-outline">${doc.document_type || 'N/A'}</span>
            </td>
            <td>${doc.linked_transaction || 'N/A'}</td>
            <td class="max-w-xs truncate" title="${extractedFields}">${extractedFields}</td>
            <td>${doc.upload_date || 'N/A'}</td>
            <td>${doc.uploaded_by || 'N/A'}</td>
            <td>
                <span class="badge ${getStatusBadgeClass(doc.status)}">${doc.status || 'N/A'}</span>
            </td>
            <td>
                <div class="flex gap-1">
                    <button class="btn btn-sm bg-blue-400 btn-circle" onclick="viewDocument('${doc.id}')">
                        <i class='bx bx-show-alt'></i>
                    </button>
                    <button class="btn btn-sm bg-yellow-400 btn-circle" onclick="editDocument('${doc.id}')">
                        <i class='bx bx-edit'></i>
                    </button>
                    <button class="btn btn-sm bg-red-400 btn-circle" onclick="deleteDocument('${doc.id}')">
                        <i class='bx bx-trash'></i>
                    </button>
                </div>
            </td>
        `;
        tbody.appendChild(row);
    });
}

function getStatusBadgeClass(status) {
    const classes = {
        'indexed': 'badge-success',
        'pending': 'badge-warning',
        'review': 'badge-info',
        'archived': 'badge-neutral'
    };
    return classes[status] || 'badge-outline';
}

// ==================== SEARCH AND FILTER ====================
function handleSearch() {
    currentPage = 1;
    loadDocuments();
}

// ==================== PAGINATION ====================
function updatePagination(total, current, last) {
    const fromElement = document.getElementById('paginationFrom');
    const toElement = document.getElementById('paginationTo');
    const totalElement = document.getElementById('paginationTotal');
    const container = document.getElementById('paginationContainer');

    if (fromElement) fromElement.textContent = ((current - 1) * itemsPerPage) + 1;
    if (toElement) toElement.textContent = Math.min(current * itemsPerPage, total);
    if (totalElement) totalElement.textContent = total;

    if (!container) return;

    container.innerHTML = '';

    // Previous button
    const prevButton = document.createElement('button');
    prevButton.className = 'join-item btn';
    prevButton.innerHTML = '<i class="bx bxs-chevron-left"></i>';
    prevButton.disabled = current === 1;
    prevButton.onclick = () => changePage(current - 1);
    container.appendChild(prevButton);

    // Page numbers
    const startPage = Math.max(1, current - 2);
    const endPage = Math.min(last, startPage + 4);
    
    for (let i = startPage; i <= endPage; i++) {
        const pageButton = document.createElement('button');
        pageButton.className = `join-item btn ${i === current ? 'btn-active' : ''}`;
        pageButton.textContent = i;
        pageButton.onclick = () => changePage(i);
        container.appendChild(pageButton);
    }

    // Next button
    const nextButton = document.createElement('button');
    nextButton.className = 'join-item btn';
    nextButton.innerHTML = '<i class="bx bxs-chevron-right"></i>';
    nextButton.disabled = current === last;
    nextButton.onclick = () => changePage(current + 1);
    container.appendChild(nextButton);
}

function changePage(page) {
    currentPage = page;
    loadDocuments();
}

// ==================== MODAL FUNCTIONS ====================
function openUploadModal() {
    const modal = document.getElementById('uploadModal');
    if (modal) modal.classList.add('modal-open');
}

function closeUploadModal() {
    const modal = document.getElementById('uploadModal');
    if (modal) modal.classList.remove('modal-open');
    const form = document.getElementById('uploadForm');
    if (form) form.reset();
}

function openOCRModal() {
    const modal = document.getElementById('ocrModal');
    if (modal) modal.classList.add('modal-open');
    
    const fileInput = document.getElementById('ocrFileInput');
    if (fileInput) {
        fileInput.addEventListener('change', handleFileSelect);
    }
}

function closeOCRModal() {
    const modal = document.getElementById('ocrModal');
    if (modal) modal.classList.remove('modal-open');
    resetOCRModal();
}

function closeViewModal() {
    const modal = document.getElementById('viewModal');
    if (modal) modal.classList.remove('modal-open');
}

function closeEditModal() {
    const modal = document.getElementById('editModal');
    if (modal) modal.classList.remove('modal-open');
}

function resetOCRModal() {
    const fileInput = document.getElementById('ocrFileInput');
    const filePreview = document.getElementById('filePreview');
    const processBtn = document.getElementById('processOCRBtn');
    const previewImage = document.getElementById('previewImage');
    const pdfPreview = document.getElementById('pdfPreview');
    const extractedText = document.getElementById('extractedText');
    const poNumber = document.getElementById('poNumber');
    const vendorName = document.getElementById('vendorName');
    const amount = document.getElementById('amount');
    const documentDate = document.getElementById('documentDate');
    const processedBy = document.getElementById('processedBy');

    if (fileInput) fileInput.value = '';
    if (filePreview) filePreview.classList.add('hidden');
    if (processBtn) processBtn.classList.add('hidden');
    if (previewImage) previewImage.classList.add('hidden');
    if (pdfPreview) pdfPreview.classList.add('hidden');
    if (extractedText) extractedText.textContent = 'No text extracted yet. Upload a document and click Process OCR.';
    if (poNumber) poNumber.value = '';
    if (vendorName) vendorName.value = '';
    if (amount) amount.value = '';
    if (documentDate) documentDate.value = '';
    if (processedBy) processedBy.value = '';
    
    currentDocumentId = null;
}

// ==================== FILE HANDLING ====================
function handleFileSelect(event) {
    const file = event.target.files[0];
    if (!file) return;

    const preview = document.getElementById('filePreview');
    const previewImage = document.getElementById('previewImage');
    const pdfPreview = document.getElementById('pdfPreview');
    const processBtn = document.getElementById('processOCRBtn');

    if (preview) preview.classList.remove('hidden');
    if (processBtn) processBtn.classList.remove('hidden');

    if (file.type.startsWith('image/')) {
        if (previewImage) {
            previewImage.classList.remove('hidden');
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImage.src = e.target.result;
            };
            reader.readAsDataURL(file);
        }
        if (pdfPreview) pdfPreview.classList.add('hidden');
    } else if (file.type === 'application/pdf') {
        if (previewImage) previewImage.classList.add('hidden');
        if (pdfPreview) {
            pdfPreview.classList.remove('hidden');
            const pdfFileName = document.getElementById('pdfFileName');
            if (pdfFileName) pdfFileName.textContent = file.name;
        }
    }
}

// ==================== OCR PROCESSING ====================
async function processOCR() {
    const fileInput = document.getElementById('ocrFileInput');
    if (!fileInput) return;
    
    const file = fileInput.files[0];
    const processedBy = document.getElementById('processedBy');
    
    if (!file) {
        showAlert('Please select a file first', 'warning');
        return;
    }

    if (!processedBy || !processedBy.value.trim()) {
        showAlert('Please enter your name in the "Processed By" field', 'warning');
        return;
    }

    showLoadingAlert('Processing OCR...');

    try {
        // First upload the document
        const uploadFormData = new FormData();
        uploadFormData.append('document_type', 'Other');
        uploadFormData.append('document_file', file);
        uploadFormData.append('uploaded_by', processedBy.value.trim());
        uploadFormData.append('uploaded_to', 'Document Tracking');
        uploadFormData.append('description', 'Document uploaded for OCR processing');

        const uploadResponse = await fetch(`${API_BASE_URL}/documents`, {
            method: 'POST',
            body: uploadFormData
        });

        const uploadData = await uploadResponse.json();

        if (uploadData.success) {
            currentDocumentId = uploadData.data.id;
            
            // Then process OCR
            const ocrResponse = await fetch(`${API_BASE_URL}/documents/${currentDocumentId}/process-ocr`, {
                method: 'POST'
            });

            const ocrData = await ocrResponse.json();

            if (ocrData.success) {
                const extractedData = ocrData.data.extracted_data || {};
                const extractedText = document.getElementById('extractedText');
                const poNumber = document.getElementById('poNumber');
                const vendorName = document.getElementById('vendorName');
                const amount = document.getElementById('amount');
                const documentDate = document.getElementById('documentDate');

                if (extractedText) extractedText.textContent = extractedData.raw_text || 'No text extracted';
                if (poNumber) poNumber.value = extractedData.po_number || '';
                if (vendorName) vendorName.value = extractedData.vendor_name || '';
                if (amount) amount.value = extractedData.amount || '';
                if (documentDate) documentDate.value = extractedData.date || '';

                showAlert('OCR processing completed successfully!', 'success');
            } else {
                throw new Error(ocrData.message || 'OCR processing failed');
            }
        } else {
            throw new Error(uploadData.message || 'Document upload failed');
        }
    } catch (error) {
        console.error('OCR processing error:', error);
        showAlert('OCR processing failed: ' + error.message, 'error');
    }
}

async function saveOCRExtraction() {
    if (!currentDocumentId) {
        showAlert('No document processed yet', 'warning');
        return;
    }

    const processedBy = document.getElementById('processedBy');
    if (!processedBy || !processedBy.value.trim()) {
        showAlert('Please enter your name in the "Processed By" field', 'warning');
        return;
    }

    const extractionData = {
        extracted_fields: JSON.stringify({
            po_number: document.getElementById('poNumber')?.value || '',
            vendor_name: document.getElementById('vendorName')?.value || '',
            amount: document.getElementById('amount')?.value || '',
            date: document.getElementById('documentDate')?.value || '',
            processed_by: processedBy.value.trim()
        }),
        status: 'indexed'
    };

    showLoadingAlert('Saving extracted data...');

    try {
        const response = await fetch(`${API_BASE_URL}/documents/${currentDocumentId}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(extractionData)
        });

        const data = await response.json();

        if (data.success) {
            showAlert('Extracted data saved successfully!', 'success');
            closeOCRModal();
            loadDocuments(); // Refresh the list
        } else {
            throw new Error(data.message || 'Failed to save data');
        }
    } catch (error) {
        console.error('Save error:', error);
        showAlert('Failed to save extracted data: ' + error.message, 'error');
    }
}

// ==================== CRUD OPERATIONS ====================
async function viewDocument(id) {
    showLoadingAlert('Loading document details...');
    try {
        const response = await fetch(`${API_BASE_URL}/documents/${id}`);
        const data = await response.json();
        
        if (data.success) {
            const doc = data.data;
            const extractedFields = doc.extracted_fields ? 
                (typeof doc.extracted_fields === 'string' ? doc.extracted_fields : JSON.stringify(doc.extracted_fields, null, 2)) : 
                'No data extracted';
            
            const viewModalContent = document.getElementById('viewModalContent');
            if (viewModalContent) {
                viewModalContent.innerHTML = `
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="font-semibold">Document ID:</label>
                                <p>${doc.document_id || 'N/A'}</p>
                            </div>
                            <div>
                                <label class="font-semibold">Type:</label>
                                <p>${doc.document_type || 'N/A'}</p>
                            </div>
                            <div>
                                <label class="font-semibold">Linked Transaction:</label>
                                <p>${doc.linked_transaction || 'N/A'}</p>
                            </div>
                            <div>
                                <label class="font-semibold">Status:</label>
                                <span class="badge ${getStatusBadgeClass(doc.status)}">${doc.status || 'N/A'}</span>
                            </div>
                        </div>
                        <div>
                            <label class="font-semibold">Extracted Fields:</label>
                            <pre class="bg-gray-100 p-3 rounded mt-1 text-sm max-h-40 overflow-auto">${extractedFields}</pre>
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="font-semibold">Upload Date:</label>
                                <p>${doc.upload_date || 'N/A'}</p>
                            </div>
                            <div>
                                <label class="font-semibold">Uploaded By:</label>
                                <p>${doc.uploaded_by || 'N/A'}</p>
                            </div>
                        </div>
                        <div>
                            <label class="font-semibold">Description:</label>
                            <p class="mt-1 p-3 bg-gray-100 rounded">${doc.description || 'No description'}</p>
                        </div>
                    </div>
                `;
            }
            const viewModal = document.getElementById('viewModal');
            if (viewModal) viewModal.classList.add('modal-open');
        } else {
            throw new Error(data.message || 'Failed to load document');
        }
    } catch (error) {
        console.error('Error loading document:', error);
        showAlert('Failed to load document details: ' + error.message, 'error');
    }
}

async function editDocument(id) {
    showLoadingAlert('Loading document for editing...');
    try {
        const response = await fetch(`${API_BASE_URL}/documents/${id}`);
        const data = await response.json();
        
        if (data.success) {
            const doc = data.data;
            const editModalContent = document.getElementById('editModalContent');
            if (editModalContent) {
                editModalContent.innerHTML = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text">Document ID</span>
                                </div>
                                <input type="text" class="input input-bordered w-full" name="document_id" value="${doc.document_id || ''}" required>
                            </label>
                        </div>
                        <div>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text">Document Type</span>
                                </div>
                                <select class="select select-bordered w-full" name="document_type" required>
                                    <option value="PO" ${doc.document_type === 'PO' ? 'selected' : ''}>Purchase Order</option>
                                    <option value="GRN" ${doc.document_type === 'GRN' ? 'selected' : ''}>Goods Received Note</option>
                                    <option value="Invoice" ${doc.document_type === 'Invoice' ? 'selected' : ''}>Invoice</option>
                                    <option value="Delivery Note" ${doc.document_type === 'Delivery Note' ? 'selected' : ''}>Delivery Note</option>
                                    <option value="Contract" ${doc.document_type === 'Contract' ? 'selected' : ''}>Contract</option>
                                    <option value="Other" ${doc.document_type === 'Other' ? 'selected' : ''}>Other</option>
                                </select>
                            </label>
                        </div>
                        <div>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text">Linked Transaction</span>
                                </div>
                                <input type="text" class="input input-bordered w-full" name="linked_transaction" value="${doc.linked_transaction || ''}">
                            </label>
                        </div>
                        <div>
                            <label class="form-control w-full">
                                <div class="label">
                                    <span class="label-text">Status</span>
                                </div>
                                <select class="select select-bordered w-full" name="status" required>
                                    <option value="pending" ${doc.status === 'pending' ? 'selected' : ''}>Pending</option>
                                    <option value="review" ${doc.status === 'review' ? 'selected' : ''}>Under Review</option>
                                    <option value="indexed" ${doc.status === 'indexed' ? 'selected' : ''}>Indexed</option>
                                    <option value="archived" ${doc.status === 'archived' ? 'selected' : ''}>Archived</option>
                                </select>
                            </label>
                        </div>
                        <div class="md:col-span-2">
                            <label class="form-control">
                                <div class="label">
                                    <span class="label-text">Extracted Fields</span>
                                </div>
                                <textarea class="textarea textarea-bordered h-24" name="extracted_fields">${typeof doc.extracted_fields === 'string' ? doc.extracted_fields : JSON.stringify(doc.extracted_fields || '')}</textarea>
                            </label>
                        </div>
                        <div class="md:col-span-2">
                            <label class="form-control">
                                <div class="label">
                                    <span class="label-text">Description</span>
                                </div>
                                <textarea class="textarea textarea-bordered h-24" name="description">${doc.description || ''}</textarea>
                            </label>
                        </div>
                    </div>
                    <input type="hidden" name="id" value="${id}">
                `;
            }
            
            const editForm = document.getElementById('editForm');
            if (editForm) {
                editForm.onsubmit = function(e) {
                    e.preventDefault();
                    updateDocument(id);
                };
            }
            
            const editModal = document.getElementById('editModal');
            if (editModal) editModal.classList.add('modal-open');
        } else {
            throw new Error(data.message || 'Failed to load document');
        }
    } catch (error) {
        console.error('Error loading document for edit:', error);
        showAlert('Failed to load document for editing: ' + error.message, 'error');
    }
}

async function updateDocument(id) {
    const form = document.getElementById('editForm');
    if (!form) return;

    const formData = new FormData(form);
    const data = Object.fromEntries(formData);

    showLoadingAlert('Updating document...');

    try {
        const response = await fetch(`${API_BASE_URL}/documents/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(data)
        });

        const result = await response.json();

        if (result.success) {
            showAlert('Document updated successfully!', 'success');
            closeEditModal();
            loadDocuments(); // Refresh the list
        } else {
            throw new Error(result.message || 'Failed to update document');
        }
    } catch (error) {
        console.error('Update error:', error);
        showAlert('Failed to update document: ' + error.message, 'error');
    }
}

function deleteDocument(id) {
    Swal.fire({
        title: 'Are you sure?',
        text: "You won't be able to revert this!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Yes, delete it!'
    }).then(async (result) => {
        if (result.isConfirmed) {
            showLoadingAlert('Deleting document...');
            try {
                const response = await fetch(`${API_BASE_URL}/documents/${id}`, {
                    method: 'DELETE'
                });

                const data = await response.json();

                if (data.success) {
                    showAlert('Document deleted successfully!', 'success');
                    loadDocuments(); // Refresh the list
                } else {
                    throw new Error(data.message || 'Failed to delete document');
                }
            } catch (error) {
                console.error('Delete error:', error);
                showAlert('Failed to delete document: ' + error.message, 'error');
            }
        }
    });
}

// ==================== UPLOAD FORM HANDLING ====================
const uploadForm = document.getElementById('uploadForm');
if (uploadForm) {
    uploadForm.onsubmit = async function(e) {
        e.preventDefault();
        
        console.log('Upload form submitted');
        
        // Validate file input
        const fileInput = this.querySelector('input[name="document_file"]');
        if (!fileInput || !fileInput.files[0]) {
            showAlert('Please select a document file to upload', 'warning');
            return;
        }

        console.log('File selected:', fileInput.files[0].name);
        
        const formData = new FormData(this);

        // Log FormData contents for debugging
        for (let [key, value] of formData.entries()) {
            if (key === 'document_file') {
                console.log('FormData:', key, value.name, value.size, value.type);
            } else {
                console.log('FormData:', key, value);
            }
        }

        showLoadingAlert('Uploading document...');

        try {
            const response = await fetch(`${API_BASE_URL}/documents`, {
                method: 'POST',
                body: formData,
                // Don't set Content-Type header - let browser set it with boundary
            });

            console.log('Response status:', response.status);
            
            const data = await response.json();
            console.log('Response data:', data);

            if (data.success) {
                showAlert('Document uploaded successfully!', 'success');
                closeUploadModal();
                loadDocuments(); // Refresh the list
                loadStats(); // Refresh stats
            } else {
                if (data.errors) {
                    // Handle validation errors
                    const errorMessages = Object.values(data.errors).flat().join(', ');
                    console.error('Validation errors:', data.errors);
                    throw new Error('Validation failed: ' + errorMessages);
                } else {
                    throw new Error(data.message || 'Upload failed');
                }
            }
        } catch (error) {
            console.error('Upload error:', error);
            showAlert('Failed to upload document: ' + error.message, 'error');
        }
    };
}

// ==================== UTILITY FUNCTIONS ====================
function showLoading() {
    const loadingRow = document.getElementById('loadingRow');
    if (loadingRow) loadingRow.classList.remove('hidden');
}

function hideLoading() {
    const loadingRow = document.getElementById('loadingRow');
    if (loadingRow) loadingRow.classList.add('hidden');
}

function showAlert(message, type = 'info') {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    });

    Toast.fire({
        icon: type,
        title: message
    });
}

function showLoadingAlert(message) {
    Swal.fire({
        title: message,
        allowOutsideClick: false,
        showConfirmButton: false,
        willOpen: () => {
            Swal.showLoading();
        }
    });
}

function showError(message) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: message,
        confirmButtonText: 'OK'
    });
}
</script>
@endsection