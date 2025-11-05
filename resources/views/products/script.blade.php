<script>
    // ================================
    // PRODUCT MANAGEMENT SCRIPT
    // ================================

    // --- Add/Edit Modal Variables ---
    const productModal = document.getElementById('productModal');
    const productTitle = document.getElementById('productModalTitle');
    const productNameInput = document.getElementById('productName');
    const productDescriptionInput = document.getElementById('productDescription');
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
    const editForm = document.getElementById('editProductForm');

    // --- Image Preview ---
    productFileInput?.addEventListener('change', (event) => {
        const files = event.target.files;
        const previewContainer = document.getElementById('productPreviewContainer'); // New container ID

        // Clear existing previews
        if (previewContainer) {
            previewContainer.innerHTML = '';
        }

        if (files.length > 0) {
            for (let i = 0; i < files.length; i++) {
                const file = files[i];
                if (file.type.startsWith('image/')) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        img.className =
                            'w-24 h-24 rounded-lg object-cover border border-gray-300 dark:border-gray-700 mr-2 mb-2';

                        // Append the new image element to the container
                        if (previewContainer) {
                            previewContainer.appendChild(img);
                        }
                    };
                    reader.readAsDataURL(file);
                }
            }
        }
    });





    // --- Helper function to render multiple attachments ---
    function renderProductAttachments(attachments, containerId, isView = false) {
        const container = document.getElementById(containerId);
        if (!container) return;

        // Clear existing previews
        container.innerHTML = '';

        const defaultImgPath = "{{ asset('asset/images/default-product.jpg') }}";
        const imagePaths = JSON.parse(attachments || '[]');

        if (imagePaths.length > 0) {
            imagePaths.forEach(path => {
                const img = document.createElement('img');
                img.src = `/storage/${path}`;
                // Apply consistent styling
                img.className =
                    'w-24 h-24 rounded-lg object-cover border border-gray-300 dark:border-gray-700 mr-2 mb-2';
                img.alt = 'Product Image';
                container.appendChild(img);
            });
        }

        // Always show a default placeholder if no images exist (especially useful for edit mode)
        if (imagePaths.length === 0 || !isView) {
            const defaultImg = document.createElement('img');
            defaultImg.src = defaultImgPath;
            // Use a slightly different class for the primary/default image in edit mode
            defaultImg.className =
                'w-24 h-24 rounded-lg object-cover border border-gray-300 dark:border-gray-700 mr-2 mb-2';
            defaultImg.alt = 'Default Image';

            // Only add the default if the container is empty or we are in a context where
            // we need to see a placeholder (like the original single preview spot, which we are replacing)
            if (imagePaths.length === 0) {
                container.appendChild(defaultImg);
            }
        }
    }


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
        productDescriptionInput.value = '';
        productPriceInput.value = '';
        productCategorySelect.value = '';
        isHotCheckbox.checked = false;
        isActiveCheckbox.checked = true;
        productFileInput.value = '';
        productPreview.src = '{{ asset('asset/images/default-product.jpg') }}';
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
    async function fetchAndOpenEditProductModal(product) {
        try {
            console.log("Product Selected Data -> ", product);
            currentProductId = product.id;
            editForm.action = "{{ url('admin_dashboard/Products') }}/" + currentProductId;
            productNameInput.value = product.product_name;
            productDescriptionInput.value = product.description || '';
            productPriceInput.value = product.price;
            productCategorySelect.value = product.category_id;
            isHotCheckbox.checked = product.isHot == 1;
            isActiveCheckbox.checked = product.isActive == 1;

            renderProductAttachments(product.attachments, 'productPreviewContainer', false);

            productTitle.textContent = 'Edit Product';
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
    // productSaveButton.addEventListener('click', async () => {
    //     productSaveButton.disabled = true;
    //     productButtonText.textContent = '';
    //     productSpinner?.classList.remove('hidden');

    //     const name = productNameInput.value.trim();
    //     const description = productDescriptionInput.value.trim();
    //     const price = productPriceInput.value.trim();
    //     const category = productCategorySelect.value;
    //     const isHot = isHotCheckbox.checked ? 1 : 0;
    //     const isActive = isActiveCheckbox.checked ? 1 : 0;
    //     const files = productFileInput.files;

    //     if (!name || !price || !category) {
    //         showToast('Please fill all required fields.', 'warning');
    //         resetButtonState();
    //         return;
    //     }

    //     const formData = new FormData();
    //     formData.append('product_name', name);
    //     formData.append('description', description);
    //     formData.append('price', price);
    //     formData.append('category_id', category);
    //     formData.append('isHot', isHot);
    //     formData.append('isActive', isActive);
    //     if (files && files.length > 0) {
    //         for (let i = 0; i < files.length; i++) {
    //             formData.append('attachments[]', files[
    //                 i]); // Use 'attachments[]' to match the form input name
    //         }
    //     }
    //     let url = '';
    //     let method = 'POST';
    //     const action = productSaveButton.getAttribute('data-action');

    //     if (action === 'add') {
    //         url = "{{ route('products.store') }}";
    //     } else if (action === 'edit' && currentProductId) {
    //         url = `{{ url('/products') }}/${currentProductId}`;
    //         formData.append('_method', 'PATCH');
    //     } else {
    //         showToast('Invalid operation.', 'error');
    //         resetButtonState();
    //         return;
    //     }

    //     try {
    //         const res = await fetch(url, {
    //             method,
    //             headers: {
    //                 'X-CSRF-TOKEN': '{{ csrf_token() }}',
    //                 'Accept': 'application/json'
    //             },
    //             body: formData
    //         });

    //         const data = await res.json().catch(() => ({}));

    //         console.log("HTTP status:", res.status, "Response:", data);

    //         if (res.status === 422 && data.errors) {
    //             let msg = `<strong>${data.title}</strong><ul>`;
    //             data.errors.forEach(e => (msg += `<li>${e}</li>`));
    //             msg += '</ul>';
    //             showToast(msg, 'error'); // use your toast/modal
    //             resetButtonState();
    //             return;
    //         }

    //         if (!res.ok || !data.success) {
    //             showToast(data.message || 'Something went wrong.', 'error');
    //             resetButtonState();
    //             return;
    //         }

    //         showToast(data.message || 'Product saved successfully!', 'success');
    //         closeProductModal();
    //         setTimeout(() => location.reload(), 1000);
    //     } catch (err) {
    //         console.error(err);
    //         showToast('Network or server error.', 'error');
    //     } finally {
    //         resetButtonState();
    //     }
    // });

    // ======================
    // VIEW PRODUCT MODAL
    // ======================
    async function fetchAndOpenViewProductModal(product) {
        try {
            // const res = await fetch(`{{ url('/products') }}/${id}`);

            // const data = await res.json();



            console.log("Product Details->", product)
            // Fill modal fields
            document.getElementById('viewProductName').innerHTML = product.product_name;
            document.getElementById('viewProductDescription').innerHTML = product.description || 'N/A';
            document.getElementById('viewProductPrice').innerHTML = `$${product.price}`;
            document.getElementById('viewProductCategory').innerHTML = product.category_name ??
                'Uncategorized';
            document.getElementById('viewProductHot').innerHTML = product.isHot ? 'Yes' : 'No';
            document.getElementById('viewProductActive').innerHTML = product.isActive ? 'Yes' : 'No';
            renderProductAttachments(product.attachments, 'viewProductImageContainer', true);

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
