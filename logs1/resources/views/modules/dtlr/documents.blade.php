@extends('layouts.app')

@section('title', 'DTLR Document Management')

@section('content')
<div class="module-content bg-white rounded-xl p-6 shadow block">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Document Management</h2>
        <button class="btn btn-primary" onclick="openUploadModal()">
            <i class="bx bx-plus mr-2"></i>Upload Document
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="stat text-primary-content rounded-lg p-4 shadow-lg border-l-4 border-primary">
            <div class="stat-figure text-primary">
                <i class="bx bxs-file text-3xl"></i>
            </div>
            <div class="stat-title text-primary">Total Documents</div>
            <div class="stat-value text-primary" id="statTotal">0</div>
        </div>
        <div class="stat text-info-content rounded-lg p-4 shadow-lg border-l-4 border-info">
            <div class="stat-figure text-info">
                <i class="bx bxs-time text-3xl"></i>
            </div>
            <div class="stat-title text-info">Pending</div>
            <div class="stat-value text-info" id="statPending">0</div>
        </div>
        <div class="stat text-success-content rounded-lg p-4 shadow-lg border-l-4 border-success">
            <div class="stat-figure text-success">
                <i class="bx bxs-check-circle text-3xl"></i>
            </div>
            <div class="stat-title text-success">Approved</div>
            <div class="stat-value text-success" id="statApproved">0</div>
        </div>
        <div class="stat text-warning-content rounded-lg p-4 shadow-lg border-l-4 border-warning">
            <div class="stat-figure text-warning">
                <i class="bx bxs-archive text-3xl"></i>
            </div>
            <div class="stat-title text-warning">Processed</div>
            <div class="stat-value text-warning" id="statProcessed">0</div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card bg-base-100 shadow-sm mb-6">
        <div class="card-body">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" id="searchInput" placeholder="Search by tracking number, title..." class="input input-bordered w-full">
                </div>
                <div class="flex gap-2">
                    <select id="typeFilter" class="select select-bordered">
                        <option value="">All Types</option>
                    </select>
                    <select id="statusFilter" class="select select-bordered">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="processed">Processed</option>
                        <option value="approved">Approved</option>
                        <option value="archived">Archived</option>
                        <option value="rejected">Rejected</option>
                    </select>
                    <button class="btn btn-outline" onclick="resetFilters()">
                        <i class="bx bx-reset"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Documents Table -->
    <div class="overflow-x-auto">
        <table class="table table-zebra w-full">
            <thead>
                <tr>
                    <th>Tracking No.</th>
                    <th>Title</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Current Branch</th>
                    <th>Uploaded By</th>
                    <th>Upload Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="documentsTableBody">
                <!-- Data will be populated by JavaScript -->
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="flex justify-center mt-6">
        <div class="join" id="paginationContainer">
            <!-- Pagination will be populated by JavaScript -->
        </div>
    </div>
</div>

<!-- Upload Modal -->
<div id="uploadModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">Upload New Document</h3>
        <form id="uploadForm" enctype="multipart/form-data">
            @csrf
            <div class="space-y-4">
                <div>
                    <label class="label">
                        <span class="label-text">Document Type</span>
                    </label>
                    <select name="document_type_id" class="select select-bordered w-full" required>
                        <option value="">Select Document Type</option>
                    </select>
                </div>
                <div>
                    <label class="label">
                        <span class="label-text">Title</span>
                    </label>
                    <input type="text" name="title" class="input input-bordered w-full" placeholder="Enter document title" required>
                </div>
                <div>
                    <label class="label">
                        <span class="label-text">Description</span>
                    </label>
                    <textarea name="description" class="textarea textarea-bordered w-full" placeholder="Enter document description"></textarea>
                </div>
                <div>
                    <label class="label">
                        <span class="label-text">Document File</span>
                    </label>
                    <input type="file" name="file" class="file-input file-input-bordered w-full" accept=".pdf,.jpg,.jpeg,.png" required>
                </div>
                <div>
                    <label class="label">
                        <span class="label-text">Current Branch</span>
                    </label>
                    <select name="current_branch_id" class="select select-bordered w-full" required>
                        <option value="">Select Branch</option>
                    </select>
                </div>
                <input type="hidden" name="created_by" value="1"> <!-- This should come from auth -->
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="closeUploadModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Upload Document</button>
            </div>
        </form>
    </div>
</div>

<!-- View Modal -->
<div id="viewModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box max-w-4xl">
        <h3 class="font-bold text-lg mb-4">Document Details</h3>
        <div id="viewModalContent">
            <!-- Content will be populated by JavaScript -->
        </div>
        <div class="modal-action">
            <button class="btn btn-ghost" onclick="closeViewModal()">Close</button>
        </div>
    </div>
</div>

<!-- Transfer Modal -->
<div id="transferModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">Transfer Document</h3>
        <form id="transferForm">
            @csrf
            <input type="hidden" name="document_id" id="transferDocumentId">
            <div class="space-y-4">
                <div>
                    <label class="label">
                        <span class="label-text">To Branch</span>
                    </label>
                    <select name="to_branch_id" class="select select-bordered w-full" required>
                        <option value="">Select Destination Branch</option>
                    </select>
                </div>
                <div>
                    <label class="label">
                        <span class="label-text">Notes</span>
                    </label>
                    <textarea name="notes" class="textarea textarea-bordered w-full" placeholder="Transfer notes..."></textarea>
                </div>
                <input type="hidden" name="performed_by" value="1"> <!-- This should come from auth -->
            </div>
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="closeTransferModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Transfer Document</button>
            </div>
        </form>
    </div>
</div>

<script>
// ==================== CONFIGURATION ====================
const API_BASE_URL = 'http://localhost:8001/api/dtlr';
let currentPage = 1;
let currentFilters = {
    search: '',
    document_type: '',
    status: ''
};

// ==================== INITIALIZATION ====================
document.addEventListener('DOMContentLoaded', function() {
    loadDocuments();
    loadDocumentTypes();
    loadBranches();
    setupEventListeners();
});

function setupEventListeners() {
    // Search with debounce
    let searchTimeout;
    document.getElementById('searchInput').addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            currentFilters.search = e.target.value;
            currentPage = 1;
            loadDocuments();
        }, 500);
    });

    // Filter changes
    document.getElementById('typeFilter').addEventListener('change', function(e) {
        currentFilters.document_type = e.target.value;
        currentPage = 1;
        loadDocuments();
    });

    document.getElementById('statusFilter').addEventListener('change', function(e) {
        currentFilters.status = e.target.value;
        currentPage = 1;
        loadDocuments();
    });
}

// ==================== DATA LOADING ====================
async function loadDocuments() {
    showLoading();
    try {
        const params = new URLSearchParams({
            page: currentPage,
            ...currentFilters
        });

        const response = await fetch(`${API_BASE_URL}/documents?${params}`);
        const result = await response.json();

        if (result.success) {
            updateStats(result.stats);
            populateDocumentsTable(result.data.data);
            setupPagination(result.data);
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        showToast('error', 'Failed to load documents: ' + error.message);
    } finally {
        hideLoading();
    }
}

async function loadDocumentTypes() {
    try {
        const response = await fetch(`${API_BASE_URL}/document-types`);
        const result = await response.json();

        if (result.success) {
            const typeSelect = document.querySelector('select[name="document_type_id"]');
            const typeFilter = document.getElementById('typeFilter');
            
            result.data.forEach(type => {
                typeSelect.innerHTML += `<option value="${type.id}">${type.name}</option>`;
                typeFilter.innerHTML += `<option value="${type.id}">${type.name}</option>`;
            });
        }
    } catch (error) {
        console.error('Failed to load document types:', error);
    }
}

async function loadBranches() {
    try {
        const response = await fetch(`${API_BASE_URL}/branches`);
        const result = await response.json();

        if (result.success) {
            const branchSelect = document.querySelector('select[name="current_branch_id"]');
            const transferBranchSelect = document.querySelector('select[name="to_branch_id"]');
            
            result.data.forEach(branch => {
                branchSelect.innerHTML += `<option value="${branch.id}">${branch.name}</option>`;
                transferBranchSelect.innerHTML += `<option value="${branch.id}">${branch.name}</option>`;
            });
        }
    } catch (error) {
        console.error('Failed to load branches:', error);
    }
}

// ==================== TABLE MANAGEMENT ====================
function populateDocumentsTable(documents) {
    const tbody = document.getElementById('documentsTableBody');
    
    if (documents.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-8 text-gray-500">
                    <i class="bx bxs-file-blank text-4xl mb-2 block"></i>
                    No documents found
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = documents.map(doc => `
        <tr>
            <td class="font-mono">${doc.tracking_number}</td>
            <td>
                <div class="flex items-center gap-2">
                    <i class="bx bxs-file-pdf text-red-500"></i>
                    <span class="font-medium">${doc.title}</span>
                </div>
            </td>
            <td>${doc.document_type.name}</td>
            <td>
                <span class="badge badge-${getStatusBadgeClass(doc.status)}">${doc.status}</span>
            </td>
            <td>${doc.current_branch.name}</td>
            <td>${doc.creator.username}</td>
            <td>${new Date(doc.created_at).toLocaleDateString()}</td>
            <td>
                <div class="flex gap-1">
                    <button class="btn btn-sm btn-circle btn-outline" title="View" onclick="viewDocument(${doc.id})">
                        <i class="bx bx-show"></i>
                    </button>
                    <button class="btn btn-sm btn-circle btn-outline" title="Transfer" onclick="openTransferModal(${doc.id})">
                        <i class="bx bx-transfer"></i>
                    </button>
                    <button class="btn btn-sm btn-circle btn-outline btn-error" title="Delete" onclick="deleteDocument(${doc.id})">
                        <i class="bx bx-trash"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

function getStatusBadgeClass(status) {
    const classes = {
        'pending': 'warning',
        'processed': 'info',
        'approved': 'success',
        'archived': 'neutral',
        'rejected': 'error'
    };
    return classes[status] || 'neutral';
}

// ==================== MODAL MANAGEMENT ====================
function openUploadModal() {
    document.getElementById('uploadModal').showModal();
}

function closeUploadModal() {
    document.getElementById('uploadModal').close();
    document.getElementById('uploadForm').reset();
}

function closeViewModal() {
    document.getElementById('viewModal').close();
}

function openTransferModal(documentId) {
    document.getElementById('transferDocumentId').value = documentId;
    document.getElementById('transferModal').showModal();
}

function closeTransferModal() {
    document.getElementById('transferModal').close();
    document.getElementById('transferForm').reset();
}

// ==================== FORM HANDLING ====================
document.getElementById('uploadForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    
    try {
        showLoading();
        const response = await fetch(`${API_BASE_URL}/documents`, {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast('success', 'Document uploaded successfully!');
            closeUploadModal();
            loadDocuments();
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        showToast('error', 'Upload failed: ' + error.message);
    } finally {
        hideLoading();
    }
});

document.getElementById('transferForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const documentId = formData.get('document_id');
    
    try {
        showLoading();
        const response = await fetch(`${API_BASE_URL}/documents/${documentId}/transfer`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(Object.fromEntries(formData))
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast('success', 'Document transferred successfully!');
            closeTransferModal();
            loadDocuments();
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        showToast('error', 'Transfer failed: ' + error.message);
    } finally {
        hideLoading();
    }
});

// ==================== ACTIONS ====================
async function viewDocument(id) {
    try {
        showLoading();
        const response = await fetch(`${API_BASE_URL}/documents/${id}`);
        const result = await response.json();
        
        if (result.success) {
            const doc = result.data;
            document.getElementById('viewModalContent').innerHTML = `
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="font-semibold">Tracking Number:</label>
                            <p class="font-mono">${doc.tracking_number}</p>
                        </div>
                        <div>
                            <label class="font-semibold">Status:</label>
                            <p><span class="badge badge-${getStatusBadgeClass(doc.status)}">${doc.status}</span></p>
                        </div>
                    </div>
                    <div>
                        <label class="font-semibold">Title:</label>
                        <p>${doc.title}</p>
                    </div>
                    <div>
                        <label class="font-semibold">Description:</label>
                        <p>${doc.description || 'No description'}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="font-semibold">Document Type:</label>
                            <p>${doc.document_type.name}</p>
                        </div>
                        <div>
                            <label class="font-semibold">Current Branch:</label>
                            <p>${doc.current_branch.name}</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="font-semibold">Uploaded By:</label>
                            <p>${doc.creator.username}</p>
                        </div>
                        <div>
                            <label class="font-semibold">Upload Date:</label>
                            <p>${new Date(doc.created_at).toLocaleString()}</p>
                        </div>
                    </div>
                    ${doc.extracted_data ? `
                    <div>
                        <label class="font-semibold">Extracted Data (OCR):</label>
                        <pre class="bg-base-200 p-2 rounded text-sm">${JSON.stringify(doc.extracted_data, null, 2)}</pre>
                    </div>
                    ` : ''}
                </div>
            `;
            document.getElementById('viewModal').showModal();
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        showToast('error', 'Failed to load document details: ' + error.message);
    } finally {
        hideLoading();
    }
}

async function deleteDocument(id) {
    if (!confirm('Are you sure you want to delete this document? This action cannot be undone.')) {
        return;
    }

    try {
        showLoading();
        const response = await fetch(`${API_BASE_URL}/documents/${id}`, {
            method: 'DELETE'
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast('success', 'Document deleted successfully!');
            loadDocuments();
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        showToast('error', 'Delete failed: ' + error.message);
    } finally {
        hideLoading();
    }
}

// ==================== UTILITY FUNCTIONS ====================
function updateStats(stats) {
    document.getElementById('statTotal').textContent = stats.total;
    document.getElementById('statPending').textContent = stats.pending;
    document.getElementById('statApproved').textContent = stats.approved;
    document.getElementById('statProcessed').textContent = stats.processed;
}

function setupPagination(paginationData) {
    const container = document.getElementById('paginationContainer');
    const { current_page, last_page } = paginationData;
    
    let paginationHTML = '';
    
    // Previous button
    paginationHTML += `
        <button class="join-item btn btn-sm ${current_page === 1 ? 'btn-disabled' : ''}" 
                onclick="changePage(${current_page - 1})">
            <i class="bx bx-chevron-left"></i>
        </button>
    `;
    
    // Page numbers
    for (let i = 1; i <= last_page; i++) {
        if (i === 1 || i === last_page || (i >= current_page - 1 && i <= current_page + 1)) {
            paginationHTML += `
                <button class="join-item btn btn-sm ${current_page === i ? 'btn-active' : ''}" 
                        onclick="changePage(${i})">
                    ${i}
                </button>
            `;
        } else if (i === current_page - 2 || i === current_page + 2) {
            paginationHTML += `<button class="join-item btn btn-sm btn-disabled">...</button>`;
        }
    }
    
    // Next button
    paginationHTML += `
        <button class="join-item btn btn-sm ${current_page === last_page ? 'btn-disabled' : ''}" 
                onclick="changePage(${current_page + 1})">
            <i class="bx bx-chevron-right"></i>
        </button>
    `;
    
    container.innerHTML = paginationHTML;
}

function changePage(page) {
    currentPage = page;
    loadDocuments();
}

function resetFilters() {
    currentFilters = { search: '', document_type: '', status: '' };
    document.getElementById('searchInput').value = '';
    document.getElementById('typeFilter').value = '';
    document.getElementById('statusFilter').value = '';
    currentPage = 1;
    loadDocuments();
}

function showLoading() {
    // You can implement a loading spinner here
    document.body.style.cursor = 'wait';
}

function hideLoading() {
    document.body.style.cursor = 'default';
}

function showToast(type, message) {
    const toast = document.createElement('div');
    toast.className = `toast toast-top toast-end`;
    toast.innerHTML = `
        <div class="alert alert-${type}">
            <span>${message}</span>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.remove();
    }, 3000);
}
</script>
@endsection