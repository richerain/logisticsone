<!-- resources/views/alms/asset-management.blade.php -->
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bxs-archive'></i>Asset Management</h2>
    </div>
    <div class="text-right">
        <span class="text-md text-gray-600">Asset Lifecycle & Maintenance</span>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg p-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="stat-card bg-blue-50 p-4 rounded-lg text-center">
            <i class='bx bx-cube text-3xl text-blue-600 mb-2'></i>
            <h3 class="font-semibold text-blue-800">Total Assets</h3>
            <p class="text-2xl font-bold text-blue-600">284</p>
        </div>
        <div class="stat-card bg-green-50 p-4 rounded-lg text-center">
            <i class='bx bx-check-shield text-3xl text-green-600 mb-2'></i>
            <h3 class="font-semibold text-green-800">Operational</h3>
            <p class="text-2xl font-bold text-green-600">245</p>
        </div>
        <div class="stat-card bg-yellow-50 p-4 rounded-lg text-center">
            <i class='bx bx-wrench text-3xl text-yellow-600 mb-2'></i>
            <h3 class="font-semibold text-yellow-800">Under Maintenance</h3>
            <p class="text-2xl font-bold text-yellow-600">18</p>
        </div>
        <div class="stat-card bg-red-50 p-4 rounded-lg text-center">
            <i class='bx bx-error text-3xl text-red-600 mb-2'></i>
            <h3 class="font-semibold text-red-800">Out of Service</h3>
            <p class="text-2xl font-bold text-red-600">21</p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="table table-zebra w-full">
            <thead>
                <tr class="bg-gray-100">
                    <th>Asset ID</th>
                    <th>Asset Name</th>
                    <th>Category</th>
                    <th>Location</th>
                    <th>Status</th>
                    <th>Last Maintenance</th>
                    <th>Next Maintenance</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>AST-001</td>
                    <td>Forklift TCM FD30</td>
                    <td>Heavy Equipment</td>
                    <td>Warehouse A</td>
                    <td><span class="badge badge-success">Operational</span></td>
                    <td>2025-01-10</td>
                    <td>2025-04-10</td>
                    <td>
                        <button class="btn btn-sm btn-outline">View</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>