<x-slot name="title">
    {{ __('System Users') }}
</x-slot>

<x-slot name="desc">
    {{ __('A list of all registered users in the system.') }}
</x-slot>

<div
    class="min-h-screen bg-gradient-to-br from-gray-50 via-white to-slate-100 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 p-6 md:p-10">

    <div class="max-w-7xl mx-auto animate-fadeIn">
        <div class="mb-8 flex flex-col md:flex-row items-start md:items-center justify-end gap-4">
            <button type="button" onclick="openAddUserModal()"
                class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl shadow-md transition-all duration-200 flex items-center gap-2">
                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Add New User
            </button>
        </div>

        {{-- Users Table --}}
        <div
            class="overflow-hidden bg-white/70 dark:bg-gray-800/60 backdrop-blur-lg rounded-2xl shadow-xl border border-gray-200 dark:border-gray-700">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gradient-to-r from-indigo-500 to-indigo-600 dark:from-indigo-600 dark:to-indigo-700">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase">ID</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase">Profile</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase">Name</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase">Email</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold text-white uppercase">Last Updated</th>
                        <th class="px-6 py-4"></th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                    @foreach ($contentData['users'] as $user)
                        <tr
                            class="hover:bg-indigo-50 dark:hover:bg-indigo-900/30 transition-all duration-200 cursor-pointer">
                            <td class="px-6 py-4 text-gray-800 dark:text-gray-200 font-medium">{{ $user->id }}</td>
                            <td class="px-6 py-4">
                                <img src="{{ $user->file_path ? asset('storage/' . $user->file_path) : asset('asset/images/default-avatar.png') }}"
                                    class="w-10 h-10 rounded-full object-cover border border-gray-300 dark:border-gray-700"
                                    alt="Profile">
                            </td>
                            <td class="px-6 py-4 text-gray-800 dark:text-gray-200">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-gray-600 dark:text-gray-400">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-gray-500 dark:text-gray-400">
                                {{ $user->updated_at->diffForHumans() }}</td>
                            <td class="px-6 py-4 text-right">
                                <div class="flex items-center justify-end gap-3">
                                    <button type="button" onclick="fetchAndOpenEditModal('{{ $user->id }}')"
                                        class="text-indigo-600 hover:text-indigo-800 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15.232 5.232l3.536 3.536M4 20h4l12-12-4-4L4 16v4z" />
                                        </svg>
                                    </button>
                                    {{-- The delete button now calls the JS function to open the modal --}}
                                    <button type="button"
                                        onclick="openDeleteConfirmModal('{{ route('users.destroy', $user->id) }}')"
                                        class="text-rose-500 hover:text-rose-700 transition">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862A2 2 0 015 19.142L4.133 7H19z" />
                                        </svg>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="p-2 px-8">
                {{ $contentData['users']->links() }}
            </div>


            @if (count($contentData['users']) === 0)
                <div class="text-center py-10 text-gray-500 dark:text-gray-400">
                    <p>No users found.</p>
                </div>
            @endif
        </div>

    </div>

    {{-- MODULAR INCLUDES --}}
    @include('dashboard.partials.add-edit-user-modal')
    @include('dashboard.partials.delete-confirm-modal')
    @include('dashboard.partials.user-scripts')

</div>
