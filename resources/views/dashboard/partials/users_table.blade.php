<x-slot name="title">
    {{ __('System Users') }}
</x-slot>

<x-slot name="desc">
    {{ __('A list of all registered users in the system.') }}
</x-slot>

<div
    class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-slate-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 p-6 md:p-10">

    <div class="max-w-7xl mx-auto animate-fadeIn">
        <div class="mb-8 flex flex-col md:flex-row items-start md:items-center justify-end gap-4">
            <button type="button" onclick="openAddUserModal()"
                class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl shadow-md transition-all duration-200 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add New User
            </button>
        </div>

        {{-- Users Table --}}
        <div
            class="overflow-hidden bg-white/70 dark:bg-gray-800/60 backdrop-blur-lg rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gradient-to-r from-indigo-500 to-indigo-600 dark:from-indigo-600 dark:to-indigo-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase">ID</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase">Profile</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase">Name</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase">Email</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase">Last Updated</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach ($contentData['users'] as $user)
                        <tr
                            class="hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-all duration-200 cursor-pointer">
                            <td class="px-6 py-4 text-gray-800 dark:text-gray-200 font-medium">{{ $user->id }}</td>
                            <td class="px-6 py-4">
                                <img src="{{ $user->file_path ? asset('storage/' . $user->file_path) : asset('images/default-avatar.png') }}"
                                    class="w-10 h-10 rounded-full object-cover border border-gray-300 dark:border-gray-700"
                                    alt="Profile">
                            </td>
                            <td class="px-6 py-4 text-gray-800 dark:text-gray-200">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                                {{ $user->updated_at->diffForHumans() }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <button type="button" onclick="fetchAndOpenEditModal('{{ $user->id }}')"
                                        class="text-indigo-600 hover:text-indigo-800 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536M4 20h4l12-12-4-4L4 16v4z" />
                                        </svg>
                                    </button>
                                    <form method="POST" action="{{ route('users.destroy', $user->id) }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" onclick="return confirm('Are you sure?')"
                                            class="text-rose-500 hover:text-rose-700 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862A2 2 0 015 19.142L4.133 7H19z" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if (count($contentData['users']) === 0)
                <div class="text-center py-10 text-gray-500 dark:text-gray-400">
                    <p>No users found.</p>
                </div>
            @endif
        </div>
    </div>

    {{-- Add/Edit Modal --}}
    <div id="addUserModal"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-all duration-300 ease-in-out">
        <div class="bg-white dark:bg-gray-900 w-full max-w-lg rounded-2xl shadow-2xl p-6 relative">

            <!-- Close Button -->
            <button type="button" onclick="closeAddUserModal()"
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Header -->
            <div class="px-6 py-6  text-center border-b border-gray-200 dark:border-gray-700">
                <h2 id="modalTitle" class="text-2xl font-bold text-gray-800 dark:text-gray-100 tracking-tight">Add New
                    User</h2>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage user details easily</p>
            </div>

            <div class="p-3 space-y-6">

                <div class="flex flex-col items-center space-y-3">
                    <div class="relative">
                        <img id="previewImage" src="{{ asset('images/default-avatar.png') }}"
                            class="w-28 h-28 rounded-full object-cover border-4 border-indigo-100 dark:border-gray-700 shadow-md transition-all duration-300 hover:scale-105"
                            alt="Profile Preview">

                        <label for="userFile"
                            class="absolute bottom-0 right-0 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full p-2 shadow-lg cursor-pointer transition-all">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4v16m8-8H4" />
                            </svg>
                        </label>
                    </div>
                    <input type="file" id="userFile" accept="image/*" class="hidden">
                    <p class="text-xs text-gray-500 dark:text-gray-400">Upload JPG, PNG below 2MB</p>
                </div>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Full
                            Name</label>
                        <input type="text" id="userName" required
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Email
                            Address</label>
                        <input type="email" id="userEmail" required
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
                    </div>

                    <div id="passwordFieldContainer">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Password</label>
                        <input type="password" id="userPassword"
                            class="w-full rounded-lg border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-800 dark:text-gray-200 px-3 py-2 focus:ring-2 focus:ring-indigo-500 focus:outline-none transition">
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <button type="button" onclick="closeAddUserModal()"
                            class="px-4 py-2.5 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                            Cancel
                        </button>
                        <button id="saveUserButton" type="button" data-action="add"
                            class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg flex items-center gap-2 font-medium shadow-md transition-all duration-300">
                            <span id="buttonText">Save User</span>
                            <svg id="spinner" class="hidden w-5 h-5 animate-spin text-white"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="toast-container" class="fixed top-5 right-5 z-[9999] space-y-3"></div>

    <script>
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

        fileInput.addEventListener('change', () => {
            const file = fileInput.files[0];
            if (file) previewImage.src = URL.createObjectURL(file);
        });

        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            toast.className =
                `${type === 'success' ? 'bg-green-500' : 'bg-rose-500'} text-white px-4 py-2 rounded-lg shadow-lg animate-fadeIn`;
            toast.textContent = message;
            container.appendChild(toast);
            setTimeout(() => toast.remove(), 3000);
        }

        function openAddUserModal() {
            nameInput.value = '';
            emailInput.value = '';
            passwordInput.value = '';
            fileInput.value = '';
            previewImage.src = '{{ asset('images/default-avatar.png') }}';
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

                // ✅ Use JS string properly for Blade variable
                const defaultAvatar = "{{ asset('images/default-avatar.png') }}";
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


        button.addEventListener('click', async () => {
            button.disabled = true;
            const spinner = document.getElementById('spinner');
            spinner?.classList.remove('hidden');

            const name = nameInput.value.trim();
            const email = emailInput.value.trim();
            const password = passwordInput.value.trim();
            const fileInput = document.getElementById('userFile');
            const action = button.getAttribute('data-action');

            if (!name || !email) {
                showToast('Please fill all required fields.', 'warning');
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
                formData.append('_method', 'PATCH');
            } else {
                showToast('Invalid operation.', 'error');
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

                // Optional: reload after short delay
                setTimeout(() => location.reload(), 1000);

            } catch (err) {
                console.error('Submit Error:', err);
                showToast('A network or server error occurred.', 'error');
            } finally {
                button.disabled = false;
                spinner?.classList.add('hidden');
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
</div>
