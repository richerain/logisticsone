<!-- resources/views/psm/vendor-products.blade.php -->
<style>
    #vendorProductsTable th,
    #vendorProductsTable td {
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
    }
</style>
<div class="mb-6 flex items-center justify-between gap-4">
    <div class="flex items-center">
        <h2 class="text-2xl font-bold text-gray-700"><i class='bx bx-fw bx-cube-alt'></i>Vendor Products Management</h2>
    </div>
    <div class="text-right">
        <span class="text-md text-gray-600">Procurement &amp; Sourcing Management</span>
    </div>
</div>

<div class="bg-white rounded-lg shadow-lg p-6">
    <div class="flex justify-between items-center mb-6">
        <h3 class="text-xl font-semibold text-gray-800">My Products</h3>
        <button id="addProductBtn" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-colors">
            <i class='bx bx-plus'></i>
            Add New Product
        </button>
    </div>

    <div class="overflow-x-auto">
        <table id="vendorProductsTable" class="table table-sm table-zebra w-full">
            <thead>
                <tr class="bg-gray-700 font-bold text-white">
                    <th class="whitespace-nowrap">ID</th>
                    <th class="whitespace-nowrap">Product ID</th>
                    <th class="whitespace-nowrap">Vendor ID</th>
                    <th class="whitespace-nowrap">Name</th>
                    <th class="whitespace-nowrap">Price</th>
                    <th class="whitespace-nowrap">Stock</th>
                    <th class="whitespace-nowrap">Type</th>
                    <th class="whitespace-nowrap">Warranty</th>
                    <th class="whitespace-nowrap">Expiration</th>
                    <th class="whitespace-nowrap">Description</th>
                    <th class="whitespace-nowrap">Module From</th>
                    <th class="whitespace-nowrap">Submodule From</th>
                    <th class="whitespace-nowrap">Created At</th>
                    <th class="whitespace-nowrap">Updated At</th>
                    <th class="whitespace-nowrap">Actions</th>
                </tr>
            </thead>
            <tbody id="vendorProductsTableBody">
                <tr>
                    <td colspan="15" class="px-6 py-8 text-center text-gray-500 whitespace-nowrap">
                        <div class="flex justify-center items-center py-4">
                            <div class="loading loading-spinner mr-3"></div>
                            Loading products...
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <div id="vendorProductsPager" class="mt-4 flex justify-between items-center">
        <div id="vendorProductsPagerInfo" class="text-sm text-gray-600"></div>
        <div class="join">
            <button class="join-item btn btn-sm" id="vendorProductsPrevBtn" data-action="prev">Prev</button>
            <span class="join-item btn btn-sm" id="vendorProductsPageDisplay">1 / 1</span>
            <button class="join-item btn btn-sm" id="vendorProductsNextBtn" data-action="next">Next</button>
        </div>
    </div>
</div>

<dialog id="productModal" class="modal">
    <div class="modal-box w-11/12 max-w-2xl">
        <div class="flex justify-between items-center mb-4">
            <h3 id="productModalTitle" class="text-xl font-semibold">Add Product</h3>
            <form method="dialog"><button><i class='bx bx-sm bx-x'></i></button></form>
        </div>
        <form id="productForm" class="space-y-4">
            <input type="hidden" id="productId">
            <input type="hidden" id="prod_vendor" name="prod_vendor">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="prod_name" class="block text-sm font-medium text-gray-700 mb-1">Product Name *</label>
                    <input type="text" id="prod_name" name="prod_name" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="prod_price" class="block text-sm font-medium text-gray-700 mb-1">Price *</label>
                    <div class="flex items-center border border-gray-300 rounded-lg">
                        <span class="px-3 text-gray-700">₱</span>
                        <input type="number" step="0.01" id="prod_price" name="prod_price" required class="w-full px-3 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 border-0">
                    </div>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="prod_stock" class="block text-sm font-medium text-gray-700 mb-1">Stock *</label>
                    <input type="number" id="prod_stock" name="prod_stock" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="prod_type" class="block text-sm font-medium text-gray-700 mb-1">Type *</label>
                    <select id="prod_type" name="prod_type" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Select Type</option>
                        <option value="equipment">Equipment</option>
                        <option value="supplies">Supplies</option>
                        <option value="furniture">Furniture</option>
                        <option value="automotive">Automotive</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label for="prod_warranty" class="block text-sm font-medium text-gray-700 mb-1">Warranty</label>
                    <input type="text" id="prod_warranty" name="prod_warranty" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label for="prod_expiration" class="block text-sm font-medium text-gray-700 mb-1">Expiration</label>
                    <input type="date" id="prod_expiration" name="prod_expiration" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>
            <div>
                <label for="prod_desc" class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                <textarea id="prod_desc" name="prod_desc" rows="3" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500" placeholder="Optional product description..."></textarea>
            </div>
            <div class="flex justify-end gap-3 mt-4">
                <button type="button" id="cancelProductBtn" class="px-4 py-2 text-gray-600 border border-gray-300 rounded-lg hover:bg-gray-50 transition-colors">Cancel</button>
                <button type="submit" id="saveProductBtn" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700 transition-colors">Save Product</button>
            </div>
        </form>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<dialog id="viewProductModal" class="modal">
    <div class="modal-box w-11/12 max-w-3xl">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-xl font-semibold">Product Details</h3>
            <form method="dialog"><button><i class='bx bx-sm bx-x'></i></button></form>
        </div>
        <div id="viewProductContent" class="space-y-2"></div>
    </div>
    <form method="dialog" class="modal-backdrop">
        <button>close</button>
    </form>
</dialog>

<script>
var API_BASE_URL = (function(){
    try {
        var isVendor = <?php echo \Auth::guard('vendor')->check() ? 'true' : 'false'; ?>;
        return isVendor ? '<?php echo url('/api/vendor/v1'); ?>' : '<?php echo url('/api/v1'); ?>';
    } catch(e) { return '<?php echo url('/api/v1'); ?>'; }
})();
var PSM_PRODUCTS_API = typeof PSM_PRODUCTS_API !== 'undefined' ? PSM_PRODUCTS_API : `${API_BASE_URL}/psm/product-management`;
var CSRF_TOKEN = typeof CSRF_TOKEN !== 'undefined' ? CSRF_TOKEN : document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
var JWT_TOKEN = typeof JWT_TOKEN !== 'undefined' ? JWT_TOKEN : localStorage.getItem('jwt');
var CURRENT_VENDOR_ID = <?php echo json_encode(optional(Auth::guard('vendor')->user())->vendorid ?? ''); ?>;

var vendorProducts = [];
var currentProductsPage = 1;
var productsPageSize = 10;

var vendorProductsElements = {
    tableBody: document.getElementById('vendorProductsTableBody'),
    pagerInfo: document.getElementById('vendorProductsPagerInfo'),
    pageDisplay: document.getElementById('vendorProductsPageDisplay'),
    pager: document.getElementById('vendorProductsPager'),
    addProductBtn: document.getElementById('addProductBtn'),
    productModal: document.getElementById('productModal'),
    productForm: document.getElementById('productForm'),
    productModalTitle: document.getElementById('productModalTitle'),
    viewProductModal: document.getElementById('viewProductModal'),
    viewProductContent: document.getElementById('viewProductContent'),
    cancelProductBtn: document.getElementById('cancelProductBtn')
};

function initVendorProducts() {
    if (!CURRENT_VENDOR_ID) {
        if (vendorProductsElements.tableBody) {
            vendorProductsElements.tableBody.innerHTML = `
                <tr>
                    <td colspan="15" class="px-6 py-8 text-center text-gray-500 whitespace-nowrap">
                        Unable to determine vendor account. Please relogin.
                    </td>
                </tr>
            `;
        }
        return;
    }
    if (vendorProductsElements.addProductBtn) {
        vendorProductsElements.addProductBtn.addEventListener('click', function () {
            openAddProductModal();
        });
    }
    if (vendorProductsElements.productForm) {
        vendorProductsElements.productForm.addEventListener('submit', handleProductSubmit);
    }
    if (vendorProductsElements.cancelProductBtn) {
        vendorProductsElements.cancelProductBtn.addEventListener('click', function () {
            closeProductModal();
        });
    }
    if (vendorProductsElements.tableBody) {
        vendorProductsElements.tableBody.addEventListener('click', handleProductsTableClick);
    }
    var pager = vendorProductsElements.pager;
    if (pager) {
        pager.addEventListener('click', function (ev) {
            var btn = ev.target.closest('button[data-action]');
            if (!btn) return;
            var act = btn.getAttribute('data-action');
            if (act === 'prev') {
                currentProductsPage = Math.max(1, currentProductsPage - 1);
                renderVendorProducts(vendorProducts);
            }
            if (act === 'next') {
                var total = vendorProducts.length || 0;
                var max = Math.max(1, Math.ceil(total / productsPageSize));
                currentProductsPage = Math.min(max, currentProductsPage + 1);
                renderVendorProducts(vendorProducts);
            }
        });
    }
    loadVendorProducts();
}

async function loadVendorProducts() {
    if (!CURRENT_VENDOR_ID) return;
    vendorProductsElements.tableBody.innerHTML = `
        <tr>
            <td colspan="15" class="px-6 py-8 text-center text-gray-500 whitespace-nowrap">
                <div class="flex justify-center items-center py-4">
                    <div class="loading loading-spinner mr-3"></div>
                    Loading products...
                </div>
            </td>
        </tr>
    `;
    try {
        var response = await fetch(`${PSM_PRODUCTS_API}/by-vendor/${CURRENT_VENDOR_ID}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? `Bearer ${JWT_TOKEN}` : ''
            },
            credentials: 'include'
        });
        if (!response.ok) {
            throw new Error('HTTP ' + response.status + ': ' + response.statusText);
        }
        var result = await response.json();
        vendorProducts = result.data || [];
        renderVendorProducts(vendorProducts);
    } catch (error) {
        console.error('Error loading products:', error);
        vendorProducts = [];
        renderVendorProducts(vendorProducts);
    }
}

function renderVendorProducts(list) {
    var total = list.length || 0;
    if (!total) {
        vendorProductsElements.tableBody.innerHTML = `
            <tr>
                <td colspan="15" class="px-6 py-8 text-center text-gray-500 whitespace-nowrap">
                    <i class='bx bx-cube-alt text-4xl text-gray-300 mb-3'></i>
                    <p class="text-lg">No products found</p>
                </td>
            </tr>
        `;
        updateVendorProductsPager(0, 1);
        return;
    }
    var totalPages = Math.max(1, Math.ceil(total / productsPageSize));
    if (currentProductsPage > totalPages) currentProductsPage = totalPages;
    if (currentProductsPage < 1) currentProductsPage = 1;
    var startIdx = (currentProductsPage - 1) * productsPageSize;
    var pageItems = list.slice(startIdx, startIdx + productsPageSize);
    var rows = pageItems.map(function (p) {
        var createdAt = p.created_at ? new Date(p.created_at).toLocaleString() : '';
        var updatedAt = p.updated_at ? new Date(p.updated_at).toLocaleString() : '';
        return `
            <tr>
                <td class="px-3 py-2 font-mono whitespace-nowrap">${p.id || ''}</td>
                <td class="px-3 py-2 whitespace-nowrap">${p.prod_id || ''}</td>
                <td class="px-3 py-2 whitespace-nowrap">${p.prod_vendor || ''}</td>
                <td class="px-3 py-2 whitespace-nowrap">${p.prod_name || ''}</td>
                <td class="px-3 py-2 whitespace-nowrap">${formatCurrency(p.prod_price)}</td>
                <td class="px-3 py-2 whitespace-nowrap">${p.prod_stock ?? ''}</td>
                <td class="px-3 py-2 whitespace-nowrap capitalize">${p.prod_type || ''}</td>
                <td class="px-3 py-2 whitespace-nowrap">${p.prod_warranty || ''}</td>
                <td class="px-3 py-2 whitespace-nowrap">${p.prod_expiration || ''}</td>
                <td class="px-3 py-2 whitespace-nowrap" title="${p.prod_desc || ''}">${p.prod_desc || ''}</td>
                <td class="px-3 py-2 whitespace-nowrap">${p.prod_module_from || ''}</td>
                <td class="px-3 py-2 whitespace-nowrap">${p.prod_submodule_from || ''}</td>
                <td class="px-3 py-2 whitespace-nowrap">${createdAt}</td>
                <td class="px-3 py-2 whitespace-nowrap">${updatedAt}</td>
                <td class="px-3 py-2 whitespace-nowrap">
                    <div class="flex items-center space-x-2">
                        <button class="text-gray-700 hover:text-gray-900 transition-colors p-2 rounded-lg hover:bg-gray-50" data-action="view" data-id="${p.id}" title="View">
                            <i class='bx bx-show-alt text-xl'></i>
                        </button>
                        <button class="text-blue-600 hover:text-blue-900 transition-colors p-2 rounded-lg hover:bg-blue-50" data-action="edit" data-id="${p.id}" title="Edit">
                            <i class='bx bx-edit-alt text-xl'></i>
                        </button>
                        <button class="text-red-600 hover:text-red-900 transition-colors p-2 rounded-lg hover:bg-red-50" data-action="delete" data-id="${p.id}" title="Delete">
                            <i class='bx bx-trash text-xl'></i>
                        </button>
                    </div>
                </td>
            </tr>
        `;
    }).join('');
    vendorProductsElements.tableBody.innerHTML = rows;
    updateVendorProductsPager(total, totalPages);
}

function updateVendorProductsPager(total, totalPages) {
    var start = total === 0 ? 0 : ((currentProductsPage - 1) * productsPageSize) + 1;
    var end = Math.min(currentProductsPage * productsPageSize, total);
    if (vendorProductsElements.pagerInfo) {
        vendorProductsElements.pagerInfo.textContent = 'Showing ' + start + '-' + end + ' of ' + total;
    }
    if (vendorProductsElements.pageDisplay) {
        vendorProductsElements.pageDisplay.textContent = currentProductsPage + ' / ' + totalPages;
    }
    var prevBtn = document.getElementById('vendorProductsPrevBtn');
    var nextBtn = document.getElementById('vendorProductsNextBtn');
    if (prevBtn) prevBtn.disabled = currentProductsPage <= 1;
    if (nextBtn) nextBtn.disabled = currentProductsPage >= totalPages;
}

function handleProductsTableClick(ev) {
    var btn = ev.target.closest('button[data-action]');
    if (!btn) return;
    var id = btn.getAttribute('data-id');
    var action = btn.getAttribute('data-action');
    if (!id) return;
    if (action === 'view') {
        viewProduct(id);
    } else if (action === 'edit') {
        editProduct(id);
    } else if (action === 'delete') {
        deleteProduct(id);
    }
}

function openAddProductModal() {
    if (!vendorProductsElements.productModalTitle) return;
    vendorProductsElements.productModalTitle.textContent = 'Add Product';
    var form = vendorProductsElements.productForm;
    if (form) {
        form.reset();
        document.getElementById('productId').value = '';
        document.getElementById('prod_vendor').value = CURRENT_VENDOR_ID || '';
    }
    if (vendorProductsElements.productModal && typeof vendorProductsElements.productModal.showModal === 'function') {
        vendorProductsElements.productModal.showModal();
    }
}

function openEditProductModal(product) {
    if (!vendorProductsElements.productModalTitle) return;
    vendorProductsElements.productModalTitle.textContent = 'Edit Product';
    document.getElementById('productId').value = product.id;
    document.getElementById('prod_vendor').value = product.prod_vendor || CURRENT_VENDOR_ID || '';
    document.getElementById('prod_name').value = product.prod_name || '';
    document.getElementById('prod_price').value = product.prod_price || '';
    document.getElementById('prod_stock').value = product.prod_stock || 0;
    document.getElementById('prod_type').value = product.prod_type || '';
    document.getElementById('prod_warranty').value = product.prod_warranty || '';
    document.getElementById('prod_expiration').value = product.prod_expiration ? String(product.prod_expiration).substring(0, 10) : '';
    document.getElementById('prod_desc').value = product.prod_desc || '';
    if (vendorProductsElements.productModal && typeof vendorProductsElements.productModal.showModal === 'function') {
        vendorProductsElements.productModal.showModal();
    }
}

function closeProductModal() {
    if (vendorProductsElements.productModal && typeof vendorProductsElements.productModal.close === 'function') {
        vendorProductsElements.productModal.close();
    }
}

async function handleProductSubmit(e) {
    e.preventDefault();
    if (!CURRENT_VENDOR_ID) return;
    var form = vendorProductsElements.productForm;
    if (!form) return;
    var formData = new FormData(form);
    var data = {};
    formData.forEach(function (value, key) {
        data[key] = value;
    });
    data.prod_vendor = CURRENT_VENDOR_ID;
    data.prod_price = parseFloat(data.prod_price);
    data.prod_stock = parseInt(data.prod_stock);
    var id = document.getElementById('productId').value;
    var url = id ? PSM_PRODUCTS_API + '/' + id : PSM_PRODUCTS_API;
    var method = id ? 'PUT' : 'POST';
    try {
        var response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? 'Bearer ' + JWT_TOKEN : ''
            },
            credentials: 'include',
            body: JSON.stringify(data)
        });
        if (!response.ok) {
            throw new Error('HTTP ' + response.status + ': ' + response.statusText);
        }
        var result = await response.json();
        if (result.success) {
            if (typeof showNotification === 'function') {
                showNotification(result.message || 'Product saved successfully', 'success');
            }
            closeProductModal();
            await loadVendorProducts();
        } else {
            throw new Error(result.message || 'Failed to save product');
        }
    } catch (error) {
        if (typeof showNotification === 'function') {
            showNotification('Error saving product: ' + error.message, 'error');
        } else {
            console.error('Error saving product:', error);
        }
    }
}

function viewProduct(id) {
    var p = vendorProducts.find(function (x) { return String(x.id) === String(id); });
    if (!p) return;
    var html = `
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div><span class="text-sm text-gray-500">Product ID</span><p class="font-semibold">${p.prod_id || ''}</p></div>
            <div><span class="text-sm text-gray-500">Name</span><p class="font-semibold">${p.prod_name || ''}</p></div>
            <div><span class="text-sm text-gray-500">Price</span><p class="font-semibold">${formatCurrency(p.prod_price)}</p></div>
            <div><span class="text-sm text-gray-500">Stock</span><p class="font-semibold">${p.prod_stock ?? ''}</p></div>
            <div><span class="text-sm text-gray-500">Type</span><p class="font-semibold capitalize">${p.prod_type || ''}</p></div>
            <div><span class="text-sm text-gray-500">Warranty</span><p class="font-semibold">${p.prod_warranty || ''}</p></div>
            <div><span class="text-sm text-gray-500">Expiration</span><p class="font-semibold">${p.prod_expiration || ''}</p></div>
            <div><span class="text-sm text-gray-500">Module From</span><p class="font-semibold">${p.prod_module_from || ''}</p></div>
            <div><span class="text-sm text-gray-500">Submodule From</span><p class="font-semibold">${p.prod_submodule_from || ''}</p></div>
        </div>
        <div class="mt-4">
            <span class="text-sm text-gray-500">Description</span>
            <p class="font-semibold">${p.prod_desc || ''}</p>
        </div>
    `;
    if (vendorProductsElements.viewProductContent) {
        vendorProductsElements.viewProductContent.innerHTML = html;
    }
    if (vendorProductsElements.viewProductModal && typeof vendorProductsElements.viewProductModal.showModal === 'function') {
        vendorProductsElements.viewProductModal.showModal();
    }
}

async function editProduct(id) {
    var p = vendorProducts.find(function (x) { return String(x.id) === String(id); });
    if (!p) {
        await loadVendorProducts();
        p = vendorProducts.find(function (x) { return String(x.id) === String(id); });
    }
    if (!p) return;
    openEditProductModal(p);
}

async function deleteProduct(id) {
    var confirmed = false;
    if (typeof Swal !== 'undefined' && typeof Swal.fire === 'function') {
        var result = await Swal.fire({
            title: 'Delete Product?',
            text: 'Are you sure you want to delete this product?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Delete',
            cancelButtonText: 'Cancel',
            reverseButtons: true
        });
        confirmed = result.isConfirmed;
    } else {
        confirmed = window.confirm('Are you sure you want to delete this product?');
    }
    if (!confirmed) return;
    try {
        var response = await fetch(PSM_PRODUCTS_API + '/' + id, {
            method: 'DELETE',
            headers: {
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Authorization': JWT_TOKEN ? 'Bearer ' + JWT_TOKEN : ''
            },
            credentials: 'include'
        });
        if (!response.ok) {
            throw new Error('HTTP ' + response.status + ': ' + response.statusText);
        }
        var result = await response.json();
        if (result.success) {
            if (typeof showNotification === 'function') {
                showNotification(result.message || 'Product deleted successfully', 'success');
            }
            await loadVendorProducts();
        } else {
            throw new Error(result.message || 'Failed to delete product');
        }
    } catch (error) {
        if (typeof showNotification === 'function') {
            showNotification('Error deleting product: ' + error.message, 'error');
        } else {
            console.error('Error deleting product:', error);
        }
    }
}

function formatCurrency(value) {
    var num = Number(value || 0);
    return '₱' + num.toLocaleString('en-PH', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
}

if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initVendorProducts);
} else {
    initVendorProducts();
}
</script>
