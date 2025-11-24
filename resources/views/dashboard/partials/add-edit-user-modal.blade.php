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
        <div class="px-6 py-6  text-center border-b border-gray-200 dark:border-gray-700">
            <h2 id="modalTitle" class="text-2xl font-bold text-gray-800 dark:text-gray-100 tracking-tight">Add New
                User</h2>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Manage user details easily</p>
        </div>

        <div class="p-3 space-y-6">

            <div class="flex flex-col items-center space-y-3">
                <div class="relative">
                    <img id="previewImage" src="{{ asset('asset/images/default-avatar.png') }}"
                        class="w-28 h-28 rounded-full object-cover border-4 border-indigo-100 dark:border-gray-700 shadow-md transition-all duration-300 hover:scale-105"
                        alt="Profile Preview">

                    <label for="userFile"
                        class="absolute bottom-0 right-0 bg-indigo-600 hover:bg-indigo-700 text-white rounded-full p-2 shadow-lg cursor-pointer transition-all">
                        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
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
                    <input type="text" id="userName" minlength="3" maxlength="30" required
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
                    <input type="password" id="userPassword" maxlength="20"
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
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
