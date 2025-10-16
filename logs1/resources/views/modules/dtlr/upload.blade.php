@extends('layouts.app')

@section('title', 'DTLR Document Upload')

@section('content')
<div class="module-content bg-white rounded-xl p-6 shadow block">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold">Document Upload & Digitization</h2>
    </div>

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="stat text-primary-content rounded-lg p-4 shadow-lg border-l-4 border-primary">
            <div class="stat-figure text-primary">
                <i class="bx bxs-cloud-upload text-3xl"></i>
            </div>
            <div class="stat-title text-primary">Today's Uploads</div>
            <div class="stat-value text-primary" id="statToday">0</div>
        </div>
        <div class="stat text-info-content rounded-lg p-4 shadow-lg border-l-4 border-info">
            <div class="stat-figure text-info">
                <i class="bx bxs-file-find text-3xl"></i>
            </div>
            <div class="stat-title text-info">OCR Processed</div>
            <div class="stat-value text-info" id="statOcr">0</div>
        </div>
        <div class="stat text-success-content rounded-lg p-4 shadow-lg border-l-4 border-success">
            <div class="stat-figure text-success">
                <i class="bx bxs-check-circle text-3xl"></i>
            </div>
            <div class="stat-title text-success">Success Rate</div>
            <div class="stat-value text-success" id="statSuccess">100%</div>
        </div>
    </div>

    <!-- Upload Form -->
    <div class="card bg-base-100 shadow-sm mb-6">
        <div class="card-body">
            <h3 class="card-title mb-4">Upload New Document</h3>
            <form id="uploadForm" enctype="multipart/form-data" class="space-y-4">
                @csrf
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="label">
                            <span class="label-text">Document Type *</span>
                        </label>
                        <select name="document_type_id" class="select select-bordered w-full" required>
                            <option value="">Select Document Type</option>
                        </select>
                    </div>
                    <div>
                        <label class="label">
                            <span class="label-text">Current Branch *</span>
                        </label>
                        <select name="current_branch_id" class="select select-bordered w-full" required>
                            <option value="">Select Branch</option>
                        </select>
                    </div>
                </div>
                
                <div>
                    <label class="label">
                        <span class="label-text">Document Title *</span>
                    </label>
                    <input type="text" name="title" class="input input-bordered w-full" placeholder="Enter document title" required>
                </div>
                
                <div>
                    <label class="label">
                        <span class="label-text">Description</span>
                    </label>
                    <textarea name="description" class="textarea textarea-bordered w-full" placeholder="Enter document description (optional)"></textarea>
                </div>
                
                <div>
                    <label class="label">
                        <span class="label-text">Document File *</span>
                    </label>
                    <input type="file" name="file" class="file-input file-input-bordered w-full" 
                           accept=".pdf,.jpg,.jpeg,.png" required>
                    <div class="label">
                        <span class="label-text-alt">Supported formats: PDF, JPG, JPEG, PNG (Max: 10MB)</span>
                    </div>
                </div>

                <div class="form-control">
                    <label class="label cursor-pointer justify-start gap-2">
                        <input type="checkbox" name="enable_ocr" class="checkbox" checked>
                        <span class="label-text">Enable OCR for automated data extraction</span>
                    </label>
                </div>

                <input type="hidden" name="created_by" value="1"> <!-- This should come from auth -->

                <div class="flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-upload mr-2"></i>Upload & Process
                    </button>
                    <button type="reset" class="btn btn-ghost">Clear Form</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Recent Uploads -->
    <div class="card bg-base-100 shadow-sm">
        <div class="card-body">
            <h3 class="card-title mb-4">Recent Uploads</h3>
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th>Tracking No.</th>
                            <th>Title</th>
                            <th>Type</th>
                            <th>Status</th>
                            <th>Upload Time</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="recentUploadsTable">
                        <!-- Data will be populated by JavaScript -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Processing Modal -->
<div id="processingModal" class="modal modal-bottom sm:modal-middle">
    <div class="modal-box">
        <h3 class="font-bold text-lg mb-4">Processing Document</h3>
        <div class="text-center">
            <div class="loading loading-spinner loading-lg text-primary mb-4"></div>
            <p>Please wait while we process your document...</p>
            <p class="text-sm text-gray-500 mt-2" id="processingStep">Uploading file</p>
        </div>
    </div>
</div>

<script>
// ==================== CONFIGURATION ====================
const API_BASE_URL = 'http://localhost:8001/api/dtlr';

// ==================== INITIALIZATION ====================
document.addEventListener('DOMContentLoaded', function() {
    loadDocumentTypes();
    loadBranches();
    loadRecentUploads();
    setupEventListeners();
});

function setupEventListeners() {
    // File input preview
    document.querySelector('input[name="file"]').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            updateFilePreview(file);
        }
    });
}

// ==================== DATA LOADING ====================
async function loadDocumentTypes() {
    try {
        const response = await fetch(`${API_BASE_URL}/document-types`);
        const result = await response.json();

        if (result.success) {
            const typeSelect = document.querySelector('select[name="document_type_id"]');
            
            result.data.forEach(type => {
                typeSelect.innerHTML += `<option value="${type.id}">${type.name}</option>`;
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
            
            result.data.forEach(branch => {
                branchSelect.innerHTML += `<option value="${branch.id}">${branch.name}</option>`;
            });
        }
    } catch (error) {
        console.error('Failed to load branches:', error);
    }
}

async function loadRecentUploads() {
    try {
        const response = await fetch(`${API_BASE_URL}/documents?page=1&per_page=5`);
        const result = await response.json();

        if (result.success) {
            populateRecentUploads(result.data.data);
            updateStats(result.data.data);
        }
    } catch (error) {
        console.error('Failed to load recent uploads:', error);
    }
}

// ==================== FORM HANDLING ====================
document.getElementById('uploadForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    
    const formData = new FormData(this);
    const fileInput = document.querySelector('input[name="file"]');
    
    if (!fileInput.files[0]) {
        showToast('error', 'Please select a file to upload');
        return;
    }

    await uploadDocument(formData);
});

async function uploadDocument(formData) {
    const processingModal = document.getElementById('processingModal');
    const processingStep = document.getElementById('processingStep');
    
    try {
        processingModal.showModal();
        processingStep.textContent = 'Uploading file...';

        // Simulate processing steps for better UX
        await new Promise(resolve => setTimeout(resolve, 1000));
        processingStep.textContent = 'Validating document...';
        
        await new Promise(resolve => setTimeout(resolve, 500));
        processingStep.textContent = 'Processing with OCR...';

        const response = await fetch(`${API_BASE_URL}/documents`, {
            method: 'POST',
            body: formData
        });
        
        const result = await response.json();
        
        processingModal.close();
        
        if (result.success) {
            showToast('success', 'Document uploaded and processed successfully!');
            document.getElementById('uploadForm').reset();
            loadRecentUploads();
            
            // Show success details
            showUploadSuccess(result.data);
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        processingModal.close();
        showToast('error', 'Upload failed: ' + error.message);
    }
}

// ==================== UI UPDATES ====================
function populateRecentUploads(documents) {
    const tbody = document.getElementById('recentUploadsTable');
    
    if (documents.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="6" class="text-center py-8 text-gray-500">
                    <i class="bx bxs-cloud-upload text-4xl mb-2 block"></i>
                    No recent uploads
                </td>
            </tr>
        `;
        return;
    }

    tbody.innerHTML = documents.map(doc => `
        <tr>
            <td class="font-mono text-sm">${doc.tracking_number}</td>
            <td>
                <div class="flex items-center gap-2">
                    <i class="bx ${getFileIcon(doc.file_path)} text-primary"></i>
                    <span class="font-medium truncate max-w-xs">${doc.title}</span>
                </div>
            </td>
            <td>${doc.document_type.name}</td>
            <td>
                <span class="badge badge-${getStatusBadgeClass(doc.status)}">${doc.status}</span>
            </td>
            <td>${new Date(doc.created_at).toLocaleTimeString()}</td>
            <td>
                <button class="btn btn-sm btn-circle btn-outline" title="View" onclick="viewUploadedDocument('${doc.tracking_number}')">
                    <i class="bx bx-show"></i>
                </button>
            </td>
        </tr>
    `).join('');
}

function updateStats(documents) {
    const today = new Date().toDateString();
    const todayUploads = documents.filter(doc => 
        new Date(doc.created_at).toDateString() === today
    ).length;
    
    const ocrProcessed = documents.filter(doc => 
        doc.ocr_processed_at !== null
    ).length;
    
    const successRate = documents.length > 0 ? 
        Math.round((documents.filter(doc => doc.status !== 'rejected').length / documents.length) * 100) : 100;

    document.getElementById('statToday').textContent = todayUploads;
    document.getElementById('statOcr').textContent = ocrProcessed;
    document.getElementById('statSuccess').textContent = successRate + '%';
}

function updateFilePreview(file) {
    // You can implement file preview here if needed
    console.log('File selected:', file.name, file.type, file.size);
}

function getFileIcon(filePath) {
    const ext = filePath.split('.').pop().toLowerCase();
    if (ext === 'pdf') return 'bxs-file-pdf';
    if (['jpg', 'jpeg', 'png', 'gif'].includes(ext)) return 'bxs-file-image';
    return 'bxs-file';
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

function showUploadSuccess(document) {
    // You can show a success modal with document details
    console.log('Upload successful:', document);
    
    showToast('success', `
        Document uploaded successfully!
        Tracking Number: ${document.tracking_number}
    `);
}

function viewUploadedDocument(trackingNumber) {
    // Implement view functionality
    showToast('info', `Viewing document: ${trackingNumber}`);
    // You can redirect to documents management page or show in modal
}

// ==================== UTILITY FUNCTIONS ====================
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