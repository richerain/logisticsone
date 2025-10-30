<!-- resources/views/alms/maintenance-management.blade.php -->
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bxs-wrench'></i>Maintenance Management</h2>
    </div>
    <div class="text-right">
        <span class="text-md text-gray-600">Asset Lifecycle & Maintenance</span>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">Maintenance Schedule</h3>
        <button class="btn btn-primary">
            <i class='bx bx-plus mr-2'></i>Schedule Maintenance
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="table table-zebra w-full">
            <thead>
                <tr class="bg-gray-100">
                    <th>Work Order #</th>
                    <th>Asset</th>
                    <th>Maintenance Type</th>
                    <th>Scheduled Date</th>
                    <th>Technician</th>
                    <th>Status</th>
                    <th>Priority</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>WO-2025-001</td>
                    <td>Forklift TCM FD30</td>
                    <td>Preventive</td>
                    <td>2025-02-01</td>
                    <td>John Smith</td>
                    <td><span class="badge badge-warning">Scheduled</span></td>
                    <td><span class="badge badge-success">Medium</span></td>
                    <td>
                        <button class="btn btn-sm btn-primary">Update</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>