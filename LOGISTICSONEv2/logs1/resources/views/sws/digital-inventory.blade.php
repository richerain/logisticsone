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
        <div class="flex space-x-2">
            <button class="btn btn-outline">
                <i class='bx bx-export mr-2'></i>Export
            </button>
            <button class="btn btn-primary">
                <i class='bx bx-plus mr-2'></i>Add Item
            </button>
        </div>
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