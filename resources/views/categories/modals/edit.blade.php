<div id="categoryModal" class="hidden fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50">
    <div
        class="bg-gradient-to-br from-[#1a2332] to-[#0f1621] rounded-2xl shadow-2xl w-full max-w-lg p-8 border border-[#2d3b4e] relative overflow-hidden transform transition-all">
        <!-- Animated Background -->
        <div class="absolute inset-0 bg-gradient-to-br from-blue-600/5 via-transparent to-blue-600/5"></div>

        <!-- Header -->
        <div class="relative z-10 flex items-center gap-3 mb-6">
            <div
                class="w-12 h-12 rounded-xl bg-gradient-to-br from-green-500 to-green-700 flex items-center justify-center shadow-lg shadow-green-500/30">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                    </path>
                </svg>
            </div>
            <h2 id="categoryModalTitle" class="text-2xl font-bold text-white">
                Edit Category
            </h2>
        </div>

        <form id="editCategoryForm" method="POST" class="relative z-10">
            @csrf
            @method('PUT')

            <div class="space-y-5">
                {{-- Category Name --}}
                <div class="group">
                    <label class="block text-sm font-semibold text-gray-300 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                            </path>
                        </svg>
                        Category Name
                    </label>
                    <input type="text" id="categoryName" name="category_name"
                        class="w-full px-4 py-3 bg-[#0f1621]/80 backdrop-blur-sm border border-[#2d3b4e] rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 hover:border-[#3d4b5e]"
                        placeholder="Enter category name" required>
                </div>

                {{-- Category Description --}}
                <div class="group">
                    <label class="block text-sm font-semibold text-gray-300 mb-2 flex items-center gap-2">
                        <svg class="w-4 h-4 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                        Description
                    </label>
                    <textarea id="categoryDescription" name="description" rows="4"
                        class="w-full px-4 py-3 bg-[#0f1621]/80 backdrop-blur-sm border border-[#2d3b4e] rounded-xl text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-green-500 focus:border-transparent transition-all duration-200 hover:border-[#3d4b5e] resize-none"
                        placeholder="Enter category description"></textarea>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end gap-3 mt-8 pt-6 border-t border-[#2d3b4e]/50">
                <button type="button" onclick="closeCategoryModal()"
                    class="px-6 py-2.5 bg-[#0f1621] border border-[#2d3b4e] text-gray-300 rounded-xl hover:bg-[#1a2332] hover:border-[#3d4b5e] transition-all duration-200 font-medium">
                    Cancel
                </button>

                <button id="saveCategoryButton" type="submit"
                    class="group relative bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white font-semibold px-8 py-2.5 rounded-xl transition-all duration-200 shadow-lg shadow-green-600/30 hover:shadow-xl hover:shadow-green-600/40 hover:-translate-y-0.5 active:translate-y-0 flex items-center gap-2">
                    <span id="categoryButtonText">Update Category</span>
                    <svg id="categorySpinner" class="hidden w-5 h-5 animate-spin text-white"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4l3-3-3-3v4a8 8 0 00-8 8z">
                        </path>
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>
