<!-- resources/views/psm/request-purchases.blade.php -->
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bx-clipboard'></i>Request Purchases</h2>
    </div>
    <div class="text-right">
        <span class="text-md text-gray-600">Procurement & Sourcing Management</span>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg p-6">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">Purchase Requests</h3>
        <div class="flex gap-3">
            <button id="addRequestBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
                <i class='bx bx-plus'></i>
                New Purchase Request
            </button>
        </div>
    </div>

    <!-- Stats Section (Static Design) -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-gradient-to-r from-blue-50 to-blue-100 border border-blue-200 rounded-lg p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-blue-500 rounded-lg">
                    <i class='bx bx-file text-white text-2xl'></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-blue-700">Total Requests</p>
                    <p class="text-2xl font-bold text-blue-900">0</p>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-green-50 to-green-100 border border-green-200 rounded-lg p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-green-500 rounded-lg">
                    <i class='bx bx-check-circle text-white text-2xl'></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-green-700">Approved</p>
                    <p class="text-2xl font-bold text-green-900">0</p>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-yellow-50 to-yellow-100 border border-yellow-200 rounded-lg p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-500 rounded-lg">
                    <i class='bx bx-time text-white text-2xl'></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-yellow-700">Pending</p>
                    <p class="text-2xl font-bold text-yellow-900">0</p>
                </div>
            </div>
        </div>
        
        <div class="bg-gradient-to-r from-red-50 to-red-100 border border-red-200 rounded-lg p-4 hover:shadow-md transition-shadow">
            <div class="flex items-center">
                <div class="p-3 bg-red-500 rounded-lg">
                    <i class='bx bx-x-circle text-white text-2xl'></i>
                </div>
                <div class="ml-4">
                    <p class="text-sm font-medium text-red-700">Cancelled</p>
                    <p class="text-2xl font-bold text-red-900">0</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Placeholder Table -->
    <div class="overflow-x-auto border border-gray-100 rounded-xl">
        <table class="w-full text-left">
            <thead class="bg-gray-50 border-b border-gray-100">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Request ID</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Requester</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Date</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Department</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center gap-2">
                            <i class='bx bx-clipboard text-4xl text-gray-300'></i>
                            <p>No purchase requests found</p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
