<!-- resources/views/psm/vendor-quote.blade.php -->
<style>
    .swal2-container { z-index: 2147483647 !important; }
    #reviewConfirmModal { z-index: 2147483646 !important; }
    #reviewConfirmModal::backdrop { z-index: 2147483645 !important; }
    #notifTable th, #notifTable td, #quotesTable th, #quotesTable td { white-space: nowrap; text-overflow: ellipsis; overflow: hidden; }
</style>
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bxs-quote-single-left'></i>Vendor Quote Management</h2>
    </div>
    <div class="text-right">
        <span class="text-md text-gray-600">Procurement & Sourcing Management</span>
    </div>
    <dialog id="viewPurchaseOrderModal" class="modal backdrop-blur-sm">
        <div class="modal-box w-11/12 max-w-3xl bg-white rounded-xl shadow-2xl p-0 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4 flex justify-between items-center">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                    <i class='bx bxs-detail'></i> Purchase Order Detail
                </h3>
                <form method="dialog"><button class="text-white/80 hover:text-white transition-colors p-1 rounded-full hover:bg-white/10"><i class='bx bx-x text-2xl'></i></button></form>
            </div>
            <div class="p-6 overflow-y-auto max-h-[70vh] bg-gray-50">
                <div id="viewPurchaseOrderContent" class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden p-6"></div>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop"><button>close</button></form>
    </dialog>
    <dialog id="reviewRequestConfirmModal" class="modal backdrop-blur-sm">
        <div class="modal-box w-11/12 max-w-md bg-white rounded-xl shadow-2xl p-0 overflow-hidden">
            <div class="px-6 py-4 flex justify-between items-center border-b">
                <h3 class="text-lg font-bold text-gray-800">Received Purchase Order</h3>
                <form method="dialog"><button class="text-gray-500 hover:text-gray-800 transition-colors p-1 rounded-full"><i class='bx bx-x text-2xl'></i></button></form>
            </div>
            <div class="p-6 space-y-4">
                <p class="text-sm text-gray-600">Received Purchase Order?</p>
                <div class="flex justify-end gap-3">
                    <button id="cancelReviewRequestBtn" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50">Cancel</button>
                    <button id="confirmReviewRequestBtn" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 flex items-center gap-2">
                        <i class='bx bx-check-circle'></i> Yes, Received It!
                    </button>
                </div>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop"><button>close</button></form>
    </dialog>
</div>

<!-- Stats Section (moved above Quote Purchase Order section) -->
<div id="quoteStatsSection" class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
    <div onclick="filterQuoteStatus('')" class="bg-white rounded-2xl shadow-sm border border-gray-100 border-b-4 border-blue-500 p-6 relative overflow-hidden group hover:shadow-md transition-all duration-300 cursor-pointer active:scale-95">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform duration-300">
            <i class='bx bxs-quote-right text-6xl text-blue-600'></i>
        </div>
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-4">
                <div class="p-3 bg-blue-50 rounded-xl text-blue-600">
                    <i class='bx bxs-quote-right text-2xl'></i>
                </div>
                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Total Quotes</h4>
            </div>
            <div class="flex items-end gap-2">
                <span id="totalQuotes" class="text-4xl font-black text-gray-800 leading-none">0</span>
                <span class="text-xs text-gray-500 mb-1">All statuses</span>
            </div>
        </div>
    </div>
    <div onclick="filterQuoteStatus('PO Received')" class="bg-white rounded-2xl shadow-sm border border-gray-100 border-b-4 border-purple-500 p-6 relative overflow-hidden group hover:shadow-md transition-all duration-300 cursor-pointer active:scale-95">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform duration-300">
            <i class='bx bx-user-voice text-6xl text-purple-700'></i>
        </div>
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-4">
                <div class="p-3 bg-purple-50 rounded-xl text-purple-700">
                    <i class='bx bx-user-voice text-2xl'></i>
                </div>
                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider">PO Received</h4>
            </div>
            <div class="flex items-end gap-2">
                <span id="poReceivedQuotes" class="text-4xl font-black text-gray-800 leading-none">0</span>
                <span class="text-xs text-gray-500 mb-1">Awaiting processing</span>
            </div>
        </div>
    </div>
    <div onclick="filterQuoteStatus('Processing Order')" class="bg-white rounded-2xl shadow-sm border border-gray-100 border-b-4 border-indigo-500 p-6 relative overflow-hidden group hover:shadow-md transition-all duration-300 cursor-pointer active:scale-95">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform duration-300">
            <i class='bx bx-cog text-6xl text-indigo-700'></i>
        </div>
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-4">
                <div class="p-3 bg-indigo-50 rounded-xl text-indigo-700">
                    <i class='bx bx-cog text-2xl'></i>
                </div>
                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Processing</h4>
            </div>
            <div class="flex items-end gap-2">
                <span id="processingQuotes" class="text-4xl font-black text-gray-800 leading-none">0</span>
                <span class="text-xs text-gray-500 mb-1">Orders in progress</span>
            </div>
        </div>
    </div>
    <div onclick="filterQuoteStatus('Delivered')" class="bg-white rounded-2xl shadow-sm border border-gray-100 border-b-4 border-emerald-500 p-6 relative overflow-hidden group hover:shadow-md transition-all duration-300 cursor-pointer active:scale-95">
        <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:scale-110 transition-transform duration-300">
            <i class='bx bx-check-circle text-6xl text-emerald-700'></i>
        </div>
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-4">
                <div class="p-3 bg-emerald-50 rounded-xl text-emerald-700">
                    <i class='bx bx-check-circle text-2xl'></i>
                </div>
                <h4 class="text-sm font-bold text-gray-500 uppercase tracking-wider">Delivered</h4>
            </div>
            <div class="flex items-end gap-2">
                <span id="deliveredQuotes" class="text-4xl font-black text-gray-800 leading-none">0</span>
                <span class="text-xs text-gray-500 mb-1">Completed orders</span>
            </div>
        </div>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg p-6">
    <!-- quote purchase order section -->
    <div class="flex items-center justify-between mb-6">
        <div class="flex items-center gap-2">
            <i class='bx bx-transfer-alt text-2xl text-blue-700'></i>
            <h3 class="text-lg font-bold text-gray-800 leading-tight">Quote Purchase Order</h3>
        </div>
        <div class="relative">
            <button id="openNewPurchaseOrderBtn" class="px-4 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white shadow transition-colors flex items-center gap-2"
                    type="button" onclick="my_modal_4.showModal()">
                <i class='bx bx-cart-download text-lg'></i>
                <span class="font-semibold">New Purchase Order</span>
            </button>
            <div id="newPurchaseBadgePulse" class="hidden absolute -top-2 -right-2 z-20">
                <span class="relative flex h-5 w-5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-blue-400 opacity-75"></span>
                    <span id="newPurchasePulseCount" class="relative inline-flex rounded-full h-5 w-5 bg-blue-500 text-[10px] font-bold text-white items-center justify-center border-2 border-white shadow-sm">0</span>
                </span>
            </div>
        </div>
    </div>
    <!-- notification purchase order card modal start -->
    <dialog id="my_modal_4" class="modal backdrop-blur-sm">
        <div class="modal-box w-11/12 max-w-5xl bg-white rounded-xl shadow-2xl p-0 overflow-hidden">
            <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4 flex justify-between items-center">
                <div class="text-xl font-bold text-white flex items-center gap-2 mb-0">
                    <i class='bx bx-cart-download'></i>
                    New Purchase Order
                </div>
                <form method="dialog"><button class="text-white/80 hover:text-white transition-colors"><i class='bx bx-x text-2xl'></i></button></form>
            </div>
            <div class="p-6">
                <div class="border border-gray-200 rounded-lg">
                    <div class="overflow-x-auto">
                        <div class="max-h-[65vh] overflow-y-auto">
                            <table id="notifTable" class="table table-sm table-zebra w-full">
                                <thead class="sticky top-0 z-10 bg-gray-900 text-white">
                                    <tr class="bg-gray-900 font-bold text-white">
                                        <th class="whitespace-nowrap py-3">Request ID</th>
                                        <th class="whitespace-nowrap py-3">Items</th>
                                        <th class="whitespace-nowrap py-3">Units</th>
                                        <th class="whitespace-nowrap py-3">Total Amount</th>
                                        <th class="whitespace-nowrap py-3">Vendor</th>
                                        <th class="whitespace-nowrap py-3">Vendor Type</th>
                                        <th class="whitespace-nowrap py-3">Status</th>
                                        <th class="whitespace-nowrap py-3">Ordered By</th>
                                        <th class="whitespace-nowrap py-3">Description</th>
                                        <th class="whitespace-nowrap py-3">Actions</th>
                                    </tr>
                                </thead>
                                <tbody id="notifTableBody">
                                    <tr>
                                        <!-- data rows -->
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div id="notifPager" class="p-3 flex justify-between items-center border-t border-gray-200">
                        <div id="notifPagerInfo" class="text-sm text-gray-600">0–0 of 0</div>
                        <div class="join">
                            <button class="join-item btn btn-sm" id="notifPrevBtn" data-action="prev">Prev</button>
                            <span class="join-item btn btn-sm" id="notifPageDisplay">1 / 1</span>
                            <button class="join-item btn btn-sm" id="notifNextBtn" data-action="next">Next</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop">
            <button>close</button>
        </form>
    </dialog>
    <!-- notification purchase order card modal end -->
    <!-- Stats Section removed from inside; now positioned above -->

    <!-- table start -->
    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table id="quotesTable" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-800 font-bold text-gray-100">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap">Quote ID</th>
                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap">Items</th>
                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap">Units</th>
                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap">Total Amount</th>
                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap">Delivery Date</th>
                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium tracking-wider whitespace-nowrap">Actions</th>
                    </tr>
                </thead>
                <tbody id="quotesTableBody" class="bg-white divide-y divide-gray-200">
                    <tr>
                        <!-- -->
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
    <!-- table end -->
    <div id="quotesPager" class="mt-4 flex justify-between items-center">
        <div id="quotesPagerInfo" class="text-sm text-gray-600"></div>
        <div class="join">
            <button class="join-item btn btn-sm" id="quotesPrevBtn" data-action="prev">Prev</button>
            <span class="join-item btn btn-sm" id="quotesPageDisplay">1 / 1</span>
            <button class="join-item btn btn-sm" id="quotesNextBtn" data-action="next">Next</button>
        </div>
    </div>
</div>

<dialog id="viewApprovedPurchaseModal" class="modal backdrop-blur-sm">
    <div class="modal-box w-11/12 max-w-3xl bg-white rounded-xl shadow-2xl p-0 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-600 to-blue-800 px-6 py-4 flex justify-between items-center">
            <h3 class="text-xl font-bold text-white flex items-center gap-2">
                <i class='bx bxs-detail'></i> Approved Purchase Detail
            </h3>
            <form method="dialog"><button class="text-white/80 hover:text-white transition-colors p-1 rounded-full hover:bg-white/10"><i class='bx bx-x text-2xl'></i></button></form>
        </div>
        <div class="p-6 overflow-y-auto max-h-[70vh] bg-gray-50">
            <div id="viewApprovedPurchaseContent" class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden p-6"></div>
        </div>
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
                <option value="PO Received">PO Received</option>
                <option value="Processing Order">Processing Order</option>
                <option value="Dispatched">Dispatched</option>
                <option value="Delivered">Delivered</option>
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
            <h3 class="text-xl font-semibold">Set Delivery Date</h3>
            <form method="dialog"><button><i class='bx bx-sm bx-x'></i></button></form>
        </div>
        <div class="space-y-3">
            <label class="block text-sm">Delivery Date</label>
            <input id="deliveryDateInput" type="date" class="input input-bordered w-full" />
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

<dialog id="reviewConfirmModal" class="modal backdrop-blur-sm">
    <div class="modal-box bg-white rounded-xl shadow-2xl p-0 overflow-hidden w-full max-w-md">
        <div class="bg-gradient-to-r from-indigo-600 to-indigo-800 px-6 py-4 flex justify-between items-center">
            <h3 class="text-xl font-bold text-white flex items-center gap-2">
                <i class='bx bx-check-circle'></i> Review Purchase
            </h3>
            <form method="dialog"><button class="text-white/80 hover:text-white transition-colors p-1 rounded-full hover:bg-white/10"><i class='bx bx-x text-2xl'></i></button></form>
        </div>
        <div class="p-6 bg-gray-50">
            <div class="bg-white p-6 rounded-lg border border-gray-200 shadow-sm text-center">
                <div class="w-16 h-16 bg-indigo-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class='bx bx-question-mark text-3xl text-indigo-600'></i>
                </div>
                <h4 class="text-lg font-bold text-gray-900 mb-2">Confirm Action</h4>
                <p class="text-gray-600 mb-6">Are you sure you want to move this approved purchase to Vendor Review?</p>
                <div class="flex justify-center gap-3">
                    <form method="dialog"><button class="px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-lg font-medium transition-colors">Cancel</button></form>
                    <button id="confirmReviewBtn" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium transition-colors shadow-sm">Confirm Review</button>
                </div>
            </div>
        </div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<script>
var API_BASE_URL = '<?php echo url('/api/v1'); ?>';
var PSM_QUOTES_API = `${API_BASE_URL}/psm/vendor-quote`;
var PSM_PURCHASES_API = `${API_BASE_URL}/psm/purchase-management`;
var PSM_PURCHASE_REQUESTS_API = `${API_BASE_URL}/psm/purchase-requests`;
var VENDOR_ME_API = `${API_BASE_URL}/vendor/auth/me`;

var CSRF_TOKEN = typeof CSRF_TOKEN !== 'undefined' ? CSRF_TOKEN : document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
var JWT_TOKEN = typeof JWT_TOKEN !== 'undefined' ? JWT_TOKEN : localStorage.getItem('jwt');

var elements = {
    newPurchaseBadge: document.getElementById('newPurchaseBadgePulse'),
    newPurchaseCountSpan: document.getElementById('newPurchasePulseCount'),
    notifModal: document.getElementById('my_modal_4'),
    notifTableBody: document.getElementById('notifTableBody'),
    notifPrevBtn: document.getElementById('notifPrevBtn'),
    notifNextBtn: document.getElementById('notifNextBtn'),
    notifPagerInfo: document.getElementById('notifPagerInfo'),
    notifPageDisplay: document.getElementById('notifPageDisplay'),
    quotesTableBody: document.getElementById('quotesTableBody'),
    // Stats
    totalQuotes: document.getElementById('totalQuotes'),
    poReceivedQuotes: document.getElementById('poReceivedQuotes'),
    processingQuotes: document.getElementById('processingQuotes'),
    deliveredQuotes: document.getElementById('deliveredQuotes'),
    viewQuoteModal: document.getElementById('viewQuoteModal'),
    viewQuoteContent: document.getElementById('viewQuoteContent'),
    updateStatusModal: document.getElementById('updateStatusModal'),
    statusSelect: document.getElementById('statusSelect'),
    confirmUpdateStatusBtn: document.getElementById('confirmUpdateStatusBtn'),
    setDeliveryModal: document.getElementById('setDeliveryModal'),
    deliveryDateInput: document.getElementById('deliveryDateInput'),
    confirmSetDeliveryBtn: document.getElementById('confirmSetDeliveryBtn'),
    deleteQuoteModal: document.getElementById('deleteQuoteModal'),
    confirmDeleteQuoteBtn: document.getElementById('confirmDeleteQuoteBtn'),
    reviewConfirmModal: document.getElementById('reviewConfirmModal'),
    confirmReviewBtn: document.getElementById('confirmReviewBtn')
};

var currentNotifications = [];
var currentQuotes = [];
var quotesLoadingTimer = null;
let currentQuotesPage = 1;
const quotesPageSize = 10;
let currentQuoteStatusFilter = '';
let vendorVendorId = null;
let currentNotifPage = 1;
const notifPageSize = 10;

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
var selectedRequestPreqId = null;

initVendorQuote();

async function initVendorQuote() {
    await loadVendorSelf();
    await loadNotifications();
    await loadQuotes();
}

async function loadVendorSelf() {
    try {
        const response = await fetch(`${VENDOR_ME_API}?t=${new Date().getTime()}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            credentials: 'include'
        });
        if (response.ok) {
            const result = await response.json();
            if (result && result.success && result.user && result.user.vendorid) {
                vendorVendorId = result.user.vendorid;
            }
        }
    } catch(e) {}
}

function setNotificationIndicator(count) {
    if (!elements.newPurchaseBadge || !elements.newPurchaseCountSpan) return;
    if (count > 0) {
        elements.newPurchaseBadge.classList.remove('hidden');
        var display = count > 99 ? '99' : String(count);
        elements.newPurchaseCountSpan.textContent = display;
    } else {
        elements.newPurchaseBadge.classList.add('hidden');
    }
}

async function loadNotifications() {
    try {
        // Ensure all current Pending purchases are mirrored into requests
        try {
            await fetch(`${PSM_PURCHASE_REQUESTS_API}/sync?t=${new Date().getTime()}`, {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': CSRF_TOKEN,
                    'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
                },
                credentials: 'include'
            });
        } catch (e) {
            // Non-blocking; proceed to fetch list even if sync fails
        }
        const qVendor = vendorVendorId ? `&vendor_id=${encodeURIComponent(vendorVendorId)}` : '';
        const response = await fetch(`${PSM_PURCHASE_REQUESTS_API}?status=Pending${qVendor}&t=${new Date().getTime()}`, {
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
            let list = result.data || [];
            if (vendorVendorId) {
                list = list.filter(r => String(r.preq_ven_id || '') === String(vendorVendorId));
            }
            currentNotifications = list;
            currentNotifPage = 1;
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

function renderNotifPage() {
    if (!elements.notifTableBody) return;
    const total = currentNotifications.length || 0;
    const totalPages = Math.max(1, Math.ceil(total / notifPageSize));
    if (currentNotifPage > totalPages) currentNotifPage = totalPages;
    if (currentNotifPage < 1) currentNotifPage = 1;
    const start = (currentNotifPage - 1) * notifPageSize;
    const end = Math.min(start + notifPageSize, total);
    const slice = currentNotifications.slice(start, end);

    if (total === 0) {
        elements.notifTableBody.innerHTML = `
            <tr>
                <td colspan="10" class="px-6 py-8 text-center text-gray-500">
                    <i class='bx bxs-bell-off text-4xl text-gray-300 mb-3'></i>
                    <p class="text-lg">No pending purchase requests</p>
                </td>
            </tr>`;
    } else {
        elements.notifTableBody.innerHTML = slice.map((r) => {
        const itemsText = Array.isArray(r.preq_name_items) ? r.preq_name_items.map(i => typeof i === 'object' ? (i.name || '') : (i || '')).join(', ') : '';
        return `
            <tr>
                <td class="px-3 py-2 font-mono whitespace-nowrap font-medium text-blue-600">${r.preq_id}</td>
                <td class="px-3 py-2 whitespace-nowrap" title="${itemsText}">${truncateItems(r.preq_name_items, 40)}</td>
                <td class="px-3 py-2 whitespace-nowrap">${r.preq_unit} units</td>
                <td class="px-3 py-2 whitespace-nowrap text-green-600 font-bold">${formatCurrency(r.preq_total_amount)}</td>
                <td class="px-3 py-2 whitespace-nowrap">${r.preq_ven_company_name || ''}</td>
                <td class="px-3 py-2 whitespace-nowrap">${r.preq_ven_type || ''}</td>
                <td class="px-3 py-2 whitespace-nowrap">${r.preq_status}</td>
                <td class="px-3 py-2 whitespace-nowrap">${r.preq_order_by || ''}</td>
                <td class="px-3 py-2 whitespace-nowrap" title="${r.preq_desc || ''}">${(r.preq_desc || '').substring(0, 32)}${(r.preq_desc||'').length>32?'…':''}</td>
                <td class="px-3 py-2 whitespace-nowrap">
                    <div class="flex items-center space-x-2">
                        <button class="text-gray-700 hover:text-gray-900 transition-colors p-2 rounded-lg hover:bg-gray-50" data-action="view-po" data-id="${r.preq_id}" title="View Purchase Order">
                            <i class='bx bx-show-alt text-xl'></i>
                        </button>
                        <button class="text-indigo-600 hover:text-indigo-900 transition-colors p-2 rounded-lg hover:bg-indigo-50" data-action="review-po" data-id="${r.preq_id}" title="Received Purchase Order">
                            <i class='bx bx-check-circle text-xl'></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
        }).join('');
    }
    if (elements.notifPageDisplay) elements.notifPageDisplay.textContent = `${currentNotifPage} / ${totalPages}`;
    if (elements.notifPrevBtn) elements.notifPrevBtn.disabled = currentNotifPage <= 1;
    if (elements.notifNextBtn) elements.notifNextBtn.disabled = currentNotifPage >= totalPages;
    if (elements.notifPagerInfo) {
        const from = total === 0 ? 0 : (start + 1);
        const to = end;
        elements.notifPagerInfo.textContent = `${from}–${to} of ${total}`;
    }
}

function displayNotifications(list) {
    currentNotifications = Array.isArray(list) ? list : [];
    renderNotifPage();
}

window.viewApprovedPurchase = function(id) {
    const purchase = currentNotifications.find(p => p.id === id);
    if (!purchase) return;
    
    const content = document.getElementById('viewApprovedPurchaseContent');
    const modal = document.getElementById('viewApprovedPurchaseModal');
    if (!content || !modal) return;

    // Build HTML for purchase details
    let itemsHtml = '';
    if (Array.isArray(purchase.pur_name_items)) {
        itemsHtml = purchase.pur_name_items.map(item => {
             const name = typeof item === 'object' ? item.name : item;
             const price = typeof item === 'object' ? (item.price || 0) : 0;
             return `
            <div class="flex justify-between py-2 border-b border-gray-100 last:border-0">
                <span class="font-medium text-gray-800">${name || 'Unknown Item'}</span>
                <span class="text-gray-600 font-mono">${formatCurrency(price)}</span>
            </div>
        `}).join('');
    }

    content.innerHTML = `
        <div class="grid grid-cols-2 gap-y-4 gap-x-6 mb-6">
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold mb-1">Purchase ID</p>
                <p class="font-mono font-bold text-gray-900 text-lg">${purchase.pur_id}</p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold mb-1">Date</p>
                <p class="font-bold text-gray-900">${formatDate(purchase.created_at)}</p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold mb-1">Total Amount</p>
                <p class="font-bold text-green-600 text-lg">${formatCurrency(purchase.pur_total_amount)}</p>
            </div>
            <div>
                <p class="text-xs uppercase tracking-wide text-gray-500 font-semibold mb-1">Status</p>
                <span class="px-2 py-1 rounded-full text-xs font-bold bg-green-100 text-green-800 border border-green-200">${purchase.pur_status}</span>
            </div>
        </div>
        <div>
            <h4 class="font-bold text-gray-800 mb-3 flex items-center gap-2"><i class='bx bx-list-ul'></i> Items</h4>
            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                ${itemsHtml || '<p class="text-gray-500 italic">No items listed</p>'}
            </div>
        </div>
    `;

    modal.showModal();
    if (elements.notifModal) elements.notifModal.setAttribute('inert', '');
}

const viewApprovedPurchaseModal = document.getElementById('viewApprovedPurchaseModal');
if (viewApprovedPurchaseModal) {
    viewApprovedPurchaseModal.addEventListener('close', () => {
        if (elements.notifModal) elements.notifModal.removeAttribute('inert');
    });
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
                elements.notifModal?.close();
                if (elements.notifModal) elements.notifModal.removeAttribute('inert');
                updateNotifIndicatorFromDOM();
                
                Toast.fire({
                    icon: 'success',
                    title: 'Successfully Confirm Review'
                });
            }
        } catch(e) {}
    });
}

if (elements.reviewConfirmModal) {
    elements.reviewConfirmModal.addEventListener('close', function() {
        if (elements.notifModal) elements.notifModal.removeAttribute('inert');
    });
}

// Pagination controls for New Purchase Order modal
if (elements.notifPrevBtn) {
    elements.notifPrevBtn.addEventListener('click', function(){
        if (currentNotifPage > 1) {
            currentNotifPage--;
            renderNotifPage();
        }
    });
}
if (elements.notifNextBtn) {
    elements.notifNextBtn.addEventListener('click', function(){
        const totalPages = Math.max(1, Math.ceil((currentNotifications.length || 0) / notifPageSize));
        if (currentNotifPage < totalPages) {
            currentNotifPage++;
            renderNotifPage();
        }
    });
}

window.viewApprovedPurchase = function(id) {
    const p = currentNotifications.find(x => x.id == id);
    if (!p) return;
    const items = Array.isArray(p.pur_name_items) ? p.pur_name_items : [];
    const itemsHtml = items.map(item => {
        const itemName = typeof item === 'object' ? item.name : item;
        const itemPrice = typeof item === 'object' ? formatCurrency(item.price) : 'N/A';
        return `
        <div class="flex justify-between items-center py-3 border-b border-gray-100 last:border-0 hover:bg-gray-50 px-2 rounded-lg transition-colors">
            <div class="flex items-center gap-3">
                <div class="p-2 bg-blue-50 rounded-lg text-blue-600">
                    <i class='bx bx-package'></i>
                </div>
                <span class="font-medium text-gray-700">${itemName}</span>
            </div>
            <span class="font-bold text-gray-900">${itemPrice}</span>
        </div>`;
    }).join('');

    const html = `
        <div class="space-y-6">
            <div class="flex justify-between items-start border-b border-gray-100 pb-4">
                <div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Purchase Order No.</span>
                    <h4 class="text-2xl font-bold text-blue-600 font-mono">${p.pur_id}</h4>
                </div>
                <div class="text-right">
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-1">Total Amount</span>
                    <div class="text-2xl font-bold text-green-600">${formatCurrency(p.pur_total_amount)}</div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-2">Vendor Details</label>
                    <div class="space-y-2">
                        <div class="flex items-center gap-2">
                            <i class='bx bx-building-house text-gray-400'></i>
                            <span class="font-semibold text-gray-700">${p.pur_company_name || 'N/A'}</span>
                        </div>
                        <div class="flex items-center gap-2">
                            <i class='bx bx-category text-gray-400'></i>
                            <span class="capitalize text-gray-600">${p.pur_ven_type || 'N/A'}</span>
                        </div>
                    </div>
                </div>
                <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                    <label class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-2">Order Info</label>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-gray-500">Units:</span>
                            <span class="font-semibold text-gray-700">${p.pur_unit}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Ordered By:</span>
                            <span class="font-semibold text-gray-700">${p.pur_order_by || 'Not specified'}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-500">Approved By:</span>
                            <span class="font-semibold text-gray-700">${p.pur_approved_by || 'Not approved'}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div>
                <label class="text-xs font-bold text-gray-400 uppercase tracking-wider block mb-3 flex justify-between items-center">
                    <span>Items List</span>
                    <span class="bg-blue-100 text-blue-800 text-xs px-2 py-0.5 rounded-full">${items.length} items</span>
                </label>
                <div class="bg-gray-50 rounded-xl border border-gray-100 p-2 max-h-60 overflow-y-auto custom-scrollbar">
                    ${itemsHtml || '<div class="text-center py-4 text-gray-500">No items found</div>'}
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-between items-center">
                <div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block">Status</span>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 mt-1">
                        <i class='bx bx-check-circle mr-1'></i>
                        ${p.pur_status}
                    </span>
                </div>
                <div>
                    <span class="text-xs font-bold text-gray-400 uppercase tracking-wider block text-right">Received Date</span>
                    <span class="text-sm font-medium text-gray-700 mt-1 block">${formatDate(p.created_at)}</span>
                </div>
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
        const response = await fetch(`${PSM_QUOTES_API}?t=${new Date().getTime()}`, {
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
            currentQuotes = (result.data || []).map(q => {
                // Force generation of Quote ID to match requested format
                // Format: QUOT + YYYYMMDD + 5 random alphanumeric
                // We check if it already matches strict format to avoid constant regeneration on same page load if we were to cache, 
                // but since we reload from API, we'll regenerate to ensure "refresh" works as requested.
                // However, to prevent flickering if ID is already correct (from DB in future), we check prefix.
                
                const today = new Date();
                const dateStr = today.getFullYear() + String(today.getMonth() + 1).padStart(2, '0') + String(today.getDate()).padStart(2, '0');
                
                // User requested specific format. If existing ID doesn't match this date pattern or prefix, regenerate.
                // For now, we will just force generate if it's not starting with QUOT + dateStr to be safe.
                if (!q.quo_id || !String(q.quo_id).startsWith(`QUOT${dateStr}`)) {
                    const randomStr = Math.random().toString(36).substring(2, 7).toUpperCase();
                    q.quo_id = `QUOT${dateStr}${randomStr}`;
                }
                return q;
            });
            loadQuoteStats();
            displayQuotes(currentQuotes);
        } else {
            currentQuotes = [];
            loadQuoteStats();
            displayQuotes([]);
            if (typeof showNotification === 'function') showNotification(result.message || 'Failed to load quotes', 'error');
        }
    } catch(e) {
        currentQuotes = [];
        loadQuoteStats();
        displayQuotes([]);
        if (typeof showNotification === 'function') showNotification('Error loading quotes: ' + (e && e.message ? e.message : 'Unknown error'), 'error');
    } finally {
        if (quotesLoadingTimer) { clearTimeout(quotesLoadingTimer); quotesLoadingTimer = null; }
        safeHideLoading();
    }
}

function displayQuotes(list) {
    if (!elements.quotesTableBody) return;
    const src = list || [];
    const filteredList = currentQuoteStatusFilter ? src.filter(q => normalizeQuoteStatus(q.quo_status) === currentQuoteStatusFilter) : src;
    const total = filteredList.length;
    if (!list || total === 0) {
        elements.quotesTableBody.innerHTML = `
            <tr>
                <td colspan="10" class="px-6 py-8 text-center text-gray-500">
                    <i class='bx bxs-quote-left text-4xl text-gray-300 mb-3'></i>
                    <p class="text-lg">No quotes found</p>
                </td>
            </tr>
        `;
        renderQuotesPager(0, 1);
        return;
    }
    const totalPages = Math.max(1, Math.ceil(total / quotesPageSize));
    if (currentQuotesPage > totalPages) currentQuotesPage = totalPages;
    if (currentQuotesPage < 1) currentQuotesPage = 1;
    const startIdx = (currentQuotesPage - 1) * quotesPageSize;
    const pageItems = filteredList.slice(startIdx, startIdx + quotesPageSize);
    elements.quotesTableBody.innerHTML = pageItems.map(function(q) {
        const normalizedStatus = normalizeQuoteStatus(q.quo_status);
        const isProcessing = normalizedStatus === 'Processing Order';
        const isDispatched = normalizedStatus === 'Dispatched';
        const isDelivered = normalizedStatus === 'Delivered';
        const isReceived = normalizedStatus === 'PO Received';
        const rowClass = isDelivered ? 'bg-gray-200' : '';
        const idStr = String(q.id);
        const statusStr = String(normalizedStatus || '');
        const dateStr = q.quo_delivery_date || '';
        
        const quoId = q.quo_id;

        let actions = "";
        const viewBtn = `<button class="text-gray-700 hover:text-gray-900 transition-colors p-2 rounded-lg hover:bg-gray-50" data-action="view" data-id="${idStr}" title="View"><i class='bx bx-show-alt text-xl'></i></button>`;
        const updateBtn = `<button class="text-green-600 hover:text-green-900 transition-colors p-2 rounded-lg hover:bg-green-50" data-action="update" data-id="${idStr}" data-status="${statusStr}" title="Update Status"><i class='bx bx-edit text-xl'></i></button>`;
        const deliveryBtn = `<button class="text-indigo-600 hover:text-indigo-900 transition-colors p-2 rounded-lg hover:bg-indigo-50" data-action="delivery" data-id="${idStr}" data-date="${dateStr}" title="Set Date Delivery"><i class='bx bx-calendar text-xl'></i></button>`;
        // Delete button intentionally hidden per requirement
        
        if (isDelivered) {
            actions = viewBtn;
        } else if (isProcessing || isDispatched) {
            actions = viewBtn + updateBtn + deliveryBtn;
        } else if (isReceived) {
            actions = viewBtn + updateBtn;
        } else {
            actions = viewBtn + updateBtn;
        }
        
        const itemsList = Array.isArray(q.quo_items) ? q.quo_items.map(i => typeof i === 'object' ? i.name : i).join(', ') : '';
        
        return `
            <tr class="${rowClass}">
                <td class="px-3 py-2 whitespace-nowrap font-mono font-bold text-black">${quoId}</td>
                <td class="px-3 py-2 whitespace-nowrap" title="${itemsList}">${truncateItems(q.quo_items, 40)}</td>
                <td class="px-3 py-2 whitespace-nowrap">${q.quo_units} units</td>
                <td class="px-3 py-2 whitespace-nowrap font-bold text-gray-700">${formatCurrency(q.quo_total_amount)}</td>
                <td class="px-3 py-2 whitespace-nowrap">${formatDate(q.quo_delivery_date)}</td>
                <td class="px-3 py-2 whitespace-nowrap">
                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-sm font-semibold ${getStatusBadgeClass(normalizedStatus)}">
                        <i class='bx ${getStatusIcon(normalizedStatus)} mr-1'></i>${normalizedStatus}
                    </span>
                </td>
                <td class="px-3 py-2 whitespace-nowrap">
                    <div class="flex items-center space-x-2">${actions}</div>
                </td>
            </tr>`;
    }).join('');
    renderQuotesPager(total, totalPages);
}

function renderQuotesPager(total, totalPages){
    const info = document.getElementById('quotesPagerInfo');
    const display = document.getElementById('quotesPageDisplay');
    const start = total === 0 ? 0 : ((currentQuotesPage - 1) * quotesPageSize) + 1;
    const end = Math.min(currentQuotesPage * quotesPageSize, total);
    if (info) info.textContent = `Showing ${start}-${end} of ${total}`;
    if (display) display.textContent = `${currentQuotesPage} / ${totalPages}`;
    const prev = document.getElementById('quotesPrevBtn');
    const next = document.getElementById('quotesNextBtn');
    if (prev) prev.disabled = currentQuotesPage <= 1;
    if (next) next.disabled = currentQuotesPage >= totalPages;
}

document.getElementById('quotesPager').addEventListener('click', function(ev){
    const btn = ev.target.closest('button[data-action]');
    if(!btn) return;
    const act = btn.getAttribute('data-action');
    if(act === 'prev'){ currentQuotesPage = Math.max(1, currentQuotesPage - 1); displayQuotes(currentQuotes); }
    if(act === 'next'){ const max = Math.max(1, Math.ceil((currentQuotes.length||0)/quotesPageSize)); currentQuotesPage = Math.min(max, currentQuotesPage + 1); displayQuotes(currentQuotes); }
});

function filterQuoteStatus(status) {
    currentQuoteStatusFilter = status || '';
    currentQuotesPage = 1;
    displayQuotes(currentQuotes);
}
// Ensure availability for inline onclick handlers
window.filterQuoteStatus = filterQuoteStatus;

function loadQuoteStats() {
    const list = currentQuotes || [];
    const countBy = (name) => list.filter(q => normalizeQuoteStatus(q.quo_status) === name).length;
    const stats = {
        total: list.length,
        received: countBy('PO Received'),
        processing: countBy('Processing Order'),
        delivered: countBy('Delivered')
    };
    if (elements.totalQuotes) elements.totalQuotes.textContent = stats.total;
    if (elements.poReceivedQuotes) elements.poReceivedQuotes.textContent = stats.received;
    if (elements.processingQuotes) elements.processingQuotes.textContent = stats.processing;
    if (elements.deliveredQuotes) elements.deliveredQuotes.textContent = stats.delivered;
}

function viewQuote(id) {
    const q = currentQuotes.find(x => x.id == id);
    if (!q) return;
    if (!elements.viewQuoteContent || !elements.viewQuoteModal) return;
    elements.viewQuoteContent.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><span class="text-sm text-gray-500">Quote ID</span><p class="font-semibold">${q.quo_id}</p></div>
            <div><span class="text-sm text-gray-500">Status</span><p class="font-semibold">${normalizeQuoteStatus(q.quo_status)}</p></div>
            <div class="md:col-span-2"><span class="text-sm text-gray-500">Items</span><p class="font-semibold">${Array.isArray(q.quo_items) ? q.quo_items.map(i => typeof i === 'object' ? i.name : i).join(', ') : ''}</p></div>
            <div><span class="text-sm text-gray-500">Units</span><p class="font-semibold">${q.quo_units}</p></div>
            <div><span class="text-sm text-gray-500">Total Amount</span><p class="font-semibold">${formatCurrency(q.quo_total_amount)}</p></div>
            <div class="md:col-span-2"><span class="text-sm text-gray-500">Delivery Date</span><p class="font-semibold">${formatDate(q.quo_delivery_date)}</p></div>
        </div>
    `;
    elements.viewQuoteModal.showModal();
}

function openUpdateStatus(id, current) {
    selectedQuoteId = id;
    if (elements.statusSelect) elements.statusSelect.value = normalizeQuoteStatus(current || 'PO Received');
    elements.updateStatusModal?.showModal();
}

if (elements.confirmUpdateStatusBtn) {
    elements.confirmUpdateStatusBtn.addEventListener('click', async function() {
        if (!selectedQuoteId) return;
        
        // Show loading state on button
        const originalBtnText = elements.confirmUpdateStatusBtn.innerHTML;
        elements.confirmUpdateStatusBtn.innerHTML = '<span class="loading loading-spinner loading-xs"></span> Updating...';
        elements.confirmUpdateStatusBtn.disabled = true;

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
            
            if (!response.ok) {
                throw new Error(`HTTP ${response.status}: ${response.statusText}`);
            }

            const result = await response.json();
            
            if (result.success) {
                // Close modal forcefully
                const modal = document.getElementById('updateStatusModal');
                if (modal) modal.close();
                selectedQuoteId = null;

                // Show success message
                if (elements.statusSelect.value === 'Delivered') {
                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'success',
                        title: 'Purchase order delivered',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true
                    });
                } else {
                    if (typeof showNotification === 'function') {
                        showNotification('Quote status updated', 'success');
                    } else {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'Quote status updated',
                            showConfirmButton: false,
                            timer: 3000
                        });
                    }
                }

                // Reload data
                await loadQuotes();
                await loadNotifications(); 
            } else {
                throw new Error(result.message || 'Update failed');
            }
        } catch(e) { 
            console.error('Update status error:', e);
            if (typeof showNotification === 'function') {
                showNotification('Error updating status: ' + (e && e.message ? e.message : 'Unknown error'), 'error'); 
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error updating status: ' + (e && e.message ? e.message : 'Unknown error')
                });
            }
        } finally {
            // Restore button state
            if (elements.confirmUpdateStatusBtn) {
                elements.confirmUpdateStatusBtn.innerHTML = originalBtnText;
                elements.confirmUpdateStatusBtn.disabled = false;
            }
        }
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
    const mm = String(dt.getMonth() + 1).padStart(2, '0');
    const dd = String(dt.getDate()).padStart(2, '0');
    const yyyy = dt.getFullYear();
    return `${mm}-${dd}-${yyyy}`;
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

function normalizeQuoteStatus(status) {
    const s = String(status || '').trim();
    if (s === 'Vendor-Review') return 'PO Received';
    if (s === 'In-Progress') return 'Processing Order';
    if (s === 'Completed') return 'Delivered';
    return s;
}

function getStatusBadgeClass(status) {
    const s = normalizeQuoteStatus(status);
    const statusClasses = {
        'Pending': 'bg-yellow-600 text-white',
        'Approved': 'bg-blue-600 text-white',
        'Rejected': 'bg-red-600 text-white',
        'Cancel': 'bg-red-600 text-white',
        'PO Received': 'bg-purple-600 text-white',
        'Processing Order': 'bg-indigo-600 text-white',
        'Dispatched': 'bg-amber-600 text-white',
        'Delivered': 'bg-green-600 text-white'
    };
    return statusClasses[s] || 'bg-gray-600 text-white';
}

function getStatusIcon(status) {
    const s = normalizeQuoteStatus(status);
    const statusIcons = {
        'Pending': 'bx-time',
        'Approved': 'bx-check-circle',
        'Rejected': 'bx-x-circle',
        'Cancel': 'bx-x-circle',
        'PO Received': 'bx-user-voice',
        'Processing Order': 'bx-cog',
        'Dispatched': 'bx-send',
        'Delivered': 'bx-check-circle'
    };
    return statusIcons[s] || 'bx-question-mark';
}
if (elements.notifTableBody) {
    elements.notifTableBody.addEventListener('click', function(e) {
        const btn = e.target.closest('button');
        if (!btn) return;
        const action = btn.dataset.action;
        const id = btn.dataset.id;
        if (!id) return;
        if (action === 'view-po') {
            window.viewPurchaseOrder(String(id));
        } else if (action === 'review-po') {
            window.openReviewPurchaseOrder(String(id));
        }
    });
}

function viewPurchaseOrder(preqId) {
    const r = (currentNotifications || []).find(x => String(x.preq_id) === String(preqId));
    const modal = document.getElementById('viewPurchaseOrderModal');
    const content = document.getElementById('viewPurchaseOrderContent');
    if (!r || !modal || !content) return;
    const itemsHtml = Array.isArray(r.preq_name_items) ? r.preq_name_items.map(item => {
        const name = typeof item === 'object' ? (item.name || '') : String(item || '');
        const price = typeof item === 'object' ? (item.price || 0) : 0;
        return `<div class="flex justify-between py-2 border-b border-gray-100 last:border-0"><span class="font-medium text-gray-800">${name}</span><span class="text-gray-600 font-mono">${formatCurrency(price)}</span></div>`;
    }).join('') : '';
    content.innerHTML = `
        <div class="grid grid-cols-2 gap-y-4 gap-x-6 mb-6">
            <div><span class="text-xs uppercase tracking-wide text-gray-500 font-semibold mb-1">Request ID</span><p class="font-mono font-bold text-gray-900 text-lg">${r.preq_id}</p></div>
            <div><span class="text-xs uppercase tracking-wide text-gray-500 font-semibold mb-1">Status</span><p class="text-sm font-medium text-gray-700">${r.preq_status || ''}</p></div>
            <div><span class="text-xs uppercase tracking-wide text-gray-500 font-semibold mb-1">Vendor</span><p class="text-sm font-medium text-gray-700">${r.preq_ven_company_name || ''}</p></div>
            <div><span class="text-xs uppercase tracking-wide text-gray-500 font-semibold mb-1">Vendor Type</span><p class="text-sm font-medium text-gray-700">${r.preq_ven_type || ''}</p></div>
            <div class="col-span-2"><span class="text-xs uppercase tracking-wide text-gray-500 font-semibold mb-1">Description</span><p class="text-sm text-gray-700">${r.preq_desc || ''}</p></div>
        </div>
        <div class="bg-gray-50 rounded-lg border border-gray-200 p-4">
            <div class="flex items-center justify-between mb-2">
                <h4 class="text-sm font-bold text-gray-700">Items</h4>
                <span class="text-xs font-semibold text-gray-500">Total: <span class="text-gray-800 font-bold">${formatCurrency(r.preq_total_amount)}</span></span>
            </div>
            ${itemsHtml || '<p class="text-sm text-gray-500">No items found</p>'}
        </div>
    `;
    modal.showModal();
}

function openReviewPurchaseOrder(preqId) {
    selectedRequestPreqId = preqId;
    const modal = document.getElementById('reviewRequestConfirmModal');
    if (modal) modal.showModal();
}

const confirmReviewBtn = document.getElementById('confirmReviewRequestBtn');
const cancelReviewBtn = document.getElementById('cancelReviewRequestBtn');
if (cancelReviewBtn) {
    cancelReviewBtn.addEventListener('click', function(ev) {
        ev.preventDefault();
        const modal = document.getElementById('reviewRequestConfirmModal');
        if (modal) modal.close();
    });
}
if (confirmReviewBtn) {
    confirmReviewBtn.addEventListener('click', async function(ev) {
        ev.preventDefault();
        if (!selectedRequestPreqId) return;
        try {
            const response = await fetch(`${PSM_PURCHASE_REQUESTS_API}/${encodeURIComponent(selectedRequestPreqId)}/review`, {
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
                document.getElementById('reviewRequestConfirmModal')?.close();
                document.getElementById('viewPurchaseOrderModal')?.close();
                document.getElementById('my_modal_4')?.close();
                if (typeof showNotification === 'function') {
                    showNotification('Purchase order received', 'success');
                } else {
                    Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: 'Successfully reviewed purchase order', showConfirmButton: false, timer: 3000 });
                }
                await loadNotifications();
                await loadQuotes();
            } else {
                throw new Error(result.message || 'Failed to review');
            }
        } catch (e) {
            if (typeof showNotification === 'function') showNotification('Error reviewing purchase order: ' + (e && e.message ? e.message : 'Unknown error'), 'error');
        } finally {
            selectedRequestPreqId = null;
        }
    });
}

// Expose modal handlers for delegated calls
window.viewPurchaseOrder = viewPurchaseOrder;
window.openReviewPurchaseOrder = openReviewPurchaseOrder;

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
            window.openSetDelivery(Number(id), btn.dataset.date);
        } else if (action === 'delete') {
            window.openDeleteQuote(Number(id));
        }
    });
}
function openSetDelivery(id, date) {
    selectedQuoteId = id;
    if (elements.deliveryDateInput) elements.deliveryDateInput.value = date ? date.substring(0,10) : '';
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
                quo_delivery_date: elements.deliveryDateInput.value || null
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
                selectedQuoteId = null;
                elements.setDeliveryModal?.close();
                if (typeof showNotification === 'function') showNotification('Delivery dates updated', 'success');
                
                await loadQuotes();
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
