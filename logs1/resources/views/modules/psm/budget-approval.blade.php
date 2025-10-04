@extends('layouts.app')

@section('title', 'Budget Approval')

@section('content')
    <div class="module-content bg-white rounded-xl p-6 shadow block">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Budget Approval</h2>
            <div class="flex items-center space-x-4">
                <div class="form-control">
                    <input type="text" placeholder="Search approvals..." class="input input-bordered w-64" id="searchApprovals">
                </div>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="stat bg-base-100 rounded-lg">
                <div class="stat-figure text-primary">
                    <i class="bx bx-money text-3xl"></i>
                </div>
                <div class="stat-title">Total Requests</div>
                <div class="stat-value text-primary" id="total-approvals">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg">
                <div class="stat-figure text-warning">
                    <i class="bx bx-time text-3xl"></i>
                </div>
                <div class="stat-title">Pending</div>
                <div class="stat-value text-warning" id="pending-approvals">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg">
                <div class="stat-figure text-success">
                    <i class="bx bx-check-circle text-3xl"></i>
                </div>
                <div class="stat-title">Approved</div>
                <div class="stat-value text-success" id="approved-approvals">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg">
                <div class="stat-figure text-error">
                    <i class="bx bx-x-circle text-3xl"></i>
                </div>
                <div class="stat-title">Rejected</div>
                <div class="stat-value text-error" id="rejected-approvals">0</div>
            </div>
        </div>

        <!-- Tabs -->
        <div class="tabs tabs-boxed mb-6 bg-base-200 p-1">
            <button class="tab tab-active" data-tab="all">All Requests</button>
            <button class="tab" data-tab="pending">Pending</button>
            <button class="tab" data-tab="approved">Approved</button>
            <button class="tab" data-tab="rejected">Rejected</button>
        </div>

        <!-- Budget Approvals Table -->
        <div class="overflow-x-auto bg-base-100 rounded-lg">
            <table class="table table-zebra w-full">
                <thead>
                    <tr class="bg-base-200">
                        <th>Request ID</th>
                        <th>Type</th>
                        <th>Entity</th>
                        <th>Budget Name</th>
                        <th>Amount</th>
                        <th>Status</th>
                        <th>Request Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="approvals-table-body">
                    <tr>
                        <td colspan="8" class="text-center py-8">
                            <div class="loading loading-spinner loading-lg"></div>
                            <p class="text-gray-500 mt-2">Loading budget approvals...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Approval Modal -->
    <div id="approvalModal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg mb-4">Review Budget Request</h3>
            <div id="approvalDetails" class="space-y-3 mb-6">
                <!-- Details will be populated here -->
            </div>
            <form id="approvalForm" class="space-y-4">
                <input type="hidden" id="approvalId">
                <input type="hidden" id="approvalType">
                <input type="hidden" id="entityId">
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Decision</span>
                    </label>
                    <div class="flex space-x-4">
                        <label class="cursor-pointer label">
                            <input type="radio" name="bud_status" value="approved" class="radio radio-success">
                            <span class="label-text ml-2">Approve</span>
                        </label>
                        <label class="cursor-pointer label">
                            <input type="radio" name="bud_status" value="rejected" class="radio radio-error">
                            <span class="label-text ml-2">Reject</span>
                        </label>
                    </div>
                </div>
                
                <div class="form-control">
                    <label class="label">
                        <span class="label-text font-semibold">Notes</span>
                    </label>
                    <textarea name="bud_desc" class="textarea textarea-bordered h-20" placeholder="Add approval notes..."></textarea>
                </div>
                
                <div class="modal-action">
                    <button type="button" class="btn btn-ghost" onclick="closeApprovalModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bx bx-check-circle mr-2"></i>Submit Decision
                    </button>
                </div>
            </form>
        </div>
    </div>

<script>
    // ==================== CONFIGURATION ====================
    const API_BASE_URL = 'http://localhost:8001/api/psm';

    let budgetApprovals = [];
    let currentApprovalTab = 'all';

    document.addEventListener('DOMContentLoaded', function() {
        loadBudgetApprovals();
        setupApprovalTabs();
        
        document.getElementById('searchApprovals').addEventListener('input', function(e) {
            filterApprovals(e.target.value);
        });
    });

    function setupApprovalTabs() {
        document.querySelectorAll('.tab[data-tab]').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.tab[data-tab]').forEach(t => t.classList.remove('tab-active'));
                this.classList.add('tab-active');
                
                currentApprovalTab = this.getAttribute('data-tab');
                filterApprovalsByTab();
            });
        });
    }

    async function loadBudgetApprovals() {
        try {
            const response = await fetch(`${API_BASE_URL}/budget-approvals`);
            const data = await response.json();
            
            if (response.ok) {
                budgetApprovals = data;
                filterApprovalsByTab();
                updateApprovalStats();
            } else {
                throw new Error(data.message || 'Failed to load budget approvals');
            }
        } catch (error) {
            console.error('Error loading budget approvals:', error);
            Swal.fire('Error', 'Failed to load budget approvals: ' + error.message, 'error');
        }
    }

    function filterApprovalsByTab() {
        let filteredApprovals = budgetApprovals;

        if (currentApprovalTab !== 'all') {
            filteredApprovals = budgetApprovals.filter(approval => 
                approval.bud_status === currentApprovalTab
            );
        }

        renderApprovals(filteredApprovals);
    }

    function filterApprovals(searchTerm) {
        let filteredApprovals = budgetApprovals;

        if (currentApprovalTab !== 'all') {
            filteredApprovals = budgetApprovals.filter(approval => 
                approval.bud_status === currentApprovalTab
            );
        }

        if (searchTerm) {
            filteredApprovals = filteredApprovals.filter(approval => 
                approval.bud_id.toString().includes(searchTerm) ||
                approval.bud_name?.toLowerCase().includes(searchTerm.toLowerCase()) ||
                approval.entity_type.toLowerCase().includes(searchTerm.toLowerCase())
            );
        }

        renderApprovals(filteredApprovals);
    }

    function renderApprovals(approvalsToRender) {
        const tbody = document.getElementById('approvals-table-body');
        
        if (approvalsToRender.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center py-8">
                        <i class="bx bx-money text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500">No budget approvals found</p>
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = approvalsToRender.map(approval => `
            <tr>
                <td class="font-mono font-semibold">#${approval.bud_id.toString().padStart(6, '0')}</td>
                <td>
                    <span class="badge badge-outline">${approval.entity_type}</span>
                </td>
                <td>Entity #${approval.entity_id}</td>
                <td>${approval.bud_name || 'N/A'}</td>
                <td class="font-semibold">₱${parseFloat(approval.total_budget || 0).toFixed(2)}</td>
                <td>
                    <span class="badge ${getApprovalStatusBadgeClass(approval.bud_status)}">
                        ${approval.bud_status}
                    </span>
                </td>
                <td>${new Date(approval.created_at).toLocaleDateString()}</td>
                <td>
                    <div class="flex space-x-1">
                        <button class="btn btn-xs btn-outline btn-info" onclick="viewApprovalDetails(${approval.bud_id})">
                            <i class="bx bx-show"></i>
                        </button>
                        ${approval.bud_status === 'pending' ? `
                            <button class="btn btn-xs btn-outline btn-warning" onclick="openApprovalModal(${approval.bud_id})">
                                <i class="bx bx-edit"></i>
                            </button>
                        ` : ''}
                    </div>
                </td>
            </tr>
        `).join('');
    }

    function getApprovalStatusBadgeClass(status) {
        switch(status) {
            case 'approved': return 'badge-success';
            case 'rejected': return 'badge-error';
            case 'pending': return 'badge-warning';
            default: return 'badge-ghost';
        }
    }

    function updateApprovalStats() {
        document.getElementById('total-approvals').textContent = budgetApprovals.length;
        document.getElementById('pending-approvals').textContent = 
            budgetApprovals.filter(a => a.bud_status === 'pending').length;
        document.getElementById('approved-approvals').textContent = 
            budgetApprovals.filter(a => a.bud_status === 'approved').length;
        document.getElementById('rejected-approvals').textContent = 
            budgetApprovals.filter(a => a.bud_status === 'rejected').length;
    }

    function viewApprovalDetails(approvalId) {
        const approval = budgetApprovals.find(a => a.bud_id === approvalId);
        if (!approval) return;

        Swal.fire({
            title: `Budget Request #${approval.bud_id.toString().padStart(6, '0')}`,
            html: `
                <div class="text-left space-y-2">
                    <p><strong>Type:</strong> ${approval.entity_type}</p>
                    <p><strong>Entity ID:</strong> ${approval.entity_id}</p>
                    <p><strong>Budget Name:</strong> ${approval.bud_name || 'N/A'}</p>
                    <p><strong>Quantity:</strong> ${approval.quantity || 'N/A'}</p>
                    <p><strong>Unit Price:</strong> ₱${parseFloat(approval.unit_price || 0).toFixed(2)}</p>
                    <p><strong>Total Budget:</strong> ₱${parseFloat(approval.total_budget || 0).toFixed(2)}</p>
                    <p><strong>Status:</strong> <span class="badge ${getApprovalStatusBadgeClass(approval.bud_status)}">${approval.bud_status}</span></p>
                    <p><strong>Description:</strong> ${approval.bud_desc || 'No description'}</p>
                    <p><strong>Created:</strong> ${new Date(approval.created_at).toLocaleString()}</p>
                    ${approval.approved_at ? `<p><strong>Approved:</strong> ${new Date(approval.approved_at).toLocaleString()}</p>` : ''}
                </div>
            `,
            icon: 'info',
            confirmButtonText: 'Close'
        });
    }

    function openApprovalModal(approvalId) {
        const approval = budgetApprovals.find(a => a.bud_id === approvalId);
        if (!approval) return;

        document.getElementById('approvalId').value = approval.bud_id;
        document.getElementById('approvalType').value = approval.entity_type;
        document.getElementById('entityId').value = approval.entity_id;

        // Populate details
        document.getElementById('approvalDetails').innerHTML = `
            <div class="bg-base-200 p-4 rounded-lg">
                <h4 class="font-semibold mb-2">Request Details</h4>
                <p><strong>Type:</strong> ${approval.entity_type}</p>
                <p><strong>Budget Name:</strong> ${approval.bud_name || 'N/A'}</p>
                <p><strong>Amount:</strong> ₱${parseFloat(approval.total_budget || 0).toFixed(2)}</p>
                <p><strong>Description:</strong> ${approval.bud_desc || 'No description'}</p>
            </div>
        `;

        // Reset form
        document.getElementById('approvalForm').reset();
        document.getElementById('approvalModal').classList.add('modal-open');
    }

    function closeApprovalModal() {
        document.getElementById('approvalModal').classList.remove('modal-open');
    }

    document.getElementById('approvalForm').addEventListener('submit', async function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const approvalId = document.getElementById('approvalId').value;
        const entityType = document.getElementById('approvalType').value;
        const entityId = document.getElementById('entityId').value;

        const approvalData = {
            bud_status: formData.get('bud_status'),
            bud_desc: formData.get('bud_desc'),
            approver_user_id: 1 // This should come from user session
        };

        try {
            const response = await fetch(`${API_BASE_URL}/budget-approvals/${approvalId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(approvalData)
            });

            const result = await response.json();

            if (response.ok) {
                Swal.fire('Success', 'Budget request updated successfully!', 'success');
                closeApprovalModal();
                loadBudgetApprovals();
            } else {
                throw new Error(result.message || 'Failed to update budget request');
            }
        } catch (error) {
            Swal.fire('Error', 'Failed to update budget request: ' + error.message, 'error');
        }
    });
</script>
@endsection