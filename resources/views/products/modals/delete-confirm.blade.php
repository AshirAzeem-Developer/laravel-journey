<div id="deleteProductModal"
    class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50 backdrop-blur-sm">
    <div id="modalContent"
        class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg w-full max-w-md p-6 text-center modal-open">
        <h2 class="text-lg font-semibold text-gray-800 dark:text-white mb-3">Confirm Deletion</h2>
        <p class="text-gray-600 dark:text-gray-300 mb-6">
            Are you sure you want to delete this product? <br>
            This action cannot be undone.
        </p>

        <div class="flex justify-center gap-3">
            <!-- Cancel Button -->
            <button id="cancelDeleteProductBtn" type="button"
                class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-white rounded-lg hover:bg-gray-400 transition">
                Cancel
            </button>

            <!-- Delete Button with Spinner -->
            <button id="confirmDeleteProductBtn" type="button"
                class="relative px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition flex items-center justify-center gap-2 disabled:opacity-75 disabled:cursor-not-allowed">
                <span id="deleteProductSpinner" class="hidden">
                    <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4">
                        </circle>
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 100 16v-4l-3 3 3 3v-4a8 8 0 01-8-8z">
                        </path>
                    </svg>
                </span>
                <span id="deleteProductText">Delete</span>
            </button>
        </div>

        <!-- Close Icon -->
        <button id="closeDeleteProductModal"
            class="absolute top-3 right-3 text-gray-500 dark:text-gray-300 hover:text-gray-700 dark:hover:text-white transition">
            ✕
        </button>
    </div>
</div>
