<!-- resources/views/psm/vendor-info.blade.php -->
<div class="container mx-auto px-4 py-6">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Vendor Information</h1>
        <button class="btn btn-primary" id="editVendorInfo">
            <i class='bx bx-edit mr-2'></i>Edit Information
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Vendor Details Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-700 border-b pb-2">Company Information</h2>
            <div class="space-y-4">
                <div class="flex items-center">
                    <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center mr-4">
                        <i class='bx bxs-building text-3xl text-gray-500'></i>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-800">Tech Solutions Inc.</h3>
                        <p class="text-gray-600">Vendor ID: VEND-001</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Company Name</label>
                        <p class="mt-1 text-gray-900">Tech Solutions Inc.</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Business Type</label>
                        <p class="mt-1 text-gray-900">IT Equipment & Supplies</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Tax Identification Number</label>
                        <p class="mt-1 text-gray-900">123-456-789-000</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Years in Business</label>
                        <p class="mt-1 text-gray-900">5 years</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Contact Information Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-700 border-b pb-2">Contact Information</h2>
            <div class="space-y-4">
                <div class="grid grid-cols-1 gap-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Contact Person</label>
                        <p class="mt-1 text-gray-900">Juan Dela Cruz</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Email Address</label>
                        <p class="mt-1 text-gray-900">{{ auth()->guard('sws')->user()->email }}</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Phone Number</label>
                        <p class="mt-1 text-gray-900">+63 912 345 6789</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600">Office Address</label>
                        <p class="mt-1 text-gray-900">123 Business Ave, Makati City, Metro Manila</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Business Statistics Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-700 border-b pb-2">Business Statistics</h2>
            <div class="grid grid-cols-2 gap-4">
                <div class="text-center p-4 bg-green-50 rounded-lg">
                    <i class='bx bxs-package text-2xl text-green-600 mb-2'></i>
                    <p class="text-2xl font-bold text-green-700">45</p>
                    <p class="text-sm text-green-600">Active Products</p>
                </div>
                <div class="text-center p-4 bg-blue-50 rounded-lg">
                    <i class='bx bxs-quote-alt-left text-2xl text-blue-600 mb-2'></i>
                    <p class="text-2xl font-bold text-blue-700">128</p>
                    <p class="text-sm text-blue-600">Total Quotes</p>
                </div>
                <div class="text-center p-4 bg-purple-50 rounded-lg">
                    <i class='bx bxs-check-circle text-2xl text-purple-600 mb-2'></i>
                    <p class="text-2xl font-bold text-purple-700">89</p>
                    <p class="text-sm text-purple-600">Approved Quotes</p>
                </div>
                <div class="text-center p-4 bg-orange-50 rounded-lg">
                    <i class='bx bxs-star text-2xl text-orange-600 mb-2'></i>
                    <p class="text-2xl font-bold text-orange-700">4.8</p>
                    <p class="text-sm text-orange-600">Rating</p>
                </div>
            </div>
        </div>

        <!-- Account Status Card -->
        <div class="bg-white rounded-lg shadow-md p-6">
            <h2 class="text-lg font-semibold mb-4 text-gray-700 border-b pb-2">Account Status</h2>
            <div class="space-y-4">
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Account Status</span>
                    <span class="badge badge-success">Active</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Verification Status</span>
                    <span class="badge badge-success">Verified</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Member Since</span>
                    <span class="text-gray-900">January 15, 2023</span>
                </div>
                <div class="flex justify-between items-center">
                    <span class="text-gray-600">Last Updated</span>
                    <span class="text-gray-900">December 1, 2024</span>
                </div>
            </div>
            
            <div class="mt-6 p-4 bg-yellow-50 rounded-lg">
                <div class="flex">
                    <i class='bx bxs-info-circle text-yellow-600 text-xl mr-2'></i>
                    <div>
                        <h4 class="font-medium text-yellow-800">Profile Completion</h4>
                        <p class="text-sm text-yellow-700">Your profile is 85% complete. Complete your profile to increase trust score.</p>
                        <div class="w-full bg-yellow-200 rounded-full h-2 mt-2">
                            <div class="bg-yellow-600 h-2 rounded-full" style="width: 85%"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Documents Section -->
    <div class="mt-6 bg-white rounded-lg shadow-md p-6">
        <h2 class="text-lg font-semibold mb-4 text-gray-700 border-b pb-2">Business Documents</h2>
        <div class="overflow-x-auto">
            <table class="table table-zebra w-full">
                <thead>
                    <tr class="bg-gray-50">
                        <th class="px-4 py-2 text-left">Document Type</th>
                        <th class="px-4 py-2 text-left">File Name</th>
                        <th class="px-4 py-2 text-left">Upload Date</th>
                        <th class="px-4 py-2 text-left">Status</th>
                        <th class="px-4 py-2 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="px-4 py-2">Business Permit</td>
                        <td class="px-4 py-2">business_permit_2024.pdf</td>
                        <td class="px-4 py-2">Nov 15, 2024</td>
                        <td class="px-4 py-2"><span class="badge badge-success">Approved</span></td>
                        <td class="px-4 py-2">
                            <button class="btn btn-sm btn-outline btn-primary">
                                <i class='bx bx-download'></i> Download
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td class="px-4 py-2">Tax Clearance</td>
                        <td class="px-4 py-2">tax_clearance_2024.pdf</td>
                        <td class="px-4 py-2">Nov 10, 2024</td>
                        <td class="px-4 py-2"><span class="badge badge-success">Approved</span></td>
                        <td class="px-4 py-2">
                            <button class="btn btn-sm btn-outline btn-primary">
                                <i class='bx bx-download'></i> Download
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const editButton = document.getElementById('editVendorInfo');
    
    editButton.addEventListener('click', function() {
        // Show edit modal or redirect to edit page
        alert('Edit vendor information functionality will be implemented here.');
    });
});
</script>