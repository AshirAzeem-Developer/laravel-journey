{{-- Toast Container --}}
<div id="toast-container" class="fixed top-5 right-5 z-[9999] space-y-3"></div>

{{-- Delete Confirmation Modal --}}
<div id="deleteConfirmModal"
    class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/60 backdrop-blur-sm transition-all">
    <div class="bg-white dark:bg-gray-900 rounded-2xl shadow-2xl w-full max-w-sm p-6 relative">
        <h3 class="text-xl font-semibold text-gray-800 dark:text-gray-100 mb-2 text-center">
            Confirm Deletion
        </h3>
        <p class="text-gray-600 dark:text-gray-400 text-center mb-6">
            Are you sure you want to delete this user? This action cannot be undone.
        </p>

        <div class="flex justify-center gap-4">
            <button id="cancelDeleteBtn"
                class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 transition">
                Cancel
            </button>
            <button id="confirmDeleteBtn"
                class="px-5 py-2.5 bg-rose-600 hover:bg-rose-700 text-white rounded-lg font-medium shadow-md transition-all duration-300 flex items-center gap-2">
                <span id="deleteButtonText">Delete</span>
                <svg id="deleteSpinner" class="hidden w-5 h-5 animate-spin text-white"
                    xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                        stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8H4z"></path>
                </svg>
            </button>
        </div>

        <button type="button" id="closeDeleteModal"
            class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>
