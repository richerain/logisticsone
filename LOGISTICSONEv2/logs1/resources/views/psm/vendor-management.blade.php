<!-- resources/views/psm/vendor-management.blade.php -->
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bxs-user-detail'></i>Vendor Management</h2>
    </div>
    <div class="text-right">
        <span class="text-md text-gray-600">Procurement & Sourcing Management</span>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">Registered Vendors</h3>
        <button class="btn btn-primary">
            <i class='bx bx-plus mr-2'></i>Add Vendor
        </button>
    </div>

    <div class="overflow-x-auto">
        <table class="table table-zebra w-full">
            <thead>
                <tr class="bg-gray-700 font-bold text-white">
                    <th>Vendor ID</th>
                    <th>Company Name</th>
                    <th>Contact Person</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>V-001</td>
                    <td>ABC Suppliers Inc.</td>
                    <td>John Doe</td>
                    <td>john@abcsuppliers.com</td>
                    <td>+63 912 345 6789</td>
                    <td><span class="badge badge-success">Active</span></td>
                    <td>
                        <button class="btn btn-sm btn-outline mr-2">View</button>
                        <button class="btn btn-sm btn-primary">Edit</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>