<!-- resources/views/psm/vendor-quote.blade.php -->
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
                <option value="Reject">Reject</option>
                <option value="Cancel">Cancel</option>
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
        elements.notifTableBody.innerHTML = `<tr><td colspan="6" class="text-center">No approved purchases</td></tr>`;
        return;
    }
    elements.notifTableBody.innerHTML = list.map(p => `
        <tr>
            <td>${p.pur_id}</td>
            <td>${Array.isArray(p.pur_name_items) ? p.pur_name_items.map(i => typeof i === 'object' ? i.name : i).join(', ') : ''}</td>
            <td>${p.pur_unit} units</td>
            <td>${formatCurrency(p.pur_total_amount)}</td>
            <td>${formatDate(p.created_at)}</td>
            <td>
                <button class="btn btn-sm btn-primary" onclick="viewApprovedPurchase(${p.id})">View</button>
                <button class="btn btn-sm btn-success" onclick="openReviewConfirm(${p.id})">Review</button>
            </td>
        </tr>
    `).join('');
}

function openReviewConfirm(id) {
    selectedPurchaseId = id;
    elements.reviewConfirmModal?.showModal();
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
                await loadNotifications();
                await loadQuotes();
                selectedPurchaseId = null;
                elements.reviewConfirmModal?.close();
            }
        } catch(e) {}
    });
}

function viewApprovedPurchase(id) {
    const p = currentNotifications.find(x => x.id == id);
    if (!p) return;
    alert(JSON.stringify(p, null, 2));
}

async function loadQuotes() {
    try {
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
        }
    } catch(e) {
        currentQuotes = [];
        displayQuotes([]);
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
    elements.quotesTableBody.innerHTML = list.map(q => `
        <tr>
            <td>${q.quo_id}</td>
            <td>${Array.isArray(q.quo_items) ? q.quo_items.map(i => typeof i === 'object' ? i.name : i).join(', ') : ''}</td>
            <td>${q.quo_units} units</td>
            <td>${formatCurrency(q.quo_total_amount)}</td>
            <td>${formatDateRange(q.quo_delivery_date_from, q.quo_delivery_date_to)}</td>
            <td>${q.quo_status}</td>
            <td>
                <button class="btn btn-sm" onclick="viewQuote(${q.id})">View</button>
                <button class="btn btn-sm" onclick="openUpdateStatus(${q.id}, '${q.quo_status}')">Update Status</button>
                <button class="btn btn-sm" onclick="openSetDelivery(${q.id}, '${q.quo_delivery_date_from || ''}', '${q.quo_delivery_date_to || ''}')">Set Date Delivery</button>
                <button class="btn btn-sm" onclick="openDeleteQuote(${q.id})">Delete</button>
            </td>
        </tr>
    `).join('');
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
            }
        } catch(e) {}
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
</script>