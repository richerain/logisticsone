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
                <span class="relative text-white text-xs font-medium">+9</span>
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
                    <tbody>
                        <tr>
                            <td>NOTI00001</td>
                            <td>item1 , item2...</td>
                            <td>10 units</td>
                            <td>₱15,000.00</td>
                            <td>mm-dd-yy</td>
                            <td>
                                <button class="btn btn-sm btn-primary" title="View Full Detail">View</button>
                            </td>
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
            <tbody>
                <tr>
                    <td>QUOT00001</td>
                    <td>item1, item2...</td>
                    <td>10 units</td>
                    <td>₱15,000.00</td>
                    <td>11-16-2025 to 11-20-2025</td>
                    <td>Vendor Review</td>
                    <td>
                        <button class="btn btn-sm btn-primary">Review</button>
                    </td>
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
<script>
var PSM_QUOTES_API = typeof PSM_QUOTES_API !== 'undefined' ? PSM_QUOTES_API : `${API_BASE_URL}/psm/vendor-quote`;
var PSM_PURCHASES_API = typeof PSM_PURCHASES_API !== 'undefined' ? PSM_PURCHASES_API : `${API_BASE_URL}/psm/purchase-management`;

var CSRF_TOKEN = typeof CSRF_TOKEN !== 'undefined' ? CSRF_TOKEN : document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
var JWT_TOKEN = typeof JWT_TOKEN !== 'undefined' ? JWT_TOKEN : localStorage.getItem('jwt');

var elements = {
    notificationBadge: document.querySelector('.indicator .indicator-item'),
    notificationCountSpan: document.querySelector('.indicator .indicator-item .relative'),
    notifModal: document.getElementById('my_modal_4'),
    notifTableBody: document.querySelector('#my_modal_4 tbody'),
    quotesTableBody: document.querySelector('table tbody')
};

var currentNotifications = [];
var currentQuotes = [];

initVendorQuote();

async function initVendorQuote() {
    await loadNotifications();
    await loadQuotes();
}

function setNotificationIndicator(count) {
    if (!elements.notificationBadge) return;
    if (count && count > 0) {
        elements.notificationBadge.classList.remove('hidden');
        if (elements.notificationCountSpan) elements.notificationCountSpan.textContent = `+${count}`;
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
                <button class="btn btn-sm btn-success" onclick="reviewPurchase(${p.id})">Review</button>
            </td>
        </tr>
    `).join('');
}

async function reviewPurchase(id) {
    try {
        const response = await fetch(`${PSM_QUOTES_API}/review-from-purchase/${id}`, {
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
        }
    } catch(e) {}
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
                <td colspan="7" class="text-center">No quotes</td>
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
                <button class="btn btn-sm" onclick="updateQuoteStatus(${q.id})">Update Status</button>
                <button class="btn btn-sm" onclick="deleteQuote(${q.id})">Delete</button>
            </td>
        </tr>
    `).join('');
}

function viewQuote(id) {
    const q = currentQuotes.find(x => x.id == id);
    if (!q) return;
    alert(JSON.stringify(q, null, 2));
}

async function updateQuoteStatus(id) {
    const q = currentQuotes.find(x => x.id == id);
    if (!q) return;
    const next = prompt('Set status', q.quo_status);
    if (!next) return;
    try {
        const response = await fetch(`${PSM_QUOTES_API}/${id}`, {
            method: 'PUT',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            credentials: 'include',
            body: JSON.stringify({ quo_status: next })
        });
        if (!response.ok) throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        const result = await response.json();
        if (result.success) {
            await loadQuotes();
        }
    } catch(e) {}
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
    const val = Number(n || 0);
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