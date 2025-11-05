<x-app-layout>
    <x-slot name="title">
        {{ __('Product List') }}
    </x-slot>

    <x-slot name="desc">
        {{ __('Overview of all available products in the system.') }}
    </x-slot>

    <div class="py-10 px-6 bg-gray-50 dark:bg-transparent min-h-screen">
        <div class="max-w-7xl mx-auto">
            <div
                class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 rounded-2xl shadow-lg overflow-hidden transition-all duration-300 hover:shadow-xl">
                <div class="p-6">
                    <!-- Header -->
                    <div class="flex items-center justify-end mb-6">
                        {{-- <div>
                            <h1 class="text-2xl font-bold text-gray-900 dark:text-white">Product List</h1>
                            <p class="text-gray-500 dark:text-gray-400 text-sm mt-1">
                                Overview of all available products in the system.
                            </p>
                        </div> --}}
                        <button id="addProductButton" onclick="openAddProductModal()"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white text-sm font-medium rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 dark:focus:ring-indigo-400 transition-all duration-200">
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 mr-2" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4" />
                            </svg>
                            Add Product
                        </button>
                    </div>

                    <!-- Table -->
                    <div class="overflow-x-auto rounded-lg border border-gray-100 dark:border-gray-700">
                        <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                            <thead class="bg-gray-100 dark:bg-gray-700">
                                <tr>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        Product Name
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        Price
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        Category
                                    </th>
                                    <th
                                        class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                        HOT / ACTIVE
                                    </th>

                                    <th
                                        class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-100 dark:divide-gray-700">
                                @forelse ($products as $product)
                                    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                            {{ $product->product_name }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                            ${{ number_format($product->price, 2) }}
                                        </td>
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                                            {{-- {{ dd($product) }} --}}
                                            {{ $product->category->category_name ?? 'Uncategorized' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                                            @if ($product->isHot)
                                                <span
                                                    class="inline-block px-3 py-1 text-xs font-semibold text-white bg-red-500 rounded-full shadow-sm">HOT</span>
                                            @endif
                                            @if ($product->isActive)
                                                <span
                                                    class="inline-block px-3 py-1 text-xs font-semibold text-white bg-green-500 rounded-full shadow-sm ml-2">ACTIVE</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-3">
                                            <button onclick="fetchAndOpenViewProductModal({{ $product }})"
                                                class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-200 transition">
                                                View
                                            </button>
                                            <!-- Edit -->
                                            <button onclick="fetchAndOpenEditProductModal({{ $product }})"
                                                class="text-green-600 hover:text-green-900 dark:text-green-400 dark:hover:text-green-200 transition">
                                                Edit
                                            </button>
                                            <!-- Delete -->
                                            <button
                                                onclick="openDeleteProductModal(`{{ route('products.destroy', $product->id) }}`)"
                                                class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-200 transition">
                                                Delete
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3"
                                            class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-400">
                                            No products available.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $products->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('products.modals.create')
    @include('products.modals.edit')
    @include('products.modals.view')
    @include('products.modals.delete-confirm')
    @include('products.script')
</x-app-layout>
<script>
    function toggleModal(id) {
        document.getElementById(id).classList.toggle('hidden');
    }

    function openEditModal(product) {
        const modal = document.getElementById('editProductModal');
        const body = document.getElementById('editProductBody');
        const form = document.getElementById('editProductForm');

        form.action = `/products/${product.id}`;
        body.innerHTML = `
            <div>
                <label class='block text-sm font-medium'>Name</label>
                <input type='text' name='name' value='${product.name}'
                    class='w-full mt-1 rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white'>
            </div>
            <div>
                <label class='block text-sm font-medium'>Price</label>
                <input type='number' name='price' value='${product.price}'
                    class='w-full mt-1 rounded-lg border-gray-300 dark:bg-gray-700 dark:border-gray-600 dark:text-white'>
            </div>
        `;
        toggleModal('editProductModal');
    }

    function openDeleteModal(productId) {
        const form = document.getElementById('deleteProductForm');
        form.action = `/products/${productId}`;
        toggleModal('deleteProductModal');
    }
</script>
