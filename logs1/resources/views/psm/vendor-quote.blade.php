<!-- resources/views/psm/vendor-quote.blade.php -->
<style>
    .swal2-container { z-index: 2147483647 !important; }
    #reviewConfirmModal { z-index: 2147483646 !important; }
    #reviewConfirmModal::backdrop { z-index: 2147483645 !important; }
</style>
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bxs-quote-single-left'></i>Vendor Quote Management</h2>
    </div>
    <div class="text-right">
        <span class="text-md text-gray-600">Procurement & Sourcing Management</span>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg p-6">
    <!-- notification purchase order card start -->
    <div class="flex justify-end mb-6">
        <div class="indicator">
            <button class="bg-red-100 p-4 rounded-lg text-left hover:bg-gray-200" type="button" onclick="my_modal_4.showModal()">
                <div class="flex justify-between items-center">
                    <div class="font-bold text-gray-800 flex items-center gap-2 mb-0">
                        <i class="bx bx-fw bxs-bell bx-tada-hover"></i>
                        Notification
                    </div>
                </div>
            </button>
            <!-- will hide if the notif modal had no new notif start -->
            <span class="indicator-item badge badge-sm badge-error border-0 rounded-full w-6 h-6 flex items-center justify-center top-0 right-0 -translate-y-1/4 translate-x-1/4">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-error opacity-75"></span>
                <span class="relative text-white text-xs font-medium">0</span>
            </span><!-- will hide if the notif modal had no new notif start -->
        </div>
    </div>
    <!-- notification purchase order card end -->
    <!-- notification purchase order card modal start -->
    <dialog id="my_modal_4" class="modal">
        <div class="modal-box w-11/12 max-w-5xl">
            <div class="flex justify-between">
                <div class="text-lg font-bold text-gray-800 flex items-center gap-2 mb-0">
                    <i class="bx bx-sm bxs-bell"></i>
                    Notification
                </div>
                <form method="dialog"><button><i class='bx bx-sm bx-x'></i></button></form>
            </div>
            <div class="overflow-x-auto mt-4 border border-gray-900 rounded-lg">
                <table class="table table-sm table-zebra w-full">
                    <thead>
                        <tr class="bg-gray-700 font-bold text-white">
                            <th>Notification ID</th>
                            <th>Items</th>
                            <th>Units</th>
                            <th>Total Amount</th>
                            <th>Received Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="notifTableBody">
                        <tr>
                            <!--   -->
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="modal-action">
            <form method="dialog"><button class="btn">Close</button></form>
            </div>
        </div>
    </dialog>
    <!-- notification purchase order card modal end -->
    <!-- table start -->
    <div class="overflow-x-auto">
        <table class="table table-sm table-zebra w-full">
            <thead>
                <tr class="bg-gray-700 font-bold text-white">
                    <th>Quote ID</th>
                    <th>Items</th>
                    <th>Units</th>
                    <th>Total Amount</th>
                    <th>Estimated Date of Delivery</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="quotesTableBody">
                <tr>
                    <!-- -->
                </tr>
            </tbody>
        </table>
    </div>
    <!-- table end -->
    <!-- pagination section start -->
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
    <!-- pagination section end -->
</div>

<dialog id="viewApprovedPurchaseModal" class="modal">
    <div class="modal-box w-11/12 max-w-3xl">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Approved Purchase Detail</h3>
            <form method="dialog"><button><i class='bx bx-sm bx-x'></i></button></form>
        </div>
        <div id="viewApprovedPurchaseContent" class="space-y-2"></div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<dialog id="viewQuoteModal" class="modal">
    <div class="modal-box w-11/12 max-w-3xl">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Quote Detail</h3>
            <form method="dialog"><button><i class='bx bx-sm bx-x'></i></button></form>
        </div>
        <div id="viewQuoteContent" class="space-y-2"></div>
    </div>
</dialog>

<dialog id="updateStatusModal" class="modal">
    <div class="modal-box w-96">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Update Status</h3>
            <form method="dialog"><button><i class='bx bx-sm bx-x'></i></button></form>
        </div>
        <div class="space-y-3">
            <select id="statusSelect" class="select select-bordered w-full">
                <option value="Vendor-Review">Vendor Review</option>
                <option value="In-Progress">In-Progress</option>
                <option value="Completed">Completed</option>
            </select>
            <div class="flex justify-end gap-2">
                <button id="confirmUpdateStatusBtn" class="btn btn-primary btn-sm">Update</button>
                <form method="dialog"><button class="btn btn-sm">Close</button></form>
            </div>
        </div>
    </div>
</dialog>

<dialog id="setDeliveryModal" class="modal">
    <div class="modal-box w-96">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Set Delivery Dates</h3>
            <form method="dialog"><button><i class='bx bx-sm bx-x'></i></button></form>
        </div>
        <div class="space-y-3">
            <label class="block text-sm">Delivery From</label>
            <input id="deliveryFromInput" type="date" class="input input-bordered w-full" />
            <label class="block text-sm">Delivery To</label>
            <input id="deliveryToInput" type="date" class="input input-bordered w-full" />
            <div class="flex justify-end gap-2">
                <button id="confirmSetDeliveryBtn" class="btn btn-primary btn-sm">Save</button>
                <form method="dialog"><button class="btn btn-sm">Close</button></form>
            </div>
        </div>
    </div>
</dialog>

<dialog id="deleteQuoteModal" class="modal">
    <div class="modal-box w-96">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Delete Quote</h3>
            <form method="dialog"><button><i class='bx bx-sm bx-x'></i></button></form>
        </div>
        <p class="mb-4">Are you sure you want to delete this quote?</p>
        <div class="flex justify-end gap-2">
            <button id="confirmDeleteQuoteBtn" class="btn btn-error btn-sm">Delete</button>
            <form method="dialog"><button class="btn btn-sm">Cancel</button></form>
        </div>
    </div>
</dialog>

<dialog id="reviewConfirmModal" class="modal">
    <div class="modal-box w-96">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Review Purchase</h3>
            <form method="dialog"><button><i class='bx bx-sm bx-x'></i></button></form>
        </div>
        <p class="mb-4">Move this approved purchase to Vendor Review?</p>
        <div class="flex justify-end gap-2">
            <button id="confirmReviewBtn" class="btn btn-primary btn-sm">Confirm</button>
            <form method="dialog"><button class="btn btn-sm">Cancel</button></form>
        </div>
    </div>
</dialog>

<script>
var API_BASE_URL = typeof API_BASE_URL !== 'undefined' ? API_BASE_URL : '<?php echo url('/api/v1'); ?>';
var PSM_QUOTES_API = typeof PSM_QUOTES_API !== 'undefined' ? PSM_QUOTES_API : `${API_BASE_URL}/psm/vendor-quote`;
var PSM_PURCHASES_API = typeof PSM_PURCHASES_API !== 'undefined' ? PSM_PURCHASES_API : `${API_BASE_URL}/psm/purchase-management`;

var CSRF_TOKEN = typeof CSRF_TOKEN !== 'undefined' ? CSRF_TOKEN : document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
var JWT_TOKEN = typeof JWT_TOKEN !== 'undefined' ? JWT_TOKEN : localStorage.getItem('jwt');

var elements = {
    notificationBadge: document.querySelector('.indicator .indicator-item'),
    notificationCountSpan: document.querySelector('.indicator .indicator-item .relative'),
    notifModal: document.getElementById('my_modal_4'),
    notifTableBody: document.getElementById('notifTableBody'),
    quotesTableBody: document.getElementById('quotesTableBody'),
    viewQuoteModal: document.getElementById('viewQuoteModal'),
    viewQuoteContent: document.getElementById('viewQuoteContent'),
    updateStatusModal: document.getElementById('updateStatusModal'),
    statusSelect: document.getElementById('statusSelect'),
    confirmUpdateStatusBtn: document.getElementById('confirmUpdateStatusBtn'),
    setDeliveryModal: document.getElementById('setDeliveryModal'),
    deliveryFromInput: document.getElementById('deliveryFromInput'),
    deliveryToInput: document.getElementById('deliveryToInput'),
    confirmSetDeliveryBtn: document.getElementById('confirmSetDeliveryBtn'),
    deleteQuoteModal: document.getElementById('deleteQuoteModal'),
    confirmDeleteQuoteBtn: document.getElementById('confirmDeleteQuoteBtn'),
    reviewConfirmModal: document.getElementById('reviewConfirmModal'),
    confirmReviewBtn: document.getElementById('confirmReviewBtn')
};

var currentNotifications = [];
var currentQuotes = [];
var quotesLoadingTimer = null;

function safeShowLoading() {
    try { if (typeof window !== 'undefined' && typeof window.showLoading === 'function') return window.showLoading(); } catch (e) {}
    document.body.style.cursor = 'wait';
}

function safeHideLoading() {
    try { if (typeof window !== 'undefined' && typeof window.hideLoading === 'function') return window.hideLoading(); } catch (e) {}
    document.body.style.cursor = 'default';
}

var selectedQuoteId = null;
var selectedPurchaseId = null;

initVendorQuote();

async function initVendorQuote() {
    await loadNotifications();
    await loadQuotes();
}

function setNotificationIndicator(count) {
    if (!elements.notificationBadge) return;
    if (count > 0) {
        elements.notificationBadge.classList.remove('hidden');
        var display = count > 99 ? '+99' : `+${count}`;
        if (elements.notificationCountSpan) elements.notificationCountSpan.textContent = display;
    } else {
        elements.notificationBadge.classList.add('hidden');
    }
}

async function loadNotifications() {
    try {
        const response = await fetch(`${PSM_QUOTES_API}/notifications`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            credentials: 'include'
        });
        if (!response.ok) throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        const result = await response.json();
        if (result.success) {
            currentNotifications = result.data || [];
            displayNotifications(currentNotifications);
            setNotificationIndicator(currentNotifications.length);
        } else {
            currentNotifications = [];
            displayNotifications([]);
            setNotificationIndicator(0);
        }
    } catch(e) {
        currentNotifications = [];
        displayNotifications([]);
        setNotificationIndicator(0);
    }
}

function displayNotifications(list) {
    if (!elements.notifTableBody) return;
    if (!list || list.length === 0) {
        elements.notifTableBody.innerHTML = `
            <tr>
                <td colspan="10" class="px-6 py-8 text-center text-gray-500">
                    <i class='bx bxs-purchase-tag text-4xl text-gray-300 mb-3'></i>
                    <p class="text-lg">No approved purchases</p>
                </td>
            </tr>`;
        return;
    }
    elements.notifTableBody.innerHTML = list.map((p, i) => {
        const itemsFull = Array.isArray(p.pur_name_items) ? p.pur_name_items.map(i => typeof i === 'object' ? i.name : i).join(', ') : '';
        const notiId = `NOTI${String(i + 1).padStart(5, '0')}`;
        return `
        <tr>
            <td class="px-3 py-2 font-mono">${notiId}</td>
            <td class="px-3 py-2" title="${itemsFull}">${truncateItems(p.pur_name_items, 40)}</td>
            <td class="px-3 py-2">${p.pur_unit} units</td>
            <td class="px-3 py-2">${formatCurrency(p.pur_total_amount)}</td>
            <td class="px-3 py-2">${formatDate(p.created_at)}</td>
            <td class="px-3 py-2">
                <div class="flex items-center space-x-2">
                    <button class="text-gray-700 hover:text-gray-900 transition-colors p-2 rounded-lg hover:bg-gray-50" data-action="view" data-id="${p.id}" title="View">
                        <i class='bx bx-show-alt text-xl'></i>
                    </button>
                    <button class="text-indigo-600 hover:text-indigo-900 transition-colors p-2 rounded-lg hover:bg-indigo-50" data-action="review" data-id="${p.id}" title="Review Purchase">
                        <i class='bx bx-check-circle text-xl'></i>
                    </button>
                </div>
            </td>
        </tr>`;
    }).join('');
}

window.openReviewConfirm = function(id) {
    selectedPurchaseId = id;
    elements.reviewConfirmModal?.showModal();
    if (elements.notifModal) elements.notifModal.setAttribute('inert', '');
    if (elements.confirmReviewBtn) elements.confirmReviewBtn.focus({ preventScroll: true });
}

function updateNotifIndicatorFromDOM() {
    try {
        const rows = elements.notifTableBody ? elements.notifTableBody.querySelectorAll('tr') : [];
        let activeCount = 0;
        rows.forEach(r => { if (!r.classList.contains('opacity-60')) activeCount++; });
        setNotificationIndicator(activeCount);
    } catch(e) {}
}

if (elements.confirmReviewBtn) {
    elements.confirmReviewBtn.addEventListener('click', async function() {
        if (!selectedPurchaseId) return;
        try {
            const response = await fetch(`${PSM_QUOTES_API}/review-from-purchase/${selectedPurchaseId}`, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
                },
                credentials: 'include'
            });
            if (!response.ok) throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            const result = await response.json();
            if (result.success) {
                const row = elements.notifTableBody.querySelector(`button[data-id='${selectedPurchaseId}']`)?.closest('tr');
                if (row) row.classList.add('opacity-60');
                await loadQuotes();
                selectedPurchaseId = null;
                elements.reviewConfirmModal?.close();
                if (elements.notifModal) elements.notifModal.removeAttribute('inert');
                updateNotifIndicatorFromDOM();
            }
        } catch(e) {}
    });
}

if (elements.reviewConfirmModal) {
    elements.reviewConfirmModal.addEventListener('close', function() {
        if (elements.notifModal) elements.notifModal.removeAttribute('inert');
    });
}

window.viewApprovedPurchase = function(id) {
    const p = currentNotifications.find(x => x.id == id);
    if (!p) return;
    const items = Array.isArray(p.pur_name_items) ? p.pur_name_items : [];
    const itemsHtml = items.map(item => {
        const itemName = typeof item === 'object' ? item.name : item;
        const itemPrice = typeof item === 'object' ? formatCurrency(item.price) : 'N/A';
        return `<li class="flex justify-between py-1 border-b border-gray-100"><span>${itemName}</span><span class="font-semibold">${itemPrice}</span></li>`;
    }).join('');
    const html = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><span class="text-sm text-gray-500">PO Number</span><p class="font-semibold">${p.pur_id}</p></div>
            <div><span class="text-sm text-gray-500">Company</span><p class="font-semibold">${p.pur_company_name || ''} <small class="text-gray-500">(${p.pur_ven_type || ''})</small></p></div>
            <div><span class="text-sm text-gray-500">Type</span><p class="font-semibold capitalize">${p.pur_ven_type || ''}</p></div>
            <div><span class="text-sm text-gray-500">Units</span><p class="font-semibold">${p.pur_unit}</p></div>
            <div><span class="text-sm text-gray-500">Amount</span><p class="font-semibold">${formatCurrency(p.pur_total_amount)}</p></div>
            <div><span class="text-sm text-gray-500">Ordered By</span><p class="font-semibold">${p.pur_order_by || 'Not specified'}</p></div>
            <div><span class="text-sm text-gray-500">Approved By</span><p class="font-semibold">${p.pur_approved_by || 'Not approved'}</p></div>
            <div><span class="text-sm text-gray-500">Status</span><p class="font-semibold">${p.pur_status}</p></div>
            <div class="md:col-span-2"><span class="text-sm text-gray-500">Items (${items.length})</span>
                <ul class="font-semibold mt-2 bg-gray-50 p-3 rounded-lg">${itemsHtml || '<li class="py-2 text-gray-500">No items</li>'}</ul>
            </div>
        </div>
    `;
    const m = document.getElementById('viewApprovedPurchaseModal');
    const c = document.getElementById('viewApprovedPurchaseContent');
    if (c) c.innerHTML = html;
    m?.showModal();
}

async function loadQuotes() {
    try {
        if (elements.quotesTableBody) {
            if (quotesLoadingTimer) { clearTimeout(quotesLoadingTimer); }
            quotesLoadingTimer = setTimeout(function() {
                elements.quotesTableBody.innerHTML = `
                    <tr>
                        <td colspan="10" class="px-6 py-4 text-center text-gray-500">
                            <div class="flex justify-center items-center py-4">
                                <div class="loading loading-spinner mr-3"></div>
                                Loading quotes...
                            </div>
                        </td>
                    </tr>
                `;
            }, 200);
        }
        safeShowLoading();
        const response = await fetch(`${PSM_QUOTES_API}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            credentials: 'include'
        });
        if (!response.ok) throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        const result = await response.json();
        if (result.success) {
            currentQuotes = result.data || [];
            displayQuotes(currentQuotes);
        } else {
            currentQuotes = [];
            displayQuotes([]);
            if (typeof showNotification === 'function') showNotification(result.message || 'Failed to load quotes', 'error');
        }
    } catch(e) {
        currentQuotes = [];
        displayQuotes([]);
        if (typeof showNotification === 'function') showNotification('Error loading quotes: ' + (e && e.message ? e.message : 'Unknown error'), 'error');
    } finally {
        if (quotesLoadingTimer) { clearTimeout(quotesLoadingTimer); quotesLoadingTimer = null; }
        safeHideLoading();
    }
}

function displayQuotes(list) {
    if (!elements.quotesTableBody) return;
    if (!list || list.length === 0) {
        elements.quotesTableBody.innerHTML = `
            <tr>
                <td colspan="10" class="px-6 py-8 text-center text-gray-500">
                    <i class='bx bxs-quote-left text-4xl text-gray-300 mb-3'></i>
                    <p class="text-lg">No quotes found</p>
                </td>
            </tr>
        `;
        return;
    }
    elements.quotesTableBody.innerHTML = list.map(function(q) {
        const isInProgress = q.quo_status === 'In-Progress';
        const isCompleted = q.quo_status === 'Completed';
        const isVendorReview = q.quo_status === 'Vendor-Review';
        const rowClass = isCompleted ? 'bg-gray-200' : '';
        const idStr = String(q.id);
        const statusStr = String(q.quo_status || '');
        const fromStr = q.quo_delivery_date_from || '';
        const toStr = q.quo_delivery_date_to || '';
        let actions = "";
        const viewBtn = "<button class=\"text-gray-700 hover:text-gray-900 transition-colors p-2 rounded-lg hover:bg-gray-50\" data-action=\"view\" data-id=\"" + idStr + "\" title=\"View\"><i class='bx bx-show-alt text-xl'></i></button>";
        const updateBtn = "<button class=\"text-green-600 hover:text-green-900 transition-colors p-2 rounded-lg hover:bg-green-50\" data-action=\"update\" data-id=\"" + idStr + "\" data-status=\"" + statusStr + "\" title=\"Update Status\"><i class='bx bx-edit text-xl'></i></button>";
        const deliveryBtn = "<button class=\"text-indigo-600 hover:text-indigo-900 transition-colors p-2 rounded-lg hover:bg-indigo-50\" data-action=\"delivery\" data-id=\"" + idStr + "\" data-from=\"" + fromStr + "\" data-to=\"" + toStr + "\" title=\"Set Date Delivery\"><i class='bx bx-calendar text-xl'></i></button>";
        const deleteBtn = "<button class=\"text-red-600 hover:text-red-900 transition-colors p-2 rounded-lg hover:bg-red-50\" data-action=\"delete\" data-id=\"" + idStr + "\" title=\"Delete\"><i class='bx bx-trash text-xl'></i></button>";
        if (isCompleted) {
            actions = viewBtn + deleteBtn;
        } else if (isInProgress) {
            actions = viewBtn + updateBtn + deliveryBtn + deleteBtn;
        } else if (isVendorReview) {
            actions = viewBtn + updateBtn + deleteBtn;
        } else {
            actions = viewBtn + updateBtn + deleteBtn;
        }
        return "<tr class=\"" + rowClass + "\">" +
               "<td>" + q.quo_id + "</td>" +
               "<td title=\"" + (Array.isArray(q.quo_items) ? q.quo_items.map(i => typeof i === 'object' ? i.name : i).join(', ') : '') + "\">" + truncateItems(q.quo_items, 40) + "</td>" +
               "<td>" + q.quo_units + " units</td>" +
               "<td>" + formatCurrency(q.quo_total_amount) + "</td>" +
               "<td>" + formatDateRange(q.quo_delivery_date_from, q.quo_delivery_date_to) + "</td>" +
               "<td><span class=\"inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium " + getStatusBadgeClass(q.quo_status) + "\"><i class='bx " + getStatusIcon(q.quo_status) + " mr-1'></i>" + q.quo_status + "</span></td>" +
               "<td>" + actions + "</td>" +
               "</tr>";
    }).join('');
}

function viewQuote(id) {
    const q = currentQuotes.find(x => x.id == id);
    if (!q) return;
    if (!elements.viewQuoteContent || !elements.viewQuoteModal) return;
    elements.viewQuoteContent.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><span class="text-sm text-gray-500">Quote ID</span><p class="font-semibold">${q.quo_id}</p></div>
            <div><span class="text-sm text-gray-500">Status</span><p class="font-semibold">${q.quo_status}</p></div>
            <div class="md:col-span-2"><span class="text-sm text-gray-500">Items</span><p class="font-semibold">${Array.isArray(q.quo_items) ? q.quo_items.map(i => typeof i === 'object' ? i.name : i).join(', ') : ''}</p></div>
            <div><span class="text-sm text-gray-500">Units</span><p class="font-semibold">${q.quo_units}</p></div>
            <div><span class="text-sm text-gray-500">Total Amount</span><p class="font-semibold">${formatCurrency(q.quo_total_amount)}</p></div>
            <div class="md:col-span-2"><span class="text-sm text-gray-500">Delivery</span><p class="font-semibold">${formatDateRange(q.quo_delivery_date_from, q.quo_delivery_date_to)}</p></div>
        </div>
    `;
    elements.viewQuoteModal.showModal();
}

function openUpdateStatus(id, current) {
    selectedQuoteId = id;
    if (elements.statusSelect) elements.statusSelect.value = current || 'Vendor-Review';
    elements.updateStatusModal?.showModal();
}

if (elements.confirmUpdateStatusBtn) {
    elements.confirmUpdateStatusBtn.addEventListener('click', async function() {
        if (!selectedQuoteId) return;
        try {
            const response = await fetch(`${PSM_QUOTES_API}/${selectedQuoteId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
                },
                credentials: 'include',
                body: JSON.stringify({ quo_status: elements.statusSelect.value })
            });
            if (!response.ok) throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            const result = await response.json();
            if (result.success) {
                await loadQuotes();
                selectedQuoteId = null;
                elements.updateStatusModal?.close();
                if (typeof showNotification === 'function') showNotification('Quote status updated', 'success');
            }
        } catch(e) { if (typeof showNotification === 'function') showNotification('Error updating status: ' + (e && e.message ? e.message : 'Unknown error'), 'error'); }
    });
}

async function deleteQuote(id) {
    try {
        const response = await fetch(`${PSM_QUOTES_API}/${id}`, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            credentials: 'include'
        });
        if (!response.ok) throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        const result = await response.json();
        if (result.success) {
            await loadQuotes();
        }
    } catch(e) {}
}

function formatCurrency(n) {
    var val = Number(n || 0);
    return `₱${val.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}`;
}

function formatDate(d) {
    if (!d) return '';
    const dt = new Date(d);
    return `${dt.getMonth()+1}-${dt.getDate()}-${dt.getFullYear()}`;
}

function formatDateRange(a, b) {
    const A = formatDate(a);
    const B = formatDate(b);
    if (!A && !B) return '';
    if (A && B) return `${A} to ${B}`;
    return A || B;
}

function truncateItems(items, maxLen) {
    try {
        const text = Array.isArray(items) ? items.map(i => typeof i === 'object' ? i.name : i).join(', ') : '';
        if (text.length <= maxLen) return text;
        return text.substring(0, maxLen - 1) + '…';
    } catch (e) { return ''; }
}

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: function(toast) {
        toast.onmouseenter = Swal.stopTimer;
        toast.onmouseleave = Swal.resumeTimer;
    }
});

function showNotification(message, type) {
    Toast.fire({
        icon: type === 'success' ? 'success' : type === 'error' ? 'error' : type === 'warning' ? 'warning' : 'info',
        title: message
    });
}

function getStatusBadgeClass(status) {
    const statusClasses = {
        'Pending': 'bg-yellow-100 text-yellow-800',
        'Approved': 'bg-blue-100 text-blue-800',
        'Rejected': 'bg-red-100 text-red-800',
        'Cancel': 'bg-red-100 text-red-800',
        'Vendor-Review': 'bg-purple-100 text-purple-800',
        'In-Progress': 'bg-indigo-100 text-indigo-800',
        'Completed': 'bg-green-100 text-green-800'
    };
    return statusClasses[status] || 'bg-gray-100 text-gray-800';
}

function getStatusIcon(status) {
    const statusIcons = {
        'Pending': 'bx-time',
        'Approved': 'bx-check-circle',
        'Rejected': 'bx-x-circle',
        'Cancel': 'bx-x-circle',
        'Vendor-Review': 'bx-user-voice',
        'In-Progress': 'bx-cog',
        'Completed': 'bx-check-circle'
    };
    return statusIcons[status] || 'bx-question-mark';
}
if (elements.notifTableBody) {
    elements.notifTableBody.addEventListener('click', function(e) {
        const btn = e.target.closest('button');
        if (!btn) return;
        const action = btn.dataset.action;
        const id = btn.dataset.id;
        if (!id) return;
        if (action === 'view') {
            window.viewApprovedPurchase(Number(id));
        } else if (action === 'review') {
            window.openReviewConfirm(Number(id));
        }
    });
}

// delegate clicks in quotes table
if (elements.quotesTableBody) {
    elements.quotesTableBody.addEventListener('click', function(e) {
        const btn = e.target.closest('button');
        if (!btn) return;
        if (btn.disabled) return;
        const id = btn.dataset.id;
        const action = btn.dataset.action;
        if (!id || !action) return;
        if (action === 'view') {
            window.viewQuote(Number(id));
        } else if (action === 'update') {
            window.openUpdateStatus(Number(id), btn.dataset.status);
        } else if (action === 'delivery') {
            window.openSetDelivery(Number(id), btn.dataset.from, btn.dataset.to);
        } else if (action === 'delete') {
            window.openDeleteQuote(Number(id));
        }
    });
}
function openSetDelivery(id, from, to) {
    selectedQuoteId = id;
    if (elements.deliveryFromInput) elements.deliveryFromInput.value = from ? from.substring(0,10) : '';
    if (elements.deliveryToInput) elements.deliveryToInput.value = to ? to.substring(0,10) : '';
    elements.setDeliveryModal?.showModal();
}

function openDeleteQuote(id) {
    const q = currentQuotes.find(x => x.id == id);
    (async function() {
        const confirmResult = await Swal.fire({
            title: 'Delete Quote?',
            text: `Are you sure you want to delete quote "${q ? q.quo_id : id}"? This action cannot be undone.`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        });
        if (!confirmResult.isConfirmed) return;
        try {
            const response = await fetch(`${PSM_QUOTES_API}/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
                },
                credentials: 'include'
            });
            if (!response.ok) throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            const result = await response.json();
            if (result.success) {
                await loadQuotes();
                if (typeof showNotification === 'function') showNotification(result.message || 'Quote deleted successfully', 'success');
            } else {
                if (typeof showNotification === 'function') showNotification(result.message || 'Failed to delete quote', 'error');
            }
        } catch(error) {
            if (typeof showNotification === 'function') showNotification('Error deleting quote: ' + (error && error.message ? error.message : 'Unknown error'), 'error');
        }
    })();
}

if (elements.confirmSetDeliveryBtn) {
    elements.confirmSetDeliveryBtn.addEventListener('click', async function() {
        if (!selectedQuoteId) return;
        try {
            const body = {
                quo_delivery_date_from: elements.deliveryFromInput.value || null,
                quo_delivery_date_to: elements.deliveryToInput.value || null
            };
            const response = await fetch(`${PSM_QUOTES_API}/${selectedQuoteId}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
                },
                credentials: 'include',
                body: JSON.stringify(body)
            });
            if (!response.ok) throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            const result = await response.json();
            if (result.success) {
                await loadQuotes();
                selectedQuoteId = null;
                elements.setDeliveryModal?.close();
                if (typeof showNotification === 'function') showNotification('Delivery dates updated', 'success');
            }
        } catch(e) { if (typeof showNotification === 'function') showNotification('Error updating delivery: ' + (e && e.message ? e.message : 'Unknown error'), 'error'); }
    });
}

if (elements.confirmDeleteQuoteBtn) {
    elements.confirmDeleteQuoteBtn.addEventListener('click', function() {
        if (!selectedQuoteId) return;
        window.openDeleteQuote(Number(selectedQuoteId));
    });
}

// expose handlers globally for inline calls
window.viewQuote = viewQuote;
window.openUpdateStatus = openUpdateStatus;
window.openSetDelivery = openSetDelivery;
window.openDeleteQuote = openDeleteQuote;
</script>