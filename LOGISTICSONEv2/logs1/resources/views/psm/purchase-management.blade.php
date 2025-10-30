<!-- resources/views/psm/purchase-management.blade.php -->
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bxs-purchase-tag'></i>Purchase Management</h2>
    </div>
    <div class="text-right">
        <span class="text-md text-gray-600">Procurement & Sourcing Management</span>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg p-6 mb-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">Purchase Orders</h3>
        <button class="btn btn-primary">
            <i class='bx bx-plus mr-2'></i>New Purchase Order
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="table table-zebra w-full">
            <thead>
                <tr class="bg-gray-100">
                    <th class="font-semibold">PO Number</th>
                    <th class="font-semibold">Vendor</th>
                    <th class="font-semibold">Amount</th>
                    <th class="font-semibold">Status</th>
                    <th class="font-semibold">Date</th>
                    <th class="font-semibold">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>PO-2025-001</td>
                    <td>ABC Suppliers</td>
                    <td>₱25,000.00</td>
                    <td><span class="badge badge-success">Approved</span></td>
                    <td>2025-01-15</td>
                    <td>
                        <button class="btn btn-sm btn-outline mr-2">View</button>
                        <button class="btn btn-sm btn-primary">Edit</button>
                    </td>
                </tr>
                <tr>
                    <td>PO-2025-002</td>
                    <td>XYZ Corporation</td>
                    <td>₱18,500.00</td>
                    <td><span class="badge badge-warning">Pending</span></td>
                    <td>2025-01-16</td>
                    <td>
                        <button class="btn btn-sm btn-outline mr-2">View</button>
                        <button class="btn btn-sm btn-primary">Edit</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>