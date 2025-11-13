<x-app-layout>
    <x-slot name="title">{{ __('Orders Management') }}</x-slot>
    <x-slot name="desc">{{ __('Comprehensive order tracking and management system') }}</x-slot>

    <div
        class="py-8 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800 min-h-screen">
        <div class="max-w-[1600px] mx-auto space-y-6">

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total Orders</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">{{ $orders->total() }}</p>
                        </div>
                        <div
                            class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-full flex items-center justify-center">
                            <i class="fas fa-shopping-cart text-blue-600 dark:text-blue-400"></i>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Pending Orders</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                                @php
                                    $pendingCount = 0;
                                    foreach ($orders as $order) {
                                        if ($order->payment_status === 'pending') {
                                            $pendingCount++;
                                        }
                                    }
                                @endphp
                                {{ $pendingCount }}
                            </p>
                        </div>
                        <div
                            class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-full flex items-center justify-center">
                            <i class="fas fa-clock text-yellow-600 dark:text-yellow-400"></i>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Completed</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                                @php
                                    $deliveredCount = 0;
                                    foreach ($orders as $order) {
                                        if ($order->order_status === 'delivered') {
                                            $deliveredCount++;
                                        }
                                    }
                                @endphp
                                {{ $deliveredCount }}
                            </p>
                        </div>
                        <div
                            class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-full flex items-center justify-center">
                            <i class="fas fa-check-circle text-green-600 dark:text-green-400"></i>
                        </div>
                    </div>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-5 hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Revenue</p>
                            <p class="text-2xl font-bold text-gray-900 dark:text-white mt-1">
                                @php
                                    $totalRevenue = 0;
                                    foreach ($orders as $order) {
                                        $totalRevenue += $order->total_amount;
                                    }
                                @endphp
                                ${{ number_format($totalRevenue, 2) }}
                            </p>
                        </div>
                        <div
                            class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-full flex items-center justify-center">
                            <i class="fas fa-dollar-sign text-purple-600 dark:text-purple-400"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Header Section with Enhanced Controls -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 p-6">
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-bold text-gray-900 dark:text-white">Orders Management</h2>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Track and manage all customer orders
                        </p>
                    </div>

                    <form method="GET" action="{{ route('admin.getAllOrders') }}"
                        class="flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                        <!-- Search Input -->
                        <div class="relative flex-1 sm:min-w-[280px]">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <i class="fas fa-search text-gray-400"></i>
                            </div>
                            <input type="text" name="search" value="{{ request('search') }}"
                                placeholder="Search by order #, user ID, transaction..."
                                class="w-full pl-10 pr-4 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all" />
                        </div>

                        <!-- Payment Status Filter -->
                        <select name="payment_status" onchange="this.form.submit()"
                            class="px-4 pr-8 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                            <option value="">All Payments</option>
                            <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Paid
                            </option>
                            <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>
                                Pending</option>
                            <option value="cancelled" {{ request('payment_status') == 'cancelled' ? 'selected' : '' }}>
                                Cancelled</option>
                            <option value="refunded" {{ request('payment_status') == 'refunded' ? 'selected' : '' }}>
                                Refunded</option>
                        </select>

                        <!-- Order Status Filter -->
                        <select name="order_status" onchange="this.form.submit()"
                            class="px-4 pr-6 py-2.5 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all">
                            <option value="">All Orders</option>
                            <option value="pending" {{ request('order_status') == 'pending' ? 'selected' : '' }}>
                                Pending</option>
                            <option value="processing" {{ request('order_status') == 'processing' ? 'selected' : '' }}>
                                Processing</option>
                            <option value="shipped" {{ request('order_status') == 'shipped' ? 'selected' : '' }}>
                                Shipped</option>
                            <option value="delivered" {{ request('order_status') == 'delivered' ? 'selected' : '' }}>
                                Delivered</option>
                        </select>

                        <!-- Export Button -->
                        <button type="submit"
                            class="inline-flex items-center justify-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition-colors shadow-sm">
                            <i class="fas fa-search"></i>
                            <span class="hidden sm:inline">Apply</span>
                        </button>
                    </form>

                </div>
            </div>

            <!-- Enhanced Orders Table -->
            <div
                class="bg-white dark:bg-gray-800 rounded-xl shadow-sm border border-gray-200 dark:border-gray-700 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-900/50">
                            <tr>

                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Order / Transaction ID
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Customer
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Amount
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Payment
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Shipping Address
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-left text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Date
                                </th>
                                <th scope="col"
                                    class="px-6 py-4 text-center text-xs font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-800">
                            @forelse ($orders as $order)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div>
                                                <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                                    #{{ $order->order_number }}
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ $order->transaction_id ?? 'No Transaction ID' }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">

                                            <div>
                                                <div class="text-sm font-medium text-gray-900 dark:text-white">
                                                    {{ $order->user->name ?? 'Guest User' }}
                                                </div>
                                                <div class="text-xs text-gray-500 dark:text-gray-400">
                                                    ID: {{ $order->user_id }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-gray-900 dark:text-white">
                                            ${{ number_format($order->total_amount, 2) }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400 capitalize">
                                            via {{ $order->payment_method }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold
                                            @if ($order->payment_status === 'paid') bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-400
                                            @elseif($order->payment_status === 'pending')
                                                bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-400
                                            @elseif($order->payment_status === 'refunded')
                                                bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                            @else
                                                bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-400 @endif">
                                            <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                            {{ ucfirst($order->payment_status) }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4">
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold
                                            @if ($order->order_status === 'delivered') bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-400
                                            @elseif($order->order_status === 'shipped')
                                                bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400
                                            @elseif($order->order_status === 'processing')
                                                bg-indigo-100 text-indigo-800 dark:bg-indigo-900/30 dark:text-indigo-400
                                            @else
                                                bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300 @endif">
                                            <i class="fas fa-circle text-[6px]"></i>
                                            {{ ucfirst($order->order_status) }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 min-w-[300px]">
                                        <div
                                            class="text-sm text-gray-900 dark:text-gray-100 whitespace-normal w-full leading-relaxed">
                                            {{ $order->shipping_address }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 min-w-[150px]">
                                        <div class="text-sm text-gray-900 dark:text-gray-100">
                                            {{ is_string($order->created_at) ? \Carbon\Carbon::parse($order->created_at)->format('M d, Y') : $order->created_at->format('M d, Y') }}
                                        </div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">
                                            {{ is_string($order->created_at) ? \Carbon\Carbon::parse($order->created_at)->format('h:i A') : $order->created_at->format('h:i A') }}
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="flex items-center justify-center gap-2">
                                            <button title="View Details" data-modal="view"
                                                data-id="{{ $order->id }}"
                                                class="p-2 text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-all">
                                                <i class="fas fa-eye text-sm"></i>
                                            </button>
                                            <form action="{{ route('admin.updateOrderStatus', $order->id) }}"
                                                method="POST" class="inline">
                                                @csrf
                                                @method('PUT')
                                                <select name="status" onchange="this.form.submit()"
                                                    class="text-sm rounded-lg border-gray-300 focus:ring-indigo-500 focus:border-indigo-500
                       dark:bg-gray-800 dark:border-gray-700 dark:text-gray-100 transition duration-150 ease-in-out">
                                                    <option value="pending"
                                                        {{ $order->order_status == 'pending' ? 'selected' : '' }}>
                                                        Pending
                                                    </option>
                                                    <option value="processing"
                                                        {{ $order->order_status == 'in_process' ? 'selected' : '' }}>In
                                                        Process
                                                    </option>
                                                    <option value="shipped"
                                                        {{ $order->order_status == 'shipped' ? 'selected' : '' }}>
                                                        Shipped
                                                    </option>
                                                    <option value="delivered"
                                                        {{ $order->order_status == 'delivered' ? 'selected' : '' }}>
                                                        Delivered
                                                    </option>
                                                    <option value="cancelled"
                                                        {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>
                                                        Cancelled
                                                    </option>
                                                </select>
                                            </form>



                                            <button title="Print Invoice"
                                                class="p-2 text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 rounded-lg transition-all">
                                                <i class="fas fa-print text-sm"></i>
                                            </button>
                                            <button title="Delete Order" data-modal="delete"
                                                data-id="{{ $order->id }}"
                                                class="p-2 text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-300 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-all">
                                                <i class="fas fa-trash-alt text-sm"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-16 text-center">
                                        <div class="flex flex-col items-center justify-center">
                                            <div
                                                class="w-16 h-16 bg-gray-100 dark:bg-gray-700 rounded-full flex items-center justify-center mb-4">
                                                <i class="fas fa-inbox text-2xl text-gray-400 dark:text-gray-500"></i>
                                            </div>
                                            <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-1">No
                                                orders found</h3>
                                            <p class="text-sm text-gray-500 dark:text-gray-400">Start by creating your
                                                first order or adjust your filters</p>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Enhanced Pagination -->
                <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900/50">

                    @if ($orders->hasPages())
                        <div class="px-6 py-4  ">
                            {{ $orders->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div id="modalBackdrop" class="hidden fixed inset-0 bg-black/50 backdrop-blur-sm z-40"></div>
</x-app-layout>

<style>
    /* Custom scrollbar */
    .overflow-x-auto::-webkit-scrollbar {

        height: 8px;
    }

    .overflow-x-auto::-webkit-scrollbar-track {
        background: rgb(243 244 246 / 1);
    }

    .dark .overflow-x-auto::-webkit-scrollbar-track {
        background: rgb(31 41 55 / 1);
    }

    .overflow-x-auto::-webkit-scrollbar-thumb {
        background: rgb(209 213 219 / 1);
        border-radius: 4px;
    }

    .dark .overflow-x-auto::-webkit-scrollbar-thumb {
        background: rgb(75 85 99 / 1);
    }

    .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: rgb(156 163 175 / 1);
    }

    .dark .overflow-x-auto::-webkit-scrollbar-thumb:hover {
        background: rgb(107 114 128 / 1);
    }

    /* Smooth transitions */
    button {
        transition: all 0.2s ease-in-out;
    }
</style>

@include('orders.modals.view')
@include('orders.modals.delete')
@include('orders.script')
