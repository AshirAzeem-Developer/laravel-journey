<x-app-layout>

    <x-slot name="title">Revenue By Year Report</x-slot>
    <x-slot name="desc">
        <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Yearly revenue trends and performance</p>

    </x-slot>

    <div class="space-y-6">
        {{-- Summary Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">All-Time Revenue</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                            ${{ number_format($revenueData->sum('total_revenue'), 2) }}
                        </p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 dark:bg-green-900 rounded-full flex items-center justify-center">
                        <span class="material-symbols-rounded text-green-600 dark:text-green-400">account_balance</span>
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
                    <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900 rounded-full flex items-center justify-center">
                        <span class="material-symbols-rounded text-blue-600 dark:text-blue-400">shopping_cart</span>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Best Year</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                            @if ($revenueData->isNotEmpty())
                                {{ $revenueData->sortByDesc('total_revenue')->first()->year }}
                            @else
                                N/A
                            @endif
                        </p>
                    </div>
                    <div
                        class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900 rounded-full flex items-center justify-center">
                        <span class="material-symbols-rounded text-yellow-600 dark:text-yellow-400">emoji_events</span>
                    </div>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-600 dark:text-gray-400">Avg Order Value</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                            ${{ $revenueData->avg('avg_order_value') ? number_format($revenueData->avg('avg_order_value'), 2) : '0.00' }}
                        </p>
                    </div>
                    <div
                        class="w-12 h-12 bg-purple-100 dark:bg-purple-900 rounded-full flex items-center justify-center">
                        <span class="material-symbols-rounded text-purple-600 dark:text-purple-400">payments</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Revenue Table --}}
        <div class="bg-white dark:bg-gray-800 rounded-xl shadow-lg overflow-hidden">
            <div class="p-6 border-b border-gray-200 dark:border-gray-700">
                <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Yearly Performance</h2>
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
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Total Revenue
                            </th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Total Orders
                            </th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Average Order Value
                            </th>
                            <th
                                class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                Growth
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($revenueData as $index => $data)
                            @php
                                $previousData = $revenueData->get($index + 1);
                                $growth = null;
                                if ($previousData) {
                                    $growth =
                                        (($data->total_revenue - $previousData->total_revenue) /
                                            $previousData->total_revenue) *
                                        100;
                                }
                            @endphp
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <span
                                            class="material-symbols-rounded text-indigo-600 dark:text-indigo-400 mr-2">calendar_today</span>
                                        <span
                                            class="text-sm font-medium text-gray-900 dark:text-white">{{ $data->year }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <span class="text-sm font-semibold text-green-600 dark:text-green-400">
                                        ${{ number_format($data->total_revenue, 2) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <span class="text-sm text-gray-900 dark:text-white">
                                        {{ number_format($data->total_orders) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    <span class="text-sm text-gray-600 dark:text-gray-400">
                                        ${{ number_format($data->avg_order_value, 2) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right">
                                    @if ($growth !== null)
                                        <span
                                            class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $growth >= 0 ? 'bg-green-100 dark:bg-green-900 text-green-800 dark:text-green-200' : 'bg-red-100 dark:bg-red-900 text-red-800 dark:text-red-200' }}">
                                            <span class="material-symbols-rounded text-sm mr-1">
                                                {{ $growth >= 0 ? 'trending_up' : 'trending_down' }}
                                            </span>
                                            {{ number_format(abs($growth), 1) }}%
                                        </span>
                                    @else
                                        <span class="text-sm text-gray-400">N/A</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <span class="material-symbols-rounded text-gray-400 text-5xl mb-2">inbox</span>
                                        <p class="text-gray-500 dark:text-gray-400">No revenue data available</p>
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
