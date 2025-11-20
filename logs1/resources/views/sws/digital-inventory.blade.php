<!-- resources/views/sws/digital-inventory.blade.php -->
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bxs-archive-in'></i>Digital Inventory</h2>
    </div>
    <div class="text-right">
        <span class="text-md text-gray-600">Smart Warehousing System</span>
    </div>
</div>
<!-- digital inventory section start -->
<div class="bg-white rounded-lg shadow-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">Inventory Overview</h3> 
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Stock Levels by Category section start -->
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
        <!-- Stock Levels by Category section end -->
        <!-- Quick Actions section start -->
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
        <!-- Quick Actions section end -->
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
    <!-- stats-card section start -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="stat-card bg-blue-50 p-4 rounded-lg text-center">
            <i class='bx bx-package text-3xl text-blue-600 mb-2'></i>
            <h3 class="font-semibold text-blue-800">Total data</h3>
            <p class="text-2xl font-bold text-blue-600">0</p>
        </div>
        <div class="stat-card bg-green-50 p-4 rounded-lg text-center">
            <i class='bx bx-package text-3xl text-green-600 mb-2'></i>
            <h3 class="font-semibold text-green-800">stat-card</h3>
            <p class="text-2xl font-bold text-green-600">0</p>
        </div>
        <div class="stat-card bg-yellow-50 p-4 rounded-lg text-center">
            <i class='bx bx-package text-3xl text-yellow-600 mb-2'></i>
            <h3 class="font-semibold text-yellow-800">stat-card</h3>
            <p class="text-2xl font-bold text-yellow-600">0</p>
        </div>
        <div class="stat-card bg-red-50 p-4 rounded-lg text-center">
            <i class='bx bx-package text-3xl text-red-600 mb-2'></i>
            <h3 class="font-semibold text-red-800">stat-card</h3>
            <p class="text-2xl font-bold text-red-600">0</p>
        </div>
    </div>
    <!-- stats-card section end -->
    <!-- digital inventory main table area section start -->
    <div class="overflow-x-auto">
        <table class="table table-zebra w-full rounded-lg">
            <thead>
                <tr class="bg-gray-700 font-bold text-white">
                    <th>column id</th>
                    <th>column</th>
                    <th>column</th>
                    <th>column</th>
                    <th>column</th>
                    <th>column Status</th>
                    <th>action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>data</td>
                    <td>data</td>
                    <td>data</td>
                    <td>data</td>
                    <td>data</td>
                    <td>data</td>
                    <td>data</td>
                </tr>
            </tbody>
        </table>
    </div>
    <!-- digital inventory main table area section end -->
</div>
<!-- digital inventory section end -->