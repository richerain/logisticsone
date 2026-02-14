// Externalized script for PSM Purchase Requisition (ASCII-safe)
(function() {
    const API_URL = '/api/v1/psm/requisitions';
    const JWT_TOKEN = localStorage.getItem('jwt');
    let currentPage = 1;
    let totalPages = 1;
    let currentFilters = {
        status: '',
        dept: '',
        search: '',
        per_page: 10
    };
    let currentRequisitions = [];

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

    const modal = document.getElementById('requisitionModal');
    const form = document.getElementById('requisitionForm');
    const addBtn = document.getElementById('addRequisitionBtn');
    const cancelBtn = document.getElementById('cancelModalBtn');
    const searchInput = document.getElementById('searchInput');
    const statusFilter = document.getElementById('statusFilter');
    const deptFilter = document.getElementById('deptFilter');
    const prevBtn = document.getElementById('purchasesPrevBtn');
    const nextBtn = document.getElementById('purchasesNextBtn');
    const pageDisplay = document.getElementById('purchasesPageDisplay');
    const pagerInfo = document.getElementById('purchasesPagerInfo');
    const addItemBtn = document.getElementById('addItemBtn');
    const itemsContainer = document.getElementById('itemsContainer');
    
    const vendorSelect = document.getElementById('req_vendor_select');
    const vendorProductsModal = document.getElementById('vendorProductsModal');
    const closeSideModalBtn = document.getElementById('closeSideModal');
    const vendorProductsTableBody = document.getElementById('vendorProductsTableBody');
    const productSearchInput = document.getElementById('productSearch');
    const sideModalVendorName = document.getElementById('sideModalVendorName');
    
    const sideRequisitionForm = document.getElementById('sideRequisitionForm');
    const sideItemsContainer = document.getElementById('sideItemsContainer');
    const sideAddItemBtn = document.getElementById('sideAddItemBtn');
    const sideCancelBtn = document.getElementById('sideCancelBtn');
    
    let allVendors = [];
    function getVendorName(vendorId) {
        if (!vendorId) return '-';
        const vendor = allVendors.find(v => v.ven_id == vendorId);
        return vendor ? vendor.ven_company_name : vendorId;
    }
    let currentVendorProducts = [];
    let selectedItems = new Map();
    let selectedItemPrices = new Map();

    async function fetchVendors() {
        try {
            const response = await fetch('/api/v1/psm/vendors', {
                headers: { 'Authorization': 'Bearer ' + JWT_TOKEN, 'Accept': 'application/json' }
            });
            const result = await response.json();
            if (result.success) {
                allVendors = result.data;
                populateVendorDropdown(allVendors);
            }
        } catch (error) {
            console.error('Error fetching vendors:', error);
        }
    }

    function populateVendorDropdown(vendors) {
        vendorSelect.innerHTML = '<option value="">Choose a Vendor Company...</option>';
        vendors.forEach(vendor => {
            const option = document.createElement('option');
            option.value = vendor.ven_id;
            option.textContent = vendor.ven_company_name + ' (' + vendor.ven_type + ')';
            vendorSelect.appendChild(option);
        });
    }

    async function fetchVendorProducts(vendorId) {
        try {
            const response = await fetch('/api/v1/psm/product-management/by-vendor/' + vendorId, {
                headers: { 'Authorization': 'Bearer ' + JWT_TOKEN, 'Accept': 'application/json' }
            });
            const result = await response.json();
            if (result.success) {
                currentVendorProducts = result.data;
                renderVendorProducts(currentVendorProducts);
            }
        } catch (error) {
            console.error('Error fetching vendor products:', error);
        }
    }

    function renderVendorProducts(products) {
        const searchTerm = productSearchInput.value.toLowerCase();
        const filtered = products.filter(p => 
            p.prod_name.toLowerCase().includes(searchTerm) || 
            (p.prod_desc && p.prod_desc.toLowerCase().includes(searchTerm)) ||
            p.prod_id.toLowerCase().includes(searchTerm)
        );

        if (filtered.length === 0) {
            vendorProductsTableBody.innerHTML = 
                '<tr>' +
                    '<td colspan="4" class="px-4 py-8 text-center text-gray-500 text-sm">' +
                        'No products found matching "' + searchTerm + '"' +
                    '</td>' +
                '</tr>';
            return;
        }

        vendorProductsTableBody.innerHTML = filtered.map(p => 
            '<tr class="hover:bg-gray-50 transition-colors">' +
                '<td class="px-4 py-3 whitespace-nowrap">' +
                    '<div class="text-xs font-bold text-gray-900">' + p.prod_name + '</div>' +
                    '<div class="text-[10px] text-gray-500">' + p.prod_id + '</div>' +
                '</td>' +
                '<td class="px-4 py-3 text-[11px] text-gray-600 max-w-[150px] truncate" title="' + (p.prod_desc || '') + '">' +
                    (p.prod_desc || '-') +
                '</td>' +
                '<td class="px-4 py-3 text-[11px] font-bold text-blue-600 whitespace-nowrap">' +
                    'PHP ' + parseFloat(p.prod_price || 0).toLocaleString(undefined, {minimumFractionDigits: 2, maximumFractionDigits: 2}) +
                '</td>' +
                '<td class="px-4 py-3 text-right">' +
                    '<button type="button" onclick="window.addItemToRequisition(\'' + p.prod_name.replace(/'/g, "\\'") + '\', ' + (p.prod_price || 0) + ')" ' +
                        'class="p-1.5 bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white rounded-lg transition-all active:scale-90">' +
                        '<i class="bx bx-plus text-lg"></i>' +
                    '</button>' +
                '</td>' +
            '</tr>'
        ).join('');
    }

    window.addItemToRequisition = (itemName, price) => {
        if (selectedItems.has(itemName)) {
            selectedItems.set(itemName, selectedItems.get(itemName) + 1);
        } else {
            selectedItems.set(itemName, 1);
            selectedItemPrices.set(itemName, price);
        }
        updateSideItemsContainer();
        Toast.fire({ icon: 'success', title: 'Added ' + itemName + ' to list' });
    };

    function updateSideItemsContainer() {
        sideItemsContainer.innerHTML = '';
        if (selectedItems.size === 0) {
            addEmptySideItemRow();
            return;
        }
        selectedItems.forEach((count, name) => {
            const price = selectedItemPrices.get(name) || 0;
            const displayValue = count > 1 ? name + ' (x' + count + ')' : name;
            addSideItemRow(displayValue, false, name, count, price);
        });
    }

    function addSideItemRow(value = '', disabled = false, originalName = null, count = 1, price = 0) {
        const div = document.createElement('div');
        div.className = 'flex flex-col gap-2 p-3 bg-gray-50 rounded-lg border border-gray-200 item-row mb-2';
        div.dataset.originalName = originalName || value;
        
        const totalPrice = count * price;
        
        div.innerHTML = 
            '<div class="flex gap-2 items-center">' +
                '<input type="text" name="items[]" required value="' + value + '" ' + (disabled ? 'disabled' : '') + ' ' +
                    'placeholder="Enter item name/description" class="flex-1 px-3 py-1.5 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">' +
                (!disabled ? 
                '<button type="button" class="remove-side-item p-1.5 text-red-500 hover:bg-red-100 rounded-lg transition-colors">' +
                    '<i class="bx bx-trash text-lg"></i>' +
                '</button>' : '') +
            '</div>' +
            (originalName ? 
            '<div class="flex items-center justify-between text-[11px] text-gray-500 px-1">' +
                '<div class="flex items-center gap-2">' +
                    '<span>Price: PHP ' + parseFloat(price).toLocaleString(undefined, {minimumFractionDigits: 2}) + '</span>' +
                    '<span>&times;</span>' +
                    '<span>Qty: ' + count + '</span>' +
                '</div>' +
                '<div class="font-bold text-blue-600">' +
                    'Total: PHP ' + parseFloat(totalPrice).toLocaleString(undefined, {minimumFractionDigits: 2}) +
                '</div>' +
            '</div>' : '');
        sideItemsContainer.appendChild(div);
    }

    function addEmptySideItemRow() {
        addSideItemRow('', false);
    }

    vendorSelect.addEventListener('change', (e) => {
        const venId = e.target.value;
        if (venId) {
                const vendor = allVendors.find(v => v.ven_id == venId);
                sideModalVendorName.textContent = vendor.ven_company_name + ' Products';
                
                const displayFields = ['req_chosen_vendor_display', 'side_req_chosen_vendor_display'];
                displayFields.forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.value = vendor.ven_company_name;
                });

                const idFields = ['req_chosen_vendor', 'side_req_chosen_vendor'];
                idFields.forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.value = vendor.ven_id;
                });

                fetchVendorProducts(venId);
            } else {
                vendorProductsTableBody.innerHTML = 
                    '<tr>' +
                        '<td colspan="3" class="px-4 py-8 text-center text-gray-500 text-sm">' +
                            'Please select a vendor to see products' +
                        '</td>' +
                    '</tr>';
                sideModalVendorName.textContent = 'Vendor Products';

                const fields = ['req_chosen_vendor', 'side_req_chosen_vendor', 'req_chosen_vendor_display', 'side_req_chosen_vendor_display'];
                fields.forEach(id => {
                    const el = document.getElementById(id);
                    if (el) el.value = '';
                });
            }
    });

    closeSideModalBtn.addEventListener('click', () => {
        vendorProductsModal.classList.add('hidden');
    });

    sideCancelBtn.addEventListener('click', () => {
        vendorProductsModal.classList.add('hidden');
    });

    productSearchInput.addEventListener('input', () => {
        renderVendorProducts(currentVendorProducts);
    });

    sideAddItemBtn.addEventListener('click', addEmptySideItemRow);

    sideItemsContainer.addEventListener('click', (e) => {
        if (e.target.closest('.remove-side-item')) {
            const row = e.target.closest('.item-row');
            const name = row.dataset.originalName;
            
            if (selectedItems.has(name)) {
                selectedItems.delete(name);
            }
            
            const rows = sideItemsContainer.querySelectorAll('.item-row');
            if (rows.length > 1) row.remove();
            else {
                row.remove();
                addEmptySideItemRow();
            }
        }
    });

    sideRequisitionForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(sideRequisitionForm);
        
        let totalReqPrice = 0;
        selectedItems.forEach((count, name) => {
            const price = selectedItemPrices.get(name) || 0;
            totalReqPrice += (count * price);
        });

        const customRows = sideItemsContainer.querySelectorAll('.item-row:not([data-original-name])');
        customRows.forEach(row => {
            const input = row.querySelector('input[name="items[]"]');
            if (input && input.value.trim() !== '') {
                // custom items contribute 0 to total price
            }
        });

        const data = {
            req_id: generateReqID(),
            req_date: new Date().toISOString().split('T')[0],
            req_requester: formData.get('req_requester'),
            req_dept: formData.get('req_dept'),
            req_note: formData.get('req_note'),
            req_items: Array.from(formData.getAll('items[]')).filter(i => i.trim() !== ''),
            req_chosen_vendor: formData.get('req_chosen_vendor'),
            req_price: totalReqPrice,
            req_status: 'Pending'
        };

        if (data.req_items.length === 0) {
            Toast.fire({ icon: 'error', title: 'Please add at least one item' });
            return;
        }

        try {
            const response = await fetch(API_URL, {
                method: 'POST',
                headers: {
                    'Authorization': 'Bearer ' + JWT_TOKEN,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify(data)
            });
            const result = await response.json();
            if (result.success) {
                Toast.fire({ icon: 'success', title: 'Requisition submitted successfully' });
                vendorProductsModal.classList.add('hidden');
                fetchRequisitions(1);
            } else {
                Toast.fire({ icon: 'error', title: result.message || 'Failed to submit' });
            }
        } catch (error) {
            console.error('Error submitting requisition:', error);
            Toast.fire({ icon: 'error', title: 'Error connecting to server' });
        }
    });

    addBtn.addEventListener('click', () => {
        sideRequisitionForm.reset();
        selectedItems.clear();
        selectedItemPrices.clear();
        updateSideItemsContainer();
        fetchVendors();
        vendorProductsModal.classList.remove('hidden');
    });

    const statusModal = document.getElementById('statusModal');
    const cancelStatusBtn = document.getElementById('cancelStatusBtn');
    const approveStatusBtn = document.getElementById('approveStatusBtn');
    const rejectStatusBtn = document.getElementById('rejectStatusBtn');
    const hideModal = () => modal.classList.add('hidden');
    cancelBtn.addEventListener('click', hideModal);
    let activeStatusId = null;

    function generateReqID() {
        const now = new Date();
        const dateStr = now.getFullYear().toString() + 
                       (now.getMonth() + 1).toString().padStart(2, '0') + 
                       now.getDate().toString().padStart(2, '0');
        const random = Math.random().toString(36).substring(2, 7).toUpperCase();
        return 'REQN' + dateStr + random;
    }

    function showModal(mode = 'new', data = null) {
        form.reset();
        itemsContainer.innerHTML = '';
        const modalTitle = document.getElementById('modalTitle');
        const submitBtn = document.getElementById('submitBtn');
        const createContent = document.getElementById('createModeContent');
        const viewContent = document.getElementById('viewModeContent');
        const inputs = form.querySelectorAll('input, select, textarea');
        
        if (mode === 'new') {
            modalTitle.textContent = 'New Purchase Requisition';
            submitBtn.classList.remove('hidden');
            createContent.classList.remove('hidden');
            viewContent.classList.add('hidden');
            inputs.forEach(i => { if(i.id !== 'req_id') i.disabled = false; });
            document.getElementById('req_id').value = generateReqID();
            document.getElementById('req_date').value = new Date().toISOString().split('T')[0];
            addEmptyItemRow();
        } else if (mode === 'view') {
            modalTitle.textContent = 'Requisition Details';
            submitBtn.classList.add('hidden');
            createContent.classList.add('hidden');
            viewContent.classList.remove('hidden');
            
            document.getElementById('view_req_id').textContent = data.req_id;
            document.getElementById('view_req_date').textContent = new Date(data.req_date).toLocaleDateString();
            document.getElementById('view_req_requester').textContent = data.req_requester;
            document.getElementById('view_req_dept').textContent = data.req_dept;
            document.getElementById('view_req_chosen_vendor').textContent = getVendorName(data.req_chosen_vendor);
            
            const overallPrice = parseFloat(data.req_price || 0).toLocaleString(undefined, {minimumFractionDigits: 2});
            const overallEl = document.getElementById('view_req_overall_price');
            if (overallEl) {
                overallEl.setAttribute('data-price', 'PHP ' + overallPrice);
                overallEl.setAttribute('data-masked', '1');
                overallEl.textContent = '*****';
            }
            
            document.getElementById('view_req_note').textContent = data.req_note || 'No additional notes.';
            
            const statusBadge = document.getElementById('view_req_status');
            statusBadge.innerHTML = 
                '<span class="px-3 py-1.5 rounded-full text-sm font-bold flex items-center gap-1.5 ' + getStatusBadgeClass(data.req_status) + '">' +
                    getStatusIcon(data.req_status) + ' ' +
                    data.req_status +
                '</span>';

            const items = Array.isArray(data.req_items) ? data.req_items : JSON.parse(data.req_items || '[]');
            const itemsList = document.getElementById('view_items_list');
            itemsList.innerHTML = items.map(item => 
                '<div class="flex items-center gap-3 p-3 bg-white border border-gray-100 rounded-lg shadow-sm">' +
                    '<i class="bx bx-check text-green-500 font-bold"></i>' +
                    '<span class="text-sm text-gray-700 font-medium">' + item + '</span>' +
                '</div>'
            ).join('');
        }
        
        modal.classList.remove('hidden');
        setTimeout(() => modal.querySelector('div').classList.remove('scale-95'), 10);
    }

    function addEmptyItemRow() {
        addItemRow('', false);
    }

    function addItemRow(value = '', disabled = false) {
        const div = document.createElement('div');
        div.className = 'flex gap-2 item-row';
        div.innerHTML = 
            '<input type="text" name="items[]" required value="' + value + '" ' + (disabled ? 'disabled' : '') + ' ' +
                'placeholder="Enter item name/description" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">' +
            (!disabled ? 
            '<button type="button" class="remove-item px-3 py-2 text-red-500 hover:bg-red-50 rounded-lg transition-colors">' +
                '<i class="bx bx-trash text-xl"></i>' +
            '</button>' : '');
        itemsContainer.appendChild(div);
    }

    addItemBtn.addEventListener('click', addEmptyItemRow);

    itemsContainer.addEventListener('click', (e) => {
        if (e.target.closest('.remove-item')) {
            const rows = itemsContainer.querySelectorAll('.item-row');
            if (rows.length > 1) e.target.closest('.item-row').remove();
            else Toast.fire({ icon: 'warning', title: 'At least one item is required' });
        }
    });

    async function fetchRequisitions(page = 1) {
        try {
            const params = new URLSearchParams({ page, ...currentFilters });
            if (page === 1) {
                try {
                    await fetch('/api/v1/psm/requisitions/import-external', {
                        method: 'POST',
                        headers: { 'Authorization': 'Bearer ' + JWT_TOKEN, 'Accept': 'application/json' }
                    });
                } catch (syncErr) {
                    console.warn('External requisition import failed:', syncErr);
                }
            }

            const response = await fetch(API_URL + '?' + params, {
                headers: { 'Authorization': 'Bearer ' + JWT_TOKEN, 'Accept': 'application/json' }
            });
            const result = await response.json();
            
            let allRequisitions = [];
            let stats = result.stats || { total: 0, approved: 0, pending: 0, rejected: 0 };
            let meta = result.meta;

            if (result.success) {
                allRequisitions = result.data;
            }
            
            currentRequisitions = allRequisitions;
            renderRequisitions(allRequisitions);
            if (meta) updatePager(meta);
            updateStats(stats);
            
        } catch (error) {
            console.error('Error:', error);
        }
    }

    function renderRequisitions(requisitions) {
        const tbody = document.getElementById('requisitionTableBody');
        if (!tbody) return;

        if (requisitions.length === 0) {
            tbody.innerHTML = '<tr><td colspan="9" class="px-6 py-12 text-center text-gray-500"><i class="bx bx-clipboard text-4xl mb-2 block text-gray-300"></i>No records found</td></tr>';
            return;
        }

        tbody.innerHTML = requisitions.map(req => {
            const items = Array.isArray(req.req_items) ? req.req_items : JSON.parse(req.req_items || '[]');
            const priceFormatted = 'PHP ' + parseFloat(req.req_price || 0).toLocaleString(undefined, { minimumFractionDigits: 2 });
            return '<tr class="hover:bg-gray-50 transition-colors">' +
                    '<td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-800">' + req.req_id + '</td>' +
                    '<td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate" title="' + items.join(', ') + '">' +
                        (items.length > 0 ? items[0] : 'No items') + ' ' +
                        (items.length > 1 ? '<span class="text-blue-600 font-semibold">(+' + (items.length - 1) + ')</span>' : '') +
                    '</td>' +
                    '<td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-emerald-600">' +
                        getVendorName(req.req_chosen_vendor) +
                    '</td>' +
                    '<td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-blue-600">' +
                        '<span class="price-mask" data-masked="1" data-price="' + priceFormatted + '">*****</span>' +
                        '<button type="button" class="ml-2 p-1.5 align-middle text-gray-600 hover:text-gray-800 hover:bg-gray-100 rounded" title="Show Total Price" onclick="togglePriceVisibility(this)"><i class="bx bx-show-alt text-2xl"></i></button>' +
                    '</td>' +
                    '<td class="px-6 py-4 whitespace-nowrap">' +
                        '<div class="text-sm font-bold text-gray-800">' + req.req_requester + '</div>' +
                        '<div class="text-xs text-gray-500">' + req.req_dept + '</div>' +
                    '</td>' +
                    '<td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">' + new Date(req.req_date).toLocaleDateString() + '</td>' +
                    '<td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate" title="' + (req.req_note || '') + '">' + (req.req_note || '-') + '</td>' +
                    '<td class="px-6 py-4 whitespace-nowrap">' +
                        '<span class="px-3 py-1.5 rounded-full text-sm font-bold flex items-center gap-1.5 w-fit ' + getStatusBadgeClass(req.req_status) + '">' +
                            getStatusIcon(req.req_status) +
                            req.req_status +
                        '</span>' +
                    '</td>' +
                    '<td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">' +
                        '<div class="flex justify-end gap-2">' +
                            '<button onclick="viewRequisition(\'' + req.id + '\')" title="View Details" class="p-2 text-blue-600 hover:bg-blue-50 rounded-lg transition-all active:scale-90">' +
                                '<i class="bx bx-show-alt text-xl"></i>' +
                            '</button>' +
                            (!req.is_external && String(req.req_status || '').toLowerCase() === 'pending' ? 
                            '<button onclick="openStatusUpdate(' + req.id + ', \'' + req.req_id + '\', \'' + req.req_status + '\')" title="Update Status" class="p-2 text-amber-600 hover:bg-amber-50 rounded-lg transition-all active:scale-90">' +
                                '<i class="bx bx-edit text-xl"></i>' +
                            '</button>' : 
                            '<button disabled title="External or Finalized" class="p-2 text-gray-400 cursor-not-allowed opacity-50">' +
                                '<i class="bx bx-lock-alt text-xl"></i>' +
                            '</button>') +
                        '</div>' +
                    '</td>' +
                '</tr>';
        }).join('');
    }

    window.togglePriceVisibility = function(btn) {
        try {
            const cell = btn.closest('td');
            if (!cell) return;
            const span = cell.querySelector('.price-mask');
            if (!span) return;
            const masked = span.getAttribute('data-masked') === '1';
            if (masked) {
                span.textContent = span.getAttribute('data-price') || '';
                span.setAttribute('data-masked', '0');
                btn.title = 'Hide Total Price';
                const icon = btn.querySelector('i');
                if (icon) { icon.classList.remove('bx-show-alt'); icon.classList.add('bx-hide'); }
            } else {
                span.textContent = '*****';
                span.setAttribute('data-masked', '1');
                btn.title = 'Show Total Price';
                const icon = btn.querySelector('i');
                if (icon) { icon.classList.remove('bx-hide'); icon.classList.add('bx-show-alt'); }
            }
        } catch (e) {
            console.warn('togglePriceVisibility error:', e);
        }
    };

    function getStatusIcon(status) {
        switch (String(status || '').toLowerCase()) {
            case 'approved': return "<i class=\"bx bx-check-circle\"></i>";
            case 'pending': return "<i class=\"bx bx-time-five\"></i>";
            case 'rejected': return "<i class=\"bx bx-x-circle\"></i>";
            default: return "<i class=\"bx bx-help-circle\"></i>";
        }
    }

    function getStatusBadgeClass(status) {
        switch (String(status || '').toLowerCase()) {
            case 'approved': return "bg-green-700 text-white shadow-sm border border-green-800";
            case 'pending': return "bg-yellow-600 text-white shadow-sm border border-yellow-700";
            case 'rejected': return "bg-red-700 text-white shadow-sm border border-red-800";
            default: return "bg-gray-600 text-white border border-gray-700";
        }
    }

    function updateStats(stats) {
        if (!stats) return;
        document.getElementById('totalReqCount').textContent = stats.total || 0;
        document.getElementById('approvedReqCount').textContent = stats.approved || 0;
        document.getElementById('pendingReqCount').textContent = stats.pending || 0;
        document.getElementById('rejectedReqCount').textContent = stats.rejected || 0;

        const pendingPulse = document.getElementById('pendingBadgePulse');
        const pendingPulseCount = document.getElementById('pendingPulseCount');
        const pendingCount = parseInt(stats.pending) || 0;

        if (pendingCount > 0) {
            pendingPulseCount.textContent = pendingCount;
            pendingPulse.classList.remove('hidden');
        } else {
            pendingPulse.classList.add('hidden');
        }
    }

    function updatePager(meta) {
        if (!meta) return;
        currentPage = meta.current_page;
        totalPages = meta.last_page;
        pageDisplay.textContent = currentPage + ' / ' + totalPages;
        pagerInfo.textContent = 'Showing ' + (meta.from || 0) + '-' + (meta.to || 0) + ' of ' + meta.total;
        prevBtn.disabled = currentPage === 1;
        nextBtn.disabled = currentPage === totalPages;
    }

    window.filterByStatus = (status) => {
        currentFilters.status = status;
        statusFilter.value = status;
        fetchRequisitions(1);
    };

    window.viewRequisition = (id) => {
        const req = currentRequisitions.find(r => r.id == id);
        if (req) showModal('view', req);
    };

    window.openStatusUpdate = (id, reqId, currentStatus) => {
        activeStatusId = id;
        document.getElementById('statusTargetId').textContent = reqId;
        statusModal.classList.remove('hidden');
    };

    const hideStatusModal = () => {
        statusModal.classList.add('hidden');
        activeStatusId = null;
    };
    cancelStatusBtn.addEventListener('click', hideStatusModal);

    async function sendStatusUpdate(newStatus) {
        try {
            const response = await fetch(API_URL + '/' + activeStatusId + '/status', {
                method: 'PATCH',
                headers: { 
                    'Authorization': 'Bearer ' + JWT_TOKEN, 
                    'Content-Type': 'application/json',
                    'Accept': 'application/json' 
                },
                body: JSON.stringify({ status: newStatus })
            });
            const result = await response.json();
            if (result.success) {
                hideStatusModal();
                Toast.fire({ icon: 'success', title: 'Status updated successfully' });
                fetchRequisitions(currentPage);
            } else {
                Toast.fire({ icon: 'error', title: result.message || 'Failed to update status' });
            }
        } catch (error) {
            Toast.fire({ icon: 'error', title: 'Failed to update status' });
        }
    }
    approveStatusBtn.addEventListener('click', () => sendStatusUpdate('Approved'));
    rejectStatusBtn.addEventListener('click', () => sendStatusUpdate('Rejected'));

    let searchTimeout;
    searchInput.addEventListener('input', (e) => {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            currentFilters.search = e.target.value;
            fetchRequisitions(1);
        }, 500);
    });

    statusFilter.addEventListener('change', (e) => {
        currentFilters.status = e.target.value;
        fetchRequisitions(1);
    });

    deptFilter.addEventListener('change', (e) => {
        currentFilters.dept = e.target.value;
        fetchRequisitions(1);
    });

    prevBtn.addEventListener('click', () => { if (currentPage > 1) fetchRequisitions(currentPage - 1); });
    nextBtn.addEventListener('click', () => { if (currentPage < totalPages) fetchRequisitions(currentPage + 1); });

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(form);
        const payload = {
            req_id: formData.get('req_id'),
            req_date: formData.get('req_date'),
            req_requester: formData.get('req_requester'),
            req_dept: formData.get('req_dept'),
            req_chosen_vendor: formData.get('req_chosen_vendor'),
            req_items: Array.from(formData.getAll('items[]')).filter(i => i.trim() !== ''),
            req_note: formData.get('req_note')
        };

        try {
            const response = await fetch(API_URL, {
                method: 'POST',
                headers: { 
                    'Authorization': 'Bearer ' + JWT_TOKEN, 
                    'Content-Type': 'application/json',
                    'Accept': 'application/json' 
                },
                body: JSON.stringify(payload)
            });
            const result = await response.json();
            if (result.success) {
                hideModal();
                Toast.fire({ icon: 'success', title: 'Requisition submitted successfully' });
                fetchRequisitions(1);
            }
        } catch (error) {
            Toast.fire({ icon: 'error', title: 'An error occurred during submission' });
        }
    });

    fetchVendors();
    fetchRequisitions();
})();

