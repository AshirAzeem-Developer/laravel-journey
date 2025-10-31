<script>
    // --- Add/Edit Modal Variables ---
    const modal = document.getElementById('addUserModal');
    const nameInput = document.getElementById('userName');
    const emailInput = document.getElementById('userEmail');
    const passwordInput = document.getElementById('userPassword');
    const fileInput = document.getElementById('userFile');
    const previewImage = document.getElementById('previewImage');
    const button = document.getElementById('saveUserButton');
    const spinner = document.getElementById('spinner');
    const buttonText = document.getElementById('buttonText');
    const modalTitle = document.getElementById('modalTitle');
    let currentUserId = null;

    // --- Delete Modal Variables ---
    const deleteModal = document.getElementById('deleteConfirmModal');
    const confirmDeleteBtn = document.getElementById('confirmDeleteBtn');
    const deleteButtonText = document.getElementById('deleteButtonText');
    const deleteSpinner = document.getElementById('deleteSpinner');
    const cancelDeleteBtn = document.getElementById('cancelDeleteBtn');
    const closeDeleteModal = document.getElementById('closeDeleteModal');
    let deleteUrl = null;
    // --------------------------------------------------------

    // --- Event Listeners for file input (Image Preview) ---
    fileInput.addEventListener('change', () => {
        const file = fileInput.files[0];
        if (file) previewImage.src = URL.createObjectURL(file);
    });

    // --- Toast Notification Function ---
    function showToast(message, type = 'success') {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className =
            `${type === 'success' ? 'bg-green-500' : 'bg-rose-500'} text-white px-4 py-2 rounded-lg shadow-lg animate-fadeIn`;
        toast.textContent = message;
        container.appendChild(toast);
        setTimeout(() => toast.remove(), 3000);
    }

    // --- Add/Edit Modal Functions ---
    function openAddUserModal() {
        nameInput.value = '';
        emailInput.value = '';
        passwordInput.value = '';
        fileInput.value = '';
        previewImage.src = '{{ asset('asset/images/default-avatar.png') }}';
        button.setAttribute('data-action', 'add');
        modalTitle.textContent = 'Add New User';
        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeAddUserModal() {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }

    async function fetchAndOpenEditModal(id) {
        try {
            const res = await fetch(`{{ url('dashboard/users') }}/${id}`);
            const data = await res.json();
            if (!data.success) throw new Error();

            currentUserId = data.user.id;
            nameInput.value = data.user.name;
            emailInput.value = data.user.email;

            const defaultAvatar = "{{ asset('asset/images/default-avatar.png') }}";
            previewImage.src = data.user.file_path ?
                `/storage/${data.user.file_path}` :
                defaultAvatar;

            button.setAttribute('data-action', 'edit');
            modalTitle.textContent = 'Edit User';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
        } catch (error) {
            console.error(error);
            showToast('Failed to fetch user data.', 'error');
        }
    }

    // --- Add/Edit Form Submission Logic ---
    button.addEventListener('click', async () => {
        // Show spinner and disable button
        button.disabled = true;
        buttonText.textContent = ''; // Hide text
        spinner?.classList.remove('hidden');

        const name = nameInput.value.trim();
        const email = emailInput.value.trim();
        const password = passwordInput.value.trim();
        const fileInput = document.getElementById('userFile');
        const action = button.getAttribute('data-action');

        if (!name || !email) {
            showToast('Please fill all required fields.', 'warning');
            // Reset state
            buttonText.textContent = 'Save User';
            button.disabled = false;
            spinner?.classList.add('hidden');
            return;
        }

        const formData = new FormData();
        formData.append('name', name);
        formData.append('email', email);
        if (password) formData.append('password', password);
        if (fileInput && fileInput.files[0]) formData.append('file', fileInput.files[0]);

        let url = '',
            method = 'POST';
        if (action === 'add') {
            url = "{{ route('users.store') }}";
        } else if (action === 'edit' && currentUserId) {
            url = `{{ url('dashboard/users') }}/${currentUserId}`;
            // FIX: Change spoofed method from 'POST' to 'PATCH' for updates
            formData.append('_method', 'PATCH');
        } else {
            showToast('Invalid operation.', 'error');
            // Reset state
            buttonText.textContent = 'Save User';
            button.disabled = false;
            spinner?.classList.add('hidden');
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

            let data = {};
            try {
                data = await res.json();
            } catch {
                console.warn('Response not JSON');
            }

            if (!res.ok || !data.success) {
                if (data.errors) {
                    Object.values(data.errors).forEach(errorArr => {
                        errorArr.forEach(errMsg => showToast(errMsg, 'error'));
                    });
                } else {
                    showToast(data.message || 'Something went wrong.', 'error');
                }
                return;
            }

            showToast(data.message || 'User saved successfully!', 'success');
            closeAddUserModal();

            setTimeout(() => location.reload(), 1000);

        } catch (err) {
            console.error('Submit Error:', err);
            showToast('A network or server error occurred.', 'error');
        } finally {
            // Reset state
            buttonText.textContent = action === 'add' ? 'Save User' : 'Update User';
            button.disabled = false;
            spinner?.classList.add('hidden');
        }
    });


    // --- Delete Modal Functions and Logic ---

    // Open modal
    function openDeleteConfirmModal(url) {
        deleteUrl = url;
        deleteModal.classList.remove('hidden');
        deleteModal.classList.add('flex');
    }

    // Close modal
    function closeDeleteConfirmModal() {
        deleteModal.classList.add('hidden');
        deleteModal.classList.remove('flex');
        // Ensure button state is reset on close
        deleteButtonText.textContent = 'Delete';
        confirmDeleteBtn.disabled = false;
        deleteSpinner?.classList.add('hidden');
    }

    // Cancel or close actions
    cancelDeleteBtn.addEventListener('click', closeDeleteConfirmModal);
    closeDeleteModal.addEventListener('click', closeDeleteConfirmModal);

    // Confirm delete action
    confirmDeleteBtn.addEventListener('click', async () => {
        if (!deleteUrl) return;

        // *** START SPINNER AND DISABLE BUTTON ***
        confirmDeleteBtn.disabled = true;
        deleteButtonText.textContent = ''; // Hide text
        deleteSpinner?.classList.remove('hidden');
        // ****************************************

        try {
            const res = await fetch(deleteUrl, {
                method: 'POST', // Use POST for cross-browser safety/CSRF compliance, overriding method
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'X-HTTP-Method-Override': 'DELETE'
                }
            });

            let data = {};
            try {
                data = await res.json();
            } catch {
                console.warn('Response not JSON');
            }

            if (res.ok && data.success) {
                showToast(data.message || 'User deleted successfully!', 'success');
                closeDeleteConfirmModal();
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast(data.message || 'Failed to delete user.', 'error');
            }
        } catch (err) {
            console.error(err);
            showToast('A network error occurred.', 'error');
        } finally {
            // *** STOP SPINNER AND ENABLE BUTTON ***
            deleteButtonText.textContent = 'Delete';
            confirmDeleteBtn.disabled = false;
            deleteSpinner?.classList.add('hidden');
            // ***************************************
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
</style>
