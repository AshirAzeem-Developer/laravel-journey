    {{-- <x-app-layout>
        <x-slot name="title">Revenue By Category and Year Report</x-slot>
        <x-slot name="desc">
            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Historical category performance analysis</p>
        </x-slot>

        <div class="space-y-6">

            <div class="flex justify-end items-center">
                <div class="flex items-center gap-3">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Category:</label>
                    <select id="categoryFilter"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"
                        onchange="window.location.href='{{ route('reports.revenueByCategoryYear') }}?category_id='+this.value">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ $category->id == $selectedCategoryId ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>


            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Revenue</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                                ${{ number_format($revenueData->sum('total_revenue'), 2) }}
                            </p>
                        </div>
                        <div
                            class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                            <span
                                class="material-symbols-rounded text-green-600 dark:text-green-400">account_balance_wallet</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Items Sold</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                                {{ number_format($revenueData->sum('total_quantity')) }}
                            </p>
                        </div>
                        <div
                            class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                            <span class="material-symbols-rounded text-blue-600 dark:text-blue-400">inventory_2</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Orders</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                                {{ number_format($revenueData->sum('total_orders')) }}
                            </p>
                        </div>
                        <div
                            class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center">
                            <span
                                class="material-symbols-rounded text-purple-600 dark:text-purple-400">shopping_bag</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Years Tracked</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                                {{ $revenueData->unique('year')->count() }}
                            </p>
                        </div>
                        <div
                            class="w-12 h-12 bg-orange-100 dark:bg-orange-900 rounded-full flex items-center justify-center">
                            <span class="material-symbols-rounded text-orange-600 dark:text-orange-400">history</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                        @if ($selectedCategoryId)
                            {{ $categories->firstWhere('id', $selectedCategoryId)->category_name }} Performance Over
                            Time
                        @else
                            All Categories Performance Over Time
                        @endif
                    </h2>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Year
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Category
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Revenue
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Items Sold
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Orders
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                    Avg Order Value
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($revenueData as $data)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span
                                                class="material-symbols-rounded text-indigo-600 dark:text-indigo-400 mr-2">calendar_today</span>
                                            <span
                                                class="text-sm font-medium text-gray-900 dark:text-white">{{ $data->year }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200">
                                            {{ $data->category_name }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <span class="text-sm font-semibold text-green-600 dark:text-green-400">
                                            ${{ number_format($data->total_revenue, 2) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <span class="text-sm text-gray-900 dark:text-white">
                                            {{ number_format($data->total_quantity) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">
                                            {{ number_format($data->total_orders) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <span class="text-sm text-gray-600 dark:text-gray-400">
                                            ${{ number_format($data->total_revenue / $data->total_orders, 2) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <span
                                                class="material-symbols-rounded text-gray-400 text-5xl mb-2">inbox</span>
                                            <p class="text-gray-500 dark:text-gray-400">
                                                No data available
                                                @if ($selectedCategoryId)
                                                    for this category
                                                @endif
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>


            @if ($selectedCategoryId && $revenueData->count() > 1)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Year-over-Year Growth</h3>
                    <div class="space-y-3">
                        @foreach ($revenueData->sortBy('year') as $index => $data)
                            @php
                                $previousData = $revenueData
                                    ->sortBy('year')
                                    ->values()
                                    ->get($index - 1);
                                if ($previousData) {
                                    $growth =
                                        (($data->total_revenue - $previousData->total_revenue) /
                                            $previousData->total_revenue) *
                                        100;
                                }
                            @endphp
                            @if (isset($growth))
                                <div
                                    class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                    <span class="text-sm font-medium text-gray-900 dark:text-white">
                                        {{ $previousData->year }} → {{ $data->year }}
                                    </span>
                                    <span
                                        class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $growth >= 0 ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' }}">
                                        <span class="material-symbols-rounded text-sm mr-1">
                                            {{ $growth >= 0 ? 'trending_up' : 'trending_down' }}
                                        </span>
                                        {{ $growth >= 0 ? '+' : '' }}{{ number_format($growth, 1) }}%
                                    </span>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </x-app-layout> --}}

    <x-app-layout>

        <div>
            <x-slot name="title">Revenue By Category and Year Report</x-slot>
            <x-slot name="desc">
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Historical category performance analysis</p>
            </x-slot>
        </div>

        <div class="space-y-6">
            {{-- Header with Filter --}}
            <div class="flex justify-end items-center">
                {{-- Category Filter --}}
                <div class="flex items-center gap-3">
                    <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Category:</label>
                    <select id="categoryFilter"
                        class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"
                        onchange="window.location.href='{{ route('reports.revenueByCategoryYear') }}?category_id='+this.value">
                        <option value="">All Categories</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ $category->id == $selectedCategoryId ? 'selected' : '' }}>
                                {{ $category->category_name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            {{-- Summary Cards --}}
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Revenue</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                                ${{ number_format($revenueData->sum('total_revenue'), 2) }}
                            </p>
                        </div>
                        <div
                            class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                            <span
                                class="material-symbols-rounded text-green-600 dark:text-green-400">account_balance_wallet</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Items Sold</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                                {{ number_format($revenueData->sum('total_quantity')) }}
                            </p>
                        </div>
                        <div
                            class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                            <span class="material-symbols-rounded text-blue-600 dark:text-blue-400">inventory_2</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Total Orders</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                                {{ number_format($revenueData->sum('total_orders')) }}
                            </p>
                        </div>
                        <div
                            class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center">
                            <span
                                class="material-symbols-rounded text-purple-600 dark:text-purple-400">shopping_bag</span>
                        </div>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm text-gray-600 dark:text-gray-400">Years Tracked</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                                {{ $revenueData->unique('year')->count() }}
                            </p>
                        </div>
                        <div
                            class="w-12 h-12 bg-orange-100 dark:bg-orange-900 rounded-full flex items-center justify-center">
                            <span class="material-symbols-rounded text-orange-600 dark:text-orange-400">history</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Revenue Table --}}
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
                <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">
                        @if ($selectedCategoryId)
                            {{ $categories->firstWhere('id', $selectedCategoryId)->category_name }} Performance Over
                            Time
                        @else
                            All Categories Performance Over Time
                        @endif
                    </h2>
                </div>

                <div class="overflow-x-auto">
                    @php
                        // Get unique years and sort them in descending order
                        $years = $revenueData->pluck('year')->unique()->sortDesc()->values();

                        // Group data by category
                        $categoryData = $revenueData->groupBy('category_name');
                    @endphp

                    <table class="w-full">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider sticky left-0 bg-gray-50 dark:bg-gray-700 z-10">
                                    Category
                                </th>
                                @foreach ($years as $year)
                                    <th
                                        class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider border-l border-gray-300 dark:border-gray-600">
                                        <div class="flex flex-col items-center">
                                            <span
                                                class="material-symbols-rounded text-indigo-600 dark:text-indigo-400 text-lg mb-1">calendar_today</span>
                                            <span>{{ $year }}</span>
                                        </div>
                                    </th>
                                @endforeach
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider border-l-2 border-indigo-300 dark:border-indigo-600">
                                    Total
                                </th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($categoryData as $categoryName => $items)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td
                                        class="px-6 py-4 whitespace-nowrap sticky left-0 bg-white dark:bg-gray-800 z-10">
                                        <div class="flex items-center">
                                            <span
                                                class="material-symbols-rounded text-indigo-600 dark:text-indigo-400 mr-2">label</span>
                                            <span
                                                class="text-sm font-medium text-gray-900 dark:text-white">{{ $categoryName }}</span>
                                        </div>
                                    </td>
                                    @php
                                        $categoryTotal = 0;
                                    @endphp
                                    @foreach ($years as $year)
                                        @php
                                            $yearData = $items->firstWhere('year', $year);
                                            $categoryTotal += $yearData ? $yearData->total_revenue : 0;
                                        @endphp
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-center border-l border-gray-200 dark:border-gray-700">
                                            @if ($yearData)
                                                <div class="space-y-1">
                                                    <div
                                                        class="text-sm font-semibold text-green-600 dark:text-green-400">
                                                        ${{ number_format($yearData->total_revenue, 2) }}
                                                    </div>
                                                    <div class="text-xs text-gray-500 dark:text-gray-400">
                                                        {{ number_format($yearData->total_quantity) }} items
                                                    </div>
                                                    <div class="text-xs text-gray-400 dark:text-gray-500">
                                                        {{ number_format($yearData->total_orders) }} orders
                                                    </div>
                                                </div>
                                            @else
                                                <span class="text-sm text-gray-400 dark:text-gray-500">-</span>
                                            @endif
                                        </td>
                                    @endforeach
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-right border-l-2 border-indigo-300 dark:border-indigo-600">
                                        <span class="text-sm font-bold text-indigo-600 dark:text-indigo-400">
                                            ${{ number_format($categoryTotal, 2) }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ count($years) + 2 }}" class="px-6 py-12 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <span
                                                class="material-symbols-rounded text-gray-400 text-5xl mb-2">inbox</span>
                                            <p class="text-gray-500 dark:text-gray-400">
                                                No data available
                                                @if ($selectedCategoryId)
                                                    for this category
                                                @endif
                                            </p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse

                            {{-- Totals Row --}}
                            @if ($categoryData->isNotEmpty())
                                <tr
                                    class="bg-indigo-50 dark:bg-indigo-900/20 font-semibold border-t-2 border-indigo-300 dark:border-indigo-600">
                                    <td
                                        class="px-6 py-4 whitespace-nowrap sticky left-0 bg-indigo-50 dark:bg-indigo-900/20 z-10">
                                        <div class="flex items-center">
                                            <span
                                                class="material-symbols-rounded text-indigo-600 dark:text-indigo-400 mr-2">functions</span>
                                            <span class="text-sm font-bold text-gray-900 dark:text-white">Year
                                                Totals</span>
                                        </div>
                                    </td>
                                    @php
                                        $grandTotal = 0;
                                    @endphp
                                    @foreach ($years as $year)
                                        @php
                                            $yearTotal = $revenueData->where('year', $year)->sum('total_revenue');
                                            $grandTotal += $yearTotal;
                                        @endphp
                                        <td
                                            class="px-6 py-4 whitespace-nowrap text-center border-l border-indigo-200 dark:border-indigo-700">
                                            <span class="text-sm font-bold text-indigo-600 dark:text-indigo-400">
                                                ${{ number_format($yearTotal, 2) }}
                                            </span>
                                        </td>
                                    @endforeach
                                    <td
                                        class="px-6 py-4 whitespace-nowrap text-right border-l-2 border-indigo-300 dark:border-indigo-600">
                                        <span class="text-sm font-bold text-indigo-700 dark:text-indigo-300">
                                            ${{ number_format($grandTotal, 2) }}
                                        </span>
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Year-over-Year Growth Chart (if we have multiple years) --}}
            @if (isset($years) && $years->count() > 1)
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">
                        @if ($selectedCategoryId)
                            {{ $categories->firstWhere('id', $selectedCategoryId)->category_name }} - Year-over-Year
                            Growth
                        @else
                            Year-over-Year Growth by Category
                        @endif
                    </h3>
                    <div class="space-y-4">
                        @foreach ($categoryData as $categoryName => $items)
                            @php
                                $sortedItems = $items->sortBy('year')->values();
                                $hasGrowth = false;
                            @endphp

                            <div class="p-4 bg-gray-50 dark:bg-gray-700 rounded-lg">
                                <h4 class="text-sm font-semibold text-gray-900 dark:text-white mb-3 flex items-center">
                                    <span
                                        class="material-symbols-rounded text-indigo-600 dark:text-indigo-400 mr-2 text-lg">label</span>
                                    {{ $categoryName }}
                                </h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach ($sortedItems as $index => $data)
                                        @php
                                            $previousData = $index > 0 ? $sortedItems->get($index - 1) : null;
                                            $growth = null;

                                            if ($previousData && $previousData->total_revenue > 0) {
                                                $growth =
                                                    (($data->total_revenue - $previousData->total_revenue) /
                                                        $previousData->total_revenue) *
                                                    100;
                                                $hasGrowth = true;
                                            }
                                        @endphp
                                        @if ($growth !== null && $previousData)
                                            <div
                                                class="flex items-center justify-between p-3 bg-white dark:bg-gray-800 rounded-lg border border-gray-200 dark:border-gray-600">
                                                <span class="text-xs font-medium text-gray-700 dark:text-gray-300">
                                                    {{ $previousData->year }} → {{ $data->year }}
                                                </span>
                                                <span
                                                    class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-semibold {{ $growth >= 0 ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' }}">
                                                    <span class="material-symbols-rounded text-sm mr-1">
                                                        {{ $growth >= 0 ? 'trending_up' : 'trending_down' }}
                                                    </span>
                                                    {{ $growth >= 0 ? '+' : '' }}{{ number_format($growth, 1) }}%
                                                </span>
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                                @if (!$hasGrowth)
                                    <p class="text-xs text-gray-500 dark:text-gray-400 text-center py-2">No
                                        year-over-year data available</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>

    </x-app-layout>
