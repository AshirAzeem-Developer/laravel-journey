<script>
    // ================================
    // CATEGORY MANAGEMENT SCRIPT
    // ================================

    // --- Add/Edit Modal Variables ---
    const categoryModal = document.getElementById('categoryModal');
    const categoryTitle = document.getElementById('categoryModalTitle');
    const categoryNameInput = document.getElementById('categoryName');
    const categoryDescriptionInput = document.getElementById('categoryDescription');
    const categorySaveButton = document.getElementById('saveCategoryButton');
    const categorySpinner = document.getElementById('categorySpinner');
    const categoryButtonText = document.getElementById('categoryButtonText');
    const addCategoryModal = document.getElementById('createCategoryModal');

    // FIX: Added variable for the View Modal container
    const viewCategoryModal = document.getElementById('viewCategoryModal');

    const editForm = document.getElementById('editCategoryForm');
    let currentCategoryId = null;

    // --- Delete Modal Variables ---
    const deleteCategoryModal = document.getElementById('deleteCategoryModal');
    const confirmDeleteCategoryBtn = document.getElementById('confirmDeleteCategoryBtn');
    const deleteCategoryText = document.getElementById('deleteCategoryText');
    const deleteCategoryIcon = document.getElementById('deleteCategoryIcon');
    const deleteCategorySpinner = document.getElementById('deleteCategorySpinner');
    const cancelDeleteCategoryBtn = document.getElementById('cancelDeleteCategoryBtn');

    // FIX: Renamed variable to avoid conflict with the function name
    const closeDeleteCategoryModalBtn = document.getElementById('closeDeleteCategoryModal');

    let deleteCategoryUrl = null;

    // --- Toast Notification ---
    function showToast(message, type = 'success') {
        // Create toast container if it doesn't exist
        let container = document.getElementById('toast-container');
        if (!container) {
            container = document.createElement('div');
            container.id = 'toast-container';
            container.className = 'fixed top-4 right-4 z-50 space-y-2';
            document.body.appendChild(container);
        }

        const toast = document.createElement('div');
        const bgColor = type === 'success' ? 'bg-green-500' : type === 'warning' ? 'bg-yellow-500' : 'bg-red-500';
        toast.className =
            `${bgColor} text-white px-6 py-3 rounded-xl shadow-2xl animate-fadeIn flex items-center gap-3 min-w-[300px] border border-white/20`;

        const icon = type === 'success' ?
            '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>' :
            type === 'warning' ?
            '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>' :
            '<svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>';

        toast.innerHTML = `${icon}<span class="font-medium">${message}</span>`;
        container.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateX(100%)';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }

    // ======================
    // ADD CATEGORY MODAL
    // ======================
    function openAddCategoryModal() {
        addCategoryModal.classList.remove('hidden');
        addCategoryModal.classList.add('flex');
    }

    function closeAddCategoryModal() {
        addCategoryModal.classList.add('hidden');
        addCategoryModal.classList.remove('flex');
    }

    function closeCategoryModal() {
        categoryModal.classList.add('hidden');
        categoryModal.classList.remove('flex');
    }

    // ======================
    // EDIT CATEGORY MODAL
    // ======================
    async function fetchAndOpenEditCategoryModal(category) {
        try {
            console.log("Category Selected Data -> ", category);
            currentCategoryId = category.id;
            // Use the correct URL structure for update action (assuming 'admin_dashboard/categories' prefix)
            editForm.action = `{{ url('admin_dashboard/Categories') }}/${category.id}`;

            categoryNameInput.value = category.category_name;
            categoryDescriptionInput.value = category.description || '';

            // --- IMAGE MANAGEMENT LOGIC ---
            const imageContainer = document.getElementById('image-management-container');
            const imageUrl = category.category_image ? `/storage/${category.category_image}` : null;

            imageContainer.innerHTML = `
                <label class="block text-sm font-semibold text-gray-300 mb-2 flex items-center gap-2">
                    <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-2-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Category Image
                </label>

                ${imageUrl ? `
                    <div class="mb-3 p-3 border border-[#2d3b4e] rounded-xl flex items-center justify-between bg-[#0f1621]">
                        <img src="${imageUrl}" class="w-16 h-16 object-cover rounded-lg mr-4 border border-green-500/20" alt="Current Image">
                        <span class="text-sm text-gray-400 truncate">${category.category_image.split('/').pop()}</span>
                        <label class="flex items-center space-x-2 ml-4 cursor-pointer">
                            <input type="checkbox" name="remove_image" value="true" class="form-checkbox h-5 w-5 text-red-500 bg-gray-800 border-gray-700 rounded focus:ring-red-500">
                            <span class="text-xs text-red-400 font-semibold">Remove</span>
                        </label>
                    </div>
                    <p class="text-xs text-gray-500 mb-2">Upload a new image to replace the current one, or check 'Remove'.</p>
                ` : '<p class="text-xs text-gray-500 mb-2">No current image. Upload a new one.</p>'}

                <input type="file" name="category_image"
                    class="w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-full file:border-0
                    file:text-sm file:font-semibold
                    file:bg-green-500/10 file:text-green-400
                    hover:file:bg-green-500/20"/>
            `;
            // --- END IMAGE MANAGEMENT LOGIC ---

            categoryTitle.textContent = 'Edit Category';
            categoryModal.classList.remove('hidden');
            categoryModal.classList.add('flex');
        } catch (error) {
            console.error(error);
            showToast('Failed to fetch category data.', 'error');
        }
    }

    // ======================
    // VIEW CATEGORY MODAL
    // ======================
    async function fetchAndOpenViewCategoryModal(category) {
        try {
            console.log("Category Details->", category);

            // --- IMAGE DISPLAY LOGIC ---
            const imageUrl = category.category_image ? `/storage/${category.category_image}` : null;
            const imageContainer = document.getElementById('viewCategoryImageContainer');

            imageContainer.innerHTML = `
                <div class="p-4 bg-[#0f1621]/60 backdrop-blur-sm rounded-xl mb-4">
                    <div class="flex items-center gap-2 mb-2">
                        <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-2-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                        <label class="text-xs text-gray-400 uppercase tracking-wide font-semibold">Category Image</label>
                    </div>
                    ${imageUrl
                        ? `<img src="${imageUrl}" class="w-full h-[35vh] object-cover rounded-xl shadow-inner border border-blue-500/30" alt="${category.category_name} Image">`
                        : `<div class="h-40 w-full flex items-center justify-center bg-[#0f1621]/80 rounded-xl border border-[#2d3b4e] text-gray-500">No Image Attached</div>`
                    }
                </div>
            `;
            // --- END IMAGE DISPLAY LOGIC ---

            // Fill remaining modal fields
            document.getElementById('viewCategoryName').textContent = category.category_name;
            document.getElementById('viewCategoryDescription').textContent = category.description ||
                'No description available';

            document.getElementById('viewCategoryProducts').textContent = category.products_count || 0;

            // Format created date if available
            if (category.created_at) {
                // Use a comprehensive format since it's a DATETIME column
                const date = new Date(category.created_at);
                document.getElementById('viewCategoryCreated').textContent = date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });
            } else {
                document.getElementById('viewCategoryCreated').textContent = 'N/A';
            }

            // Show modal
            viewCategoryModal.classList.remove('hidden');
            viewCategoryModal.classList.add('flex');
        } catch (err) {
            console.error(err);
            showToast('Unable to load category details.', 'error');
        }
    }

    function closeViewCategoryModal() {
        viewCategoryModal.classList.add('hidden');
        viewCategoryModal.classList.remove('flex');
    }

    // ======================
    // DELETE CATEGORY MODAL
    // ======================
    function openDeleteCategoryModal(url) {
        deleteCategoryUrl = url;
        deleteCategoryModal.classList.remove('hidden');
        deleteCategoryModal.classList.add('flex');
    }

    function closeDeleteCategoryModalHandler() {
        deleteCategoryModal.classList.add('hidden');
        deleteCategoryModal.classList.remove('flex');
        deleteCategoryText.textContent = 'Delete Category';
        deleteCategoryIcon.classList.remove('hidden');
        confirmDeleteCategoryBtn.disabled = false;
        deleteCategorySpinner?.classList.add('hidden');
    }

    // Event Listeners for Delete Modal
    cancelDeleteCategoryBtn.addEventListener('click', closeDeleteCategoryModalHandler);
    // FIX: Using the renamed variable
    closeDeleteCategoryModalBtn.addEventListener('click', closeDeleteCategoryModalHandler);

    confirmDeleteCategoryBtn.addEventListener('click', async () => {
        if (!deleteCategoryUrl) return;

        confirmDeleteCategoryBtn.disabled = true;
        deleteCategoryText.textContent = 'Deleting...';
        deleteCategoryIcon.classList.add('hidden');
        deleteCategorySpinner?.classList.remove('hidden');

        console.log("URL for Delete: ", deleteCategoryUrl);

        try {
            const res = await fetch(deleteCategoryUrl, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            const data = await res.json().catch(() => ({}));

            if (res.ok && data.success) {
                showToast(data.message || 'Category deleted successfully!', 'success');
                closeDeleteCategoryModalHandler();
                setTimeout(() => location.reload(), 1000);
            } else if (res.status === 409) {
                // Handles the specific conflict error from the controller (category has products)
                showToast(data.message || 'Category has associated products and cannot be deleted.',
                    'error');
                closeDeleteCategoryModalHandler();
            } else {
                showToast(data.message || 'Failed to delete category.', 'error');
            }
        } catch (err) {
            console.error(err);
            showToast('Network error occurred.', 'error');
        } finally {
            deleteCategoryText.textContent = 'Delete Category';
            deleteCategoryIcon.classList.remove('hidden');
            confirmDeleteCategoryBtn.disabled = false;
            deleteCategorySpinner?.classList.add('hidden');
        }
    });

    // ======================
    // MODAL ANIMATIONS & UTILS
    // ======================

    // Close modals on ESC key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            // Check if modals exist before attempting to close
            if (categoryModal && !categoryModal.classList.contains('hidden')) closeCategoryModal();
            if (addCategoryModal && !addCategoryModal.classList.contains('hidden')) closeAddCategoryModal();
            if (viewCategoryModal && !viewCategoryModal.classList.contains('hidden')) closeViewCategoryModal();
            if (deleteCategoryModal && !deleteCategoryModal.classList.contains('hidden'))
                closeDeleteCategoryModalHandler();
        }
    });

    // Close modals when clicking outside
    // FIX: Use the actual variable names defined at the top
    [categoryModal, addCategoryModal, viewCategoryModal, deleteCategoryModal].forEach(
        modal => {
            if (modal) {
                modal.addEventListener('click', (e) => {
                    if (e.target === modal) {
                        modal.classList.add('hidden');
                        modal.classList.remove('flex');
                    }
                });
            }
        });
</script>

<style>
    @keyframes modalFadeIn {
        from {
            opacity: 0;
            transform: scale(0.95) translateY(-20px);
        }

        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    @keyframes modalFadeOut {
        from {
            opacity: 1;
            transform: scale(1) translateY(0);
        }

        to {
            opacity: 0;
            transform: scale(0.95) translateY(-20px);
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
            transform: translateX(100%);
        }

        to {
            opacity: 1;
            transform: translateX(0);
        }
    }

    .animate-fadeIn {
        animation: fadeIn 0.3s ease-out;
        transition: opacity 0.3s ease-out, transform 0.3s ease-out;
    }

    /* Smooth transitions for modal backdrop */
    #categoryModal,
    #createCategoryModal,
    #viewCategoryModal,
    #deleteCategoryModal {
        transition: opacity 0.2s ease-out;
    }

    #categoryModal.hidden,
    #createCategoryModal.hidden,
    #viewCategoryModal.hidden,
    #deleteCategoryModal.hidden {
        opacity: 0;
        pointer-events: none;
    }
</style>
