<script>
    // ================================
    // PRODUCT MANAGEMENT SCRIPT
    // ================================

    // --- Add/Edit Modal Variables ---
    const productModal = document.getElementById('productModal');
    const productTitle = document.getElementById('productModalTitle');
    const productNameInput = document.getElementById('productName');
    const productPriceInput = document.getElementById('productPrice');
    const productCategorySelect = document.getElementById('productCategory');
    const isHotCheckbox = document.getElementById('productHot');
    const isActiveCheckbox = document.getElementById('productActive');
    const productFileInput = document.getElementById('productFile');
    const productPreview = document.getElementById('productPreview');
    const productSaveButton = document.getElementById('saveProductButton');
    const productSpinner = document.getElementById('productSpinner');
    const productButtonText = document.getElementById('productButtonText');
    let currentProductId = null;

    // --- Delete Modal Variables ---
    const deleteProductModal = document.getElementById('deleteProductModal');
    const confirmDeleteProductBtn = document.getElementById('confirmDeleteProductBtn');
    const deleteProductText = document.getElementById('deleteProductText');
    const deleteProductSpinner = document.getElementById('deleteProductSpinner');
    const cancelDeleteProductBtn = document.getElementById('cancelDeleteProductBtn');
    const closeDeleteProductModal = document.getElementById('closeDeleteProductModal');
    let deleteProductUrl = null;

    // --- Image Preview ---
    productFileInput?.addEventListener('change', () => {
        const file = productFileInput.files[0];
        if (file) productPreview.src = URL.createObjectURL(file);
    });

    // --- Toast Notification ---
    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className =
            `${type === 'success' ? 'bg-green-500' : type === 'warning' ? 'bg-yellow-500' : 'bg-rose-500'}
             text-white px-4 py-2 rounded-lg shadow-lg animate-fadeIn mt-2`;
        toast.textContent = message;
        container.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    // ======================
    // ADD PRODUCT MODAL
    // ======================
    function openAddProductModal() {
        productTitle.textContent = 'Add New Product';
        productNameInput.value = '';
        productPriceInput.value = '';
        productCategorySelect.value = '';
        isHotCheckbox.checked = false;
        isActiveCheckbox.checked = true;
        productFileInput.value = '';
        productPreview.src = '{{ asset('asset/images/default-product.jpg') }}';
        productSaveButton.setAttribute('data-action', 'add');
        productModal.classList.remove('hidden');
        productModal.classList.add('flex');
    }

    function closeProductModal() {
        productModal.classList.add('hidden');
        productModal.classList.remove('flex');
    }

    // ======================
    // EDIT PRODUCT MODAL
    // ======================
    async function fetchAndOpenEditProductModal(id) {
        try {
            const res = await fetch(`{{ url('/products') }}/${id}`);
            const data = await res.json();
            if (!data.success) throw new Error('Fetch failed');

            currentProductId = data.product.id;
            productNameInput.value = data.product.product_name;
            productPriceInput.value = data.product.price;
            productCategorySelect.value = data.product.category_id;
            isHotCheckbox.checked = data.product.isHot == 1;
            isActiveCheckbox.checked = data.product.isActive == 1;

            const defaultImg = "{{ asset('asset/images/default-product.jpg') }}";
            productPreview.src = data.product.image_path ?
                `/storage/${data.product.image_path}` :
                defaultImg;

            productTitle.textContent = 'Edit Product';
            productSaveButton.setAttribute('data-action', 'edit');
            productModal.classList.remove('hidden');
            productModal.classList.add('flex');
        } catch (error) {
            console.error(error);
            showToast('Failed to fetch product data.', 'error');
        }
    }

    // ======================
    // ADD/EDIT SUBMISSION
    // ======================
    productSaveButton.addEventListener('click', async () => {
        productSaveButton.disabled = true;
        productButtonText.textContent = '';
        productSpinner?.classList.remove('hidden');

        const name = productNameInput.value.trim();
        const price = productPriceInput.value.trim();
        const category = productCategorySelect.value;
        const isHot = isHotCheckbox.checked ? 1 : 0;
        const isActive = isActiveCheckbox.checked ? 1 : 0;
        const file = productFileInput.files[0];

        if (!name || !price || !category) {
            showToast('Please fill all required fields.', 'warning');
            resetButtonState();
            return;
        }

        const formData = new FormData();
        formData.append('product_name', name);
        formData.append('price', price);
        formData.append('category_id', category);
        formData.append('isHot', isHot);
        formData.append('isActive', isActive);
        if (file) formData.append('file', file);

        let url = '';
        let method = 'POST';
        const action = productSaveButton.getAttribute('data-action');

        if (action === 'add') {
            url = "{{ route('products.store') }}";
        } else if (action === 'edit' && currentProductId) {
            url = `{{ url('/products') }}/${currentProductId}`;
            formData.append('_method', 'PATCH');
        } else {
            showToast('Invalid operation.', 'error');
            resetButtonState();
            return;
        }

        try {
            const res = await fetch(url, {
                method,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: formData
            });

            const data = await res.json().catch(() => ({}));

            console.log("Data ->", res);
            console.log("Data (JSON) ->", data);
            if (!res.ok || res.status !== 200) {
                if (data.errors) {
                    Object.values(data.errors).forEach(errs =>
                        errs.forEach(msg => showToast(msg, 'error'))
                    );
                } else {
                    showToast(data.message || 'Something went wrong.', 'error');
                }
                return;
            }

            showToast(data.message || 'Product saved successfully!', 'success');
            closeProductModal();
            setTimeout(() => location.reload(), 1000);
        } catch (err) {
            console.error(err);
            showToast('Network or server error.', 'error');
        } finally {
            resetButtonState();
        }
    });

    // ======================
    // VIEW PRODUCT MODAL
    // ======================
    async function fetchAndOpenViewProductModal(id) {
        try {
            const res = await fetch(`{{ url('/products') }}/${id}`);

            const data = await res.json();
            console.log("Product Details->", data)
            if (!data.success) throw new Error('Failed to fetch');

            // Fill modal fields
            document.getElementById('viewProductName').innerHTML = data.product.product_name;
            document.getElementById('viewProductDescription').innerHTML = data.product.description || 'N/A';
            document.getElementById('viewProductPrice').innerHTML = `$${data.product.price}`;
            document.getElementById('viewProductCategory').innerHTML = data.product.category_name ??
                'Uncategorized';
            document.getElementById('viewProductHot').innerHTML = data.product.isHot ? 'Yes' : 'No';
            document.getElementById('viewProductActive').innerHTML = data.product.isActive ? 'Yes' : 'No';
            document.getElementById('viewProductImage').src = data.product.image_path ?
                `/storage/${data.product.image_path}` :
                "{{ asset('asset/images/default-product.jpg') }}";

            // Show modal
            const modal = document.getElementById('viewProductModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        } catch (err) {
            console.error(err.message);
            showToast('Unable to load product details.', 'error');
        }
    }

    function closeViewProductModal() {
        const modal = document.getElementById('viewProductModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }



    function resetButtonState() {
        productButtonText.textContent =
            productSaveButton.getAttribute('data-action') === 'add' ?
            'Save Product' :
            'Update Product';
        productSaveButton.disabled = false;
        productSpinner?.classList.add('hidden');
    }

    // ======================
    // DELETE PRODUCT MODAL
    // ======================
    function openDeleteProductModal(url) {

        deleteProductUrl = url;
        deleteProductModal.classList.remove('hidden');
        deleteProductModal.classList.add('flex');
    }

    function closeDeleteProductModalHandler() {
        deleteProductModal.classList.add('hidden');
        deleteProductModal.classList.remove('flex');
        deleteProductText.textContent = 'Delete';
        confirmDeleteProductBtn.disabled = false;
        deleteProductSpinner?.classList.add('hidden');
    }

    cancelDeleteProductBtn.addEventListener('click', closeDeleteProductModalHandler);
    closeDeleteProductModal.addEventListener('click', closeDeleteProductModalHandler);

    confirmDeleteProductBtn.addEventListener('click', async () => {
        if (!deleteProductUrl) return;

        confirmDeleteProductBtn.disabled = true;
        deleteProductText.textContent = '';
        deleteProductSpinner?.classList.remove('hidden');

        console.log("URL for Delete : ", deleteProductUrl)
        try {
            const res = await fetch(deleteProductUrl, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            const data = await res.json().catch(() => ({}));

            if (res.ok && data.success) {
                showToast(data.message || 'Product deleted successfully!', 'success');
                closeDeleteProductModalHandler();
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.message || 'Failed to delete product.', 'error');
            }
        } catch (err) {
            console.error(err);
            showToast('Network error occurred.', 'error');
        } finally {
            deleteProductText.textContent = 'Delete';
            confirmDeleteProductBtn.disabled = false;
            deleteProductSpinner?.classList.add('hidden');
        }
    });
</script>

<style>
    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: scale(0.95);
        }

        to {
            opacity: 1;
            transform: scale(1);
        }
    }

    @keyframes modalFadeOut {
        from {
            opacity: 1;
            transform: scale(1);
        }

        to {
            opacity: 0;
            transform: scale(0.95);
        }
    }

    .modal-open #modalContent {
        animation: modalFadeIn 0.3s ease-out forwards;
    }

    .modal-close #modalContent {
        animation: modalFadeOut 0.2s ease-in forwards;
    }

    @keyframes fadeIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
    }
</style>
