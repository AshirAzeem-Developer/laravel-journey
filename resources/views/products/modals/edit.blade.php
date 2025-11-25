<div id="productModal" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
    <div id="modalContent" class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg w-full max-w-lg p-6">
        <h2 id="productModalTitle" class="text-xl font-semibold text-gray-800 dark:text-white mb-4">
            Edit Product
        </h2>

        <form id="editProductForm" enctype="multipart/form-data" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-4">
                {{-- Product Name --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Product Name
                    </label>
                    <input type="text" id="productName" name="product_name"
                        class="mt-1 w-full border-gray-300 dark:border-gray-700 rounded-lg dark:bg-gray-900 dark:text-gray-100"
                        placeholder="Enter product name" required>
                </div>
                {{-- Product description --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Product Description
                    </label>
                    <textarea id="productDescription" name="description" rows="3"
                        class="mt-1 w-full border-gray-300 dark:border-gray-700 rounded-lg dark:bg-gray-900 dark:text-gray-100"
                        placeholder="Enter product description max 300 Characters Allowed" maxlength="300"></textarea>
                </div>

                {{-- Price --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Price
                    </label>
                    <input type="number" id="productPrice" name="price" step="0.01"
                        class="mt-1 w-full border-gray-300 dark:border-gray-700 rounded-lg dark:bg-gray-900 dark:text-gray-100"
                        placeholder="Enter product price" required>
                </div>

                {{-- Category --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Category
                    </label>
                    <select id="productCategory" name="category_id"
                        class="mt-1 w-full border-gray-300 dark:border-gray-700 rounded-lg dark:bg-gray-900 dark:text-gray-100"
                        required>
                        <option value="">Select Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Status Checkboxes --}}
                <div class="flex items-center gap-6">
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" id="productHot" name="isHot"
                            class="rounded text-blue-600 dark:bg-gray-900">
                        <span class="text-gray-700 dark:text-gray-300">Hot Product</span>
                    </label>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" id="productActive" name="isActive"
                            class="rounded text-green-600 dark:bg-gray-900" checked>
                        <span class="text-gray-700 dark:text-gray-300">Active</span>
                    </label>
                </div>

                {{-- File Upload --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                        Product Image
                    </label>
                    <input type="file" id="productFile" name="attachments[]" multiple
                        class="mt-1 w-full text-sm text-gray-600 dark:text-gray-300
                        file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0
                        file:text-sm file:font-semibold
                        file:bg-blue-600 file:text-white hover:file:bg-blue-700">
                    <div class="mt-3 flex flex-wrap" id="productPreviewContainer">

                        <img id="productPreview" src="{{ asset('asset/images/default-product.jpg') }}"
                            class="w-24 h-24 rounded-lg object-cover border border-gray-300 dark:border-gray-700 mr-2 mb-2"
                            alt="Preview">
                    </div>
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex justify-end gap-2 mt-6">
                <button type="button" onclick="closeProductModal()"
                    class="px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-700 dark:text-white rounded-lg hover:bg-gray-400">
                    Cancel
                </button>

                <button id="saveProductButton1" type="submit"
                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 flex items-center gap-2"
                    data-action="edit">
                    <span id="productButtonText">Update Product</span>
                    <svg id="productSpinner" class="hidden w-5 h-5 animate-spin text-white"
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
