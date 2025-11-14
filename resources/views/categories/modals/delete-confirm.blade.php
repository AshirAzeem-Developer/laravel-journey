<div id="deleteCategoryModal"
    class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
    <div
        class="bg-gradient-to-br from-[#1a2332] to-[#0f1621] rounded-2xl shadow-2xl w-full max-w-md p-8 border border-[#2d3b4e] relative overflow-hidden text-center transform transition-all">
        <!-- Animated Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-red-600/5 via-transparent to-orange-600/5"></div>

        <!-- Warning Icon -->
        <div class="relative z-10 flex justify-center mb-6">
            <div
                class="w-20 h-20 rounded-full bg-gradient-to-br from-red-500/20 to-red-700/20 border-2 border-red-500/50 flex items-center justify-center animate-pulse">
                <svg class="w-10 h-10 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z">
                    </path>
                </svg>
            </div>
        </div>

        <!-- Content -->
        <div class="relative z-10">
            <h2 class="text-2xl font-bold text-white mb-3">Confirm Deletion</h2>
            <p class="text-gray-300 mb-2 leading-relaxed">
                Are you sure you want to delete this category?
            </p>
            <p class="text-gray-400 text-sm mb-6">
                This action cannot be undone and may affect associated products.
            </p>

            <!-- Action Buttons -->
            <div class="flex justify-center gap-3">
                <!-- Cancel Button -->
                <button id="cancelDeleteCategoryBtn" type="button"
                    class="px-6 py-2.5 bg-[#0f1621] border border-[#2d3b4e] text-gray-300 rounded-xl hover:bg-[#1a2332] hover:border-[#3d4b5e] transition-all duration-200 font-medium flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                        </path>
                    </svg>
                    Cancel
                </button>

                <!-- Delete Button with Spinner -->
                <button id="confirmDeleteCategoryBtn" type="button"
                    class="group relative bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-semibold px-8 py-2.5 rounded-xl transition-all duration-200 shadow-lg shadow-red-600/30 hover:shadow-xl hover:shadow-red-600/40 hover:-translate-y-0.5 active:translate-y-0 flex items-center gap-2 disabled:opacity-75 disabled:cursor-not-allowed disabled:hover:translate-y-0">
                    <span id="deleteCategorySpinner" class="hidden">
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
                    <svg id="deleteCategoryIcon" class="w-5 h-5" fill="none" stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                        </path>
                    </svg>
                    <span id="deleteCategoryText">Delete Category</span>
                </button>
            </div>
        </div>

        <!-- Close Icon -->
        <button id="closeDeleteCategoryModal" type="button"
            class="absolute top-4 right-4 text-gray-400 hover:text-white transition-colors z-20 p-2 hover:bg-[#1a2332] rounded-lg">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
    </div>
</div>
