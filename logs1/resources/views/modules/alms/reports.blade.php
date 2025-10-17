@extends('layouts.app')

@section('title', 'Reports & Analytics - ALMS')

@section('content')
<div class="module-content bg-white rounded-xl p-6 shadow block">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-gray-800">Reports & Analytics</h2>
        <div class="flex gap-2">
            <button class="btn btn-outline" onclick="exportToPDF()">
                <i class="bx bx-download mr-2"></i>Export PDF
            </button>
            <button class="btn btn-outline" onclick="exportToExcel()">
                <i class="bx bx-table mr-2"></i>Export Excel
            </button>
        </div>
    </div>

    <!-- Report Type Selector -->
    <div class="flex flex-wrap gap-4 mb-6 p-4 bg-base-200 rounded-lg">
        <button class="btn btn-primary" onclick="loadReport('overview')">
            <i class="bx bx-home mr-2"></i>Overview
        </button>
        <button class="btn btn-outline" onclick="loadReport('asset_status')">
            <i class="bx bx-cube mr-2"></i>Asset Status
        </button>
        <button class="btn btn-outline" onclick="loadReport('maintenance_analytics')">
            <i class="bx bx-wrench mr-2"></i>Maintenance Analytics
        </button>
        <button class="btn btn-outline" onclick="loadReport('cost_analysis')">
            <i class="bx bx-dollar-circle mr-2"></i>Cost Analysis
        </button>
        <button class="btn btn-outline" onclick="loadReport('branch_assets')">
            <i class="bx bx-building mr-2"></i>Branch Assets
        </button>
    </div>

    <!-- Date Range Filter -->
    <div class="flex flex-col lg:flex-row gap-4 mb-6 p-4 bg-base-200 rounded-lg">
        <div class="flex gap-4 items-center">
            <label class="label">
                <span class="label-text font-semibold">Date Range:</span>
            </label>
            <input type="date" id="dateFrom" class="input input-bordered">
            <span class="text-gray-500">to</span>
            <input type="date" id="dateTo" class="input input-bordered">
            <button class="btn btn-primary" onclick="applyDateFilter()">
                <i class="bx bx-filter-alt mr-2"></i>Apply
            </button>
            <button class="btn btn-ghost" onclick="clearDateFilter()">
                <i class="bx bx-reset mr-2"></i>Clear
            </button>
        </div>
    </div>

    <!-- Loading State -->
    <div id="loadingState" class="text-center py-8 hidden">
        <div class="loading loading-spinner loading-lg text-primary"></div>
        <p class="mt-4 text-gray-600">Generating report...</p>
    </div>

    <!-- Overview Report -->
    <div id="overviewReport" class="report-section">
        <!-- Overview content will be loaded here -->
    </div>

    <!-- Asset Status Report -->
    <div id="assetStatusReport" class="report-section hidden">
        <!-- Asset Status content will be loaded here -->
    </div>

    <!-- Maintenance Analytics Report -->
    <div id="maintenanceAnalyticsReport" class="report-section hidden">
        <!-- Maintenance Analytics content will be loaded here -->
    </div>

    <!-- Cost Analysis Report -->
    <div id="costAnalysisReport" class="report-section hidden">
        <!-- Cost Analysis content will be loaded here -->
    </div>

    <!-- Branch Assets Report -->
    <div id="branchAssetsReport" class="report-section hidden">
        <!-- Branch Assets content will be loaded here -->
    </div>
</div>

<!-- Loading Toast -->
<div id="loadingToast" class="toast toast-middle toast-center hidden">
    <div class="alert alert-info">
        <span>Loading...</span>
    </div>
</div>

<script>
// ==================== CONFIGURATION ====================
const API_BASE_URL = 'http://localhost:8001/api/alms';
let currentReportType = 'overview';

// ==================== INITIALIZATION ====================
document.addEventListener('DOMContentLoaded', function() {
    setDefaultDateRange();
    loadReport('overview');
});

// ==================== REPORT MANAGEMENT ====================
async function loadReport(reportType) {
    currentReportType = reportType;
    
    // Update active button
    document.querySelectorAll('.btn-outline').forEach(btn => btn.classList.replace('btn-primary', 'btn-outline'));
    document.querySelectorAll('.btn-primary').forEach(btn => btn.classList.replace('btn-primary', 'btn-outline'));
    
    // Get the clicked button
    const clickedButton = event.target;
    clickedButton.classList.replace('btn-outline', 'btn-primary');
    
    // Hide all report sections
    document.querySelectorAll('.report-section').forEach(section => {
        section.classList.add('hidden');
    });
    
    showLoading();
    
    try {
        const dateFrom = document.getElementById('dateFrom').value;
        const dateTo = document.getElementById('dateTo').value;
        
        const params = new URLSearchParams({
            type: reportType
        });
        
        if (dateFrom) params.append('date_from', dateFrom);
        if (dateTo) params.append('date_to', dateTo);
        
        const response = await fetch(`${API_BASE_URL}/reports?${params}`);
        const data = await response.json();
        
        if (data.success) {
            displayReportData(reportType, data.data);
        } else {
            showError('Failed to load report: ' + (data.message || 'Unknown error'));
        }
    } catch (error) {
        showError('Error loading report: ' + error.message);
    } finally {
        hideLoading();
    }
}

function displayOverviewReport(data) {
    const reportSection = document.getElementById('overviewReport');
    
    reportSection.innerHTML = `
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
            <!-- Key Metrics -->
            <div class="bg-base-100 rounded-lg p-6 shadow">
                <h3 class="text-xl font-bold mb-4">Key Metrics</h3>
                <div class="grid grid-cols-2 gap-4">
                    <div class="stat">
                        <div class="stat-figure text-primary">
                            <i class="bx bx-cube text-2xl"></i>
                        </div>
                        <div class="stat-title">Total Assets</div>
                        <div class="stat-value text-primary">${data.total_assets}</div>
                    </div>
                    <div class="stat">
                        <div class="stat-figure text-success">
                            <i class="bx bx-check-circle text-2xl"></i>
                        </div>
                        <div class="stat-title">Active Assets</div>
                        <div class="stat-value text-success">${data.active_assets}</div>
                    </div>
                    <div class="stat">
                        <div class="stat-figure text-warning">
                            <i class="bx bx-wrench text-2xl"></i>
                        </div>
                        <div class="stat-title">In Maintenance</div>
                        <div class="stat-value text-warning">${data.maintenance_assets}</div>
                    </div>
                    <div class="stat">
                        <div class="stat-figure text-error">
                            <i class="bx bx-trash text-2xl"></i>
                        </div>
                        <div class="stat-title">Disposed Assets</div>
                        <div class="stat-value text-error">${data.disposed_assets}</div>
                    </div>
                </div>
                <div class="stat mt-4">
                    <div class="stat-figure text-info">
                        <i class="bx bx-dollar-circle text-2xl"></i>
                    </div>
                    <div class="stat-title">Total Asset Value</div>
                    <div class="stat-value text-info">₱${parseFloat(data.total_value).toLocaleString('en-PH', { minimumFractionDigits: 2 })}</div>
                </div>
            </div>
            
            <!-- Assets by Category -->
            <div class="bg-base-100 rounded-lg p-6 shadow">
                <h3 class="text-xl font-bold mb-4">Assets by Category</h3>
                <div class="space-y-3">
                    ${data.assets_by_category.map(category => `
                        <div class="flex justify-between items-center">
                            <span class="font-medium">${category.category}</span>
                            <div class="flex items-center gap-2">
                                <span class="badge badge-primary">${category.count}</span>
                                <div class="w-24 bg-gray-200 rounded-full h-2">
                                    <div class="bg-primary h-2 rounded-full" style="width: ${(category.count / data.total_assets) * 100}%"></div>
                                </div>
                            </div>
                        </div>
                    `).join('')}
                </div>
            </div>
        </div>
        
        <!-- Recent Maintenance -->
        <div class="bg-base-100 rounded-lg p-6 shadow">
            <h3 class="text-xl font-bold mb-4">Recent Maintenance Activities</h3>
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th>Record ID</th>
                            <th>Asset</th>
                            <th>Date</th>
                            <th>Cost</th>
                            <th>Description</th>
                            <th>Performed By</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.recent_maintenance.length > 0 ? data.recent_maintenance.map(record => `
                            <tr>
                                <td class="font-mono">${record.record_id}</td>
                                <td>
                                    <div class="font-semibold">${record.asset.name}</div>
                                    <div class="text-sm text-gray-500">${record.asset.alms_id}</div>
                                </td>
                                <td>${new Date(record.performed_date).toLocaleDateString()}</td>
                                <td class="font-mono">${record.cost ? '₱' + parseFloat(record.cost).toLocaleString('en-PH', { minimumFractionDigits: 2 }) : 'N/A'}</td>
                                <td>${record.description || 'No description'}</td>
                                <td>${record.performed_by || 'N/A'}</td>
                            </tr>
                        `).join('') : `
                            <tr>
                                <td colspan="6" class="text-center py-4 text-gray-500">No recent maintenance records</td>
                            </tr>
                        `}
                    </tbody>
                </table>
            </div>
        </div>
    `;
}

function displayAssetStatusReport(data) {
    const reportSection = document.getElementById('assetStatusReport');
    
    reportSection.innerHTML = `
        <div class="bg-base-100 rounded-lg p-6 shadow mb-6">
            <h3 class="text-xl font-bold mb-4">Asset Status Distribution</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                ${data.map(status => `
                    <div class="stat">
                        <div class="stat-figure ${getStatusColor(status.status)}">
                            <i class="bx ${getStatusIcon(status.status)} text-2xl"></i>
                        </div>
                        <div class="stat-title">${getStatusText(status.status)}</div>
                        <div class="stat-value ${getStatusColor(status.status)}">${status.count}</div>
                    </div>
                `).join('')}
            </div>
        </div>
        
        <div class="bg-base-100 rounded-lg p-6 shadow">
            <h3 class="text-xl font-bold mb-4">Status Breakdown</h3>
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th>Status</th>
                            <th>Count</th>
                            <th>Percentage</th>
                            <th>Trend</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.map(status => {
                            const total = data.reduce((sum, s) => sum + s.count, 0);
                            const percentage = ((status.count / total) * 100).toFixed(1);
                            return `
                                <tr>
                                    <td>
                                        <span class="badge ${getStatusBadgeClass(status.status)}">${getStatusText(status.status)}</span>
                                    </td>
                                    <td class="font-semibold">${status.count}</td>
                                    <td>${percentage}%</td>
                                    <td>
                                        <div class="w-32 bg-gray-200 rounded-full h-3">
                                            <div class="h-3 rounded-full ${getStatusColor(status.status)}" style="width: ${percentage}%"></div>
                                        </div>
                                    </td>
                                </tr>
                            `;
                        }).join('')}
                    </tbody>
                </table>
            </div>
        </div>
    `;
}

function displayMaintenanceAnalyticsReport(data) {
    const reportSection = document.getElementById('maintenanceAnalyticsReport');
    
    reportSection.innerHTML = `
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <div class="stat bg-base-100 rounded-lg p-6 shadow">
                <div class="stat-figure text-primary">
                    <i class="bx bx-wrench text-2xl"></i>
                </div>
                <div class="stat-title">Total Maintenance</div>
                <div class="stat-value text-primary">${data.total_maintenance}</div>
            </div>
            
            <div class="stat bg-base-100 rounded-lg p-6 shadow">
                <div class="stat-figure text-warning">
                    <i class="bx bx-dollar-circle text-2xl"></i>
                </div>
                <div class="stat-title">Total Maintenance Cost</div>
                <div class="stat-value text-warning">₱${parseFloat(data.total_maintenance_cost || 0).toLocaleString('en-PH', { minimumFractionDigits: 2 })}</div>
            </div>
            
            <div class="stat bg-base-100 rounded-lg p-6 shadow">
                <div class="stat-figure text-info">
                    <i class="bx bx-calculator text-2xl"></i>
                </div>
                <div class="stat-title">Average Cost</div>
                <div class="stat-value text-info">₱${parseFloat(data.avg_maintenance_cost || 0).toLocaleString('en-PH', { minimumFractionDigits: 2 })}</div>
            </div>
        </div>
        
        <div class="bg-base-100 rounded-lg p-6 shadow">
            <h3 class="text-xl font-bold mb-4">Maintenance by Month</h3>
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th>Month</th>
                            <th>Maintenance Count</th>
                            <th>Total Cost</th>
                            <th>Average Cost</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.maintenance_by_month && data.maintenance_by_month.length > 0 ? data.maintenance_by_month.map(month => {
                            const monthName = new Date(month.year, month.month - 1).toLocaleString('default', { month: 'long', year: 'numeric' });
                            const avgCost = month.total_cost / month.count;
                            return `
                                <tr>
                                    <td class="font-semibold">${monthName}</td>
                                    <td>${month.count}</td>
                                    <td class="font-mono">₱${parseFloat(month.total_cost || 0).toLocaleString('en-PH', { minimumFractionDigits: 2 })}</td>
                                    <td class="font-mono">₱${parseFloat(avgCost || 0).toLocaleString('en-PH', { minimumFractionDigits: 2 })}</td>
                                </tr>
                            `;
                        }).join('') : `
                            <tr>
                                <td colspan="4" class="text-center py-4 text-gray-500">No maintenance data available</td>
                            </tr>
                        `}
                    </tbody>
                </table>
            </div>
        </div>
    `;
}

function displayCostAnalysisReport(data) {
    const reportSection = document.getElementById('costAnalysisReport');
    
    reportSection.innerHTML = `
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <div class="stat bg-base-100 rounded-lg p-6 shadow">
                <div class="stat-figure text-primary">
                    <i class="bx bx-purchase-tag text-2xl"></i>
                </div>
                <div class="stat-title">Total Acquisition Cost</div>
                <div class="stat-value text-primary">₱${parseFloat(data.total_acquisition_cost || 0).toLocaleString('en-PH', { minimumFractionDigits: 2 })}</div>
            </div>
            
            <div class="stat bg-base-100 rounded-lg p-6 shadow">
                <div class="stat-figure text-warning">
                    <i class="bx bx-wrench text-2xl"></i>
                </div>
                <div class="stat-title">Total Maintenance Cost</div>
                <div class="stat-value text-warning">₱${parseFloat(data.total_maintenance_cost || 0).toLocaleString('en-PH', { minimumFractionDigits: 2 })}</div>
            </div>
            
            <div class="stat bg-base-100 rounded-lg p-6 shadow">
                <div class="stat-figure text-success">
                    <i class="bx bx-dollar-circle text-2xl"></i>
                </div>
                <div class="stat-title">Total Recovered Value</div>
                <div class="stat-value text-success">₱${parseFloat(data.total_disposal_value || 0).toLocaleString('en-PH', { minimumFractionDigits: 2 })}</div>
            </div>
        </div>
        
        <div class="bg-base-100 rounded-lg p-6 shadow">
            <h3 class="text-xl font-bold mb-4">Cost by Category</h3>
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th>Category</th>
                            <th>Total Acquisition Cost</th>
                            <th>Percentage</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data.cost_by_category && data.cost_by_category.length > 0 ? data.cost_by_category.map(category => {
                            const percentage = ((category.total_cost / data.total_acquisition_cost) * 100).toFixed(1);
                            return `
                                <tr>
                                    <td class="font-semibold">${category.category}</td>
                                    <td class="font-mono">₱${parseFloat(category.total_cost || 0).toLocaleString('en-PH', { minimumFractionDigits: 2 })}</td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <span>${percentage}%</span>
                                            <div class="w-32 bg-gray-200 rounded-full h-3">
                                                <div class="bg-primary h-3 rounded-full" style="width: ${percentage}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            `;
                        }).join('') : `
                            <tr>
                                <td colspan="3" class="text-center py-4 text-gray-500">No cost data available</td>
                            </tr>
                        `}
                    </tbody>
                </table>
            </div>
        </div>
    `;
}

function displayBranchAssetsReport(data) {
    const reportSection = document.getElementById('branchAssetsReport');
    
    reportSection.innerHTML = `
        <div class="bg-base-100 rounded-lg p-6 shadow">
            <h3 class="text-xl font-bold mb-4">Assets by Branch</h3>
            <div class="overflow-x-auto">
                <table class="table table-zebra w-full">
                    <thead>
                        <tr>
                            <th>Branch</th>
                            <th>Asset Count</th>
                            <th>Total Value</th>
                            <th>Average Value per Asset</th>
                            <th>Distribution</th>
                        </tr>
                    </thead>
                    <tbody>
                        ${data && data.length > 0 ? data.map(branch => {
                            const avgValue = branch.total_value / branch.asset_count;
                            const totalAssets = data.reduce((sum, b) => sum + b.asset_count, 0);
                            const percentage = ((branch.asset_count / totalAssets) * 100).toFixed(1);
                            return `
                                <tr>
                                    <td class="font-semibold">${branch.branch}</td>
                                    <td>${branch.asset_count}</td>
                                    <td class="font-mono">₱${parseFloat(branch.total_value || 0).toLocaleString('en-PH', { minimumFractionDigits: 2 })}</td>
                                    <td class="font-mono">₱${parseFloat(avgValue || 0).toLocaleString('en-PH', { minimumFractionDigits: 2 })}</td>
                                    <td>
                                        <div class="flex items-center gap-2">
                                            <span>${percentage}%</span>
                                            <div class="w-32 bg-gray-200 rounded-full h-3">
                                                <div class="bg-primary h-3 rounded-full" style="width: ${percentage}%"></div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            `;
                        }).join('') : `
                            <tr>
                                <td colspan="5" class="text-center py-4 text-gray-500">No branch asset data available</td>
                            </tr>
                        `}
                    </tbody>
                </table>
            </div>
        </div>
    `;
}

// ==================== UTILITY FUNCTIONS ====================
function getStatusColor(status) {
    const colors = {
        'active': 'text-success',
        'in_maintenance': 'text-warning',
        'disposed': 'text-error'
    };
    return colors[status] || 'text-primary';
}

function getStatusIcon(status) {
    const icons = {
        'active': 'bx-check-circle',
        'in_maintenance': 'bx-wrench',
        'disposed': 'bx-trash'
    };
    return icons[status] || 'bx-cube';
}

function getStatusText(status) {
    const texts = {
        'active': 'Active',
        'in_maintenance': 'In Maintenance',
        'disposed': 'Disposed'
    };
    return texts[status] || status;
}

function getStatusBadgeClass(status) {
    const classes = {
        'active': 'badge-success',
        'in_maintenance': 'badge-warning',
        'disposed': 'badge-error'
    };
    return classes[status] || 'badge-neutral';
}

function setDefaultDateRange() {
    const today = new Date();
    const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
    
    document.getElementById('dateFrom').value = firstDay.toISOString().split('T')[0];
    document.getElementById('dateTo').value = today.toISOString().split('T')[0];
}

function applyDateFilter() {
    loadReport(currentReportType);
}

function clearDateFilter() {
    setDefaultDateRange();
    loadReport(currentReportType);
}

function exportToPDF() {
    showLoading();
    // Simulate PDF export
    setTimeout(() => {
        hideLoading();
        showSuccess('PDF export started. Check your downloads.');
    }, 1000);
}

function exportToExcel() {
    showLoading();
    // Simulate Excel export
    setTimeout(() => {
        hideLoading();
        showSuccess('Excel export started. Check your downloads.');
    }, 1000);
}

function showLoading() {
    document.getElementById('loadingState').classList.remove('hidden');
    document.getElementById('loadingToast').classList.remove('hidden');
}

function hideLoading() {
    document.getElementById('loadingState').classList.add('hidden');
    document.getElementById('loadingToast').classList.add('hidden');
}

function showSuccess(message) {
    Swal.fire({
        icon: 'success',
        title: 'Success',
        text: message,
        timer: 2000,
        showConfirmButton: false
    });
}

function showError(message) {
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: message
    });
}
</script>

<style>
.report-section {
    animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.stat {
    padding: 1rem;
    border-radius: 0.5rem;
    background: white;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
}

.stat-value {
    font-size: 2rem;
    font-weight: bold;
    margin-top: 0.25rem;
}

.stat-title {
    font-size: 0.875rem;
    color: #6b7280;
    text-transform: uppercase;
    font-weight: 600;
}

.stat-figure {
    margin-bottom: 0.5rem;
}
</style>
@endsection