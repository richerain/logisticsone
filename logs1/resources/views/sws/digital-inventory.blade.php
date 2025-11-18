<!-- resources/views/sws/digital-inventory.blade.php -->
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bxs-archive-in'></i>Digital Inventory</h2>
    </div>
    <div class="text-right">
        <span class="text-md text-gray-600">Smart Warehousing System</span>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">Inventory Overview</h3> 
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="inventory-section">
            <h4 class="font-semibold mb-4">Stock Levels by Category</h4>
            <div class="space-y-3">
                <div>
                    <div class="flex justify-between mb-1">
                        <span>Equipment</span>
                        <span>65%</span>
                    </div>
                    <progress class="progress progress-primary w-full" value="65" max="100"></progress>
                </div>
                <div>
                    <div class="flex justify-between mb-1">
                        <span>Supplies</span>
                        <span>45%</span>
                    </div>
                    <progress class="progress progress-warning w-full" value="45" max="100"></progress>
                </div>
                <div>
                    <div class="flex justify-between mb-1">
                        <span>Furniture</span>
                        <span>80%</span>
                    </div>
                    <progress class="progress progress-success w-full" value="80" max="100"></progress>
                </div>
                <div>
                    <div class="flex justify-between mb-1">
                        <span>Automotive</span>
                        <span>50%</span>
                    </div>
                    <progress class="progress progress-secondary w-full" value="50" max="100"></progress>
                </div>
            </div>
        </div>

        <div class="inventory-section">
            <h4 class="font-semibold mb-4">Quick Actions</h4>
            <div class="grid grid-cols-2 gap-3">
                <button class="btn btn-outline">
                    <i class='bx bx-search mr-2'></i>Search Item
                </button>
                <button class="btn btn-outline">
                    <i class='bx bx-transfer mr-2'></i>Transfer
                </button>
                <button class="btn btn-outline">
                    <i class='bx bx-barcode mr-2'></i>Scan Barcode
                </button>
                <button class="btn btn-outline">
                    <i class='bx bx-report mr-2'></i>Generate Report
                </button>
            </div>
        </div>
    </div>
</div>
<!-- digital inventory main table area start -->
<div class="bg-white rounded-lg shadow-lg p-6 mt-5">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">Inventory</h3>
        <div class="flex space-x-2">
            <button class="btn btn-success"><!-- capable of requesting to buy new items in psm -->
                <i class='bx bxs-purchase-tag mr-2'></i>Purchase New Item
            </button>
            <button class="btn btn-primary">
                <i class='bx bxs-down-arrow-square mr-2'></i></i>Inventory New Item
            </button>
        </div>
    </div>

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
<!-- digital inventory main table area start -->