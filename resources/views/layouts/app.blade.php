<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        /* Custom scrollbar */
        ::-webkit-scrollbar {
            width: 10px;
            height: 10px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }

        ::-webkit-scrollbar-thumb {
            background: #cbd5e1;
            border-radius: 5px;
        }

        ::-webkit-scrollbar-thumb:hover {
            background: #94a3b8;
        }

        .dark ::-webkit-scrollbar-track {
            background: #1e293b;
        }

        .dark ::-webkit-scrollbar-thumb {
            background: #475569;
        }

        .dark ::-webkit-scrollbar-thumb:hover {
            background: #64748b;
        }

        * {
            transition-property: background-color, border-color, color, fill, stroke;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        main {
            animation: slideIn 0.3s ease-out;
        }

        /* Toast Animation */
        @keyframes slideInRight {
            from {
                opacity: 0;
                transform: translateX(20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .animate-slideInRight {
            animation: slideInRight 0.3s ease-out;
        }

        /* Loading Spinner */
        .loading-overlay {
            position: fixed;
            inset: 0;
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.2s;
        }

        .loading-overlay.active {
            opacity: 1;
            pointer-events: all;
        }

        .spinner {
            width: 40px;
            height: 40px;
            border: 4px solid #e5e7eb;
            border-top-color: #6366f1;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .dark .loading-overlay {
            background: rgba(15, 23, 42, 0.9);
        }

        .dark .spinner {
            border-color: #374151;
            border-top-color: #818cf8;
        }

        /* Dark Toast Adjustments */
        .dark #toast-container div {
            color: #f9fafb;
        }

        .dark .bg-yellow-500 {
            color: #111827 !important;
        }
    </style>

    @stack('styles')
</head>

<body class="font-sans antialiased bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-900 dark:to-gray-800">
    @include('components.toast')
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside
            class="hidden md:flex flex-shrink-0 w-72 fixed h-screen z-30 overflow-y-auto bg-white dark:bg-gray-800 shadow-xl border-r border-gray-200 dark:border-gray-700">
            @include('layouts.sideNav')
        </aside>

        <!-- Mobile Sidebar -->
        <div class="md:hidden fixed inset-0 z-40 hidden" id="mobileSidebarOverlay">
            <div class="absolute inset-0 bg-gray-900/50 backdrop-blur-sm" onclick="toggleMobileSidebar()"></div>
            <aside
                class="absolute left-0 top-0 bottom-0 w-72 bg-white dark:bg-gray-800 shadow-2xl transform -translate-x-full transition-transform duration-300"
                id="mobileSidebar">
                @include('layouts.sideNav')
            </aside>
        </div>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col md:ml-72 min-w-0">
            <!-- Navbar -->
            <nav
                class="sticky top-0 z-20 bg-white/80 dark:bg-gray-800/80 backdrop-blur-lg shadow-sm border-b border-gray-200 dark:border-gray-700">
                <div class="flex items-center justify-between px-4 sm:px-6 lg:px-8 h-16">
                    <!-- Mobile Menu Button -->
                    <button onclick="toggleMobileSidebar()"
                        class="md:hidden p-2 rounded-lg text-gray-500 hover:text-gray-700 hover:bg-gray-100 dark:text-gray-400 dark:hover:text-gray-200 dark:hover:bg-gray-700 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>

                    <!-- Logo -->
                    <div class="md:hidden flex items-center">
                        <span class="text-xl font-bold text-gray-800 dark:text-white">
                            {{ config('app.name', 'Laravel') }}
                        </span>
                    </div>

                    <!-- Navigation -->
                    <div class="flex-1 md:flex-none w-full">
                        @include('layouts.navigation')
                    </div>
                </div>
            </nav>

            <!-- Header -->
            @isset($header)
                <header class="bg-white dark:bg-gray-800 shadow-sm border-b border-gray-200 dark:border-gray-700">
                    <div class="max-w-full mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <div class="flex items-center justify-between dark:text-white">
                            {{ $header }}
                        </div>
                    </div>
                </header>
            @endisset

            <!-- Page -->
            <main class="flex-1 overflow-x-hidden">
                <div class="min-h-full p-8">
                    <div class="mb-8">
                        @isset($title)
                            <h2 class="text-3xl font-bold text-gray-900 dark:text-white mb-2">{{ $title }}</h2>
                        @endisset
                        @isset($desc)
                            <p class="text-gray-600 dark:text-gray-400">{{ $desc }}</p>
                        @endisset
                    </div>
                    {{ $slot }}
                </div>

                <!-- Toast Container -->
                <div id="toast-container" class="fixed top-5 right-5 z-[9999] space-y-3"></div>
            </main>

            <!-- Footer -->
            <footer class="bg-white dark:bg-gray-800 border-t border-gray-200 dark:border-gray-700 mt-auto">
                <div class="max-w-full mx-auto px-4 sm:px-6 lg:px-8 py-4">
                    <div
                        class="flex flex-col sm:flex-row items-center justify-between text-sm text-gray-600 dark:text-gray-400">
                        <div class="flex items-center space-x-4 mb-2 sm:mb-0">
                            <span>© {{ date('Y') }} {{ config('app.name', 'Laravel') }}. All rights reserved.</span>
                        </div>
                        <div class="flex items-center space-x-4">
                            <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400">Privacy
                                Policy</a>
                            <span class="text-gray-300 dark:text-gray-600">•</span>
                            <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400">Terms</a>
                            <span class="text-gray-300 dark:text-gray-600">•</span>
                            <a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400">Support</a>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Back to Top -->
    <button onclick="scrollToTop()" id="backToTop"
        class="fixed bottom-8 right-8 bg-indigo-600 hover:bg-indigo-700 text-white p-3 rounded-full shadow-lg hover:shadow-xl transition-all duration-300 opacity-0 pointer-events-none z-50">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
        </svg>
    </button>

    <!-- Utility Scripts -->
    <script>
        function toggleMobileSidebar() {
            const overlay = document.getElementById('mobileSidebarOverlay');
            const sidebar = document.getElementById('mobileSidebar');
            if (overlay.classList.contains('hidden')) {
                overlay.classList.remove('hidden');
                setTimeout(() => sidebar.classList.remove('-translate-x-full'), 10);
            } else {
                sidebar.classList.add('-translate-x-full');
                setTimeout(() => overlay.classList.add('hidden'), 300);
            }
        }

        const backToTopButton = document.getElementById('backToTop');
        window.addEventListener('scroll', () => {
            if (window.pageYOffset > 300) backToTopButton.classList.remove('opacity-0', 'pointer-events-none');
            else backToTopButton.classList.add('opacity-0', 'pointer-events-none');
        });

        function scrollToTop() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        /* Toast Function */
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            if (!container) return;

            const toast = document.createElement('div');
            toast.className = `
                flex items-center p-4 rounded-lg shadow-md animate-slideInRight
                text-white font-medium transition-all duration-300
                ${type === 'success' ? 'bg-green-600' :
                  type === 'error' ? 'bg-red-600' :
                  type === 'warning' ? 'bg-yellow-500 text-gray-900' :
                  'bg-blue-600'}
            `;

            toast.innerHTML = `
                <span class="material-symbols-rounded mr-2">
                    ${type === 'success' ? 'check_circle' :
                      type === 'error' ? 'error' :
                      type === 'warning' ? 'warning' : 'info'}
                </span>
                <span>${message}</span>
            `;

            container.appendChild(toast);
            setTimeout(() => {
                toast.classList.add('opacity-0', 'translate-x-2');
                setTimeout(() => toast.remove(), 500);
            }, 3000);
        }
    </script>

    {{-- <!-- Laravel Flash Toasts -->
    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', () => showToast("{{ session('success') }}", 'success'));
        </script>
    @endif
    @if (session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', () => showToast("{{ session('error') }}", 'error'));
        </script>
    @endif --}}

    @stack('scripts')
</body>

</html>
