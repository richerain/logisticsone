<!-- views/psm/budgeting.blade.php -->
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bxs-wallet'></i>Budgeting</h2>
    </div>
    <div class="flex items-center gap-4">
        <span class="text-md text-gray-600">Procurement & Sourcing Management</span>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg p-6 mb-6">
    <!-- Consolidated Budget Request Section -->
    <div class="">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                <i class='bx bx-git-pull-request text-blue-600'></i>
                Consolidated Budget Request
            </h3>
        </div>

        <!-- Filters (Matching Requisition Records design) -->
        <div class="bg-gray-50 rounded-xl p-4 mb-6 border border-gray-100">
            <div class="grid grid-cols-1 md:grid-cols-12 gap-4">
                <div class="md:col-span-6 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <i class='bx bx-search text-gray-400 text-xl'></i>
                    </div>
                    <input type="text" id="consolidatedSearchInput" placeholder="Search by ID, requester, or department..." 
                        class="block w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm transition-all text-sm">
                </div>
                <div class="md:col-span-6">
                    <select id="consolidatedDeptFilter" class="block w-full px-4 py-2.5 border border-gray-200 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 bg-white shadow-sm transition-all text-sm">
                        <option value="">All Departments</option>
                        <option value="Human Resource Department">Human Resource Department</option>
                        <option value="Core Transaction Office">Core Transaction Office</option>
                        <option value="Logistics Office">Logistics Office</option>
                        <option value="Administrative Office">Administrative Office</option>
                        <option value="Financial Department">Financial Department</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Table Container -->
        <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-800 font-bold text-gray-100">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Requisition ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Items</th>
                            <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Total Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Requester / Dept</th>
                            <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Note</th>
                            <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody id="consolidatedTableBody" class="bg-white divide-y divide-gray-200">
                        <tr>
                            <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                                <div class="flex justify-center items-center py-4">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600 mr-3"></div>
                                    Loading approved requisitions...
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Calculation Area (Footer) -->
        <div class="bg-gray-50 border-x border-b border-gray-200 rounded-b-lg p-6 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-4">
                <div class="bg-blue-100 p-3 rounded-xl text-blue-600">
                    <i class='bx bx-calculator text-2xl'></i>
                </div>
                <div>
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-wider">Total Consolidated Amount</p>
                    <h4 class="text-2xl font-black text-blue-600 leading-none" id="consolidatedTotalAmount">₱0.00</h4>
                </div>
            </div>
            <button id="consolidateBtn" class="bg-blue-600 hover:bg-blue-700 text-white px-8 py-3 rounded-xl flex items-center gap-2 transition-all shadow-lg hover:shadow-blue-200 active:scale-95 font-bold uppercase tracking-wide text-sm">
                <i class='bx bx-git-pull-request'></i>
                Consolidated Budget Request
            </button>
        </div>

        <!-- Pagination (Matching Requisition Records design) -->
        <div id="consolidatedPager" class="flex items-center justify-between mt-4 px-2"> 
            <div id="consolidatedPagerInfo" class="text-sm text-gray-600"></div> 
            <div class="join"> 
                <button class="btn btn-sm join-item" id="consolidatedPrevBtn" data-action="prev">Prev</button> 
                <span class="btn btn-sm join-item" id="consolidatedPageDisplay">1 / 1</span> 
                <button class="btn btn-sm join-item" id="consolidatedNextBtn" data-action="next">Next</button> 
            </div> 
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg p-6">
    <!-- Budget Overview Header -->
    <div class="flex items-center justify-between mb-6">
        <h3 class="text-xl font-bold text-gray-800">Budget Overview</h3>
        <div class="flex gap-2">
            <button onclick="openRequestStatusModal()" class="btn btn-primary btn-sm gap-2">
                <i class='bx bx-list-ul'></i> Request Budget Status
            </button>
        </div>
    </div>

    <!-- Stats Section -->
    <div id="statsSection" class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-blue-50 rounded-xl shadow-sm p-6 border border-blue-100 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-blue-500 uppercase tracking-wider">Total Budget</p>
                    <h3 class="text-2xl font-bold text-blue-700 mt-2" id="totalBudget">₱0.00</h3>
                </div>
                <div class="p-2 bg-blue-100 rounded-lg text-blue-600">
                    <i class='bx bx-wallet text-xl'></i>
                </div>
            </div>
        </div>
        
        <div class="bg-orange-50 rounded-xl shadow-sm p-6 border border-orange-100 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-orange-500 uppercase tracking-wider">Spent</p>
                    <h3 class="text-2xl font-bold text-orange-700 mt-2" id="spentBudget">₱0.00</h3>
                </div>
                <div class="p-2 bg-orange-100 rounded-lg text-orange-600">
                    <i class='bx bx-cart text-xl'></i>
                </div>
            </div>
        </div>

        <div class="bg-green-50 rounded-xl shadow-sm p-6 border border-green-100 hover:shadow-md transition-shadow">
            <div class="flex justify-between items-start">
                <div>
                    <p class="text-xs font-semibold text-green-500 uppercase tracking-wider">Remaining</p>
                    <h3 class="text-2xl font-bold text-green-700 mt-2" id="remainingBudget">₱0.00</h3>
                </div>
                <div class="p-2 bg-green-100 rounded-lg text-green-600">
                    <i class='bx bx-pie-chart-alt-2 text-xl'></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Logistic I Budget Panel (Renamed from Latest Budget Details) -->
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
            <h3 class="text-md font-semibold text-gray-800">Logistic I Budget</h3>
            <span id="detailStatus" class="px-3 py-1 text-xs rounded-full bg-gray-200 text-gray-600 flex items-center gap-1">
                <i class='bx bx-question-mark'></i> No Data
            </span>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- New Fields -->
            <div class="flex items-start gap-3">
                <div class="p-2 bg-gray-100 rounded-lg text-gray-600">
                    <i class='bx bx-id-card text-xl'></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase mb-1">Budget ID</p>
                    <p class="font-medium text-gray-900" id="detailBudgetId">-</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                    <i class='bx bx-wallet text-xl'></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase mb-1">Allocated</p>
                    <p class="font-medium text-blue-700" id="detailAllocated">-</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="p-2 bg-orange-50 rounded-lg text-orange-600">
                    <i class='bx bx-purchase-tag text-xl'></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase mb-1">Spent</p>
                    <p class="font-medium text-orange-700" id="detailSpent">-</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="p-2 bg-green-50 rounded-lg text-green-600">
                    <i class='bx bx-pie-chart-alt-2 text-xl'></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase mb-1">Remaining</p>
                    <p class="font-medium text-green-700" id="detailRemaining">-</p>
                </div>
            </div>
            
            <!-- Existing Fields -->
            <div class="flex items-start gap-3">
                <div class="p-2 bg-purple-50 rounded-lg text-purple-600">
                    <i class='bx bx-calendar-check text-xl'></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase mb-1">Validity Type</p>
                    <p class="font-medium text-gray-900" id="detailValidityType">-</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="p-2 bg-indigo-50 rounded-lg text-indigo-600">
                    <i class='bx bx-calendar text-xl'></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase mb-1">Valid From</p>
                    <p class="font-medium text-gray-900" id="detailValidFrom">-</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="p-2 bg-pink-50 rounded-lg text-pink-600">
                    <i class='bx bx-calendar-x text-xl'></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase mb-1">Valid To</p>
                    <p class="font-medium text-gray-900" id="detailValidTo">-</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="p-2 bg-teal-50 rounded-lg text-teal-600">
                    <i class='bx bx-time-five text-xl'></i>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase mb-1">Assigned Date</p>
                    <p class="font-medium text-gray-900" id="detailAssignedDate">-</p>
                </div>
            </div>
             <div class="md:col-span-2 lg:col-span-4 flex items-start gap-3">
                <div class="p-2 bg-gray-50 rounded-lg text-gray-600">
                    <i class='bx bx-detail text-xl'></i>
                </div>
                <div class="flex-1">
                    <p class="text-xs text-gray-500 uppercase mb-1">Description</p>
                    <p class="font-medium text-gray-900" id="detailDesc">-</p>
                </div>
            </div>
        </div>
    </div>

    <hr class="my-6 border-gray-200">

    <!-- Budget Logs Table Container -->
    <div class="mt-8">
        <h3 class="text-md font-semibold text-gray-800 mb-4">Budget Logs</h3>
        <div class="overflow-x-auto rounded-lg border border-gray-200">
            <table class="min-w-full divide-y divide-gray-200" id="budgetLogsTable">
                <thead class="bg-gray-800">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-100 capitalize tracking-wider whitespace-nowrap">Log ID</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-100 capitalize tracking-wider whitespace-nowrap">Spent Amount</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-100 capitalize tracking-wider whitespace-nowrap">Spent To</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-100 capitalize tracking-wider whitespace-nowrap">Type</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-100 capitalize tracking-wider whitespace-nowrap">Spent Date</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-100 capitalize tracking-wider whitespace-nowrap">Action</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="budgetLogsTableBody">
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class='bx bx-loader-alt bx-spin text-4xl mb-2'></i>
                                <p>Loading logs...</p>
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        
        <!-- Pagination Controls -->
        <div id="logsPager" class="flex items-center justify-between mt-4">
            <div id="logsPagerInfo" class="text-sm text-gray-600"></div>
            <div class="join">
                <button class="btn btn-sm join-item" id="logsPrevBtn" data-action="prev">Prev</button>
                <span class="btn btn-sm join-item" id="logsPageDisplay">1 / 1</span>
                <button class="btn btn-sm join-item" id="logsNextBtn" data-action="next">Next</button>
            </div>
        </div>
    </div>
</div>

<!-- Budget Log Details Modal -->
<div id="budgetLogModal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-60 transition-opacity" aria-hidden="true" onclick="closeBudgetLogModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <div class="bg-white">
                <div class="px-6 py-4 border-b border-gray-100 flex justify-between items-center bg-gray-50">
                    <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2" id="modal-title">
                        <i class='bx bx-file text-blue-600'></i> Budget Log Details
                    </h3>
                    <button type="button" class="text-gray-400 hover:text-gray-500 focus:outline-none" onclick="closeBudgetLogModal()">
                        <span class="sr-only">Close</span>
                        <i class='bx bx-x text-2xl'></i>
                    </button>
                </div>
                
                <div class="p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Primary Info -->
                        <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                            <p class="text-xs font-semibold text-blue-500 uppercase tracking-wider mb-1">Spent Amount</p>
                            <p class="text-2xl font-bold text-blue-700" id="modalSpentAmount">-</p>
                            <div class="mt-3 pt-3 border-t border-blue-100">
                                <p class="text-xs text-blue-400 uppercase mb-1">Transaction Type</p>
                                <div id="modalType" class="mt-1">
                                    -
                                </div>
                            </div>
                        </div>

                        <!-- Date & Reference -->
                        <div class="space-y-4">
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Log Reference ID</p>
                                <div class="flex items-center gap-2 bg-gray-50 p-2 rounded border border-gray-100">
                                    <i class='bx bx-hash text-gray-400'></i>
                                    <span class="text-sm font-mono font-medium text-gray-700" id="modalLogId">-</span>
                                </div>
                            </div>
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1">Budget Source ID</p>
                                <div class="flex items-center gap-2 bg-gray-50 p-2 rounded border border-gray-100">
                                    <i class='bx bx-id-card text-gray-400'></i>
                                    <span class="text-sm font-mono font-medium text-gray-700" id="modalBudgetId">-</span>
                                </div>
                            </div>
                        </div>

                        <!-- Detailed Info -->
                        <div class="md:col-span-2 space-y-4">
                             <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Spent On</p>
                                <div class="p-3 bg-gray-50 rounded-lg border border-gray-100">
                                    <p class="text-sm font-medium text-gray-900 break-words" id="modalSpentTo">-</p>
                                </div>
                            </div>
                            
                            <div>
                                <p class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Transaction Date</p>
                                <div class="flex items-start gap-3 p-3 bg-gray-50 rounded-lg border border-gray-100">
                                    <div class="p-2 bg-white rounded-full shadow-sm text-gray-600">
                                        <i class='bx bx-calendar-event text-lg'></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900" id="modalSpentDate">-</p>
                                        <p class="text-xs text-gray-500 mt-0.5">Date Recorded</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-4 flex justify-end">
                <button type="button" class="inline-flex justify-center rounded-lg border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:text-sm" onclick="closeBudgetLogModal()">
                    Close Details
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Request Budget Modal -->
<div id="requestBudgetModal" class="fixed inset-0 z-[110] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-60 transition-opacity" aria-hidden="true" onclick="closeRequestBudgetModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
            <div class="bg-gradient-to-r from-blue-600 to-blue-700 px-6 py-4 border-b border-gray-100 flex justify-between items-center">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <i class='bx bx-plus-circle'></i> New Budget Request
                </h3>
                <button type="button" class="text-white hover:text-gray-200 focus:outline-none" onclick="closeRequestBudgetModal()">
                    <span class="sr-only">Close</span>
                    <i class='bx bx-x text-2xl'></i>
                </button>
            </div>
            
            <div class="p-8 bg-gray-50">
                <form id="requestBudgetForm" class="space-y-6">
                    @php
                        $user = Auth::guard('sws')->user();
                        if (!$user) {
                            $user = Auth::user();
                        }
                        
                        $reqName = '';
                        $reqContact = 'logistic1.microfinancial@gmail.com / +63 912 345 6789'; // Default fallback

                        if ($user) {
                            // Construct Name: First Last - Role
                            $firstName = $user->firstname ?? $user->name ?? ''; // Fallback to name if firstname missing
                            $lastName = $user->lastname ?? '';
                            $role = $user->roles ?? 'Staff';
                            
                            $fullName = trim($firstName . ' ' . $lastName);
                            if (empty($fullName) && !empty($user->name)) {
                                $fullName = $user->name;
                            }
                            
                            $reqName = $fullName . ' - ' . ucfirst($role);

                            // Construct Contact: Email / Phone
                            $email = $user->email ?? '';
                            $phone = $user->contactnum ?? '';
                            
                            if (!empty($email) || !empty($phone)) {
                                $reqContact = trim($email . ' / ' . $phone, ' / ');
                            }
                        }
                    @endphp

                    <!-- Hidden Requester Information (Automatically Filled) -->
                    <input type="hidden" name="req_by" id="req_by" value="{{ $reqName }}">
                    <input type="hidden" name="req_dept" id="req_dept" value="Logistics 1">
                    <input type="hidden" name="req_contact" id="req_contact" value="{{ $reqContact }}">
                    <input type="hidden" name="req_date" id="req_date">

                    <!-- Section 2: Financial Details -->
                    <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-100">
                        <h4 class="text-sm font-semibold text-gray-500 uppercase tracking-wider mb-4 border-b pb-2">Financial Details</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="md:col-span-2">
                                <label for="req_amount" class="block text-sm font-medium text-gray-700 mb-1">Requested Amount</label>
                                <div class="relative rounded-md shadow-sm">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">₱</span>
                                    </div>
                                    <input type="number" step="0.01" name="req_amount" id="req_amount" class="focus:ring-blue-500 focus:border-blue-500 block w-full pl-7 pr-12 sm:text-sm border-gray-300 rounded-md py-2" placeholder="0.00" required>
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 sm:text-sm">PHP</span>
                                    </div>
                                </div>
                            </div>
                            <div class="md:col-span-2">
                                <label for="req_purpose" class="block text-sm font-medium text-gray-700 mb-1">Purpose / Description</label>
                                <div class="relative rounded-md shadow-sm">
                                    <textarea name="req_purpose" id="req_purpose" rows="4" class="focus:ring-blue-500 focus:border-blue-500 block w-full sm:text-sm border-gray-300 rounded-md p-3" placeholder="Describe the purpose of this budget request..." required></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="bg-gray-100 px-6 py-4 flex justify-end gap-3 border-t border-gray-200">
                <button type="button" class="px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" onclick="closeRequestBudgetModal()">
                    Cancel
                </button>
                <button type="button" class="px-4 py-2 bg-blue-600 border border-transparent rounded-md shadow-sm text-sm font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 flex items-center gap-2" onclick="submitRequestBudget()">
                    <i class='bx bx-send'></i> Send Request
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Request Budget Status Modal -->
<div id="requestStatusModal" class="fixed inset-0 z-[100] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-60 transition-opacity" aria-hidden="true" onclick="closeRequestStatusModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-5xl sm:w-full">
            <div class="bg-white">
                <div class="px-6 py-5 border-b border-gray-200 flex justify-between items-center bg-gray-50">
                    <h3 class="text-xl font-bold text-gray-800 flex items-center gap-2">
                        <i class='bx bx-list-ul text-blue-600'></i> Request Budget Status
                    </h3>
                    <button onclick="openRequestBudgetModal()" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 gap-2">
                        <i class='bx bx-plus-circle'></i> Request Budget
                    </button>
                </div>
                
                <div class="p-6 bg-gray-50 min-h-[300px]">
                    <div class="bg-white rounded-xl shadow-sm overflow-x-auto border border-gray-200 custom-scrollbar">
                        <table class="min-w-full divide-y divide-gray-200" id="requestStatusTable">
                            <thead class="bg-gray-800 font-bold text-gray-100">
                                <tr>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider whitespace-nowrap">Req ID</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider whitespace-nowrap">Requested By</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider whitespace-nowrap">Date</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider whitespace-nowrap">Dept</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider whitespace-nowrap">Amount</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider whitespace-nowrap">Purpose</th>
                                    <th scope="col" class="px-6 py-4 text-left text-xs font-bold uppercase tracking-wider whitespace-nowrap">Status</th>
                                    <th scope="col" class="px-6 py-4 text-right text-xs font-bold uppercase tracking-wider whitespace-nowrap">Action</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-100" id="requestStatusTableBody">
                                <tr>
                                    <td colspan="9" class="px-6 py-12 text-center text-sm text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <i class='bx bx-loader-alt bx-spin text-3xl mb-2 text-blue-500'></i>
                                            <span>Loading requests...</span>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-4 flex justify-end border-t border-gray-200">
                <button type="button" class="px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" onclick="closeRequestStatusModal()">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>


<!-- View Request Details Modal -->
<div id="viewRequestModal" class="fixed inset-0 z-[120] hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-900 bg-opacity-60 transition-opacity" aria-hidden="true" onclick="closeViewRequestModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-gradient-to-r from-gray-100 to-gray-200 px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                    <i class='bx bx-file'></i> Request Details
                </h3>
                <button type="button" class="text-gray-500 hover:text-gray-700 focus:outline-none" onclick="closeViewRequestModal()">
                    <span class="sr-only">Close</span>
                    <i class='bx bx-x text-2xl'></i>
                </button>
            </div>
            <div class="p-6 bg-white space-y-4">
                <div class="grid grid-cols-2 gap-4 text-sm">
                    <div>
                        <span class="block text-gray-500 text-xs uppercase tracking-wide">Request ID</span>
                        <span id="view_req_id" class="font-semibold text-gray-900"></span>
                    </div>
                    <div>
                        <span class="block text-gray-500 text-xs uppercase tracking-wide">Date</span>
                        <span id="view_req_date" class="font-medium text-gray-900"></span>
                    </div>
                    <div class="col-span-2">
                        <span class="block text-gray-500 text-xs uppercase tracking-wide">Requested By</span>
                        <span id="view_req_by" class="font-medium text-gray-900"></span>
                    </div>
                    <div>
                        <span class="block text-gray-500 text-xs uppercase tracking-wide">Department</span>
                        <span id="view_req_dept" class="font-medium text-gray-900"></span>
                    </div>
                    <div>
                        <span class="block text-gray-500 text-xs uppercase tracking-wide">Amount</span>
                        <span id="view_req_amount" class="font-bold text-blue-600 text-lg"></span>
                    </div>
                    <div class="col-span-2">
                        <span class="block text-gray-500 text-xs uppercase tracking-wide">Status</span>
                        <span id="view_req_status" class="inline-flex px-2 py-1 rounded-full text-xs font-semibold"></span>
                    </div>
                    <div class="col-span-2">
                        <span class="block text-gray-500 text-xs uppercase tracking-wide mb-1">Purpose</span>
                        <div id="view_req_purpose" class="bg-gray-50 p-3 rounded border border-gray-200 text-gray-700 whitespace-pre-wrap"></div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-6 py-4 flex justify-end border-t border-gray-200">
                <button type="button" class="px-4 py-2 bg-white border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500" onclick="closeViewRequestModal()">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Global functions for Modal
    window.allBudgetLogs = []; // Store logs for modal access

    window.openBudgetLogModal = function(logId) {
        const log = window.allBudgetLogs.find(l => (l.log_code || l.id) == logId);
        if (!log) return;

        document.getElementById('modalLogId').textContent = log.log_code || log.id || '-';
        document.getElementById('modalBudgetId').textContent = log.bud_id || '-';
        document.getElementById('modalSpentAmount').textContent = window.formatCurrencyGlobal(log.bud_spent);
        document.getElementById('modalSpentTo').textContent = log.spent_to || '-';
        document.getElementById('modalSpentDate').textContent = window.formatDateGlobal(log.bud_spent_date);

        // Style Type
        let typeClass = 'bg-gray-100 text-gray-800';
        let typeIcon = 'bx-question-mark';
        
        if (log.bud_type === 'Purchase Payment') {
            typeClass = 'bg-blue-100 text-blue-800';
            typeIcon = 'bx-purchase-tag';
        } else if (log.bud_type === 'Project Payment') {
            typeClass = 'bg-purple-100 text-purple-800';
            typeIcon = 'bx-building-house';
        }

        document.getElementById('modalType').innerHTML = `
            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${typeClass} items-center gap-1">
                <i class='bx ${typeIcon}'></i> ${log.bud_type || '-'}
            </span>
        `;

        document.getElementById('budgetLogModal').classList.remove('hidden');
    };

    window.closeBudgetLogModal = function() {
        document.getElementById('budgetLogModal').classList.add('hidden');
    };

    // --- Request Budget Modal Logic ---
    window.openRequestBudgetModal = function() {
        document.getElementById('requestBudgetForm').reset();
        
        // Set default date to today
        const today = new Date().toISOString().split('T')[0];
        document.getElementById('req_date').value = today;
        
        document.getElementById('requestBudgetModal').classList.remove('hidden');
    };

    window.closeRequestBudgetModal = function() {
        document.getElementById('requestBudgetModal').classList.add('hidden');
    };

    window.submitRequestBudget = function() {
        const form = document.getElementById('requestBudgetForm');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        
        // Auto-generate Request ID: REQB + YYYYMMDD + 5 random alphanumeric
        const now = new Date();
        const year = now.getFullYear();
        const month = String(now.getMonth() + 1).padStart(2, '0');
        const day = String(now.getDate()).padStart(2, '0');
        const randomStr = Math.random().toString(36).substring(2, 7).toUpperCase();
        const generatedReqId = `REQB${year}${month}${day}${randomStr}`;
        
        data.req_id = generatedReqId;
        
        // Safety check for hidden fields
        if (!data.req_by || !data.req_dept || !data.req_contact) {
             Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'User information is missing. Please reload the page.',
            });
            return;
        }
        
        const token = localStorage.getItem('jwt');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        fetch('/api/v1/psm/budget-requests', {
            method: 'POST',
            headers: {
                'Authorization': token ? `Bearer ${token}` : '',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken || '',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            if (result.success) {
                // Success Workflow:
                // 1. Refresh the Status Table (in background or foreground)
                fetchRequestStatus();
                
                // 2. Close Both Modals
                closeRequestBudgetModal();
                closeRequestStatusModal();

                // 3. Show Success Alert (Toast)
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });

                Toast.fire({
                    icon: 'success',
                    title: 'Request successfully submitted'
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: result.message || 'Failed to send request.',
                });
            }
        })
        .catch(error => {
            console.error('Error submitting request:', error);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'An error occurred while sending the request.',
            });
        });
    };

    // --- Request Status Modal Logic ---
    window.openRequestStatusModal = function() {
        document.getElementById('requestStatusModal').classList.remove('hidden');
        fetchRequestStatus();
    };

    window.closeRequestStatusModal = function() {
        document.getElementById('requestStatusModal').classList.add('hidden');
    };

    // View Request Details Modal Logic
    window.openViewRequestDetails = function(req) {
        document.getElementById('view_req_id').textContent = '#' + req.req_id;
        document.getElementById('view_req_date').textContent = window.formatDateGlobal(req.req_date);
        document.getElementById('view_req_by').textContent = req.req_by;
        document.getElementById('view_req_dept').textContent = req.req_dept;
        document.getElementById('view_req_amount').textContent = window.formatCurrencyGlobal(req.req_amount);
        document.getElementById('view_req_contact').textContent = req.req_contact;
        document.getElementById('view_req_purpose').textContent = req.req_purpose;

        const statusEl = document.getElementById('view_req_status');
        statusEl.textContent = req.req_status;
        
        // Reset classes
        statusEl.className = 'inline-flex px-2 py-1 rounded-full text-xs font-semibold items-center gap-1';
        
        if (req.req_status === 'Approved') {
            statusEl.classList.add('bg-green-100', 'text-green-800');
            statusEl.innerHTML = "<i class='bx bx-check-circle'></i> Approved";
        } else if (req.req_status === 'Rejected') {
            statusEl.classList.add('bg-red-100', 'text-red-800');
            statusEl.innerHTML = "<i class='bx bx-x-circle'></i> Rejected";
        } else if (req.req_status === 'Cancelled') {
            statusEl.classList.add('bg-gray-100', 'text-gray-800');
            statusEl.innerHTML = "<i class='bx bx-block'></i> Cancelled";
        } else {
            statusEl.classList.add('bg-yellow-100', 'text-yellow-800');
            statusEl.innerHTML = "<i class='bx bx-time-five'></i> Pending";
        }

        document.getElementById('viewRequestModal').classList.remove('hidden');
    }

    window.closeViewRequestModal = function() {
        document.getElementById('viewRequestModal').classList.add('hidden');
    }

    // Cancel Request Logic
    window.confirmCancelRequest = function(reqId) {
        Swal.fire({
            title: 'Are you sure?',
            text: "You want to cancel this budget request?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, cancel it!',
            cancelButtonText: 'No, keep it'
        }).then((result) => {
            if (result.isConfirmed) {
                cancelRequest(reqId);
            }
        });
    }

    window.cancelRequest = function(reqId) {
        const token = localStorage.getItem('jwt');
        
        fetch(`/api/v1/psm/budget-requests/${reqId}/cancel`, {
            method: 'PATCH',
            headers: {
                'Authorization': token ? `Bearer ${token}` : '',
                'X-API-KEY': '{{ \Illuminate\Support\Facades\DB::connection("main")->table("api_keys")->orderBy("created_at", "desc")->value("key") ?? "" }}',
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Refresh the table
                fetchRequestStatus();
                
                // Show Toast
                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true,
                    didOpen: (toast) => {
                        toast.addEventListener('mouseenter', Swal.stopTimer)
                        toast.addEventListener('mouseleave', Swal.resumeTimer)
                    }
                });

                Toast.fire({
                    icon: 'success',
                    title: 'Request successfully cancelled'
                });
            } else {
                Swal.fire('Error', data.message || 'Failed to cancel request', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', 'An unexpected error occurred.', 'error');
        });
    }

    function fetchRequestStatus() {
        const tbody = document.getElementById('requestStatusTableBody');
        // Only show loading if empty or explicitly requested, otherwise it might flicker too much on refresh
        // But for now, simple is fine.
        // tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-12 text-center text-sm text-gray-500"><div class="flex flex-col items-center justify-center"><i class="bx bx-loader-alt bx-spin text-3xl mb-2 text-blue-500"></i><span>Loading requests...</span></div></td></tr>';

        const token = localStorage.getItem('jwt');
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

        fetch('/api/v1/psm/budget-requests', {
            method: 'GET',
            headers: {
                'Authorization': token ? `Bearer ${token}` : '',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken || '',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(result => {
            if (result.success && Array.isArray(result.data)) {
                renderRequestStatusTable(result.data);
            } else {
                tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">No requests found or error loading data.</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error fetching request status:', error);
            tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-sm text-red-500">Error loading requests.</td></tr>';
        });
    }

    function renderRequestStatusTable(requests) {
        const tbody = document.getElementById('requestStatusTableBody');
        tbody.innerHTML = '';

        // Render all budget requests from the refreshed database
        const newFormatRequests = requests;

        if (newFormatRequests.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="9" class="px-6 py-12 text-center">
                        <div class="flex flex-col items-center justify-center">
                            <div class="bg-gray-50 rounded-full p-4 mb-4">
                                <i class="bx bx-file-blank text-5xl text-gray-300"></i>
                            </div>
                            <p class="text-gray-500 font-medium text-lg">No budget requests found!</p>
                            <p class="text-gray-400 text-sm mt-1">Start by consolidating your approved requisitions.</p>
                        </div>
                    </td>
                </tr>
            `;
            return;
        }

        newFormatRequests.forEach(req => {
            let statusColor = 'bg-gray-100 text-gray-800';
            let statusIcon = 'bx-time';
            let actionButtons = '';
            
            if (req.req_status === 'Approved') {
                statusColor = 'bg-green-50 text-green-700 border-green-200';
                statusIcon = 'bx-check-circle';
                actionButtons = `
                    <button onclick='openViewRequestDetails(${JSON.stringify(req)})' class="text-blue-600 hover:text-blue-900 font-bold text-[11px] bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-all border border-blue-100 uppercase tracking-wider">View Details</button>
                `;
            } else if (req.req_status === 'Rejected') {
                statusColor = 'bg-red-50 text-red-700 border-red-200';
                statusIcon = 'bx-x-circle';
                actionButtons = `
                    <button onclick='openViewRequestDetails(${JSON.stringify(req)})' class="text-blue-600 hover:text-blue-900 font-bold text-[11px] bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-all border border-blue-100 uppercase tracking-wider">View Details</button>
                `;
            } else if (req.req_status === 'Cancelled') {
                statusColor = 'bg-gray-50 text-gray-700 border-gray-200';
                statusIcon = 'bx-block';
                actionButtons = `
                    <button onclick='openViewRequestDetails(${JSON.stringify(req)})' class="text-blue-600 hover:text-blue-900 font-bold text-[11px] bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-all border border-blue-100 uppercase tracking-wider">View Details</button>
                `;
            } else if (req.req_status === 'Pending') {
                statusColor = 'bg-yellow-50 text-yellow-700 border-yellow-200';
                statusIcon = 'bx-time-five';
                actionButtons = `
                    <div class="flex gap-2 justify-end">
                        <button onclick='openViewRequestDetails(${JSON.stringify(req)})' class="text-blue-600 hover:text-blue-900 font-bold text-[11px] bg-blue-50 hover:bg-blue-100 px-3 py-1.5 rounded-lg transition-all border border-blue-100 uppercase tracking-wider">View</button>
                        <button onclick="confirmCancelRequest('${req.req_id}')" class="text-red-600 hover:text-red-900 font-bold text-[11px] bg-red-50 hover:bg-red-100 px-3 py-1.5 rounded-lg transition-all border border-red-100 uppercase tracking-wider">Cancel</button>
                    </div>
                `;
            }

            const row = `
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-xs font-bold text-blue-600 bg-blue-50 px-2 py-1 rounded border border-blue-100">${req.req_id}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="flex flex-col">
                            <span class="text-sm font-bold text-gray-900 leading-tight">${req.req_by.split(' - ')[0]}</span>
                            <span class="text-[11px] text-gray-500 font-medium">${req.req_by.split(' - ')[1] || ''}</span>
                        </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">${window.formatDateGlobal(req.req_date)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 font-medium">${req.req_dept}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">${window.formatCurrencyGlobal(req.req_amount)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 max-w-xs truncate font-medium" title="${req.req_purpose}">${req.req_purpose}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full ${statusColor} items-center gap-1.5 border">
                            <i class='bx ${statusIcon}'></i> ${req.req_status}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right">
                        ${actionButtons}
                    </td>
                </tr>
            `;
            tbody.insertAdjacentHTML('beforeend', row);
        });
    }
    
    // Helper functions for global scope (needed for modal)
    window.formatCurrencyGlobal = function(amount) {
        const val = parseFloat(amount || 0);
        return '₱' + val.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
    };

    window.formatDateGlobal = function(dateString) {
        if (!dateString) return 'N/A';
        const date = new Date(dateString);
        if (isNaN(date.getTime())) return 'Invalid Date';
        return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
    };

    // Initialize Budgeting Module
    (function() {
        console.log('Budgeting Module Initialized');
        
        // Variables for existing Budget Logs
        let currentLogsPage = 1;
        const logsPageSize = 10;

        // Variables for Consolidated Budget Requests
        let currentConsolidatedPage = 1;
        const consolidatedPageSize = 10;
        let allApprovedRequisitions = [];
        let filteredRequisitions = [];

        function init() {
            fetchBudgets();
            fetchBudgetLogs();
            fetchApprovedRequisitions();
            setupEventListeners();
        }

        function setupEventListeners() {
            // Logs pagination
            const logsPager = document.getElementById('logsPager');
            if (logsPager) {
                logsPager.addEventListener('click', function(ev){
                    const btn = ev.target.closest('button[data-action]');
                    if(!btn) return;
                    const act = btn.getAttribute('data-action');
                    const total = window.allBudgetLogs ? window.allBudgetLogs.length : 0;
                    const totalPages = Math.ceil(total / logsPageSize);
                    
                    if(act === 'prev'){ 
                        currentLogsPage = Math.max(1, currentLogsPage - 1); 
                        renderBudgetLogsTable(window.allBudgetLogs); 
                    }
                    if(act === 'next'){ 
                        currentLogsPage = Math.min(totalPages, currentLogsPage + 1); 
                        renderBudgetLogsTable(window.allBudgetLogs); 
                    }
                });
            }

            // Consolidated pagination
            const consolidatedPager = document.getElementById('consolidatedPager');
            if (consolidatedPager) {
                consolidatedPager.addEventListener('click', function(ev){
                    const btn = ev.target.closest('button[data-action]');
                    if(!btn) return;
                    const act = btn.getAttribute('data-action');
                    const totalPages = Math.ceil(filteredRequisitions.length / consolidatedPageSize);
                    
                    if(act === 'prev'){ 
                        currentConsolidatedPage = Math.max(1, currentConsolidatedPage - 1); 
                        displayConsolidatedTable(); 
                    }
                    if(act === 'next'){ 
                        currentConsolidatedPage = Math.min(totalPages, currentConsolidatedPage + 1); 
                        displayConsolidatedTable(); 
                    }
                });
            }

            // Consolidated filters
            document.getElementById('consolidatedSearchInput')?.addEventListener('input', applyConsolidatedFilters);
            document.getElementById('consolidatedDeptFilter')?.addEventListener('change', applyConsolidatedFilters);

            // Consolidated button
            document.getElementById('consolidateBtn')?.addEventListener('click', () => {
                const totalAmount = calculateTotalAmount(filteredRequisitions);
                if (totalAmount <= 0) {
                    if (typeof showNotification === 'function') {
                        showNotification('No approved requisitions to consolidate or total amount is zero.', 'warning');
                    } else {
                        Swal.fire('Warning', 'No approved requisitions to consolidate or total amount is zero.', 'warning');
                    }
                    return;
                }
                
                Swal.fire({
                    title: 'Consolidate Budget Request?',
                    text: `You are about to request a total of ${formatCurrency(totalAmount)} for all approved requisitions.`,
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#2563eb',
                    cancelButtonColor: '#64748b',
                    confirmButtonText: 'Yes, Proceed',
                    cancelButtonText: 'Cancel'
                }).then((result) => {
                    if (result.isConfirmed) {
                        const amount = totalAmount;
                        const req_by = "{{ $reqName }}";
                        const req_dept = "Logistics 1";
                        const req_contact = "{{ $reqContact }}";
                        const req_date = new Date().toISOString().split('T')[0];
                        const req_purpose = "Consolidated budget request for approved Purchase Requisitions";
                        
                        // Auto-generate Request ID: REQB + YYYYMMDD + 5 random alphanumeric
                        const nowGen = new Date();
                        const yearGen = nowGen.getFullYear();
                        const monthGen = String(nowGen.getMonth() + 1).padStart(2, '0');
                        const dayGen = String(nowGen.getDate()).padStart(2, '0');
                        const randomStrGen = Math.random().toString(36).substring(2, 7).toUpperCase();
                        const generatedReqId = `REQB${yearGen}${monthGen}${dayGen}${randomStrGen}`;
                        
                        const token = localStorage.getItem('jwt');
                        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');

                        fetch('/api/v1/psm/budget-requests', {
                            method: 'POST',
                            headers: {
                                'Authorization': token ? `Bearer ${token}` : '',
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': csrfToken || '',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify({
                                req_id: generatedReqId,
                                req_by,
                                req_dept,
                                req_contact,
                                req_date,
                                req_amount: amount,
                                req_purpose
                            })
                        })
                        .then(response => response.json())
                        .then(result => {
                            if (result.success) {
                                fetchRequestStatus();
                                openRequestStatusModal();
                                
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Consolidated!',
                                    text: 'Budget request has been automatically generated.',
                                    toast: true,
                                    position: 'top-end',
                                    showConfirmButton: false,
                                    timer: 3000,
                                    timerProgressBar: true
                                });
                            } else {
                                Swal.fire('Error', result.message || 'Failed to generate request', 'error');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire('Error', 'An unexpected error occurred.', 'error');
                        });
                    }
                });
            });
        }

        function fetchApprovedRequisitions() {
            const token = localStorage.getItem('jwt');
            
            fetch('/api/v1/psm/requisitions', {
                method: 'GET',
                headers: {
                    'Authorization': token ? `Bearer ${token}` : '',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && Array.isArray(data.data)) {
                    allApprovedRequisitions = data.data.filter(req => req.req_status === 'Approved');
                    allApprovedRequisitions.sort((a, b) => (b.id || 0) - (a.id || 0));
                    applyConsolidatedFilters();
                } else {
                    renderEmptyConsolidatedTable('No approved requisitions found.');
                }
            })
            .catch(error => {
                console.error('Error fetching requisitions:', error);
                renderEmptyConsolidatedTable('Error loading requisitions.');
            });
        }

        function applyConsolidatedFilters() {
            const searchTerm = document.getElementById('consolidatedSearchInput')?.value.toLowerCase() || '';
            const deptFilter = document.getElementById('consolidatedDeptFilter')?.value || '';

            filteredRequisitions = allApprovedRequisitions.filter(req => {
                const matchesSearch = 
                    (req.req_id && req.req_id.toLowerCase().includes(searchTerm)) ||
                    (req.req_requester && req.req_requester.toLowerCase().includes(searchTerm)) ||
                    (req.req_dept && req.req_dept.toLowerCase().includes(searchTerm));
                
                const matchesDept = !deptFilter || req.req_dept === deptFilter;
                return matchesSearch && matchesDept;
            });

            currentConsolidatedPage = 1;
            displayConsolidatedTable();
            updateConsolidatedTotal();
        }

        function displayConsolidatedTable() {
            const tbody = document.getElementById('consolidatedTableBody');
            if (!tbody) return;

            if (filteredRequisitions.length === 0) {
                renderEmptyConsolidatedTable('No matching approved requisitions found.');
                return;
            }

            const totalPages = Math.ceil(filteredRequisitions.length / consolidatedPageSize);
            const start = (currentConsolidatedPage - 1) * consolidatedPageSize;
            const end = start + consolidatedPageSize;
            const paginatedData = filteredRequisitions.slice(start, end);

            tbody.innerHTML = '';
            paginatedData.forEach(req => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-gray-50 transition-colors';
                
                let itemsList = '-';
                try {
                    const items = typeof req.req_items === 'string' ? JSON.parse(req.req_items) : req.req_items;
                    if (Array.isArray(items)) {
                        itemsList = items.join(', ');
                    }
                } catch (e) {
                    itemsList = req.req_items || '-';
                }

                tr.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">${req.req_id || '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 max-w-xs truncate" title="${itemsList}">${itemsList}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-600">${window.formatCurrencyGlobal(req.req_price || 0)}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-bold text-gray-800">${req.req_requester || '-'}</div>
                        <div class="text-[10px] text-gray-500 uppercase font-semibold">${req.req_dept || '-'}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDate(req.req_date)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 italic max-w-xs truncate" title="${req.req_note || ''}">${req.req_note || '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-4 py-1.5 text-xs font-black rounded-full bg-green-600 text-white border border-green-700 flex items-center gap-1.5 w-fit shadow-sm">
                            <i class='bx bxs-check-circle text-sm'></i> Approved
                        </span>
                    </td>
                `;
                tbody.appendChild(tr);
            });

            updateConsolidatedPager(filteredRequisitions.length, totalPages);
        }

        function updateConsolidatedTotal() {
            const total = calculateTotalAmount(filteredRequisitions);
            const totalEl = document.getElementById('consolidatedTotalAmount');
            if (totalEl) totalEl.textContent = window.formatCurrencyGlobal(total);
        }

        function calculateTotalAmount(requisitions) {
            return requisitions.reduce((sum, req) => sum + parseFloat(req.req_price || 0), 0);
        }

        function updateConsolidatedPager(total, totalPages) {
            const info = document.getElementById('consolidatedPagerInfo');
            const display = document.getElementById('consolidatedPageDisplay');
            const start = total === 0 ? 0 : ((currentConsolidatedPage - 1) * consolidatedPageSize) + 1;
            const end = Math.min(currentConsolidatedPage * consolidatedPageSize, total);
            
            if (info) info.textContent = `Showing ${start}-${end} of ${total}`;
            if (display) display.textContent = `${currentConsolidatedPage} / ${totalPages || 1}`;
            
            const prev = document.getElementById('consolidatedPrevBtn');
            const next = document.getElementById('consolidatedNextBtn');
            
            if (prev) prev.disabled = currentConsolidatedPage <= 1;
            if (next) next.disabled = currentConsolidatedPage >= totalPages;
        }

        function renderEmptyConsolidatedTable(message) {
            const tbody = document.getElementById('consolidatedTableBody');
            if (tbody) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                            <div class="flex flex-col items-center justify-center">
                                <i class='bx bx-clipboard text-6xl mb-4 text-gray-300'></i>
                                <p class="text-lg font-medium">${message}</p>
                            </div>
                        </td>
                    </tr>
                `;
            }
            updateConsolidatedTotal();
            updateConsolidatedPager(0, 1);
        }

        init();

        function fetchBudgets() {
            const token = localStorage.getItem('jwt');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            // Show loading state
            renderLoadingState();

            // Fetch from API
            fetch('/api/v1/psm/budget-management/all', {
                method: 'GET',
                headers: {
                    'Authorization': token ? `Bearer ${token}` : '',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || '',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) {
                    // Try to read error body if possible
                    return response.text().then(text => {
                        console.error('Fetch error response:', text);
                        throw new Error(`HTTP error! status: ${response.status}`);
                    });
                }
                return response.json();
            })
            .then(data => {
                console.log('Budgets data:', data);
                if (data.success && Array.isArray(data.data) && data.data.length > 0) {
                    renderBudgetTable(data.data);
                    updateStats(data.data);
                    renderBudgetDetails(data.data[0]);
                } else if (data.success && Array.isArray(data.data) && data.data.length === 0) {
                    renderEmptyState('No budgets found.');
                    updateStats([]); 
                    renderBudgetDetails(null);
                } else {
                    renderEmptyState(data.message || 'No budgets found.');
                    updateStats([]);
                    renderBudgetDetails(null);
                }
            })
            .catch(error => {
                console.error('Error fetching budgets:', error);
                renderEmptyState('Error loading budgets. Please try again.');
                updateStats([]);
            });
        }

        function renderLoadingState() {
            const tbody = document.getElementById('budgetsTableBody');
            if (!tbody) return;
            tbody.innerHTML = `
                <tr>
                    <td colspan="13" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class='bx bx-loader-alt bx-spin text-4xl mb-2'></i>
                            <p>Loading budgets...</p>
                        </div>
                    </td>
                </tr>
            `;
        }

        function renderBudgetTable(budgets) {
            const tbody = document.getElementById('budgetsTableBody');
            if (!tbody) return;
            tbody.innerHTML = '';

            budgets.forEach(budget => {
                const healthColor = getHealthColor(budget.bud_amount_status_health);
                
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-gray-50 transition-colors';
                
                tr.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${budget.bud_id || '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600 font-bold">${formatCurrency(budget.bud_allocated_amount)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-orange-600 font-bold">${formatCurrency(budget.bud_spent_amount)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 font-bold">${formatCurrency(budget.bud_remaining_amount)}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${healthColor.bg} ${healthColor.text}">
                            ${budget.bud_amount_status_health || 'Unknown'}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${budget.bud_validity_type || '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDate(budget.bud_valid_from)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDate(budget.bud_valid_to)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDate(budget.bud_assigned_date)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${budget.bud_for_department || '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${budget.bud_for_module || '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${budget.bud_for_submodule || '-'}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 max-w-xs truncate" title="${budget.bud_desc || ''}">${budget.bud_desc || '-'}</td>
                `;
                tbody.appendChild(tr);
            });
        }

        function renderEmptyState(message = 'No budgets found.') {
            const tbody = document.getElementById('budgetsTableBody');
            if (!tbody) return;
            tbody.innerHTML = `
                <tr>
                    <td colspan="13" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class='bx bx-wallet text-6xl mb-4 text-gray-300'></i>
                            <p class="text-lg font-medium">${message}</p>
                        </div>
                    </td>
                </tr>
            `;
        }

        function updateStats(budgets) {
            let total = 0;
            let spent = 0;
            let remaining = 0;

            budgets.forEach(b => {
                total += parseFloat(b.bud_allocated_amount || 0);
                spent += parseFloat(b.bud_spent_amount || 0);
                remaining += parseFloat(b.bud_remaining_amount || 0);
            });

            const totalEl = document.getElementById('totalBudget');
            if(totalEl) totalEl.textContent = formatCurrency(total);
            
            const spentEl = document.getElementById('spentBudget');
            if(spentEl) spentEl.textContent = formatCurrency(spent);
            
            const remainingEl = document.getElementById('remainingBudget');
            if(remainingEl) remainingEl.textContent = formatCurrency(remaining);
        }

        function renderBudgetDetails(budget) {
            const healthData = budget ? getHealthColor(budget.bud_amount_status_health) : { bg: 'bg-gray-200', text: 'text-gray-600', icon: 'bx-question-mark' };
            const statusText = budget ? (budget.bud_amount_status_health || 'Unknown') : 'No Data';

            const fields = {
                'detailBudgetId': budget ? (budget.bud_id || '-') : '-',
                'detailAllocated': budget ? formatCurrency(budget.bud_allocated_amount) : '-',
                'detailSpent': budget ? formatCurrency(budget.bud_spent_amount) : '-',
                'detailRemaining': budget ? formatCurrency(budget.bud_remaining_amount) : '-',
                'detailValidityType': budget ? (budget.bud_validity_type || '-') : '-',
                'detailValidFrom': budget ? formatDate(budget.bud_valid_from) : '-',
                'detailValidTo': budget ? formatDate(budget.bud_valid_to) : '-',
                'detailAssignedDate': budget ? formatDate(budget.bud_assigned_date) : '-',
                'detailDesc': budget ? (budget.bud_desc || '-') : '-'
            };

            for (const [id, value] of Object.entries(fields)) {
                const el = document.getElementById(id);
                if (el) el.textContent = value;
            }

            const statusEl = document.getElementById('detailStatus');
            if (statusEl) {
                statusEl.innerHTML = `<i class='bx ${healthData.icon}'></i> ${statusText}`;
                statusEl.className = `px-3 py-1 text-xs rounded-full ${healthData.bg} ${healthData.text} flex items-center gap-1`;
            }
        }

        function getHealthColor(status) {
            switch(status) {
                case 'Healthy': return { bg: 'bg-green-100', text: 'text-green-800', icon: 'bx-check-circle' };
                case 'Stable': return { bg: 'bg-blue-100', text: 'text-blue-800', icon: 'bx-info-circle' };
                case 'Alert': return { bg: 'bg-yellow-100', text: 'text-yellow-800', icon: 'bx-error-circle' };
                case 'Exceeded': return { bg: 'bg-red-100', text: 'text-red-800', icon: 'bx-x-circle' };
                default: return { bg: 'bg-gray-100', text: 'text-gray-800', icon: 'bx-question-mark' };
            }
        }

        function formatCurrency(amount) {
            const val = parseFloat(amount || 0);
            return '₱' + val.toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
        }

        function formatDate(dateString) {
            if (!dateString) return 'N/A';
            const date = new Date(dateString);
            if (isNaN(date.getTime())) return 'Invalid Date';
            return date.toLocaleDateString('en-US', { year: 'numeric', month: 'short', day: 'numeric' });
        }

        function fetchBudgetLogs() {
            const token = localStorage.getItem('jwt');
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
            
            renderLogsLoadingState();
            
            // Fetch from API
            fetch('/api/v1/psm/budget-logs/all', {
                method: 'GET',
                headers: {
                    'Authorization': token ? `Bearer ${token}` : '',
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken || '',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                return response.json();
            })
            .then(data => {
                console.log('Budget Logs data:', data);
                if (data.success && Array.isArray(data.data) && data.data.length > 0) {
                    window.allBudgetLogs = data.data; // Store globally for modal
                    renderBudgetLogsTable(data.data);
                } else {
                    window.allBudgetLogs = [];
                    renderLogsEmptyState(data.message || 'No budget logs found.');
                }
            })
            .catch(error => {
                console.error('Error fetching budget logs:', error);
                renderLogsEmptyState('Error loading logs. Please try again.');
            });
        }

        function renderLogsLoadingState() {
            const tbody = document.getElementById('budgetLogsTableBody');
            if (!tbody) return;
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class='bx bx-loader-alt bx-spin text-4xl mb-2'></i>
                            <p>Loading logs...</p>
                        </div>
                    </td>
                </tr>
            `;
        }

        function renderBudgetLogsTable(logs) {
            const tbody = document.getElementById('budgetLogsTableBody');
            if (!tbody) return;
            tbody.innerHTML = '';

            // Sort logs: latest first
            logs.sort((a, b) => {
                const dateA = new Date(a.bud_spent_date || 0);
                const dateB = new Date(b.bud_spent_date || 0);
                return dateB - dateA; // Descending
            });

            // Pagination Logic
            const total = logs.length;
            const totalPages = Math.ceil(total / logsPageSize);
            
            // Adjust current page if out of bounds
            if (currentLogsPage > totalPages && totalPages > 0) currentLogsPage = totalPages;
            if (currentLogsPage < 1) currentLogsPage = 1;

            const start = (currentLogsPage - 1) * logsPageSize;
            const end = start + logsPageSize;
            const paginatedLogs = logs.slice(start, end);

            paginatedLogs.forEach(log => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-gray-50 transition-colors';
                
                // Style for Type
                let typeClass = 'bg-gray-100 text-gray-800';
                let typeIcon = 'bx-question-mark';
                
                if (log.bud_type === 'Purchase Payment') {
                    typeClass = 'bg-blue-100 text-blue-800';
                    typeIcon = 'bx-purchase-tag';
                } else if (log.bud_type === 'Project Payment') {
                    typeClass = 'bg-purple-100 text-purple-800';
                    typeIcon = 'bx-building-house';
                }

                // Truncate Spent To
                let spentTo = log.spent_to || '-';
                if (spentTo.length > 30) {
                    spentTo = spentTo.substring(0, 30) + '...';
                }
                
                tr.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${log.log_code || log.id}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-orange-700">${formatCurrency(log.bud_spent)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500" title="${log.spent_to || ''}">${spentTo}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${typeClass} items-center gap-1">
                            <i class='bx ${typeIcon}'></i> ${log.bud_type || '-'}
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDate(log.bud_spent_date)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                        <button class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 p-2 rounded-full transition-colors" title="View Full Budget Logs" onclick="openBudgetLogModal('${log.log_code || log.id}')">
                            <i class='bx bx-show-alt text-lg'></i>
                        </button>
                    </td>
                `;
                tbody.appendChild(tr);
            });

            renderLogsPager(total, totalPages);
        }

        function renderLogsPager(total, totalPages) {
            const info = document.getElementById('logsPagerInfo');
            const display = document.getElementById('logsPageDisplay');
            const start = total === 0 ? 0 : ((currentLogsPage - 1) * logsPageSize) + 1;
            const end = Math.min(currentLogsPage * logsPageSize, total);
            
            if (info) info.textContent = `Showing ${start}-${end} of ${total}`;
            if (display) display.textContent = `${currentLogsPage} / ${totalPages || 1}`;
            
            const prev = document.getElementById('logsPrevBtn');
            const next = document.getElementById('logsNextBtn');
            
            if (prev) prev.disabled = currentLogsPage <= 1;
            if (next) next.disabled = currentLogsPage >= totalPages;
        }

        function renderLogsEmptyState(message = 'No budget logs yet.') {
            const tbody = document.getElementById('budgetLogsTableBody');
            if (!tbody) return;
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                        <div class="flex flex-col items-center justify-center">
                            <i class='bx bx-list-ul text-6xl mb-4 text-gray-300'></i>
                            <p class="text-lg font-medium">${message}</p>
                        </div>
                    </td>
                </tr>
            `;
        }
    })();
</script>

