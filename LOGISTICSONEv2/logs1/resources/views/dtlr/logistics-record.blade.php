<!-- resources/views/dtlr/logistics-record.blade.php -->
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bxs-package'></i>Logistics Record</h2>
    </div>
    <div class="text-right">
        <span class="text-md text-gray-600">Document Tracking & Logistics Record</span>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">Shipment Records</h3>
        <div class="flex space-x-2">
            <button class="btn btn-outline">
                <i class='bx bx-filter mr-2'></i>Filter
            </button>
            <button class="btn btn-primary">
                <i class='bx bx-plus mr-2'></i>New Record
            </button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="table table-zebra w-full">
            <thead>
                <tr class="bg-gray-100">
                    <th>Shipment ID</th>
                    <th>Origin</th>
                    <th>Destination</th>
                    <th>Carrier</th>
                    <th>Status</th>
                    <th>Estimated Delivery</th>
                    <th>Actual Delivery</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>SH-2025-001</td>
                    <td>Manila Warehouse</td>
                    <td>Cebu Distribution Center</td>
                    <td>LBC Express</td>
                    <td><span class="badge badge-success">Delivered</span></td>
                    <td>2025-01-18</td>
                    <td>2025-01-17</td>
                    <td>
                        <button class="btn btn-sm btn-outline">View Details</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>