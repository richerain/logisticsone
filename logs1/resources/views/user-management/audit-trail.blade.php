<!-- resources/views/user-management/audit-trail.blade.php -->
 <div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bx-history'></i>Audit Trail</h2>
    </div>
    <div class="text-right">
        <span class="text-md text-gray-600">User Management</span>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">System Logs Overview</h3>
        <div class="flex gap-2 whitespace-nowrap">
            <button id="exportLogsBtn" title="Export Logs" class="bg-success hover:bg-green-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 whitespace-nowrap">
                <i class='bx bxs-file-export'></i> Export
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-blue-100 border border-blue-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-3 bg-blue-500 rounded-lg"><i class='bx bx-list-ul text-white text-2xl'></i></div>
                <div class="ml-4"><p class="text-sm font-medium text-blue-700">Total Logs</p><p id="statTotalLogs" class="text-2xl font-bold text-blue-900">0</p></div>
            </div>
        </div>
        <div class="bg-green-100 border border-green-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-3 bg-green-500 rounded-lg"><i class='bx bx-calendar-check text-white text-2xl'></i></div>
                <div class="ml-4"><p class="text-sm font-medium text-green-700">Today</p><p id="statTodayLogs" class="text-2xl font-bold text-green-900">0</p></div>
            </div>
        </div>
        <div class="bg-yellow-100 border border-yellow-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-500 rounded-lg"><i class='bx bx-error text-white text-2xl'></i></div>
                <div class="ml-4"><p class="text-sm font-medium text-yellow-700">Warnings</p><p id="statWarningLogs" class="text-2xl font-bold text-yellow-900">0</p></div>
            </div>
        </div>
        <div class="bg-red-100 border border-red-200 rounded-lg p-4">
            <div class="flex items-center">
                <div class="p-3 bg-red-500 rounded-lg"><i class='bx bx-x-circle text-white text-2xl'></i></div>
                <div class="ml-4"><p class="text-sm font-medium text-red-700">Errors</p><p id="statErrorLogs" class="text-2xl font-bold text-red-900">0</p></div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-800 font-bold text-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider whitespace-nowrap">Log ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider whitespace-nowrap">User</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider whitespace-nowrap">Action</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider whitespace-nowrap">Module</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider whitespace-nowrap">IP Address</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider whitespace-nowrap">Date/Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider whitespace-nowrap">Details</th>
                    </tr>
                </thead>
                <tbody id="logsTableBody" class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                            <div class="flex justify-center items-center py-4">
                                <i class='bx bx-info-circle mr-2'></i> No logs found (Module Under Construction)
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div id="logPager" class="flex items-center justify-between mt-3">
        <div id="logPagerInfo" class="text-sm text-gray-600"></div>
        <div class="join">
            <button class="btn btn-sm join-item" id="logPrevBtn" data-action="prev" disabled>Prev</button>
            <span class="btn btn-sm join-item" id="logPageDisplay">1 / 1</span>
            <button class="btn btn-sm join-item" id="logNextBtn" data-action="next" disabled>Next</button>
        </div>
    </div>
</div>

<script>
    // Placeholder script for future implementation
    console.log('Audit Trail Module Loaded');
</script>
