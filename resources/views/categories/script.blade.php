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
    const editForm = document.getElementById('editCategoryForm');
    let currentCategoryId = null;

    // --- Delete Modal Variables ---
    const deleteCategoryModal = document.getElementById('deleteCategoryModal');
    const confirmDeleteCategoryBtn = document.getElementById('confirmDeleteCategoryBtn');
    const deleteCategoryText = document.getElementById('deleteCategoryText');
    const deleteCategoryIcon = document.getElementById('deleteCategoryIcon');
    const deleteCategorySpinner = document.getElementById('deleteCategorySpinner');
    const cancelDeleteCategoryBtn = document.getElementById('cancelDeleteCategoryBtn');
    const closeDeleteCategoryModal = document.getElementById('closeDeleteCategoryModal');
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
            editForm.action = "{{ url('admin_dashboard/Categories') }}/" + currentCategoryId;

            categoryNameInput.value = category.category_name;
            categoryDescriptionInput.value = category.description || '';

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

            // Fill modal fields
            document.getElementById('viewCategoryName').textContent = category.category_name;
            document.getElementById('viewCategoryDescription').textContent = category.description ||
                'No description available';
            document.getElementById('viewCategoryProducts').textContent = category.products_count || 0;

            // Format created date if available
            if (category.created_at) {
                const date = new Date(category.created_at);
                document.getElementById('viewCategoryCreated').textContent = date.toLocaleDateString('en-US', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
            } else {
                document.getElementById('viewCategoryCreated').textContent = 'N/A';
            }

            // Show modal
            const modal = document.getElementById('viewCategoryModal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        } catch (err) {
            console.error(err);
            showToast('Unable to load category details.', 'error');
        }
    }

    function closeViewCategoryModal() {
        const modal = document.getElementById('viewCategoryModal');
        modal.classList.add('hidden');
        modal.classList.remove('flex');
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
    closeDeleteCategoryModal.addEventListener('click', closeDeleteCategoryModalHandler);

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
    // MODAL ANIMATIONS
    // ======================
    function toggleModal(id) {
        const modal = document.getElementById(id);
        if (modal) {
            modal.classList.toggle('hidden');
            modal.classList.toggle('flex');
        }
    }

    // Close modals on ESC key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape') {
            if (!categoryModal.classList.contains('hidden')) closeCategoryModal();
            if (!addCategoryModal.classList.contains('hidden')) closeAddCategoryModal();
            if (!viewCategoryModal.classList.contains('hidden')) closeViewCategoryModal();
            if (!deleteCategoryModal.classList.contains('hidden')) closeDeleteCategoryModalHandler();
        }
    });

    // Close modals when clicking outside
    [categoryModal, addCategoryModal, document.getElementById('viewCategoryModal'), deleteCategoryModal].forEach(
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
