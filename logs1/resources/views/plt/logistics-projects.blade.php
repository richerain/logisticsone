<!-- resources/views/plt/logistics-projects.blade.php -->
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bxs-package'></i>Logistics Projects</h2>
    </div>
    <div class="text-right">
        <span class="text-md text-gray-600">Project Logistics Tracker</span>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg p-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="stat-card bg-blue-50 p-4 rounded-lg text-center">
            <i class='bx bx-clipboard text-3xl text-blue-600 mb-2'></i>
            <h3 class="font-semibold text-blue-800">Total Projects</h3>
            <p class="text-2xl font-bold text-blue-600">15</p>
        </div>
        <div class="stat-card bg-green-50 p-4 rounded-lg text-center">
            <i class='bx bx-check-circle text-3xl text-green-600 mb-2'></i>
            <h3 class="font-semibold text-green-800">Completed</h3>
            <p class="text-2xl font-bold text-green-600">8</p>
        </div>
        <div class="stat-card bg-yellow-50 p-4 rounded-lg text-center">
            <i class='bx bx-time text-3xl text-yellow-600 mb-2'></i>
            <h3 class="font-semibold text-yellow-800">In Progress</h3>
            <p class="text-2xl font-bold text-yellow-600">5</p>
        </div>
        <div class="stat-card bg-red-50 p-4 rounded-lg text-center">
            <i class='bx bx-error-circle text-3xl text-red-600 mb-2'></i>
            <h3 class="font-semibold text-red-800">Delayed</h3>
            <p class="text-2xl font-bold text-red-600">2</p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="table table-zebra w-full">
            <thead>
                <tr class="bg-gray-700 font-bold text-white">
                    <th>Project ID</th>
                    <th>Project Name</th>
                    <th>Client</th>
                    <th>Start Date</th>
                    <th>End Date</th>
                    <th>Status</th>
                    <th>Progress</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>PRJ-2025-001</td>
                    <td>Warehouse Relocation</td>
                    <td>ABC Corporation</td>
                    <td>2025-01-10</td>
                    <td>2025-03-15</td>
                    <td><span class="badge badge-warning">In Progress</span></td>
                    <td>
                        <progress class="progress progress-warning w-24" value="65" max="100"></progress>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline">View Details</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>