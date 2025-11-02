<!-- resources/views/sws/inventory-flow.blade.php -->
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bx-sync'></i>Inventory Flow</h2>
    </div>
    <div class="text-right">
        <span class="text-md text-gray-600">Smart Warehousing System</span>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg p-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="stat-card bg-blue-50 p-4 rounded-lg text-center">
            <i class='bx bx-package text-3xl text-blue-600 mb-2'></i>
            <h3 class="font-semibold text-blue-800">Total Items</h3>
            <p class="text-2xl font-bold text-blue-600">1,247</p>
        </div>
        <div class="stat-card bg-green-50 p-4 rounded-lg text-center">
            <i class='bx bx-trending-up text-3xl text-green-600 mb-2'></i>
            <h3 class="font-semibold text-green-800">Incoming</h3>
            <p class="text-2xl font-bold text-green-600">45</p>
        </div>
        <div class="stat-card bg-yellow-50 p-4 rounded-lg text-center">
            <i class='bx bx-trending-down text-3xl text-yellow-600 mb-2'></i>
            <h3 class="font-semibold text-yellow-800">Outgoing</h3>
            <p class="text-2xl font-bold text-yellow-600">32</p>
        </div>
        <div class="stat-card bg-red-50 p-4 rounded-lg text-center">
            <i class='bx bx-error text-3xl text-red-600 mb-2'></i>
            <h3 class="font-semibold text-red-800">Low Stock</h3>
            <p class="text-2xl font-bold text-red-600">12</p>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="table table-zebra w-full">
            <thead>
                <tr class="bg-gray-700 font-bold text-white">
                    <th>Item Code</th>
                    <th>Product Name</th>
                    <th>Category</th>
                    <th>Current Stock</th>
                    <th>Min Stock</th>
                    <th>Status</th>
                    <th>Last Updated</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>ITM-001</td>
                    <td>Office Chair Executive</td>
                    <td>Furniture</td>
                    <td>25</td>
                    <td>10</td>
                    <td><span class="badge badge-success">In Stock</span></td>
                    <td>2025-01-15</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>