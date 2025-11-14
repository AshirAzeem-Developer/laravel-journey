<div id="viewCategoryModal"
    class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
    <div
        class="bg-gradient-to-br from-[#1a2332] to-[#0f1621] rounded-2xl shadow-2xl w-full max-w-lg p-8 border border-[#2d3b4e] relative overflow-hidden transform transition-all">
        <!-- Animated Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-blue-600/5 via-transparent to-blue-600/5"></div>

        <!-- Header -->
        <div class="relative z-10 flex items-center gap-3 mb-6">
            <div
                class="w-12 h-12 rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center shadow-lg shadow-blue-500/30">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                    </path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-white">Category Details</h2>
        </div>

        <div id="viewCategoryContent" class="relative z-10 space-y-4">
            <!-- Category Name -->
            <div
                class="p-4 bg-[#0f1621]/60 backdrop-blur-sm rounded-xl border border-[#2d3b4e] hover:border-[#3d4b5e] transition-all">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                        </path>
                    </svg>
                    <label class="text-xs text-gray-400 uppercase tracking-wide font-semibold">Category Name</label>
                </div>
                <p id="viewCategoryName" class="text-white font-semibold text-lg">—</p>
            </div>

            <!-- Description -->
            <div
                class="p-4 bg-[#0f1621]/60 backdrop-blur-sm rounded-xl border border-[#2d3b4e] hover:border-[#3d4b5e] transition-all">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <label class="text-xs text-gray-400 uppercase tracking-wide font-semibold">Description</label>
                </div>
                <p id="viewCategoryDescription" class="text-gray-300 leading-relaxed">—</p>
            </div>

            <!-- Products Count -->
            <div
                class="p-4 bg-[#0f1621]/60 backdrop-blur-sm rounded-xl border border-[#2d3b4e] hover:border-[#3d4b5e] transition-all">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                    </svg>
                    <label class="text-xs text-gray-400 uppercase tracking-wide font-semibold">Total Products</label>
                </div>
                <div class="flex items-center gap-3">
                    <span id="viewCategoryProducts" class="text-white font-bold text-2xl">—</span>
                    <span
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-500/20 text-blue-400 border border-blue-500/30">
                        Products in category
                    </span>
                </div>
            </div>

            <!-- Created Date (Optional) -->
            <div
                class="p-4 bg-[#0f1621]/60 backdrop-blur-sm rounded-xl border border-[#2d3b4e] hover:border-[#3d4b5e] transition-all">
                <div class="flex items-center gap-2 mb-2">
                    <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                        </path>
                    </svg>
                    <label class="text-xs text-gray-400 uppercase tracking-wide font-semibold">Created Date</label>
                </div>
                <p id="viewCategoryCreated" class="text-gray-300">—</p>
            </div>
        </div>

        <!-- Close Button -->
        <div class="flex justify-end mt-8 pt-6 border-t border-[#2d3b4e]/50 relative z-10">
            <button type="button" onclick="closeViewCategoryModal()"
                class="group relative bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold px-8 py-2.5 rounded-xl transition-all duration-200 shadow-lg shadow-blue-600/30 hover:shadow-xl hover:shadow-blue-600/40 hover:-translate-y-0.5 active:translate-y-0 flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                    </path>
                </svg>
                Close
            </button>
        </div>
    </div>
</div>
