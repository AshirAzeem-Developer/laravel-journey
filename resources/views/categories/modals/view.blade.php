<div id="viewCategoryModal"
    class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 ">
    <div
        class="bg-gradient-to-br from-[#1a2332] to-[#0f1621] rounded-2xl shadow-2xl w-full max-w-lg p-8 border border-[#2d3b4e] relative overflow-hidden transform transition-all max-h-[90vh] overflow-y-auto">
        <!-- Animated Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-blue-600/5 via-transparent to-blue-600/5"></div>

        <!-- Header -->
        <div class="relative z-10 flex items-center justify-between mb-6">

            <div class="flex items-center gap-3">
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

            <button type="button" onclick="closeViewCategoryModal()"
                class="group relative bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-semibold p-3 rounded-md transition-all duration-200 shadow-lg shadow-blue-600/30 hover:shadow-xl hover:shadow-blue-600/40 hover:-translate-y-0.5 active:translate-y-0 flex items-center gap-2">
                <i class="fa fa-close"></i>

            </button>
        </div>

        <div id="viewCategoryContent" class="relative z-10 space-y-">

            {{-- CRITICAL FIX: Container for Image Injection --}}
            <div id="viewCategoryImageContainer">
                {{-- Content is dynamically injected here by fetchAndOpenViewCategoryModal --}}
            </div>

            <div class="grid grid-cols-2 items-stretch justify-center gap-3">

                <!-- Category Name -->
                <div
                    class="p-3 bg-[#0f1621]/60 backdrop-blur-sm rounded-xl border border-[#2d3b4e] hover:border-[#3d4b5e] transition-all">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-tag text-blue-400"></i>
                        <label class="text-xs text-gray-400 uppercase tracking-wide font-semibold">Category Name</label>
                    </div>
                    <p id="viewCategoryName" class="text-white font-semibold text-base">—</p>
                </div>

                <!-- Description -->
                <div
                    class="p-3 bg-[#0f1621]/60 backdrop-blur-sm rounded-xl border border-[#2d3b4e] hover:border-[#3d4b5e] transition-all">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-align-left text-blue-400"></i>
                        <label class="text-xs text-gray-400 uppercase tracking-wide font-semibold">Description</label>
                    </div>
                    <p id="viewCategoryDescription" class="text-gray-300 leading-relaxed text-base">—</p>
                </div>

                <!-- Products Count -->
                <div
                    class="p-3 bg-[#0f1621]/60 backdrop-blur-sm rounded-xl border border-[#2d3b4e] hover:border-[#3d4b5e] transition-all">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-boxes text-blue-400"></i>
                        <label class="text-xs text-gray-400 uppercase tracking-wide font-semibold">Total
                            Products</label>
                    </div>
                    <div class="flex items-center gap-3">
                        <span id="viewCategoryProducts" class="text-white font-bold text-xl">—</span>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-blue-500/20 text-blue-400 border border-blue-500/30">
                            Products in category
                        </span>
                    </div>
                </div>

                <!-- Created Date (Optional) -->
                <div
                    class="p-3 bg-[#0f1621]/60 backdrop-blur-sm rounded-xl border border-[#2d3b4e] hover:border-[#3d4b5e] transition-all">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-calendar-alt text-blue-400"></i>
                        <label class="text-xs text-gray-400 uppercase tracking-wide font-semibold">Created Date</label>
                    </div>
                    <p id="viewCategoryCreated" class="text-gray-300 text-xs">—</p>
                </div>
            </div>

        </div>
    </div>
</div>
