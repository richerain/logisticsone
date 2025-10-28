@extends('layouts.app')

@section('title', 'Logistics Record - DTLR')

@section('content')
    <div class="module-content bg-white rounded-xl p-6 shadow block">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Logistics Record</h2>
            <button class="btn btn-primary" id="exportLogsBtn">
                <i class="bx bx-export mr-2"></i>Export Logs
            </button>
        </div>

        <!-- Stats Section -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-primary">
                <div class="stat-figure text-primary">
                    <i class="bx bx-list-check text-3xl"></i>
                </div>
                <div class="stat-title">Total Logs</div>
                <div class="stat-value text-primary" id="total-logs">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-warning">
                <div class="stat-figure text-warning">
                    <i class="bx bx-time text-3xl"></i>
                </div>
                <div class="stat-title">Logs Today</div>
                <div class="stat-value text-warning" id="logs-today">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-success">
                <div class="stat-figure text-success">
                    <i class="bx bx-brain text-3xl"></i>
                </div>
                <div class="stat-title">AI/OCR Used</div>
                <div class="stat-value text-success" id="ai-ocr-used">0</div>
            </div>
            <div class="stat bg-base-100 rounded-lg shadow-lg border-l-4 border-info">
                <div class="stat-figure text-info">
                    <i class="bx bx-cube text-3xl"></i>
                </div>
                <div class="stat-title">Top Module</div>
                <div class="stat-value text-info text-lg" id="top-module">-</div>
            </div>
        </div>

        <!-- Search and Filters -->
        <div class="flex gap-4 mb-6 flex-wrap">
            <div class="form-control flex-1 min-w-[300px]">
                <input type="text" placeholder="Search logs..." class="input input-bordered w-full" id="searchLogs">
            </div>
            <select class="select select-bordered" id="moduleFilter">
                <option value="">All Modules</option>
                <option value="Document Tracker">Document Tracker</option>
                <option value="Smart Warehousing System">Smart Warehousing System</option>
                <option value="Procurement & Sourcing Management">Procurement & Sourcing Management</option>
                <option value="Project Logistics Tracker">Project Logistics Tracker</option>
                <option value="Asset Lifecycle & Maintenance">Asset Lifecycle & Maintenance</option>
                <option value="Document Tracking & Logistics Record">Document Tracking & Logistics Record</option>
            </select>
            <select class="select select-bordered" id="aiOcrFilter">
                <option value="">All AI/OCR</option>
                <option value="true">AI/OCR Used</option>
                <option value="false">No AI/OCR</option>
            </select>
            <input type="date" class="input input-bordered" id="dateFromFilter" placeholder="From Date">
            <input type="date" class="input input-bordered" id="dateToFilter" placeholder="To Date">
        </div>

        <!-- Logistics Records Table -->
        <div class="overflow-x-auto bg-base-100 rounded-lg">
            <table class="table table-zebra w-full">
                <thead>
                    <tr class="bg-gray-900 text-white">
                        <th>Log ID</th>
                        <th>Action</th>
                        <th>Module</th>
                        <th>Timestamp</th>
                        <th>AI/OCR Used</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="logs-table-body">
                    <tr>
                        <td colspan="8" class="text-center py-8">
                            <div class="loading loading-spinner loading-lg"></div>
                            <p class="text-gray-500 mt-2">Loading logistics records...</p>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- View Log Modal -->
    <div id="viewLogModal" class="modal modal-lg">
        <div class="modal-box max-w-4xl p-0 overflow-visible">
            <div class="flex justify-between items-center bg-green-700 p-4 rounded-t-lg">
                <h3 class="font-bold text-white text-lg">Logistics Record Details</h3>
                <button class="btn btn-sm btn-circle btn-ghost hover:bg-white/20 text-white" id="closeViewLogModalX">✕</button>
            </div>
            <div class="p-4 max-h-[70vh] overflow-y-auto">
                <div class="space-y-4" id="logDetails">
                    <!-- Log details will be populated here -->
                </div>
                <div class="modal-action flex justify-end pt-4 border-t">
                    <button type="button" class="btn btn-ghost btn-sm hover:bg-gray-100 transition-colors px-4" id="closeViewLogModal">Close</button>
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
    let logisticsRecords = [];

    // ==================== CONFIGURATION ====================
    const API_BASE_URL = 'http://localhost:8001/api/dtlr';

    // Utility functions
    function formatDate(dateString) {
        return new Date(dateString).toLocaleDateString('en-US', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
    }

    function getTimeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffMs = now - date;
        const diffMins = Math.floor(diffMs / 60000);
        const diffHours = Math.floor(diffMs / 3600000);
        const diffDays = Math.floor(diffMs / 86400000);

        if (diffMins < 1) return 'Just now';
        if (diffMins < 60) return `${diffMins} min ago`;
        if (diffHours < 24) return `${diffHours} hr ago`;
        if (diffDays < 7) return `${diffDays} day${diffDays > 1 ? 's' : ''} ago`;
        
        return formatDate(dateString);
    }

    function getAiOcrBadge(aiOcrUsed) {
        return aiOcrUsed ? 
            `<span class="badge badge-success text-white uppercase font-bold">Yes</span>` :
            `<span class="badge badge-error text-white uppercase font-bold">No</span>`;
    }

    function getActionBadge(action) {
        const actionClasses = {
            'Document Uploaded': 'bg-blue-500',
            'Document Updated': 'bg-yellow-500',
            'Document Deleted': 'bg-red-500',
            'Approved': 'bg-green-500',
            'Rejected': 'bg-red-500',
            'Delivered': 'bg-purple-500',
            'Created': 'bg-indigo-500',
            'Modified': 'bg-orange-500'
        };
        
        return `<span class="badge text-white font-semibold text-xs px-2 py-1 ${actionClasses[action] || 'bg-gray-500'} border-0">
            ${action}
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
        loadLogisticsRecords();
        loadStats();
    });

    function initializeEventListeners() {
        // Export logs button
        document.getElementById('exportLogsBtn').addEventListener('click', exportLogs);

        // Close modal buttons
        document.getElementById('closeViewLogModal').addEventListener('click', closeViewLogModal);
        document.getElementById('closeViewLogModalX').addEventListener('click', closeViewLogModal);

        // Search and filter
        document.getElementById('searchLogs').addEventListener('input', filterLogs);
        document.getElementById('moduleFilter').addEventListener('change', filterLogs);
        document.getElementById('aiOcrFilter').addEventListener('change', filterLogs);
        document.getElementById('dateFromFilter').addEventListener('change', filterLogs);
        document.getElementById('dateToFilter').addEventListener('change', filterLogs);
    }

    async function loadStats() {
        try {
            const response = await fetch(`${API_BASE_URL}/stats`);
            const result = await response.json();
            
            if (result.success) {
                const stats = result.data.logs;
                document.getElementById('total-logs').textContent = stats.total_logs;
                document.getElementById('logs-today').textContent = stats.logs_today;
                document.getElementById('ai-ocr-used').textContent = stats.ai_ocr_used;
                document.getElementById('top-module').textContent = stats.top_module ? stats.top_module.module : '-';
            }
        } catch (error) {
            console.error('Error loading stats:', error);
        }
    }

    async function loadLogisticsRecords() {
        try {
            showLogsLoadingState();
            const response = await fetch(`${API_BASE_URL}/logistics-records`);
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const result = await response.json();
            
            if (result.success) {
                logisticsRecords = result.data || [];
                renderLogs(logisticsRecords);
            } else {
                throw new Error(result.message || 'Failed to load logistics records');
            }
        } catch (error) {
            console.error('Error loading logistics records:', error);
            showLogsErrorState('Failed to load logistics records: ' + error.message);
        }
    }

    function showLogsLoadingState() {
        const tbody = document.getElementById('logs-table-body');
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-8">
                    <div class="loading loading-spinner loading-lg"></div>
                    <p class="text-gray-500 mt-2">Loading logistics records...</p>
                </td>
            </tr>
        `;
    }

    function showLogsErrorState(message) {
        const tbody = document.getElementById('logs-table-body');
        tbody.innerHTML = `
            <tr>
                <td colspan="8" class="text-center py-8">
                    <i class="bx bx-error text-4xl text-red-400 mb-2"></i>
                    <p class="text-red-500">${message}</p>
                    <button class="btn btn-sm btn-outline mt-2" onclick="loadLogisticsRecords()">Retry</button>
                </td>
            </tr>
        `;
    }

    function renderLogs(logsData) {
        const tbody = document.getElementById('logs-table-body');
        
        if (logsData.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="8" class="text-center py-8">
                        <i class="bx bx-list-check text-4xl text-gray-400 mb-2"></i>
                        <p class="text-gray-500">No logistics records found</p>
                    </td>
                </tr>
            `;
            return;
        }

        tbody.innerHTML = logsData.map(log => {
            const truncatedDetails = log.details ? 
                (log.details.length > 100 ? log.details.substring(0, 100) + '...' : log.details) :
                'No details';
                
            return `
            <tr>
                <td class="font-mono font-semibold text-sm">${log.log_id}</td>
                <td>${getActionBadge(log.action)}</td>
                <td class="text-sm">${log.module}</td>
                <td class="text-sm">
                    <div>${formatDate(log.timestamp)}</div>
                    <div class="text-xs text-gray-500">${getTimeAgo(log.timestamp)}</div>
                </td>
                <td>${getAiOcrBadge(log.ai_ocr_used)}</td>
                <td>
                    <div class="flex space-x-1">
                        <button title="View" class="btn btn-sm btn-circle btn-info view-log-btn" data-log-id="${log.id}">
                            <i class="bx bx-show-alt text-sm"></i>
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
        document.querySelectorAll('.view-log-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const logId = this.getAttribute('data-log-id');
                viewLog(parseInt(logId));
            });
        });
    }

    function filterLogs() {
        const searchTerm = document.getElementById('searchLogs').value.toLowerCase();
        const moduleFilter = document.getElementById('moduleFilter').value;
        const aiOcrFilter = document.getElementById('aiOcrFilter').value;
        const dateFrom = document.getElementById('dateFromFilter').value;
        const dateTo = document.getElementById('dateToFilter').value;
        
        const filtered = logisticsRecords.filter(log => {
            const matchesSearch = searchTerm === '' || 
                log.log_id.toLowerCase().includes(searchTerm) ||
                log.action.toLowerCase().includes(searchTerm) ||
                log.module.toLowerCase().includes(searchTerm) ||
                log.performed_by.toLowerCase().includes(searchTerm) ||
                (log.details && log.details.toLowerCase().includes(searchTerm));
            
            const matchesModule = moduleFilter === '' || log.module === moduleFilter;
            const matchesAiOcr = aiOcrFilter === '' || log.ai_ocr_used.toString() === aiOcrFilter;
            
            let matchesDate = true;
            if (dateFrom) {
                matchesDate = matchesDate && new Date(log.timestamp) >= new Date(dateFrom);
            }
            if (dateTo) {
                matchesDate = matchesDate && new Date(log.timestamp) <= new Date(dateTo + 'T23:59:59');
            }
            
            return matchesSearch && matchesModule && matchesAiOcr && matchesDate;
        });
        
        renderLogs(filtered);
    }

    // Modal Functions
    function openViewLogModal() {
        document.getElementById('viewLogModal').classList.add('modal-open');
    }

    function closeViewLogModal() {
        document.getElementById('viewLogModal').classList.remove('modal-open');
    }

    // Log Actions
    function viewLog(logId) {
        const log = logisticsRecords.find(l => l.id === logId);
        if (!log) return;

        const logDetails = `
            <div class="space-y-4">
                <!-- Basic Information -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <strong class="text-gray-700 text-xs">Log ID:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1 font-mono">${log.log_id}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Action:</strong>
                        <p class="mt-1 p-2">${getActionBadge(log.action)}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Module:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${log.module}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Performed By:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${log.performed_by}</p>
                    </div>
                </div>

                <!-- Timestamp Information -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <strong class="text-gray-700 text-xs">Timestamp:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${formatDate(log.timestamp)}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Time Ago:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${getTimeAgo(log.timestamp)}</p>
                    </div>
                </div>

                <!-- AI/OCR Information -->
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <strong class="text-gray-700 text-xs">AI/OCR Used:</strong>
                        <p class="mt-1 p-2">${getAiOcrBadge(log.ai_ocr_used)}</p>
                    </div>
                    <div>
                        <strong class="text-gray-700 text-xs">Record Created:</strong>
                        <p class="text-sm p-2 bg-gray-50 rounded border mt-1">${formatDate(log.created_at)}</p>
                    </div>
                </div>

                <!-- Details -->
                <div>
                    <strong class="text-gray-700 text-xs">Details:</strong>
                    <p class="text-sm p-2 bg-gray-50 rounded border mt-1 min-h-20">${log.details || 'No additional details'}</p>
                </div>
            </div>
        `;

        document.getElementById('logDetails').innerHTML = logDetails;
        openViewLogModal();
    }

    async function exportLogs() {
        try {
            showLoadingModal('Exporting Logs...', 'Please wait while we prepare your export.');

            // Build query parameters from current filters
            const params = new URLSearchParams();
            
            const searchTerm = document.getElementById('searchLogs').value;
            if (searchTerm) params.append('search', searchTerm);
            
            const moduleFilter = document.getElementById('moduleFilter').value;
            if (moduleFilter) params.append('module', moduleFilter);
            
            const aiOcrFilter = document.getElementById('aiOcrFilter').value;
            if (aiOcrFilter) params.append('ai_ocr_used', aiOcrFilter);
            
            const dateFrom = document.getElementById('dateFromFilter').value;
            if (dateFrom) params.append('date_from', dateFrom);
            
            const dateTo = document.getElementById('dateToFilter').value;
            if (dateTo) params.append('date_to', dateTo);

            const response = await fetch(`${API_BASE_URL}/logistics-records/export?${params.toString()}`);
            const result = await response.json();

            if (response.ok && result.success) {
                // Create and download CSV file
                const logs = result.data;
                const csvContent = convertToCSV(logs);
                downloadCSV(csvContent, `logistics-records-${new Date().toISOString().split('T')[0]}.csv`);
                
                hideLoadingModal();
                showSuccessToast('Logs exported successfully!');
            } else {
                throw new Error(result.message || 'Export failed');
            }
        } catch (error) {
            hideLoadingModal();
            Swal.fire('Error', 'Failed to export logs: ' + error.message, 'error');
        }
    }

    function convertToCSV(logs) {
        const headers = ['Log ID', 'Action', 'Module', 'Performed By', 'Timestamp', 'AI/OCR Used', 'Details'];
        const csvRows = [headers.join(',')];

        logs.forEach(log => {
            const row = [
                `"${log['Log ID']}"`,
                `"${log['Action']}"`,
                `"${log['Module']}"`,
                `"${log['Performed By']}"`,
                `"${log['Timestamp']}"`,
                `"${log['AI/OCR Used']}"`,
                `"${(log['Details'] || '').replace(/"/g, '""')}"`
            ];
            csvRows.push(row.join(','));
        });

        return csvRows.join('\n');
    }

    function downloadCSV(csvContent, filename) {
        const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
        const url = URL.createObjectURL(blob);
        const link = document.createElement('a');
        link.setAttribute('href', url);
        link.setAttribute('download', filename);
        link.style.visibility = 'hidden';
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    }
</script>

<style>
    .modal-box {
        max-height: 85vh;
    }
    .modal-box .max-h-\[70vh\] {
        max-height: 70vh;
    }
    .table td {
        white-space: nowrap;
    }
    .truncate {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>
@endsection