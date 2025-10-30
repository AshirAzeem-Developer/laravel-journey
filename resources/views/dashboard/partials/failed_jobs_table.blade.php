<x-slot name="title">
    {{ __('Failed Queue Jobs') }}
</x-slot>

<x-slot name="desc">
    {{ __(' Alerting to recent background processing failures.') }}
</x-slot>

<section class="p-6 bg-gray-50 dark:bg-gray-900 rounded-2xl shadow-sm">
    <div class="flex items-center justify-end mb-6">
        <span
            class="px-3 py-1 text-xs font-medium text-red-600 bg-red-100 rounded-full dark:bg-red-900 dark:text-red-300">
            ⚠ System Alerts
        </span>
    </div>

    <div
        class="overflow-x-auto bg-white dark:bg-gray-800 rounded-xl shadow-md border border-red-100 dark:border-gray-700">
        <table class="min-w-full divide-y divide-red-200 dark:divide-gray-700">
            <thead class="bg-red-50 dark:bg-gray-700/50">
                <tr>
                    <th
                        class="px-6 py-3 text-left text-xs font-semibold text-red-700 dark:text-red-300 uppercase tracking-wider">
                        ID
                    </th>
                    <th
                        class="px-6 py-3 text-left text-xs font-semibold text-red-700 dark:text-red-300 uppercase tracking-wider">
                        Queue
                    </th>
                    <th
                        class="px-6 py-3 text-left text-xs font-semibold text-red-700 dark:text-red-300 uppercase tracking-wider">
                        Failure Time
                    </th>
                    <th
                        class="px-6 py-3 text-left text-xs font-semibold text-red-700 dark:text-red-300 uppercase tracking-wider">
                        Exception Summary
                    </th>
                </tr>
            </thead>

            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                @forelse ($contentData['failedJobs'] as $job)
                    <tr
                        class="hover:bg-red-50/70 dark:hover:bg-gray-700 transition-colors duration-150 border-l-4 border-transparent hover:border-red-400">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-100">
                            {{ $job->id }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                            {{ $job->queue }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                            {{ \Carbon\Carbon::parse($job->failed_at)->format('Y-m-d H:i') }}
                        </td>
                        <td
                            class="px-6 py-4 text-sm text-red-600 dark:text-red-400 max-w-md overflow-hidden truncate font-medium">
                            {{ Str::before($job->exception, ' in ') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4"
                            class="px-6 py-6 text-center text-sm text-gray-600 dark:text-gray-400 bg-green-50 dark:bg-gray-800/50 rounded-lg">
                            ✅ Great! No failed jobs found.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</section>
