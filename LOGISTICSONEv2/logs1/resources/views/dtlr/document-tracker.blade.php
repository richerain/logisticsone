<!-- resources/views/dtlr/document-tracker.blade.php -->
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bxs-file'></i>Document Tracker</h2>
    </div>
    <div class="text-right">
        <span class="text-md text-gray-600">Document Tracking & Logistics Record</span>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg p-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="stat-card bg-blue-50 p-4 rounded-lg text-center">
            <i class='bx bx-file text-3xl text-blue-600 mb-2'></i>
            <h3 class="font-semibold text-blue-800">Total Documents</h3>
            <p class="text-2xl font-bold text-blue-600">1,842</p>
        </div>
        <div class="stat-card bg-green-50 p-4 rounded-lg text-center">
            <i class='bx bx-check-circle text-3xl text-green-600 mb-2'></i>
            <h3 class="font-semibold text-green-800">Approved</h3>
            <p class="text-2xl font-bold text-green-600">1,245</p>
        </div>
        <div class="stat-card bg-yellow-50 p-4 rounded-lg text-center">
            <i class='bx bx-time text-3xl text-yellow-600 mb-2'></i>
            <h3 class="font-semibold text-yellow-800">Pending Review</h3>
            <p class="text-2xl font-bold text-yellow-600">387</p>
        </div>
        <div class="stat-card bg-red-50 p-4 rounded-lg text-center">
            <i class='bx bx-error-circle text-3xl text-red-600 mb-2'></i>
            <h3 class="font-semibold text-red-800">Rejected</h3>
            <p class="text-2xl font-bold text-red-600">210</p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="table table-zebra w-full">
            <thead>
                <tr class="bg-gray-100">
                    <th>Document ID</th>
                    <th>Document Type</th>
                    <th>Title</th>
                    <th>Owner</th>
                    <th>Created Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>DOC-2025-001</td>
                    <td>Purchase Order</td>
                    <td>PO for Office Supplies</td>
                    <td>John Doe</td>
                    <td>2025-01-15</td>
                    <td><span class="badge badge-success">Approved</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline mr-2">View</button>
                        <button class="btn btn-sm btn-primary">Track</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>