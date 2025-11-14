@php

    $type = session('toast.type') ?? (session('success') ? 'success' : (session('error') ? 'error' : 'info'));
    $message = session('toast.message') ?? (session('success') ?? session('error'));

@endphp


@if ($message)
    <div id="toast"
        class="fixed top-5 right-5 z-[9999] flex items-center gap-3 px-5 py-3 rounded-xl shadow-lg border text-white backdrop-blur-lg animate-fadeIn
        @switch($type)
            @case('success') bg-green-500/90 border-green-400 @break
            @case('error') bg-rose-500/90 border-rose-400 @break
            @case('warning') bg-yellow-500/90 border-yellow-400 @break
            @case('info') bg-blue-500/90 border-blue-400 @break
            @default bg-blue-500/90 border-blue-400
        @endswitch">

        {{-- Icon --}}
        @switch($type)
            @case('success')
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                </svg>
            @break

            @case('error')
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            @break

            @case('warning')
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 9v2m0 4h.01M10 3h4l8 16H2L10 3z" />
                </svg>
            @break

            @case('info')
                <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13 16h-1v-4h-1m1-4h.01M12 20h.01" />
                </svg>
            @break
        @endswitch

        {{-- Message --}}
        <span class="text-sm font-medium tracking-wide">
            {{ $message }}
        </span>
    </div>

    {{-- Auto-hide script --}}
    <script>
        setTimeout(() => {
            const toast = document.getElementById('toast');
            toast?.classList.add('opacity-0', 'translate-x-5');
            setTimeout(() => toast?.remove(), 4000);
        }, 3000);
    </script>

    {{-- Inline animation styles --}}
    <style>
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fadeIn {
            animation: fadeIn 0.4s ease-out;
        }

        #toast {
            transition: all 0.5s ease-in-out;
        }
    </style>
@endif
