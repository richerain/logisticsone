<!-- resources/views/psm/product-management.blade.php -->
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Product Management</h1>
        <div class="text-right">
            <span class="text-md text-gray-600">Welcome back, {{ Auth::guard('sws')->user()->firstname }} - {{ ucfirst(Auth::guard('sws')->user()->roles) }}</span>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-md p-6">
        <div class="overflow-x-auto">
            <div class="flex justify-between items-center mb-4">
                <h1 class="text-lg font-semibold mb-4">Product List</h1>
                <button class="btn btn-primary">
                    <i class='bx bx-plus mr-2'></i>Add Product
                </button>
            </div>
            <table class="table table-sm table-zebra w-full">
                <thead>
                    <tr class="bg-gray-700 font-bold text-white">
                        <th class="px-4 py-2 text-left">Product ID</th>
                        <th class="px-4 py-2 text-left">Product Name</th>
                        <th class="px-4 py-2 text-left">Category</th>
                        <th class="px-4 py-2 text-left">Price</th>
                        <th class="px-4 py-2 text-left">Stock</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="px-4 py-2">PROD-001</td>
                        <td class="px-4 py-2">Laptop Computer</td>
                        <td class="px-4 py-2">Electronics</td>
                        <td class="px-4 py-2">₱45,000.00</td>
                        <td class="px-4 py-2">25</td>
                        <td class="px-4 py-2"><span class="badge badge-success">Active</span></td>
                        <td class="px-4 py-2">
                            <div class="flex space-x-2">
                                <button class="btn btn-sm btn-outline btn-primary">
                                    edit
                                </button>
                                <button class="btn btn-sm btn-outline btn-error">
                                    delete
                                </button>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2">PROD-002</td>
                        <td class="px-4 py-2">Office Chair</td>
                        <td class="px-4 py-2">Furniture</td>
                        <td class="px-4 py-2">₱3,500.00</td>
                        <td class="px-4 py-2">50</td>
                        <td class="px-4 py-2"><span class="badge badge-success">Active</span></td>
                        <td class="px-4 py-2">
                            <div class="flex space-x-2">
                                <button class="btn btn-sm btn-outline btn-primary">
                                    edit
                                </button>
                                <button class="btn btn-sm btn-outline btn-error">
                                    delete
                                </button>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <div class="mt-4 flex justify-between items-center">
            <div class="text-sm text-gray-600">
                Showing 1 to 2 of 2 entries
            </div>
            <div class="join">
                <button class="join-item btn btn-sm">
                    <i class='bx bxs-chevrons-left'></i>
                </button>
                <button class="join-item btn btn-sm">1</button>
                <button class="join-item btn btn-sm">
                    <i class='bx bxs-chevrons-right'></i>
                </button>
            </div>
        </div>
    </div>
</div>