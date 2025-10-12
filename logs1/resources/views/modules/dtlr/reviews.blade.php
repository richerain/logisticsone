@extends('layouts.app')

@section('title', 'DTLR Document Reviews')

@section('content')
<div class="module-content bg-white rounded-xl p-6 shadow block">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Document Reviews</h2>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="stat bg-primary text-primary-content rounded-lg p-4">
            <div class="stat-figure text-secondary">
                <i class="bx bxs-notepad text-3xl"></i>
            </div>
            <div class="stat-title text-white">Total Reviews</div>
            <div class="stat-value text-white" id="statTotal">0</div>
        </div>
        <div class="stat bg-warning text-warning-content rounded-lg p-4">
            <div class="stat-figure text-secondary">
                <i class="bx bxs-time text-3xl"></i>
            </div>
            <div class="stat-title text-white">Pending</div>
            <div class="stat-value text-white" id="statPending">0</div>
        </div>
        <div class="stat bg-success text-success-content rounded-lg p-4">
            <div class="stat-figure text-secondary">
                <i class="bx bxs-check-circle text-3xl"></i>
            </div>
            <div class="stat-title text-white">Approved</div>
            <div class="stat-value text-white" id="statApproved">0</div>
        </div>
        <div class="stat bg-error text-error-content rounded-lg p-4">
            <div class="stat-figure text-secondary">
                <i class="bx bxs-x-circle text-3xl"></i>
            </div>
            <div class="stat-title text-white">Rejected</div>
            <div class="stat-value text-white" id="statRejected">0</div>
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
        <h3 class="font-bold text-lg mb-4">Document Review</h3>
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
                        <div class="font-medium">${review.document.title}</div>
                        <div class="text-xs text-gray-500 font-mono">${review.document.tracking_number}</div>
                    </div>
                </div>
            </td>
            <td>
                <div class="flex items-center gap-2">
                    <div class="avatar placeholder">
                        <div class="bg-neutral text-neutral-content rounded-full w-8">
                            <span class="text-xs">${review.reviewer.username.charAt(0).toUpperCase()}</span>
                        </div>
                    </div>
                    <span>${review.reviewer.username}</span>
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
async function openReviewModal(reviewId = null) {
    const modal = document.getElementById('reviewModal');
    const form = document.getElementById('reviewForm');
    const documentInfo = document.getElementById('reviewDocumentInfo');
    
    if (reviewId) {
        // Edit existing review
        try {
            showLoading();
            const response = await fetch(`${API_BASE_URL}/document-reviews/${reviewId}`);
            const result = await response.json();
            
            if (result.success) {
                const review = result.data;
                document.getElementById('reviewId').value = review.id;
                
                documentInfo.innerHTML = `
                    <div class="bg-base-200 p-3 rounded">
                        <h4 class="font-semibold mb-2">Document Information</h4>
                        <div class="grid grid-cols-2 gap-2 text-sm">
                            <div>
                                <span class="font-medium">Tracking No:</span>
                                <p class="font-mono">${review.document.tracking_number}</p>
                            </div>
                            <div>
                                <span class="font-medium">Title:</span>
                                <p>${review.document.title}</p>
                            </div>
                            <div>
                                <span class="font-medium">Type:</span>
                                <p>${review.document.document_type.name}</p>
                            </div>
                            <div>
                                <span class="font-medium">Current Status:</span>
                                <p><span class="badge badge-${getDocumentStatusBadgeClass(review.document.status)}">${review.document.status}</span></p>
                            </div>
                        </div>
                    </div>
                `;
                
                form.review_status.value = review.review_status;
                form.comments.value = review.comments || '';
            }
        } catch (error) {
            showToast('error', 'Failed to load review: ' + error.message);
            return;
        } finally {
            hideLoading();
        }
    } else {
        // Create new review - you might want to pre-select a document
        documentInfo.innerHTML = `
            <div class="alert alert-info">
                <i class="bx bx-info-circle"></i>
                <span>Select a document to review from the main table</span>
            </div>
        `;
        form.reset();
        document.getElementById('reviewId').value = '';
    }
    
    modal.showModal();
}

function closeReviewModal() {
    document.getElementById('reviewModal').close();
}

async function viewReviewDetails(reviewId) {
    try {
        showLoading();
        const response = await fetch(`${API_BASE_URL}/document-reviews/${reviewId}`);
        const result = await response.json();
        
        if (result.success) {
            const review = result.data;
            
            // You can implement a detailed view modal here
            showToast('info', `
                Review Details:
                Document: ${review.document.title}
                Status: ${review.review_status}
                Reviewer: ${review.reviewer.username}
                ${review.comments ? `Comments: ${review.comments}` : ''}
            `);
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