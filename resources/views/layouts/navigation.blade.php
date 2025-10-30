<nav x-data="{ open: false, searchOpen: false }"
    class="bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg border-b border-gray-200 dark:border-gray-700 sticky top-0 z-50 transition-all duration-200 w-full">
    <!-- Primary Navigation Menu -->
    <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 w-full">
        <div class="flex flex-row items-center justify-end h-16 w-full ">

            {{-- Left Side - Search Bar (Desktop) --}}
            {{-- <div class="hidden md:flex flex-1 max-w-md ">
                <div class="relative w-full">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <span class="material-symbols-rounded text-gray-400 dark:text-gray-500 text-xl">search</span>
                    </div>
                    <input type="text" placeholder="Search..."
                        class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
                </div>
            </div> --}}

            {{-- Right Side - Actions and User Menu --}}
            <div class="flex items-center space-x-3 max-w-full ">

                {{-- Mobile Search Button --}}
                <button @click="searchOpen = !searchOpen"
                    class="md:hidden p-2 rounded-lg text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <span class="material-symbols-rounded text-xl">search</span>
                </button>

                {{-- Notifications --}}
                <div class="hidden sm:block relative">
                    <button
                        class="p-2 rounded-lg text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors relative">
                        <span class="material-symbols-rounded text-xl">notifications</span>
                        <span
                            class="absolute top-1 right-1 w-2 h-2 bg-red-500 rounded-full ring-2 ring-white dark:ring-gray-800"></span>
                    </button>
                </div>

                {{-- Theme Toggle --}}
                <button onclick="toggleTheme()"
                    class="hidden sm:block p-2 rounded-lg text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors">
                    <span class="material-symbols-rounded text-xl" id="theme-icon">light_mode</span>
                </button>

                {{-- Settings Dropdown (Desktop) --}}
                <div class="hidden sm:flex sm:items-center">
                    <x-dropdown align="right" width="64">
                        <x-slot name="trigger">
                            <button
                                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 text-sm leading-4 font-medium rounded-lg text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition-all duration-200 shadow-sm hover:shadow">
                                <div class="flex items-center space-x-2">
                                    <div
                                        class="w-8 h-8 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <div class="text-left hidden lg:block">
                                        <div class="text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ Auth::user()->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}
                                        </div>
                                    </div>
                                </div>
                                <div class="ml-2">
                                    <svg class="fill-current h-4 w-4 text-gray-500 dark:text-gray-400"
                                        xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </button>
                        </x-slot>

                        <x-slot name="content">
                            {{-- User Info Header --}}
                            <div class="px-4 py-3 border-b border-gray-200 dark:border-gray-700">
                                <div class="flex items-center space-x-3">
                                    <div
                                        class="w-10 h-10 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-white font-semibold">
                                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <div class="text-sm font-semibold text-gray-900 dark:text-black">
                                            {{ Auth::user()->name }}</div>
                                        <div class="text-xs text-gray-500 dark:text-gray-600">{{ Auth::user()->email }}
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Menu Items --}}
                            <div class="py-1">
                                <x-dropdown-link :href="route('profile.edit')" class="flex items-center">
                                    <span
                                        class="material-symbols-rounded text-gray-500 dark:text-gray-400 text-lg mr-3">person</span>
                                    {{ __('Profile') }}
                                </x-dropdown-link>

                                <x-dropdown-link href="#" class="flex items-center">
                                    <span
                                        class="material-symbols-rounded text-gray-500 dark:text-gray-400 text-lg mr-3">settings</span>
                                    {{ __('Settings') }}
                                </x-dropdown-link>

                                <x-dropdown-link href="#" class="flex items-center">
                                    <span
                                        class="material-symbols-rounded text-gray-500 dark:text-gray-400 text-lg mr-3">help</span>
                                    {{ __('Help & Support') }}
                                </x-dropdown-link>
                            </div>

                            {{-- Logout --}}
                            <div class="border-t border-gray-200 dark:border-gray-700">
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <x-dropdown-link :href="route('logout')"
                                        onclick="event.preventDefault(); this.closest('form').submit();"
                                        class="flex items-center text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20">
                                        <span class="material-symbols-rounded text-lg mr-3">logout</span>
                                        {{ __('Log Out') }}
                                    </x-dropdown-link>
                                </form>
                            </div>
                        </x-slot>
                    </x-dropdown>
                </div>

                {{-- Mobile Menu Button --}}
                <div class="flex items-center sm:hidden">
                    <button @click="open = !open"
                        class="inline-flex items-center justify-center p-2 rounded-lg text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-gray-700 focus:outline-none transition-colors">
                        <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                            <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                            <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- Mobile Search Bar --}}
    <div x-show="searchOpen" x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 transform -translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-2"
        class="md:hidden border-t border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-3">
        <div class="relative">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <span class="material-symbols-rounded text-gray-400 dark:text-gray-500 text-xl">search</span>
            </div>
            <input type="text" placeholder="Search..."
                class="block w-full pl-10 pr-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-gray-50 dark:bg-gray-700 text-gray-900 dark:text-gray-100 placeholder-gray-500 dark:placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200">
        </div>
    </div>

    {{-- Responsive Navigation Menu (Mobile) --}}
    <div :class="{ 'block': open, 'hidden': !open }"
        class="hidden sm:hidden border-t border-gray-200 dark:border-gray-700">

        {{-- Dashboard Links --}}
        <div class="pt-2 pb-3 space-y-1 px-2">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="flex items-center">
                <span class="material-symbols-rounded text-gray-500 dark:text-gray-400 text-xl mr-3">dashboard</span>
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
        </div>

        {{-- User Settings Section --}}
        <div class="pt-4 pb-1 border-t border-gray-200 dark:border-gray-700">
            <div class="px-4 mb-3">
                <div class="flex items-center space-x-3">
                    <div
                        class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-full flex items-center justify-center text-black font-semibold text-lg">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="font-semibold text-base text-gray-800 dark:text-white">{{ Auth::user()->name }}
                        </div>
                        <div class="text-sm text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</div>
                    </div>
                </div>
            </div>

            <div class="mt-3 space-y-1 px-2">
                <x-responsive-nav-link :href="route('profile.edit')" class="flex items-center">
                    <span class="material-symbols-rounded text-gray-500 dark:text-gray-400 text-xl mr-3">person</span>
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link href="#" class="flex items-center">
                    <span class="material-symbols-rounded text-gray-500 dark:text-gray-400 text-xl mr-3">settings</span>
                    {{ __('Settings') }}
                </x-responsive-nav-link>

                <x-responsive-nav-link href="#" class="flex items-center">
                    <span
                        class="material-symbols-rounded text-gray-500 dark:text-gray-400 text-xl mr-3">notifications</span>
                    {{ __('Notifications') }}
                    <span class="ml-auto w-2 h-2 bg-red-500 rounded-full"></span>
                </x-responsive-nav-link>

                <x-responsive-nav-link href="#" class="flex items-center">
                    <span class="material-symbols-rounded text-gray-500 dark:text-gray-400 text-xl mr-3">help</span>
                    {{ __('Help & Support') }}
                </x-responsive-nav-link>

                {{-- Logout --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();"
                        class="flex items-center text-red-600 dark:text-red-400">
                        <span class="material-symbols-rounded text-xl mr-3">logout</span>
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>

        {{-- Theme Toggle (Mobile) --}}
        <div class="border-t border-gray-200 dark:border-gray-700 px-4 py-3">
            <button onclick="toggleTheme()"
                class="flex items-center w-full text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 px-3 py-2 rounded-lg transition-colors">
                <span class="material-symbols-rounded text-xl mr-3" id="mobile-theme-icon">light_mode</span>
                <span>Toggle Theme</span>
            </button>
        </div>
    </div>

    <style>
        /* Enhanced dropdown styling */
        .dropdown-content {
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        }

        /* Smooth animations */
        [x-cloak] {
            display: none !important;
        }

        /* Custom scrollbar for dropdown */
        .overflow-y-auto::-webkit-scrollbar {
            width: 6px;
        }

        .overflow-y-auto::-webkit-scrollbar-track {
            background: transparent;
        }

        .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 3px;
        }

        .dark .overflow-y-auto::-webkit-scrollbar-thumb {
            background: #475569;
        }
    </style>

    <script>
        // Theme Toggle Function
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.classList.contains('dark') ? 'dark' : 'light';
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';

            // Toggle theme
            if (newTheme === 'dark') {
                html.classList.add('dark');
                updateThemeIcon('dark_mode');
            } else {
                html.classList.remove('dark');
                updateThemeIcon('light_mode');
            }

            // Save preference
            localStorage.setItem('theme', newTheme);
        }

        // Update theme icon
        function updateThemeIcon(icon) {
            const desktopIcon = document.getElementById('theme-icon');
            const mobileIcon = document.getElementById('mobile-theme-icon');
            if (desktopIcon) desktopIcon.textContent = icon;
            if (mobileIcon) mobileIcon.textContent = icon;
        }

        // Initialize theme on page load
        document.addEventListener('DOMContentLoaded', function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            const html = document.documentElement;

            if (savedTheme === 'dark') {
                html.classList.add('dark');
                updateThemeIcon('dark_mode');
            } else {
                html.classList.remove('dark');
                updateThemeIcon('light_mode');
            }
        });

        // Close mobile menu when clicking outside
        document.addEventListener('click', function(event) {
            const nav = document.querySelector('nav');
            const mobileMenu = nav.querySelector('[x-data]');

            if (!nav.contains(event.target)) {
                // Close menus if clicking outside
                Alpine.store('open', false);
            }
        });
    </script>
</nav>
