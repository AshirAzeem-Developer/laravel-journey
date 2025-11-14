<aside class="w-full bg-white dark:bg-gray-800 rounded-xl shadow-xl flex flex-col h-full transition-colors duration-200"
    id="sidenav-main">

    @php
        // Ensure we have a reliable active section even if the controller didn't provide it
if (!isset($activeSection)) {
    if (request()->routeIs('adminDashboard')) {
        $activeSection = 'summary_stats';
    } else {
        // For the route named 'dashboard.section' the route parameter is 'section'
        $activeSection = request()->route('section') ?? null;
    }
}

// Normalize counters used in the sidebar so we can show badges reliably
$totalUsersCount = $contentData['totalUsers'] ?? ($summaryData['totalUsers'] ?? null);

if (!isset($failedJobsCount)) {
    if (isset($contentData['failedJobs'])) {
        $failedJobsCount = is_countable($contentData['failedJobs']) ? count($contentData['failedJobs']) : null;
            } elseif (isset($failedJobs)) {
                $failedJobsCount = is_countable($failedJobs) ? count($failedJobs) : null;
            } else {
                $failedJobsCount = null;
            }
        }
    @endphp

    {{-- Sidenav Header (Logo and Brand Name) --}}
    <div class="p-4 border-b border-gray-200 dark:border-gray-700 flex justify-between items-center flex-shrink-0">
        <a class="flex items-center justify-center  py-3 " href="{{ route('adminDashboard') }}">
            <img src="{{ asset('asset/images/logo1.png') }}" class="w-full h-auto rounded-lg" alt="main_logo">
            {{-- <span
                class="ml-1 text-sm text-gray-900 dark:text-white font-bold">{{ config('app.name', 'Dashboard') }}</span> --}}
        </a>

        {{-- Mobile Close Button --}}
        <button
            class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 md:hidden p-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
            onclick="toggleMobileSidebar()" aria-label="Close sidebar">
            <span class="material-symbols-rounded">close</span>
        </button>
    </div>
    {{-- Scrollable Content Area --}}
    <div class="flex-1 overflow-y-auto px-4" id="sidenav-collapse-main">
        <ul class="flex flex-col space-y-1 mt-4">

            {{-- DASHBOARD SECTION --}}
            <li class="mb-1">
                <h6 class="px-3 text-xs font-bold uppercase text-gray-500 dark:text-gray-400 tracking-wider mb-2">
                    Dashboard
                </h6>
            </li>

            {{-- Dashboard Summary --}}
            <li class="mb-1">
                <a class="flex items-center py-2.5 px-3 rounded-lg transition-all duration-200 {{ !isset($activeSection) || $activeSection === 'summary_stats' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/50' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                    href="{{ route('adminDashboard') }}">
                    <span
                        class="material-symbols-rounded mr-3 text-xl {{ !isset($activeSection) || $activeSection === 'summary_stats' ? 'text-white' : 'text-gray-500 dark:text-gray-400' }}">
                        dashboard
                    </span>
                    <span class="text-sm font-medium">Dashboard Summary</span>
                </a>
            </li>

            {{-- System Users --}}
            <li class="mb-1">
                <a class="flex items-center py-2.5 px-3 rounded-lg transition-all duration-200 {{ isset($activeSection) && $activeSection === 'users' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/50' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                    href="{{ route('dashboard.section', 'users') }}">
                    <span
                        class="material-symbols-rounded mr-3 text-xl {{ isset($activeSection) && $activeSection === 'users' ? 'text-white' : 'text-gray-500 dark:text-gray-400' }}">
                        group
                    </span>
                    <span class="text-sm font-medium">System Users</span>
                    @if (isset($totalUsersCount) && $totalUsersCount > 0)
                        <span
                            class="ml-auto bg-indigo-100 dark:bg-indigo-900 text-indigo-600 dark:text-indigo-300 text-xs font-semibold px-2 py-0.5 rounded-full">
                            {{ $totalUsersCount }}
                        </span>
                    @endif
                </a>
            </li>

            {{-- Active Sessions --}}
            <li class="mb-1">
                <a class="flex items-center py-2.5 px-3 rounded-lg transition-all duration-200 {{ isset($activeSection) && $activeSection === 'sessions' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/50' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                    href="{{ route('dashboard.section', 'sessions') }}">
                    <span
                        class="material-symbols-rounded mr-3 text-xl {{ isset($activeSection) && $activeSection === 'sessions' ? 'text-white' : 'text-gray-500 dark:text-gray-400' }}">
                        shield_person
                    </span>
                    <span class="text-sm font-medium">Active Sessions</span>
                    <span class="ml-auto w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
                </a>
            </li>

            {{-- Failed Jobs --}}
            <li class="mb-1">
                <a class="flex items-center py-2.5 px-3 rounded-lg transition-all duration-200 {{ isset($activeSection) && $activeSection === 'jobs' ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/50' : 'text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}"
                    href="{{ route('dashboard.section', 'jobs') }}">
                    <span
                        class="material-symbols-rounded mr-3 text-xl {{ isset($activeSection) && $activeSection === 'jobs' ? 'text-white' : 'text-gray-500 dark:text-gray-400' }}">
                        error
                    </span>
                    <span class="text-sm font-medium">Failed Jobs</span>
                    @if (isset($failedJobsCount) && $failedJobsCount > 0)
                        <span
                            class="ml-auto bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-300 text-xs font-semibold px-2 py-0.5 rounded-full">
                            {{ $failedJobsCount }}
                        </span>
                    @endif
                </a>
            </li>

            {{-- MAIN PAGES SECTION --}}
            <li class="mt-6 mb-1">
                <h6 class="px-3 text-xs font-bold uppercase text-gray-500 dark:text-gray-400 tracking-wider mb-2">
                    Main Pages
                </h6>
            </li>

            {{-- Upload --}}
            {{-- <li class="mb-1">
                <a class="flex items-center py-2.5 px-3 rounded-lg transition-all duration-200 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                    href="#">
                    <span class="material-symbols-rounded mr-3 text-xl text-gray-500 dark:text-gray-400">
                        file_upload
                    </span>
                    <span class="text-sm font-medium">Upload</span>
                </a>
            </li> --}}

            {{-- Products --}}
            <li class="mb-1">
                <a class="flex items-center py-2.5 px-3 rounded-lg transition-all duration-200 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                    href="{{ route('products.index') }}">

                    <span class="material-symbols-rounded mr-3 text-xl text-gray-500 dark:text-gray-400">
                        inventory_2
                    </span>
                    <span class="text-sm font-medium">Products</span>
                </a>
            </li>
            {{-- Orders --}}
            <li class="mb-1">
                <a class="flex items-center py-2.5 px-3 rounded-lg transition-all duration-200 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                    href="{{ route('admin.getAllOrders') }}">
                    <span class="material-symbols-rounded mr-3 text-xl text-gray-500 dark:text-gray-400">
                        shopping_cart
                    </span>
                    <span class="text-sm font-medium">Orders</span>
                </a>
            </li>

            {{-- Categories --}}

            <li class="mb-1">
                <a class="flex items-center py-2.5 px-3 rounded-lg transition-all duration-200 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                    href="{{ route('admin.getAllCategories') }}">
                    <span class="material-symbols-rounded mr-3 text-xl text-gray-500 dark:text-gray-400">
                        category
                    </span>
                    <span class="text-sm font-medium">Categories</span>
                </a>
            </li>

            {{-- Reports Dropdown --}}
            <li class="mb-1">
                <button
                    class="flex justify-between items-center w-full py-2.5 px-3 rounded-lg transition-all duration-200 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700"
                    onclick="toggleDropdown('reports-collapse', this)" aria-expanded="false"
                    aria-controls="reports-collapse">
                    <div class="flex items-center">
                        <span class="material-symbols-rounded mr-3 text-xl text-gray-500 dark:text-gray-400">
                            bar_chart
                        </span>
                        <span class="text-sm font-medium">Reports</span>
                    </div>
                    <svg class="h-5 w-5 text-gray-500 dark:text-gray-400 transition-transform duration-200 dropdown-arrow"
                        viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd"
                            d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.25 4.25a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                            clip-rule="evenodd" />
                    </svg>
                </button>

                <div id="reports-collapse"
                    class="mt-1 ml-6 space-y-1 overflow-hidden max-h-0 transition-all duration-300">
                    <ul class="flex flex-col ml-4 border-l-2 border-gray-200 dark:border-gray-700 pl-2">
                        <li class="mb-1">
                            <a class="flex items-center py-2 px-3 rounded-lg text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-gray-200 transition-colors"
                                href="#">
                                <span class="w-1.5 h-1.5 bg-gray-400 dark:bg-gray-500 rounded-full mr-2"></span>
                                Revenue By Month
                            </a>
                        </li>
                        <li class="mb-1">
                            <a class="flex items-center py-2 px-3 rounded-lg text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-gray-200 transition-colors"
                                href="#">
                                <span class="w-1.5 h-1.5 bg-gray-400 dark:bg-gray-500 rounded-full mr-2"></span>
                                Revenue By Year
                            </a>
                        </li>
                        <li class="mb-1">
                            <a class="flex items-center py-2 px-3 rounded-lg text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-gray-200 transition-colors"
                                href="#">
                                <span class="w-1.5 h-1.5 bg-gray-400 dark:bg-gray-500 rounded-full mr-2"></span>
                                Revenue By Category
                            </a>
                        </li>
                        <li class="mb-1">
                            <a class="flex items-center py-2 px-3 rounded-lg text-sm text-gray-600 dark:text-gray-400 hover:bg-gray-50 dark:hover:bg-gray-700/50 hover:text-gray-900 dark:hover:text-gray-200 transition-colors"
                                href="#">
                                <span class="w-1.5 h-1.5 bg-gray-400 dark:bg-gray-500 rounded-full mr-2"></span>
                                Revenue By Category and Year
                            </a>
                        </li>
                    </ul>
                </div>
            </li>

            {{-- ACCOUNT PAGES SECTION --}}
            <li class="mt-6 mb-1">
                <h6 class="px-3 text-xs font-bold uppercase text-gray-500 dark:text-gray-400 tracking-wider mb-2">
                    Account Pages
                </h6>
            </li>

            {{-- Profile --}}
            <li class="mb-1">
                <a class="flex items-center py-2.5 px-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
                    href="{{ route('profile.edit') }}">
                    <span class="material-symbols-rounded mr-3 text-xl text-gray-500 dark:text-gray-400">
                        person
                    </span>
                    <span class="text-sm font-medium">Profile</span>
                </a>
            </li>


            @guest
                {{-- Sign In --}}
                <li class="mb-1">
                    <a class="flex items-center py-2.5 px-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
                        href="#">
                        <span class="material-symbols-rounded mr-3 text-xl text-gray-500 dark:text-gray-400">
                            login
                        </span>
                        <span class="text-sm font-medium">Sign In</span>
                    </a>
                </li>

                {{-- Sign Up --}}
                <li class="mb-1">
                    <a class="flex items-center py-2.5 px-3 rounded-lg text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 transition-all duration-200"
                        href="#">
                        <span class="material-symbols-rounded mr-3 text-xl text-gray-500 dark:text-gray-400">
                            assignment
                        </span>
                        <span class="text-sm font-medium">Sign Up</span>
                    </a>
                </li>
            @endauth

        </ul>
    </div>

    {{-- Sidebar Footer with User Info or Version --}}
    <div class="p-4 border-t border-gray-200 dark:border-gray-700 mt-auto flex-shrink-0">
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-indigo-100 dark:bg-indigo-900 rounded-full flex items-center justify-center">
                    <span class="material-symbols-rounded text-indigo-600 dark:text-indigo-400 text-lg">
                        verified_user
                    </span>
                </div>
                <div>
                    <p class="text-xs font-semibold text-gray-900 dark:text-white">System v2.0</p>
                    <p class="text-xs text-gray-500 dark:text-gray-400">All systems operational</p>
                </div>
            </div>
            <span class="w-2 h-2 bg-green-500 rounded-full animate-pulse"></span>
        </div>
    </div>

    <style>
        /* Smooth dropdown animation */
        .max-h-0 {
            max-height: 0;
        }

        .max-h-96 {
            max-height: 24rem;
        }

        /* Rotate arrow when dropdown is open */
        .dropdown-arrow.rotated {
            transform: rotate(180deg);
        }

        /* Custom scrollbar for sidebar */
        #sidenav-collapse-main::-webkit-scrollbar {
            width: 6px;
        }

        #sidenav-collapse-main::-webkit-scrollbar-track {
            background: transparent;
        }

        #sidenav-collapse-main::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        #sidenav-collapse-main::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .dark #sidenav-collapse-main::-webkit-scrollbar-thumb {
            background: #475569;
        }

        .dark #sidenav-collapse-main::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }
    </style>

    <script>
        // Toggle dropdown with smooth animation
        function toggleDropdown(dropdownId, button) {
            const dropdown = document.getElementById(dropdownId);
            const arrow = button.querySelector('.dropdown-arrow');
            const isOpen = !dropdown.classList.contains('max-h-0');

            if (isOpen) {
                // Close dropdown
                dropdown.classList.remove('max-h-96');
                dropdown.classList.add('max-h-0');
                arrow.classList.remove('rotated');
                button.setAttribute('aria-expanded', 'false');
            } else {
                // Open dropdown
                dropdown.classList.remove('max-h-0');
                dropdown.classList.add('max-h-96');
                arrow.classList.add('rotated');
                button.setAttribute('aria-expanded', 'true');
            }
        }

        // Auto-open dropdown if a child link is active
        document.addEventListener('DOMContentLoaded', function() {
            const activeLinks = document.querySelectorAll('#reports-collapse a');
            activeLinks.forEach(link => {
                if (link.classList.contains('active') || window.location.href === link.href) {
                    const dropdown = document.getElementById('reports-collapse');
                    const button = dropdown.previousElementSibling;
                    const arrow = button.querySelector('.dropdown-arrow');

                    dropdown.classList.remove('max-h-0');
                    dropdown.classList.add('max-h-96');
                    arrow.classList.add('rotated');
                    button.setAttribute('aria-expanded', 'true');
                }
            });
        });
    </script>

</aside>
