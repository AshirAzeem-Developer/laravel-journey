<div id="viewProductModal" class="hidden fixed inset-0 bg-black/50 items-center justify-center z-50">
    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-lg w-full max-w-lg p-6">
        <h2 class="text-xl font-semibold text-gray-800 dark:text-white mb-4">Product Details</h2>

        <div id="viewProductContent" class="space-y-3 text-gray-700 dark:text-gray-300">
            <p><strong>Name:</strong> <span id="viewProductName">—</span></p>
            <p><strong>Description:</strong> <span id="viewProductDescription" class=" break-words overflow-auto">—</span>
            </p>
            <p><strong>Price:</strong> <span id="viewProductPrice">—</span></p>
            <p><strong>Category:</strong> <span id="viewProductCategory">—</span></p>

            <p><strong>Hot Product:</strong>
                <span id="viewProductHot" class="font-semibold">—</span>
            </p>

            <p><strong>Active Status:</strong>
                <span id="viewProductActive" class="font-semibold">—</span>
            </p>

            <div class="mt-4">
                <strong>Product Image:</strong>
                {{-- <img id="viewProductImage" src="{{ asset('asset/images/default-product.jpg') }}" alt="Product Image"
                    class="mt-2 rounded-lg w-40 h-40 object-cover border border-gray-300 dark:border-gray-700"> --}}
                <div class="flex flex-wrap" id="viewProductImageContainer">
                </div>
            </div>

        </div>

        <div class="flex justify-end mt-6">
            <button type="button" onclick="closeViewProductModal()"
                class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none">
                Close
            </button>
        </div>
    </div>
</div>
