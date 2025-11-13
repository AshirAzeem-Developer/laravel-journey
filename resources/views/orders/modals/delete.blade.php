<div id="deleteModal" class="hidden fixed inset-0 flex items-center justify-center z-50 p-4 transition-all">
    <div
        class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-md transform scale-95 opacity-0 transition-all duration-300">
        <form id="deleteForm" method="POST">
            @csrf
            @method('DELETE')
            <div class="p-6 text-center">
                <div
                    class="w-14 h-14 mx-auto bg-red-100 dark:bg-red-900/30 rounded-full flex items-center justify-center mb-4">
                    <i class="fas fa-exclamation-triangle text-red-600 dark:text-red-400 text-2xl"></i>
                </div>
                <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">Delete Order?</h3>
                <p id="deleteMessage" class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                    Are you sure you want to delete this order? This action cannot be undone.
                </p>

                <div class="flex justify-center gap-3">
                    <button type="button"
                        class="px-4 py-2 rounded-lg border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition"
                        onclick="closeModal('deleteModal')">
                        Cancel
                    </button>
                    <button type="submit"
                        class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-semibold">
                        Delete
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
