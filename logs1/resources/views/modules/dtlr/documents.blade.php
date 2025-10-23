@extends('layouts.app')

@section('title', 'Document Tracker - DTLR')

@section('content')
    <div class="module-content bg-white rounded-xl p-6 shadow block">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Document Tracker</h2>
            <button class="btn btn-primary" id="addDocumentBtn">
                <i class="bx bx-plus mr-2"></i>Upload Document
            </button>
        </div>

        <!-- Stats Section -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-primary">
                <div class="stat-figure text-primary">
                    <i class="bx bx-file text-3xl"></i>
                </div>
                <div class="stat-title">Total Documents</div>
                <div class="stat-value text-primary" id="total-documents">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-warning">
                <div class="stat-figure text-warning">
                    <i class="bx bx-time text-3xl"></i>
                </div>
                <div class="stat-title">Pending Review</div>
                <div class="stat-value text-warning" id="pending-documents">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-success">
                <div class="stat-figure text-success">
                    <i class="bx bx-check-circle text-3xl"></i>
                </div>
                <div class="stat-title">Indexed</div>
                <div class="stat-value text-success" id="indexed-documents">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-info">
                <div class="stat-figure text-info">
                    <i class="bx bx-archive text-3xl"></i>
                </div>
                <div class="stat-title">Archived</div>
                <div class="stat-value text-info" id="archived-documents">0</div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="flex gap-4 mb-6 flex-wrap">
            <div class="form-control flex-1 min-w-[300px]">
                <input type="text" placeholder="Search documents..." class="input input-bordered w-full" id="searchDocuments">
            </div>
            <select class="select select-bordered" id="documentTypeFilter">
                <option value="">All Types</option>
                <option value="PO">Purchase Order</option>
                <option value="GRN">Goods Received Note</option>
                <option value="Invoice">Invoice</option>
                <option value="Delivery Note">Delivery Note</option>
                <option value="Quotation">Quotation</option>
                <option value="Contract">Contract</option>
                <option value="Other">Other</option>
            </select>
            <select class="select select-bordered" id="statusFilter">
                <option value="">All Status</option>
                <option value="Indexed">Indexed</option>
                <option value="Pending Review">Pending Review</option>
                <option value="Archived">Archived</option>
            </select>
        </div>

        <!-- Documents Table -->
        <div class="overflow-x-auto bg-base-100 rounded-lg">
            <table class="table table-zebra w-full">
                <thead>
                    <tr class="bg-gray-900 text-white">
                        <th>Document ID</th>
                        <th>Document Type</th>
                        <th>Upload Date</th>
                        <th>Status</th>
                        <th>File</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="documents-table-body">
                    <tr>
                        <td colspan="8" class="text-center py-8">
                            <div class="loading loading-spinner loading-lg"></div>
                            <p class="text-gray-500 mt-2">Loading documents...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add/Edit Document Modal -->
    <div id="documentModal" class="modal modal-lg">
        <div class="modal-box max-w-4xl p-0 overflow-visible">
            <div class="flex justify-between items-center bg-green-700 p-4 rounded-t-lg">
                <h3 class="font-bold text-white text-lg" id="documentModalTitle">Upload Document</h3>
                <button class="btn btn-sm btn-circle btn-ghost hover:bg-white/20 text-white" id="closeDocumentModalX">✕</button>
            </div>
            <div class="p-4 max-h-[70vh] overflow-y-auto">
                <form id="documentForm" class="space-y-4" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" id="documentId" name="document_id">
                    
                    <!-- Auto-generated Document ID -->
                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Document ID</span>
                        </label>
                        <input type="text" id="documentIdDisplay" class="input input-bordered input-sm w-full bg-gray-100" 
                               readonly placeholder="Auto-generated">
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Document Type *</span>
                            </label>
                            <select id="documentType" name="document_type" class="select select-bordered select-sm w-full" required>
                                <option value="">Select Document Type</option>
                                <option value="PO">Purchase Order</option>
                                <option value="GRN">Goods Received Note</option>
                                <option value="Invoice">Invoice</option>
                                <option value="Delivery Note">Delivery Note</option>
                                <option value="Quotation">Quotation</option>
                                <option value="Contract">Contract</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div class="form-control">
                            <label class="label">
                                <span class="label-text font-semibold">Linked Transaction</span>
                            </label>
                            <input type="text" id="linkedTransaction" name="linked_transaction" class="input input-bordered input-sm w-full" 
                                   placeholder="e.g., PO-12345, GRN-67890">
                        </div>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Document File *</span>
                        </label>
                        <input type="file" id="documentFile" name="document_file" class="file-input file-input-bordered file-input-sm w-full" 
                               accept=".pdf,.jpg,.jpeg,.png" required>
                        <label class="label">
                            <span class="label-text-alt">Supported formats: PDF, JPG, JPEG, PNG (Max: 10MB)</span>
                        </label>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Uploaded By *</span>
                        </label>
                        <input type="text" id="uploadedBy" name="uploaded_by" class="input input-bordered input-sm w-full" 
                               placeholder="Enter your name or department" required>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Status</span>
                        </label>
                        <select id="documentStatus" name="status" class="select select-bordered select-sm w-full">
                            <option value="Pending Review">Pending Review</option>
                            <option value="Indexed">Indexed</option>
                            <option value="Archived">Archived</option>
                        </select>
                    </div>

                    <div class="form-control">
                        <label class="label">
                            <span class="label-text font-semibold">Extracted Fields (OCR)</span>
                        </label>
                        <textarea id="extractedFields" name="extracted_fields" class="textarea textarea-bordered textarea-sm h-24" 
                                  placeholder="OCR extracted data will appear here automatically..."></textarea>
                        <label class="label">
                            <span class="label-text-alt">Fields extracted by OCR will be populated automatically</span>
                        </label>
                    </div>

                    <!-- Modal Actions -->
                    <div class="modal-action flex justify-end space-x-3 pt-4 border-t">
                        <button type="button" class="btn btn-ghost btn-sm hover:bg-gray-100 transition-colors px-4" id="closeDocumentModal">Cancel</button>
                        <button type="submit" class="btn btn-primary btn-sm bg-gradient-to-r from-primary to-primary/90 hover:from-primary/90 hover:to-primary transition-all duration-300 shadow-lg px-4" id="documentSubmitBtn">
                            <i class="bx bx-save mr-1"></i><span id="documentModalSubmitText">Upload Document</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Document Modal -->
    <div id="viewDocumentModal" class="modal modal-lg">
        <div class="modal-box max-w-4xl p-0 overflow-visible">
            <div class="flex justify-between items-center bg-green-700 p-4 rounded-t-lg">
                <h3 class="font-bold text-white text-lg">Document Details</h3>
                <button class="btn btn-sm btn-circle btn-ghost hover:bg-white/20 text-white" id="closeViewDocumentModalX">✕</button>
            </div>
            <div class="p-4 max-h-[70vh] overflow-y-auto">
                <div class="space-y-4" id="documentDetails">
                    <!-- Document details will be populated here -->
                </div>
                <div class="modal-action flex justify-end pt-4 border-t">
                    <button type="button" class="btn btn-ghost btn-sm hover:bg-gray-100 transition-colors px-4" id="closeViewDocumentModal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Loading Modal -->
    <div id="loadingModal" class="modal">
        <div class="modal-box max-w-sm text-center p-4">
            <div class="loading loading-spinner loading-lg text-primary mb-2"></div>
            <h3 class="font-bold text-sm mb-1" id="loadingTitle">Processing...</h3>
        </div>
    </div>

<script>
    let documents = [];

    // ==================== CONFIGURATION ====================
    const API_BASE_URL = 'http://localhost:8001/api/dtlr';

    // Utility functions
    function formatDate(dateString) {
        return new Date(dateString).toLocaleDateString('en-US', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    function getStatusBadge(status) {
        const statusClasses = {
            'Indexed': 'bg-green-600 uppercase',
            'Pending Review': 'bg-yellow-400 uppercase',
            'Archived': 'bg-blue-400 uppercase'
        };
        
        return `<span class="badge text-white font-bold tracking-wider text-xs px-3 py-2 ${statusClasses[status] || 'bg-gray-400'} border-0">
            ${status}
        </span>`;
    }

    function getDocumentTypeBadge(type) {
        const typeClasses = {
            'PO': 'bg-purple-500',
            'GRN': 'bg-orange-500',
            'Invoice': 'bg-green-500',
            'Delivery Note': 'bg-blue-500',
            'Quotation': 'bg-pink-500',
            'Contract': 'bg-red-500',
            'Other': 'bg-gray-500'
        };
        
        const fullTypes = {
            'PO': 'Purchase Order',
            'GRN': 'Goods Received Note',
            'Invoice': 'Invoice',
            'Delivery Note': 'Delivery Note',
            'Quotation': 'Quotation',
            'Contract': 'Contract',
            'Other': 'Other Document'
        };
        
        return `<span class="badge text-white font-semibold text-xs px-2 py-1 ${typeClasses[type] || 'bg-gray-400'} border-0">
            ${fullTypes[type] || type}
        </span>`;
    }

    // Show loading modal
    function showLoadingModal(title = 'Processing...') {
        document.getElementById('loadingTitle').textContent = title;
        document.getElementById('loadingModal').classList.add('modal-open');
    }

    // Hide loading modal
    function hideLoadingModal() {
        document.getElementById('loadingModal').classList.remove('modal-open');
    }

    // Show success toast
    function showSuccessToast(message) {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        });

        Toast.fire({
            icon: 'success',
            title: message
        });
    }

    // Load data on page load
    document.addEventListener('DOMContentLoaded', function() {
        initializeEventListeners();
        loadDocuments();
        loadStats();
    });

    function initializeEventListeners() {
        // Add document button
        document.getElementById('addDocumentBtn').addEventListener('click', openAddDocumentModal);

        // Close modal buttons
        document.getElementById('closeDocumentModal').addEventListener('click', closeDocumentModal);
        document.getElementById('closeDocumentModalX').addEventListener('click', closeDocumentModal);
        document.getElementById('closeViewDocumentModal').addEventListener('click', closeViewDocumentModal);
        document.getElementById('closeViewDocumentModalX').addEventListener('click', closeViewDocumentModal);

        // Form submission
        document.getElementById('documentForm').addEventListener('submit', handleDocumentSubmit);

        // Search and filter
        document.getElementById('searchDocuments').addEventListener('input', filterDocuments);
        document.getElementById('documentTypeFilter').addEventListener('change', filterDocuments);
        document.getElementById('statusFilter').addEventListener('change', filterDocuments);
    }

    async function loadStats() {
        try {
            const response = await fetch(`${API_BASE_URL}/stats`);
            const result = await response.json();
            
            if (result.success) {
                const stats = result.data.documents;
                document.getElementById('total-documents').textContent = stats.total_documents;
                document.getElementById('pending-documents').textContent = stats.pending_review;
                document.getElementById('indexed-documents').textContent = stats.indexed_documents;
                document.getElementById('archived-documents').textContent = stats.archived_documents;
            }
        } catch (error) {
            console.error('Error loading stats:', error);
        }
    }

    async function loadDocuments() {
        try {
            showDocumentsLoadingState();
            const response = await fetch(`${API_BASE_URL}/documents`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const result = await response.json();
            
            if (result.success) {
                documents = result.data || [];
                renderDocuments(documents);
            } else {
                throw new Error(result.message || 'Failed to load documents');
            }
        } catch (error) {
            console.error('Error loading documents:', error);
            showDocumentsErrorState('Failed to load documents: ' + error.message);
        }
    }

    function showDocumentsLoadingState() {
        const tbody = document.getElementById('documents-table-body');
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-8">
                    <div class="loading loading-spinner loading-lg"></div>
                    <p class="text-gray-500 mt-2">Loading documents...</p>
                </td>
            </tr>
        `;
    }

    function showDocumentsErrorState(message) {
        const tbody = document.getElementById('documents-table-body');
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-8">
                    <i class="bx bx-error text-4xl text-red-400 mb-2"></i>
                    <p class="text-red-500">${message}</p>
                    <button class="btn btn-sm btn-outline mt-2" onclick="loadDocuments()">Retry</button>
                </td>
            </tr>
        `;
    }

    function renderDocuments(documentsData) {
        const tbody = document.getElementById('documents-table-body');
        
        if (documentsData.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center py-8">
                        <i class="bx bx-file text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500">No documents found</p>
                        <button class="btn btn-sm btn-primary mt-2" id="addFirstDocumentBtn">Upload First Document</button>
                    </td>
                </tr>
            `;
            document.getElementById('addFirstDocumentBtn')?.addEventListener('click', openAddDocumentModal);
            return;
        }

        tbody.innerHTML = documentsData.map(document => {
            const fileBadge = document.file_path ? 
                `<span class="badge badge-success text-white">Available</span>` :
                `<span class="badge badge-error text-white">Missing</span>`;
                
            return `
            <tr>
                <td class="font-mono font-semibold text-sm">${document.document_id}</td>
                <td>${getDocumentTypeBadge(document.document_type)}</td>
                <td class="text-sm">${formatDate(document.upload_date)}</td>
                <td>${getStatusBadge(document.status)}</td>
                <td>${fileBadge}</td>
                <td>
                    <div class="flex space-x-1">
                        <button title="View" class="btn btn-sm btn-circle btn-info view-document-btn" data-document-id="${document.id}">
                            <i class="bx bx-show-alt text-sm"></i>
                        </button>
                        ${document.file_path ? `
                        <button title="Download" class="btn btn-sm btn-circle btn-success download-document-btn" data-document-id="${document.id}">
                            <i class="bx bx-download text-sm"></i>
                        </button>
                        ` : ''}
                        <button title="Edit" class="hidden btn btn-sm btn-circle btn-warning edit-document-btn" data-document-id="${document.id}">
                            <i class="bx bx-edit text-sm"></i>
                        </button>
                        <button title="Delete" class="btn btn-sm btn-circle btn-error delete-document-btn" data-document-id="${document.id}">
                            <i class="bx bx-trash text-sm"></i>
                        </button>
                    </div>
                </td>
            </tr>
            `;
        }).join('');

        // Add event listeners to dynamically created buttons
        addDynamicEventListeners();
    }

    function addDynamicEventListeners() {
        document.querySelectorAll('.view-document-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const documentId = this.getAttribute('data-document-id');
                viewDocument(parseInt(documentId));
            });
        });

        document.querySelectorAll('.download-document-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const documentId = this.getAttribute('data-document-id');
                downloadDocument(parseInt(documentId));
            });
        });

        document.querySelectorAll('.edit-document-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const documentId = this.getAttribute('data-document-id');
                editDocument(parseInt(documentId));
            });
        });

        document.querySelectorAll('.delete-document-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const documentId = this.getAttribute('data-document-id');
                deleteDocument(parseInt(documentId));
            });
        });
    }

    function filterDocuments() {
        const searchTerm = document.getElementById('searchDocuments').value.toLowerCase();
        const typeFilter = document.getElementById('documentTypeFilter').value;
        const statusFilter = document.getElementById('statusFilter').value;
        
        const filtered = documents.filter(document => {
            const matchesSearch = searchTerm === '' || 
                document.document_id.toLowerCase().includes(searchTerm) ||
                document.document_type.toLowerCase().includes(searchTerm) ||
                (document.linked_transaction && document.linked_transaction.toLowerCase().includes(searchTerm)) ||
                document.uploaded_by.toLowerCase().includes(searchTerm);
            
            const matchesType = typeFilter === '' || document.document_type === typeFilter;
            const matchesStatus = statusFilter === '' || document.status === statusFilter;
            
            return matchesSearch && matchesType && matchesStatus;
        });
        
        renderDocuments(filtered);
    }

    // Modal Functions
    function openAddDocumentModal() {
        document.getElementById('documentModalTitle').textContent = 'Upload Document';
        document.getElementById('documentModalSubmitText').textContent = 'Upload Document';
        document.getElementById('documentForm').reset();
        document.getElementById('documentId').value = '';
        document.getElementById('documentIdDisplay').value = 'Auto-generated';
        document.getElementById('documentStatus').value = 'Pending Review';
        
        document.getElementById('documentModal').classList.add('modal-open');
    }

    function closeDocumentModal() {
        document.getElementById('documentModal').classList.remove('modal-open');
        document.getElementById('documentForm').reset();
    }

    function openViewDocumentModal() {
        document.getElementById('viewDocumentModal').classList.add('modal-open');
    }

    function closeViewDocumentModal() {
        document.getElementById('viewDocumentModal').classList.remove('modal-open');
    }

    // Document Actions
    function viewDocument(documentId) {
        const document = documents.find(d => d.id === documentId);
        if (!document) return;

        const documentDetails = `
            <div class="space-y-4">
                <!-- Basic Information -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <strong class="text-gray-700 text-xs">Document ID:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1 font-mono">${document.document_id}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Document Type:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${getDocumentTypeBadge(document.document_type)}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Linked Transaction:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${document.linked_transaction || 'N/A'}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Uploaded By:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${document.uploaded_by}</p>
                    </div>
                </div>

                <!-- File Information -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <strong class="text-gray-700 text-xs">Upload Date:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${formatDate(document.upload_date)}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Status:</strong>
                        <p class="mt-1 p-2">${getStatusBadge(document.status)}</p>
                    </div>
                </div>

                <!-- File Details -->
                ${document.file_path ? `
                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <strong class="text-gray-700 text-xs">File Name:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${document.file_name}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">File Type:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${document.file_type}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">File Size:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${formatFileSize(document.file_size)}</p>
                    </div>
                </div>
                ` : '<p class="text-warning text-sm">No file attached</p>'}

                <!-- Extracted Fields -->
                ${document.extracted_fields ? `
                <div>
                    <strong class="text-gray-700 text-xs">OCR Extracted Fields:</strong>
                    <div class="text-sm p-2 bg-gray-50 rounded border mt-1 max-h-32 overflow-y-auto">
                        <pre>${JSON.stringify(JSON.parse(document.extracted_fields), null, 2)}</pre>
                    </div>
                </div>
                ` : ''}

                <!-- Action Buttons -->
                ${document.file_path ? `
                <div class="flex justify-center pt-4">
                    <button class="btn btn-success btn-sm download-from-view" data-document-id="${document.id}">
                        <i class="bx bx-download mr-2"></i>Download File
                    </button>
                </div>
                ` : ''}
            </div>
        `;

        document.getElementById('documentDetails').innerHTML = documentDetails;
        
        // Add event listener for download button in view modal
        const downloadBtn = document.querySelector('.download-from-view');
        if (downloadBtn) {
            downloadBtn.addEventListener('click', function() {
                downloadDocument(document.id);
            });
        }
        
        openViewDocumentModal();
    }

    function formatFileSize(bytes) {
        if (!bytes) return '0 Bytes';
        const k = 1024;
        const sizes = ['Bytes', 'KB', 'MB', 'GB'];
        const i = Math.floor(Math.log(bytes) / Math.log(k));
        return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
    }

    function editDocument(documentId) {
        const document = documents.find(d => d.id === documentId);
        if (!document) return;

        document.getElementById('documentModalTitle').textContent = 'Edit Document';
        document.getElementById('documentModalSubmitText').textContent = 'Update Document';
        
        document.getElementById('documentId').value = document.id;
        document.getElementById('documentIdDisplay').value = document.document_id;
        document.getElementById('documentType').value = document.document_type;
        document.getElementById('linkedTransaction').value = document.linked_transaction || '';
        document.getElementById('uploadedBy').value = document.uploaded_by;
        document.getElementById('documentStatus').value = document.status;
        document.getElementById('extractedFields').value = document.extracted_fields || '';

        // File input is not required for edit
        document.getElementById('documentFile').required = false;

        document.getElementById('documentModal').classList.add('modal-open');
    }

    async function handleDocumentSubmit(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const documentId = document.getElementById('documentId').value;
        const isEdit = !!documentId;

        try {
            showLoadingModal(
                isEdit ? 'Updating Document...' : 'Uploading Document...',
                isEdit ? 'Please wait while we update the document.' : 'Please wait while we upload and process the document.'
            );

            let response;
            if (isEdit) {
                // For edits, use regular JSON
                const documentData = {
                    document_type: formData.get('document_type'),
                    linked_transaction: formData.get('linked_transaction'),
                    uploaded_by: formData.get('uploaded_by'),
                    status: formData.get('status'),
                    extracted_fields: formData.get('extracted_fields')
                };

                response = await fetch(`${API_BASE_URL}/documents/${documentId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify(documentData)
                });
            } else {
                // For new documents, use FormData for file upload
                response = await fetch(`${API_BASE_URL}/documents`, {
                    method: 'POST',
                    body: formData
                });
            }

            const result = await response.json();

            if (response.ok && result.success) {
                hideLoadingModal();
                closeDocumentModal();
                
                // Wait for data to reload before showing success message
                await loadDocuments();
                await loadStats();
                
                showSuccessToast(
                    isEdit ? 'Document updated successfully!' : 'Document uploaded successfully!'
                );
            } else {
                throw new Error(result.message || `Failed to ${isEdit ? 'update' : 'upload'} document`);
            }
        } catch (error) {
            hideLoadingModal();
            Swal.fire('Error', `Failed to ${isEdit ? 'update' : 'upload'} document: ` + error.message, 'error');
        }
    }

    async function downloadDocument(documentId) {
        try {
            showLoadingModal('Preparing Download...', 'Please wait while we prepare your file for download.');
            
            const response = await fetch(`${API_BASE_URL}/documents/${documentId}/download`);
            
            if (!response.ok) {
                const errorData = await response.json();
                throw new Error(errorData.message || 'Download failed');
            }

            // Get filename from Content-Disposition header or use document ID
            const contentDisposition = response.headers.get('Content-Disposition');
            let filename = `document-${documentId}.pdf`;
            
            if (contentDisposition) {
                const filenameMatch = contentDisposition.match(/filename="(.+)"/);
                if (filenameMatch) {
                    filename = filenameMatch[1];
                }
            }

            // Create blob and download
            const blob = await response.blob();
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = filename;
            document.body.appendChild(a);
            a.click();
            window.URL.revokeObjectURL(url);
            document.body.removeChild(a);
            
            hideLoadingModal();
            showSuccessToast('Document downloaded successfully!');
            
        } catch (error) {
            hideLoadingModal();
            Swal.fire('Error', 'Failed to download document: ' + error.message, 'error');
        }
    }

    async function deleteDocument(documentId) {
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: "This will permanently delete the document and its associated file!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        });

        if (result.isConfirmed) {
            try {
                showLoadingModal('Deleting Document...', 'Please wait while we remove the document.');

                const response = await fetch(`${API_BASE_URL}/documents/${documentId}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                const result = await response.json();

                if (response.ok && result.success) {
                    hideLoadingModal();
                    
                    // Wait for data to reload before showing success message
                    await loadDocuments();
                    await loadStats();
                    
                    showSuccessToast('Document deleted successfully!');
                } else {
                    throw new Error(result.message || 'Failed to delete document');
                }
            } catch (error) {
                hideLoadingModal();
                Swal.fire('Error', 'Failed to delete document: ' + error.message, 'error');
            }
        }
    }
</script>

<style>
    .modal-box {
        max-height: 85vh;
    }
    input:read-only {
        background-color: #f3f4f6;
        cursor: not-allowed;
    }
    .modal-box .max-h-\[70vh\] {
        max-height: 70vh;
    }
    .table td {
        white-space: nowrap;
    }
    pre {
        white-space: pre-wrap;
        word-wrap: break-word;
    }
</style>
@endsection