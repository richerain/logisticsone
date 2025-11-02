<!-- resources/views/psm/vendor-quote.blade.php -->
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bxs-quote-single-left'></i>Vendor Quote Management</h2>
    </div>
    <div class="text-right">
        <span class="text-md text-gray-600">Procurement & Sourcing Management</span>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg p-6">
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="stat-card bg-blue-50 p-4 rounded-lg">
            <h3 class="font-semibold text-blue-800">Pending Quotes</h3>
            <p class="text-2xl font-bold text-blue-600">12</p>
        </div>
        <div class="stat-card bg-green-50 p-4 rounded-lg">
            <h3 class="font-semibold text-green-800">Approved Quotes</h3>
            <p class="text-2xl font-bold text-green-600">8</p>
        </div>
        <div class="stat-card bg-red-50 p-4 rounded-lg">
            <h3 class="font-semibold text-red-800">Rejected Quotes</h3>
            <p class="text-2xl font-bold text-red-600">3</p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="table table-zebra w-full">
            <thead>
                <tr class="bg-gray-700 font-bold text-white">
                    <th>Quote ID</th>
                    <th>Vendor</th>
                    <th>Product</th>
                    <th>Quote Amount</th>
                    <th>Validity</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>QT-2025-001</td>
                    <td>ABC Suppliers</td>
                    <td>Office Chairs</td>
                    <td>₱15,000.00</td>
                    <td>2025-02-15</td>
                    <td><span class="badge badge-warning">Pending Review</span></td>
                    <td>
                        <button class="btn btn-sm btn-primary">Review</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>