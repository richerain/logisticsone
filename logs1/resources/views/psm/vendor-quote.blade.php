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