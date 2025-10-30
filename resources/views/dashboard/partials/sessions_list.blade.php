  <x-slot name="title">
      {{ __('Recent User Sessions') }}
  </x-slot>

  <x-slot name="desc">
      {{ __('Displaying the top 10 most recently active sessions.') }}
  </x-slot>



  <section class="p-6 bg-gray-50 dark:bg-gray-900 rounded-2xl shadow-sm">
      <div class="flex items-center justify-end mb-6">
          <span
              class="px-3 py-1 text-xs font-medium text-blue-600 bg-blue-100 rounded-full dark:bg-blue-900 dark:text-blue-300">
              Active Users
          </span>
      </div>

      <div
          class="overflow-x-auto bg-white dark:bg-gray-800 rounded-xl shadow-md border border-gray-100 dark:border-gray-700">
          <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
              <thead class="bg-gray-100 dark:bg-gray-700/50">
                  <tr>
                      <th
                          class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                          User ID
                      </th>
                      <th
                          class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                          IP Address
                      </th>
                      <th
                          class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                          Browser / Agent
                      </th>
                      <th
                          class="px-6 py-3 text-left text-xs font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider">
                          Last Activity
                      </th>
                  </tr>
              </thead>

              <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                  @forelse ($contentData['sessions'] as $session)
                      <tr class="hover:bg-blue-50 dark:hover:bg-gray-700 transition-colors duration-150">
                          <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-800 dark:text-gray-100">
                              {{ $session->user_id ?? 'Guest' }}
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400">
                              {{ $session->ip_address ?? 'N/A' }}
                          </td>
                          <td
                              class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 dark:text-gray-400 max-w-xs overflow-hidden truncate">
                              {{ Str::limit($session->user_agent, 50) }}
                          </td>
                          <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-400">
                              {{ \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans() }}
                          </td>
                      </tr>
                  @empty
                      <tr>
                          <td colspan="4" class="px-6 py-6 text-center text-sm text-gray-500 dark:text-gray-400">
                              No active sessions found.
                          </td>
                      </tr>
                  @endforelse
              </tbody>
          </table>
      </div>
  </section>
