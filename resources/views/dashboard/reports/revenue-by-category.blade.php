<x-app-layout>

    <x-slot name="title">Revenue By Category Report</x-slot>
    <x-slot name="desc">
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Category performance for {{ $selectedYear }}</p>
    </x-slot>


    <div class="space-y-6">
        {{-- Header with Filter --}}
        <div class="flex justify-end items-center">
            {{-- Year Filter --}}
            <div class="flex items-center gap-3">
                <label class="text-sm font-medium text-gray-700 dark:text-gray-300">Year:</label>
                <select id="yearFilter"
                    class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-white focus:ring-2 focus:ring-indigo-500"
                    onchange="window.location.href='{{ route('reports.revenueByCategory') }}?year='+this.value">
                    @foreach ($availableYears as $year)
                        <option value="{{ $year }}" {{ $year == $selectedYear ? 'selected' : '' }}>
                            {{ $year }}
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
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                        <span class="material-symbols-rounded text-green-600 dark:text-green-400">payments</span>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Total Categories</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                            {{ $revenueData->count() }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                        <span class="material-symbols-rounded text-blue-600 dark:text-blue-400">category</span>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Items Sold</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                            {{ number_format($revenueData->sum('total_quantity')) }}
                        </p>
                    </div>
                    <div
                        class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center">
                        <span class="material-symbols-rounded text-purple-600 dark:text-purple-400">inventory</span>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Top Category</p>
                        <p class="text-lg font-bold text-gray-900 dark:text-white mt-1">
                            @if ($revenueData->isNotEmpty())
                                {{ Str::limit($revenueData->sortByDesc('total_revenue')->first()->category_name, 15) }}
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                    <div
                        class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
                        <span class="material-symbols-rounded text-yellow-600 dark:text-yellow-400">star</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Revenue Table --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Category Performance</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Rank
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
                                Market Share
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @php $totalRevenue = $revenueData->sum('total_revenue'); @endphp
                        @forelse($revenueData as $index => $data)
                            @php
                                $marketShare = $totalRevenue > 0 ? ($data->total_revenue / $totalRevenue) * 100 : 0;
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span
                                        class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $index < 3 ? 'bg-yellow-100 dark:bg-yellow-900 text-yellow-700 dark:text-yellow-300' : 'bg-gray-100 dark:bg-gray-700 text-gray-600 dark:text-gray-400' }} text-sm font-bold">
                                        {{ $index + 1 }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span
                                            class="material-symbols-rounded text-indigo-600 dark:text-indigo-400 mr-2">label</span>
                                        <span
                                            class="text-sm font-medium text-gray-900 dark:text-white">{{ $data->category_name }}</span>
                                    </div>
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
                                    <div class="flex items-center justify-end gap-2">
                                        <div class="w-24 bg-gray-200 dark:bg-gray-700 rounded-full h-2">
                                            <div class="bg-indigo-600 h-2 rounded-full"
                                                style="width: {{ $marketShare }}%"></div>
                                        </div>
                                        <span class="text-sm text-gray-600 dark:text-gray-400 w-12 text-right">
                                            {{ number_format($marketShare, 1) }}%
                                        </span>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <span class="material-symbols-rounded text-gray-400 text-5xl mb-2">inbox</span>
                                        <p class="text-gray-500 dark:text-gray-400">No category data available for
                                            {{ $selectedYear }}</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
