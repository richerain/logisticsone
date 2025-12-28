<!-- resources/views/alms/asset-management.blade.php -->
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bxs-archive'></i>Asset Management</h2>
    </div>
    <div class="text-right">
        <span class="text-md text-gray-600">Asset Lifecycle & Maintenance</span>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">Assets</h3>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="stat-card bg-blue-50 p-4 rounded-lg text-center">
            <i class='bx bx-cube text-3xl text-blue-600 mb-2'></i>
            <h3 class="font-semibold text-blue-800">Total Assets</h3>
            <p id="totalAssetsCount" class="text-2xl font-bold text-blue-600">0</p>
        </div>
        <div class="stat-card bg-green-50 p-4 rounded-lg text-center">
            <i class='bx bx-check-shield text-3xl text-green-600 mb-2'></i>
            <h3 class="font-semibold text-green-800">Operational</h3>
            <p id="operationalCount" class="text-2xl font-bold text-green-600">0</p>
        </div>
        <div class="stat-card bg-yellow-50 p-4 rounded-lg text-center">
            <i class='bx bx-wrench text-3xl text-yellow-600 mb-2'></i>
            <h3 class="font-semibold text-yellow-800">Under Maintenance</h3>
            <p id="underMaintenanceCount" class="text-2xl font-bold text-yellow-600">0</p>
        </div>
        <div class="stat-card bg-red-50 p-4 rounded-lg text-center">
            <i class='bx bx-error text-3xl text-red-600 mb-2'></i>
            <h3 class="font-semibold text-red-800">Out of Service</h3>
            <p id="outOfServiceCount" class="text-2xl font-bold text-red-600">0</p>
        </div>
    </div>

    <div class="flex items-center justify-between gap-3 mb-4">
        <div class="flex items-center gap-2">
            <label class="text-sm text-gray-600">Category</label>
            <select id="assetFilterCategory" class="select select-bordered select-sm">
                <option value="all">All</option>
            </select>
        </div>
        <div class="flex items-center gap-2">
            <label class="text-sm text-gray-600">Status</label>
            <select id="assetFilterStatus" class="select select-bordered select-sm">
                <option value="all">All</option>
                <option value="operational">Operational</option>
                <option value="under_maintenance">Under Maintenance</option>
                <option value="out_of_service">Out of Service</option>
            </select>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="table table-zebra w-full" id="assetsTable">
            <thead>
                <tr class="bg-gray-700 font-bold text-white">
                    <th class="whitespace-nowrap">Asset ID</th>
                    <th class="whitespace-nowrap">Asset Name</th>
                    <th class="whitespace-nowrap">Category</th>
                    <th class="whitespace-nowrap">Location</th>
                    <th class="whitespace-nowrap">Status</th>
                    <th class="whitespace-nowrap">Last Maintenance</th>
                    <th class="whitespace-nowrap">Next Maintenance</th>
                    <th class="whitespace-nowrap">Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr id="loadingStateRow">
                    <td colspan="8">
                        <div class="text-center py-8">
                            <div class="loading loading-spinner loading-lg"></div>
                            <p class="mt-2 text-gray-600">Loading Assets...</p>
                            <p class="text-sm text-gray-500" id="loading"></p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div id="assetsPager" class="flex items-center justify-between mt-4">
        <div id="assetsPagerInfo" class="text-sm text-gray-600"></div>
        <div class="join">
            <button class="btn btn-sm join-item" id="assetsPrevBtn" data-action="prev">Prev</button>
            <span class="btn btn-sm join-item" id="assetsPageDisplay">1 / 1</span>
            <button class="btn btn-sm join-item" id="assetsNextBtn" data-action="next">Next</button>
        </div>
    </div>

    

    <dialog id="viewAssetModal" class="modal">
        <div class="modal-box max-w-2xl">
            <h3 class="font-bold text-lg">Asset Details</h3>
            <div id="viewAssetContent" class="mt-4"></div>
            <div class="modal-action">
                <button class="btn" onclick="document.getElementById('viewAssetModal').close()">Close</button>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop"><button>close</button></form>
    </dialog>

    <dialog id="statusAssetModal" class="modal">
        <div class="modal-box max-w-md">
            <h3 class="font-bold text-lg">Change Asset Status</h3>
            <div class="mt-4">
                <select id="statusSelect" class="select select-bordered w-full">
                    <option value="operational">Operational</option>
                    <option value="under_maintenance">Under Maintenance</option>
                    <option value="out_of_service">Out of Service</option>
                </select>
            </div>
            <div class="modal-action">
                <button class="btn" onclick="document.getElementById('statusAssetModal').close()">Cancel</button>
                <button id="saveStatusBtn" class="btn btn-primary" type="button">Save</button>
            </div>
        </div>
        <form method="dialog" class="modal-backdrop"><button>close</button></form>
    </dialog>

    <dialog id="requestMaintenanceModal" class="modal">
        <div class="modal-box max-w-xl">
            <h3 class="font-bold text-lg">Request for Maintenance</h3>
            <form id="requestMaintenanceForm" class="mt-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="label"><span class="label-text">Maintenance Type</span></label>
                        <input type="text" id="req_type" class="input input-bordered w-full" placeholder="Preventive, Corrective..." required />
                    </div>
                    <div>
                        <label class="label"><span class="label-text">Priority</span></label>
                        <select id="req_priority" class="select select-bordered w-full">
                            <option value="low">Low</option>
                            <option value="medium" selected>Medium</option>
                            <option value="high">High</option>
                        </select>
                    </div>
                </div>
                <div class="modal-action">
                    <button type="button" id="cancelRequestMaintenance" class="btn">Cancel</button>
                    <button type="submit" id="sendRequestBtn" class="btn btn-primary">Send Request</button>
                </div>
            </form>
        </div>
        <form method="dialog" class="modal-backdrop"><button>close</button></form>
    </dialog>

    <script>
        (function() {
            
            const table = document.getElementById('assetsTable').querySelector('tbody');
            const csrf = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const loadingInfo = document.getElementById('loading');
            let assetsState = [];
            let currentAssetsPage = 1;
            const assetsPageSize = 10;
            let currentRequestAsset = null;
            const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 1500, timerProgressBar: true });
            let assetFilterCategory = 'all';
            let assetFilterStatus = 'all';

            function statusBadge(status) {
                if (status === 'operational') return "<span class='badge badge-success inline-flex items-center gap-1'><i class='bx bx-check-shield text-current'></i>Operational</span>";
                if (status === 'under_maintenance') return "<span class='badge badge-warning inline-flex items-center gap-1'><i class='bx bx-wrench text-current'></i>Under Maintenance</span>";
                if (status === 'out_of_service') return "<span class='badge badge-error inline-flex items-center gap-1'><i class='bx bx-error text-current'></i>Out of Service</span>";
                return "<span class='badge badge-info inline-flex items-center gap-1'><i class='bx bx-info-circle text-current'></i>N/A</span>";
            }

            function renderAssets(assets) {
                table.innerHTML = '';
                if (!assets || assets.length === 0) {
                    table.innerHTML = `<tr><td colspan="8" class="px-4 py-3 text-center text-gray-500"><i class='bx bxs-archive mr-2'></i>No Assets Found</td></tr>`;
                    updateStats([]);
                    renderAssetsPager(0, 1);
                    return;
                }
                const filtered = assets.filter(a => {
                    const catOk = (assetFilterCategory === 'all') || (String(a.asset_category) === assetFilterCategory);
                    const statOk = (assetFilterStatus === 'all') || (String(a.asset_status) === assetFilterStatus);
                    return catOk && statOk;
                });
                const total = filtered.length;
                const totalPages = Math.max(1, Math.ceil(total / assetsPageSize));
                if (currentAssetsPage > totalPages) currentAssetsPage = totalPages;
                if (currentAssetsPage < 1) currentAssetsPage = 1;
                const start = (currentAssetsPage - 1) * assetsPageSize;
                const pageItems = filtered.slice(start, start + assetsPageSize);
                const rows = pageItems.map(a => `
                    <tr>
                        <td class="whitespace-nowrap">${a.asset_code}</td>
                        <td class="whitespace-nowrap">${a.asset_name}</td>
                        <td class="whitespace-nowrap">${a.asset_category ?? ''}</td>
                        <td class="whitespace-nowrap">${a.asset_location ?? ''}</td>
                        <td class="whitespace-nowrap">${statusBadge(a.asset_status)}</td>
                        <td class="whitespace-nowrap">${a.last_maintenance ?? ''}</td>
                        <td class="whitespace-nowrap">${a.next_maintenance ?? ''}</td>
                        <td class="whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <button class="text-indigo-600 transition-colors p-2 rounded-lg hover:bg-gray-50" title="View Asset" data-action="view" data-id="${a.id}"><i class='bx bx-show-alt text-xl'></i></button>
                                <button class="text-amber-600 transition-colors p-2 rounded-lg hover:bg-gray-50" title="Asset Status" data-action="status" data-id="${a.id}"><i class='bx bx-cog text-xl'></i></button>
                                ${(a.asset_status === 'operational' || a.asset_status === 'out_of_service') ? `<button class="text-green-600 transition-colors p-2 rounded-lg hover:bg-gray-50" title="Request for Maintenance" data-action="request" data-id="${a.id}"><i class='bx bxs-traffic-cone text-xl'></i></button>` : ''}
                                <button class="text-red-600 transition-colors p-2 rounded-lg hover:bg-gray-50" title="Delete Asset" data-action="delete" data-id="${a.id}"><i class='bx bx-trash text-xl'></i></button>
                            </div>
                        </td>
                    </tr>
                `).join('');
                table.innerHTML = rows;
                updateStats(filtered);
                renderAssetsPager(total, totalPages);
            }

            function renderAssetsPager(total, totalPages){
                const info = document.getElementById('assetsPagerInfo');
                const display = document.getElementById('assetsPageDisplay');
                const start = total === 0 ? 0 : ((currentAssetsPage - 1) * assetsPageSize) + 1;
                const end = Math.min(currentAssetsPage * assetsPageSize, total);
                if (info) info.textContent = `Showing ${start}-${end} of ${total}`;
                if (display) display.textContent = `${currentAssetsPage} / ${totalPages}`;
                const prev = document.getElementById('assetsPrevBtn');
                const next = document.getElementById('assetsNextBtn');
                if (prev) prev.disabled = currentAssetsPage <= 1;
                if (next) next.disabled = currentAssetsPage >= totalPages;
            }

            function updateStats(assets) {
                const total = assets.length;
                const operational = assets.filter(a => a.asset_status === 'operational').length;
                const underMaintenance = assets.filter(a => a.asset_status === 'under_maintenance').length;
                const outOfService = assets.filter(a => a.asset_status === 'out_of_service').length;

                const totalEl = document.getElementById('totalAssetsCount');
                const opEl = document.getElementById('operationalCount');
                const umEl = document.getElementById('underMaintenanceCount');
                const oosEl = document.getElementById('outOfServiceCount');

                if (totalEl) totalEl.textContent = total;
                if (opEl) opEl.textContent = operational;
                if (umEl) umEl.textContent = underMaintenance;
                if (oosEl) oosEl.textContent = outOfService;
            }

            function findAsset(id) { return assetsState.find(a => String(a.id) === String(id)); }

            function openViewModal(asset) {
                const modal = document.getElementById('viewAssetModal');
                const container = document.getElementById('viewAssetContent');
                container.innerHTML = `
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div><span class="text-sm text-gray-500">Asset ID</span><p class="font-semibold">${asset.asset_code}</p></div>
                        <div><span class="text-sm text-gray-500">Asset Name</span><p class="font-semibold">${asset.asset_name}</p></div>
                        <div><span class="text-sm text-gray-500">Category</span><p class="font-semibold">${asset.asset_category ?? ''}</p></div>
                        <div><span class="text-sm text-gray-500">Location</span><p class="font-semibold">${asset.asset_location ?? ''}</p></div>
                        <div><span class="text-sm text-gray-500">Status</span><p class="font-semibold">${asset.asset_status}</p></div>
                        <div><span class="text-sm text-gray-500">Last Maintenance</span><p class="font-semibold">${asset.last_maintenance ?? ''}</p></div>
                        <div><span class="text-sm text-gray-500">Next Maintenance</span><p class="font-semibold">${asset.next_maintenance ?? ''}</p></div>
                        <div><span class="text-sm text-gray-500">Created</span><p class="font-semibold">${asset.created_at ?? ''}</p></div>
                        <div><span class="text-sm text-gray-500">Updated</span><p class="font-semibold">${asset.updated_at ?? ''}</p></div>
                    </div>
                `;
                modal.showModal();
            }

            function openStatusModal(asset) {
                const modal = document.getElementById('statusAssetModal');
                const select = document.getElementById('statusSelect');
                const saveBtn = document.getElementById('saveStatusBtn');
                select.value = asset.asset_status;
                saveBtn.onclick = async function() {
                    try {
                        const res = await fetch(`/alms/assets/${asset.id}/status`, { method: 'PUT', headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf }, body: JSON.stringify({ asset_status: select.value }) });
                        const data = await res.json();
                        if (!res.ok) throw new Error(data.message || 'Failed to update status');
                        const idx = assetsState.findIndex(a => String(a.id) === String(asset.id));
                        if (idx !== -1) assetsState[idx] = data.asset;
                        renderAssets(assetsState);
                        Toast.fire({ icon: 'success', title: 'Status updated' });
                        modal.close();
                    } catch (e) {
                        Swal.fire({ icon: 'error', title: 'Error', text: e.message });
                    }
                };
                modal.showModal();
            }

            async function deleteAsset(asset) {
                const confirm = await Swal.fire({ title: 'Delete Asset?', text: 'This will permanently delete the asset.', icon: 'warning', showCancelButton: true, confirmButtonText: 'Delete', cancelButtonText: 'Cancel' });
                if (!confirm.isConfirmed) return;
                try {
                    const res = await fetch(`/alms/assets/${asset.id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': csrf } });
                    const data = await res.json();
                    if (!res.ok) throw new Error(data.message || 'Failed to delete asset');
                    assetsState = assetsState.filter(a => String(a.id) !== String(asset.id));
                    renderAssets(assetsState);
                    Toast.fire({ icon: 'success', title: 'Asset deleted' });
                } catch (e) {
                    Swal.fire({ icon: 'error', title: 'Error', text: e.message });
                }
            }

            async function loadAssets() {
                try {
                    const res = await fetch('/alms/assets', { headers: { 'Accept': 'application/json' } });
                    const json = await res.json();
                    const assets = json.data || [];
                    assetsState = assets;
                    updateFilterOptions();
                    renderAssets(assetsState);
                    
                } catch (e) {
                    loadingInfo.textContent = 'Failed to load assets';
                }
            }
            (async function(){
                await loadAssets();
            })();

            document.getElementById('assetsTable').addEventListener('click', function(ev) {
                const btn = ev.target.closest('button[data-action]');
                if (!btn) return;
                const id = btn.getAttribute('data-id');
                const asset = findAsset(id);
                if (!asset) return;
                const action = btn.getAttribute('data-action');
                if (action === 'view') return openViewModal(asset);
                if (action === 'status') return openStatusModal(asset);
                if (action === 'request') return openRequestModal(asset);
                if (action === 'delete') return deleteAsset(asset);
            });

            function getFilteredAssets(){
                return (assetsState||[]).filter(a => {
                    const catOk = (assetFilterCategory === 'all') || (String(a.asset_category) === assetFilterCategory);
                    const statOk = (assetFilterStatus === 'all') || (String(a.asset_status) === assetFilterStatus);
                    return catOk && statOk;
                });
            }

            document.getElementById('assetsPager').addEventListener('click', function(ev){
                const btn = ev.target.closest('button[data-action]');
                if(!btn) return;
                const act = btn.getAttribute('data-action');
                if(act === 'prev'){ currentAssetsPage = Math.max(1, currentAssetsPage - 1); renderAssets(assetsState); }
                if(act === 'next'){ const max = Math.max(1, Math.ceil((getFilteredAssets().length||0)/assetsPageSize)); currentAssetsPage = Math.min(max, currentAssetsPage + 1); renderAssets(assetsState); }
            });

            function updateFilterOptions(){
                const catSel = document.getElementById('assetFilterCategory');
                if(!catSel) return;
                const cats = Array.from(new Set((assetsState||[]).map(a => a.asset_category).filter(Boolean)));
                const opts = ['<option value="all">All</option>'].concat(cats.map(c => `<option value="${String(c)}">${String(c)}</option>`)).join('');
                catSel.innerHTML = opts;
            }

            const catSel = document.getElementById('assetFilterCategory');
            const statSel = document.getElementById('assetFilterStatus');
            if(catSel) catSel.addEventListener('change', function(){ assetFilterCategory = catSel.value || 'all'; currentAssetsPage=1; renderAssets(assetsState); });
            if(statSel) statSel.addEventListener('change', function(){ assetFilterStatus = statSel.value || 'all'; currentAssetsPage=1; renderAssets(assetsState); });

            function openRequestModal(asset){
                const modal = document.getElementById('requestMaintenanceModal');
                const typeInput = document.getElementById('req_type');
                const prioSelect = document.getElementById('req_priority');
                currentRequestAsset = asset;
                if(typeInput) typeInput.value = '';
                if(prioSelect) prioSelect.value = 'medium';
                modal.showModal();
            }

            const reqForm = document.getElementById('requestMaintenanceForm');
            const cancelReqBtn = document.getElementById('cancelRequestMaintenance');
            if(cancelReqBtn) cancelReqBtn.addEventListener('click', function(){ document.getElementById('requestMaintenanceModal').close(); });
            if(reqForm) reqForm.addEventListener('submit', async function(e){
                e.preventDefault();
                const payload = {
                    req_asset_name: (currentRequestAsset && currentRequestAsset.asset_name) ? currentRequestAsset.asset_name : '',
                    req_type: document.getElementById('req_type').value,
                    req_priority: document.getElementById('req_priority').value
                };
                if(!payload.req_asset_name){
                    Swal.fire({ icon: 'error', title: 'Error', text: 'Asset not found' });
                    return;
                }
                try {
                    const res = await fetch('/alms/request-maintenance', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
                        body: JSON.stringify(payload)
                    });
                    const data = await res.json();
                    if(!res.ok){ throw new Error(data.message || 'Failed to send request'); }
                    document.getElementById('requestMaintenanceModal').close();
                    Toast.fire({ icon: 'success', title: 'Request sent' });
                } catch (err) {
                    Swal.fire({ icon: 'error', title: 'Error', text: err.message });
                }
            });
        })();
    </script>
</div>