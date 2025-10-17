@extends('layouts.app')

@section('title', 'DTLR Document Reviews')

@section('content')
<div class="module-content bg-white rounded-xl p-6 shadow block">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Document Reviews</h2>
        <button class="btn btn-primary" onclick="openReviewModal()">
            <i class="bx bx-plus mr-2"></i>Create Review
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="stat text-primary-content rounded-lg p-4 shadow-lg border-l-4 border-primary">
            <div class="stat-figure text-primary">
                <i class="bx bxs-notepad text-3xl"></i>
            </div>
            <div class="stat-title text-primary">Total Reviews</div>
            <div class="stat-value text-primary" id="statTotal">0</div>
        </div>
        <div class="stat text-warning-content rounded-lg p-4 shadow-lg border-l-4 border-warning">
            <div class="stat-figure text-warning">
                <i class="bx bxs-time text-3xl"></i>
            </div>
            <div class="stat-title text-warning">Pending</div>
            <div class="stat-value text-warning" id="statPending">0</div>
        </div>
        <div class="stat text-success-content rounded-lg p-4 shadow-lg border-l-4 border-success">
            <div class="stat-figure text-success">
                <i class="bx bxs-check-circle text-3xl"></i>
            </div>
            <div class="stat-title text-success">Approved</div>
            <div class="stat-value text-success" id="statApproved">0</div>
        </div>
        <div class="stat text-error-content rounded-lg p-4 shadow-lg border-l-4 border-error">
            <div class="stat-figure text-error">
                <i class="bx bxs-x-circle text-3xl"></i>
            </div>
            <div class="stat-title text-error">Rejected</div>
            <div class="stat-value text-error" id="statRejected">0</div>
        </div>
    </div>

    <!-- Search and Filters -->
    <div class="card bg-base-100 shadow-sm mb-6">
        <div class="card-body">
            <div class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <input type="text" id="searchInput" placeholder="Search by tracking number, document title, or comments..." class="input input-bordered w-full">
                </div>
                <div class="flex gap-2">
                    <select id="statusFilter" class="select select-bordered">
                        <option value="">All Status</option>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                    <button class="btn btn-outline" onclick="resetFilters()">
                        <i class="bx bx-reset"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Reviews Table -->
    <div class="overflow-x-auto">
        <table class="table table-zebra w-full">
            <thead>
                <tr>
                    <th>Review ID</th>
                    <th>Document</th>
                    <th>Reviewer</th>
                    <th>Status</th>
                    <th>Comments</th>
                    <th>Review Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="reviewsTableBody">
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

<!-- Review Modal -->
<div id="reviewModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box max-w-2xl">
        <h3 class="font-bold text-lg mb-4" id="reviewModalTitle">Create Document Review</h3>
        <form id="reviewForm">
            @csrf
            <input type="hidden" name="review_id" id="reviewId">
            <input type="hidden" name="reviewer_id" value="1"> <!-- This should come from auth -->
            
            <div class="space-y-4">
                <div id="reviewDocumentInfo">
                    <!-- Document info will be populated by JavaScript -->
                </div>
                
                <div>
                    <label class="label">
                        <span class="label-text">Document *</span>
                    </label>
                    <select name="document_id" id="documentSelect" class="select select-bordered w-full" required>
                        <option value="">Select Document</option>
                    </select>
                </div>
                
                <div>
                    <label class="label">
                        <span class="label-text">Review Status *</span>
                    </label>
                    <select name="review_status" class="select select-bordered w-full" required>
                        <option value="pending">Pending</option>
                        <option value="approved">Approved</option>
                        <option value="rejected">Rejected</option>
                    </select>
                </div>
                
                <div>
                    <label class="label">
                        <span class="label-text">Comments</span>
                    </label>
                    <textarea name="comments" class="textarea textarea-bordered w-full" placeholder="Enter review comments..." rows="4"></textarea>
                </div>
            </div>
            
            <div class="modal-action">
                <button type="button" class="btn btn-ghost" onclick="closeReviewModal()">Cancel</button>
                <button type="submit" class="btn btn-primary">Submit Review</button>
            </div>
        </form>
    </div>
</div>

<!-- Review Details Modal -->
<div id="reviewDetailsModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box max-w-2xl">
        <h3 class="font-bold text-lg mb-4">Review Details</h3>
        <div id="reviewDetailsContent">
            <!-- Content will be populated by JavaScript -->
        </div>
        <div class="modal-action">
            <button class="btn btn-ghost" onclick="closeReviewDetailsModal()">Close</button>
        </div>
    </div>
</div>

<script>
// ==================== CONFIGURATION ====================
const API_BASE_URL = 'http://localhost:8001/api/dtlr';
let currentPage = 1;
let currentFilters = {
    search: '',
    review_status: ''
};

// ==================== INITIALIZATION ====================
document.addEventListener('DOMContentLoaded', function() {
    loadDocumentReviews();
    loadDocumentsForReview();
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
            loadDocumentReviews();
        }, 500);
    });

    // Filter changes
    document.getElementById('statusFilter').addEventListener('change', function(e) {
        currentFilters.review_status = e.target.value;
        currentPage = 1;
        loadDocumentReviews();
    });

    // Document selection change
    document.getElementById('documentSelect').addEventListener('change', function(e) {
        if (e.target.value) {
            loadDocumentInfo(e.target.value);
        }
    });
}

// ==================== DATA LOADING ====================
async function loadDocumentReviews() {
    showLoading();
    try {
        const params = new URLSearchParams({
            page: currentPage,
            ...currentFilters
        });

        const response = await fetch(`${API_BASE_URL}/document-reviews?${params}`);
        const result = await response.json();

        if (result.success) {
            updateStats(result.stats);
            populateReviewsTable(result.data.data);
            setupPagination(result.data);
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        showToast('error', 'Failed to load document reviews: ' + error.message);
    } finally {
        hideLoading();
    }
}

async function loadDocumentsForReview() {
    try {
        const response = await fetch(`${API_BASE_URL}/documents?per_page=100`);
        const result = await response.json();

        if (result.success) {
            const documentSelect = document.getElementById('documentSelect');
            documentSelect.innerHTML = '<option value="">Select Document</option>';
            
            result.data.data.forEach(doc => {
                documentSelect.innerHTML += `<option value="${doc.id}">${doc.tracking_number} - ${doc.title}</option>`;
            });
        }
    } catch (error) {
        console.error('Failed to load documents:', error);
        showToast('error', 'Failed to load documents');
    }
}

async function loadDocumentInfo(documentId) {
    try {
        const response = await fetch(`${API_BASE_URL}/documents/${documentId}`);
        const result = await response.json();
        
        if (result.success) {
            const doc = result.data;
            document.getElementById('reviewDocumentInfo').innerHTML = `
                <div class="bg-base-200 p-3 rounded">
                    <h4 class="font-semibold mb-2">Document Information</h4>
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div>
                            <span class="font-medium">Tracking No:</span>
                            <p class="font-mono">${doc.tracking_number}</p>
                        </div>
                        <div>
                            <span class="font-medium">Title:</span>
                            <p>${doc.title}</p>
                        </div>
                        <div>
                            <span class="font-medium">Type:</span>
                            <p>${doc.document_type?.name || 'N/A'}</p>
                        </div>
                        <div>
                            <span class="font-medium">Current Status:</span>
                            <p><span class="badge badge-${getDocumentStatusBadgeClass(doc.status)}">${doc.status}</span></p>
                        </div>
                    </div>
                </div>
            `;
        }
    } catch (error) {
        console.error('Failed to load document info:', error);
    }
}

// ==================== TABLE MANAGEMENT ====================
function populateReviewsTable(reviews) {
    const tbody = document.getElementById('reviewsTableBody');
    
    if (reviews.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center py-8 text-gray-500">
                    <i class="bx bxs-check-shield text-4xl mb-2 block"></i>
                    No document reviews found
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = reviews.map(review => `
        <tr>
            <td class="font-mono">REV${String(review.id).padStart(5, '0')}</td>
            <td>
                <div class="flex items-center gap-2">
                    <i class="bx bxs-file text-primary"></i>
                    <div>
                        <div class="font-medium">${review.document?.title || 'N/A'}</div>
                        <div class="text-xs text-gray-500 font-mono">${review.document?.tracking_number || 'N/A'}</div>
                    </div>
                </div>
            </td>
            <td>
                <div class="flex items-center gap-2">
                    <div class="avatar placeholder">
                        <div class="bg-neutral text-neutral-content rounded-full w-8">
                            <span class="text-xs">${review.reviewer?.username?.charAt(0).toUpperCase() || 'U'}</span>
                        </div>
                    </div>
                    <span>${review.reviewer?.username || 'Unknown'}</span>
                </div>
            </td>
            <td>
                <span class="badge badge-${getReviewStatusBadgeClass(review.review_status)}">
                    ${review.review_status.toUpperCase()}
                </span>
            </td>
            <td>
                <div class="max-w-xs truncate" title="${review.comments || 'No comments'}">
                    ${review.comments || '<span class="text-gray-400">No comments</span>'}
                </div>
            </td>
            <td>
                ${review.reviewed_at ? `
                    <div class="text-sm">
                        <div>${new Date(review.reviewed_at).toLocaleDateString()}</div>
                        <div class="text-gray-500">${new Date(review.reviewed_at).toLocaleTimeString()}</div>
                    </div>
                ` : '<span class="text-gray-400">Not reviewed</span>'}
            </td>
            <td>
                <div class="flex gap-1">
                    <button class="btn btn-sm btn-circle btn-outline" title="Edit Review" onclick="openReviewModal(${review.id})">
                        <i class="bx bx-edit"></i>
                    </button>
                    <button class="btn btn-sm btn-circle btn-outline" title="View Details" onclick="viewReviewDetails(${review.id})">
                        <i class="bx bx-show"></i>
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

function getReviewStatusBadgeClass(status) {
    const classes = {
        'pending': 'warning',
        'approved': 'success',
        'rejected': 'error'
    };
    return classes[status] || 'neutral';
}

// ==================== MODAL MANAGEMENT ====================
function openReviewModal(reviewId = null) {
    const modal = document.getElementById('reviewModal');
    const form = document.getElementById('reviewForm');
    const title = document.getElementById('reviewModalTitle');
    
    if (reviewId) {
        // Edit existing review
        title.textContent = 'Edit Document Review';
        loadReviewForEdit(reviewId);
    } else {
        // Create new review
        title.textContent = 'Create Document Review';
        form.reset();
        document.getElementById('reviewId').value = '';
        document.getElementById('reviewDocumentInfo').innerHTML = `
            <div class="alert alert-info">
                <i class="bx bx-info-circle"></i>
                <span>Select a document to review</span>
            </div>
        `;
    }
    
    modal.showModal();
}

function closeReviewModal() {
    document.getElementById('reviewModal').close();
}

function closeReviewDetailsModal() {
    document.getElementById('reviewDetailsModal').close();
}

async function loadReviewForEdit(reviewId) {
    try {
        showLoading();
        const response = await fetch(`${API_BASE_URL}/document-reviews/${reviewId}`);
        const result = await response.json();
        
        if (result.success) {
            const review = result.data;
            document.getElementById('reviewId').value = review.id;
            document.getElementById('documentSelect').value = review.document_id;
            document.querySelector('select[name="review_status"]').value = review.review_status;
            document.querySelector('textarea[name="comments"]').value = review.comments || '';
            
            // Load document info
            loadDocumentInfo(review.document_id);
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        showToast('error', 'Failed to load review: ' + error.message);
    } finally {
        hideLoading();
    }
}

async function viewReviewDetails(reviewId) {
    try {
        showLoading();
        const response = await fetch(`${API_BASE_URL}/document-reviews/${reviewId}`);
        const result = await response.json();
        
        if (result.success) {
            const review = result.data;
            document.getElementById('reviewDetailsContent').innerHTML = `
                <div class="space-y-4">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="font-semibold">Review ID:</label>
                            <p class="font-mono">REV${String(review.id).padStart(5, '0')}</p>
                        </div>
                        <div>
                            <label class="font-semibold">Status:</label>
                            <p><span class="badge badge-${getReviewStatusBadgeClass(review.review_status)}">${review.review_status.toUpperCase()}</span></p>
                        </div>
                    </div>
                    
                    <div class="border-t pt-4">
                        <label class="font-semibold">Document Information:</label>
                        <div class="bg-base-200 p-3 rounded mt-2">
                            <div class="grid grid-cols-2 gap-2 text-sm">
                                <div>
                                    <span class="font-medium">Tracking No:</span>
                                    <p class="font-mono">${review.document?.tracking_number || 'N/A'}</p>
                                </div>
                                <div>
                                    <span class="font-medium">Title:</span>
                                    <p>${review.document?.title || 'N/A'}</p>
                                </div>
                                <div>
                                    <span class="font-medium">Type:</span>
                                    <p>${review.document?.document_type?.name || 'N/A'}</p>
                                </div>
                                <div>
                                    <span class="font-medium">Current Status:</span>
                                    <p><span class="badge badge-${getDocumentStatusBadgeClass(review.document?.status)}">${review.document?.status || 'N/A'}</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="font-semibold">Reviewer:</label>
                            <div class="flex items-center gap-2 mt-1">
                                <div class="avatar placeholder">
                                    <div class="bg-neutral text-neutral-content rounded-full w-8">
                                        <span class="text-xs">${review.reviewer?.username?.charAt(0).toUpperCase() || 'U'}</span>
                                    </div>
                                </div>
                                <div>
                                    <p>${review.reviewer?.username || 'Unknown'}</p>
                                    <p class="text-xs text-gray-500">${review.reviewer?.role || 'N/A'}</p>
                                </div>
                            </div>
                        </div>
                        <div>
                            <label class="font-semibold">Review Date:</label>
                            <p>${review.reviewed_at ? new Date(review.reviewed_at).toLocaleString() : 'Not reviewed'}</p>
                        </div>
                    </div>
                    
                    ${review.comments ? `
                    <div>
                        <label class="font-semibold">Comments:</label>
                        <p class="bg-base-200 p-3 rounded mt-1">${review.comments}</p>
                    </div>
                    ` : ''}
                </div>
            `;
            document.getElementById('reviewDetailsModal').showModal();
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        showToast('error', 'Failed to load review details: ' + error.message);
    } finally {
        hideLoading();
    }
}

// ==================== FORM HANDLING ====================
document.getElementById('reviewForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const reviewId = formData.get('review_id');
    const url = reviewId ? 
        `${API_BASE_URL}/document-reviews/${reviewId}` : 
        `${API_BASE_URL}/document-reviews`;
    
    const method = reviewId ? 'PUT' : 'POST';
    
    try {
        showLoading();
        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(Object.fromEntries(formData))
        });
        
        const result = await response.json();
        
        if (result.success) {
            showToast('success', reviewId ? 'Review updated successfully!' : 'Review submitted successfully!');
            closeReviewModal();
            loadDocumentReviews();
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        showToast('error', 'Operation failed: ' + error.message);
    } finally {
        hideLoading();
    }
});

// ==================== UTILITY FUNCTIONS ====================
function updateStats(stats) {
    document.getElementById('statTotal').textContent = stats.total;
    document.getElementById('statPending').textContent = stats.pending;
    document.getElementById('statApproved').textContent = stats.approved;
    document.getElementById('statRejected').textContent = stats.rejected;
}

function getDocumentStatusBadgeClass(status) {
    const classes = {
        'pending': 'warning',
        'processed': 'info',
        'approved': 'success',
        'archived': 'neutral',
        'rejected': 'error'
    };
    return classes[status] || 'neutral';
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
    loadDocumentReviews();
}

function resetFilters() {
    currentFilters = { search: '', review_status: '' };
    document.getElementById('searchInput').value = '';
    document.getElementById('statusFilter').value = '';
    currentPage = 1;
    loadDocumentReviews();
}

function showLoading() {
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
    }, 5000);
}
</script>
@endsection