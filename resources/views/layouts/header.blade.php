@php
    // --- Breadcrumb Logic (Simulating original PHP with Laravel helpers) ---
    // Get the current path (e.g., /admin/user-profile)
    $path = Request::path();
    $pageName = 'Dashboard';

    if ($path !== '/') {
        // Remove the first segment if it's 'public' or similar, then explode
    $pathSegments = collect(explode('/', $path))->filter()->values();

    if ($pathSegments->isNotEmpty()) {
        $lastSegment = $pathSegments->last();
        // Remove file extension (if any), replace hyphens with spaces, and capitalize
        $pageName = pathinfo($lastSegment, PATHINFO_FILENAME);
        $pageName = str_replace('-', ' ', $pageName);
        $pageName = ucwords($pageName);
    }
}

// --- Placeholder icons for Tailwind environment (using Lucide icons) ---
$settingsIcon =
    '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18A2 2 0 0 1 9 6c-2 0-3.9 1-5 2.5v.5c-.7 1.2-1 2.5-1 3.8a9.1 9.1 0 0 0 2 5.5l1.3 1.3A9.1 9.1 0 0 0 12 22a9.1 9.1 0 0 0 5.7-2l1.3-1.3a9.1 9.1 0 0 0 2-5.5c0-1.3-.3-2.6-1-3.8v-.5C18.9 7 17 6 15 6a2 2 0 0 1-1.8-1.8V4c0-1.1-.9-2-2-2z"/><circle cx="12" cy="12" r="3"/></svg>';
$notificationsIcon =
    '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="w-5 h-5"><path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>';

@endphp

<nav class="sticky top-0 z-10 w-full flex items-center bg-white/80 backdrop-blur-sm px-6 mx-auto rounded-xl shadow-lg transition-all duration-300"
    id="navbarBlur" data-scroll="true">
    <div class="container-fluid flex justify-between items-center w-full py-2">

        {{-- Breadcrumb Navigation --}}
        <nav aria-label="breadcrumb" class="hidden md:block">
            <ol class="flex space-x-2 text-sm mb-0 pt-1">
                <li class="text-sm">
                    <a class="text-gray-600 hover:text-gray-800 transition opacity-70"
                        href="{{ url('/dashboard') }}">Pages</a>
                </li>
                <li class="text-sm font-semibold text-gray-900 capitalize" aria-current="page">
                    / {{ $pageName }}
                </li>
            </ol>
        </nav>

        {{-- Right-Side Menu (User, Toggler, Settings, Notifications) --}}
        <ul class="flex items-center space-x-4 ml-auto">

            {{-- User Authentication Status --}}
            <li class="flex items-center justify-center">
                @auth
                    {{-- Assuming 'Welcome, [User Name]' is a link or just text --}}
                    <span
                        class="inline-block px-4 py-2 text-sm font-medium text-blue-600 bg-blue-50 border border-blue-200 rounded-lg shadow-sm">
                        Welcome, {{ Auth::user()->name }}
                    </span>
                @else
                    <p class="text-sm text-gray-600">
                        You are not logged in. Please
                        <a href="{{ route('login') ?? '/login' }}" class="text-blue-600 hover:underline font-medium">log
                            in</a>.
                    </p>
                @endauth
            </li>

            {{-- Sign Out Button --}}
            @auth
                <li class="flex items-center">
                    <form method="POST" action="{{ route('logout') ?? '/logout' }}">
                        @csrf
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-red-600 rounded-lg hover:bg-red-700 transition shadow-md">
                            Sign Out
                        </button>
                    </form>
                </li>
            @endauth

            {{-- Mobile Sidenav Toggler (Hidden on XL screens) --}}
            <li class="xl:hidden pl-3 flex items-center">
                {{-- NOTE: This requires JavaScript to toggle the sidebar, which is not included here. --}}
                <a href="javascript:;" class="text-gray-600 hover:text-gray-900 p-0 transition-colors"
                    id="iconNavbarSidenav">
                    <div class="flex flex-col space-y-1 w-5 h-5">
                        <span class="block h-0.5 bg-gray-600 rounded-full w-full"></span>
                        <span class="block h-0.5 bg-gray-600 rounded-full w-full"></span>
                        <span class="block h-0.5 bg-gray-600 rounded-full w-full"></span>
                    </div>
                </a>
            </li>

            {{-- Settings Icon (Fixed Plugin Button) --}}
            <li class="px-3 flex items-center">
                <a href="javascript:;" class="text-gray-600 hover:text-gray-900 p-0 transition-colors">
                    {!! $settingsIcon !!} {{-- Icon: Gear/Settings --}}
                </a>
            </li>

            {{-- Notifications Dropdown --}}
            <li class="relative pe-3 flex items-center group">
                {{-- Dropdown Toggle Button --}}
                <a href="javascript:;" class="text-gray-600 hover:text-gray-900 p-0 transition-colors"
                    id="dropdownMenuButton">
                    {!! $notificationsIcon !!} {{-- Icon: Bell/Notifications --}}
                </a>

                {{-- Dropdown Menu (Placeholder structure maintained) --}}
                <ul
                    class="absolute right-0 top-full mt-2 w-72 origin-top-right rounded-xl shadow-2xl bg-white ring-1 ring-black ring-opacity-5 divide-y divide-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 transform scale-95 group-hover:scale-100">

                    {{-- Notification Item 1 --}}
                    <li class="p-2">
                        <a class="block hover:bg-gray-50 rounded-lg transition-colors p-2" href="javascript:;">
                            <div class="flex py-1 space-x-3 items-center">
                                <div class="flex-shrink-0">
                                    <img src="{{ asset('assets/asset/images/team-2.jpg') }}"
                                        onerror="this.src='https://placehold.co/32x32/1d4ed8/ffffff?text=U'"
                                        class="h-8 w-8 rounded-full object-cover shadow-sm">
                                </div>
                                <div class="flex flex-col justify-center">
                                    <h6 class="text-sm font-normal mb-1 leading-tight">
                                        <span class="font-semibold">New message</span> from Laur
                                    </h6>
                                    <p class="text-xs text-gray-500 mb-0">
                                        <i class="fas fa-clock mr-1"></i> {{-- Placeholder icon for clock --}}
                                        13 minutes ago
                                    </p>
                                </div>
                            </div>
                        </a>
                    </li>

                    {{-- Notification Item 2 (Spotify Logo) --}}
                    <li class="p-2">
                        <a class="block hover:bg-gray-50 rounded-lg transition-colors p-2" href="javascript:;">
                            <div class="flex py-1 space-x-3 items-center">
                                <div class="flex-shrink-0">
                                    <span
                                        class="h-8 w-8 rounded-full bg-black flex items-center justify-center text-white text-xs font-bold shadow-sm">SP</span>
                                    {{-- Placeholder for SVG logo --}}
                                </div>
                                <div class="flex flex-col justify-center">
                                    <h6 class="text-sm font-normal mb-1 leading-tight">
                                        <span class="font-semibold">New album</span> by Travis Scott
                                    </h6>
                                    <p class="text-xs text-gray-500 mb-0">
                                        <i class="fas fa-clock mr-1"></i>
                                        1 day
                                    </p>
                                </div>
                            </div>
                        </a>
                    </li>

                    {{-- Notification Item 3 (Credit Card SVG) --}}
                    <li class="p-2">
                        <a class="block hover:bg-gray-50 rounded-lg transition-colors p-2" href="javascript:;">
                            <div class="flex py-1 space-x-3 items-center">
                                <div class="flex-shrink-0">
                                    <div
                                        class="h-8 w-8 rounded-full bg-gray-500 flex items-center justify-center shadow-sm">
                                        {{-- Inline SVG for Credit Card --}}
                                        <svg width="16" height="16" viewBox="0 0 43 36" fill="currentColor"
                                            xmlns="http://www.w3.org/2000/svg" class="text-white">
                                            <g fill="currentColor" fill-rule="nonzero">
                                                <path opacity="0.59"
                                                    d="M43 10.7482083L43 3.58333333C43 1.60354167 41.3964583 0 39.4166667 0L3.58333333 0C1.60354167 0 0 1.60354167 0 3.58333333L0 10.7482083L43 10.7482083Z">
                                                </path>
                                                <path
                                                    d="M0 16.125L0 32.25C0 34.2297917 1.60354167 35.8333333 3.58333333 35.8333333L39.4166667 35.8333333C41.3964583 35.8333333 43 34.2297917 43 32.25L43 16.125L0 16.125Z M19.7083333 26.875L7.16666667 26.875L7.16666667 23.2916667L19.7083333 23.2916667L19.7083333 26.875Z M35.8333333 26.875L28.6666667 26.875L28.6666667 23.2916667L35.8333333 23.2916667L35.8333333 26.875Z">
                                                </path>
                                            </g>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex flex-col justify-center">
                                    <h6 class="text-sm font-normal mb-1 leading-tight">
                                        Payment successfully completed
                                    </h6>
                                    <p class="text-xs text-gray-500 mb-0">
                                        <i class="fas fa-clock mr-1"></i>
                                        2 days
                                    </p>
                                </div>
                            </div>
                        </a>
                    </li>
                </ul>
            </li>

        </ul>
    </div>


</nav>
