<div id="viewModal" class="hidden fixed inset-0 flex items-center justify-center z-50 p-4 transition-all">
    <div
        class="bg-white dark:bg-gray-800 rounded-2xl shadow-2xl w-full max-w-2xl transform scale-95 opacity-0 transition-all duration-300">
        <div class="p-6 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center">
            <h3 class="text-xl font-bold text-gray-900 dark:text-white">Order Details</h3>
            <button class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300" onclick="closeModal('viewModal')">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <div class="p-6 space-y-4">
            <div id="viewContent" class="space-y-3 text-gray-700 dark:text-gray-200">
            </div>
        </div>

        <div class="p-4 border-t border-gray-200 dark:border-gray-700 flex justify-end">
            <button
                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-semibold flex items-center gap-2">
                <i class="fas fa-print"></i> Print Invoice
            </button>
        </div>
    </div>
</div>
