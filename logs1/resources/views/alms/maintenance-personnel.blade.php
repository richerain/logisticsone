<!-- resources/views/alms/maintenance-personnel.blade.php -->
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bxs-user-badge'></i>Maintenance Personnel</h2>
    </div>
    <div class="text-right">
        <span class="text-md text-gray-600">Asset Lifecycle & Maintenance</span>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg p-6">
    <!-- Header Section -->
    <div class="flex justify-between items-center mb-6">
        <div class="flex items-center gap-3">
            <i class='bx bxs-group text-2xl text-gray-800'></i>
            <h3 class="text-lg font-bold text-gray-800 tracking-tight leading-none">Personnel List</h3>
        </div>
        <button class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-all shadow-md active:scale-95">
            <i class='bx bx-plus-circle text-lg'></i>
            Add Personnel
        </button>
    </div>

    <!-- Stats Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
        <div class="stat-card bg-blue-50 p-4 rounded-lg text-center border border-blue-100">
            <i class='bx bxs-user text-3xl text-blue-600 mb-2'></i>
            <h3 class="font-semibold text-blue-800">Total Personnel</h3>
            <p class="text-2xl font-bold text-blue-600">8</p>
        </div>
        <div class="stat-card bg-green-50 p-4 rounded-lg text-center border border-green-100">
            <i class='bx bxs-user-check text-3xl text-green-600 mb-2'></i>
            <h3 class="font-semibold text-green-800">Available (Free)</h3>
            <p class="text-2xl font-bold text-green-600">5</p>
        </div>
        <div class="stat-card bg-amber-50 p-4 rounded-lg text-center border border-amber-100">
            <i class='bx bxs-user-voice text-3xl text-amber-600 mb-2'></i>
            <h3 class="font-semibold text-amber-800">On Duty (Busy)</h3>
            <p class="text-2xl font-bold text-amber-600">3</p>
        </div>
    </div>

    <!-- Filters -->
    <div class="flex flex-col md:flex-row items-center justify-between gap-4 mb-6 bg-gray-50 p-4 rounded-xl border border-gray-100">
        <div class="relative w-full md:w-96">
            <i class='bx bx-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-lg'></i>
            <input type="text" placeholder="Search personnel name or specialization..." class="w-full pl-10 pr-4 py-2 bg-white border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 transition-all">
        </div>
        <div class="flex items-center gap-3 w-full md:w-auto">
            <select class="select select-bordered select-sm w-full md:w-40">
                <option value="all">All Specializations</option>
                <option value="electrical">Electrical</option>
                <option value="mechanical">Mechanical</option>
                <option value="plumbing">Plumbing</option>
                <option value="it">IT Support</option>
            </select>
            <select class="select select-bordered select-sm w-full md:w-32">
                <option value="all">All Status</option>
                <option value="free">Free</option>
                <option value="busy">Busy</option>
            </select>
        </div>
    </div>

    <!-- Personnel Table -->
    <div class="overflow-x-auto rounded-lg border border-gray-200">
        <table class="table table-zebra w-full">
            <thead>
                <tr class="bg-gray-700 font-bold text-white">
                    <th class="whitespace-nowrap px-6 py-4">Personnel ID</th>
                    <th class="whitespace-nowrap px-6 py-4">Full Name</th>
                    <th class="whitespace-nowrap px-6 py-4">Specialization</th>
                    <th class="whitespace-nowrap px-6 py-4 text-center">Current Status</th>
                    <th class="whitespace-nowrap px-6 py-4">Contact</th>
                    <th class="whitespace-nowrap px-6 py-4 text-right">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-semibold text-gray-700">EMP-2026-001</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-blue-100 flex items-center justify-center text-blue-600 font-bold">JD</div>
                            <span class="font-bold text-gray-800">John Doe</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">Electrical Specialist</td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                            FREE
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">+63 912 345 6789</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="View Details">
                                <i class='bx bx-show-alt text-xl'></i>
                            </button>
                            <button class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit Personnel">
                                <i class='bx bx-edit text-xl'></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-semibold text-gray-700">EMP-2026-002</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 font-bold">JS</div>
                            <span class="font-bold text-gray-800">Jane Smith</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">Mechanical Engineer</td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-bold">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                            BUSY
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">+63 923 456 7890</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="View Details">
                                <i class='bx bx-show-alt text-xl'></i>
                            </button>
                            <button class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit Personnel">
                                <i class='bx bx-edit text-xl'></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-semibold text-gray-700">EMP-2026-003</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-600 font-bold">RK</div>
                            <span class="font-bold text-gray-800">Robert King</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">Plumbing & HVAC</td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-bold">
                            <span class="w-1.5 h-1.5 rounded-full bg-green-500"></span>
                            FREE
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">+63 934 567 8901</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="View Details">
                                <i class='bx bx-show-alt text-xl'></i>
                            </button>
                            <button class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit Personnel">
                                <i class='bx bx-edit text-xl'></i>
                            </button>
                        </div>
                    </td>
                </tr>
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-6 py-4 font-semibold text-gray-700">EMP-2026-004</td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-rose-100 flex items-center justify-center text-rose-600 font-bold">ML</div>
                            <span class="font-bold text-gray-800">Maria Lopez</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-gray-600">IT Infrastructure</td>
                    <td class="px-6 py-4 text-center">
                        <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-amber-100 text-amber-700 text-xs font-bold">
                            <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                            BUSY
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-600">+63 945 678 9012</td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex justify-end gap-2">
                            <button class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-colors" title="View Details">
                                <i class='bx bx-show-alt text-xl'></i>
                            </button>
                            <button class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-colors" title="Edit Personnel">
                                <i class='bx bx-edit text-xl'></i>
                            </button>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Paging -->
    <div class="flex items-center justify-between mt-6">
        <div class="text-sm text-gray-600">Showing 1-4 of 8 personnel</div>
        <div class="join">
            <button class="btn btn-sm join-item" disabled>Prev</button>
            <span class="btn btn-sm join-item bg-blue-600 text-white border-blue-600">1 / 2</span>
            <button class="btn btn-sm join-item">Next</button>
        </div>
    </div>
</div>
