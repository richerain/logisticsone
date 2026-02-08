<!-- resources/views/user-management/account-management.blade.php -->
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bxs-user-detail'></i>Account Management</h2>
    </div>
    <div class="text-right">
        <span class="text-md text-gray-600">User Management</span>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg p-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6" id="amStats">
        <div class="stat-card bg-blue-50 p-4 rounded-lg text-center">
            <i class='bx bxs-user text-3xl text-blue-600 mb-2'></i>
            <h3 class="font-semibold text-blue-800">Total Employee</h3>
            <p class="text-2xl font-bold text-blue-600" id="statTotalEmployee">0</p>
        </div>
        <div class="stat-card bg-slate-100 p-4 rounded-lg text-center">
            <i class='bx bxs-store-alt text-3xl text-slate-700 mb-2'></i>
            <h3 class="font-semibold text-slate-800">Total Vendor</h3>
            <p class="text-2xl font-bold text-slate-700" id="statTotalVendor">0</p>
        </div>
        <div class="stat-card bg-green-50 p-4 rounded-lg text-center">
            <i class='bx bxs-user-check text-3xl text-green-600 mb-2'></i>
            <h3 class="font-semibold text-green-800">Active</h3>
            <p class="text-2xl font-bold text-green-600" id="statActive">0</p>
        </div>
        <div class="stat-card bg-red-50 p-4 rounded-lg text-center">
            <i class='bx bxs-user-x text-3xl text-red-600 mb-2'></i>
            <h3 class="font-semibold text-red-800">Inactive</h3>
            <p class="text-2xl font-bold text-red-600" id="statInactive">0</p>
        </div>
    </div>

    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
        <!-- Left Side: Search and Filter -->
        <div class="flex gap-2 items-center w-full md:w-auto">
            <div class="relative w-full md:w-64">
                <input type="text" id="accountSearch" placeholder="Search accounts..." class="input input-bordered input-sm w-full pl-10">
                <i class='bx bx-search absolute left-3 top-2 text-gray-500'></i>
            </div>
            <select id="accountTypeFilter" class="select select-bordered select-sm w-full md:w-48">
                <option value="employee">Employee Accounts</option>
                <option value="vendor">Vendor Accounts</option>
            </select>
        </div>

        <!-- Right Side: Register Buttons -->
        <div class="flex gap-2 items-center w-full md:w-auto">
            <button id="btnRegisterEmployee" class="btn btn-primary btn-sm gap-2">
                <i class='bx bx-user-plus text-lg'></i> Register Employee Account
            </button>
            <button id="btnRegisterVendor" class="btn btn-primary btn-sm gap-2 hidden">
                <i class='bx bx-store-alt text-lg'></i> Register Vendor Account
            </button>
        </div>
    </div>

    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-800 font-bold text-gray-100">
                    <tr id="tableHeaderRow">
                        <!-- Headers will be injected dynamically -->
                    </tr>
                </thead>
                <tbody id="accountsTableBody" class="bg-white divide-y divide-gray-200">
                    <tr>
                        <td colspan="100%" class="px-6 py-4 text-center text-gray-500">
                            <div class="flex justify-center items-center py-4">
                                <span class="loading loading-spinner loading-md mr-2"></span> Loading accounts...
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <div id="accountPager" class="flex items-center justify-between mt-3 px-4">
        <div id="accountPagerInfo" class="text-sm text-gray-600"></div>
        <div class="join">
            <button class="btn btn-sm join-item" id="accountPrevBtn" disabled>Prev</button>
            <span class="btn btn-sm join-item" id="accountPageDisplay">1 / 1</span>
            <button class="btn btn-sm join-item" id="accountNextBtn" disabled>Next</button>
        </div>
    </div>

    <!-- Modals -->
    <dialog id="roleModal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Change Role</h3>
            <p class="py-4">Select a new role for this user.</p>
            <div class="form-control w-full">
                <label class="label">
                    <span class="label-text">Role</span>
                </label>
                <select id="roleSelect" class="select select-bordered w-full">
                    <option value="superadmin">Superadmin</option>
                    <option value="Admin">Admin</option>
                    <option value="Manager">Manager</option>
                    <option value="Staff">Staff</option>
                    <option value="User">User</option>
                </select>
            </div>
            <div class="modal-action">
                <button class="btn" id="roleModalCancel">Cancel</button>
                <button class="btn btn-primary" id="roleModalSave">Save</button>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <dialog id="statusModal" class="modal">
        <div class="modal-box">
            <h3 class="font-bold text-lg">Change Status</h3>
            <p class="py-4">Select the new status for this account.</p>
            <div class="form-control w-full">
                <label class="label">
                    <span class="label-text">Status</span>
                </label>
                <select id="statusSelect" class="select select-bordered w-full">
                    <option value="active">Active</option>
                    <option value="inactive">Inactive</option>
                </select>
            </div>
            <div class="modal-action">
                <button class="btn" id="statusModalCancel">Cancel</button>
                <button class="btn btn-primary" id="statusModalSave">Save</button>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <!-- Register Employee Modal -->
    <dialog id="registerEmployeeModal" class="modal">
        <div class="modal-box w-11/12 max-w-2xl">
            <h3 class="font-bold text-lg">Register Employee Account</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 py-4">
                <div class="form-control w-full">
                    <label class="label"><span class="label-text">First Name *</span></label>
                    <input type="text" id="regEmpFirstname" class="input input-bordered w-full" required />
                </div>
                <div class="form-control w-full">
                    <label class="label"><span class="label-text">Middle Name (Optional)</span></label>
                    <input type="text" id="regEmpMiddlename" class="input input-bordered w-full" />
                </div>
                <div class="form-control w-full">
                    <label class="label"><span class="label-text">Last Name *</span></label>
                    <input type="text" id="regEmpLastname" class="input input-bordered w-full" required />
                </div>
                <div class="form-control w-full">
                    <label class="label"><span class="label-text">Email *</span></label>
                    <input type="email" id="regEmpEmail" class="input input-bordered w-full" required />
                </div>
                <div class="form-control w-full">
                     <!-- password field start --> 
                     <label class="block relative"> 
                         <span class="label-text">Account Password *</span> 
                         <div class="relative mt-1"> 
                             <!-- left lock icon --> 
                             <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"> 
                                 <i class="bx bxs-lock text-gray-400 text-lg" aria-hidden="true"></i> 
                             </span> 
                             <input id="regEmpPassword" name="password" type="password" placeholder="Password" class="input input-bordered w-full pl-10 pr-12 mt-0" aria-label="Password" required/> 
                             <!-- toggle show/hide button --> 
                             <button type="button" onclick="togglePassword(this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500" aria-pressed="false" aria-label="Show password"> 
                                 <i class="bx bx-show-alt text-lg"></i> 
                             </button> 
                         </div> 
                     </label> 
                     <!-- password field end --> 
                </div>
                <div class="form-control w-full">
                    <label class="label"><span class="label-text">Role *</span></label>
                    <select id="regEmpRole" class="select select-bordered w-full">
                        <option value="superadmin">Superadmin</option>
                        <option value="Admin">Admin</option>
                        <option value="Manager">Manager</option>
                        <option value="Staff">Staff</option>
                    </select>
                </div>
                <div class="form-control w-full">
                    <label class="label"><span class="label-text">Status *</span></label>
                    <select id="regEmpStatus" class="select select-bordered w-full">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-action">
                <button class="btn" id="regEmpCancel">Cancel</button>
                <button class="btn btn-primary" id="regEmpSave">Register Account</button>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <!-- Register Vendor Modal -->
    <dialog id="registerVendorModal" class="modal">
        <div class="modal-box w-11/12 max-w-2xl">
            <h3 class="font-bold text-lg">Register Vendor Account</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 py-4">
                <div class="form-control w-full">
                    <label class="label"><span class="label-text">First Name *</span></label>
                    <input type="text" id="regVenFirstname" class="input input-bordered w-full" required />
                </div>
                <div class="form-control w-full">
                    <label class="label"><span class="label-text">Middle Name (Optional)</span></label>
                    <input type="text" id="regVenMiddlename" class="input input-bordered w-full" />
                </div>
                <div class="form-control w-full">
                    <label class="label"><span class="label-text">Last Name *</span></label>
                    <input type="text" id="regVenLastname" class="input input-bordered w-full" required />
                </div>
                <div class="form-control w-full">
                    <label class="label"><span class="label-text">Email *</span></label>
                    <input type="email" id="regVenEmail" class="input input-bordered w-full" required />
                </div>
                <div class="form-control w-full">
                     <!-- password field start --> 
                     <label class="block relative"> 
                         <span class="label-text">Account Password *</span> 
                         <div class="relative mt-1"> 
                             <!-- left lock icon --> 
                             <span class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"> 
                                 <i class="bx bxs-lock text-gray-400 text-lg" aria-hidden="true"></i> 
                             </span> 
                             <input id="regVenPassword" name="password" type="password" placeholder="Password" class="input input-bordered w-full pl-10 pr-12 mt-0" aria-label="Password" required/> 
                             <!-- toggle show/hide button --> 
                             <button type="button" onclick="togglePassword(this)" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500" aria-pressed="false" aria-label="Show password"> 
                                 <i class="bx bx-show-alt text-lg"></i> 
                             </button> 
                         </div> 
                     </label> 
                     <!-- password field end --> 
                </div>
                <div class="form-control w-full">
                    <label class="label"><span class="label-text">Status *</span></label>
                    <select id="regVenStatus" class="select select-bordered w-full">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
            </div>
            <div class="modal-action">
                <button class="btn" id="regVenCancel">Cancel</button>
                <button class="btn btn-primary" id="regVenSave">Register Account</button>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>

    <!-- View Account Modal -->
    <dialog id="viewAccountModal" class="modal">
        <div class="modal-box w-11/12 max-w-2xl">
            <h3 class="font-bold text-lg mb-4">Account Details</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="viewAccountContent">
                <!-- Fields will be injected here -->
                <div class="form-control w-full">
                    <label class="label"><span class="label-text font-semibold">Account ID</span></label>
                    <input type="text" id="viewId" class="input input-bordered w-full" readonly />
                </div>
                <div class="form-control w-full">
                    <label class="label"><span class="label-text font-semibold">Full Name</span></label>
                    <input type="text" id="viewName" class="input input-bordered w-full" readonly />
                </div>
                <div class="form-control w-full">
                    <label class="label"><span class="label-text font-semibold">Email</span></label>
                    <input type="text" id="viewEmail" class="input input-bordered w-full" readonly />
                </div>
                <div class="form-control w-full">
                    <label class="label"><span class="label-text font-semibold">Role</span></label>
                    <input type="text" id="viewRole" class="input input-bordered w-full" readonly />
                </div>
                <div class="form-control w-full">
                    <label class="label"><span class="label-text font-semibold">Status</span></label>
                    <input type="text" id="viewStatus" class="input input-bordered w-full capitalize" readonly />
                </div>
                <div class="form-control w-full">
                    <label class="label"><span class="label-text font-semibold">Last Login</span></label>
                    <input type="text" id="viewLastLogin" class="input input-bordered w-full" readonly />
                </div>
            </div>
            <div class="modal-action">
                <button class="btn" onclick="document.getElementById('viewAccountModal').close()">Close</button>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
</div>

<script>
    // Global function for password toggle
    window.togglePassword = function(btn) {
        if (!btn || typeof btn.closest !== 'function') return;
        
        const container = btn.closest('.relative');
        if (!container) return;

        const input = container.querySelector('input');
        const icon = btn.querySelector('i');
        
        if (!input || !icon) return;

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bx-show-alt');
            icon.classList.add('bx-hide');
            btn.setAttribute('aria-pressed', 'true');
            btn.setAttribute('aria-label', 'Hide password');
        } else {
            input.type = 'password';
            icon.classList.remove('bx-hide');
            icon.classList.add('bx-show-alt');
            btn.setAttribute('aria-pressed', 'false');
            btn.setAttribute('aria-label', 'Show password');
        }
    };

    (function() {
        let currentPage = 1;
        let currentType = 'employee';
        let currentSearch = '';
        let totalPages = 1;
        let currentActionId = null;
        const csrfToken = "{{ csrf_token() }}";

        function getAuthHeaders() {
            const token = localStorage.getItem('jwt');
            return {
                'Authorization': token ? `Bearer ${token}` : '',
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken,
                'X-Requested-With': 'XMLHttpRequest'
            };
        }

        // SweetAlert Mixin
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

        const tableHeaders = {
            employee: ['Employee ID', 'Name', 'Email', 'Role', 'Status', 'Last Login', 'Actions'],
            vendor: ['Vendor ID', 'Contact Person', 'Email', 'Status', 'Last Login', 'Actions']
        };

        const filterSelect = document.getElementById('accountTypeFilter');
        const searchInput = document.getElementById('accountSearch');
        const prevBtn = document.getElementById('accountPrevBtn');
        const nextBtn = document.getElementById('accountNextBtn');
        const accountsTableBody = document.getElementById('accountsTableBody');

        function init() {
            filterSelect.addEventListener('change', (e) => {
                currentType = e.target.value;
                currentPage = 1;
                updateTableHeaders();
                updateRegisterButtons();
                loadAccounts();
            });

            searchInput.addEventListener('input', debounce((e) => {
                currentSearch = e.target.value;
                currentPage = 1;
                loadAccounts();
            }, 500));

            prevBtn.addEventListener('click', () => {
                if (currentPage > 1) {
                    currentPage--;
                    loadAccounts();
                }
            });

            nextBtn.addEventListener('click', () => {
                if (currentPage < totalPages) {
                    currentPage++;
                    loadAccounts();
                }
            });
            
            // Event delegation for table actions
            accountsTableBody.addEventListener('click', function(e) {
                let target = e.target;
                // Handle text nodes (nodeType 3) by getting parent
                if (target.nodeType === 3) target = target.parentElement;
                
                if (!target || typeof target.closest !== 'function') return;

                const btn = target.closest('button');
                if (!btn) return;
                
                const action = btn.dataset.action;
                const id = btn.dataset.id;
                
                if (action === 'change-role') {
                    const role = btn.dataset.role;
                    openRoleModal(id, role);
                } else if (action === 'change-status') {
                    const status = btn.dataset.status;
                    openStatusModal(id, status);
                } else if (action === 'view-account') {
                    openViewModal(id);
                } else if (action === 'delete-account') {
                    deleteAccount(id);
                }
            });
            
            // Modal event listeners
            document.getElementById('roleModalSave').addEventListener('click', submitRoleChange);
            document.getElementById('roleModalCancel').addEventListener('click', closeRoleModal);
            
            document.getElementById('statusModalSave').addEventListener('click', submitStatusChange);
            document.getElementById('statusModalCancel').addEventListener('click', closeStatusModal);

            // Register Modal Listeners
            document.getElementById('btnRegisterEmployee').addEventListener('click', openRegisterEmployeeModal);
            document.getElementById('btnRegisterVendor').addEventListener('click', openRegisterVendorModal);
            
            document.getElementById('regEmpCancel').addEventListener('click', closeRegisterEmployeeModal);
            document.getElementById('regEmpSave').addEventListener('click', submitRegisterEmployee);
            
            document.getElementById('regVenCancel').addEventListener('click', closeRegisterVendorModal);
            document.getElementById('regVenSave').addEventListener('click', submitRegisterVendor);

            updateTableHeaders();
            updateRegisterButtons();
            loadAccounts();
            loadStats();
        }

        function updateRegisterButtons() {
            const btnEmployee = document.getElementById('btnRegisterEmployee');
            const btnVendor = document.getElementById('btnRegisterVendor');
            if (currentType === 'employee') {
                btnEmployee.classList.remove('hidden');
                btnVendor.classList.add('hidden');
            } else {
                btnEmployee.classList.add('hidden');
                btnVendor.classList.remove('hidden');
            }
        }

        function updateTableHeaders() {
            const headers = tableHeaders[currentType];
            const headerRow = document.getElementById('tableHeaderRow');
            headerRow.innerHTML = headers.map(h => 
                `<th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider whitespace-nowrap">${h}</th>`
            ).join('');
        }

        async function loadStats() {
            try {
                const response = await fetch('/api/v1/user-management/stats', {
                    headers: getAuthHeaders()
                });
                if (!response.ok) throw new Error('Failed to load stats');
                const data = await response.json();
                
                document.getElementById('statTotalEmployee').textContent = data.total_employee;
                document.getElementById('statTotalVendor').textContent = data.total_vendor;
                document.getElementById('statActive').textContent = data.active;
                document.getElementById('statInactive').textContent = data.inactive;
            } catch (e) {
                console.error(e);
            }
        }

        async function loadAccounts() {
            const tbody = document.getElementById('accountsTableBody');
            tbody.innerHTML = `
                <tr>
                    <td colspan="100%" class="px-6 py-4 text-center text-gray-500">
                        <div class="flex justify-center items-center py-4">
                            <span class="loading loading-spinner loading-md mr-2"></span> Loading accounts...
                        </div>
                    </td>
                </tr>
            `;

            try {
                const query = new URLSearchParams({
                    page: currentPage,
                    type: currentType,
                    search: currentSearch
                });

                const response = await fetch(`/api/v1/user-management/accounts?${query}`, {
                    headers: getAuthHeaders()
                });
                if (!response.ok) throw new Error('Error loading accounts');
                
                const data = await response.json();
                renderTable(data.data);
                updatePagination(data);
            } catch (e) {
                console.error(e);
                tbody.innerHTML = `<tr><td colspan="100%" class="text-center py-4 text-red-500">Error loading accounts</td></tr>`;
            }
        }

        function renderTable(accounts) {
            const tbody = document.getElementById('accountsTableBody');
            if (!accounts || accounts.length === 0) {
                tbody.innerHTML = `<tr><td colspan="100%" class="text-center py-4 text-gray-500">No accounts found</td></tr>`;
                return;
            }

            tbody.innerHTML = accounts.map(acc => {
                const viewBtn = `<button class="text-indigo-600 transition-colors p-2 rounded-lg hover:bg-gray-50" title="View" data-action="view-account" data-id="${acc.id}"><i class='bx bx-show-alt text-xl pointer-events-none'></i></button>`;
                const changeStatusBtn = `<button class="text-orange-600 transition-colors p-2 rounded-lg hover:bg-gray-50" title="Change Status" data-action="change-status" data-id="${acc.id}" data-status="${acc.status}"><i class='bx bx-refresh text-xl pointer-events-none'></i></button>`;
                const deleteBtn = `<button class="text-red-600 transition-colors p-2 rounded-lg hover:bg-gray-50" title="Delete Account" data-action="delete-account" data-id="${acc.id}"><i class='bx bx-trash text-xl pointer-events-none'></i></button>`;
                
                // Status Logic
                const isActive = acc.status === 'active';
                const statusClass = isActive ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800';
                const statusIcon = isActive ? "<i class='bx bxs-check-circle'></i>" : "<i class='bx bxs-x-circle'></i>";
                const statusHtml = `
                    <span class="px-2 inline-flex items-center gap-1 text-xs leading-5 font-semibold rounded-full ${statusClass} capitalize">
                        ${statusIcon} ${acc.status}
                    </span>
                `;

                if (currentType === 'employee') {
                    const changeRoleBtn = `<button class="text-blue-600 transition-colors p-2 rounded-lg hover:bg-gray-50" title="Change Role" data-action="change-role" data-id="${acc.id}" data-role="${acc.roles || ''}"><i class='bx bx-user-circle text-xl pointer-events-none'></i></button>`;
                    
                    return `
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${acc.employeeid || '-'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${acc.firstname} ${acc.lastname}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${acc.email}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 capitalize">${acc.roles || '-'}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                ${statusHtml}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDate(acc.last_login)}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex gap-1">
                                    ${viewBtn}
                                    ${changeRoleBtn}
                                    ${changeStatusBtn}
                                    ${deleteBtn}
                                </div>
                            </td>
                        </tr>
                    `;
                } else {
                    const fullname = `${acc.firstname} ${acc.middlename ? acc.middlename + ' ' : ''}${acc.lastname || ''}`;
                    return `
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${acc.vendorid || '-'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${fullname}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${acc.email}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                ${statusHtml}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${formatDate(acc.last_login)}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex gap-1">
                                    ${viewBtn}
                                    ${changeStatusBtn}
                                    ${deleteBtn}
                                </div>
                            </td>
                        </tr>
                    `;
                }
            }).join('');
        }

        function updatePagination(data) {
            totalPages = data.last_page;
            document.getElementById('accountPageDisplay').textContent = `${data.current_page} / ${data.last_page}`;
            document.getElementById('accountPagerInfo').textContent = `Showing ${data.from || 0} to ${data.to || 0} of ${data.total} results`;
            
            document.getElementById('accountPrevBtn').disabled = data.current_page <= 1;
            document.getElementById('accountNextBtn').disabled = data.current_page >= data.last_page;
        }

        function formatDate(dateString) {
            if (!dateString) return 'Never';
            const date = new Date(dateString);
            const datePart = date.toLocaleDateString('en-US', { month: '2-digit', day: '2-digit', year: '2-digit' });
            const timePart = date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', second: '2-digit', hour12: true });
            return `${datePart} ${timePart}`;
        }

        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }
        
        // Modal Functions
        function openRoleModal(id, role) {
            currentActionId = id;
            document.getElementById('roleSelect').value = role || 'User';
            document.getElementById('roleModal').showModal();
        }
        
        function closeRoleModal() {
            document.getElementById('roleModal').close();
            currentActionId = null;
        }
        
        function openStatusModal(id, status) {
            currentActionId = id;
            document.getElementById('statusSelect').value = status || 'active';
            document.getElementById('statusModal').showModal();
        }
        
        function closeStatusModal() {
            document.getElementById('statusModal').close();
            currentActionId = null;
        }

        // Register Modal Functions
        function openRegisterEmployeeModal() {
            document.getElementById('registerEmployeeModal').showModal();
        }

        function closeRegisterEmployeeModal() {
            document.getElementById('registerEmployeeModal').close();
            // Clear fields
            document.getElementById('regEmpFirstname').value = '';
            document.getElementById('regEmpMiddlename').value = '';
            document.getElementById('regEmpLastname').value = '';
            document.getElementById('regEmpEmail').value = '';
            document.getElementById('regEmpPassword').value = '';
            document.getElementById('regEmpRole').value = 'Staff';
            document.getElementById('regEmpStatus').value = 'active';
        }

        function openRegisterVendorModal() {
            document.getElementById('registerVendorModal').showModal();
        }

        function closeRegisterVendorModal() {
            document.getElementById('registerVendorModal').close();
            // Clear fields
            document.getElementById('regVenFirstname').value = '';
            document.getElementById('regVenMiddlename').value = '';
            document.getElementById('regVenLastname').value = '';
            document.getElementById('regVenEmail').value = '';
            document.getElementById('regVenPassword').value = '';
            document.getElementById('regVenStatus').value = 'active';
        }

        async function openViewModal(id) {
            try {
                const response = await fetch(`/api/v1/user-management/accounts/${id}?type=${currentType}`, {
                    headers: getAuthHeaders()
                });
                if (!response.ok) throw new Error('Failed to fetch details');
                const data = await response.json();

                // Populate fields
                document.getElementById('viewId').value = currentType === 'employee' ? data.employeeid : data.vendorid;
                document.getElementById('viewName').value = `${data.firstname} ${data.middlename ? data.middlename + ' ' : ''}${data.lastname}`;
                document.getElementById('viewEmail').value = data.email;
                document.getElementById('viewRole').value = data.roles || 'Vendor';
                document.getElementById('viewStatus').value = data.status;
                document.getElementById('viewLastLogin').value = formatDate(data.last_login);
                
                document.getElementById('viewAccountModal').showModal();
            } catch (e) {
                console.error(e);
                Swal.fire('Error', 'Could not load account details', 'error');
            }
        }

        async function deleteAccount(id) {
            const result = await Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            });

            if (result.isConfirmed) {
                try {
                    const response = await fetch(`/api/v1/user-management/accounts/${id}?type=${currentType}`, {
                        method: 'DELETE',
                        headers: getAuthHeaders()
                    });
                    
                    if (response.ok) {
                        Toast.fire({
                            icon: 'success',
                            title: 'Account deleted successfully'
                        });
                        loadAccounts();
                        loadStats();
                    } else {
                        Swal.fire('Error', 'Failed to delete account', 'error');
                    }
                } catch (e) {
                    console.error(e);
                    Swal.fire('Error', 'An error occurred', 'error');
                }
            }
        }

        async function submitRegisterEmployee() {
            const btn = document.getElementById('regEmpSave');
            const originalText = btn.textContent;
            
            const firstname = document.getElementById('regEmpFirstname').value;
            const middlename = document.getElementById('regEmpMiddlename').value;
            const lastname = document.getElementById('regEmpLastname').value;
            const email = document.getElementById('regEmpEmail').value;
            const password = document.getElementById('regEmpPassword').value;
            const role = document.getElementById('regEmpRole').value;
            const status = document.getElementById('regEmpStatus').value;

            if (!firstname || !lastname || !email || !password) {
                Swal.fire('Error', 'Please fill in all required fields', 'error');
                return;
            }

            btn.disabled = true;
            btn.textContent = 'Registering...';

            try {
                const response = await fetch('/api/v1/user-management/accounts/employee', {
                    method: 'POST',
                    headers: getAuthHeaders(),
                    body: JSON.stringify({ firstname, middlename, lastname, email, password, role, status })
                });

                if (response.ok) {
                    closeRegisterEmployeeModal();
                    loadAccounts();
                    loadStats();
                    Toast.fire({
                        icon: 'success',
                        title: 'Employee registered successfully'
                    });
                } else {
                    const data = await response.json();
                    Swal.fire('Error', data.message || 'Failed to register employee', 'error');
                }
            } catch (e) {
                console.error(e);
                Swal.fire('Error', 'Error registering employee', 'error');
            } finally {
                btn.disabled = false;
                btn.textContent = originalText;
            }
        }

        async function submitRegisterVendor() {
            const btn = document.getElementById('regVenSave');
            const originalText = btn.textContent;
            
            const firstname = document.getElementById('regVenFirstname').value;
            const middlename = document.getElementById('regVenMiddlename').value;
            const lastname = document.getElementById('regVenLastname').value;
            const email = document.getElementById('regVenEmail').value;
            const password = document.getElementById('regVenPassword').value;
            const status = document.getElementById('regVenStatus').value;

            if (!firstname || !lastname || !email || !password) {
                Swal.fire('Error', 'Please fill in all required fields', 'error');
                return;
            }

            btn.disabled = true;
            btn.textContent = 'Registering...';

            try {
                const response = await fetch('/api/v1/user-management/accounts/vendor', {
                    method: 'POST',
                    headers: getAuthHeaders(),
                    body: JSON.stringify({ firstname, middlename, lastname, email, password, status })
                });

                if (response.ok) {
                    closeRegisterVendorModal();
                    loadAccounts();
                    loadStats();
                    Toast.fire({
                        icon: 'success',
                        title: 'Vendor registered successfully'
                    });
                } else {
                    const data = await response.json();
                    Swal.fire('Error', data.message || 'Failed to register vendor', 'error');
                }
            } catch (e) {
                console.error(e);
                Swal.fire('Error', 'Error registering vendor', 'error');
            } finally {
                btn.disabled = false;
                btn.textContent = originalText;
            }
        }
        
        async function submitRoleChange() {
            if (!currentActionId) return;
            const role = document.getElementById('roleSelect').value;
            const btn = document.getElementById('roleModalSave');
            const originalText = btn.textContent;
            btn.disabled = true;
            btn.textContent = 'Saving...';
            
            try {
                const response = await fetch(`/api/v1/user-management/accounts/${currentActionId}/role`, {
                    method: 'PUT',
                    headers: getAuthHeaders(),
                    body: JSON.stringify({ role })
                });
                
                if (response.ok) {
                    closeRoleModal();
                    loadAccounts(); 
                    Toast.fire({
                        icon: 'success',
                        title: 'Role updated successfully'
                    });
                } else {
                    Swal.fire('Error', 'Failed to update role', 'error');
                }
            } catch (e) {
                console.error(e);
                Swal.fire('Error', 'Error updating role', 'error');
            } finally {
                btn.disabled = false;
                btn.textContent = originalText;
            }
        }
        
        async function submitStatusChange() {
            if (!currentActionId) return;
            const status = document.getElementById('statusSelect').value;
            const btn = document.getElementById('statusModalSave');
            const originalText = btn.textContent;
            btn.disabled = true;
            btn.textContent = 'Saving...';
            
            try {
                const response = await fetch(`/api/v1/user-management/accounts/${currentActionId}/status`, {
                    method: 'PUT',
                    headers: getAuthHeaders(),
                    body: JSON.stringify({ status, type: currentType })
                });
                
                if (response.ok) {
                    closeStatusModal();
                    loadAccounts();
                    loadStats();
                    Toast.fire({
                        icon: 'success',
                        title: 'Status updated successfully'
                    });
                } else {
                    Swal.fire('Error', 'Failed to update status', 'error');
                }
            } catch (e) {
                console.error(e);
                Swal.fire('Error', 'Error updating status', 'error');
            } finally {
                btn.disabled = false;
                btn.textContent = originalText;
            }
        }

        init();
    })();
</script>
